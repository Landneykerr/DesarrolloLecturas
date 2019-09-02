<?php
	session_start();
	include_once(dirname(__FILE__)."/../Clases/ClassPostgresBD.php");
	include_once(dirname(__FILE__)."/../Excel/PHPExcel.php");


	$_myDataBase =  new PostgresDB('190.93.133.87','5432','test_lecturas','t3st3r','lecturas');
	$mes    	= $_GET['Mes'];
	$anno       = $_GET['Anno'];
	$cicloRuta  = explode('_',$_GET['Ciclo']);
	$tipo 		= $_GET['Tipo'];

	ini_set("memory_limit", "512M");
	$prueba = new PHPExcel(); 

	if($tipo == "Pendientes"){
		$campos = array("CUENTA","MEDIDOR","NOMBRE","DIRECCION");	
	}else if($tipo == 'Tomadas'){
		$campos = array("CUENTA","MEDIDOR","LECTURA","ANOMALIA","MENSAJE","CRITICA","NOMBRE","DIRECCION");	
	}
	
	
	$prueba = new PHPExcel(); 
	for($i=0; $i<count($campos); $i++){
		$prueba->setActiveSheetIndex(0)->setCellValueByColumnAndRow($i,1,$campos[$i]); 
	}    

	$_myDataBase->OpenPostgres();	
	if($tipo == 'Pendientes'){
		$_query =$_myDataBase->PostgresSelectWhereOrder("maestro.emsa", 
														"cuenta,medidor||' '||serie as medidor,nombre, direccion", 
														"mes=".$mes." AND anno=".$anno." AND id_ciclo=".$cicloRuta[0]." AND ruta='".$cicloRuta[1]."' AND estado_lectura='P'", 
														"cuenta");
	}else if($tipo == 'Tomadas'){
		$_query = $_myDataBase->PostgresFunctionCamposTable("cuenta,medidor,lectura,descripcion_anomalia,mensaje,descripcion_critica,nombre,direccion","toma.toma_lectura(".$mes.",".$anno.",'".$_GET['Ciclo']."')"); 
	}

	$fila = 2;
	while($RtaRow = pg_fetch_array($_query)){
		for($i=0; $i<count($RtaRow); $i++){
			$prueba->setActiveSheetIndex(0)->setCellValueExplicitByColumnAndRow($i, $fila, $RtaRow[$i], PHPExcel_Cell_DataType::TYPE_STRING); 
		}
		$fila++;
	}
	$_myDataBase->ClosePostgres();

	$prueba->getActiveSheet()->setTitle("Cuentas"); 
	$objWriter = PHPExcel_IOFactory::createWriter($prueba, 'Excel2007'); 
	$objWriter->save("Estado_".$tipo."_".$cicloRuta[0]."_".$cicloRuta[1].".xlsx");   

	header( "Content-Type: application/force-download"); 
	header( "Content-Length: ".filesize("Estado_".$tipo."_".$cicloRuta[0]."_".$cicloRuta[1].".xlsx")); 
	header( "Content-Disposition: attachment; filename=".basename("Estado_".$tipo."_".$cicloRuta[0]."_".$cicloRuta[1].".xlsx")); 
	readfile("Estado_".$tipo."_".$cicloRuta[0]."_".$cicloRuta[1].".xlsx");	
	unlink("Estado_".$tipo."_".$cicloRuta[0]."_".$cicloRuta[1].".xlsx");
?>