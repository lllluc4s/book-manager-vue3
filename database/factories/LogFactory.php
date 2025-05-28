<?php

namespace Database\Factories;

use App\Models\Log;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class LogFactory extends Factory
{
    protected $model = Log::class;

    public function definition(): array
    {
        return [
            'level' => $this->faker->randomElement(['debug', 'info', 'notice', 'warning', 'error', 'critical', 'alert', 'emergency']),
            'message' => $this->faker->sentence(),
            'context' => [
                'user_id' => $this->faker->numberBetween(1, 100),
                'ip_address' => $this->faker->ipv4(),
                'user_agent' => $this->faker->userAgent(),
                'extra_data' => $this->faker->words(3, true)
            ],
            'channel' => $this->faker->randomElement(['app', 'scheduler', 'auth', 'database', 'mail']),
            'created_at' => $this->faker->dateTimeBetween('-60 days', 'now'),
            'updated_at' => function (array $attributes) {
                return $attributes['created_at'];
            },
        ];
    }

    /**
     * Log antigo (mais de 30 dias)
     */
    public function old(int $days = 35): static
    {
        return $this->state(function (array $attributes) use ($days) {
            $oldDate = Carbon::now()->subDays($days);
            return [
                'created_at' => $oldDate,
                'updated_at' => $oldDate,
            ];
        });
    }

    /**
     * Log recente (menos de 30 dias)
     */
    public function recent(int $days = 15): static
    {
        return $this->state(function (array $attributes) use ($days) {
            $recentDate = Carbon::now()->subDays($days);
            return [
                'created_at' => $recentDate,
                'updated_at' => $recentDate,
            ];
        });
    }

    /**
     * Log de erro
     */
    public function error(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'level' => 'error',
                'message' => 'An error occurred: ' . $this->faker->sentence(),
                'context' => [
                    'error_code' => $this->faker->numberBetween(400, 599),
                    'exception' => $this->faker->word() . 'Exception',
                    'file' => $this->faker->filePath(),
                    'line' => $this->faker->numberBetween(1, 1000),
                ],
            ];
        });
    }

    /**
     * Log do scheduler
     */
    public function scheduler(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'level' => 'info',
                'channel' => 'scheduler',
                'message' => 'Scheduled task executed: ' . $this->faker->words(2, true),
                'context' => [
                    'command' => $this->faker->word(),
                    'execution_time' => $this->faker->randomFloat(4, 0.1, 10.0),
                    'memory_used' => $this->faker->numberBetween(1000000, 50000000),
                ],
            ];
        });
    }
}
