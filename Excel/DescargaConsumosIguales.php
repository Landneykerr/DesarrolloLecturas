<?php
	session_start();
	include_once(dirname(__FILE__)."/../Clases/ClassPostgresBD.php");
	include_once(dirname(__FILE__)."/../Excel/PHPExcel.php");


	$_myDataBase =  new PostgresDB();
	$mes    	= $_GET['Mes'];
	$anno       = $_GET['Anno'];
	$ciclo  	= $_GET['Ciclo'];

	$nombreArchivo = "ConsumosIguales".$ciclo."_".$mes."_".$anno.".xlsx";
	

	ini_set("memory_limit", "812M");
	$prueba = new PHPExcel(); 

	
	$fila = 1;
	$campos = array("CUENTA","MEDIDA","NOMBRE","MEDIDOR","PERIODO1","ANOMALIA1","CRITICA1","FECHA TOMA1","MENSAJE1","PERIODO2","ANOMALIA2","CRITICA2","FECHA TOMA2","MENSAJE2","PERIODO3","ANOMALIA3","CRITICA3","FECHA TOMA3","MENSAJE3","CONSUMO1","CONSUMO2");	
	for($i=0; $i<count($campos); $i++){
		$prueba->setActiveSheetIndex(0)->setCellValueByColumnAndRow($i,1,$campos[$i]); 
	}  
	$fila++;

	$_myDataBase->OpenPostgres();	
	$_query = $_myDataBase->PostgresFunctionCamposTableWhere("cuenta,medida,nombre,medidor,periodo1,anomalia1,critica1,fecha_toma1,mensaje1,periodo2,anomalia2,critica2,fecha_toma2,mensaje2,periodo3,anomalia3,critica3,fecha_toma3,mensaje3,consumo1,consumo2",
														"reportes.reporte_consumos_iguales(".$ciclo.",".$mes.",".$anno.")"," lectura1 <> -1"); 

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