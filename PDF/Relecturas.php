<?php
	session_start();
	include_once(dirname(__FILE__)."/../fpdf17/fpdf.php");
	include_once(dirname(__FILE__)."/../Clases/ClassPostgresBD.php");
	

	$mes       = $_GET['Mes'];
	$anno      = $_GET['Anno'];
	$ciclo     = $_GET['Ciclo'];
	$municipio = $_GET['Municipio'];
	$ruta      = $_GET['Ruta'];
	$check     = $_GET['Check'];

	$PDF_connect = new PostgresDB();	
	$PDF_connect->OpenPostgres();	

	class PDF extends FPDF{	
		function Header(){	
			$this->Image('../imagenes/logo_sypelc.png',40,10,35);
			$this->SetFont('Arial','B',12);
			$this->Cell(0,0,'SYPELC SAS',0,0,'C');		
			$this->Ln(5);
			$this->Cell(0,0,'FORMATO LECTURAS ZONA ORDEN PUBLICO',0,0,'C');
			$this->Ln(10);
		}
	}

	$dato       = $PDF_connect->PostgresSelectWhereOrder("parametros.municipios",
														"nombre_municipio",
														"id_municipio=".$municipio."",
														"nombre_municipio");
	$nombre = pg_fetch_assoc($dato);
	
	//$TamañoHoja = array(445.9,490.4);				//configuracion del tamaño de la hoja
	$pdf=new PDF('L','mm','Legal');		//configuracion de orientacion y unidades de medida
	
	
	$pdf->AddPage();
	$pdf->SetMargins(5,15,5);					//configuracion de margenes
	$pdf->SetFont('Arial','B',9);					//configuracion del tamaño de la letra
	$pdf->Cell(0,0,'RUTA: '.$ruta.' CICLO: '.$ciclo.' MUNICIPIO: '.$nombre['nombre_municipio'].'',0,0,'C');
	$pdf->Ln(5);

	$pdf->Ln(5);		
	$pdf->Cell(5,5,'ID',1,0,'C');	
	$pdf->Cell(20,5,'CUENTA',1,0,'C');
	$pdf->Cell(20,5,'RUTA',1,0,'C');
	$pdf->Cell(80,5,'CLIENTE',1,0,'C');
	$pdf->Cell(80,5,'DIRECCION',1,0,'C');
	$pdf->Cell(10,5,'DIG',1,0,'C');
	$pdf->Cell(10,5,'MED',1,0,'C');	
	$pdf->Cell(20,5,'SERIE',1,0,'C');
	$pdf->Cell(15,5,'LECT',1,0,'C');
	$pdf->Cell(15,5,'ANOM',1,0,'C');
	$pdf->Cell(50,5,'OBSERVACION',1,0,'C');
	$pdf->Ln();
	$pdf->SetFont('Arial','',7);

	/************************ Consulta de la informacion detallado de los usuarios **************************/	
	$query = $PDF_connect->PostgresFunctionCamposTable("cuenta,medidor,serie,digitos,nombre,direccion,factor,tipo_uso,lectura_1,promedio_1,tipo_energia_1,anomalia_1,id_ciclo_real",
													"maestro.programacion_by_agrupacion_pdf(".$ciclo.",".$municipio.",'".$ruta."',".$mes.",".$anno.",".$check.") ");
	$item = 1;
	while($rtaQuery = pg_fetch_assoc($query)){
		if($rtaQuery['lectura_1']!="0"){
			$valor = $rtaQuery['lectura_1'];		//Valor de la lectura
		}else{
			$valor="";
		}		
		$rutaCompleta = $PDF_connect->PostgresSelectWhereOrder("maestro.log_ciclo_muni_ruta_cuentas",
																"ruta_completa",
																"cuenta=".$rtaQuery['cuenta']." AND mes=".$mes."AND anno=".$anno." AND id_ciclo=".$ciclo."AND ruta='".$ruta."'",
																"cuenta");
		$rtaRuta = pg_fetch_assoc($rutaCompleta);
		$pdf->Cell(5,5,$item,1,0,'C');	
		$pdf->Cell(20,5,$rtaQuery['cuenta'],1,0,'C');
		$pdf->Cell(20,5,$rtaRuta['ruta_completa'],1,0,'C');
		$pdf->Cell(80,5,$rtaQuery['nombre'],1,0,'C');		
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(80,5,$rtaQuery['direccion'],1,0,'C');
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(10,5,$rtaQuery['digitos'],1,0,'C');
		$pdf->Cell(10,5,$rtaQuery['medidor'],1,0,'C');
		$pdf->Cell(20,5,$rtaQuery['serie'],1,0,'C');
		$pdf->Cell(15,5,'',1,0,'C');
		$pdf->Cell(15,5,'',1,0,'C');
		$pdf->Cell(50,5,'',1,0,'C');
		$pdf->Ln();
		$item++;
	}	

	$PDF_connect->ClosePostgres();
	$pdf->Output('Reporte.pdf', 'I');
?>
