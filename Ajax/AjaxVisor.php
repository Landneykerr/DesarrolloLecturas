<?php
	session_start();

	include_once(dirname(__FILE__)."/../Clases/ClassVisor.php");
	
	switch($_POST['Peticion']){
		case 'ConsultarFotos':			ConsultarFotos($_POST['Fecha'],$_POST['Inspector']);				 break;
		/*case 'ConsultaRuta':			ConsultaRuta($_POST['Dato'],$_POST['Fecha'],$_POST['ValorR']);		 break;
		case 'ConsultaMapa':			ConsultaMapa();		             								 	 break;
		case 'ConsultaMapaUsuario':		ConsultaMapaUsuario($_POST['Dato'],$_POST['Tipo']);				     break;*/
	};


	function ConsultarFotos($fecha,$inspector){		
		//echo "DatosPrueba";
		$AjaxDigitacion 	= new ClassVisor();
		echo $AjaxDigitacion->consultarFotos($fecha,$inspector);
	}
	/*
	function ConsultaRuta($dato,$fecha,$radio){
		$AjaxConsultas 	= new Consultas();
		echo $AjaxConsultas->consultarRuta($dato,$fecha,$radio);
	}

	function ConsultaMapa(){
		$AjaxConsultas 	= new Consultas();
		echo $AjaxConsultas->consultarMapa();	
	}

	function ConsultaMapaUsuario($dato,$peticion){
		$AjaxConsultas 	= new Consultas();
		echo $AjaxConsultas->consultarMapaUsuario($dato,$peticion);
	}*/


?>