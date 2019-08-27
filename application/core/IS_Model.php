<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class IS_Model extends CI_Model {
	public $tbl; #dbmodel

	public function __construct() {
		parent::__construct();

		// DATABASE 1 - asistencias
		$asistencias							= get_var('mysql_db1');
		$this->tbl['empresas']					= "$asistencias.cat_empresas";
		$this->tbl['menu']			 			= "$asistencias.sys_menu";
		$this->tbl['perfiles'] 					= "$asistencias.sys_perfiles";
		$this->tbl['usuarios'] 					= "$asistencias.sys_usuarios";
		$this->tbl['asistencias'] 				= "$asistencias.tbl_asistencias";
		$this->tbl['configuraciones_empresas']	= "$asistencias.tbl_configuraciones_empresas";
		$this->tbl['contactos_rh']				= "$asistencias.tbl_contactos_rh";
		$this->tbl['coord_asistencias']			= "$asistencias.tbl_coord_asistencias";
		$this->tbl['empleados']					= "$asistencias.tbl_empleados";
	}
}
