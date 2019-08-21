<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends IS_Controller {

	public function __construct() {
		parent::__construct();
	}

    public function index() {
    	// debug($this->session->userdata());
    	$dataView['section'] = lang('menu_inicio');
    	
		$this->load_view('inicio', $dataView);
	}

}

/* End of file Inicio.php */
/* Location: ./application/controllers/Inicio.php */