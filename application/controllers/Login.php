<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends IS_Controller {

	public function __construct() {
		parent::__construct();
		$this->js_dir = 'js';

        $this->load->model('login_model', 'db_login');
	}

	public function index() {
        $this->check_userAuthentication(TRUE);
	}

    public function logout() {
        $this->session->sess_destroy();
        redirect(base_url(), 'refresh');
    }

    /**
     * AUTENTICACION DEL USARIO
     * @return JSON result
     */
    public function auth() {
        try {
            $_POST OR setException(lang('login_clave_wrong'));

            //Busca el usuarios
            $sqlData  = $this->input->post();
            $userData = $this->db_login->get_autentication($sqlData);
            //enviamos error de usuario|contraseña
            count($userData) OR setException(lang('login_clave_wrong'), lang('general_alerta'), 'warning');

            //GENERAMOS LA SESSION
            self::setSession($userData);
            $response = array(
                 'success'  => TRUE
                ,'redirect' => 'inicio'
            );
        } catch (IS_Exception $e) {
            // Sin autorización
            $response = array(
                 'success'  => FALSE
                ,'title'    => $e->getTitle()
                ,'msg'      => $e->getMessage()
                ,'type'     => $e->getTypeMessage()
            );
        }

        echo json_encode($response);
    }

	private function setSession($userData=array()) {
    	// Establece los datos de la sesión de usuario
        $this->session->set_userdata(
        	array(
                 'id_usuario'        => $userData['id_usuario']
                ,'language'          => 'mx'
                ,'id_empresa'        => $userData['id_empresa']
                ,'empresa'           => $userData['empresa']
                ,'nombre_completo'   => $userData['full_name']
                ,'default_url'       => 'inicio'
                ,'correo'            => $userData['correo']
                ,'id_perfil'         => $userData['id_perfil']
                ,'isLogged'          => TRUE
                ,'is_root'           => ($userData['id_perfil'] == 1)
            )
        );

        return TRUE;
    }
}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */