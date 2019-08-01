@extends('layout.master')

	@section('title', 'Editor de Especialidad')
	
	@section('content')
		<div class="content">
			<h1 class="title">Edición de Especialidad <i>"{{$speciality->Descripcion}}"</i></h1>
			<form id="updateBoxForm" action="./updateSpeciality" method="POST">
				<div class="form-group">
						@csrf
						<input type="hidden" id="IdTipoDoctor" name="IdTipoDoctor" value="{{$speciality->IdTipoDoctor}}"/>
						<div class="input-group mb-3" id="">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-pencil-alt"></i>&nbsp;Descripción</span>
							</div>
							<input type="text" class="form-control" id="Descripcion" name="Descripcion" value="{{$speciality->Descripcion}}" required />
						</div>
						<div class="form-group userBoxButtons">
							<a href="./abm-specialty" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver</a>
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