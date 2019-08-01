<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use \App\Mes;
use \App\BloqueHorario;
use \App\Reserva;
use \App\ReservaCashe;
use \App\LogError;
use \App\Usuario;
use \App\Box;

class reservaController extends Controller
{
    public function getReservationData(){
		try{
			$year = Input::get('yearSelect');
			$month = Input::get('monthSelect');
			$idBox = Input::get('boxSelect');
			
			if(strlen($month) == 1)
				$month = '0'.$month;
			
			$date = $year.'-'.$month.'-%';
			
			$blocks = BloqueHorario::orderBy('Orden', 'ASC')->get();
			$reservations = Reserva::where('IdBox', $idBox)->where('Cancelada', '0')->where('Fecha', 'LIKE', $date)->get();
			$box = Box::where('IdBox', $idBox)->get()->first();
			
			foreach($reservations as $res){
				$userName = Usuario::where('idUsuario', $res->IdUsuario)->get();
				
				if(count($userName) > 0){
					$userName = $userName->first()->NombreUsuario;
				}else{
					$userName = 'NA';
				}
				
				$res->NombreUsuario = $userName;
			}
			
			if(session()->get('userType') == 1){
				$user = Usuario::orderBy('Nombres', 'ASC')->get();
			}else{
				$user = [session()->get('userFirstName'), session()->get('userLastName'), session()->get('idUsuario')];
			}
			
			return response()->json([
				'type' => 'response',
				'daysInMonth' => cal_days_in_month(CAL_GREGORIAN, $month, $year),
				'blocks' => $blocks,
				'reservations' => $reservations,
				'userType' => session()->get('userType'),
				'box' => $box,
				'user' => $user,
				'userId' => session()->get('idUsuario')
			]);
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'reservaController';
			$errorLog->metodo = 'getReservationData';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			return response()->json([
				'type' => 'error',
				'message' => 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde'
			]);			
		}
	}
	
	public function checkCancel(Request $request){
		try{
			$reservation = Reserva::where('IdReserva', $request->reservationId)->get();
			
			if(count($reservation) == 0){
				return response()->json([
					'type' => 'error',
					'message' => 'No se ha encontrado la reserva'
				]);
			}
			
			$reservation = $reservation->first();
			$user = Usuario::where('idUsuario', $reservation->IdUsuario)->get();
			
			if(count($user) == 0){
				$user = 'NA';
			}else{
				$user = $user->first();
			}
			
			$hourBlock = BloqueHorario::where('IdBloqueHorario', $reservation->IdBloqueHorario)->get()->first();
			
			if($this->isAdmin()){
				return $this->cancel(true, $reservation, $user, $hourBlock->Descripcion);
			}else{
				if($this->isReservationOwner(session()->get('idUsuario'), $reservation)){
					if($this->isInCurrentMonth($reservation)){
						if($this->isFreeHour($reservation)){
							return $this->cancel(true, $reservation, $user, $hourBlock->Descripcion);
						}
						
						if($this->isBlock($request->reservationId)){
							return $this->cancel(false, 0, 0, 0);
						}
						
						if($this->isPeriodic($reservation)){
							if($this->hasCancelationLimit(session()->get('idUsuario'), $reservation, 2)){
								return $this->cancel(false, 0, 0, 0);
							}else{
								if($this->weeksPerMonth($reservation) > 4){
									if($this->weekOfReservation($reservation) <= 2){
										if($this->hasCancelationLimit(session()->get('idUsuario'), $reservation, 2)){
											return $this->cancel(false, 0, 0, 0);
										}else{
											return $this->cancel(true, $reservation, $user, $hourBlock->Descripcion);
										}
									}
									
									if($this->weekOfReservation($reservation) == 3){
										if($this->hasCancelationLimit(session()->get('idUsuario'), $reservation, 1)){
											return $this->cancel(false, 0, 0, 0);
										}else{
											return $this->cancel(true, $reservation, $user, $hourBlock->Descripcion);
										}
									}
									
									if($this->weekOfReservation($reservation) > 3){
										return $this->cancel(false, 0, 0, 0);
									}
								}else{
									if($this->weekOfReservation($reservation) == 1){
										if($this->hasCancelationLimit(session()->get('idUsuario'), $reservation, 2)){
											return $this->cancel(false, 0, 0, 0);
										}else{
											return $this->cancel(true, $reservation, $user, $hourBlock->Descripcion);
										}
									}
									
									if($this->weekOfReservation($reservation) == 2){
										if($this->hasCancelationLimit(session()->get('idUsuario'), $reservation, 1)){
											return $this->cancel(false, 0, 0, 0);
										}else{
											return $this->cancel(true, $reservation, $user, $hourBlock->Descripcion);
										}
									}
									
									if($this->weekOfReservation($reservation) > 2){
										return $this->cancel(false, 0, 0, 0);
									}
								}
							}
						}
					}else{
						if($this->isInPermitedDates(20, 31)){
							if($this->isFreeHour($reservation)){
								return $this->cancel(true, $reservation, $user, $hourBlock->Descripcion);
							}
							
							if($this->isPeriodic($reservation)){
								return $this->cancelAll($reservation, $user, $hourBlock->Descripcion);
							}
							
							if($this->isBlock($reservation)){
								return $this->cancelAll($reservation, $user, $hourBlock->Descripcion);
							}
						}else{
							return $this->cancel(false, 0, 0, 0);
						}
					}
				}else{
					return $this->cancel(false, 0, 0, 0);
				}
			}
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'reservaController';
			$errorLog->metodo = 'checkCancel';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			return response()->json([
				'type' => 'error',
				'message' => 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde'
			]);			
		}
	}
	
