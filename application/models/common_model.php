<?php
			
class common_model extends MY_Model {
	var $role;
	function __construct()
	{
		parent::__construct();
		 $this->gate_db=$this->load->database('gate', TRUE);
		 $this->donasi_db=$this->load->database('donasi', TRUE);
		 $role=$this->config->item('myrole');
	}

	function ubahStatusFixedAsset($id=null, $sts=null){
		$this->db->trans_begin();
		$sts=($sts=="1"?"0":"1");
		try {
			if ($this->db->where('id',$id)->update('ak_fixed_asset_setting', array("isactive"=>$sts))){
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
	function delThis($id=null, $nm_table, $nm_field){
		$this->db->trans_begin();
		$str="";
		try {
			
				$str="delete from $nm_table where $nm_field=".$id;
			

			if ($this->db->query($str) ) {
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
	public function comboTipeDonasi($empty=''){
			$query = $this->donasi_db->query("select distinct type from jenis_donasi")->result();
			$combo = array();
			if (!empty($query)){
				$combo = $this->commonlib->buildcombo($query,'type','type',$empty);
			}
			return $combo;
		}
	public function comboCabang($empty=''){
		$query = $this->gate_db->select()
			->order_by('id_cabang')
			->get('mst_cabang')
			->result();
		$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'id_cabang','kota',$empty);
		}
		return $combo;
	}

	public function comboGetMasterCabang($empty=''){
		$str="select * from mst_cabang  ";
		$query=$this->gate_db->query($str)->result();
		$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'id_cabang','kota',$empty);
		}
		return $combo;
	}
function deltruefixedasset($id=null, $sts=null){
		$this->db->trans_begin();
		$sts=($sts=="1"?"0":"1");
		try {
			if ($this->db->where('id',$id)->delete('ak_fixed_asset_setting')){
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
	function delPeriode($id=null){
		$this->db->trans_begin();
		try {
			if ($this->db->delete('ak_periodebuku', array("id"=>$id))){
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
	function delak_jurnalnonkas($id=null){
		$this->db->trans_begin();
		try {
			if ($this->db->delete('ak_jurnal', array("notransaksi"=>$id))){
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
	function deljurnal($id=null){
		$this->db->trans_begin();
		try {
			if ($this->db->delete('ak_jurnal', array("notransaksi"=>$id))){
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
	function delkasmasuk($id=null){
		$this->db->trans_begin();
		try {
			if ($this->db->delete('ak_jurnal', array("notransaksi"=>$id))){
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
	function delkaskeluar($id=null){
		$this->db->trans_begin();
		try {
			if ($this->db->delete('ak_jurnal', array("notransaksi"=>$id))){
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
	public function getCabang($id){
		$query = $this->gate_db->query("select * from mst_cabang where id_cabang=".$id)->row();		
		return $query;
	}
	public function comboGetCabang($empty=''){
		$str="select * from mst_cabang where id_cabang<>0 ";
		$query=$this->gate_db->query($str)->result();
		$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'id_cabang','kota',$empty);
		}
		return $combo;
	}
	public function comboGetAllCabang($empty=''){
		$str="select * from mst_cabang  ";
		$query=$this->gate_db->query($str)->result();
		$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'id_cabang','kota',$empty);
		}
		return $combo;
	}
	public function cbget_sourcecab($empty=''){
		// no zisco, dps, dokter
		$rsArrJabId=$this->db->query("select distinct id_cab from ak_rekeningperkiraan where id_cab not in (0, 1)")->result();
		$tags = array();
			foreach ($rsArrJabId as $row) {
				$tags[] =htmlspecialchars( $row->id_cab, ENT_NOQUOTES, 'UTF-8' );
			}
		$arIdcab= implode(',', $tags);

		$str="select * from mst_cabang where id_cabang in (".$arIdcab.") ";
		$query=$this->gate_db->query($str)->result();
		$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'id_cabang','kota',$empty);
		}
		return $combo;
	}

	public function cbget_destcab($empty=''){
		// no zisco, dps, dokter
		$rsArrJabId=$this->db->query("select distinct id_cab from ak_rekeningperkiraan where id_cab<>0")->result();
		$tags = array();
			foreach ($rsArrJabId as $row) {
				$tags[] =htmlspecialchars( $row->id_cab, ENT_NOQUOTES, 'UTF-8' );
			}
		$arIdcab= implode(',', $tags);

		$str="select * from mst_cabang where id_cabang not in (".$arIdcab.") ";
		$query=$this->gate_db->query($str)->result();
		$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'id_cabang','kota',$empty);
		}
		return $combo;
	}
	public function comboGetLevel3NonKas($empty=''){
		//$str="select * from ak_rekeningperkiraan where (idparent not in ('1-1100', '1-1200') ) and (idacc not in ('1-1100', '1-1200'))";
		$str="select * from ak_rekeningperkiraan where status=1 and level=3 and kelompok='A' and (idacc not like  '1.1.1.%' ) and  (idacc not like   '1.1.2.%' ) and  (idacc not like  '4-%')";
		
		$query=$this->db->query($str)->result();
		$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'idacc','nama',$empty);
		}
		return $combo;
	}
	public function comboGetLevel5($empty=''){
		//$str="select * from ak_rekeningperkiraan where (idparent not in ('1-1100', '1-1200') ) and (idacc not in ('1-1100', '1-1200'))";
		$str="select * from ak_rekeningperkiraan where level=5 and kelompok='A' ";
		
		$query=$this->db->query($str)->result();
		$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'idacc','nama',$empty);
		}
		return $combo;
	}
	public function comboGetLevel3($empty=''){
		$str="select * from ak_rekeningperkiraan where level=3 and kelompok='A'";
		$query=$this->db->query($str)->result();
		$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'idacc','nama',$empty);
		}
		return $combo;
	}
	public function comboGetLevel4($empty=''){
		$str="select * from ak_rekeningperkiraan where level=4 and kelompok='A'";
		$query=$this->db->query($str)->result();
		$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'idacc','nama',$empty);
		}
		return $combo;
	}
	public function comboGetLevel2($empty=''){
		$str="select * from ak_rekeningperkiraan where level=2 and kelompok='A'";
		$query=$this->db->query($str)->result();
		$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'idacc','nama',$empty);
		}
		return $combo;
	}
	public function comboGetLevel1($empty=''){
		$str="select * from ak_rekeningperkiraan where level=1";
		$query=$this->db->query($str)->result();
		$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'idacc','nama',$empty);
		}
		return $combo;
	}
	public function comboGetLevel1_nonkas($empty=''){
		$str="select * from ak_rekeningperkiraan where level=1 and idacc not like '4.%'";
		$query=$this->db->query($str)->result();
		$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'idacc','nama',$empty);
		}
		return $combo;
	}
	public function comboGetPeriode($empty=''){
		$str="select * from ak_periodebuku where isactive=1";
		$query=$this->db->query($str)->row();
		/*$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'idacc','nama',$empty);
		}
		return $combo;*/
		return $query->thnbuku;
	}
	
	public function comboGetFixAssetLevel3($empty=''){
		$str="select * from ak_rekeningperkiraan where level=3 and idparent='1-3000' and idacc not like '1-34%'";
		$query=$this->db->query($str)->result();
		$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'idacc','nama',$empty);
		}
		return $combo;
	}
	public function comboGetFixAssetLevel4($empty=''){
		$str="select * from ak_rekeningperkiraan where level=4 and idparent='1-3100' and d_c='D'";
		$query=$this->db->query($str)->result();
		$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'idacc','nama',$empty);
		}
		return $combo;
	}
	
	public function comboGetFixAssetLevel5($empty=''){
		$str="select * from ak_rekeningperkiraan where level=5 and idparent='1-3101' and d_c='D'";
		$query=$this->db->query($str)->result();
		$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'idacc','nama',$empty);
		}
		return $combo;
	}
	public function comboGetAkumulasiLevel4($empty=''){
		$str="select * from ak_rekeningperkiraan where level=4 and idparent='1-3100' and upper(nama) like '%AKUM%'";
		$query=$this->db->query($str)->result();
		$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'idacc','nama',$empty);
		}
		return $combo;
	}
	public function comboGetAkumulasiLevel5($empty=''){
		$str="select * from ak_rekeningperkiraan where level=5 and idparent='1-3101' and upper(nama) like '%AKUM%'";
		$query=$this->db->query($str)->result();
		$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'idacc','nama',$empty);
		}
		return $combo;
	}
	public function comboGetBiayaSusut($empty=''){
		$str="select * from ak_rekeningperkiraan where kelompok='B' and upper(nama) like '%PENYUSUTAN%' and level>3";
		$query=$this->db->query($str)->result();
		$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo2($query,'idacc','idacc','nama',$empty);
		}
		return $combo;
	}
	public function comboGetAkunKasTunai($empty=''){
		$str="select * from ak_rekeningperkiraan where idacc  like  '1.1.1.%' ";
		$query=$this->db->query($str);
		/*$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'idacc','nama',$empty);
		}
		return $combo;*/
		return $query;
	}
	public function comboGetAkunKas($empty=''){
		$str="select * from ak_rekeningperkiraan where (idacc  like  '1.1.1.%'  or  idacc  like   '1.1.2.%' )  and id_cab=".$this->session->userdata('auth')->ID_CABANG;
		$query=$this->db->query($str);
		/*$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'idacc','nama',$empty);
		}
		return $combo;*/
		return $query;
	}

	
	public function comboGetAkunKasCabang($idcab){
		$str="select * from ak_rekeningperkiraan where status=1 and (idacc  like  '1.1.1.%'  or  idacc  like   '1.1.2.%' )  and id_cab=".$idcab;
		$query=$this->db->query($str)->result();
		$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'idacc','nama','');
		}
		return $combo;
		//return $query;
	}
	public function comboGetAkunBiayaGaji($empty=''){
		$str="select * from ak_rekeningperkiraan where idacc  like '5.5.1%' and level=5 order by idacc asc";
		$query=$this->db->query($str)->result();
		$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'idacc','nama',$empty);
		}
		return $combo;
		//return $query;
	}
	public function comboGetAkunBankGaji($empty=''){
		
		$str="select * from ak_rekeningperkiraan where idacc  like '1.1.2%' and level=5 and  id_cab =  1 and (nama like '%amil%' or nama like '%tasharuf%') order by idacc asc";
		$query=$this->db->query($str)->result();
		$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'idacc','nama',$empty);
		}
		return $combo;
		//return $query;
	}
	public function comboGetAkunKasZIS($empty=''){
		//$str="select * from ak_rekeningperkiraan where (idacc  like  '1.1.1.%'  or  idacc  like   '1.1.2.%' )  ". ($this->session->userdata('auth')->ID_CABANG==1 ? '':" and id_cab=".$this->session->userdata('auth')->ID_CABANG);
		//jika login user cab<>1 selain pusat maka harus include akun bank pusat untuk transaksi R/K
		$str="select * from ak_rekeningperkiraan where (idacc  like '1.1.1%' or  idacc  like  '1.1.2%') and level=5 and  id_cab=".$this->session->userdata('auth')->id_cabang.($this->session->userdata('auth')->ID_CABANG<=1?" union select * from ak_rekeningperkiraan where  (idacc  like '1.1.1%' or  idacc  like  '1.1.2%') and id_cab=1 and level=5":""). " order by idacc asc";
		$query=$this->db->query($str)->result();
		$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'idacc','nama',$empty);
		}
		return $combo;
		//return $query;
	}
	public function comboGetAkunKasZIS_Cabang($idcabang){
		
		$str="select * from ak_rekeningperkiraan where (idacc  like '1.1.1%' or  idacc  like  '1.1.2%') and level=5 and  id_cab=".$idcabang. " order by idacc asc";
		$query=$this->db->query($str)->result();
		$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'idacc','nama','');
		}
		return $combo;
		//return $str;
	}
	
	public function comboGetAkunZisCabang($idCabang=''){		
	
		$str="select * from ak_rekeningperkiraan where id_cab=".$idCabang." and level=5 and idacc like '4.%'";
		$query=$this->db->query($str)->result();
		$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'idacc','nama');
		}
		return $combo;
		//return $query;
	}
	public function comboGetAkunNonKas($empty=''){
		//$str="select * from ak_rekeningperkiraan where (idparent not in ('1-1100', '1-1200') ) and (idacc not in ('1-1100', '1-1200'))";
		$str="select * from ak_rekeningperkiraan where (idacc not like  '1.1.1.%' ) and  (idacc not like   '1.1.2.%' ) and  (idacc not like  '4.%')";
		//kas, bank, pendapatan zis
		$query=$this->db->query($str);
		/*$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'idacc','nama',$empty);
		}*/
		return $query;
	}
	
	public function nextcol($cur="A"){ // generate next column di XLS: A,B,C, dst.
		$int = ord($cur);
		$int++;
		$chr = chr($int);
		return $chr;
	}
	public function comboGetRptCash($empty=''){
		//id_cab=0=>center fill all kas
		$str='';
		if (in_array($this->role, [1, 6, 28, 40])) {
			
			$str="select * from ak_rekeningperkiraan where (idacc  like  '1.1.1.%' )  and id_cab=".$this->session->userdata('auth')->ID_CABANG;
		}else{
			$str="select * from ak_rekeningperkiraan where (idacc  like  '1.1.1.%' )  ".($this->session->userdata('auth')->ID_CABANG==1?' and id_cab=1':" and id_cab=".$this->session->userdata('auth')->ID_CABANG);
		}
		$query=$this->db->query($str);
		/*$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'idacc','nama',$empty);
		}
		return $combo;*/
		return $query;
	}

	public function comboGetrptBank($empty=''){

		if (substr($this->session->userdata('auth')->ROLE, 0, 5)=="Kasir"){
			$str="select * from ak_rekeningperkiraan where (idacc  like  '1.1.2.%' ) and id_cab=".$this->session->userdata('auth')->ID_CABANG;			
		}else{
			$str="select * from ak_rekeningperkiraan where (idacc  like  '1.1.2.%' ) ".($this->session->userdata('auth')->ID_CABANG==1?' and id_cab=1':" and id_cab=".$this->session->userdata('auth')->ID_CABANG);
		}
		
		$query=$this->db->query($str);
		/*$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'idacc','nama',$empty);
		}
		return $combo;*/
		return $query;
	}

	public function comboGetRptCash__($empty=''){
		//id_cab=0=>center fill all kas
		$str="select * from ak_rekeningperkiraan where (idacc  like  '1.1.1.%' )  ".($this->session->userdata('auth')->ID_CABANG==1?' and id_cab=1':" and id_cab=".$this->session->userdata('auth')->ID_CABANG);
		$query=$this->db->query($str);
		/*$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'idacc','nama',$empty);
		}
		return $combo;*/
		return $query;
	}
	public function comboGetrptBank__($empty=''){
		$str="select * from ak_rekeningperkiraan where (idacc  like  '1.1.2.%' ) ".($this->session->userdata('auth')->ID_CABANG==1?' and id_cab=1':" and id_cab=".$this->session->userdata('auth')->ID_CABANG);
		$query=$this->db->query($str);
		/*$combo = array();
		if (!empty($query)){
			$combo = $this->commonlib->buildcombo($query,'idacc','nama',$empty);
		}
		return $combo;*/
		return $query;
	}
}