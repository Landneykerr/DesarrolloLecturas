/************Llamado al archivo que contiene las funciones principales para el manejo de las tablas*****************/
document.write("<script type='text/javascript' src='../FrameWork/dataTables/js/jquery.dataTables.min.js'></script>");


function MostrarTabla(Tabla,datosjson){
	Tabla.fnClearTable();
	if(datosjson.length>0){
		Tabla.fnAddData(datosjson);
	}	
}

/********************************************************************************************************************
Version: 	1.1 
Fecha: 		23-01-2013
Permite mostrar los datos recibidos con formato JSON y mostrarlos en una tabla
********************************************************************************************************************/
function MostrarResultadoTabla(Tabla,datosjson,indices){		
	Tabla.fnClearTable();
	    Tabla.fnDraw();
	    $.each(datosjson,function(index,value) 			//rocorre json de retorno y se insertan los datos en la tabla	
		{ 	var Instruccion = "Tabla.fnAddData([	datosjson[index].";
			for(var i=0;i<indices.length-1;i++){	
				Instruccion += indices[i]+",datosjson[index].";
			}
			Instruccion += indices[indices.length-1]+"],true)";
			eval(Instruccion);
		});
}



/********************************************************************************************************************
Version: 	1.1 
Fecha: 		23-01-2013
Permite crear una tabla con las caracteristicas predefinidas
********************************************************************************************************************/
function CrearDataTable(NombreDataTable, _paginacion, _info, _filtro){
	return($("#"+NombreDataTable).dataTable({
					"scrollX": 			true,
					"info": 			_info,
					"paging": 			_paginacion,
					"bJQueryUI":        true,
					"bPaginate":        true,
					"bSort":            true,
					"bFilter":          _filtro,
					"bScrollAUtoCss":   true,
					"iTabIndex":        1,
					"lengthMenu": 		[ [10, 25, 50, -1], [10, 25, 50, "Todas"] ],
					"oLanguage":{   "sLengthMenu":  "Ver _MENU_ filas",
									"sZeroRecords": "No hay datos para mostrar",
									"sInfo":        "Registro _START_ al _END_ de _TOTAL_ ",
									"sInfoEmpty":   "Registro 0 al 0 de 0 registros",
									"sInfoFiltered":"(filtrado desde _MAX_ registros)",
									"sSearch": 		"Filtrar"					    
								}
				}));
}


function CrearDataTableChkBox(NombreDataTable, _paginacion, _info, rows_selected){
	//var rows_selected = [];				
	return($("#"+NombreDataTable).dataTable({
		"scrollX": 			true,
		"info": 			_info,
		"paging": 			_paginacion,
		"bJQueryUI":        true,
		"bPaginate":        true,
		"bSort":            true,
		"bFilter":          true,
		"bScrollAUtoCss":   true,
		"iTabIndex":        1,
		"lengthMenu": 		[ [10, 25, 50, -1], [10, 25, 50, "Todas"] ],
		"oLanguage":{   "sLengthMenu":  "Mostrar _MENU_ datos por pagina",
						"sZeroRecords": "No hay datos para mostrar",
						"sInfo":        "Registro _START_ al _END_ de _TOTAL_ ",
						"sInfoEmpty":   "Registro 0 al 0 de 0 registros",
						"sInfoFiltered":"(filtrado desde _MAX_ registros)"    
		},'columnDefs': [{	'targets': 0,
		       				'searchable':false,
		       				'orderable':false,
		       				'className': 'dt-body-center',
		       				'render': function (data, type, full, meta){
		           				return '<input type="checkbox">';
		       				}
		}],'order': [1, 'asc'],
		'rowCallback': function(row, data, dataIndex){
		   	var rowId = data[0];
			if($.inArray(rowId, rows_selected) !== -1){
		        $(row).find('input[type="checkbox"]').prop('checked', true);
		        $(row).addClass('row_selected');
		    }
		}			
	}));
}


