<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrTokenService
{
    public function generarToken($cursoId)
    {
        // Buscar un token válido
        $token = DB::table('qr_tokens')
            ->where('curso_id', $cursoId)
            ->where('expiracion', '>=', now())
            ->whereColumn('usos_actuales', '<', 'limite_uso')
            ->first();

        if (!$token) {
            // Crear un nuevo token si no existe o los anteriores han expirado
            $tokenId = DB::table('qr_tokens')->insertGetId([
                'curso_id' => $cursoId,
                'token' => Str::random(32),
                'limite_uso' => 300,
                'expiracion' => Carbon::now()->addHours(24),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $token = DB::table('qr_tokens')->find($tokenId);
        }

        return $token;
    }

    public function generarQrCode($url)
    {
        $qrCodeSvg = QrCode::format('svg')->size(300)->generate($url);
        return 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);
    }
}
