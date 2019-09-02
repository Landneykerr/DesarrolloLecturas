<?php
	session_start();
	include_once(dirname(__FILE__)."/../Clases/ClassPostgresBD.php");

	$mensaje = '';
	
	$arrayNombre = "";
	$arrayInf	 = "";
    

	foreach ($_FILES as $key) {
		if($key['error'] == UPLOAD_ERR_OK){
			$nombreOriginal = $_SESSION['UserName']."_".$key['name'];
			$nombreTemporal = $key['tmp_name'];
			move_uploaded_file($nombreTemporal, $nombreOriginal);
		}


		if($key['error'] == ''){
			$mensaje .='-> Archivo <b>'.$nombreOriginal.'</b> Subido correctamente. <br>';
			$im = file_get_contents($nombreOriginal);
			$imdata = base64_encode($im);

			$arrayNombre 	.= "'".$key['name']."',";
			$arrayInf		.= "'".$imdata."',";


		}else if($key['error'] != ''){
			$mensaje .='-> No se pudo subir el archivo <b>'.$nombreOriginal.'</b> debido al siguiente error \n: '.$key['error'];
		}
		unlink($nombreOriginal);
	}

	$arrayNombre= "array[".substr($arrayNombre,0,-1)."]";
	$arrayInf 	= "array[".substr($arrayInf,0,-1)."]";

	$dig_connect = new PostgresDB();
	$dig_connect->setConexion('fotos');
	$dig_connect->OpenPostgres();
	echo $dig_connect->PostgresFunction("supervision.reportes('".$_POST['fecha']."','".$_SESSION['UserName']."','".$_POST['descripcion']."',".$arrayInf.",".$arrayNombre.")");
	$dig_connect->ClosePostgres();
?>