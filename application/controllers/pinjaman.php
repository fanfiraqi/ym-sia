<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class pinjaman extends MY_App {

	function __construct()
	{
		parent::__construct();
		$this->load->model('pinjaman_model');
		$this->config->set_item('mymenu', 'menuEmpat');
		$this->config->set_item('mysubmenu', 'menuEmpatSatu');
		$this->auth->authorize();
	}
	
	public function index()
	{	$nmCabang="Admin";
		if ($this->session->userdata('auth')->ID_CABANG != '0'){
			$nmCabang = $this->db->query("select KOTA from mst_cabang where id_cabang=".$this->session->userdata('auth')->ID_CABANG)->row();
			$nmCabang=$nmCabang->KOTA;
		}
		$data["nmCabang"]=$nmCabang;
		
		$data['accvia'] =$this->pinjaman_model->getCabAcc();
		$this->template->set('breadcrumbs','<li><a href="#">Buku Pembantu Hutang</a></li><li><a href="#">Master Peminjaman</a></li>');
		$this->template->set('pagetitle','Daftar Catatan Pinjaman');		
		$this->template->load('default','fpinjaman/index',$data);
	}
	
	public function json_data(){
		//if ($this->input->is_ajax_request()){
		
			$start = $this->input->get('iDisplayStart');
			$limit = $this->input->get('iDisplayLength');
			$sortby = $this->input->get('iSortCol_0');
			$srotdir = $this->input->get('sSortDir_0');
			
			$str = "SELECT * FROM ak_pinjaman_header  WHERE STATUS='Belum Lunas' and id_cabang=".$this->session->userdata('auth')->ID_CABANG;
			
			if ( $_GET['sSearch'] != "" )
			{
				
				$str .= " AND NAMA like '%".mysql_real_escape_string( $_GET['sSearch'] )."%' ";
				
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
				$scek=$this->db->query("SELECT COUNT(*) jml FROM ak_pinjaman_angsuran WHERE id_header=".$row->ID)->row();
				
				$aaData[] = array(
					'ID'=>$row->ID,		
					'NAMA'=>$row->NAMA,
					'JENIS'=>$row->JNS_PEMINJAM,
					'TGL'=>$row->TGL_PINJAM,
					'TELP_HP'=>$row->TELP." / ".$row->NO_HP,
					'JUMLAH'=>"Rp.&nbsp;".number_format($row->JUMLAH,0,',','.'),
					'LAMA'=>$row->LAMA." Kali",
					'STATUS'=>$row->STATUS,
					'ACTION'=>($scek->jml<=0? '<a href="javascript:void(0)" onclick="editPinjaman(this)" data-id="'.$row->ID.'"><i class="fa fa-edit" title="Edit"></i></a> | <a href="javascript:void(0)" onclick="delPinjaman('.$row->ID.', \''.$row->NAMA.'\')"><i class="fa fa-power-off" title="Delete"></i></a>' : 'disabled')
					
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
	
	public function savePinjaman(){
		$isi="awal<br>";$strGetJ="";
		if ($this->input->is_ajax_request()){	
			$this->load->library('form_validation');
			$state=$this->input->post('state');
			$isi.=$state."#".$this->input->post('nama')."#".$this->input->post('alamat');
			$rules = array(				
					array(
						'field' => 'nama',
						'label' => 'NAMA',
						'rules' => 'trim|xss_clean|required'
					),
					array(
						'field' => 'alamat',
						'label' => 'ALAMAT',
						'rules' => 'trim|xss_clean|required'
					),
					array(
						'field' => 'jumlah',
						'label' => 'JUMLAH',
						'rules' => 'trim|xss_clean|required|numeric'
					),
					array(
						'field' => 'lama',
						'label' => 'LAMA',
						'rules' => 'trim|xss_clean|required|numeric'
					),
					array(
						'field' => 'hp',
						'label' => 'HP',
						'rules' => 'trim|xss_clean|required|numeric'
					),
					array(
						'field' => 'telepon',
						'label' => 'TELEPON',
						'rules' => 'trim|xss_clean|required|numeric'
					),
					array(
						'field' => 'keperluan',
						'label' => 'KEPERLUAN',
						'rules' => 'trim|xss_clean|required'
					)
			);
			$this->form_validation->set_rules($rules);
			$this->form_validation->set_message('required', 'Field %s harus diisi.');
			$this->form_validation->set_message('numeric', 'Field %s harus diisi angka.');
			$respon = new StdClass();
			if ($this->form_validation->run() == TRUE){
				$isi.="masuk validation";
				$this->db->trans_begin();
				try {						
					$prefix=($this->input->post('jnspeminjam')=="Karyawan"?"1.1.07":"1.1.14");
					//get acc piutang							
					$strGet="select * from ak_rekeningperkiraan where id_cab=".$this->session->userdata('auth')->ID_CABANG." and idacc like '".$prefix."%.01'";
					$acc_piutang=$this->db->query($strGet)->row();
					$id_acc_piutang=(sizeof($acc_piutang)>0?$acc_piutang->idacc:$prefix.".01.01");	//jika tdk ada maka masuk pusat

					$data = array(
							'ID_CABANG' => ($this->session->userdata('auth')->ID_CABANG=='0'?'1':$this->session->userdata('auth')->ID_CABANG),
							'NAMA' => $this->input->post('nama'),
							'JNS_PEMINJAM' => $this->input->post('jnspeminjam'),
							'ALAMAT' => $this->input->post('alamat'),
							'NO_IDENTITAS' => $this->input->post('noidentitas'),
							'JK' => $this->input->post('jnskelamin'),
							'TELP' => $this->input->post('telepon'),							
							'NO_HP' => $this->input->post('hp'),							
							'TGL_PINJAM' => $this->input->post('tanggal'),	
							'JUMLAH' => $this->input->post('jumlah'),							
							'LAMA' => $this->input->post('lama'),							
							'ACC_BAYAR_VIA' => $this->input->post('accvia'),							
							'ACC_ID_PIUTANG' => $id_acc_piutang,							
							'KEPERLUAN' => $this->input->post('keperluan'),							
							'PETUGAS_ENTRI' => $this->session->userdata('auth')->NAMA,							
							'MENYETUJUI' => $this->input->post('menyetujui'),							
							'MENGETAHUI' =>$this->input->post('mengetahui'),							
							//'ISACTIVE' => $this->input->post('status'),						
							'CREATED_BY' =>$this->session->userdata('auth')->NAMA,
							'CREATED_DATE' =>date('Y-m-d H:i:s'),
							'UPDATED_BY' => $this->session->userdata('auth')->NAMA,
							'UPDATED_DATE' =>date('Y-m-d H:i:s')
						);			
					
					

					if($state=="add"){ 
						
						if ($this->db->insert('ak_pinjaman_header',$data)){
							$id_head=$this->db->insert_id();
							//$this->db->trans_commit();
							
							//INSERT KE JURNAL AKUNTANSI PIUTANG LMI							
							$dataP = array(
								'bulan' => date('m'),
								'tahun' => date('Y'),
								'id_cab'=>$this->session->userdata('auth')->ID_CABANG,
								//'notransaksi' => $this->input->post('id'),
								'tanggal' => date('Y-m-d'),
								'idperkdebet' => $id_acc_piutang,
								'idperkkredit' => $this->input->post('accvia'),
								'penanda' => 'Piutang#'.$id_head,
								'nobuktiref' => '',
								'sumber_data' => 'kaskeluar',
								'keterangan' => 'Buku Pembantu Piutang '.$this->input->post('jnspeminjam').' atas nama '.$this->input->post('nama'),
								'jumlah' => $this->input->post('jumlah'),
								'jenis' => 'BKK',
								'uname' => $this->session->userdata('auth')->USERNAME,
								'waktuentri' => date('Y-m-d H:i:s')
							);
							$this->db->insert('ak_jurnal',$dataP);
							$this->db->trans_commit();
							$respon->status = 'success';
						} else {
							throw new Exception("gagal simpan");
						}


						
					}else{
											
						if ($this->db->where('ID',$state)->update('ak_pinjaman_header',$data)){
									$this->db->trans_commit();
									//get notrans from jurnal utk update nominal & idperkiraan debet-kredit (penanda =piutang#id_head, tgl jurnal, id cabang)
									$strGetJ="select notransaksi from ak_jurnal where id_cab=".$this->session->userdata('auth')->ID_CABANG." and penanda='Piutang#".$state."'";
									$rsGet=$this->db->query($strGetJ)->row();
									$dataPU = array(										
										'idperkdebet' => $id_acc_piutang,
										'idperkkredit' => $this->input->post('accvia'),	
										'keterangan' => 'Buku Pembantu Piutang '.$this->input->post('jnspeminjam').' atas nama '.$this->input->post('nama'),
										'jumlah' => $this->input->post('jumlah')
										
									);
									$this->db->where('notransaksi',$rsGet->notransaksi)->update('ak_jurnal',$dataPU);
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
					$isi.="error exception";
					$this->db->trans_rollback();
				}
				$respon->status = 'success';
			} else {
				$respon->status = 'error';
				$isi.="error validation_errors";
				$respon->errormsg = validation_errors();
				
			}
			$respon->data=$isi;	
			$respon->strGetJ=$strGetJ;	
			echo json_encode($respon);
			//exit;
		
		} 
	}

	public function delPinjaman(){
		$id=$this->input->post('idx');
		$res = $this->pinjaman_model->deletePinjaman($id);
		return $res;
	}
	
	
	public function getPinjaman(){
		$id = $this->input->post('id');
		$str="select * from ak_pinjaman_header where ID=$id";
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
	public function getPegawai(){
		$keyword = $this->input->post('term');
		$data['response'] = 'false';
		$str=" select * from v_pegawai P where  (P.NAMA LIKE '%{$keyword}%' or P.NIK LIKE '%{$keyword}%') and P.NIK not in (select distinct NIK from ak_pinjaman_header where status='Belum Lunas') order by P.NAMA";
		/*$query = $this->db->select()
			->where($where)			
			->order_by('P.NAMA')
			->get('v_pegawai P')
			->result();*/
		$query = $this->db->query($str)->result();

		if( ! empty($query) )
		{
			$data['response'] = 'true'; //Set response
			$data['message'] = array(); //Create array
			foreach( $query as $row )
			{
				$data['message'][] = array(
					'id'=>$row->NIK,
					'label' => $row->NIK.' - '.$row->NAMA,
					'value' => $row->NAMA,
					'cabang' => $row->NAMA_CABANG,
					'divisi' => $row->NAMA_DIV,
					'jabatan' => $row->NAMA_JAB,
					'jatahcuti' => $row->JATAHCUTI,
					''
				);
			}
		}
		echo json_encode($data);
	}
}
