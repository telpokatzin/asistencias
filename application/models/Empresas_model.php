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
		!isset($data['id_empresa']) 	OR $this->db->where('id_empresa', $data['id_empresa']);
		!isset($data['correo']) 		OR $this->db->where('correo', $data['correo']);
		$request = $this->db->select('*')
			->where('activo', 1)
			->get($tbl['contactos_rh']);

		return $all ? $request->result_array() : $request->row_array();
	}

	public function insert_contactoRH(array $data, $batch=FALSE) {
		$tbl = $this->tbl;

		$batch ? $this->db->insert_batch($tbl['contactos_rh'], $data) : $this->db->insert($tbl['contactos_rh'], $data);
		$error = $this->db->error();

		return $error['message'] ? FALSE : ($batch?TRUE:$this->db->insert_id());
	}

	public function get_turnos(array $data, $all=TRUE) {
		$tbl = $this->tbl;

		!isset($data['diferent']) 			OR $this->db->where('id_turno_empresa !=', $data['diferent']);
		!isset($data['id_turno_empresa']) 	OR $this->db->where('id_turno_empresa', $data['id_turno_empresa']);
		!isset($data['id_empresa']) 		OR $this->db->where('id_empresa', $data['id_empresa']);
		!isset($data['turno']) 				OR $this->db->where('turno', $data['turno']);
		$request = $this->db->select("*
				,TIME_FORMAT(entrada, '%h:%i %p') AS custom_entrada
				,TIME_FORMAT(salida, '%h:%i %p') AS custom_salida
			", FALSE)
			->where('activo', 1)
			->get($tbl['turnos_empresas']);

		return $all ? $request->result_array() : $request->row_array();
	}

	public function update_turno(array $data, $affected_rows=TRUE) {
		$tbl = $this->tbl;

		!isset($data['id_turno_empresa']) OR $this->db->where('id_turno_empresa', $data['id_turno_empresa']);
		$this->db->where('id_empresa', $data['id_empresa'])
			->update($tbl['turnos_empresas'], $data);
		$affected = $this->db->affected_rows();

		$error = $this->db->error();
		if ($error['message']) {
			log_message('error', $error['message']);
			return FALSE;
		}

		return $affected_rows ? $affected : TRUE;
	}
}

/* End of file Empresas_model.php */
/* Location: ./application/models/Empresas_model.php */