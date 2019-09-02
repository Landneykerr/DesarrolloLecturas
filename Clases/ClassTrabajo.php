<?php
	session_start();
	include_once(dirname(__FILE__)."/../Clases/ClassPostgresBD.php");

	class ClassTrabajo{
	   private $trabajo_connect;
	   
		function ClassTrabajo(){
			$this->trabajo_connect = new PostgresDB();
		}


		function getCiclosActivos($_mes, $_anno){
			$this->trabajo_connect->OpenPostgres();
			$Informacion =  $this->trabajo_connect->PostgresSelectDistinctWhereOrder( 	"maestro.log_cargue_ciclos", 
																						"id_ciclo AS texto, id_ciclo AS valor", 
																						"mes = ".$_mes." AND anno = ".$_anno." AND estado_ciclo = 'P'", 
																						"texto");
			return json_encode($this->trabajo_connect->QueryToJson($Informacion,["texto","valor"],[null,null],true));
			$this->trabajo_connect->ClosePostgres();
		}


		function ConsultarAsignacion($_ciclo, $_mes, $_anno){
			$this->trabajo_connect->OpenPostgres();
			$Informacion =  $this->trabajo_connect->PostgresFunctionTable("maestro.Programacion_By_Ruta(".$_ciclo.",".$_mes.",".$_anno.");");
			return json_encode($this->trabajo_connect->QueryToJson($Informacion,["id","inspector","ruta","total","leidas","pendientes","foto","voucher"],[null,null,null,null,null,null,'true','true'],false));
			$this->trabajo_connect->ClosePostgres();
		}


		function AsignarTrabajo($_inspector, $_rutas,$_fotoCiclo){
			for($j=0;$j<sizeof($_rutas['Id_Rutas']);$j++){
				$arrayRutas 	.= $_rutas['Id_Rutas'][$j]['id'].",";
				$arrayFotos		.= $_rutas['Id_Rutas'][$j]['foto'].",";
				$arrayVoucher	.= $_rutas['Id_Rutas'][$j]['voucher'].",";
			}
			$arrayRutas		= "array[".substr($arrayRutas,0,-1)."]";
			$arrayFotos		= "array[".substr($arrayFotos,0,-1)."]";
			$arrayVoucher	= "array[".substr($arrayVoucher,0,-1)."]";

			$this->trabajo_connect->OpenPostgres();
			$_respuesta = $this->trabajo_connect->PostgresFunction("maestro.asignacion_trabajo_tecnico(".$_inspector.",".$arrayRutas.",".$arrayFotos.",".$arrayVoucher.",".$_fotoCiclo.")");
			$this->trabajo_connect->ClosePostgres();
			return $_respuesta;
		}

		function ValidarTrabajo($_inspector,$mes,$anno, $_rutas){
			for($j=0;$j<sizeof($_rutas['Id_Rutas']);$j++){
				$arrayRutas 	.= $_rutas['Id_Rutas'][$j]['id'].",";
				$arrayFotos		.= $_rutas['Id_Rutas'][$j]['foto'].",";
				$arrayVoucher	.= $_rutas['Id_Rutas'][$j]['voucher'].",";
				$arrayRutasT    .="'".$_rutas['Id_Rutas'][$j]['rutas']."',";
			}
			$arrayRutas		= "array[".substr($arrayRutas,0,-1)."]";
			$arrayFotos		= "array[".substr($arrayFotos,0,-1)."]";
			$arrayVoucher	= "array[".substr($arrayVoucher,0,-1)."]";
			$arrayRutasT	= "array[".substr($arrayRutasT,0,-1)."]";

			$this->trabajo_connect->OpenPostgres();			
			$_respuesta = $this->trabajo_connect->PostgresFunction("maestro.validar_asignacion_trabajo_tecnico(".$mes.",".$anno.",".$arrayRutasT.",".$_inspector.")");
			$this->trabajo_connect->ClosePostgres();
			return $_respuesta;
		}


		function EliminarAsignacion($_inspector, $_rutas){
			for($j=0;$j<sizeof($_rutas['Id_Rutas']);$j++){
				$arrayRutas 	.= $_rutas['Id_Rutas'][$j]['id'].",";
			}
			$arrayRutas= "array[".substr($arrayRutas,0,-1)."]";
			$this->trabajo_connect->OpenPostgres();
			$_respuesta = $this->trabajo_connect->PostgresFunction("maestro.eliminar_asignacion_trabajo_tecnico(".$_inspector.",".$arrayRutas.")");
			$this->trabajo_connect->ClosePostgres();
			return $_respuesta;
		}




		/**METODOS PARA EL PROCESO DE RECUPERACION**/
		function ConsultaRecuperacion($_ciclo, $_mes, $_anno, $_anomalias){
			$this->trabajo_connect->OpenPostgres();
			$Informacion =  $this->trabajo_connect->PostgresFunctionCamposTable("ruta,secuencia_ruta,cuenta,nombre,direccion,medidor,lectura,id_anomalia,mensaje","toma.reporte_cuentas_pendientes_no_inspector(".$_mes.",".$_anno.",".$_ciclo.",'R') WHERE id_anomalia IN (".$_anomalias.")");
			return json_encode($this->trabajo_connect->QueryToJson($Informacion,["","ruta","secuencia_ruta","cuenta","nombre","direccion","medidor","id_anomalia","mensaje"],[null,null,null,null,null,null],false));
			$this->trabajo_connect->ClosePostgres();
		}



		function AsignarRecuperacion($_inspector, $_mes, $_anno, $_cuentas){
			for($j=0;$j<sizeof($_cuentas['Cuentas']);$j++){
				$arrayCuentas 	.= $_cuentas['Cuentas'][$j]['cuenta'].",";
			}
			$arrayCuentas= "array[".substr($arrayCuentas,0,-1)."]";
			$this->trabajo_connect->OpenPostgres();
			$_respuesta = $this->trabajo_connect->PostgresFunction("maestro.asignacion_no_inspectores(".$_inspector.",".$_mes.",".$_anno.",'R',".$arrayCuentas.",'".$_SESSION['UserName']."')");
			$this->trabajo_connect->ClosePostgres();
			return $_respuesta;
		}



		/**METODOS PARA EL PROCESO DE RECUPERACION**/
		function ConsultaVerificacion($_ciclo, $_mes, $_anno, $_criticas){
			$this->trabajo_connect->OpenPostgres();
			$Informacion =  $this->trabajo_connect->PostgresFunctionCamposTable("ruta,secuencia_ruta,cuenta,nombre,direccion,medidor,lectura,str_critica,mensaje","toma.reporte_cuentas_pendientes_no_inspector(".$_mes.",".$_anno.",".$_ciclo.",'V') WHERE str_critica IN (".$_criticas.")");
			return json_encode($this->trabajo_connect->QueryToJson($Informacion,["","ruta","secuencia_ruta","cuenta","nombre","direccion","medidor","lectura","str_critica","mensaje"],[null,null,null,null,null,null],false));
			$this->trabajo_connect->ClosePostgres();
		}


		function AsignarVerificacion($_inspector, $_mes, $_anno, $_cuentas){
			for($j=0;$j<sizeof($_cuentas['Cuentas']);$j++){
				$arrayCuentas 	.= $_cuentas['Cuentas'][$j]['cuenta'].",";
			}
			$arrayCuentas= "array[".substr($arrayCuentas,0,-1)."]";
			$this->trabajo_connect->OpenPostgres();
			$_respuesta = $this->trabajo_connect->PostgresFunction("maestro.asignacion_no_inspectores(".$_inspector.",".$_mes.",".$_anno.",'V',".$arrayCuentas.",'".$_SESSION['UserName']."')");
			$this->trabajo_connect->ClosePostgres();
			return $_respuesta;
		}

		function ActualizarCuentas($_ciclo, $_mes, $_anno, $_cuentas,$_dias,$_operacion){
			$Cuentas = explode(",", $_cuentas);
			for($j=0;$j<sizeof($Cuentas);$j++){
				$arrayCuentas 	.= $Cuentas[$j].",";
			}
			$arrayCuentas= "array[".substr($arrayCuentas,0,-1)."]";
			$this->trabajo_connect->OpenPostgres();
			$_respuesta = $this->trabajo_connect->PostgresFunction("reportes.update_fecha_toma(".$_ciclo.",".$_mes.",".$_anno.",".$arrayCuentas.",".$_dias.",'".$_operacion."')");
			$this->trabajo_connect->ClosePostgres();
			return $_respuesta;	
		}
	}
?>

