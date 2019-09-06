<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class jurnalnonkas extends MY_App {
	var $branch = array();
	

	function __construct()
	{
		parent::__construct();
		//$this->load->model('jurnalnonkas_model');
		$this->config->set_item('mymenu', 'menuTiga');
		$this->config->set_item('mysubmenu', 'menuTigaLima');
		$this->auth->authorize();

	}
	
	public function index()
	{	$periode=$this->common_model->comboGetPeriode();
		$data['periode']=$periode;
		$this->template->set('breadcrumbs','<li><a href="#">Jurnal</a></li><li><a href="#">Jurnal Non Kas & Bank</a></li>');
		$this->template->set('pagetitle','Jurnal Umum Transaksi Non Kas');		
		$this->template->load('default','fjurnal/vjurnalnonkas',$data);
		
	}
	
	
	
	public function json_data(){
		//if ($this->input->is_ajax_request()){
		
			$start = $this->input->get('iDisplayStart');
			$limit = $this->input->get('iDisplayLength');
			$sortby = $this->input->get('iSortCol_0');
			$srotdir = $this->input->get('sSortDir_0');
			$tanggal1=$this->input->get('tanggal1');
			$tanggal2=$this->input->get('tanggal2');
			//$str = "select @nom:=@nom+1 as nomor, jurnal.*, (select nama from rekeningperkiraan where idacc=jurnal.idperkdebet) akundebet, (select nama from rekeningperkiraan where idacc=jurnal.idperkkredit) akunkredit from jurnal where tanggal='$tanggal' and sumber_data='jurnalumum' and jenis='BNK'".($this->session->userdata('auth')->ID_CABANG==0?'':" and id_cab=".$this->session->userdata('auth')->ID_CABANG);
			
			$pengunci=$this->db->query("select * from ak_pengunci where id=1")->row();
			$tanggalkunci='';
			$stsKunci=0;
				if (sizeof($pengunci)>0){
					$tanggalkunci=$pengunci->tanggal;
					if ($tanggalkunci>$tanggal1 || $tanggalkunci>date('Y-m-d')){
						$stsKunci=1;
					}
				}

			$str = "select @nom:=@nom+1 as nomor, ak_jurnal.*, (select nama from ak_rekeningperkiraan where idacc=ak_jurnal.idperkdebet) akundebet, (select nama from ak_rekeningperkiraan where idacc=ak_jurnal.idperkkredit) akunkredit from ak_jurnal where (tanggal between '$tanggal1' and '$tanggal2') and sumber_data='jurnalumum' and jenis='BNK'  and id_cab=".$this->session->userdata('auth')->ID_CABANG;
			
						
			if ( $_GET['sSearch'] != "" )
			{
				
				$str .= " and `keterangan` like '%".mysql_real_escape_string( $_GET['sSearch'] )."' ";
				
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
				if ($stsKunci==0){
					if (substr($this->session->userdata('auth')->ROLE, 0, 5)=="Kasir"){
						$stsVal=($row->status_validasi==1?"Jurnal tervalidasi":'<a href="javascript:void(0)" onclick="editjurnalnonkas(this)" data-id="'.$row->notransaksi.'"><i class="fa fa-edit" title="Edit"></i></a> | <a href="javascript:void(0)" onclick="deljurnalnonkas('.$row->notransaksi.')"><i class="fa fa-trash-o" title="Delete"></i></a>');
						$strIcon=$stsVal;
					}else{
						$strIcon='<a href="javascript:void(0)" onclick="editjurnalnonkas(this)" data-id="'.$row->notransaksi.'"><i class="fa fa-edit" title="Edit"></i></a> | <a href="javascript:void(0)" onclick="deljurnalnonkas('.$row->notransaksi.')"><i class="fa fa-trash-o" title="Delete"></i></a>';
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
				"iTotalRecords" => $iTotal,
				"str" => $str,
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => $aaData
			);
			echo json_encode($output);
		//}
	}	
	
	public function deljurnalnonkas(){
		$id=$this->input->post('idx');
		$res = $this->common_model->deljurnalnonkas($id);
		return $res;
	}
	public function savejurnalnonkas(){
		$isi="awal<br>";
		if ($this->input->is_ajax_request()){	
			$this->load->library('form_validation');
			$state=$this->input->post('state');
			


			$rules = array(	
					array(
						'field' => 'tglJurnal',
						'label' => 'TANGGAL_JURNAL',
						'rules' => 'trim|xss_clean|required'
					),
					array(
						'field' => 'jumlah',
						'label' => 'JUMLAH',
						'rules' => 'trim|xss_clean|required|numeric'
					),					
					array(
						'field' => 'ket',
						'label' => 'MEMO',
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
				$tanggal=$this->input->post('tglJurnal');

					if($state=="add"){ 
						$data = array(
							'bulan' => substr($tanggal, 5,2),
							'tahun' => substr($tanggal, 0,4),
							//'notransaksi' => $this->input->post('id'),
							'id_cab'=>$this->session->userdata('auth')->ID_CABANG,
							'tanggal' => $tanggal,
							'idperkdebet' => $this->input->post('akundebet'),
							'idperkkredit' => $this->input->post('akunkredit'),
							'nobuktiref' => $this->input->post('bukti'),
							'sumber_data' => 'jurnalumum',
							'keterangan' => $this->input->post('ket'),
							'jumlah' => $this->input->post('jumlah'),
							'jenis' => 'BNK',
							'isjurnalbalik' => $this->input->post('ckOpsi'),
							'uname' => $this->session->userdata('auth')->id,
							'waktuentri' => date('Y-m-d H:i:s')
						);
						if ($this->db->insert('ak_jurnal',$data)){
							$this->db->trans_commit();
							$respon->status = 'success';
						} else {
							throw new Exception("gagal simpan");
						}
					}else{						
						$data = array(
							'bulan' => substr($tanggal, 5,2),
							'tahun' => substr($tanggal, 0,4),							
							'tanggal' => $tanggal,	
							'id_cab'=>$this->session->userdata('auth')->ID_CABANG,
							'idperkdebet' => $this->input->post('akundebet'),
							'idperkkredit' => $this->input->post('akunkredit'),
							'nobuktiref' => $this->input->post('bukti'),
							'keterangan' => $this->input->post('ket'),
							'jumlah' => $this->input->post('jumlah'),
							'jenis' => 'BNK',
							'isjurnalbalik' => $this->input->post('ckOpsi'),
							'uname' => $this->session->userdata('auth')->id,
							'waktuentri' => date('Y-m-d H:i:s')
						);
						if ($this->db->where('notransaksi',$state)->update('ak_jurnal',$data)){
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
	
	public function getjurnalnonkas(){
		$periode=$this->common_model->comboGetPeriode();
		$id = $this->input->post('id');
		$str="select ak_jurnal.*, (select nama from ak_rekeningperkiraan where idacc=ak_jurnal.idperkdebet) akundebet, (select nama from ak_rekeningperkiraan where idacc=ak_jurnal.idperkkredit) akunkredit from ak_jurnal where notransaksi='$id' and jenis='BNK'";
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
		
		/*if ( $this->session->userdata('auth')->ROLE=='Kasir Pusat'|| $this->session->userdata('auth')->ROLE=='Accounting Pusat'){
			$str="select * from rekeningperkiraan where status=1  and (nama LIKE '%{$keyword}%' or idacc LIKE '%{$keyword}%') order by idacc";
		}else{
			$str="select * from rekeningperkiraan where  (idacc not like '1.1.01%') and  (idacc not like  '1.1.02%') and status=1 ".($this->session->userdata('auth')->ID_CABANG==0?"":" and id_cab=".$this->session->userdata('auth')->ID_CABANG)." and  (nama LIKE '%{$keyword}%' or idacc LIKE '%{$keyword}%') order by idacc";
		}*/
		
		$str="select * from ak_rekeningperkiraan where  level>3 and  (idacc not like '1.1.01%') and  (idacc not like  '1.1.02%') and status=1  and id_cab=".$this->session->userdata('auth')->ID_CABANG." and  (nama LIKE '%{$keyword}%' or idacc LIKE '%{$keyword}%') order by idacc";

		$query = $this->db->query($str)->result();

		
		if( ! empty($query) )
		{
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

	
	
}
