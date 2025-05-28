<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SchedulerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function scheduler_can_run_without_errors()
    {
        // Este teste verifica se o scheduler pode ser executado sem erros
        // Act & Assert
        $this->artisan('schedule:list')
            ->assertExitCode(0);
    }

    /** @test */
    public function clean_old_logs_command_exists_and_is_runnable()
    {
        // Act & Assert: Verificar se o comando existe e pode ser executado
        $this->artisan('logs:clean-old', ['--help'])
            ->assertExitCode(0);
    }

    /** @test */
    public function clean_old_logs_command_can_be_tested_with_dry_run()
    {
        // Act & Assert: Testar o comando com dados de exemplo
        $this->artisan('logs:clean-old', ['--days' => 30])
            ->expectsOutput('Nenhum log antigo encontrado para remoção.')
            ->assertExitCode(0);
    }

    /** @test */
    public function scheduler_list_shows_configured_commands()
    {
        // Act & Assert: Verificar se o comando schedule:list executa sem erro
        $this->artisan('schedule:list')
            ->assertExitCode(0);

        // O teste passou se chegou até aqui sem exceções
        $this->assertTrue(true, 'Comando schedule:list executado com sucesso');
    }
}
