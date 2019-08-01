@extends('layout.master')

	@section('title', 'Edición de Usuario')
	
	@section('content')
		<div class="content">
			<h1 class="title">Edición de usuario <i>"{{$user->NombreUsuario}}"</i></h1>
			<form id="updateUserForm" action="update" method="POST">
				<div class="form-group">
						@csrf
						<input type="hidden" id="idUsuario" name="idUsuario" value="{{$user->idUsuario}}"/>
						<div class="input-group mb-3" id="">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-user"></i>&nbsp;Nombres</span>
							</div>
							<input type="text" class="form-control" id="Nombres" name="Nombres" value="{{$user->Nombres}}" required />
						</div>
						<div class="input-group mb-3" id="">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-user"></i>&nbsp;Apellidos</span>
							</div>
							<input type="text" class="form-control" id="Apellidos" name="Apellidos" value="{{$user->Apellidos}}" required />
						</div>
						<div class="input-group mb-3" id="">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-address-card"></i>&nbsp;RUT</span>
							</div>
							<input type="text" class="form-control" id="RUT" name="RUT" value="{{$user->RUT}}" required />
						</div>
						<div class="input-group mb-3" id="">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-phone"></i>&nbsp;Telefono</span>
							</div>
							<input type="text" class="form-control" id="Telefono" name="Telefono" value="{{$user->Telefono}}" required />
						</div>
						<div class="input-group mb-3" id="">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-envelope"></i>&nbsp;Email</span>
							</div>
							<input type="email" class="form-control" id="Email" name="Email" value="{{$user->Email}}" required />
						</div>
						<div class="input-group mb-3" id="">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-map-marker-alt"></i>&nbsp;Dirección</span>
							</div>
							<input type="text" class="form-control" id="Direccion" name="Direccion" value="{{$user->Direccion}}" required />
						</div>
						<div class="input-group mb-3" id="">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-user"></i>&nbsp;Usuario</span>
							</div>
							@if(session()->get('userType') == 1)
								<input type="text" class="form-control" id="NombreUsuario" name="NombreUsuario" value="{{$user->NombreUsuario}}" required />
							@endif
							@if(session()->get('userType') != 1)
								<input disabled type="text" class="form-control" id="NombreUsuario" name="NombreUsuario" value="{{$user->NombreUsuario}}" required />
							@endif
						</div>
						<div class="input-group mb-3" id="">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-key"></i>&nbsp;Clave</span>
							</div>
							<input type="password" class="form-control" id="clave" name="clave"/>
							<p class="errorMessage">La clave debe tener al menos una mayúscula, al menos una minúscula, al menos un número, y ser de entre 6 y 10 caracteres de largo</p>
						</div>
						<div class="input-group mb-3" id="">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-tag"></i>&nbsp;Especialidad</span>
							</div>
							<select class="form-control" name="IdTipoDoctor" id="IdTipoDoctor">
								@forelse($specialities as $specialitie)
									@if($user->IdTipoDoctor == $specialitie->IdTipoDoctor)
										<option selected value="{{$specialitie->IdTipoDoctor}}">{{$specialitie->Descripcion}}</option>
									@endif
									@if($user->IdTipoDoctor != $specialitie->IdTipoDoctor)
										<option value="{{$specialitie->IdTipoDoctor}}">{{$specialitie->Descripcion}}</option>
									@endif
								@empty
								@endforelse
							</select>
						</div>
						<div class="input-group mb-3" id="">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-user-tag"></i>&nbsp;Tipo de Usuario</span>
							</div>
							<select class="form-control" name="IdTipoUsuario" id="IdTipoUsuario">
								@forelse($userTypes as $userType)
									@if($user->IdTipoUsuario == $userType->IdTipoUsuario)
										<option selected value="{{$userType->IdTipoUsuario}}">{{$userType->Descripcion}}</option>
									@endif
									@if($user->IdTipoUsuario != $userType->IdTipoUsuario)
										<option value="{{$userType->IdTipoUsuario}}">{{$userType->Descripcion}}</option>
									@endif
								@empty
								@endforelse
							</select>
						</div>
						<div class="input-group mb-3" id="">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-check"></i>&nbsp;Usuario Activo</span>
							</div>
							@if($user->Activo == '1')
								<input type="checkbox" checked="true" name="Activo" id="Activo" class="form-control"/>
							@endif
							@if($user->Activo == '0')
								<input type="checkbox" name="Activo" id="Activo" class="form-control"/>
							@endif
						</div>
				</div>
			</form>
			<div class="form-group userEditButtons">
				<a href="./abm-user" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver</a>
				<button class="btn btn-success" id="submitUpdate"><i class="fas fa-check"></i> Guardar</button>
			</div>
		</div>
		<script>
			$(document).ready(function(){
				$('#submitUpdate').on('click', function(){
					$('.errorMessage').css({'display':'none'});
					
					if($('#clave').val() != ""){
						if(validPassword($('#clave').val())){
							$('#updateUserForm').submit();
						}else{
							$('.errorMessage').css({'display':'block'});
						}
					}else{
						$('#updateUserForm').submit();
					}
				});
			});
		</script>
	@endsection