	public function reservationCancel(Request $request){
		try{
			$resCashe = ReservaCashe::where('IdReserva', $request->reserveId)->where('idUsuario', session()->get('idUsuario'))->get();
			
			if(count($resCashe) > 0){
				$reservation = Reserva::where('IdReserva', $request->reserveId)->get()->first();
				$userId = Usuario::where('idUsuario', $reservation->IdUsuario)->get()->first()->idUsuario;
				$block = $reservation->IdBloqueHorario;
				
				if($reservation->EsBloque == 0){
					if($reservation->PosicionBloque == 1){
						Reserva::where('Fecha', $reservation->Fecha)
						->where('IdUsuario', $userId)
						->where('IdBloqueHorario', $block)
						->delete();
						
						$block++;
						
						Reserva::where('Fecha', $reservation->Fecha)
						->where('IdUsuario', $userId)
						->where('IdBloqueHorario', $block)
						->delete();
					}else{
						Reserva::where('Fecha', $reservation->Fecha)
						->where('IdUsuario', $userId)
						->where('IdBloqueHorario', $block)
						->delete();
						
						$block--;
						
						Reserva::where('Fecha', $reservation->Fecha)
						->where('IdUsuario', $userId)
						->where('IdBloqueHorario', $block)
						->delete();
					}
				}else{
					return response()->json([
						'type' => 'error',
						'message' => 'Esto es muy jodido, lo vemos despues'
					]);
				}
				
				ReservaCashe::where('IdReserva', $request->reserveId)->where('idUsuario', session()->get('idUsuario'))->delete();
				
				return response()->json([
					'type' => 'response',
					'message' => 'La reserva se ha cancelado exitosamente'
				]);
			}else{
				return response()->json([
					'type' => 'error',
					'message' => 'Ha ocurrido un error, por favor intente mas tarde'
				]);
			}
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'reservaController';
			$errorLog->metodo = 'reservationCancel';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			return response()->json([
				'type' => 'error',
				'message' => 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde'
			]);			
		}
	}
	
	public function deleteCashe(Request $request){
		try{
			ReservaCashe::where('IdReserva', $request->reservId)->where('IdUsuario', session()->get('idUsuario'))->delete();
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'reservaController';
			$errorLog->metodo = 'deleteCashe';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			return response()->json([
				'type' => 'error',
				'message' => 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde'
			]);			
		}
	}
	
