<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Correos {

	/**
	 * Envío de correos a usuarios
	 * @param  array  $data destinatiarios y mensage a enviar
	 * @return Boolean resultado del envío del correo
	 */
	static function send_mail($data=array()) {
		$CI =& get_instance();
		$view 		= isset($data['view']) ? $data['view'] : 'main-template';
		$url_kiosko = get_cookie('url_kiosko');
		$url_system = $url_kiosko ? $url_kiosko : base_url();

		$htmlData = array(
			 'url_image'    	=> base_url(get_var('path_img'))
			,'contenido'		=> $data['message']
			,'base_url' 		=> $url_system
			,'title' 			=> utf8_decode(get_var('site_title'))
			,'app_title' 		=> utf8_decode(get_var('app_title'))
			,'no_responder' 	=> lang('mail_no_responder')
			,'anio' 			=> date('Y')
			,'mail_click_aqui' 	=> str_replace('{custom_url}', $url_system, lang('mail_click_aqui'))
		);
		$htmlTPL = $CI->parser_view("mail/$view", $htmlData);
		// echo $htmlTPL;
		// exit;
	
		// Create ArrayData
		$tplData = array(
			 'body' 	=> utf8_decode($htmlTPL)
			,'tipo' 	=> 'html'
			,'asunto' 	=> utf8_decode($data['asunto'])
			,'img_pae' 	=> FCPATH.get_var('path_img') . '/logo2.png'
			,'img_name' => 'logo2.png'
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
		$resultado = $CI->mail->send($tplData);
		
		return $resultado;
	}
}

/* End of file Correos.php */
/* Location: ./application/controllers/Correos.php */