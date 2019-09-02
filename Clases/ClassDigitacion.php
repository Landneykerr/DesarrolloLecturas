<?php
	session_start();
	include_once(dirname(__FILE__)."/../Clases/ClassPostgresBD.php");

	class ClassDigitacion{
		private $dig_connect;
	   
		function ClassDigitacion(){
			$this->dig_connect = new PostgresDB();
		}


		function GuardarDatosLectura($_idProgramacion, $_tipoLectura, $_idSerial1, $_lectura1, $_critica1, $_idSerial2, $_lectura2, $_critica2, $_idSerial3, $_lectura3, $_critica3, $_anomalia, $_mensaje, $_inspector){
			
			$this->dig_connect->OpenPostgres();	


			$queryRespuesta = $this->dig_connect->PostgresFunction("maestro.recibir_no_lecturas(".$_idSerial1.",".$_idProgramacion.",'".$_tipoLectura."',".$_lectura1.",".$_critica1.",".$_idSerial2.",".$_lectura2.",".$_critica2.",".$_idSerial3.",".$_lectura3.",".$_critica3.",".$_anomalia.",'".utf8_encode(str_replace("&","y",$_mensaje))."','00',".$_inspector.",'".date("d/m/Y H:i:s")."',0,0,'00:00:00:00:00:00')");

            if($queryRespuesta == $_idSerial1){
                return true;
            }else{
                return false;
            }

            $this->dig_connect->ClosePostgres();    
		}


		function ConsultarDescripcionCritica($_critica1, $_critica2, $_critica3){
			$this->dig_connect->OpenPostgres();	

			$queryCritica = $this->dig_connect->PostgresSelectWhereOrder("parametros.critica", 
					"descripcion",
					"rango_minimo <= ".$_critica1." AND rango_maximo > ".$_critica1,
					"descripcion");

			$infCritica = pg_fetch_assoc($queryCritica);
			$dataCritica['primera'] = $infCritica['descripcion'];



			$queryCritica = $this->dig_connect->PostgresSelectWhereOrder("parametros.critica", 
					"descripcion",
					"rango_minimo <= ".$_critica2." AND rango_maximo > ".$_critica2,
					"descripcion");

			$infCritica = pg_fetch_assoc($queryCritica);
			$dataCritica['segunda'] = $infCritica['descripcion'];




			$queryCritica = $this->dig_connect->PostgresSelectWhereOrder("parametros.critica", 
					"descripcion",
					"rango_minimo <= ".$_critica3." AND rango_maximo > ".$_critica3,
					"descripcion");

			$infCritica = pg_fetch_assoc($queryCritica);
			$dataCritica['tercera'] = $infCritica['descripcion'];

			return json_encode($dataCritica);
			
			$this->dig_connect->ClosePostgres();	
		}



		function ConsultarInfCuentaLectura($_mes, $_anno, $_tipo, $_cuenta){
			$this->dig_connect->OpenPostgres();	

			$Informacion = $this->dig_connect->PostgresSelectJoinWhereOrder("maestro.log_cuentas_no_inspectores AS a", 
				"b.id_programacion, a.cuenta, a.estado, b.id_inspector, b.usuario, b.fecha_programacion, b.tipo", 
				"maestro.log_programacion_no_inspectores AS b", 
				"a.id_programacion = b.id_programacion",
				"b.mes = ".$_mes." AND b.anno = ".$_anno." AND b.tipo = '".$_tipo."' AND a.cuenta = ".$_cuenta." AND a.estado = 'P'" , 
				"id_programacion");

			if(pg_num_rows($Informacion) == 0){
				$data['estado'] 	= false;
				$data['mensaje'] 	= "Cuenta no disponible";
				$data['id_programacion'] = null;
				$data['inspector']	= null;
				$data['tipo']		= null;
				$data['id_ciclo'] 	= null;
				$data['cuenta']		= null;
				$data['nombre'] 	= null;
				$data['direccion'] 	= null;


				$lectura[0]['id_serial']	= -1;
				$lectura[0]['lectura'] 		= -1;
				$lectura[0]['promedio'] 	= -1;
				$lectura[0]['tipo_energia'] = "N";	

				$lectura[1]['id_serial']	= -1;
				$lectura[1]['lectura'] 		= -1;
				$lectura[1]['promedio'] 	= -1;
				$lectura[1]['tipo_energia'] = "N";	

				$lectura[2]['id_serial']	= -1;
				$lectura[2]['lectura'] 		= -1;
				$lectura[2]['promedio'] 	= -1;
				$lectura[2]['tipo_energia'] = "N";

				$medidor[0]['medidor'] 	= null;
				$medidor[0]['factor'] 	= null;
				$medidor[0]['lecturas']	= $lectura;

				$data['medidores'] = $medidor;

			}else{
				$data['estado'] = true;
				$data['mensaje'] = "Cuenta encontrada";

				$infDatos = pg_fetch_assoc($Informacion);

				$data['id_programacion']= $infDatos['id_programacion'];
				$data['inspector']		= $infDatos['id_inspector'];
				$data['tipo']			= $infDatos['tipo'];


				$queryCuenta = $this->dig_connect->PostgresFunctionCamposTable("id_ciclo, id_municipio, ruta, medidor, serie, nombre, direccion, factor, id_serial_1, lectura_1, tipo_energia_1, promedio_1, id_serial_2, lectura_2, tipo_energia_2, promedio_2, id_serial_3, lectura_3, tipo_energia_3, promedio_3", "maestro.inf_cuenta(".$_cuenta.",".$_mes.",".$_anno.")"); 


				$i = 0;

				while($infCuenta = pg_fetch_assoc($queryCuenta)){

					$data['id_ciclo'] 	= $infCuenta['id_ciclo']."-".$infCuenta['id_municipio']."-".$infCuenta['ruta'];
					$data['cuenta']		= $_cuenta;
					//$data['medidor'] 	= $infCuenta['medidor']." ".$infCuenta['serie'];

					$data['nombre'] 	= $infCuenta['nombre'];
					$data['direccion'] 	= $infCuenta['direccion'];
					

					$medidor[$i]['medidor'] = $infCuenta['medidor']." ".$infCuenta['serie'];
					$medidor[$i]['factor']	= $infCuenta['factor'];


					if($infCuenta['id_serial_1'] != -1){
						$lectura[0]['id_serial']	= $infCuenta['id_serial_1'];
						$lectura[0]['lectura'] 		= $infCuenta['lectura_1'];
						$lectura[0]['promedio'] 	= $infCuenta['promedio_1'];
						$lectura[0]['tipo_energia'] = $infCuenta['tipo_energia_1'];
					}else{
						$lectura[0]['id_serial']	= -1;
						$lectura[0]['lectura'] 		= -1;
						$lectura[0]['promedio'] 	= -1;
						$lectura[0]['tipo_energia'] = "N";	
					}


					if($infCuenta['id_serial_2'] != -1){
						$lectura[1]['id_serial']	= $infCuenta['id_serial_2'];
						$lectura[1]['lectura'] 		= $infCuenta['lectura_2'];
						$lectura[1]['promedio'] 	= $infCuenta['promedio_2'];
						$lectura[1]['tipo_energia'] = $infCuenta['tipo_energia_2'];
					}else{
						$lectura[1]['id_serial']	= -1;
						$lectura[1]['lectura'] 		= -1;
						$lectura[1]['promedio'] 	= -1;
						$lectura[1]['tipo_energia'] = "N";
					}


					if($infCuenta['id_serial_3'] != -1){
						$lectura[2]['id_serial']	= $infCuenta['id_serial_3'];
						$lectura[2]['lectura'] 		= $infCuenta['lectura_3'];
						$lectura[2]['promedio'] 	= $infCuenta['promedio_3'];
						$lectura[2]['tipo_energia'] = $infCuenta['tipo_energia_3'];
					}else{
						$lectura[2]['id_serial']	= -1;
						$lectura[2]['lectura'] 		= -1;
						$lectura[2]['promedio'] 	= -1;
						$lectura[2]['tipo_energia'] = "N";	
					}

					$medidor[$i]['lecturas']	= $lectura;

					$i = $i+1;
				}

				$data['medidores'] 	= $medidor;
			}

			return json_encode($data);


			/*return json_encode($this->dig_connect->QueryToJson($Informacion, 
				["id_programacion", "cuenta", "estado", "id_inspector", "usuario", "fecha_programacion"],
				[null, null, null, null, null, null], false));*/

			$this->dig_connect->ClosePostgres();
		}


		function ConsultarSupervision($_fecha){
			$this->dig_connect->setConexion('fotos');
			$this->dig_connect->OpenPostgres();
			$Informacion = 	$this->dig_connect->PostgresFunctionCamposTable("id_serial,fecha_registro,fecha,descripcion,usuario,archivos", 
																			"supervision.fcn_consulta_supervision('".$_fecha."', '')"); 
			
			return json_encode($this->dig_connect->QueryToJson($Informacion,["id_serial","fecha_registro","fecha","descripcion","usuario","archivos"],[null,null,null,null,null,null],false));
			$this->dig_connect->setDefaultConexion();
			$this->dig_connect->ClosePostgres();
		}


		function ConsultaCorreccion($_mes, $_anno, $_tipo, $_dato){
			$this->dig_connect->OpenPostgres();
			if($_tipo == 1){
				$Informacion = 	$this->dig_connect->PostgresFunctionCamposTableWhere(	"id_toma_lectura,id_ciclo,cuenta,medidor,tipo_energia,lectura_anterior,lectura,descripcion_critica,descripcion_anomalia,mensaje",
																						"toma.toma_lectura_by_cuenta(".$_dato.")",
																						"mes = ".$_mes." AND anno = ".$_anno);
			}else{
				$Informacion = 	$this->dig_connect->PostgresFunctionCamposTableWhere(	"id_toma_lectura,id_ciclo,cuenta,medidor,tipo_energia,lectura_anterior,lectura,descripcion_critica,descripcion_anomalia,mensaje",
																						"toma.toma_lectura_by_medidor('".$_dato."')",
																						"mes = ".$_mes." AND anno = ".$_anno);
			}

			return json_encode($this->dig_connect->QueryToJson($Informacion,["id_toma_lectura","id_ciclo","cuenta","medidor","tipo_energia","lectura_anterior","lectura","descripcion_critica","descripcion_anomalia","mensaje"],[null,null,null,null,null,null,null],false));
			$this->dig_connect->ClosePostgres();
		}



		function GuardarCorreccion($_id_serial, $_lectura, $_anomalia, $_mensaje, $_foto, $_username){
			$this->dig_connect->OpenPostgres();
			return $this->dig_connect->PostgresFunction("toma.guardar_correccion(".$_id_serial.",".$_lectura.",".$_anomalia.",'".$_mensaje."','".$_foto."','".$_username."')");
			$this->dig_connect->ClosePostgres();
		}


		function ConsultarRecuperacion($_mes, $_anno, $_tipo){
			$this->dig_connect->OpenPostgres();
			$Informacion = 	$this->dig_connect->PostgresFunctionTable("toma.consulta_no_lecturas(".$_mes.",".$_anno.",".$_tipo.")");
			

			return json_encode($this->dig_connect->QueryToJson($Informacion,["serial","ciclo","cuenta","medidor","direccion","fecha","lectura","lecturaactual","promedio","anomalia","critica","mensaje"],
								[null,null,null,null,null,null,null,null,null,null],false));
			$this->dig_connect->ClosePostgres();
		}


		function ProcesarRecuperacion($_id, $_estado,$_username){
			$arrayId = "";
			for($j=0;$j<sizeof($_id['Id']);$j++){
				$arrayId 	.= $_id['Id'][$j]['id_serial'].",";
			}
			$arrayId= "array[".substr($arrayId,0,-1)."]";
		
			$this->dig_connect->OpenPostgres();
			$Informacion = 	$this->dig_connect->PostgresFunction("maestro.ProcesarRecuperacion(".$arrayId.",'".$_estado."','".$_username."')");
			$this->dig_connect->ClosePostgres();
			return $Informacion;
		}
	}

?>