<?
class Model_Pregunta extends CI_Model
{
    function __construct()
	{
		parent::__construct();
        $this->load->database();
		
    }

    //funcion para pobtener los detalles de una pregunta
    public function get_detalles($_IDPregunta){
        $respuesta=$this->db->select('*')->where("IDPregunta='$_IDPregunta'")->get("tbpreguntas");
        return $respuesta->row_array();
    }
}