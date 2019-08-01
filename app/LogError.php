<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogError extends Model
{
	public $timestamps = false;
    protected $table = 'logerror';
	
    protected $fillable = [
        'id',
		'idUsuario',
		'controller',
		'metodo',
		'mensaje',
		'fecha'
    ];
}
