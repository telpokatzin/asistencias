<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('debug')) {
	/**
	 * Funcion para imprimir el debug php en 2 formas
	 * 1.- print_r 
	 * 2.- var_dump
	 * defaul 1
	 * @param $data Datos a imprimir cualquier tipo de dato.
	 * @param INT $type Forma de mostrar el dato var_dump o print_r
	 * @param Bollean $die bandera para finalizar el proceso TRUE/FALSE
	 */
	function debug($data, $type = 1, $die = TRUE) {
		echo "<pre>";
		if ($type === 2) {
			var_dump($data);
		} else {
			print_r($data);
		}
		echo "</pre>";

		$die AND die();
	}
}

if(!function_exists('LogTxt')){
	function LogTxt($userData=array(), $filepath='') {
		$CI 	=& get_instance();
		$config =& get_config();

		$ip_loc = '';
		$filepath= ($filepath ? $filepath : $config['log_path_access']);
		file_exists($filepath) OR mkdir($filepath, 0755, TRUE);
		
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
			if($pos=strpos($_SERVER["HTTP_X_FORWARDED_FOR"], " ")) {
				$ip_loc = substr($_SERVER["HTTP_X_FORWARDED_FOR"],0,$pos);
			}
	    }

		$archivo = "$filepath/log_$userData[id_empresa]_".date("Ymd").".$config[log_file_extension]";
		$fp = fopen($archivo, "a+");

		$txtData = array(
			 'FECHA' 			=> date("d-m-Y H:i:s")
			,'ID_USUARIO' 		=> $userData['id_usuario']
			,'ID_LLAVE_GLOBAL' 	=> $userData['id_llave_global']
			,'EXTERNO' 			=> $userData['externo']
			,'NOMBRE' 			=> $userData['nombre_completo']
			// ,'ID_PERFIL' 		=> $userData['id_perfil']
			,'ID_PAIS_NOMINA'	=> $userData['id_pais_nomina']
			,'ID_EMPRESA_NOMINA'=> $userData['id_empresa_nomina']
			,'IP_PUBLICA' 		=> $CI->input->ip_address()
			,'IP_LOCAL' 		=> $ip_loc
			,'NOMBRE_PC' 		=> $_SERVER['HTTP_HOST']
			,'NAVEGADOR' 		=> $_SERVER['HTTP_USER_AGENT']
			,'URL_ANTERIOR' 	=> (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : ''
			,'URL_ACTUAL' 		=> $_SERVER['PHP_SELF']
			,'URL_PARAMS'		=> (isset($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : ''
		);
		$log  = implode('|',$txtData);
		$log .= "\r\n";			
		$write = fputs($fp, $log);
		fclose($fp);
	}
}

if ( ! function_exists('set_exception')) {
	/**
	 * generamos la excepcion 
	 * @param String $msg description
	 * @param Intedger $code description
	 * @param Boolean $previous description
	 **/
	function setException($message='', $title=NULL, $typeMsg=NULL, $class=NULL) {
		throw new IS_Exception($message, $title, $typeMsg, $class);
	}
}

if ( ! function_exists('getException')) {
	/**
	 * Obtenemos la excepcion para mandale al usuario el error generado
	 * @param String $exception
	 **/
	function getException($exception) {
		return [
			 'success' 	=> FALSE
			,'title' 	=> $exception->getTitle()
			,'msg' 		=> $exception->getMessage()
			,'type' 	=> $exception->getTypeMessage()
		];
	}
}


if ( ! function_exists('timestamp')) {
	function timestamp() {
		return date('Y-m-d H:i:s');
	}
}

if(!function_exists('sanitizar_string')) {
	/**
	 * Reemplazó de caracteres especiales
	 */
	function sanitizar_string($string) {
	    $string = trim($string);
	 
	    $string = str_replace(
	        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
	        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
	        $string
	    );
	 
	    $string = str_replace(
	        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
	        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
	        $string
	    );
	 
	    $string = str_replace(
	        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
	        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
	        $string
	    );
	 
	    $string = str_replace(
	        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
	        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
	        $string
	    );
	 
	    $string = str_replace(
	        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
	        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
	        $string
	    );
	 
	    $string = str_replace(
	        array('ñ', 'Ñ', 'ç', 'Ç'),
	        array('n', 'N', 'c', 'C',),
	        $string
	    );
	 
	    $string = str_replace(
	        array("\\", "º", "-", "~",
	             "#", "@", "|", "!", "\"",
	             "·", "$", "%", "&", "/",
	             "(", ")", "?", "'", "¡",
	             "¿", "[", "^", "<code>", "]",
	             "+", "}", "{", "¨", "´",
	             ">", "< ", ";", ",", ":"),
	        '-',
	        $string
	    );
	    
		return $string;
	}
}

if(!function_exists('timestamp_complete')) {
    /**
    * Devuelve la fecha larga de un timestamp determinado
    * @param string $timestamp
    * @param bool $time
    * @return string
    */
    function timestamp_complete($timestamp="" , $time = FALSE, $muestra_day= FALSE, $mont_day_yaer=FALSE, $format = FALSE) {
		// Crea fecha larga i.e: Miércoles 06 de Mayo del 2015
		//dump_var($timestamp);
    	if($timestamp==""){
    		$str_dia= lang('general_dia_'.date('w'));
			$dia	= date("d");
			$mes 	= lang('general_mes'.date("n"));
			$anio 	= date("Y");

			if($time) {
				$time = date('H:i:s');
				return "$str_dia $dia ". sprintf(lang('general_timestamp_string'),$mes, $anio, $time);
			}

			if($muestra_day) {
				return "$dia / ".$mes." / ".$anio;
			}

			if($mont_day_yaer) {
				return $mes.' '.$dia.', '.$anio;	
			}

			if($format) {
				return $dia.' de '.$mes.' de '.$anio;	
			}

			return "$dia ". sprintf(lang('general_fecha_actual'),$mes, $anio);

		}else{
			$timestamp = explode(' ', $timestamp);
			$time      = ($time AND isset($timestamp[1])) ? $timestamp[1] : '';
			$date      = explode('-', $timestamp[0]);
			$strDate   = strtotime($timestamp[0]);
			$day       = get_days(date('w', $strDate));
			$month     = get_months(date('n', $strDate));

			if($muestra_day){
				return "$day ".$date[2]." ". sprintf(lang('general_timestamp_string'), $month, $date[0], $time);
			}
			if($mont_day_yaer){
				return $month.' '.$date[2].", ".$date[0];
			}
			
			return $date[2]." ". sprintf(lang('general_timestamp_string'), $month, $date[0], $time);
		}
	}
}

if(!function_exists('get_days')) {
    /**
    * Devuelve el item del dia con respecto al indice $index,
    * si el $index no se define devolvera un array con todos los dias
    * @param int $index
    * @return array
    */
    function get_days($index = FALSE, $sub = TRUE, $short=FALSE) {
    	$short = ($short ? '_short' : '');

		if($sub){
			$days[0]= lang("general{$short}_dia_0");
			$days[1]= lang("general{$short}_dia_1");
			$days[2]= lang("general{$short}_dia_2");
			$days[3]= lang("general{$short}_dia_3");
			$days[4]= lang("general{$short}_dia_4");
			$days[5]= lang("general{$short}_dia_5");
			$days[6]= lang("general{$short}_dia_6");
			
		}else{
			$days[0]= lang("general{$short}_dia_1");
			$days[1]= lang("general{$short}_dia_2");
			$days[2]= lang("general{$short}_dia_3");
			$days[3]= lang("general{$short}_dia_4");
			$days[4]= lang("general{$short}_dia_5");
			$days[5]= lang("general{$short}_dia_6");
			$days[6]= lang("general{$short}_dia_0");
		}

		if($index){
			return $days[ltrim($index,'0')];
		}

		return $days;
    }
}

if(!function_exists('get_months')) {
    /**
    * Devuelve el item del mes con respecto al indice $index,
    * si el $index no se define devolvera un array con todos los meses
    * @param int $index
    * @return array
    */
    function get_months($index = FALSE, $short=FALSE) {
    	$short = ($short ? '_short' : '');
    	$index AND $index 	= ltrim($index, '0');
    	$index OR $index 	= 0;

		$months[0]  = '';
		$months[1]  = lang("general{$short}_mes1");
		$months[2]  = lang("general{$short}_mes2");
		$months[3]  = lang("general{$short}_mes3");
		$months[4]  = lang("general{$short}_mes4");
		$months[5]  = lang("general{$short}_mes5");
		$months[6]  = lang("general{$short}_mes6");
		$months[7]  = lang("general{$short}_mes7");
		$months[8]  = lang("general{$short}_mes8");
		$months[9]  = lang("general{$short}_mes9");
		$months[10] = lang("general{$short}_mes10");
		$months[11] = lang("general{$short}_mes11");
		$months[12] = lang("general{$short}_mes12");
		
		if ($index) return $months[$index];
		
		return $months;
    }
}

if ( ! function_exists('getMeses')) {
	/**
	 * Obtenemos los datos de los meses del año
	 * @param  integer $mes  Mes que se necesita la información
	 * @param  boolean $anio Año de los meses a generar, DEFAULT año actual
	 * @return Array $meses
	 */
	function getMeses($mes=1, $anio=FALSE, $limit=12) {
		!$anio AND $anio = date('Y');
		$meses 	= array();

		foreach (range(1, 12) as $_mes) {
			if ($limit < $_mes) break;

			$month 		= "$anio-$_mes";
			$tmp 		= date('Y-m-d', strtotime("$month + 1 month"));
			$last_day 	= date('Y-m-d', strtotime("$tmp - 1 day"));

			$meses[$_mes] = array(
	             'fecha_inicio' => $month.'-01'
	            ,'fecha_fin'    => "$month-$last_day"
	            ,'mes'          => str_pad($_mes, 2, '0', STR_PAD_LEFT)
	            ,'mes_texto'    => strtoupper(lang('general_mes'.$_mes))
	            ,'fechas'       => strtoupper(lang('general_mes'.$_mes))
			);
		}

		return $mes ? $meses[$mes] : $meses;
	}
}

if ( ! function_exists('array_msort')) {
	// Regresa un arreglo multidimensional ordenado por las columnas indicadas:
	// array_msort($dataArray, array('campo1'=>SORT_ASC, 'campo2'=>SORT_DESC));
	function array_msort($array, $cols){
	    $colarr = array();
	    foreach ($cols as $col => $order) {
	        $colarr[$col] = array();
	        foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
	    }
	    $eval = 'array_multisort(';
	    foreach ($cols as $col => $order) {
	        $eval .= '$colarr[\''.$col.'\'],'.$order.',';
	    }
	    $eval = substr($eval,0,-1).');';
	    eval($eval);
	    $ret = array();
	    foreach ($colarr as $col => $arr) {
	        foreach ($arr as $k => $v) {
	            $k = substr($k,1);
	            if (!isset($ret[$k])) $ret[$k] = $array[$k];
	            $ret[$k][$col] = $array[$k][$col];
	        }
	    }
	    return $ret;
	}
}

if ( ! function_exists('remove_eol')) {
	/**
	 * Removelos los daltos de linea|tabuladores de un String
	 * @param  String|Array $data 
	 */
	function remove_eol(&$data) {
	    if (is_array($data)) {
	    	foreach ($data as &$val) {
				$val = str_replace(array("\r\n\t", "\r", "\n", "\t"), '', $val);
	    	}
	    } else {
	    	$data = str_replace(array("\r\n\t", "\r", "\n", "\t"), '', $data);
	    }
	}
}

if ( ! function_exists('is_root')) {
	function is_root() {
		$CI 	=& get_instance();
		$perfil = $CI->session->userdata('perfil');

		return (md5(strtolower($perfil)) == '63a9f0ea7bb98050796b649e85481845');
	}
}

if ( ! function_exists('sec_to_time')) {
	/**
	 * Obtenemos el total de horas:minutos de los parametros obtenidos
	 * @param  integer $seconds total de segundos a convertir en hrs
	 * @param  integer $minuts  total de minutos a convertir en hrs
	 * @param  integer $hours   total de horas a sumar con los minutos y segundos
	 * @return String HRS
	 *
	 * @example sec_to_time(3600, 80, 1) = 3:20
	 */
	function sec_to_time($seconds=0, $minuts=0, $hours=0) {
		$minutos = '';
		
		//OBTENEMOS LAS HORAS DE LOS MINUTOS RECIBIDOS
		if ($minuts) {
			$hours 	 += floor($minuts / 60);
			$seconds += (($minuts % 60)* 60);
		}
		
		//OBTENEMOS LAS HORAS|MINUTOS DE LOS SEGUNDOS RECIBIDOS
		if ($seconds) {
			$hours += floor($seconds / 3600);
			$minutos = ':'.str_pad((($seconds % 3600)/60), 2, '0', STR_PAD_LEFT);
		}

		return "{$hours}{$minutos}";
	}
}

if ( ! function_exists('create_dir')) {
	function create_dir($data) {
		$folder_name 	= strtolower($data['folder_name']);
		$folder_name 	= sanitizar_string($folder_name);
		$folder_name 	= trim(str_replace(array('-', '__'), '_', $folder_name), '_');
		$timestamp 		= date('YmdHis');
		$path 			= isset($data['path']) ? $data['path'] : FCPATH;
		
		$_dir = $path. get_var('path_tmp')."/{$folder_name}_{$timestamp}";
		if(!is_dir($_dir)) {
			mkdir($_dir, 0777, TRUE);
		}

		return array(
			 'ruta_completa' => $_dir
			,'directory' 	 => get_var('path_tmp')."/{$folder_name}_{$timestamp}"
			,'folder_name' 	 => "{$folder_name}_{$timestamp}"
		);
	}
}

if ( ! function_exists('delete_directory')) {
	function delete_directory($data) {
		$folder_name = isset($data['folder_name']) ? $data['folder_name'] : 'tmp';
		$path 		 = isset($data['path']) ? $data['path'] : FCPATH;
		$dirname 	 = $path. get_var('path_tmp')."/$folder_name";

		if (is_dir($dirname)) {
           $dir_handle = opendir($dirname);
		    while($file = readdir($dir_handle)) {
		        if ($file != "." && $file != "..") {
		            if (!is_dir($dirname."/".$file))
		                unlink($dirname."/".$file);
		            else delete_directory($dirname.'/'.$file);
		        }
		    }

	    	closedir($dir_handle);
	    	rmdir($dirname);
    		return TRUE;
		}

		return TRUE;
	}
}

if ( !function_exists('time_to_porcentaje')) {
	function time_to_porcentaje($time=FALSE) {
		$response = '-';
		if ($time AND strstr($time, ':')) {
			list($horas, $minutos) = explode(':', $time);
			$horas = ltrim($horas, '0');
			$min = 60;
			$por = 100;

			$porcentaje = round((($minutos*$por) / $min), 0);
			$response = "$horas.$porcentaje";
		}

		return $response;
	}
}

if ( !function_exists('add_prefix')) {
	function add_key_prefix($data=array(), $prefix='_') {
		$response = array();
		foreach ($data as $key => $value) {
			if (is_array($value)) {
				$response[$key] = add_key_prefix($value, $prefix);
			} else {
				$response["{$prefix}_$key"] = $value;
			}
		}

		return $response;
	}
}

if ( !function_exists('coalesce')) {
	function coalesce() {
		$response 	= NULL;
		$argList 	= func_get_args();

		foreach ($argList as $value) {
			if (!is_null($value)) {
				$response = $value;
				break;
			}
		}

		return $response;
	}
}

/* End of file System_helper.php */
/* Location: ./application/helpers/System_helper.php */