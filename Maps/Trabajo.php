<?php
	session_start();
	include_once(dirname(__FILE__)."/../Clases/ClassUsuarios.php");
	include_once(dirname(__FILE__)."/../Clases/ClassConfiguracion.php");
	include_once(dirname(__FILE__)."/../Clases/ClassParametros.php");

	$FcnUsuario 		= new Usuario();
	$FcnConfiguracion	= new Configuracion();
	$FcnParametros		= new ClassParametros();

	if(!isset($_SESSION['Accesos']['Trabajo']))
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
				//CrearDatePicker("FechaPDA");

				//funciones para el control de la tabla en la pestaña de los empleados
				oTable11 = CrearDataTable("TablaSectorRuta",true,true);
				/*oTable12 = CrearDataTable("TableUsuarios",true,true);
				oTable13 = CrearDataTable("TablaAccesosUsuarios",false,false);
				oTable14 = CrearDataTable("TablaAccesosSupervisores",false,false);*/
				

				$('#TablaSectorRuta tbody').on( 'click', 'tr', function () {
					$(this).toggleClass('row_selected');
				});

				/*$('#TableUsuarios tbody').on( 'click', 'tr', function () {
					$(this).toggleClass('row_selected');
				});*/


				$("#ConsultarAsignacion").click(function(){
					var SendInformacionN =	$.ajax({    async:  	true,
														type:   	"POST",
														dataType: 	"json",
														url:    	"../Ajax/AjaxTrabajo.php",
														data:   {   Peticion: 	"ConsultarAsignacion",
																	Ciclo: 		$("#CicloAsignacion option:selected").val(),
																	Mes: 		$("#MesAsignacion option:selected").val(),
																	Anno: 		$("#AnnoAsignacion option:selected").val()
																},
														success:function(data){
															MostrarTabla(oTable11,data,["id","inspector","ruta","total","leidas","pendientes"]);
														}
													});

					SendInformacionN.fail(function(jqXHR, textStatus) {
						alert( "Error en la consulta de usuarios." );
					});
				})

				$("#ProgramarAsignacion").click(function(){
					var Id_Rutas 	= InfTablaSelectedToJSON(oTable11,"Id_Rutas",["id"],[0]);
					var SendInformacionN =	$.ajax({    async:  	true,
														type:   	"POST",
														url:    	"../Ajax/AjaxTrabajo.php",
														data:   {   Peticion: 	"AsignarTrabajo",
																	Inspector: 	$("#TecnicoAsignacion option:selected").val(),
																	Rutas: 		Id_Rutas 
																},
														success:function(data){
															alert(data);
														}
													});

					SendInformacionN.fail(function(jqXHR, textStatus) {
						alert( "Error de conexion con el servidor." );
					});
				})


				$("#EliminarAsignacion").click(function(){
					var Id_Rutas 	= InfTablaSelectedToJSON(oTable11,"Id_Rutas",["id"],[0]);
					var SendInformacionN =	$.ajax({    async:  	true,
														type:   	"POST",
														url:    	"../Ajax/AjaxTrabajo.php",
														data:   {   Peticion: 	"EliminarAsignacion",
																	Inspector: 	$("#TecnicoAsignacion option:selected").val(),
																	Rutas: 		Id_Rutas 
																},
														success:function(data){
															alert(data);
														}
													});

					SendInformacionN.fail(function(jqXHR, textStatus) {
						alert( "Error de conexion con el servidor." );
					});
				})

				$("#LecturaManuales").click(function(){
					var datos = [];
					var dato = GetColumnOfRowSelected(oTable11,2);

					datos = dato.split("-");
					var Municipio = datos[1];
					var Ruta      = datos[2];
					
					url = "../PDF/FormatoBalances.php?Mes="+$("#MesAsignacion option:selected").val()+"&Anno="+$("#AnnoAsignacion option:selected").val()+"&Ciclo="+$("#CicloAsignacion option:selected").val()+"&Municipio="+Municipio+"&Ruta="+Ruta;	
					window.open(url, '_blank');
					return false;
				})

				/*$('#TablaAccesosUsuarios tbody').on( 'click', 'tr', function () {
					if ( $(this).hasClass('row_selected') ) {
						var anSelected = fnGetSelected(oTable13);
						if(oTable13.fnGetData(anSelected[0],3)=="Si"){
							oTable13.fnUpdate("No", anSelected[0], 3);
						}else{
							oTable13.fnUpdate("Si", anSelected[0], 3);
						}
						$(this).removeClass('row_selected');

					}else {
						oTable13.$('tr.row_selected').removeClass('row_selected');
						$(this).addClass('row_selected');
					}
				});*/


				/*$("#CrearTecnico").click(function(){   
					if (($("#NodoEmpleado").val()=="")||($("#NombreEmpleado").val()=="")||($("#CedulaEmpleado").val()=="")){
						alert("Datos Incompletos");
					}else{ 
						$.ajax({    async:  false,
									type:   "POST",
									url:    "../Ajax/AjaxConfiguracion.php",
									data:   {   Peticion: 	"CrearTecnico",
												PDA:   		$("#CodigoPDA").val(),
												Codigo: 	$("#CodigoEmpleado").val(),
												Nombre: 	$("#NombreEmpleado").val(),
												Apellido: 	$("#ApellidoEmpleado").val(),
												Cedula: 	$("#CedulaEmpleado").val(),
												Fecha:  	$("#FechaPDA").val()
											},success: function(data){ 	
												if(data==1){
													$("#CodigoPDA").val(""); 
													$("#CodigoEmpleado").val("");
													$("#NombreEmpleado").val("");
													$("#ApellidoEmpleado").val("");
													$("#CedulaEmpleado").val("");
													$("#FechaPDA").val("");
													alert('Tecnico Creado Correctamente.');
												}else{
													alert('Error, no se pudo crear el tecnico.')
												}									
											}
						});
					}
				});*/


				/*$("#InsertarUsuario").click(function(){   
					if (($("#NombreUsuario").val()=="")||($("#CedulaUsuario").val()=="")||($("#NitUsuario").val()=="")||($("#ContrasenaUsuario").val()=="")){
						alert("Datos Incompletos.");
					}else if ($("#ContrasenaUsuario").val()!=$("#ContrasenaUsuario2").val()){
						alert("Error de coincidencia de contraseñas.");
					}else{   
						$.ajax({    async:  false,
									type:   "POST",
									url:    "../Ajax/AjaxConfiguracion.php",
									data:   {   Peticion: 	"CrearUsuario",
												Nombre:  	$("#NombreUsuario").val(),
												Cedula: 	$("#CedulaUsuario").val(),
												Usuario: 	$("#NitUsuario").val(),
												Contrasena:	$("#ContrasenaUsuario").val()
											},success: function(data){ 	
												if(data==1){
													$("#CedulaUsuario").val(""); 
													$("#NombreUsuario").val("");
													$("#NitUsuario").val("");
													$("#ContrasenaUsuario").val("");
													$("#ContrasenaUsuario2").val("");
													alert('Usuario Creado Correctamente.');
												}else{
													alert('Error, no se pudo crear el tecnico.')
												}						
											}
						});
					}
				});*/


				


				/*$("#ConsultarTecnicos").click(function(){
					var SendInformacionN =	$.ajax({    async:  	true,
														type:   	"POST",
														dataType: 	"json",
														url:    	"../Ajax/AjaxConfiguracion.php",
														data:   {   Peticion: 	"ConsultarTecnicos"
																},
														success:function(data){
															MostrarTabla(oTable11,data,["pda","codigo_interno","nombre","apellido","cedula","fecha_pda"]);
														}
													});

					SendInformacionN.fail(function(jqXHR, textStatus) {
						alert( "Error en la consulta de usuarios." );
					});
				})*/


				/*$("#EliminarUsuario").click(function(){   
					var InfTablaUsuarios 	= InfTablaSelectedToJSON(oTable12,"ListaUsuarios",["Usuario"],[2]);
					var SendInformacionN =	$.ajax({    async:  	true,
														type:   	"POST",
														dataType: 	"json",
														url:    	"../Ajax/AjaxConfiguracion.php",
														data:   {   Peticion: 	"EliminarUsuarios",
																	Usuarios: 	InfTablaUsuarios 
																},
														success:function(data){
															MostrarTabla(oTable12,data,["cedula","nombre","username"]);
														}
													});

					SendInformacionN.fail(function(jqXHR, textStatus) {
						alert( "Error en la consulta de archivos incompletos." );
					});
				});*/


				/*$("#EliminarTecnicos").click(function(){
					var InfTablaTecnicos 	= InfTablaSelectedToJSON(oTable11,"ListaTecnicos",["pda"],[0]);
					var SendInformacionN =	$.ajax({    async:  	true,
														type:   	"POST",
														dataType: 	"json",
														url:    	"../Ajax/AjaxConfiguracion.php",
														data:   {   Peticion: 	"EliminarTecnicos",
																	Tecnicos: 	InfTablaTecnicos 
																},
														success:function(data){
															MostrarTabla(oTable11,data,["pda","codigo_interno","nombre","apellido","cedula","fecha_pda"]);
														}
													});

					SendInformacionN.fail(function(jqXHR, textStatus) {
						alert( "Error en la consulta de archivos incompletos." );
					});
				});*/


				/*$("#cfgAccesosUsuarios").change(function(){
					var SendInformacion =   $.ajax({    async:  	false, 
														dataType: 	"json", 
														type:  	 	"POST",
														url:    	"../Ajax/AjaxConfiguracion.php",
														data:   {	Peticion: 		"CargarAccesosUsuarios",
																	Username: 		$("#cfgAccesosUsuarios option:selected").val()
																},
														success:function(data){
															MostrarTabla(oTable13,data,["id_acceso","pagina","titulo_modulo","acceso"]);
														}
													});
					
					SendInformacion.fail(function(jqXHR, textStatus) {
						alert( "Error de conexion.");
					});
				})*/


				/*$("#cfgAccesosGuardar").click(function(){
					var InfTablaAccesos	= InfTablaToJSON(oTable13,"Accesos",["id","valor"],[0,3]);
					var SendInformacion =   $.ajax({    async:  	false,  
														type:  	 	"POST",
														url:    	"../Ajax/AjaxConfiguracion.php",
														data:   {	Peticion: 	"GuardarAccesoUsuario",
																	Username: 	$("#cfgAccesosUsuarios option:selected").val(),
																	Accesos: 	InfTablaAccesos 
																},
														success:function(data){
															alert(data);
														}
													});
						
					SendInformacion.fail(function(jqXHR, textStatus) {
						alert( "Error consultando tecnicos.");
					});
				})*/	
				
				$("#Configuracion").tabs();		        	
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
								<?php $FcnUsuario->AccesoPaginas("Trabajo"); ?>
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
							<div id="Configuracion">
								<ul>
									<?php $FcnUsuario->AccesoModulos("Trabajo"); ?>
								</ul>
								<?php 
								if(isset($_SESSION['Accesos']['Trabajo']['trabajo_programacion'])){ ?>								
									<div id="trabajo_programacion" height="80%">
										
										<label class="LabelInputBig" for="CodigoEmpleado">Codigo Interno:</label>
										<input type="text" id="CodigoEmpleado" size="8"/>  

										<div class="cleaner h05"></div>
										
										<label class="LabelInputBig" for="NombreEmpleado">Nombre:</label>
										<input type="text" id="NombreEmpleado" size="30"/>

										<div class="cleaner h05"></div>

										<label class="LabelInputBig" for="ApellidoEmpleado">Apellido:</label>
										<input type="text" id="ApellidoEmpleado" size="30"/> 

										<div class="cleaner h05"></div>

										<label class="LabelInputBig" for="CedulaEmpleado">Cedula:</label>
										<input type="text" id="CedulaEmpleado" size="10"/> 

										<div class="cleaner h05"></div>

										<label class="LabelInputBig" for="CedulaEmpleado">Password:</label>
										<input type="password" id="CedulaEmpleado" size="10"/> 

										<div class="cleaner h05"></div>

										<label class="LabelInputBig" for="FechaPDA">Fecha:</label>
										<input type="text" id="FechaPDA" size="12"/> 
										<div class="cleaner h05"></div>
										<center>
											<input type='button' id="ConsultarTecnicos" value='Consultar' />
											<input type='button' id="CrearTecnico" value='Agregar' />
											<input type='button' id="EliminarTecnicos" value='Eliminar' /> 
										</center>
										<div class="cleaner h10"></div>

										<table cellpadding="0" cellpadding="0" border="0" class="display" id="TableEmpleados" width="100%">
											<thead>
												<tr> 
													<th width="10%">Cod. Interno</th>
													<th width="35%">Nombre</th>
													<th width="35%">Apellido</th>
													<th width="10%">Cedula</th>
												</tr>
											</thead>
											<tbody>
											</tbody>
										</table> 
									</div>
								<?php }
								if(isset($_SESSION['Accesos']['Trabajo']['trabajo_asignacion'])){ ?>	
									<div id="trabajo_asignacion" height="80%">
										<center>
											<label class="LabelInputSmaller" for="CicloAsignacion">Ciclo</label>
											<select id="CicloAsignacion"> 
											<?php
												$_ciclos = json_decode($FcnParametros->getCiclos());
												foreach($_ciclos as $obj){
													echo "<option value='".$obj->id_ciclo."'>".$obj->id_ciclo."</option>";											   
												}
											?> 
											</select>

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
											<input type='button' id="ConsultarAsignacion" value='Consultar' />
										</center>

										<center>
											<label class="LabelInputSmaller" for="TecnicoAsignacion">Tecnico</label>
											<select id="TecnicoAsignacion">
											<?php
												$_inspectores = json_decode($FcnParametros->getInspectoresActivos());
												foreach($_inspectores as $obj){
												echo "<option value='".$obj->id_inspector."'>".$obj->nombre."</option>";											   
												}
											?>
											</select>
											<input type='button' id="ProgramarAsignacion" value='Asignar' />
											<input type='button' id="EliminarAsignacion" value='Desasignar' />
											<input type='button' id="LecturaManuales" value='GenerarPDF' />
										</center>

										<table cellpadding="0" cellpadding="0" border="0" class="display" id="TablaSectorRuta" width="100%">
											<thead>
												<tr> 
													<th width="5%">Id</th>
													<th width="45%">Inspector</th>
													<th width="20%">Ruta</th>
													<th width="10%">Total</th>
													<th width="10%">Leidas</th>
													<th width="10%">Pend.</th>													
												</tr>
											</thead>
											<tbody2
											</tbody>
										</table> 
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