<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Empresas_model extends IS_Model {

	public function get_empresa(array $data, $all=TRUE) {
		$tbl = $this->tbl;

		!isset($data['diferent']) 		OR $this->db->where('id_empresa !=', $data['diferent']);
		!isset($data['id_empresa']) 	OR $this->db->where('id_empresa', $data['id_empresa']);
		!isset($data['empresa']) 		OR $this->db->where('empresa', $data['empresa']);
		!isset($data['razon_social']) 	OR $this->db->where('razon_social', $data['razon_social']);
		$request = $this->db->select('*')
			->where('activo', 1)
			->where('id_empresa !=', 1)
			->get($tbl['empresas']);

		return $all ? $request->result_array() : $request->row_array();
	}

	public function insert_empresa(array $data, $batch=FALSE) {
		$tbl = $this->tbl;

		$batch ? $this->db->insert_batch($tbl['empresas'], $data) : $this->db->insert($tbl['empresas'], $data);
		$error = $this->db->error();

		return $error['message'] ? FALSE : ($batch?TRUE:$this->db->insert_id());
	}

	public function update_empresa(array $data, $affected_rows=TRUE) {
		$tbl = $this->tbl;

		$this->db->where('id_empresa', $data['id_empresa'])
			->update($tbl['empresas'], $data);
		$affected = $this->db->affected_rows();

		$error = $this->db->error();
		if ($error['message']) {
			log_message('error', $error['message']);
			return FALSE;
		}

		return $affected_rows ? $affected : TRUE;
	}

	public function get_contactosRH_empresa(array $data, $all=TRUE) {
		$tbl = $this->tbl;

		!isset($data['diferent']) 		OR $this->db->where('id_contacto_rh !=', $data['diferent']);
		!isset($data['id_contacto_rh']) OR $this->db->where('id_contacto_rh', $data['id_contacto_rh']);
		!isset($data['id_empresa']) 	OR $this->db->where('id_empresa', $data['id_empresa']);
		$request = $this->db->select('*')
			->where('activo', 1)
			->get($tbl['contactos_rh']);

		return $all ? $request->result_array() : $request->row_array();
	}

	public function update_contactoRH(array $data, $affected_rows=TRUE) {
		$tbl = $this->tbl;

		!isset($data['id_contacto_rh']) OR $this->db->where('id_contacto_rh', $data['id_contacto_rh']);
		$this->db->where('id_empresa', $data['id_empresa'])
			->update($tbl['contactos_rh'], $data);
		$affected = $this->db->affected_rows();

		$error = $this->db->error();
		if ($error['message']) {
			log_message('error', $error['message']);
			return FALSE;
		}

		return $affected_rows ? $affected : TRUE;
	}

	public function get_contactos_rh(array $data, $all=TRUE) {
		$tbl = $this->tbl;

		!isset($data['diferent']) 		OR $this->db->where('id_contacto_rh !=', $data['diferent']);
		!isset($data['id_contacto_rh']) OR $this->db->where('id_contacto_rh', $data['id_contacto_rh']);
		$request = $this->db->select('*')
			->where('activo', 1)
			->where('id_empresa', $data['id_empresa'])
			->get($tbl['contactos_rh']);

		return $all ? $request->result_array() : $request->row_array();
	}
}

/* End of file Empresas_model.php */
/* Location: ./application/models/Empresas_model.php */