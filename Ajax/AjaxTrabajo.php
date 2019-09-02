<?php
	session_start();

	include_once(dirname(__FILE__)."/../Clases/ClassTrabajo.php");
	
	switch($_POST['Peticion']){
		case 'getCiclosActivos': 		getCiclosActivos($_POST['Mes'], $_POST['Anno']); 																break;

		case 'ConsultarAsignacion': 	ConsultarAsignacion($_POST['Ciclo'], $_POST['Mes'], $_POST['Anno']);											break;	
		case 'AsignarTrabajo':			AsignarTrabajo($_POST['Inspector'], $_POST['Rutas'],$_POST['FotoCiclo']);										break;
		case 'ValidarTrabajo':			ValidarTrabajo($_POST['Inspector'],$_POST['Mes'],$_POST['Anno'], $_POST['Rutas']);							    break;
		case 'EliminarAsignacion':		EliminarAsignacion($_POST['Inspector'], $_POST['Rutas']);														break;	

		case 'ConsultaRecuperacion':	ConsultaRecuperacion($_POST['Ciclo'], $_POST['Mes'], $_POST['Anno'], $_POST['Anomalias']);						break;	
		case 'AsignarRecuperacion':		AsignarRecuperacion($_POST['Inspector'], $_POST['Mes'], $_POST['Anno'], $_POST['Cuenta']);						break;	
	
		case 'ConsultaVerificacion':	ConsultaVerificacion($_POST['Ciclo'], $_POST['Mes'], $_POST['Anno'], $_POST['Criticas']);						break;	
		case 'AsignarVerificacion':		AsignarVerificacion($_POST['Inspector'], $_POST['Mes'], $_POST['Anno'], $_POST['Cuenta']);						break;

		case 'ActualizarCuentas':		ActualizarCuentas($_POST['Ciclo'], $_POST['Mes'], $_POST['Anno'], $_POST['Cuentas'],$_POST['Dias'],$_POST['Operacion']);				break;		

	};


	function getCiclosActivos($_mes, $_anno){
		$AjaxTrabajo 	= new ClassTrabajo();
		echo $AjaxTrabajo->getCiclosActivos($_mes, $_anno);
	}

	function ConsultarAsignacion($_ciclo, $_mes, $_anno){
		$AjaxTrabajo 	= new ClassTrabajo();
		echo $AjaxTrabajo->ConsultarAsignacion($_ciclo, $_mes, $_anno);
	}


	function AsignarTrabajo($_inspector, $_rutas,$_fotoCiclo){
		$AjaxTrabajo 	= new ClassTrabajo();
		echo $AjaxTrabajo->AsignarTrabajo($_inspector, $_rutas,$_fotoCiclo);
	}

	function ValidarTrabajo($_inspector,$_mes,$_anno, $_rutas){
		$AjaxTrabajo 	= new ClassTrabajo();
		echo $AjaxTrabajo->ValidarTrabajo($_inspector,$_mes,$_anno, $_rutas);
	}


	function EliminarAsignacion($_inspector, $_rutas){
		$AjaxTrabajo 	= new ClassTrabajo();
		echo $AjaxTrabajo->EliminarAsignacion($_inspector, $_rutas);
	}


	function ConsultaRecuperacion($_ciclo, $_mes, $_anno, $_anomalias){
		$AjaxTrabajo 	= new ClassTrabajo();
		echo $AjaxTrabajo->ConsultaRecuperacion($_ciclo, $_mes, $_anno, $_anomalias);
	}


	function AsignarRecuperacion($_inspector, $_mes, $_anno, $_cuentas){
		$AjaxTrabajo 	= new ClassTrabajo();
		echo $AjaxTrabajo->AsignarRecuperacion($_inspector, $_mes, $_anno, $_cuentas);
	}


	function ConsultaVerificacion($_ciclo, $_mes, $_anno, $_criticas){
		$AjaxTrabajo 	= new ClassTrabajo();
		echo $AjaxTrabajo->ConsultaVerificacion($_ciclo, $_mes, $_anno, $_criticas);
	}


	function AsignarVerificacion($_inspector, $_mes, $_anno, $_cuentas){
		$AjaxTrabajo 	= new ClassTrabajo();
		echo $AjaxTrabajo->AsignarVerificacion($_inspector, $_mes, $_anno, $_cuentas);
	}

	function ActualizarCuentas($_ciclo, $_mes, $_anno, $_cuentas,$_dias,$_operacion){
		$AjaxTrabajo 	= new ClassTrabajo();
		echo $AjaxTrabajo->ActualizarCuentas($_ciclo, $_mes, $_anno, $_cuentas,$_dias,$_operacion);	
	}

?>