<?php
namespace Tests\Unit\Commands;

use Carbon\Carbon;
use App\Models\Log;
use Tests\TestCase;
use Illuminate\Support\Facades\Log as LaravelLog;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CleanOldLogsCommandTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock do canal scheduler para evitar erros nos testes
        LaravelLog::shouldReceive('channel')
            ->with('scheduler')
            ->andReturnSelf();

        LaravelLog::shouldReceive('info')
            ->andReturn(true);

        LaravelLog::shouldReceive('error')
            ->andReturn(true);
    }

    /** @test */
    public function it_removes_old_logs_older_than_30_days_by_default()
    {
        // Arrange: Criar logs antigos e recentes
        $oldDate    = Carbon::now()->subDays(35);
        $recentDate = Carbon::now()->subDays(15);

        // Logs antigos (devem ser removidos)
        Log::factory()->create(['created_at' => $oldDate, 'message' => 'Log antigo 1']);
        Log::factory()->create(['created_at' => $oldDate, 'message' => 'Log antigo 2']);

        // Logs recentes (devem permanecer)
        Log::factory()->create(['created_at' => $recentDate, 'message' => 'Log recente']);

        $this->assertEquals(3, Log::count());

        // Act: Executar o comando
        $this->artisan('logs:clean-old')
            ->expectsOutput('Iniciando limpeza de logs antigos...')
            ->expectsQuestion('Deseja remover 2 registos de log?', 'yes')
            ->expectsOutput('✅ Limpeza concluída com sucesso!')
            ->expectsOutput('📊 Registos removidos: 2')
            ->assertExitCode(0);

        // Assert: Verificar que apenas logs antigos foram removidos
        $this->assertEquals(1, Log::count());
        $this->assertEquals('Log recente', Log::first()->message);
    }

    /** @test */
    public function it_accepts_custom_days_parameter()
    {
        // Arrange: Criar logs com diferentes idades
        $veryOldDate = Carbon::now()->subDays(50);
        $oldDate     = Carbon::now()->subDays(20);
        $recentDate  = Carbon::now()->subDays(5);

        Log::factory()->create(['created_at' => $veryOldDate, 'message' => 'Log muito antigo']);
        Log::factory()->create(['created_at' => $oldDate, 'message' => 'Log antigo']);
        Log::factory()->create(['created_at' => $recentDate, 'message' => 'Log recente']);

        // Act: Executar comando com parâmetro personalizado (15 dias)
        $this->artisan('logs:clean-old', ['--days' => 15])
            ->expectsQuestion('Deseja remover 2 registos de log?', 'yes')
            ->assertExitCode(0);

        // Assert: Apenas o log recente deve permanecer
        $this->assertEquals(1, Log::count());
        $this->assertEquals('Log recente', Log::first()->message);
    }

    /** @test */
    public function it_shows_message_when_no_old_logs_exist()
    {
        // Arrange: Criar apenas logs recentes
        Log::factory()->create(['created_at' => Carbon::now()->subDays(5)]);

        // Act & Assert
        $this->artisan('logs:clean-old')
            ->expectsOutput('Nenhum log antigo encontrado para remoção.')
            ->assertExitCode(0);

        $this->assertEquals(1, Log::count());
    }

    /** @test */
    public function it_handles_user_interaction_properly()
    {
        // Arrange
        Log::factory()->create(['created_at' => Carbon::now()->subDays(35)]);

        // Act & Assert: Teste simples de interação
        $this->artisan('logs:clean-old')
            ->expectsQuestion('Deseja remover 1 registos de log?', 'yes')
            ->assertExitCode(0);

        // Verificar que o log foi removido
        $this->assertEquals(0, Log::count());
    }

    /** @test */
    public function it_logs_successful_execution()
    {
        // Arrange
        Log::factory()->create(['created_at' => Carbon::now()->subDays(35)]);

        // Act
        $this->artisan('logs:clean-old')
            ->expectsQuestion('Deseja remover 1 registos de log?', 'yes');

        // Assert: Verificar se o log foi registrado
        LaravelLog::shouldHaveReceived('channel')
            ->with('scheduler')
            ->atLeast()
            ->once();
    }

    /** @test */
    public function it_returns_success_exit_code_on_completion()
    {
        // Arrange
        Log::factory()->create(['created_at' => Carbon::now()->subDays(35)]);

        // Act & Assert
        $this->artisan('logs:clean-old')
            ->expectsQuestion('Deseja remover 1 registos de log?', 'yes')
            ->assertExitCode(0);
    }
}
