<?php
	session_start();

	include_once(dirname(__FILE__)."/../Clases/ClassPostgresBD.php");
	
	switch($_POST['Peticion']){
		case 'Parametros':  				DescargarParametros($_POST['Inspector']);																						break;
		case 'Trabajo': 					DescargarTrabajo($_POST['Inspector'], $_POST['RutasLecturas'], $_POST['RutasRecuperaciones'], $_POST['RutasVerificaciones']);	break;
		case 'UploadErrorPrinter':          LoadErrorPrinter($_POST['datosimpresion']);			                        													break;
		case 'SyncDate':          			SyncDate($_POST['bluetooth'], $_POST['fecha_hora']);			                        										break;	
		case 'UpdateTrabajo':          		UpdateTrabajo($_POST['Trabajo'], $_POST['origen'],$_POST['Lector']);
	}


	function SyncDate($_bluetooth, $fecha_hora){
		$date 	= new DateTime(); // Fecha actual
		$date2 	= explode(" ", $fecha_hora); // Segunda fecha
		$date->setTimeZone( new DateTimeZone('America/Bogota')); // Definimos seTimeZone para asegurarnos de que sea la hora actual del lugar donde estamos


		if($date->format('d/m/Y') != $date2[0]){
			echo "Bad_1|".$date->format("d/m/Y H:i:s")."|".$fecha_hora;
		}else{
			$hora_server	= explode(":", $date->format('H:i:s'));
			$hora_movil 	= explode(":", $date2[1]);

			$segServer 	= ((int)$hora_server[0]*3600) + ((int)$hora_server[1]*60) + (int)$hora_server;
			$segMovil 	= ((int)$hora_movil[0]*3600) + ((int)$hora_movil[1]*60) + (int)$hora_movil;

			if(abs($segServer-$segMovil)>300){
				echo "Bad_2|".$date->format("d/m/Y H:i:s")."|".$fecha_hora;
			}else{
				echo "Ok_1|".$date->format("d/m/Y H:i:s")."|".$fecha_hora;
			}
		}
	}


	function LoadErrorPrinter($_datos){
		$postgresWS = new PostgresDB();
		$postgresWS->OpenPostgres();

		$datosImpresion = json_decode($_datos, true);
		$data = array();
		$k=0;
		for($i=0;$i<count($datosImpresion['Impresion']);$i++){
			if($postgresWS->PostgresInsertIntoValues("toma.registro_impresion",
					"cuenta,id_inspector,error,fecha_impresion",
					$datosImpresion['Impresion'][$i]['cuenta'].",".$datosImpresion['Impresion'][$i]['id_inspector'].",'".$datosImpresion['Impresion'][$i]['error']."','".$datosImpresion['Impresion'][$i]['fecha_toma']."'")){
				$data[$k]['id'] = $datosImpresion['Impresion'][$i]['id'];
				$k++;
			}
		}
		$postgresWS->ClosePostgres();
		echo json_encode($data);
	}


	function DescargarParametros($_inspector){
		$postgresWS = new PostgresDB();
		$postgresWS->OpenPostgres();
			

		/**
			Consulta de los datos de inspectores
		**/
		$queryArchivo = $postgresWS->PostgresSelectDistinctWhereOrder(	"parametros.inspectores", 
																		"id_inspector,nombre,cedula,tipo_inspector", 
																		"estado = TRUE", 
																		"nombre");
		$data['Inspectores'] = $postgresWS->QueryToJson($queryArchivo,["id_inspector","nombre","cedula","tipo_inspector"],[null,null,null,null],true);
		

		/**
			Consulta de los nombre de municipios y sus respectivos codigos
		**/
		$queryArchivo = $postgresWS->PostgresSelectDistinctWhereOrder(	"parametros.municipios", 
																		"id_municipio,nombre_municipio", 
																		"id_serial IS NOT NULL", 
																		"nombre_municipio");
		$data['Municipios'] = $postgresWS->QueryToJson($queryArchivo,["id_municipio","nombre_municipio"],[null,null],true);

		/**
			Consulta de las anomalias y sus casos de aplicacion
		**/
		$queryArchivo = $postgresWS->PostgresSelectDistinctWhereOrder(	"parametros.anomalias", 
																		"id_anomalia,descripcion,aplica_residencial,aplica_no_residencial,lectura,mensaje,foto,cant_fotos", 
																		"id_serial IS NOT NULL", 
																		"descripcion");
		$data['Anomalias'] = $postgresWS->QueryToJson($queryArchivo,["id_anomalia","descripcion","aplica_residencial","aplica_no_residencial","lectura","mensaje","foto","cant_fotos"],[null,null,null,null,null,null,null],true);


		/**
			Consulta de los rangos de critica y su descripcion
		**/
		$queryArchivo = $postgresWS->PostgresSelectDistinctWhereOrder(	"parametros.critica", 
																		"rango_minimo,rango_maximo,descripcion,mensaje,vr_incremento,vr_disminucion,consumo", 
																		"id_serial IS NOT NULL", 
																		"descripcion");
		$data['Criticas'] = $postgresWS->QueryToJson($queryArchivo,["rango_minimo","rango_maximo","descripcion","mensaje","vr_incremento","vr_disminucion","consumo"],[null,null,null,null],true);


		/**
			Consulta de los Tipos de Uso y su descripcion
		**/

		$queryArchivo = $postgresWS->PostgresSelectDistinctWhereOrder(	"parametros.tipo_usos", 
																		"id_uso,descripcion", 
																		"id_uso IS NOT NULL", 
																		"descripcion");
		$data['Usos'] = $postgresWS->QueryToJson($queryArchivo,["id_uso","descripcion"],[null,null],true);


		/**
			Consulta de los Mensajes codificados y la respectiva descripcion
		**/

		$queryArchivo = $postgresWS->PostgresSelectDistinctWhereOrder(	"parametros.vista_mensajes_decodificado", 
																		"codigo,descripcion,macro", 
																		"codigo IS NOT NULL", 
																		"descripcion");
		$data['Mensajes'] = $postgresWS->QueryToJson($queryArchivo,["codigo","descripcion","macro"],[null,null,null],true);


		/**
			Consulta de las siglas que identifican a los macromedidores
		**/

		$queryArchivo = $postgresWS->PostgresSelectDistinctWhereOrder(	"parametros.filtro_macro", 
																		"sigla,descripcion", 
																		"sigla IS NOT NULL", 
																		"descripcion");
		$data['SiglasMacro'] = $postgresWS->QueryToJson($queryArchivo,["sigla","descripcion"],[null,null],true);


		/**
			Consulta de las siglas que identifican a los medidores para evaluar la conformidad en sellos y caja
		**/

		$queryArchivo = $postgresWS->PostgresSelectDistinctWhereOrder(	"parametros.medidores_nc", 
																		"marca,descripcion", 
																		"marca IS NOT NULL", 
																		"descripcion");
		$data['SiglasMedidoresNC'] = $postgresWS->QueryToJson($queryArchivo,["marca","descripcion"],[null,null],true);



		$postgresWS->ClosePostgres();
		echo json_encode($data);
	}


	function DescargarTrabajo($_inspector, $_rutasLecturas, $_rutasRecuperaciones, $_rutasVerificaciones){
		$postgresWS = new PostgresDB();
		$postgresWS->OpenPostgres();

		if($_rutas_cargadas == ""){
			$_rutas_cargadas = "''";
		}


		//Se carga la informacion general de las rutas pendientes por cargar
		$queryRutas = $postgresWS->PostgresFunctionTable("maestro.Programacion_Maestro_Rutas(".$_inspector.") WHERE id_programacion NOT IN (".$_rutasLecturas.")");
		$k = 0;
		$informacion = array();
		$data = array();
		while($infRuta = pg_fetch_assoc($queryRutas)){
			$queryRespuesta = $postgresWS->PostgresFunctionTable("maestro.programacion_by_agrupacion(".$infRuta['id_ciclo'].",".$infRuta['id_municipio'].",'".$infRuta['ruta']."',".$infRuta['mes'].",".$infRuta['anno'].")");	
			
			$data[$k]['id_programacion']= $infRuta['id_programacion'];
			$data[$k]['id_inspector'] 	= $infRuta['id_inspector'];
			$data[$k]['id_ciclo'] 		= $infRuta['id_ciclo'];
			$data[$k]['id_municipio'] 	= $infRuta['id_municipio'];
			$data[$k]['ruta'] 			= $infRuta['ruta'];
			$data[$k]['mes'] 			= $infRuta['mes'];
			$data[$k]['anno'] 			= $infRuta['anno'];
			$data[$k]['tipo'] 			= 'L';
			$data[$k]['foto']			= $infRuta['foto'];	
			$data[$k]['voucher']		= $infRuta['voucher'];	
			$data[$k]['fotociclo']		= $infRuta['fotociclo'];
			$data[$k]['cuentas'] = $postgresWS->QueryToJson($queryRespuesta,["id","id_ciclo","id_ciclo_real","mes","anno","ruta","cuenta","medidor","serie","digitos","nombre","direccion",
						"factor","tipo_uso","id_serial_1","lectura_1","tipo_energia_1","anomalia_1","promedio_1","id_serial_2","lectura_2","tipo_energia_2","anomalia_2",
						"promedio_2","id_serial_3","lectura_3","tipo_energia_3","anomalia_3","promedio_3","id_municipio","estado_lectura"],[null,null],true);
			$k++;
		}
		$informacion['TrabajoProgramado'] = $data;


		//SE INICIA EL PROCEDIMIENTO PARA CARGAR LAS RECUPERACIONES Y VERIFICACIONES QUE SE TENGAN ASIGNADAS
		$queryRutas = $postgresWS->PostgresFunctionTable("maestro.programacion_no_inspectores(".$_inspector.",'R') WHERE id_programacion NOT IN (".$_rutasRecuperaciones.")");
		$k = 0;
		unset($data);
		$data = array();
		while($infRuta = pg_fetch_assoc($queryRutas)){
			$queryRespuesta = $postgresWS->PostgresFunctionTable("maestro.programacion_by_agrupacion_no_inspectores(".$infRuta['id_programacion'].",".$infRuta['mes'].",".$infRuta['anno'].")");	
			
			$data[$k]['id_programacion']= $infRuta['id_programacion'];
			$data[$k]['id_inspector'] 	= $infRuta['id_inspector'];
			$data[$k]['id_ciclo'] 		= $infRuta['id_ciclo'];
			$data[$k]['id_municipio'] 	= $infRuta['id_municipio'];
			$data[$k]['ruta'] 			= $infRuta['ruta'];
			$data[$k]['mes'] 			= $infRuta['mes'];
			$data[$k]['anno'] 			= $infRuta['anno'];
			$data[$k]['tipo'] 			= 'R';
			$data[$k]['foto']			= $infRuta['foto'];	
			$data[$k]['voucher']		= $infRuta['voucher'];
			$data[$k]['fotociclo']		= '0';	
			$data[$k]['cuentas'] = $postgresWS->QueryToJson($queryRespuesta,["id","id_ciclo","id_ciclo_real","mes","anno","ruta","cuenta","medidor","serie","digitos","nombre","direccion",
						"factor","tipo_uso","id_serial_1","lectura_1","tipo_energia_1","anomalia_1","promedio_1","id_serial_2","lectura_2","tipo_energia_2","anomalia_2",
						"promedio_2","id_serial_3","lectura_3","tipo_energia_3","anomalia_3","promedio_3","id_municipio","estado_lectura"],[null,null],true);
			$k++;
		}
		$informacion['TrabajoRecuperaciones'] = $data;


		//SE INICIA EL PROCEDIMIENTO PARA CARGAR LAS RECUPERACIONES Y VERIFICACIONES QUE SE TENGAN ASIGNADAS
		$queryRutas = $postgresWS->PostgresFunctionTable("maestro.programacion_no_inspectores(".$_inspector.",'V') WHERE id_programacion NOT IN (".$_rutasVerificaciones.")");
		$k = 0;
		unset($data);
		$data = array();
		while($infRuta = pg_fetch_assoc($queryRutas)){
			$queryRespuesta = $postgresWS->PostgresFunctionTable("maestro.programacion_by_agrupacion_no_inspectores(".$infRuta['id_programacion'].",".$infRuta['mes'].",".$infRuta['anno'].")");	
			
			$data[$k]['id_programacion']= $infRuta['id_programacion'];
			$data[$k]['id_inspector'] 	= $infRuta['id_inspector'];
			$data[$k]['id_ciclo'] 		= $infRuta['id_ciclo'];
			$data[$k]['id_municipio'] 	= $infRuta['id_municipio'];
			$data[$k]['ruta'] 			= $infRuta['ruta'];
			$data[$k]['mes'] 			= $infRuta['mes'];
			$data[$k]['anno'] 			= $infRuta['anno'];
			$data[$k]['tipo'] 			= 'V';
			$data[$k]['foto']			= $infRuta['foto'];	
			$data[$k]['voucher']		= $infRuta['voucher'];
			$data[$k]['fotociclo']		= '0';	
			$data[$k]['cuentas'] = $postgresWS->QueryToJson($queryRespuesta,["id","id_ciclo","id_ciclo_real","mes","anno","ruta","cuenta","medidor","serie","digitos","nombre","direccion",
						"factor","tipo_uso","id_serial_1","lectura_1","tipo_energia_1","anomalia_1","promedio_1","id_serial_2","lectura_2","tipo_energia_2","anomalia_2",
						"promedio_2","id_serial_3","lectura_3","tipo_energia_3","anomalia_3","promedio_3","id_municipio","estado_lectura"],[null,null],true);
			$k++;
		}
		$informacion['TrabajoVerificaciones'] = $data;


		//Inicio de proceso de consultas que ya han sido terminadas pero que el verificador y/o lector aun tienen cargadas
		$queryRespuesta = $postgresWS->PostgresFunctionTable("maestro.Rutas_Terminadas_Inspector(".$_inspector.",'L') WHERE id_programacion IN (".$_rutasLecturas.")");
		$k = 0;
		unset($data);
		$data = array();
		while($rtaQuery = pg_fetch_assoc($queryRespuesta)){
			$data[$k]['id_programacion']=$rtaQuery['id_programacion'];
			$data[$k]['id_lector']		=$_inspector;
			$k++;
		}
		$informacion['LecturasTerminadas'] = $data;


		$queryRespuesta = $postgresWS->PostgresFunctionTable("maestro.Rutas_Terminadas_Inspector(".$_inspector.",'V') WHERE id_programacion IN (".$_rutasVerificaciones.")");
		$k = 0;
		unset($data);
		$data = array();
		while($rtaQuery = pg_fetch_assoc($queryRespuesta)){
			$data[$k]['id_programacion']=$rtaQuery['id_programacion'];
			$data[$k]['id_lector']		=$_inspector;
			$k++;
		}
		$informacion['VerificacionesTerminadas'] = $data;


		$queryRespuesta = $postgresWS->PostgresFunctionTable("maestro.Rutas_Terminadas_Inspector(".$_inspector.",'R') WHERE id_programacion IN (".$_rutasRecuperaciones.")");
		$k = 0;
		unset($data);
		$data = array();
		while($rtaQuery = pg_fetch_assoc($queryRespuesta)){
			$data[$k]['id_programacion']=$rtaQuery['id_programacion'];
			$data[$k]['id_lector']		=$_inspector;
			$k++;
		}
		$informacion['RecuperacionesTerminadas'] = $data;


		$postgresWS->ClosePostgres();
		echo json_encode($informacion);
	}


	function UpdateTrabajo($trabajo,$origen,$lector){
		$postgresWS = new PostgresDB();
		$postgresWS->OpenPostgres();			
		$cuentas = array();
		$informacion = array();
		$request = json_decode($trabajo,true);
        $file = fopen("uploads/".str_replace(":","_",$origen.$lector).".txt", "a") or die("No se pudo generar el archivo");

        for($i=0;$i<count($request['Trabajo']);$i++){            
            fputs($file,$request['Trabajo'][$i]['mes'].",".$request['Trabajo'][$i]['anno'].",".$request['Trabajo'][$i]['id_ciclo'].",".$request['Trabajo'][$i]['ruta']);
            fputs($file,"\n");            
    	
            $queryRespuesta = $postgresWS->PostgresFunctionTable("toma.rutas_compartidas(".$request['Trabajo'][$i]['mes'].",".$request['Trabajo'][$i]['anno'].",".$request['Trabajo'][$i]['id_ciclo'].",'".$request['Trabajo'][$i]['ruta']."',".$lector.")");
			$k = 0;
			unset($data);
			$data = array();
			while($infRuta = pg_fetch_assoc($queryRespuesta)){			
				$data[$k]['id_maestro_emsa']= $infRuta['id_maestro_emsa'];				
				$k++;
			}
			$informacion[$i] = $data;
    	}
    	$cuentas['Cuentas'] = $informacion;
		$postgresWS->ClosePostgres();
		echo json_encode($cuentas);
	}
?>	