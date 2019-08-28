<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalogos_model extends IS_Model {

	public function get_empresas($data=array(), $all=TRUE) {
		$tbl = $this->tbl;

		!isset($data['id_empresa']) OR $this->db->where("CE.id_empresa_nomina IN($data[id_empresa])");
		$request = $this->db->select("CE.*", FALSE)
			->from("$tbl[empresas] AS CE")
			->join("$tbl[configuraciones_empresas] AS SEC", 'SEC.id_empresa=CE.id_empresa', 'LEFT')
			->where('CE.activo', 1)
			->where('CE.id_empresa !=', 1)
			->group_by('CE.id_empresa')
			->get();
			
		// debug($this->db->last_query());
		return $all ? $request->result_array() : $request->row_array();
	}

	public function get_sucursales(array $data, $all=TRUE) {
		$tbl = $this->tbl;
		isset($data['id_pais_nomina']) OR $data['id_pais_nomina'] = 0;
		isset($data['id_empresa_nomina']) OR $data['id_empresa_nomina'] = 0;
		$sucursal = $this->col_sucursal();

		$request = $this->db->select("
				 $sucursal AS id_sucursal
				,$sucursal AS sucursal
				,$sucursal AS custom_sucursal
			", FALSE)
			->where('id_pais_global', $data['id_pais_nomina'])
			->where('id_empresa_nomina', $data['id_empresa_nomina'])
			->where('activo', 1)
			->group_by('custom_sucursal')
			->order_by('custom_sucursal')
			->get("$tbl[empleados]");

		// debug($this->db->last_query());
		return $all ? $request->result_array() : $request->row_array();
	}

	public function get_estatus_sol($data=array(), $all=TRUE) {
		$tbl = $this->tbl;

		isset($data['id_estatus']) 	AND $this->db->where("id_estatus IN($data[id_estatus])");
		$request = $this->db->select('*')
			->where('activo', 1)
			->order_by('id_estatus')
			->get("$tbl[estatus_solicitudes]");

		return $all ? $request->result_array() : $request->row_array();
	}

	public function get_reportes($data=array(), $all=TRUE) {
		$tbl = $this->tbl;

		isset($data['id_reporte']) 	AND $this->db->where("id_reporte IN($data[id_reporte])");
		$request = $this->db->select('*')
			->where('activo', 1)
			->order_by('id_reporte')
			->get("$tbl[reportes]");

		return $all ? $request->result_array() : $request->row_array();
	}
	
}

/* End of file Catalogos_model.php */
/* Location: ./application/models/Catalogos_model.php */