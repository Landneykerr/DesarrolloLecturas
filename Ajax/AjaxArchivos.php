<?php
	session_start();

	include_once(dirname(__FILE__)."/../Clases/ClassArchivos.php");
	
	switch($_POST['Peticion']){
		case 'CerrarCiclos': 				CerrarCiclos($_POST['Mes'], $_POST['Anno'], $_POST['Ciclos']);			break;
	};


	function CerrarCiclos($_mes, $_anno, $_ciclos){
		$AjaxArchivos 	= new ClassArchivos();
		echo $AjaxArchivos->CerrarCiclos($_mes, $_anno, $_ciclos);
	}

?>