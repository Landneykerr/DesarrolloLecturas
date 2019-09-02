<?php
	session_start();
	include_once(dirname(__FILE__)."/../Clases/ClassPostgresBD.php");
	include_once(dirname(__FILE__)."/../Excel/PHPExcel.php");


	$_myDataBase =  new PostgresDB('190.93.133.87','5432','test_lecturas','t3st3r','lecturas');
	$_mes    	 = $_GET['Mes'];
	$_anno       = $_GET['Anno'];
	$_ciclo      = $_GET['Ciclo'];
	$_municipio  = $_GET['Municipio'];
	$_ruta       = $_GET['Ruta'];

	ini_set("memory_limit", "512M");
	$prueba = new PHPExcel(); 
	
	$campos = array("CUENTA","MEDIDOR","LECTURA","ANOMALIA","MENSAJE","CRITICA","FECHA");
		
	$prueba = new PHPExcel(); 
	for($i=0; $i<count($campos); $i++){
		$prueba->setActiveSheetIndex(0)->setCellValueByColumnAndRow($i,1,$campos[$i]); 
	}    

	$_myDataBase->OpenPostgres();	
			
    $_query = $_myDataBase->PostgresFunctionCamposTable("cuenta,medidor,str_lectura,descripcion_anomalia,mensaje,descripcion_critica,fecha_toma","toma.toma_lectura(".$_mes.",".$_anno.",".$_ciclo.",".$_municipio.",'".$_ruta."')");	

	$fila = 2;
	while($RtaRow = pg_fetch_array($_query)){
		for($i=0; $i<count($RtaRow); $i++){
			$prueba->setActiveSheetIndex(0)->setCellValueExplicitByColumnAndRow($i, $fila, $RtaRow[$i], PHPExcel_Cell_DataType::TYPE_STRING); 
		}
		$fila++;
	}
	$_myDataBase->ClosePostgres();

	$prueba->getActiveSheet()->setTitle("Cronologico"); 
	$objWriter = PHPExcel_IOFactory::createWriter($prueba, 'Excel2007'); 
	$objWriter->save("Cronologico_".$_ciclo."_".$_municipio."_".$_ruta.".xlsx");   

	header( "Content-Type: application/force-download"); 
	header( "Content-Length: ".filesize("Cronologico_".$_ciclo."_".$_municipio."_".$_ruta.".xlsx")); 
	header( "Content-Disposition: attachment; filename=".basename("Cronologico_".$_ciclo."_".$_municipio."_".$_ruta.".xlsx")); 
	readfile("Cronologico_".$_ciclo."_".$_municipio."_".$_ruta.".xlsx");	
	unlink("Cronologico_".$_ciclo."_".$_municipio."_".$_ruta.".xlsx");
?>