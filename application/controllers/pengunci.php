<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class pengunci extends MY_App {
	var $branch = array();
	function __construct()
	{
		parent::__construct();
		
		$this->config->set_item('mymenu', 'menuTiga');
		$this->config->set_item('mysubmenu', 'menuTigaEnam');
		$this->auth->authorize();
	}
	
	public function index()
	{	
		$res= $this->db->query("select * from ak_pengunci where id=1")->row();
		$data['res']=$res;
		$this->template->set('breadcrumbs','<li><a href="#">Pengaturan</a></li><li><a href="#">Seting Tanggal Pengunci Transaksi</a></li>');
		$this->template->set('pagetitle','Form Pengunci Transaksi Jurnal Akuntansi  ');		
		$this->template->load('default','setting/vpengunci',$data);
	}
	public function json(){
			$start = $this->input->get('iDisplayStart');
			$limit = $this->input->get('iDisplayLength');
			$sortby = $this->input->get('iSortCol_0');
			$srotdir = $this->input->get('sSortDir_0');
			
			$str = "SELECT * FROM ak_pengunci";
			
						
			if ( $_GET['sSearch'] != "" )
			{
				
				$str.= " WHERE pengunci LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' ";
				
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
					'penguncian'=>$row->penguncian,
					'tanggal'=>$row->tanggal,
					'pengunci'=>$row->pengunci,
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
	public function saveForm(){
		$isi="awal<br>";
		if ($this->input->is_ajax_request()){	
			
			$state=$this->input->post('state');
			
			$respon = new StdClass();
			
				
				$this->db->trans_begin();
				try {						
					
					//cek closing
					$str="select count(*) jml from ak_jurnal where status_validasi=0 and id_cab".( $this->input->post('wilayah')=="Pusat"?"<=1":">1");
					$sql=$this->db->query($str)->row();
					if ($sql->jml<=0) {
					$data = array(							
							'tanggal' => $this->input->post('tglKunci'),						
							'pengunci' => $this->input->post('pengunci'),						
							'penguncian' => $this->input->post('wilayah'),						
							'updatetime' => $this->input->post('waktu')						
						);			
					
							
						if ($this->db->where('id',( $this->input->post('wilayah')=="Pusat"?1:2))->update('ak_pengunci',$data)){
									$this->db->trans_commit();
									$respon->status = 'success';
						} else {
							throw new Exception("gagal simpan");
						}
					
					$isi.="Proses query<br>";
					}else{
						$respon->status = 'error';
						$respon->errormsg = "Ada transaksi jurnal yang belum di validasi di ".$this->input->post('wilayah');
					}
					$respon->str=$str;
				} catch (Exception $e) {
					$respon->status = 'error';
					$respon->errormsg = $e->getMessage();
					$this->db->trans_rollback();
				}
				
			
			//$respon->data=$isi;	
			echo json_encode($respon);
			//exit;
		
		} 
	}

	public function getParams(){
		$id = $this->input->post('id');
		$str="select * from ak_pengunci where id=".$id;
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
