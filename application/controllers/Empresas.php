<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Empresas extends IS_Controller {

	public function __construct() {
		parent::__construct();
		$this->modulo 	= 'empresas';
		$this->js 		= '';
		//Do your magic here
		$this->load->model('Empresas_model', 'db_empresas');
	}

	public function index() {
		$dataView['Empresas_empresa'] 	= lang('Empresas_empresa');
		$dataView['general_acciones'] 	= lang('general_acciones');
		$dataView['general_editar'] 	= lang('general_editar');
		$dataView['general_delete'] 	= lang('general_delete');

		$includes['js'][] = array('name'=>'empresas', 'dirname'=>$this->js);
		$this->load_view("{$this->modulo}/list-empresas", $dataView, $includes);
	}

	public function get_empresas_ajax() {
		$data = $this->db_catalogos->get_empresas();

        echo json_encode($data);
	}

	public function process_remove_empresa() {
		try {
			$this->db->trans_begin();
			$sqlData = array(
				 'id_empresa' 		=> $this->input->post('id_empresa')
				,'id_usuario_edit' 	=> $this->session->userdata('id_usuario')
				,'timestamp_edit' 	=> timestamp()
				,'activo' 			=> 0
			);
			$update = $this->db_empresas->update_empresa($sqlData);
			$update OR setException();

			$response = array(
				 'success' 	=> TRUE
				,'title' 	=> lang('general_exito')
				,'msg' 		=> lang('Empresas_remove_success')
				,'type' 	=> 'success'
			);
			
			$this->db->trans_commit();
		} catch (IS_Exception $e) {
			$this->db->trans_rollback();
			$response = array(
				 'success' 	=> FALSE
				,'title' 	=> $e->getTitle()
				,'msg' 		=> $e->getMessage()
				,'type' 	=> $e->getTypeMessage()
			);
		}

		echo json_encode($response);
	}
}

/* End of file Empresas.php */
/* Location: ./application/controllers/Empresas.php */