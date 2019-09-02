<?php
	session_start();
	include_once(dirname(__FILE__)."/../fpdf17/fpdf.php");
	include_once(dirname(__FILE__)."/../Clases/ClassPostgresBD.php");
	

	$mes       	= $_GET['Mes'];
	$anno      	= $_GET['Anno'];
	$ciclo     	= $_GET['Ciclo'];
	$municipio 	= $_GET['Municipio'];
	$ruta      	= $_GET['Ruta'];
	$check     	= $_GET['Check'];
	$checkFecha = $_GET['CheckFecha'];
	$checkInsp  = $_GET['CheckInsp'];
	$day 	   	= $_GET['Day'];
	$month 	   	= $_GET['Month'];
	$year 	   	= $_GET['Year'];
	$id 		= $_GET['Id'];
 	$rutas 	   	= json_decode($_GET['Rutas'],true);

	$_myDB = new PostgresDB();	
	$_myDB ->OpenPostgres();	

	$fecha = getdate();
	
	if($fecha[mday]==28 && ($fecha[mon]==2)){
		$fecha[mon]=$fecha[mon]+1;
		$fecha[mday]=1;
	} 
	elseif($fecha[mday]==30 && ($fecha[mon]==4 || $fecha[mon]==6 || $fecha[mon]==9 || $fecha[mon]==11)){
		$fecha[mon]=$fecha[mon]+1;
		$fecha[mday]=1;
	}
	elseif($fecha[mday]==31 && ($fecha[mon]==1 || $fecha[mon]==3 || $fecha[mon]==5 || $fecha[mon]==7 || $fecha[mon]==8 || $fecha[mon]==10 || $fecha[mon]==12)){
		$fecha[mon]=$fecha[mon]+1;
		$fecha[mday]=1;
	} 
	elseif($fecha[mday]==31 && ($fecha[mon]==12)){
		$fecha[mon]=1;
		$fecha[mday]=1;
	} else{
		$fecha[mday]=$fecha[mday]+1;
	}

	for($j=0;$j<sizeof($rutas['Info_Ruta']);$j++){
		$arrayInfoRuta	.="'".$rutas['Info_Ruta'][$j]['ruta']."',";
		$total 	     = $total+$rutas['Info_Ruta'][$j]['total'];
	}

	$arrayInfoRuta	= "array[".substr($arrayInfoRuta,0,-1)."]";

	$query_codigo = $_myDB->PostgresSelectWhereOrder("maestro.log_programacion", "id_inspector", "id_maestro_ciclos=".$id, "id_inspector LIMIT 1");

	$row_codigo	= pg_fetch_assoc($query_codigo);
	$codigo		= $row_codigo['id_inspector'];


	$query = $_myDB->PostgresFunctionTable("maestro.programacion_by_agrupacion_pdf(".$arrayInfoRuta.",".$mes.",".$anno.",".$check.")");


	function round_up ($value, $places=0) {
	  if ($places < 0) { $places = 0; }
	  $mult = pow(10, $places);
	  return ceil($value * $mult) / $mult;
	}

	$pdf = new FPDF('P','mm',array(216,333));
	$pdf->SetMargins(0, 0, 0); 
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial','',8);
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetLineWidth(0.3);


