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
         												"cuenta,nombre_foto,fecha_toma,foto", 
         												"id_maestro_emsa = ".$idmaestro, 
         												"nombre_foto");

        while($row   = pg_fetch_assoc($query)){

        	$cuenta = $row['cuenta'];

	        if(!file_exists('uploads/'.$row['cuenta'])){
	            mkdir('uploads/'.$row['cuenta']);
	        }

	        $rutaT = 'uploads/'.$row['cuenta'];    
	        $im = base64_decode($row['foto']);
	        file_put_contents($rutaT."/".$row['nombre_foto'], $im);

	        $fotos[$i] = $rutaT."/".$row['nombre_foto'];
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
		//$mail->AddAddress("jaime.gutierrez@sypelcltda.com", "Julian Poveda");
		$mail->AddAddress("reportesulectura@gmail.com", "Yury");
		
		for ($j=0; $j < count($fotos); $j++) { 
			$mail->AddAttachment($fotos[$j]); 
		}		
		
		
		if(!$mail->Send()) {
			//echo "Mailer Error: " . $mail->ErrorInfo;
		}else{
			//echo "Message sent!";
		}

	}
		
		
?>