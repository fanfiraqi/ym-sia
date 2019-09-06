<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class setSaldo extends MY_App {
	var $branch = array();
	function __construct()
	{
		parent::__construct();
		//$this->load->model('cabang_model');
		$this->gate_db=$this->load->database('gate', TRUE);
		$this->config->set_item('mymenu', 'menuSatu');
		$this->config->set_item('mysubmenu', 'menuSatuDelapan');
		$this->auth->authorize();
	}
	
	public function index()
	{	
		$data['arrBulan'] = $this->arrBulan2;
		$data['arrThn'] = $this->getYearArr();		
		$level1=$this->common_model->comboGetLevel1(' [ Pilih Akun Induk/Level 1 ] ');
		$level2=$this->common_model->comboGetLevel2(' [ Pilih Akun Level 2 ] ');	
		$level3=$this->common_model->comboGetLevel3(' [ Pilih Akun Level 3 ] ');
		$data['level1'] = $level1;
		$data['level2'] = $level2;
		$data['level3'] = $level3;
		$this->template->set('breadcrumbs','<li><a href="#">Pengaturan</a></li><li><a href="#">Set Saldo Awal Neraca</a></li>');
		$this->template->set('pagetitle','Halaman Setting Saldo Awal Rekening');		
		$this->template->load('default','fmaster/vsetsaldo',$data);
	}
	
	
	
	public function json_data(){
		//if ($this->input->is_ajax_request()){
		
			$start = $this->input->get('iDisplayStart');
			$limit = $this->input->get('iDisplayLength');
			$sortby = $this->input->get('iSortCol_0');
			$srotdir = $this->input->get('sSortDir_0');
			
			//$level1=(!empty($_GET['lvl1'])?$_GET['lvl1']:'1-0000');
			//$level2=(!empty($_GET['lvl2'])?$_GET['lvl2']:'1-1000');
			$level3=(!empty($_GET['lvl3'])?$_GET['lvl3']:'1-1100');
			$cab=(!empty($_GET['cab'])?$_GET['cab']:0);

			$str = "select * from ak_rekeningperkiraan where  id_cab=$cab and idparent='$level3' and status=1 ";
			
						
			/*if ( $_GET['sSearch'] != "" )
			{
				
				$str.= " and idacc like '%".mysql_real_escape_string( $_GET['sSearch'] )."%' or nama like '%".mysql_real_escape_string( $_GET['sSearch'] )."%' ";
				
			}*/
			
			
			if ( isset( $_GET['iSortCol_0'] ) )
			{
				$str .= " ORDER BY idacc, ".$_GET['mDataProp_'.$_GET['iSortCol_0']]." ".$_GET['sSortDir_0'];
			}else{
				$str .=" ORDER BY  idacc";
			}
			
			
			$strfilter = $str;
			//if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
			//{
				//$strfilter .= " LIMIT ". mysql_real_escape_string( $_GET['iDisplayStart'] ) .", ". mysql_real_escape_string( $_GET['iDisplayLength'] );
			//}
			
			$iFilteredTotal = $this->db->query($str)->num_rows();
			if ($iFilteredTotal>1){	//if level 4 is lowest =>pendapatan zis
				if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
				{
					$strfilter .= " LIMIT ". mysql_real_escape_string( $_GET['iDisplayStart'] ) .", ". mysql_real_escape_string( $_GET['iDisplayLength'] );
				}
			}
			$iTotal = $iFilteredTotal;
			$query = $this->db->query($strfilter)->result();
			
			$aaData = array();
			foreach($query as $row){
				//cek level 4, jika ada child tampilkan child + text box, jika paling kecil level 4 misal pendapatan maka lgs tampil texbox
				$strCek="select count(*) scek from ak_rekeningperkiraan where idparent='".$row->idacc."' order by idacc";
				$cek=$this->db->query($strCek)->row();
				$rsname=$this->gate_db->query("SELECT ifnull(kota, 'Pusat') as nmkota FROM mst_cabang WHERE id_cabang=".$row->id_cab)->row();

				$aaData[] = array(
						'IDACC'=>$row->idacc,
						'NAMA'=>$row->nama,
						'LEVEL'=>$row->level,
						'IDPARENT'=>$row->idparent,
						'ID_CAB'=>$rsname->nmkota,
						'SALDO'=>($cek->scek>0?'Parent Lvl5':'<input type="hidden" name="txtidacc[]" id="txtidacc[]" value="'.$row->idacc.'"><input type="text" name="txtsaldo[]" id="txtsaldo[]" value="'.$row->initval.'">')
					);
				if ($cek->scek>0){
					$sLevel5="select * from ak_rekeningperkiraan where idparent='".$row->idacc."'";
					$sLevel5Limit=$sLevel5;
					if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
					{
						$sLevel5Limit .= " LIMIT ". mysql_real_escape_string( $_GET['iDisplayStart'] ) .", ". mysql_real_escape_string( $_GET['iDisplayLength'] );
					}
					$queryL5 = $this->db->query($sLevel5Limit)->result();
					$iFilteredTotal = ($this->db->query($sLevel5)->num_rows())+1;
					$iTotal = $iFilteredTotal;
					foreach($queryL5 as $row5){
						$aaData[] = array(
						'IDACC'=>$row5->idacc,
						'NAMA'=>"&nbsp;&nbsp;&nbsp;".$row5->nama,
						'LEVEL'=>$row5->level,
						'IDPARENT'=>$row5->idparent,
						'ID_CAB'=>$rsname->nmkota,
						'SALDO'=>'<input type="hidden" name="txtidacc[]" id="txtidacc[]" value="'.$row5->idacc.'"><input type="text" name="txtsaldo[]" id="txtsaldo[]" value="'.$row5->initval.'">'
					);
					}
				}
			}
			
			$output = array(
				"squery"=>$strfilter,
				"sEcho" => intval($_GET['sEcho']),
				"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => $aaData
			);
			echo json_encode($output);
		//}
	}	
	
	
	
	public function saveSaldo(){
		$teks="awal<br>";
		if ($this->input->is_ajax_request()){	
			$respon = new StdClass();			
				$this->db->trans_begin();
				try {						
					$arrIdacc=$this->input->post('txtidacc');
					$saldo=$this->input->post('txtsaldo');
					for($i=0; $i<sizeof($arrIdacc); $i++){
						$strUpdate="update ak_rekeningperkiraan set initval=".$saldo[$i]." where idacc='".$arrIdacc[$i]."'";
						$this->db->query($strUpdate);
						$teks.=$strUpdate."<br>";
						
					}
					$this->db->trans_commit();
					$respon->status = 'success';			
					$respon->data = $arrIdacc;			
				} catch (Exception $e) {
					$respon->status = 'error';
					$respon->errormsg = $e->getMessage();
					$this->db->trans_rollback();
				}
				//$respon->status = 'success';
			
			$respon->teks=$teks;	
			echo json_encode($respon);
			//exit;
		
		} 
	}

	public function ubahStatus(){
		$id=$this->input->post('idx');
		$sts=$this->input->post('status');
		$res = $this->cabang_model->ubahStatus($id, $sts);
		return $res;
	}
	
}
