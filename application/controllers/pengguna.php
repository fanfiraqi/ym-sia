<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class pengguna extends MY_App {
	var $branch = array();
	

	function __construct()
	{
		parent::__construct();
		$this->load->model('pengguna_model');
		$this->config->set_item('mymenu', 'menuSatu');
		$this->config->set_item('mysubmenu', 'menuSatuDua');
		$this->auth->authorize('login','logout','dologin');

	}
	
	public function index()
	{	$this->template->set('breadcrumbs','<li><a href="#">Pengaturan</a></li><li><a href="#">Pengguna Aplikasi</a></li>');
		$this->template->set('pagetitle','Daftar Pengguna Aplikasi');		
		$this->template->load('default','fmaster/vpengguna',compact('str'));
		
	}
	
	public function editMyProfil($id)
	{	
		$data["res"]=$this->db->query("Select * from ak_mst_user where id=$id")->row();
		$this->template->set('breadcrumbs','<li><a href="#">Pengaturan</a></li><li><a href="#">Edit My Profile</a></li>');
		$this->template->set('pagetitle','Edit Data Profil');		
		$this->template->load('default','fmaster/vuser_edit',$data);
		
	}
	
	public function json_data(){
		//if ($this->input->is_ajax_request()){
		
			$start = $this->input->get('iDisplayStart');
			$limit = $this->input->get('iDisplayLength');
			$sortby = $this->input->get('iSortCol_0');
			$srotdir = $this->input->get('sSortDir_0');
			
			$str = "select ak_mst_user.*, (select KOTA from mst_cabang where ID_CABANG=ak_mst_user.ID_CABANG) as WILAYAH from ak_mst_user";
			
						
			if ( $_GET['sSearch'] != "" )
			{
				
				$str .= " where `NAMA` like '%".mysql_real_escape_string( $_GET['sSearch'] )."' or `USERNAME` like '%".mysql_real_escape_string( $_GET['sSearch'] )."%' ";
				
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
					'ID'=>$row->ID,
					'USERNAME'=>$row->USERNAME,
					'PASSWORD'=>"***",
					'NAMA'=>$row->NAMA,
					'WILAYAH'=>$row->WILAYAH,
					'ROLE_AKSES'=>$row->ROLE,
					'ISACTIVE'=>($row->ISACTIVE=="1"?"Aktif":"Tidak Aktif"),
					'ACTION'=>"<a href='javascript:void(0)' onclick='editUser(this)' data-id='".$row->ID."'><i class='fa fa-edit' title='Edit'></i></a> | <a href='javascript:void()' onclick=\"delUser(".$row->ID.",'".$row->USERNAME."')\"><i class='fa fa-trash-o' title='Delete'></i></a>"
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
	public function edit($id=null){
	
		if($this->input->post())
		{
			$role=$this->session->userdata('auth')->ROLE;
			$this->load->library('form_validation');
			
				$rules = array(			
					
					array(
						'field' => 'nama',
						'label' => 'NAMA',
						'rules' => 'trim|xss_clean|required'
					),
					array(
						'field' => 'password',
						'label' => 'PASSWORD',
						'rules' => 'trim|xss_clean|required'
					)
				);
			
			$this->form_validation->set_rules($rules);
			$this->form_validation->set_message('required', 'Field %s harus diisi.');
			$respon = new StdClass();
			if ($this->form_validation->run() == TRUE){
				
				$this->db->trans_begin();
				try {
						
					
						$data = array(		
							'NAMA' => $this->input->post('nama'),
							'PASSWORD' => md5($this->input->post('password')),													
							'UPDATED_BY' =>'admin',
							'UPDATED_DATE' =>date('Y-m-d H:i:s')
						);
					
					if ($this->db->where('ID',$this->input->post('id'))->update('ak_mst_user', $data)){
						$this->db->trans_commit();
					} else {
						throw new Exception("gagal simpan");
					}
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
			exit;
		
		} 
		
		$this->template->set('pagetitle','Update Data Pengguna');
		//$cabang=$this->common_model->comboGetCabang();

		$data['row'] = $this->pengguna_model->getById($id);
		$data['cabang'] = $this->common_model->comboGetCabang();
		if (empty($data['row'])){
			flashMessage('Data Invalid','danger');
			redirect('pengguna');
		}
		$this->template->load('default','fmaster/user_edit',$data);
		
	}
	public function delUser(){
		$id=$this->input->post('idx');
		$res = $this->pengguna_model->deleteUser($id);
		return $res;
	}
	public function saveUser(){
		$isi="awal<br>";
		if ($this->input->is_ajax_request()){	
			$this->load->library('form_validation');
			$state=$this->input->post('state');
			if ($state=='add'){
				$rules = array(				
					array(
						'field' => 'username',
						'label' => 'USERNAME',
						'rules' => 'trim|xss_clean|required'
					),
					array(
						'field' => 'password',
						'label' => 'PASSWORD',
						'rules' => 'trim|xss_clean|required'
					),
					array(
						'field' => 'nama',
						'label' => 'NAMA',
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
						'field' => 'nama',
						'label' => 'NAMA',
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
			$respon = new StdClass();
			if ($this->form_validation->run() == TRUE){
				$isi.="masuk form valid<br>";
				$state=$this->input->post('state');
				
				$this->db->trans_begin();
				try {						
					$cabang=0;		//user manager,direktur, & admin
					if (substr($this->input->post('role'), 0, 5)=="Kasir" || $this->input->post('role')=="Accounting"){
						$cabang=$this->input->post('wilayah');
					}
					$data = array(
							'USERNAME' => $this->input->post('username'),
							'PASSWORD' => md5($this->input->post('password')),
							'NAMA' => $this->input->post('nama'),
							'ID_CABANG' => $cabang,
							'ROLE' => $this->input->post('role'),							
							'ISACTIVE' => $this->input->post('status'),						
							'CREATED_BY' =>'admin',
							'CREATED_DATE' =>date('Y-m-d H:i:s'),
							'UPDATED_BY' =>'admin',
							'UPDATED_DATE' =>date('Y-m-d H:i:s')
						);			
					

					if($state=="add"){ 
						
						if ($this->db->insert('ak_mst_user',$data)){
							$this->db->trans_commit();
							$respon->status = 'success';
						} else {
							throw new Exception("gagal simpan");
						}
					}else{
						//note empty ganti is_null utk 
						//if ($this->input->post('password')=='' || empty($this->input->post('password') ) ) {
						if ($this->input->post('password')==''  ) {
							$data = array(
							'USERNAME' => $this->input->post('username'),							
							'NAMA' => $this->input->post('nama'),
							'ID_CABANG' => $cabang,
							'ROLE' => $this->input->post('role'),							
							'ISACTIVE' => $this->input->post('status'),						
							'CREATED_BY' =>'admin',
							'CREATED_DATE' =>date('Y-m-d H:i:s'),
							'UPDATED_BY' =>'admin',
							'UPDATED_DATE' =>date('Y-m-d H:i:s')
						);	
						}
						
						if ($this->db->where('ID',$state)->update('ak_mst_user',$data)){
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
	
	public function getUser(){
		$id = $this->input->post('id');
		$str="select * from ak_mst_user where ID=$id";
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
	
		
	public function login(){
		if ($this->auth->is_login()){
			redirect('/');
		}		
		$this->load->view('login');
	}
	
	public function dologin(){
		$data['response']='true';
		if ($this->input->is_ajax_request()){
			$user = trim(strip_tags($this->input->post('username')));
			$password = md5(stripslashes(trim($this->input->post('password'))));
			if($this->auth->do_login($user,$password)){
				$data['response']='true';
			} else {
				$data['response']='false';
			}
			echo json_encode($data); 
			//echo json_encode(array('response'=>'true'));
		} else {
			redirect('/');
		}
	}
	
	public function logout(){
		$this->auth->logout();
		redirect($this->config->item('gate_link')."/keluar");
	}
}
