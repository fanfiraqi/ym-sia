<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class rekeningakun extends MY_App {
	var $branch = array();
	

	function __construct()
	{
		parent::__construct();
		$this->load->model('rekening_model');
		$this->gate_db=$this->load->database('gate', TRUE);
		$this->config->set_item('mymenu', 'menuSatu');
		$this->config->set_item('mysubmenu', 'menuSatuEmpat');
		$this->auth->authorize();

	}
	

	public function index()
	{	
		$cab_source=$this->common_model->cbget_sourcecab();		
		$cab_dest=$this->common_model->cbget_destcab();	
		$cabang=$this->common_model->comboGetAllCabang();		
		$data['cabang']=$cabang;
		$data['cab_dest']=$cab_dest;
		$data['cab_source']=$cab_source;
		$this->template->set('breadcrumbs','<li><a href="#">Pengaturan</a></li><li><a href="#">Master Rekening Perkiraan</a></li>');
		$this->template->set('pagetitle','Maintenance Rekening Perkiraan Akuntansi');		
		$this->template->load('default','fmaster/vrekening',$data);
		
	}
	public function checking(){
		$cab_source = $this->input->post('cab_source');
		//$cab_dest =(strlen($this->input->post('cab_dest')) <=1?"0".$this->input->post('cab_dest'): $this->input->post('cab_dest'));
		$res=$this->db->query("select count(*) jml from ak_rekeningperkiraan where id_cab=".$this->input->post('cab_dest'))->row();
		$respon = new StdClass();
			if ($res->jml<=0) {
				$this->db->trans_commit();
				$respon->status = 'success';
				$respon->jml = $res->jml;
			} else {
				$respon->status = 'error';
				$respon->errormsg = $this->input->post('cab_dest');
				
			}
		
		echo json_encode($respon);
	}
	public function copyAkun(){
		
		$str="";
	if ($this->input->is_ajax_request()){
		$cab_source = $this->input->post('cab_source');
		$kode_cab_dest=$this->input->post('cab_dest');
		$res=$this->db->query("select count(*) jml from ak_rekeningperkiraan where id_cab=".$this->input->post('cab_dest'))->row();
		if ($res->jml<=0) {
			$rsnmcab=$this->gate_db->query("select kota from mst_cabang where id_cabang=".$cab_source)->row();
			$rsnmcab_dest=$this->gate_db->query("select kota from mst_cabang where id_cabang=".$this->input->post('cab_dest'))->row();
			$cab_dest =(strlen($this->input->post('cab_dest')) <=1?"0".$this->input->post('cab_dest'): $this->input->post('cab_dest'));
			$str="INSERT INTO ak_rekeningperkiraan (idacc, nama, kelompok, `level`, idparent, id_cab, d_c )
				SELECT  CONCAT(SUBSTR(idacc, 1,6 ),'".$cab_dest."' , SUBSTR(idacc, 9,3 )) idnew  , REPLACE(ucase(nama), '".strtoupper(trim($rsnmcab->kota))."', '".strtoupper(trim($rsnmcab_dest->kota))."') nm, kelompok, `level`,
				IF(LENGTH(idparent)<=5, idparent, CONCAT(SUBSTR(idparent, 1,6), '".$cab_dest."') ) deparent, '".$this->input->post('cab_dest')."', d_c
				FROM ak_rekeningperkiraan
				WHERE `level`>3 AND id_cab=".$cab_source;
			$respon = new StdClass();
				if ($this->db->query($str)) {
					$this->db->trans_commit();
					$respon->status = 'success_insert';
				} else {
					$respon->status = 'error';
					$respon->errormsg = $str;
					$this->db->trans_rollback();
				}
		}else{			
			$respon->status = 'coa_exist';
		}
		$respon->str=$str;
		echo json_encode($respon);
	}
				
	}
	public function json_data_view(){
			$cabang = $this->input->get('cabang');
			$start = $this->input->get('iDisplayStart');
			$limit = $this->input->get('iDisplayLength');
			$sortby = $this->input->get('iSortCol_0');
			$srotdir = $this->input->get('sSortDir_0');
			
			
			

			$str = "select * from ak_rekeningperkiraan where level=1 and status=1 ";					
			
			
			if ( isset( $_GET['iSortCol_0'] ) )
			{
				$str .= " ORDER BY idacc, ".$_GET['mDataProp_'.$_GET['iSortCol_0']]." ".$_GET['sSortDir_0'];
			}else{
				$str .=" ORDER BY  idacc";
			}		
			
			$strfilter = $str;
			
			
			$iFilteredTotal = $this->db->query($str)->num_rows();
			if ($iFilteredTotal>1){	
				if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
				{
					$strfilter .= " LIMIT ". mysql_real_escape_string( $_GET['iDisplayStart'] ) .", ". mysql_real_escape_string( $_GET['iDisplayLength'] );
				}
			}
			$iTotal = $iFilteredTotal;
			$query = $this->db->query($strfilter)->result();
			
			$aaData = array();
			foreach($query as $row){				
				$aaData[] = array(
						'IDACC'=>$row->idacc,
						'NAMA'=>$row->nama,
						'KELOMPOK'=>$row->kelompok,
						'LEVEL'=>$row->level,
						'IDPARENT'=>$row->idparent,
						'SALDO_AWAL'=>$row->initval
					);
				$sLevel2="select * from ak_rekeningperkiraan where level=2 and idparent='".$row->idacc."' ";	
				$strL2=$sLevel2;
				if ($this->db->query($strL2)->num_rows() >0){				
					if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
					{
						$strL2 .= " LIMIT ". mysql_real_escape_string( $_GET['iDisplayStart'] ) .", ". mysql_real_escape_string( $_GET['iDisplayLength'] );
					}
					//$queryL5 = $this->db->query($sLevel5Limit)->result();
					$rsL2=$this->db->query($strL2)->result();
					$iFilteredTotal = ($this->db->query($sLevel2)->num_rows())+1;
					$iTotal = $iFilteredTotal;
					foreach($rsL2 as $row2){
						$aaData[] = array(
						'IDACC'=>$row2->idacc,
						'NAMA'=>"&nbsp;&nbsp;&nbsp;&nbsp;".$row2->nama,
						'KELOMPOK'=>$row2->kelompok,
						'LEVEL'=>$row2->level,
						'IDPARENT'=>$row2->idparent,
						'SALDO_AWAL'=>$row2->initval
						);


						$sLevel3="select * from ak_rekeningperkiraan where level=3 and idparent='".$row2->idacc."' ";		
						$strL3=$sLevel3;
						if ($this->db->query($strL3)->num_rows() >0){				
							if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
							{
								$strL3 .= " LIMIT ". mysql_real_escape_string( $_GET['iDisplayStart'] ) .", ". mysql_real_escape_string( $_GET['iDisplayLength'] );
							}
							$rsL3=$this->db->query($strL3)->result();
							$iFilteredTotal = ($this->db->query($sLevel3)->num_rows())+1;
							$iTotal = $iFilteredTotal;
							foreach($rsL3 as $row3){
								$aaData[] = array(
								'IDACC'=>$row3->idacc,
								'NAMA'=>"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$row3->nama,
								'KELOMPOK'=>$row3->kelompok,
								'LEVEL'=>$row3->level,
								'IDPARENT'=>$row3->idparent,
								'SALDO_AWAL'=>$row3->initval
								);

								$sLevel4="select * from ak_rekeningperkiraan where level=4 and idparent='".$row3->idacc."' and id_cab=".$cabang;		
								$strL4=$sLevel4;
								if ($this->db->query($strL4)->num_rows() >0){				
									if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
									{
										$strL4 .= " LIMIT ". mysql_real_escape_string( $_GET['iDisplayStart'] ) .", ". mysql_real_escape_string( $_GET['iDisplayLength'] );
									}
									$rsL4=$this->db->query($strL4)->result();
									$iFilteredTotal = ($this->db->query($sLevel4)->num_rows())+1;
									$iTotal = $iFilteredTotal;
									foreach($rsL4 as $row4){
										$aaData[] = array(
										'IDACC'=>$row4->idacc,
										'NAMA'=>"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$row4->nama,
										'KELOMPOK'=>$row4->kelompok,
										'LEVEL'=>$row4->level,
										'IDPARENT'=>$row4->idparent,
										'SALDO_AWAL'=>$row4->initval
										);

										$sLevel5="select * from ak_rekeningperkiraan where level=5 and idparent='".$row4->idacc."' and id_cab=".$cabang;		
										$strL5=$sLevel5;
										if ($this->db->query($strL5)->num_rows() >0){				
											if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
											{
												$strL5 .= " LIMIT ". mysql_real_escape_string( $_GET['iDisplayStart'] ) .", ". mysql_real_escape_string( $_GET['iDisplayLength'] );
											}
											$rsL5=$this->db->query($strL5)->result();
											$iFilteredTotal = ($this->db->query($sLevel5)->num_rows())+1;
											$iTotal = $iFilteredTotal;
											foreach($rsL5 as $row5){
												$aaData[] = array(
												'IDACC'=>$row5->idacc,
												'NAMA'=>"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$row5->nama,
												'KELOMPOK'=>$row5->kelompok,
												'LEVEL'=>$row5->level,
												'IDPARENT'=>$row5->idparent,
												'SALDO_AWAL'=>$row5->initval
												);
											}
										}//level 5

									}
								}//level 4

							}
						}//level 3

					}
				}//level 2

			}//level 1
			
			$output = array(
				"squery"=>$strfilter,
				"sEcho" => intval($_GET['sEcho']),
				"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => $aaData
			);
			echo json_encode($output);
		//}

	}
	public function json_data_level2(){
		//if ($this->input->is_ajax_request()){
		
			$start = $this->input->get('iDisplayStart');
			$limit = $this->input->get('iDisplayLength');
			$sortby = $this->input->get('iSortCol_0');
			$srotdir = $this->input->get('sSortDir_0');
			
			$str = "select * from ak_rekeningperkiraan WHERE level=2 ";
			
						
			if ( $_GET['sSearch'] != "" )
			{
				
				$str .= " AND ( `idacc` like '%".mysql_real_escape_string( $_GET['sSearch'] )."%'  or `nama` like '%".mysql_real_escape_string( $_GET['sSearch'] )."%' or `kelompok` like '%".mysql_real_escape_string( $_GET['sSearch'] )."%' ) ";
				
			}
			
			
			if ( isset( $_GET['iSortCol_0'] ) )
			{
				$str .= " ORDER BY ".$_GET['mDataProp_'.$_GET['iSortCol_0']]." ".$_GET['sSortDir_0'];
			}else{
				$str .= " ORDER BY kelompok, idacc";
			}
			
			
			$strfilter = $str;
			if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
			{
				$strfilter .= " LIMIT ". mysql_real_escape_string( $_GET['iDisplayStart'] ) .", ". mysql_real_escape_string( $_GET['iDisplayLength'] );
			}
			
			$iFilteredTotal = $this->db->query($str)->num_rows();
			$iTotal = $iFilteredTotal;
			$query = $this->db->query($strfilter)->result();
			
			$aaData = array();
			foreach($query as $row){
				$cekChild="select count(*) JML from ak_rekeningperkiraan where idparent= '".$row->idacc."'";
				$resCek = $this->db->query($cekChild)->row();
				$rsname=$this->gate_db->query("SELECT ifnull(kota, 'Pusat') as nmcabang FROM mst_cabang WHERE id_cabang=".($row->id_cab==0?1:$row->id_cab))->row();

				$aaData[] = array(
					'IDACC'=>$row->idacc,
					'NAMA'=>$row->nama,
					'KELOMPOK'=>$row->kelompok,
					'LEVEL'=>$row->level,
					'IDPARENT'=>$row->idparent,
					'CABANG'=>$rsname->nmcabang,
					'SALDO'=>$row->initval,
					'STATUS'=>($row->status==1?"Aktif":"Tidak Aktif"),
					'ACTION'=>'<a href="javascript:void(0)" onclick="editAkunLevel2(this)" data-id="'.$row->idacc.'"><i class="fa fa-edit" title="Edit"></i></a> '.($resCek->JML>0?'':'| <a href="javascript:void(0)" onclick="delLevel2(\''.$row->idacc.'\',\''.$row->nama.'\')"><i class="fa fa-trash-o" title="Delete"></i></a>')
					
				);
			}
			
			$output = array(
				"sEcho" => intval($_GET['sEcho']),
				"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => $aaData
			);
			echo json_encode($output);
		//}
	}	
	
	public function delLevel2(){
		$id=$this->input->post('idx');
		$res = $this->rekening_model->delAkunById($id);
		return $res;
	}
	public function saveLevel2(){
		$isi="awal<br>";
		if ($this->input->is_ajax_request()){	
			$this->load->library('form_validation');
			$state=$this->input->post('state');
			if ($state=='add'){
				$rules = array(				
					array(
						'field' => 'idacc',
						'label' => 'ID_AKUN',
						'rules' => 'trim|xss_clean|required'
					),					
					
					array(
						'field' => 'nama',
						'label' => 'NAMA',
						'rules' => 'trim|xss_clean|required'
					),
					array(
						'field' => 'saldoawal',
						'label' => 'SALDOAWAL',
						'rules' => 'trim|xss_clean|required|numeric'
					)
				);
			}else{
				$rules = array(				
					array(
						'field' => 'nama',
						'label' => 'NAMA',
						'rules' => 'trim|xss_clean|required'
					),
					
					array(
						'field' => 'saldoawal',
						'label' => 'SALDOAWAL',
						'rules' => 'trim|xss_clean|required|numeric'
					)
				);
			}
			$this->form_validation->set_rules($rules);
			$this->form_validation->set_message('required', 'Field %s harus diisi.');
			$this->form_validation->set_message('numeric', 'Field %s harus diisi angka.');
			$respon = new StdClass();
			if ($this->form_validation->run() == TRUE){
				$isi.="masuk form valid<br>";
				$state=$this->input->post('state');
				
				$this->db->trans_begin();
				try {	
					if($state=="add"){ 
						$data = array(
							'idacc' => $this->input->post('idacc'),
							'nama' => $this->input->post('nama'),
							'kelompok' => $this->input->post('kelompok'),							
							'idparent' => $this->input->post('level1'),
							'level' => 2,							
							'initval' => $this->input->post('saldoawal'),						
							'id_cab' => 0,						
							'status' => $this->input->post('status'),						
							'keterangan' => $this->input->post('ket')
						);	
						if ($this->db->insert('ak_rekeningperkiraan',$data)){
							$this->db->trans_commit();
							$respon->status = 'success';
						} else {
							throw new Exception("gagal simpan");
						}
					}else{	
						$data = array(							
							'nama' => $this->input->post('nama'),
							'kelompok' => $this->input->post('kelompok'),							
							'idparent' => $this->input->post('level1'),
							'level' => 2,							
							'initval' => $this->input->post('saldoawal'),	
							'id_cab' => $this->input->post('cabang_2'),	
							'status' => $this->input->post('status'),						
							'keterangan' => $this->input->post('status')
						);	
						
						if ($this->db->where('idacc',$state)->update('ak_rekeningperkiraan',$data)){
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
	
	public function getAccDetail(){
		$id = $this->input->post('id');
		$level = $this->input->post('level');
		switch($level){
			case '3':
				$str="SELECT det.* , (SELECT idparent FROM ak_rekeningperkiraan WHERE idacc=det.`idparent`) AS level1
					FROM ak_rekeningperkiraan det
					WHERE det.idacc='$id'";
				break;
			case '4':
				$str="SELECT det.* , (SELECT idparent FROM ak_rekeningperkiraan WHERE idacc=det.`idparent`) AS level2, 
						(SELECT idparent FROM ak_rekeningperkiraan WHERE idacc=detl1.`idparent`) AS level1 
						FROM ak_rekeningperkiraan det, ak_rekeningperkiraan detl1
						WHERE det.idacc='$id' AND det.idparent=detl1.`idacc`";
				break;
			case '5':
				$str="SELECT det.* , (SELECT idparent FROM ak_rekeningperkiraan WHERE idacc=det.`idparent`) AS level3, 
					(SELECT idparent FROM ak_rekeningperkiraan WHERE idacc=detl2.`idparent`) AS level2 ,
					(SELECT idparent FROM ak_rekeningperkiraan WHERE idacc=detl1.`idparent`) AS level1 
					FROM ak_rekeningperkiraan det, ak_rekeningperkiraan detl2, ak_rekeningperkiraan detl1
					WHERE det.idacc='$id' 
					AND det.idparent=detl2.`idacc`
					AND detl2.idparent=detl1.`idacc`";
				break;
			default:
				$str="select * from ak_rekeningperkiraan where idacc='$id'";
				break;
		}
		
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
	public function comboLevel2_nonkas(){
		$level1 = $this->input->post('level1');
		//$id_cabang = 1;
		/*$query = $this->db->select('idacc,nama')
			->where(array('idparent'=>$level1))
			->get('ak_rekeningperkiraan')->result();*/
		$str="select * from ak_rekeningperkiraan where idparent='$level1' and  (idacc not like  '4-%')";
		$query =$this->db->query($str)->result();
		$respon = new StdClass();
		$respon->status = 0;
		$respon->str = $str;
		if (!empty($query)){
			$respon->status = 1;
			$respon->data = $query;
		}
		echo json_encode($respon);
	}
	public function comboLevel2(){
		$level1 = $this->input->post('level1');
		//$id_cabang = 1;
		$query = $this->db->select('idacc,nama')
			->where(array('idparent'=>$level1))
			->get('ak_rekeningperkiraan')->result();
		$respon = new StdClass();
		$respon->status = 0;
		if (!empty($query)){
			$respon->status = 1;
			$respon->data = $query;
		}
		echo json_encode($respon);
	}
		
	//================== level 3 ================================
	public function json_data_level3(){
		//if ($this->input->is_ajax_request()){
		
			$start = $this->input->get('iDisplayStart');
			$limit = $this->input->get('iDisplayLength');
			$sortby = $this->input->get('iSortCol_0');
			$srotdir = $this->input->get('sSortDir_0');
			
			$str = "select * from ak_rekeningperkiraan WHERE level=3 ";
			
						
			if ( $_GET['sSearch'] != "" )
			{
				
				$str .= " AND ( `idacc` like '%".mysql_real_escape_string( $_GET['sSearch'] )."%'  or `nama` like '%".mysql_real_escape_string( $_GET['sSearch'] )."%' or `kelompok` like '%".mysql_real_escape_string( $_GET['sSearch'] )."%' ) ";
				
			}
			
			
			if ( isset( $_GET['iSortCol_0'] ) )
			{
				$str .= " ORDER BY ".$_GET['mDataProp_'.$_GET['iSortCol_0']]." ".$_GET['sSortDir_0'];
			}else{
				$str .= " ORDER BY kelompok, idacc";
			}
			
			
			$strfilter = $str;
			if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
			{
				$strfilter .= " LIMIT ". mysql_real_escape_string( $_GET['iDisplayStart'] ) .", ". mysql_real_escape_string( $_GET['iDisplayLength'] );
			}
			
			$iFilteredTotal = $this->db->query($str)->num_rows();
			$iTotal = $iFilteredTotal;
			$query = $this->db->query($strfilter)->result();
			
			$aaData = array();
			foreach($query as $row){
				$cekChild="select count(*) JML from ak_rekeningperkiraan where idparent= '".$row->idacc."'";
				$resCek = $this->db->query($cekChild)->row();
				$rsname=$this->gate_db->query("SELECT ifnull(kota, 'Pusat') as nmcabang FROM mst_cabang WHERE id_cabang=".($row->id_cab==0?1:$row->id_cab))->row();
				$aaData[] = array(
					'IDACC'=>$row->idacc,
					'NAMA'=>$row->nama,
					'KELOMPOK'=>$row->kelompok,
					'LEVEL'=>$row->level,
					'IDPARENT'=>$row->idparent,
					'CABANG'=>$rsname->nmcabang,
					'SALDO'=>$row->initval,
					'STATUS'=>($row->status==1?"Aktif":"Tidak Aktif"),
					'ACTION'=>'<a href="javascript:void(0)" onclick="editAkunLevel3(this)" data-id="'.$row->idacc.'"><i class="fa fa-edit" title="Edit"></i></a> '.($resCek->JML>0?'':'| <a href="javascript:void(0)" onclick="delLevel3(\''.$row->idacc.'\',\''.$row->nama.'\')"><i class="fa fa-trash-o" title="Delete"></i></a>')
					
				);
			}
			
			$output = array(
				"sEcho" => intval($_GET['sEcho']),
				"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => $aaData
			);
			echo json_encode($output);
		//}
	}
	
	public function comboLevel3_nonkas(){
		$level2 = $this->input->post('level2');
		//$id_cabang = 1;
		$str="select * from ak_rekeningperkiraan where idparent='$level2' and (idacc not like '1.1.1%') and  (idacc not like  '1.1.2%'') and  (idacc not like  '4.%')";
		$query =$this->db->query($str)->result();
		/*$query = $this->db->select('idacc,nama')
			->where(array('idparent'=>$level2, (idacc not like '1.1.1%') and  (idacc not like  '1.1.02%'') and  (idacc not like  '4-%')))
			->get('ak_rekeningperkiraan')->result();*/
		
		$respon = new StdClass();
		$respon->status = 0;
		if (!empty($query)){
			$respon->status = 1;
			$respon->data = $query;
		}
		echo json_encode($respon);
	}
	public function comboLevel3(){
		$level2 = $this->input->post('level2');
		//$id_cabang = 1;
		$query = $this->db->select('idacc,nama')
			->where(array('idparent'=>$level2))
			->get('ak_rekeningperkiraan')->result();
		$respon = new StdClass();
		$respon->status = 0;
		if (!empty($query)){
			$respon->status = 1;
			$respon->data = $query;
		}
		echo json_encode($respon);
	}
	public function comboLevel3_saldo(){
		$level2 = $this->input->post('level2');
		$cab = $this->input->post('cab');
		//$id_cabang = 1;
		$query = $this->db->select('idacc,nama')
			->where(array('idparent'=>$level2, 'id_cab'=>$cab))
			->get('ak_rekeningperkiraan')->result();
		$respon = new StdClass();
		$respon->status = 0;
		if (!empty($query)){
			$respon->status = 1;
			$respon->data = $query;
		}
		echo json_encode($respon);
	}
	public function delLevel3(){
		$id=$this->input->post('idx');
		$res = $this->rekening_model->delAkunById($id);
		return $res;
	}
	public function saveLevel3(){
		$isi="awal<br>";
		if ($this->input->is_ajax_request()){	
			$this->load->library('form_validation');
			$state=$this->input->post('state');
			if ($state=='add'){
				$rules = array(				
					array(
						'field' => 'idacc_3',
						'label' => 'ID_AKUN',
						'rules' => 'trim|xss_clean|required'
					),					
					array(
						'field' => 'nama_3',
						'label' => 'NAMA',
						'rules' => 'trim|xss_clean|required'
					),
					
					array(
						'field' => 'saldoawal_3',
						'label' => 'SALDOAWAL',
						'rules' => 'trim|xss_clean|required|numeric'
					)
				);
			}else{
				$rules = array(
					array(
						'field' => 'nama_3',
						'label' => 'ID_AKUN',
						'rules' => 'trim|xss_clean|required'
					),
					array(
						'field' => 'saldoawal_3',
						'label' => 'SALDOAWAL',
						'rules' => 'trim|xss_clean|required|numeric'
					)
				);
			}
			$this->form_validation->set_rules($rules);
			$this->form_validation->set_message('required', 'Field %s harus diisi.');
			$this->form_validation->set_message('numeric', 'Field %s harus diisi angka.');
			$respon = new StdClass();
			if ($this->form_validation->run() == TRUE){
				$isi.="masuk form valid<br>";
				$state=$this->input->post('state_3');
				
				$this->db->trans_begin();
				try {	
					if($state=="add"){ 
						$data = array(
							'idacc' => $this->input->post('idacc_3'),
							'nama' => $this->input->post('nama_3'),
							'kelompok' => $this->input->post('kelompok_3'),							
							'idparent' => $this->input->post('level2_3'),
							'level' => 3,							
							'initval' => $this->input->post('saldoawal_3'),		
							'id_cab' => $this->input->post('cabang_3'),	
							'status' => $this->input->post('status_3'),						
							'keterangan' => $this->input->post('ket_3')
						);	
						if ($this->db->insert('ak_rekeningperkiraan',$data)){
							$this->db->trans_commit();
							$respon->status = 'success';
						} else {
							throw new Exception("gagal simpan");
						}
					}else{	
						$data = array(							
							'nama' => $this->input->post('nama_3'),
							'kelompok' => $this->input->post('kelompok_3'),							
							'idparent' => $this->input->post('level2_3'),
							'level' => 3,							
							'initval' => $this->input->post('saldoawal_3'),	
							'id_cab' => $this->input->post('cabang_3'),	
							'status' => $this->input->post('status_3'),						
							'keterangan' => $this->input->post('ket_3')
						);	
						
						if ($this->db->where('idacc',$state)->update('ak_rekeningperkiraan',$data)){
									$this->db->trans_commit();
									$respon->status = 'success';
						} else {
							throw new Exception("gagal simpan");
						}
					}
					$isi.="Proses query<br>".$this->db->last_query();
					
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
	//================== level 3 ================================

	//================== level 4 ================================
	public function json_data_level4(){
		//if ($this->input->is_ajax_request()){
		
			$start = $this->input->get('iDisplayStart');
			$limit = $this->input->get('iDisplayLength');
			$sortby = $this->input->get('iSortCol_0');
			$srotdir = $this->input->get('sSortDir_0');
			
			$str = "select  * from ak_rekeningperkiraan WHERE level=4 ";
			
						
			if ( $_GET['sSearch'] != "" )
			{
				
				$str .= " AND ( `idacc` like '%".mysql_real_escape_string( $_GET['sSearch'] )."%'  or `nama` like '%".mysql_real_escape_string( $_GET['sSearch'] )."%' or `kelompok` like '%".mysql_real_escape_string( $_GET['sSearch'] )."%' ) ";
				
			}
			
			
			if ( isset( $_GET['iSortCol_0'] ) )
			{
				$str .= " ORDER BY ".$_GET['mDataProp_'.$_GET['iSortCol_0']]." ".$_GET['sSortDir_0'];
			}else{
				$str .= " ORDER BY kelompok, idacc";
			}
			
			
			$strfilter = $str;
			if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
			{
				$strfilter .= " LIMIT ". mysql_real_escape_string( $_GET['iDisplayStart'] ) .", ". mysql_real_escape_string( $_GET['iDisplayLength'] );
			}
			
			$iFilteredTotal = $this->db->query($str)->num_rows();
			$iTotal = $iFilteredTotal;
			$query = $this->db->query($strfilter)->result();
			
			$aaData = array();
			foreach($query as $row){
				$cekChild="select count(*) JML from ak_rekeningperkiraan where idparent= '".$row->idacc."'";
				$resCek = $this->db->query($cekChild)->row();
				$rsname=$this->gate_db->query("SELECT ifnull(kota, 'Pusat') as nmcabang FROM mst_cabang WHERE id_cabang=".$row->id_cab)->row();
				$aaData[] = array(
					'IDACC'=>$row->idacc,
					'NAMA'=>$row->nama,
					'KELOMPOK'=>$row->kelompok,
					'LEVEL'=>$row->level,
					'IDPARENT'=>$row->idparent,
					'CABANG'=>$rsname->nmcabang,
					'SALDO'=>$row->initval,
					'STATUS'=>($row->status==1?"Aktif":"Tidak Aktif"),
					'ACTION'=>'<a href="javascript:void(0)" onclick="editAkunlevel4(this)" data-id="'.$row->idacc.'"><i class="fa fa-edit" title="Edit"></i></a> '.($resCek->JML>0?'':'| <a href="javascript:void(0)" onclick="dellevel4(\''.$row->idacc.'\',\''.$row->nama.'\')"><i class="fa fa-trash-o" title="Delete"></i></a>')
					
				);
			}
			
			$output = array(
				"sEcho" => intval($_GET['sEcho']),
				"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => $aaData
			);
			echo json_encode($output);
		//}
	}

	public function dellevel4(){
		$id=$this->input->post('idx');
		$res = $this->rekening_model->delAkunById($id);
		return $res;
	}
	public function savelevel4(){
		$isi="awal<br>";
		if ($this->input->is_ajax_request()) {	
			$this->load->library('form_validation');
			$state=$this->input->post('state_4');
			if ($state=='add'){
				$rules = array(				
					array(
						'field' => 'idacc_4',
						'label' => 'ID_AKUN',
						'rules' => 'trim|xss_clean|required'
					),					
					array(
						'field' => 'nama_4',
						'label' => 'NAMA',
						'rules' => 'trim|xss_clean|required'
					),
					
					array(
						'field' => 'saldoawal_4',
						'label' => 'SALDOAWAL',
						'rules' => 'trim|xss_clean|required|numeric'
					)
				);
			}else{
				$rules = array(
					array(
						'field' => 'nama_4',
						'label' => 'ID_AKUN',
						'rules' => 'trim|xss_clean|required'
					),
					array(
						'field' => 'saldoawal_4',
						'label' => 'SALDOAWAL',
						'rules' => 'trim|xss_clean|required|numeric'
					)
				);
			}
			$this->form_validation->set_rules($rules);
			$this->form_validation->set_message('required', 'Field %s harus diisi.');
			$this->form_validation->set_message('numeric', 'Field %s harus diisi angka.');
			$respon = new StdClass();
			if ($this->form_validation->run() == TRUE){
				$isi.="masuk form valid<br>";
				$state=$this->input->post('state_4');
				
				$this->db->trans_begin();
				try {	
					if($state=="add"){ 
						$data = array(
							'idacc' => $this->input->post('idacc_4'),
							'nama' => $this->input->post('nama_4'),
							'kelompok' => $this->input->post('kelompok_4'),							
							'idparent' => $this->input->post('level3_4'),
							'level' => 4,							
							'initval' => $this->input->post('saldoawal_4'),	
							'id_cab' => $this->input->post('cabang_4'),	
							'status' => $this->input->post('status_4'),						
							'keterangan' => $this->input->post('ket_4')
						);	
						if ($this->db->insert('ak_rekeningperkiraan',$data)){
							$this->db->trans_commit();
							$respon->status = 'success';
						} else {
							throw new Exception("gagal simpan");
						}
					}else{	
						$data = array(							
							'nama' => $this->input->post('nama_4'),
							'kelompok' => $this->input->post('kelompok_4'),							
							'idparent' => $this->input->post('level3_4'),
							'level' => 4,							
							'initval' => $this->input->post('saldoawal_4'),	
							'id_cab' => $this->input->post('cabang_4'),	
							'status' => $this->input->post('status_4'),						
							'keterangan' => $this->input->post('ket_4')
						);	
						
						if ($this->db->where('idacc',$state)->update('ak_rekeningperkiraan',$data)){
									$this->db->trans_commit();
									$respon->status = 'success';
						} else {
							throw new Exception("gagal simpan");
						}
					}
					$isi.="Proses query<br>".$this->db->last_query();
					
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
	//================== level 4 ================================

	//================== level 5 ================================
	public function json_data_level5(){
		//if ($this->input->is_ajax_request()){
		
			$start = $this->input->get('iDisplayStart');
			$limit = $this->input->get('iDisplayLength');
			$sortby = $this->input->get('iSortCol_0');
			$srotdir = $this->input->get('sSortDir_0');
			
			$str = "select * from ak_rekeningperkiraan WHERE level=5 ";
			
						
			if ( $_GET['sSearch'] != "" )
			{
				
				$str .= " AND ( `idacc` like '%".mysql_real_escape_string( $_GET['sSearch'] )."%'  or `nama` like '%".mysql_real_escape_string( $_GET['sSearch'] )."%' or `kelompok` like '%".mysql_real_escape_string( $_GET['sSearch'] )."%' ) ";
				
			}
			
			
			if ( isset( $_GET['iSortCol_0'] ) )
			{
				$str .= " ORDER BY ".$_GET['mDataProp_'.$_GET['iSortCol_0']]." ".$_GET['sSortDir_0'];
			}else{
				$str .= " ORDER BY kelompok, idacc";
			}
			
			
			$strfilter = $str;
			if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
			{
				$strfilter .= " LIMIT ". mysql_real_escape_string( $_GET['iDisplayStart'] ) .", ". mysql_real_escape_string( $_GET['iDisplayLength'] );
			}
			
			$iFilteredTotal = $this->db->query($str)->num_rows();
			$iTotal = $iFilteredTotal;
			$query = $this->db->query($strfilter)->result();
			
			$aaData = array();
			foreach($query as $row){
				$cekChild="select count(*) JML from ak_rekeningperkiraan where idparent= '".$row->idacc."'";
				$resCek = $this->db->query($cekChild)->row();
				$rsname=$this->gate_db->query("SELECT ifnull(kota, 'Pusat') as nmcabang FROM mst_cabang WHERE id_cabang=".$row->id_cab)->row();
				$aaData[] = array(
					'IDACC'=>$row->idacc,
					'NAMA'=>$row->nama,
					'KELOMPOK'=>$row->kelompok,
					'LEVEL'=>$row->level,
					'IDPARENT'=>$row->idparent,
					'CABANG'=>$rsname->nmcabang,
					'SALDO'=>$row->initval,
					'STATUS'=>($row->status==1?"Aktif":"Tidak Aktif"),
					'ACTION'=>'<a href="javascript:void(0)" onclick="editAkunlevel5(this)" data-id="'.$row->idacc.'"><i class="fa fa-edit" title="Edit"></i></a> '.($resCek->JML>0?'':'| <a href="javascript:void(0)" onclick="delLevel5(\''.$row->idacc.'\',\''.$row->nama.'\')"><i class="fa fa-trash-o" title="Delete"></i></a>')
					
				);
			}
			
			$output = array(
				"sEcho" => intval($_GET['sEcho']),
				"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => $aaData
			);
			echo json_encode($output);
		//}
	}

	public function delLevel5(){
		$id=$this->input->post('idx');
		$res = $this->rekening_model->delAkunById($id);
		return $res;
	}
	public function savelevel5(){
		$isi="awal<br>";
		if ($this->input->is_ajax_request()){	
			$this->load->library('form_validation');
			$state=$this->input->post('state_5');
			if ($state=='add'){
				$rules = array(				
					array(
						'field' => 'idacc_5',
						'label' => 'ID_AKUN',
						'rules' => 'trim|xss_clean|required'
					),					
					array(
						'field' => 'nama_5',
						'label' => 'NAMA',
						'rules' => 'trim|xss_clean|required'
					),
					
					array(
						'field' => 'saldoawal_5',
						'label' => 'SALDOAWAL',
						'rules' => 'trim|xss_clean|required|numeric'
					)
				);
			}else{
				$rules = array(
					array(
						'field' => 'nama_5',
						'label' => 'ID_AKUN',
						'rules' => 'trim|xss_clean|required'
					),
					array(
						'field' => 'saldoawal_5',
						'label' => 'SALDOAWAL',
						'rules' => 'trim|xss_clean|required|numeric'
					)
				);
			}
			$this->form_validation->set_rules($rules);
			$this->form_validation->set_message('required', 'Field %s harus diisi.');
			$this->form_validation->set_message('numeric', 'Field %s harus diisi angka.');
			$respon = new StdClass();
			if ($this->form_validation->run() == TRUE){
				$isi.="masuk form valid<br>";
				$state=$this->input->post('state_5');
				
				$this->db->trans_begin();
				try {	
					if($state=="add"){ 
						$data = array(
							'idacc' => $this->input->post('idacc_5'),
							'nama' => $this->input->post('nama_5'),
							'kelompok' => $this->input->post('kelompok_5'),							
							'idparent' => $this->input->post('level4_5'),
							'level' => 5,							
							'initval' => $this->input->post('saldoawal_5'),		
							'id_cab' => $this->input->post('cabang_5'),	
							'status' => $this->input->post('status_5'),						
							'keterangan' => $this->input->post('ket_5')
						);	
						if ($this->db->insert('ak_rekeningperkiraan',$data)){
							$this->db->trans_commit();
							$respon->status = 'success';
						} else {
							throw new Exception("gagal simpan");
						}
					}else{	
						$data = array(							
							'nama' => $this->input->post('nama_5'),
							'kelompok' => $this->input->post('kelompok_5'),							
							'idparent' => $this->input->post('level4_5'),
							'level' => 5,							
							'initval' => $this->input->post('saldoawal_5'),		
							'id_cab' => $this->input->post('cabang_5'),	
							'status' => $this->input->post('status_5'),						
							'keterangan' => $this->input->post('ket_5')
						);	
						
						if ($this->db->where('idacc',$state)->update('ak_rekeningperkiraan',$data)){
									$this->db->trans_commit();
									$respon->status = 'success';
						} else {
							throw new Exception("gagal simpan");
						}
					}
					$isi.="Proses query<br>".$this->db->last_query();
					
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

	public function comboLevel4(){
		$level3 = $this->input->post('level3');
		//$id_cabang = 1;
		$query = $this->db->select('idacc,nama')
			->where(array('idparent'=>$level3))
			->get('ak_rekeningperkiraan')->result();
		$respon = new StdClass();
		$respon->status = 0;
		if (!empty($query)){
			$respon->status = 1;
			$respon->data = $query;
		}
		echo json_encode($respon);
	}
	public function comboLevel4_susut(){
		$level3 = $this->input->post('level3');
		//$id_cabang = 1;
		/*$query = $this->db->select('idacc,nama')
			->where(array('idparent'=>$level3, 'd_c'=>'C'))
			->get('ak_rekeningperkiraan')->result();*/
		$str="select * from ak_rekeningperkiraan where idparent='$level3' and   upper(nama) not like '%AKUM%'";
		$query =$this->db->query($str)->result();
		$respon = new StdClass();
		$respon->status = 0;
		$respon->str = $str;
		if (!empty($query)){
			$respon->status = 1;
			$respon->data = $query;
		}
		echo json_encode($respon);
	}
	public function comboLevel4_akum(){
		$level3 = $this->input->post('level3');
		//$id_cabang = 1;
		/*$query = $this->db->select('idacc,nama')
			->where(array('idparent'=>$level3, 'd_c'=>'C'))
			->get('ak_rekeningperkiraan')->result();*/
		$str="select * from ak_rekeningperkiraan where idparent='$level3' and   upper(nama) like '%AKUM%'";
		$query =$this->db->query($str)->result();
		$respon = new StdClass();
		$respon->status = 0;
		$respon->str = $str;
		if (!empty($query)){
			$respon->status = 1;
			$respon->data = $query;
		}
		echo json_encode($respon);
	}
	public function comboLevel5(){
		$level4 = $this->input->post('level4');
		//$id_cabang = 1;
		$query = $this->db->select('idacc,nama')
			->where(array('idparent'=>$level4))
			->get('ak_rekeningperkiraan')->result();
		$respon = new StdClass();
		$respon->status = 0;
		if (!empty($query)){
			$respon->status = 1;
			$respon->data = $query;
		}
		echo json_encode($respon);
	}
	public function comboLevel5_susut(){
		$level4 = $this->input->post('level4');
		//$id_cabang = 1;
		/*$query = $this->db->select('idacc,nama')
			->where(array('idparent'=>$level4, 'd_c'=>'C'))
			->get('ak_rekeningperkiraan')->result();*/
		$str="select * from ak_rekeningperkiraan where idparent='$level4' and  upper(nama) not like '%AKUM%'";
		$query =$this->db->query($str)->result();
		$respon = new StdClass();
		$respon->status = 0;
		if (!empty($query)){
			$respon->status = 1;
			$respon->data = $query;
		}
		echo json_encode($respon);
	}
	public function comboLevel5_akum(){
		$level4 = $this->input->post('level4');
		//$id_cabang = 1;
		/*$query = $this->db->select('idacc,nama')
			->where(array('idparent'=>$level4, 'd_c'=>'C'))
			->get('ak_rekeningperkiraan')->result();*/
		$str="select * from ak_rekeningperkiraan where idparent='$level4' and  upper(nama) like '%AKUM%'";
		$query =$this->db->query($str)->result();
		$respon = new StdClass();
		$respon->status = 0;
		$respon->str = $str;
		if (!empty($query)){
			$respon->status = 1;
			$respon->data = $query;
		}
		echo json_encode($respon);
	}
	//================== level 5 ================================


	public function fillCashReg(){
		$wilayah = $this->input->post('wilayah');
		//$id_cabang = 1;
		/*$query = $this->db->select('idacc,nama')
			->where(array('id_cab'=>$wilayah, ''))
			->get('ak_rekeningperkiraan')->result();*/
		$str="select * from ak_rekeningperkiraan where id_cab=$wilayah and  idacc like '1.1.1%'";
		$query =$this->db->query($str)->result();
		
		$respon = new StdClass();
		$respon->status = 0;
		if (!empty($query)){
			$respon->status = 1;
			$respon->data = $query;
		}
		echo json_encode($respon);
	}

	public function fillBankReg(){
		$wilayah = $this->input->post('wilayah');
		//$id_cabang = 1;
		/*$query = $this->db->select('idacc,nama')
			->where(array('id_cab'=>$wilayah, ''))
			->get('ak_rekeningperkiraan')->result();*/
		$str="select * from ak_rekeningperkiraan where id_cab=$wilayah and  idacc like '1.1.02%'";
		$query =$this->db->query($str)->result();
		
		$respon = new StdClass();
		$respon->status = 0;
		if (!empty($query)){
			$respon->status = 1;
			$respon->data = $query;
		}
		echo json_encode($respon);
	}

	public function getAkunBB(){
		$keyword = $this->input->post('keyword');
		$wilayah = $this->input->post('wilayah');
		$data['response'] = 'false';
		
		//if (substr($this->session->userdata('auth')->ROLE, 0, 5)=="Kasir"){
			/*switch ($this->session->userdata('auth')->ROLE){
				case "Kasir Tunai":
					$str="select * from ak_rekeningperkiraan where level>3 and (idacc not like  '4-%')  and status=1  and id_cab=".$this->session->userdata('auth')->ID_CABANG." and (nama LIKE '%{$keyword}%' or idacc LIKE '%{$keyword}%') order by idacc";
					break;
				case "Kasir Laysos":
					$str="SELECT * FROM ak_rekeningperkiraan WHERE LEVEL>3 AND (idacc NOT LIKE  '4-%' AND idacc NOT LIKE '6-%' and idacc NOT LIKE  '1.1.02%'' and idacc NOT LIKE  '1.1.01%')  AND STATUS=1  AND id_cab=".$this->session->userdata('auth')->ID_CABANG." and (nama LIKE '%{$keyword}%' or idacc LIKE '%{$keyword}%') UNION SELECT * FROM ak_rekeningperkiraan  WHERE LEVEL>3 AND (idacc LIKE '6-12%'  OR idacc IN (SELECT idacc FROM ak_rekeningperkiraan WHERE UPPER(nama) LIKE '%LAYSOS%' AND idacc LIKE '1-1%'))   AND STATUS=1  AND  id_cab=".$this->session->userdata('auth')->ID_CABANG." and (nama LIKE '%{$keyword}%' or idacc LIKE '%{$keyword}%') ORDER BY idacc";					
					break;
				case "Kasir ASAH":
					$str="SELECT * FROM ak_rekeningperkiraan WHERE LEVEL>3 AND (idacc NOT LIKE  '4-%' AND idacc NOT LIKE '6-%' and idacc NOT LIKE  '1.1.01%' and idacc NOT LIKE  '1.1.02%'')  AND STATUS=1  AND id_cab=".$this->session->userdata('auth')->ID_CABANG." and (nama LIKE '%{$keyword}%' or idacc LIKE '%{$keyword}%') UNION SELECT * FROM ak_rekeningperkiraan  WHERE LEVEL>3 AND (idacc LIKE '6-11%' OR idacc LIKE '6-15%' OR idacc IN (SELECT idacc FROM ak_rekeningperkiraan WHERE UPPER(nama) LIKE '%ASAH%' AND idacc LIKE '1-1%'))  AND STATUS=1  AND id_cab=".$this->session->userdata('auth')->ID_CABANG." and (nama LIKE '%{$keyword}%' or idacc LIKE '%{$keyword}%') ORDER BY idacc";
				break;
			}*/
		//	$str="select * from ak_rekeningperkiraan where level>3 and (idacc not like  '4.%')  and status=1  and id_cab=".$this->session->userdata('auth')->ID_CABANG." and (nama LIKE '%{$keyword}%' or idacc LIKE '%{$keyword}%') order by idacc";
		//}else{
			$str="select * from ak_rekeningperkiraan where  level>3 and status=1  and id_cab=".$wilayah." and (nama LIKE '%{$keyword}%' or idacc LIKE '%{$keyword}%') order by idacc";
		//}


		

		$query = $this->db->query($str)->result();
		//$data['str']=$str;
		
		if( ! empty($query) )
		{	$data['strq'] = $str; 
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

	public function getCashRegister(){
		$keyword = $this->input->post('keyword');
		$wilayah = $this->input->post('wilayah');
		$data['response'] = 'false';
		
		/*if (substr($this->session->userdata('auth')->ROLE, 0, 5)=="Kasir"){
			switch ($this->session->userdata('auth')->ROLE){
				case "Kasir Tunai":
					$str="select * from ak_rekeningperkiraan where (idacc  like '1.1.01%')  and id_cab=".$wilayah." and (nama LIKE '%{$keyword}%' or idacc LIKE '%{$keyword}%') order by idacc";
					break;
				case "Kasir Laysos":
					$str="select * from ak_rekeningperkiraan where (idacc  like '1.1.01%' and upper(nama) like '%LAYSOS%')  and id_cab=".$wilayah." and (nama LIKE '%{$keyword}%' or idacc LIKE '%{$keyword}%') order by idacc";
										
					break;
				case "Kasir ASAH":
					$str="select * from ak_rekeningperkiraan where (idacc  like '1.1.01%' and upper(nama) like '%ASAH%')  and id_cab=".$wilayah." and (nama LIKE '%{$keyword}%' or idacc LIKE '%{$keyword}%') order by idacc";
					break;
			}
		}else{*/
			$str="select * from ak_rekeningperkiraan where (idacc  like '1.1.1%') and id_cab= ".$wilayah." and (nama LIKE '%{$keyword}%' or idacc LIKE '%{$keyword}%') order by idacc";
		//}
		
		$query = $this->db->query($str)->result();
		//$data['str']=$str;
		
		if( ! empty($query) )
		{	$data['strq'] = $str; 
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

	public function getBankRegister(){
		$keyword = $this->input->post('keyword');
		$wilayah = $this->input->post('wilayah');
		$data['response'] = 'false';
		
		/*if (substr($this->session->userdata('auth')->ROLE, 0, 5)=="Kasir"){
			switch ($this->session->userdata('auth')->ROLE){
				case "Kasir Tunai":
					$str="select * from ak_rekeningperkiraan where (idacc  like '1.1.02%'')  and id_cab=".$wilayah." and (nama LIKE '%{$keyword}%' or idacc LIKE '%{$keyword}%') order by idacc";
					break;
				case "Kasir Laysos":
					$str="select * from ak_rekeningperkiraan where (idacc  like '1.1.02%'' and upper(nama) like '%LAYSOS%')  and id_cab=".$wilayah." and (nama LIKE '%{$keyword}%' or idacc LIKE '%{$keyword}%') order by idacc";
										
					break;
				case "Kasir ASAH":
					$str="select * from ak_rekeningperkiraan where (idacc  like '1.1.02%'' and upper(nama) like '%ASAH%')  and id_cab=".$wilayah." and (nama LIKE '%{$keyword}%' or idacc LIKE '%{$keyword}%') order by idacc";
					break;
			}
		}else{*/
			$str="select * from ak_rekeningperkiraan where (idacc  like '1.1.2%')  and id_cab=".$wilayah." and (nama LIKE '%{$keyword}%' or idacc LIKE '%{$keyword}%') order by idacc";
		//}
		
		$query = $this->db->query($str)->result();
		//$data['str']=$str;
		
		if( ! empty($query) )
		{	$data['strq'] = $str; 
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
}
