<?php
  
  include_once(dirname(__FILE__)."/../Clases/ClassPostgresBD.php");
  include_once(dirname(__FILE__)."/../PHPMailer/Ejemplo.php");
  

    switch($_POST['Peticion']){
        case 'UploadTrabajo':       UploadTrabajo($_POST['informacion'],$_POST['origen']);                    break;
    };

    function base64_to_jpeg($base64_string, $output_file) {
       $ifp = fopen($output_file, "wb"); 
       fwrite($ifp, base64_decode(str_replace(" ", "+", $base64_string))); 
       fclose($ifp); 

       return $output_file; 
    }

    function UploadTrabajo($json,$origen_lectura){
        $stringInfoInsertados="";
        $postgresWS = new PostgresDB();
        $postgresWS->OpenPostgres();
            
        $request = json_decode($json,true);
        $file = fopen("uploads/".str_replace(":","_",$origen_lectura).".txt", "a") or die("No se pudo generar el archivo");
        for($i=0;$i<count($request['informacion']);$i++){            
            fputs($file,$request['informacion'][$i]['tipo'].",".$request['informacion'][$i]['id_programacion'].",".$request['informacion'][$i]['id'].",".$request['informacion'][$i]['id_serial1'].",".$request['informacion'][$i]['lectura1'].",'".$request['informacion'][$i]['critica1']."',".$request['informacion'][$i]['id_serial2'].",".$request['informacion'][$i]['lectura2'].",'".$request['informacion'][$i]['critica2']."',".$request['informacion'][$i]['id_serial3'].",".$request['informacion'][$i]['lectura3'].",'".$request['informacion'][$i]['critica3']."',".$request['informacion'][$i]['anomalia'].",".$request['informacion'][$i]['mensaje'].",".$request['informacion'][$i]['tipo_uso'].",".$request['informacion'][$i]['id_inspector'].",".$request['informacion'][$i]['fecha_toma'].",".$request['informacion'][$i]['longitud'].",".$request['informacion'][$i]['latitud'].",".$origen_lectura.",".$request['informacion'][$i]['x'].",".$request['informacion'][$i]['y']);
            fputs($file,"\n");            
    	
            if($request['informacion'][$i]['tipo'] == "L"){
                $queryRespuesta = $postgresWS->PostgresFunction("maestro.recibir_toma_lectura(".$request['informacion'][$i]['id_serial1'].",".$request['informacion'][$i]['lectura1'].",'".$request['informacion'][$i]['critica1']."',".$request['informacion'][$i]['id_serial2'].",".$request['informacion'][$i]['lectura2'].",'".$request['informacion'][$i]['critica2']."',".$request['informacion'][$i]['id_serial3'].",".$request['informacion'][$i]['lectura3'].",'".$request['informacion'][$i]['critica3']."',".$request['informacion'][$i]['anomalia'].",'".utf8_encode(str_replace("&","y",$request['informacion'][$i]['mensaje']))."','".$request['informacion'][$i]['tipo_uso']."',".$request['informacion'][$i]['id_inspector'].",'".$request['informacion'][$i]['fecha_toma']."',".$request['informacion'][$i]['longitud'].",".$request['informacion'][$i]['latitud'].",'".$origen_lectura."',".$request['informacion'][$i]['x'].",".$request['informacion'][$i]['y'].")");
                if($queryRespuesta == $request['informacion'][$i]['id_serial1']){
                    $stringInfoInsertados=$request['informacion'][$i]['id']."|".$stringInfoInsertados;
                }else{
                    $stringInfoInsertados = "-".$request['informacion'][$i]['id']."|".$stringInfoInsertados;  
                }  
            }else{
               $queryRespuesta = $postgresWS->PostgresFunction("maestro.recibir_no_lecturas(".$request['informacion'][$i]['id_serial1'].",".$request['informacion'][$i]['id_programacion'].",'".$request['informacion'][$i]['tipo']."',".$request['informacion'][$i]['lectura1'].",'".$request['informacion'][$i]['critica1']."',".$request['informacion'][$i]['id_serial2'].",".$request['informacion'][$i]['lectura2'].",'".$request['informacion'][$i]['critica2']."',".$request['informacion'][$i]['id_serial3'].",".$request['informacion'][$i]['lectura3'].",'".$request['informacion'][$i]['critica3']."',".$request['informacion'][$i]['anomalia'].",'".utf8_encode(str_replace("&","y",$request['informacion'][$i]['mensaje']))."','".$request['informacion'][$i]['tipo_uso']."',".$request['informacion'][$i]['id_inspector'].",'".$request['informacion'][$i]['fecha_toma']."',".$request['informacion'][$i]['longitud'].",".$request['informacion'][$i]['latitud'].",'".$origen_lectura."',".$request['informacion'][$i]['x'].",".$request['informacion'][$i]['y'].")");
                if($queryRespuesta == $request['informacion'][$i]['id_serial1']){
                    $stringInfoInsertados=$request['informacion'][$i]['id']."|".$stringInfoInsertados;
                }else{
                    $stringInfoInsertados = "-".$request['informacion'][$i]['id']."|".$stringInfoInsertados;  
                }
            }

            /**Para guardar las fotos para enviar el correo**/   
           if($request['informacion'][$i]['anomalia']==22||$request['informacion'][$i]['anomalia']==52){
                if(count($request['informacion'][$i]['fotos'])>0){
                    for($j=0;$j<count($request['informacion'][$i]['fotos']);$j++){          
                        
                        $imagen = base64_to_jpeg(str_replace("\"","",$request['informacion'][$i]['fotos'][$j]['foto']),$request['informacion'][$i]['fotos'][$j]['nombre_foto']);
                        $im = file_get_contents($imagen);   
                        $im = new Imagick();
                        $dibujo = new ImagickDraw();
                        $im->readimage($imagen);
                        $im->thumbnailImage(620,430,true);

                        $dibujo->setFillColor('orange');
                        $dibujo->setFont('Bookman-DemiItalic');    
                        $dibujo->setFontSize(20);    
                        $im->annotateImage($dibujo,340,405,0,$request['informacion'][$i]['fotos'][$j]['fecha_toma']);

                        $im->setImageFormat("jpg");                   
                        //$imdata = base64_encode($im);

                        if(!file_exists('uploads/'.$request['informacion'][$i]['fotos'][$j]['cuenta'])){
                            mkdir('uploads/'.$request['informacion'][$i]['fotos'][$j]['cuenta']);      
                        }

                        $file_path = "uploads/".$request['informacion'][$i]['fotos'][$j]['cuenta']."/";
                        $nombre_imagen = $file_path.$request['informacion'][$i]['fotos'][$j]['nombre_foto']; 
                        file_put_contents($nombre_imagen, $im);

                        unlink($request['informacion'][$i]['fotos'][$j]['nombre_foto']);
                        $queryRespuestaFoto = $postgresWS->PostgresFunction("toma.recibir_lectura_foto(".$request['informacion'][$i]['id_serial1'].",".$request['informacion'][$i]['fotos'][$j]['cuenta'].",'".$request['informacion'][$i]['fotos'][$j]['nombre_foto']."','".$request['informacion'][$i]['fotos'][$j]['fecha_toma']."')");
                        if($queryRespuestaFoto == $request['informacion'][$i]['id_serial1']){
                            enviarEmail($request['informacion'][$i]['id_serial1']); 
                        }
                    }                
                }
            }       
    	}	
        fclose($file);
        $postgresWS->ClosePostgres();
        echo $stringInfoInsertados;
    }
 ?>