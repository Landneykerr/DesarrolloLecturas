<?php
	require_once('/media/sda1/www/ControlEnergia/BaseDatos/ConsultasBD.php');
	require_once('/media/sda1/www/ControlEnergia/PHPMailer/class.phpmailer.php');
	require_once('/media/sda1/www/ControlEnergia/PHPMailer/class.smtp.php');
	require_once ('/media/sda1/www/ControlEnergia/jpgraph/jpgraph.php');
	require_once ('/media/sda1/www/ControlEnergia/jpgraph/jpgraph_pie.php');
	require_once ('/media/sda1/www/ControlEnergia/jpgraph/jpgraph_pie3d.php');

		//Creacion del archivo csv que contiene el informe
		$fecha = date('d-m-Y');
		//$fecha = '12-02-2014';
		$csv_end = "\n";  
		$csv_sep = ";";
		$csv_file 	= 	'Informe Verificacion '.$fecha.'.csv';
		$graph_file = 	'Grafica Informe Verificacion '.$fecha.'.png';
		$csv 		=	"PDA;TECNICO;REGISTRADAS;IMPRESAS;NO IMPRESAS;VERIFICADAS\n";

		$Conexion = DoConection();
        $Query = pg_query($Conexion,"SELECT * FROM ordenes_trabajo_historico.InformeVerificacion('".$fecha."')");
        while($RtaQuery = pg_fetch_assoc($Query)){
        	if($RtaQuery['tecnico']=='RESUMEN TOTALES'){
        		$abiertas 	= $RtaQuery['abiertas'];
        		$impresas 	= $RtaQuery['impresas'];
        		$no_impresas= $RtaQuery['no_impresas'];
        		$verificadas = $RtaQuery['verificadas'];
        	}
        	$csv.=$RtaQuery['pda'].$csv_sep.$RtaQuery['tecnico'].$csv_sep.$RtaQuery['abiertas'].$csv_sep.$RtaQuery['impresas'].$csv_sep.$RtaQuery['no_impresas'].$csv_sep.$RtaQuery['verificadas'].$csv_end;
        }
        pg_close($Conexion);   

        if (!$handle = fopen($csv_file, "w")) {   
			echo "No se puede generar el archivo.";  
			exit;  
		}  
		if (fwrite($handle, utf8_decode($csv)) === FALSE){   
			echo "No es posible escribir el archivo";  
			exit;  
		}  
		fclose($handle);  


		 //Creacion del archivo que contiene la grafica del informe
        $data = array($abiertas-$impresas,$impresas-$verificadas,$verificadas);		 
		$graph = new PieGraph(500,400);
		$graph->SetShadow();		 
		$graph->title->Set("Resumen Informe Verificacion ".$fecha);		 
		$p1 = new PiePlot3D($data);
		$p1->SetSize(0.4);
		$p1->SetCenter(0.45);
		$p1 ->SetLegends(array("No Transmitidas","Por Verificar","Verificadas"));
		$graph->Add($p1);
		$graph->Stroke(_IMG_HANDLER);
		$graph->img->Stream($graph_file);


		//Inicio de creacion de correo electronico automatico
		$mail             = new PHPMailer();
		$body             = "Buen dia. \n Se adjunta archivo .csv y grafica con informe de verificacion de actas a la fecha ".$fecha.", este informe es realizado de forma automatica por el sistema y se basa exclusivamente en la informacion recibida en linea por las cuadrillas, favor no responder este correo.";
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
		
		$mail->Subject    = "Informe De Verificacion ".$fecha;
		$mail->MsgHTML($body);
		$mail->AddAddress("julianpovedadaza@gmail.com", "Julian Poveda");
		$mail->AddAddress("fernanda.otalora@sypelcltda.com", "Fernanda Otalora");
		$mail->AddAddress("linda.guzman@sypelcltda.com", "Linda Guzman");
		$mail->AddAddress("mayori.lozada@sypelcltda.com", "Maryori Lozada");
		$mail->AddAddress("david.botello@sypelcltda.com", "David Botello");

		$mail->AddAttachment($csv_file);      // attachment
		$mail->AddAttachment('Grafica Informe Verificacion '.$fecha.'.png'); 
		
		if(!$mail->Send()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
		}else{
			echo "Message sent!";
		}
		unlink($csv_file);
		unlink($graph_file);
?>