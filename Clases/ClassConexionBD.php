<?php
class ConexionBD{
    // Contenedor de la instancia del singleton
    private static  $instancia;
    private         $listServer;
    private         $conexion;

    /*private         $servidor;
    private         $puerto;
    private         $base_datos;
    private         $usuario;
    private         $password;*/

	 
    // Un constructor privado evita la creación de un nuevo objeto
    private function __construct() {
        $this->listServer['linode']['servidor']     = "localhost";
        $this->listServer['linode']['puerto']       = "5432";
        $this->listServer['linode']['base_datos']   = "lecturas";
        $this->listServer['linode']['usuario']      = "postgres";
        $this->listServer['linode']['password']     = "p3g4sus";

        /*$this->listServer['linode']['servidor']     = "45.33.62.183";
        $this->listServer['linode']['puerto']       = "5432";
        $this->listServer['linode']['base_datos']   = "lecturas";
        $this->listServer['linode']['usuario']      = "postgres";
        $this->listServer['linode']['password']     = "p3g4sus";*/

        $this->listServer['fotos']['servidor']  = "186.115.150.189";
        $this->listServer['fotos']['puerto']    = "5432";
        $this->listServer['fotos']['base_datos']= "fotos_lecturas";
        $this->listServer['fotos']['usuario']   = "consult_fotos";
        $this->listServer['fotos']['password']  = "l3ctur4sf0t0s";

		/*$this->servidor     = "45.33.62.183";
        $this->puerto       = "5432";
        $this->base_datos   = "lecturas";
        $this->usuario      = "postgres";
        $this->password     = "p3g4sus";*/
    }
 
    // método singleton
    public static function getInstance(){
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        } 
        return self::$instancia;
    }


    public function setConexion($_conexion){
        $this->conexion = $_conexion;
    }

    public function getConexion(){
        return $this->conexion;
    }

	
	public function getServidor(){
		return $this->listServer[$this->conexion]['servidor'];
    }

    public function getPuerto(){
        return $this->listServer[$this->conexion]['puerto'];
        //return $this->puerto;
    }

    public function getBaseDatos(){
        return $this->listServer[$this->conexion]['base_datos'];
        //return $this->base_datos;
    }

    public function getUsuario(){
        return $this->listServer[$this->conexion]['usuario'];
        //return $this->usuario;
    }

    public function getPassword(){
        return $this->listServer[$this->conexion]['password'];
        //return $this->password;
    }

    public function __clone(){
        trigger_error('La clonación de este objeto no está permitida', E_USER_ERROR);
    }
}
?>