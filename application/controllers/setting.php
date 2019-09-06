<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class setting extends MY_App {

	function __construct()
	{
		parent::__construct();
		$this->config->set_item('mymenu', 'menuSatu');
		$this->config->set_item('mysubmenu', 'menuSatuSatu');
		$this->load->helper(array('form', 'url'));
		$this->load->model('param_model');
		$this->auth->authorize();
	}
	
	
	
	public function parameter(){
		$this->template->set('breadcrumbs','<li><a href="#">Pengaturan</a></li><li><a href="#">Parameter</a></li>');
		$this->template->set('pagetitle','Setting Parameter');
		$this->template->load('default','setting/parameter');
	}
	
	public function savingParams(){
			$id = $this->input->post('id');
			$value1 = $this->input->post('value1');							
			$value3 = strtoupper($this->input->post('value3'));							
			$description = $this->input->post('description');				
			$this->db->trans_begin();
			$respon = new StdClass();
		try {
			$nama_file = $_FILES['userfile']['name'];
			
			
			$config['file_name'] = $nama_file;
			$config['upload_path'] = './assets/img';
			$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
			$config['max_size']    = '1024';
			$config['max_width']  = '1600';
			$config['max_height']  = '1200';
			$this->load->library('upload', $config);
			$error='';
			$dataUpload='';
			
			if ( ! $this->upload->do_upload('userfile')){
				$error = array('error' => $this->upload->display_errors());
				//throw new Exception("gagal simpan, ".var_dump($error).'#'.print_r($_FILES).'#'.print_r($_POST));
				
				$data= array(
						'value1' => $value1,						
						'value3' =>$value3,
						'description' =>$description
						
				);
			}else{
				$dataUpload = $this->upload->data();
				$filenya= $dataUpload['file_name'];		
				$data= array(
						'value1' => $value1,
						'value2' =>$filenya,
						'value3' =>$value3,
						'description' =>$description
						
				);
			}
			
				
					
					$this->db->where('id',$id)->update('ak_params',$data);				
					$this->db->trans_commit();
					$this->session->set_userdata('labelling',$this->param_model->get('company', true));
					$this->session->set_userdata('param_company',$this->param_model->get('company'));
					$this->session->set_userdata('company_dept',$this->param_model->getDept('company'));
					$this->session->set_userdata('logo',$this->param_model->getLogo('company'));
					$respon->status = 'success';
			//$respon->isi='<br>file='.$dataUpload['file_name'];
			
			//$respon->isi=$error."#".var_dump($_FILES['userfile'])."#".var_dump($this->upload->data());

		}catch (Exception $e) {
				//$respon->isi="error loh";
					$respon->status = 'error';
					$respon->errormsg = $e->getMessage();;
					$this->db->trans_rollback();
		}
				
		$this->template->set('pagetitle','Setting Parameter');
		$this->template->load('default','setting/parameter');

	}
	
	public function getParams(){
		$id = $this->input->post('id');
		$str="select * from ak_params where id=$id";
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
	public function json(){
			$start = $this->input->get('iDisplayStart');
			$limit = $this->input->get('iDisplayLength');
			$sortby = $this->input->get('iSortCol_0');
			$srotdir = $this->input->get('sSortDir_0');
			
			$str = "SELECT * FROM ak_params";
			
						
			if ( $_GET['sSearch'] != "" )
			{
				
				$str.= " WHERE value1 LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' ";
				
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
					'id'=>$row->id,
					'value1'=>$row->value1,
					'value3'=>$row->value3,
					'description'=>$row->description,
					'value2'=>"<img src=\"".base_url("assets/img/".$row->value2)."\" width=\"100\" height=\"80\">",										
					'ACTION'=>'<a href="javascript:void(0)"	onclick="editParams(this)" data-id="'.$row->id.'" ><i class="fa fa-edit" title="Edit Detail"></i></a> '
				);
			}
			
			$output = array(
				"sEcho" => intval($_GET['sEcho']),
				"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => $aaData
			);
			echo json_encode($output);
	}
}
