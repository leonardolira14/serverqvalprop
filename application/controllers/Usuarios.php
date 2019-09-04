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
    public function valid_password($password = '')
	{
		$password = trim($password);
		$regex_lowercase = '/[a-z]/';
		$regex_uppercase = '/[A-Z]/';
		$regex_number = '/[0-9]/';
		$regex_special = '/[!@#$%^&*()\-_=+{};:,<.>§~]/';
		if (empty($password))
		{
			$this->form_validation->set_message('valid_password', 'El campo {field} es requerido.');
			return FALSE;
		}
		if (preg_match_all($regex_lowercase, $password) < 1)
		{
			$this->form_validation->set_message('valid_password', 'El campo {field} debe contener al menos una letra minúscula.');
			return FALSE;
		}
		if (preg_match_all($regex_uppercase, $password) < 1)
		{
			$this->form_validation->set_message('valid_password', 'El campo {field} debe contener al menos una letra mayúscula.');
			return FALSE;
		}
		if (preg_match_all($regex_number, $password) < 1)
		{
			$this->form_validation->set_message('valid_password', 'El campo {field} debe contener al menos un número.');
			return FALSE;
		}
		if (preg_match_all($regex_special, $password) < 1)
		{
			$this->form_validation->set_message('valid_password', 'El campo {field} debe contener al menos un carácter especial.' . ' ' . htmlentities('!@#$%^&*()\-_=+{};:,<.>§~'));
			return FALSE;
		}
		if (strlen($password) < 6)
		{
			$this->form_validation->set_message('valid_password', 'El campo {field} debe contener al menos 6 caracteres de longitud.');
			return FALSE;
		}
		if (strlen($password) > 32)
		{
			$this->form_validation->set_message('valid_password', 'El campo {field} no debe sobrepasar los 32 caracteres.');
			return FALSE;
		}
		return TRUE;
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
    public function updatepass_post(){
        $_POST = json_decode(file_get_contents("php://input"), true);
        
        $config=array( array(
			'field'=>'clave', 
			'label'=>'Contraseña Actual', 
			'rules'=>'trim|required|xss_clean'					
			),array(
				'field'=>'nueva', 
				'label'=>'Contraseña Nueva', 
				'rules'=>'callback_valid_password'						
			),array(
				'field'=>'repetir', 
				'label'=>'Confirmar Contraseña', 
				'rules'=>'matches[nueva]'						
			));
            $this->form_validation->set_error_delimiters('<p>', '</p>');
            $this->form_validation->set_rules($config);
            $array=array("required"=>'El campo %s es obligatorio',"valid_email"=>'El campo %s no es valido',"min_length[3]"=>'El campo %s debe ser mayor a 3 Digitos',"min_length[10]"=>'El campo %s debe ser mayor a 10 Digitos','alpha'=>'El campo %s debe estar compuesto solo por letras',"matches"=>"Las contraseñas no coinciden",'is_unique'=>'El contenido del campo %s ya esta registrado');
            $this->form_validation->set_message($array);
            if($this->form_validation->run() !=false){
                // traigo lo datos del token 
                $IDUsuario=$_POST["IDUsuario"];
                $clave=$_POST["clave"];
                $nueva=$_POST["nueva"];
                    // ahora valido la contraseña
                    if( $this->Model_Usuarios->validate_clave($IDUsuario,$clave)===true){
                        $this->Model_Usuarios->update_clave($IDUsuario,$nueva);
                        $_data["code"]=0;
                        $_data["ok"]="Success";
                        $this->response($_data,200);
                    }else{
                        $_data["code"]=0;
                        $_data["ok"]="error";
                        $_data["result"]="Contraseña actual no coincide.";
                        $this->response($_data,404);
                    }
                    
                   
            }else{
                $_data["code"]=1990;
                $_data["ok"]="Error";
                $_data["result"]=validation_errors();
                $this->response($_data,404);
            }
            
       
    }
}
