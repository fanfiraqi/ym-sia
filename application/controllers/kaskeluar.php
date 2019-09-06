<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class kaskeluar extends MY_App {
	var $branch = array();
	

	function __construct()
	{
		parent::__construct();
		$this->load->model('report_model');
		$this->config->set_item('mymenu', 'menuTiga');
		$this->config->set_item('mysubmenu', 'menuTigaTiga');
		$this->auth->authorize();

	}
	
	public function index()
	{	$periode=$this->common_model->comboGetPeriode();	
		$data['periode']=$periode;
		$this->template->set('breadcrumbs','<li><a href="#">Jurnal</a></li><li><a href="#">Pengeluaran Kas & Bank</a></li>');
		$this->template->set('pagetitle','Jurnal Umum Transaksi Bank & Kas Keluar');		
		$this->template->load('default','fjurnal/vkaskeluar',$data);
		
	}
	
	
	
	public function json_data(){
		//if ($this->input->is_ajax_request()){
			$tanggal1=$this->input->get('tanggal1');
			$tanggal2=$this->input->get('tanggal2');
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
			
			//$str = "select @nom:=@nom+1 as nomor, jurnal.*, (select nama from rekeningperkiraan where idacc=jurnal.idperkdebet) akundebet, (select nama from ak_rekeningperkiraan where idacc=jurnal.idperkkredit) akunkredit from jurnal where tanggal='$tanggal' and sumber_data='kaskeluar' and jenis='BKK'".($this->session->userdata('auth')->ID_CABANG==0?'':" and id_cab=".$this->session->userdata('auth')->ID_CABANG);
			
			$str = "select @nom:=@nom+1 as nomor, ak_jurnal.*, (select nama from ak_rekeningperkiraan where idacc=ak_jurnal.idperkdebet) akundebet, (select nama from ak_rekeningperkiraan where idacc=ak_jurnal.idperkkredit) akunkredit from ak_jurnal where (tanggal between '$tanggal1' and '$tanggal2') and sumber_data='kaskeluar' and jenis='BKK'  and id_cab=".$this->session->userdata('auth')->ID_CABANG;
						
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
						$stsVal=($row->status_validasi==1?"Jurnal tervalidasi":'<a href="javascript:void(0)" onclick="editkaskeluar(this)" data-id="'.$row->notransaksi.'"><i class="fa fa-edit" title="Edit"></i></a> | <a href="javascript:void(0)" onclick="delkaskeluar('.$row->notransaksi.')"><i class="fa fa-trash-o" title="Delete"></i></a>');
						$strIcon=$stsVal;
					}else{
						$strIcon='<a href="javascript:void(0)" onclick="editkaskeluar(this)" data-id="'.$row->notransaksi.'"><i class="fa fa-edit" title="Edit"></i></a> | <a href="javascript:void(0)" onclick="delkaskeluar('.$row->notransaksi.')"><i class="fa fa-trash-o" title="Delete"></i></a>';
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
					
				//($stsKunci==0?'<a href="javascript:void(0)" onclick="editkaskeluar(this)" data-id="'.$row->notransaksi.'"><i class="fa fa-edit" title="Edit"></i></a> | <a href="javascript:void(0)" onclick="delkaskeluar('.$row->notransaksi.')"><i class="fa fa-trash-o" title="Delete"></i></a>':'Edit Locked')
					//'ACTION'=>' <a href="javascript:void(0)"	onclick="editGroup(this)" data-id="'.$row->id_group.'"><i class="icon-pencil" title="Edit '.$row->id_group.'"></i></a>| <a href="javascript:void(0)" onclick="delGroup(this)" data-id="'.$row->id_group.'"><i class="icon-remove" title="Hapus '.$row->id_group.'"></i></a> '
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
	
	public function delkaskeluar(){
		$id=$this->input->post('idx');
		$res = $this->common_model->delkaskeluar($id);
		return $res;
	}
	public function savekaskeluar(){
		$isi="awal<br>";
		if ($this->input->is_ajax_request()){	
			$this->load->library('form_validation');
			$state=$this->input->post('state');
			$tanggal=$this->input->post('tglJurnal');
			$rules = array(	
					array(
						'field' => 'akunkredit',
						'label' => 'AKUNKREDIT',
						'rules' => 'trim|xss_clean|required'
					),
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
				$isAnggaran='off';
				$periodAnggaran='';
				if ($this->session->userdata('auth')->ID_CABANG==1){
					$isAnggaran=$this->input->post('ckOpsi');
					//$periodAnggaran=date('Ym');
					$periodAnggaran=substr($tanggal, 0,4).substr($tanggal, 5,2);
				}
				$this->db->trans_begin();
				try {						
					

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
							'sumber_data' => 'kaskeluar',
							'keterangan' => $this->input->post('ket'),
							'jumlah' => $this->input->post('jumlah'),
							'jenis' => 'BKK',
							'uname' => $this->session->userdata('auth')->id,
							'waktuentri' => date('Y-m-d H:i:s'),
							'isAnggaran' => $isAnggaran,
							'periodAnggaran' => $periodAnggaran

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
							'id_cab'=>$this->session->userdata('auth')->ID_CABANG,
							'idperkdebet' => $this->input->post('akundebet'),
							'idperkkredit' => $this->input->post('akunkredit'),
							'tanggal' => $tanggal,							
							'nobuktiref' => $this->input->post('bukti'),
							'keterangan' => $this->input->post('ket'),
							'jumlah' => $this->input->post('jumlah'),
							'jenis' => 'BKK',
							'uname' => $this->session->userdata('auth')->id,
							'waktuentri' => date('Y-m-d H:i:s'),
							'isAnggaran' => $isAnggaran,
							'periodAnggaran' => $periodAnggaran
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
	
	
	
	public function getSaldoKas(){
		$initval=0;
		$saldoLalu=0;
		$initval=$this->report_model->getInitValCashFlow($this->session->userdata('auth')->ID_CABANG);
		$saldoLalu=$this->report_model->getSaldoLaluDashboard();		
		$kasMasuk=$this->report_model->kasMasukHariIni();		
		$kasKeluar=$this->report_model->kasKeluarHariIni();
		$jumlah=($initval+$saldoLalu+$kasMasuk)-$kasKeluar;
		$respon['status']='success';
		$respon['jml']=$jumlah;
		echo json_encode($respon);
	}
	public function getkaskeluar(){
		$periode=$this->common_model->comboGetPeriode();
		$id = $this->input->post('id');
		$str="select ak_jurnal.*, (select nama from ak_rekeningperkiraan where idacc=ak_jurnal.idperkdebet) akundebet, (select nama from ak_rekeningperkiraan where idacc=ak_jurnal.idperkkredit) akunkredit from ak_jurnal where notransaksi='$id' and jenis='BKK'";
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
	
}
