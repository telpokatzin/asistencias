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
		$dataView['general_empresa'] 	= lang('general_empresa');
		$dataView['empresas_razon_social'] 	= lang('empresas_razon_social');
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
			$response = getException($e);
		}

		echo json_encode($response);
	}

	public function get_modal_nueva_empresa() {
		$dataView['empresas_nueva_empresa'] = lang('empresas_nueva_empresa');
		$dataView['general_empresa'] 		= lang('general_empresa');
		$dataView['empresas_razon_social'] 	= lang('empresas_razon_social');
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
			$response = getException($e);
		}

		echo json_encode($response);
	}

	public function get_modal_update_empresa() {
		$dataView['empresas_update_empresa'] = lang('empresas_update_empresa');
		$dataView['general_empresa'] 		 = lang('general_empresa');
		$dataView['empresas_razon_social'] 	 = lang('empresas_razon_social');
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
			$response = getException($e);
		}

		echo json_encode($response);
	}

	public function configuraciones() {
		$_POST OR redirect('empresas','refresh');

		#LABELS
		$dataView['general_empresa'] 		= lang('general_empresa');
		$dataView['general_razon_soc'] 		= lang('general_razon_soc');
		$dataView['empresas_contactos_rh'] 	= lang('empresas_contactos_rh');
		$dataView['empresas_turnos'] 		= lang('empresas_turnos');
		$dataView['empresas_turno'] 		= lang('empresas_turno');
		$dataView['general_nombre'] 		= lang('general_nombre');
		$dataView['general_correo'] 		= lang('general_correo');
		$dataView['general_hora_entrada'] 	= lang('general_hora_entrada');
		$dataView['general_hora_salida'] 	= lang('general_hora_salida');
		$dataView['general_acciones'] 		= lang('general_acciones');
		$dataView['general_undefined'] 		= lang('general_undefined');
		$dataView['empresas_message1'] 		= lang('empresas_message1');
		$dataView['general_editar'] 		= lang('general_editar');
		$dataView['general_delete'] 		= lang('general_delete');
		$dataView['section'] 				= lang('empresas_settings');

		#DATA
		$sqlData = $this->input->post();
		$empresa = $this->db_empresas->get_empresa($sqlData, FALSE);
		$config  = $this->db_empresas->get_config_empresas($sqlData);

		$dataView['dias-habiles'] = self::get_dias_habiles_empresa($config);
		$dataView = array_merge($dataView, $empresa, $config);
		$dataPost = array('id_empresa'=> $empresa['id_empresa']);
		$dataView['dataEncription'] = $this->encryption->encrypt(json_encode($dataPost));

		$includes['js'][] = array('name'=>'settings', 'dirname'=>$this->js);
		$includes['js'][] = array('name'=>'settings-crh', 'dirname'=>$this->js);
		$includes['js'][] = array('name'=>'settings-turnos', 'dirname'=>$this->js);
		$this->load_view("{$this->modulo}/settings", $dataView, $includes);
	}

	public function get_dias_habiles_empresa($settings=FALSE, $return=TRUE) {
		#LANG
		$dataView['general_dias_habiles'] 	= lang('general_dias_habiles');
		$dataView['general_undefined'] 		= lang('general_undefined');

		#DATA
		if (!$settings) {
			$sqlData = $this->input->post();
			$settings  = $this->db_empresas->get_config_empresas($sqlData);
		}

		$dataView['dias_habiles'] = array();
		if (isset($settings['dias_habiles'])) {
			$dias_habiles = explode(',', $settings['dias_habiles']);
			foreach ($dias_habiles as $weekDay) {
				$dataView['dias_habiles'][] = [
					 'day' 		=> lang("general_dia_{$weekDay}")
					,'shortDay' => lang("general_dia_{$weekDay}_short")
				];
			}
		}

		$dias_habiles = $this->parser_view("{$this->modulo}/list-dias-habiles-empresa", $dataView);
		if($return) return $dias_habiles;

		echo $dias_habiles;
	}

	public function get_contacto_rh() {
		$sqlData = $this->input->post();
		$contactosRH = $this->db_empresas->get_contactos_rh($sqlData);
		
		echo json_encode($contactosRH);
	}

	public function process_remove_CRH() {
		try {
			$sqlData = array(
				 'id_contacto_rh' 	=> $this->input->post('id_contacto_rh')
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
			$response = getException($e);
		}

		echo json_encode($response);
	}

	public function process_update_CRH() {
		try { 
			$sqlData = array(
				 'diferent' 		=> $this->input->post('id_contacto_rh')
				,'id_empresa' 		=> $this->input->post('id_empresa')
				,'nombre' 			=> $this->input->post('nombre')
				,'correo' 			=> $this->input->post('correo')
				,'id_usuario_edit' 	=> $this->session->userdata('id_usuario')
				,'timestamp' 		=> timestamp()
			);
			$exist = $this->db_empresas->get_contactos_rh($sqlData);
			!$exist OR setException(lang('empresas_crh_duplicado'), lang('general_alerta'), 'warning');
			unset($sqlData['diferent']);
			$sqlData['id_contacto_rh'] = $this->input->post('id_contacto_rh');

			$update = $this->db_empresas->update_contactoRH($sqlData);
			$update OR setException();
			
			$response = array(
				 'success' 	=> TRUE
				,'title' 	=> lang('general_exito')
				,'msg' 		=> lang('empresas_crh_update_success')
				,'type' 	=> 'success'
			);
		} catch (IS_Exception $e) {
			$response = getException($e);
		}

		echo json_encode($response);
	}

	public function get_modal_nuevo_CRH() {
		//LANG
		$dataView['empresas_contactos_rh'] 	= lang('empresas_contactos_rh');
		$dataView['general_nombre'] 		= lang('general_nombre');
		$dataView['general_correo'] 		= lang('general_correo');
		$dataView['empresas_add_contactorh']= lang('empresas_add_contactorh');
		$dataView['general_close'] 			= lang('general_close');
		$dataView['general_acciones'] 		= lang('general_acciones');

		//DATA
		$sqlData 		= $this->input->post();
		$colaboradores 	= $this->db_empresas->get_colaboradores_noCRH($sqlData);
		$dataView['dataTable'] = $colaboradores;

		$this->parser_view("{$this->modulo}/modal-nuevo-contacto-rh", $dataView, FALSE);
	}

	public function process_save_contacto_rh() {
		try {
			$sqlData = array(
				 'id_empresa' 		=> $this->input->post('id_empresa')
				,'id_empleado' 		=> $this->input->post('id_empleado')
				,'id_usuario_edit' 	=> $this->session->userdata('id_usuario')
				,'timestamp_edit' 	=> timestamp()
				,'activo' 			=> 1
			);
			$update = $this->db_empresas->update_contactoRH($sqlData);
			if (!$update) {
				unset($sqlData['id_usuario_edit'], $sqlData['timestamp_edit']);
				$sqlData['id_usuario'] 	= $this->session->userdata('id_usuario');
				$sqlData['timestamp'] 	= timestamp();
				$insert = $this->db_empresas->insert_contactoRH($sqlData);
				$insert OR setException();
			}
			
			$response = array(
				 'success' 	=> TRUE
				,'title' 	=> lang('general_exito')
				,'msg' 		=> lang('empresas_insert_crh_success')
				,'type' 	=> 'success'
			);
		} catch (IS_Exception $e) {
			$response = getException($e);
		}

		echo json_encode($response);
	}

	public function get_turnos_empresa() {
		$sqlData = $this->input->post();
		$turnos = $this->db_empresas->get_turnos($sqlData);
		
		echo json_encode($turnos);
	}

	public function process_remove_turno() {
		try {
			$sqlData = array(
				 'id_turno' => $this->input->post('id_turno')
				,'id_empresa' 		=> $this->input->post('id_empresa')
				,'id_usuario_edit' 	=> $this->session->userdata('id_usuario')
				,'timestamp_edit' 	=> timestamp()
				,'activo' 			=> 0
			);
			$update = $this->db_empresas->update_turno($sqlData);
			$update OR setException();

			$response = array(
				 'success' 	=> TRUE
				,'title' 	=> lang('general_exito')
				,'msg' 		=> lang('empresas_remove_turno_success')
				,'type' 	=> 'success'
			);
		} catch (IS_Exception $e) {
			$response = getException($e);
		}

		echo json_encode($response);
	}

	public function get_modal_update_turno() {
		//LANG
		$dataView['empresas_update_empresa'] = lang('empresas_update_empresa');
		$dataView['empresas_turno'] 		 = lang('empresas_turno');
		$dataView['general_hora_entrada'] 	 = lang('general_hora_entrada');
		$dataView['general_hora_salida'] 	 = lang('general_hora_salida');
		$dataView['empresas_tolerancia'] 	 = lang('empresas_tolerancia');
		$dataView['empresas_message2'] 	 	 = lang('empresas_message2');
		$dataView['general_close'] 			 = lang('general_close');
		$dataView['general_save'] 			 = lang('general_save');

		//DATA
		$sqlData = $this->input->post();
		$turno 	 = $this->db_empresas->get_turnos($sqlData, FALSE);

		$dataView = array_merge($dataView, $turno);
		$dataPost = array('id_empresa'=> $sqlData['id_empresa'], 'id_turno'=> $sqlData['id_turno']);
		$dataView['dataEncription'] = $this->encryption->encrypt(json_encode($dataPost));

		$this->parser_view("{$this->modulo}/modal-update-turno", $dataView, FALSE);
	}

	public function get_modal_dias_habiles() {
		#LABELS
		$dataView['general_dias_habiles'] 		= lang('general_dias_habiles');
		$dataView['empresas_indicar_dia_habil'] = lang('empresas_indicar_dia_habil');
		$dataView['general_close'] 				= lang('general_close');
		$dataView['general_save'] 				= lang('general_save');

		#DATA
		$sqlData = $this->input->post();
		$dias_semana 	= $this->db_catalogos->get_dias_semana();
		$config_empresa = $this->db_empresas->get_config_empresas($sqlData);
		$dataView = array_merge($dataView, $config_empresa);
		$dataView['dias_semana'] = $dias_semana;

		$this->parser_view("{$this->modulo}/modal-dias-habiles", $dataView, FALSE);
	}

	public function process_save_dias_habiles() {
		try {
			$dias_habiles = $this->input->post('dias_habiles');
			
			$sqlData = array(
				 'id_empresa' 		=> $this->input->post('id_empresa')
				,'dias_habiles' 	=> implode(',', $dias_habiles)
				,'id_usuario_edit' 	=> $this->session->userdata('id_usuario')
				,'timestamp_edit' 	=> timestamp()
			);
			$update = $this->db_empresas->update_config_empresa($sqlData);
			$update OR setException();

			$response = array(
				 'success' 	=> TRUE
				,'title' 	=> lang('general_exito')
				,'msg' 		=> lang('general_save_success')
				,'type' 	=> 'success'
			);
			
		} catch (IS_Exception $e) {
			$response = getException($e);
		}

		echo json_encode($response);
	}

	public function process_save_turno_empresa() {
		try {
			$sqlData = array(
				 'id_empresa' 		=> $this->input->post('id_empresa')
				,'id_turno' 		=> $this->input->post('id_turno')
				,'id_usuario_edit' 	=> $this->session->userdata('id_usuario')
				,'timestamp' 		=> timestamp()
			);
			$update = $this->db_empresas->update_config_empresa($sqlData);
			$update OR setException();
			
			$response = array(
				 'success' 	=> TRUE
				,'title' 	=> lang('general_exito')
				,'msg' 		=> lang('empresas_turno_asignado_success')
				,'type' 	=> 'success'
			);
		} catch (IS_Exception $e) {
			$response = getException($e);
		}

		echo json_encode($response);
	}

	public function process_update_turno() {
		try {
			$sqlData = array(
				 'diferent' 		=> $this->input->post('id_turno')
				,'id_empresa' 		=> $this->input->post('id_empresa')
				,'turno' 			=> $this->input->post('turno')
				,'entrada' 			=> $this->input->post('entrada')
				,'salida' 			=> $this->input->post('salida')
				,'tolerancia' 		=> $this->input->post('tolerancia')
				,'id_usuario_edit' 	=> $this->session->userdata('id_usuario')
				,'timestamp' 		=> timestamp()
			);
			$exist = $this->db_empresas->get_turnos($sqlData);
			!$exist OR setException(lang('empresas_turno_duplicado'), lang('general_alerta'), 'warning');
			unset($sqlData['diferent']);
			$sqlData['id_turno'] = $this->input->post('id_turno');

			$update = $this->db_empresas->update_turno($sqlData);
			$update OR setException();
			
			$response = array(
				 'success' 	=> TRUE
				,'title' 	=> lang('general_exito')
				,'msg' 		=> lang('empresas_turno_update_success')
				,'type' 	=> 'success'
			);
		} catch (IS_Exception $e) {
			$response = getException($e);
		}

		echo json_encode($response);
	}
}

/* End of file Empresas.php */
/* Location: ./application/controllers/Empresas.php */