if($check=='true'){

	$cont=0;						//Contador para el número de comprobante
	$aux_cont=1;
	$pag=$total/24;
	$pag=round_up($pag);			//Número de páginas generadas redondeadas hacia arriba

	
	for($i=1;$i<=$pag;$i++){		//Para correr imágenes x,y
		//Información de la planilla
		// j-->eje x      k--> eje y
			$aux_cont=$i;
		for ($k=7; $k<=320; $k=$k+41.62){
			for($j=20;$j<=210;$j=$j+72){	
				
				$row 	   		= pg_fetch_assoc($query, $aux_cont-1);
				$nombre	   		= substr($row['nombre'],0,45);
				$cuenta	   		= str_pad($row['cuenta'], 6, "0", STR_PAD_LEFT);
				$direccion 		= substr($row['direccion'],0,100);
				$medidor  		= $row['medidor'];
				$serie	   		= $row['serie'];
				$ruta_completa 	= $row['ruta_completa'];
				
				$pdf->SetFont('Arial','',8);
				$pdf->Text($j-4, $k-2, "SYPELC SAS"."                                        ".$aux_cont);
				$pdf->Text($j-4, $k+1, "Nit: 800.024.524-3");                                            
				$pdf->Text($j-4, $k+4, "COMPROBANTE DE LECTURA");
				$pdf->SetFont('Arial','',6);
				$pdf->Text($j-17, $k+8,  utf8_decode("Suscriptor:  ".$nombre));		//Concatenar la base de datos
				$pdf->Text($j-17, $k+11, utf8_decode("Dirección:   "));
				$pdf->SetFont('Arial','',5);				
				$pdf->Text($j-6,  $k+11, utf8_decode($direccion));
				$pdf->SetFont('Arial','',6);
				$pdf->Text($j-17, $k+14, utf8_decode("Medidor:     ".$medidor."-".$serie));
				$pdf->Text($j+17, $k+14, utf8_decode("Cuenta:  ".$cuenta));
				//Salto línea
				$pdf->Text($j-17, $k+19, utf8_decode("Lectura: "));
				$pdf->line($j-5, $k+19, $j+13, $k+19);									//Línea para escribir
				$pdf->Text($j+17, $k+19, utf8_decode("Observ: "));
				$pdf->line($j+25, $k+19, $j+48, $k+19);									//Línea para escribir
				$pdf->Text($j-17, $k+24, utf8_decode("Cod Lector: "));
				if($checkInsp=='true'){
					$pdf->Text($j, $k+24, $codigo);
					//$pdf->line($j-5, $k+24, $j+13, $k+24);								//Línea para escribir
				}else{
					$pdf->line($j-5, $k+24, $j+13, $k+24);								//Línea para escribir
				}
				$pdf->Text($j+17, $k+24, utf8_decode("Ruta:      ".$ruta_completa));
				$pdf->SetFont('Arial','',6);											//FECHA-->concatenar la fecha de la bd.

				if($checkFecha=='true'){
					$pdf->Text($j-12, $k+28, utf8_decode("Estimado usuario el día de hoy ".$day."/".$month."/".$year." visitamos su"));
				}else{
					$pdf->Text($j-12, $k+28, utf8_decode("Estimado usuario el día de hoy ____/____/____ visitamos su"));
				}
				//$pdf->Text($j-12, $k+28, utf8_decode("Estimado usuario el día de hoy ".$day."/".$month."/".$year." visitamos su"));
				$pdf->Text($j-16, $k+31, utf8_decode("residencia, si su contador no fue leido comuníquese al Tel 6680097"));
				$aux_cont=$aux_cont+$pag;
				$pdf->Image('EMSA.jpg',$j-18,$k-5,10,10);
				if($aux_cont>$total){
					break;
				}
			}
			if($aux_cont>$total){
				break;
			}

		}	$pdf->Text(107, 332, "".$i);	
			$pdf->SetLineWidth(0.1);
			$pdf->SetDrawColor(100,100,100);
			//Líneas horizontales
			$pdf->line(0.4, 41.62, 215.6, 41.62);
			$pdf->line(0.4, 83.25, 215.6, 83.25);
			$pdf->line(0.4, 124.87, 215.6, 124.87);
			$pdf->line(0.4, 166.5, 215.6, 166.5);
			$pdf->line(0.4, 208.125, 215.6, 208.125);
			$pdf->line(0.4, 249.75, 215.6, 249.75);
			$pdf->line(0.4, 291.37, 215.6, 291.37);
			//Líneas verticales			
			$pdf->line(72, 0.4, 72, 332);
			$pdf->line(144, 0.4, 144, 332);
			//Crear las nuevas páginas
			if($i<$pag){
				$pdf->AddPage();
			}
	}
}

