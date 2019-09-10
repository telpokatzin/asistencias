<?php if (!defined( 'BASEPATH')) exit('No direct script access allowed'); 
class Check_session {

	/**
	* Validación de sessión y acceso a controladores sin session
	*/
	public function check_authorized_sites() {
		$CI =& get_instance();

		$uriString 			= $this->query_uri_string($CI);
		$isLogged 			= $CI->session->userdata('isLogged');
		$sites_availables 	= array();

		//Si la peticion es parte de la autenticación ó un API REST, 
		//agregamos a la lista de controladores permitidos
		if (strstr($uriString, 'auth') || strstr($uriString, 'apis/') || strstr($uriString, 'pruebas') || $isLogged) {
			array_push($sites_availables, $uriString);
		}

		// Controladores especificos permitidos sin sessión de usuario
		$sites_availables[] = '';
		$sites_availables[] = 'error';
		$sites_availables[] = 'error-ie';

		// Valida que exista la sesion y bloquea login
		if(($uriString === '' ||  $uriString === 'global/login') && $isLogged) { 
			redirect(base_url('inicio'));
		}

		// Verifica los controladores permitidos para cargar
		if(in_array($uriString, $sites_availables)) {
			return false;
		}

		//SI ES UNA PETICIÓN AJAX Y SE ACABO LA SESSION, RETORNA EL ESTATUS 401
        if (!$isLogged AND $CI->input->is_ajax_request()) {
        	set_status_header(401);
            die();
        }

		#Si no existe sesión reenvía a login
		// $CI->session->sess_destroy();
		// redirect(base_url());
	}

	private function query_uri_string($CI) {
		// Retorna el nombre del controlador al que se accesa vía URI
		$new_uri  	= "";
		$uriString  = $CI->uri->uri_string();		
		$uriString  = explode('/', $uriString);
		foreach($uriString as $value){
			if(!is_numeric($value)){
				$new_uri .= $value.'/';
			}
		}
		return trim($new_uri, '/');
	}
}