<?php

namespace App\Console\Commands;

use App\Models\Cursos;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpirarCertificados extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

     protected $signature = 'certificados:expirar';
     protected $description = 'Expira los certificados de los cursos que ya pasaron su fecha límite';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $cursos = Cursos::where('estado', 'Certificado Disponible')
        ->whereDate('fecha_fin', '<', Carbon::now()->toDateString())
        ->update(['estado' => 'Expirado']);

        $this->info("Se han expirado {$cursos} cursos.");
    }
}
