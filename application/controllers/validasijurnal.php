<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class validasijurnal extends MY_App {
	
	

	function __construct()
	{
		parent::__construct();
		//$this->load->model('rekening_model');
		$this->config->set_item('mymenu', 'menuTiga');
		$this->config->set_item('mysubmenu', 'menuTigaTujuh');
		$this->auth->authorize();

	}
	
	
	public function index()
	{	$arrJenis=array(
			'penghimpunan'=>'Pendapatan ZIS',
			'penyaluran'=>'Penyaluran Program',			
			'penggajian'=>'Penggajian',			
			'kasmasuk'=>'Pemasukan Kas/Bank',			
			'kaskeluar'=>'Pengeluaran Kas/Bank',			
			'jurnalumum'=>'Jurnal Non Kas/Bank',			
			'susut'=>'Jurnal Penyusutan'
		);
		$data['arrJenis']=$arrJenis;
		$this->template->set('breadcrumbs','<li><a href="#">Jurnal</a></li><li><a href="#">Validasi Transaksi Jurnal</a></li>');
		$this->template->set('pagetitle','Cek & Validasi Transaksi Jurnal');		
		$this->template->load('default','fjurnal/vvfilter',$data);
		
	}
	public function display(){
		$arrJenis=array(
			'penghimpunan'=>'Pendapatan ZIS',
			'penyaluran'=>'Penyaluran Program',
			'penggajian'=>'Penggajian',	
			'kasmasuk'=>'Pemasukan Kas/Bank',			
			'kaskeluar'=>'Pengeluaran Kas/Bank',			
			'jurnalumum'=>'Jurnal Non Kas/Bank',			
			'susut'=>'Jurnal Penyusutan'
		);
		$jenis=$this->input->post('cbjenis');	
		$tglJurnal=$this->input->post('tgl1');	
		//$rsStatus=$this->db->query("select * from jurnal where sumber_data='$jenis' and tgl_jurnal='$tglJurnal' ".($this->session->userdata('auth')->ID_CABANG==0?"":" and id_cab=".$this->session->userdata('auth')->ID_CABANG))->row();
		$data['nmcabang']="Semua Cabang";
		if ($this->session->userdata('auth')->ID_CABANG>1){
			$nmcabang=$this->db->query('select KOTA from mst_cabang where ID_CABANG='.$this->session->userdata('auth')->ID_CABANG)->row();
			$data['nmcabang']=($this->session->userdata('auth')->ID_CABANG==0?"KANTOR ". $nmcabang->KOTA:" CABANG ". $nmcabang->KOTA);
		}
		$data['pilihan']=$this->input->post('pilihan');	
		$data['jenis']=$jenis;
		$data['jenis_trans']=$arrJenis[$jenis];
		$data['tglJurnal']=$tglJurnal;
		/*if (sizeof($rsStatus)<=0){
			$status='Belum Ada Transaksi';
		}else{
			$status=($rsStatus->status_validasi==0?'open':'valid');
		}
		$data['status']=$status;*/
		$this->template->set('breadcrumbs','<li><a href="#">Jurnal</a></li><li><a href="#">Form Validasi Jurnal <u><b>'.$arrJenis[$jenis].'</b></u> '.$data['nmcabang'].'</a></li>');
		$this->template->set('pagetitle','Form Validasi Transaksi Jurnal '.$arrJenis[$jenis]);		
		$this->template->load('default','fjurnal/vdisplayValidasi',$data);

	}

	public function dashboard($jenisjurnal){
		$arrJenis=array(
			'tsetoranzis'=>'ZIS Rutin',
			'tinsidentil'=>'ZIS Insidentil',			
			'kasmasuk'=>'Pemasukan Kas/Bank',			
			'kaskeluar'=>'Pengeluaran Kas/Bank',			
			'jurnalumum'=>'Jurnal Non Kas/Bank',			
			'susut'=>'Jurnal Penyusutan'
		);
		$jenis=$jenisjurnal;	
		$tglJurnal=date('Y-m-d');	
		//$rsStatus=$this->db->query("select * from jurnal where sumber_data='$jenis' and tgl_jurnal='$tglJurnal' ".($this->session->userdata('auth')->ID_CABANG==0?"":" and id_cab=".$this->session->userdata('auth')->ID_CABANG))->row();

		//$data['nmcabang']=$nmcabang;
		$data['pilihan']='validasi';
		$data['jenis']=$jenis;
		$data['jenis_trans']=$arrJenis[$jenis];
		$data['tglJurnal']=$tglJurnal;
		/*if (sizeof($rsStatus)<=0){
			$status='Belum Ada Transaksi';
		}else{
			$status=($rsStatus->status_validasi==0?'open':'valid');
		}
		$data['status']=$status;*/
		//$this->template->set('breadcrumbs','<li><a href="#">Jurnal</a></li><li><a href="#">Form Validasi Jurnal '.$arrJenis[$jenis].' '..'</a></li>');
		$this->template->set('pagetitle','Daftar Transaksi Jurnal '.$arrJenis[$jenis].'Tanggal '.$tglJurnal);		
		$this->template->load('default','fjurnal/vdisplayValidasi',$data);

	}
	public function getValidation(){
		$id = $this->input->post('id');
		$respon=array();
		$sts=1;
		$this->db->trans_begin();
		try {
			if ($this->db->where('notransaksi',$id)->update('ak_jurnal', array("status_validasi"=>$sts, 'petugas_validasi'=>$this->session->userdata('auth')->id, 'tgl_validasi'=>date('Y-m-d H:i:s')))) {
				$this->db->trans_commit();
				$respon['status'] = 'success';
			} else {
				throw new Exception("gagal simpan");
			}
		} catch (Exception $e) {
			$respon['status'] = 'error';
			$respon['errormsg'] = $e->getMessage();
			$this->db->trans_rollback();
		}
		echo  json_encode($respon);
	}

	public function validate_all(){
		$sumber_data = $this->input->post('sumber_data');	
		$pilihan = $this->input->post('pilihan');	
		$tanggal = $this->input->post('tanggal');	
		
		$str="update ak_jurnal set status_validasi=1, tgl_validasi='".date('Y-m-d')."', petugas_validasi=".$this->session->userdata('auth')->id." where  sumber_data='".$sumber_data."' ".($this->session->userdata('auth')->ID_CABANG>1?" and id_cab=".$this->session->userdata('auth')->ID_CABANG:"")." ".($pilihan=='validasi'?" and status_validasi=0 ":" and tanggal='".$tglJurnal."'");

		if($this->db->query($str)){
			$respon['status'] = 'success';
			$respon['str'] = $str;			
		} else {			
			$respon['status'] = 'error';
			$respon['errormsg'] = 'Invalid Data';			
		}
		echo json_encode($respon);
	}
}