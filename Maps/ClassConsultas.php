<?php
	session_start();
	include_once(dirname(__FILE__)."/../Clases/ClassPostgresBD.php");

	class Consultas{
		private $consulta_connect;
	   
		function Consultas(){
			$this->consulta_connect = new PostgresDB();
        }


        function ConsultarResumenCiclos($_mes, $_anno){
        	$data = array();
        	$this->consulta_connect->OpenPostgres();
			$Informacion = 	$this->consulta_connect->PostgresSelectWhereOrder(	"parametros.ciclos", 
																				"id_ciclo AS texto, id_ciclo AS valor", 
																				"id_ciclo NOT IN (SELECT id_ciclo FROM maestro.log_cargue_ciclos WHERE mes = ".$_mes." AND anno = ".$_anno.")", 
																				"texto"); 
			$data['CiclosPorCargar']= $this->consulta_connect->QueryToJson($Informacion,["texto","valor"],[null,null],true);

			$Informacion = 	$this->consulta_connect->PostgresSelectWhereOrder(	"maestro.log_cargue_ciclos", 
																				"mes,anno,id_ciclo,fecha_cargue,estado_ciclo,usuario", 
																				"mes = ".$_mes." AND anno = ".$_anno."", 
																				"mes,anno,id_ciclo"); 
			$data['CiclosCargados'] = $this->consulta_connect->QueryToJson($Informacion,["mes","anno","id_ciclo","estado_ciclo","fecha_cargue","usuario"],[null,null,null,null,null,null],false);

			return json_encode($data);
			$this->consulta_connect->ClosePostgres();
        }



        function ConsultaErroresImpresion($_fecha){
        	$this->consulta_connect->OpenPostgres();
			$Informacion = 	$this->consulta_connect->PostgresSelectJoinWhereOrder("toma.registro_impresion AS a", 
				"a.cuenta,a.id_inspector||' '||b.nombre AS inspector,a.error,a.fecha_impresion,a.fecha_recepcion", 
				"parametros.inspectores AS b", "a.id_inspector = b.id_inspector",
				"a.fecha_impresion >= '".$_fecha."' AND a.fecha_impresion<CAST('".$_fecha."' AS TIMESTAMP) + interval '1 day'", "fecha_impresion");

			return json_encode($this->consulta_connect->QueryToJson($Informacion,["cuenta","inspector","error","fecha_impresion","fecha_recepcion"],[null,null,null,null,null],false));
			$this->consulta_connect->ClosePostgres();
        }


        function ConsultaCorrecciones($_mes, $_anno, $_ciclo){
        	$this->consulta_connect->OpenPostgres();
			$Informacion = 	$this->consulta_connect->PostgresFunctionTable(	"toma.informe_correcciones(".$_mes.",".$_anno.",".$_ciclo.")"); ;
			return json_encode($this->consulta_connect->QueryToJson($Informacion,["ciclo","medidor","cuenta","inspector","base_lectura","base_anomalia","base_mensaje","base_fecha","base_foto","correccion_lectura","correccion_anomalia","correccion_mensaje","correccion_fecha","correccion_foto","analista","tipo","fecha_cambio"],[null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null],false));
			$this->consulta_connect->ClosePostgres();
        }





        function ConsultaPeriodo($_mes, $_anno){
        	$this->consulta_connect->OpenPostgres();
			$Informacion = 	$this->consulta_connect->PostgresSelectDistinctWhereGroupOrder("maestro.log_ciclo_muni_rutas", 
					"id_ciclo, sum(total) AS total, sum(total_leidas) as leidas, sum(total_pendientes) AS pendientes", 
					"mes = ".$_mes." AND anno = ".$_anno, 
					"id_ciclo", 
					"id_ciclo"); 
			return json_encode($this->consulta_connect->QueryToJson($Informacion,["id_ciclo","total","leidas","pendientes"],[null,null,null,null],false));
			$this->consulta_connect->ClosePostgres();
        }



        function TerminarRutasNoLecturas($_id){
        	$retorno = "";
        	$this->consulta_connect->OpenPostgres();
        	for($i=0; $i<count($_id['IdNoLecturas']); $i++){
        		//$array .= $_ciclos['IdNoLecturas'][$i]['id'].",";
        		if($this->consulta_connect->PostgresUpdateValues("maestro.log_cuentas_no_inspectores", "estado ='R'", "estado = 'P' AND id_programacion =".$_id['IdNoLecturas'][$i]['id'])){
        			$retorno = "Programacion terminada correctamente.";
        		}else{
        			$retorno = "Error terminando la programacion.";
        		}
        	}
        	$this->consulta_connect->ClosePostgres();	
        	return $retorno;
        }


        function ConsultaRutasCiclo($_mes, $_anno, $_ciclos){
        	$array = "";
        	for($i=0; $i<count($_ciclos['CiclosSeleccionados']); $i++){
        		$array .= $_ciclos['CiclosSeleccionados'][$i]['ciclo'].",";
        	}
        	$array = "array[".substr($array,0,-1)."]";

        	$this->consulta_connect->OpenPostgres();
			$Informacion = 	$this->consulta_connect->PostgresFunctionTable(	"maestro.Consulta_General(".$_mes.",".$_anno.",".$array.")"); 
			return json_encode($this->consulta_connect->QueryToJson($Informacion,["ruta","inspector","total","leidas","pendientes","estado"],[null,null,null,null,null],false));
			$this->consulta_connect->ClosePostgres();
        }


        function ConsultaPeriodoNoLecturas($_mes, $_anno){
        	$this->consulta_connect->OpenPostgres();
			$Informacion = 	$this->consulta_connect->PostgresFunctionTable(	"maestro.consulta_general_no_lecturas(".$_mes.",".$_anno.")"); 
			return json_encode($this->consulta_connect->QueryToJson($Informacion,["id_programacion","ruta","inspector","total","leidas","pendientes","estado"],[null,null,null,null,null],false));
			$this->consulta_connect->ClosePostgres();	
        }


        function ConsultaRutasEstado($_mes, $_anno, $_rutas){
        	$array = "";
        	for($i=0; $i<count($_rutas['RutasSeleccionadas']); $i++){
        		$datos .= "'".$_rutas['RutasSeleccionadas'][$i]['ruta']."',";
        	}
        	$datos =  substr($datos,0,-1);
        	$array = "array[".$datos."]";

        	$this->consulta_connect->OpenPostgres();
			//return $this->consulta_connect->QueryToJson($Informacion,["ruta","id_inspector","inspector","leidas"],[null,null,null,null],false);
			
			$Informacion = 	$this->consulta_connect->PostgresFunctionTable(	"toma.Consulta_General_Inspectores(".$_mes.",".$_anno.",".$array.")"); 
			$AporteInspectores 	= $this->consulta_connect->QueryToJson($Informacion,["ruta","id_inspector","inspector","leidas"],[null,null,null,null],false);
			
			$Informacion = 	$this->consulta_connect->PostgresSelectWhereOrder(	"maestro.log_ciclo_muni_ruta_cuentas", 
																				"id_ciclo||'-'||id_municipio||'-'||ruta AS ruta,cuenta,medidor||' '||serie as medidor,nombre, direccion", 
																				"mes=".$_mes." AND anno=".$_anno." AND CAST(id_ciclo AS TEXT)||'-'||CAST(id_municipio AS TEXT)||'-'||ruta IN (".$datos.") AND estado_lectura='P'", 
																				"cuenta");			
			$ClientesPendientes =  $this->consulta_connect->QueryToJson($Informacion,["ruta","cuenta","medidor","nombre","direccion"],[null,null,null,null,null],false);

			$arrayResultado = array();
			$arrayResultado["AporteInspectores"]	= $AporteInspectores;
			$arrayResultado["ClientesPendientes"] 	= $ClientesPendientes;

			return json_encode($arrayResultado);
			$this->consulta_connect->ClosePostgres();
        }


		function ConsultaLecturasTomadas($_mes, $_anno, $_inspectores){
        	$array = "";
        	for($i=0; $i<count($_inspectores['InspectoresSeleccionados']); $i++){
        		$ruta .= "'".$_inspectores['InspectoresSeleccionados'][$i]['ruta']."',";
        		$inspector .= $_inspectores['InspectoresSeleccionados'][$i]['inspector'].",";
        	}
        	$ruta =  substr($ruta,0,-1);
        	$array_ruta = "array[".$ruta."]";

        	$inspector =  substr($inspector,0,-1);
        	$array_inspector = "array[".$inspector."]";

        	$this->consulta_connect->OpenPostgres();
			
			$Informacion = 	$this->consulta_connect->PostgresFunctionTable(	"toma.toma_lectura(".$_mes.",".$_anno.",".$array_ruta.",".$array_inspector.")"); 
			$ClientesLeidos 	= $this->consulta_connect->QueryToJson($Informacion,
					["ruta","cuenta","medidor","nombre","direccion","str_lectura","fecha_toma","fecha_recepcion","descripcion_anomalia","mensaje","descripcion_critica","id_lector"],
					[null,null,null,null,null,null,null,null,null,null,null,null,null],false);

			
			$arrayResultado = array();
			$arrayResultado["ClientesLeidos"] 		= $ClientesLeidos;

			return json_encode($arrayResultado);
			$this->consulta_connect->ClosePostgres();
        }



        /**CONSULTAS VIEJAS**/
        function consultaGeneral($_mes, $_anno, $_ciclo){
        	$this->consulta_connect->OpenPostgres();
			$Informacion = 	$this->consulta_connect->PostgresFunctionTable(	"maestro.Consulta_General(".$_mes.",".$_anno.",".$_ciclo.")"); 
			return $this->consulta_connect->QueryToJson($Informacion,["municipio","ruta","total","leidas","pendientes","estado"],[null,null,null,null,null,null],false);
			$this->consulta_connect->ClosePostgres();      
        }


        function ConsultaCiclosLecturas($_mes, $_anno, $_estado){
        	$this->consulta_connect->OpenPostgres();
			$Informacion = 	$this->consulta_connect->PostgresSelectDistinctWhereOrder(	"maestro.log_ciclo_muni_rutas", 
																						"id_ciclo AS texto, id_ciclo AS valor", 
																						"mes=".$_mes." AND anno=".$_anno." AND estado_ciclo_ruta IN (".$_estado.")", 
																						"texto");
			return $this->consulta_connect->QueryToJson($Informacion,["texto","valor"],[null,null],true);
			$this->consulta_connect->ClosePostgres();
        }


        function ConsultaMunicipiosLecturas($_mes, $_anno, $_ciclo, $_estado){
        	$this->consulta_connect->OpenPostgres();
			$Informacion = 	$this->consulta_connect->PostgresSelectJoinWhereOrder(	"maestro.log_ciclo_muni_rutas as a", 
																					"DISTINCT a.id_municipio AS valor, b.nombre_municipio AS texto", 
																					"parametros.municipios as b",
																					"a.id_municipio = b.id_municipio",
																					"mes=".$_mes." AND anno=".$_anno." AND id_ciclo=".$_ciclo." AND estado_ciclo_ruta IN (".$_estado.")", 
																					"texto");
			return $this->consulta_connect->QueryToJson($Informacion,["texto","valor"],[null,null],true);
			$this->consulta_connect->ClosePostgres();
        }


        function ConsultaRutasLecturas($_mes, $_anno, $_ciclo, $_municipio, $_estado){
        	$this->consulta_connect->OpenPostgres();
			$Informacion = 	$this->consulta_connect->PostgresSelectDistinctWhereOrder(	"maestro.log_ciclo_muni_rutas", 
																						"ruta AS texto, ruta AS valor", 
																						"mes=".$_mes." AND anno=".$_anno." AND id_ciclo=".$_ciclo." AND id_municipio=".$_municipio." AND estado_ciclo_ruta IN (".$_estado.")", 
																						"texto");
			return $this->consulta_connect->QueryToJson($Informacion,["texto","valor"],[null,null],true);
			$this->consulta_connect->ClosePostgres();
        }


        //Esperar que se descargue para realizar la consulta.. se de hacer Join
        function consultaCliente($_seleccion, $_dato){
        	$this->consulta_connect->OpenPostgres();
        	if($_seleccion=="Cuenta"){
        		$Informacion = 	$this->consulta_connect->PostgresFunctionTable("toma.toma_lectura_by_cuenta(".$_dato.")");
        	}else{
        		$Informacion = 	$this->consulta_connect->PostgresFunctionTable("toma.Toma_Lectura_By_Medidor('".$_dato."')");
        	}
			
			return $this->consulta_connect->QueryToJson($Informacion,["cuenta","medidor","str_lectura","descripcion_anomalia","mensaje","descripcion_critica","nombre","fecha_toma"],[null,null,null,null,null,null,null,null],false);
			$this->consulta_connect->ClosePostgres();
        }


        //Esperar que se descargue para realizar la consulta.. se de hacer Join
        function ConsultaCorreccion($_mes, $_anno, $_tipo, $_dato){
        	$this->consulta_connect->OpenPostgres();
        	if($_tipo=="Cuenta"){
        		$Informacion = $this->consulta_connect->PostgresFunctionCamposTable("id_toma_lectura,cuenta,medidor,lectura,id_anomalia,mensaje,tipo_uso",
        																			"toma.toma_lectura_by_cuenta(".$_dato.") WHERE mes=".$_mes." AND anno=".$_anno);
        	}else{
        		$Informacion = $this->consulta_connect->PostgresFunctionCamposTable("id_toma_lectura,cuenta,medidor,lectura,id_anomalia,mensaje,tipo_uso",
        																			"toma.Toma_Lectura_By_Medidor('".$_dato."') WHERE mes=".$_mes." AND anno=".$_anno);
        	}
			
			return $this->consulta_connect->QueryToJson($Informacion,["id_toma_lectura","cuenta","medidor","lectura","id_anomalia","mensaje","tipo_uso"],[null,null,null,null,null,null,null],false);
			$this->consulta_connect->ClosePostgres();
        }

       	
       	//Esperar que se descargue para realizar la consulta.. se de hacer Join
        function ConsultaDetalleTomadas($_mes, $_anno, $_ciclo, $_municipio, $_ruta){
        	$this->consulta_connect->OpenPostgres();
        	$Informacion = 	$this->consulta_connect->PostgresFunctionTable(	"toma.toma_lectura(".$_mes.",".$_anno.",".$_ciclo.",".$_municipio.",'".$_ruta."')"); 				
			return $this->consulta_connect->QueryToJson($Informacion,["cuenta","medidor","str_lectura","descripcion_anomalia","mensaje","descripcion_critica","nombre","direccion"],[null,null,null,null,null,null,null,null],false);
			$this->consulta_connect->ClosePostgres();
		}


		//Esperar que se descargue para realizar la consulta.. se de hacer Join
        function ConsultaCronologicoTomadas($_mes, $_anno, $_ciclo, $_municipio, $_ruta){
        	$this->consulta_connect->OpenPostgres();
        	$Informacion = 	$this->consulta_connect->PostgresFunctionTable(	"toma.toma_lectura(".$_mes.",".$_anno.",".$_ciclo.",".$_municipio.",'".$_ruta."')"); 				
			return $this->consulta_connect->QueryToJson($Informacion,["cuenta","medidor","str_lectura","descripcion_anomalia","mensaje","descripcion_critica","fecha_toma"],[null,null,null,null,null,null,null],false);
			$this->consulta_connect->ClosePostgres();
		}


		//Esperar que se descargue para realizar la consulta.. se de hacer Join
        function ConsultaPendientesClientes($_mes, $_anno, $_ciclo, $_municipio, $_ruta){
        	$this->consulta_connect->OpenPostgres();
        	$Informacion = 	$this->consulta_connect->PostgresSelectWhereOrder(	"maestro.log_ciclo_muni_ruta_cuentas", 
																				"cuenta,medidor||' '||serie as medidor,nombre, direccion", 
																				"mes=".$_mes." AND anno=".$_anno." AND id_ciclo=".$_ciclo." AND id_municipio = ".$_municipio." AND ruta='".$_ruta."' AND estado_lectura='P'", 
																				"cuenta");			
			return $this->consulta_connect->QueryToJson($Informacion,["cuenta","medidor","nombre","direccion"],[null,null,null,null],false);
			$this->consulta_connect->ClosePostgres();
		}


		function ConsultaGeneralInspector($_mes, $_anno, $_ciclo){
        	$this->consulta_connect->OpenPostgres();
        	$Informacion = 	$this->consulta_connect->PostgresFunctionTable(	"toma.Consulta_General_Inspectores(".$_ciclo.",".$_mes.",".$_anno.")"); 				
			return $this->consulta_connect->QueryToJson($Informacion,["inspector","ruta","total","leidas","pendientes"],[null,null,null,null,null],false);
			$this->consulta_connect->ClosePostgres();
		}

		function ConsultaConsolidado($_mes, $_anno, $_ciclo){
        	$this->consulta_connect->OpenPostgres();
        	$Informacion = 	$this->consulta_connect->PostgresFunctionTable(	"toma.Toma_Consolidado(".$_mes.",".$_anno.",".$_ciclo.")"); 				
			return $this->consulta_connect->QueryToJson($Informacion,["cuenta","medida","lectura","id_anomalia","digitos","fecha","mensaje","id_lector","tipo_uso","bluetooth","longitud","latitud"],[null,null,null,null,null,null,null,null,null,null,null,null],false);
			$this->consulta_connect->ClosePostgres();
		}



		function consultarFotos($tipo,$dato){						
			if($tipo == "fecha"){
				$fecha=date("Y-m-d",strtotime($dato));
	        	$this->consulta_connect->OpenPostgres();
				$Informacion = 	$this->consulta_connect->PostgresSelectJoinJoinWhereOrder("toma.registro_fotografico as a", 
																					"a.cuenta,a.fecha,b.id_ciclo,c.nombre_municipio,b.ruta_completa,b.nombre,b.serie", 
																					"maestro.log_ciclo_muni_ruta_cuentas as b", 
																					"a.id_maestro_emsa = b.id_serial",
																					"parametros.municipios as c",
																					"b.id_municipio = c.id_municipio",
																					"a.fecha='".$fecha."'",
																					"a.fecha");
				return $this->consulta_connect->QueryToJson($Informacion,["cuenta","fecha","id_ciclo","nombre_municipio","ruta_completa","nombre","serie"],[null,'Fecha',null,null,null,null,null],false);
				$this->consulta_connect->ClosePostgres();
			}else{
				$this->consulta_connect->OpenPostgres();
				$Informacion = 	$this->consulta_connect->PostgresSelectJoinJoinWhereOrder("toma.registro_fotografico as a", 
																					"a.cuenta,a.fecha,b.id_ciclo,c.nombre_municipio,b.ruta_completa,b.nombre,b.serie", 
																					"maestro.log_ciclo_muni_ruta_cuentas as b", 
																					"a.id_maestro_emsa = b.id_serial",
																					"parametros.municipios as c",
																					"b.id_municipio = c.id_municipio",
																					"a.cuenta=".$dato."",
																					"a.fecha");
				return $this->consulta_connect->QueryToJson($Informacion,["cuenta","fecha","id_ciclo","nombre_municipio","ruta_completa","nombre","serie"],[null,'Fecha',null,null,null,null,null],false);
				$this->consulta_connect->ClosePostgres();
			}
        }

         function consultarRuta($dato,$fecha,$radio){
         	if($radio=="b"){
         		$fecha1 = date("Y-m-d",strtotime($fecha));
         	}else{
         		$fecha1= $this->consulta_connect->ddmmaaaa2($fecha);	
         	}         	
        	$this->consulta_connect->OpenPostgres();
			$Informacion = 	$this->consulta_connect->PostgresSelectWhereOrder(	"toma.registro_fotografico", 
																				"ruta", 
																				"cuenta=".$dato." AND fecha='".$fecha1."'", 
																				"ruta");
			return $this->consulta_connect->QueryToJson($Informacion,["ruta"],[null],true);
			$this->consulta_connect->ClosePostgres();
        }

        
        /**
        	FUNCIONES UTILIZADAS PARA LA CONSULTA DE LOS DATOS PARA DIBUJAR LAS COORDENADAS GEOGRAFICAS.
        **/
        function consultarMapa(){
        	   	$this->consulta_connect->OpenPostgres();
				$Informacion = 	$this->consulta_connect->PostgresSelectJoinJoinWhereGroupOrder("maestro.log_ciclo_muni_rutas AS a", 
																								"a.mes,a.anno,a.id_ciclo,a.ruta,a.id_municipio,c.id_lector", 
																								"maestro.log_ciclo_muni_ruta_cuentas AS b", 
																								"a.mes=b.mes AND a.anno=b.anno AND a.id_ciclo=b.id_ciclo AND a.ruta = b.ruta AND a.id_municipio = b.id_municipio", 
																								"toma.lectura AS c", 
																								"b.id_serial = c.id_maestro_emsa",
																								"a.mes is not null",
																								"a.mes,a.anno,a.id_ciclo,a.ruta,a.id_municipio,c.id_lector",
																								"mes,anno,id_ciclo,ruta,id_municipio");
				return $this->consulta_connect->QueryToJson($Informacion,["mes","anno","id_ciclo","ruta","id_municipio","id_lector"],[null,null,null,null,null,null],false);
				$this->consulta_connect->ClosePostgres();

        }

        function consultarRutaCuenta($mes, $anno, $ciclo, $municipio, $ruta){
        	$this->consulta_connect->OpenPostgres();
        	$Informacion = 	$this->consulta_connect->PostgresFunctionCamposTableOrder("pocision",
        																   	     "toma.consulta_pocision_gps_ruta(".$mes.",".$anno.",".$ciclo.",'".$ruta."',".$municipio.") ",
        																   	     "fecha_toma");
			$data = array();
			$i = 0;
			while ($row = pg_fetch_assoc($Informacion)) {
				if($row['pocision'] != ""){
					if($row['pocision'] != "0:0"){
					   $data[$i] = $row['pocision'];
				  	   $i++;
					}				  
				}				
				
  			}
			return json_encode($data);
			$this->consulta_connect->ClosePostgres();
        }

        function consultarRutaInspector($mes, $anno, $ciclo, $municipio, $ruta, $insp){
        	$this->consulta_connect->OpenPostgres();
        	$Informacion = 	$this->consulta_connect->PostgresFunctionCamposTableOrder("pocision",
        																   			  "toma.consulta_pocision_gps_inspector(".$mes.",".$anno.",".$ciclo.",'".$ruta."',".$municipio.",".$insp.") ",
        																   			  "fecha_toma"); 							
			
			$data = array();
			$i = 0;
			while ($row = pg_fetch_assoc($Informacion)) {
				if($row['pocision'] != ""){
					if($row['pocision'] != "0:0"){
					   $data[$i] = $row['pocision'];
				  	   $i++;
					}				  
				}				
				
  			}
			return json_encode($data);
			$this->consulta_connect->ClosePostgres();
        }

        function consultarRutaCuentaDatos($dato, $tipo){
        	$this->consulta_connect->OpenPostgres();

        	if($tipo==1){
        		$Informacion = 	$this->consulta_connect->PostgresSelectJoinWhereOrder("maestro.ordenes AS a", 
																					"a.order_id,a.nombre,a.elemento,b.latitud||':'||b.longitud AS pocision, fecha_registro as fecha_toma",
																					"salida.inf_basica AS b", 
																					"a.order_id = b.id_orden",
																					"a.order_id  = ".$dato."", 
																					"a.order_id");											
        	}else if($tipo==4){
        		$Informacion = 	$this->consulta_connect->PostgresSelectJoinWhereOrder("maestro.ordenes AS a", 
																					"a.order_id,a.nombre,a.elemento,b.latitud||':'||b.longitud AS pocision, fecha_registro as fecha_toma",
																					"salida.inf_basica AS b", 
																					"a.order_id = b.id_orden",
																					"b.acta  = ".$dato."", 
																					"a.order_id");	
        	}else if($tipo==5){
        		$Informacion = 	$this->consulta_connect->PostgresSelectJoinWhereOrder("maestro.ordenes AS a", 
																					"a.order_id,a.nombre,a.elemento,b.latitud||':'||b.longitud AS pocision, fecha_registro as fecha_toma",
																					"salida.inf_basica AS b", 
																					"a.order_id = b.id_orden",
																					"b.fecha_registro::date = '".$dato."'", 
																					"a.order_id");	
        	}else if($tipo==2){
        		$Informacion = 	$this->consulta_connect->PostgresSelectJoinWhereOrder("maestro.ordenes AS a", 
																					"a.order_id,a.nombre,a.elemento,b.latitud||':'||b.longitud AS pocision, fecha_registro as fecha_toma",
																					"salida.inf_basica AS b", 
																					"a.order_id = b.id_orden",
																					"a.elemento = '".$dato."'", 
																					"a.order_id");	
        	}else if($tipo==3){
        		$Informacion = 	$this->consulta_connect->PostgresSelectJoinWhereOrder("maestro.ordenes AS a", 
																					"a.order_id,a.nombre,a.elemento,b.latitud||':'||b.longitud AS pocision, fecha_registro as fecha_toma",
																					"salida.inf_basica AS b", 
																					"a.order_id = b.id_orden",
																					"a.nombre = '".$dato."'", 
																					"a.order_id");	
        	}
        	
			$data = array();
			$arrayName = array();
			$i = 0;
			while ($row = pg_fetch_assoc($Informacion)) {
				if($row['pocision'] != ""){
					if($row['pocision'] != "0:0"){					    
					   $arrayName['cuenta'] = $row['order_id'];
					   $arrayName['medidor'] = $row['elemento'];
					   $arrayName['nombre'] = $row['nombre'];
					   $arrayName['pocision'] = $row['pocision'];					
					   $arrayName['fecha'] = date("d-m-Y",strtotime($row['fecha_toma']));
					   $data[$i] = $arrayName;
				  	   $i++;
					}				  
				}				
				
  			}
			return json_encode($data);
			$this->consulta_connect->ClosePostgres();
        }

        function consultarRutaAnomalias($mes, $anno, $ciclo, $municipio, $ruta){
        	$this->consulta_connect->OpenPostgres();
        	$Informacion = 	$this->consulta_connect->PostgresFunctionCamposTableOrder("fecha_toma,pocision,cuenta,anomalia,mensaje ",
        																   			  "toma.consulta_pocision_gps_anomalias(".$mes.",".$anno.",".$ciclo.",'".$ruta."',".$municipio.") ",
        																   			  "fecha_toma"); 										
			$data = array();
			$arrayName = array();
			$i = 0;
			while ($row = pg_fetch_assoc($Informacion)) {
				if($row['pocision'] != ""){
					if($row['pocision'] != "0:0"){					    
					   $arrayName['cuenta'] = $row['cuenta'];
					   $arrayName['anomalia'] = $row['anomalia'];
					   $arrayName['mensaje'] = $row['mensaje'];
					   $arrayName['pocision'] = $row['pocision'];
					   $arrayName['fecha'] = date("d-m-Y",strtotime($row['fecha_toma']));					
					   $data[$i] = $arrayName;
				  	   $i++;
					}				  
				}				
				
  			}
			return json_encode($data);
			$this->consulta_connect->ClosePostgres();
        }

        function consultarMapaUsuario($dato,$peticion){
        	$this->consulta_connect->OpenPostgres();
        	$Informacion = 	$this->consulta_connect->PostgresFunctionCamposTableOrder("fecha_toma,pocision,cuenta,medidor,nombre,anomalia",
        																   			  "toma.consulta_pocision_usuario('".$peticion."',".$dato.") ",
        																   			  "fecha_toma"); 										
			$data = array();
			$arrayName = array();
			$i = 0;
			while ($row = pg_fetch_assoc($Informacion)) {
				if($row['pocision'] != ""){
					if($row['pocision'] != "0:0"){					    
					   $arrayName['cuenta'] = $row['cuenta'];
					   $arrayName['anomalia'] = $row['anomalia'];
					   $arrayName['medidor'] = $row['medidor'];
					   $arrayName['pocision'] = $row['pocision'];
					   $arrayName['nombre'] = $row['nombre'];
					   $arrayName['fecha'] = date("d-m-Y",strtotime($row['fecha_toma']));					
					   $data[$i] = $arrayName;
				  	   $i++;
					}				  
				}				
				
  			}
			return json_encode($data);
			$this->consulta_connect->ClosePostgres();
        }

	}

?>