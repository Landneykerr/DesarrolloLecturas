<?php
    session_start();
	include_once(dirname(__FILE__)."/../Clases/ClassPostgresBD.php");

    class ClassArchivos{
	   private $arch_connect;
	   
	    function ClassArchivos(){
            $this->arch_connect = new PostgresDB();
        }

        function RegistrarInformacionArchivo($_ciclo,$_mes,$_anno,$_archivo,$_delimitador){
            $this->arch_connect->OpenPostgres();
        	$retorno =  $this->arch_connect->PostgresFunction("maestro.cargue_archivos(".$_ciclo.",".$_mes.",".$_anno.",'".$_archivo."','".$_delimitador."','".$_SESSION['UserName']."') ");
        	$this->arch_connect->ClosePostgres();
            return $retorno;
            //return "maestro.cargue_archivos(".$_ciclo.",".$_mes.",".$_anno.",".$_archivo.",".$_delimitador.") ";
        }


        function CerrarCiclos($_mes, $_anno, $_ciclos){
            $array = "";
            for($i=0; $i<count($_ciclos['ListaCiclos']); $i++){
                $array .= $_ciclos['ListaCiclos'][$i]['Ciclo'].",";
            }
            $array = "array[".substr($array,0,-1)."]";

            $this->arch_connect->OpenPostgres();
            return $Informacion =  $this->arch_connect->PostgresFunction( "maestro.cerrar_ciclos(".$_mes.",".$_anno.",".$array.")"); 
            $this->arch_connect->ClosePostgres();
        }
	}
?>

