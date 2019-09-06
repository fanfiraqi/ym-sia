<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class fixedasset extends MY_App {
	var $branch = array();
	function __construct()
	{
		parent::__construct();
		//$this->load->model('fixedasset_model');
		$this->config->set_item('mymenu', 'menuSatu');
		$this->config->set_item('mysubmenu', 'menuSatuEnam');
		$this->auth->authorize();
	}
	
	public function index()
	{	
		$this->template->set('breadcrumbs','<li><a href="#">Pengaturan</a></li><li><a href="#">Fixed Asset Grouping</a></li>');
		$this->template->set('pagetitle','Daftar Pengelompokan Fixed Asset Nurul Hayat');		
		$this->template->load('default','fmaster/vfixedasset');
	}
	
	
	
	public function json_data(){
		//if ($this->input->is_ajax_request()){
		
			$start = $this->input->get('iDisplayStart');
			$limit = $this->input->get('iDisplayLength');
			$sortby = $this->input->get('iSortCol_0');
			$srotdir = $this->input->get('sSortDir_0');
			
			$str = "SELECT  @nom:=@nom+1 as nomor, ak_fixed_asset_setting.*, (SELECT nama FROM ak_rekeningperkiraan WHERE idacc=ak_fixed_asset_setting.`accFixedAsset`) nmaset, (SELECT nama FROM ak_rekeningperkiraan WHERE idacc=ak_fixed_asset_setting.`accSusutBiaya` ) nmbiaya, (SELECT nama FROM ak_rekeningperkiraan WHERE idacc=ak_fixed_asset_setting.`accSusutAkm` ) nmakm  FROM ak_fixed_asset_setting where  id_cab=".$this->session->userdata('auth')->ID_CABANG;
			
						
			if ( $_GET['sSearch'] != "" )
			{
				
				//$str.= " and KOTA like '%".mysql_real_escape_string( $_GET['sSearch'] )."%' or ALAMAT like '%".mysql_real_escape_string( $_GET['sSearch'] )."%' ";
				
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
			$this->db->query("set @nom=0;");
			$query = $this->db->query($strfilter)->result();
			
			$aaData = array();
			foreach($query as $row){
				$aaData[] = array(
					'ID'=>$row->nomor,
					'ASET'=>$row->nmaset,
					'AKUM'=>$row->nmakm,
					'BIAYA'=>$row->nmbiaya,
					'NILAI'=>$row->nilai_perolehan_saat_ini,
					'LAMA'=>$row->lama_sisa_susut,
					'TGL'=>$row->tgl_perolehan,
					'ISACTIVE'=>($row->isactive=="1"?"Aktif":"Tidak Aktif"),
					'ACTION'=>' <a href="javascript:void(0)" onclick="editfixedasset(this)" data-id="'.$row->id.'"><i class="fa fa-edit" title="Edit"></i></a>&nbsp;| &nbsp;<a href="javascript:void(0)" onclick="deltruefixedasset('.$row->id.', '.$row->isactive.',\''.$row->nmaset.'\')"><i class="fa fa-trash-o" title="Delete"></i></a> | &nbsp;<a href="javascript:void(0)" onclick="delfixedasset('.$row->id.', '.$row->isactive.',\''.$row->nmaset.'\')"  <i class="fa fa-power-off" title="Aktif/non Aktifkan"></i></a>'	


				);
			}
			
			$output = array(
				"sEcho" => intval($_GET['sEcho']),
				"str" => $str,
				"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => $aaData
			);
			echo json_encode($output);
		//}
	}	
	
	public function getfixedasset(){
		$id = $this->input->post('id');
		$str="select * from mst_fixedasset where id=$id";
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
	
	public function viewPostJurnal(){
		if ($this->input->is_ajax_request()){	
			$this->template->set('pagetitle','Daftar Fixed Asset Nurul Hayat');		
			$this->template->load('default','fmaster/vfixedasset');
		}
	}
	public function saveSellAsset(){
		if ($this->input->is_ajax_request()){	
			$this->load->library('form_validation');
			$idtr_susut=$this->input->post('idtr_susut');
			$rules = array(				
					array(
						'field' => 'jual_nilai',
						'label' => 'NILAIPEROLEHAN',
						'rules' => 'trim|xss_clean|required|numeric'
					),
					array(
						'field' => 'jual_lama',
						'label' => 'LAMASUSUT',
						'rules' => 'trim|xss_clean|required|numeric'
					)
			);
			$this->form_validation->set_rules($rules);
			$this->form_validation->set_message('required', 'Field %s harus diisi.');
			$this->form_validation->set_message('numeric', 'Field %s harus diisi angka.');
			$respon = new StdClass();
			if ($this->form_validation->run() == TRUE){
				
				$this->db->trans_begin();
				try {						
					$harga_jual=$this->input->post('harga_jual');
					$nilaibuku=$this->input->post('jual_nilaibuku');
					$data = array(
							'tgl_jual' => $this->input->post('tgl_jual'),
							'nilai_jual' => $this->input->post('harga_jual'),
							'alasan_jual' => $this->input->post('alasan'),
							'mengetahui' => $this->input->post('mengetahui'),
							'isactive' =>0						
						);			
										
						if ($this->db->where('id',$idtr_susut)->update('ak_fixed_asset_setting',$data)){
									$this->db->trans_commit();
									$respon->status = 'success';
						} else {
							throw new Exception("gagal simpan");
						}
					//post jurnal => tidak dicover
					
					
				} catch (Exception $e) {
					$respon->status = 'error';
					$respon->errormsg = $e->getMessage();;
					$this->db->trans_rollback();
				}
				$respon->status = 'success';
			} else {
				$respon->status = 'error';
				$respon->errormsg = validation_errors();
				
			}
			
			echo json_encode($respon);
			//exit;
		
		} 
	}
	public function savefixedasset(){
		$isi="awal<br>";
		if ($this->input->is_ajax_request()){	
			$this->load->library('form_validation');
			$state=$this->input->post('state');
			$rules = array(				
					array(
						'field' => 'np_saat_ini',
						'label' => 'NILAI_PEROLEHAN_SAAT INI',
						'rules' => 'trim|xss_clean|required|numeric'
					),
					array(
						'field' => 'lama_saat_ini',
						'label' => 'LAMA_SUSUT_SD_SAAT_INI',
						'rules' => 'trim|xss_clean|required|numeric'
					),
					array(
						'field' => 'lama_sisa',
						'label' => 'LAMA_SISA',
						'rules' => 'trim|xss_clean|required|numeric'
					)
			);
			$this->form_validation->set_rules($rules);
			$this->form_validation->set_message('required', 'Field %s harus diisi.');
			$this->form_validation->set_message('numeric', 'Field %s harus diisi angka.');
			$respon = new StdClass();
			if ($this->form_validation->run() == TRUE){
				
				$this->db->trans_begin();
				try {						
					
					$data = array(
							'accFixedAsset' => $this->input->post('akunAsset'),
							'accSusutAkm' => $this->input->post('akunAkum'),
							'accSusutBiaya' => $this->input->post('akunSusut'),
							'id_cab' => $this->session->userdata('auth')->ID_CABANG,
							'tgl_perolehan' => $this->input->post('tgl_perolehan'),							
							'nilai_perolehan_saat_ini' => $this->input->post('np_saat_ini'),						
							'lama_sisa_susut' =>$this->input->post('lama_sisa'),
							'lama_susut_sd_saat_ini' =>$this->input->post('lama_saat_ini'),
							'keterangan' =>$this->input->post('ket'),
							'isactive' =>$this->input->post('status')						
						);			
					

					if($state=="add"){ 
						
						if ($this->db->insert('ak_fixed_asset_setting',$data)){
							$this->db->trans_commit();
							$respon->status = 'success';
						} else {
							throw new Exception("gagal simpan");
						}

						
					}else{
											
						if ($this->db->where('id',$state)->update('ak_fixed_asset_setting',$data)){
									$this->db->trans_commit();
									$respon->status = 'success';
						} else {
							throw new Exception("gagal simpan");
						}
					}
					$isi.="Proses query<br>";
					
				} catch (Exception $e) {
					$respon->status = 'error';
					$respon->errormsg = $e->getMessage();;
					$this->db->trans_rollback();
				}
				$respon->status = 'success';
			} else {
				$respon->status = 'error';
				$respon->errormsg = validation_errors();
				
			}
			$respon->data=$isi;	
			echo json_encode($respon);
			//exit;
		
		} 
	}
	public function deltruefixedasset(){
		$id=$this->input->post('idx');
		$sts=$this->input->post('status');
		$res = $this->common_model->deltruefixedasset($id, $sts);
		return $res;
	}
	public function ubahStatus(){
		$id=$this->input->post('idx');
		$sts=$this->input->post('status');
		$res = $this->common_model->ubahStatusFixedAsset($id, $sts);
		return $res;
	}
	public function getFixedAsetSet(){
		$periode=$this->common_model->comboGetPeriode();
		$id = $this->input->post('id');
		$str="select f.*, (select nama from ak_rekeningperkiraan where idacc=f.accFixedAsset) akunasset, (select nama from ak_rekeningperkiraan where idacc=f.accSusutAkm) akunakum, (select nama from ak_rekeningperkiraan where idacc=f.accSusutBiaya) akunsusut from ak_fixed_asset_setting f where id='$id' ";
		$query = $this->db->query($str)->row();		

		if(empty($query)){
			$respon['str'] = $str;
			$respon['status'] = 'error';
			$respon['errormsg'] = 'Invalid Data';
		} else {
			$respon['status'] = 'success';
			$respon['data'] = $query;
			
		}
		$respon['periode']=$periode;
		echo json_encode($respon);
	}
	public function getAkunAsset(){
		$keyword = $this->input->post('term');
		$data['response'] = 'false';
		$str="select * from ak_rekeningperkiraan where idacc like '1.2%' and status=1 ".($this->session->userdata('auth')->ID_CABANG==0?"":" and id_cab=".$this->session->userdata('auth')->ID_CABANG)." and (nama LIKE '%{$keyword}%'  or idacc LIKE '%{$keyword}%')  AND (UPPER(nama) NOT LIKE '%AKUM%' AND UPPER(nama) NOT LIKE '%TANAH%')";
		$query = $this->db->query($str)->result();

		
		if( ! empty($query) )
		{
			$data['response'] = 'true'; //Set response
			$data['message'] = array(); //Create array
			foreach( $query as $row )
			{
				$data['message'][] = array(
					'pid'=>$row->idacc,
					'id'=>$row->idacc,
					'label' => $row->idacc.' - '.$row->nama,
					'value' => $row->nama,
					'initval' => $row->initval,
					''
				);
			}
		}
		echo json_encode($data);
	}
	public function getAkunKredit(){
		$keyword = $this->input->post('term');
		$data['response'] = 'false';
		$str="select * from ak_rekeningperkiraan where idacc not like '4-%' and status=1 ".($this->session->userdata('auth')->ID_CABANG==0?"":" and id_cab=".$this->session->userdata('auth')->ID_CABANG)." and (nama LIKE '%{$keyword}%' or idacc LIKE '%{$keyword}%') ";
		$query = $this->db->query($str)->result();

		
		if( ! empty($query) )
		{
			$data['response'] = 'true'; //Set response
			$data['message'] = array(); //Create array
			foreach( $query as $row )
			{
				$data['message'][] = array(
					'pid'=>$row->idacc,
					'id'=>$row->idacc,
					'label' => $row->idacc.' - '.$row->nama,
					'value' => $row->nama,
					'kelompok' => $row->kelompok,
					''
				);
			}
		}
		echo json_encode($data);
	}

	public function getAkunAkum(){
		$keyword = $this->input->post('term');
		$data['response'] = 'false';
		$str="select * from ak_rekeningperkiraan where idacc like '1.2%' and level>3 and status=1 ".($this->session->userdata('auth')->ID_CABANG==0?"":" and id_cab=".$this->session->userdata('auth')->ID_CABANG)." and (nama LIKE '%{$keyword}%' or idacc LIKE '%{$keyword}%' ) AND upper(nama) like '%AKUM%'";
		$query = $this->db->query($str)->result();

		
		if( ! empty($query) )
		{
			$data['response'] = 'true'; //Set response
			$data['message'] = array(); //Create array
			foreach( $query as $row )
			{
				$data['message'][] = array(
					'pid'=>$row->idacc,
					'id'=>$row->idacc,
					'label' => $row->idacc.' - '.$row->nama,
					'value' => $row->nama,
					''
				);
			}
		}
		echo json_encode($data);
	}
	public function getAkunSusut(){
		$keyword = $this->input->post('term');
		$data['response'] = 'false';
		$str="select * from ak_rekeningperkiraan where kelompok='B' and level>3 and status=1 ".($this->session->userdata('auth')->ID_CABANG==0?"":" and id_cab=".$this->session->userdata('auth')->ID_CABANG)." and (nama LIKE '%{$keyword}%' or idacc LIKE '%{$keyword}%') AND upper(nama) like '%PENYUSUTAN%'";
		$query = $this->db->query($str)->result();

		
		if( ! empty($query) )
		{
			$data['response'] = 'true'; //Set response
			$data['message'] = array(); //Create array
			foreach( $query as $row )
			{
				$data['message'][] = array(
					'pid'=>$row->idacc,
					'id'=>$row->idacc,
					'label' => $row->idacc.' - '.$row->nama,
					'value' => $row->nama,
					''
				);
			}
		}
		echo json_encode($data);
	}
	public function getFixedToSell(){
		$keyword = $this->input->post('term');
		$data['response'] = 'false';
		$str="SELECT r.nama, PERIOD_DIFF(DATE_FORMAT(CURDATE(), '%Y%m'),DATE_FORMAT(tgl_perolehan,'%Y%m')) lamabln, f.* FROM ak_fixed_asset_setting f, ak_rekeningperkiraan r WHERE f.accfixedasset=r.idacc AND f.isactive=1 ".($this->session->userdata('auth')->ID_CABANG==0?"":" and r.id_cab=".$this->session->userdata('auth')->ID_CABANG)." and r.nama LIKE '%{$keyword}%' ";
		$query = $this->db->query($str)->result();

		
		if( ! empty($query) )
		{
			$data['response'] = 'true'; //Set response
			$data['message'] = array(); //Create array
			foreach( $query as $row )
			{
				$data['message'][] = array(
					'pid'=>$row->accFixedAsset,
					'id'=>$row->accFixedAsset,
					'label' => $row->accFixedAsset.' - '.$row->nama,
					'nilai' => ( $row->nilai_perolehan_saat_ini / $row->lama_sisa_susut) * ( $row->lama_sisa_susut + $row->lama_susut_sd_saat_ini ),
					'tgl_perolehan' => $row->tgl_perolehan,
					'lama' => ( $row->lama_sisa_susut + $row->lama_susut_sd_saat_ini ),
					'idtr_susut' => $row->id,
					'idakum' => $row->accSusutAkm,
					'sisabln' => $row->lama_sisa_susut,
					'lamabln' => $row->lama_susut_sd_saat_ini,
					'status' => ($row->isactive==1?"Aktif":"Tidak Aktif"),
					'value' => $row->nama,
					''
				);
			}
		}
		echo json_encode($data);
	}

	public function getNilaiSisa(){
		//$periode=$this->common_model->comboGetPeriode();
		$idakum = $this->input->post('idakum');
		$strCekUsiaDiJurnal="select sum(jumlah) jml, count(*) cnt from jurnal where (idperkdebet='".$idakum."' or idperkkredit='".$idakum."') and sumber_data='susut' and concat(tahun,bulan)<='".date('Ym')."' and status_validasi=1";		
		$query = $this->db->query($strCekUsiaDiJurnal)->row();	

		if(empty($query)){
			$respon['strCekUsiaDiJurnal'] = $strCekUsiaDiJurnal;
			$respon['status'] = 'error';
			$respon['errormsg'] = 'Invalid Data';
		} else {
			$respon['status'] = 'success';
			$respon['item'] = $query;
			
		}
		
		echo json_encode($respon);
	}
}
