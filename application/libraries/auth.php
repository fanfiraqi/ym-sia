<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Auth{
	
	public $allowedActions = array();
	protected $_methods = array();
	
	function __construct()
	{
		$this->CI =& get_instance();
		$this->_methods = $this->CI->router->method;
	}
	
	function do_login($username,$password)
	{
		$this->CI->db->select(" *  ",false)
			->from('ak_mst_user u',false)			
			->where(array('u.username'=>$username,'u.password'=>$password,'u.isactive'=>1))
			->limit(1);
		$result = $this->CI->db->get();
                
                
		if($result->num_rows() == 0) 
		{
			return false;
		}
		else	
		{
			$userdata = $result->row();
			$session = array();
			$session['auth'] = $userdata;
			$this->CI->session->set_userdata($session);
			return true;
		}
	}
function is_login()
	{	
		$this->CI->load->helper('cookie');
    	$this->CI->load->library('session');
    	//$APIUrl = 'https://ym-gate.appdemo.online/api/';
    	$APIUrl = 'http://ym-gate.yatimmandiri.test/api/';
    	$urlRequest = 'pengguna/sso'; // contoh endpoint
    	$apiKey = 'YAt1MmaNdIR1aPiK3y';
    	$secret = 'hRHIzFLBGoaM76M7q';	//hRHIzFLBGoaM76M7q
    	$data = array(
    		'CODE' => get_cookie('code')
    	);
    
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $APIUrl . $urlRequest);
    	curl_setopt($ch, CURLOPT_POST, 1);
    	curl_setopt($ch, CURLOPT_HTTPHEADER, [
    		'API-KEY: ' . $apiKey,
    		'SECRET: ' . $secret
    	]);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    
    	$response = curl_exec($ch);
    	curl_close($ch);
 
	    //$session = array();
		//$session['auth'] = json_decode($response, TRUE);
		//$this->CI->session->set_userdata($session);
		/*
		$arrSes=json_decode($response, TRUE);
        $user_id= $arrSes["data"] ["user_id"];
       // $user_id= 1;
		$result=$this->CI->gate_db->query("SELECT g.name ROLE, g.id, u.*, u.id_cabang ID_CABANG FROM users u, users_groups ug, groups g WHERE u.id=ug.user_id AND g.id= ug.group_id AND u.id=".$user_id);
		
		$userdata = $result->row();
		
		if($result->num_rows() == 0) 
		{
			return false;
		}
		else	
		{
			
			$session = array();
			$session['auth'] = $userdata;
			$session['gate'] = $arrSes["data"] ;
			$session['logo'] = "logo2.png" ;
			$this->CI->config->set_item('role', $arrSes["data"]['group_id']);
			//$session['sql'] = "SELECT g.name ROLE, g.id, u.* FROM users u, users_groups ug, groups g WHERE u.id=ug.user_id AND g.id= ug.group_id AND u.id=".$user_id;
			$this->CI->session->set_userdata($session);
			$sess=$this->CI->session->userdata('gate');
				$role=$sess['group_id'];
				$this->CI->config->set_item('myrole', $role);
			return true;
		}*/
		
		//$session = array();
		$arrSes=json_decode($response, TRUE);
        $user_id= $arrSes["data"] ["user_id"];
        $user_id= 11;
		$result=$this->CI->gate_db->query("SELECT g.name ROLE, g.id group_id, u.*, u.id_cabang ID_CABANG FROM users u, users_groups ug, groups g WHERE u.id=ug.user_id AND g.id= ug.group_id AND u.id=".$user_id);
		
		$userdata = $result->row();
		
		if($result->num_rows() == 0) 
		{
			return false;
		}
		else	
		{
			
			$session = array();
			$session['auth'] = $userdata;
			$session['gate'] = $arrSes["data"] ;
			$session['logo'] = "logo2.png" ;
			
			//$session['sql'] = "SELECT g.name ROLE, g.id, u.* FROM users u, users_groups ug, groups g WHERE u.id=ug.user_id AND g.id= ug.group_id AND u.id=".$user_id;
			$this->CI->session->set_userdata($session);
			$sess=$this->CI->session->userdata('gate');
			//$role=$sess['group_id'];
			$role=$userdata->group_id;
			$this->CI->config->set_item('myrole', $role);
			$this->CI->config->set_item('role', $role);
			return true;
		}

	}	

	
	
	function authorize(){
		$args = func_get_args();
		if ((!in_array($this->CI->router->method,$args)) && (@$args[0]<>'*') ){
			if ($this->is_login()==false){
				$this->CI->session->set_userdata(array('flashmsg'=>'Anda tidak memiliki hak untuk mengakses.<br />Silakan login terlebih dahulu.','flashtype'=>'danger'));
				//debug('auth error');
				redirect($this->config->item('gate_link')."/keluar");;
			}
		}
	}
	
	
	function cek_menu($idmenu)
	{
		$this->CI->load->model('usermodel');
		$status_user = $this->CI->session->userdata('level');
		$allowed_level = $this->CI->usermodel->get_array_menu($idmenu);
		//if(in_array($status_user,$allowed_level) == false)
                if ($allowed_level == false)
		{
			die("Maaf, Anda tidak berhak untuk mengakses halaman ini. ");
			
		}
	}
	function logout()
	{
		$this->CI->session->unset_userdata('auth');
	}
	
}