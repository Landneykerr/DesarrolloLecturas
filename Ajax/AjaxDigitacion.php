<?php
	session_start();

	include_once(dirname(__FILE__)."/../Clases/ClassDigitacion.php");
	
	switch($_POST['Peticion']){
		case 'ConsultaCorreccion': 		
			ConsultaCorreccion($_POST['Mes'], $_POST['Anno'], $_POST['TipoBusqueda'], $_POST['DatoBusqueda']); 										
			break;

		case 'GuardarCorreccion': 		
			GuardarCorreccion($_POST['IdSerial'], $_POST['Lectura'], $_POST['Anomalia'], $_POST['Mensaje'], $_POST['Foto'], $_SESSION['UserName'] );
			break;

		case 'ConsultarRecuperacion': 	
			ConsultarRecuperacion($_POST['Mes'], $_POST['Anno'], $_POST['Tipo']); 																	
			break;

		case 'RechazarRecuperacion': 	
			RechazarRecuperacion($_POST['Id'], $_SESSION['UserName'] );																						
			break;

		case 'ProcesarRecuperacion': 	
			ProcesarRecuperacion($_POST['Id'], $_SESSION['UserName'] );																						
			break;

		case 'CargarArchSupervision': 	
			CargarArchSupervision();																												
			break;

		case 'ConsultarSupervision': 	
			ConsultarSupervision($_POST['Fecha']);																									
			break;

		case 'ConsultarInfCuentaLectura':
			ConsultarInfCuentaLectura($_POST['Mes'], $_POST['Anno'], $_POST['Tipo'], $_POST['Cuenta']);
			break;

		case 'ConsultarDescripcionCritica':
			ConsultarDescripcionCritica($_POST['Critica1'], $_POST['Critica2'], $_POST['Critica3']);
			break;

		case 'GuardarDatosLectura':
			GuardarDatosLectura($_POST['IdProgramacion'], $_POST['TipoLectura'], $_POST['IdSerial1'], $_POST['Lectura1'], $_POST['Critica1'], $_POST['IdSerial2'], $_POST['Lectura2'], $_POST['Critica2'], $_POST['IdSerial3'], $_POST['Lectura3'], $_POST['Critica3'], $_POST['Anomalia'], $_POST['Mensaje'], $_POST['Inspector']);
			break;	
	};


	function GuardarDatosLectura($_idProgramacion, $_tipoLectura, $_idSerial1, $_lectura1, $_critica1, $_idSerial2, $_lectura2, $_critica2, $_idSerial3, $_lectura3, $_critica3, $_anomalia, $_mensaje, $_inspector){

		$AjaxDigitacion = new ClassDigitacion();
		echo $AjaxDigitacion->GuardarDatosLectura($_idProgramacion, $_tipoLectura, $_idSerial1, $_lectura1, $_critica1, $_idSerial2, $_lectura2, $_critica2, $_idSerial3, $_lectura3, $_critica3, $_anomalia, $_mensaje, $_inspector);

	}


	function ConsultarDescripcionCritica($_critica1, $_critica2, $_critica3){
		$AjaxDigitacion = new ClassDigitacion();
		echo $AjaxDigitacion->ConsultarDescripcionCritica($_critica1, $_critica2, $_critica3);
	}
	

	function ConsultarInfCuentaLectura($_mes, $_anno, $_tipo, $_cuenta){
		$AjaxDigitacion = new ClassDigitacion();
		echo $AjaxDigitacion->ConsultarInfCuentaLectura($_mes, $_anno, $_tipo, $_cuenta);
	}


	function ConsultarSupervision($_fecha){
		$AjaxDigitacion 	= new ClassDigitacion();
		echo $AjaxDigitacion->ConsultarSupervision($_fecha);
	}


	function ConsultaCorreccion($_mes, $_anno, $_tipo, $_dato){
		$AjaxDigitacion 	= new ClassDigitacion();
		echo $AjaxDigitacion->ConsultaCorreccion($_mes, $_anno, $_tipo, $_dato);
	}


	function GuardarCorreccion($_id_serial, $_lectura, $_anomalia, $_mensaje, $_foto, $_username){
		$AjaxDigitacion 	= new ClassDigitacion();
		echo $AjaxDigitacion->GuardarCorreccion($_id_serial, $_lectura, $_anomalia, $_mensaje, $_foto, $_username);
	}


	function ConsultarRecuperacion($_mes, $_anno, $_tipo){
		$AjaxDigitacion 	= new ClassDigitacion();
		echo $AjaxDigitacion->ConsultarRecuperacion($_mes, $_anno, $_tipo);
	}


	function RechazarRecuperacion($_id, $_username){
		$AjaxDigitacion 	= new ClassDigitacion();
		$_estado = 'R';
		echo $AjaxDigitacion->ProcesarRecuperacion($_id, $_estado,$_username);
	}


	function ProcesarRecuperacion($_id, $_username){
		$AjaxDigitacion 	= new ClassDigitacion();
		$_estado = 'V';
		echo $AjaxDigitacion->ProcesarRecuperacion($_id, $_estado,$_username);
	}

	
?>