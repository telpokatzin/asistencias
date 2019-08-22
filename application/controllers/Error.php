<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error extends IS_Controller {

	public function error404() {
		$this->parser_view('errors/error_404', array(), FALSE);
	}

}

/* End of file Error.php */
/* Location: ./application/controllers/Error.php */