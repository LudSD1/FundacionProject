<?php

namespace App\Jobs;

use App\Models\Certificado;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class GenerarCertificadoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $certificado;

    public function __construct(Certificado $certificado)
    {
        $this->certificado = $certificado;
    }

    public function handle()
    {
        // Generar QR
        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
            ->size(200)
            ->generate(route('verificar.certificado', ['codigo' => $this->certificado->codigo_certificado]));

        $qrBase64 = 'data:image/png;base64,' . base64_encode($qrCode);

        // Generar PDF
        $pdf = Pdf::loadView('certificados.plantilla', [
            'curso' => $this->certificado->curso->nombreCurso,
            'inscrito' => $this->certificado->inscrito,
            'codigo_certificado' => $this->certificado->codigo_certificado,
            'fecha_emision' => $this->certificado->created_at->format('d/m/Y'),
            'qr_base64' => $qrBase64,
        ]);

        // Guardar PDF en el almacenamiento
        $pdfPath = "certificados/{$this->certificado->codigo_certificado}.pdf";
        Storage::disk('public')->put($pdfPath, $pdf->output());

        // Actualizar el certificado con la ruta del archivo
        $this->certificado->update(['archivo_pdf' => $pdfPath]);
    }
}
