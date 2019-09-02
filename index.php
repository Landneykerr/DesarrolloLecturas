<?php
	include_once("Clases/ClassUsuario.php");

	$MyUsuario = new Usuario();
	$MySession = session_start();

	unset($_SESSION['UserName']);
	unset($_SESSION['NombreCompleto']);
	unset($_SESSION['Nivel']);
	unset($_SESSION['Accesos']);

	if($_POST){             
		if(($_POST['username']!="")&&($_POST['password']!="")){  
			if($MyUsuario->LogginUsuario($_POST['username'],$_POST['password'])){
				header("Location: Paginas/Consultas.php"); 
			}else{  
				echo '<div class="alert alert-danger alert-dismissable">
  						<strong>¡ERROR!</strong> No es posible iniciar sesion, verifique el usuario y la contraseña.
					</div>';
			}
		}else{   
			echo '<div class="alert alert-danger alert-dismissable">
  						<strong>¡ERROR!</strong> Debe ingresar los datos de usuario y/o contraseña.
					</div>';
		}
	}

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="../../favicon.ico">

		<!-- Bootstrap core CSS -->
		<link rel="stylesheet" type="text/css" href="FrameWork/bootstrap-3.3.5-dist/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="FrameWork/bootstrap-3.3.5-dist/css/bootstrap-theme.min.css">
		<link rel="stylesheet" type="text/css" href="FrameWork/dataTables/css/dataTables.bootstrap.css">
		<link rel="stylesheet" type="text/css" href="FrameWork/css/theme.css">
		

		<!-- Bootstrap core JS -->
		<script type="text/javascript" src="FrameWork/bootstrap-3.3.5-dist/js/jquery.js"></script>
		<script type="text/javascript" src="FrameWork/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="FrameWork/bootstrap-filestyle-1.2.1/bootstrap-filestyle.min.js"></script>
	</head>

	<body>
		<div class="container">
			<div class="row">
				<div class="col-md-4 col-md-offset-4">
					<div class="login-panel panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Inicio de Sesion</h3>
						</div>
						<div class="panel-body">
							<form role="form" method="post" action="index.php">
								<fieldset>
									<div class="form-group">
										<input class="form-control" placeholder="Usuario" name="username" type="text" autofocus>
									</div>
									<div class="form-group">
										<input class="form-control" placeholder="Contraseña" name="password" type="password" value="">
									</div>
									<!--a href="index.php" class="btn btn-lg btn-success btn-block">Login</a-->
									<input type="submit" name="submit" id="login_submit" value="Ingresar" class="btn btn-lg btn-success btn-block"/>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
