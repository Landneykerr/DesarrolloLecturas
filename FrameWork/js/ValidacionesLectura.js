

function Usuario(_datos){
	this.datos 		= _datos;

	this.estado 	= _datos['estado'];
	this.mensaje 	= _datos['mensaje'];
	this.id_programacion = _datos['id_programacion'];
	this.inspector 	= _datos['inspector'];
	this.tipo 		= _datos['tipo'];

	this.ruta 		= _datos['id_ciclo']
	this.cuenta 	= _datos['cuenta'];
	this.usuario 	= _datos['nombre'];
	this.direccion 	= _datos['direccion'];

	this.id_medidor 	= -1;
	

	this.medidor = new Array();

	for(i=0; i< _datos['medidores'].length; i++){
		this.medidor[i] = new Medidor(_datos['medidores'][i]);
	}

}



Usuario.prototype.getEstado = function(){
	return this.estado;
}

Usuario.prototype.getMensaje = function(){
	return this.mensaje;
}

Usuario.prototype.getIdProgramacion = function(){
	return this.id_programacion;
}

Usuario.prototype.getInspector = function(){
	return this.inspector;
}

Usuario.prototype.getTipo = function(){
	return this.tipo;
}

Usuario.prototype.getRuta = function(){
	return this.ruta;
}

Usuario.prototype.getCuenta = function(){
	return this.cuenta;
}

Usuario.prototype.getMedidor = function(){
	return this.medidor;
}

Usuario.prototype.getUsuario = function(){
	return this.usuario;
}

Usuario.prototype.getDireccion = function(){
	return this.direccion;
}

Usuario.prototype.getFactor = function(){
	return this.factor;
}


Usuario.prototype.getLecturas = function(){
	return this.lectura;
}


Usuario.prototype.getIdMedidor = function(){
	return this.id_medidor;	
}


Usuario.prototype.setIdMedidor = function(_idMedidor){
	this.id_medidor = _idMedidor;
}


Usuario.prototype.mostrarInformacion = function(_estadoCuenta, _ruta, _medidor, _usuario, _direccion, _infBasica, _btnGuardar){
	_estadoCuenta.removeClass("alert-success");
	_estadoCuenta.removeClass("alert-danger");

	_infBasica.removeClass("alert-success");
	_infBasica.removeClass("alert-danger");

	_infBasica.val("");



	if(this.estado === true){
		_estadoCuenta.addClass("alert-success");
		_estadoCuenta.val(this.mensaje);

		_ruta.val(this.ruta);
		_medidor.val(this.medidor);
		_usuario.val(this.usuario);
		_direccion.val(this.direccion);	
		_btnGuardar.attr("disabled", false);

	}else{
		_estadoCuenta.addClass("alert-danger");
		_estadoCuenta.val(this.mensaje);
		_btnGuardar.attr("disabled", true);	
	}


	_medidor.find('option').remove().end();

	if(this.medidor[0]['medidor'] !== null){

		for(i=0; i<this.medidor.length; i++){

			_medidor.append($("<option></option>")
            	        .attr("value", i)
                	    .text(this.medidor[i]['medidor']));	
		}
	}
}



Usuario.prototype.limpiarCampos = function(_inputCuenta, _estadoCuenta, _ruta, _medidor, _usuario, _direccion, _infBasica, _btnGuardar, _tipoEnergia1, 
	_inputLectura1,	_lblCritica1, _tipoEnergia2, _inputLectura2, _lblCritica2, _tipoEnergia3, _inputLectura3, _lblCritica3, _obsLectura){

	_estadoCuenta.removeClass("alert-success");
	_estadoCuenta.removeClass("alert-danger");

	_infBasica.removeClass("alert-success");
	_infBasica.removeClass("alert-danger");

	_ruta.val("");
	_medidor.val("");
	_usuario.val("");
	_direccion.val("");	
	_btnGuardar.attr("disabled", true);


	_tipoEnergia1.text("N/A");
	_inputLectura1.val("");
	_inputLectura1.attr("disabled", true);
	_lblCritica1.removeClass("alert-danger");
	_lblCritica1.val("");


	_tipoEnergia2.text("N/A");
	_inputLectura2.val("");
	_inputLectura2.attr("disabled", true);
	_lblCritica2.removeClass("alert-danger");
	_lblCritica2.val("");

	_tipoEnergia3.text("N/A");
	_inputLectura3.val("");
	_inputLectura3.attr("disabled", true);
	_lblCritica3.removeClass("alert-danger");
	_lblCritica3.val("");

	_obsLectura.val("");

	_medidor.find('option').remove().end();

	_inputCuenta.select();
}



