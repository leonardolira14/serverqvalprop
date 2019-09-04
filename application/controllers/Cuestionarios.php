<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once( APPPATH.'/libraries/REST_Controller.php' );
use Restserver\libraries\REST_Controller;
class Cuestionarios extends REST_Controller {

	function __construct()
	{
		header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
    	header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
    	header("Access-Control-Allow-Origin: *");
        parent::__construct();
        $this->load->model("Model_Cuestionarios");
    	
    }
    
    public function getcuestionario_post(){
        $datos=$this->post();
        $respuesta=$this->Model_Cuestionarios->get($datos["IDEmpresa"],$datos["IDUsuario"],$datos["Fecha"],$datos["Status"]);

        if(!isset($datos["IDEmpresa"]) &&  !isset($datos["IDUsuario"])){
            $_data["ok"]="error";
            $_data["mensaje"]="Datos no validos";
            $this->response($_data, 400);
        }else{
            $_data["ok"]="ok";
            $_data["cuestionarios"]=$respuesta;
            $this->response($_data, 200);
        }
    }
    public function getpreguntaspendientes_post(){
        $datos=$this->post();
        $respuesta=$this->Model_Cuestionarios->get_lista_preguntas_pendiente($datos["IDCuestionario"]);
        if(!isset($datos["IDCuestionario"])){
            $_data["ok"]="error";
            $_data["mensaje"]="Datos no validos";
            $this->response($_data, 400);
        }else{
            $_data["ok"]="ok";
            $_data["preguntas"]=$respuesta;
            $this->response($_data, 200);
        }
    }

    //funcion para guardar un cuestionario contestado
    public function savecalificacion_post(){
        $datos=$this->POST();
        // obtengo los datos del cuestionario
        $_datos_cuestionario=$this->Model_Cuestionarios->get_id($datos["IDCuestionario"]);
        //ahora guardo la calificacion
        $_ID_Valora=$this->Model_Cuestionarios->save_calificacion(
            $datos["IDCuestionario"],
            $datos["empresa"],
            $_datos_cuestionario["IDEmpresa"],
            $datos["empresa"]
        );
        $promedio=$this->Model_Cuestionarios->adddetallecalificacion($datos["cuestionario"],$_ID_Valora);
        $this->Model_Cuestionarios->modpromedio($promedio,$_ID_Valora);
        // ahora cambio de pendiente a contestado
        $this->Model_Cuestionarios->update_status($datos["empresa"],$datos["IDUsuario"],$datos["IDCuestionario"],$_ID_Valora);

		$data=array("pass"=>1,"mensaje"=>"ok");
        $this->response($data, 200);
    }

    public function getrespuestas_post(){
        $datos=$this->POST();
        if(!isset($datos["IDEmpresa"]) &&  !isset($datos["IDUsuario"])){
            $_data["ok"]="error";
            $_data["mensaje"]="Datos no validos";
            $this->response($_data, 400);
        }else{
            $_data["ok"]="ok";
            $respuesta=$this->Model_Cuestionarios->datos_valora($datos["IDEmpresa"],$datos["IDUsuario"],$datos["IDCuestionario"]);
            $_data["detalles"]=$this->Model_Cuestionarios->detalles_valora($respuesta["IDValoracion"]);
            $this->response($_data, 200);
        }
        
        
        
    }
    
}


