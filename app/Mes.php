<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mes extends Model
{
    public $timestamps = false;
    protected $table = 'mes';
	protected $primaryKey = 'idMes';
	
    protected $fillable = [
        'Descripcion',
		'descripcionBreve'
    ];
}
