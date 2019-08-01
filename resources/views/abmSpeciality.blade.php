@extends('layout.master')

	@section('title', 'Administrador de Especialidades')
	
	@section('content')
		<!--Content-->
		<div class="content">
			<h1 class="title">Administrador de Especialidades</h1>
			
			<a href="./create-speciality" class="btn btn-success newUserBtn"><i class="fas fa-plus"></i> Nueva Especialidad</a>
			<div class="table-responsive">
				<table class="table" id="specialityTable">
					
				</table>
			</div>
		</div>
		
		<!--Pagination-->
		<div class='wrapper text-center'>
			<div class="btn-group" id="specialityPagination">
			</div>
		</div>
		
		<!--User Delete Confirm Modal-->
		<div class="modal" id="specialityDeleteConfirm">
			<div class="modal-dialog">
				<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Confirmación</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body" id="specialityDeleteConfirmMessage">
					
				</div>
				<div class="modal-footer">
					<input type="hidden" id="specialityIdToDelete"/>
					<input type="hidden" id="csrfToken" value="{{csrf_token()}}"/>
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-window-close"></i> Cancelar</button>
					<button type="button" class="btn btn-danger" onclick="specialityDelete()"><i class="fas fa-trash"></i> Eliminar</button>
				</div>
			
				</div>
			</div>
		</div>
		<input type="hidden" id="laravelToken" value="{{csrf_token()}}"/>
		<script>
			$(document).ready(function() {
				fillSpecialityTable(1);
				$('#footer').css({'margin-top':'20px'});
			});
		</script>
	@endsection