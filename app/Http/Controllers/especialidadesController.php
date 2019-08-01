<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use \App\Especialidad;
use \App\LogError;

class especialidadesController extends Controller
{
    public function getAllSpecialties(){
		try{
			$specialities = Especialidad::orderBy('Descripcion', 'ASC')->paginate(7);
			
			if(count($specialities) > 0){
				return response()->json([
					'type' => 'response',
					'specialities' => $specialities
				]);	
			}else{
				return response()->json([
					'type' => 'error',
					'message' => 'No se han encontrado especialidades'
				]);	
			}
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'especialidadesController';
			$errorLog->metodo = 'getAllSpecialities';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			return response()->json([
					'type' => 'error',
					'message' => 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde'
				]);	
		}
	}
	
	public function specialityEdit(){
		try{
			$specialityId = Input::get('id');
			$speciality = Especialidad::where('IdTipoDoctor', $specialityId)->get();
			
			if(count($speciality) == 0){
				session()->flash('warning', 'No se ha encontrado la especialidad a editar');
				return redirect('./abm-specialty');
			}
			
			$speciality = $speciality->first();
			
			return view('specialityEdit')->with('speciality', $speciality);
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'especialidadesController';
			$errorLog->metodo = 'specialityEdit';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			session()->flash('error', 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde');
			return redirect('./abm-specialty');
		}
	}
	
	public function updateSpeciality(Request $request){
		try{
			if($request->Descripcion == '' || $request->Descripcion == null){
				session()->flash('warning', 'Por favor complete todos los campos');
				return redirect('./speciality-edit?id='.$request->IdTipoDoctor);
			}
			$speciality = Especialidad::where('IdTipoDoctor', $request->IdTipoDoctor)->get();
			
			if(count($speciality) == 0){
				session()->flash('warning', 'No se ha encontrado la Especialidad a actualizar');
				return redirect('./speciality-edit?id='.$request->IdTipoDoctor);
			}
			
			$speciality = $speciality->first();
			
			$speciality->Descripcion = $request->Descripcion;
			$speciality->save();
			
			session()->flash('success', 'La Especialidad se ha actualizado correctamente');
			return redirect('./speciality-edit?id='.$request->IdTipoDoctor);
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'especialidadesController';
			$errorLog->metodo = 'updateSpeciality';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			session()->flash('error', 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde');
			return redirect('./speciality-edit?id='.$request->IdTipoDoctor);
		}
	}
	
	public function createSpeciality(Request $request){
		try{
			if($request->Descripcion == '' || $request->Descripcion == null){
				session()->flash('warning', 'Por favor complete todos los campos');
				return redirect('./create-speciality');
			}
			
			$speciality = new Especialidad();
			
			$speciality->Descripcion = $request->Descripcion;
			$speciality->save();			
			
			session()->flash('success', 'La Especialidad se ha creado exitosamente');
			return redirect('./abm-specialty');
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'especialidadesController';
			$errorLog->metodo = 'createSpeciality';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			session()->flash('error', 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde');
			return redirect('./abm-specialty');
		}
	}
	
	public function deleteSpeciality(Request $request){
		try{
			$SpecialityName = Especialidad::where('IdTipoDoctor', $request->specialityId)->get()->first()->Descripcion;
			Especialidad::where('IdTipoDoctor', $request->specialityId)->delete();
			
			return response()->json([
				'type' => 'response',
				'message' => 'La Especialidad '.$SpecialityName.' ha sido eliminada exitosamente'
			]);
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'especialidadesController';
			$errorLog->metodo = 'deleteSpeciality';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			session()->flash('error', 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde');
			return redirect('./abm-specialty');
		}
	}
}
