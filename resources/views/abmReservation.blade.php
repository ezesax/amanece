@extends('layout.master')

	@section('title', 'Administrador de Reservas')
	
	@section('content')
	<style>
		.alert{
			border:none !important;
			z-index:0 !important;
		}
		
		#reservationTableContent{
			overflow:hidden;
			display:none;
		}
		
		table{
			table-layout:fixed;
		}
		
		th{
			width:100px !important;
		}
		
		td{
			color:#212529;
			font-weight:bold;
			height:60px;
		}
		
		td, th, table, #reservationTableContent{
			outline:1px solid #ccc;
		}
		
		td:hover{
			cursor:pointer;
			opacity:0.5;
		}
		
		.th, #fixColumn{
			background-color:#f8f9fa;
			position:sticky;
			left:0px;
			z-index:800;
		}
		
		#spaceBlock{
			display:block
			width:100%;
			height:100px;
		}
		
		@media (min-width: 0px) and (max-width: 1000px){
			.th, #fixColumn{
				position:relative;
			}
		}
	</style>
	
		<!--Content-->
		<div class="content">
			<h1 class="title">Administrador de Reservas</h1>
			<button style="display:none" class="btn btn-primary newUserBtn float-left" id="showFilter"><i class="fas fa-filter"></i> Abrir Filtro</button>
			<button class="btn btn-danger newUserBtn float-left" id="hideFilter"><i class="fas fa-filter"></i> Cerrar Filtro</button>
			<br><br><br>
			<div id="reservationFilter">
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="fas fa-calendar-alt"></i>&nbsp;Año</span>
					</div>
					<select id="yearSelect" class="form-control"/>
					
					</select>
				</div>
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="fas fa-calendar-alt"></i>&nbsp;Mes</span>
					</div>
					<select id="monthSelect" class="form-control"/>
					
					</select>
				</div>
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="fas fa-square"></i>&nbsp;Box</span>
					</div>
					<select id="boxSelect" class="form-control"/>
					
					</select>
				</div>
				<button class="btn btn-dark newUserBtn" id="reservationSearch"><i class="fas fa-search"></i> Filtrar</button>
			</div>
			<div id="spaceBlock"></div>
			<div class="table-responsive" id="reservationTableContent">
				<table class="table table-bordered table-sm" id="reservationTable">
					
				</table>
				<input type="hidden" id="cell" name="cell" value=""/>
			</div>
		</div>
		<div class="modal" id="modalReservation">
			<div class="modal-dialog">
				<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="modalReservationTitle">Completar datos de reserva</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body" id="modalReservationContent">
					<h5 id="modalReservationSubtitle"></h5>
					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-user"></i>&nbsp;Usuario</span>
						</div>
						<select id="userSelect" class="form-control"/>
							
						</select>
					</div>
					<div class="input-group mb-3" id="">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-clock"></i>&nbsp;¿Es hora suelta?</span>
						</div>
						<input type="checkbox" name="freeHour" id="freeHour" class="form-control"/>
					</div>
					<div class="input-group mb-3" id="">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-clock"></i>&nbsp;¿Es periódica?</span>
						</div>
						<input type="checkbox" name="periodicHour" id="periodicHour" class="form-control"/>
					</div>
					<div class="input-group mb-3" id="blockHourPanel">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-clock"></i>&nbsp;¿Es hora bloque?</span>
						</div>
						<input type="checkbox" name="blockHour" id="blockHour" class="form-control"/>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" id="boxToReserve" name="boxToReserve" value=""/>
					<input type="hidden" id="laravelToken" value="{{csrf_token()}}"/>
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-window-close"></i> Cerrar</button>
					<button type="button" class="btn btn-success" onclick="saveReservation()"><i class="fas fa-check"></i> Guardar</button>
				</div>
			
				</div>
			</div>
		</div>
		<div class="modal" id="modalReservationConfirmCancel">
			<div class="modal-dialog">
				<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="modalReservationConfirmCancelTitle">Cancelar reserva</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body" id="modalReservationContent">
					<h5 id="modalReservationConfirmCancelSubtitle">Va a eliminar la reserva</h5>
					<div class="input-group mb-3" id="allHoursPeriod" style="display:none">
						<p>Si selecciona este checkbox se eliminarán todas las horas del mes correspondientes a este día y bloque horario. Si lo deja sin completar solo se eliminará esta hora suelta.</p>
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-clock"></i>&nbsp;¿Todo el período?</span>
						</div>
						<input type="checkbox" name="all" id="all" class="form-control"/>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" id="idReserve" name="idReserve" value=""/>
					<input type="hidden" id="laravelToken" value="{{csrf_token()}}"/>
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-window-close"></i> Cerrar</button>
					<button type="button" class="btn btn-danger" onclick="cancelReservation()"><i class="fas fa-trash-alt"></i> Eliminar</button>
				</div>
			
				</div>
			</div>
		</div>
		<input type="hidden" id="currentBox" value=""/>
		<script>
			$(document).ready(function() {
				$('#footer').css({'position':'fixed', 'bottom':'0px', 'width':'100%'});
				fillReservationSelects();
				
				$('#showFilter').on('click', function(){
					$('#reservationFilter').show(250);
					$('#showFilter').css('display', 'none');
					$('#hideFilter').css('display', 'block');	
				});
				
				$('#hideFilter').on('click', function(){
					$('#reservationFilter').hide(250);
					$('#hideFilter').css('display', 'none');
					$('#showFilter').css('display', 'block');
				});
				
				$('#reservationSearch').on('click', function(){
					buildReservationTable();
				});
				
				$('#reservationTableContent').on('mousemove', function(e){
					var marginX = scale(e.clientX, (document.getElementById('reservationTableContent').offsetLeft + ($('#fixColumn').width()*2)), $('#reservationTableContent').width(), 0, $('#reservationTable').width());
					document.getElementById('reservationTableContent').scrollLeft = marginX;
				});
				
				if($(window).width() < 1001){
					$('#reservationTableContent').unbind();
					$('#reservationTableContent').css('overflow-x', 'auto');
				}
			});
		</script>
	@endsection