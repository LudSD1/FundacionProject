<?php

// OPCIÓN 1: USAR TRAIT EN LUGAR DE BaseModel (Para User específicamente)

// app/Traits/HasEncryptedRoutes.php
namespace App\Traits;

use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Encryption\DecryptException;

trait HasEncryptedRoutes
{
    /**
     * Manejo mágico de URLs encriptadas
     */
    public function __get($key)
    {
        // URLs automáticas
        if (str_ends_with($key, '_url')) {
            return $this->generateEncryptedRoute($key);
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
     * Para model binding automático
     */
    public function getRouteKey()
    {
        return encrypt($this->getKey());
    }

    /**
     * Resolver model binding con IDs encriptados
     */
    public function resolveRouteBinding($value, $field = null)
    {
        try {
            $decryptedId = decrypt($value);
            return $this->where($this->getRouteKeyName(), $decryptedId)->first();
        } catch (DecryptException $e) {
            return $this->where($this->getRouteKeyName(), $value)->first();
        }
    }
}
