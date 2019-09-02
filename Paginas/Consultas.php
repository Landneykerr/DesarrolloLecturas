<?php
	session_start();
	include_once(dirname(__FILE__)."/../Clases/ClassUsuario.php");
	include_once(dirname(__FILE__)."/../Clases/ClassConfiguracion.php");
	include_once(dirname(__FILE__)."/../Clases/ClassParametros.php");

	$FcnUsuario 		= new Usuario();
	$FcnConfiguracion	= new Configuracion();
	$FcnParametros		= new ClassParametros();


	if(!isset($_SESSION['Accesos']['Consultas']))
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
				oTable11 = CrearDataTable("TablaEstadoCiclos",true,true,true);
				oTable12 = CrearDataTable("TablaEstadoRutas",true,true,true);
				oTable13 = CrearDataTable("TablaAporteInspectores",true,true,true);
				oTable14 = CrearDataTable("TablaLecturasPendientes",true,true,true);
				oTable15 = CrearDataTable("TablaLecturasTomadas",true,true,true);
				oTable16 = CrearDataTable("TablaErroresImpresion",true,true,true);
				oTable17 = CrearDataTable("TablaCorrecciones",true,true,true);
				oTable18 = CrearDataTable("TablaEstadoNoLecturas",true,true,true);
				oTable19 = CrearDataTable("TablaPendientesNoLecturas",true,true,true);
				oTable20 = CrearDataTable("TablaTomadasNoLecturas",true,true,true);
				oTable21 = CrearDataTable("TablaPosteriores",true,true,true);


				
				$('#TablaEstadoNoLecturas tbody').on( 'click', 'tr', function () {
					$(this).toggleClass('selected');	
				});


				$('#TerminarNoLecturas').click(function(){
					var listaNoLecturas = InfTablaSelectedToJSON(oTable18,"IdNoLecturas",["id"],[0]);	

					$.ajax({ 	async: 		false, 
								type: 		"POST", 
								url: 		"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"TerminarRutasNoLecturas", 
												IdNoLecturas: 	listaNoLecturas
											}, 
								success: function(data){ 	
									alert(data);
								} 
							});
				});




				$('#TablaEstadoCiclos tbody').on( 'click', 'tr', function () {
					$(this).toggleClass('selected');

					var listaCiclos = InfTablaSelectedToJSON(oTable11,"CiclosSeleccionados",["ciclo"],[0]);			        
					$.ajax({ 	async: 		false, 
								type: 		"POST", 
								dataType: 	"json", 
								url: 		"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaRutasCiclo", 
												Mes: 			$("#MesConsulta option:selected").val(),
												Anno: 			$("#AnnoConsulta option:selected").val(),
												Ciclos: 		listaCiclos
											}, 
								success: function(data){ 	
									MostrarTabla(oTable12,data);
								} 
							});
				});


				$('#TablaEstadoRutas tbody').on( 'click', 'tr', function () {
					$(this).toggleClass('selected');
					var listaRutas = InfTablaSelectedToJSON(oTable12,"RutasSeleccionadas",["ruta"],[0]);	
					$.ajax({ 	async: 		true, 
								type: 		"POST", 
								dataType: 	"json", 
								url: 		"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaRutasEstado", 
												Mes: 			$("#MesConsulta option:selected").val(),
												Anno: 			$("#AnnoConsulta option:selected").val(),
												Rutas: 			listaRutas
											}, 
								success: function(data){ 	
									MostrarTabla(oTable13,data['AporteInspectores']);
									MostrarTabla(oTable14,data['ClientesPendientes']);
								} 
							});	
				});


				$('#TablaAporteInspectores tbody').on( 'click', 'tr', function () {
					$(this).toggleClass('selected');
					var listaInspectores = InfTablaSelectedToJSON(oTable13,"InspectoresSeleccionados",["ruta","inspector"],[0,1]);	
					$.ajax({ 	async: 		true, 
								type: 		"POST", 
								dataType: 	"json", 
								url: 		"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaLecturasTomadas", 
												Mes: 			$("#MesConsulta option:selected").val(),
												Anno: 			$("#AnnoConsulta option:selected").val(),
												Inspectores:	listaInspectores
											}, 
								success: function(data){ 	
									MostrarTabla(oTable15,data['ClientesLeidos']);
								} 
							});	
				});


				$("#ConsultaGeneralCiclos").click(function(){
					$.ajax({ 	async: 		false, 
								type: 		"POST", 
								dataType: 	"json", 
								url: 		"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaPeriodo", 
												Mes: 			$("#MesConsulta option:selected").val(),
												Anno: 			$("#AnnoConsulta option:selected").val()
											}, 
								success: function(data){ 
									MostrarTabla(oTable11,data);
								} 
							});
				});


				$("#ConsultaNoLecturas").click(function(){
					$.ajax({ 	async: 		false, 
								type: 		"POST", 
								dataType: 	"json", 
								url: 		"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaPeriodoNoLecturas", 
												Mes: 			$("#MesNoLecturas option:selected").val(),
												Anno: 			$("#AnnoNoLecturas option:selected").val()
											}, 
								success: function(data){ 
									MostrarTabla(oTable18,data);
								} 
							});
				})

				$("#ConsultaConsolidado").click(function(){
					var Ciclo = GetColumnOfRowSelected(oTable11,0);
					url = "../Excel/DescargaConsolidado.php?Mes="+$("#MesConsulta option:selected").val()+"&Anno="+$("#AnnoConsulta option:selected").val()+"&Ciclo="+Ciclo;	
					window.open(url, '_blank');
					return false;
				});

				$("#ConsultaConsolidadoGPS").click(function(){
					var Ciclo = GetColumnOfRowSelected(oTable11,0);
					url = "../Excel/DescargaConsolidadoGPS.php?Mes="+$("#MesConsulta option:selected").val()+"&Anno="+$("#AnnoConsulta option:selected").val()+"&Ciclo="+Ciclo;	
					window.open(url, '_blank');
					return false;
				});


				$("#ConsultaConsolidadoEncrypt").click(function(){
					var Ciclo = GetColumnOfRowSelected(oTable11,0);
					url = "../Excel/DescargaConsolidadoEncrypt.php?Mes="+$("#MesConsulta option:selected").val()+"&Anno="+$("#AnnoConsulta option:selected").val()+"&Ciclo="+Ciclo;	
					window.open(url, '_blank');
					return false;
				});


				$("#ConsultaErroresImpresion").click(function(){
					$.ajax({ 	async: 		false, 
								type: 		"POST", 
								dataType: 	"json", 
								url: 		"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaErroresImpresion", 
												Fecha: 			$("#FechaErroresImpresion").val()
											}, 
								success: function(data){ 
									MostrarTabla(oTable16,data);
								} 
							});
				});


				$("#ConsultaCorrecciones").click(function(){
					$.ajax({ 	async: 		false, 
								type: 		"POST", 
								dataType: 	"json", 
								url: 		"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaCorrecciones", 
												Ciclo: 			$("#CicloCorreccion option:selected").val(),
												Mes: 			$("#MesCorreccion option:selected").val(),
												Anno: 			$("#AnnoCorreccion option:selected").val()
											}, 
								success: function(data){ 
									MostrarTabla(oTable17,data);
								} 
							});
				});


				$("#DescargaCorrecciones").click(function(){
					var Ciclo = GetColumnOfRowSelected(oTable11,0);
					url = "../Excel/DescargaCorrecciones.php?Mes="+$("#MesCorreccion option:selected").val()+"&Anno="+$("#AnnoCorreccion option:selected").val()+"&Ciclo="+$("#CicloCorreccion option:selected").val();	
					window.open(url, '_blank');
					return false;
				});

				$("#ConsultarTiempos").click(function(){
					var ciclo 	= GetColumnOfRowSelected(oTable11,0);
					var rutaSeleccionada 	= GetColumnOfRowSelected(oTable12,0).split("-");
					var mes 	= $("#MesConsulta option:selected").val();
					var anno 	= $("#AnnoConsulta option:selected").val();										
					var municipio = rutaSeleccionada[1];
					var ruta = rutaSeleccionada[2];				

					url = "../Excel/DescargarTiempos.php?Mes="+mes+"&Anno="+anno+"&Ciclo="+ciclo+"&Municipio="+municipio+"&Ruta="+ruta;
					window.open(url, '_blank');
					return false;
				});

				$("#ConsultarPosterior").click(function(){

					var Cuenta = $("#CuentaPosterior").val();
					url = "../Excel/DescargaPosterior.php?Cuenta="+Cuenta;	
					window.open(url, '_blank');
					return false;
					
				});

				$("#DescargaTodosCiclos").click(function(){
					
					url = "../Excel/DescargaTodosConsolidados.php?Mes="+$("#MesCorreccion option:selected").val()+"&Anno="+$("#AnnoCorreccion option:selected").val();
					window.open(url, '_blank');
					return false;					
				});


			});
		</script>
	</head>

	<body>
		<header>
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-4 col-md-4"><h2>SYPELC - Consultas</h2></div>
					<div class="col-sm-8 col-md-8">
						<div id="navbar" class="navbar-collapse collapse">
							<ul class="nav navbar-nav navbar-right">
								<?php $FcnUsuario->AccesoPaginas("Reportes"); ?>
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
					<?php $FcnUsuario->AccesoModulos("Consultas"); ?>
				</ul>

				<div class="tab-content">
					<?php 
					if(isset($_SESSION['Accesos']['Consultas']['consultas_general'])){ ?>
						<div id="consultas_general" class="tab-pane fade" height="100%">
							<div class="row">
								<div class="col-md-2">
									<div class="panel panel-success table-responsive">
										<div class="panel-heading">Periodo Consulta</div>						
										<div class="panel-body">
											<div class="form-group">
												<label for="MesConsulta">Mes</label>
												<select id="MesConsulta" class="form-control" >
													<?php
														$_mes = json_decode($FcnParametros->getMes());
														foreach($_mes as $obj){
															echo "<option value='".$obj->numero_mes."'>".$obj->nombre_mes."</option>";											   
														}
													?> 
												</select>
											</div>

											<div class="form-group">
												<label for="AnnoConsulta">Año</label>
												<select id="AnnoConsulta" class="form-control" >
													<?php
														$_anno = json_decode($FcnParametros->getAnno());
														foreach($_anno as $obj){
															echo "<option value='".$obj->anno."'>".$obj->anno."</option>";											   
														}
													?> 
												</select>
											</div>

											<div class="form-group">
												<button id="ConsultaGeneralCiclos" type="button" class="btn btn-primary btn-block btn-md">
													Consultar    <span class="glyphicon glyphicon-search"></span>
												</button>
												<button id="ConsultaConsolidado" type="button" class="btn btn-success btn-block btn-md">
													Consolidado  <span class="glyphicon glyphicon-save"></span>
												</button>
												<button id="ConsultaConsolidadoGPS" type="button" class="btn btn-danger btn-block btn-md">
													ConsolidadoGPS  <span class="glyphicon glyphicon-save"></span>
												</button>
												<!--<button id="ConsultaConsolidadoEncrypt" type="button" class="btn btn-danger btn-block btn-md">
													Encriptado  <span class="glyphicon glyphicon-save"></span>
												</button>-->
											</div>														
										</div>
									</div>	
								</div>

								<div class="col-md-3">
									<div class="panel panel-success table-responsive">
										<div class="panel-heading">Estado General Ciclos</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<table id="TablaEstadoCiclos" class="table table-condensed" cellspacing="0" width="99%">
															<thead>
																<tr class="info"> 
																	<th width="25%">Ciclo</th>
																	<th width="25%">Total</th>
																	<th width="25%">Leidas</th>
																	<th width="25%">Pend.</th>
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
								</div>
								
								<div class=" col-md-7">
									<div class="panel panel-primary table-responsive">
										<div class="panel-heading clearfix">
											<h3 class="panel-title pull-left" style="padding-top: 7.5px;">Estado General Rutas</h3>
											<div class="btn-group pull-right">
												<a href="#" id="ConsultarTiempos" class="btn btn-info btn-sm">ConsultarTiempos</a>											
											</div>											
										</div>										
										<div class="panel-body">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<table id="TablaEstadoRutas" class="table table-condensed" cellspacing="0" width="99%">
															<thead>
																<tr class="warning"> 
																	<th width="20%">Ruta</th>
																	<th width="30%">Inspector</th>
																	<th width="10%">Total</th>
																	<th width="10%">Leidas</th>
																	<th width="10%">Pendi.</th>
																	<th width="10%">Est.</th>
																	<th width="10%">Rendimiento</th>
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
								</div>	
							</div>

							<div class="row">
								<div class=" col-md-5">
									<div class="panel panel-info table-responsive">
										<div class="panel-heading">Aporte De Inspectores A Rutas</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<table id="TablaAporteInspectores" class="table table-condensed" cellspacing="0" width="99%">
															<thead>
																<tr class="danger"> 
																	<th width="20%">Ruta</th>
																	<th width="15%">Cod</th>
																	<th width="45%">Nombre</th>
																	<th width="20%">Leidas</th>
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
								</div>
										
								<div class=" col-md-7">
									<div class="panel panel-danger table-responsive">
										<div class="panel-heading">Clientes Pendientes De Toma De Lectura</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<table id="TablaLecturasPendientes" class="table table-condensed" cellspacing="0" width="99%">
															<thead>
																<tr class="primary"> 
																	<th width="15%">Ruta</th>
																	<th width="15%">Cuenta</th>
																	<th width="15%">Medidor</th>
																	<th width="30%">Nombre</th>
																	<th width="25%">Direccion</th>
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
								</div>	
							</div>

							<div class="row">
								<div class=" col-md-12">
									<div class="panel panel-warning table-responsive">
										<div class="panel-heading">Lista de Clientes A Los Cuales Ya Se Realizo Toma De Lectura</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<table id="TablaLecturasTomadas" class="table table-bordered table-condensed" cellspacing="0" width="99%">
															<thead>
																<tr> 
																	<th width="8%">Ruta</th>
																	<th width="8%">Cuenta</th>
																	<th width="15%">Medidor</th>
																	<th width="30%">Nombre</th>
																	<th width="30%">Direccion</th>
																	<th width="8%">Lectura</th>
																	<th width="10%">Hora Toma</th>
																	<th width="10%">Hora Recep.</th>
																	<th width="15%">Anomalia</th>
																	<th width="15%">Mensaje</th>
																	<th width="8%">Critica</th>
																	<th width="8%">Insp.</th>
																	<th width="8%">GPS</th>
																	<th width="8%">Magna</th>
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
								</div>	
							</div>
						</div>		
					<?php } 
					if(isset($_SESSION['Accesos']['Consultas']['consultas_no_lecturas'])){ ?>
						<div id="consultas_no_lecturas" class="tab-pane fade" height="100%">
							<div class="row">
								<div class="col-md-2">
									<div class="panel panel-success">
										<div class="panel-heading">Periodo Consulta</div>						
										<div class="panel-body">
											<div class="form-group">
												<label for="MesNoLecturas">Mes</label>
												<select id="MesNoLecturas" class="form-control" >
													<?php
														$_mes = json_decode($FcnParametros->getMes());
														foreach($_mes as $obj){
															echo "<option value='".$obj->numero_mes."'>".$obj->nombre_mes."</option>";											   
														}
													?> 
												</select>
											</div>

											<div class="form-group">
												<label for="AnnoNoLecturas">Año</label>
												<select id="AnnoNoLecturas" class="form-control" >
													<?php
														$_anno = json_decode($FcnParametros->getAnno());
														foreach($_anno as $obj){
															echo "<option value='".$obj->anno."'>".$obj->anno."</option>";											   
														}
													?> 
												</select>
											</div>

											<div class="form-group">
												<button id="ConsultaNoLecturas" type="button" class="btn btn-primary btn-block btn-md">
													Consultar    <span class="glyphicon glyphicon-search"></span>
												</button>
												
												<button id="TerminarNoLecturas" type="button" class="btn btn-danger btn-block btn-md">
													Terminar    <span class="glyphicon glyphicon-remove"></span>
												</button>
											</div>														
										</div>
									</div>	
								</div>

								<div class=" col-md-10">
									<div class="panel panel-primary table-responsive">
										<div class="panel-heading">Estado General Verificaciones & Recuperaciones</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<table id="TablaEstadoNoLecturas" class="table table-condensed" cellspacing="0" width="99%">
															<thead>
																<tr class="warning"> 
																	<th width="10%">Id</th>
																	<th width="20%">Ruta</th>
																	<th width="42%">Inspector</th>
																	<th width="7%">Total</th>
																	<th width="7%">Leidas</th>
																	<th width="7%">Pendi.</th>
																	<th width="7%">Est.</th>
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
								</div>	
							</div>
						</div>		
					<?php }
					if(isset($_SESSION['Accesos']['Consultas']['consultas_correcciones'])){ ?>
						<div id="consultas_correcciones" class="tab-pane fade" height="100%">
							<div class="row">
								<div class="col-md-2">
									<div class="panel panel-success table-responsive">
										<div class="panel-heading">Periodo Consulta</div>						
										<div class="panel-body">
											<div class="form-group">
												<label for="MesCorreccion">Mes</label>
												<select id="MesCorreccion" class="form-control periodo_cargue" >
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
												<select id="AnnoCorreccion" class="form-control periodo_cargue" >
													<?php
														$_anno = json_decode($FcnParametros->getAnno());
														foreach($_anno as $obj){
															echo "<option value='".$obj->anno."'>".$obj->anno."</option>";											   
														}
													?> 
												</select>
											</div>

											<div class="form-group">
												<label for="CicloCorreccion">Ciclo</label>
												<select id="CicloCorreccion" class="form-control" >
													<option value = '-1'>Todos</option>
													<?php
														$_ciclo = json_decode($FcnParametros->getCiclos());
														foreach($_ciclo as $obj){
															echo "<option value='".$obj->id_ciclo."'>".$obj->id_ciclo."</option>";											   
														}
													?>
												</select>
											</div>

											<div class="form-group">
												<button id="ConsultaCorrecciones" type="button" class="btn btn-primary btn-block btn-md">
													Consultar    <span class="glyphicon glyphicon-search"></span>
												</button>
												<button id="DescargaCorrecciones" type="button" class="btn btn-success btn-block btn-md">
													Descargar  <span class="glyphicon glyphicon-save"></span>
												</button>
												<button id="DescargaTodosCiclos" type="button" class="btn btn-warning btn-block btn-md">
													Consolidados  <span class="glyphicon glyphicon-save"></span>
												</button>
											</div>
										</div>
									</div>	
								</div>

								<div class="col-md-10">
									<div class="panel panel-success">
										<div class="panel-heading">Estado General Ciclos</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<table id="TablaCorrecciones" class="table table-reponsive table-condensed table-bordered" cellspacing="0" width="99%">
															<thead>
																<tr class="info"> 
																	<th rowspan="2" width="10%">Ciclo</th>
																	<th rowspan="2" width="15%">Medidor</th>
																	<th rowspan="2" width="10%">Cuenta</th>
																	<th rowspan="2" width="10%">Inspector</th>
																	<th colspan="5" width="10%"><center>Inf Base</center></th>
																	<th colspan="5" width="10%"><center>Inf Correccion</center></th>
																	<th rowspan="2" width="10%">Analista</th>
																	<th rowspan="2" width="10%">Tipo</th>
																	<th rowspan="2" width="15%">Fecha Correccion</th>
																</tr>
																<tr class="info"> 
																	<th>Lectura</th>
																	<th>Anomalia</th>
																	<th>Mensaje</th>
																	<th>Fecha</th>
																	<th>Foto</th>
																	<th>Lectura</th>
																	<th>Anomalia</th>
																	<th>Mensaje</th>
																	<th>Fecha</th>
																	<th>Foto</th>
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
								</div>
							</div>
						</div>		
					<?php } 
					if(isset($_SESSION['Accesos']['Consultas']['consultas_errores_impresion'])){ ?>
						<div id="consultas_errores_impresion" class="tab-pane fade" height="100%">
							<div class="row">
								<div class="col-md-2">
									<div class="panel panel-success table-responsive">
										<div class="panel-heading">Consulta</div>						
										<div class="panel-body">
											<div class="form-group">
												<label for="FechaErroresImpresion">Fecha de Consulta</label>
												<input type="date" class="form-control" id="FechaErroresImpresion" placeholder="Fecha Consulta"/>
											</div>

											<div class="form-group">
												<button id="ConsultaErroresImpresion" type="button" class="btn btn-primary btn-block btn-md">
													Consultar    <span class="glyphicon glyphicon-search"></span>
												</button>
											</div>					
										</div>
									</div>	
								</div>

								<div class=" col-md-10">
									<div class="panel panel-primary table-responsive">
										<div class="panel-heading">Resumen Errores de Impresion</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<table id="TablaErroresImpresion" class="table table-condensed table-bordered" cellspacing="0" width="99%">
															<thead>
																<tr class="warning"> 
																	<th width="10%">Cuenta</th>
																	<th width="30%">Inspector</th>
																	<th width="30%">Error</th>
																	<th width="15%">Fecha Impresion</th>
																	<th width="15%">Fecha Recepcion.</th>
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
								</div>	
							</div>
						</div>		
					<?php } if(isset($_SESSION['Accesos']['Consultas']['cuenta_posteriores'])){ ?>
						<div id="cuenta_posteriores" class="tab-pane fade" height="100%">
							<div class="row">
								<div class="col-md-3">
									<div class="panel panel-success table-responsive">
										<div class="panel-heading">Consulta</div>						
										<div class="panel-body">

											<div class="form-group">                                            	
                                            	<input type="text" class="form-control" placeholder="Cuenta" id="CuentaPosterior">
                                        	</div>

											<div class="form-group">
												<button id="ConsultarPosterior" type="button" class="btn btn-primary btn-block btn-md">
													Consultar    <span class="glyphicon glyphicon-search"></span>
												</button>
											</div>					
										</div>
									</div>	
								</div>

								<div class=" col-md-9">
									<div class="panel panel-primary table-responsive">
										<div class="panel-heading">Cuentas Anteriores y Posteriores</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<table id="TablaPosteriores" class="table table-condensed table-bordered" cellspacing="0" width="99%">
															<thead>
																<tr class="warning"> 
																	<th width="10%">Id</th>
																	<th width="10%">Cuenta</th>																	
																	<th width="20%">Nombre</th>
																	<th width="20%">Direccion</th>
																	<th width="20%">Medidor</th>
																	<th width="10%">Fecha Toma</th>
																	<th width="10%">Lector</th>
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
								</div>	
							</div>
						</div>		
					<?php }?>
			</div>
		</div>
	</body>
</html>
