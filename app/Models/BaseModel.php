<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Encryption\DecryptException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BaseModel extends Model
{
    /**
     * Manejo mágico de URLs encriptadas
     */
    public function __get($key)
    {
        // URLs automáticas - EXCLUIR youtube_url y otras URLs específicas
        if (str_ends_with($key, '_url')) {
            // Lista de URLs que NO deben ser manejadas automáticamente
            $excludedUrls = ['youtube_url', 'image_url', 'avatar_url', 'profile_url'];

            if (!in_array($key, $excludedUrls)) {
                return $this->generateEncryptedRoute($key);
            }
        }

        // ID encriptado directo
        if ($key === 'encrypted_id') {
            return encrypt($this->id);
        }

        return parent::__get($key);
    }

    /**
     * Genera rutas automáticamente con IDs encriptados
     */
    private function generateEncryptedRoute($urlKey)
    {
        $action = str_replace('_url', '', $urlKey);
        $tableName = $this->getTable();
        $routeName = $tableName . '.' . $action;

        // Verificar si la ruta existe
        if (Route::has($routeName)) {
            return route($routeName, encrypt($this->id));
        }

        // Rutas personalizadas
        if (isset($this->encryptedRoutes[$urlKey])) {
            $customRoute = $this->encryptedRoutes[$urlKey];
            if (Route::has($customRoute)) {
                return route($customRoute, encrypt($this->id));
            }
        }

        return '#';
    }

    /**
     * Para model binding automático - SIEMPRE devolver encriptado
     */
    public function getRouteKey()
    {
        return encrypt($this->getKey());
    }

    /**
     * Resolver model binding con IDs encriptados y BLOQUEAR IDs numerales
     */
    public function resolveRouteBinding($value, $field = null)
    {
        // BLOQUEAR: Si el valor parece un ID numeral
        if ($this->isNumericId($value)) {
            abort(404, 'Acceso no permitido');
        }

        try {
            // Solo permitir acceso con IDs encriptados
            $decryptedId = decrypt($value);

            // Verificación adicional: el ID desencriptado debe ser numérico
            if (!is_numeric($decryptedId)) {
                abort(404, 'ID inválido');
            }

            return $this->where($this->getRouteKeyName(), $decryptedId)->first()
                ?? abort(404);
        } catch (DecryptException $e) {
            // Si no se puede desencriptar, mostrar 404
            abort(404, 'ID inválido');
        }
    }

    /**
     * Verificar si el valor es un ID numérico (sin encriptar)
     */
    protected function isNumericId($value)
    {
        // Convertir a string para verificar
        $value = (string) $value;
 
        // Si es completamente numérico Y no contiene caracteres especiales
        // típicos de encriptación (base64), es un ID sin encriptar
        if (ctype_digit($value)) {
            return true;
        }

        // Si es numérico como string (permite negativos, pero seguimos bloqueando)
        if (is_numeric($value) && !str_contains($value, '.')) {
            return true;
        }

        return false;
    }

    /**
     * Método para verificar si un string está encriptado
     */
    protected function isEncrypted($value)
    {
        try {
            decrypt($value);
            return true;
        } catch (DecryptException $e) {
            return false;
        }
    }
}
