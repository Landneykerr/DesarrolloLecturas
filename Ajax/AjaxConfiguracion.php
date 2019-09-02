<?php
	session_start();

	include_once(dirname(__FILE__)."/../Clases/ClassConfiguracion.php");
	
	switch($_POST['Peticion']){
		case 'ConsultarPaginas':		ConsultarPaginas();																							break;
		case 'CrearPagina':				CrearPagina($_POST['Pagina'], $_POST['Descripcion']);														break;
		case 'EliminarPagina': 			EliminarPagina($_POST['Pagina']);																			break;
		
		case 'ConsultarModulos':		ConsultarModulos($_POST['Pagina']);																			break;
		case 'CrearModulo': 			CrearModulo($_POST['Pagina'], $_POST['Modulo'], $_POST['Titulo'], $_POST['Descripcion']);					break;
		case 'EliminarModulos': 		EliminarModulos($_POST['Pagina'], $_POST['Modulos']);														break;
		
		case 'ConsultarUsuarios':  		ConsultarUsuarios();																						break;
		case 'EliminarUsuarios':		EliminarUsuarios($_POST['Usuarios']);																		break;
		case 'CrearUsuario':  			CrearUsuario($_POST['Cedula'], $_POST['Nombre'], $_POST['Correo'], $_POST['Usuario'], $_POST['Contrasena']);break;
		

		//case 'ConsultarAccesoPaginas': 	ConsultarAccesoPaginas($_SESSION['UserName']);																	break;
		case 'ConsultarAccesos':		ConsultarAccesos($_SESSION['UserName'], $_POST['Usuario'], $_POST['Pagina']);								break;
		case 'GuardarAccesosUsuario':	GuardarAccesosUsuario($_POST['Usuario'], $_POST['Accesos']);												break;
		
		case 'GenerarClaveRSA': 		GenerarClaveRSA($_SESSION['UserName']);																		break;
		case 'ConsultarClaveRSA': 		ConsultarClaveRSA();																						break;
	};


	function CrearUsuario($_cedula, $_nombre, $_correo, $_usuario, $_contrasena){
		$AjaxConfiguracion 	= new Configuracion();
		echo $AjaxConfiguracion->crearUsuario($_cedula, $_nombre, $_correo, $_usuario, $_contrasena);
	}


	function ConsultarUsuarios(){
		$AjaxConfiguracion 	= new Configuracion();
		echo $AjaxConfiguracion->ConsultarUsuarios(false);
	}


	function EliminarUsuarios($_usuarios){
		$AjaxConfiguracion 	= new Configuracion();
		echo $AjaxConfiguracion->eliminarUsuarios($_usuarios);
	}


	function ConsultarPaginas(){
		$AjaxConfiguracion 	= new Configuracion();
		echo $AjaxConfiguracion->ConsultarPaginas(false);
	}


	function CrearPagina($_pagina, $_descripcion){
		$AjaxConfiguracion 	= new Configuracion();
		echo $AjaxConfiguracion->CrearPagina($_pagina, $_descripcion);
	}

	function EliminarPagina($_pagina){
		$AjaxConfiguracion 	= new Configuracion();
		echo $AjaxConfiguracion->EliminarPagina($_pagina);
	}

	function ConsultarModulos($_pagina){
		$AjaxConfiguracion 	= new Configuracion();
		echo $AjaxConfiguracion->ConsultarModulos($_pagina, false);
	}

	function CrearModulo($_pagina, $_modulo, $_titulo, $_descripcion){
		$AjaxConfiguracion 	= new Configuracion();
		echo $AjaxConfiguracion->CrearModulo($_pagina, $_modulo, $_titulo, $_descripcion);
	}

	function EliminarModulos($_pagina, $_modulos){
		$AjaxConfiguracion 	= new Configuracion();
		echo $AjaxConfiguracion->EliminarModulos($_pagina, $_modulos);
	}


	function ConsultarAccesoPaginas($_usuario){
		$AjaxConfiguracion 	= new Configuracion();
		echo $AjaxConfiguracion->ConsultarAccesoPaginas($_usuario);
	}


	function ConsultarAccesos($_usuario_padre, $_usuario_hijo, $_pagina){
		$AjaxConfiguracion 	= new Configuracion();
		echo $AjaxConfiguracion->ConsultarAccesos($_usuario_padre, $_usuario_hijo, $_pagina);
	}


	function GuardarAccesosUsuario($_username, $_accesos){
		$AjaxConfiguracion 	= new Configuracion();
		echo $AjaxConfiguracion->GuardarAccesosUsuario($_username, $_accesos);
	}


	function GenerarClaveRSA($_username){
		$AjaxConfiguracion 	= new Configuracion();
		echo $AjaxConfiguracion->GenerarClaveRSA($_username);
	}


	function ConsultarClaveRSA(){
		$AjaxConfiguracion 	= new Configuracion();
		echo $AjaxConfiguracion->ConsultarClaveRSA();
	}

?>