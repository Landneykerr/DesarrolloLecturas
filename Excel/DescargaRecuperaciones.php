<?php
	session_start();
	include_once(dirname(__FILE__)."/../Clases/ClassPostgresBD.php");
	include_once(dirname(__FILE__)."/../Excel/PHPExcel.php");


	$_myDataBase =  new PostgresDB('190.93.133.87','5432','test_lecturas','t3st3r','lecturas');
	$mes    	= $_GET['Mes'];
	$anno       = $_GET['Anno'];
	$ciclo  	= $_GET['Ciclo'];
	$anomalias	= $_GET['Campos'];
	$nombreArchivo = "Recuperaciones_".$ciclo."_".$mes."_".$anno.".xlsx";
	

	ini_set("memory_limit", "512M");
	$prueba = new PHPExcel(); 

	$campos = array("CODIGO","MEDIDA","RUTA","CLIENTE","DIRECCION","MEDIDOR","SERIE","DIGITOS","LECTURA","ANOMALIA","INSPECTOR","MENSAJE","LECTURA ANTERIOR","ANOMALIA ANTERIOR","PROMEDIO","LECTURA_EMSA","TIPO_USO","LECT","OBS","MSJ","RESPONSABLE","REVISO","FECHA","DF","CORRECIONES","REVISO");
	
	$prueba = new PHPExcel(); 
	for($i=0; $i<count($campos); $i++){
		$prueba->setActiveSheetIndex(0)->setCellValueByColumnAndRow($i,1,$campos[$i]); 
	}   

	$_myDataBase->OpenPostgres();	
	$_query = $_myDataBase->PostgresFunctionCamposTable("cuenta,medida,ruta,cliente,direccion,medidor,serie,digitos,lectura,anomalia,inspector,observacion,lectura_anterior,anomalia_anterior,promedio,lectura_emsa,tipo_uso","toma.reporte_recuperaciones(".$mes.",".$anno.",".$ciclo.") WHERE anomalia IN (".$anomalias.")"); 

	$fila = 2;
	while($RtaRow = pg_fetch_array($_query)){
		for($i=0; $i<count($RtaRow); $i++){
			$prueba->setActiveSheetIndex(0)->setCellValueExplicitByColumnAndRow($i, $fila, utf8_decode($RtaRow[$i]), PHPExcel_Cell_DataType::TYPE_STRING); 
		}
		$fila++;
	}
	$_myDataBase->ClosePostgres();

	$prueba->getActiveSheet()->setTitle("Critica"); 
	$objWriter = PHPExcel_IOFactory::createWriter($prueba, 'Excel2007'); 
	$objWriter->save($nombreArchivo);   

	//echo "cuenta,serie_medidor,id_lector,lect_anterior,lect_actual,id_anomalia,mensaje,nombre,direccion,energia,factor,promedio,str_precritica,intentos","toma.Toma_Critica(".$mes.",".$anno.",".$ciclo.") WHERE str_precritica IN (".$critica.")";

	header( "Content-Type: application/force-download"); 
	header( "Content-Length: ".filesize($nombreArchivo)); 
	header( "Content-Disposition: attachment; filename=".basename($nombreArchivo)); 
	readfile($nombreArchivo);	
	unlink($nombreArchivo);
?>