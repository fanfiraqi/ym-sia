<?php
			
class param_model extends MY_Model {
	
	public function get($name,$all=false){
		$query = $this->db->select()
			->where('LOWER(name)',strtolower($name))
			->get('ak_params')
			->row();
		if ($all){
			return $query;
		} else {
			return $query->value1;
		}
	}
	public function getLogo($name,$all=false){		
		//$mylogo="kop.jpg";		
		$query = $this->db->select()
			->where('LOWER(name)',strtolower($name))
			->get('ak_params');
			
		if ($query->num_rows()>0){
				$res=$query->row();
				$mylogo=$res->value2;
		}
		return $mylogo;
	}
	public function getDept($name,$all=false){		
		$mydept="";		
		$query = $this->db->select()
			->where('LOWER(name)',strtolower($name))
			->get('ak_params');
			
		if ($query->num_rows()>0){
				$res=$query->row();
				$mydept=$res->value3;
		}
		return $mydept;
	}
	
}