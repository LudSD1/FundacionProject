<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Recursos extends BaseModel
{
    use HasFactory, SoftDeletes;
    protected $softDelete = true;
    public function cursos() :BelongsTo

    {

        return $this->belongsTo(Cursos::class, 'cursos_id');

    }


}
