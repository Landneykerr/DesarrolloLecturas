<?php
	session_start();
	include_once(dirname(__FILE__)."/../Clases/ClassUsuario.php");
	include_once(dirname(__FILE__)."/../Clases/ClassConfiguracion.php");
	include_once(dirname(__FILE__)."/../Clases/ClassParametros.php");

	$FcnUsuario 		= new Usuario();
	$FcnConfiguracion	= new Configuracion();
	$FcnParametros		= new ClassParametros();

	if(!isset($_SESSION['Accesos']['Digitacion']))
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
		<script type="text/javascript" src="../FrameWork/bootstrap-filestyle-1.2.1/bootstrap-filestyle.min.js"></script>
		<script type="text/javascript" src="../FrameWork/dataTables/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="../FrameWork/dataTables/js/dataTables.bootstrap.js"></script>		
		<script type="text/javascript" src="../FrameWork/dataTables/js/hTablas.js"></script>
		<script type="text/javascript" src="../FrameWork/jquery/FuncionesRepetitivas.js"></script>



		<!-- js para el manejo de las validaciones de lecturas -->
		<script type="text/javascript" src="../FrameWork/js/ValidacionesLectura.js"></script>


		
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				var usuario;			

				function getDescripcionCritica(){
					var SendInformacionN =	$.ajax({    async:  	false,
														type:   	"POST",
														dataType: 	"json",
														url:    	"../Ajax/AjaxDigitacion.php",
														data:   {   Peticion: 	"ConsultarDescripcionCritica",
																	Critica1: 	usuario.medidor[usuario.id_medidor].lectura[0].getCritica(),
																	Critica2: 	usuario.medidor[usuario.id_medidor].lectura[1].getCritica(),
																	Critica3: 	usuario.medidor[usuario.id_medidor].lectura[2].getCritica()
																},
														success:function(data){
															usuario.medidor[usuario.id_medidor].lectura[0].setDescripcionCritica(data['primera']);
															usuario.medidor[usuario.id_medidor].lectura[1].setDescripcionCritica(data['segunda']);
															usuario.medidor[usuario.id_medidor].lectura[2].setDescripcionCritica(data['tercera']);
														}
													});

					SendInformacionN.fail(function(jqXHR, textStatus) {
						alert( "Error en la captura de la descripcio de la critica." );
					});	
				}


				oTable11 = CrearDataTable("TablaCorreccion",true,true,true);
				oTable12 = CrearDataTable("TablaRecuperacionesPendientes",true,true,true);
				oTable13 = CrearDataTableHiperLink("TablaReportesSupervisores",true,true,5);
				oTable14 = CrearDataTable("TablaDigLecturas", true, true, true);



				$('#TablaRecuperacionesPendientes tbody').on( 'click', 'tr', function () {
					$(this).toggleClass('selected');
				});


				$('#TablaCorreccion tbody').on( 'click', 'tr', function () {
					if ( $(this).hasClass('selected') ) {
						$(this).removeClass('selected');
						$(".EdicionCorreccion").attr("disabled",true);
						$("#LecturaCorreccion").val("");
						$("#AnomaliaCorreccion").val(-1);
						$("#MensajeCorreccion").val("");
						$("#FotoCorreccion").val("");
					}else {
						oTable11.$('tr.selected').removeClass('selected');
						$(this).addClass('selected');
						$(".EdicionCorreccion").attr("disabled",false);

						var anSelected = fnGetSelected(oTable11);
						$("#LecturaCorreccion").val(oTable11.fnGetData(anSelected[0],6));
						$("#AnomaliaCorreccion").val(-1);
						$("#MensajeCorreccion").val(oTable11.fnGetData(anSelected[0],9));
					}
				});


				$("#CuentaLectura").focusout(function(){
					
					var SendInformacionN =	$.ajax({    async:  	true,
														type:   	"POST",
														dataType: 	"json",
														url:    	"../Ajax/AjaxDigitacion.php",
														data:   {   Peticion: 		"ConsultarInfCuentaLectura",
																	Mes: 			$("#MesLectura option:selected").val(),
																	Anno: 			$("#AnnoLectura option:selected").val(),
																	Tipo: 			$("#TipoLectura option:selected").val(),
																	Cuenta: 		$("#CuentaLectura").val()
																},
														success:function(data){
															
															usuario = new Usuario(data);															

															usuario.mostrarInformacion($("#MensajeCuentaLectura"), $("#CicloRutaLectura"), $("#MedidorLectura"), $("#UsuarioLectura"), $("#DireccionLectura"), $(".InfBasicaLectura"), $("#GuardarLectura"));															
														}
													});

					SendInformacionN.fail(function(jqXHR, textStatus) {
						alert( "Error en la consulta de los datos basicos de la cuenta." );
					});
				});



				$("#MedidorLectura").change(function(){
					usuario.setIdMedidor($("#MedidorLectura option:selected").val());

					usuario.checkInputLectura($("#tipoEnergia1"), $("#NuevaLectura1"), 0);
					usuario.checkInputLectura($("#tipoEnergia2"), $("#NuevaLectura2"), 1);
					usuario.checkInputLectura($("#tipoEnergia3"), $("#NuevaLectura3"), 2);

					$(".NuevaLectura").val("");
				});


				$(".NuevaLectura").change(function(){
					
					usuario.calcularCritica($("#NuevaLectura1").val(), 0);
					usuario.calcularCritica($("#NuevaLectura2").val(), 1);
					usuario.calcularCritica($("#NuevaLectura3").val(), 2);

					getDescripcionCritica();

					usuario.statusLectura($("#CriticaCuentaLectura1"), 0);
					usuario.statusLectura($("#CriticaCuentaLectura2"), 1);
					usuario.statusLectura($("#CriticaCuentaLectura3"), 2);
				});



				$("#GuardarLectura").click(function(){

					var SendInformacionN =	$.ajax({    async:  	true,
														type:   	"POST",
														url:    	"../Ajax/AjaxDigitacion.php",
														data:   {   Peticion: 		"GuardarDatosLectura",
																	IdProgramacion:	usuario.getIdProgramacion(),
																	TipoLectura: 	usuario.getTipo(),
																	IdSerial1: 		usuario.medidor[usuario.id_medidor].lectura[0].getIdSerial(),
																	Lectura1: 		usuario.medidor[usuario.id_medidor].lectura[0].getLectura(),
																	Critica1: 		usuario.medidor[usuario.id_medidor].lectura[0].getCritica(),
																	IdSerial2: 		usuario.medidor[usuario.id_medidor].lectura[1].getIdSerial(),
																	Lectura2: 		usuario.medidor[usuario.id_medidor].lectura[1].getLectura(),
																	Critica2: 		usuario.medidor[usuario.id_medidor].lectura[1].getCritica(),
																	IdSerial3: 		usuario.medidor[usuario.id_medidor].lectura[2].getIdSerial(),
																	Lectura3: 		usuario.medidor[usuario.id_medidor].lectura[2].getLectura(),
																	Critica3: 		usuario.medidor[usuario.id_medidor].lectura[2].getCritica(),
																	Anomalia: 		$("#AnomaliaLectura option:selected").val(),
																	Mensaje: 		$("#ObservacionLectura").val(),
																	Inspector: 		usuario.getInspector()
																},
														success:function(data){
															if(data == true){
																usuario.limpiarCampos($("#CuentaLectura"), $("#MensajeCuentaLectura"), $("#CicloRutaLectura"), $("#MedidorLectura"), $("#UsuarioLectura"), $("#DireccionLectura"), $(".InfBasicaLectura"), $("#GuardarLectura"), $("#tipoEnergia1"), $("#NuevaLectura1"), $("#CriticaCuentaLectura1"), $("#tipoEnergia2"), $("#NuevaLectura2"), $("#CriticaCuentaLectura2"), $("#tipoEnergia3"), $("#NuevaLectura3"), $("#CriticaCuentaLectura3"), $("#ObservacionLectura"));
															}															
														}
													});

					SendInformacionN.fail(function(jqXHR, textStatus) {
						alert( "Error guardando los datos de lectura." );
					});
				});


				//JQUERY CORRECCIONES
				$("#ConsultarCorreccion").click(function(){
					var SendInformacionN =	$.ajax({    async:  	true,
														type:   	"POST",
														dataType: 	"json",
														url:    	"../Ajax/AjaxDigitacion.php",
														data:   {   Peticion: 		"ConsultaCorreccion",
																	Mes: 			$("#MesCorreccion option:selected").val(),
																	Anno: 			$("#AnnoCorreccion option:selected").val(),
																	TipoBusqueda: 	$("#TipoCorreccion option:selected").val(),
																	DatoBusqueda: 	$("#DatoCorreccion").val()
																},
														success:function(data){
															MostrarTabla(oTable11,data);
														}
													});

					SendInformacionN.fail(function(jqXHR, textStatus) {
						alert( "Error en la consulta de verificaciones." );
					});					
				})


				$("#GuardarCorreccion").click(function(){
					var anSelected = fnGetSelected(oTable11);
					var SendInformacionN =	$.ajax({    async:  	true,
														type:   	"POST",
														url:    	"../Ajax/AjaxDigitacion.php",
														data:   {   Peticion: 		"GuardarCorreccion",
																	IdSerial: 		oTable11.fnGetData(anSelected[0],0),
																	Lectura: 		$("#LecturaCorreccion").val(),
																	Anomalia: 		$("#AnomaliaCorreccion option:selected").val(),
																	Mensaje: 		$("#MensajeCorreccion").val(),
																	Foto:  			$("#FotoCorreccion").val()
																},
														success:function(data){
															alert(data);
														}
													});

					SendInformacionN.fail(function(jqXHR, textStatus) {
						alert( "Error en la consulta de verificaciones." );
					});	
				})


				$("#ConsultarRecuperacion").click(function(){
					ConsultarRecuperacion();
				});

				$("#ConsultarSupervision").click(function(){
					ConsultarSupervision()
				});


				$("#ProcesarRecuperacion").click(function(){   		
					var IdSerial 	= InfTablaSelectedToJSON(oTable12,"Id",["id_serial"],[0]);

					$.ajax({    async:  	false,
								type:   	"POST",
								url:    	"../Ajax/AjaxDigitacion.php",
								data:   {   Peticion: 	"ProcesarRecuperacion",
											Id: 		IdSerial,
											Tipo: 		$("#TipoRecuperacion option:selected").val()
										},success: function(data){ 													
											alert(data);		
											ConsultarRecuperacion();													
										}
					});				
				});


				$("#RechazarRecuperacion").click(function(){   		
					var IdSerial 	= InfTablaSelectedToJSON(oTable12,"Id",["id_serial"],[0]);
					$.ajax({    async:  	false,
								type:   	"POST",
								url:    	"../Ajax/AjaxDigitacion.php",
								data:   {   Peticion: 	"RechazarRecuperacion",
											Id: 		IdSerial,
											Tipo: 		$("#TipoRecuperacion option:selected").val()
										},success: function(data){ 													
											alert(data);	
											ConsultarRecuperacion();														
										}
					});				
				});

				$(".EdicionCorreccion").attr("disabled",true);
				$(".InfBasicaLectura").attr("disabled", true);
				$("#GuardarLectura").attr("disabled", true);	


				$("#CargarSupervision").click(SubirArchivos);


				function SubirArchivos(){
					var archivos = document.getElementById("archivos_supervision");
					var listArchivos = archivos.files;
					var archivos = new FormData();

					alert(listArchivos);
					for(i=0; i<listArchivos.length; i++){
						archivos.append('archivo'+i,listArchivos[i]);
					}

					archivos.append('fecha',$("#FechaSupervision").val());
					archivos.append('descripcion',$("#DescripcionSupervision").val());

					$.ajax({    async:  		false,
								type:   		"POST",
								url:    		"../Ajax/SupervisorMultipleFiles.php",
								contentType: 	false,
								processData: 	false,
								cache: 			false,
								data:   		archivos
					}).done(function(msg){
						alert(msg);
						$(".mensaje_cargue").html(msg);
						$(".mensaje_cargue").show('slow');
						$("#FechaSupervision").val("");
						$("#DescripcionSupervision").val("");
					});
				}



				function ConsultarRecuperacion(){
					var SendInformacionN =	$.ajax({    async:  	true,
														type:   	"POST",
														dataType: 	"json",
														url:    	"../Ajax/AjaxDigitacion.php",
														data:   {   Peticion: 		"ConsultarRecuperacion",
																	Mes: 			$("#MesRecuperacion option:selected").val(),
																	Anno: 			$("#AnnoRecuperacion option:selected").val(),
																	Tipo: 			$("#TipoRecuperacion option:selected").val()
																},
														success:function(data){
															MostrarTabla(oTable12,data);
														}
													});

					SendInformacionN.fail(function(jqXHR, textStatus) {
						alert( "Error en la consulta de verificaciones." );
					});		
				}


				function ConsultarSupervision(){
					var SendInformacionN =	$.ajax({    async:  	true,
														type:   	"POST",
														dataType: 	"json",
														url:    	"../Ajax/AjaxDigitacion.php",
														data:   {   Peticion: 		"ConsultarSupervision",
																	Fecha: 			$("#FechaSupervision").val()
																},
														success:function(data){
															MostrarTabla(oTable13,data);
														}
													});

					SendInformacionN.fail(function(jqXHR, textStatus) {
						alert( "Error en la consulta de verificaciones." );
					});		
				}


			});
		</script>
	</head>

	<body>
		<header>
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-4 col-md-4">
						<h2>SYPELC - Digitacion</h2>	
					</div>
					<div class="col-sm-8 col-md-8">
						<div id="navbar" class="navbar-collapse collapse">
							<ul class="nav navbar-nav navbar-right">
								<?php $FcnUsuario->AccesoPaginas("Digitacion"); ?>
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
					<?php $FcnUsuario->AccesoModulos("Digitacion"); ?>
				</ul>

				<div class="tab-content">
					<?php 
					if(isset($_SESSION['Accesos']['Digitacion']['digitacion_correccion'])){ ?>
						<div id="digitacion_correccion" class="tab-pane fade" height="100%">
							<div class="main row">
								<div class="col-md-2">
									<div class="main row">
										<div class="panel panel-success table-responsive">
											<div class="panel-heading">Datos de Busqueda</div>
											
											<div class="panel-body">
												<div class="form-group">
													<label for="MesCorreccion">Mes</label>
													<select id="MesCorreccion" class="form-control PeriodoCorreccion" >
														<?php
															$_mes = json_decode($FcnParametros->getMes());
															foreach($_mes as $obj){
																echo "<option value='".$obj->numero_mes."'>".$obj->nombre_mes."</option>";											   
															}
														?> 
													</select>
												</div>

												<div class="form-group">
													<label for="AnnoCorreccion">Año</label>
													<select id="AnnoCorreccion" class="form-control PeriodoCorreccion" >
														<?php
															$_anno = json_decode($FcnParametros->getAnno());
															foreach($_anno as $obj){
																echo "<option value='".$obj->anno."'>".$obj->anno."</option>";											   
															}
														?> 
													</select>
												</div>


												<div class="form-group">
													<label for="TipoCorreccion">Buscar Por:</label>
													<select id="TipoCorreccion" class="form-control" >
														<option value="1">Cuenta</option>
														<option value="2">Medidor</option>
													</select>
												</div>

												<div class="form-group">
													<label class="sr-only" for="DatoCorreccion">Datos a Buscar</label>
													<input class="form-control" id="DatoCorreccion" type="text" placeholder="Dato a Buscar">
												</div>
										
												<div class="form-group">
													<button id="ConsultarCorreccion" type="button" class="btn btn-primary btn-md pull-right">Consultar</button>
												</div>
											</div>					
										</div>
									</div>
								</div> 

								<div class="col-md-8">
									<div class="panel panel-success">
										<div class="panel-heading">Estado General Ciclos</div>
										<div class="panel-body">
											<div class="col-md-12">
												<div class="form-group">
													<table id="TablaCorreccion" class="table  table-responsive table-bordered table-condensed" cellspacing="0" width="99%">
														<thead>
															<tr class="info"> 
																<th rowspan="2" width="10%">Id</th>
																<th rowspan="2" width="10%">Ciclo</th>
																<th rowspan="2" width="10%">Cuenta</th>
																<th rowspan="2" width="15%">Medidor</th>
																<th rowspan="2" width="8%">Tipo</th>
																<th colspan="2" width="8%"><center>Lectura</center></th>												
																<th rowspan="2" width="8%">Critica</th>
																<th rowspan="2" width="18%">Anomalia</th>
																<th rowspan="2" width="23%">Mensaje</th>
															</tr>
															<tr class="info">
																<th width="8%">Anterior</th>
																<th width="8%">Actual</th>
															</tr>
														</thead>
														<tbody>							
														</tbody>
													</table>	
												</div>
											</div>		
										</div>					
									</div>
								</div>

								<div class="col-md-2">
									<div class="main row">
										<div class="panel panel-success table-responsive">
											<div class="panel-heading">Datos de Edicion</div>
											
											<div class="panel-body">
												<div class="form-group">
													<label class="sr-only" for="LecturaCorreccion">Lectura</label>
													<input class="form-control EdicionCorreccion" id="LecturaCorreccion" type="text" placeholder="Nueva Lectura">
												</div>

												<div class="form-group">
													<label for="AnomaliaCorreccion">Anomalia</label>
													<select id="AnomaliaCorreccion" class="form-control EdicionCorreccion" >
														<?php
															$_anomalia = json_decode($FcnParametros->getAnomalia(true, "id_anomalia"));
															foreach($_anomalia as $obj){
																echo "<option value='".$obj->id_anomalia."'>".$obj->descripcion."</option>";											   
															}
														?> 
													</select>
												</div>


												<div class="form-group">
													<label class="sr-only" for="MensajeCorreccion">Mensaje</label>
													<textarea class="form-control EdicionCorreccion" id="MensajeCorreccion" type="text" placeholder="Nuevo Mensaje" rows="6"></textarea>
												</div>

												<div class="form-group">
													<label class="sr-only" for="FotoCorreccion">Foto</label>
													<input class="form-control EdicionCorreccion" id="FotoCorreccion" type="text" placeholder="Foto">
												</div>
										
												<div class="form-group">
													<button id="GuardarCorreccion" type="button" class="btn btn-danger btn-md pull-right EdicionCorreccion">Guardar</button>
												</div>
											</div>					
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php }
					if(isset($_SESSION['Accesos']['Digitacion']['digitacion_recuperaciones'])){ ?>
						<div id="digitacion_recuperaciones" class="tab-pane fade" height="100%" >
							<div class="main row">
								<div class="col-md-2">
									<div class="main row">
										<div class="panel panel-success table-responsive">
											<div class="panel-heading">Datos de Busqueda</div>
											
											<div class="panel-body">
												<div class="form-group">
													<label for="MesRecuperacion">Mes</label>
													<select id="MesRecuperacion" class="form-control DatosRecuperacion" >
														<?php
															$_mes = json_decode($FcnParametros->getMes());
															foreach($_mes as $obj){
																echo "<option value='".$obj->numero_mes."'>".$obj->nombre_mes."</option>";											   
															}
														?> 
													</select>
												</div>

												<div class="form-group">
													<label for="AnnoRecuperacion">Año</label>
													<select id="AnnoRecuperacion" class="form-control DatosRecuperacion" >
														<?php
															$_anno = json_decode($FcnParametros->getAnno());
															foreach($_anno as $obj){
																echo "<option value='".$obj->anno."'>".$obj->anno."</option>";											   
															}
														?> 
													</select>
												</div>


												<div class="form-group">
													<label for="TipoRecuperacion">Buscar:</label>
													<select id="TipoRecuperacion" class="form-control DatosRecuperacion" >
														<option value="0">Recuperaciones</option>
														<option value="1">Verificaciones</option>
													</select>
												</div>

												
												<div class="form-group">
													<button id="ConsultarRecuperacion" type="button" class="btn btn-block btn-primary btn-md pull-right">Consultar</button>
												</div>
												<br></br>
												<div class="form-group">
													<button id="ProcesarRecuperacion" type="button" class="btn btn-success btn-md pull-left">Procesar</button>
													<button id="RechazarRecuperacion" type="button" class="btn btn-danger btn-md pull-right">Rechazar</button>
												</div>
											</div>					
										</div>
									</div>
								</div> 

								<div class="col-md-10">
									<div class="panel panel-success table-responsive">
										<div class="panel-heading">Estado General Ciclos 
											<button type="button" class="btn pull-right btn-default btn-sm">Descargar</button>
										</div>
										<div class="panel-body">
											<table id="TablaRecuperacionesPendientes" class="table table-condensed" cellspacing="0" width="100%">
												<thead>
													<tr class="info"> 
														<th width="10%">Id</th>
														<th width="10%">Ciclo</th>
														<th width="15%">Cuenta</th>
														<th width="15%">Medidor</th>
														<th width="15%">Direccion</th>
														<th width="15%">Fecha</th>
														<th width="15%">Lect A</th>
														<th width="15%">Lectura</th>
														<th width="15%">Promedio</th>
														<th width="10%">Anomalia</th>													
														<th width="10%">Critica</th>
														<th width="20%">Mensaje</th>
													</tr>
												</thead>
												<tbody>							
												</tbody>
											</table>	
										</div>					
									</div>
								</div>
							</div>
						</div>
					<?php } 
					if(isset($_SESSION['Accesos']['Digitacion']['digitacion_post_recuperaciones'])){ ?>
						<div id="digitacion_post_recuperaciones" class="tab-pane fade" height="100%" >
							<div class="main row">
								<div class="col-md-2">
									<div class="main row">
										<div class="panel panel-success table-responsive">
											<div class="panel-heading">Datos de Busqueda</div>
											
											<div class="panel-body">
												<div class="form-group">
													<label for="MesRecuperacion">Mes</label>
													<select id="MesRecuperacion" class="form-control DatosRecuperacion" >
														<?php
															$_mes = json_decode($FcnParametros->getMes());
															foreach($_mes as $obj){
																echo "<option value='".$obj->numero_mes."'>".$obj->nombre_mes."</option>";											   
															}
														?> 
													</select>
												</div>

												<div class="form-group">
													<label for="AnnoRecuperacion">Año</label>
													<select id="AnnoRecuperacion" class="form-control DatosRecuperacion" >
														<?php
															$_anno = json_decode($FcnParametros->getAnno());
															foreach($_anno as $obj){
																echo "<option value='".$obj->anno."'>".$obj->anno."</option>";											   
															}
														?> 
													</select>
												</div>


												<div class="form-group">
													<label for="TipoRecuperacion">Buscar:</label>
													<select id="TipoRecuperacion" class="form-control DatosRecuperacion" >
														<option value="0">Recuperaciones</option>
														<option value="1">Verificaciones</option>
													</select>
												</div>

												
												<div class="form-group">
													<button id="ConsultarRecuperacion" type="button" class="btn btn-block btn-primary btn-md pull-right">Consultar</button>
												</div>
												<br></br>
												<div class="form-group">
													<button id="ProcesarRecuperacion" type="button" class="btn btn-success btn-md pull-left">Procesar</button>
													<button id="RechazarRecuperacion" type="button" class="btn btn-danger btn-md pull-right">Rechazar</button>
												</div>
											</div>					
										</div>
									</div>
								</div> 

								<div class="col-md-10">
									<div class="panel panel-success table-responsive">
										<div class="panel-heading">Estado General Ciclos 
											<button type="button" class="btn pull-right btn-default btn-sm">Descargar</button>
										</div>
										<div class="panel-body">
											<table id="TablaRecuperacionesPendientes" class="table table-condensed" cellspacing="0" width="100%">
												<thead>
													<tr class="info"> 
														<th width="10%">Id</th>
														<th width="10%">Ciclo</th>
														<th width="15%">Cuenta</th>
														<th width="15%">Medidor</th>
														<th width="15%">Direccion</th>
														<th width="15%">Lectura</th>
														<th width="10%">Anomalia</th>													
														<th width="10%">Critica</th>
														<th width="20%">Mensaje</th>
													</tr>
												</thead>
												<tbody>							
												</tbody>
											</table>	
										</div>					
									</div>
								</div>
							</div>
						</div>
					<?php } 
					if(isset($_SESSION['Accesos']['Digitacion']['digitacion_supervision'])){ ?>
						<div id="digitacion_supervision" class="tab-pane fade" height="100%" >
							<div class="main row">
								<div class="col-md-3">
									<div class="main row">
										<div class="panel panel-success table-responsive">
											<div class="panel-heading">Datos de Busqueda</div>
											
											<div class="panel-body">
												<div class="form-group">
													<label for="FechaSupervision">Fecha</label>
													<input type="date" class="form-control" id="FechaSupervision" placeholder="DD/MM/YYYY"/>
												</div>

												<div class="form-group">
													<label class="sr-only" for="DescripcionSupervision">Descripcion</label>
													<textarea type="text" class="form-control" id="DescripcionSupervision" placeholder="Descripcion" rows="6"></textarea>
												</div>

												<div class="form-group">
													<label for="archivos_supervision">Archivos a Cargar</label>
													<input type="file" class="filestyle" data-placeholder="No file" id="archivos_supervision" multiple/>
												</div>

												<div class="form-group mensaje_cargue">
												</div>

												<div class="form-group">
													<button id="CargarSupervision" type="button" class="btn btn-danger btn-block btn-md">
														Cargar  <span class="glyphicon glyphicon-floppy-open"></span>
													</button>
													<button id="ConsultarSupervision" type="button" class="btn btn-primary btn-block btn-md">
														Consultar    <span class="glyphicon glyphicon-search"></span>
													</button>
													
												</div>													
											</div>					
										</div>
									</div>
								</div> 

								<div class="col-md-9">
									<div class="panel panel-success table-responsive">
										<div class="panel-heading">Estado General Ciclos 
											<button type="button" class="btn pull-right btn-default btn-sm">Descargar</button>
										</div>
										<div class="panel-body">
											<table id="TablaReportesSupervisores" class="table table-condensed" cellspacing="0" width="100%">
												<thead>
													<tr class="info"> 
														<th width="10%">Id</th>
														<th width="15%">Fecha Registro</th>
														<th width="20%">Fecha</th>
														<th width="30%">Descripcion</th>
														<th width="10%">Usuario</th>
														<th width="15%">Archivo</th>
													</tr>
												</thead>
												<tbody>							
												</tbody>
											</table>	
										</div>					
									</div>
								</div>
							</div>
						</div>
					<?php } 
					if(isset($_SESSION['Accesos']['Digitacion']['digitacion_lecturas'])){ ?>
						<div id="digitacion_lecturas" class="tab-pane fade" height="100%">
							<div class="main row">

								<div class="col-md-3">
									<div class="panel panel-success table-responsive">
										<div class="panel-heading">Datos de Busqueda</div>
										
										<div class="panel-body">
											<div class="form-group">
												<label for="MesLectura">Mes</label>
												<select id="MesLectura" class="form-control PeriodoLectura" >
													<?php
														$_mes = json_decode($FcnParametros->getMes());
														foreach($_mes as $obj){
															echo "<option value='".$obj->numero_mes."'>".$obj->nombre_mes."</option>";											   
														}
													?> 
												</select>
											</div>

											<div class="form-group">
												<label for="AnnoLectura">Año</label>
												<select id="AnnoLectura" class="form-control PeriodoLectura" >
													<?php
														$_anno = json_decode($FcnParametros->getAnno());
														foreach($_anno as $obj){
															echo "<option value='".$obj->anno."'>".$obj->anno."</option>";											   
														}
													?> 
												</select>
											</div>


											<div class="form-group">
												<label for="TipoLectura">Tipo</label>
												<select id="TipoLectura" class="form-control" >
													<option value="R">Recuperacion</option>
													<option value="V">Verificacion</option>
												</select>
											</div>
										</div>					
									</div>
								</div> 

								<div class="col-md-4">
									<div class="panel panel-success">
										<div class="panel-heading">Datos Generales de Cuenta</div>

										<div class="panel-body">

											<div class="row">
												<div class="form-group col-md-6">
													<label class="sr-only" for="CuentaLectura">Cuenta Usuario</label>
													<input class="form-control InfBusquedaLectura" id="CuentaLectura" type="text" placeholder="Cuenta Usuario">
												</div>	

												<div class="form-group col-md-6">
													<input class="form-control InfBasicaLectura" id="MensajeCuentaLectura" type="text" placeholder="">
												</div>	
											</div>	

											<div class="form-group">
												<label class="sr-only" for="CicloRutaLectura">Ciclo Municipio Ruta</label>
												<input class="form-control InfBasicaLectura" id="CicloRutaLectura" type="text" placeholder="Ciclo Municipio Ruta">
											</div>

											<div class="form-group">
												<label for="MedidorLectura">Medidores</label>
											    <select multiple class="form-control" id="MedidorLectura"></select>

											</div>

											<div class="form-group">
												<label class="sr-only" for="UsuarioLectura">Usuario</label>
												<textarea class="form-control InfBasicaLectura" id="UsuarioLectura" rows="3" type="text" placeholder="Nombre Usuario"></textarea>
											</div>

											<div class="form-group">
												<label class="sr-only" for="DireccionLectura">Direccion</label>
												<textarea class="form-control InfBasicaLectura" id="DireccionLectura" rows="3" type="text" placeholder="Direccion"></textarea>
											</div>

										</div>
									</div>	
								</div>	


								<div class="col-md-4">
									<div class="panel panel-success">
										<div class="panel-heading">Informacion de Digitación</div>

										<div class="panel-body">

											<div class="form-group">
												<label for="AnomaliaLectura">Anomalia</label>
												<select id="AnomaliaLectura" class="form-control DigitacionLectura" >
													<?php
														$_anomalia = json_decode($FcnParametros->getAnomalia(false, "id_anomalia"));
														foreach($_anomalia as $obj){
															echo "<option value='".$obj->id_anomalia."'>".$obj->id_anomalia." ".$obj->descripcion."</option>";		
														}
													?> 
												</select>
											</div>


											<div class="row">
												<div class="form-group col-md-6">
													<label class="sr-only" for="NuevaLectura1">Lectura</label>

													<div class="input-group">
														<span class="input-group-addon" id="tipoEnergia1">N/A</span>
														<input class="form-control NuevaLectura" id="NuevaLectura1" type="text" placeholder="Lectura">
													</div>
												</div>	

												<div class="form-group col-md-6">
													<label class="sr-only" for="CriticaCuentaLectura1">Critica</label>
													<input class="form-control InfBasicaLectura" id="CriticaCuentaLectura1" type="text" placeholder="">
												</div>	
											</div>


											<div class="row">
												<div class="form-group col-md-6">
													<label class="sr-only" for="NuevaLectura2">Lectura</label>

													<div class="input-group">
														<span class="input-group-addon" id="tipoEnergia2">N/A</span>
														<input class="form-control NuevaLectura" id="NuevaLectura2" type="text" placeholder="Lectura">
													</div>
												</div>	

												<div class="form-group col-md-6">
													<label class="sr-only" for="CriticaCuentaLectura2">Critica</label>
													<input class="form-control InfBasicaLectura" id="CriticaCuentaLectura2" type="text" placeholder="">
												</div>	
											</div>


											<div class="row">
												<div class="form-group col-md-6">
													<label class="sr-only" for="NuevaLectura3">Lectura</label>

													<div class="input-group">
														<span class="input-group-addon" id="tipoEnergia3">N/A</span>
														<input class="form-control NuevaLectura" id="NuevaLectura3" type="text" placeholder="Lectura">
													</div>
												</div>	

												<div class="form-group col-md-6">
													<label class="sr-only" for="CriticaCuentaLectura3">Critica</label>
													<input class="form-control InfBasicaLectura" id="CriticaCuentaLectura3" type="text" placeholder="">
												</div>	
											</div>	


											
											<div class="form-group">
												<label class="sr-only" for="ObservacionLectura">Observacion</label>
												<textarea class="form-control DigitacionLectura" id="ObservacionLectura" type="text" rows="5" placeholder="Observacion"></textarea>
											</div>

											

											<div class="form-group">
												<button id="GuardarLectura" type="button" class="btn btn-danger btn-md pull-right">Guardar</button>
											</div>
	
										</div>					
									</div>
								</div>

							</div>
						</div>
					<?php }  ?>	
					<div class="clearfix"></div>
				</div>	
			</div>	
		</div>
	</body>
</html>
