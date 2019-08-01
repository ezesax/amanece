@extends('layout.master')

	@section('title', 'Administrador de Usuarios')
	
	@section('content')
		<!--Content-->
		<div class="content">
			<h1 class="title">Administrador de Usuarios</h1>
			<div class="input-group mb-3" id="userFilter">
				<div class="input-group-prepend">
					<span class="input-group-text">Filtro</span>
				</div>
				<input type="text" id="filterSearch" class="form-control"/>
				<div class="input-group-append">
					<button id="searchFilterBtn" class="btn btn-dark" type="button"><i class="fas fa-search"></i></button>
				</div>
			</div>
			<a href="./create-user" class="btn btn-success newUserBtn"><i class="fas fa-plus"></i> Nuevo Usuario</a>
			<div class="table-responsive">
				<table class="table" id="userTable">
					
				</table>
			</div>
		</div>
		
		<!--Pagination-->
		<div class='wrapper text-center'>
			<div class="btn-group" id="userPagination">
			</div>
		</div>
		
		<!--User Delete Confirm Modal-->
		<div class="modal" id="userDeleteConfirm">
			<div class="modal-dialog">
				<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Confirmación</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body" id="userDeleteConfirmMessage">
					
				</div>
				<div class="modal-footer">
					<input type="hidden" id="userIdToDelete"/>
					<input type="hidden" id="csrfToken" value="{{csrf_token()}}"/>
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-window-close"></i> Cancelar</button>
					<button type="button" class="btn btn-danger" onclick="userDelete()"><i class="fas fa-trash"></i> Eliminar</button>
				</div>
			
				</div>
			</div>
		</div>
		<input type="hidden" id="laravelToken" value="{{csrf_token()}}"/>
		<script>
			$(document).ready(function() {
				fillUserTable(1, '');
				$('#footer').css({'margin-top':'20px'});
				
				$('#filterSearch').on('keypress', function(e){
					if(e.keyCode == 13){
						fillUserTable(1, $('#filterSearch').val());
					}
				});
				
				$('#searchFilterBtn').on('click', function(){
					fillUserTable(1, $('#filterSearch').val());
				});
			});
		</script>
	@endsection