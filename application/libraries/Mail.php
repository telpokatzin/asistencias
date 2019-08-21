<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mail extends PHPMailer {
/**
* Descripcion:	Envia email usando SMTP
* Creación:		2015-08-11
* @author 		Oscar Maldonado - O3M
*/

	public $email_onoff;
	public $email_address, $email_name;
	public $email_bcc_onoff, $email_bcc;
	public $success, $resultado, $data;
	public $body, $tipo, $asunto, $adjuntos, $destinatarios, $destinatariosCc, $destinatariosBcc, $imagenes;
	private $ci;
	function __construct() {
		$this->ci =& get_instance();

        $this->email_onoff		= get_var('email_onoff') ? TRUE : FALSE; 
        $this->success 			= FALSE;

        $this->cuenta 			= get_var('email_cuenta', 1);

        $this->Host             = get_var('email_'.$this->cuenta.'_host');
        $this->email_address   	= get_var('email_'.$this->cuenta.'_address');
        $this->Username			= get_var('email_'.$this->cuenta.'_user', FALSE);
        $this->Password			= get_var('email_'.$this->cuenta.'_pass', FALSE);
        $this->Port				= get_var('email_'.$this->cuenta.'_port', FALSE);
        $this->SMTPSecure		= get_var('email_'.$this->cuenta.'_stmp_secure', FALSE);
        $this->SMTPAuth			= get_var('email_'.$this->cuenta.'_stmp_auth', FALSE);
        $this->email_name		= get_var('email_name');
        $this->email_bcc_onoff	= get_var('email_bcc_onoff') ? TRUE : FALSE;
        $this->email_bcc		= get_var('email_bcc');
        $this->email_debug		= get_var('email_debug', 0);
    }

	public function send($data=array()) {
        $mail = new PHPMailer;
		if($this->email_onoff){
			// Variables recibidas
			$this->body 				= $data['body'];
			$this->tipo 				= ($data['tipo']=='html') ? TRUE : FALSE;
			$this->asunto 				= (isset($data['asunto'])) ? $data['asunto'] : $this->email_name;
			$this->adjuntos 			= (isset($data['adjuntos'])) ? $data['adjuntos'] : FALSE;	
			$this->destinatarios 		= (isset($data['destinatarios'])) ? $data['destinatarios'] : FALSE;	
			$this->destinatariosCc  	= (isset($data['destinatariosCC'])) ? $data['destinatariosCC'] : FALSE;
			$this->destinatariosBcc 	= (isset($data['destinatariosBCC'])) ? $data['destinatariosBCC'] : FALSE;
			$this->imagenes 			= (isset($data['imagenes'])) ? $data['imagenes'] : FALSE;
			// Setup
			$mail->isSMTP();	//Establece uso de SMTP
			$mail->SMTPDebug 		= $this->email_debug; //Enable SMTP debugging :  0=>off; 1=>client msg; 2=>server & client msg
			// $mail->Debugoutput 		= 'html';
			if ($this->email_debug) {
				$date = date('Ymd-His');
				$filelog = get_var('log_path_email').'/emaillog_'.$date.'.log';
				$log = fopen($filelog, 'w' );		        
				$mail->Debugoutput = function($str) use ($filelog) {
				   error_log($str, 3, $filelog);
				};		        
				fclose($log);
			}

			// $mail->isSMTP();	//Establece uso de SMTP
			$mail->Host 			= $this->Host;
			$mail->Port 			= $this->Port;
			$mail->SMTPSecure 		= $this->SMTPSecure;
			$mail->SMTPAuth 		= $this->SMTPAuth;
			$mail->Username 		= $this->Username;
			$mail->Password 		= $this->Password;
			$mail->email_address	= $this->email_address;
			$mail->email_name 		= $this->email_name;
			$mail->email_bcc_onoff 	= ($this->email_bcc_onoff) ? TRUE : FALSE;
			$mail->email_bcc 		= $this->email_bcc;
				//print_debug($mail);		
			//Emisor Data
			$mail->setFrom($this->email_address, $this->email_name);
			//Direccion de respuesta
			$mail->addReplyTo($this->email_address, $this->email_name);
			//Receptor Data
			if(is_array($this->destinatarios)){
				foreach($this->destinatarios as $destinatario){
					$mail->addAddress($destinatario['email'], $destinatario['nombre']);
				}
			}
			// CC

			if(is_array($this->destinatariosCc)){
				foreach($this->destinatariosCc as $destinatarioCc){
					$mail->addCC($destinatarioCc['email'], $destinatarioCc['nombre']);
				}
			}
			// BCC
			if(is_array($this->destinatariosBcc)){
				foreach($this->destinatariosBcc as $destinatarioBcc){
					$mail->addBCC($destinatarioBcc['email'], $destinatarioBcc['nombre']);
				}
			}
			// Copia oculta - Acuses
			if($this->email_bcc_onoff){			
				$mail->addBCC($this->email_bcc, $this->email_bcc);
			}
			//Asunto
			$mail->Subject = $this->asunto;
			// Imagenes			
			if(is_array($this->imagenes)>0){
				foreach($this->imagenes as $imagen){
					$mail->AddEmbeddedImage(trim($imagen['ruta'],'/').'/'.$imagen['file'], $imagen['alias'],$imagen['file'], $imagen['encode'], $imagen['mime']);
				}
			}
			
			$mail->Body = $this->body;
			$mail->IsHTML($this->tipo);
    		$mail->AddEmbeddedImage($data['img_pae'], 'logoPAE', $data['img_name']);
    		$mail->AddEmbeddedImage(FCPATH.get_var('path_img') . '/somos_pae_footer.png', 'somos_pae', 'somos_pae_footer.png');

			//Texto plano alternativo al HTML
			$mail->AltBody = 'Su correo no soporta HTML, por favor, contacte a su administrador de correo.';
			//Adjunto
			if(is_array($this->adjuntos)){
				foreach($this->adjuntos as $adjunto){
					$mail->addAttachment($adjunto);
				}
			}
			// Envío de correo e imprime mensajes
			if (!$mail->send()) {
			    $respuesta = array('success' => FALSE, 'error' => $mail->ErrorInfo, 'msj' =>  $mail->ErrorInfo);
			} else {
			    $respuesta = array('success' => TRUE, 'msj' => lang("msg_succes_send_mail"));
			}
		}else{ 
			// $this->success = TRUE; 
			$respuesta = array('success' => TRUE, 'msj' => lang("msg_succes_send_mail"));
		}
		return $respuesta;
	}
}