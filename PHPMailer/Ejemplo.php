<?php
	include_once(dirname(__FILE__)."/class.phpmailer.php");
    include_once(dirname(__FILE__)."/class.smtp.php");
    include_once(dirname(__FILE__)."/../Clases/ClassPostgresBD.php");

	function enviarEmail($idmaestro){

		$postgresWS = new PostgresDB();
        $postgresWS->OpenPostgres();	

        $fotos = array();
        $i=0;

        $query =  $postgresWS->PostgresSelectWhereOrder("toma.registro_fotos", 
         												"cuenta", 
         												"id_maestro_emsa = ".$idmaestro, 
         												"cuenta LIMIT 1");

        $row   = pg_fetch_assoc($query);
        $cuenta = 	$row['cuenta'];
	    $directorio = opendir("uploads/".$row['cuenta']); 
		while ($archivo = readdir($directorio)) 
		{		    
		  $fotos[$i] = "uploads/".$row['cuenta']."/".$archivo;
	    	$i = $i+1;  
		}	

	    $queryInfo =  $postgresWS->PostgresSelectWhereOrder("toma.lectura", 
         												   "id_anomalia,fecha_toma,mensaje,latitud,longitud", 
         												   "id_maestro_emsa = ".$idmaestro, 
         												   "fecha_toma DESC LIMIT 1");	

	    while($row   = pg_fetch_assoc($queryInfo)){
	        
	        $body = "Buen dia. \n"."Datos de la cuenta ".$cuenta." \n Anomalia: ".$row['id_anomalia']." Mensaje: ".$row['mensaje']." Fecha Toma: ".$row['fecha_toma']." Pocison GPS: Lat:".$row['latitud']." Long:".$row['longitud'];
	    
	    }    



    	$postgresWS->ClosePostgres(); 


		//Inicio de creacion de correo electronico automatico
		$mail             = new PHPMailer();
		//$body             = "Buen dia. ";
		//$body             = eregi_replace("[\]",'',$body);
		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->Host       = "Sypelc.Villavicencio.com"; // SMTP server
		$mail->SMTPDebug  = 2;                     		// enables SMTP debug information (for testing)
		$mail->SMTPAuth   = true;                  		// enable SMTP authentication
		$mail->SMTPSecure = "tls";                 		// sets the prefix to the servier
		$mail->Host       = "smtp.gmail.com";      		// sets GMAIL as the SMTP server
		$mail->Port       = 587;                   		// set the SMTP port for the GMAIL server
		$mail->Username   = "prueba.correoemsa@gmail.com";  	// GMAIL username
		$mail->Password   = "pruebaemsa";            // GMAIL password
		
		$mail->Subject    = "Informe Anomalias de Lecturas";
		$mail->MsgHTML($body);
		//$mail->AddAddress("grupodesarrollosypelc@gmail.com", "Julian Poveda");
		$mail->AddAddress("reportesulectura@gmail.com", "Lecturas");
		
		for ($j=0; $j < count($fotos); $j++) { 
			$mail->AddAttachment($fotos[$j]); 
		}		
		
		
		if(!$mail->Send()) {
			//echo "Mailer Error: " . $mail->ErrorInfo;
		}else{
			removeDirectory($row['cuenta']);
		}

	}

	function removeDirectory($path){
	    $path = rtrim( strval( $path ), '/' ) ;
	    
	    $d = dir( $path );
	    
	    if( ! $d )
	        return false;
	    
	    while ( false !== ($current = $d->read()) )
	    {
	        if( $current === '.' || $current === '..')
	            continue;
	        
	        $file = $d->path . '/' . $current;
	        
	        if( is_dir($file) )
	            removeDirectory($file);
	        
	        if( is_file($file) )
	            unlink($file);
	    }
	    
	    rmdir( $d->path );
	    $d->close();
	    return true;
	}
		
		
?>