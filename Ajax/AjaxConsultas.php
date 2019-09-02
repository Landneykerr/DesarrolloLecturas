<?php
	session_start();

	include_once(dirname(__FILE__)."/../Clases/ClassConsultas.php");
	
	switch($_POST['Peticion']){
		case 'ConsultaPeriodo':  				ConsultaPeriodo($_POST['Mes'], $_POST['Anno']);									break;
		case 'ConsultaRutasCiclo': 				ConsultaRutasCiclo($_POST['Mes'], $_POST['Anno'], $_POST['Ciclos']);			break;
		case 'ConsultaRutasEstado': 			ConsultaRutasEstado($_POST['Mes'], $_POST['Anno'], $_POST['Rutas']);			break;
		case 'ConsultaLecturasTomadas':			ConsultaLecturasTomadas($_POST['Mes'], $_POST['Anno'], $_POST['Inspectores']);	break;
		case 'ConsultaErroresImpresion': 		ConsultaErroresImpresion($_POST['Fecha']);										break;

		case 'ConsultarResumenCiclos': 			ConsultarResumenCiclos($_POST['Mes'], $_POST['Anno']);							break;
		case 'ConsultaCorrecciones': 			ConsultaCorrecciones($_POST['Mes'], $_POST['Anno'], $_POST['Ciclo']); 			break;


		case 'ConsultaPeriodoNoLecturas': 		ConsultaPeriodoNoLecturas($_POST['Mes'], $_POST['Anno']);						break;

		case 'TerminarRutasNoLecturas': 		TerminarRutasNoLecturas($_POST['IdNoLecturas']);								break;




		case 'ConsultaGeneral':  				ConsultaGeneral($_POST['Mes'], $_POST['Anno']);																	break;		
		case 'ConsultaCliente':  				ConsultaCliente($_POST['Seleccion'], $_POST['Dato'] );																				break;	
		
		case 'ConsultaCiclosLecturas': 			ConsultaCiclosLecturas($_POST['Mes'], $_POST['Anno'], $_POST['Estado']);															break;
		case 'ConsultaMunicipiosLecturas': 		ConsultaMunicipiosLecturas($_POST['Mes'], $_POST['Anno'], $_POST['Ciclo'], $_POST['Estado']);										break;
		case 'ConsultaRutasLecturas': 			ConsultaRutasLecturas($_POST['Mes'], $_POST['Anno'], $_POST['Ciclo'], $_POST['Municipio'], $_POST['Estado']);						break;
		
		case 'ConsultaDetalleTomadas': 			ConsultaDetalleTomadas($_POST['Mes'], $_POST['Anno'], $_POST['Ciclo'], $_POST['Municipio'], $_POST['Ruta']);						break;
		case 'ConsultaPendientesClientes': 		ConsultaPendientesClientes($_POST['Mes'], $_POST['Anno'], $_POST['Ciclo'], $_POST['Municipio'], $_POST['Ruta']);					break;		
		case 'ConsultaGeneralInspector': 		ConsultaGeneralInspector($_POST['Mes'], $_POST['Anno'], $_POST['Ciclo']); 
		case 'ConsultaConsolidado': 			ConsultaConsolidado($_POST['Mes'], $_POST['Anno'], $_POST['Ciclo']); 																break;
		case 'ConsultaCorreccion': 				ConsultaCorreccion($_POST['Mes'], $_POST['Anno'], $_POST['TipoBusqueda'], $_POST['DatoBusqueda']); 									break;
		
		case 'ConsultaCronologicoTomadas': 		ConsultaCronologicoTomadas($_POST['Mes'], $_POST['Anno'], $_POST['Ciclo'], $_POST['Municipio'], $_POST['Ruta']);					break;
		case 'ConsultaRuta': 					ConsultaRuta($_POST['Mes'], $_POST['Anno'], $_POST['Ciclo'], $_POST['Municipio'], $_POST['Ruta']);									break;
		case 'ConsultaRutaCuentas': 			ConsultaRutaCuentas($_POST['Mes'], $_POST['Anno'], $_POST['Ciclo'], $_POST['Municipio'], $_POST['Ruta']);							break;
		case 'ConsultaAnomalias': 			    ConsultaAnomalias($_POST['Mes'], $_POST['Anno'], $_POST['Ciclo'], $_POST['Municipio'], $_POST['Ruta']);								break;
		case 'ConsultaRutaInspector': 			ConsultaRutaInspector($_POST['Mes'], $_POST['Anno'], $_POST['Ciclo'], $_POST['Municipio'], $_POST['Ruta'], $_POST['Inspector']);	break;
		case 'ConsultaDetalleRechazadas': 		ConsultaDetalleRechazadas($_POST['Mes'], $_POST['Anno'], $_POST['Ciclo'], $_POST['Municipio'], $_POST['Ruta']);						break;

		case 'ConsultaPosterior':  				ConsultaPosterior($_POST['CuentaPosterior']);																						break;
		
	};


	function TerminarRutasNoLecturas($_id){
		$AjaxConsultas 	= new Consultas();
		echo $AjaxConsultas->TerminarRutasNoLecturas($_id);
	}

	function ConsultaPeriodo($_mes, $_anno){
		$AjaxConsultas 	= new Consultas();
		echo $AjaxConsultas->ConsultaPeriodo($_mes, $_anno);
	}


	function ConsultaRutasCiclo($_mes, $_anno, $_ciclos){
		$AjaxConsultas 	= new Consultas();
		echo $AjaxConsultas->ConsultaRutasCiclo($_mes, $_anno, $_ciclos);
	}


	function ConsultaRutasEstado($_mes, $_anno, $_rutas){
		$AjaxConsultas 	= new Consultas();
		echo $AjaxConsultas->ConsultaRutasEstado($_mes, $_anno, $_rutas);
	}


	function ConsultaLecturasTomadas($_mes, $_anno, $_inspectores){
		$AjaxConsultas 	= new Consultas();
		echo $AjaxConsultas->ConsultaLecturasTomadas($_mes, $_anno, $_inspectores);
	}


	function ConsultarResumenCiclos($_mes, $_anno){
		$AjaxConsultas 	= new Consultas();
		echo $AjaxConsultas->ConsultarResumenCiclos($_mes, $_anno);
	}


	function ConsultaErroresImpresion($_fecha){
		$AjaxConsultas 	= new Consultas();
		echo $AjaxConsultas->ConsultaErroresImpresion($_fecha);
	}

	function ConsultaCorrecciones($_mes, $_anno, $_ciclo){
		$AjaxConsultas 	= new Consultas();
		echo $AjaxConsultas->ConsultaCorrecciones($_mes, $_anno, $_ciclo);
	}


	function ConsultaPeriodoNoLecturas($_mes, $_anno){
		$AjaxConsultas 	= new Consultas();
		echo $AjaxConsultas->ConsultaPeriodoNoLecturas($_mes, $_anno);
	}


	function ConsultaGeneral($_mes, $_anno, $_ciclo){
		$AjaxConsultas 	= new Consultas();
		echo $AjaxConsultas->consultaGeneral($_mes, $_anno, $_ciclo);
	}


	function ConsultaCliente($_seleccion, $_dato){
		$AjaxConsultas 	= new Consultas();
		echo $AjaxConsultas->consultaCliente($_seleccion, $_dato);
	}


	function ConsultaCorreccion($_mes, $_anno, $_tipo, $_dato){
		$AjaxConsultas 	= new Consultas();
		echo $AjaxConsultas->ConsultaCorreccion($_mes, $_anno, $_tipo, $_dato);
	}


	function ConsultaCiclosLecturas($_mes, $_anno, $_estado){
		$AjaxConsultas 	= new Consultas();
		echo $AjaxConsultas->ConsultaCiclosLecturas($_mes, $_anno, $_estado);
	}


	function ConsultaMunicipiosLecturas($_mes, $_anno, $_ciclo, $_estado){
		$AjaxConsultas 	= new Consultas();
		echo $AjaxConsultas->ConsultaMunicipiosLecturas($_mes, $_anno, $_ciclo, $_estado);
	}


	function ConsultaRutasLecturas($_mes, $_anno, $_ciclo, $_municipio, $_estado){
		$AjaxConsultas 	= new Consultas();
		echo $AjaxConsultas->ConsultaRutasLecturas($_mes, $_anno, $_ciclo, $_municipio, $_estado);
	}


	function ConsultaPosterior($_cuenta){
		$AjaxConsultas 	= new Consultas();
		$datos = $AjaxConsultas->CuentaPosterior($_cuenta);
	}