Usuario.prototype.checkInputLectura = function(_tipoEnergia, _inputLectura, _item){
	if(this.medidor[0]['medidor'] === null){
		_tipoEnergia.text("N/A");
		_inputLectura.attr("disabled", true);

	}else if(this.medidor[this.id_medidor].lectura[_item].getIdSerial() !== -1){
		_tipoEnergia.text(this.medidor[this.id_medidor].lectura[_item].getTipoEnergia());	
		_inputLectura.attr("disabled", false);

	}else{
		_tipoEnergia.text("N/A");
		_inputLectura.attr("disabled", true);
	}
}



Usuario.prototype.statusLectura = function(_lblCritica, _item){
	_lblCritica.removeClass("alert-danger");
	
	if(this.medidor[this.id_medidor].lectura[_item].getEstadoCritica() === true){
		_lblCritica.val("");
	}else{
		_lblCritica.addClass("alert-danger");
		_lblCritica.val(this.medidor[this.id_medidor].lectura[_item].getDescripcionCritica());
	}
}



Usuario.prototype.calcularCritica = function(_lecturaActual, _item){
	//this.lectura[_item].setLectura(_lecturaActual);

	if(_lecturaActual == ""){
		if(this.medidor[this.id_medidor].lectura[_item].getIdSerial() == -1){
			this.medidor[this.id_medidor].lectura[_item].setCritica(1);
			this.medidor[this.id_medidor].lectura[_item].setLectura(-1);
		}else{
			//alert(_item+" lectura esta vacia.");
			this.medidor[this.id_medidor].lectura[_item].setCritica(1);
			this.medidor[this.id_medidor].lectura[_item].setLectura(-1);
		}
	}else if(_lecturaActual == -1){
		this.medidor[this.id_medidor].lectura[_item].setCritica(1);
		this.medidor[this.id_medidor].lectura[_item].setLectura(_lecturaActual);
	}else{
		this.medidor[this.id_medidor].lectura[_item].setLectura(_lecturaActual);

		if(this.medidor[this.id_medidor].lectura[_item].getPromedio() == 0){
			this.medidor[this.id_medidor].lectura[_item].setCritica(((_lecturaActual - this.medidor[this.id_medidor].lectura[_item].getLecturaAnterior())/0.000001) * this.medidor[this.id_medidor].factor);
		}else{
			this.medidor[this.id_medidor].lectura[_item].setCritica(((_lecturaActual - this.medidor[this.id_medidor].lectura[_item].getLecturaAnterior())/this.medidor[this.id_medidor].lectura[_item].getPromedio()) * this.medidor[this.id_medidor].factor);
		}
	}
}









function Medidor(_datosMedidor){
	this.medidor = _datosMedidor['medidor'];
	this.factor  = _datosMedidor['factor'];

	this.lectura = new Array();

	for(j=0; j<_datosMedidor['lecturas'].length; j++){
		this.lectura[j] = new Lectura(_datosMedidor['lecturas'][j]);
	}
}


Medidor.prototype.getMedidor = function(){
	return this.medidor;
}


Medidor.prototype.getFactor = function(){
	return this.factor;
}







function Lectura(_datosLectura){
	this.id_serial 			= _datosLectura['id_serial'];
	this.lectura_anterior 	= _datosLectura['lectura'];
	this.promedio 			= _datosLectura['promedio'];
	this.tipo_energia 		= _datosLectura['tipo_energia'];

	this.lectura 			= -1;
	this.critica 			= 1;
	this.descripcionCritica = "Normal";
	this.estadoCritica		= true;
}


Lectura.prototype.getIdSerial = function(){
	return this.id_serial;
}


Lectura.prototype.getLecturaAnterior = function(){
	return this.lectura_anterior;
}


Lectura.prototype.getPromedio = function(){
	return this.promedio;
}


Lectura.prototype.getTipoEnergia = function(){
	return this.tipo_energia;
}


Lectura.prototype.getCritica = function(){
	return this.critica;
}


Lectura.prototype.setCritica = function(_critica){
	this.critica = _critica;
}


Lectura.prototype.getEstadoCritica = function(){
	return this.estadoCritica;
}



Lectura.prototype.getDescripcionCritica = function(){
	return this.descripcionCritica;
}


Lectura.prototype.setDescripcionCritica = function(_descripcion){
	this.descripcionCritica = _descripcion;

	if(_descripcion == "Normal"){
		this.estadoCritica = true;
	}else{
		this.estadoCritica = false;
	}
}


Lectura.prototype.getLectura = function(){
	return this.lectura;
}


Lectura.prototype.setLectura = function(_lectura){
	this.lectura = _lectura;
}
