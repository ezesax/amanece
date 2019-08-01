<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Usuario;

class pageController extends Controller
{
    public function login(){
		return view('login');
	}
	
	public function mainMenu(){
		return view('main');
	}
	
	public function abmUser(){
		return view('abmUser');
	}
	
	public function abmBox(){
		return view('abmBox');
	}
	
	public function abmSpeciality(){
		return view('abmSpeciality');
	}
	
	public function newSpeciality(){
		return view('newSpeciality');
	}
	
	public function abmReservation(){
		//$users = Usuario::All();
		//$larger = "";
		//
		//foreach($users as $user){
		//	if(strlen($user->NombreUsuario) > strlen($larger))
		//		$larger = $user->NombreUsuario;
		//}
		//
		//$user = Usuario::where('NombreUsuario', $larger)->get()->first();
		//$larger = $larger.' - '.$user->idUsuario;
		//
		//return $larger;
		
		return view('abmReservation');
	}
}
