@extends('layout.master')

	@section('title', 'Inicio')
	
	@section('content')
		<script>
			$(document).ready(function(){
				$('#footer').css({'position':'fixed', 'bottom':'0px', 'width':'100%'});
			});
		</script>
	@endsection