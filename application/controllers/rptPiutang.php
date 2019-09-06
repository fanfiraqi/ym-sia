<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class rptPiutang extends MY_App {

	function __construct()
	{
		parent::__construct();		
		$this->load->model('report_model');
		$this->load->helper('file');
		$this->load->library('CI_Pdf');
		$this->load->helper('download');
		$this->config->set_item('mymenu', 'menuLima');
		$this->config->set_item('mysubmenu', 'menuLima1');
		$this->auth->authorize();
	}
	
	public function index()
	{	
		$this->template->set('breadcrumbs','<li><a href="#">Laporan</a></li><li><a href="#">Data Peminjam</a></li>');
		$this->template->set('pagetitle','Informasi Data Pinjaman (View/Cetak)');		
		$this->template->load('default','freport/vfilterLoan',compact('str'));
	}

	public function personalLoan($param=null)
	{	
		$header=$this->commonlib->reportHeader();
		$footer=$this->commonlib->reportFooter();
		if ($param!=null){
			$arr=explode("_",$param);
			$id_head=$arr[0];
			$display=$arr[1];			
			
		}else{
			$display=$this->input->post('display1');
			$id_head=$this->input->post('id_head');
			
		}
		
		$strMaster="select * from ak_pinjaman_header  where id =$id_head order by id desc limit 1";
		$resMaster=$this->db->query($strMaster)->row();
		
		$strDetil="select * from ak_pinjaman_angsuran where id_header=".$id_head;
		$resDetil=$this->db->query($strDetil)->result();
		
		$rsCab=$this->db->query("select KOTA from sialmi.mst_cabang where ID_CABANG=".($this->session->userdata('auth')->ID_CABANG<='1'?'1':$this->session->userdata('auth')->ID_CABANG))->row();
		
		$data['nmcabang']=$rsCab;
		$data['id_head']=$id_head;
		$data['title']="Informasi Data Pinjaman Perorangan";
		$data['display']=$display;
		$data['resMaster']=$resMaster;
		$data['resDetil']=$resDetil;
		$namafile="data_pinjaman_".$id_head;
		if ($display==0){
			$this->template->set('breadcrumbs','<li><a href="#">Laporan</a></li><li><a href="#">Data Peminjam</a></li>');
			$this->template->set('pagetitle','Informasi Data Pinjaman Perorangan (View/Cetak)');		
			$this->template->load('default','freport/personalLoan',$data);
		}else{
			$html=$header;
			$html.=$this->load->view('freport/personalLoan', $data, true);
			$html.=$footer;
			$this->ci_pdf->pdf_create($html, $namafile);
		}	
	}
	public function rekapLoan($param=null)
	{	
		$header=$this->commonlib->reportHeader();
		$footer=$this->commonlib->reportFooter();
		$display="";
		$jns_status="";
		if ($param!=null){
			//if $jns_status Belum_lunas
			$arr=explode("_",$param);
			if (sizeof($arr)>=3){
				$jns_status="Belum Lunas";
				$display=$arr[2];
			//$jns_status=str_replace("%20"," ",$arr[0]);			
			}else{
				$jns_status=$arr[0];
				$display=$arr[1];
			}
						
			
		}else{
			$display=$this->input->post('display2');
			$jns_status=$this->input->post('jns_status');
			
		}
		
		$strMaster="SELECT h.*, a.JML_CICILAN, SUM(a.JML_BAYAR) SDH_BAYAR, COUNT(a.JML_BAYAR) JML_ANGS
			FROM `ak_pinjaman_header` h, ak_pinjaman_angsuran a
			WHERE h.id=a.id_header and jml_bayar>0 AND h.STATUS='".$jns_status."' and id_cabang=".($this->session->userdata('auth')->ID_CABANG<='1'?'1':$this->session->userdata('auth')->ID_CABANG)." group by h.id";
		$resMaster=$this->db->query($strMaster)->result();		
		
		$rsCab=$this->db->query("select KOTA from sialmi.mst_cabang where ID_CABANG=".($this->session->userdata('auth')->ID_CABANG<='1'?'1':$this->session->userdata('auth')->ID_CABANG))->row();
		
		$data['nmcabang']=$rsCab;
		$data['strMaster']=$strMaster;
		$data['title']="Data Rekap Pinjaman ".$jns_status;
		$data['display']=$display;
		$data['resMaster']=$resMaster;
		$data['jns_status']=$jns_status;
		//$data['resDetil']=$resDetil;
		$namafile="rekap_pinjaman_".$jns_status;
		if ($display==0){
			$this->template->set('breadcrumbs','<li><a href="#">Laporan</a></li><li><a href="#">Rekap Pinjaman</a></li>');
			$this->template->set('pagetitle',"Data Rekap Pinjaman ".$jns_status." (View/Cetak)");		
			$this->template->load('default','freport/rekapLoan',$data);
		}else{
			$html=$header;
			$html.=$this->load->view('freport/rekapLoan', $data, true);
			$html.=$footer;
			$this->ci_pdf->pdf_create_report($html, $namafile, 'a4', 'landscape');
		}	
	}
	public function getPeminjam(){
		$keyword = $this->input->post('term');
		$data['response'] = 'false';
		$str=" select * from ak_pinjaman_header where  NAMA LIKE '%{$keyword}%' order by NAMA";
	
		$query = $this->db->query($str)->result();

		if( ! empty($query) )
		{
			$data['response'] = 'true'; //Set response
			$data['message'] = array(); //Create array
			foreach( $query as $row )
			{
				$data['message'][] = array(
					'id'=>$row->ID,
					'label' => $row->NAMA,
					'value' => $row->NAMA,				
					''
				);
			}
		}
		echo json_encode($data);
	}
}
