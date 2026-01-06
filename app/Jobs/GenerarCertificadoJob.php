<?php

namespace App\Jobs;

use App\Models\Certificado;
use App\Models\CertificateTemplate;
use App\Models\Cursos;
use App\Models\Inscritos;
use App\Notifications\CertificadoGeneradoNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GenerarCertificadoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Número de intentos antes de marcar como fallido
     */
    public $tries = 3;

    /**
     * Timeout en segundos (5 minutos)
     */
    public $timeout = 300;

    /**
     * Tiempo de espera entre reintentos (en segundos)
     */
    public $backoff = [60, 120];

    protected $inscritoId;
    protected $cursoId;
    protected $codigoCertificado;
    protected $enviarNotificacion;

    /**
     * Create a new job instance.
     */
    public function __construct(
        int $inscritoId,
        int $cursoId,
        string $codigoCertificado,
        bool $enviarNotificacion = true
    ) {
        $this->inscritoId = $inscritoId;
        $this->cursoId = $cursoId;
        $this->codigoCertificado = $codigoCertificado;
        $this->enviarNotificacion = $enviarNotificacion;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            Log::info("Iniciando generación de certificado: {$this->codigoCertificado}");

            // Cargar datos necesarios
            $inscrito = Inscritos::with('estudiantes')->findOrFail($this->inscritoId);
            $curso = Cursos::findOrFail($this->cursoId);
            $plantilla = CertificateTemplate::where('curso_id', $this->cursoId)->first();

            if (!$plantilla) {
                throw new \Exception("No se encontró plantilla para el curso ID: {$this->cursoId}");
            }

            // Generar y guardar código QR
            $qrPath = $this->generarQR($inscrito, $curso);

            // Generar PDF del certificado
            $this->generarPDF($inscrito, $curso, $plantilla, $qrPath);

            // Enviar notificación por email
            if ($this->enviarNotificacion && $inscrito->estudiantes && $inscrito->estudiantes->email) {
                $inscrito->estudiantes->notify(
                    new CertificadoGeneradoNotification($inscrito, $this->codigoCertificado)
                );
                Log::info("Notificación enviada a: {$inscrito->estudiantes->email}");
            }

            Log::info("Certificado generado exitosamente: {$this->codigoCertificado}");
        } catch (\Exception $e) {
            Log::error("Error generando certificado {$this->codigoCertificado}: " . $e->getMessage());
            Log::error($e->getTraceAsString());

            // Re-lanzar la excepción para que Laravel maneje los reintentos
            throw $e;
        }
    }

    /**
     * Generar código QR y guardarlo
     */
    protected function generarQR(Inscritos $inscrito, Cursos $curso): string
    {
        $qrCodeSvg = QrCode::format('svg')
            ->size(200)
            ->generate(route('verificar.certificado', ['codigo' => $this->codigoCertificado]));

        $qrPath = "certificados/{$curso->id}/qrcode_{$inscrito->id}.svg";
        Storage::disk('public')->put($qrPath, $qrCodeSvg);

        Log::info("QR generado y guardado en: {$qrPath}");

        return $qrPath;
    }

    /**
     * Generar PDF del certificado
     */
    protected function generarPDF(Inscritos $inscrito, Cursos $curso, CertificateTemplate $plantilla, string $qrPath): void
    {
        // Obtener ruta absoluta del QR
        $qrFilePath = storage_path('app/public/' . $qrPath);

        // Configurar límites de memoria y tiempo
        ini_set('memory_limit', '256M');
        set_time_limit(300);

        // Preparar datos para la vista
        $data = [
            'curso' => $curso->nombreCurso,
            'inscrito' => $inscrito,
            'codigo_certificado' => $this->codigoCertificado,
            'fecha_emision' => now()->format('d/m/Y'),
            'fecha_finalizacion' => $curso->fecha_fin ? Carbon::parse($curso->fecha_fin)->format('d/m/Y') : now()->format('d/m/Y'),
            'qr_file_path' => $qrFilePath,
            'tipo' => ucfirst($curso->tipo ?? 'Curso'),
            'plantillaf' => $plantilla->template_front_path,
            'plantillab' => $plantilla->template_back_path,
            'primary_color' => $plantilla->primary_color ?? '#000000',
            'font_family' => $plantilla->font_family ?? 'Arial',
            'font_size' => $plantilla->font_size ?? 12,
        ];

        // Generar PDF
        $pdf = Pdf::loadView('certificados.plantilla', $data);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('dpi', 250);

        // Guardar PDF
        $pdfPath = "certificados/{$curso->id}/certificado_{$inscrito->id}.pdf";
        Storage::disk('public')->put($pdfPath, $pdf->output());

        // Actualizar registro del certificado con la ruta del PDF
        Certificado::where('codigo_certificado', $this->codigoCertificado)
            ->update(['archivo_pdf' => $pdfPath]);

        Log::info("PDF generado y guardado en: {$pdfPath}");
    }

    /**
     * Manejar el fallo del job después de todos los reintentos
     */
    public function failed(\Throwable $exception)
    {
        Log::error("Job de certificado falló definitivamente: {$this->codigoCertificado}");
        Log::error("Error: " . $exception->getMessage());

        // Aquí podrías notificar a un administrador o marcar el certificado como fallido
        // Por ejemplo: enviar email a admin, actualizar estado en BD, etc.
    }
}
