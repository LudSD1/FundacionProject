<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Encryption\DecryptException;

class TransparentIdEncryption
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Desencriptar IDs de entrada
        $this->decryptRouteParameters($request);

        // 2. Procesar request normalmente
        $response = $next($request);

        // 3. Encriptar IDs en respuestas JSON
        $this->encryptResponseIds($response);

        return $response;
    }

    private function decryptRouteParameters(Request $request)
    {
        if (!$request->route()) return;

        $routeParameters = $request->route()->parameters();

        foreach ($routeParameters as $key => $value) {
            if ($this->looksLikeEncryptedId($value)) {
                try {
                    $decrypted = decrypt($value);
                    if (is_numeric($decrypted)) {
                        $request->route()->setParameter($key, $decrypted);
                    }
                } catch (DecryptException $e) {
                    continue;
                }
            }
        }
    }

    private function encryptResponseIds($response)
    {
        if ($response instanceof JsonResponse) {
            $data = $response->getData(true);
            $encryptedData = $this->encryptIdsInData($data);
            $response->setData($encryptedData);
        }
    }

    private function encryptIdsInData($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if ($this->shouldEncryptKey($key) && is_numeric($value)) {
                    $data[$key] = encrypt($value);
                } elseif (is_array($value) || is_object($value)) {
                    $data[$key] = $this->encryptIdsInData($value);
                }
            }
        }
        return $data;
    }

    private function looksLikeEncryptedId($value): bool
    {
        return is_string($value) && strlen($value) > 20 && !is_numeric($value);
    }

    private function shouldEncryptKey($key): bool
    {
        return $key === 'id' || str_ends_with($key, '_id');
    }
}
