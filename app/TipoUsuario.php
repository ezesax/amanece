<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoUsuario extends Model
{
	public $timestamps = false;
	protected $table = 'tipousuario';
	protected $primaryKey = 'IdTipoUsuario';
	
    protected $fillable = [
        'IdTipoUsuario',
		'Descripcion'
    ];
}
