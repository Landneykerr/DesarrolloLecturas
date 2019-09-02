<?php
	session_start();
	include_once(dirname(__FILE__)."/../Clases/ClassPostgresBD.php");

	class Movimientos{
		private $mov_connect;
	   
		function Movimientos(){
			$this->mov_connect = new PostgresDB();
        }


        function VerNotificaciones($_fecha){
        	$this->mov_connect->OpenPostgres();
			$Informacion = 	$this->mov_connect->PostgresSelectJoinWhereOrder(	"SolicitudesNotificadas as a", 
																				"a.revision, a.codigo, b.fechanotificacion as fecha_asignacion, b.jornadanotificacion as jornada", 
																				"notificaciones as b", 
																				"a.revision=b.revision", 
																				"b.fechanotificacion='".$_fecha."' and estado_revision = 'N'",
																				"b.fechanotificacion ASC");


			return $this->mov_connect->QueryToJson($Informacion,["revision","codigo","fecha_asignacion","jornada"],[null,null,'Fecha',null],false);
			$this->mov_connect->ClosePostgres();
        }



        function Movimientos2Notificaciones($_revisiones, $_fechaConsulta, $_fecha, $_jornada){
        	$this->mov_connect->OpenPostgres();
        	for($j=0;$j<sizeof($_revisiones['ListaRevisiones']);$j++){
				$this->mov_connect->PostgresFunction("ModificarFechaNotificacion('".$_revisiones['ListaRevisiones'][$j]['Revision']."','".$_fecha."','".$_jornada."')");
			}
        	$this->mov_connect->ClosePostgres();
        	return $this-> VerNotificaciones($_fechaConsulta);
        }
	}

?>