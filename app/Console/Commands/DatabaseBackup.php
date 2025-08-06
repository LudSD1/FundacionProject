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
        // Buscar mysqldump en el sistema
        $mysqldumpPath = $this->findMysqldump();
        if (!$mysqldumpPath) {
            $this->error('❌ mysqldump no está disponible en el sistema');
            $this->error('Por favor instala MySQL client o asegúrate de que mysqldump esté en el PATH');
            return 1;
        }

        $this->info("🔍 Usando mysqldump desde: {$mysqldumpPath}");

        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST', '127.0.0.1');
        $port = env('DB_PORT', '3306');

        if (!$database) {
            $this->error('❌ No se encontró configuración de base de datos');
            $this->error('Verifica que DB_DATABASE esté configurado en el archivo .env');
            return 1;
        }

        if (!$username) {
            $this->error('❌ No se encontró usuario de base de datos');
            $this->error('Verifica que DB_USERNAME esté configurado en el archivo .env');
            return 1;
        }

        $this->info("🔧 Configuración de backup:");
        $this->info("   Host: {$host}:{$port}");
        $this->info("   Base de datos: {$database}");
        $this->info("   Usuario: {$username}");

        $date = Carbon::now()->format('Y-m-d_H-i-s');
        $backupPath = $this->option('path') ?: storage_path('backups');

        // Crear directorio si no existe
        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
            $this->info("Directorio de backups creado: {$backupPath}");
        }

        $filename = "backup_{$database}_{$date}.sql";
        $fullPath = $backupPath . DIRECTORY_SEPARATOR . $filename;

        // Construir comando mysqldump de forma segura
        $command = [
            escapeshellarg($mysqldumpPath),
            '--user=' . escapeshellarg($username),
            '--host=' . escapeshellarg($host),
            '--port=' . escapeshellarg($port),
            '--single-transaction',
            '--routines',
            '--triggers',
            '--result-file=' . escapeshellarg($fullPath)
        ];

        if ($password) {
            $command[] = '--password=' . escapeshellarg($password);
        }

        $command[] = escapeshellarg($database);

        // Convertir array a string para ejecución
        $commandString = implode(' ', $command);

        $this->info("Iniciando backup de la base de datos: {$database}");
        $this->info("Archivo: {$filename}");

        // Ejecutar comando y capturar tanto stdout como stderr
        $descriptorspec = [
            0 => ["pipe", "r"],  // stdin
            1 => ["pipe", "w"],  // stdout
            2 => ["pipe", "w"]   // stderr
        ];

        $process = proc_open($commandString, $descriptorspec, $pipes);
        $output = [];
        $errorOutput = [];

        if (is_resource($process)) {
            // Cerrar stdin
            fclose($pipes[0]);

            // Leer stdout
            $stdout = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            // Leer stderr
            $stderr = stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            // Obtener código de retorno
            $return = proc_close($process);

            if (!empty($stdout)) {
                $output = explode("\n", trim($stdout));
            }
            if (!empty($stderr)) {
                $errorOutput = explode("\n", trim($stderr));
            }
        } else {
            $return = 1;
            $errorOutput = ['No se pudo ejecutar el comando mysqldump'];
        }

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
            $this->error('Comando ejecutado: ' . $commandString);

            if (!empty($errorOutput)) {
                $this->error('Errores del comando:');
                foreach ($errorOutput as $line) {
                    if (!empty(trim($line))) {
                        $this->error($line);
                    }
                }
            }

            if (!empty($output)) {
                $this->error('Salida del comando:');
                foreach ($output as $line) {
                    if (!empty(trim($line))) {
                        $this->error($line);
                    }
                }
            }

            // Verificar si el archivo existe pero está vacío
            if (file_exists($fullPath)) {
                $fileSize = filesize($fullPath);
                $this->error("Archivo creado pero con tamaño: {$fileSize} bytes");
                if ($fileSize == 0) {
                    $this->error('El archivo de backup está vacío. Posibles causas:');
                    $this->error('- Credenciales de base de datos incorrectas');
                    $this->error('- mysqldump no está instalado o no está en el PATH');
                    $this->error('- Permisos insuficientes para acceder a la base de datos');
                    $this->error('- La base de datos no existe o está vacía');
                }
            }

            Log::channel('admin')->error('Database backup failed', [
                'database' => $database,
                'command' => $commandString,
                'return_code' => $return,
                'output' => $output,
                'error_output' => $errorOutput,
                'file_exists' => file_exists($fullPath),
                'file_size' => file_exists($fullPath) ? filesize($fullPath) : 0,
                'timestamp' => now()
            ]);

            return 1;
        }
    }

    /**
     * Buscar mysqldump en ubicaciones comunes del sistema
     */
    private function findMysqldump(): ?string
    {
        // Primero intentar desde PATH
        $command = 'mysqldump --version';
        $descriptorspec = [
            0 => ["pipe", "r"],
            1 => ["pipe", "w"],
            2 => ["pipe", "w"]
        ];

        $process = proc_open($command, $descriptorspec, $pipes);
        if (is_resource($process)) {
            fclose($pipes[0]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            $return = proc_close($process);
            if ($return === 0) {
                return 'mysqldump';
            }
        }

        // Buscar en ubicaciones comunes de Windows
        $commonPaths = [
            'C:\Program Files\MySQL\MySQL Server 8.0\bin\mysqldump.exe',
            'C:\Program Files\MySQL\MySQL Server 5.7\bin\mysqldump.exe',
            'C:\Program Files\MySQL\MySQL Workbench 8.0 CE\mysqldump.exe',
            'C:\Program Files (x86)\MySQL\MySQL Server 8.0\bin\mysqldump.exe',
            'C:\Program Files (x86)\MySQL\MySQL Server 5.7\bin\mysqldump.exe',
            'C:\xampp\mysql\bin\mysqldump.exe',
            'C:\wamp64\bin\mysql\mysql8.0.21\bin\mysqldump.exe',
            'C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysqldump.exe'
        ];

        foreach ($commonPaths as $path) {
            if (file_exists($path)) {
                // Verificar que funcione
                $testCommand = escapeshellarg($path) . ' --version';
                $process = proc_open($testCommand, $descriptorspec, $pipes);
                if (is_resource($process)) {
                    fclose($pipes[0]);
                    fclose($pipes[1]);
                    fclose($pipes[2]);
                    $return = proc_close($process);
                    if ($return === 0) {
                        return $path;
                    }
                }
            }
        }

        return null;
    }
}