function CrearTableTwoChkBox(NombreDataTable, _paginacion, _info, rows_selected, name_rows, rows_check_box){
	//var rows_selected = [];				
	return($("#"+NombreDataTable).dataTable({
		"scrollX": 			true,
		"info": 			_info,
		"paging": 			_paginacion,
		"bJQueryUI":        true,
		"bPaginate":        true,
		"bSort":            true,
		"bFilter":          true,
		"bScrollAUtoCss":   true,
		"iTabIndex":        1,
		"lengthMenu": 		[ [10, 25, 50, -1], [10, 25, 50, "Todas"] ],
		"oLanguage":{   "sLengthMenu":  "Mostrar _MENU_ datos por pagina",
						"sZeroRecords": "No hay datos para mostrar",
						"sInfo":        "Registro _START_ al _END_ de _TOTAL_ ",
						"sInfoEmpty":   "Registro 0 al 0 de 0 registros",
						"sInfoFiltered":"(filtrado desde _MAX_ registros)"    
		},'columnDefs': [{	'targets': rows_check_box[0],
		       				'searchable':false,
		       				'orderable':false,
		       				'className': 'dt-body-center',
		       				'render': function (data, type, full, meta){
		           				var checked = "";
		       					if(data == "1" ){
		       						checked = 'checked="checked"';
		       					}
		           				return '<input id="'+name_rows[0]+'" type="checkbox"' + checked + '  />';
		       				}
		},{					'targets': rows_check_box[1],
		       				'searchable':false,
		       				'orderable':false,
		       				'className': 'dt-body-center',
		       				'render': function (data, type, full, meta){
		       					var checked = "";
		       					if(data == "1" ){
		       						checked = 'checked="checked"';
		       					}
		           				return '<input id="'+name_rows[1]+'" type="checkbox"' + checked + '  />';
		       				}
		}],'order': [1, 'asc'],
		'rowCallback': function(row, data, dataIndex){
		   	var rowId = data[0];
			if($.inArray(rowId, rows_selected) !== -1){
		        $(row).find('input[type="checkbox"]').prop('checked', true);
		        //$(row).addClass('row_selected');
		    }
		}			
	}));
}


function CrearDataTableHiperLink(NombreDataTable, _paginacion, _info, row_hiperlink){
	//var rows_selected = [];				
	return($("#"+NombreDataTable).dataTable({
		"scrollX": 			true,
		"info": 			_info,
		"paging": 			_paginacion,
		"bJQueryUI":        true,
		"bPaginate":        true,
		"bSort":            true,
		"bFilter":          true,
		"bScrollAUtoCss":   true,
		"iTabIndex":        1,
		"lengthMenu": 		[ [10, 25, 50, -1], [10, 25, 50, "Todas"] ],
		"oLanguage":{   "sLengthMenu":  "Mostrar _MENU_ datos por pagina",
						"sZeroRecords": "No hay datos para mostrar",
						"sInfo":        "Registro _START_ al _END_ de _TOTAL_ ",
						"sInfoEmpty":   "Registro 0 al 0 de 0 registros",
						"sInfoFiltered":"(filtrado desde _MAX_ registros)"    
		},'columnDefs': [{	'targets': row_hiperlink,
		       				'searchable':false,
		       				'orderable':false,
		       				'className': 'dt-body-center',
		       				'render': function (data, type, full, meta){
		       					var htmlText = '';
		       					var listFiles = data.split("|");
		       					for(var i=0;i<listFiles.length;i++){
		       						var nameFiles = listFiles[i].split("=");
		       						var htmlText = htmlText + '<a target="_blank" href="'+listFiles[i]+'">'+nameFiles[nameFiles.length-1]+'</a><br>';
		       					}
								return htmlText;
		       				}
		}]			
	}));
}


function updateDataTableSelectAllCtrl(table, head_check){
	var $table             = table.dataTable();
	var $chkbox_all        = $('tbody input[type="checkbox"]', $table);
	var $chkbox_checked    = $('tbody input[type="checkbox"]:checked', $table);
	var chkbox_select_all  = head_check.get(0);
	//var chkbox_select_all  = $("#trabajo_verificacion input[name='select_all']").get(0);
	//$('thead input[name="select_all"]', $table).get(0);

	
	if($chkbox_checked.length === 0){								//Si ninguno de los checkbox estan seleccionados
		chkbox_select_all.checked = false;
		if('indeterminate' in chkbox_select_all){
			chkbox_select_all.indeterminate = false;
		}
	} else if ($chkbox_checked.length === $chkbox_all.length){		//Si algunos de los checkbox estan seleccionados
		chkbox_select_all.checked = true;
		if('indeterminate' in chkbox_select_all){
			chkbox_select_all.indeterminate = false;
		}
	} else {														//Si todos los checkbox estan seleccionados
		chkbox_select_all.checked = true;
		if('indeterminate' in chkbox_select_all){
			chkbox_select_all.indeterminate = true;
		}
	}
}



/********************************************************************************************************************
Version: 	1.1 
Fecha: 		23-01-2013
Permite ocultar columnas especificas de una tabla
********************************************************************************************************************/
function OcultarColumnas(NombreTabla,Columnas,NumColumnas){	
	NombreTabla.fnClearTable();
	NombreTabla.fnDraw();			
	for(var i=0;i<NumColumnas;i++){				//bucle para mostrar todas las columnas
		NombreTabla.fnSetColumnVis( i, true );
	}

	for(var i=0;i<Columnas.length;i++){			//bucle para ocultar las columnas especidifcadas
		NombreTabla.fnSetColumnVis( Columnas[i], false );
	}
}



