<?
class Model_Cuestionarios extends CI_Model
{
    function __construct()
	{
		parent::__construct();
        $this->load->database();
		$this->load->model("Model_Pregunta");
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
    
    //funcion para obtener los cuestionarios
    public function get($_IDEmpresa,$_IDUsuario,$_Fecha,$_Status){
         //primero veo que tipo de fecha
         $_fechas=docemeces();
         if ($_Fecha==='A'):
            $fecha_inicio=$_fechas[0]."-".date('d'); $fecha_fin=$_fechas[12]."-".date('d');
         else:
            $fecha_inicio=$_fechas[11]."-".date('d'); $fecha_fin=$_fechas[12]."-".date('d');
         endif;

         $respuesta=$this->db->select('IDCuestionario,Fecha_Envio,Hora_Envio,RazonSocial')->join("empresa","empresa.IDEmpresa=tb_cuestionarios_usuarios_plus.IDEmpresa_Emisora")->from('tb_cuestionarios_usuarios_plus')->where("tb_cuestionarios_usuarios_plus.IDEmpresa='$_IDEmpresa' and IDUsuario='$_IDUsuario' and  Status='$_Status' and date(Fecha_Envio) between '$fecha_inicio' and '$fecha_fin'")->get();
        
         $custionarios=$respuesta->result_array();
         foreach ($custionarios as $key => $Item) {
            $listado_preguntas=[];
            $datos_cuestionario=$this->get_detalle_id($Item["IDCuestionario"]);
            $custionarios[$key]["Nombre_Cuestionario"]= $datos_cuestionario["Nombre"];
            
         }
         return $custionarios;
         
    }
    //funcion para obtener el listado de preguntas de un cuestionario pendiente
    public function get_lista_preguntas_pendiente($_IDCuestionaro){
        $datos_cuestionario=$this->get_detalle_id($_IDCuestionaro);
        $_lista_preguntas=json_decode($datos_cuestionario["Cuestionario"]);
        $listado_preguntas=[];
        foreach($_lista_preguntas as $pregunta){
            $datos_pregunta=$this->Model_Pregunta->get_detalles($pregunta);
            array_push( $listado_preguntas,array(
                "IDPregunta"=>$datos_pregunta["IDPregunta"],
                "Pregunta"=>$datos_pregunta["Pregunta"],
                "Forma"=>$datos_pregunta["Forma"],
                "Respuestas"=>$datos_pregunta["Respuestas"],
                "Obligatoria"=>$datos_pregunta["Obligatoria"]
            ));
        }
        
     return $listado_preguntas;
    }
    // function para botener los detalles de un cuestionario
    public function get_detalle_id($_IDCuestionario){
        $respuesta=$this->db->select('*')->join("detallecuestionario","detallecuestionario.IDCuestionario=cuestionario.IDCuestionario")->from('cuestionario')->where("cuestionario.IDCuestionario='$_IDCuestionario'")->get();
        return $respuesta->row_array();
    }
     // function para los datos del cuestionario
     public function get_id($_IDCuestionario){
        $respuesta=$this->db->select('*')->where("IDCuestionario='$_IDCuestionario'")->get('cuestionario');
        return $respuesta->row_array();
    }
    //funcion para obtener la valoracin especifica
    public function datos_valora($_IDEmpresa,$_IDUsuario,$_IDCuestionario){
        $respuesta=$this->db->select()->where("IDEmpresa='$_IDEmpresa' and IDUsuario='$_IDUsuario' and IDCuestionario='$_IDCuestionario'")->get("tb_cuestionarios_usuarios_plus");
        return $respuesta->row_array();
    }   
    //funcion para guardar la calificacion
    public function save_calificacion($_IDCuestionario,$IDEmisor,$IDRecptor,$_IDUsuario_Emisor){
        $array=array(
            "Calificacion"=>0,
            "IDCuestionario"=>$_IDCuestionario,
            "IDEmisor"=>$IDEmisor,
            "IDReceptor"=>$IDRecptor,
            "TEmisor"=>'E',
            "TReceptor"=>'I',
            "IDUsuarioEmisor"=>$_IDUsuario_Emisor

        );
        $this->db->insert("tbcalificaciones",$array);
		return $this->db->insert_id();

    }

    //funcion para guardar los detalles de la calificacion
    public function adddetallecalificacion($_cuestionario,$_ID_Valora){
        $pp=0;
        $po=0;
        
    foreach ($_cuestionario as $_pregunta) {
        $array=[];
        //obtengo los datos de la pregunta
        if(isset($_pregunta['RespuestaUs'])){
            if(gettype($_pregunta['RespuestaUs'])=="array"){
                $respuesta=json_encode($_pregunta['RespuestaUs']);
            }else{
                $respuesta=$_pregunta['RespuestaUs'];
            }
        }else{
            $respuesta='';
        }
        
        $datos_pregunta=$this->Model_Pregunta->get_detalles($_pregunta["IDPregunta"]);
        $calif=_is_respcorrect($datos_pregunta["Respuesta"],$respuesta,$datos_pregunta["Peso"],$datos_pregunta["Forma"]);
        $array=array(
            "IDValora"=>$_ID_Valora,
            "IDPregunta"=>$datos_pregunta["IDPregunta"],
            "Respuesta"=>$respuesta,
            "Calificacion"=>$calif
        );
        $this->db->insert("detallecalificacion",$array);
        $pp=$pp+(float)$datos_pregunta["Peso"];
        $po=$po+$calif;
        
    }		
    $media= _media_puntos($po,$pp);
    return $media["num"];
    }   
    public function modpromedio($_Promedio,$_ID_Valora){
		$array=array("Calificacion"=>$_Promedio);
		$this->db->where("IDCalificacion='$_ID_Valora'")->update("tbcalificaciones",$array);
    }
    
    //cambio el status de pendinte a contestado
    public function update_status($_IDEmpresa,$_IDUsuario,$_IDCuestionario,$_ID_Valora){
        $array=array(
            "Fecha_Respuesta"=>date("Y-m-d"),
            "Hora_Respuesta"=>date("h:i:s"),
            "Status"=>1,
            "IDValoracion"=>$_ID_Valora
        );
        $this->db->where("IDEmpresa='$_IDEmpresa' and IDUsuario='$_IDUsuario' and IDCuestionario='$_IDCuestionario'")->update("tb_cuestionarios_usuarios_plus",$array);
    }
    //funcion para obtener las preguntas con las respuestas de una valoracion
    public function detalles_valora($_IDValora){
        $respuesta=$this->db->select('tbpreguntas.Pregunta,detallecalificacion.Respuesta')->join("tbpreguntas","tbpreguntas.IDPregunta=detallecalificacion.IDPregunta")->where("IDValora='$_IDValora'")->get('detallecalificacion');
        return $respuesta->result_array();
    }
}