<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
	public $timestamps = false;
    protected $table = 'tipodoctor';
	protected $primaryKey = 'IdTipoDoctor';
	
    protected $fillable = [
        'IdTipoDoctor',
		'Descripcion'
    ];
}