/********************************************************************************************************************
Version: 	1.1 
Fecha: 		23-01-2013
Permite capturar el id de la fila de una tabla especifica
********************************************************************************************************************/
function fnGetSelected(oTableLocal){   
	return oTableLocal.$('tr.selected');    
}



/********************************************************************************************************************
Version: 	1.1 
Fecha: 		23-01-2013
Permite capturar la informacion de una tabla especifica y retorna un array con la informacion
********************************************************************************************************************/
function InfTableToArray(NombreTabla,Columnas){
	var numRows = NombreTabla.dataTable().fnGetNodes();
	var arrayReturn = new Array(numRows.length);
	
	for (i = 0; i < arrayReturn.length; i++){ 
		arrayReturn[i]=new Array(Columnas.length); 
	} 
	
	//var rowsArray = 0;
	for(var i=0;i<numRows.length;i++){
		for(var j=0;j<Columnas.length;j++){
			arrayReturn[i][j] = NombreTabla.dataTable().fnGetData(numRows[i],Columnas[j]);
		}
	}
	return arrayReturn;
}


/********************************************************************************************************************
Version: 	1.1 
Fecha: 		24-01-2013
Permite capturar la informacion de una tabla especifica y retorna la informacion como formato JSON
********************************************************************************************************************/
function InfTablaToJSON(NombreTabla,NombreJSON,NombreColumnas,Columnas){
	var numRows 	= NombreTabla.dataTable().fnGetNodes();
	if(numRows.length>0){	
		var returnJSON 	='{"'+NombreJSON+'":[';
		for(var i=0;i<numRows.length;i++){
			returnJSON 	+= '{';
			for(var j=0;j<Columnas.length;j++){
				returnJSON	+=	'"'+NombreColumnas[j]+'":"'+NombreTabla.dataTable().fnGetData(numRows[i],Columnas[j])+'",';
			}
			returnJSON = returnJSON.substring(0,returnJSON.length-1)+'},';      //elimina la ultima coma del string JSON
		}
		returnJSON = returnJSON.substring(0,returnJSON.length-1)+']}';      //elimina la ultima coma del string JSON
		return(JSON.parse(returnJSON));
	}
}


function GetColumnOfRowSelected(NombreTabla,Columna){
	var sData = fnGetSelected(NombreTabla);
	return (NombreTabla.dataTable().fnGetData(sData[0],Columna));
}


/********************************************************************************************************************
Version: 	1.1 
Fecha: 		24-01-2013
Permite capturar la informacion de una tabla especifica y retorna la informacion como formato JSON
********************************************************************************************************************/
function InfTablaSelectedToJSON(NombreTabla,NombreJSON,NombreColumnas,Columnas){
	var sData = fnGetSelected(NombreTabla);

	//var numRows 	= NombreTabla.dataTable().fnGetNodes();
	if(sData.length>0){	
		var returnJSON 	='{"'+NombreJSON+'":[';
		for(var i=0;i<sData.length;i++){
			returnJSON 	+= '{';
			for(var j=0;j<Columnas.length;j++){
				returnJSON	+=	'"'+NombreColumnas[j]+'":"'+NombreTabla.dataTable().fnGetData(sData[i],Columnas[j])+'",';
			}
			returnJSON = returnJSON.substring(0,returnJSON.length-1)+'},';      //elimina la ultima coma del string JSON
		}
		returnJSON = returnJSON.substring(0,returnJSON.length-1)+']}';      //elimina la ultima coma del string JSON
		return(JSON.parse(returnJSON));
	}
}


/********************************************************************************************************************
Version: 	1.0
Fecha: 		22-08-2014
Permite capturar la informacion de una tabla especifica ya filtrada y retorna la informacion como formato JSON
********************************************************************************************************************/
function InfTablaFilterToJSON(NombreTabla,NombreJSON,NombreColumnas,Columnas){
	var sData = NombreTabla._('tr', {"filter":"applied"});
	alert(sData.length);

	//var numRows 	= NombreTabla.dataTable().fnGetNodes();
	if(sData.length>0){	
		var returnJSON 	='{"'+NombreJSON+'":[';
		for(var i=0;i<sData.length;i++){
			returnJSON 	+= '{';
			for(var j=0;j<Columnas.length;j++){
				returnJSON	+=	'"'+NombreColumnas[j]+'":"'+sData[i][j]+'",';
			}
			returnJSON = returnJSON.substring(0,returnJSON.length-1)+'},';      //elimina la ultima coma del string JSON
		}
		returnJSON = returnJSON.substring(0,returnJSON.length-1)+']}';      //elimina la ultima coma del string JSON
		return(JSON.parse(returnJSON));
	}
}