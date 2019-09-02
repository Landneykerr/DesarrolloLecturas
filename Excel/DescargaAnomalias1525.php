<?php
	session_start();
	include_once(dirname(__FILE__)."/../Clases/ClassPostgresBD.php");
	include_once(dirname(__FILE__)."/../Excel/PHPExcel.php");


	$_myDataBase =  new PostgresDB();
	$mes    	= $_GET['Mes'];
	$anno       = $_GET['Anno'];
	$ciclo  	= $_GET['Ciclo'];

	$nombreArchivo = "Anomalias1525".$ciclo."_".$mes."_".$anno.".xlsx";
	

	ini_set("memory_limit", "512M");
	$prueba = new PHPExcel(); 

	
	$fila = 1;
	$campos = array("CUENTA","ANOMALIA","CRITICA","FECHA TOMA","MENSAJE","INSPECTOR");	
	for($i=0; $i<count($campos); $i++){
		$prueba->setActiveSheetIndex(0)->setCellValueByColumnAndRow($i,1,$campos[$i]); 
	}  
	$fila++;

	$_myDataBase->OpenPostgres();	
	$_query = $_myDataBase->PostgresFunctionCamposTable("cuenta,anomalia,critica,fecha_toma,mensaje,inspector",
														"reportes.reporte_anomalias(".$ciclo.",".$mes.",".$anno.")"); 

	while($RtaRow = pg_fetch_array($_query)){
		for($i=0; $i<count($RtaRow); $i++){
			$prueba->setActiveSheetIndex(0)->setCellValueExplicitByColumnAndRow($i, $fila, $RtaRow[$i], PHPExcel_Cell_DataType::TYPE_STRING); 
		}
		$fila++;
	}
	$_myDataBase->ClosePostgres();

	$prueba->getActiveSheet()->setTitle("Consolidado"); 
	$objWriter = PHPExcel_IOFactory::createWriter($prueba, 'Excel2007'); 
	$objWriter->save($nombreArchivo);   

	header( "Content-Type: application/force-download"); 
	header( "Content-Length: ".filesize($nombreArchivo)); 
	header( "Content-Disposition: attachment; filename=".basename($nombreArchivo)); 
	readfile($nombreArchivo);	
	unlink($nombreArchivo);
?>