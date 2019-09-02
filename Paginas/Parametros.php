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
				oTable11 = CrearDataTable("InspectoresTable", true, true, true);
				oTable12 = CrearDataTable("BluetoothTable",true,true,true);
				oTable13 = CrearDataTable("AnomaliasTable",true,true,true);
				oTable14 = CrearDataTable("DepartamentosTable",true,true,true);
				oTable15 = CrearDataTable("MunicipiosTable",true,true,true);
				oTable16 = CrearDataTable("CiclosTable",true,true,true);
				oTable17 = CrearDataTable("CodMensajesTable",true,true,true);
				oTable18 = CrearDataTable("CriticaTable",true,true,true);
				oTable19 = CrearDataTable("FiltroMacroTable",true,true,true);
				oTable20 = CrearDataTable("MedidoresNCTable", true, true, true);


				$('#InspectoresTable tbody').on( 'click', 'tr', function () {
					$(this).toggleClass('selected');

					if ($(this).hasClass('selected')){
						var anSelected = fnGetSelected(oTable11);
						$("#CodigoInspector").val(oTable11.fnGetData(anSelected[0],0));
						$("#CedulaInspector").val(oTable11.fnGetData(anSelected[0],1));
						$("#NombreInspector").val(oTable11.fnGetData(anSelected[0],2));
						$("#TelefonoInspector").val(oTable11.fnGetData(anSelected[0],3));						
					}else{
						$(".DatosInspector").val("");
					}
				});

				$('#BluetoothTable tbody').on( 'click', 'tr', function () {
					$(this).toggleClass('selected');
				});

				$('#AnomaliasTable tbody').on( 'click', 'tr', function () {
					$(this).toggleClass('selected');
				});

				$('#DepartamentosTable tbody').on( 'click', 'tr', function () {
					$(this).toggleClass('selected');
				});

				$('#MunicipiosTable tbody').on( 'click', 'tr', function () {
					$(this).toggleClass('selected');
				});

				$('#CiclosTable tbody').on( 'click', 'tr', function () {
					$(this).toggleClass('selected');
				});

				$('#CodMensajesTable tbody').on( 'click', 'tr', function () {
					$(this).toggleClass('selected');
				});

				$('#CriticaTable tbody').on( 'click', 'tr', function () {
					$(this).toggleClass('selected');
				});

				$('#FiltroMacroTable tbody').on( 'click', 'tr', function () {
					$(this).toggleClass('selected');
				});

				$('#MedidoresNCTable tbody').on( 'click', 'tr', function () {
					$(this).toggleClass('selected');
				});



				/*******************************/
				$("#ConsultarMedidoresNC").click(function(){
					ConsultarMedidoresNC();
				})


				$("#CrearMedidorNC").click(function(){
					if (($("#MarcaMedidorNC").val()=="")||($("#DescripcionMedidorNC").val()=="")){
						alert("Datos Incompletos.");
					}else{   
						CrearMedidorNC();
					}
				})


				$("#EliminarMedidoresNC").click(function(){
					EliminarMedidoresNC();
				})


				/*******************************/




				$("#ConsultarBluetooth").click(function(){
					ConsultarBlueooth();
				})


				$("#CrearBluetooth").click(function(){
					if (($("#CodigoBluetooth").val()=="")||($("#DescripcionBluetooth").val()=="")){
						alert("Datos Incompletos.");
					}else{   
						CrearBluetooth();
					}
				})


				$("#EliminarBluetooth").click(function(){
					EliminarBluetooth();
				})


				$("#ConsultarInspectores").click(function(){
					ConsultarInspectores();
				})


				$("#CrearInspector").click(function(){
					if (($("#CodigoInspector").val()=="")||($("#NombreInspector").val()=="")||($("#CedulaInspector").val()=="")){
						alert("Datos Incompletos.");
					}else{ 
						CrearInspector();
					}
				})


				$("#EliminarInspectores").click(function(){
					EliminarInspectores();
				})


				$("#ConsultarAnomalias").click(function(){
					ConsultarAnomalias();
				})


				$("#CrearAnomalia").click(function(){
					var DatosCompletos = true;
					$(".DatosTxtAnomalia").each(function(){
						if($(this).val() == ""){
							DatosCompletos = false;
						}
					});

					$(".DatosCmbAnomalia").each(function(){
						if($(this).val() == -1){
							DatosCompletos = false;
						}
					})

					if (DatosCompletos == false){
						alert("Datos Incompletos.");
					}else{ 
						CrearAnomalia();
					}
				})


				$("#EliminarAnomalias").click(function(){
					EliminarAnomalias();
				})


				$("#ConsultarDepartamentos").click(function(){
					ConsultarDepartamentos();
				})

				$("#CrearDepartamento").click(function(){
					if (($("#CodigoDepartamento").val()=="")||($("#nombre_departamento").val()=="")){
						alert("Datos Incompletos.");
					}else{   
						CrearDepartamento();
					}
				})

				$("#EliminarDepartamentos").click(function(){
					EliminarDepartamentos();
				})


				$("#ConsultarMunicipios").click(function(){
					ConsultarMunicipios();
				})


				$("#CrearMunicipio").click(function(){
					if (($("#CodigoMunicipio").val()=="")||($("#NombreMunicipio").val()=="")){
						alert("Datos Incompletos.");
					}else{   
						CrearMunicipio();
					}
				})


				$("#EliminarMunicipios").click(function(){
					EliminarMunicipios();
				})


				$("#ConsultarCiclos").click(function(){
					ConsultarCiclos();
				})


				$("#CrearCiclo").click(function(){
					if (($("#CodigoCiclo").val()=="")||($("#TipoCiclo").val()=="")){
						alert("Datos Incompletos.");
					}else{   
						CrearCiclo();
					}
				})


				$("#EliminarCiclos").click(function(){
					EliminarCiclos();
				})


				$("#ConsultarCodMensajes").click(function(){
					ConsultarMensajes();
				})


				$("#CrearMensaje").click(function(){
					if (($("#CodigoMensaje").val() == "")||($("#DescripcionMensaje").val() == "")){
						alert("Datos Incompletos.");
					}else{   
						CrearMensaje();
					}
				})


				$("#EliminarCodMensajes").click(function(){
					EliminarCodMensajes();
				})


				$("#ConsultarCriticas").click(function(){
					ConsultarCriticas();
				})


				$("#CrearCritica").click(function(){
					if (($("#MinimoCritica").val() == "")||($("#MaximoCritica").val() == "")||($("#DescripcionCritica").val() == "")){
						alert("Datos Incompletos.");
					}else{   
						CrearCritica();
					}
				})


				$("#EliminarCriticas").click(function(){
					EliminarCriticas();
				})


				$("#ConsultarFiltroMacro").click(function(){
					ConsultarFiltroMacro();
				})


				$("#CrearFiltroMacro").click(function(){
					if (($("#SiglaMacro").val()=="")||($("#DescripcionMacro").val()=="")){
						alert("Datos Incompletos.");
					}else{   
						CrearFiltroMacro();
					}
				});


				$("#EliminarFiltroMacro").click(function(){
					EliminarFiltroMacro();
				});


				/********************************************/
				function ConsultarMedidoresNC(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json",
								url: 			"../Ajax/AjaxParametros.php", 
								data: 		{	Peticion: 	"ConsultarMedidoresNC"
											}, 
								success: function(data){ 	
									MostrarTabla(oTable20,data);
								} 
							});	
				}	


				function CrearMedidorNC(){
					$.ajax({    async:  false,
								type:   "POST",
								url:    "../Ajax/AjaxParametros.php",
								data:   {   Peticion: 		"CrearMedidorNC",
											Marca:  		$("#MarcaMedidorNC").val(),
											Descripcion: 	$("#DescripcionMedidorNC").val()
										},success: function(data){ 	
											if(data==1){
												$(".DatosMedidorNC").val(""); 
												alert('Marca medidor registrada correctamente.');
												ConsultarMedidoresNC();
											}else{
												alert('Error, no se pudo registrar la marca del medidor.');
											}						
										}
					});
				}


				function EliminarMedidoresNC(){
					var InfSeleccionada 	= InfTablaSelectedToJSON(oTable20,"ListaMarcaNC",["id"],[0]);
					$.ajax({    async:  	false,
								type:   	"POST",
								dataType: 	"json",
								url:    	"../Ajax/AjaxParametros.php",
								data:   {   Peticion: 		"EliminarMedidoresNC",
											ListaMarca: 	InfSeleccionada				 			
										},
								success: function(data){ 	
									MostrarTabla(oTable20,data);
								}
					});
				}

				/*********************************************/



				function ConsultarBlueooth(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json",
								url: 			"../Ajax/AjaxParametros.php", 
								data: 		{	Peticion: 	"ConsultarBluetooth"
											}, 
								success: function(data){ 	
									MostrarTabla(oTable12,data);
								} 
							});	
				}	

				function CrearBluetooth(){
					$.ajax({    async:  false,
								type:   "POST",
								url:    "../Ajax/AjaxParametros.php",
								data:   {   Peticion: 		"CrearBluetooth",
											Codigo:  		$("#CodigoBluetooth").val(),
											Descripcion: 	$("#DescripcionBluetooth").val()
										},success: function(data){ 	
											if(data==1){
												$(".DatosBluetooth").val(""); 
												alert('Codigo bluetooth registrado correctamente.');
												ConsultarBlueooth();
											}else{
												alert('Error, no se pudo registrar el codigo del bluetooth.');
											}						
										}
					});
				}

				function EliminarBluetooth(){
					var InfSeleccionada 	= InfTablaSelectedToJSON(oTable12,"ListaBluetooth",["codigo"],[0]);
					$.ajax({    async:  	false,
								type:   	"POST",
								dataType: 	"json",
								url:    	"../Ajax/AjaxParametros.php",
								data:   {   Peticion: 			"EliminarBluetooth",
											ListaBluetooth: 	InfSeleccionada				 			
										},
								success: function(data){ 	
									MostrarTabla(oTable12,data);
								}
					});
				}

				function ConsultarInspectores(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json",
								url: 			"../Ajax/AjaxParametros.php", 
								data: 		{	Peticion: 	"ConsultarInspector"
											}, 
								success: function(data){ 	
									MostrarTabla(oTable11,data);
								} 
							});
				}

				function CrearInspector(){
					$.ajax({    async:  false,
								type:   "POST",
								url:    "../Ajax/AjaxParametros.php",
								data:   {   Peticion: 	"CrearInspector",
											Codigo:  	$("#CodigoInspector").val(),
											Nombre:  	$("#NombreInspector").val(),
											Cedula:  	$("#CedulaInspector").val(),
											Celular: 	$("#TelefonoInspector").val(),
											Tipo: 		$("#TipoInspector option:selected").val()
										},success: function(data){ 	
											if(data == 1){
												$(".DatosInspector").val(""); 
												alert('Inspector Creado Correctamente.');
												ConsultarInspectores();
											}else if(data == -1){
												alert('El codigo del inspector ya fue usado.');
											}else{
												alert('Error, no se pudo crear Inspector.');
											}						
										}
					});
				}

				function EliminarInspectores(){
					var InfInspectorSeleccionada 	= InfTablaSelectedToJSON(oTable11,"ListaSeleccionadas",["IdInspector"],[0]);
					$.ajax({    async:  	false,
								type:   	"POST",
								dataType: 	"json",
								url:    	"../Ajax/AjaxParametros.php",
								data:   {   Peticion: 	"EliminarInspector",
											IdInspectorSeleccionado: InfInspectorSeleccionada				 			
										},
								success: function(data){ 
									MostrarTabla(oTable11,data);
								}
					});
				}

				function ConsultarAnomalias(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json",
								url: 			"../Ajax/AjaxParametros.php", 
								data: 		{	Peticion: 	"ConsultarAnomalias"
											}, 
								success: function(data){ 	
									MostrarTabla(oTable13,data);
								} 
							});	
				}

				function CrearAnomalia(){
					$.ajax({    async:  false,
								type:   "POST",
								url:    "../Ajax/AjaxParametros.php",
								data:   {   Peticion: 		"CrearAnomalia",
											Codigo:  	    $("#CodigoAnomalia").val(),
											Descripcion: 	$("#DescripcionAnomalia").val(),
											Residencial: 	$("#AplicaResidencialAnomalia option:selected").val(),
											No_Residencial:	$("#AplicaNoResidencialAnomalia option:selected").val(),
											Toma_lectura:   $("#TomaLecturaAnomalia option:selected").val(),
											Mensaje:        $("#MensajeAnomalia option:selected").val(),
											Foto:           $("#FotoAnomalia option:selected").val()
										},success: function(data){ 	
											if(data==1){
												$(".DatosTxtAnomalia").val(""); 
												$(".DatosCmbAnomalia").val(-1);
												alert('Anomalia Creada Correctamente.');
												ConsultarAnomalias();
											}else{
												alert('Error, no se pudo crear la Anomalia.');
											}						
										}
					});
				}

				function EliminarAnomalias(){
					var InfAnomaliaSeleccionada 	= InfTablaSelectedToJSON(oTable13,"ListaSeleccionadas",["Idanomalia"],[0]);
					$.ajax({    async:  	false,
								type:   	"POST",
								dataType: 	"json",
								url:    	"../Ajax/AjaxParametros.php",
								data:   {   Peticion: 	"EliminarAnomalia",
											IdAnomalia: InfAnomaliaSeleccionada				 			
										},
								success: function(data){ 	
									MostrarTabla(oTable13,data);
								}
					});
				}

				function ConsultarDepartamentos(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json",
								url: 			"../Ajax/AjaxParametros.php", 
								data: 		{	Peticion: 	"ConsultarDepartamentos"
											}, 
								success: function(data){ 	
									MostrarTabla(oTable14,data);
								} 
							});
				}

				function CrearDepartamento(){
					$.ajax({    async:  false,
								type:   "POST",
								url:    "../Ajax/AjaxParametros.php",
								data:   {   Peticion: 	"CrearDepartamentos",
											Codigo:  	$("#CodigoDepartamento").val(),
											Nombre:  	$("#NombreDepartamento").val()
										},success: function(data){ 	
											if(data==1){
												$(".DatosDepartamento").val(""); 
												alert('Departamento Creado Correctamente.');
												ConsultarDepartamentos();
											}else{
												alert('Error, no se pudo crear Departamento.');
											}						
										}
					});
				}

				function EliminarDepartamentos(){
					var InfDepaSeleccionada 	= InfTablaSelectedToJSON(oTable14,"ListaSeleccionadas",["IdDepartamento"],[0]);
					$.ajax({    async:  	false,
								type:   	"POST",
								dataType: 	"json",
								url:    	"../Ajax/AjaxParametros.php",
								data:   {   Peticion: 	"EliminarDepartamentos",
											IdDepartamentoSeleccionado: InfDepaSeleccionada				 			
										},
								success: function(data){ 	
									MostrarTabla(oTable14,data);
								}
					});
				}

				function ConsultarMunicipios(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json",
								url: 			"../Ajax/AjaxParametros.php", 
								data: 		{	Peticion: 	"ConsultarMunicipio"
											}, 
								success: function(data){ 	
									MostrarTabla(oTable15,data);
								} 
					});	
				}

				function CrearMunicipio(){
					$.ajax({    async:  false,
								type:   "POST",
								url:    "../Ajax/AjaxParametros.php",
								data:   {   Peticion: 	"CrearMunicipio",
											Codigo:  	$("#CodigoMunicipio").val(),
											Nombre:  	$("#NombreMunicipio").val()
										},success: function(data){ 	
											if(data==1){
												$(".DatosMunicipio").val(""); 
												alert('Municipio Creado Correctamente.');
												ConsultarMunicipios();
											}else{
												alert('Error, no se pudo crear Municipio.');
											}						
										}
						});
				}

				function EliminarMunicipios(){
					var InfMuniSeleccionada 	= InfTablaSelectedToJSON(oTable15,"ListaSeleccionadas",["IdMunicipio"],[0]);
					$.ajax({    async:  	false,
								type:   	"POST",
								dataType: 	"json",
								url:    	"../Ajax/AjaxParametros.php",
								data:   {   Peticion: 	"EliminarMunicipio",
											IdMunicipioSeleccionado: InfMuniSeleccionada				 			
										},
								success: function(data){ 	
									MostrarTabla(oTable15,data);
								}
					});
				}

				function ConsultarCiclos(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json",
								url: 			"../Ajax/AjaxParametros.php", 
								data: 		{	Peticion: 	"ConsultarCiclos"
											}, 
								success: function(data){ 	
									MostrarTabla(oTable16,data);
								} 
					});
				}

				function CrearCiclo(){
					$.ajax({    async:  false,
								type:   "POST",
								url:    "../Ajax/AjaxParametros.php",
								data:   {   Peticion: 		"CrearCiclo",
											Codigo:  	    $("#CodigoCiclo").val(),
											Descripcion: 	$("#TipoCiclo").val()
										},success: function(data){ 	
											if(data==1){
												$("#CodigoCiclo").val(""); 
												$("#TipoCiclo").val("");
												alert('Ciclo Creada Correctamente.');
												ConsultarCiclos();
											}else{
												alert('Error, no se pudo crear el Ciclo.');
											}						
										}
					});
				}

				function EliminarCiclos(){
					var InfCiclosSeleccionados	= InfTablaSelectedToJSON(oTable16,"ListaSeleccionadas",["IdCiclo"],[0]);
					$.ajax({    async: 	 	false,
								type:   	"POST",
								dataType: 	"json",
								url:    	"../Ajax/AjaxParametros.php",
								data:   {   Peticion: 			"EliminarCiclo",
											IdCicloSeleccionado: InfCiclosSeleccionados				 			
										},
	                            success: function(data){ 	
	                            	MostrarTabla(oTable16,data);
								}
					});
				}

				function ConsultarMensajes(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json",
								url: 			"../Ajax/AjaxParametros.php", 
								data: 		{	Peticion: 	"ConsultarMensajes"
											}, 
								success: function(data){ 	
									MostrarTabla(oTable17,data);
								} 
					});	
				}

				function CrearMensaje(){
					$.ajax({    async:  false,
									type:   "POST",
									url:    "../Ajax/AjaxParametros.php",
									data:   {   Peticion: 		"CrearMensaje",
												Codigo:  		$("#CodigoMensaje").val(),
												Descripcion: 	$("#DescripcionMensaje").val(),
												Macro: 			$("#TipoMensaje option:selected").val()
											},success: function(data){ 	
												if(data==1){
													$(".DatosMensaje").val(""); 
													$("#TipoMensaje").val("-1");
													alert('Mensaje Creado Correctamente.');
													ConsultarMensajes();
												}else{
													alert('Error, no se pudo crear el mensaje.');
												}						
											}
					});
				}

				function EliminarCodMensajes(){
					var InfSeleccionada 	= InfTablaSelectedToJSON(oTable17,"ListaMensajes",["codigo"],[0]);
					$.ajax({    async:  	false,
								type:   	"POST",
								dataType: 	"json",
								url:    	"../Ajax/AjaxParametros.php",
								data:   {   Peticion: 		"EliminarMensajes",
											ListaMensajes: 	InfSeleccionada				 			
										},
	                            success: function(data){ 	
	                            	MostrarTabla(oTable17,data);
	                            }
					});
				}

				function ConsultarCriticas(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json",
								url: 			"../Ajax/AjaxParametros.php", 
								data: 		{	Peticion: 	"ConsultarCriticas"
											}, 
								success: function(data){ 	
									MostrarTabla(oTable18,data);
								} 
							});	
				}

				function CrearCritica(){
					$.ajax({    async:  false,
								type:   "POST",
								url:    "../Ajax/AjaxParametros.php",
								data:   {   Peticion: 	"CrearCriticas",
											RangoMinimo:  	$("#MinimoCritica").val(),
											RangoMaximo:  	$("#MaximoCritica").val(),
											Descripcion: 	$("#DescripcionCritica").val()
										},success: function(data){ 	
											if(data==1){
												$(".DatosCritica").val(""); 
												alert('Critica Creada Correctamente.');
												ConsultarCriticas();
											}else{
												alert('Error, no se pudo crear la Critica.');
											}						
										}
					});
				}

				function EliminarCriticas(){
					var InfCriticaSeleccionada 	= InfTablaSelectedToJSON(oTable18,"ListaSeleccionadas",["IdCritica"],[0]);
					$.ajax({    async:  	false,
								type:   	"POST",
								dataType: 	"json",
								url:   		"../Ajax/AjaxParametros.php",
								data:   {   Peticion: 	"EliminarCriticas",
											IdCriticaSeleccionado: InfCriticaSeleccionada				 			
										},
	                            success: function(data){ 	
	                            	MostrarTabla(oTable18,data);
								}
					});
				}

				function ConsultarFiltroMacro(){
					$.ajax({ 	async: 			false, 
								type: 			"POST", 
								dataType: 		"json",
								url: 			"../Ajax/AjaxParametros.php", 
								data: 		{	Peticion: 	"ConsultarSiglas"
											}, 
								success: function(data){ 	
									MostrarTabla(oTable19,data);
								} 
					});
				}

				function CrearFiltroMacro(){
					$.ajax({    async:  false,
									type:   "POST",
									url:    "../Ajax/AjaxParametros.php",
									data:   {   Peticion: 	"CrearSigla",
												Codigo:  	    $("#SiglaMacro").val(),
												Descripcion: 	$("#DescripcionMacro").val()
											},success: function(data){ 	
												if(data==1){
													$(".DatosMacro").val(""); 
													alert('Sigla Creada Correctamente.');
													ConsultarFiltroMacro();
												}else{
													alert('Error, no se pudo crear la Sigla.');
												}						
											}
						});
				}

				function EliminarFiltroMacro(){
					var InfSiglaSeleccionada 	= InfTablaSelectedToJSON(oTable19,"ListaSeleccionadas",["IdSigla"],[0]);
					$.ajax({    async:  	false,
								type:   	"POST",
								dataType: 	"json",
								url:    	"../Ajax/AjaxParametros.php",
								data:   {   Peticion: 	"EliminarSigla",
											IdSiglaSeleccionado: InfSiglaSeleccionada				 			
										},
	                            success: function(data){ 	
	                            	MostrarTabla(oTable19,data);
								}
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
						<h2>SYPELC - Parametros</h2>	
					</div>
					<div class="col-sm-8 col-md-8">
						<div id="navbar" class="navbar-collapse collapse">
							<ul class="nav navbar-nav navbar-right">
								<?php $FcnUsuario->AccesoPaginas("Parametros"); ?>
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
					<?php $FcnUsuario->AccesoModulos("Parametros"); ?>
				</ul>

				<div class="tab-content">
					<?php 
					if(isset($_SESSION['Accesos']['Parametros']['param_dist_geografica'])){ ?>
						<div id="param_dist_geografica" class="tab-pane fade" height="100%">
							<div class="row">
								<div class="col-md-4">
									<div class="panel panel-success table-responsive">
										<div class="panel-heading">Admin Departamentos</div>
										<div class="panel-body">
											<div class="row">
												<div class="form-group">
													<div class="col-md-4">													
														<label class="sr-only" for="CodigoDepartamento">Codigo</label>
														<input class="form-control DatosDepartamento" id="CodigoDepartamento" type="text" placeholder="Codigo">
													</div>
													<div class="col-md-8">	
														<label class="sr-only" for="NombreDepartamento">Nombre</label>
														<input class="form-control DatosDepartamento" id="NombreDepartamento" type="text" placeholder="Nombre Departamento"> 
													</div>
												</div>
												<br>
												<div class="form-group">
													<div class="col-md-12">
														<button id="CrearDepartamento" type="button" class="btn btn-success btn-md pull-right">Crear Dpto</button>
													</div>
												</div>					
											</div>
											<br>
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<table id="DepartamentosTable" class="table table-condensed" cellspacing="0" width="99%">
															<thead>
																<tr class="info"> 
																	<th width="25%">Codigo</th>
																	<th width="75%">Departamento</th>
																</tr>
															</thead>
															<tbody>							
															</tbody>
														</table>	
													</div>

													<div class="form-group">
														<button id="ConsultarDepartamentos" type="button" class="btn btn-primary btn-md pull-right">Consultar Dpto</button>
														<button id="EliminarDepartamentos" type="button" class="btn btn-danger btn-md pull-left">Eliminar Dpto</button>
													</div>
												</div>		
											</div>				
										</div>
									</div>	
								</div> 

								<div class="col-md-4">
									<div class="panel panel-success table-responsive">
										<div class="panel-heading">Admin Municipios</div>
										<div class="panel-body">
											<div class="row">
												<div class="form-group">
													<div class="col-md-4">													
														<label class="sr-only" for="CodigoMunicipio">Codigo</label>
														<input class="form-control DatosMunicipio" id="CodigoMunicipio" type="text" placeholder="Codigo">
													</div>
													<div class="col-md-8">	
														<label class="sr-only" for="NombreMunicipio">Nombre</label>
														<input class="form-control DatosMunicipio" id="NombreMunicipio" type="text" placeholder="Nombre Municipio"> 
													</div>
												</div>
												<br>
												<div class="form-group">
													<div class="col-md-12">
														<button id="CrearMunicipio" type="button" class="btn btn-success btn-md pull-right">Crear Municipio</button>
													</div>
												</div>					
											</div>
											<br>
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<table id="MunicipiosTable" class="table table-condensed" cellspacing="0" width="99%">
															<thead>
																<tr class="info"> 
																	<th width="25%">Codigo</th>
																	<th width="75%">Municipio</th>
																</tr>
															</thead>
															<tbody>							
															</tbody>
														</table>	
													</div>

													<div class="form-group">
														<button id="ConsultarMunicipios" type="button" class="btn btn-primary btn-md pull-right">Consultar</button>
														<button id="EliminarMunicipios" type="button" class="btn btn-danger btn-md pull-left">Eliminar</button>
													</div>
												</div>		
											</div>				
										</div>
									</div>	
								</div> 

								<div class="col-md-4">
									<div class="panel panel-success table-responsive">
										<div class="panel-heading">Admin Ciclos</div>
										<div class="panel-body">
											<div class="row">
												<div class="form-group">
													<div class="col-md-4">													
														<label for="CodigoCiclo">Ciclo</label>
														<input class="form-control DatosCiclo" id="CodigoCiclo" type="text" placeholder="# Ciclo">
													</div>
													<div class="col-md-8">	
														<label for="TipoCiclo">Tipo</label>
														<select class="form-control" id="TipoCiclo">
															<option value="">...</option>
															<option value="URBANO">URBANO</option>
															<option value="RURAL">RURAL</option>
															<option value="DESTACADOS">DESTACADOS</option>
														</select>
													</div>	
												</div>
												<br>
												<div class="form-group">
													<div class="col-md-12">
														<button id="CrearCiclo" type="button" class="btn btn-success btn-md pull-right">Crear Ciclo</button>
													</div>
												</div>					
											</div>
											<br>
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<table id="CiclosTable" class="table table-condensed" cellspacing="0" width="99%">
															<thead>
																<tr class="info"> 
																	<th width="25%">Ciclo</th>
																	<th width="75%">Tipo</th>
																</tr>
															</thead>
															<tbody>							
															</tbody>
														</table>	
													</div>

													<div class="form-group">
														<button id="ConsultarCiclos" type="button" class="btn btn-primary btn-md pull-right">Consultar</button>
														<button id="EliminarCiclos" type="button" class="btn btn-danger btn-md pull-left">Eliminar</button>
													</div>
												</div>		
											</div>				
										</div>
									</div>	
								</div>
							</div>
						</div>
					<?php }
					if(isset($_SESSION['Accesos']['Parametros']['param_anomalias'])){ ?>
						<div id="param_anomalias" class="tab-pane fade" height="100%" >
							<div class="row">
								<div class="col-md-4">
									<div class="panel panel-success table-responsive">
										<div class="panel-heading">Creacion de Anomalias</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<label class="sr-only" for="CodigoAnomalia">Codigo Anomalia</label>
														<input class="form-control DatosTxtAnomalia" id="CodigoAnomalia" type="text" placeholder="Codigo Anomalia">
													</div>

													<div class="form-group">
														<label class="sr-only" for="DescripcionAnomalia">Descripcion Anomalia</label>
														<input class="form-control DatosTxtAnomalia" id="DescripcionAnomalia" type="text" placeholder="Descripcion Anomalia"> 
													</div>

													<div class="form-horizontal">
														<div class="form-group">
															<label class="control-label col-md-7" for="AplicaResidencialAnomalia">Aplica Residencial</label>
															<div class="col-md-5">
																<select class="form-control DatosCmbAnomalia" id="AplicaResidencialAnomalia"> 
																	<option value="-1">...</option>
																	<option value="TRUE">SI</option>
																	<option value="FALSE">NO</option>
																</select>
															</div>
														</div>
													</div>	

													<div class="form-horizontal">
														<div class="form-group">
															<label class="control-label col-md-7" for="AplicaNoResidencialAnomalia">Aplica No Residencial</label>
															<div class="col-md-5">
																<select class="form-control DatosCmbAnomalia" id="AplicaNoResidencialAnomalia"> 
																	<option value="-1">...</option>
																	<option value="TRUE">SI</option>
																	<option value="FALSE">NO</option>
																</select>
															</div>
														</div>
													</div>

													<div class="form-horizontal">
														<div class="form-group">
															<label class="control-label col-md-7" for="TomaLecturaAnomalia">Toma Lectura</label>
															<div class="col-md-5">
																<select class="form-control DatosCmbAnomalia" id="TomaLecturaAnomalia">
																	<option value="-1">...</option>
																	<option value="TRUE">SI</option>
																	<option value="FALSE">NO</option>
																</select> 
															</div>
														</div>	
													</div>

													<div class="form-horizontal">
														<div class="form-group">
															<label class="control-label col-md-7" for="MensajeAnomalia">Mensaje</label>
															<div class="col-md-5">
																<select class="form-control DatosCmbAnomalia" id="MensajeAnomalia">
																	<option value="-1">...</option>
																	<option value="S">SI</option>
																	<option value="O">OPCIONAL</option>
																	<option value="N">NO</option>
																</select> 
															</div>	
														</div>
													</div>

													<div class="form-horizontal">
														<div class="form-group">
															<label class="control-label col-md-7" for="FotoAnomalia">Foto</label>
															<div class="col-md-5">
																<select class="form-control DatosCmbAnomalia" id="FotoAnomalia">
																	<option value="-1">...</option>
																	<option value="TRUE">SI</option>
																	<option value="FALSE">NO</option>
																</select> 
															</div>
														</div>	
													</div>
												
													<div class="form-group">
														<button id="CrearAnomalia" type="button" class="btn btn-success btn-md pull-right">Crear Anomalia</button>
													</div>	
												</div>					
											</div>		
										</div>
									</div>	
								</div> 

								<div class="col-md-8">
									<div class="panel panel-success table-responsive">
										<div class="panel-heading">Tabla de Anomalias</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<table id="AnomaliasTable" class="table table-condensed" cellspacing="0" width="99%">
															<thead>
																<tr class="info"> 
																	<th width="10%">Id</th>
																	<th width="40%">Descripcion</th>
																	<th width="10%">Resi</th>
																	<th width="10%">No Resi</th>
																	<th width="10%">Lect.</th>
																	<th width="10%">Msj.</th>
																	<th width="10%">Foto</th>
																</tr>
															</thead>
															<tbody>							
															</tbody>
														</table>	
													</div>

													<div class="form-group">
														<button id="EliminarAnomalias" type="button" class="btn btn-danger btn-md pull-left">Eliminar Anomalias</button>
														<button id="ConsultarAnomalias" type="button" class="btn btn-primary btn-md pull-right">Consultar Anomalias</button>
													</div>
												</div>		
											</div>				
										</div>
									</div>	
								</div>
							</div> 						
						</div>
					<?php }
					if(isset($_SESSION['Accesos']['Parametros']['param_vali_lecturas'])){ ?>
						<div id="param_vali_lecturas" class="tab-pane fade" height="100%" >
							<div class="row">
								<div class="col-md-3">
									<div class="panel panel-success table-responsive">
										<div class="panel-heading">Codigo Mensajes</div>
										<div class="panel-body">
											<div class="row">
												<div class="form-group">
													<label class="sr-only" for="CodigoMensaje">Codigo Mensaje</label>
													<input class="form-control DatosMensaje" id="CodigoMensaje" type="text" placeholder="Codigo Mensaje">
												</div>

												<div class="form-group">
													<label class="sr-only" for="DescripcionMensaje">Descripcion Mensaje</label>
													<input class="form-control DatosMensaje" id="DescripcionMensaje" type="text" placeholder="Descripcion Mensaje"> 
												</div>

												<div class="form-group">
													<div class="form-inline">
														<label class="control-label" for="TipoMensaje">Aplica A Macro</label>
														<select class="form-control" id="TipoMensaje">
															<option value = "-1">...</option>
															<option value = "0">No</option>
															<option value = "1">Si</option>
														</select> 
													</div>
												</div>

												<div class="form-group">
													<button id="CrearMensaje" type="button" class="btn btn-success btn-md pull-right">Crear Mensaje</button>
												</div>
											</div>
											<br>
											<div class="row">
												<div class="form-group">
													<table id="CodMensajesTable" class="table table-condensed" cellspacing="0" width="99%">
														<thead>
															<tr class="info"> 
																<th width="20%">Cod</th>
																<th width="60%">Descripcion</th>
																<th width="20%">Macro</th>
															</tr>
														</thead>
														<tbody>							
														</tbody>
													</table>	
												</div>

												<div class="form-group">
													<button id="ConsultarCodMensajes" type="button" class="btn btn-primary btn-md pull-right">Consultar</button>
													<button id="EliminarCodMensajes" type="button" class="btn btn-danger btn-md pull-left">Eliminar</button>
												</div>	
											</div>				
										</div>
									</div>	
								</div> 

								<div class="col-md-6">
									<div class="panel panel-success table-responsive">
										<div class="panel-heading">Administracion de Criticas</div>
										<div class="panel-body">
											<div class="row">
												<div class="form-group">
													<div class="col-md-3">
														<label class="sr-only" for="MinimoCritica">Rango Minimo</label>
														<input class="form-control DatosCritica" id="MinimoCritica" type="text" placeholder="Rango Minimo">
													</div>
									
													<div class="col-md-3">
														<label class="sr-only" for="MaximoCritica">Rango Maximo</label>
														<input class="form-control DatosCritica" id="MaximoCritica" type="text" placeholder="Rango Maximo"> 
													</div>

													<div class="col-md-6">
														<label class="sr-only" for="DescripcionCritica">Descripcion Critica</label>
														<input class="form-control DatosCritica" id="DescripcionCritica" type="text" placeholder="Descripcion Critica"> 
													</div>
												</div>
												<br>
												<div class="form-group">
													<div class="col-md-12">
														<button id="CrearCritica" type="button" class="btn btn-success btn-md pull-right">Crear Critica</button>
													</div>
												</div>
											</div>
											<br>
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<table id="CriticaTable" class="table table-condensed" cellspacing="0" width="99%">
															<thead>
																<tr class="info"> 
																	<th width="10%">Id</th>
																	<th width="35%">Rango Minimo</th>
																	<th width="35%">Rango Maximo</th>
																	<th width="20%">Descripcion</th>
																</tr>
															</thead>
															<tbody>							
															</tbody>
														</table>	
													</div>

													<div class="form-group">
														<button id="ConsultarCriticas" type="button" class="btn btn-primary btn-md pull-right">Consultar Criticas</button>
														<button id="EliminarCriticas" type="button" class="btn btn-danger btn-md pull-left">Eliminar Criticas</button>
													</div>
												</div>		
											</div>				
										</div>
									</div>	
								</div>

								<div class="col-md-3">
									<div class="panel panel-success table-responsive">
										<div class="panel-heading">Filtro Macro</div>
										<div class="panel-body">
											<div class="row">
												<div class="form-group">
													<label class="sr-only" for="SiglaMacro">Sigla Filtro Macro</label>
													<input class="form-control DatosMacro" id="SiglaMacro" type="text" placeholder="Sigla Filtro Macro">
												</div>

												<div class="form-group">
													<label class="sr-only" for="DescripcionMacro">Descripcion Macro</label>
													<input class="form-control DatosMacro" id="DescripcionMacro" type="text" placeholder="Descripcion Macro"> 
												</div>

												<div class="form-group">
													<button id="CrearFiltroMacro" type="button" class="btn btn-success btn-md pull-right">Crear Filtro</button>
												</div>
											</div>
											<br>


											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<table id="FiltroMacroTable" class="table table-condensed" cellspacing="0" width="99%">
															<thead>
																<tr class="info"> 
																	<th width="23%">Sigla</th>
																	<th width="70%">Descripcion</th>
																</tr>
															</thead>
															<tbody>							
															</tbody>
														</table>	
													</div>

													<div class="form-group">
														<button id="ConsultarFiltroMacro" type="button" class="btn btn-primary btn-md pull-right">Consultar</button>
														<button id="EliminarFiltroMacro" type="button" class="btn btn-danger btn-md pull-left">Eliminar</button>
													</div>
												</div>		
											</div>				
										</div>
									</div>	
								</div>
							</div> 						
						</div>
					<?php }
					if(isset($_SESSION['Accesos']['Parametros']['param_autenticacion'])){ ?>
						<div id="param_autenticacion" class="tab-pane fade" height="100%" >
							<div class="row">
								<div class="col-md-2">
									<div class="panel panel-success table-responsive">
										<div class="panel-heading">Crear Inspector</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<label class="sr-only" for="CodigoInspector">Codigo</label>
														<input class="form-control DatosInspector" id="CodigoInspector" type="text" placeholder="Codigo">
													</div>

													<div class="form-group">
														<label class="sr-only" for="CedulaInspector">Cedula</label>
														<input class="form-control DatosInspector" id="CedulaInspector" type="text" placeholder="Cedula">
													</div>

													<div class="form-group">
														<label class="sr-only" for="NombreInspector">Nombre</label>
														<input class="form-control DatosInspector" id="NombreInspector" type="text" placeholder="Nombre"> 
													</div>


													<div class="form-group">
														<label class="sr-only" for="TelefonoInspector">Telefono</label>
														<input class="form-control DatosInspector" id="TelefonoInspector" type="text" placeholder="Telefono"> 
													</div>

													<div class="form-group">
														<div class="form-inline">
															<label class="control-label" for="TipoInspector">Tipo</label>
															<select class="form-control" id="TipoInspector">
															<?php
																$_ciclos = json_decode($FcnParametros->getTipoInspector());
																foreach($_ciclos as $obj){
																	echo "<option value='".$obj->id_serial."'>".$obj->descripcion."</option>";											   
																}
															?> 
															</select> 
														</div>
													</div>

													<div class="form-group">
														<button id="CrearInspector" type="button" class="btn btn-block btn-success btn-md">Crear Inspector</button>
													</div>	
												</div>					
											</div>		
										</div>
									</div>	
								</div> 

								<div class="col-md-6">
									<div class="panel panel-success table-responsive">
										<div class="panel-heading">Consulta de Inspectores & Verificadores</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<table id="InspectoresTable" class="table table-condensed" cellspacing="0" width="99%">
															<thead>
																<tr class="info"> 
																	<th width="10%">Cod.</th>
																	<th width="20%">Cedula</th>
																	<th width="40%">Nombre</th>
																	<th width="20%">Telefono</th>																	
																	<th width="10%">Tipo</th>
																</tr>
															</thead>
															<tbody>							
															</tbody>
														</table>	
													</div>

													<div class="form-group">
														<button id="ConsultarInspectores" type="button" class="btn btn-primary btn-md pull-right">Consultar Inspectores</button>
														<button id="EliminarInspectores" type="button" class="btn btn-danger btn-md pull-left">Eliminar Inspectores</span>														
														</button>
													</div>
												</div>		
											</div>				
										</div>
									</div>	
								</div> 

								<div class="col-md-4">
									<div class="panel panel-success table-responsive">
										<div class="panel-heading">Administracion Equipos Moviles</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<div class="form-inline">
															<label class="sr-only" for="CodigoBluetooth">Codigo Bluetooth</label>
															<input class="form-control DatosBluetooth" id="CodigoBluetooth" type="text" placeholder="Codigo Bluetooth">
															<button id="CrearBluetooth" type="button" class="btn btn-success btn-md">Registrar Bluetooth</button>
														</div>
													</div>

													<div class="form-group">
														<label class="sr-only" for="DescripcionBluetooth">Descripcion Equipo</label>
														<input class="form-control DatosBluetooth" id="DescripcionBluetooth" type="text" placeholder="Descripcion Equipo">
													</div>
												</div>
											</div>


											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<table id="BluetoothTable" class="table table-condensed" cellspacing="0" width="99%">
															<thead>
																<tr class="info"> 
																	<th width="25%">Codigo</th>
																	<th width="50%">Descripcion</th>
																	<th width="25%">Key</th>
																</tr>
															</thead>
															<tbody>							
															</tbody>
														</table>	
													</div>

													<div class="form-group">
														<button id="ConsultarBluetooth" type="button" class="btn btn-primary btn-md pull-right">Consultar Bluetooth's</button>
														<button id="EliminarBluetooth" type="button" class="btn btn-danger btn-md pull-left">Eliminar Bluetooth's</button>
													</div>
												</div>		
											</div>				
										</div>
									</div>	
								</div>
							</div> 							
						</div>
					<?php } 
					if(isset($_SESSION['Accesos']['Parametros']['param_medidores_nc'])){ ?>
						<div id="param_medidores_nc" class="tab-pane fade" height="100%" >
							<div class="row">
								<div class="col-md-2">
									<div class="panel panel-success table-responsive">
										<div class="panel-heading">Crear Medidor NC</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<label class="sr-only" for="MarcaMedidorNC">Marca Medidor</label>
														<input class="form-control DatosMedidorNC" id="MarcaMedidorNC" type="text" placeholder="Marca">
													</div>

													<div class="form-group">
														<label class="sr-only" for="DescripcionMedidorNC">Descripcion</label>
														<input class="form-control DatosMedidorNC" id="DescripcionMedidorNC" type="text" placeholder="Descripcion">
													</div>


													<div class="form-group">
														<button id="CrearMedidorNC" type="button" class="btn btn-block btn-success btn-md">Crear Medidor</button>
													</div>	
												</div>					
											</div>		
										</div>
									</div>	
								</div> 

								<div class="col-md-6">
									<div class="panel panel-success table-responsive">
										<div class="panel-heading">Consulta de Medidores NC</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<table id="MedidoresNCTable" class="table table-condensed" cellspacing="0" width="99%">
															<thead>
																<tr class="info"> 
																	<th width="10%">Id</th>
																	<th width="30%">Marca</th>
																	<th width="60%">Descripcion</th>
																</tr>
															</thead>
															<tbody>							
															</tbody>
														</table>	
													</div>

													<div class="form-group">
														<button id="ConsultarMedidoresNC" type="button" class="btn btn-primary btn-md pull-right">Consultar Medidores</button>
														<button id="EliminarMedidoresNC" type="button" class="btn btn-danger btn-md pull-left">Eliminar Medidores</span>														
														</button>
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
