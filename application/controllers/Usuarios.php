<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once( APPPATH.'/libraries/REST_Controller.php' );
use Restserver\libraries\REST_Controller;
class Usuarios extends REST_Controller {

	function __construct()
	{
		header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
    	header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
    	header("Access-Control-Allow-Origin: *");
        parent::__construct();
        $this->load->model("Model_Usuarios");
    	
	}

	public function login_post()
	{
        $datos=$this->post();
        $respuesta=$this->Model_Usuarios->login($datos["usuario"],$datos["clave"]);
        if($respuesta===false){
            $_data["ok"]="error";
            $_data["mensaje"]="Usuario y/o contraseña no validos";
            $this->response( $_data, 400);
        }else{
            $_data["ok"]="ok";
            $_data["datos_usuario"]= $respuesta;
            $this->response($_data, 200);
        }
    }
    public function updatenameavatar(){
        $datos=$this->post();
        $respuesta=$this->Model_Usuarios->updateFoto($datos["IDUsuario"],$datos["Foto"]);
        if($respuesta===true){
            $_data["ok"]="error";
            $_data["mensaje"]="Error al actualizar";
            $this->response( $_data, 400);
        }else{
            $_data["ok"]="ok";
            $_data["datos_usuario"]=$this->Model_Usuarios->getdataID($datos["IDUsuario"]);
            $this->response($_data, 200);
        }
    }
}