	private function isReservationOwner($userId, $reservation){
		try{			
			if($reservation->IdUsuario == $userId)
				return true;
			
			return false;
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'reservaController';
			$errorLog->metodo = 'isReservationOwner';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			return response()->json([
				'type' => 'error',
				'message' => 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde'
			]);			
		}
	}
	
	private function isInCurrentMonth($reservation){
		try{			
			$resMonth = substr($reservation->Fecha,5,2);
			
			if($resMonth == date('m'))
				return true;
			
			return false;
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'reservaController';
			$errorLog->metodo = 'isInCurrentMonth';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			return response()->json([
				'type' => 'error',
				'message' => 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde'
			]);			
		}
	}
	
	private function isFreeHour($reservation){
		try{			
			if($reservation->EsPeriodica == '0'
				&& $reservation->EsBloque == '0'){
					return true;
			}
			
			return false;
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'reservaController';
			$errorLog->metodo = 'isFreeHour';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			return response()->json([
				'type' => 'error',
				'message' => 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde'
			]);			
		}
	}
	
	private function isBlock($reservation){
		try{			
			if($reservation->EsBloque == '1'){
					return true;
			}
			
			return false;
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'reservaController';
			$errorLog->metodo = 'isBlock';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			return response()->json([
				'type' => 'error',
				'message' => 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde'
			]);			
		}
	}
	
	private function isPeriodic($reservation){
		try{			
			if($reservation->EsPeriodica == '1'){
					return true;
			}
			
			return false;
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'reservaController';
			$errorLog->metodo = 'isPeriodic';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			return response()->json([
				'type' => 'error',
				'message' => 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde'
			]);			
		}
	}
	
	private function hasCancelationLimit($userId, $reservation, $limit){
		try{			
			$limit = $limit * 2;
			
			$hourBlock = BloqueHorario::where('IdBloqueHorario', $reservation->IdBloqueHorario)->get()->first();
			$date = substr($reservation->Fecha,0,4).'-'.substr($reservation->Fecha,5,2).'-%';
			
			$res = Reserva::where('EsPeriodica', '1')
				->where('EsBloque', '0')
				->where('Fecha', 'LIKE', $date)
				->where('Cancelada', '1')
				->where('IdBloqueHorario', $reservation->IdBloqueHorario)
				->where('IdUsuario', $userId)
				->get();
				
				if(count($res) >= $limit)
					return true;
				
				return false;				
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'reservaController';
			$errorLog->metodo = 'hasCancelationLimit';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			return response()->json([
				'type' => 'error',
				'message' => 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde'
			]);			
		}
	}
	
	private function weeksPerMonth($reservation){
		try{			
			$year = substr($reservation->Fecha,0,4);
			$month = substr($reservation->Fecha,5,2);
			
			$firstday = date("w", mktime(0, 0, 0, $month, 1, $year)); 
			$lastday = date("t", mktime(0, 0, 0, $month, 1, $year));
			$weeks = 1 + ceil(($lastday-8+$firstday)/7);
			
			return $weeks;
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'reservaController';
			$errorLog->metodo = 'weeksPerMonth';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			return response()->json([
				'type' => 'error',
				'message' => 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde'
			]);			
		}
	}
	
	private function weekOfReservation($reservation){
		try{			
			$year = substr($reservation->FechaReservaEfectiva,0,4);
			$month = substr($reservation->FechaReservaEfectiva,5,2);
			$day = substr($reservation->FechaReservaEfectiva,8,2);
			
			$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
			$week = 1;
			$origDay = $day;
			
			for($i = 1; $i <= $daysInMonth; $i++){
				$date = $year.$month.$day;
				$date = DateTime::createFromFormat('Ymd', $date);
				
				if($i == $origDay)
					return $week;
				if($date->format('D') == 'Sun')
					$week++;
				
				$day++;
			}
			
			return $week;
			
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'reservaController';
			$errorLog->metodo = 'weekOfReservation';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			return response()->json([
				'type' => 'error',
				'message' => 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde'
			]);			
		}
	}
	
