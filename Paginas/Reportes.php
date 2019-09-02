<?php
	session_start();
	include_once(dirname(__FILE__)."/../Clases/ClassUsuario.php");
	include_once(dirname(__FILE__)."/../Clases/ClassConfiguracion.php");
	include_once(dirname(__FILE__)."/../Clases/ClassParametros.php");

	$FcnUsuario 		= new Usuario();
	$FcnConfiguracion	= new Configuracion();
	$FcnParametros		= new ClassParametros();


	if(!isset($_SESSION['Accesos']['Reportes']))
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
				

				$('#TablaEstadoCiclos tbody').on( 'click', 'tr', function () {
					$(this).toggleClass('selected');		
					
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


				
				/** NUEVO PARA LA PRUEBA**/

				$("#DescargarSinLectura").click(function(){
					var Ciclo = GetColumnOfRowSelected(oTable11,0);
					url = "../Excel/DescargaSinLectura.php?Mes="+$("#MesConsulta option:selected").val()+"&Anno="+$("#AnnoConsulta option:selected").val()+"&Ciclo="+Ciclo;	
					window.open(url, '_blank');
					return false;
				});

				$("#DescargarConsumosIguales").click(function(){
					var Ciclo = GetColumnOfRowSelected(oTable11,0);
					url = "../Excel/DescargaConsumosIguales.php?Mes="+$("#MesConsulta option:selected").val()+"&Anno="+$("#AnnoConsulta option:selected").val()+"&Ciclo="+Ciclo;	
					window.open(url, '_blank');
					return false;
				});

				$("#DescargarAnomaliaRepetida").click(function(){
					var Ciclo = GetColumnOfRowSelected(oTable11,0);
					url = "../Excel/DescargaAnomaliasRepetidas.php?Mes="+$("#MesConsulta option:selected").val()+"&Anno="+$("#AnnoConsulta option:selected").val()+"&Ciclo="+Ciclo;	
					window.open(url, '_blank');
					return false;
				});

				$("#DescargarAnomalias").click(function(){
					var Ciclo = GetColumnOfRowSelected(oTable11,0);
					url = "../Excel/DescargaAnomalias1525.php?Mes="+$("#MesConsulta option:selected").val()+"&Anno="+$("#AnnoConsulta option:selected").val()+"&Ciclo="+Ciclo;	
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
					<?php $FcnUsuario->AccesoModulos("Reportes"); ?>
				</ul>

				<div class="tab-content">
					<?php 
					if(isset($_SESSION['Accesos']['Reportes']['reportes_clientes'])){ ?>
						<div id="reportes_clientes" class="tab-pane fade" height="100%">
							<div class="row">
								<div class="col-md-3">
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
												
											</div>														
										</div>
									</div>	
								</div>

								<div class="col-md-5">
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
								
								<div class=" col-md-4">
									<div class="panel panel-primary table-responsive">
										<div class="panel-heading">Reportes Generar</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<button id="DescargarSinLectura" type="button" class="btn btn-success btn-block btn-md">
															Tres Periodos sin Lectura  <span class="glyphicon glyphicon-save"></span>
														</button>
														<button id="DescargarConsumosIguales" type="button" class="btn btn-warning btn-block btn-md">
															Consumos Iguales  <span class="glyphicon glyphicon-save"></span>
														</button>
														<button id="DescargarAnomaliaRepetida" type="button" class="btn btn-primary btn-block btn-md">
															Anomalia Repetida  <span class="glyphicon glyphicon-save"></span>
														</button>
														<button id="DescargarAnomalias" type="button" class="btn btn-primary btn-block btn-md">
															Anomalia(15,25)  <span class="glyphicon glyphicon-save"></span>
														</button>
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
