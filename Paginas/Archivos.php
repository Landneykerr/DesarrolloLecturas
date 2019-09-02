<?php
	session_start();
	include_once(dirname(__FILE__)."/../Clases/ClassUsuario.php");
	include_once(dirname(__FILE__)."/../Clases/ClassConfiguracion.php");
	include_once(dirname(__FILE__)."/../Clases/ClassArchivos.php");
	include_once(dirname(__FILE__)."/../Clases/ClassParametros.php");

	$FcnUsuario 		= new Usuario();
	$FcnConfiguracion	= new Configuracion();
	$FcnArchivo			= new ClassArchivos();
	$FcnParametros		= new ClassParametros();


	if(!isset($_SESSION['Accesos']['Archivos']))
		header("Location: ../index.php");

	if($_FILES){ 
		if (is_uploaded_file($_FILES['FileCiclo']['tmp_name'])){ 
			$file= fopen($_FILES['FileCiclo']['tmp_name'],"r")or exit("Unable to open file!");
			$new_archivo = "";
			//$new_archivo = "mes,anno,cuenta,id_ciclo,departamento,id_municipio,sector,ruta_completa,nombre,direccion,estado,medidor,serie,medida,factor,digitos,estrato,tarifa,carga,tipo_energia,tipo_medicion,promedio,lectura,tipo_uso,nodo\n";
			while(!feof($file)){
				$campos = explode(",",fgets($file));
				
				if(sizeof($campos)>=23){
					$new_fila = $_POST["MesCargue"].",".$_POST["AnnoCargue"];
					$campos[2] = $_POST["CicloCargue"]; 
					if(!is_numeric($campos[21])){
						$campos[21] = 0;
					}

					for($i=1;$i<sizeof($campos);$i++){
						$new_fila .= ",".$campos[$i];
					}
					$new_archivo	.=	$new_fila;
				}	
			}
			fclose($file);

			$destino = dirname(__FILE__)."/../UpLoad/"."maestro_".$_POST["CicloCargue"]."_".$_POST["MesCargue"]."_".$_POST["AnnoCargue"].".cvs";
			$file2 = fopen($destino,"w");
			fputs($file2,$new_archivo);
			fclose($file2);

			if(file_exists($destino)){
				$resultado_cargue_archvo = $FcnArchivo->RegistrarInformacionArchivo($_POST["CicloCargue"],
																					$_POST["MesCargue"],
																					$_POST["AnnoCargue"],
																					"maestro_".$_POST["CicloCargue"]."_".$_POST["MesCargue"]."_".$_POST["AnnoCargue"].".cvs",
																					",");
				unlink($destino);
				echo "<script type='text/javascript'> alert('".$resultado_cargue_archvo."'); </script>";
			}else{
				echo "<script type='text/javascript'> alert('Error Archivo.'); </script>";
			}
		}else{ 
				echo "<script type='text/javascript'> alert('Error al cargar el archivo del ciclo ".$_POST["CicloCargue"]." para el periodo ".str_pad($_POST["MesCargue"],2,"0",STR_PAD_LEFT)."-".$_POST["AnnoCargue"].".'); </script>";
		}
	}


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
		
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				oTable11 = CrearDataTable("ArchivosTable",true,true,true);
				


				$('#ArchivosTable tbody').on( 'click', 'tr', function () {
					$(this).toggleClass('selected');
				});


				$(".periodo_cargue").change(function(){
					VerResumenCiclos();
				});


				$("#CerrarCiclos").click(function(){
					CerrarCiclos();
				})

				function VerResumenCiclos(){
					var SendInformacionN =	$.ajax({    async:  	true,
														type:   	"POST",
														dataType: 	"json",
														url:    	"../Ajax/AjaxConsultas.php",
														data:   {   Peticion: 	"ConsultarResumenCiclos",
																	Mes: 		$("#MesCargue option:selected").val(),
																	Anno: 		$("#AnnoCargue option:selected").val()
																},
														success:function(data){
															MostrarResultadoCombo(CicloCargue,data['CiclosPorCargar']);
															MostrarTabla(oTable11,data['CiclosCargados']);
														}
													});

					SendInformacionN.fail(function(jqXHR, textStatus) {
						alert( "Error en la consulta de usuarios." );
					});
				}


				function CerrarCiclos(){
					var InfTablaCiclos 	= InfTablaSelectedToJSON(oTable11,"ListaCiclos",["Ciclo"],[2]);
					var SendInformacionN =	$.ajax({    async:  	false,
														type:   	"POST",
														url:    	"../Ajax/AjaxArchivos.php",
														data:   {   Peticion: 	"CerrarCiclos",
																	Mes:  		$("#MesCargue option:selected").val(),
																	Anno: 		$("#AnnoCargue option:selected").val(),
																	Ciclos: 	InfTablaCiclos 
																},
														success:function(data){
															alert(data);
														}
													});

					SendInformacionN.fail(function(jqXHR, textStatus) {
						alert( "Error cerrando los ciclos." );
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
						<h2>SYPELC - Archivos</h2>	
					</div>
					<div class="col-sm-8 col-md-8">
						<div id="navbar" class="navbar-collapse collapse">
							<ul class="nav navbar-nav navbar-right">
								<?php $FcnUsuario->AccesoPaginas("Archivos"); ?>
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
					<!--li class="active"><a data-toggle="tab" href="#archivos_cargue">Administracion de Archivos</a></li-->
					<?php $FcnUsuario->AccesoModulos("Archivos"); ?>
				</ul>

				<div class="tab-content">
					<?php 
					if(isset($_SESSION['Accesos']['Archivos']['archivos_cargue'])){ ?>
						<div id="archivos_cargue" class="tab-pane fade" height="100%">
							<div class="row">
								<div class="col-md-3">
									<div class="panel panel-success table-responsive">
										<div class="panel-heading">Periodo Cargue</div>	
										<form id="UpLoadCiclo" name="UpLoadCiclo" method="post" action="Archivos.php" enctype="multipart/form-data">								
											<div class="panel-body">
												<div class="form-group">
													<label for="MesCargue">Mes</label>
													<select id="MesCargue" name="MesCargue" class="form-control periodo_cargue" >
														<?php
															$_mes = json_decode($FcnParametros->getMes());
															foreach($_mes as $obj){
																echo "<option value='".$obj->numero_mes."'>".$obj->nombre_mes."</option>";											   
															}
														?> 
													</select>
												</div>

												<div class="form-group">
													<label for="AnnoCargue">Año</label>
													<select id="AnnoCargue" name="AnnoCargue" class="form-control periodo_cargue" >
														<?php
															$_anno = json_decode($FcnParametros->getAnno());
															foreach($_anno as $obj){
																echo "<option value='".$obj->anno."'>".$obj->anno."</option>";											   
															}
														?> 
													</select>
												</div>

												<div class="form-group">
													<label for="CicloCargue">Ciclo</label>
													<select id="CicloCargue" name="CicloCargue" class="form-control" >
													</select>
												</div>

												<div class="form-group">
													<label for="FileCiclo">Archivo a Cargar</label>
													<input type="file" class="filestyle" data-placeholder="No file" name="FileCiclo" id="FileCiclo">
												</div>	

												<div class="form-group">
													<input type='submit' value='Cargar Ciclo' class="btn btn-success pull-right"/> 
												</div>
											</div>	
										</form>				
									</div>
								</div> 

								<div class="col-md-9">
									<div class="panel panel-success table-responsive">
										<div class="panel-heading">Archivos Cargados</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<table id="ArchivosTable" class="table table-condensed" cellspacing="0" width="99%">
															<thead>
																<tr class="info"> 
																	<th width="10%">Mes</th>
																	<th width="10%">Año</th>
																	<th width="10%">Ciclo</th>
																	<th width="10%">Estado</th>
																	<th width="30%">Fecha Cargue</th>
																	<th width="30%">Usuario</th>
																</tr>
															</thead>
															<tbody>							
															</tbody>
														</table>	
													</div>

													<div class="form-group">
														<button id="CerrarCiclos" type="button" class="btn btn-warning btn-md pull-right">Cerrar Ciclo</button>
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
