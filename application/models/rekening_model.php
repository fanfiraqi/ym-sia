<?php
			
class rekening_model extends MY_Model {
	var $table = 'ak_mst_user';
	var $primaryID = 'ID';

	function delAkunById($id=null){
		$this->db->trans_begin();
		try {
			if ($this->db->delete('ak_rekeningperkiraan', array("idacc"=>$id))){
				$this->db->trans_commit();
				$respon->status = 'success';
			} else {
				throw new Exception("gagal simpan");
			}
		} catch (Exception $e) {
			$respon->status = 'error';
			$respon->errormsg = $e->getMessage();;
				$this->db->trans_rollback();
		}
	
	return $respon;
	}
}