<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class PaymentMethod extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'description',
        'account_number',
        'account_holder',
        'qr_image',
        'is_active',
        'sort_order',
        'additional_info'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'additional_info' => 'array',
        'sort_order' => 'integer'
    ];

    protected $dates = [
        'deleted_at'
    ];

    /**
     * Scope para obtener solo métodos activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para ordenar por sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
    }

    /**
     * Obtener la URL completa de la imagen QR
     */
    public function getQrImageUrlAttribute()
    {
        if ($this->qr_image) {
            return Storage::url($this->qr_image);
        }
        return null;
    }

    /**
     * Obtener el nombre del tipo de método de pago
     */
    public function getTypeNameAttribute()
    {
        $types = [
            'bank' => 'Banco',
            'mobile_payment' => 'Pago Móvil',
            'digital_wallet' => 'Billetera Digital',
            'cryptocurrency' => 'Criptomoneda',
            'other' => 'Otro'
        ];

        return $types[$this->type] ?? 'Desconocido';
    }

    /**
     * Obtener el estado como texto
     */
    public function getStatusTextAttribute()
    {
        return $this->is_active ? 'Activo' : 'Inactivo';
    }

    /**
     * Eliminar la imagen QR del storage cuando se elimina el registro
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($paymentMethod) {
            if ($paymentMethod->qr_image && Storage::exists($paymentMethod->qr_image)) {
                Storage::delete($paymentMethod->qr_image);
            }
        });
    }
}
