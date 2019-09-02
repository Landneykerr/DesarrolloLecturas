<?php 
	session_start();
    include_once(dirname(__FILE__)."/../Clases/ClassPostgresBD.php");
    include_once(dirname(__FILE__)."/../Excel/PHPExcel.php");

    ini_set("memory_limit", "512M"); 
    $consulta_bd = new PostgresDB('localhost','5432','postgres','4y4n4m1_r3y','eaav_desviaciones'); 
    //$consulta_bd = new PostgresDB('190.93.133.127','5432','postgres','4y4n4m1_r3y','eaav_desviaciones');

    $campos = array("fecha entrega carta",
                    "forma entrega carta",
                    "revision",
                    "codigo",
                    "zona",
                    "ciclo",
                    "acta efectiva",
                    "fecha actividad efectiva",
                    "tecnico efectiva",
                    "precinto efectiva",
                    "novedad1",
                    "lectura1",
                    "novedad2",
                    "lectura2",
                    "lectura efectiva",
                    "diagnostico",
                    "respuesta",
                    "acta notificacion",
                    "fecha actividad notificacion",
                    "precinto notificacion",
                    "lectura notificacion",
                    "fecha notificacion",
                    "jornada notificacion",
                    "motivo notificacion",
                    "observacion",
                    "tecnico notificacion",
                    "oficio",
                    "fecha oficio");
    
    $prueba = new PHPExcel(); 
    for($i=0; $i<count($campos); $i++){
        $prueba->setActiveSheetIndex(0)->setCellValueByColumnAndRow($i,1,$campos[$i]); 
    }    

    $consulta_bd->OpenPostgres();
    $Row = $consulta_bd->PostgresFunctionCamposTable("fecha_entrega_carta,forma_entrega_carta,revision,codigo,zona,ciclo,acta_efectiva,fecha_actividad_e,nombre_tecnico_e,precinto_e,novedad1,lectura1,novedad2,lectura2,lectura_e,diagnostico,respuesta,acta_notificacion,fecha_actividad_n,precinto_n,lectura_n,fecha_notificacion,jornada_notificacion,motivo_notificacion,observacion,nombre_tecnico_n,oficio,fecha_oficio",
                                                     "consultacontrol('".$_GET['ini']."','".$_GET['fin']."') ");

    $fila = 2;
    while($RtaRow = pg_fetch_array($Row)){
        for($i=0; $i<count($RtaRow); $i++){
             $prueba->setActiveSheetIndex(0)->setCellValueByColumnAndRow($i,$fila,$RtaRow[$i]); 
        }
        $fila++;
    }
    $consulta_bd->ClosePostgres();

    $prueba->getActiveSheet()->setTitle("Control"); 

    $objWriter = PHPExcel_IOFactory::createWriter($prueba, 'Excel2007'); 
    $objWriter->save('Control.xlsx');   

	//fclose($handle);  
	header( "Content-Type: application/force-download"); 
	header( "Content-Length: ".filesize('Control.xlsx')); 
	header( "Content-Disposition: attachment; filename=".basename('Control.xlsx')); 
	readfile('Control.xlsx');	
    unlink('Control.xlsx');	
?>