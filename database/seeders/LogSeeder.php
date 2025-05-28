<?php
namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Log;
use Illuminate\Database\Seeder;

class LogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Criando logs de exemplo...');

        // Logs antigos (mais de 30 dias) - para testar a limpeza
        for ($i = 0; $i < 10; $i++) {
            Log::create([
                'level'   => 'info',
                'message' => "Log antigo de teste #{$i}",
                'context' => [
                    'user_id' => rand(1, 3),
                    'action'  => 'test_action',
                    'data'    => ['key' => 'value'],
                ],
                'channel'    => 'testing',
                'created_at' => Carbon::now()->subDays(rand(31, 60)),
                'updated_at' => Carbon::now()->subDays(rand(31, 60)),
            ]);
        }

        // Logs recentes (últimos 30 dias) - não devem ser removidos
        for ($i = 0; $i < 5; $i++) {
            Log::create([
                'level'   => ['info', 'warning', 'error'][rand(0, 2)],
                'message' => "Log recente de teste #{$i}",
                'context' => [
                    'user_id' => rand(1, 3),
                    'action'  => 'recent_action',
                    'data'    => ['recent' => true],
                ],
                'channel'    => 'application',
                'created_at' => Carbon::now()->subDays(rand(1, 29)),
                'updated_at' => Carbon::now()->subDays(rand(1, 29)),
            ]);
        }

        $this->command->info('Logs de exemplo criados com sucesso!');
        $this->command->info('- 10 logs antigos (>30 dias)');
        $this->command->info('- 5 logs recentes (<30 dias)');
    }
}