/**
	Funciones Para consultar informacion que se va a representar en el mapa
	**/
	function ConsultaRuta($mes, $anno, $ciclo, $municipio, $ruta){ 
		$AjaxConsultas 	= new Consultas();
		echo $AjaxConsultas->consultarRutaCuenta($mes, $anno, $ciclo, $municipio, $ruta);
	}

	function ConsultaRutaCuentas($mes, $anno, $ciclo, $municipio, $ruta){ 
		$AjaxConsultas 	= new Consultas();
		echo $AjaxConsultas->consultarRutaCuentaDatos($mes, $anno, $ciclo, $municipio, $ruta);
	}

	function ConsultaRutaInspector($mes, $anno, $ciclo, $municipio, $ruta, $insp){ 
		$AjaxConsultas 	= new Consultas();
		echo $AjaxConsultas->consultarRutaInspector($mes, $anno, $ciclo, $municipio, $ruta, $insp);
	}

	function ConsultaAnomalias($mes, $anno, $ciclo, $municipio, $ruta, $insp){ 
		$AjaxConsultas 	= new Consultas();
		echo $AjaxConsultas->consultarRutaAnomalias($mes, $anno, $ciclo, $municipio, $ruta);
	}
	/**
	END FUCTION;
	**/
	
	function ConsultaDetalleTomadas($_mes, $_anno, $_ciclo, $_municipio, $_ruta){
		$AjaxConsultas 	= new Consultas();
		echo $AjaxConsultas->ConsultaDetalleTomadas($_mes, $_anno, $_ciclo, $_municipio, $_ruta);
	}


	function ConsultaCronologicoTomadas($_mes, $_anno, $_ciclo, $_municipio, $_ruta){
		$AjaxConsultas 	= new Consultas();
		echo $AjaxConsultas->ConsultaCronologicoTomadas($_mes, $_anno, $_ciclo, $_municipio, $_ruta);
	}

	function ConsultaPendientesClientes($_mes, $_anno, $_ciclo, $_municipio, $_ruta){
		$AjaxConsultas 	= new Consultas();
		echo $AjaxConsultas->ConsultaPendientesClientes($_mes, $_anno, $_ciclo, $_municipio, $_ruta);
	}

	function ConsultaGeneralInspector($_mes, $_anno, $_ciclo){
		$AjaxConsultas 	= new Consultas();
		echo $AjaxConsultas->ConsultaGeneralInspector($_mes, $_anno, $_ciclo);
	}

	function ConsultaConsolidado($_mes, $_anno, $_ciclo){
		$AjaxConsultas 	= new Consultas();
		echo $AjaxConsultas->ConsultaConsolidado($_mes, $_anno, $_ciclo);
	}

	function ConsultaDetalleRechazadas($_mes, $_anno, $_ciclo, $_municipio, $_ruta){
		$AjaxConsultas 	= new Consultas();
		echo $AjaxConsultas->ConsultaDetalleRechazadas($_mes, $_anno, $_ciclo, $_municipio, $_ruta);
	}
	

?>