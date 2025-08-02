<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InscritoProgreso extends BaseModel
{
    use HasFactory;

    protected $table = 'inscrito_progreso';

    protected $fillable = ['inscrito_id', 'tema_id', 'subtemas_id','desbloqueado'];

    public function inscrito()
    {
        return $this->belongsTo(Inscritos::class);
    }

    public function tema()
    {
        return $this->belongsTo(Tema::class);
    }

    public function subtema()
    {
        return $this->belongsTo(Subtema::class);
    }

}
