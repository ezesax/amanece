<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    public $timestamps = false;
	protected $table = 'reserva';
	protected $primaryKey = 'IdReserva';
	
    protected $fillable = [
        'IdReserva',
		'IdBloqueHorario',
		'IdUsuario',
		'Fecha',
		'IdBox',
		'EsPeriodica',
		'InicioPeriodo',
		'TerminoPeriodo',
		'Cancelada',
		'FechaCancelacion',
		'EsInicioDeSerie',
		'FechaReservaEfectiva',
		'EsBloque',
		'CostoReserva',
		'PosicionBloque'
    ];
}
