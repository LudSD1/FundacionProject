<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TutorRepresentanteLegal extends BaseModel
{
    use HasFactory, SoftDeletes;
    protected $softDelete = true;


    public function estudianteMenor(){

        return $this->belongsTo(User::class, 'estudiante_id');

    }
}
