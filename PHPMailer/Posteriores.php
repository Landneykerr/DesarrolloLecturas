<?php
	require_once('/media/sda1/www/ControlEnergia/BaseDatos/ConsultasBD.php');
	require_once('/media/sda1/www/ControlEnergia/ClasesPhp/QueryOracle.php');
	require_once('/media/sda1/www/ControlEnergia/PHPMailer/class.phpmailer.php');
	require_once('/media/sda1/www/ControlEnergia/PHPMailer/class.smtp.php');
	


	echo "Hola";





	class OraclePHP {
		private $user;
	    private $pass;
	    private $host;
	    private $StringQuery;
	    private $Query;
	    private $Row;
	    private $InfRetorno;
	    private	$dbconn;*/


		function __construct(){

		}

	
		function Conectar() {
			$this->user 	= "SGD";
			$this->pass 	= "sistemasce";
			$this->host		= "192.168.0.50/perdidas"; 
			//$this->host		= "localhost/perdidas"; 
			$this->dbconn = oci_pconnect($this->user, $this->pass, $this->host);
		}


		function Desconectar(){
			oci_close($this->dbconn);
		}


		function InformacionPosteriores(){
			//Variables para las consultas de Oracle con php
			$this->Conectar();


			$QueryPsg = SelectDistinctOrder("pda,orden,cuenta,acta",
			    							"ordenes_trabajo_historico.ordenes_trabajo",
			    							"fecha='".date('d-m-Y')."' AND estado analista = 0",
			    							"pda,orden,cuenta,acta");

			//Por cada registro devuelto por postgres se realiza una consulta en oracle
			$RtaQueryPsg = pg_fetch_assoc($QueryPsg){		
			   	$this->StringQuery =   "SELECT 	ID_ORDEN, PROPIETARIO, DIRECCION
										FROM 	SGD_ORDENES_TRABAJO_PDA        
										WHERE 	ID_ORDEN =".$RtaQueryPsg['orden']."
										ORDER BY ID_ORDEN ASC"; 

				$this->Query = oci_parse($this->dbconn, $this->StringQuery);
				oci_execute($this->Query);
												
				$this->Row = oci_fetch_assoc($this->Query);
				$this->Desconectar();

				print_r($this->Row);

				//return $this->InfRetorno;
			}
		}
	}


?>