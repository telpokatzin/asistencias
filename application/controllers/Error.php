<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error extends IS_Controller {

	public function error404() {
		$dataView['body_class'] = 'off-canvas-sidebar';
		$this->load_view_individual('errors/error_404', $dataView);
	}

}

/* End of file Error.php */
/* Location: ./application/controllers/Error.php */