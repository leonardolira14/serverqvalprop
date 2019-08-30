<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists("converter_cvs")){
	//convertir a cvs
	function converter_cvs($array,$titulo,$cabezeras){
		$output=fopen("php://output", 'W')or die("Can't open php://output");
		header('Content-Encoding: UTF-8');
    	header('Content-Type: text/csv; charset=utf-8' );
		header("Content-Disposition:attachment;filename=".$titulo.".csv"); 
		header('Content-Transfer-Encoding: binary');
	 	fputs( $output, "\xEF\xBB\xBF" ); 
		fputcsv($output, $cabezeras);
 
		foreach($array as $pregunta) {
		    fputcsv($output,$pregunta);
		}
		fclose($output) or die("Can't close php://output");
		}
}
if(!function_exists("genereclabe"))
{
		function genereclabe()
		{
			$psswd =substr( md5(microtime()), 1, 8);
			return $psswd;
		}

}
if(!function_exists("_compracion"))
{
		function _comparacion($numero1,$numero2)
		{
			if($numero1===$numero2){
				return 1;
			}else if($numero1>$numero2){
				return 2;
			}elseif($numero1<$numero2){
				return 3;
			}
		}

}
if(!function_exists("validar_clave")){
	function validar_clave($clave){
   if(strlen($clave) < 6){
   	  $_data["pass"]=0;
      $_data["mensaje"] = "La clave debe tener al menos 6 caracteres";
   }else if(strlen($clave) > 16){
     $_data["pass"]=0;
      $_data["mensaje"] = "La clave no puede tener más de 16 caracteres";
    
   }else if (!preg_match('`[a-z]`',$clave)){
     $_data["pass"]=0;
      $_data["mensaje"] ="La clave debe tener al menos una letra minúscula";
     
   }else if (!preg_match('`[A-Z]`',$clave)){
     $_data["pass"]=0;
      $_data["mensaje"] ="La clave debe tener al menos una letra mayúscula";
    
   }else if (!preg_match('`[0-9]`',$clave)){
      $_data["pass"]=0;
      $_data["mensaje"] = "La clave debe tener al menos un caracter numérico";
     
   }else{
   	 $_data["pass"]=1;
   }
   return $_data;
   
}
}
if(!function_exists("limpiar_array")){
	function limpiar_array($array){
		foreach ($arry as $item) {
			
		}
	}	
}
if(!function_exists("quitaritem")){
	function quitaritem($array,$IDPregunta,$nomenclatura){
		
		foreach ($array as $key=>$items) {
			if(is_numeric($items)){
				if($items===$IDPregunta){
					unset($array[$key]);
				}
			}else{
				if($items===$nomenclatura){
					unset($array[$key]);
				}
			}	
		}		
		$cadena="";
		$i=1;
		foreach ($array as $key=> $valor) {
			
			if($valor!= null && !empty($valor)){
				$cadena.=$valor.",";			    
			}
			
			
		}
		$lis=substr($cadena,0,strlen($cadena)-1);
		return $lis;
	}	
}
if(!function_exists("_media_puntos"))
{
	function _media_puntos($_puntos_obtenidos,$_puntos_posibles){
		
		if($_puntos_obtenidos===0 && $_puntos_posibles===0){
			$num=0;
		}else{
			$num=round(($_puntos_obtenidos/$_puntos_posibles)*10,2);
		}

		if($num===0){
				$_data["class"]="text-blue";
		}else if($num>0){
				$_data["class"]="text-success";
		}else if($num<0){
				$_data["class"]="text-red";
		}
		$_data["num"]=$num;
		return $_data;
	}
}
if(!function_exists('_increment'))
{
	function _increment($a,$b,$c)
	{

		
		$num=0;
		$_data=[];
		
		if(bccomp($a, $b)===0){
			$num=0;
		}else if((int)$b===0){
			$num=100;
		}else if((int)$a===0){
			$num=-100;
		}else{
			
			$num=round((((float)$a-(float)$b)/(float)$b)*100,2);
		}

		if($c==="imagen"){
			if($num===0){
				$_data["class"]="text-blue";
			}else if($num>0){
				$_data["class"]="text-success";
			}else if($num<0){
				$_data["class"]="text-red";
			}
			$_data["num"]=$num."%";

		}else{
			if($num===0){
				$_data["class"]="text-blue";
			}else if($num<0){
				$_data["class"]="text-success";
			}else if($num>0){
				$_data["class"]="text-red";
			}
			$_data["num"]=$num."%";

		}
		
		return $_data;
	}
}
if(!function_exists('_build_joson'))
{
	function _build_json($_status=FALSE,$_data=FALSE,$_controller=FALSE)
	{
		$CI= &get_instance();
		if(!(boolean)$_status)
		{
			if(isset($_data['message_identifier']))
			{
				if((boolean)$_controller)
					$_data["message"]=$CI->lang->line($CI->data["controller"].$_data["message_identifier"]);
				else
					$_data["message"]=$CI->lang->line($_data["message_identifier"]);
			}
			else
			{
					$_data["message"]=$CI->lang->line("_cannot_complete");
			}
		}
		$_data["status"]=$_status;
		exit(json_encode($_data));
	}
}
if(!function_exists('_is_ajax_request'))
{
	function _is_ajax_request()
	{
		$CI= &get_instance();
		if(!$CI->input->is_ajax_request())
			_build_json();
	}
}
if(!function_exists('_is_post'))
{
	function _is_post()
	{
		if($_SERVER['REQUEST_METHOD']!=='POST')
			_build_json();
	}	
}
if(!function_exists("_media_puntos"))
{
	function _media_puntos($_puntos_obtenidos,$_puntos_posibles){
		
		if($_puntos_obtenidos===0 && $_puntos_posibles===0){
			$num=0;
		}else{
			$num=round(($_puntos_obtenidos/$_puntos_posibles)*10,2);
		}

		if($num===0){
				$_data["class"]="text-blue";
		}else if($num>0){
				$_data["class"]="text-success";
		}else if($num<0){
				$_data["class"]="text-red";
		}
		$_data["num"]=$num;
		return $_data;
	}
}
if(!function_exists("_comentario")){
	function comentario($fechainicio,$fechafin,$veces){
		//primero verifico cuantos dias han pasado de una fecha a otra fecha
		$meses=["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
		$_fecha_inicio=explode("/",$fechainicio);
		$_fecha_fin=explode("/",$fechafin);
		
		$_mes_inicio=$meses[(int)$_fecha_inicio[1]-1];	
		$_mes_fin=$meses[(int)$_fecha_fin[1]-1];	
		
		$fecha1 = strtotime($fechainicio);
		$fecha2 = strtotime($fechafin);
		
		$diferencia=$fecha2-$fecha1;
		$dias=(( ( $diferencia / 60 ) / 60 ) / 24);
		if((int)$dias===1){
			$dias="1 día";
		}else{
			$dias="los ".round ($dias,0)." días";
		}
		if((int)$veces===1){
			$veces="una 1 vez";
		}else{
			$veces=$veces." veces";
		}
		$cadena="Los resultados están basados en $veces que fue contestado este cuestionario, en $dias que van del $_fecha_inicio[2] de $_mes_inicio del $_fecha_inicio[0] a $_fecha_fin[2] de $_mes_fin del $_fecha_fin[0]";
		return $cadena;

	}
}
if(!function_exists("dame_mes"))
{
	function dame_mes($index){
		$meses=["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
		return $meses[(int)$index-1];

	}
	

}
if(!function_exists("docemeces"))
{
function docemeces(){
	$fechas=[];
	  for($i=12;$i>=0;$i--){ 
		array_push($fechas,date("Y-m",mktime(0,0,0,date("m")-$i,date("d"),date("Y"))));
	  } 
	  return $fechas;
  }
}
if(!function_exists("docemecespasados"))
{
  function docemecespasados(){
	$fechas=[];
	  for($i=12;$i>=0;$i--){ 
		array_push($fechas,date("Y-m",mktime(0,0,0,date("m")-$i,date("d"),date("Y")-2)));
	  } 
	  return $fechas;
  }
}
if(!function_exists("_is_respcorrect"))
{
	function _is_respcorrect($respuesta_correcta,$respuesta,$calificacion,$tipopregunta){
		if($tipopregunta==="HORAS"){
			$dias=(int)$respuesta;
			$dias=1-(int)$dias/24;
			$peso=(float)$calificacion;
			$_calificacion=$peso*$dias;
			return $_calificacion;
		}
		if($tipopregunta==="SEGUNDOS"){
			$dias=(int)$respuesta;
			$dias=1-(int)$dias/60;
			$peso=(float)$calificacion;
			$_calificacion=$peso*$dias;
			return $_calificacion;
		}
		
		if($tipopregunta==="MINUTOS"){
			$dias=(int)$respuesta;
			$dias=1-(int)$dias/60;
			$peso=(float)$calificacion;
			$_calificacion=$peso*$dias;
			return $_calificacion;
		}

		if($tipopregunta==="DIAS"){
			$dias=(int)$respuesta;
			$dias=1-(int)$dias/34;
			$peso=(float)$calificacion;
			$_calificacion=$peso*$dias;
			return $_calificacion;
		}
		if($tipopregunta==="SI/NO" || $tipopregunta==="SI/NO/NA" || $tipopregunta==="SI/NO/NS"){
			if($respuesta==="NA" || $respuesta==="NS"){
				return $_calificacion=0;
			}else{
				if($respuesta_correcta!==$respuesta){
					return $_calificacion=0;
				}else{
					return $_calificacion=$calificacion;
				}
			}
		}
		
}
if(!function_exists("docemeces"))
			{
					function docemeces(){
						$fechas=[];
							for($i=12;$i>=0;$i--){ 
								array_push($fechas,date("Y-m",mktime(0,0,0,date("m")-$i,date("d"),date("Y"))));
							} 
							return $fechas;
					}
				}	
			}
