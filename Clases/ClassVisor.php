<?php
	session_start();
	include_once(dirname(__FILE__)."/../Clases/ClassPostgresBD.php");

	class ClassVisor{
	   private $trabajo_connect;
	   
		function ClassVisor(){
			$this->trabajo_connect = new PostgresDB();
		}

		function consultarFotos($fecha, $inspector){
			//echo "Hola si envia los datos";
		  //return  "Datos Prueba desde Clase".$fecha."--".$inspector;
			/*$this->trabajo_connect->OpenPostgres();
			$InfProg=$this->trabajo_connect->PostgresSelectDistinctWhereOrder(	"registro.imagenes_visor", 
																			"foto,cuenta, fecha_toma",
																			"inspector =".$inspector."  AND cast(fecha_toma as date) = '".$fecha."'", 
																			"fecha_toma");
			while($rtaInfProg=pg_fetch_assoc($InfProg)){
				return  $rtaInfProg['cuenta'];
			}
			$this->trabajo_connect->ClosePostgres();
			*/
			 $conn = pg_connect("user=consult_fotos password=l3ctur4sf0t0s dbname=fotos_lecturas host=186.115.150.189");												
		     $query = pg_query($conn, "SELECT foto,cuenta, fecha_toma FROM registro.imagenes_visor ORDER BY fecha_toma DESC");
												    
		    while($row   = pg_fetch_assoc($query)){            
/*			  echo "<br>";
												      echo "
												        <li>
												            <a href='data:image/jpg;base64,".$row['foto']."'  data-caption='Cuenta:".$row['cuenta']." FechaToma:".$row['fecha_toma']."' ><b>Ver Foto</b></a>
												        </li>
												      ";
												      echo "<br>";*/
												      echo "Hola si envia los datos";
			}
			pg_close($conn);	
					
	  }
	
	}	
?>

