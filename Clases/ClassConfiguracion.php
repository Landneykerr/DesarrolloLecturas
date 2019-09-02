<?php
	session_start();
	include_once(dirname(__FILE__)."/../Clases/ClassPostgresBD.php");
	include_once("RSA.php");

	class Configuracion{
		private $cfg_connect;
	   	private $ObjRSA;
		function Configuracion(){
			$this->cfg_connect 	= new PostgresDB();
			$this->ObjRSA 		= new RSA();
        }



        /**
		Function para crear un tecnico en el sistema
		**/
		function crearUsuario($_cedula, $_nombre, $_correo, $_usuario, $_contrasena){
			$this->cfg_connect->OpenPostgres();
			return $this->cfg_connect->PostgresInsertIntoValues("configuracion.usuarios_web",
																"nombre,username,contrasena,cedula,nivel,correo",
																"'".$_nombre."','".$_usuario."','".md5($_contrasena)."','".$_cedula."',".($_SESSION['Nivel']+1).",'".$_correo."'");
			$this->cfg_connect->ClosePostgres();
		}


		/**
		Function para crear un tecnico en el sistema
		**/
		function ConsultarUsuarios($_tipo){
			$this->cfg_connect->OpenPostgres();
			$informacion = 	$this->cfg_connect->PostgresSelectWhereOrder(	"configuracion.usuarios_web", 
																			"nombre,correo,username", 
																			"nivel>".$_SESSION['Nivel'], 
																			"nombre");
			return json_encode($this->cfg_connect->QueryToJson($informacion,["nombre","correo","username"],[null,null,null,null],$_tipo));
			$this->cfg_connect->ClosePostgres();
		}


		/**
		Function para crear un tecnico en el sistema
		**/
		function eliminarUsuarios($_usuarios){
			$this->cfg_connect->OpenPostgres();
			for($j=0;$j<sizeof($_usuarios['ListaUsuarios']);$j++){
				$this->cfg_connect->PostgresEliminarRegistro("configuracion.usuarios_web", "username='".$_usuarios['ListaUsuarios'][$j]["Usuario"]."' AND nivel>".$_SESSION['Nivel']);
			}
			$this->cfg_connect->ClosePostgres();
			return $this->ConsultarUsuarios(false);			
		}


		/**
		Function para consultar las paginas que tiene el sistema
		**/
		function ConsultarPaginas($_tipo){
			$this->cfg_connect->OpenPostgres();
			$informacion = 	$this->cfg_connect->PostgresSelectWhereOrder(	"configuracion.paginas", 
																			"pagina,descripcion", 
																			"estado IS TRUE", 
																			"pagina");
			return json_encode($this->cfg_connect->QueryToJson($informacion,["pagina","descripcion"],[null,null],$_tipo));
			$this->cfg_connect->ClosePostgres();
		}


		/**
		Function para crear paginas en el sistema
		**/
		function CrearPagina($_pagina, $_descripcion){
			$this->cfg_connect->OpenPostgres();
			return $this->cfg_connect->PostgresInsertIntoValues("configuracion.paginas",
																"pagina,descripcion",
																"'".$_pagina."','".$_descripcion."'");
			$this->cfg_connect->ClosePostgres();
		}


		/**
		Function para eliminar paginas del sistema
		**/
		function EliminarPagina($_pagina){
			$this->cfg_connect->OpenPostgres();
			$this->cfg_connect->PostgresEliminarRegistro("configuracion.paginas", "pagina='".$_pagina."'");
			$this->cfg_connect->ClosePostgres();
			return $this->ConsultarPaginas();			
		}


		/**
		Function para consultar las todos los modulos existentes en el sistema
		**/
		function ConsultarModulos($_pagina, $_tipo){
			$this->cfg_connect->OpenPostgres();
			$informacion = 	$this->cfg_connect->PostgresSelectWhereOrder(	"configuracion.modulos", 
																			"modulo,titulo_modulo,descripcion", 
																			"pagina = '".$_pagina."'", 
																			"titulo_modulo");
			return json_encode($this->cfg_connect->QueryToJson($informacion,["modulo","titulo_modulo","descripcion"],[null,null,null],$_tipo));
			$this->cfg_connect->ClosePostgres();
		}


		/**
		Function para crear modulos en el sistema
		**/
		function CrearModulo($_pagina, $_modulo, $_titulo, $_descripcion){
			$this->cfg_connect->OpenPostgres();
			return $this->cfg_connect->PostgresInsertIntoValues("configuracion.modulos",
																"pagina,modulo,titulo_modulo,descripcion",
																"'".$_pagina."','".$_modulo."','".$_titulo."','".$_descripcion."'");
			$this->cfg_connect->ClosePostgres();
		}


		/**
		Function para eliminar los modulos del sistema
		**/
		function EliminarModulos($_pagina, $_modulos){
			$this->cfg_connect->OpenPostgres();
			for($j=0;$j<sizeof($_modulos['ListaModulos']);$j++){
				$this->cfg_connect->PostgresEliminarRegistro("configuracion.modulos", "pagina='".$_pagina."' AND modulo='".$_modulos['ListaModulos'][$j]["Modulo"]."'");
			}
			$this->cfg_connect->ClosePostgres();
			return $this->ConsultarModulos();			
		}



		/**
		Funcion para consultar las paginas de accesos de un usuario del sistema
		**/
		function ConsultarAccesoPaginas($_usuario){
			$this->cfg_connect->OpenPostgres();
			$InfCfg = $this->cfg_connect->PostgresSelectDistinctWhereOrder("configuracion.vista_usuarios_accesos", "pagina AS valor, pagina AS texto", "username='".$_usuario."'", "texto");
			return json_encode($this->cfg_connect->QueryToJson($InfCfg,["valor","texto"],[null,null],true));
			$this->cfg_connect->ClosePostgres();
		}



		/**
		Funcion para consultar los accesos de un usuario del sistema y ponerlo en contraste con el usuario padre
		**/
		function ConsultarAccesos($_usuario_padre, $_usuario_hijo, $_pagina){
			$this->cfg_connect->OpenPostgres();
			$InfCfg = $this->cfg_connect->PostgresFunctionTable("configuracion.consulta_accesos_usuario('".$_usuario_padre."','".$_usuario_hijo."','".$_pagina."')");
			return json_encode($this->cfg_connect->QueryToJson($InfCfg,["id","modulo","acceso"],[null,null,null],false));
			$this->cfg_connect->ClosePostgres();
		}


		/**
		Funcion encargada de actualizar los accesos del usuario al sistema
		**/
		function GuardarAccesosUsuario($_usuario, $_accesos){
			$retorno = "";
			$this->cfg_connect->OpenPostgres();

			for($j=0;$j<sizeof($_accesos['Accesos']);$j++){
				if($_accesos['Accesos'][$j]['valor']=='No'){
					$this->cfg_connect->PostgresEliminarRegistro("configuracion.accesos_web","username='".$_usuario."' AND id_acceso = ".$_accesos['Accesos'][$j]['id']);
				}else if($_accesos['Accesos'][$j]['valor']=='Si'){
					$this->cfg_connect->PostgresInsertIntoValues("configuracion.accesos_web", "id_acceso,username", $_accesos['Accesos'][$j]['id'].",'".$_usuario."'");
				}
			}	
			$this->cfg_connect->ClosePostgres();
			return "Accesos actualizados.";
		}


		/**
		Funcion encargada de actualizar los accesos del usuario al sistema
		**/
		function GenerarClaveRSA($_usuario){			
			$resultado = $this->ObjRSA->GenerarClaves();	
			$this->cfg_connect->OpenPostgres();
			if($this->cfg_connect->PostgresInsertIntoValues("configuracion.llave_rsa",
															"modulo,publica,privada,phi,usuario",
															"'".$resultado['modulo']."','".$resultado['publica']."','".$resultado['privada']."','".$resultado['phi']."','".$_usuario."'")){
				return "Llave RSA generada correctamente.";
			}else{
				return "Error al generar la llave RSA.";
			}
			$this->cfg_connect->ClosePostgres();		
		}


		/**
		Funcion para consultar los accesos de un usuario del sistema y ponerlo en contraste con el usuario padre
		**/
		function ConsultarClaveRSA(){
			$this->cfg_connect->OpenPostgres();
			$InfCfg = $this->cfg_connect->PostgresSelectWhereOrder(	"configuracion.llave_rsa",
																	"id_serial,modulo,privada,publica,fecha_generacion",
																	"id_serial IS NOT NULL",
																	"fecha_generacion DESC");
			return $this->cfg_connect->QueryToJson($InfCfg,["id_serial","modulo","privada","publica","fecha_generacion"],[null,null,null,null,null],false);
			$this->cfg_connect->ClosePostgres();
		}



	}

?>