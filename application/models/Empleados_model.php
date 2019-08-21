<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Empleados_model extends IS_Model {

	public function get_dataEmpleados(array $data, $all=TRUE) {
		$tbl = $this->tbl;
		$sucursal = $this->col_sucursal('TE');
		isset($data['id_pais_nomina']) OR $data['id_pais_nomina'] = 0;
		isset($data['id_empresa_nomina']) OR $data['id_empresa_nomina'] = 0;

		isset($data['id_sucursal']) AND $this->db->having('custom_sucursal', $data['id_sucursal']);
		isset($data['id_llave_global']) AND $this->db->where('TE.id_llave_global', $data['id_llave_global']);
		isset($data['notIn_empleados']) AND $this->db->where("TE.id_llave_global NOT IN($data[notIn_empleados])");
		$request = $this->db->select("TE.*
				,$sucursal AS custom_sucursal
				,COALESCE(TE.mail_empresa, TE.mail) AS mail
				,CP.id_pais_nomina
				,CP.pais
				,CONCAT(CP.pais, '(', CP.id_pais_nomina,')') AS custom_pais
				,CE.empresa
				,CE.razon_social
				,CONCAT(CE.empresa, '(', CE.id_empresa_nomina, ')') AS custom_empresa
				,CP.clave_corta
			", FALSE)
			->from("(
				SELECT *
					,CONCAT_WS(' ', TRIM(COALESCE(nombre, '')), TRIM(COALESCE(paterno, '')), TRIM(COALESCE(materno, ''))) AS full_name
					,DATE_FORMAT(fecha_ingreso, '".DATEFORMAT."') AS custom_fecha_ingreso
				FROM $tbl[empleados]
				WHERE activo = 1
				AND id_pais_global = '$data[id_pais_nomina]'
				AND id_empresa_nomina IN($data[id_empresa_nomina])
				GROUP BY id_llave_global
			) AS TE")
			->join("$tbl[paises] AS CP", 'TE.id_pais_global=CP.id_pais_nomina', 'LEFT')
			->join("$tbl[empresas] AS CE", 'TE.id_pais_global=CE.id_pais_global AND TE.id_empresa_nomina=CE.id_empresa_nomina', 'LEFT')
			->get();

		// debug($this->db->last_query());
		return $all ? $request->result_array() : $request->row_array();
	}

	public function get_dataPersonales(array $data, $all=TRUE) {
		$tbl = $this->tbl;
		$sucursal = $this->col_sucursal('VWP');

		isset($data['id_pais_nomina']) 		AND $this->db->where('VWP.id_pais_nomina', $data['id_pais_nomina']);
		isset($data['id_empresa_nomina']) 	AND $this->db->where('VWP.id_empresa_nomina', $data['id_empresa_nomina']);
		isset($data['id_llave_global']) 	AND $this->db->where('VWP.id_llave_global', $data['id_llave_global']);
		isset($data['externo']) 			AND $this->db->where('VWP.externo', $data['externo']);
		$request = $this->db->select("VWP.*", FALSE)
			->from("$tbl[personales] AS VWP")
			->get();

		// debug($this->db->last_query());
		return $all ? $request->result_array() : $request->row_array();
	}

	public function get_dataLider(array $data, $all=TRUE) {
		$tbl = $this->tbl;
		$sucursal = $this->col_sucursal('TP');

		isset($data['nivel']) AND $this->db->where('TS.nivel', $data['nivel']);
		$request = $this->db->select("TP.*
				,$sucursal AS custom_sucursal
				,CP.id_pais_nomina
				,CP.pais
				,CONCAT(CP.pais, '(', CP.id_pais_nomina,')') AS custom_pais
				,CE.empresa
				,CE.razon_social
				,CONCAT(CE.empresa, '(', CE.id_empresa_nomina, ')') AS custom_empresa
			", FALSE)
			->from("$tbl[supervisores] AS TS")
			->join("$tbl[personales] AS TP", 'TS.id_llave_global_supervisor=TP.id_llave_global AND TS.externo=TP.externo')
			->join("$tbl[paises] AS CP", 'TP.id_pais_global=CP.id_pais_nomina', 'LEFT')
			->join("$tbl[empresas] AS CE", 'TP.id_pais_global=CE.id_pais_global AND TP.id_empresa_nomina=CE.id_empresa_nomina', 'LEFT')
			->where('TS.id_llave_global_empleado', $data['id_llave_global'])
			->where('TS.activo', 1)
			->order_by('TS.nivel')
			->get();

		// debug($this->db->last_query());
		return $all ? $request->result_array() : $request->row_array();
	}

	public function get_encargados_areas(array $data, $all=TRUE) {
		$tbl = $this->tbl;
		$sucursal = $this->col_sucursal('TP');

		$request = $this->db->select("TP.*", FALSE)
			->from("$tbl[areas_encargados] AS TAE")
			->join("$tbl[personales] AS TP", 'TAE.id_llave_global=TP.id_llave_global AND TAE.externo=TP.externo')
			->where('TAE.id_pais_nomina', $data['id_pais_nomina'])
			->where('TAE.id_empresa_nomina', $data['id_empresa_nomina'])
			->where('TAE.activo', 1)
			->get();

		// debug($this->db->last_query());
		return $all ? $request->result_array() : $request->row_array();

	}
}

/* End of file Empleados_model.php */
/* Location: ./application/models/Empleados_model.php */