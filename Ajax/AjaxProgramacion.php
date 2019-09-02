<?php
	session_start();

	include_once(dirname(__FILE__)."/../Clases/ClassProgramacion.php");
	
	switch($_POST['Peticion']){
		case 'ConsultarDesviaciones':	ConsultarDesviaciones();																						break;		
		case 'ProgramarDesviaciones':	ProgramarDesviaciones($_POST['Fecha'], $_POST['Tecnico'], $_POST['Jornada'], $_POST['Revisiones']);				break;	
		case 'ConsultarAsignadas':		ConsultarAsignadas();																							break;	
		case 'DesasignarDesviaciones':	DesasignarDesviaciones($_POST['Revisiones']);																	break;	
		case 'ConsultarTerreno':		ConsultarTerreno();																								break;	
		case 'ConsultarNotificadas':	ConsultarNotificadas();																							break;	
		case 'AsignarNotificadas':		AsignarNotificadas($_POST['Tecnico'], $_POST['Notificaciones']);												break;	
	};


	function ConsultarDesviaciones(){
		$AjaxProgramacion 	= new Programacion();
		echo $AjaxProgramacion->consultarDesviaciones();
	}


	function ProgramarDesviaciones($_fecha, $_tecnico, $_jornada, $_revisiones){
		$AjaxProgramacion 	= new Programacion();
		echo $AjaxProgramacion->programarDesviaciones($_fecha, $_tecnico, $_jornada, $_revisiones);
	}


	function ConsultarAsignadas(){
		$AjaxProgramacion 	= new Programacion();
		echo $AjaxProgramacion->consultarAsignadas();
	}


	function DesasignarDesviaciones($_revisiones){
		$AjaxProgramacion 	= new Programacion();
		echo $AjaxProgramacion->desasignarDesviaciones($_revisiones);
	}


	function ConsultarTerreno(){
		$AjaxProgramacion 	= new Programacion();
		echo $AjaxProgramacion->consultarTerreno();
	}


	function ConsultarNotificadas(){
		$AjaxProgramacion 	= new Programacion();
		echo $AjaxProgramacion->consultarNotificadas();
	}


	function AsignarNotificadas($_tecnico, $_notificaciones){
		$AjaxProgramacion 	= new Programacion();
		echo $AjaxProgramacion->asignarNotificadas($_tecnico, $_notificaciones);
	}

?>