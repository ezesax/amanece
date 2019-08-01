<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/* pageController */
	
	//GET ROUTES
	Route::get('/', 'pageController@login');
	Route::get('/main', 'pageController@mainMenu');
	Route::get('/abm-user', 'pageController@abmUser');
	Route::get('/abm-box', 'pageController@abmBox');
	Route::get('/abm-specialty', 'pageController@abmSpeciality');
	Route::get('/create-speciality', 'pageController@newSpeciality');
	Route::get('/abm-reservation', 'pageController@abmReservation');


/* usuarioController */

	//GET ROUTES
	Route::get('/logOut', 'usuarioController@logOut');
	Route::get('/abmUser', 'usuarioController@abmUser');
	Route::get('/getAllUsers', 'usuarioController@getAllUsers');
	Route::get('/getUserTypes', 'usuarioController@getUserTypes');
	Route::get('/user-edit', 'usuarioController@userEdit');
	Route::get('/create-user', 'usuarioController@newUser');
	
	//POST ROUTES
	Route::post('/login', 'usuarioController@login');
	Route::post('/getUserById', 'usuarioController@getUserById');
	Route::post('/getUserByFilter', 'usuarioController@getUserByFilter');
	Route::post('/create', 'usuarioController@create');
	Route::post('/update', 'usuarioController@update');
	Route::post('/deleteUser', 'usuarioController@deleteUser');
	
/* especialidadesController */
	//GET ROUTES
	Route::get('/getAllSpecialties', 'especialidadesController@getAllSpecialties');
	Route::get('/speciality-edit', 'especialidadesController@specialityEdit');
	
	//POST ROUTES
	Route::post('/updateSpeciality', 'especialidadesController@updateSpeciality');
	Route::post('/createSpeciality', 'especialidadesController@createSpeciality');
	Route::post('/deleteSpeciality', 'especialidadesController@deleteSpeciality');
	
/* boxController */

	//GET ROUTES
	Route::get('/getAllBoxes', 'boxController@getAllBoxes');
	Route::get('/getAllBoxesWithNoPaginate', 'boxController@getAllBoxesWithNoPaginate');
	Route::get('/box-edit', 'boxController@boxEdit');
	Route::get('/create-box', 'boxController@newBox');
	
	//POST ROUTES
	Route::post('/deleteBox', 'boxController@deleteBox');
	Route::post('/updateBox', 'boxController@updateBox');
	Route::post('/createBox', 'boxController@createBox');
	
/* configuracionController */
	
	//GET ROUTES
	Route::get('/abm-hora-bloque', 'configuracionController@abmHoraBloque');
	
	//POST ROUTES
	Route::post('/updateBlockHour', 'configuracionController@updateBlockHour');
	
/* reservaController */

	//GET ROUTES
	Route::get('getReservationData', 'reservaController@getReservationData');
	
	//POST ROUTES
	Route::post('checkCancel', 'reservaController@checkCancel');
	Route::post('reservationCancel', 'reservaController@reservationCancel');
	Route::post('deleteCashe', 'reservaController@deleteCashe');
	
/*TEST ROUTES*/

	Route::get('test', function(){
		$resDate = new DateTime('2019-08-21 20:30:00');
		$currentDate = new DateTime('2019-08-20 20:30:00');
		
		$interval = $resDate->diff($currentDate);
		
		return $interval->format('%Y años, %m Meses, %d Dias, %H Horas, %i Minutos, %s Segundos');
		
		if(intval($interval->format('%d')) > 0){
			return true;
		}
		
		return false;
	});