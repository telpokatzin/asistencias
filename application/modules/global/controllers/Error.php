<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error extends IS_Controller {

	public function error404() {
		$data_view['heading'] = lang('error_404_titulo');
		$data_view['message'] = lang('error_404_mensaje');

		$this->parser_view('errors/html/error_404', $data_view, FALSE);
	}

	public function error_ie() {
		$data_view['heading'] = lang('error_ie_titulo');
		$data_view['message'] = lang('error_ie_mensaje');

		$this->parser_view('errors/html/error_general', $data_view, FALSE);
	}
}

/* End of file Error.php */
/* Location: ./application/controllers/Error.php */