<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$menu="pengguna";
class MY_App extends CI_Controller {
	public $arrBulan=array("1"=>"Januari", 
			"2"=>"Februari",
			"3"=>"Maret",
			"4"=>"April",
			"5"=>"Mei",
			"6"=>"Juni",
			"7"=>"Juli",
			"8"=>"Agustus",
			"9"=>"September",
			"10"=>"Oktober",
			"11"=>"November",
			"12"=>"Desember"
			);
	public $arrBulan2=array("01"=>"Januari", 
			"02"=>"Februari",
			"03"=>"Maret",
			"04"=>"April",
			"05"=>"Mei",
			"06"=>"Juni",
			"07"=>"Juli",
			"08"=>"Agustus",
			"09"=>"September",
			"10"=>"Oktober",
			"11"=>"November",
			"12"=>"Desember"
			);
	public $arrIntBln=array("1"=>"01",
				"2"=>"02",
				"3"=>"03",
				"4"=>"04",
				"5"=>"05",
				"6"=>"06",
				"7"=>"07",
				"8"=>"08",
				"9"=>"09",
				"10"=>"10",
				"11"=>"11",
				"12"=>"12"
				);
	public $arrKel=array("1.2.1"=>"Aset Tetap (AT)", "1.2.3"=>"Aset Tidak Lancar Kelolaan dana Infaq (ATLKI)","1.2.5"=>"Aset Tidak Lancar Kelolaan dana Waqaf (ATLKW)");
	public $arrGrup=array("Bangunan"=>"Bangunan", "Kendaraan"=>"Kendaraan","Inventaris"=>"Inventaris");
	function __construct()
    {
        parent::__construct();
		//$this->auth->authorize('login');
		//$this->load->helper('url');
		//$this->initdata();
		$this->load->model('common_model');
		$this->gate_db=$this->load->database('gate', TRUE);
		
		// $this->load->library('template');
		
    }
	
	function getYearArr(){
		$now=date('Y');
		//$j=0;
		for ($i=date('Y')-10; $i<=date('Y')+10; $i++) {	
			$year[$i]=$i;
			

		}
		return $year;
	}

	function initdata(){
		$first = $this->session->userdata('1stvisit');
		if (!$first){
			$this->session->set_userdata('labelling',$this->param_model->get('company', true));
			$this->session->set_userdata('param_company',$this->param_model->get('company'));
			$this->session->set_userdata('company_dept',$this->param_model->getDept('company'));
			$this->session->set_userdata('logo',$this->param_model->getLogo('company'));
			$this->session->set_userdata('1stvisit',true);
		}
	}
	
	function divTreeFilter($datas, $parent = 0, $depth = 0){
		global $branch;
		if($depth > 1000) return ''; // Make sure not to have an endless recursion
		
		for($i=0, $ni=count($datas); $i < $ni; $i++){
			if($datas[$i]['idparent'] == $parent){
				$val = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $depth);
				$val .= $depth==0?'':'&raquo; ';
				$val .= $datas[$i]['nama'];
				$branch[$datas[$i]['idacc']] = $val;
				$tree = $this->divTree($datas, $datas[$i]['idacc'], $depth+1);
			}
		}
		return $branch;
	}
	function divTree($datas, $parent = 0, $depth = 0){
		global $branch;
		if($depth > 1000) return ''; // Make sure not to have an endless recursion
		
		for($i=0, $ni=count($datas); $i < $ni; $i++){
			if($datas[$i]['idparent'] == $parent){
				$val = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $depth);
				$val .= $depth==0?'':'&raquo; ';
				$val .= $datas[$i]['nama'];
				$branch[$datas[$i]['idacc']] = $val;
				$tree = $this->divTree($datas, $datas[$i]['idacc'], $depth+1);
			}
		}
		return $branch;
	}
	function divTreeKas($datas, $parent = 0, $depth = 0){
		global $branch2;
		if($depth > 1000) return ''; // Make sure not to have an endless recursion
		
		for($i=0, $ni=count($datas); $i < $ni; $i++){
			if($datas[$i]['idparent'] == $parent){
				$val = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $depth);
				$val .= $depth==0?'':'&raquo; ';
				$val .= $datas[$i]['idacc'].' - '.$datas[$i]['nama'];
				$branch2[$datas[$i]['idacc']] = $val;
				$tree = $this->divTreeKas($datas, $datas[$i]['idacc'], $depth+1);
			}
		}
		return $branch2;
	}
}
