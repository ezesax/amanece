<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    public $timestamps = false;
    protected $table = 'configuracion';
	protected $primaryKey = 'Id';
	
    protected $fillable = [
        'AnioInicio',
		'AnioTermino',
		'ValorHoraBloque'
    ];
}
