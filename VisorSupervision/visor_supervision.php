<?php
	session_start();
	include_once(dirname(__FILE__)."/../Clases/ClassPostgresBD.php");

	$id 	= $_GET['id'];
	$archivo= $_GET['archivo'];


	$dig_connect = new PostgresDB();

	$dig_connect->setConexion('fotos');
	$dig_connect->OpenPostgres();
	$Informacion =  $dig_connect->PostgresSelectWhereOrder(	"supervision.archivos_reportes", 
																	"archivo", 
																	"id_reporte=".$id." AND nombre_archivo='".$archivo."'", 
																	"archivo");
	$row   = pg_fetch_assoc($Informacion);

	$dig_connect->setDefaultConexion();
	$dig_connect->ClosePostgres();

		 
	echo "<li>
			<img src='data:image/jpg;base64,".$row['archivo']."'>
		</li>";
  ?> 