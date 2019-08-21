<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Empresas_model extends IS_Model {

	public function get_empresa(array $data, $all=TRUE) {
		$tbl = $this->tbl;

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

	public function update_empresa(array $data, $affected_rows=FALSE) {
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
}

/* End of file Empresas_model.php */
/* Location: ./application/models/Empresas_model.php */