<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database {--path=} {--compress}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear backup de la base de datos MySQL';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST');
        $port = env('DB_PORT', '3306');

        if (!$database) {
            $this->error('No se encontró configuración de base de datos');
            return 1;
        }

        $date = Carbon::now()->format('Y-m-d_H-i-s');
        $backupPath = $this->option('path') ?: storage_path('backups');

        // Crear directorio si no existe
        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
            $this->info("Directorio de backups creado: {$backupPath}");
        }

        $filename = "backup_{$database}_{$date}.sql";
        $fullPath = $backupPath . DIRECTORY_SEPARATOR . $filename;

        // Construir comando mysqldump
        $command = "mysqldump";
        $command .= " --user={$username}";
        $command .= " --host={$host}";
        $command .= " --port={$port}";

        if ($password) {
            $command .= " --password={$password}";
        }

        $command .= " --single-transaction";
        $command .= " --routines";
        $command .= " --triggers";
        $command .= " {$database}";
        $command .= " > \"{$fullPath}\"";

        $this->info("Iniciando backup de la base de datos: {$database}");
        $this->info("Archivo: {$filename}");

        // Ejecutar comando
        exec($command, $output, $return);

        if ($return === 0 && file_exists($fullPath)) {
            $fileSize = filesize($fullPath);
            $fileSizeMB = round($fileSize / 1024 / 1024, 2);

            // Comprimir si se solicita
            if ($this->option('compress')) {
                $this->info("Comprimiendo backup...");
                $compressedPath = $fullPath . '.gz';

                if (function_exists('gzencode')) {
                    $data = file_get_contents($fullPath);
                    $compressed = gzencode($data, 9);
                    file_put_contents($compressedPath, $compressed);
                    unlink($fullPath); // Eliminar archivo sin comprimir

                    $fullPath = $compressedPath;
                    $filename .= '.gz';
                    $fileSize = filesize($fullPath);
                    $fileSizeMB = round($fileSize / 1024 / 1024, 2);

                    $this->info("Backup comprimido exitosamente");
                } else {
                    $this->warn("Función gzencode no disponible, backup sin comprimir");
                }
            }

            $this->info("✅ Backup creado exitosamente!");
            $this->info("📁 Archivo: {$filename}");
            $this->info("📊 Tamaño: {$fileSizeMB} MB");
            $this->info("📍 Ubicación: {$fullPath}");

            // Log del backup
            Log::channel('admin')->info('Database backup created', [
                'filename' => $filename,
                'path' => $fullPath,
                'size' => $fileSize,
                'size_mb' => $fileSizeMB,
                'database' => $database,
                'compressed' => $this->option('compress'),
                'created_by' => 'system_command',
                'timestamp' => now()
            ]);

            return 0;
        } else {
            $this->error('❌ Error creando el backup');
            $this->error('Comando ejecutado: ' . $command);

            if (!empty($output)) {
                $this->error('Salida del comando:');
                foreach ($output as $line) {
                    $this->error($line);
                }
            }

            Log::channel('admin')->error('Database backup failed', [
                'database' => $database,
                'command' => $command,
                'return_code' => $return,
                'output' => $output,
                'timestamp' => now()
            ]);

            return 1;
        }
    }
}
