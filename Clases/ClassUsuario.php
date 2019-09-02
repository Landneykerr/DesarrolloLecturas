<?php
	include_once(dirname(__FILE__)."/../Clases/ClassPostgresBD.php");

	class Usuario{
		private $usuario_connect;
	   
		function Usuario(){
			$this->usuario_connect = new PostgresDB();
		}


		/**
		Funcion para eliminar todos los datos de session del usuario
		**/
		function CloseSession(){

		}


		/**
		Funcion que carga la informacion inicial e importante del usuario, es el punto de partida para todos los accesos tanto a paginas como a modulos
		**/
		function LogginUsuario($_usuario, $_contrasena){
			$this->usuario_connect->OpenPostgres();
			if($this->usuario_connect->PostgresExisteRegistro("configuracion.usuarios_web","username='".$_usuario."' AND contrasena='".md5($_contrasena)."' AND estado = TRUE")){
				$InfUser=$this->usuario_connect->PostgresSelectDistinctWhereOrder(  "configuracion.usuarios_web", 
																					"nombre,nivel",
																					"username='".$_usuario."'", 
																					"nombre");
				//session_start();
				$row = pg_fetch_assoc($InfUser);
				$_SESSION['UserName']       = $_usuario;
				$_SESSION['NombreCompleto'] = $row['nombre'];           //se almacena la informacion basica y necesaria en una variable de sesion
				$_SESSION['Nivel']          = $row['nivel'];

				$InfAcceso = $this->usuario_connect->PostgresSelectDistinctWhereOrder(  "configuracion.vista_usuarios_accesos", 
																						"pagina,modulo,titulo_modulo",
																						"username='".$_usuario."'", 
																						"pagina,titulo_modulo");
				while($row = pg_fetch_assoc($InfAcceso)){
					$_SESSION['Accesos'][$row['pagina']][$row['modulo']] = $row['titulo_modulo'];
				}

				$this->usuario_connect->ClosePostgres();
				
				return true;
			}else{
				return false;
			}
		}


		/**
		Funcion que muestra el menu al lado izquierdo de las paginas a las cuales tiene habilitado el acceso el usuario
		**/
		function AccesoPaginas($_pagina){
			$id = 1;
			while (current($_SESSION['Accesos'])){
				if (key($_SESSION['Accesos']) == $_pagina) {
					echo "<li><a href='#'>".key($_SESSION['Accesos'])."</a></li>";
				}else{
					echo "<li><a href='".key($_SESSION['Accesos']).".php'>".key($_SESSION['Accesos'])."</a></li>";
				}
				$id++;
				next($_SESSION['Accesos']);
			}
		}



		/** 
		Funcion encargada de mostrar los modulos a los que tiene acceso el usuario.          
		**/
		function AccesoModulos($_pagina){
			$i=0;
			while (current($_SESSION['Accesos'][$_pagina])){
				if($i == 0){
					//echo "<li class='active'><a data-toggle='tab' href='#".key($_SESSION['Accesos'][$_pagina])."'>".$_SESSION['Accesos'][$_pagina][key($_SESSION['Accesos'][$_pagina])]."</a></li>";   
					echo "<li><a data-toggle='tab' href='#".key($_SESSION['Accesos'][$_pagina])."'>".$_SESSION['Accesos'][$_pagina][key($_SESSION['Accesos'][$_pagina])]."</a></li>";   
				}else{
					echo "<li><a data-toggle='tab' href='#".key($_SESSION['Accesos'][$_pagina])."'>".$_SESSION['Accesos'][$_pagina][key($_SESSION['Accesos'][$_pagina])]."</a></li>";   
				}
				next($_SESSION['Accesos'][$_pagina]);
				$i++;
			}
		}


		/**
		Funcion para mostrar en un combo los tipos de archivos que puede manipular un usuario segun el proceso al que pertenezca
		**/
		function getTipoArchivos(){
			echo "<option values='0'>...</option>";
			$this->usuario_connect->OpenPostgres(); 
			$InfArchivos=$this->usuario_connect->PostgresSelectDistinctWhereOrder(  "archivos.vista_tipo_archivos", 
																					"id_serial,descripcion_archivo",
																					"id_proceso='".$_SESSION['Proceso']."'", 
																					"descripcion_archivo");
			while($row = pg_fetch_assoc($InfArchivos)){
			   echo "<option value='".$row['id_serial']."'>".$row['descripcion_archivo']."</option>";
			}
			$this->usuario_connect->ClosePostgres();
		}
	}
?>