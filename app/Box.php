<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    public $timestamps = false;
    protected $table = 'box';
	protected $primaryKey = 'IdBox';
	
    protected $fillable = [
        'IdBox',
		'Descripcion',
		'IdTipoBox',
		'ValorHora',
		'horaBloque',
    ];
}
