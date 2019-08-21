<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pruebas extends Is_Controller {

	public function __construct() {
		parent::__construct();
		//Do your magic here
	}

	public function index() {
		echo "<ul>
			<li><a href='".base_url('pruebas/email')."' target='_blank'>Enviar Correo</a></li>
			<li><a href='".base_url('pruebas/excel')."' target='_blank'>Gerenar Archivo Excel</a></li>
			<li><a href='".base_url('pruebas/pdf')."' target='_blank'>Generar Archivo PDF</a></li>
		</ul>";
	}

	/**
	 * Prueba de envío de correo
	 * @return [type] [description]
	 */
	public function email() {
		$data = array(
			 // 'message' 	=> 'Correo de Pruebas'
			 'message' 	=> $this->load_view_unique("mail/prueba", $this->session->userdata(), TRUE)
			,'titulo' 	=> 'Pruebas'
			,'view' 	=> 'main-template'
			,'asunto' 	=> 'Pruebas'
			,'para' 	=> 'laura.barranco@isolution.mx'
		);

		// Send email
		$resultado = Correos::send_mail($data);
		if($resultado['success']){
			$msj = "Correo enviado OK: ".date("Y-m-d H:i:s") . '<br>'.$resultado['msj'];
		}else{
			$msj = "ERROR: No se pudo enviar el correo: ".$resultado['msj'];
		}

		echo $msj;
	}

	/**
	 * Prueba de generador de archivo excel
	 * @return [type] [description]
	 */
	public function excel() {
		$setting = array(
			 'filename' 			=> 'Reportes_PruebaIS_'.date('Ymd_His')
			,'report_information' 	=> array(
				 array('cell'=> 'A1',	'text' => date('Y-m-d H:i:s'))
				,array('cell'=> 'B1', 	'text' => 1)
				,array('cell'=> 'C1', 	'text' => 'UNO')
				,array('cell'=> 'D1', 	'text' => 2)
				,array('cell'=> 'E1', 	'text' => 'DOS')
			)
			// ,'return_file_path' => TRUE
		);

		// echo "<pre>";
		// print_r($setting);
		$this->excel->download_file($setting);
		echo "Archivo Excel generado: ".date("Y-m-d H:i:s");
	}

	public function pdf() {
		$settings = array(
			 'file_name' 	=> 'prueba.pdf'
			,'content_file' => 'Hola mundo: '. date('Y-m-d H:i:s')
			,'load_file' 	=> TRUE
		);
		$file_path  = $this->dom_pdf->create_file($settings);
		echo "Archivo PDF generado: ".date("Y-m-d H:i:s");
	}
}

/* End of file Pruebas.php */
/* Location: ./application/controllers/Pruebas.php */