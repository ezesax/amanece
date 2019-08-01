<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BloqueHorario extends Model
{
    public $timestamps = false;
    protected $table = 'bloquehorario';
	protected $primaryKey = 'IdBloqueHorario';
	
    protected $fillable = [
        'Orden',
		'Descripcion'
    ];
}
