<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class aset extends MY_App {
	var $branch = array();
	function __construct()
	{
		parent::__construct();
		//$this->load->model('fixedasset_model');
		$this->config->set_item('mymenu', 'menuEmpat');

		$this->auth->authorize();
	}
	
	public function index()
	{	$this->config->set_item('mysubmenu', 'menuEmpatSatu');
		if ($this->session->userdata('auth')->id_cabang=="1"){
			$data['cabang'] =$this->common_model->comboCabang();
		}else{
			$data['cabang'] =$this->common_model->getCabang($this->session->userdata('auth')->id_cabang);
		}
		
		//$arrKel=array("AT"=>"Aset Tetap (AT)", "ATLKI"=>"Aset Tidak Lancar Kelolaan dana Infaq (ATLKI)","ATLKW"=>"Aset Tidak Lancar Kelolaan dana Waqaf (ATLKW)");
		$arrKel=array("1.2.1"=>"Aset Tetap (AT)", "1.2.3"=>"Aset Tidak Lancar Kelolaan dana Infaq (ATLKI)","1.2.5"=>"Aset Tidak Lancar Kelolaan dana Waqaf (ATLKW)");
		$arrGrup=array("Bangunan"=>"Bangunan", "Kendaraan"=>"Kendaraan","Inventaris"=>"Inventaris");
		$data['kelompok'] =$arrKel;
		$data['grup'] =$arrGrup;
		$this->template->set('breadcrumbs','<li><a href="#">Pengaturan</a></li><li><a href="#">Kelola Asset </a></li>');
		$this->template->set('pagetitle','Kelola Asset ');		
		$this->template->load('default','fmaster/vaset', $data);
	}
	
	public function disposal()
	{	$this->config->set_item('mysubmenu', 'menuEmpatDua');
		if ($this->session->userdata('auth')->id_cabang=="1"){
			$data['cabang'] =$this->common_model->comboCabang();
		}else{
			$data['cabang'] =$this->common_model->getCabang($this->session->userdata('auth')->id_cabang);
		}
		
		
		$arrKel=$this->arrKel;
		$arrGrup=$this->arrGrup;
		$data['kelompok'] =$arrKel;
		$data['grup'] =$arrGrup;
		$this->template->set('breadcrumbs','<li><a href="#">Pengaturan</a></li><li><a href="#">Disposing Asset </a></li>');
		$this->template->set('pagetitle','Disposing Asset ');		
		$this->template->load('default','fmaster/vdisposal_aset', $data);
	}
	
	public function json_data_dispose(){
			$cabang = $this->input->get('cabang');
			$arrKel=$this->arrKel;
			$arrGrup=$this->arrGrup;
			$start = $this->input->get('iDisplayStart');
			$limit = $this->input->get('iDisplayLength');
			$sortby = $this->input->get('iSortCol_0');
			$srotdir = $this->input->get('sSortDir_0');
			
			$str = "SELECT  @nom:=@nom+1 as nomor, ak_asset_list.*  FROM ak_asset_list where nilai_jual>0 and  id_cab=".($this->session->userdata('auth')->id_cabang>1? $this->session->userdata('auth')->id_cabang :$cabang);
			
						
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
					'KELOMPOK'=>$arrKel[$row->kelompok],
					'GRUP'=>$row->grup,
					'NAMA_ASET'=>$row->nama_aset,
					'TGL_PEROLEHAN'=>$row->tgl_perolehan,
					'NILAI_PEROLEHAN'=>$row->nilai_perolehan,
					'TGL_JUAL'=>$row->tgl_jual,
					'NILAI_JUAL'=>$row->nilai_jual,
					'ISACTIVE'=>($row->isactive=="1"?"Aktif":"Tidak Aktif"),
					'ACTION'=>' -'	


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
	}
	public function json_data(){
		//if ($this->input->is_ajax_request()){
			$cabang = $this->input->get('cabang');
			$arrKel=$this->arrKel;
			$arrGrup=$this->arrGrup;
			$start = $this->input->get('iDisplayStart');
			$limit = $this->input->get('iDisplayLength');
			$sortby = $this->input->get('iSortCol_0');
			$srotdir = $this->input->get('sSortDir_0');
			
			$str = "SELECT  @nom:=@nom+1 as nomor, ak_asset_list.*  FROM ak_asset_list where  id_cab=".($this->session->userdata('auth')->id_cabang>1? $this->session->userdata('auth')->id_cabang :$cabang);
			
						
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
					'KELOMPOK'=>$arrKel[$row->kelompok],
					'GRUP'=>$row->grup,
					'NAMA_ASET'=>$row->nama_aset,
					'TGL_PEROLEHAN'=>$row->tgl_perolehan,
					'NILAI_PEROLEHAN'=>$row->nilai_perolehan,
					'PERSEN_SUSUT_THN'=>$row->persen_susut_thn,
					'UMUR_EKONOMIS'=>$row->umur_ekonomis,
					'ISACTIVE'=>($row->isactive=="1"?"Aktif":"Tidak Aktif"),
					'ACTION'=>' <a href="javascript:void(0)" onclick="editfixedasset(this)" data-id="'.$row->id.'"><i class="fa fa-edit" title="Edit"></i></a>&nbsp;| &nbsp;<a href="javascript:void(0)" onclick="deltruefixedasset('.$row->id.', '.$row->isactive.',\''.$row->nama_aset.'\')"><i class="fa fa-trash-o" title="Delete"></i></a> | &nbsp;<a href="javascript:void(0)" onclick="delfixedasset('.$row->id.', '.$row->isactive.',\''.$row->nama_aset.'\')"  <i class="fa fa-power-off" title="Aktif/non Aktifkan"></i></a>'	


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
	
	public function getAset(){
		$id = $this->input->post('id');
		$str="select * from ak_asset_list where id=$id";
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
										
						if ($this->db->where('id',$idtr_susut)->update('ak_asset_list',$data)){
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
				//$respon->status = 'success';
			} else {
				$respon->status = 'error';
				$respon->errormsg = validation_errors();
				
			}
			
			echo json_encode($respon);
			//exit;
		
		} 
	}
	public function saveAsset(){
		$isi="awal<br>";
		if ($this->input->is_ajax_request()){	
			$this->load->library('form_validation');
			$state=$this->input->post('state');
			$rules = array(				
					array(
						'field' => 'nilai_perolehan',
						'label' => 'NILAI_PEROLEHAN',
						'rules' => 'trim|xss_clean|required|numeric'
					),
					array(
						'field' => 'nama_aset',
						'label' => 'NAMA_ASET',
						'rules' => 'trim|xss_clean|required'
					),
					array(
						'field' => 'nilai_residu',
						'label' => 'NILAI_RESIDU',
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
							'kelompok' => $this->input->post('kelompok'),
							'grup' => $this->input->post('grup'),
							'nama_aset' => $this->input->post('nama_aset'),							
							'id_cab' => $this->input->post('cabang'),							
							'tgl_perolehan' => $this->input->post('tgl_perolehan'),							
							'nilai_perolehan' => $this->input->post('nilai_perolehan'),						
							'persen_susut_thn' =>$this->input->post('persen_susut'),
							'umur_ekonomis' =>$this->input->post('umur'),
							'nilai_residu' =>$this->input->post('nilai_residu'),
							'nilai_susut_thn' =>$this->input->post('nilai_susut'),
							'keterangan' =>$this->input->post('ket'),
							'isactive' =>$this->input->post('status')						
						);			
					

					if($state=="add"){ 
						
						if ($this->db->insert('ak_asset_list',$data)){
							$this->db->trans_commit();
							$respon->status = 'success';
						} else {
							throw new Exception("gagal simpan");
						}

						
					}else{
											
						if ($this->db->where('id',$state)->update('ak_asset_list',$data)){
									$this->db->trans_commit();
									$respon->status = 'success';
						} else {
							throw new Exception("gagal simpan");
						}
					}
					$isi.="Proses query<br>";
					
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
		$str="select f.*, (select nama from ak_rekeningperkiraan where idacc=f.accFixedAsset) akunasset, (select nama from ak_rekeningperkiraan where idacc=f.accSusutAkm) akunakum, (select nama from ak_rekeningperkiraan where idacc=f.accSusutBiaya) akunsusut from ak_asset_list f where id='$id' ";
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

	public function getPersen(){
		
		$kelompok = $this->input->post('kelompok');
		$cabang = $this->input->post('cabang');
		$grup = $this->input->post('grup');
		
		$query=$this->db->query("select persen_susut from ak_set_aset where kelompok='".$kelompok."' and id_cab=".$cabang." and grup='".$grup."'")->row();

		if(empty($query)){
			
			$respon['status'] = 'error';
			$respon['errormsg'] = 'Invalid Data';
		} else {
			$respon['status'] = 'success';
			$respon['persen'] = $query->persen_susut;
			
		}
		
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
	public function getAsetToSell(){
		$keyword = $this->input->post('term');
		$data['response'] = 'false';
		$str="SELECT f.*, PERIOD_DIFF(DATE_FORMAT(CURDATE(), '%Y%m'),DATE_FORMAT(tgl_perolehan,'%Y%m')) lamabln FROM ak_asset_list f WHERE  f.isactive=1 ".($this->session->userdata('auth')->id_cabang==0?"":" and id_cab=".$this->session->userdata('auth')->id_cabang)." and nama_aset LIKE '%{$keyword}%' ";
		$query = $this->db->query($str)->result();

		
		if( ! empty($query) )
		{
			$data['response'] = 'true'; //Set response
			$data['message'] = array(); //Create array
			foreach( $query as $row )
			{
				$data['message'][] = array(
					'pid'=>$row->id,
					'id'=>$row->id,
					'idtr_susut' => $row->id,
					'label' => $row->nama_aset,
					'nilai_perolehan' => $row->nilai_perolehan,
					'tgl_perolehan' => $row->tgl_perolehan,
					'umur' => $row->umur_ekonomis,
					'lama' => round($row->lamabln/12),
					'nilai_susut' => $row->nilai_susut_thn,					
					'status' => ($row->isactive==1?"Aktif":"Tidak Aktif"),
					'value' => $row->nama_aset,
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
