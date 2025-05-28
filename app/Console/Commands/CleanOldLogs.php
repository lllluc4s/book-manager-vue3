<?php
namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Log;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log as LaravelLog;

class CleanOldLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:clean-old {--days=30 : Number of days to consider logs as old}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove logs antigos da base de dados (padrão: mais de 30 dias)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days       = (int) $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);

        $this->info('Iniciando limpeza de logs antigos...');
        $this->info("Data limite: {$cutoffDate->format('d/m/Y H:i:s')}");

        try {
            // Count how many records will be removed
            $totalToRemove = Log::oldLogs($days)->count();

            if (0 === $totalToRemove) {
                $this->info('Nenhum log antigo encontrado para remoção.');
                LaravelLog::channel('scheduler')->info('Limpeza de logs executada: Nenhum log para remover', [
                    'days'            => $days,
                    'cutoff_date'     => $cutoffDate->toDateTimeString(),
                    'records_removed' => 0,
                ]);

                return self::SUCCESS;
            }

            // Confirm removal if executed manually
            if ($this->input->isInteractive()) {
                if (!$this->confirm("Deseja remover {$totalToRemove} registos de log?")) {
                    $this->info('Operação cancelada.');

                    return self::SUCCESS;
                }
            }

            // Remove old logs
            $recordsRemoved = Log::oldLogs($days)->delete();

            $this->info('✅ Limpeza concluída com sucesso!');
            $this->info("📊 Registos removidos: {$recordsRemoved}");

            // Log the execution
            LaravelLog::channel('scheduler')->info('Limpeza de logs executada com sucesso', [
                'days'            => $days,
                'cutoff_date'     => $cutoffDate->toDateTimeString(),
                'records_removed' => $recordsRemoved,
                'memory_used'     => memory_get_peak_usage(true),
                'execution_time'  => defined('LARAVEL_START') ? microtime(true) - LARAVEL_START : 0,
            ]);

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error("❌ Erro durante a limpeza de logs: {$e->getMessage()}");

            // Log the error
            LaravelLog::channel('scheduler')->error('Erro na limpeza de logs', [
                'days'        => $days,
                'cutoff_date' => $cutoffDate->toDateTimeString(),
                'error'       => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);

            return self::FAILURE;
        }
    }
}
