<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factura extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'aporte_id',
        'numero_factura',
        'cuf',
        'cufd',
        'codigo_control',
        'nit_emisor',
        'razon_social_emisor',
        'direccion_emisor',
        'fecha_emision',
        'nit_cliente',
        'razon_social_cliente',
        'complemento_ci',
        'monto_total',
        'descuento',
        'monto_final',
        'leyenda',
        'estado',
        'xml_representacion' // Optional
    ];

    protected $casts = [
        'fecha_emision' => 'datetime',
        'monto_total' => 'decimal:2',
        'descuento' => 'decimal:2',
        'monto_final' => 'decimal:2',
    ];

    public function aporte(): BelongsTo
    {
        return $this->belongsTo(Aportes::class, 'aporte_id');
    }
}
