@extends('layout.master')

	@section('title', 'Editor de Box')
	
	@section('content')
		<div class="content">
			<h1 class="title">Edición de Box <i>"{{$box->Descripcion}}"</i></h1>
			<form id="updateBoxForm" action="./updateBox" method="POST">
				<div class="form-group">
						@csrf
						<input type="hidden" id="IdBox" name="IdBox" value="{{$box->IdBox}}"/>
						<div class="input-group mb-3" id="">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-pencil-alt"></i>&nbsp;Descripción</span>
							</div>
							<input type="text" class="form-control" id="Descripcion" name="Descripcion" value="{{$box->Descripcion}}" required />
						</div>
						<div class="input-group mb-3" id="">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-tag"></i>&nbsp;Tipo Box</span>
							</div>
							<select class="form-control" id="IdTipoBox" name="IdTipoBox" required>
								@forelse($boxTypes as $boxType)
									@if($boxType->IdTipoBox == $box->IdTipoBox)
										<option selected value="{{$boxType->IdTipoBox}}">{{$boxType->Descripcion}}</option>
									@endif
									@if($boxType->IdTipoBox != $box->IdTipoBox)
										<option value="{{$boxType->IdTipoBox}}">{{$boxType->Descripcion}}</option>
									@endif
								@empty
								@endforelse
							</select>
						</div>
						<div class="input-group mb-3" id="">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-money-bill-alt"></i>&nbsp;Valor</span>
							</div>
							<input type="text" class="form-control" id="ValorHora" name="ValorHora" value="{{$box->ValorHora}}" required />
						</div>
						<div class="input-group mb-3" id="">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-clock"></i>&nbsp;Admite horas bloque</span>
							</div>
							@if($box->horaBloque == '1')
								<input type="checkbox" checked="true" name="horaBloque" id="horaBloque" class="form-control"/>
							@endif
							@if($box->horaBloque == '0')
								<input type="checkbox" name="horaBloque" id="horaBloque" class="form-control"/>
							@endif
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