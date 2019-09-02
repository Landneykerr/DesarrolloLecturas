<?php
	session_start();
	include_once(dirname(__FILE__)."/../Clases/ClassPostgresBD.php");

	class Programacion{
		private $prog_connect;
	   
		function Programacion(){
			$this->prog_connect = new PostgresDB();
        }



       	/**
		Function para crear un tecnico en el sistema
		**/
		function consultarDesviaciones(){
			$this->prog_connect->OpenPostgres();
			$informacion = 	$this->prog_connect->PostgresSelectWhereOrder(	"SolicitudesPendientes", 
																			"revision,codigo,ciclo,visita,direccion", 
																			"revision IS NOT NULL", 
																			"revision DESC");
			return $this->prog_connect->QueryToJson($informacion,["revision","codigo","ciclo","visita","direccion"],[null,null,null,null,null],false);
			$this->prog_connect->ClosePostgres();
		}


		function programarDesviaciones($_fecha, $_tecnico, $_jornada, $_revisiones){
			$this->prog_connect->OpenPostgres();
			for($j=0;$j<sizeof($_revisiones['ListaPendientes']);$j++){
				$this->prog_connect->PostgresFunction("ProgramarRevision('".$_fecha."',".$_tecnico.",'".$_jornada."','".$_revisiones['ListaPendientes'][$j]['Revision']."')");
			}
			$this->prog_connect->ClosePostgres();
			return $this->consultarDesviaciones();			
		}


		/**
		Function para crear un tecnico en el sistema
		**/
		function consultarAsignadas(){
			$this->prog_connect->OpenPostgres();
			$informacion = 	$this->prog_connect->PostgresSelectWhereOrder(	"SolicitudesProgramadas", 
																			"fecha_cargue,fecha_asignacion,revision,codigo,ciclo||'-'||ruta as ciclo,tecnico,jornada", 
																			"revision IS NOT NULL", 
																			"fecha_cargue");
			return $this->prog_connect->QueryToJson($informacion,["fecha_cargue","fecha_asignacion","revision","codigo","ciclo","tecnico","jornada"],['Fecha','Fecha',null,null,null,null,null],false);
			$this->prog_connect->ClosePostgres();
		}


		/**
		Function para crear un tecnico en el sistema
		**/
		function consultarTerreno(){
			$this->prog_connect->OpenPostgres();
			$informacion = 	$this->prog_connect->PostgresSelectWhereOrder(	"SolicitudesTerreno", 
																			"fecha_asignacion,revision,codigo,ciclo||'-'||ruta as ciclo,tecnico,jornada", 
																			"revision IS NOT NULL", 
																			"fecha_asignacion");
			return $this->prog_connect->QueryToJson($informacion,["fecha_asignacion","revision","codigo","ciclo","tecnico","jornada"],['Fecha',null,null,null,null,null,null],false);
			$this->prog_connect->ClosePostgres();
		}


		/**
		Function para crear un tecnico en el sistema
		**/
		function desasignarDesviaciones($_revisiones){
			$this->prog_connect->OpenPostgres();
			for($j=0;$j<sizeof($_revisiones['ListaAsignadas']);$j++){
				$this->prog_connect->PostgresUpdateValues(	"solicitudes", 
															"estado_revision='N', fecha_asignacion=null, jornada=null, tecnico=null, pda= null", 
															"estado_revision='P' and revision='".$_revisiones['ListaAsignadas'][$j]['Revision']."'");
			}
			$this->prog_connect->ClosePostgres();
			return $this->consultarAsignadas();
		}


		/**
		Function para crear un tecnico en el sistema
		**/
		function consultarNotificadas(){
			$this->prog_connect->OpenPostgres();
			$informacion = 	$this->prog_connect->PostgresSelectJoinWhereOrder(	"SolicitudesNotificadas as a",
																				"a.revision, a.codigo, cast(b.ciclo as text)||'-'||a.ruta as ciclo, b.visita, b.direccion, a.tecnico, cast(b.fechanotificacion as text)||' '||b.jornadanotificacion as fechanotificacion",			
																				"notificaciones as b",
																				"a.revision=b.revision",
																				"a.revision IS NOT NULL",
																				"b.fechanotificacion ASC");
			
			return $this->prog_connect->QueryToJson($informacion,["revision","codigo","ciclo","visita","direccion","tecnico","fechanotificacion"],[null,null,null,null,null,null,null],false);
			$this->prog_connect->ClosePostgres();
		}


		/**
		Function para crear un tecnico en el sistema
		**/
		function asignarNotificadas($_tecnico, $_notificaciones){
			$this->prog_connect->OpenPostgres();
			for($j=0;$j<sizeof($_notificaciones['ListaNotificadas']);$j++){
				$_datos = explode(" ",$_notificaciones['ListaNotificadas'][$j]['Fecha']);
				$this->prog_connect->PostgresFunction("ProgramarRevision('".$_datos[0]."',".$_tecnico.",'".$_datos[1]."','".$_notificaciones['ListaNotificadas'][$j]['Revision']."')");
			}
			$this->prog_connect->ClosePostgres();
			return $this->consultarNotificadas();
		}
	}

?>