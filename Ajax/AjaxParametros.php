<?php
	session_start();

	include_once(dirname(__FILE__)."/../Clases/ClassParametros.php");
	
	switch($_POST['Peticion']){

		case 'ConsultarAnomalias':  
			ConsultarAnomalias();	
			break;		

		case 'CrearAnomalia':  
			CrearAnomalia($_POST['Codigo'],$_POST['Descripcion'], $_POST['Residencial'], $_POST['No_Residencial'], $_POST['Toma_lectura'], $_POST['Mensaje'], $_POST['Foto']);   
			break;
		

		case 'EliminarAnomalia'	  		:  EliminarAnomalia($_POST['IdAnomalia']); 																															    break;		
		case 'ConsultarCiclos'    		:  ConsultarCiclos();                                                                                                                                                   break;
		case 'CrearCiclo'         		:  CrearCiclo($_POST['Codigo'],$_POST['Descripcion']);  																												break;	
		case 'EliminarCiclo'      		:  EliminarCiclo($_POST['IdCicloSeleccionado']);  																												        break;
		case 'ConsultarSiglas'    		:  ConsultarSiglas();  																												                                    break;
		case 'CrearSigla'         		:  CrearSigla($_POST['Codigo'],$_POST['Descripcion']);  																												break;
		case 'EliminarSigla'      		:  EliminarSigla($_POST['IdSiglaSeleccionado']);  																												        break;
		case 'ConsultarCriticas'  		:  ConsultarCriticas();                                                                                                                                                 break;
		case 'CrearCriticas'      		:  CrearCriticas($_POST['RangoMinimo'],$_POST['RangoMaximo'],$_POST['Descripcion']);  											                                        break;
		case 'EliminarCriticas'   		:  EliminarCriticas($_POST['IdCriticaSeleccionado']);  																												    break;
		case 'ConsultarDepartamentos'   :  ConsultarDepartamentos();                                                                                                                                            break;
		case 'CrearDepartamentos'       :  CrearDepartamentos($_POST['Codigo'],$_POST['Nombre']);  											                                                                    break;
		case 'EliminarDepartamentos'   	:  EliminarDepartamentos($_POST['IdDepartamentoSeleccionado']);  																										break;
		case 'ConsultarInspector'       :  ConsultarInspector();                                                                                                                                                break;
		case 'CrearInspector'           :  CrearInspector($_POST['Codigo'], $_POST['Nombre'], $_POST['Cedula'], $_POST['Celular'], $_POST['Tipo']);  											                                                    break;
		case 'EliminarInspector'   	    :  EliminarInspector($_POST['IdInspectorSeleccionado']);  																										        break;
		case 'ConsultarMunicipio'       :  ConsultarMunicipio();                                                                                                                                                break;
		case 'CrearMunicipio'           :  CrearMunicipio($_POST['Codigo'],$_POST['Nombre']);  											                                                                        break;
		case 'EliminarMunicipio'   	    :  EliminarMunicipio($_POST['IdMunicipioSeleccionado']);  																										        break;
		case 'ConsultarMensajes'		: ConsultarMensajes();																																					break;
		case 'CrearMensaje'				: CrearMensaje($_POST['Codigo'], $_POST['Descripcion'], $_POST['Macro']);																													break;
		case 'EliminarMensajes'			: EliminarMensajes($_POST['ListaMensajes']);																															break;
		case 'ConsultarBluetooth'		: ConsultarBluetooth();																																					break;
		case 'CrearBluetooth'			: CrearBluetooth($_POST['Codigo'],$_POST['Descripcion']);																												break;
		case 'EliminarBluetooth'		: EliminarBluetooth($_POST['ListaBluetooth']);																															break;
		case 'ConsultarCIIU' 			: ConsultarCIIU();																																						break;
		case 'EliminarCIIU' 			: EliminarCIIU($_POST['ListaCIIU']); 																																	break;
		case 'CrearCIIU' 				: CrearCIIU($_POST['Codigo'], $_POST['Descripcion']);																													break;
	
		case 'ConsultarFiltroCIIU' 		:  ConsultarFiltroCIIU();  																												                                break;
		case 'CrearFiltroCIIU'         	:  CrearFiltroCIIU($_POST['Codigo'],$_POST['Descripcion']);  																											break;
		case 'EliminarFiltroCIIU'      	:  EliminarFiltroCIIU($_POST['ListaFiltroCIIU']);  																												        break;
		


		/**********************/
		case 'ConsultarMedidoresNC': 
			ConsultarMedidoresNC();
			break;


		case 'CrearMedidorNC': 
			CrearMedidorNC($_POST['Marca'],$_POST['Descripcion']);
			break;
		

		case 'EliminarMedidoresNC': 
			EliminarMedidoresNC($_POST['ListaMarca']);
			break;		
	};


	function ConsultarAnomalias(){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->consultarAnomalias();
	}

	function CrearAnomalia($_codigo, $_descripcion, $_residencial, $_noresidencial, $_tomalectura, $_mensaje, $_foto){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->crearAnomalia($_codigo, $_descripcion, $_residencial, $_noresidencial, $_tomalectura, $_mensaje, $_foto);
	}

	function EliminarAnomalia($_idanomalia){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->eliminarAnomalia($_idanomalia);
	}

	function ConsultarCiclos(){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->consultarCiclos();
	}

	function CrearCiclo($_codigo, $_descripcion){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->crearCiclo($_codigo, $_descripcion);
	}

	function EliminarCiclo($_seleccionados){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->eliminarCiclo($_seleccionados);
	}

	function ConsultarSiglas(){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->consultarSiglas();
	}

	function CrearSigla($_codigo, $_descripcion){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->crearSigla($_codigo, $_descripcion);
	}

	function EliminarSigla($_seleccionados){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->eliminarSigla($_seleccionados);
	}

	function ConsultarCriticas(){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->consultarCriticas();
	}

	function CrearCriticas($_minimo,$_maximo, $_descripcion){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->crearCriticas($_minimo,$_maximo, $_descripcion);
	}

	function EliminarCriticas($_seleccionados){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->eliminarCriticas($_seleccionados);
	}

	


	function ConsultarMensajes(){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->consultarMensajes();
	}

	function CrearMensaje($_codigo, $_descripcion, $_macro){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->crearMensaje($_codigo, $_descripcion, $_macro);
	}

	function EliminarMensajes($_listaMensajes){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->eliminarMensajes($_listaMensajes);
	}


	function ConsultarBluetooth(){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->consultarBluetooth();
	}

	function CrearBluetooth($_codigo, $_descripcion){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->crearBluetooth($_codigo, $_descripcion);
	}

	function EliminarBluetooth($_listaBluetooth){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->eliminarBluetooth($_listaBluetooth);
	}


	function ConsultarDepartamentos(){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->consultarDepartamentos();
	}

	function CrearDepartamentos($_codigo,$_nombre){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->crearDepartamentos($_codigo,$_nombre);
	}

	function EliminarDepartamentos($_seleccionados){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->eliminarDepartamentos($_seleccionados);
	}

	function ConsultarInspector(){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->consultarInspector();
	}

	function CrearInspector($_codigo,$_nombre,$_cedula,$_celular,$_tipo){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->crearInspector($_codigo,$_nombre,$_cedula,$_celular,$_tipo);
	}

	function EliminarInspector($_seleccionados){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->eliminarInspector($_seleccionados);
	}

	function ConsultarMunicipio(){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->consultarMunicipio();
	}

	function CrearMunicipio($_codigo,$_nombre){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->crearMunicipio($_codigo,$_nombre);
	}

	function EliminarMunicipio($_seleccionados){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->eliminarMunicipio($_seleccionados);
	}



	function ConsultarCIIU(){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->consultarCIIU();
	}

	function EliminarCIIU($_seleccionados){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->eliminarCIIU($_seleccionados);
	}

	function CrearCIIU($_codigo,$_descripcion){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->crearCIIU($_codigo,$_descripcion);
	}




	/**Codigo para el filtro de las siglas CIIU**/
	function ConsultarFiltroCIIU(){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->consultarFiltroCIIU();
	}

	function CrearFiltroCIIU($_codigo, $_descripcion){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->crearFiltroCIIU($_codigo, $_descripcion);
	}

	function EliminarFiltroCIIU($_seleccionados){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->eliminarFiltroCIIU($_seleccionados);
	}


	function ConsultarMedidoresNC(){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->ConsultarMedidoresNC();
	}

	function CrearMedidorNC($_marca, $_descripcion){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->crearMedidorNC($_marca, $_descripcion);
	}

	function EliminarMedidoresNC($_listaMarca){
		$AjaxParametros 	= new ClassParametros();
		echo $AjaxParametros->eliminarMedidoresNC($_listaMarca);
	}



?>