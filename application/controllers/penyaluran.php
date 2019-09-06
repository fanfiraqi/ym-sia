<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class penyaluran extends MY_App {
	var $branch = array();
	

	function __construct()
	{
		parent::__construct();
		//$this->load->model('rekening_model');
		$this->config->set_item('mymenu', 'menuTiga');
		
		$this->gate_db=$this->load->database('gate', TRUE);
		$this->finance_db=$this->load->database('keuangan', TRUE);
		$this->auth->authorize();

	}
	
	public function index()
	{	$this->config->set_item('mysubmenu', 'menuTigadelapan');
		$this->template->set('breadcrumbs','<li><a href="#">Jurnal</a></li><li><a href="#">Jurnal Pengeluaran Penyaluran Program</a></li>');
		$this->template->set('pagetitle','Posting Jurnal Pengeluaran Penyaluran Program');		
		$this->template->load('default','fjurnal/vpenyaluran',compact('str'));
		
	}
	public function updateJurnal(){
		if ($this->input->is_ajax_request()){
			$resultstr="";
			try {
			$respon = new StdClass();
			$tanggal=$this->input->post('tanggal');			
			$bln=substr($tanggal, 5, 2);
			$thn=substr($tanggal, 0, 4);

			//$pilih=$this->input->post('cbPilih');			
			$notransaksi=$this->input->post('notransaksi');			
			
			$cbZisDebet=$this->input->post('cbZisDebet');
			$cbZisKredit=$this->input->post('cbZisKredit');
			//get R/K pusat = akun milik cabang, ex:2.1.03.02.02
			//$idrk_pusat=(strlen($this->session->userdata('auth')->ID_CABANG)>1?'2.1.03.':'2.1.03.0').$this->session->userdata('auth')->ID_CABANG.'.02';		

			//r/k cabang akun milik pusat 1.1.08.01.
			//$idrk_cabang=(strlen($this->session->userdata('auth')->ID_CABANG)>1?'1.1.08.01.':'1.1.08.01.0').$this->session->userdata('auth')->ID_CABANG;

			$txtJmlZis=$this->input->post('txtJmlZis');
			$uraiZis=$this->input->post('uraiZis');	
			
			
			for ($x=0; $x<sizeof($notransaksi);$x++){ //kendala 2 baris jurnal pucat- cabang
				
				$qzis="update ak_jurnal  set   idperkdebet='".$cbZisDebet[$x]."', idperkkredit='".$cbZisKredit[$x]."', keterangan='".addslashes($uraiZis[$x])."', jumlah=".$txtJmlZis[$x].", waktuentri='".date('Y-m-d H:i:s')."' where notransaksi=".$notransaksi[$x];
				$this->db->query($qzis);
				$this->db->trans_commit();

			}
			$respon->status = 'success';
			$respon->data=$resultstr;			
			//$respon->cbPilih=$pilih;
		} catch (Exception $e) {
					$respon->status = 'error';
					$respon->errormsg = $e->getMessage();
					$this->db->trans_rollback();
		}
		
			
		echo json_encode($respon);
			
		}
	}
	public function saveJurnal(){
		if ($this->input->is_ajax_request()){
			$resultstr="";
			try {
			$respon = new StdClass();
			$tanggal=date('Y-m-d');		//tanggal jurnal	
			//$tanggal=$this->input->post('tanggal_jurnal');		//tanggal jurnal	
			//$tanggal_setor=$this->input->post('tanggal_setor');		//tanggal setor	
			$bln=substr($tanggal, 5, 2);
			$thn=substr($tanggal, 0, 4);

			
			
			//umum
			$a_idprogram=$this->input->post('a_idprogram');
			$a_idcabang=$this->input->post('a_idcabang');
			$a_txtjml=$this->input->post('a_txtjml');
			$a_cbDebet=$this->input->post('a_cbDebet');	//id_acc kas/bank pusat
			$a_cbKredit=$this->input->post('a_cbKredit');	//id_acc pendapatan cabang
			$a_urai=$this->input->post('a_urai');	//id_acc pendapatan cabang

			//khusus
			$b_idprogram=$this->input->post('b_idprogram');
			$b_idcabang=$this->input->post('b_idcabang');
			$b_txtJml=$this->input->post('b_txtJml');
			$pusat_debet_rk=$this->input->post('pusat_debet_rk');	
			$pusat_kredit_idacc=$this->input->post('pusat_kredit_idacc');	
			
			$c_txtJml=$this->input->post('c_txtJml');
			$cab_debet_idacc=$this->input->post('cab_debet_idacc');	
			$cab_kredit_rk=$this->input->post('cab_kredit_rk');
			$b_urai=$this->input->post('b_urai');
			$resultstr="";
					
			//umum
			for ($x=0; $x<sizeof($a_txtjml);$x++){	
					//jurnal cabang	 
					$qzis="insert into ak_jurnal (bulan, tahun, id_cab, tanggal, idperkdebet, idperkkredit, nobuktiref, penanda, sumber_data, keterangan, jumlah, jenis, waktuentri, status_validasi)  values('$bln', '$thn', ".$a_idcabang[$x].", '$tanggal', '".$a_cbDebet[$x]."',  '".$a_cbKredit[$x]."',  '',  '', 'penyaluran".sizeof($a_txtjml)."',  '".addslashes($a_urai[$x])."',  '".$a_txtjml[$x]."', 'BKK', '".date('Y-m-d H:i:s')."', 0)";
					
					$this->db->query($qzis);
					$this->db->trans_commit();

					//update status pos setoran zis=1
					$strUp="update penyaluran set sts_post_sia=1, tgl_post_sia='".date('Y-m-d')."', user_post_sia='".$this->session->userdata('auth')->id."' where id_cabang=".$a_idcabang[$x]." and id_program=".$a_idprogram[$x]; 
					$this->finance_db->query($strUp);		

					$this->finance_db->trans_commit();
					$resultstr.="umum".$qzis."<br>".$strUp;
			}
			
			//khusus
			for ($x=0; $x<sizeof($b_txtJml);$x++){
				
					//jurnal cabang		
					$qzis="insert into ak_jurnal (bulan, tahun, id_cab, tanggal, idperkdebet, idperkkredit, nobuktiref, penanda, sumber_data, keterangan, jumlah, jenis, waktuentri, status_validasi)  values('$bln', '$thn', ".$b_idcabang[$x].", '$tanggal', '".$cab_debet_idacc[$x]."',  '".$cab_kredit_rk[$x]."',  '',  '', 'penyaluran',  '".addslashes($b_urai[$x])."',  '".$b_txtJml[$x]."', 'BNK', '".date('Y-m-d H:i:s')."', 0)";
					
					$this->db->query($qzis);
					$this->db->trans_commit();

					//jurnal ke pusat
					$qzis_pusat="insert into ak_jurnal (bulan, tahun, id_cab, tanggal, idperkdebet, idperkkredit, nobuktiref, penanda, sumber_data, keterangan, jumlah, jenis, waktuentri, status_validasi)  values('$bln', '$thn', 1, '$tanggal', '".$pusat_debet_rk[$x]."',  '".$pusat_kredit_idacc[$x]."',  '',  '', 'penyaluran',  '".addslashes($b_urai[$x])."',  '".$c_txtJml[$x]."', 'BKK', '".date('Y-m-d H:i:s')."', 0)";
					
					$this->db->query($qzis_pusat);	
					$this->db->trans_commit();

					//update status pos setoran zis=1
					$strUp="update penyaluran set sts_post_sia=1, tgl_post_sia='".date('Y-m-d')."', user_post_sia='".$this->session->userdata('auth')->id."' where id_cabang=".$b_idcabang[$x]." and id_program=".$b_idprogram[$x]; //where ?
					$this->finance_db->query($strUp);				

					$this->finance_db->trans_commit();
					$resultstr.="khusus".$qzis."<br>".$strUp;

					
			}
				
			


		
			$respon->status = 'success';
			$respon->data=$resultstr;			
			//$respon->cbPilih=$pilih;
		} catch (Exception $e) {
					$respon->status = 'error';
					$respon->errormsg = $e->getMessage();
					$this->db->trans_rollback();
		}
		
			
		echo json_encode($respon);
			
		}
	}

	public function generateData(){
		$tgl1=$this->input->post("tgl1");
		$tgl2=$this->input->post("tgl2");
		//$pilihan=$this->input->post("pilihan");
		$pilihan="New";

		$pengunci=$this->db->query("select * from ak_pengunci where id=1")->row();
		$tanggalkunci='';
		$stsKunci=0;
		$akunKas=$this->common_model->comboGetAkunKasZIS();
		if (sizeof($pengunci)>0){
			$tanggalkunci=$pengunci->tanggal;
			if ($tanggalkunci>$tgl1 || $tanggalkunci>date('Y-m-d')){
				$stsKunci=1;
			}
		}
		
		$html="";
		$html.='<form name="frmpenyaluran" id="frmpenyaluran" class="form-horizontal">';
		$html.='<div class="alert alert-info alert-dismissable">';
       
		$html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>';
		//$html.="<b>Info : </b> <br>Tanggal BKM  : $tgl1 s.d $tgl2 <br>Tanggal Kunci jurnal :$tanggalkunci<br>Tanggal Jurnal ; ".date('Y-m-d')."<br><b>Status&nbsp; : &nbsp;$pilihan</b>";
		$html.="<b>Info : </b> <br>Filter Tanggal  : $tgl1 s.d $tgl2 <br>Tanggal Jurnal ; ".date('Y-m-d')."<br><b>Status&nbsp; : &nbsp;$pilihan</b>";
		$html.="<br> Jika ada data yang kurang lengkap maka data tidak dapat disimpan (misal COA, data cabang)";
		$html.="</div>";

		$html.='<div style="overflow:auto">';
		

		//PROSES
		//get penyaluran
		$strJurnal="SELECT  distinct id_cabang, id_program FROM penyaluran WHERE (tanggal BETWEEN '$tgl1'  AND '$tgl2') and sts_post_sia=0 order by id_cabang";
		
		$qryJurnal=$this->finance_db->query($strJurnal)->result();
		$cekCOA=0;
		if (sizeof($qryJurnal)>0){
		foreach($qryJurnal as $row){
			$rscab=$this->gate_db->query("select kota from mst_cabang where id_cabang=".$row->id_cabang)->row();
			$html.='<div class="control-group"><h5  >Cabang : '.$row->id_cabang.' - '.$rscab->kota.'</h5></div>';			

			if (!in_array($row->id_program, [1,2,6,7])){	
				$html.='<table class="table table-striped table-bordered table-hover">';	
				
				$html.='<tr><th style="width: 10%">No</th><th >Tgl Transaksi</th><th >Sisi</th><th >Jurnal</th><th >Nominal</th><th >Uraian</th></tr>';
				//detil  (debet)
				$strDebet="SELECT distinct id_program, tanggal, SUM(nominal) nominal, (select idacc from mst_program where id=penyaluran.id_program) idacc_program  FROM penyaluran where (tanggal BETWEEN '$tgl1'  AND '$tgl2') and id_program=".$row->id_program." and sts_post_sia=0 and id_cabang=".$row->id_cabang." group by id_program, tanggal";
				//$html.='<tr><th colspan=6>'.$strDebet.'</th></tr>';
				$qryDebet=$this->finance_db->query($strDebet)->result();
				$i=1; 
				$akunKasCabang=$this->common_model->comboGetAkunKasCabang($row->id_cabang);
				foreach($qryDebet as $rowDebet){
				    
					$id_cab=(strlen($row->id_cabang)<=1?"0".$row->id_cabang:$row->id_cabang);
					
					$idacc=str_replace('xx',$id_cab,$rowDebet->idacc_program);
					$rsakun=$this->db->query("select  * from ak_rekeningperkiraan where idacc='".$idacc."'")->row();	//name, account_number, idacc
					$qry=$this->finance_db->query("select  * from mst_program where id='".$row->id_program."'")->row();
					$urai="penyaluran Program  ".(sizeof($qry)<=0?"":$qry->nama_program)." cabang ".$rscab->kota;
					$html.="<tr>";	
					$html.="<td >".$i."</td>";
					$html.="<td >".$rowDebet->tanggal.'<input type="hidden" name="a_idcabang[]" id="a_idcabang[]" class="form-control"  value='.$row->id_cabang.'><input type="hidden" name="a_idprogram[]" id="a_idprogram[]" class="form-control"  value='.$row->id_program.'>'."</td>";
					$html.="<td >Debet</td>";				
					//$html.="<td >".$rowDebet->idacc_program."#".$idacc."</td>";
					
					$html.='<td >'.(sizeof($rsakun)>0?$rsakun->idacc.' - '.$rsakun->nama.'<input type="hidden" name="a_cbDebet[]" id="a_cbDebet[]" class="form-control"   value='.$idacc.'>':"COA penyaluran program ".$row->id_program." cabang ini belum di set").'</td>';
					//$html.='<td >'.$idacc.'</td>';
					$html.='<td rowspan=2><input type="text" name="a_txtjml[]" id="a_txtjml[]" class="form-control"   onblur="blurObj(this)" onclick="clickObj(this)"  value='.$rowDebet->nominal.'></td>';
					$html.='<td rowspan=2>'.form_textarea(array('name'=>'a_urai[]','id'=>'a_urai[]','class'=>'form-control', 'value'=>$urai)).'</td>';
					$html.="</tr>";
					
					//kredit input by tgl trans
					$html.="<tr>";	
					$html.="<td ></td>";
					$html.="<td >".$rowDebet->tanggal."</td>";
					$html.="<td >Kredit</td>";	
					$html.='<td >'.form_dropdown('a_cbKredit[]',$akunKasCabang,'','id="a_cbKredit[]" class="form-control"').'</td>';
					//$html.='<td><input type="text" name="txtJmlZisKredit[]" id="txtJmlZisKredit[]" class="form-control"   onblur="blurObj(this)" onclick="clickObj(this)"  value='.$rowDebet->nominal.'></td>';
					$html.="</tr>";
					if (sizeof($rsakun)<=0) {  $cekCOA++;}
					$i++;
				} 
				$html.="</table><br>";
				
			}else{	
				//genius, dutaguru, RK, bestari
				$html.='<table class="table table-striped table-bordered table-hover">';	
				$html.='<tr><th style="width: 10%">No</th><th >Tgl Transaksi</th><th >Sisi</th><th >Pusat</th><th >Cabang</th><th >Nominal</th><th >Keterangan</th></tr>';
				//detil  (debet)
				$strDebet="SELECT distinct id_program, tanggal, SUM(nominal) nominal, (select idacc from mst_program where id=penyaluran.id_program) idacc_program  FROM penyaluran where (tanggal BETWEEN '$tgl1'  AND '$tgl2') and id_program=".$row->id_program." and sts_post_sia=0 and id_cabang=".$row->id_cabang." group by id_program, tanggal";
				
				$qryDebet=$this->finance_db->query($strDebet)->result();
				$i=1; 
				foreach($qryDebet as $rowDebet){
					$id_cab=(strlen($row->id_cabang)<=1?"0".$row->id_cabang:$row->id_cabang);
					//$idacc=str_replace('xx',$id_cab,$rowDebet->idacc_program);
					$cab_debet_idacc="5.2.4.".$id_cab.".01";	//5.2.4.xx.01 Gaji SDM Penyaluran
					$cab_kredit_rk="2.1.3.".$id_cab.".04";		// R/K PUSAT TASHARUF

					$pusat_debet_rk="1.1.7.01.".$id_cab;	//R/K Cabang 
					$pusat_kredit_idacc=$akunKas;	//akunkas/bank

					$rsakun_cabD=$this->db->query("select  * from ak_rekeningperkiraan where idacc='".$cab_debet_idacc."'")->row();	//name, account_number, idacc
					$rsakun_cabK=$this->db->query("select  * from ak_rekeningperkiraan where idacc='".$cab_kredit_rk."'")->row();	//name, account_number, idacc
					$rsakun_pusatD=$this->db->query("select  * from ak_rekeningperkiraan where idacc='".$pusat_debet_rk."'")->row();	//name, account_number, idacc
					if (sizeof($rsakun_cabD)<=0) {  $cekCOA++;}
					if (sizeof($rsakun_cabK)<=0) {  $cekCOA++;}
					if (sizeof($rsakun_pusatD)<=0) {  $cekCOA++;}
					$qry=$this->finance_db->query("select  * from mst_program where id='".$row->id_program."'")->row();
					$urai="penyaluran Penggajian Program  ".(sizeof($qry)<=0?"":$qry->nama_program)." cabang ".$rscab->kota;

					$html.="<tr>";	
					$html.="<td >".$i."</td>";
					$html.="<td >".$rowDebet->tanggal.'<input type="hidden" name="b_idcabang[]" id="b_idcabang[]" class="form-control"  value='.$row->id_cabang.'><input type="hidden" name="b_idprogram[]" id="b_idprogram[]" class="form-control"  value='.$row->id_program.'>'."</td>";
					$html.="<td >Debet</td>";
					$html.='<td >'.(sizeof($rsakun_pusatD)>0?$rsakun_pusatD->idacc.' - '.$rsakun_pusatD->nama.'<input type="hidden" name="pusat_debet_rk[]" id="pusat_debet_rk[]"  value="'.$pusat_debet_rk.'">':"Data COA tidak Ketemu").'</td>';	//Pusat
					$html.='<td >'.(sizeof($rsakun_cabD)>0?$rsakun_cabD->idacc.' - '.$rsakun_cabD->nama.'<input type="hidden" name="cab_debet_idacc[]" id="cab_debet_idacc[]"  value="'.$cab_debet_idacc.'">':"Data COA tidak Ketemu").'</td>';	//cabang						
					
					$html.='<td ><input type="text" name="b_txtJml[]" id="b_txtJml[]" class="form-control"   onblur="blurObj(this)" onclick="clickObj(this)"  value='.$rowDebet->nominal.'>
					</td>';
					$html.='<td rowspan=2>'.form_textarea(array('name'=>'b_urai[]','id'=>'b_urai[]','class'=>'form-control', 'value'=>$urai)).'</td>';
					$html.="</tr>";

					//kredit input by tgl trans
					$html.="<tr>";	
					$html.="<td ></td>";
					$html.="<td >".$rowDebet->tanggal."</td>";
					$html.="<td >Kredit</td>";
					$html.='<td >'.form_dropdown('pusat_kredit_idacc[]',$akunKas,'','id="pusat_kredit_idacc[]" class="form-control"').'</td>';
					$html.='<td >'.(sizeof($rsakun_cabK)>0?$rsakun_cabD->idacc.' - '.$rsakun_cabK->nama.'<input type="hidden" name="cab_kredit_rk[]" id="cab_kredit_rk[]"  value="'.$cab_kredit_rk.'">':"Data COA tidak Ketemu").'</td>';	//cabang
					$html.='<td><input type="text" name="c_txtJml[]" id="c_txtJml[]" class="form-control"   onblur="blurObj(this)" onclick="clickObj(this)"  value='.$rowDebet->nominal.'></td>';
					//$html.="<td >Kredit</td>";	
					$html.="</tr>";

					$i++;
				} 
				$html.="</table><br>";

			}
			
			
			//kredit

				
			
	
		}

		

	   
	   $html.='<div class="row"><div class="col-md-12" style="text-align:center"><input type="button" class="btn btn-primary" id="btsimpan" name="btsimpan"  value="Save Post" onclick="simpanForm()" '.($cekCOA>0?"disabled":"").'>';
	   $html.='<input type="hidden" id="tgljurnal" name="tgljurnal"  value="'.date('Y-m-d').'" >';	  
	   $html.="</div></div><br></form>";
		}else{
			$html.='<div class="row"><div class="col-md-12" style="text-align:center">Data tidak ditemukan</div></div>';
		}
		$respon["html"]=$html;
		$respon["status"]="success";
		
		
		echo json_encode($respon);
		
	}
	
}
