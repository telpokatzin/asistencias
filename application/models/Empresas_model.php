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
		!isset($data['id_empleado']) OR $this->db->where('id_empleado', $data['id_empleado']);
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

		!isset($data['diferent']) 		OR $this->db->where('TCRH.id_contacto_rh !=', $data['diferent']);
		!isset($data['id_contacto_rh']) OR $this->db->where('TCRH.id_contacto_rh', $data['id_contacto_rh']);
		!isset($data['id_empresa']) 	OR $this->db->where('TCRH.id_empresa', $data['id_empresa']);
		!isset($data['correo']) 		OR $this->db->where('TE.correo', $data['correo']);
		$request = $this->db->select("TE.*
				,TCRH.id_contacto_rh
				,CONCAT_WS(' ', COALESCE(TE.nombre, ''), COALESCE(TE.paterno, ''), , COALESCE(TE.materno, '')) AS nombre_completo
			", FALSE)
			->from("$tbl[contactos_rh] AS TCRH")
			->join("$tbl[empleados] AS TE", 'TCRH.id_empleado=TE.id_empleado', 'LEFT')
			->where('TE.activo', 1)
			->where('TCRH.activo', 1)
			->get();

		return $all ? $request->result_array() : $request->row_array();
	}

	public function get_colaboradores_noCRH(array $data, $all=TRUE) {
		$tbl = $this->tbl;

		$request = $this->db->select("TE.*
				,CONCAT_WS(' ', COALESCE(TE.nombre, ''), COALESCE(TE.paterno, ''), , COALESCE(TE.materno, '')) AS nombre_completo
			", FALSE)
			->from("$tbl[empleados] AS TE")
			->join("$tbl[contactos_rh] AS TCRH", 'TCRH.id_empleado=TE.id_empleado AND TCRH.activo=1', 'LEFT')
			->where('TE.id_empresa', $data['id_empresa'])
			->where('TE.activo', 1)
			->where('TCRH.id_empleado', NULL)
			->get();

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

		!isset($data['diferent']) 	OR $this->db->where('id_turno !=', $data['diferent']);
		!isset($data['id_turno']) 	OR $this->db->where('id_turno', $data['id_turno']);
		!isset($data['id_empresa']) OR $this->db->where('id_empresa', $data['id_empresa']);
		!isset($data['turno']) 		OR $this->db->where('turno', $data['turno']);
		$request = $this->db->select("*
				,TIME_FORMAT(entrada, '%h:%i %p') AS custom_entrada
				,TIME_FORMAT(salida, '%h:%i %p') AS custom_salida
			", FALSE)
			->where('activo', 1)
			->get($tbl['turnos']);

		return $all ? $request->result_array() : $request->row_array();
	}

	public function update_turno(array $data, $affected_rows=TRUE) {
		$tbl = $this->tbl;

		!isset($data['id_turno']) OR $this->db->where('id_turno', $data['id_turno']);
		$this->db->where('id_empresa', $data['id_empresa'])
			->update($tbl['turnos'], $data);
		$affected = $this->db->affected_rows();

		$error = $this->db->error();
		if ($error['message']) {
			log_message('error', $error['message']);
			return FALSE;
		}

		return $affected_rows ? $affected : TRUE;
	}

	public function get_config_empresas(array $data, $all=FALSE) {
		$tbl = $this->tbl;

		!isset($data['id_configuracion_empresa']) 	OR $this->db->where('TCE.id_configuracion_empresa', $data['id_configuracion_empresa']);
		!isset($data['id_empresa']) 	OR $this->db->where('TCE.id_empresa', $data['id_empresa']);
		$request = $this->db->select('TCE.*, TT.turno')
			->from("$tbl[configuraciones_empresas] AS TCE")
			->join("$tbl[turnos] AS TT", 'TCE.id_turno=TT.id_turno AND TT.activo=1', 'LEFT')
			->where('TCE.activo', 1)
			->where('TCE.id_empresa !=', 1)
			->get();

		return $all ? $request->result_array() : $request->row_array();
	}

	public function update_config_empresa(array $data, $affected_rows=TRUE) {
		$tbl = $this->tbl;

		$this->db->where('id_empresa', $data['id_empresa'])
			->update($tbl['configuraciones_empresas'], $data);
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