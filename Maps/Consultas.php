<?php
	session_start();
	include_once(dirname(__FILE__)."/../Clases/ClassUsuarios.php");
	include_once(dirname(__FILE__)."/../Clases/ClassConfiguracion.php");
	include_once(dirname(__FILE__)."/../Clases/ClassParametros.php");

	$FcnUsuario 		= new Usuario();
	$FcnConfiguracion	= new Configuracion();
	$FcnParametros		= new ClassParametros();


	if(!isset($_SESSION['Accesos']['Consultas']))
		header("Location: ../index.php");

	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>SERCODELL SAS</title>
		<meta name="keywords" content="Control Facturas, Sypelc.ltda, Julian Eduardo Poveda, Ingreniero Desarrollador" />
		<meta name="description" content="Control de Indicadores Contrato Sypelc.ltda -- Emsa" />

		<!--hojas de estilos-->
		<link href="../css/templatemo_style.css" media="screen" title="shadow" rel="stylesheet" type="text/css"/>
		<link href="../css/overcast/jquery-ui.min.css" title="shadow" rel="stylesheet" type="text/css"/>
		<link href="../css/DataTable/jquery.dataTables.css" media="screen" title="shadow" rel="stylesheet" type="text/css"/>


		<!--scripts-->
		<script type="text/javascript" src="../js/jquery/jquery-1.9.1.js"></script>
		<script type="text/javascript" src="../js/jquery/jquery-ui-1.10.3.custom.min.js"></script>
		<script type="text/javascript" src="../js/dataTable/jquery.dataTables.min.js"></script>  
		<script type="text/javascript" src="../js/dataTable/jquery.js"></script>  

		<script type="text/javascript" src="../js/jsOur/FuncionesRepetitivas.jquery.ui.js"></script>  
		<script type="text/javascript" src="../js/jsOur/hTablas.js"></script>  

		<script type="text/javascript">  
			$(document).ready(function(evento){ 
				oTable11=CrearDataTable("TableConsultaGeneral",true,true);
				oTable12=CrearDataTable("TableConsultaTomadas",true,true);
				oTable13=CrearDataTable("TableConsultaCliente",true,true);
				oTable14=CrearDataTable("TableConsultaPendientes",true,true);
				oTable15=CrearDataTable("TableConsultaGeneralInspectores",true,true);
				oTable16=CrearDataTable("TableConsultaCronologicoRuta",true,true);
				oTable17=CrearDataTable("TableConsultaConsolidado",true,true);


				$("#ConsultaGeneral").click(function(){
					$.ajax({ 	async: 		false, 
								type: 		"POST", 
								dataType: 	"json", 
								url: 		"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaGeneral", 
												Mes: 			$("#MesAsignacion option:selected").val(),
												Anno: 			$("#AnnoAsignacion option:selected").val(),
												Ciclo: 			$("#CicloAsignacion option:selected").val()	
											}, 
								success: function(data){ 	
									MostrarTabla(oTable11,data,["municipio","ruta","total","leidas","pendientes","estado"]);
								} 
							});	
				});

				
				
				
				$("#ConsultaCliente").click(function(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json", 
								url: 			"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaCliente", 
												Seleccion:  	$("#TipoBusqueda option:selected").val(),
												Dato:  			$("#DatoDigitado").val()
											}, 
								success: function(data){ 	
									MostrarTabla(oTable13,data,["cuenta","medidor","str_lectura","descripcion_anomalia","mensaje","descripcion_critica","nombre","fecha_toma"]);
								} 
							});	
				});


				/**********************************************************************************************************
				*************************Peticiones ajax para la carga de los ciclos asignados*****************************
				**********************************************************************************************************/
				$("#AnnoAsignacion").change(function(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json", 
								url: 			"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaCiclosLecturas", 
												Mes:  			$("#MesAsignacion option:selected").val(),
												Anno:  			$("#AnnoAsignacion option:selected").val(),
												Estado: 		"'P','E','T'"			
											}, 
								success: function(data){ 	
									MostrarResultadoCombo(CicloAsignacion,data);
								} 
							});
				})


				/**********************************************************************************************************
				******************Peticiones ajax para la carga de los rendimientos de los inspectores*********************
				**********************************************************************************************************/
				$("#AnnoGeneralInspector").change(function(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json", 
								url: 			"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaCiclosLecturas", 
												Mes:  			$("#MesGeneralInspector option:selected").val(),
												Anno:  			$("#AnnoGeneralInspector option:selected").val(),
												Estado: 		"'P','E','T'"			
											}, 
								success: function(data){ 	
									MostrarResultadoCombo(CicloGeneralInspector,data);
								} 
							});
				})

				$("#ConsultaGeneralInspector").click(function(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json", 
								url: 			"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaGeneralInspector", 
												Mes:  			$("#MesGeneralInspector option:selected").val(),
												Anno:  			$("#AnnoGeneralInspector option:selected").val(),
												Ciclo: 			$("#CicloGeneralInspector option:selected").val()
											}, 
								success: function(data){ 
									MostrarTabla(oTable15,data,["inspector","ruta","total","leidas","pendientes"]);
								} 
							});			
				})


				/**********************************************************************************************************
				**************Peticiones ajax para la carga de los combos para la generacion del consolidado***************
				**********************************************************************************************************/
				$("#AnnoConsolidado").change(function(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json", 
								url: 			"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaCiclosLecturas", 
												Mes:  			$("#MesConsolidado option:selected").val(),
												Anno:  			$("#AnnoConsolidado option:selected").val(),
												Estado: 		"'P','E','T'"			
											}, 
								success: function(data){ 	
									MostrarResultadoCombo(CicloConsolidado,data);
								} 
							});
				})

				$("#ConsultaConsolidado").click(function(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json", 
								url: 			"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaConsolidado", 
												Mes:  			$("#MesConsolidado option:selected").val(),
												Anno:  			$("#AnnoConsolidado option:selected").val(),
												Ciclo: 			$("#CicloConsolidado option:selected").val()
											}, 
								success: function(data){ 
									MostrarTabla(oTable17,data,["cuenta","medida","lectura","id_anomalia","digitos","fecha","mensaje","id_lector","tipo_uso","bluetooth","longitud","latitud"]);
								} 
							});			
				})


				$("#DescargaConsolidadoExcel").click(function(){
					url = "../Excel/DescargaConsolidado.php?Mes="+$("#MesConsolidado option:selected").val()+"&Anno="+$("#AnnoConsolidado option:selected").val()+"&Ciclo="+$("#CicloConsolidado option:selected").val();	
					window.open(url, '_blank');
					return false;
				})

				$("#DescargaConsolidadoCSV").click(function(){
					url = "../Excel/DescargaConsolidadoCSV.php?Mes="+$("#MesConsolidado option:selected").val()+"&Anno="+$("#AnnoConsolidado option:selected").val()+"&Ciclo="+$("#CicloConsolidado option:selected").val();	
					window.open(url, '_blank');
					return false;
				})




				/**********************************************************************************************************
				**************Peticiones ajax para la carga de los combos para la generacion del consolidado***************
				**********************************************************************************************************/
				$("#AnnoCritica").change(function(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json", 
								url: 			"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaCiclosLecturas", 
												Mes:  			$("#MesCritica option:selected").val(),
												Anno:  			$("#AnnoCritica option:selected").val(),
												Estado: 		"'P','E','T'"			
											}, 
								success: function(data){ 	
									MostrarResultadoCombo(CicloCritica,data);
								} 
							});
				})

				$("#DescargaCritica").click(function(){
					var SelectConsulta="";
					$("#consultas_critica input[type=checkbox]").each(function() { 
						if($(this).is(":checked")) { 
							SelectConsulta += "'"+$(this).val()+"',"
						}         
					});
					SelectConsulta = SelectConsulta.substring(0,SelectConsulta.length-1);
						

					url = "../Excel/DescargaCritica.php?Mes="+$("#MesCritica option:selected").val()+"&Anno="+$("#AnnoCritica option:selected").val()+"&Ciclo="+$("#CicloCritica option:selected").val()+"&Campos="+SelectConsulta;	
					window.open(url, '_blank');
					return false;
				})

				$("#PDFCritica").click(function(){
					var SelectConsulta="";
					$("#consultas_critica input[type=checkbox]").each(function() { 
						if($(this).is(":checked")) { 
							SelectConsulta += "'"+$(this).val()+"',"
						}         
					});
					SelectConsulta = SelectConsulta.substring(0,SelectConsulta.length-1);
						

					url = "../PDF/ReporteCriticas.php?Mes="+$("#MesCritica option:selected").val()+"&Anno="+$("#AnnoCritica option:selected").val()+"&Ciclo="+$("#CicloCritica option:selected").val()+"&Campos="+SelectConsulta;	
					window.open(url, '_blank');
					return false;
				})
					/**********************************************************************************************************
				**************Peticiones ajax para la carga de los combos para generar reporte de Anomalias***************
				**********************************************************************************************************/
				$("#AnnoAnomalias").change(function(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json", 
								url: 			"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaCiclosLecturas", 
												Mes:  			$("#MesAnomalias option:selected").val(),
												Anno:  			$("#AnnoAnomalias option:selected").val(),
												Estado: 		"'P','E','T'"			
											}, 
								success: function(data){ 	
									MostrarResultadoCombo(CicloAnomalias,data);
								} 
							});
				})

				$("#PDFAnomalias").click(function(){
					var SelectConsulta="";
					$("#consulta_reporte_anomalias input[type=checkbox]").each(function() { 
						if($(this).is(":checked")) { 
							SelectConsulta += ""+$(this).val()+","
						}         
					});
					SelectConsulta = SelectConsulta.substring(0,SelectConsulta.length-1);
						

					url = "../PDF/ReporteAnomaliasPDF.php?Mes="+$("#MesAnomalias option:selected").val()+"&Anno="+$("#AnnoAnomalias option:selected").val()+"&Ciclo="+$("#CicloAnomalias option:selected").val()+"&Campos="+SelectConsulta;	
					window.open(url, '_blank');
					return false;
				})
				/**********************************************************************************************************
				*******************Peticiones ajax para la carga parcial de datos de las lecturas pendientes***************
				**********************************************************************************************************/
				$("#AnnoPendientes").change(function(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json", 
								url: 			"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaCiclosLecturas", 
												Mes:  			$("#MesPendientes option:selected").val(),
												Anno:  			$("#AnnoPendientes option:selected").val(),
												Estado: 		"'P','E'"			
											}, 
								success: function(data){ 	
									MostrarResultadoCombo(CicloPendientes,data);
								} 
							});
				})

				$("#CicloPendientes").change(function(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json", 
								url: 			"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaMunicipiosLecturas", 
												Mes:  			$("#MesPendientes option:selected").val(),
												Anno:  			$("#AnnoPendientes option:selected").val(),
												Ciclo: 			$("#CicloPendientes option:selected").val(),
												Estado: 		"'P','E'"
											}, 
								success: function(data){ 	
									MostrarResultadoCombo(MunicipioPendientes,data);
								} 
							});
				})


				$("#MunicipioPendientes").change(function(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json", 
								url: 			"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaRutasLecturas", 
												Mes:  			$("#MesPendientes option:selected").val(),
												Anno:  			$("#AnnoPendientes option:selected").val(),
												Ciclo: 			$("#CicloPendientes option:selected").val(),
												Municipio: 		$("#MunicipioPendientes option:selected").val(),
												Estado: 		"'P','E'"
											}, 
								success: function(data){ 	
									MostrarResultadoCombo(RutaPendientes,data);
								} 
							});
				})

				$("#ConsultaPendientes").click(function(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json", 
								url: 			"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaPendientesClientes", 
												Mes:  			$("#MesPendientes option:selected").val(),
												Anno:  			$("#AnnoPendientes option:selected").val(),
												Ciclo: 			$("#CicloPendientes option:selected").val(),
												Municipio: 		$("#MunicipioPendientes option:selected").val(),
												Ruta: 			$("#RutaPendientes option:selected").val()
											}, 
								success: function(data){ 
									MostrarTabla(oTable14,data,["cuenta","medidor","nombre","direccion"]);
								} 
							});			
				})


				/****************************************************************************************************
				*****************Peticiones ajax para la carga parcial de datos de las lecturas tomadas**************
				****************************************************************************************************/
				$("#AnnoTomadas").change(function(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json", 
								url: 			"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaCiclosLecturas", 
												Mes:  			$("#MesTomadas option:selected").val(),
												Anno:  			$("#AnnoTomadas option:selected").val(),
												Estado: 		"'E','T'"			
											}, 
								success: function(data){ 	
									MostrarResultadoCombo(CicloTomadas,data);
								} 
							});
				})

				$("#CicloTomadas").change(function(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json", 
								url: 			"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaMunicipiosLecturas", 
												Mes:  			$("#MesTomadas option:selected").val(),
												Anno:  			$("#AnnoTomadas option:selected").val(),
												Ciclo: 			$("#CicloTomadas option:selected").val(),
												Estado: 		"'T','E'"
											}, 
								success: function(data){ 	
									MostrarResultadoCombo(MunicipioTomadas,data);
								} 
							});
				})


				$("#MunicipioTomadas").change(function(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json", 
								url: 			"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaRutasLecturas", 
												Mes:  			$("#MesTomadas option:selected").val(),
												Anno:  			$("#AnnoTomadas option:selected").val(),
												Ciclo: 			$("#CicloTomadas option:selected").val(),
												Municipio: 		$("#MunicipioTomadas option:selected").val(),
												Estado: 		"'T','E'"
											}, 
								success: function(data){ 	
									MostrarResultadoCombo(RutaTomadas,data);
								} 
							});
				})
				/** Consultas para los reportes**/

				$("#AnnoReporte").change(function(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json", 
								url: 			"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaCiclosLecturas", 
												Mes:  			$("#MesReporte option:selected").val(),
												Anno:  			$("#AnnoReporte option:selected").val(),
												Estado: 		"'E','T'"			
											}, 
								success: function(data){ 	
									MostrarResultadoCombo(CicloReporte,data);
								} 
							});
				})

				$("#CicloReporte").change(function(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json", 
								url: 			"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaMunicipiosLecturas", 
												Mes:  			$("#MesReporte option:selected").val(),
												Anno:  			$("#AnnoReporte option:selected").val(),
												Ciclo: 			$("#CicloReporte option:selected").val(),
												Estado: 		"'T','E'"
											}, 
								success: function(data){ 	
									MostrarResultadoCombo(MunicipioReporte,data);
								} 
							});
				})


				$("#MunicipioReporte").change(function(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json", 
								url: 			"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaRutasLecturas", 
												Mes:  			$("#MesReporte option:selected").val(),
												Anno:  			$("#AnnoReporte option:selected").val(),
												Ciclo: 			$("#CicloReporte option:selected").val(),
												Municipio: 		$("#MunicipioReporte option:selected").val(),
												Estado: 		"'T','E'"
											}, 
								success: function(data){ 	
									MostrarResultadoCombo(RutaReporte,data);
								} 
							});
				})

				/**Consultas generales de las lecturas tomadas y pendientes**/
				$("#ConsultaTomadas").click(function(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json", 
								url: 			"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaDetalleTomadas", 
												Mes:  			$("#MesTomadas option:selected").val(),
												Anno:  			$("#AnnoTomadas option:selected").val(),
												Ciclo: 			$("#CicloTomadas option:selected").val(),
												Municipio: 		$("#MunicipioTomadas option:selected").val(),
												Ruta: 			$("#RutaTomadas option:selected").val()
											}, 
								success: function(data){ 
									MostrarTabla(oTable12,data,["cuenta","medidor","str_lectura","descripcion_anomalia","mensaje","descripcion_critica","nombre","direccion"]);
								} 
							});			
				})


				/****************************************************************************************************
				**************Peticiones ajax para la carga parcial de datos del cronologico de lecturas*************
				****************************************************************************************************/
				$("#AnnoCronologico").change(function(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json", 
								url: 			"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaCiclosLecturas", 
												Mes:  			$("#MesCronologico option:selected").val(),
												Anno:  			$("#AnnoCronologico option:selected").val(),
												Estado: 		"'E','T'"			
											}, 
								success: function(data){ 	
									MostrarResultadoCombo(CicloCronologico,data);
								} 
							});
				})

				$("#CicloCronologico").change(function(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json", 
								url: 			"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaMunicipiosLecturas", 
												Mes:  			$("#MesCronologico option:selected").val(),
												Anno:  			$("#AnnoCronologico option:selected").val(),
												Ciclo: 			$("#CicloCronologico option:selected").val(),
												Estado: 		"'T','E'"
											}, 
								success: function(data){ 	
									MostrarResultadoCombo(MunicipioCronologico,data);
								} 
							});
				})


				$("#MunicipioCronologico").change(function(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json", 
								url: 			"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaRutasLecturas", 
												Mes:  			$("#MesCronologico option:selected").val(),
												Anno:  			$("#AnnoCronologico option:selected").val(),
												Ciclo: 			$("#CicloCronologico option:selected").val(),
												Municipio: 		$("#MunicipioCronologico option:selected").val(),
												Estado: 		"'T','E'"
											}, 
								success: function(data){ 	
									MostrarResultadoCombo(RutaCronologico,data);
								} 
							});
				})



				/**Consultas generales de las lecturas tomadas y pendientes**/
				$("#ConsultaCronologico").click(function(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json", 
								url: 			"../Ajax/AjaxConsultas.php", 
								data: 		{	Peticion: 		"ConsultaCronologicoTomadas", 
												Mes:  			$("#MesCronologico option:selected").val(),
												Anno:  			$("#AnnoCronologico option:selected").val(),
												Ciclo: 			$("#CicloCronologico option:selected").val(),
												Municipio: 		$("#MunicipioCronologico option:selected").val(),
												Ruta: 			$("#RutaCronologico option:selected").val()
											}, 
								success: function(data){ 
									MostrarTabla(oTable16,data,["cuenta","medidor","str_lectura","descripcion_anomalia","mensaje","descripcion_critica","fecha_toma"]);
								} 
							});			
				})

				/****************************************************************************************************
				***************** Peticiones Para Generar en PDF los Reportes Necesarios  **************************
				****************************************************************************************************/
				/*$("#LecturaManuales").click(function(){
					url = "../PDF/FormatoBalances.php?Mes="+$("#MesReporte option:selected").val()+"&Anno="+$("#AnnoReporte option:selected").val()+"&Ciclo="+$("#CicloReporte option:selected").val()+"&Municipio="+$("#MunicipioReporte option:selected").val()+"&Ruta="+$("#RutaReporte option:selected").val();	
					window.open(url, '_blank');
					return false;
				})*/
	
				$("#ConsultaRelecturas").click(function(){
					url = "../PDF/Relecturas.php?Mes="+$("#MesReporte option:selected").val()+"&Anno="+$("#AnnoReporte option:selected").val()+"&Ciclo="+$("#CicloReporte option:selected").val()+"&Municipio="+$("#MunicipioReporte option:selected").val()+"&Ruta="+$("#RutaReporte option:selected").val();	
					window.open(url, '_blank');
					return false;
				})
				
				$("#ConsultaCriticaCero").click(function(){
					url = "../PDF/Criticacero.php?Mes="+$("#MesReporte option:selected").val()+"&Anno="+$("#AnnoReporte option:selected").val()+"&Ciclo="+$("#CicloReporte option:selected").val()+"&Municipio="+$("#MunicipioReporte option:selected").val()+"&Ruta="+$("#RutaReporte option:selected").val();	
					window.open(url, '_blank');
					return false;
				})
				/******/
				$("#ExportarPendientes").click(function(){
					url = "../Excel/EstadoCicloRuta.php?Mes="+$("#MesPendientes option:selected").val()+"&Anno="+$("#AnnoPendientes option:selected").val()+"&Tipo=Pendientes&Ciclo="+$("#RutaPendientes option:selected").val();	
					window.open(url, '_blank');
					return false;
				})

				$("#ExportarDetalle").click(function(){
					url = "../Excel/EstadoCicloRuta.php?Mes="+$("#MesDetalle option:selected").val()+"&Anno="+$("#AnnoDetalle option:selected").val()+"&Tipo=Tomadas&Ciclo="+$("#RutaDetalle option:selected").val();	
					window.open(url, '_blank');
					return false;
				})





				$("#ConsultasProcesos").tabs( {
					"show": function(event, ui) {
						var table = $.fn.dataTable.fnTables(true);
						if ( table.length > 0 ) {
							$(table).dataTable().fnAdjustColumnSizing();
						}
					}
				});
			});

		</script>
	</head>

	<body>
		<div id="templatemo_body_wrapper">
			<div id="templatemo_wrapper">
				<div id="tempaltemo_header">
					<span id="header_icon"></span>
					<div id="header_content">
						<div id="site_title">
							<a>
								<img src="../imagenes/sercodel.png" alt="LOGO"/>
							</a>            
						</div>						
					</div>
				</div> <!-- end of header -->

				
				<div id="templatemo_main_top"></div>

				<div id="templatemo_main"><span id="main_top"></span><!--span id="main_bottom"></span-->
					<div id="templatemo_sidebar">
						<div id="templatemo_menu">
							<ul>
								<?php $FcnUsuario->AccesoPaginas("Consultas"); ?>
								<li><a href="../index.php" target="_parent">Cerrar Sesion</a></li>
							</ul>    	
						</div> <!-- end of templatemo_menu -->


						<center>
							<a href="http://validator.w3.org/check?uri=referer"><img style="border:0;width:88px;height:31px" src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Transitional" width="88" height="31" vspace="8" border="0" /></a> &nbsp;&nbsp;&nbsp;
							<a href="http://jigsaw.w3.org/css-validator/check/referer"><img style="border:0;width:88px;height:31px"  src="http://jigsaw.w3.org/css-validator/images/vcss-blue" alt="Valid CSS!" vspace="8" border="0" /></a>
						</center>

						<div class="cleaner"></div>
					</div> <!-- end of sidebar -->

					<div id="templatemo_content">
						<div class="content_box">
							<div id="ConsultasProcesos">
								<ul>
									<?php $FcnUsuario->AccesoModulos("Consultas"); ?>
								</ul>
								<?php 
								if(isset($_SESSION['Accesos']['Consultas']['consultas_general_ciclo'])){ ?>
									<div id="consultas_general_ciclo" height="80%" >
										<center>
											<label class="LabelInputSmaller" for="MesAsignacion">Mes</label>
											<select id="MesAsignacion"> 
											<?php
												$_mes = json_decode($FcnParametros->getMes());
												foreach($_mes as $obj){
													echo "<option value='".$obj->numero_mes."'>".$obj->nombre_mes."</option>";											   
												}
											?> 
											</select>									
											
											<label class="LabelInputSmaller" for="AnnoAsignacion">Año</label>
											<select id="AnnoAsignacion"> 
											<?php
												$_anno = json_decode($FcnParametros->getAnno());
												foreach($_anno as $obj){
													echo "<option value='".$obj->anno."'>".$obj->anno."</option>";											   
												}
											?> 
											</select>

											<label class="LabelInputSmaller" for="CicloAsignacion">Ciclo</label>
											<select id="CicloAsignacion"> 
											</select>										
											<input type='button' id="ConsultaGeneral" value='Consultar' />
										</center>
										<div class="cleaner h05"></div>
										<table cellpadding="0" cellpadding="0" border="0" class="display" id="TableConsultaGeneral" width="100%">
											<thead>
												<tr> 
													<th width="40%">Municipio</th>
													<th width="20%">Ruta</th>
													<th width="10%">Total</th>
													<th width="10%">Leidas</th>
													<th width="10%">Pend.</th>
													<th width="10%">Estado</th>
												</tr>
											</thead>
											<tbody>										
											</tbody>
										</table> 
									</div>
								<?php }
								if(isset($_SESSION['Accesos']['Consultas']['consultas_consolidado'])){ ?>
									<div id="consultas_consolidado" height="80%" >
										<center>
											<label class="LabelInputSmaller" for="MesConsolidado">Mes</label>
											<select id="MesConsolidado"> 
											<?php
												$_mes = json_decode($FcnParametros->getMes());
												foreach($_mes as $obj){
													echo "<option value='".$obj->numero_mes."'>".$obj->nombre_mes."</option>";											   
												}
											?> 
											</select>									
											
											<label class="LabelInputSmaller" for="AnnoConsolidado">Año</label>
											<select id="AnnoConsolidado"> 
											<?php
												$_anno = json_decode($FcnParametros->getAnno());
												foreach($_anno as $obj){
													echo "<option value='".$obj->anno."'>".$obj->anno."</option>";											   
												}
											?> 
											</select>

											<label class="LabelInputSmaller" for="CicloConsolidado">Ciclo</label>
											<select id="CicloConsolidado"> 
											</select>										
											<input type='button' id="ConsultaConsolidado" value='Consultar' />
											<input type='button' id="DescargaConsolidadoExcel" value='Excel' />
											<input type='button' id="DescargaConsolidadoCSV" value='CSV' />
										</center>
										<div class="cleaner h05"></div>
										<table cellpadding="0" cellpadding="0" border="0" class="display" id="TableConsultaConsolidado" width="100%">
											<thead>
												<tr> 
													<th width="10%">Cod.</th>
													<th width="10%">Med</th>
													<th width="10%">Lect.</th>
													<th width="10%">Anom</th>
													<th width="10%">Dig</th>
													<th width="10%">Fecha</th>
													<th width="10%">Mensaje</th>
													<th width="10%">Insp</th>
													<th width="10%">CIIU</th>
													<th width="10%">Bluetooth</th>
													<th width="10%">Long.</th>
													<th width="10%">Lat.</th>
												</tr>
											</thead>
											<tbody>										
											</tbody>
										</table> 
									</div>
								<?php }
								if(isset($_SESSION['Accesos']['Consultas']['consultas_critica'])){ ?>
									<div id="consultas_critica" height="80%" >
										<center>
											<label class="LabelInputSmaller" for="MesCritica">Mes</label>
											<select id="MesCritica"> 
											<?php
												$_mes = json_decode($FcnParametros->getMes());
												foreach($_mes as $obj){
													echo "<option value='".$obj->numero_mes."'>".$obj->nombre_mes."</option>";											   
												}
											?> 
											</select>									
											
											<label class="LabelInputSmaller" for="AnnoCritica">Año</label>
											<select id="AnnoCritica"> 
											<?php
												$_anno = json_decode($FcnParametros->getAnno());
												foreach($_anno as $obj){
													echo "<option value='".$obj->anno."'>".$obj->anno."</option>";											   
												}
											?> 
											</select>

											<label class="LabelInputSmaller" for="CicloCritica">Ciclo</label>
											<select id="CicloCritica"> 
											</select>										
											<input type='button' id="DescargaCritica" value='Descargar' />
											<input type='button' id="PDFCritica" value='GenerarPDF' />
										</center>

										<?php
											$_critica = json_decode($FcnParametros->getCritica());
											foreach($_critica as $obj){
												echo "<label class='LabelInput' for='Item1'>".$obj->descripcion."</label>";
												echo "<input type='checkbox' name='".$obj->descripcion."' value='".$obj->descripcion."'/>";											   
											}
										?> 
									<div class="cleaner h05"></div>
									</div>
								<?php }
								if(isset($_SESSION['Accesos']['Consultas']['consultas_cronologico_ruta'])){ ?>
									<div id="consultas_cronologico_ruta" height="80%" >
										<label class="LabelInputSmall" for="MesCronologico">Mes</label>
										<select id="MesCronologico"> 
										<?php
											$_mes = json_decode($FcnParametros->getMes());
											foreach($_mes as $obj){
												echo "<option value='".$obj->numero_mes."'>".$obj->nombre_mes."</option>";											   
											}
										?> 
										</select>									
											
										<label class="LabelInputSmaller" for="AnnoCronologico">Año</label>
										<select id="AnnoCronologico"> 
										<?php
											$_anno = json_decode($FcnParametros->getAnno());
											foreach($_anno as $obj){
												echo "<option value='".$obj->anno."'>".$obj->anno."</option>";											   
											}
										?> 
										</select>	

										<label class="LabelInputSmaller" for="CicloCronologico">Ciclo</label>
										<select id="CicloCronologico"> 
										</select>

										<div class="cleaner h05"></div>
										<label class="LabelInputSmall" for="MunicipioCronologico">Municipio</label>
										<select id="MunicipioCronologico"> 
										</select>

										<label class="LabelInputSmaller" for="RutaCronologico">Ruta</label>
										<select id="RutaCronologico"> 
										</select>

										<input type='button' id="ConsultaCronologico" value='Consultar' />
										<input type='button' id="ExportarCronologico" value='Exportar' />

										<div class="cleaner h05"></div>
										<table cellpadding="0" cellpadding="0" border="0" class="display" id="TableConsultaCronologicoRuta" width="100%">
											<thead>
												<tr> 
													<th width="10%">Cuenta</th>
													<th width="10%">Medidor</th>
													<th width="10%">Lect</th>
													<th width="10%">Anom</th>
													<th width="20%">Msj</th>
													<th width="20%">Critica</th>
													<th width="20%">Fecha Hora</th>
												</tr>
											</thead>
											<tbody>										
											</tbody>
										</table>  
									</div>
								<?php }
								if(isset($_SESSION['Accesos']['Consultas']['consultas_general_inspector'])){ ?>
									<div id="consultas_general_inspector" height="80%" >
										<center>
											<label class="LabelInputSmaller" for="MesGeneralInspector">Mes</label>
											<select id="MesGeneralInspector"> 
											<?php
												$_mes = json_decode($FcnParametros->getMes());
												foreach($_mes as $obj){
													echo "<option value='".$obj->numero_mes."'>".$obj->nombre_mes."</option>";											   
												}
											?> 
											</select>									
											
											<label class="LabelInputSmaller" for="AnnoGeneralInspector">Año</label>
											<select id="AnnoGeneralInspector"> 
											<?php
												$_anno = json_decode($FcnParametros->getAnno());
												foreach($_anno as $obj){
													echo "<option value='".$obj->anno."'>".$obj->anno."</option>";											   
												}
											?> 
											</select>

											<label class="LabelInputSmaller" for="CicloGeneralInspector">Ciclo</label>
											<select id="CicloGeneralInspector"> 
											</select>										
											<input type='button' id="ConsultaGeneralInspector" value='Consultar' />
										</center>
										<div class="cleaner h05"></div>
										<table cellpadding="0" cellpadding="0" border="0" class="display" id="TableConsultaGeneralInspectores" width="100%">
											<thead>
												<tr> 
													<th width="50%">Inspector</th>
													<th width="20%">Ruta</th>
													<th width="10%">Total</th>
													<th width="10%">Leidas</th>
													<th width="10%">Pend.</th>
												</tr>
											</thead>
											<tbody>										
											</tbody>
										</table> 
									</div>
								<?php }
								if(isset($_SESSION['Accesos']['Consultas']['consultas_lecturas_tomadas'])){ ?>
									<div id="consultas_lecturas_tomadas" height="80%" >
										<label class="LabelInputSmall" for="MesTomadas">Mes</label>
										<select id="MesTomadas"> 
										<?php
											$_mes = json_decode($FcnParametros->getMes());
											foreach($_mes as $obj){
												echo "<option value='".$obj->numero_mes."'>".$obj->nombre_mes."</option>";											   
											}
										?> 
										</select>									
											
										<label class="LabelInputSmaller" for="AnnoTomadas">Año</label>
										<select id="AnnoTomadas"> 
										<?php
											$_anno = json_decode($FcnParametros->getAnno());
											foreach($_anno as $obj){
												echo "<option value='".$obj->anno."'>".$obj->anno."</option>";											   
											}
										?> 
										</select>	

										<label class="LabelInputSmaller" for="CicloTomadas">Ciclo</label>
										<select id="CicloTomadas"> 
										</select>

										<div class="cleaner h05"></div>
										<label class="LabelInputSmall" for="MunicipioTomadas">Municipio</label>
										<select id="MunicipioTomadas"> 
										</select>

										<label class="LabelInputSmaller" for="RutaTomadas">Ruta</label>
										<select id="RutaTomadas"> 
										</select>

										<input type='button' id="ConsultaTomadas" value='Consultar' />
										<input type='button' id="ExportarTomadas" value='Exportar' />
										
										<div class="cleaner h05"></div>
										<table cellpadding="0" cellpadding="0" border="0" class="display" id="TableConsultaTomadas" width="100%">
											<thead>
												<tr> 
													<th width="10%">Cuenta</th>
													<th width="10%">Medidor</th>
													<th width="10%">Lect</th>
													<th width="10%">Anom</th>
													<th width="20%">Msj</th>
													<th width="20%">Critica</th>
													<th width="10%">Nombre</th>
													<th width="10%">Direccion</th>
												</tr>
											</thead>
											<tbody>										
											</tbody>
										</table> 
									</div>
								<?php }
								if(isset($_SESSION['Accesos']['Consultas']['consultas_lecturas_pendientes'])){ ?>
									<div id="consultas_lecturas_pendientes" height="80%" >
										<label class="LabelInputSmall" for="MesPendientes">Mes</label>
										<select id="MesPendientes"> 
										<?php
											$_mes = json_decode($FcnParametros->getMes());
											foreach($_mes as $obj){
												echo "<option value='".$obj->numero_mes."'>".$obj->nombre_mes."</option>";											   
											}
										?> 
										</select>									
											
										<label class="LabelInputSmaller" for="AnnoPendientes">Año</label>
										<select id="AnnoPendientes"> 
										<?php
											$_anno = json_decode($FcnParametros->getAnno());
											foreach($_anno as $obj){
												echo "<option value='".$obj->anno."'>".$obj->anno."</option>";											   
											}
										?> 
										</select>	

										<label class="LabelInputSmaller" for="CicloPendientes">Ciclo</label>
										<select id="CicloPendientes"> 
										</select>

										<div class="cleaner h05"></div>
										<label class="LabelInputSmall" for="MunicipioPendientes">Municipio</label>
										<select id="MunicipioPendientes"> 
										</select>

										<label class="LabelInputSmaller" for="RutaPendientes">Ruta</label>
										<select id="RutaPendientes"> 
										</select>

										<input type='button' id="ConsultaPendientes" value='Consultar'/>
										<input type='button' id="ExportarPendientes" value='Exportar' />
										
										<div class="cleaner h05"></div>
										<table cellpadding="0" cellpadding="0" border="0" class="display" id="TableConsultaPendientes" width="100%">
											<thead>
												<tr> 
													<th width="15%">Cuenta</th>
													<th width="15%">Medidor</th>
													<th width="35%">Nombre</th>
													<th width="35%">Direccion</th>
												</tr>
											</thead>
											<tbody>										
											</tbody>
										</table> 
									</div>
								<?php }
								if(isset($_SESSION['Accesos']['Consultas']['consultas_cliente'])){ ?>
									<div id="consultas_cliente" height="80%" >
										<center>
											<label class="LabelInputSmall" for="TipoBusqueda">Buscar Por:</label>
											<select id="TipoBusqueda"> 
												<option value="Cuenta">Cuenta</option>
												<option value="Medidor">Medidor</option>
											</select>	
											<label class="LabelInput" for="CedulaEmpleado">Dato a Consultar:</label>
											<input type="text" id="DatoDigitado" size="20"/> 
											<input type='button' id="ConsultaCliente" value='Consultar'/>	
										</center>
										<div class="cleaner h05"></div>
										<table cellpadding="0" cellpadding="0" border="0" class="display" id="TableConsultaCliente" width="100%">
											<thead>
												<tr> 
													<th width="10%">Cuenta</th>
													<th width="10%">Medidor</th>
													<th width="10%">Lect</th>
													<th width="10%">Anom</th>
													<th width="20%">Msj</th>
													<th width="20%">Critica</th>
													<th width="10%">Nombre</th>
													<th width="10%">Fecha Hora</th>
												</tr>
											</thead>
											<tbody>										
											</tbody>
										</table> 
									</div>
								<?php }
								if(isset($_SESSION['Accesos']['Consultas']['consultas_reportes'])){ ?>
									<div id="consultas_reportes" height="80%" >
										<select id="MesReporte">
										<?php
											$_mes = json_decode($FcnParametros->getMes());
											foreach($_mes as $obj){
												echo "<option value='".$obj->numero_mes."'>".$obj->nombre_mes."</option>";											   
											}
										?> 
										</select>									
											
										<label class="LabelInputSmaller" for="AnnoReporte">Año</label>
										<select id="AnnoReporte">
										<?php
											$_anno = json_decode($FcnParametros->getAnno());
											foreach($_anno as $obj){
												echo "<option value='".$obj->anno."'>".$obj->anno."</option>";											   
											}
										?> 
										</select>	

										<label class="LabelInputSmaller" for="CicloReporte">Ciclo</label>
										<select id="CicloReporte"> 
										</select>
										
										<label class="LabelInputSmall" for="MunicipioReporte">Municipio</label>
										<select id="MunicipioReporte"> 
										</select>
										<div class="cleaner h05"></div>
										<label class="LabelInputSmaller" for="RutaReporte">Ruta</label>
										<select id="RutaReporte"> 
										</select> 
										<div class="cleaner h05"></div>
										<div class="cleaner h05"></div>
										<input type='button' id="ConsultaRelecturas" value='Relecturas'/>
										<input type='button' id="ConsultaCriticaCero" value='CriticaCero'/>										
										<input type='button' id="ReporteAnomalias" value='ReporteAnomalias'/>
									</div>
									<?php }
									if(isset($_SESSION['Accesos']['Consultas']['consulta_reporte_anomalias'])){ ?>
									<div id="consulta_reporte_anomalias" height="80%" >
										<center>
											<label class="LabelInputSmaller" for="MesAnomalias">Mes</label>
											<select id="MesAnomalias"> 
											<?php
												$_mes = json_decode($FcnParametros->getMes());
												foreach($_mes as $obj){
													echo "<option value='".$obj->numero_mes."'>".$obj->nombre_mes."</option>";											   
												}
											?> 
											</select>									
											
											<label class="LabelInputSmaller" for="AnnoAnomalias">Año</label>
											<select id="AnnoAnomalias"> 
											<?php
												$_anno = json_decode($FcnParametros->getAnno());
												foreach($_anno as $obj){
													echo "<option value='".$obj->anno."'>".$obj->anno."</option>";											   
												}
											?> 
											</select>

											<label class="LabelInputSmaller" for="CicloAnomalias">Ciclo</label>
											<select id="CicloAnomalias"> 
											</select>																					
											<input type='button' id="PDFAnomalias" value='GenerarPDF' />
										</center>
										<div class="cleaner h05"></div>
										<?php
											$_critica = json_decode($FcnParametros->getAnomalia());
											foreach($_critica as $obj){
												echo "<label class='LabelInput' for='Item1'>".$obj->id_anomalia."</label>";
												echo "<input type='checkbox' name='".$obj->id_anomalia."' value='".$obj->id_anomalia."'/>";											   
											}
										?> 
									<div class="cleaner h05"></div>
									</div>
								<?php  }?>
							</div>
						</div>						
						<div class="last_box"></div>
					</div>
					<div class="cleaner"></div>    
				</div>

				<div id="templatemo_main_bottom"></div>

			</div> <!-- end of wrapper -->
		</div>

		<div id="templatemo_footer_wrapper">
			<div id="templatemo_footer">
				Derechos de Autor © 2012 <a href="#">Sypelc.Ltda</a> | 
				Departamento de Electronica Diseño & Desarrollo | 
				Plataformas <a href="http://validator.w3.org/check?uri=referer">XHTML</a> &amp; 
				<a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a>
			</div>
		</div>
	</body>
</html>