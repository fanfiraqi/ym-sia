<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class jurnalmanual extends MY_App {
	var $branch = array();
	

	function __construct()
	{
		parent::__construct();
		$this->load->model('report_model');
		$this->config->set_item('mymenu', 'menuTiga');
		$this->config->set_item('mysubmenu', 'menuTigaEmpat');
		$this->auth->authorize();


	}
	
	public function index()
	{	$periode=$this->common_model->comboGetPeriode();
		$arrJenis=array(			
			'kasmasuk'=>'Pemasukan Kas/Bank',			
			'kaskeluar'=>'Pengeluaran Kas/Bank',			
			'jurnalumum'=>'Jurnal Non Kas/Bank',			
			'susut'=>'Jurnal Penyusutan'
		);
		if ($this->session->userdata('auth')->id_cabang=="1"){
			$data['cabang'] =$this->common_model->comboCabang('--- Semua Cabang ---');
		}else{
			$data['cabang'] =$this->common_model->getCabang($this->session->userdata('auth')->id_cabang);
		}
		$arrJenisFilter=array(
			'penghimpunan'=>'Pendapatan ZIS',
			'penyaluran'=>'Penyaluran Program',			
			'penggajian'=>'Penggajian',			
			'kasmasuk'=>'Pemasukan Kas/Bank',			
			'kaskeluar'=>'Pengeluaran Kas/Bank',			
			'jurnalumum'=>'Jurnal Non Kas/Bank',			
			'susut'=>'Jurnal Penyusutan'
		);
		$data['arrJenis']=$arrJenis;
		$data['arrJenisFilter']=$arrJenisFilter;
		//$data['akunNonKas']=$akunNonKas;
		$data['periode']=$periode;		
		$this->template->set('breadcrumbs','<li><a href="#">Jurnal</a></li><li><a href="#">Jurnal Manual</a></li>');
		$this->template->set('pagetitle','Jurnal Umum Kas/Bank Masuk/Keluar dan Non Kas/Bank');		
		$this->template->load('default','fjurnal/vjurnalmanual',$data);
		
	}
	
	
	
	public function json_data(){
		//if ($this->input->is_ajax_request()){
			$role=$this->config->item('myrole');
			$tanggal1=$this->input->get('tanggal1');
			$tanggal2=$this->input->get('tanggal2');
			$cabang=$this->input->get('cabang');
			$jenis=$this->input->get('jenis');
			$pengunci=$this->db->query("select * from ak_pengunci where id=1")->row();
			$tanggalkunci='';
			$stsKunci=0;
				if (sizeof($pengunci)>0){
					$tanggalkunci=$pengunci->tanggal;
					if ($tanggalkunci>$tanggal1 || $tanggalkunci>date('Y-m-d')){
						$stsKunci=1;
					}
				}

			$start = $this->input->get('iDisplayStart');
			$limit = $this->input->get('iDisplayLength');
			$sortby = $this->input->get('iSortCol_0');
			$srotdir = $this->input->get('sSortDir_0');
			
			
			//$str = "select @nom:=@nom+1 as nomor, ak_jurnal.*, (select nama from ak_rekeningperkiraan where idacc=ak_jurnal.idperkdebet) akundebet, (select nama from ak_rekeningperkiraan where idacc=ak_jurnal.idperkkredit) akunkredit from ak_jurnal where (tanggal between '$tanggal1' and '$tanggal2')  ".($this->session->userdata('auth')->id_cabang<=1?'':" and id_cab=".$this->session->userdata('auth')->id_cabang);
			$str = "select @nom:=@nom+1 as nomor, ak_jurnal.*, (select nama from ak_rekeningperkiraan where idacc=ak_jurnal.idperkdebet) akundebet, (select nama from ak_rekeningperkiraan where idacc=ak_jurnal.idperkkredit) akunkredit from ak_jurnal where (tanggal between '$tanggal1' and '$tanggal2')   and id_cab=".$cabang."  and sumber_data='".$jenis."'";
			
						
			if ( $_GET['sSearch'] != "" )
			{
				
				$str .= " and ( `keterangan` like '%".mysql_real_escape_string( $_GET['sSearch'] )."' or `penanda` like '%".mysql_real_escape_string( $_GET['sSearch'] )."' or `nobuktiref` like '%".mysql_real_escape_string( $_GET['sSearch'] )."')";
				
			}
			
			
			if ( isset( $_GET['iSortCol_0'] ) )
			{
				//$str .= " ORDER BY ".$_GET['mDataProp_'.$_GET['iSortCol_0']]." ".$_GET['sSortDir_0'];
				$str .= " ORDER BY tanggal";
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
			$stsVal="";
			$aaData = array();
			$strIcon="";
			foreach($query as $row){
				
				//'<a href="'.base_url('employee/view/'.$row->ID).'"><i class="fa fa-eye" title="Lihat Detail"></i></a> | <a href="'.base_url('employee/edit/'.$row->ID).'"><i class="fa fa-pencil" title="Edit"></i></a>'
				if ($tanggalkunci< $row->tanggal){
					if (in_array($role, [25, 48])){
						$stsVal=($row->status_validasi==1?"Jurnal tervalidasi":'<a href="'.base_url('jurnalmanual/edit/'.$row->notransaksi).'" ><i class="fa fa-edit" title="Edit"></i></a> | <a href="javascript:void(0)" onclick="deljurnal('.$row->notransaksi.')"><i class="fa fa-trash-o" title="Delete"></i></a>');
						$strIcon=$stsVal;
					}else{
						$strIcon='<a href="'.base_url('jurnalmanual/edit/'.$row->notransaksi).'" ><i class="fa fa-edit" title="Edit"></i></a> | <a href="javascript:void(0)" onclick="deljurnal('.$row->notransaksi.')"><i class="fa fa-trash-o" title="Delete"></i></a>';
					}					
				}else{
					$strIcon='Edit Locked';
				}

				$aaData[] = array(
					'NOMER'=>$row->nomor,
					'TANGGAL'=>$row->tanggal,
					'KETERANGAN'=>$row->akundebet.'<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row->akunkredit.'<br>( '.$row->keterangan.' )',
					'DEBET'=>number_format($row->jumlah,2,',','.'),
					'KREDIT'=>'<br>&nbsp;'.number_format($row->jumlah,2,',','.'),
					'BUKTI'=>$row->nobuktiref,
					'ACTION'=>$strIcon

					
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
	
	public function deljurnal(){
		$id=$this->input->post('idx');
		$res = $this->common_model->deljurnal($id);
		return $res;
	}
	public function savePost(){
		$isi="awal<br>";
		if ($this->input->is_ajax_request()){	
			$this->load->library('form_validation');
			$state=$this->input->post('state');
			
				$rules = array(				
					array(
						'field' => 'cbjenis',
						'label' => 'JENIS',
						'rules' => 'trim|xss_clean|required'
					),
					
					array(
						'field' => 'tglJurnal',
						'label' => 'TANGGAL_JURNAL',
						'rules' => 'trim|xss_clean|required'
					),					
					array(
						'field' => 'bukti',
						'label' => 'BUKTI',
						'rules' => 'trim|xss_clean|required'
					)
				);
			
			$this->form_validation->set_rules($rules);
			$this->form_validation->set_message('required', 'Field %s harus diisi.');
			$this->form_validation->set_message('numeric', 'Field %s harus diisi angka.');
			$respon = new StdClass();
			if ($this->form_validation->run() == TRUE){
				$isi.="masuk form valid<br>";
				
				$this->db->trans_begin();
				try {						
					
						$sumber_data = $this->input->post('cbjenis');
						$tanggal = $this->input->post('tglJurnal');
						$bukti = $this->input->post('bukti');
						$ket = $this->input->post('ket');
						$cek="";
					if($state=="add"){ 
						$coa = $this->input->post('idcoa');
						//$coa = $this->input->post('coa');
						$id_cab = $this->input->post('idcab');
						$penanda = $this->input->post('penjelasan');
						$debet = $this->input->post('debet');	//nominal
						$kredit = $this->input->post('kredit');	//nominal
					
						$cnt = 0;
						$idDebet="";
						$idKredit="";	
						$penanda_jenis="";
						switch ($sumber_data){
								case "kasmasuk": 
										$cek.="masuk switch";
										$x=0;
										foreach($coa as $row){
											if ($debet[$x] > 0){
												$idDebet=$coa[$x];
												$penanda_jenis=$penanda[$x];
												$cek.=$debet[$x]."#".$idDebet;
											}
											$x++;
										}
										$y=0;
										foreach($coa as $rowIns){
											if ($kredit[$y] > 0){
												$data = array(
													'bulan' => substr($tanggal, 5,2),
													'tahun' => substr($tanggal, 0,4),
													'id_cab'=>$id_cab[$y],
													'tanggal' => $tanggal,
													'idperkdebet' => $idDebet,
													'idperkkredit' => $coa[$y],
													'nobuktiref' => $bukti,
													'penanda' => $penanda_jenis,
													'penanda_kredit' => $penanda[$y],
													'sumber_data' => $sumber_data,
													'keterangan' => $ket,
													'jumlah' => $kredit[$y],
													'jenis' => 'BKM',
													'uname' => $this->session->userdata('auth')->id,
													'waktuentri' => date('Y-m-d H:i:s')
												);

												if ($this->db->insert('ak_jurnal',$data)){
													$this->db->trans_commit();
													$respon->status = 'success';
												} else {
													throw new Exception("gagal simpan");
												}
											}
											$y++;
										}
									break;

									case "kaskeluar": 
										$x=0;
										foreach($coa as $row){
											if ($kredit[$x] > 0){
												$idKredit=$coa[$x];
												$penanda_jenis=$penanda[$x];
											}
											$x++;
										}
										$y=0;
										foreach($coa as $rowIns){
											if ($debet[$y] > 0){
												$data = array(
													'bulan' => substr($tanggal, 5,2),
													'tahun' => substr($tanggal, 0,4),
													'id_cab'=>$id_cab[$y],
													'tanggal' => $tanggal,
													'idperkdebet' => $coa[$y],
													'idperkkredit' => $idKredit,
													'nobuktiref' => $bukti,
													'penanda' => $penanda[$y],
													'penanda_kredit' => $penanda_jenis,
													'sumber_data' => $sumber_data,
													'keterangan' => $ket,
													'jumlah' => $debet[$y],
													'jenis' => 'BKK',
													'uname' => $this->session->userdata('auth')->id,
													'waktuentri' => date('Y-m-d H:i:s')
												);

												if ($this->db->insert('ak_jurnal',$data)){
													$this->db->trans_commit();
													$respon->status = 'success';
												} else {
													throw new Exception("gagal simpan");
												}
											}
											$y++;
										}
									break;
									case "jurnalumum": 
									case "susut": 
										$x=0;
										$nom=0;
										$trs="D";
										$penandaD="";
										$penandaK="";
										$id_cabangs="";
										foreach($coa as $row){
											if ($kredit[$x] > 0){
												$idKredit=$coa[$x];
												$nom=$kredit[$x];
												$penandaK=$penanda[$x];
												$id_cabangs=$id_cab[$x];
												$trs="K";
											}else{
												$idDebet=$coa[$x];
												$nom=$debet[$x];
												$penandaD=$penanda[$x];
												$trs="D";
												$id_cabangs=$id_cab[$x];
											}
											$x++;
										}
										
											$data = array(
													'bulan' => substr($tanggal, 5,2),
													'tahun' => substr($tanggal, 0,4),
													'id_cab'=>$id_cabangs,
													'tanggal' => $tanggal,
													'idperkdebet' => $idDebet,
													'idperkkredit' => $idKredit,
													'nobuktiref' => $bukti,
													'penanda' => $penandaD,
													'penanda_kredit' => $penandaK,
													'sumber_data' => $sumber_data,
													'keterangan' => $ket,
													'jumlah' => $nom,
													'jenis' => 'BNK',
													'uname' => $this->session->userdata('auth')->id,
													'waktuentri' => date('Y-m-d H:i:s')
												);

												if ($this->db->insert('ak_jurnal',$data)){
													$this->db->trans_commit();
													$respon->status = 'success';
												} else {
													throw new Exception("gagal simpan");
												}
											
									
									break;
							}

					
					}
					$isi.=$cek;
					
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
	
	public function getkasmasuk(){
		$periode=$this->common_model->comboGetPeriode();
		$id = $this->input->post('id');
		$str="select ak_jurnal.*, (select nama from ak_rekeningperkiraan where idacc=ak_jurnal.idperkdebet) akundebet, (select nama from ak_rekeningperkiraan where idacc=ak_jurnal.idperkkredit) akunkredit from ak_jurnal where notransaksi='$id' and jenis='BKM'";
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
	
	public function getAkunNonKas(){
		$keyword = $this->input->post('term');
		$data['response'] = 'false';
		
		//debet kas / bank akun 1.
		$str="select * from ak_rekeningperkiraan where level=5 and status=1  ".($this->session->userdata('auth')->id_cabang<=1?"":"and id_cab=".$this->session->userdata('auth')->id_cabang)." and (nama LIKE '%{$keyword}%' or idacc LIKE '%{$keyword}%') and (idacc not like  '1.1.1.%' ) and  (idacc not like   '1.1.2.%') order by idacc";
		
		$query = $this->db->query($str)->result();
		//$data['str']=$str;
		
		if( ! empty($query) )
		{	$data['strq'] = $str; 
			$data['response'] = 'true'; //Set response
			$data['message'] = array(); //Create array
			foreach( $query as $row )
			{	
				$strcek="select count(*) jml from ak_rekeningperkiraan where status=1 and idparent='".$row->idacc."'";
			    $qcek=$this->db->query($strcek)->row();
				if ( $qcek->jml<=0){
					$data['message'][] = array(
						'pid'=>$row->idacc,
						'id'=>$row->idacc,
						'id_cab'=>$row->id_cab,
						'label' => $row->idacc.' - '.$row->nama,
						'value' => $row->nama,
						''
					);
				}

				
			}
		}
		echo json_encode($data);
	}
	public function getAkunAll(){
		$keyword = $this->input->post('term');
		$data['response'] = 'false';
		
		//debet kas / bank akun 1.
		$str="select * from ak_rekeningperkiraan where level=5 and status=1  ".($this->session->userdata('auth')->id_cabang<=1?"":"and id_cab=".$this->session->userdata('auth')->id_cabang)." and (nama LIKE '%{$keyword}%' or idacc LIKE '%{$keyword}%') order by idacc";
		
		$query = $this->db->query($str)->result();
		//$data['str']=$str;
		
		if( ! empty($query) )
		{	$data['strq'] = $str; 
			$data['response'] = 'true'; //Set response
			$data['message'] = array(); //Create array
			foreach( $query as $row )
			{	
				$strcek="select count(*) jml from ak_rekeningperkiraan where status=1 and idparent='".$row->idacc."'";
			    $qcek=$this->db->query($strcek)->row();
				if ( $qcek->jml<=0){
					$data['message'][] = array(
						'pid'=>$row->idacc,
						'id'=>$row->idacc,
						'id_cab'=>$row->id_cab,
						'label' => $row->idacc.' - '.$row->nama,
						'value' => $row->nama,
						''
					);
				}

				
			}
		}
		echo json_encode($data);
	}
	
	public function getAkunDebet(){
		$keyword = $this->input->post('term');
		$data['response'] = 'false';
		
		//debet kas / bank akun 1.
		$str="select * from ak_rekeningperkiraan where level=5 and (idacc  like '1.1.1%' or  idacc  like  '1.1.2%')  and status=1  and id_cab=".$this->session->userdata('auth')->ID_CABANG." and (nama LIKE '%{$keyword}%' or idacc LIKE '%{$keyword}%') order by idacc";
		
		$query = $this->db->query($str)->result();
		//$data['str']=$str;
		
		if( ! empty($query) )
		{	$data['strq'] = $str; 
			$data['response'] = 'true'; //Set response
			$data['message'] = array(); //Create array
			foreach( $query as $row )
			{	
				$strcek="select count(*) jml from ak_rekeningperkiraan where status=1 and idparent='".$row->idacc."'";
			    $qcek=$this->db->query($strcek)->row();
				if ( $qcek->jml<=0){
					$data['message'][] = array(
						'pid'=>$row->idacc,
						'id'=>$row->idacc,
						'label' => $row->idacc.' - '.$row->nama,
						'value' => $row->nama,
						''
					);
				}

				
			}
		}
		echo json_encode($data);
	}

	public function getAkunKredit(){
		$keyword = $this->input->post('term');
		$data['response'] = 'false';//
	
		$str="select * from ak_rekeningperkiraan where level>4  and status=1  and id_cab=".$this->session->userdata('auth')->ID_CABANG." and (nama LIKE '%{$keyword}%' or idacc LIKE '%{$keyword}%') order by idacc";
		

		$query = $this->db->query($str)->result();
	
		
		if( ! empty($query) )
		{
			$data['strq'] = $str; //Set response
			$data['response'] = 'true'; //Set response
			$data['message'] = array(); //Create array
			foreach( $query as $row )
			{	$strcek="select count(*) jml from ak_rekeningperkiraan where status=1 and idparent='".$row->idacc."'";
			    $qcek=$this->db->query($strcek)->row();
				if ( $qcek->jml<=0){
					$data['message'][] = array(
						'pid'=>$row->idacc,
						'id'=>$row->idacc,
						'label' => $row->idacc.' - '.$row->nama,
						'value' => $row->nama,
						''
					);
				}
			}
		}
		echo json_encode($data);
	}

	public function getSaldoKas(){
		$idacc=$this->input->post("idacc");	
		$jumlah=$this->report_model->getsaldo($idacc);		
		$respon['status']='success';
		$respon['jml']=$jumlah;
		echo json_encode($respon);
	}

	public function edit($id=null){
		if($this->input->post()){
			$this->load->library('form_validation');
			$rules = array(
				array(
					'field' => 'bukti',
					'label' => 'NO_BUKTI',
					'rules' => 'trim|xss_clean|required'
				)
			);
		
			$this->form_validation->set_rules($rules);
			$this->form_validation->set_message('required', 'Field %s harus diisi.');
			$respon = new StdClass();
			if ($this->form_validation->run() == TRUE){
				
				$this->db->trans_begin();
				try {
					$cabang = $this->input->post('cabang');
					$notrans = $this->input->post('notrans');
					$sumber_data = $this->input->post('sumber_data');
					$jenis = $this->input->post('jenis');
					$tanggal = $this->input->post('tglJurnal');
					$bukti = $this->input->post('bukti');
					$ket = $this->input->post('ket');
					$coa = $this->input->post('idcoa');
					//$coa = $this->input->post('coa');
					$id_cab = $this->input->post('idcab');
					$penanda = $this->input->post('penjelasan');
					$debet = $this->input->post('debet');	//nominal
					$kredit = $this->input->post('kredit');	//nominal
					

					$cnt = 0;
					$idDebet="";
					$idKredit="";	
					$penanda_jenis="";
					$x=0;
					$nom=0;
					$trs="D";
					$penandaD="";
					$penandaK="";
					$id_cabangs="";
						foreach($coa as $row){
							if ($kredit[$x] > 0){
								$idKredit=$coa[$x];
								$nom=$kredit[$x];
								$penandaK=$penanda[$x];
								$id_cabangs=$id_cab[$x];
								$trs="K";
							}else{
								$idDebet=$coa[$x];
								$nom=$debet[$x];
								$penandaD=$penanda[$x];
								$trs="D";
								$id_cabangs=$id_cab[$x];
							}
							$x++;
						}
						
					$data = array(
						'bulan' => substr($tanggal, 5,2),
						'tahun' => substr($tanggal, 0,4),
						'id_cab'=>$id_cabangs,
						'tanggal' => $tanggal,
						'idperkdebet' => $idDebet,
						'idperkkredit' => $idKredit,
						'nobuktiref' => $bukti,
						'penanda' => $penandaD,
						'penanda_kredit' => $penandaK,
						'sumber_data' => $sumber_data,
						'keterangan' => $ket,
						'jumlah' => $nom,
						'jenis' => 'BNK',
						'uname' => $this->session->userdata('auth')->id,
						'waktuentri' => date('Y-m-d H:i:s')
					);

					if ($this->db->where('notransaksi',$notrans)->update('ak_jurnal',$data)){
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
				
			} else {
				$respon->status = 'error';
				$respon->errormsg = validation_errors();
				
			}
			echo json_encode($respon);
			exit;
		}
		$arrJenis=array(
			'penghimpunan'=>'Pendapatan ZIS',
			'penyaluran'=>'Penyaluran Program',			
			'penggajian'=>'Penggajian',			
			'kasmasuk'=>'Pemasukan Kas/Bank',			
			'kaskeluar'=>'Pengeluaran Kas/Bank',			
			'jurnalumum'=>'Jurnal Non Kas/Bank',			
			'susut'=>'Jurnal Penyusutan'
		);
		
		
		$res=$this->db->query("select * from ak_jurnal where notransaksi=".$id)->row();
		$data['row'] = $res;
		$resAkunDebet=$this->db->query("select * from ak_rekeningperkiraan where idacc='".$res->idperkdebet."'")->row();
		$resAkunKredit=$this->db->query("select * from ak_rekeningperkiraan where idacc='".$res->idperkkredit."'")->row();
		$data['resAkunDebet'] = $resAkunDebet;
		$data['resAkunKredit'] = $resAkunKredit;
		$data['cabang'] =$this->common_model->getCabang($res->id_cab);
		$data['arrJenis'] = $arrJenis;
		
		$this->template->set('breadcrumbs','<li><a href="#">Jurnal</a></li><li><a href="#">Jurnal Manual</a></li>');
		$this->template->set('pagetitle','Editing Jurnal Umum Kas/Bank Masuk/Keluar dan Non Kas/Bank');		
		$this->template->load('default','fjurnal/vedit_jurnalmanual',$data);
		//redirect('jurnalmanual');

	}
}
