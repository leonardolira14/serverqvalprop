<?
class Model_Cuestionarios extends CI_Model
{
    function __construct()
	{
		parent::__construct();
        $this->load->database();
		$this->constante="FpgH456Gtdgh43i349gjsjf%ttt";
    }
    // funcion para obtener los cuestionarios de un usario
    public function getnumCuestionario($_ID_Usuario,$_Empresa,$_Status,$_Fecha){
        //primero veo que tipo de fecha
        $_fechas=docemeces();

       if ($_Fecha==='A'):
           $fecha_inicio=$_fechas[0]."-".date('d'); $fecha_fin=$_fechas[12]."-".date('d');
        else:
           $fecha_inicio=$_fechas[11]."-".date('d'); $fecha_fin=$_fechas[12]."-".date('d');
        endif;

        //ahora mando a traer los registros de los cuestionarios 
        $respuesta=$this->db->select('*')->where("IDEmpresa='$_Empresa' and IDUsuario='$_ID_Usuario' and  Status='$_Status' and date(Fecha_Envio) between '$fecha_inicio' and '$fecha_fin'")->get('tb_cuestionarios_usuarios_plus');
        return $respuesta->num_rows();
    }   
}