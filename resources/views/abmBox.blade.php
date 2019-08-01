@extends('layout.master')

	@section('title', 'Administrador de Box')
	
	@section('content')
		<!--Content-->
		<div class="content">
			<h1 class="title">Administrador de Box</h1>
			
			<a href="./create-box" class="btn btn-success newUserBtn"><i class="fas fa-plus"></i> Nuevo Box</a>
			<div class="table-responsive">
				<table class="table" id="boxTable">
					
				</table>
			</div>
		</div>
		
		<!--Pagination-->
		<div class='wrapper text-center'>
			<div class="btn-group" id="boxPagination">
			</div>
		</div>
		
		<!--User Delete Confirm Modal-->
		<div class="modal" id="boxDeleteConfirm">
			<div class="modal-dialog">
				<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Confirmación</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body" id="boxDeleteConfirmMessage">
					
				</div>
				<div class="modal-footer">
					<input type="hidden" id="boxIdToDelete"/>
					<input type="hidden" id="csrfToken" value="{{csrf_token()}}"/>
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-window-close"></i> Cancelar</button>
					<button type="button" class="btn btn-danger" onclick="boxDelete()"><i class="fas fa-trash"></i> Eliminar</button>
				</div>
			
				</div>
			</div>
		</div>
		<input type="hidden" id="laravelToken" value="{{csrf_token()}}"/>
		<script>
			$(document).ready(function() {
				fillBoxTable(1);
				$('#footer').css({'position':'fixed', 'bottom':'0px', 'width':'100%'});
			});
		</script>
	@endsection