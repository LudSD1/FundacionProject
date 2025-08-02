<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\SoftDeletes;

class Notas_Boletin extends BaseModel
{
    use HasFactory, SoftDeletes;
    protected $softDelete = true;
    protected $table = "notas_boletin";


    public function boletin(): BelongsTo
    {
        return $this->belongsTo(Boletin::class, 'id', 'boletin_id');
    }

}
