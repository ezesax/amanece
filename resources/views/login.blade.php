<!DOCTYPE html>

<html>
	<head>
		<link rel="icon" href="./images/icon.png"/>
        <title>Amanece - LogIn</title>
		
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link type="text/css" rel="stylesheet" href="./css/app.css"/>
		<link type="text/css" rel="stylesheet" href="./css/style.css"/>
		<link rel="stylesheet" href="./fontawesome/css/all.css"/>
		<script type="text/javascript" src="./js/app.js"></script>
		<script type="text/javascript" src="./js/functions.js"></script>
	</head>
	<body class="loginFormBody">
		@if(session()->has('error'))
			<div class="alert alert-danger alertLogin alert-dismissible fade show" role="alert">
				<strong>ERROR!</strong> {{session()->get('error')}}
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		@endif
		<div class="login-form loginForm">
			<form action="./login" method="POST">
				@csrf
				<img class="loginFormImg" src="./images/logo.png"/>      
				<div class="form-group">
					<input type="text" name="username" id="username" class="form-control" placeholder="Usuario" required="required">
				</div>
				<div class="form-group">
					<input type="password" name="password" id="password" class="form-control" placeholder="Clave" required="required">
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-dark centerButton">Entrar</button>
				</div>      
			</form>
		</div>
	</body>
</html>