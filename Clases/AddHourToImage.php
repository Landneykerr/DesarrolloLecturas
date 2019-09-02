<?php
class AddHourToImage{
	private $ancho;
    private $alto;
    private $separacion;
    private $margen_inferior;
    private $margen_izquierda;
    private $ruta_numeros;
    private $sep_hora;
    private $sep_fecha;

	public function __construct($_ancho, $_alto, $_separacion, $_margen_inferior, $_margen_izquierda, $_ruta_numeros,$_separador_fecha,$_separador_hora){
		$this->ancho 			= $_ancho;
		$this->alto 			= $_alto;
		$this->separacion 		= $_separacion;
		$this->margen_inferior 	= $_margen_inferior;
		$this->margen_izquierda = $_margen_izquierda;
		$this->ruta_numeros 	= $_ruta_numeros;
		$this->sep_hora         = $_separador_hora;
		$this->sep_fecha        = $_separador_fecha;
	} 



	function SetHourImage($_lista_imagenes, $_hora, $_fecha, $_pre_nombre){
		for($k=0; $k<count($_lista_imagenes);$k++){
			$fondo = imagecreatefromjpeg($_lista_imagenes[$k]); 
			$fondoAncho = imagesx($fondo); 
			$fondoAlto = imagesy($fondo); 
			
			$hora_sistema = $this->getArrayHora($_hora, $this->sep_hora);
			for($i=0; $i<count($hora_sistema);$i++){
				$texto = imagecreatefrompng($this->ruta_numeros."/".$hora_sistema[$i].".png");
				$textoAncho = imagesx($texto); 
				$textoAlto = imagesy($texto); 
				imagecopy($fondo, $texto, $fondoAncho - (15*$this->ancho) - $this->separacion - $this->margen_izquierda + $i*$this->ancho, $fondoAlto - $textoAlto - $this->margen_inferior,0,0,$textoAncho,$textoAlto);
			};


			$fecha_sistema = $this->getArrayFecha($_fecha, $this->sep_fecha);
			for($i=0; $i<count($fecha_sistema);$i++){
				$texto = imagecreatefrompng($this->ruta_numeros."/".$fecha_sistema[$i].".png");
				$textoAncho = imagesx($texto); 
				$textoAlto = imagesy($texto); 
				imagecopy($fondo, $texto, $fondoAncho - (10*$this->ancho) - $this->margen_izquierda + ($i*$this->ancho), $fondoAlto - $textoAlto-$this->margen_inferior,0,0,$textoAncho,$textoAlto);
			};	 
			imagepng($fondo,$_pre_nombre.str_replace(".jpg",".png",$_lista_imagenes[$k])); 	 
			imagedestroy($fondo); 
			imagedestroy($texto); 
		}	
	}



	function getArrayHora($_hora,$_separador){
		//$hora = explode(":", date('H:i:s'));
		$hora = explode($_separador, $_hora);
		$array_hora[0] = (int)($hora[0]/10);
		$array_hora[1] = $hora[0] % 10;
		$array_hora[2] = "puntos";
		$array_hora[3] = (int)($hora[1]/10);
		$array_hora[4] = $hora[1] % 10;
		return $array_hora;
	}


	function getArrayFecha($_fecha,$_separador){
		//$fecha = explode(":", date('d:m:Y'));
		$fecha = explode($_separador, $_fecha);
		$array_fecha[0] = (int)($fecha[0]/10);
		$array_fecha[1] = $fecha[0] % 10;
		$array_fecha[2] = "slash";
		$array_fecha[3] = (int)($fecha[1]/10);
		$array_fecha[4] = $fecha[1] % 10;
		$array_fecha[5] = "slash";
		$array_fecha[6] = (int)($fecha[2]/1000);
		$array_fecha[7] = (int)(($fecha[2]%1000)/100);
		$array_fecha[8] = (int)(($fecha[2]%100)/10);
		$array_fecha[9] = $fecha[2] % 10;
		return $array_fecha;
	}
}

	
?>