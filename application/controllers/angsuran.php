<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class angsuran extends MY_App {

	function __construct()
	{
		parent::__construct();
		$this->load->model('pinjaman_model');
		$this->config->set_item('mymenu', 'menuEmpat');
		$this->config->set_item('mysubmenu', 'menuEmpatDua');
		$this->auth->authorize();
	}
	
	public function index()
	{
		$this->template->set('breadcrumbs','<li><a href="#">Buku Pembantu Hutang</a></li><li><a href="#">Pembayaran Angsuran Pinjaman</a></li>');
		$this->template->set('pagetitle','Daftar Catatan Angsuran Karyawan');		
		$this->template->load('default','fpinjaman/index_angsuran',compact('str'));
	}
	
	public function json_data($pid){
		//if ($this->input->is_ajax_request()){
		
			$start = $this->input->get('iDisplayStart');
			$limit = $this->input->get('iDisplayLength');
			$sortby = $this->input->get('iSortCol_0');
			$srotdir = $this->input->get('sSortDir_0');
			
			$str = "SELECT a.*, h.id FROM ak_pinjaman_header h, ak_pinjaman_angsuran a WHERE h.ID=a.ID_HEADER AND h.ID=$pid and h.status='Belum Lunas'";
			
			if ( $_GET['sSearch'] != "" )
			{
				
				//$str .= " AND k.NAMA like '%".mysql_real_escape_string( $_GET['sSearch'] )."%' ";
				
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
					'ID'=>$row->ID_HEADER,					
					'CICILAN'=>$row->CICILAN_KE,
					'TGL'=>$row->TGL_BAYAR,
					'JUMLAH'=>"Rp.&nbsp;".number_format($row->JML_CICILAN,0,',','.'),
					'STATUS'=>($row->JML_BAYAR >= $row->JML_CICILAN?'Lunas':'Belum Lunas')
					//'ACTION'=>($row->JML_BAYAR >= $row->JML_CICILAN?'-':'<a href="javascript:void()" onclick="lunasi('.$row->ID_HEADER.','.$row->CICILAN_KE.', '.$row->JML_CICILAN.')"><i class="fa fa-pencil" title="Set Lunas"></i>Set Lunas</a>')
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
	
	
	public function saveAngsuran($id){
		$rscicil=$this->pinjaman_model->getAngsuran($id);
		$rsheader=$this->pinjaman_model->getPinjaman($id);
		$cicil_ke=$rscicil->jml_ke;
		$respon = new StdClass();
		if ($cicil_ke <= $rsheader->LAMA){
			$dataBayar = array(
				'ID_HEADER' => $id,
				'CICILAN_KE' => $cicil_ke,
				'JML_CICILAN' => ($rsheader->JUMLAH/$rsheader->LAMA),
				'TGL_BAYAR' => date('Y-m-d'),
				'JML_BAYAR' => ($rsheader->JUMLAH/$rsheader->LAMA),
				'ACC_BAYAR_VIA' => $this->input->post('accvia'),
				'PETUGAS' =>$this->session->userdata('auth')->USERNAME,				
				'CREATED_DATE' => date('Y-m-d H:i:s')
			);

			if ($this->db->insert('ak_pinjaman_angsuran',$dataBayar)){
				if ($cicil_ke >= $rsheader->LAMA){
					$this->db->where('ID',$id)->update('ak_pinjaman_header',array('STATUS'=>'Lunas'));
				}

				//entri jurnal
				$dataP = array(
								'bulan' => date('m'),
								'tahun' => date('Y'),
								'id_cab'=>$this->session->userdata('auth')->ID_CABANG,
								//'notransaksi' => $this->input->post('id'),
								'tanggal' => date('Y-m-d'),
								'idperkdebet' => $this->input->post('accvia'),
								'idperkkredit' => $rsheader->ACC_ID_PIUTANG,
								'penanda' => 'Pelunasan_Piutang#'.$id.'#ke_'.$cicil_ke,
								'nobuktiref' => '',
								'sumber_data' => 'kasmasuk',
								'keterangan' => 'Buku Pembantu Piutang '.$this->input->post('jnspeminjam').' pelunasan cicilan piutang',
								'jumlah' => ($rsheader->JUMLAH/$rsheader->LAMA),
								'jenis' => 'BKM',
								'uname' => $this->session->userdata('auth')->USERNAME,
								'waktuentri' => date('Y-m-d H:i:s')
							);
							$this->db->insert('ak_jurnal',$dataP);
							$this->db->trans_commit();
							$respon->status = 'success';
			}else {
				throw new Exception("gagal simpan");
			}
			
		}
		

		echo json_encode($respon);

	}
	
	public function getView(){
		$keyword = $this->input->post('term');
		$data['response'] = 'false';
		
		$str= "SELECT ak_pinjaman_header.*, (select kota from mst_cabang where id_cabang=ak_pinjaman_header.id_cabang) NMCABANG from ak_pinjaman_header where status='Belum Lunas' and  NAMA LIKE '%{$keyword}%'";
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
					'cabang' => $row->NMCABANG,
					'tgl_pinjam' => $row->TGL_PINJAM,
					'keperluan' => $row->KEPERLUAN,
					'jumlah' => $row->JUMLAH,
					'lama' => $row->LAMA,
					'jns_peminjam' => $row->JNS_PEMINJAM,
					'status' => $row->STATUS,
					''
				);
			}
		}
		echo json_encode($data);
	}

	public function view($id){
		//$id = $this->input->post('id');
		
		$data['rowAngs'] = $this->pinjaman_model->getAngsuran($id);
		$data['rowHead'] = $this->pinjaman_model->getPinjaman($id);
		$data['accvia'] =$this->pinjaman_model->getCabAcc();
		
		if ($this->input->is_ajax_request()){
			$this->template->load('ajax','fpinjaman/formBayar',$data);
		} else {
			//$this->template->set('pagetitle','Detail Permohonan Ijin');
			//$this->template->load('default','cuti/view',$data);
		}
	}
	
}
