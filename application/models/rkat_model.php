<?php
			
class rkat_model extends MY_Model {
	var $table = 'mst_cabang';
	var $primaryID = 'ID_CABANG';

	public function validasi_rkat($kelompok=null, $id_head=null){
		$this->db->trans_begin();
		$nmtable=($kelompok=="PHP"?"rkat_php_header":($kelompok=="PDG"?"rkat_pdg_header":"rkat_sdm_header"));
		$respon = new StdClass();
		try {
			if ($this->db->where('id_header',$id_head)->update($nmtable, array("status_validasi"=>1, "petugas_validasi"=>$this->session->userdata('auth')->USERNAME) ) ) {
				$this->db->trans_commit();
				$respon->status = 'success';
			} else {
				throw new Exception("gagal simpan");
			}
		} catch (Exception $e) {
			$respon->status = 'error';
			$respon->errormsg = $e->getMessage();
				$this->db->trans_rollback();
		}
	
		return $respon;
	}

	public function comboGetLevel($empty='', $level, $kelompok){
		$str="select * from mst_item_rkat where level=$level and kelompok='$kelompok' ".($level>1?" and idparent = (SELECT id FROM mst_item_rkat WHERE kelompok='$kelompok' AND LEVEL=".($level-1)." ORDER BY ID ASC LIMIT 1)":"");
		
		$query=$this->db->query($str)->result();
		$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'id','nama_item',$empty);
		}
		return $combo;
	}
}