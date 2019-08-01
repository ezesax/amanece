<!DOCTYPE html>

<html>
    <head>
		<link rel="icon" href="./images/icon.png"/>
        <title>Amanece - @yield('title')</title>
		
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<link type="text/css" rel="stylesheet" href="./css/app.css"/>
		<link type="text/css" rel="stylesheet" href="./css/style.css"/>
		<link rel="stylesheet" href="./fontawesome/css/all.css"/>
		<script type="text/javascript" src="./js/app.js"></script>
		<script type="text/javascript" src="./js/functions.js"></script>
    </head>
    <body>
		<div id="loadingBackGround"></div>
		<div id="loading">
			<div id="loadingcontent" style="">
				<p id="loadingspinner" style="">
					<i class="fa fa-spinner fa-spin fa-2x"></i><br />
					<b>Cargando...</b>
				</p>
			</div>
		</div>
		@if(session()->has('error'))
			<div class="alert alert-danger alertLogin alert-dismissible fade show" role="alert">
				<strong>ERROR!</strong> {{session()->get('error')}}
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		@endif
		@if(session()->has('success'))
			<div class="alert alert-success alertLogin alert-dismissible fade show" role="alert">
				<strong>EXITO!</strong> {{session()->get('success')}}
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		@endif
		@if(session()->has('warning'))
			<div class="alert alert-warning alertLogin alert-dismissible fade show" role="alert">
				<strong>ADVERTENCIA!</strong> {{session()->get('warning')}}
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		@endif
		<nav class="navbar navbar-expand-md navbar-dark bg-dark">
			<a class="navbar-brand" href="#"><img src="./images/logo.png"/></a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsingNavbar">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="navbar-collapse collapse" id="collapsingNavbar">
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link" href="./main"><i class="fas fa-home"></i> Inicio</a>
					</li>
					<li class="nav-item">
						@if(session()->get('userType') == 1) <!--Admin reports-->
							<a class="nav-link" href="#"><i class="fas fa-file-excel"></i> Reportes</a>
						@endif
						@if(session()->get('userType') == 2) <!--User report-->
							<a class="nav-link" href="#"><i class="fas fa-file-excel"></i> Reportes</a>
						@endif
					</li>
					@if(session()->get('userType') == 1)
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fas fa-user-cog"></i> Administrador
							</a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
								<a class="dropdown-item" href="./abm-box"><i class="fas fa-square"></i> Box</a>
								<a class="dropdown-item" href="./abm-user"><i class="fas fa-user-md"></i> Terapeutas</a>
								<a class="dropdown-item" href="./abm-specialty"><i class="fas fa-tags"></i> Especialidades</a>
								<a class="dropdown-item" href="./abm-hora-bloque"><i class="fas fa-clock"></i></i> Hora bloque</a>
								<a class="dropdown-item" href="./abm-reservation"><i class="fas fa-calendar-alt"></i> Reservas</a>
							</div>
						</li>
					@endif
					
					@if(session()->get('userType') == 2)
						<li class="nav-item">
							<a class="nav-link" href="./abm-reservation"><i class="fas fa-calendar-alt"></i> Reservas	</a>
						</li>
					@endif
					
					@if(session()->get('port') == 1)
						<li class="nav-item">
							<a class="nav-link" href="#"><i class="fas fa-bars"></i> Disponibilidad</a>
						</li>
					@endif
				</ul>
				<ul class="navbar-nav ml-auto">
					<li class="nav-item">
						<a class="nav-link" href=""><i class="fas fa-user"></i> {{session()->get('userName')}}</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="./logOut"><i class="fas fa-sign-out-alt"></i> Salir</a>
					</li>
				</ul>
			</div>
		</nav>
        <div class="container contentDiv">
            @yield('content')
        </div>
		<footer id="footer" class="page-footer font-small unique-color-dark">
			<div class="container text-center">
				<div class="row mt-3">
					<div class="col-md-4 col-lg-4 col-xl-4 mx-auto mb-4">
						<h6 class="text-uppercase font-weight-bold">Info contacto</h6>
					</div>
					<div class="col-md-4 col-lg-4 col-xl-4 mx-auto mb-md-0 mb-4">
						<p class="align-middle">
							<i class="fas fa-envelope mr-3"></i> reservas@amanece.cl
						</p>
					</div>
					<div class="col-md-4 col-lg-4 col-xl-4 mx-auto mb-md-0 mb-4">
						<p class="align-middle">
							<i class="fas fa-phone mr-3"></i> +56 2 324 59 397
						</p>
					</div>
				</div>
			</div>
		</footer>
		<div class="modal" id="modalMessage">
			<div class="modal-dialog">
				<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="modalMessageTitle"></h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body" id="modalMessageMessage">
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-window-close"></i> Cerrar</button>
				</div>
			
				</div>
			</div>
		</div>
    </body>
</html>