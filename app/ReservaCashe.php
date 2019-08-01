<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReservaCashe extends Model
{
    public $timestamps = false;
	protected $table = 'reservacanceladacashe';
	protected $primaryKey = 'id';
	
    protected $fillable = [
        'id',
		'IdUsuario',
		'IdReserva'
    ];
}
