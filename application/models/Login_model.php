<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends IS_Model {

	public function get_autentication(array $data, $all=FALSE) {
		$tbl 	  = $this->tbl;

		$request = $this->db->select("
				 CE.id_empresa
				,CE.empresa
				,TE.num_empleado
				,TE.nombre
				,TE.paterno
				,TE.materno
				,CONCAT_WS(' ', TRIM(TE.nombre), TRIM(TE.paterno), TRIM(TE.materno)) AS full_name
				,TE.correo
				,SU.id_usuario
				,SU.usuario
				,SU.password
				,SU.id_perfil
			", FALSE)
			->from("$tbl[configuraciones_empresas] AS TCE")
			->join("$tbl[empresas] AS CE", 'TCE.id_empresa=CE.id_empresa')
			->join("$tbl[empleados] AS TE", 'TCE.id_empresa=TE.id_empresa')
			->join("$tbl[usuarios] AS SU", 'SU.id_empleado=TE.id_empleado', 'LEFT')
			->where("MD5(SU.usuario)", md5($data['usuario']))
			->where('SU.password', $data['password'])
			->where('CE.activo', 1)
			->where('TE.activo', 1)
			->where('TCE.activo', 1)
			->get();

		return $all ? $request->result_array() : $request->row_array();
	}
}

/* End of file Login_model.php */
/* Location: ./application/models/Login_model.php */