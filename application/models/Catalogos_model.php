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

	public function get_preguntas_empresa(array $data, $all=TRUE) {
		$tbl = $this->tbl;

		isset($data['id_pregunta']) AND $this->db->where("CPE.id_pregunta IN($data[id_pregunta])");
		isset($data['id_pais_nomina']) AND $this->db->where("CPE.id_pais_nomina IN($data[id_pais_nomina])");
		isset($data['id_empresa_nomina']) AND $this->db->where("CPE.id_empresa_nomina IN($data[id_empresa_nomina])");
		isset($data['id_empresa_area']) AND $this->db->where('TAE.id_empresa_area', $data['id_empresa_area']);
		isset($data['id_llave_global']) AND $this->db->where('TAE.id_llave_global', $data['id_llave_global']);
		isset($data['externo']) AND $this->db->where('TAE.externo', $data['externo']);
		$request = $this->db->select("
				 CPE.id_pregunta_empresa
				,CPE.id_empresa_area
				,CP.id_pregunta
				,CP.pregunta
				,CP.indicaciones
				,CR.type
				,CR.label
				,CR.class
				,CPR.id_respuesta
				,CPR.key_respuesta
				,CPR.class_respuesta
			", FALSE)
			->from("$tbl[preguntas_empresas] AS CPE")
			->join("$tbl[empresas_areas] AS TEA", 'CPE.id_pais_nomina=TEA.id_pais_nomina AND CPE.id_empresa_nomina=TEA.id_empresa_nomina AND CPE.id_empresa_area=TEA.id_empresa_area', 'LEFT')
			->join("$tbl[areas_encargados] AS TAE", 'TEA.id_empresa_area=TAE.id_empresa_area AND TAE.activo=1', 'LEFT')
			->join("$tbl[preguntas] AS CP", 'CPE.id_pregunta=CP.id_pregunta', 'LEFT')
			->join("$tbl[preguntas_respuestas] AS CPR", 'CPE.id_pregunta=CPR.id_pregunta AND CPR.activo=1', 'LEFT')
			->join("$tbl[respuestas] AS CR", 'CPR.id_respuesta=CR.id_respuesta', 'LEFT')
			// ->where('TEA.activo', 1)
			->where('CPE.type', $data['type'])
			->where('CPE.activo', 1)
			->order_by('CPE.orden, CPR.orden')
			->get();

		// debug($this->db->last_query());
		return $all ? $request->result_array() : $request->row_array();
	}

	public function get_areas(array $data, $all=TRUE) {
		$tbl = $this->tbl;

		isset($data['id_empresa_area']) AND $this->db->where("TEA.id_empresa_area IN($data[id_empresa_area])");
		isset($data['id_pais_nomina']) AND $this->db->where("TEA.id_pais_nomina IN($data[id_pais_nomina])");
		isset($data['id_empresa_nomina']) AND $this->db->where("TEA.id_empresa_nomina IN($data[id_empresa_nomina])");
		$request = $this->db->select("CA.*")
			->from("$tbl[empresas_areas] AS TEA")
			->join("$tbl[areas] AS CA", 'TEA.id_area=CA.id_area', 'LEFT')
			->where('TEA.activo', 1)
			->order_by('CA.area')
			->get();

		// debug($this->db->last_query());
		return $all ? $request->result_array() : $request->row_array();
	}

	public function get_contactos_rh(array $data, $all=TRUE) {
		$tbl = $this->tbl;

		$request = $this->db->select("VWP.*")
			->from("$tbl[contactos_rh] AS CCRH")
			->join("$tbl[personales] AS VWP", 'CCRH.id_llave_global=VWP.id_llave_global AND CCRH.externo=VWP.externo', 'INNER')
			->where("CCRH.id_pais_nomina IN($data[id_pais_nomina])")
			->where("CCRH.id_empresa_nomina IN($data[id_empresa_nomina])")
			->where('CCRH.activo', 1)
			->get();

		// debug($this->db->last_query());
		return $all ? $request->result_array() : $request->row_array();
	}
}

/* End of file Catalogos_model.php */
/* Location: ./application/models/Catalogos_model.php */