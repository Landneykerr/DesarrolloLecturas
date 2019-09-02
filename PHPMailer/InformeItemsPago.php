<?php
	require_once('/media/sda1/www/ControlEnergia/BaseDatos/ConsultasBD.php');
	require_once('/media/sda1/www/ControlEnergia/PHPMailer/class.phpmailer.php');
	require_once('/media/sda1/www/ControlEnergia/PHPMailer/class.smtp.php');
	require_once ('/media/sda1/www/ControlEnergia/jpgraph/jpgraph.php');
	require_once ('/media/sda1/www/ControlEnergia/jpgraph/jpgraph_pie.php');
	require_once ('/media/sda1/www/ControlEnergia/jpgraph/jpgraph_pie3d.php');

		//Creacion del archivo csv que contiene el informe
		$fecha = date('d-m-Y');
		$csv_end = "\n";  
		$csv_sep = ";";
		$csv_file 	= 	'Informe Items De Pago '.$fecha.'.csv';
		//$graph_file = 	'Grafica Informe No Conformidades '.date('d-m-Y').'.png';
		$csv 		=	"PDA;TECNICO;ORDEN;MUNICIPIO;UBICACION;ITEM1;ITEM2;ITEM3;ITEM4;ITEM5;ITEM6\n";

		//$Conexion = DoConection();
        $Query = SelectDistinctOrder(	"pda,tecnico,orden,municipio,ubicacion,items_pago_1,items_pago_2,items_pago_3,items_pago_4,items_pago_5,items_pago_6",
        								"ordenes_trabajo_historico.ordenes_trabajo",
        								"items_pago_1 <> 0 AND fecha = '".$fecha."'",
        								"pda,tecnico,orden");

        //pg_query($Conexion,"SELECT * FROM ordenes_trabajo_historico.InformeNoConformidades('".date('d-m-Y')."')");
        //$Query = pg_query($Conexion,"SELECT * FROM ordenes_trabajo_historico.InformeNoConformidades('04-02-2014')");
        
        while($RtaQuery = pg_fetch_assoc($Query)){
        	$csv.=$RtaQuery['pda'].$csv_sep.$RtaQuery['tecnico'].$csv_sep.$RtaQuery['orden'].$csv_sep.$RtaQuery['municipio'].$csv_sep.$RtaQuery['ubicacion'].$csv_sep.$RtaQuery['items_pago_1'].$csv_sep.$RtaQuery['items_pago_2'].$csv_sep.$RtaQuery['items_pago_3'].$csv_sep.$RtaQuery['items_pago_4'].$csv_sep.$RtaQuery['items_pago_5'].$csv_sep.$RtaQuery['items_pago_6'].$csv_end;
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
        /*$data = array($sin_analizar,$conformes,$no_conformes);		 
		$graph = new PieGraph(500,400);
		$graph->SetShadow();		 
		$graph->title->Set("Resumen Informe De No Conformidades ".date('H:i:s d-m-Y'));		 
		$p1 = new PiePlot3D($data);
		$p1->SetSize(0.4);
		$p1->SetCenter(0.45);
		$p1 ->SetLegends(array("Sin Analizar","Conformes","No Conformes"));
		$graph->Add($p1);
		$graph->Stroke(_IMG_HANDLER);
		$graph->img->Stream($graph_file);*/


		//Inicio de creacion de correo electronico automatico
		$mail             = new PHPMailer();
		$body             = "Buen dia. \n Se adjunta archivo .csv con informe de items de pago a la fecha ".$fecha.", este informe es realizado de forma automatica por el sistema y se basa exclusivamente en la informacion recibida en linea por las cuadrillas, favor no responder este correo.";
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
		
		$mail->Subject    = "Informe De Items de Pago ".$fecha;
		$mail->MsgHTML($body);
		$mail->AddAddress("julianpovedadaza@gmail.com", "Julian Poveda");
		$mail->AddAddress("fernanda.otalora@sypelcltda.com", "Yury");
		/*$mail->AddAddress("linda.guzman@sypelcltda.com", "Linda Guzman");
		$mail->AddAddress("mayori.lozada@sypelcltda.com", "Maryori Lozada");
		$mail->AddAddress("david.botello@sypelcltda.com", "David Botello");*/

		$mail->AddAttachment($csv_file);      // attachment
		//$mail->AddAttachment('Grafica Informe No Conformidades '.date('d-m-Y').'.png'); 
		
		if(!$mail->Send()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
		}else{
			echo "Message sent!";
		}
		unlink($csv_file);
		//unlink($graph_file);
?>