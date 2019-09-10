<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Correos extends IS_Controller {

	/**
	 * Envío de correos a usuarios
	 * @param  array  $data destinatiarios y mensage a enviar
	 * @return Boolean resultado del envío del correo
	 */
	public function send_mail($data=array()) {
		$view 		= isset($data['view']) ? $data['view'] : 'main-template';
        $MODULO 	= config_item('modulo');
        $app_title 	= get_var('app_title');
        $app_title 	.= ($MODULO ? "- $MODULO": '');


		$htmlData = array(
			 'url_image'    	=> base_url(get_var('path_img'))
			,'contenido'		=> $data['message']
			,'base_url' 		=> base_url()
			,'title' 			=> utf8_decode(get_var('site_title'))
			,'app_title' 		=> utf8_decode($app_title)
			,'no_responder' 	=> lang('mail_no_responder')
			,'anio' 			=> date('Y')
			,'mail_click_aqui' 	=> str_replace('{custom_url}', base_url(), lang('mail_click_aqui'))
		);
		$htmlTPL = $this->parser_view("mail/$view", $htmlData);
		// echo $htmlTPL;
		// exit;
	
		// Create ArrayData
		$tplData = array(
			 'body' 	=> utf8_decode($htmlTPL)
			,'tipo' 	=> 'html'
			,'asunto' 	=> utf8_decode($data['asunto'])
			,'img_pae' 	=> FCPATH.get_var('path_img') . '/logo-white.png'
			,'img_name' => 'logo-white.png'
		);

		if (isset($data['para'])) {
			$tplData['destinatarios'] = is_array($data['para']) ? $data['para'] : array(array('email' => $data['para'], 'nombre' => ''));
		}

		if (isset($data['cc'])) {
			$tplData['destinatariosCC'] = is_array($data['cc']) ? $data['cc'] : array(array('email' => $data['cc'], 'nombre' => ''));
		}

		if (isset($data['cco'])) {
			$tplData['destinatariosBCC'] = is_array($data['cco']) ? $data['cco'] : array(array('email' => $data['cco'], 'nombre' => ''));
		}

		// Send email
		$resultado = $this->mail->send($tplData);
		
		return $resultado;
	}
}

/* End of file Correos.php */
/* Location: ./application/controllers/Correos.php */