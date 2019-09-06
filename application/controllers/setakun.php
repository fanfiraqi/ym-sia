<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class setakun extends MY_App {
	var $branch = array(); var $gate_db;
	function __construct()
	{
		parent::__construct();
		$this->load->model('cabang_model');
		$this->config->set_item('mymenu', 'menuSatu');		
		$this->gate_db=$this->load->database('gate', TRUE);
		$this->donasi_db=$this->load->database('donasi', TRUE);
		$this->auth->authorize();
	}
	
	public function donasi()
	{	
		$this->config->set_item('mysubmenu', 'menuSatuTujuh');
		$data['tipe'] =$this->common_model->comboTipeDonasi();
		$this->template->set('breadcrumbs','<li><a href="#">Pengaturan</a></li><li class="active"><span>Set Akun Jenis Donasi</span></li>');
		$this->template->set('pagetitle','Set Akun Jenis Donasi');		
		$this->template->load('default','fmaster/vsetdonasi',$data);
	}
	
	public function bank()
	{	
		$this->config->set_item('mysubmenu', 'menuSatuDelapan');
		$data['row'] =$this->gate_db->query("select * from mst_bank where cabang_id=1 and active=1")->result();
		$this->template->set('breadcrumbs','<li><a href="#">Pengaturan</a></li><li class="active"><span>Set Akun Rekening Bank Pusat</span></li>');
		$this->template->set('pagetitle','Set Akun Rekening Bank Pusat');		
		$this->template->load('default','fmaster/vsetakunbank',$data);
	}
	
	public function aset()
	{	
		$this->config->set_item('mySubMenu', 'menuSatuEnam');
		$data['cabang'] =$this->common_model->comboCabang();
		//$arrKel=array("AT"=>"Aset Tetap (AT)", "ATLKI"=>"Aset Tidak Lancar Kelolaan dana Infaq (ATLKI)","ATLKW"=>"Aset Tidak Lancar Kelolaan dana Waqaf (ATLKW)");
		$arrKel=array("1.2.1"=>"Aset Tetap (AT)", "1.2.3"=>"Aset Tidak Lancar Kelolaan dana Infaq (ATLKI)","1.2.5"=>"Aset Tidak Lancar Kelolaan dana Waqaf (ATLKW)");
		$arrGrup=array("Bangunan"=>"Bangunan", "Kendaraan"=>"Kendaraan","Inventaris"=>"Inventaris");
		$data['kelompok'] =$arrKel;
		$data['grup'] =$arrGrup;
		$queryAset = $this->db->query("select * from ak_rekeningperkiraan where level=5 and idacc like '1.2%' and id_cab=1" )->result();
		$data['aset'] = $this->commonlib->buildcombo2($queryAset,'idacc','idacc','nama','');
		$queryAkum = $this->db->query("select * from ak_rekeningperkiraan where level=5 and nama like '%Akumulasi%'  ")->result();
		$data['akum'] = $this->commonlib->buildcombo($queryAkum,'idacc','nama','');
		$querySusut = $this->db->query("select * from ak_rekeningperkiraan where level=5 and kelompok='B' and  nama like '%penyusutan%'")->result();
		$data['susut'] = $this->commonlib->buildcombo($querySusut,'idacc','nama','');


		$this->template->set('breadcrumbs','<li><a href="#">Pengaturan</a></li><li class="active"><span>Set Akun Aset</span></li>');
		$this->template->set('pagetitle','Set Akun Aset');		
		$this->template->load('default','fmaster/vsetakunaset',$data);
	}
	
	public function getAkunAset(){
		$cabang = $this->input->post('cabang');
		$kelompok = $this->input->post('kelompok');
		
		$queryAset = $this->db->query("select * from ak_rekeningperkiraan where level=5 and idacc like '".$kelompok."%'  and id_cab=".$cabang )->result();
		$queryAkum = $this->db->query("select * from ak_rekeningperkiraan where level=5 and  nama like '%Akumulasi%'  and id_cab=".$cabang)->result();
		$querySusut = $this->db->query("select * from ak_rekeningperkiraan where level=5 and  kelompok='B'  and  nama like '%penyusutan%'  and id_cab=".$cabang)->result();
		$respon = new StdClass();
		$respon->status = 0;
		if (!empty($queryAset)){
			$respon->status = 1;
			$respon->dataAset = $queryAset;
			$respon->dataAkum = $queryAkum;
			$respon->dataSusut = $querySusut;
		}
		echo json_encode($respon);
	}
	
	public function json_data_aset(){
			
			$start = $this->input->get('iDisplayStart');
			$limit = $this->input->get('iDisplayLength');
			$sortby = $this->input->get('iSortCol_0');
			$srotdir = $this->input->get('sSortDir_0');
			
			$cabang=(!empty($_GET['cabang'])?$_GET['cabang']:"");

			$str = "select * from ak_set_aset where  id_cab ='".$cabang."' and active=1";
			
			
			
			if ( $_GET['sSearch'] != "" )
			{
				
				$str.= "  and  nama like '%".mysql_real_escape_string( $_GET['sSearch'] )."%' or nik like '%".mysql_real_escape_string( $_GET['sSearch'] )."%' ";
				
			}
			
			
			if ( isset( $_GET['iSortCol_0'] ) )
			{
				$str .= " ORDER BY ".$_GET['mDataProp_'.$_GET['iSortCol_0']]." ".$_GET['sSortDir_0'];
			}
			
			
			$strfilter = $str;
			if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
			{
				$strfilter .= " LIMIT ". mysql_real_escape_string( $_GET['iDisplayStart'] ) .", ". mysql_real_escape_string( $_GET['iDisplayLength'] );
			}
			
					
			$iFilteredTotal = $this->db->query($str)->num_rows();			
			$iTotal = $iFilteredTotal;
			$query = $this->db->query($strfilter)->result();
			$arrKel=array("1.2.1"=>"Aset Tetap (AT)", "1.2.3"=>"Aset Tidak Lancar Kelolaan dana Infaq (ATLKI)","1.2.5"=>"Aset Tidak Lancar Kelolaan dana Waqaf (ATLKW)");
			$aaData = array();
			foreach($query as $row){
				$aaData[] = array(
						'ID'=>$row->id,
						'KELOMPOK'=>$arrKel[$row->kelompok],
						'GRUP'=>$row->grup,
						'ID_ASET'=>$row->id_aset,
						'ID_AKUM'=>$row->id_akum,
						'ID_SUSUT'=>$row->id_susut,
						'PERSEN_SUSUT'=>$row->persen_susut,
						'ACTION'=>"<a href='javascript:void(0)' onclick='editThis(this)' data-id='".$row->id."'><i class='fa fa-edit' title='Edit'></i></a> | <a href='javascript:void()' onclick=\"delThis(".$row->id.",'".$arrKel[$row->kelompok]."')\"><i class='fa fa-trash-o' title='Delete'></i></a>"
						
					);			
			}
			
			$output = array(
				"squery"=>$strfilter,
				"sEcho" => intval($_GET['sEcho']),
				"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => $aaData
			);
			echo json_encode($output);
	}

	public function json_data_donasi(){
			
			$start = $this->input->get('iDisplayStart');
			$limit = $this->input->get('iDisplayLength');
			$sortby = $this->input->get('iSortCol_0');
			$srotdir = $this->input->get('sSortDir_0');
			
			$level3=(!empty($_GET['lvl3'])?$_GET['lvl3']:'1-1100');
			$tipe=(!empty($_GET['tipe'])?$_GET['tipe']:"");

			$str = "select * from jenis_donasi where  type='".$tipe."' and active=1";
			
			if ( isset( $_GET['iSortCol_0'] ) )
			{
				$str .= " ORDER BY id, ".$_GET['mDataProp_'.$_GET['iSortCol_0']]." ".$_GET['sSortDir_0'];
			}else{
				$str .=" ORDER BY  id";
			}
			
			$strfilter = $str;
			
			$iFilteredTotal = $this->donasi_db->query($str)->num_rows();
			if ($iFilteredTotal>1){	//if level 4 is lowest =>pendapatan zis
				if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
				{
					$strfilter .= " LIMIT ". mysql_real_escape_string( $_GET['iDisplayStart'] ) .", ". mysql_real_escape_string( $_GET['iDisplayLength'] );
				}
			}
			$iTotal = $iFilteredTotal;
			$query = $this->donasi_db->query($strfilter)->result();
			
			$aaData = array();
			foreach($query as $row){
				$aaData[] = array(
						'ID'=>$row->id,
						'TIPE'=>$row->type,
						'TITLE'=>$row->title,
						'IDACC_ZIS'=>'<input type="hidden" name="txtid[]" id="txtid[]" value="'.$row->id.'"><input type="text" name="txtidacc[]" id="txtidacc[]" value="'.$row->idacc_zis.'">',
						'IDACC_AMIL'=>'<input type="text" name="txtidacc_amil[]" id="txtidacc_amil[]" value="'.$row->idacc_amil.'">',
						'PERSEN_AMIL'=>'<input type="text" name="txtpersen_amil[]" id="txtpersen_amil[]" value="'.$row->persen_amil.'">'
					);			
			}
			
			$output = array(
				"squery"=>$strfilter,
				"sEcho" => intval($_GET['sEcho']),
				"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => $aaData
			);
			echo json_encode($output);
	}

	public function saveAkunDonasi(){
		$teks="awal<br>";
		if ($this->input->is_ajax_request()){	
			$respon = new StdClass();			
				$this->donasi_db->trans_begin();
				try {						
					$arrId=$this->input->post('txtid');
					$arrIdacc=$this->input->post('txtidacc');
					$arrIdacc_amil=$this->input->post('txtidacc_amil');
					$persen_amil=$this->input->post('txtpersen_amil');
					for($i=0; $i<sizeof($arrId); $i++){
						$strUpdate="update jenis_donasi set persen_amil=".$persen_amil[$i].", idacc_zis='".$arrIdacc[$i]."', idacc_amil='".$arrIdacc_amil[$i]."'  where id='".$arrId[$i]."'";
						$this->donasi_db->query($strUpdate);
						$teks.=$strUpdate."<br>";
						
					}
					$this->donasi_db->trans_commit();
					$respon->status = 'success';			
					$respon->data = $arrIdacc;			
				} catch (Exception $e) {
					$respon->status = 'error';
					$respon->errormsg = $e->getMessage();
					$this->donasi_db->trans_rollback();
				}
				//$respon->status = 'success';
			
			$respon->teks=$teks;	
			echo json_encode($respon);
			//exit;
		
		} 
	}

	public function saveAkunBank(){
		$teks="awal<br>";
		if ($this->input->is_ajax_request()){	
			$respon = new StdClass();			
				$this->gate_db->trans_begin();
				try {						
					$arrId=$this->input->post('txtid');
					$arrIdacc=$this->input->post('txtidacc');
					for($i=0; $i<sizeof($arrId); $i++){
						$strUpdate="update mst_bank set idacc='".$arrIdacc[$i]."'  where id='".$arrId[$i]."'";
						$this->gate_db->query($strUpdate);
						$teks.=$strUpdate."<br>";
						
					}
					$this->gate_db->trans_commit();
					$respon->status = 'success';			
					$respon->data = $arrIdacc;			
				} catch (Exception $e) {
					$respon->status = 'error';
					$respon->errormsg = $e->getMessage();
					$this->gate_db->trans_rollback();
				}
				//$respon->status = 'success';
			
			$respon->teks=$teks;	
			echo json_encode($respon);
			//exit;
		
		} 
	}


	public function saveSetAset(){
		if ($this->input->is_ajax_request()){
		
			$this->load->library('form_validation');
			$state=$this->input->post('state');
			
			$rules = array(
				array(
					'field' => 'persen',
					'label' => 'PERSEN_PENYUSUTAN',
					'rules' => 'trim|xss_clean|required'
				)
			);
			$this->form_validation->set_rules($rules);
			$this->form_validation->set_message('required', 'Field %s harus diisi.');
			$this->form_validation->set_message('greater_than', 'Field %s harus lebih besar dari 0.');
			$respon = new StdClass();
			if ($this->form_validation->run() == TRUE){
				
				$this->db->trans_begin();
				try {
					
					$data = array(
						'kelompok' => $this->input->post('kelompok'),
						'grup' => $this->input->post('grup'),
						'id_cab' =>$this->input->post('cabang'),
						'id_aset' =>$this->input->post('coa_aset'),
						'id_akum' =>$this->input->post('coa_akum'),
						'id_susut' =>$this->input->post('coa_susut'),
						'persen_susut' =>$this->input->post('persen'),
						'active' => $this->input->post('status')
					);
					
					if($state=="add"){ 
						
						if ($this->db->insert('ak_set_aset',$data)){
							$this->db->trans_commit();
							$respon->status = 'success';
						} else {
							throw new Exception("gagal simpan");
						}
					}else{
												
						if ($this->db->where('id',$state)->update('ak_set_aset',$data)){
									$this->db->trans_commit();
									$respon->status = 'success';
						} else {
							throw new Exception("gagal simpan");
						}
					}
				} catch (Exception $e) {
					$respon->status = 'error';
					$respon->errormsg = $e->getMessage();
					$this->db->trans_rollback();
				}
				$respon->status = 'success';
			} else {
				$respon->status = 'error';
				$respon->errormsg = validation_errors();
				
			}
			echo json_encode($respon);
			
		}
			
	}


	public function delThis(){
		$id=$this->input->post('idx');
		$nmtable=$this->input->post('proses');
		$field=$this->input->post('field');
		$res = $this->common_model->delThis($id,$nmtable,$field);
		return $res;
	}

	public function editThis(){
		$id = $this->input->post('id');	//id as nik=>peg_pendidikan
		$nmtable=$this->input->post('tabel');
		$field=$this->input->post('field');
		
		$str="select * from ".$nmtable." where ".$field."='".$id."'";
		$query = $this->db->query($str)->row();		

		if(empty($query)){
			$respon['status'] = 'error';
			$respon['errormsg'] = 'Invalid Data';
		} else {
			
			$respon['status'] = 'success';
			$respon['data'] = $query;
			
			
		}
		echo json_encode($respon);
	}
	
}
