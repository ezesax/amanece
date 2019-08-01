<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use\App\Usuario;
use \App\TipoUsuario;
use \App\Especialidad;
use \App\LogError;

class usuarioController extends Controller
{
    public function login(Request $request){
		try{
			$user = Usuario::where('NombreUsuario', strtoupper($request->username))->where('clave', md5(strtoupper($request->username).':'.$request->password))->get();
			if(count($user) > 0){
				$user = $user->first();
				
				session()->put('userName', $user->NombreUsuario);
				session()->put('userFirstName', $user->Nombres);
				session()->put('userLastName', $user->Apellidos);
				session()->put('idUsuario', $user->idUsuario);
				session()->put('userType', $user->IdTipoUsuario);
				session()->put('port', 1);
				
				return redirect('/main');
			}else{
				session()->flash('error', 'Los datos ingresados son incorrectos');
				return redirect('/');
			}
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'usuarioController';
			$errorLog->metodo = 'login';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			session()->flash('error', 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde');
			return redirect('/');
		}
	}
	
	public function logOut(){
		try{
			session()->flush();
			return redirect('/');
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'usuarioController';
			$errorLog->metodo = 'logOut';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			session()->flash('error', 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde');
			return redirect('/');
		}
	}
	
	public function getAllUsers(){
		try{
			$users = Usuario::orderBy('Apellidos', 'ASC')->paginate(7);
			
			if(count($users) > 0){
				return response()->json([
					'type' => 'response',
					'users' => $users
				]);	
			}else{
				return response()->json([
					'type' => 'error',
					'message' => 'No hay ningún usuario registrado'
				]);	
			}
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'usuarioController';
			$errorLog->metodo = 'getAllUsers';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			return response()->json([
					'type' => 'error',
					'message' => 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde'
				]);	
		}
	}
	
	public function getUserById(Request $request){
		try{
			$user = Usuario::where('idUsuario', $request->userId)->get();
			
			if(count($user) > 0){
				return response()->json([
					'type' => 'response',
					'user' => $user->first()
				]);			
			}else{
				return response()->json([
					'type' => 'error',
					'message' => 'El usuario no ha sido encontrado'
				]);	
			}
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'usuarioController';
			$errorLog->metodo = 'getUserById';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			return response()->json([
					'type' => 'error',
					'message' => 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde'
			]);	
		}
	}
	
	public function getUserByFilter(Request $request){
		try{
			$users = Usuario::where('NombreUsuario', 'like', '%'.strtoupper($request->filter).'%')
					->orWhere('Nombres', 'like', '%'.$request->filter.'%')
					->orWhere('Apellidos', 'like', '%'.$request->filter.'%')
					->orWhere('RUT', 'like', '%'.$request->filter.'%')
					->orWhere('Telefono', 'like', '%'.$request->filter.'%')
					->orWhere('Email', 'like', '%'.$request->filter.'%')
					->orWhere('Direccion', 'like', '%'.$request->filter.'%')
					->paginate(7);
					
			if(count($users) > 0){
				return response()->json([
					'type' => 'response',
					'users' => $users
				]);
			}else{
				return response()->json([
					'type' => 'warning',
					'message' => 'Ningun usuario coincide con esta búsqueda'
				]);
			}
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'usuarioController';
			$errorLog->metodo = 'getUserByFilter';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			return response()->json([
					'type' => 'error',
					'message' => 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde'
				]);	
		}
	}
	
	public function newUser(){
		try{
			$userTypes = TipoUsuario::all();
			
			return view('newUser')->with('userTypes', $userTypes);
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'usuarioController';
			$errorLog->metodo = 'newUser';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
				
			session()->flash('error', 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde');
			return redirect('./abm-user');
		}
	}
	
	public function create(Request $request){
		try{
			if($request->NombreUsuario 		== ""		|| $request->NombreUsuario == null
				|| $request->Email 			== "" 		|| $request->Email == null
				|| $request->clave 			== "" 		|| $request->clave == null
				|| $request->IdTipoUsuario 	== "" 		|| $request->IdTipoUsuario == null){
					session()->flash('warning', 'Por favor complete todos los campos');
					return redirect('./create-user');
				}
				
			$user = Usuario::where('NombreUsuario', $request->NombreUsuario)->get();
			if(count($user) > 0){
				session()->flash('error', 'El usuario ya existe');
				return redirect('./create-user');
			}
			
			if(!$this->validPassword($request->clave)){
				session()->flash('error', 'La clave no cumple con los parametros esperados');
				return redirect('./create-user');
			}
			
			$user = new Usuario();
			
			$user->NombreUsuario = strtoupper($request->NombreUsuario);
			$user->Email = $request->Email;
			$user->IdTipoDoctor = 1;
			$user->IdTipoUsuario = $request->IdTipoUsuario;
			$user->clave = md5(strtoupper($request->NombreUsuario).':'.$request->clave);
			$user->Activo = 1;
			$user->Ingreso = date('Y-m-d H:i:s');
			$user->UltimaReserva = null;
			
			$user->Nombres = "";
			$user->Apellidos = "";
			$user->RUT = "";
			$user->Telefono = "";
			$user->Direccion = "";
			
			$user->save();
			
			session()->flash('success', 'El usuario se ha creado correctamente');
			return redirect('./abm-user');
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'usuarioController';
			$errorLog->metodo = 'create';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			session()->flash('success', 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde');
			return redirect('./create-user');
		}
	}
	
	public function userEdit(){
		try{
			$userId = Input::get('id');
			$user = Usuario::where('idUsuario', $userId)->get()->first();
			
			$specialities = Especialidad::all();
			$userTypes = TipoUsuario::all();
			
			return view('userEdit')->with(['user' => $user, 'specialities' => $specialities, 'userTypes' => $userTypes]);
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'usuarioController';
			$errorLog->metodo = 'userEdit';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			session()->flash('success', 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde');
			return redirect('./user-edit?id='.$userId);
		}
	}
	
	public function update(Request $request){
		try{
			if($request->Nombres == "" 			|| $request->Nombres == null
				|| $request->Apellidos == "" 	|| $request->Apellidos == null
				|| $request->RUT == "" 			|| $request->RUT == null
				|| $request->Telefono == "" 	|| $request->Telefono == null
				|| $request->Email == "" 		|| $request->Email == null
				|| $request->Direccion == "" 	|| $request->Direccion == null){
					session()->flash('warning', 'Por faovr, complete todos los campos');
					return redirect('user-edit?id='.$request->idUsuario);
			}
			if($request->clave != "" && $request->clave != null){
				if(!$this->validPassword($request->clave)){
					session()->flash('error', 'La clave no cumple con los parametros esperados');
					return redirect('./user-edit?id='.$request->idUsuario);
				}
			}
			
			$user = Usuario::where('idUsuario', $request->idUsuario)->get()->first();
			
			$user->Nombres = $request->Nombres;
			$user->Apellidos = $request->Apellidos;
			$user->RUT = $request->RUT;
			$user->Telefono = $request->Telefono;
			$user->Email = $request->Email;
			$user->Direccion = $request->Direccion;
			$user->idTipoDoctor = $request->IdTipoDoctor;
			$user->idTipoUsuario = $request->IdTipoUsuario;
			
			if($request->Activo == true){
				$user->Activo = 1;
			}else{
				$user->Activo = 0;
			}
			
			if(session()->get('userType') == 1){
				$user->NombreUsuario = strtoupper($request->NombreUsuario);
			}
			
			if($request->clave != "" && $request->clave != null){
				$user->clave = md5(strtoupper($user->NombreUsuario).':'.$request->clave);
			}
			
			$user->save();
			
			session()->flash('success', 'El usuario se ha actualizado correctamente');
			return redirect('user-edit?id='.$request->idUsuario);
			
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'usuarioController';
			$errorLog->metodo = 'update';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			session()->flash('success', 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde');
			return redirect('./user-edit?id='.$request->idUsuario);
		}
	}
	
	public function deleteUser(Request $request){
		try{
			$userName = Usuario::where('idUsuario', $request->idUsuario)->get()->first()->NombreUsuario;
			Usuario::where('idUsuario', $request->idUsuario)->delete();
			
			return response()->json([
				'type' => 'response',
				'message' => 'El usuario '.$userName.' ha sido eliminado exitosamente'
			]);
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'usuarioController';
			$errorLog->metodo = 'deleteUser';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			return response()->json([
					'type' => 'error',
					'message' => 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde'
				]);	
		}
	}
	
	public function getUserTypes(){
		try{
			$userType = TipoUsuario::all();
			
			if(count($userType) > 0){
				return response()->json([
					'type' => 'response',
					'userType' => $userType
				]);	
			}else{
				return response()->json([
					'type' => 'error',
					'message' => 'No se ha encontrado ningún tipo de usuario'
				]);	
			}
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'usuarioController';
			$errorLog->metodo = 'getUserTypes';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			return response()->json([
					'type' => 'error',
					'message' => 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde'
				]);	
		}
	}
	
	private function validPassword($pass){
		$correctLength = false;
		$hasMayus = false;
		$hasMinus = false;
		$hasNumber = false;
		
		if(strlen($pass) >= 6 && strlen($pass) <= 10)
			$correctLength = true;
		
		for($i = 0; $i < strlen($pass); $i++){
			if(is_numeric($pass[$i])){
				$hasNumber = true;
				break;
			}
		}
		
		for($i = 0; $i < strlen($pass); $i++){
			if(!is_numeric($pass[$i]) && $pass[$i] == strtoupper($pass[$i])){
				$hasMayus = true;
				break;
			}
		}
		
		for($i = 0; $i < strlen($pass); $i++){
			if(!is_numeric($pass[$i]) && $pass[$i] == strtolower($pass[$i])){
				$hasMinus = true;
				break;
			}
		}
		
		if($correctLength && $hasMayus && $hasMinus && $hasNumber)
			return true;
		
		return false;
	}
}