if($check=='false'){
	$cont=0;						//Contador para el número de comprobante
	$aux_cont=1;
	$num_rows=pg_num_rows($query);
	$pag=$num_rows/24;
	$pag=round_up($pag);			//Número de páginas generadas redondeadas hacia arriba

	
	for($i=1;$i<=$pag;$i++){		//Para correr imágenes x,y
		//Información de la planilla
		// j-->eje x      k--> eje y
			$aux_cont=$i;
		for ($k=7; $k<=320; $k=$k+41.62){
			for($j=20;$j<=210;$j=$j+72){	
				
				$row 	   		= pg_fetch_assoc($query, $aux_cont-1);
				$nombre	   		= substr($row['nombre'],0,45);
				$cuenta	   		= str_pad($row['cuenta'], 6, "0", STR_PAD_LEFT);
				$direccion 		= substr($row['direccion'],0,100);
				$medidor  		= $row['medidor'];
				$serie	   		= $row['serie'];
				$ruta_completa 	= $row['ruta_completa'];
				$id 			= $row['id'];
				
				$pdf->SetFont('Arial','',8);
				$pdf->Text($j-4, $k-2, "SYPELC SAS"."                                        ".$aux_cont);
				$pdf->Text($j-4, $k+1, "Nit: 800.024.524-3");                                            
				$pdf->Text($j-4, $k+4, "COMPROBANTE DE LECTURA");
				$pdf->SetFont('Arial','',6);
				$pdf->Text($j-17, $k+8,  utf8_decode("Suscriptor:  ".$nombre));		//Concatenar la base de datos
				$pdf->Text($j-17, $k+11, utf8_decode("Dirección:   "));
				$pdf->SetFont('Arial','',5);				
				$pdf->Text($j-6,  $k+11, utf8_decode($direccion));
				$pdf->SetFont('Arial','',6);
				$pdf->Text($j-17, $k+14, utf8_decode("Medidor:     ".$medidor."-".$serie));
				$pdf->Text($j+17, $k+14, utf8_decode("Cuenta:  ".$cuenta));
				//Salto línea
				$pdf->Text($j-17, $k+19, utf8_decode("Lectura: "));
				$pdf->line($j-5, $k+19, $j+13, $k+19);									//Línea para escribir
				$pdf->Text($j+17, $k+19, utf8_decode("Observ: "));
				$pdf->line($j+25, $k+19, $j+48, $k+19);									//Línea para escribir
				$pdf->Text($j-17, $k+24, utf8_decode("Cod Lector: "));
				//$pdf->line($j-5, $k+24, $j+13, $k+24);									//Línea para escribir
				if($checkInsp=='true'){
					$pdf->Text($j, $k+24, $codigo);
					//$pdf->line($j-5, $k+24, $j+13, $k+24);								//Línea para escribir
				}else{
					$pdf->line($j-5, $k+24, $j+13, $k+24);								//Línea para escribir
				}
				$pdf->Text($j+17, $k+24, utf8_decode("Ruta:      ".$ruta_completa));
				$pdf->SetFont('Arial','',6);											//FECHA-->concatenar la fecha de la bd.
				
				if($checkFecha=='true'){
					$pdf->Text($j-12, $k+28, utf8_decode("Estimado usuario el día de hoy ".$day."/".$month."/".$year." visitamos su"));
				}else{
					$pdf->Text($j-12, $k+28, utf8_decode("Estimado usuario el día de hoy ____/____/____ visitamos su"));
				}
				//$pdf->Text($j-12, $k+28, utf8_decode("Estimado usuario el día de hoy ".$day."/".$month."/".$year." visitamos su"));
				$pdf->Text($j-16, $k+31, utf8_decode("residencia, si su contador no fue leido comuníquese al Tel 6680097"));
				$aux_cont=$aux_cont+$pag;
				$pdf->Image('EMSA.jpg',$j-18,$k-5,10,10);
				if($aux_cont>$num_rows){
					break;
				}
			}
			if($aux_cont>$num_rows){
				break;
			}

		}	$pdf->Text(107, 332, "".$i);	
			$pdf->SetLineWidth(0.1);
			$pdf->SetDrawColor(100,100,100);
			//Líneas horizontales
			$pdf->line(0.4, 41.62, 215.6, 41.62);
			$pdf->line(0.4, 83.25, 215.6, 83.25);
			$pdf->line(0.4, 124.87, 215.6, 124.87);
			$pdf->line(0.4, 166.5, 215.6, 166.5);
			$pdf->line(0.4, 208.125, 215.6, 208.125);
			$pdf->line(0.4, 249.75, 215.6, 249.75);
			$pdf->line(0.4, 291.37, 215.6, 291.37);
			//Líneas verticales
			$pdf->line(72, 0.4, 72, 332);
			$pdf->line(144, 0.4, 144, 332);
			//Crear las nuevas páginas
			if($i<$pag){
				$pdf->AddPage();
			}
	}

}

	$pdf->Output();
?>






<?php 
	#Powered by...
	#Landneyker...
?>