	private function isInPermitedDates($from, $to){
		try{
			if(intval(date('d')) >= $from && intval(date('d')) <= $to)
				return true;
			
			return false;
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'reservaController';
			$errorLog->metodo = 'isInPermitedDates';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			return response()->json([
				'type' => 'error',
				'message' => 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde'
			]);			
		}
	}
	
	private function cancel($bool, $reservation, $user, $hourBlock){
		try{
			if($bool == false){
				return response()->json([
					'type' => 'error',
					'message' => 'No se puede cancelar esta hora'
				]);
			}else{
				$date = substr($reservation->Fecha,8,2).'/'.substr($reservation->Fecha,5,2).'/'.substr($reservation->Fecha,0,4);
				
				if($this->isAdmin()){
					$resCashe = new ReservaCashe();
					$resCashe->IdUsuario = session()->get('idUsuario');
					$resCashe->IdReserva = $reservation->IdReserva;
					$resCashe->save();
									
					return response()->json([
						'type' => 'response',
						'reservationId' => $reservation->IdReserva,
						'user' => $user,
						'date' => $date,
						'hour' => $hourBlock
					]);
				}else{
					if($this->correctAnticipation($reservationDate, $hourBlock)){
						$resCashe = new ReservaCashe();
						$resCashe->IdUsuario = session()->get('idUsuario');
						$resCashe->IdReserva = $reservation->IdReserva;
						$resCashe->save();
										
						return response()->json([
							'type' => 'response',
							'reservationId' => $reservation->IdReserva,
							'user' => $user,
							'date' => $date,
							'hour' => $hourBlock
						]);
					}else{
						return response()->json([
							'type' => 'error',
							'message' => 'Debe haber al menos 24 horas para poder cancelar'
						]);
					}
				}
			}
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'reservaController';
			$errorLog->metodo = 'cancel';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			return response()->json([
				'type' => 'error',
				'message' => 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde'
			]);			
		}
	}
	
	private function cancelAll($reservation, $user, $hourBlock){
		try{
			$resCashe = new ReservaCashe();
			$resCashe->IdUsuario = session()->get('idUsuario');
			$resCashe->IdReserva = $reservation->IdReserva;
			$resCashe->save();
							
			return response()->json([
				'type' => 'response',
				'reservationId' => $reservation->IdReserva,
				'user' => $user,
				'date' => $date,
				'hour' => $hourBlock,
				'requireAllConfirmation' => true
			]);
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'reservaController';
			$errorLog->metodo = 'cancel';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			return response()->json([
				'type' => 'error',
				'message' => 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde'
			]);			
		}
	}
	
	private function correctAnticipation($reservationDate, $hourBlock){
		try{
			$resDate = new DateTime($reservationDate.' '.$hourBlock.':00');
			$currentDate = new DateTime('Y-m-d H:i:s');
			
			$interval = $resDate->diff($currentDate);
			
			if(intval($interval->format('%d')) > 0){
				return true;
			}
			
			return false;
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'reservaController';
			$errorLog->metodo = 'correctAnticipation';
			$errorLog->mensaje = $ex->getMessage();
			$errorLog->fecha = $currentDate;
			
			$errorLog->save();
			
			return response()->json([
				'type' => 'error',
				'message' => 'Ha ocurrido un error de servidor, por favor intente de nuevo más tarde'
			]);			
		}
	}
	
	private function isAdmin(){
		try{
			if(session()->get('userType') == 1)
				return true;
			
			return false;
		}catch(\Exception $ex){
			$currentDate = date('Y-m-d H:i:s');
			
			$errorLog = new LogError();
			
			$errorLog->idUsuario = session()->get('idUsuario');
			$errorLog->controller = 'reservaController';
			$errorLog->metodo = 'isAdmin';
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
