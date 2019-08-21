<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends IS_Model {

	public function get_menu(array $data) {
		$tbl    = $this->tbl;
		
		if($data['ids_menu'] !== FALSE AND $data['ids_menu'] !== '' AND $data['ids_menu'] !== NULL) 
			$this->db->where_in('id_menu', explode(',', $data['ids_menu']));
		$request = $this->db->select('*')
			->where('activo', 1)
			->order_by('id_padre, orden ASC')
			->get($tbl['menu']);
		
		return $request->result_array();
	}

}

/* End of file Menu_model.php */
/* Location: ./application/models/Menu_model.php */