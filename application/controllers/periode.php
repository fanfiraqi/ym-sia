<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class periode extends MY_App {
	var $branch = array();
	

	function __construct()
	{
		parent::__construct();
		//$this->load->model('periode_model');
		$this->config->set_item('mymenu', 'menuSatu');
		$this->config->set_item('mysubmenu', 'menuSatuTiga');
		$this->auth->authorize();

	}
	
	public function index()
	{	$this->template->set('breadcrumbs','<li><a href="#">Pengaturan</a></li><li><a href="#">Periode Buku</a></li>');
		$this->template->set('pagetitle','Daftar Periode Buku');		
		$this->template->load('default','fmaster/vperiode',compact('str'));
		
	}
	
	
	
	public function json_data(){
		//if ($this->input->is_ajax_request()){
		
			$start = $this->input->get('iDisplayStart');
			$limit = $this->input->get('iDisplayLength');
			$sortby = $this->input->get('iSortCol_0');
			$srotdir = $this->input->get('sSortDir_0');
			
			$str = "select * from ak_periodebuku";
			
						
			if ( $_GET['sSearch'] != "" )
			{
				
				$str .= " where `thnbuku` like '%".mysql_real_escape_string( $_GET['sSearch'] )."' or `tglawal` like '%".mysql_real_escape_string( $_GET['sSearch'] )."%' or `tglakhir` like '%".mysql_real_escape_string( $_GET['sSearch'] )."%'";
				
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
			
			$aaData = array();
			foreach($query as $row){
				$aaData[] = array(
					'ID'=>$row->id,
					'TAHUN'=>$row->thnbuku,
					'TGL1'=>$row->tglawal,
					'TGL2'=>$row->tglakhir,
					'ISACTIVE'=>($row->isactive=="1"?"Aktif":"Tidak Aktif"),
					'ACTION'=>'<a href="javascript:void(0)" onclick="editPeriode(this)" data-id="'.$row->id.'"><i class="fa fa-edit" title="Edit"></i></a> | '.($row->isactive=="1"?'':'<a href="javascript:void()" onclick="delPeriode('.$row->id.','.$row->thnbuku.')"><i class="fa fa-trash-o" title="Delete"></i></a>')
					//'ACTION'=>' <a href="javascript:void(0)"	onclick="editGroup(this)" data-id="'.$row->id_group.'"><i class="icon-pencil" title="Edit '.$row->id_group.'"></i></a>| <a href="javascript:void(0)" onclick="delGroup(this)" data-id="'.$row->id_group.'"><i class="icon-remove" title="Hapus '.$row->id_group.'"></i></a> '
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
	
	public function delPeriode(){
		$id=$this->input->post('idx');
		$res = $this->common_model->delPeriode($id);
		return $res;
	}
	public function savePeriode(){
		$isi="awal<br>";
		if ($this->input->is_ajax_request()){	
			$this->load->library('form_validation');
			$state=$this->input->post('state');
			if ($state=='add'){
				$rules = array(				
					array(
						'field' => 'tahun',
						'label' => 'TAHUN',
						'rules' => 'trim|xss_clean|required|numeric'
					),
					array(
						'field' => 'tgl1',
						'label' => 'TANGGAL_AWAL',
						'rules' => 'trim|xss_clean|required'
					),
					array(
						'field' => 'tgl2',
						'label' => 'TANGGAL_AKHIR',
						'rules' => 'trim|xss_clean|required'
					),
					array(
						'field' => 'status',
						'label' => 'STATUS',
						'rules' => 'trim|xss_clean|required'
					)
				);
			}else{
				$rules = array(				
					
					array(
						'field' => 'tgl1',
						'label' => 'TANGGAL_AWAL',
						'rules' => 'trim|xss_clean|required'
					),
					array(
						'field' => 'tgl2',
						'label' => 'TANGGAL_AKHIR',
						'rules' => 'trim|xss_clean|required'
					),
					array(
						'field' => 'status',
						'label' => 'STATUS',
						'rules' => 'trim|xss_clean|required'
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
					$status=$this->input->post('status');
					if ($status==1){
						$this->db->query('update ak_periodebuku set isactive=0');
						$this->db->trans_commit();
					}
					$data = array(
							'thnbuku' => $this->input->post('tahun'),
							'tglawal' => $this->input->post('tgl1'),
							'tglakhir' => $this->input->post('tgl2'),
							'isactive' => $status
						);			
					

					if($state=="add"){ 
						
						if ($this->db->insert('ak_periodebuku',$data)){
							$this->db->trans_commit();
							$respon->status = 'success';
						} else {
							throw new Exception("gagal simpan");
						}
					}else{						
						
						if ($this->db->where('id',$state)->update('ak_periodebuku',$data)){
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
	
	public function getPeriode(){
		$id = $this->input->post('id');
		$str="select * from ak_periodebuku where id=$id";
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
