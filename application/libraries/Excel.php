<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Excel extends PHPExcel{
	
	public function __construct() {
		parent::__construct();
	}

	private function setupFile($setting) {
		$title 			= isset($setting['title']) 			? $setting['title'] 		: 'Reporte';
		$subject 		= isset($setting['subject']) 		? $setting['subject'] 		: '';
		$description	= isset($setting['description'])	? $setting['description'] 	: '';
		$TEMPLATE 	 	= isset($setting['template']) 		? $setting['template'] 		: FALSE;
		$objPHPExcel 	=  $TEMPLATE ? PHPExcel_IOFactory::load($TEMPLATE) : new PHPExcel();
		
		// Set document properties
		$objPHPExcel->getProperties()->setCreator("IS Intelligent Solution - Reporte PAE")
									 ->setLastModifiedBy("")
									 ->setTitle($title)
									 ->setSubject($subject)
									 ->setDescription($description);
		
		return $objPHPExcel;
	}

	private function setContent(&$objPHPExcel, $setting) {
		$HEADERS 			= isset($setting['headers']) 			? $setting['headers'] 			 : FALSE;
		$REPORT_INFORMATION = isset($setting['report_information']) ? $setting['report_information'] : array();
		$DATA   			= isset($setting['data']) 				? $setting['data'] 				 : array();

		$objPHPExcel->getActiveSheet()->setTitle($setting['sheet_name']);

		//SET HEADER
		$HEADERS AND $objPHPExcel->getActiveSheet()->fromArray($HEADERS['data'], null, $HEADERS['cell']);
		
		//SET INFORMATION REPORT
  		foreach ($REPORT_INFORMATION as $info) {
      		$objPHPExcel->getActiveSheet()->setCellValue($info['cell'], $info['text']);
  		}

  		//INSERTAMOS LOS DATOS EN LAS CELDAS
  		if (isset($DATA['data']))
  			$objPHPExcel->getActiveSheet()->fromArray($DATA['data'], null, $DATA['cell'], TRUE);
  		
	    if (isset($setting['autoSize'])) {
			foreach ($setting['autoSize'] as $cell) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($cell)->setAutoSize(true);
			}
		}

		//DAMOS FORMATO A LA HOJA
		if (isset($setting['font'])) {
			$objPHPExcel->getActiveSheet()->getStyle($setting['font']['cells'])->applyFromArray($setting['font']);
		}

		return $objPHPExcel;
	}

	/**
	 * Función para la descarga de archivos de Excel
	 * con opcion para guardarlo en la carpeta temporal
	 */
	public function download_file($setting=array()) {
		ini_set('max_execution_time', 0);
		$content_type 		= isset($setting['content_type']) 		? $setting['content_type'] 	: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
		$filename 			= isset($setting['filename']) 			? $setting['filename'] 		: 'Reporte';
		$extension 			= isset($setting['extension']) 			? $setting['extension'] 	: 'xlsx';
		$version 			= $extension == 'xls' 					? 'Excel5'					: 'Excel2007';
		$sheet_name 		= isset($setting['sheet_name'])			? $setting['sheet_name'] 	: 'hoja1';
		$RETURN_FILE_PATH 	= isset($setting['return_file_path']) 	? TRUE 						: FALSE;
		$objPHPExcel 		= self::setupFile($setting);

		//ACTIVAMOS LA HOJA1
		$objPHPExcel->setActiveSheetIndex(0);
		$setting['sheet_name'] = $sheet_name;
		self::setContent($objPHPExcel, $setting);

		//GUARDAMOS EL ARCHIVO EN LA CARPETA TEMPORAL Y RETORNAMOS LA RUTA DEL ARCHIVO
		if ($RETURN_FILE_PATH) {
			$dir_tmp 	= isset($setting['directory']) ? $setting['directory'] : get_var('path_tmp');
			$pathfile  	= "{$dir_tmp}/{$filename}.{$extension}";
			$objWriter 	= PHPExcel_IOFactory::createWriter($objPHPExcel, $version);
			$objWriter->save(LOCALPATH.$pathfile);

			return $pathfile;
		}

		//DESCARGA DEL ARCHIVO DESDE EL BROWSER
		header("Content-Type: $content_type");
		header("Content-Disposition: attachment;filename=$filename.$extension");
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $version);
		$objWriter->save('php://output');
		exit;
	}

	public function download_file_multiple_sheets($settings=array()) {
		ini_set('max_execution_time', 0);
		$content_type 		= isset($settings['content_type']) 		? $settings['content_type'] 	: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
		$filename 			= isset($settings['filename']) 			? $settings['filename'] 		: 'Reporte';
		$extension 			= isset($settings['extension']) 		? $settings['extension'] 		: 'xlsx';
		$version 			= $extension == 'xls' 					? 'Excel5'					: 'Excel2007';
		$RETURN_FILE_PATH 	= isset($settings['return_file_path']) 	? TRUE : FALSE;
		$objPHPExcel 		= self::setupFile($settings);

		$sheet = 0;
		foreach ($settings['sheets'] as $setting) {
			$setting['sheet_name'] = isset($setting['sheet_name'])	? $setting['sheet_name'] 	: 'hoja'.($sheet+1);
			$objPHPExcel->setActiveSheetIndex($sheet++);
			self::setContent($objPHPExcel, $setting);
		}
		$objPHPExcel->setActiveSheetIndex(0);

		//GUARDAMOS EL ARCHIVO EN LA CARPETA TEMPORAL Y RETORNAMOS LA RUTA DEL ARCHIVO
		if ($RETURN_FILE_PATH) {
			$dir_tmp 	= isset($setting['directory']) ? $setting['directory'] : get_var('path_tmp');
			$pathfile  	= "{$dir_tmp}/{$filename}.{$extension}";
			$objWriter 	= PHPExcel_IOFactory::createWriter($objPHPExcel, $version);
			$objWriter->save(LOCALPATH.$pathfile);

			return $pathfile;
		}

		//DESCARGA DEL ARCHIVO DESDE EL BROWSER
		header("Content-Type: $content_type");
		header("Content-Disposition: attachment;filename=$filename.$extension");
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $version);
		$objWriter->save('php://output');
		exit;
	}

}

/* End of file Excel.php */
/* Location: ./application/libraries/Excel.php */