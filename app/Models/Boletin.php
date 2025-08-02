<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Boletin extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = "boletin";
    protected $softDelete = true;
    protected $fillable = [ 'nota_final', 'comentario_boletin', 'inscripcion_id',  'updated_at'];


    public function incripcion(): BelongsTo
    {
        return $this->belongsTo(Inscritos::class, 'id' ,'inscripcion_id');

    }

    public function notasBoletin(): HasMany
    {
        return $this->hasMany(Notas_Boletin::class, 'boletin_id');
    }

}
