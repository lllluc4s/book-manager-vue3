<?php

namespace Tests\Unit\Models;

use App\Models\Log;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class LogModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_log_entry()
    {
        // Arrange & Act
        $log = Log::create([
            'level' => 'info',
            'message' => 'Test log message',
            'context' => ['key' => 'value'],
            'channel' => 'testing'
        ]);

        // Assert
        $this->assertDatabaseHas('logs', [
            'level' => 'info',
            'message' => 'Test log message',
            'channel' => 'testing'
        ]);

        $this->assertEquals(['key' => 'value'], $log->context);
    }

    /** @test */
    public function it_casts_context_to_array()
    {
        // Arrange & Act
        $log = Log::create([
            'level' => 'error',
            'message' => 'Error message',
            'context' => ['error' => 'Something went wrong', 'code' => 500],
            'channel' => 'app'
        ]);

        // Assert
        $this->assertIsArray($log->context);
        $this->assertEquals('Something went wrong', $log->context['error']);
        $this->assertEquals(500, $log->context['code']);
    }

    /** @test */
    public function scope_old_logs_returns_logs_older_than_specified_days()
    {
        // Arrange
        $oldDate = Carbon::now()->subDays(35);
        $recentDate = Carbon::now()->subDays(15);

        $oldLog = Log::factory()->create(['created_at' => $oldDate]);
        $recentLog = Log::factory()->create(['created_at' => $recentDate]);

        // Act
        $oldLogs = Log::oldLogs(30)->get();

        // Assert
        $this->assertCount(1, $oldLogs);
        $this->assertEquals($oldLog->id, $oldLogs->first()->id);
    }

    /** @test */
    public function scope_by_level_filters_logs_by_level()
    {
        // Arrange
        Log::factory()->create(['level' => 'info']);
        Log::factory()->create(['level' => 'error']);
        Log::factory()->create(['level' => 'warning']);

        // Act
        $errorLogs = Log::byLevel('error')->get();
        $infoLogs = Log::byLevel('info')->get();

        // Assert
        $this->assertCount(1, $errorLogs);
        $this->assertCount(1, $infoLogs);
        $this->assertEquals('error', $errorLogs->first()->level);
        $this->assertEquals('info', $infoLogs->first()->level);
    }

    /** @test */
    public function scope_by_channel_filters_logs_by_channel()
    {
        // Arrange
        Log::factory()->create(['channel' => 'app']);
        Log::factory()->create(['channel' => 'scheduler']);
        Log::factory()->create(['channel' => 'auth']);

        // Act
        $schedulerLogs = Log::byChannel('scheduler')->get();
        $appLogs = Log::byChannel('app')->get();

        // Assert
        $this->assertCount(1, $schedulerLogs);
        $this->assertCount(1, $appLogs);
        $this->assertEquals('scheduler', $schedulerLogs->first()->channel);
        $this->assertEquals('app', $appLogs->first()->channel);
    }

    /** @test */
    public function it_can_combine_scopes()
    {
        // Arrange
        $oldDate = Carbon::now()->subDays(35);
        $recentDate = Carbon::now()->subDays(15);

        Log::factory()->create([
            'level' => 'error',
            'channel' => 'app',
            'created_at' => $oldDate
        ]);

        Log::factory()->create([
            'level' => 'error',
            'channel' => 'scheduler',
            'created_at' => $oldDate
        ]);

        Log::factory()->create([
            'level' => 'info',
            'channel' => 'app',
            'created_at' => $recentDate
        ]);

        // Act
        $oldErrorAppLogs = Log::oldLogs(30)
            ->byLevel('error')
            ->byChannel('app')
            ->get();

        // Assert
        $this->assertCount(1, $oldErrorAppLogs);
        $this->assertEquals('error', $oldErrorAppLogs->first()->level);
        $this->assertEquals('app', $oldErrorAppLogs->first()->channel);
    }

    /** @test */
    public function it_has_proper_fillable_attributes()
    {
        // Arrange
        $log = new Log();

        // Act & Assert
        $expectedFillable = ['level', 'message', 'context', 'channel'];
        $this->assertEquals($expectedFillable, $log->getFillable());
    }

    /** @test */
    public function timestamps_are_cast_to_carbon_instances()
    {
        // Arrange & Act
        $log = Log::factory()->create();

        // Assert
        $this->assertInstanceOf(Carbon::class, $log->created_at);
        $this->assertInstanceOf(Carbon::class, $log->updated_at);
    }
}
