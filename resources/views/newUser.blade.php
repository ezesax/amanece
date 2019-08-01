@extends('layout.master')

	@section('title', 'Creador de Usuario')
	
	@section('content')
		<div class="content">
			<h1 class="title">Crear nuevo Usuario</h1>
			<form id="createUserForm" action="create" method="POST">
				<div class="form-group">
						@csrf
						<div class="input-group mb-3" id="">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-user"></i>&nbsp;Usuario</span>
							</div>
							<input type="text" class="form-control" id="NombreUsuario" name="NombreUsuario" required />
						</div>
						<div class="input-group mb-3" id="">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-envelope"></i>&nbsp;Email</span>
							</div>
							<input type="email" class="form-control" id="Email" name="Email" required />
						</div>
						<div class="input-group mb-3" id="">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-key"></i>&nbsp;Clave</span>
							</div>
							<input type="password" class="form-control" id="clave" name="clave" required />
							<p class="errorMessage">La clave debe tener al menos una mayúscula, al menos una minúscula, al menos un número, y ser de entre 6 y 10 caracteres de largo</p>
						</div>
						<div class="input-group mb-3" id="">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-user-tag"></i>&nbsp;Tipo de Usuario</span>
							</div>
							<select class="form-control" id="IdTipoUsuario" name="IdTipoUsuario"/>
								@forelse($userTypes as $userType)
									@if($userType->IdTipoUsuario == 2)
										<option selected value="{{$userType->IdTipoUsuario}}">{{$userType->Descripcion}}</option>
									@endif
									@if($userType->IdTipoUsuario != 2)
										<option value="{{$userType->IdTipoUsuario}}">{{$userType->Descripcion}}</option>
									@endif
								@empty
								@endforelse
							</select>
						</div>
				</div>
			</form>
			<div class="form-group userEditButtons">
				<a href="./abm-user" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver</a>
				<button class="btn btn-success" id="submitNewUser"><i class="fas fa-check"></i> Guardar</button>
			</div>
		</div>
		<script>
			$(document).ready(function(){
				$('#footer').css({'position':'fixed', 'bottom':'0px', 'width':'100%'});
				
				$('#submitNewUser').on('click', function(){
					$('.errorMessage').css({'display':'none'});
					
					if(validPassword($('#clave').val())){
						$('#createUserForm').submit();
					}else{
					$('.errorMessage').css({'display':'block'});
					}
				});
			});
		</script>
	@endsection