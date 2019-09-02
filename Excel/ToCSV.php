<?php 
	session_start();
    include_once(dirname(__FILE__)."/../Clases/ClassPostgresBD.php");
    include_once(dirname(__FILE__)."/../Excel/PHPExcel.php");

    //$consulta_bd = new PostgresDB('190.93.133.127','5432','postgres','4y4n4m1_r3y','eaav_desviaciones');
    ini_set("memory_limit", "512M");
    $consulta_bd = new PostgresDB('localhost','5432','postgres','4y4n4m1_r3y','eaav_desviaciones'); 
    $fila=1;

    $prueba = new PHPExcel(); 
    $prueba->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2,$fila,"Tipo de Consulta:");
    $prueba->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3,$fila,$_GET['tabla']);                                    $fila++;
    $prueba->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2,$fila,"Periodo de Consulta:");    
    $prueba->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3,$fila,$_GET['mes']."/".$_GET['ano']);                     $fila++;

    
    $fila++;
    //se coloca los nombres de las columnas
    $NombreColumnas = explode(",",$_GET['campos']);
    for($i=0;$i<sizeof($NombreColumnas);$i++){
        $prueba->setActiveSheetIndex(0)->setCellValueByColumnAndRow($i,$fila,$NombreColumnas[$i]); 
    }
   
    //se calcula el numero maximo de dias que tiene el mes seleccionado
    $diasmes = date("t", mktime( 0, 0, 0, $_GET['mes'], $fila, $_GET['ano'])); 
    if($_GET['tabla']=="Desviaciones"){
        $tabla= "desviaciones_end";
        $CampoCondicion = "fecha";
    }else if ($_GET['tabla']=="Notificaciones") {
        $tabla= "notificaciones";
        $CampoCondicion = "fechavisita";
    }
    
    $consulta_bd->OpenPostgres();
    $Row = $consulta_bd->PostgresSelectWhereGroupOrder( $tabla, 
                                                        $_GET['campos'], 
                                                        $CampoCondicion.">='01-".$_GET['mes']."-".$_GET['ano']."' and ".$CampoCondicion."<='".$diasmes."-".$_GET['mes']."-".$_GET['ano']."'",
                                                        $_GET['campos'],
                                                        $_GET['campos']);
    $fila++;
    while($RtaRow = pg_fetch_assoc($Row)){
        for($i=0;$i<sizeof($NombreColumnas);$i++){
            $prueba->setActiveSheetIndex(0)->setCellValueByColumnAndRow($i,$fila,$RtaRow[$NombreColumnas[$i]]);
        }
        $fila++;
    }
    $consulta_bd->ClosePostgres();    

    $prueba->getActiveSheet()->setTitle("Consolidado"); 

    $objWriter = PHPExcel_IOFactory::createWriter($prueba, 'Excel2007'); 
    $objWriter->save('Consolidado.xlsx');   

	header( "Content-Type: application/force-download"); 
	header( "Content-Length: ".filesize('Consolidado.xlsx')); 
	header( "Content-Disposition: attachment; filename=".basename('Consolidado.xlsx')); 
	readfile('Consolidado.xlsx');	
    unlink('Consolidado.xlsx');	
?>