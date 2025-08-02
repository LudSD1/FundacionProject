<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtributosDocentes extends BaseModel
{
    use HasFactory;

    protected $fillable = ['id','formacion', 'Especializacion','ExperienciaL', 'docente_id'];


    public function docente(){

            return $this->belongsTo(User::class, 'docente_id');

    }




}
