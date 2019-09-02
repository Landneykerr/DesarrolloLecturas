<?php
	session_start();

	include_once(dirname(__FILE__)."/../Clases/ClassMovimientos.php");
	
	switch($_POST['Peticion']){
		case 'VerNotificaciones':  			VerNotificaciones($_POST['FechaConsulta']);																				break;	
		case 'Movimientos2Notificaciones':	Movimientos2Notificaciones($_POST['Revisiones'], $_POST['FechaConsulta'], $_POST['NewFecha'], $_POST['NewJornada']);	break;
	};


	function VerNotificaciones($_fecha){
		$AjaxMovimientos 	= new Movimientos();
		echo $AjaxMovimientos->VerNotificaciones($_fecha);
	}


	function Movimientos2Notificaciones($_revisiones, $_fechaConsulta, $_fecha, $_jornada){
		$AjaxMovimientos 	= new Movimientos();
		echo $AjaxMovimientos->Movimientos2Notificaciones($_revisiones, $_fechaConsulta, $_fecha, $_jornada);
	}


	

?>