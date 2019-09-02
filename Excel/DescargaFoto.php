<?php
	session_start();
	
	$_myDataBase =  new PostgresDB();
	$fecha    	= $_GET['Fecha'];
	$inspector  = $_GET['Inspector'];

    $conn = pg_connect("user=consult_fotos password=l3ctur4sf0t0s dbname=fotos_lecturas host=186.115.150.189");    
    $query = pg_query($conn, "SELECT foto,cuenta, fecha_toma,nombre_foto FROM registro.imagenes_visor WHERE inspector = ".$inspector." AND cast(fecha_toma AS date) = '".$fecha."' ORDER BY fecha_toma DESC");


    $zip = new ZipArchive();
 	$zip->open("uploads/prueba.zip",ZipArchive::CREATE);

 	
    while($row   = pg_fetch_assoc($query)){

    	$Base64Img = base64_decode($row['foto']);
    	file_put_contents("uploads/".$row['nombre_foto'],$Base64Img);	
    	
    	$zip->addFile("uploads/".$row['nombre_foto'], $row['nombre_foto']);
      //  unlink("uploads/".$row['nombre_foto']);
    }    

 	$zip->close();

 	header( "Content-Type: application/force-download"); 
	header( "Content-Length: ".filesize("uploads/prueba.zip")); 
	header( "Content-Disposition: attachment; filename=".basename("prueba.zip")); 
	readfile("uploads/prueba.zip");	

?>