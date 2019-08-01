<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use \App\Configuracion;

class configuracionController extends Controller
{
    public function abmHoraBloque(){
		try{
			$config = Configuracion::all();
			
			if(count($config) == 0){
				session()->flash('warning', 'No se han encontrado configuraciones');
				return redirect('./main');
			}
			
			$config = $config->first();
			
			return view('abmHoraBloque')->with('config', $config);;
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'configuracionController';
			$errorLog->metodo = 'getAllConfiguration';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			session()->flash('error', 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde');
			return redirect('./main');
			
		}
	}
	
	public function updateBlockHour(Request $request){
		try{
			$config = Configuracion::all()->first();
			$config->ValorHoraBloque = $request->ValorHoraBloque;
			
			$config->save();
			
			session()->flash('success', 'El valor se ha actualizado correctamente');
			return redirect('./abm-hora-bloque');
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'configuracionController';
			$errorLog->metodo = 'updateBlockHour';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			session()->flash('error', 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde');
			return redirect('./abm-hora-bloque');
			
		}
	}
}
