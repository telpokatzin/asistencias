<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class General extends IS_Controller {

	public function __construct() {
		parent::__construct();

		//LOAD MODELS
	}

	/**
	 * Generación del SELECT HTML
	 * Obtenemos las empresas activas del pais seleccionado
	 * @return String select HTML
	 */
	public function load_empresas_ajax() {
		$sqlData = array(
			 'id_pais_nomina' 	=> $this->input->post('id_pais_nomina')
			,'class' 			=> $this->input->post('class')
			,'multiple' 		=> $this->input->post('multiple')
			,'add_leyenda' 		=> $this->input->post('add_leyenda')
		);

		echo $this->build_select_empresas($sqlData);
	}


	/**
	 * Generación del SELECT HTML
	 * Obtenemos las sucursales de la empresa seleccionado
	 * @return String Select HTML 
	 */
	public function load_sucursales_ajax() {
		$sqlData = array(
			 'id_pais_nomina' 		=> $this->input->post('id_pais_nomina')
			,'id_empresa_nomina' 	=> $this->input->post('id_empresa_nomina')
			,'leyenda' 				=> lang('general_todos')
		);

		echo $this->build_select_sucursales($sqlData);
	}

	/**
	 * Counstruimos el select HTML de los años del calendario de captura
	 * @return [type] [description]
	 */
	public function load_anios_calendario_ajax() {
		$sqlData = array(
			 'id_pais_nomina' 	 => $this->input->post('id_pais_nomina')
			,'id_empresa_nomina' => $this->input->post('id_empresa_nomina')
		);
		
		echo $this->build_select_anios_calendario($sqlData);
	}
}

/* End of file General.php */
/* Location: ./application/controllers/General.php */