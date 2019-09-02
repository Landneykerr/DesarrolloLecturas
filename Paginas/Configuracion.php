<?php
	session_start();
	include_once(dirname(__FILE__)."/../Clases/ClassUsuario.php");
	include_once(dirname(__FILE__)."/../Clases/ClassConfiguracion.php");
	include_once(dirname(__FILE__)."/../Clases/ClassParametros.php");

	$FcnUsuario 		= new Usuario();
	$FcnConfiguracion	= new Configuracion();
	$FcnParametros		= new ClassParametros();


	if(!isset($_SESSION['Accesos']['Configuracion']))
		header("Location: ../index.php");
?>


<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="../../favicon.ico">

		<title>Dashboard Template for Bootstrap</title>

		<!-- Bootstrap core CSS -->
		<link rel="stylesheet" type="text/css" href="../FrameWork/bootstrap-3.3.5-dist/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../FrameWork/bootstrap-3.3.5-dist/css/bootstrap-theme.min.css">
		<link rel="stylesheet" type="text/css" href="../FrameWork/dataTables/css/dataTables.bootstrap.css">
		<link rel="stylesheet" type="text/css" href="../FrameWork/css/theme.css">
		

		<!-- Bootstrap core JS -->
		<script type="text/javascript" src="../FrameWork/bootstrap-3.3.5-dist/js/jquery.js"></script>
		<script type="text/javascript" src="../FrameWork/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="../FrameWork/dataTables/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="../FrameWork/dataTables/js/dataTables.bootstrap.js"></script>		
		<script type="text/javascript" src="../FrameWork/dataTables/js/hTablas.js"></script>
		<script type="text/javascript" src="../FrameWork/jquery/FuncionesRepetitivas.js"></script>
		
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				oTable11 = CrearDataTable("PaginasTable",true,true,true);
				oTable12 = CrearDataTable("ModulosTable",true,true,true);
				oTable13 = CrearDataTable("UsuariosTable",true,true,true);
				oTable14 = CrearDataTable("AccesosTable",false,false,false);


				$('#PaginasTable tbody').on( 'click', 'tr', function () {
					if ( $(this).hasClass('selected') ) {
						$(this).removeClass('selected');
					}else {
						oTable11.$('tr.selected').removeClass('selected');
						$(this).addClass('selected');
					}
					ConsultarModulosPagina();
				});


				$('#ModulosTable tbody').on( 'click', 'tr', function () {
					$(this).toggleClass('selected');
				});


				$('#UsuariosTable tbody').on( 'click', 'tr', function () {
					if ( $(this).hasClass('selected') ) {
						$(this).removeClass('selected');
					}else {
						oTable13.$('tr.selected').removeClass('selected');
						$(this).addClass('selected');
					}
					$("#PaginaAccesos").val(-1);
					ConsultarAccesoModulos();
				});


				$("#ConsultarPaginas").click(function(){
					ConsultarPaginas();
				})


				$("#CrearPagina").click(function(){
					var SendInformacionN =	$.ajax({    async:  	true,
														type:   	"POST",
														url:    	"../Ajax/AjaxConfiguracion.php",
														data:   {   Peticion: 		"CrearPagina",
																	Pagina: 		$("#PaginaNombre").val(),
																	Descripcion: 	$("#PaginaDescripcion").val()
																},
														success:function(data){
															if(data==1){
																$(".DatosPagina").val("");
																alert("Pagina creada correctamente.");
																ConsultarPaginas();
															}else{
																alert("Error al crear la pagina.");
															}
														}
													});

					SendInformacionN.fail(function(jqXHR, textStatus) {
						alert( "Error en la consulta de usuarios." );
					});
				})


				$("#EliminarPagina").click(function(){
					var anSelected = fnGetSelected(oTable11);
					var SendInformacionN =	$.ajax({    async:  	true,
														type:   	"POST",
														dataType: 	"json",
														url:    	"../Ajax/AjaxConfiguracion.php",
														data:   {   Peticion: 	"EliminarPagina",
																	Pagina: 	oTable11.fnGetData(anSelected[0],0)
																},
														success:function(data){
															ConsultarPaginas();
														}
													});

					SendInformacionN.fail(function(jqXHR, textStatus) {
						alert( "Error en la consulta de archivos incompletos." );
					});
				})


				$("#CrearModulo").click(function(){
					var anSelected = fnGetSelected(oTable11);
					var SendInformacionN =	$.ajax({    async:  	true,
														type:   	"POST",
														url:    	"../Ajax/AjaxConfiguracion.php",
														data:   {   Peticion: 		"CrearModulo",
																	Pagina: 		oTable11.fnGetData(anSelected[0],0),
																	Modulo: 		$("#ModuloNombre").val(),
																	Titulo: 		$("#ModuloTitulo").val(),
																	Descripcion: 	$("#ModuloDescripcion").val()
																},
														success:function(data){
															if(data==1){
																$(".DatosModulo").val("");
																alert("Modulo creado correctamente.");
																ConsultarModulosPagina();
															}else{
																alert("Error al crear el modulo.");
															}
														}
													});

					SendInformacionN.fail(function(jqXHR, textStatus) {
						alert( "Error en la consulta de usuarios." );
					});
				})


				$("#EliminarModulos").click(function(){
					var anSelected 		= fnGetSelected(oTable11);
					var InfTablaModulos = InfTablaSelectedToJSON(oTable12,"ListaModulos",["Modulo"],[0]);
					var SendInformacionN=	$.ajax({    async:  	true,
														type:   	"POST",
														dataType: 	"json",
														url:    	"../Ajax/AjaxConfiguracion.php",
														data:   {   Peticion: 	"EliminarModulos",
																	Pagina: 	oTable11.fnGetData(anSelected[0],0),
																	Modulos: 	InfTablaModulos 
																},
														success:function(data){
															ConsultarModulosPagina();
														}
													});

					SendInformacionN.fail(function(jqXHR, textStatus) {
						alert( "Error en la consulta de archivos incompletos." );
					});
				})


				$("#ConsultarUsuarios").click(function(){
					ConsultarUsuarios();
				})

				$("#EliminarUsuarios").click(function(){
					EliminarUsuarios();
				})


				$("#CrearUsuario").click(function(){
					CrearUsuario();
				});


				$("#PaginaAccesos").change(function(){
					ConsultarAccesoModulos();
				})


				$('#AccesosTable tbody').on( 'click', 'tr', function () {
					if ( $(this).hasClass('selected') ) {
						var anSelected = fnGetSelected(oTable14);
						if(oTable14.fnGetData(anSelected[0],2)=="Si"){
							oTable14.fnUpdate("No", anSelected[0], 2);
						}else{
							oTable14.fnUpdate("Si", anSelected[0], 2);
						}
						$(this).removeClass('selected');

					}else {
						oTable14.$('tr.selected').removeClass('selected');
						$(this).addClass('selected');
					}
				});


				$("#GuardarAccesosUsuario").click(function(){
					GuardarAccesosUsuario();
				})


				function ConsultarPaginas(){
					var SendInformacionN =	$.ajax({    async:  	true,
														type:   	"POST",
														dataType: 	"json",
														url:    	"../Ajax/AjaxConfiguracion.php",
														data:   {   Peticion: 	"ConsultarPaginas"
																},
														success:function(data){
															MostrarTabla(oTable11,data,["pagina","descripcion"]);
														}
													});

					SendInformacionN.fail(function(jqXHR, textStatus) {
						alert( "Error en la consulta de usuarios." );
					});
				}

				function ConsultarModulosPagina(){
					var SendInformacionN =	$.ajax({    async:  	true,
														type:   	"POST",
														dataType: 	"json",
														url:    	"../Ajax/AjaxConfiguracion.php",
														data:   {   Peticion: 	"ConsultarModulos",
																	Pagina: 	GetColumnOfRowSelected(oTable11,0)
																},
														success:function(data){
															MostrarTabla(oTable12,data);
														}
													});

					SendInformacionN.fail(function(jqXHR, textStatus) {
						alert( "Error en la consulta de usuarios." );
					});
				}

				function ConsultarUsuarios(){
					var SendInformacionN =	$.ajax({    async:  	true,
														type:   	"POST",
														dataType: 	"json",
														url:    	"../Ajax/AjaxConfiguracion.php",
														data:   {   Peticion: 	"ConsultarUsuarios"
																},
														success:function(data){
															MostrarTabla(oTable13,data);
														}
													});

					SendInformacionN.fail(function(jqXHR, textStatus) {
						alert( "Error en la consulta de usuarios." );
					});
				}

				function EliminarUsuarios(){
					var InfTablaUsuarios 	= InfTablaSelectedToJSON(oTable13,"ListaUsuarios",["Usuario"],[2]);
					var SendInformacionN =	$.ajax({    async:  	true,
														type:   	"POST",
														dataType: 	"json",
														url:    	"../Ajax/AjaxConfiguracion.php",
														data:   {   Peticion: 	"EliminarUsuarios",
																	Usuarios: 	InfTablaUsuarios 
																},
														success:function(data){
															alert("Usuario Eliminado Correctamente.");
														}
													});

					SendInformacionN.fail(function(jqXHR, textStatus) {
						alert( "Error en la consulta de archivos incompletos." );
					});
				}

				function CrearUsuario(){
					if (($("#NombreUsuario").val()=="")||($("#CedulaUsuario").val()=="")||($("#NicknameUsuario").val()=="")||($("#ContrasenaUsuario").val()=="")){
						alert("Datos Incompletos.");
					}else if ($("#ContrasenaUsuario").val()!=$("#ReContrasenaUsuario").val()){
						alert("Error de coincidencia de contraseñas.");
					}else{   
						$.ajax({    async:  false,
									type:   "POST",
									url:    "../Ajax/AjaxConfiguracion.php",
									data:   {   Peticion: 	"CrearUsuario",
												Nombre:  	$("#NombreUsuario").val(),
												Cedula: 	$("#CedulaUsuario").val(),
												Correo: 	$("#CorreoUsuario").val(),
												Usuario: 	$("#NicknameUsuario").val(),
												Contrasena:	$("#ContrasenaUsuario").val()
											},success: function(data){ 	
												if(data==1){
													$(".DatosUsuario").val(""); 
													alert('Usuario Creado Correctamente.');
													ConsultarUsuarios();
												}else{
													alert('Error, no se pudo crear el tecnico.')
												}						
											}
						});
					}
				}

				function ConsultarAccesoModulos(){
					var SendInformacionN = 	$.ajax({ async: 		false,
													type:   	"POST",
													dataType: 	"json",
													url:    	"../Ajax/AjaxConfiguracion.php",
													data:   {   Peticion: 	"ConsultarAccesos",
																Usuario:  	GetColumnOfRowSelected(oTable13, 2),
																Pagina: 	$("#PaginaAccesos option:selected").val()
													},success: function(data){ 	
														MostrarTabla(oTable14,data);			
													}
											});

					SendInformacionN.fail(function(jqXHR, textStatus) {
						alert( "Error en la consulta de accesos." );
					});
				}

				function GuardarAccesosUsuario(){
					var InfTablaAccesos	= InfTablaToJSON(oTable14,"Accesos",["id","valor"],[0,2]);
					var SendInformacion =   $.ajax({    async:  	false,  
														type:  	 	"POST",
														url:    	"../Ajax/AjaxConfiguracion.php",
														data:   {	Peticion: 	"GuardarAccesosUsuario",
																	Usuario:  	GetColumnOfRowSelected(oTable13, 2),
																	Accesos: 	InfTablaAccesos 
																},
														success:function(data){
															alert(data);
														}
													});
						
					SendInformacion.fail(function(jqXHR, textStatus) {
						alert( "Error consultando tecnicos.");
					});
				}
			});
		</script>
	</head>

	<body>
		<header>
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-4 col-md-4"><h2>SYPELC - Configuracion</h2></div>
					<div class="col-sm-8 col-md-8">
						<div id="navbar" class="navbar-collapse collapse">
							<ul class="nav navbar-nav navbar-right">
								<?php $FcnUsuario->AccesoPaginas("Configuracion"); ?>
								<li><a href="../index.php">Salir</a></li>
							</ul>
						</div>	
					</div>
				</div>				
			</div>
		</header>

		<div class="container-fluid">
			<div class="col-sm-9 col-md-12 ">	
				<ul class="nav nav-tabs">
					<?php $FcnUsuario->AccesoModulos("Configuracion"); ?>
				</ul>

				<div class="tab-content">
					<?php 
					if(isset($_SESSION['Accesos']['Configuracion']['conf_web'])){ ?>
						<div id="conf_web" class="tab-pane fade" height="100%">
							<div class="row">
								<div class="col-md-6">
									<div class="panel panel-success table-responsive">
										<div class="panel-heading">Admin Paginas</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-md-12">
													<div class="form-inline">
														<div class="form-group">
															<label class="sr-only" for="PaginaNombre">Nombre</label>
															<input class="form-control DatosPagina" id="PaginaNombre" type="text" placeholder="Nombre Pagina">
														</div>

														<div class="form-group">
															<label class="sr-only" for="PaginaDescripcion">Descripcion</label>
															<input class="form-control DatosPagina" id="PaginaDescripcion" type="text" placeholder="Descripcion Pagina"> 
														</div>
												
														<div class="form-group">
															<button id="CrearPagina" type="button" class="btn btn-block btn-success btn-md pull-right">Crear Pagina</button>
														</div>
													</div>	
												</div>					
											</div>
											<br>
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<table id="PaginasTable" class="table table-condensed" cellspacing="0" width="99%">
															<thead>
																<tr class="info"> 
																	<th width="25%">Pagina</th>
																	<th width="75%">Descripcion</th>
																</tr>
															</thead>
															<tbody>							
															</tbody>
														</table>	
													</div>

													<div class="form-group">
														<button id="ConsultarPaginas" type="button" class="btn btn-primary btn-md pull-right">Consultar</button>
														<button id="EliminarPagina" type="button" class="btn btn-danger btn-md pull-left">Eliminar</button>
													</div>
												</div>		
											</div>				
										</div>
									</div>	
								</div> 

								<div class="col-md-6">
									<div class="panel panel-success table-responsive">
										<div class="panel-heading">Admin Modulos</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-md-12">
													<div class = "form-inline">
														<div class="form-group">
															<label class="sr-only" for="ModuloNombre">Nombre Modulo</label>
															<input class="form-control DatosModulo" id="ModuloNombre" type="text" placeholder="Nombre Modulo">
														</div>

														<div class="form-group">
															<label class="sr-only" for="ModuloTitulo">Titulo Modulo</label>
															<input class="form-control DatosModulo" id="ModuloTitulo" type="text" placeholder="Titulo Modulo">
														</div>
													</div>
													<br>
													<div class = "form-inline">
														<div class="form-group">
															<label class="sr-only" for="ModuloDescripcion">Descripcion Modulo</label>
															<input class="form-control DatosModulo" id="ModuloDescripcion" type="text" placeholder="Descripcion Modulo"> 
														</div>
												
														<div class="form-group">
															<button id="CrearModulo" type="button" class="btn btn-block btn-success btn-md">Crear Modulo</button>
														</div>
													</div>
												</div>					
											</div>
											<br>
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<table id="ModulosTable" class="table table-condensed" cellspacing="0" width="99%">
															<thead>
																<tr class="info"> 
																	<th width="25%">Nombre</th>
																	<th width="15%">Titulo</th>
																	<th width="60%">Descripcion</th>
																</tr>
															</thead>
															<tbody>							
															</tbody>
														</table>	
													</div>

													<div class="form-group">
														<button id="EliminarModulos" type="button" class="btn btn-danger btn-md pull-left">Eliminar</button>
													</div>
												</div>		
											</div>				
										</div>
									</div>	
								</div>
							</div>
						</div>
					<?php }
					if(isset($_SESSION['Accesos']['Configuracion']['conf_usuarios'])){ ?>	
						<div id="conf_usuarios" class="tab-pane fade" height="100%" >
							<div class="row">
								<div class="col-md-3">
									<div class="panel panel-success table-responsive">
										<div class="panel-heading">Crear Usuario</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-md-12 formulario_nuevo_usuario">
													<div class="form-group">
														<label class="sr-only" for="CedulaUsuario">Cedula</label>
														<input class="form-control DatosUsuario" id="CedulaUsuario" type="text" placeholder="Cedula">
													</div>

													<div class="form-group">
														<label class="sr-only" for="NombreUsuario">Nombre</label>
														<input class="form-control DatosUsuario" id="NombreUsuario" type="text" placeholder="Nombre"> 
													</div>

													<div class="form-group">
														<label class="sr-only" for="NicknameUsuario">Nickname</label>
														<input class="form-control DatosUsuario" id="NicknameUsuario" type="text" placeholder="Usuario"> 
													</div>

													<div class="form-group">
														<label class="sr-only" for="CorreoUsuario">Correo Electronico</label>
														<input class="form-control DatosUsuario" id="CorreoUsuario" type="email" placeholder="Correo Electronico"> 
													</div>

													<div class="form-group">
														<label class="sr-only" for="ContrasenaUsuario">Contraseña</label>
														<input class="form-control DatosUsuario" id="ContrasenaUsuario" type="password" placeholder="Contraseña"> 
													</div>

													<div class="form-group">
														<label class="sr-only" for="ReContrasenaUsuario">Confirmar Contraseña</label>
														<input class="form-control DatosUsuario" id="ReContrasenaUsuario" type="password" placeholder="Confirmar Contraseña"> 
													</div>
												
													<div class="form-group">
														<button id="CrearUsuario" type="button" class="btn btn-success btn-md pull-right">Crear Usuario WEB</button>
													</div>	
												</div>					
											</div>		
										</div>
									</div>	
								</div> 

								<div class="col-md-6">
									<div class="panel panel-success table-responsive">
										<div class="panel-heading">Consulta de Usuarios WEB</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<table id="UsuariosTable" class="table table-condensed" cellspacing="0" width="99%">
															<thead>
																<tr class="info"> 
																	<th width="40%">Nombre</th>
																	<th width="40%">Correo Electronico</th>
																	<th width="20%">Nickname</th>
																</tr>
															</thead>
															<tbody>							
															</tbody>
														</table>	
													</div>

													<div class="form-group">
														<button id="ConsultarUsuarios" type="button" class="btn btn-primary btn-md pull-right">Consultar Usuarios</button>
														<button id="EliminarUsuarios" type="button" class="btn btn-danger btn-md pull-left">Eliminar Usuario</span>														
														</button>
													</div>
												</div>		
											</div>				
										</div>
									</div>	
								</div> 

								<div class="col-md-3">
									<div class="panel panel-success table-responsive">
										<div class="panel-heading">Accesos Usuarios WEB</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-md-12">
													<div class="form-inline">
														<div class="form-group">
															<label class="control-label" for="PaginaAccesos">Pagina</label>
															<select class="form-control" id="PaginaAccesos">
															<?php
																echo "<option value='-1'>...</option>";	
																$_paginas = json_decode($FcnConfiguracion->ConsultarAccesoPaginas($_SESSION['UserName']));
																foreach($_paginas as $obj){
																	echo "<option value='".$obj->valor."'>".$obj->texto."</option>";											   
																}
															?> 
															</select> 
														</div>
													</div>
												</div>
											</div>


											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<table id="AccesosTable" class="table table-condensed" cellspacing="0" width="99%">
															<thead>
																<tr class="info"> 
																	<th width="10%">Id</th>
																	<th width="70%">Modulo</th>
																	<th width="20%">Acceso</th>
																</tr>
															</thead>
															<tbody>							
															</tbody>
														</table>	
													</div>

													<div class="form-group">
														<button id="GuardarAccesosUsuario" type="button" class="btn btn-warning btn-md pull-right">Guardar Cambios</button>
													</div>
												</div>		
											</div>				
										</div>
									</div>	
								</div>
							</div> 							
						</div>
					<?php } ?>		
				</div>	
			</div>	
		</div>
	</body>
</html>
