<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoBox extends Model
{
    public $timestamps = false;
    protected $table = 'tipobox';
	protected $primaryKey = 'IdTipoBox';
	
    protected $fillable = [
        'IdTipoBox',
		'Descripcion'
    ];
}
