<?php
	require_once('/media/sda1/www/ControlEnergia/BaseDatos/OracleBD.php');
	require_once('/media/sda1/www/ControlEnergia/BaseDatos/ConsultasBD.php');
	require_once('/media/sda1/www/ControlEnergia/PHPMailer/class.phpmailer.php');
	require_once('/media/sda1/www/ControlEnergia/PHPMailer/class.smtp.php');


	$InfResultado = "ORDEN;PROPIETARIO;DIRECCION;TIPO;MARCA;SERIE;TIPO;MARCA;SERIE;OBSERVACION\n";
	$ConsultaOracle = new OraclePHP("SGD","sistemasce","192.168.0.50","PERDIDAS");

	$Query = SelectDistinctOrder(	"pda,orden,cuenta,acta",
									"ordenes_trabajo_historico.ordenes_trabajo",
									"fecha='06-02-2014' AND estado_analista=0",
									"pda,orden,cuenta,acta");

	$ConsultaOracle->Conectar();
	
	while($RtaQuery = pg_fetch_assoc($Query)){

		$ResultadoAnalisis = $ConsultaOracle->SelectDistinctOrder(	"ID_ORDEN, PROPIETARIO, DIRECCION",
																	"SGD_ORDENES_TRABAJO_PDA",
																	"ID_ORDEN = ".$RtaQuery['orden'],
																	"ID_ORDEN, PROPIETARIO, DIRECCION");
		for($i=0;$i<sizeof($ResultadoAnalisis);$i++){
			$InfResultado .= $RtaQuery['orden'].";".$ResultadoAnalisis[$i]['PROPIETARIO'].";".$ResultadoAnalisis[$i]['DIRECCION'].";";	
		}


		$ResultadoAnalisis = $ConsultaOracle->SelectDistinctOrder(	"TIPO, MARCA, SERIE",
																	"SGD_MVTO_CONTADORES_PDA",
																	"ID_ORDEN = ".$RtaQuery['orden']." AND TIPO IN ('E','R')",
																	"TIPO");

		for($i=0;$i<sizeof($ResultadoAnalisis);$i++){
			$InfResultado .= $ResultadoAnalisis[$i]['TIPO'].";".$ResultadoAnalisis[$i]['MARCA'].";".$ResultadoAnalisis[$i]['SERIE'].";";	
		}

		$ResultadoAnalisis = $ConsultaOracle->SelectDistinctOrder(	"TIPO,MARCA,SERIE",
																	"SGD_MVTO_CONTADORES_PDA",
																	"ID_ORDEN= ".$RtaQuery['orden']." AND TIPO IN ('D','P')",
																	"ID_ORDEN");

		for($i=0;$i<sizeof($ResultadoAnalisis);$i++){
			$InfResultado .= $ResultadoAnalisis[$i]['TIPO'].";".$ResultadoAnalisis[$i]['MARCA'].";".$ResultadoAnalisis[$i]['SERIE'].";";	
		}

		$ResultadoAnalisis = $ConsultaOracle->SelectDistinctOrder(	"ID_INCONSISTENCIA,VALOR",
																	"SGD_INCONSISTENCIA_PDA",
																	"ID_ORDEN = ".$RtaQuery['orden']." AND (COD_INCONSISTENCIA IN ('GEN00','GEN01','GEN02','GEN03'))",
																	"ID_INCONSISTENCIA");
		for($i=0;$i<sizeof($ResultadoAnalisis);$i++){
			$InfResultado .= substr($ResultadoAnalisis[$i]['VALOR'],1)." ";	
		}

		$InfResultado .= "\n";		
	}

	$ConsultaOracle->Desconectar();
	




	$csv_file 	= 	'Informe De Posteriores '.date('H:i:s d-m-Y').'.csv';
	
    if (!$handle = fopen($csv_file, "w")) {   
		echo "No se puede generar el archivo.";  
		exit;  
	}  
	if (fwrite($handle, utf8_decode($InfResultado)) === FALSE){   
		echo "No es posible escribir el archivo";  
		exit;  
	}  
	fclose($handle);  


	//Inicio de creacion de correo electronico automatico
	$mail             = new PHPMailer();
	$body             = "Buen dia. \n Se adjunta archivo .csv ordenes de trabajo sin revisar para revision de posteriores, este informe es realizado de forma automatica por el sistema y se basa exclusivamente en la informacion recibida en linea por las cuadrillas, favor no responder este correo.";
	//$body             = eregi_replace("[\]",'',$body);
	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->Host       = "Sypelc.Villavicencio.com"; // SMTP server
	$mail->SMTPDebug  = 2;                     		// enables SMTP debug information (for testing)
	$mail->SMTPAuth   = true;                  		// enable SMTP authentication
	$mail->SMTPSecure = "tls";                 		// sets the prefix to the servier
	$mail->Host       = "smtp.gmail.com";      		// sets GMAIL as the SMTP server
	$mail->Port       = 587;                   		// set the SMTP port for the GMAIL server
	$mail->Username   = "CTI.sypelc@gmail.com";  	// GMAIL username
	$mail->Password   = "Sypelcs0p0rt3";            // GMAIL password
		
	$mail->Subject    = "Informe Posteriores ".date('H:i:s d-m-Y');
	$mail->MsgHTML($body);
	$mail->AddAddress("julianpovedadaza@gmail.com", "Julian Poveda");
	//$mail->AddAddress("fernanda.otalora@sypelcltda.com", "Yury");
	//$mail->AddAddress("linda.guzman@sypelcltda.com", "Linda Guzman");
	//$mail->AddAddress("mayori.lozada@sypelcltda.com", "Maryori Lozada");
	//$mail->AddAddress("david.botello@sypelcltda.com", "David Botello");

	$mail->AddAttachment($csv_file);      // attachment
	//$mail->AddAttachment('Grafica Informe No Conformidades '.date('d-m-Y').'.png'); 
		
	if(!$mail->Send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	}else{
		echo "Message sent!";
	}
	unlink($csv_file);
?>