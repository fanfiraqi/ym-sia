<?php
			
class rpb_model extends MY_Model {
	var $table = 'mst_cabang';
	var $primaryID = 'ID_CABANG';

	public function approve_rpb($kelompok=null, $id_head=null){
		$this->db->trans_begin();
		$nmtable=($kelompok=="PDG"?"rpb_pdg_header":"rpb_sdm_header");
		$respon = new StdClass();
		try {
			if ($this->db->where('id_header',$id_head)->update($nmtable, array("status_approve"=>'Telah Disetujui', "petugas_approve"=>$this->session->userdata('auth')->USERNAME, "tgl_approve"=>date('Y-m-d H:i:s')) ) ) {
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

	
}