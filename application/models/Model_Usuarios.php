<?
class Model_Usuarios extends CI_Model
{
    function __construct()
	{
		parent::__construct();
        $this->load->database();
		$this->constante="FpgH456Gtdgh43i349gjsjf%ttt";
    }

    // funcion para activar el login
    public function login($_Usuario,$_Clave){
        //concateno la clave
        $_Clave=md5($_Clave.$this->constante);
                
        //ahora realizo la consulta
        $sql=$this->db->select('*')->where("Correo='$_Usuario' and Clave='$_Clave'")->get("tbusuarios_plus");
        //vdebug($_Clave);
        if($sql->num_rows()===0){
            return false;
        }else{
            return $sql->row_array();
        }	
    }

    // function para obtener los datos de un usario por su ID
    public function getdataID($_IDUsuario){
        $sql=$this->db->select('*')->where("IDUsuario='$_IDUsuario'")->get("tbusuarios_plus");
        return $sql->row_array();
    }

    // funcion para actulizar los datos 
    public function update_date($_IDUsuario,$_Nombre,$_Apellido,$_Correo){
        $array=array(
            "Nombre"=>$_Nombre,
            "Apellidos"=>$_Apellido,
            "Correo"=>$_Correo
        );
       return  $this->db->where("IDUsuario='$_IDUsuario'")->update("tbusuarios_plus",$array);
    }

    //funcion para actualizar el nombre de la foto
    public function updateFoto($_IDUsuario,$_Foto){
        $array=array(
            "Foto"=>$_Foto
        );
       return  $this->db->where("IDUsuario='$_IDUsuario'")->update("tbusuarios_plus",$array);
    }   
}