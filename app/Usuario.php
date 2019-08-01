<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
	public $timestamps = false;
	protected $table = 'usuario';
	protected $primaryKey = 'idUsuario';
	
    protected $fillable = [
        'idUsuario',
		'NombreUsuario',
		'idTipoUsuario',
		'idTipoDoctor',
		'clave',
		'Nombres',
		'Apellidos',
		'RUT',
		'Telefono',
		'Email',
		'Direccion',
		'Activo',
		'Ingreso',
		'UltimaReserva'
    ];
}
