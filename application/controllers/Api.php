<?php 
 @include_once(APPPATH.'core/IS_Rest.php');

class Api extends IS_Rest {

	public function __construct() {
		parent::__construct();

		//Do your magic here
        $this->load->model('login_model', 'db_login');
		$this->load->model('Empresas_model', 'db_empresas');
	}
    
    public function index() {
    	return FALSE;
    }

    public function login() {
    	try {
            $_POST OR setException(lang('login_clave_wrong'));

            //Busca el usuarios
            $sqlData  = $this->input->post();
            $userData = $this->db_login->get_autentication($sqlData);
            //enviamos error de usuario|contraseña
            count($userData) OR setException(lang('login_clave_wrong'), lang('general_alerta'), 'warning');

            //GENERAMOS LA SESSION
            $response = array(
                 'success'  => TRUE
                ,'data' 	=> $userData
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

        return $this->response->json($response, 200);
    }
}