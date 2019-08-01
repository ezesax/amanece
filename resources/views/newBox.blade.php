@extends('layout.master')

	@section('title', 'Creador de Box')
	
	@section('content')
		<div class="content">
			<h1 class="title">Crear nuevo Box</h1>
			<form id="createBoxForm" action="./createBox" method="POST">
				<div class="form-group">
						@csrf
						<div class="input-group mb-3" id="">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-pencil-alt"></i>&nbsp;Descripción</span>
							</div>
							<input type="text" class="form-control" id="Descripcion" name="Descripcion" required />
						</div>
						<div class="input-group mb-3" id="">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-tag"></i>&nbsp;Tipo Box</span>
							</div>
							<select class="form-control" id="IdTipoBox" name="IdTipoBox">
								@forelse($boxTypes as $boxType)
									<option value="{{$boxType->IdTipoBox}}">{{$boxType->Descripcion}}</option>
								@empty
								@endforelse
							</select>
						</div>
						<div class="input-group mb-3" id="">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-money-bill-alt"></i>&nbsp;Valor</span>
							</div>
							<input type="text" class="form-control" id="ValorHora" name="ValorHora" required />
						</div>
						<div class="input-group mb-3" id="">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-clock"></i>&nbsp;Admite horas bloque</span>
							</div>
							<input type="checkbox" checked="true" name="horaBloque" id="horaBloque" class="form-control"/>
						</div>
						<div class="form-group userBoxButtons">
							<a href="./abm-box" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver</a>
							<button class="btn btn-success" id="submitUpdate"><i class="fas fa-check"></i> Guardar</button>
						</div>
				</div>
			</form>
		</div>
		<script>
			$(document).ready(function(){
				$('#footer').css({'position':'fixed', 'bottom':'0px', 'width':'100%'});
			});
		</script>
	@endsection