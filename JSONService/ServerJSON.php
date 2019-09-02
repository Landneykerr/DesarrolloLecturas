<?php
	/**
	Fecha: 			27-10-2014
	Version: 		1.0
	Creado Por: 	Julian Eduardo Poveda Daza
	Descripcion: 	Archivos para comunicacion entre aplicacion movil y servidor sin necesidad de usar web service
	**/
	
	include_once (dirname(__FILE__)."/../BaseDatos/PostgresBD.php");

	

	switch($_POST['peticion']){
		case 'codigo_apertura':	ConsultarCodigoApertura($_POST['solicitud']);	break;
	}

	
	function ConsultarCodigoApertura($_solicitud){
		//$myDB = new PostgresDB('190.93.133.87','5432','admin_desviaciones','4y4n4m1','desviaciones_facturas');
		$myDB = new PostgresDB();
		$myDB->OpenPostgres();
		echo $myDB->PostgresFunction("entrada.registro_apertura_orden(".$_solicitud.")");
        $myDB->ClosePostgres();
	}
?>