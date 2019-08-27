<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Empresas extends IS_Controller {

	public function __construct() {
		parent::__construct();
		$this->modulo 	= 'empresas';
		$this->js 		= 'empresas';
		//Do your magic here
		$this->load->model('Empresas_model', 'db_empresas');
	}

	public function index() {
		$dataView['Empresas_empresa'] 	= lang('empresas_empresa');
		$dataView['Empresas_razon_social'] 	= lang('empresas_razon_social');
		$dataView['general_acciones'] 	= lang('general_acciones');
		$dataView['empresas_settings'] 	= lang('empresas_settings');
		$dataView['general_editar'] 	= lang('general_editar');
		$dataView['general_delete'] 	= lang('general_delete');

		$includes['js'][] = array('name'=>'list-empresas', 'dirname'=>$this->js);
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
				,'msg' 		=> lang('empresas_remove_success')
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

	public function get_modal_nueva_empresa() {
		$dataView['Empresas_nueva_empresa'] = lang('empresas_nueva_empresa');
		$dataView['Empresas_empresa'] 		= lang('empresas_empresa');
		$dataView['Empresas_razon_social'] 	= lang('empresas_razon_social');
		$dataView['general_close'] 			= lang('general_close');
		$dataView['general_save'] 			= lang('general_save');

		$this->parser_view("{$this->modulo}/modal-nueva-empresa", $dataView, FALSE);
	}

	public function process_save_empresa() {
		try {
			$sqlData = array(
				 'empresa' 		=> $this->input->post('empresa')
				,'razon_social' => $this->input->post('razon_social')
				,'id_usuario' 	=> $this->session->userdata('id_usuario')
				,'timestamp' 	=> timestamp()
			);
			$exist = $this->db_empresas->get_empresa($sqlData);
			!$exist OR setException(lang('empresas_duplicado'), lang('general_alerta'), 'warning');
			$insert = $this->db_empresas->insert_empresa($sqlData);
			$insert OR setException();
			
			$response = array(
				 'success' 	=> TRUE
				,'title' 	=> lang('general_exito')
				,'msg' 		=> lang('empresas_insert_success')
				,'type' 	=> 'success'
			);
		} catch (IS_Exception $e) {
			$response = array(
				 'success' 	=> FALSE
				,'title' 	=> $e->getTitle()
				,'msg' 		=> $e->getMessage()
				,'type' 	=> $e->getTypeMessage()
			);
		}

		echo json_encode($response);
	}

	public function get_modal_update_empresa() {
		$dataView['Empresas_update_empresa'] = lang('empresas_update_empresa');
		$dataView['Empresas_empresa'] 		 = lang('empresas_empresa');
		$dataView['Empresas_razon_social'] 	 = lang('empresas_razon_social');
		$dataView['general_close'] 			 = lang('general_close');
		$dataView['general_save'] 			 = lang('general_save');

		$sqlData = $this->input->post();
		$empresa = $this->db_empresas->get_empresa($sqlData, FALSE);
		$empresa OR setException();

		$dataView = array_merge($dataView, $empresa);
		$dataPost = array('id_empresa'=> $empresa['id_empresa']);
		$dataView['dataEncription'] = $this->encryption->encrypt(json_encode($dataPost));

		$this->parser_view("{$this->modulo}/modal-update-empresa", $dataView, FALSE);
	}

	public function process_update_empresa() {
		try {
			$sqlData = array(
				 'diferent' 		=> $this->input->post('id_empresa')
				,'empresa' 			=> $this->input->post('empresa')
				,'razon_social' 	=> $this->input->post('razon_social')
				,'id_usuario_edit' 	=> $this->session->userdata('id_usuario')
				,'timestamp' 		=> timestamp()
			);
			$exist = $this->db_empresas->get_empresa($sqlData);
			!$exist OR setException(lang('empresas_duplicado'), lang('general_alerta'), 'warning');
			unset($sqlData['diferent']);
			$sqlData['id_empresa'] = $this->input->post('id_empresa');

			$update = $this->db_empresas->update_empresa($sqlData);
			$update OR setException();
			
			$response = array(
				 'success' 	=> TRUE
				,'title' 	=> lang('general_exito')
				,'msg' 		=> lang('empresas_update_success')
				,'type' 	=> 'success'
			);
		} catch (IS_Exception $e) {
			$response = array(
				 'success' 	=> FALSE
				,'title' 	=> $e->getTitle()
				,'msg' 		=> $e->getMessage()
				,'type' 	=> $e->getTypeMessage()
			);
		}

		echo json_encode($response);
	}

	public function configuraciones() {
		$_POST OR redirect('empresas','refresh');

		#LABELS
		$dataView['empresas_contactos_rh'] 	= lang('empresas_contactos_rh');
		$dataView['empresas_turnos'] 	= lang('empresas_turnos');
		$dataView['empresas_turno'] 	= lang('empresas_turno');
		$dataView['general_nombre'] 	= lang('general_nombre');
		$dataView['general_correo'] 	= lang('general_correo');
		$dataView['general_acciones'] 	= lang('general_acciones');
		$dataView['general_editar'] 	= lang('general_editar');
		$dataView['general_delete'] 	= lang('general_delete');
		$dataView['section'] 			= lang('empresas_settings');

		#DATA
		$sqlData = $this->input->post();
		$empresa = $this->db_empresas->get_empresa($sqlData, FALSE);
		$dataView = array_merge($dataView, $empresa);
		$dataPost = array('id_empresa'=> $empresa['id_empresa']);
		$dataView['dataEncription'] = $this->encryption->encrypt(json_encode($dataPost));

		$includes['js'][] = array('name'=>'settings-crh', 'dirname'=>$this->js);
		$this->load_view("{$this->modulo}/settings", $dataView, $includes);
	}

	public function get_contacto_rh() {
		$sqlData = $this->input->post();
		$contactosRH = $this->db_empresas->get_contactosRH_empresa($sqlData);
		
		echo json_encode($contactosRH);
	}

	public function process_remove_CRH() {
		try {
			$sqlData = array(
				 'id_contacto_rh' 	=> $this->session->userdata('id_contacto_rh')
				,'id_empresa' 		=> $this->input->post('id_empresa')
				,'id_usuario_edit' 	=> $this->session->userdata('id_usuario')
				,'timestamp_edit' 	=> timestamp()
				,'activo' 			=> 0
			);
			$update = $this->db_empresas->update_contactoRH($sqlData);
			$update OR setException();

			$response = array(
				 'success' 	=> TRUE
				,'title' 	=> lang('general_exito')
				,'msg' 		=> lang('empresas_remove_crh_success')
				,'type' 	=> 'success'
			);
		} catch (IS_Exception $e) {
			$response = array(
				 'success' 	=> FALSE
				,'title' 	=> $e->getTitle()
				,'msg' 		=> $e->getMessage()
				,'type' 	=> $e->getTypeMessage()
			);
		}

		echo json_encode($response);
	}

	public function get_modal_update_CRH() {
		//LANG
		$dataView['empresas_update_empresa'] = lang('empresas_update_empresa');
		$dataView['empresas_empresa'] 		 = lang('empresas_empresa');
		$dataView['general_nombre'] 		 = lang('general_nombre');
		$dataView['general_correo'] 		 = lang('general_correo');
		$dataView['general_close'] 			 = lang('general_close');
		$dataView['general_save'] 			 = lang('general_save');

		//DATA
		$sqlData 	= $this->input->post();
		$empresa 	= $this->db_empresas->get_empresa($sqlData, FALSE);
		$contactoRH = $this->db_empresas->get_contactos_rh($sqlData, FALSE);

		$dataView = array_merge($dataView, $empresa, $contactoRH);
		$dataPost = array('id_empresa'=> $empresa['id_empresa'], 'id_contacto_rh'=> $contactoRH['id_contacto_rh']);
		$dataView['dataEncription'] = $this->encryption->encrypt(json_encode($dataPost));

		$this->parser_view("{$this->modulo}/modal-update-contacto-rh", $dataView, FALSE);
	}

	public function process_update_contacto_rh() {
		try { debug($_POST);
			$sqlData = array(
				 'diferent' 		=> $this->input->post('id_empresa')
				,'empresa' 			=> $this->input->post('empresa')
				,'razon_social' 	=> $this->input->post('razon_social')
				,'id_usuario_edit' 	=> $this->session->userdata('id_usuario')
				,'timestamp' 		=> timestamp()
			);
			$exist = $this->db_empresas->get_empresa($sqlData);
			!$exist OR setException(lang('empresas_duplicado'), lang('general_alerta'), 'warning');
			unset($sqlData['diferent']);
			$sqlData['id_empresa'] = $this->input->post('id_empresa');

			$update = $this->db_empresas->update_empresa($sqlData);
			$update OR setException();
			
			$response = array(
				 'success' 	=> TRUE
				,'title' 	=> lang('general_exito')
				,'msg' 		=> lang('empresas_update_success')
				,'type' 	=> 'success'
			);
		} catch (IS_Exception $e) {
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