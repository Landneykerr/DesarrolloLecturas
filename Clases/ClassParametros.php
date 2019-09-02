<?php
	session_start();
	include_once(dirname(__FILE__)."/../Clases/ClassPostgresBD.php");

	class ClassParametros{
		private $param_connect;
	   
		function ClassParametros(){
			$this->param_connect = new PostgresDB();
		}


		function getCiclos(){
			$this->param_connect->OpenPostgres();
			$Informacion =  $this->param_connect->PostgresSelectDistinctWhereOrder( "parametros.ciclos", 
																					"id_ciclo", 
																					"id_ciclo IS NOT NULL", 
																					"id_ciclo");
			return json_encode($this->param_connect->QueryToJson($Informacion,["id_ciclo"],[null],true));
			$this->param_connect->ClosePostgres();
		}

		function getMes(){
			$data[0]["nombre_mes"]  = "Enero";
			$data[0]["numero_mes"]  = "1";
			$data[1]["nombre_mes"]  = "Febrero";
			$data[1]["numero_mes"]  = "2";
			$data[2]["nombre_mes"]  = "Marzo";
			$data[2]["numero_mes"]  = "3";
			$data[3]["nombre_mes"]  = "Abril";
			$data[3]["numero_mes"]  = "4";
			$data[4]["nombre_mes"]  = "Mayo";
			$data[4]["numero_mes"]  = "5";
			$data[5]["nombre_mes"]  = "Junio";
			$data[5]["numero_mes"]  = "6";
			$data[6]["nombre_mes"]  = "Julio";
			$data[6]["numero_mes"]  = "7";
			$data[7]["nombre_mes"]  = "Agosto";
			$data[7]["numero_mes"]  = "8";
			$data[8]["nombre_mes"]  = "Septiembre";
			$data[8]["numero_mes"]  = "9";
			$data[9]["nombre_mes"]  = "Octubre";
			$data[9]["numero_mes"]  = "10";
			$data[10]["nombre_mes"]  = "Noviembre";
			$data[10]["numero_mes"]  = "11";
			$data[11]["nombre_mes"]  = "Diciembre";
			$data[11]["numero_mes"]  = "12";
			return json_encode($data);
		}

	
		function getAnno(){
			$data[0]['anno'] = "...";
			$j=1;
			for($i=date('Y'); $i<=date('Y')+1;$i++){
				$data[$j]['anno'] = $i;
				$j++;
			}
			return json_encode($data);
		}


		function getInspectoresActivos($_tipo){
			$this->param_connect->OpenPostgres();
			$Informacion =  $this->param_connect->PostgresSelectDistinctWhereOrder( "parametros.inspectores", 
																					"id_inspector,nombre", 
																					"estado IS TRUE", 
																					"nombre");
			return json_encode($this->param_connect->QueryToJson($Informacion,["id_inspector","nombre"],[null,null],true));
			$this->param_connect->ClosePostgres();
		}


		function getTipoInspector(){
            $this->param_connect->OpenPostgres();
            $Informacion =  $this->param_connect->PostgresSelectDistinctWhereOrder( "parametros.tipo_inspector", 
                                                                                    "id_serial,descripcion", 
                                                                                    "id_serial IS NOT NULL", 
                                                                                    "id_serial");
            return json_encode($this->param_connect->QueryToJson($Informacion,["id_serial","descripcion"],[null,null],true));
            $this->param_connect->ClosePostgres();
        }


		function consultarAnomalias(){
			$this->param_connect->OpenPostgres();
			$Informacion =  $this->param_connect->PostgresSelectDistinctWhereOrder( "parametros.vista_anomalias_decodificado", 
																					"id_anomalia,descripcion,aplica_residencial,aplica_no_residencial,lectura,mensaje,foto", 
																					"id_anomalia is not null", 
																					"id_anomalia");
			return json_encode($this->param_connect->QueryToJson($Informacion,["id_anomalia","descripcion","aplica_residencial","aplica_no_residencial","lectura","mensaje","foto"],[null,null,null,null,null,null,null],false));
			$this->param_connect->ClosePostgres();
		}

		function crearAnomalia($_codigo, $_descripcion, $_residencial, $_noresidencial, $_tomalectura, $_mensaje, $_foto){
			$this->param_connect->OpenPostgres();
			return $this->param_connect->PostgresInsertIntoValues("parametros.anomalias",
																"id_anomalia,descripcion,aplica_residencial,aplica_no_residencial,lectura,mensaje,foto",
																"'".$_codigo."','".$_descripcion."','".$_residencial."','".$_noresidencial."','".$_tomalectura."','".$_mensaje."', '".$_foto."'");
			$this->param_connect->ClosePostgres();
		}
		
		function crearCiclo($_codigo, $_descripcion){
			$this->param_connect->OpenPostgres();
			return $this->param_connect->PostgresInsertIntoValues("parametros.ciclos",
																"id_ciclo,descripcion",
																"'".$_codigo."','".$_descripcion."'");
			$this->param_connect->ClosePostgres();
		}
		
		function eliminarAnomalia($_idanomalia){
			$this->param_connect->OpenPostgres();
			for($j=0;$j<sizeof($_idanomalia['ListaSeleccionadas']);$j++){
				$this->param_connect->PostgresEliminarRegistro("parametros.anomalias", "id_anomalia='".$_idanomalia['ListaSeleccionadas'][$j]["Idanomalia"]."' ");
			}
			$this->param_connect->ClosePostgres();
			return $this->consultarAnomalias();          
		}

		function eliminarCiclo($_seleccionados){
			$this->param_connect->OpenPostgres();
			for($j=0;$j<sizeof($_seleccionados['ListaSeleccionadas']);$j++){
				$this->param_connect->PostgresEliminarRegistro("parametros.ciclos", "id_ciclo='".$_seleccionados['ListaSeleccionadas'][$j]["IdCiclo"]."' ");
			}
			$this->param_connect->ClosePostgres();
			return $this->consultarCiclos();          
		}

		function consultarCiclos(){
			$this->param_connect->OpenPostgres();
			$Informacion =  $this->param_connect->PostgresSelectDistinctWhereOrder( "parametros.ciclos", 
																					"id_serial,id_ciclo,descripcion", 
																					"id_serial is not null", 
																					"id_ciclo");
			return json_encode($this->param_connect->QueryToJson($Informacion,["id_ciclo","descripcion"],[null,null],false));
			$this->param_connect->ClosePostgres();
		}

		function consultarSiglas(){
			$this->param_connect->OpenPostgres();
			$Informacion =  $this->param_connect->PostgresSelectDistinctWhereOrder( "parametros.filtro_macro", 
																					"id_serial,sigla,descripcion", 
																					"id_serial is not null", 
																					"id_serial");
			return json_encode($this->param_connect->QueryToJson($Informacion,["sigla","descripcion"],[null,null,null],false));
			$this->param_connect->ClosePostgres();
		}


		function consultarMensajes(){
			$this->param_connect->OpenPostgres();
			$Informacion =  $this->param_connect->PostgresSelectDistinctWhereOrder( "parametros.vista_mensajes_decodificado", 
																					"codigo,descripcion,macro", 
																					"id_serial is not null", 
																					"codigo");
			return json_encode($this->param_connect->QueryToJson($Informacion,["codigo","descripcion","macro"],[null,null,null],false));
			$this->param_connect->ClosePostgres();
		}

		function crearMensaje($_codigo,$_descripcion,$_macro){
			$this->param_connect->OpenPostgres();
			return $this->param_connect->PostgresInsertIntoValues("parametros.codigos_mensajes",
																"codigo,descripcion,macro",
																"'".$_codigo."','".$_descripcion."',".$_macro);
			$this->param_connect->ClosePostgres();
		}


		function eliminarMensajes($listaMensajes){
			$this->param_connect->OpenPostgres();
			for($j=0;$j<sizeof($listaMensajes['ListaMensajes']);$j++){
				$this->param_connect->PostgresEliminarRegistro("parametros.codigos_mensajes", "codigo='".$listaMensajes['ListaMensajes'][$j]["codigo"]."'");
			}
			$this->param_connect->ClosePostgres();
			return $this->consultarMensajes();         
		}



		function consultarBluetooth(){
			$this->param_connect->OpenPostgres();
			$Informacion =  $this->param_connect->PostgresSelectDistinctWhereOrder( "parametros.bluetooth", 
																					"code_bluetooth as codigo,descripcion, pass_administrador", 
																					"id_serial is not null", 
																					"codigo");
			return json_encode($this->param_connect->QueryToJson($Informacion,["codigo","descripcion","pass_administrador"],[null,null, null],false));
			$this->param_connect->ClosePostgres();
		}

		function crearBluetooth($_codigo,$_descripcion){
			$this->param_connect->OpenPostgres();
			return $this->param_connect->PostgresInsertIntoValues(  "parametros.bluetooth",
																	"code_bluetooth,descripcion",
																	"'".$_codigo."','".$_descripcion."'");
			$this->param_connect->ClosePostgres();
		}

		function eliminarBluetooth($listaBluetooth){
			$this->param_connect->OpenPostgres();
			for($j=0;$j<sizeof($listaBluetooth['ListaBluetooth']);$j++){
				$this->param_connect->PostgresEliminarRegistro("parametros.bluetooth", "code_bluetooth='".$listaBluetooth['ListaBluetooth'][$j]["codigo"]."'");
			}
			$this->param_connect->ClosePostgres();
			return $this->consultarBluetooth();         
		}



		function crearSigla($_codigo, $_descripcion){
			$this->param_connect->OpenPostgres();
			return $this->param_connect->PostgresInsertIntoValues("parametros.filtro_macro",
																"sigla,descripcion",
																"'".$_codigo."','".$_descripcion."'");
			$this->param_connect->ClosePostgres();
		}

		function eliminarSigla($_seleccionados){
			$this->param_connect->OpenPostgres();
			for($j=0;$j<sizeof($_seleccionados['ListaSeleccionadas']);$j++){
				$this->param_connect->PostgresEliminarRegistro("parametros.filtro_macro", "sigla='".$_seleccionados['ListaSeleccionadas'][$j]["IdSigla"]."' ");
			}
			$this->param_connect->ClosePostgres();
			return $this->consultarSiglas();          
		}


		function getCritica(){
			$this->param_connect->OpenPostgres();
			$Informacion =  $this->param_connect->PostgresSelectDistinctWhereOrder( "parametros.critica", 
																					"id_serial,rango_minimo,rango_maximo,descripcion", 
																					"id_serial is not null", 
																					"rango_minimo");
			$j=0;
			while($rta = pg_fetch_assoc($Informacion)){
				$data[$j]['descripcion'] = $rta['descripcion'];
				$j++;
			}
			$this->param_connect->ClosePostgres();
			return json_encode($data);
		}

		function getAnomalia($_combo, $_orden){
			$this->param_connect->OpenPostgres();
			$Informacion =  $this->param_connect->PostgresSelectDistinctWhereOrder( "parametros.anomalias", 
																					"id_anomalia,descripcion", 
																					"id_anomalia is not null", 
																					$_orden);
			$j=0;
			if($_combo){
				$data[$j]['id_anomalia'] = "-1";
				$data[$j]['descripcion'] = "...";
				$j++;
			}	
			while($rta = pg_fetch_assoc($Informacion)){
				$data[$j]['id_anomalia'] = $rta['id_anomalia'];
				$data[$j]['descripcion'] = $rta['descripcion'];
				$j++;
			}
			$this->param_connect->ClosePostgres();
			return json_encode($data);
		}

		function consultarCriticas(){
			$this->param_connect->OpenPostgres();
			$Informacion =  $this->param_connect->PostgresSelectDistinctWhereOrder( "parametros.critica", 
																					"id_serial,rango_minimo,rango_maximo,descripcion", 
																					"id_serial is not null", 
																					"id_serial");
			return json_encode($this->param_connect->QueryToJson($Informacion,["id_serial","rango_minimo","rango_maximo","descripcion"],[null,null,null,null],false));
			$this->param_connect->ClosePostgres();
		}

		function crearCriticas($_minimo,$_maximo, $_descripcion){
			$this->param_connect->OpenPostgres();
			return $this->param_connect->PostgresInsertIntoValues("parametros.critica",
																"rango_minimo,rango_maximo,descripcion",
																"'".$_minimo."','".$_maximo."','".$_descripcion."'");
			$this->param_connect->ClosePostgres();
		}

		function eliminarCriticas($_seleccionados){
			$this->param_connect->OpenPostgres();
			for($j=0;$j<sizeof($_seleccionados['ListaSeleccionadas']);$j++){
				$this->param_connect->PostgresEliminarRegistro("parametros.critica", "id_serial='".$_seleccionados['ListaSeleccionadas'][$j]["IdCritica"]."' ");
			}
			$this->param_connect->ClosePostgres();
			return $this->consultarCriticas();          
		}

		function consultarDepartamentos(){
			$this->param_connect->OpenPostgres();
			$Informacion =  $this->param_connect->PostgresSelectDistinctWhereOrder( "parametros.departamentos", 
																					"id_serial,id_departamento,nombre_departamento", 
																					"id_serial is not null", 
																					"nombre_departamento");
			return json_encode($this->param_connect->QueryToJson($Informacion,["id_departamento","nombre_departamento"],[null,null],false));
			$this->param_connect->ClosePostgres();
		}

		function crearDepartamentos($_codigo,$_nombre){
			$this->param_connect->OpenPostgres();
			return $this->param_connect->PostgresInsertIntoValues("parametros.departamentos",
																"id_departamento,nombre_departamento",
																"'".$_codigo."','".$_nombre."'");
			$this->param_connect->ClosePostgres();
		}

		function eliminarDepartamentos($_seleccionados){
			$this->param_connect->OpenPostgres();
			for($j=0;$j<sizeof($_seleccionados['ListaSeleccionadas']);$j++){
				$this->param_connect->PostgresEliminarRegistro("parametros.departamentos", "id_departamento='".$_seleccionados['ListaSeleccionadas'][$j]["IdDepartamento"]."' ");
			}
			$this->param_connect->ClosePostgres();
			return $this->consultarDepartamentos();          
		}

		function consultarInspector(){
			$this->param_connect->OpenPostgres();
			$Informacion =  $this->param_connect->PostgresSelectJoinWhereOrder( "parametros.inspectores AS a", 
																				"a.id_inspector,a.cedula,a.nombre,a.celular,b.descripcion AS tipo_inspector", 
																				"parametros.tipo_inspector AS b",
																				"a.tipo_inspector = b.id_serial",
																				"estado='TRUE'", 
																				"nombre");
			return json_encode($this->param_connect->QueryToJson($Informacion,["id_inspector","cedula","nombre","celular","tipo_inspector"],[null,null,null,null,null],false));
			$this->param_connect->ClosePostgres();
		}

		function crearInspector($_codigo,$_nombre,$_cedula,$_celular,$_tipo){
			$this->param_connect->OpenPostgres();
			if($this->param_connect->PostgresExisteRegistro("parametros.inspectores", "id_inspector=".$_codigo." AND cedula = '".$_cedula."'")){
				$resultado = $this->param_connect->PostgresUpdateValues("parametros.inspectores", "estado='TRUE', nombre = '".$_nombre."', tipo_inspector=".$_tipo.", celular=".$_celular, "id_inspector=".$_codigo);
			}else if($this->param_connect->PostgresExisteRegistro("parametros.inspectores", "id_inspector=".$_codigo." AND cedula <> '".$_cedula."'")){
				$resultado = -1;
			}else{	
				$resultado =  $this->param_connect->PostgresInsertIntoValues("parametros.inspectores",
																"id_inspector,nombre,cedula,celular,tipo_inspector",
																"'".$_codigo."','".$_nombre."','".$_cedula."',".$_celular.",".$_tipo);
			}
			$this->param_connect->ClosePostgres();
			return $resultado;
		}

		function eliminarInspector($_seleccionados){
			$this->param_connect->OpenPostgres();
			for($j=0;$j<sizeof($_seleccionados['ListaSeleccionadas']);$j++){
				$this->param_connect->PostgresUpdateValues("parametros.inspectores","estado='FALSE'" ,"id_inspector=".$_seleccionados['ListaSeleccionadas'][$j]["IdInspector"]);
			}
			$this->param_connect->ClosePostgres();
			return $this->consultarInspector();          
		}

		function consultarMunicipio(){
			$this->param_connect->OpenPostgres();
			$Informacion =  $this->param_connect->PostgresSelectDistinctWhereOrder( "parametros.municipios", 
																					"id_serial,id_municipio,nombre_municipio", 
																					"id_serial is not null", 
																					"nombre_municipio");
			return json_encode($this->param_connect->QueryToJson($Informacion,["id_municipio","nombre_municipio"],[null,null],false));
			$this->param_connect->ClosePostgres();
		}

		function crearMunicipio($_codigo,$_nombre){
			$this->param_connect->OpenPostgres();
			return $this->param_connect->PostgresInsertIntoValues("parametros.municipios",
																"id_municipio,nombre_municipio",
																"'".$_codigo."','".$_nombre."'");
			$this->param_connect->ClosePostgres();
		}

		function eliminarMunicipio($_seleccionados){
			$this->param_connect->OpenPostgres();
			for($j=0;$j<sizeof($_seleccionados['ListaSeleccionadas']);$j++){
				$this->param_connect->PostgresEliminarRegistro(" parametros.municipios", "id_municipio=".$_seleccionados['ListaSeleccionadas'][$j]["IdMunicipio"]);
			}
			$this->param_connect->ClosePostgres();
			return $this->consultarMunicipio();          
		}



		function consultarCIIU(){
			$this->param_connect->OpenPostgres();
			$Informacion =  $this->param_connect->PostgresSelectDistinctWhereOrder( "parametros.tipo_usos", 
																					"id_uso,descripcion", 
																					"id_serial is not null", 
																					"id_uso");
			return $this->param_connect->QueryToJson($Informacion,["id_uso","descripcion"],[null,null],false);
			$this->param_connect->ClosePostgres();
		}


		function eliminarCIIU($_seleccionados){
			$this->param_connect->OpenPostgres();
			for($j=0;$j<sizeof($_seleccionados['ListaCIIU']);$j++){
				$this->param_connect->PostgresEliminarRegistro(" parametros.tipo_usos", "id_uso='".$_seleccionados['ListaCIIU'][$j]["codigo"]."' ");
			}
			$this->param_connect->ClosePostgres();
			return $this->consultarCIIU();          
		}
		

		function crearCIIU($_codigo,$_descripcion){
			$this->param_connect->OpenPostgres();
			return $this->param_connect->PostgresInsertIntoValues(  "parametros.tipo_usos",
																	"id_uso,descripcion",
																	"'".$_codigo."','".$_descripcion."'");
			$this->param_connect->ClosePostgres();
		}





		function consultarFiltroCIIU(){
			$this->param_connect->OpenPostgres();
			$Informacion =  $this->param_connect->PostgresSelectDistinctWhereOrder( "parametros.filtro_ciiu", 
																					"id_serial,sigla,descripcion", 
																					"id_serial is not null", 
																					"sigla");
			return $this->param_connect->QueryToJson($Informacion,["id_serial","sigla","descripcion"],[null,null],false);
			$this->param_connect->ClosePostgres();
		}

		function crearFiltroCIIU($_codigo, $_descripcion){
			$this->param_connect->OpenPostgres();   
			return $this->param_connect->PostgresInsertIntoValues(  "parametros.filtro_ciiu",
																	"sigla,descripcion",
																	"'".$_codigo."','".$_descripcion."'");
			$this->param_connect->ClosePostgres();
		}

		function eliminarFiltroCIIU($_seleccionados){
			$this->param_connect->OpenPostgres();
			for($j=0;$j<sizeof($_seleccionados['ListaFiltroCIIU']);$j++){
				$this->param_connect->PostgresEliminarRegistro("parametros.filtro_ciiu", "id_serial='".$_seleccionados['ListaFiltroCIIU'][$j]["codigo"]."' ");
			}
			$this->param_connect->ClosePostgres();
			return $this->consultarFiltroCIIU();          
		}


		function consultarMedidoresNC(){
			$this->param_connect->OpenPostgres();
			$Informacion =  $this->param_connect->PostgresSelectDistinctWhereOrder( "parametros.medidores_nc", 
																					"id_serial, marca, descripcion", 
																					"id_serial is not null", 
																					"marca");
			return json_encode($this->param_connect->QueryToJson($Informacion,["id_serial","marca","descripcion"],[null, null, null],false));
			$this->param_connect->ClosePostgres();
		}


		function crearMedidorNC($_marca,$_descripcion){
			$this->param_connect->OpenPostgres();
			return $this->param_connect->PostgresInsertIntoValues(  "parametros.medidores_nc",
																	"marca, descripcion",
																	"'".$_marca."','".$_descripcion."'");
			$this->param_connect->ClosePostgres();
		}


		function eliminarMedidoresNC($listaMarca){
			$this->param_connect->OpenPostgres();
			for($j=0;$j<sizeof($listaMarca['ListaMarcaNC']);$j++){
				$this->param_connect->PostgresEliminarRegistro("parametros.medidores_nc", 
					"id_serial=".$listaMarca['ListaMarcaNC'][$j]["id"]);
			}
			$this->param_connect->ClosePostgres();
			return $this->consultarMedidoresNC();         
		}

	}
?>

