<?php
    include_once(dirname(__FILE__)."/../Clases/ClassConexionBD.php");

    class PostgresDB{
        private $dataConection;
        private $dbconnPostgres;
        private $serverPostgres;
        private $puertoPostgres;
        private $usernamePostgres;
        private $passwordPostgres;
        private $basedatosPostgres;


        public function __construct(){
            $this->dataConection    = ConexionBD::getInstance(); 
            $this->setDefaultConexion();   
        }


        function setConexion($_conexion){
            $this->dataConection->setConexion($_conexion);
            $this->serverPostgres   = $this->dataConection->getServidor();
            $this->puertoPostgres   = $this->dataConection->getPuerto();
            $this->usernamePostgres = $this->dataConection->getUsuario();
            $this->passwordPostgres = $this->dataConection->getPassword();
            $this->basedatosPostgres= $this->dataConection->getBaseDatos();
        }

        public function setDefaultConexion(){
            $this->SetConexion('linode');    
        }


        function getConexion(){
            return $this->dataConection->getConexion();
        }


        function OpenPostgres(){ 
            $this->dbconnPostgres =  pg_connect("host=".$this->serverPostgres." port=".$this->puertoPostgres." user=".$this->usernamePostgres." password=".$this->passwordPostgres." dbname=".$this->basedatosPostgres);
            if (!$this->dbconnPostgres) {
                //die("No pudo conectarse: host=".$this->serverPostgres." port=".$this->puertoPostgres." user=".$this->usernamePostgres." password=".$this->passwordPostgres." dbname=".$this->basedatosPostgres);
                die("No pudo conectarse");
            }
        }

        function ClosePostgres(){
            pg_close($this->dbconnPostgres);
        }
    
   
        function PostgresSelectWhereOrder($_tabla, $_campos, $_condicion, $_orden){
            $result = pg_query($this->dbconnPostgres, "SELECT ".$_campos." FROM ".$_tabla." WHERE ".$_condicion." ORDER BY ".$_orden); 
            return $result;
        }


        function PostgresSelectWhereGroupOrder($_tabla, $_campos, $_condicion, $_grupo, $_orden){
            $result = pg_query($this->dbconnPostgres, "SELECT ".$_campos." FROM ".$_tabla." WHERE ".$_condicion." GROUP BY ".$_grupo." ORDER BY ".$_orden); 
            return $result;
        }

        function PostgresSelectDistinctWhereGroupOrder($_tabla, $_campos, $_condicion, $_grupo, $_orden){
            $result = pg_query($this->dbconnPostgres, "SELECT DISTINCT ".$_campos." FROM ".$_tabla." WHERE ".$_condicion." GROUP BY ".$_grupo." ORDER BY ".$_orden); 
            return $result;
        }


        function PostgresSelectJoinWhereOrder($_tabla, $_campos, $_join, $_on,$_condicion, $_orden){
            $result = pg_query($this->dbconnPostgres, "SELECT ".$_campos." FROM ".$_tabla." JOIN ".$_join." ON ".$_on." WHERE ".$_condicion." ORDER BY ".$_orden); 
            return $result;
        }

        function PostgresSelectJoinJoinWhereOrder($_tabla, $_campos, $_join, $_on, $_join1, $_on1,$_condicion, $_orden){            
            $result = pg_query($this->dbconnPostgres, "SELECT DISTINCT ".$_campos." FROM ".$_tabla." JOIN ".$_join." ON ".$_on." JOIN ".$_join1." ON ".$_on1." WHERE ".$_condicion." ORDER BY ".$_orden); 
            return $result;
        }


        function PostgresSelectDistinctWhereOrder($_tabla, $_campos, $_condicion, $_orden){
            $result = pg_query($this->dbconnPostgres, "SELECT DISTINCT ".$_campos." FROM ".$_tabla." WHERE ".$_condicion." ORDER BY ".$_orden); 
            return $result;
        }


        function PostgresInsertIntoValues($_tabla, $_campos, $_valores){
            $result = pg_query( $this->dbconnPostgres, "INSERT INTO ".$_tabla."(".$_campos.") VALUES(".$_valores.");");
            if(pg_affected_rows($result)>0){
                return true;
            }else{
                return false;
            }
        }


        function PostgresUpdateValues($_tabla, $_datos, $_condicion){
            $result = pg_query( $this->dbconnPostgres, "UPDATE ".$_tabla." SET ".$_datos." WHERE ".$_condicion);
            if(pg_affected_rows($result)>0){
                return true;
            }else{
                return false;
            }
        }



        function PostgresExisteRegistro($_tabla, $_condicion){
            $result = pg_query($this->dbconnPostgres, "SELECT count(*) as existentes FROM ".$_tabla." WHERE ".$_condicion); 
            $RtaResult = pg_fetch_assoc($result);
            if($RtaResult['existentes']>0){
                return true;
            }else{
                return false;
            }
        }

       
        function PostgresEliminarRegistro($_tabla, $_condicion){
            $result = pg_query($this->dbconnPostgres, " DELETE FROM ".$_tabla." WHERE ".$_condicion); 
            $RtaResult = pg_fetch_assoc($result);
            if(pg_affected_rows($result)>0){
                return true;
            }else{
                return false;
            }
        }


        function PostgresFunction($_funcion){
             $result = pg_query($this->dbconnPostgres, "SELECT * FROM ".$_funcion." AS resultado");
             $rta_result  = pg_fetch_assoc($result);
             return $rta_result['resultado'];
        }

        function PostgresFunctionCamposTable($_campos, $_funcion){
             $result = pg_query($this->dbconnPostgres, "SELECT ".$_campos." FROM ".$_funcion);
             return $result;
        }


        function PostgresFunctionCamposTableWhere($_campos, $_funcion, $_condicion){
             $result = pg_query($this->dbconnPostgres, "SELECT ".$_campos." FROM ".$_funcion." WHERE ".$_condicion);
             return $result;
        }


        function PostgresFunctionCamposTableOrder($_campos, $_funcion, $_order){
             $result = pg_query($this->dbconnPostgres, "SELECT ".$_campos." FROM ".$_funcion." ORDER BY ".$_order." ASC");
             return $result;
        }

        function PostgresFunctionTable($_funcion){
             $result = pg_query($this->dbconnPostgres, "SELECT * FROM ".$_funcion);
             return $result;
        }


        //Funciones auxiliares para la conversion de la informacion consultada
        function QueryToJson($Query,$Campos,$Ajustes,$_key){
            $i=0;
            $data = array();
            while($Row=pg_fetch_assoc($Query)){
                for($j=0;$j<sizeof($Campos);$j++){
                    switch ($Ajustes[$j]){
                        case 'true'     : $ValorCampo   = 1;                                    break;  
                        case 'false'    : $ValorCampo   = 0;                                    break;  
                        case null       : $ValorCampo   = $Row[$Campos[$j]];                    break;  
                        case 'Fecha'    : $ValorCampo   = $this->ddmmaaaa($Row[$Campos[$j]]);   break;
                        case 'Moneda'   : $ValorCampo   = $this->Moneda($Row[$Campos[$j]]);     break;
                    }
                    if($_key){
                        $data[$i][$Campos[$j]]  = $ValorCampo;
                    }else{
                        $data[$i][$j]  = $ValorCampo;
                    }
                }
                $i++;
            }
            return $data;

            //if($_key){
            //    return $data;
            //}else{
            //    return json_encode($data);
            //}
        }


        function ddmmaaaa($Fecha){
            $Frag = explode("-",$Fecha);
            return($Frag[2]."/".$Frag[1]."/".$Frag[0]);
        }

         function ddmmaaaa2($Fecha){
            $Frag = explode("/",$Fecha);
            return($Frag[2]."-".$Frag[1]."-".$Frag[0]);
        }

        //funcion que muestra valores en formato de moneda
        function Moneda($Valor){
            return(number_format($Valor,0,",","."));
        }


                    /**
            FUNCION PARA REALIZAR LA CONSULTA DE LOS DATOS PARA DIBUJAR EL MAPA
            **/
        function PostgresSelectJoinJoinWhereGroupOrder($_tabla, $_campos, $_join, $_on, $_join1, $_on1,$_condicion,$_grupo,$_orden){            
                $result = pg_query($this->dbconnPostgres, "SELECT DISTINCT ".$_campos." FROM ".$_tabla." JOIN ".$_join." ON ".$_on." JOIN ".$_join1." ON ".$_on1."  WHERE ".$_condicion."  GROUP BY ".$_grupo." ORDER BY ".$_orden); 
                return $result;
        }   

    /*function InsertarFile($Datos)
    {   $doConection = DoConection();
        $query = "  INSERT INTO 
                    archivoexcel (namefile,fecha)  
                    values ('".$Datos[0]."','".$Datos[1]."')";
        $result =pg_query($doConection,$query);
        pg_close($doConection);
        return($result);
    }
    

    function InsertReg($doConection,$Tabla,$Campos,$Datos)
    {   $query = "  INSERT INTO " 
                 .$Tabla." ( ".$Campos. " )  
                    values (".$Datos.")";
        $result =pg_query($doConection,$query);
        return(pg_affected_rows($result));       
    }

  
    function InsertarInTable($Tabla,$Campos,$Datos)
    {   $doConection = DoConection();
        $query = "  INSERT INTO " 
                 .$Tabla." ( ".$Campos. " )  
                    values (".$Datos.")";
        $result =pg_query($doConection,$query);
        pg_close($doConection);
        return(pg_affected_rows($result));  
    }
   
    function BorrarInTable($Tabla,$Condicion)
    {   $doConection = DoConection();
        $query = "  DELETE FROM ".$Tabla." WHERE ".$Condicion;
        $result =pg_query($doConection,$query);// or die ('ERROR AL ACTUALIZAR DATOS EN LA FILA: ' .$Fila. ' DEL ARCHIVO, <br>SOLO SE HAN ACTUALIZADO '.($Fila-1).' FILAS');
        pg_close($doConection);
        return($result);
    }
    
    function ActualizarInTable($Tabla,$Datos,$Condicion)
    {   $doConection = DoConection();
        $query = "  UPDATE ".$Tabla." SET ".$Datos. " WHERE ".$Condicion;
        $result =pg_query($doConection,$query);// or die ('ERROR AL ACTUALIZAR DATOS EN LA FILA: ' .$Fila. ' DEL ARCHIVO, <br>SOLO SE HAN ACTUALIZADO '.($Fila-1).' FILAS');
        pg_close($doConection);
        return(pg_affected_rows($result));
    }

    function ActualizarRegInTable($Conexion,$Tabla,$Datos,$Condicion)
    {  //$doConection = DoConection();
        $query = "  UPDATE ".$Tabla." SET ".$Datos. " WHERE ".$Condicion;
        $result =pg_query($Conexion,$query);// or die ('ERROR AL ACTUALIZAR DATOS EN LA FILA: ' .$Fila. ' DEL ARCHIVO, <br>SOLO SE HAN ACTUALIZADO '.($Fila-1).' FILAS');
        //pg_close($doConection);
        return(pg_affected_rows($result));
    }
    
    function InsertarBDImg($Datos)
    {   $doConection = DoConection();
        $query = "  INSERT INTO 
                    imagenes(fecha,sampleimg,bigimg,proceso,titulo)  
                    values ('".$Datos[0]."','".$Datos[1]."','".$Datos[2]."','".$Datos[3]."','".$Datos[4]."')";
        $result =pg_query($doConection,$query);// or die ('ERROR AL ACTUALIZAR DATOS EN LA FILA: ' .$Fila. ' DEL ARCHIVO, <br>SOLO SE HAN ACTUALIZADO '.($Fila-1).' FILAS');
        return($result);        
    }
    
    function LastReport($Tabla, $Criterio, $Limite)
    {   $doConection = DoConection();
        $result = pg_query($doConection,"SELECT * FROM ".$Tabla." WHERE ".$Criterio." ORDER BY fecha DESC LIMIT ".$Limite);
        pg_close($doConection);
        return $result;
    }

    function LastReport2($Tabla, $Campos, $Criterio, $Orden, $Limite)
    {   $doConection = DoConection();
        $result = pg_query($doConection,"SELECT ".$Campos." FROM ".$Tabla." WHERE ".$Criterio." ORDER BY ".$Orden." DESC LIMIT ".$Limite);
        pg_close($doConection);
        return $result;
    }
    
    
    function ConsultarNoRepeat($Columnas,$Tabla,$Criterio,$Orden)
    {   $doConection = DoConection();    
        if($Criterio==null)
            $result = pg_query($doConection, "SELECT DISTINCT ".$Columnas. " FROM " .$Tabla." ORDER BY ".$Orden."") or die ('Error al consultar los datos'.  pg_last_error());
        
        else
            $result = pg_query($doConection, "SELECT DISTINCT ".$Columnas. " FROM " .$Tabla." WHERE ".$Criterio." ORDER BY ".$Orden."") or die ('Error al consultar los datos'.  pg_last_error());
        
            pg_close($doConection);
            return $result;
    }
    
    function ConsultarRepeat($Columnas,$Tabla,$Criterio,$Orden)
    {   $doConection = DoConection();    
        if($Criterio==null)
            $result = pg_query($doConection, "SELECT ".$Columnas. " FROM " .$Tabla." ORDER BY ".$Orden."") or die ('Error al consultar los datos'.  pg_last_error());
        
        else
            $result = pg_query($doConection, "SELECT ".$Columnas. " FROM " .$Tabla." WHERE ".$Criterio." ORDER BY ".$Orden."") or die ('Error al consultar los datos'.  pg_last_error());
        
            pg_close($doConection);
            return pg_num_rows($result);
    }

    function SelectInnerJoinOn($Select,$From,$InnerJoin,$On,$Where,$OrderBy)
    {   $doConection = DoConection();
        $result = pg_query($doConection,    " SELECT ".$Select.
                                            " FROM " .$From.
                                            " INNER JOIN " .$InnerJoin.
                                            " ON " .$On.
                                            " WHERE ".$Where.
                                            " ORDER BY ".$OrderBy) or die ('Error al consultar los datos'.  pg_last_error());
        return($result);
        pg_close($doConection); 
    }


    function IsInTable($Tabla,$Condicion)
    {   $doConection = DoConection(); 
        $result = pg_query($doConection,"SELECT * FROM " .$Tabla." WHERE ".$Condicion) or die ('Error al consultar los datos'.  pg_last_error());
        if(pg_num_rows($result)>0)
            return(true);
        else
            return(false);   
        pg_close($doConection);
        
    }

    function QueryDistinctGroupOrder($Select,$From,$Where,$GroupBy,$OrderBy)
    {   $doConection = DoConection(); 
        $result = pg_query($doConection,    "SELECT DISTINCT ".$Select.
                                            " FROM " .$From.
                                            " WHERE ".$Where.
                                            " GROUP BY ".$GroupBy.
                                            " ORDER BY ".$OrderBy) or die ('Error al consultar los datos'.  pg_last_error());
        return($result);
        pg_close($doConection); 

    }
    

    function QueryDistinctJoinGroupOrder($Select,$From,$Join,$On,$Where,$GroupBy,$OrderBy)
    {   $doConection = DoConection(); 
        $result = pg_query($doConection,    "SELECT DISTINCT ".$Select.
                                            " FROM " .$From.
                                            " JOIN ".$Join.
                                            " ON ".$On.
                                            " WHERE ".$Where.
                                            " GROUP BY ".$GroupBy.
                                            " ORDER BY ".$OrderBy) or die ('Error al consultar los datos'.  pg_last_error());
        return($result);
        pg_close($doConection); 

    }  

    function QueryDistinctJoinOrder($Select,$From,$Join,$On,$Where,$OrderBy)
    {   $doConection = DoConection(); 
        $result = pg_query($doConection,    "SELECT DISTINCT ".$Select.
                                            " FROM " .$From.
                                            " JOIN ".$Join.
                                            " ON ".$On.
                                            " WHERE ".$Where.
                                            " ORDER BY ".$OrderBy) or die ('Error al consultar los datos'.  pg_last_error());
        return($result);
        pg_close($doConection); 
    }   


    function QueryDistinctCrossOrder($Select,$From,$Cross,$Where,$OrderBy)
    {   $doConection = DoConection(); 
        $result = pg_query($doConection,    "SELECT DISTINCT ".$Select.
                                            " FROM " .$From.
                                            " CROSS JOIN ".$Cross.
                                            " WHERE ".$Where.
                                            " ORDER BY ".$OrderBy) or die ('Error al consultar los datos'.  pg_last_error());
        return($result);
        pg_close($doConection);  

    }


    function QueryDistinctCrossOrderLimit($Select,$From,$Cross,$Where,$OrderBy,$Limit)
    {   $doConection = DoConection(); 
        $result = pg_query($doConection,    "SELECT DISTINCT ".$Select.
                                            " FROM " .$From.
                                            " CROSS JOIN ".$Cross.
                                            " WHERE ".$Where.
                                            " ORDER BY ".$OrderBy.
                                            " LIMIT ".$Limit) or die ('Error al consultar los datos'.  pg_last_error());
        return($result);
        pg_close($doConection); 
    }


    //consultas que devuelven valores numericos
    function ConsultarCantidad($Tabla,$Criterio)
    {   $doConection = DoConection(); 
        $result = pg_query($doConection, "SELECT COUNT(*) As cantidad FROM " .$Tabla." WHERE ".$Criterio) or die ('Error al consultar los datos'.  pg_last_error());
        return($result);
        pg_close($doConection);
    }


    function ConsultarSuma($Tabla,$Campos,$Criterio)
    {   $doConection = DoConection();  
        $result = pg_query($doConection, "SELECT SUM(asignacion) AS total FROM " .$Tabla." WHERE ".$Criterio) or die ('Error al consultar los datos'.  pg_last_error());  
        $resultSuma=pg_fetch_assoc($result);
        return($resultSuma['total']);
        pg_close($doConection);

    }*/

}


       
 ?>