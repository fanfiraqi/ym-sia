<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class dashboard extends MY_App {
	var $branch = array();

	function __construct()
	{
		parent::__construct();
		$this->load->model('report_model');
		//$this->CI->output->set_template('default');
		$this->auth->authorize('index');
	}
	
	public function index()
	{	if ($this->auth->is_login()){
			$data["role"]=$this->config->item('myrole');
			$nmcabang=$this->db->query('select KOTA from mst_cabang where ID_CABANG='.$this->session->userdata('auth')->id_cabang)->row();
			$this->template->set('pagetitle','Beranda '.$this->session->userdata('param_company').' '.($this->session->userdata('auth')->id_cabang!=0?'CABANG':'').' '.(sizeof($nmcabang)>0?$nmcabang->KOTA:''));	
			$this->template->set('breadcrumbs','<li><a href="#">Home</a></li><li><a href="#">Dashboard</a></li>');
			$this->template->load('default','dashboard/index',$data);
			
		}else{
			redirect($this->config->item('gate_link')."/keluar");
		}
	}
	
	public function logout(){
		//$str="update client_login set lastlogin='".date('Y-m-d H:i:s')."' where client_uname='".$this->session->userdata('auth')->client_uname."'";
		//$this->db->query($str);
		//$this->db->trans_commit();
		$this->auth->logout();
		//redirect('/');
		redirect($this->config->item('gate_link')."/keluar");
	}
	
}
