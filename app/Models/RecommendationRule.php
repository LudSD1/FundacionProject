<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecommendationRule extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'weight',
        'is_active',
        'config',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'config'    => 'array',
        'weight'    => 'integer',
    ];

    /**
     * Solo reglas activas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Obtener un valor de configuración con fallback
     */
    public function getConfigValue(string $key, $default = null)
    {
        return data_get($this->config, $key, $default);
    }
}
