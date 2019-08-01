<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use \App\LogError;
use \App\Box;
use \App\TipoBox;

class boxController extends Controller
{
    public function getAllBoxes(){
		try{
			$boxes = Box::paginate(7);
			
			if(count($boxes) == 0){
				return response()->json([
					'type' => 'error',
					'message' => 'No se ha encontrado ningún box'
				]);
			}
			
			foreach($boxes as $box){
				$box->TipoBox = TipoBox::where('IdTipoBox', $box->IdTipoBox)->get()->first()->Descripcion;
				if($box->horaBloque == 0)
					$box->AdmiteBloque = 'No';
				if($box->horaBloque == 1)
					$box->AdmiteBloque = 'Si';
			}
			
			return response()->json([
				'type' => 'response',
				'boxes' => $boxes
			]);
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'boxController';
			$errorLog->metodo = 'getAllBoxes';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			return response()->json([
				'type' => 'error',
				'message' => 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde'
			]);	
		}
	}
	
	public function getAllBoxesWithNoPaginate(){
		try{
			$boxes = Box::all();
			
			if(count($boxes) == 0){
				return response()->json([
					'type' => 'error',
					'message' => 'No se ha encontrado ningún box'
				]);
			}
			
			foreach($boxes as $box){
				$box->TipoBox = TipoBox::where('IdTipoBox', $box->IdTipoBox)->get()->first()->Descripcion;
				if($box->horaBloque == 0)
					$box->AdmiteBloque = 'No';
				if($box->horaBloque == 1)
					$box->AdmiteBloque = 'Si';
			}
			
			return response()->json([
				'type' => 'response',
				'boxes' => $boxes
			]);
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'boxController';
			$errorLog->metodo = 'getAllBoxesWithNoPaginate';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			return response()->json([
				'type' => 'error',
				'message' => 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde'
			]);	
		}
	}
	
	public function boxEdit(){
		try{
			$boxId = Input::get('id');
			
			$box = Box::where('IdBox', $boxId)->get();
			
			if(count($box) == 0){
				session()->flash('warning', 'No se ha encontrado ningún box');
				return redirect('./abm-box');
			}
			
			$box = $box->first();
			$boxTypes = TipoBox::all();
			
			return view('boxEdit')->with(['box' => $box, 'boxTypes' => $boxTypes]);
			
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'boxController';
			$errorLog->metodo = 'getAllBoxes';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			session()->flash('error', 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde');
			return redirect('./abm-box');
		}
	}
	
	public function updateBox(Request $request){
		try{
			if($request->Descripcion == '' || $request->Descripcion == null
				|| $request->IdTipoBox == '' || $request->IdTipoBox == null
				|| $request->ValorHora == '' || $request->ValorHora == null){
					session()->flash('warning', 'Por favor, complete todos los campos');
					return redirect('./box-edit?id='.$request->IdBox);
			
			}
			
			$box = Box::where('IdBox', $request->IdBox)->get();
			
			if(count($box) == 0){
				session()->flash('error', 'El box que intenta actualizar no existe');
				return redirect('./box-edit?id='.$request->IdBox);
			}
			
			$box = $box->first();
			
			$box->Descripcion = $request->Descripcion;
			$box->IdTipoBox = intval($request->IdTipoBox);
			$box->ValorHora = $request->ValorHora;
			
			if($request->horaBloque == true){
				$box->horaBloque = 1;
			}else{
				$box->horaBloque = 0;
			}
			
			$box->save();
			
			session()->flash('success', 'El box se ha actualizado exitosamente');
			return redirect('./box-edit?id='.$request->IdBox);
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'boxController';
			$errorLog->metodo = 'updateBox';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			session()->flash('error', 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde');
			return redirect('./box-edit?id='.$request->IdBox);
		}
	}
	
	public function newBox(){
		try{
			$boxTypes = TipoBox::all();
			
			return view('newBox')->with('boxTypes', $boxTypes);
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'boxController';
			$errorLog->metodo = 'newBox';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			session()->flash('error', 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde');
			return redirect('./box-edit?id='.$request->IdBox);
		}
	}
	
	public function createBox(Request $request){
		try{
			if($request->Descripcion == '' || $request->Descripcion == null
				|| $request->IdTipoBox == '' || $request->IdTipoBox == null
				|| $request->ValorHora == '' || $request->ValorHora == null){
					session()->flash('warning', 'Por favor, complete todos los campos');
					return redirect('create-box');
			
			}
			
			$box = new Box();
			
			$box->Descripcion = $request->Descripcion;
			$box->IdTipoBox = $request->IdTipoBox;
			$box->ValorHora = $request->ValorHora;
			
			if($request->horaBloque == true){
				$box->horaBloque = 1;
			}else{
				$box->horaBloque = 0;
			}
			
			$box->save();
			
			session()->flash('success', 'El box se ha creado correctamente');
			return redirect('./abm-box');
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'boxController';
			$errorLog->metodo = 'createBox';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			session()->flash('error', 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde');
			return redirect('./abm-box');
		}
	}
	
	public function deleteBox(Request $request){
		try{
			Box::where('IdBox', $request->idBox)->delete();
			
			return response()->json([
				'type' => 'response',
				'message' => 'El box se ha eliminado correctamente'
			]);	
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'boxController';
			$errorLog->metodo = 'deleteBox';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			return response()->json([
				'type' => 'error',
				'message' => 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde'
			]);	
		}
	}
}
