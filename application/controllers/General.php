<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once( APPPATH.'/libraries/REST_Controller.php' );
use Restserver\libraries\REST_Controller;
class General extends REST_Controller {

	function __construct()
	{
		header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
    	header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
    	header("Access-Control-Allow-Origin: *");
        parent::__construct();
        $this->load->model("Model_Usuarios");
        $this->load->model("Model_Cuestionarios");
    	
    }
    Public function panel_post(){
        $datos=$this->post();
       
        //ahora tengo que revisar cuaantos cuestionarios tiene pendiente
       $_data["NumPendientes"]= $this->Model_Cuestionarios->getnumCuestionario($datos["usuario"],$datos["IDEmpresa"],'0',"A");
       $_data["NumREsueltos"]= $this->Model_Cuestionarios->getnumCuestionario($datos["usuario"],$datos["IDEmpresa"],'1',"A");
       $_data["NumResueltosMes"]= $this->Model_Cuestionarios->getnumCuestionario($datos["usuario"],$datos["IDEmpresa"],'1',"M");
       $_data["ok"]="ok";
       $this->response($_data, 200);
    }


    public function updatedatos_post(){
        $datos=$this->post();
        
        $respuesta=$this->Model_Usuarios->update_date($datos["IDUsuario"],$datos["nombre"],$datos["apellidos"],$datos["correo"]);
        if($respuesta===true){
            $_data["ok"]="ok";
            $_data["datos_usuario"]=$this->Model_Usuarios->getdataID($datos["IDUsuario"]);
            $this->response($_data, 200);
        }else{
            $_data["ok"]="error";
            $_data["mensaje"]="Error en la conexion favor de contactar al administrador";
             $this->response($_data, 400);
        }
    }
}