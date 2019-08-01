@extends('layout.master')

	@section('title', 'Editor de Hora Bloque')
	
	@section('content')
		<div class="content">
			<h1 class="title">Edición de Hora Bloque</h1>
			<form id="updateBoxForm" action="./updateBlockHour" method="POST">
				<div class="form-group">
						@csrf
						<input type="hidden" id="Id" name="Id" value="{{$config->Id}}"/>
						<div class="input-group mb-3" id="">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-money-bill-alt"></i>&nbsp;Valor Hora Bloque</span>
							</div>
							<input disabled type="text" class="form-control" id="ValorHoraBloque" name="ValorHoraBloque" value="{{$config->ValorHoraBloque}}" required />
						</div>
						<div class="form-group userBoxButtons">
							<a href="./main" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver</a>
							<a style="color:#fff" class="btn btn-primary" id="blockHourUpdate" style="display:inline-block"><i class="fas fa-edit"></i> Editar</a>
							<button class="btn btn-success" id="blockHourSave" style="display:none"><i class="fas fa-check"></i> Guardar</button>
						</div>
				</div>
			</form>
		</div>
		<script>
			$(document).ready(function(){
				$('#footer').css({'position':'fixed', 'bottom':'0px', 'width':'100%'});
				
				$('#blockHourUpdate').on('click', function(){
					$('#blockHourUpdate').css('display','none');
					$('#ValorHoraBloque').prop('disabled', '');
					$('#blockHourSave').css('display','inline-block');
				});
			});
		</script>
	@endsection