<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class pendapatanzis extends MY_App {
	var $branch = array();
	

	function __construct()
	{
		parent::__construct();
		//$this->load->model('rekening_model');
		$this->config->set_item('mymenu', 'menuTiga');
		$this->config->set_item('mysubmenu', 'menuTigaSatu');
		$this->gate_db=$this->load->database('gate', TRUE);
		$this->donasi_db=$this->load->database('donasi', TRUE);
		$this->auth->authorize();

	}
	
	public function index()
	{
		$this->template->set('breadcrumbs','<li><a href="#">Jurnal</a></li><li><a href="#">Jurnal Pendapatan ZIS</a></li>');
		$this->template->set('pagetitle','Posting Jurnal Pendapatan ZIS Rutin dan Insidentil');		
		$this->template->load('default','fjurnal/vpendapatanzis',compact('str'));
		
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
			$tanggal=$this->input->post('tanggal_jurnal');		//tanggal jurnal	
			$tanggal_setor=$this->input->post('tanggal_setor');		//tanggal setor	
			$bln=substr($tanggal, 5, 2);
			$thn=substr($tanggal, 0, 4);

			
			//$pilih=$this->input->post('cbPilih');
			//$idtr=$this->input->post('idtr');
			$penanda=$this->input->post('penanda'); //tanggal zis dimasukkan penanda
			$penandaAmil=$this->input->post('penandaAmil'); //amil

			$nobuktiref=$this->input->post('nobuktiref');
			$cbZisDebet=$this->input->post('cbZisDebet');	//id_acc kas/bank pusat
			$cbZisKredit=$this->input->post('cbZisKredit');	//id_acc pendapatan cabang

			//amil
			$nobuktirefAmil=$this->input->post('nobuktiref');
			$cbZisDebetAmil=$this->input->post('cbZisDebetAmil');	//id_acc kas/bank pusat
			$cbZisKreditAmil=$this->input->post('cbZisKreditAmil');	//id_acc pendapatan cabang
			
			
			//get R/K pusat = akun milik cabang, ex:2.1.03.02.02
			$idrk_pusat=(strlen($this->session->userdata('auth')->ID_CABANG)>1?'2.1.03.':'2.1.03.0').$this->session->userdata('auth')->ID_CABANG.'.02';		

			//r/k cabang akun milik pusat 1.1.08.01.
			$idrk_cabang=(strlen($this->session->userdata('auth')->ID_CABANG)>1?'1.1.08.01.':'1.1.08.01.0').$this->session->userdata('auth')->ID_CABANG;	
			
			$txtJmlZis=$this->input->post('txtJmlZis');
			$uraiZis=$this->input->post('uraiZis');	

			//amil
			$txtJmlZisAmil=$this->input->post('txtJmlZisAmil');
			$uraiZisAmil=$this->input->post('uraiZisAmil');	
			
			
			for ($x=0; $x<sizeof($cbZisDebet);$x++){
				//$x=$pilih[$y];
				// cek pemilik cbZisDebet pemilik jurnal
				//get pemilik jurnal
				$pemilik=$this->db->query("select id_cab from ak_rekeningperkiraan where idacc='".$cbZisDebet[$x]."'")->row();
				
				if ($pemilik->id_cab <> 1){	//bukan pusat
					//jurnal cabang zis		 
					$qzis="insert into ak_jurnal (bulan, tahun, id_cab, tanggal, idperkdebet, idperkkredit, nobuktiref, penanda, sumber_data, keterangan, jumlah, jenis, waktuentri, status_validasi)  values('$bln', '$thn', ".$this->session->userdata('auth')->ID_CABANG.", '$tanggal', '".$cbZisDebet[$x]."',  '".$cbZisKredit[$x]."',  '".$nobuktiref[$x]."',  '".$penanda[$x]."', 'tsetoranzis',  '".addslashes($uraiZis[$x])."',  '".$txtJmlZis[$x]."', 'BKM', '".date('Y-m-d H:i:s')."', 0)";
					
					$this->db->query($qzis);
					
					//jurnal cabang bagian amil	
					$qzisAmil="insert into ak_jurnal (bulan, tahun, id_cab, tanggal, idperkdebet, idperkkredit, nobuktiref, penanda, sumber_data, keterangan, jumlah, jenis, waktuentri, status_validasi)  values('$bln', '$thn', ".$this->session->userdata('auth')->ID_CABANG.", '$tanggal', '".$cbZisDebetAmil[$x]."',  '".$cbZisKreditAmil[$x]."',  '".$nobuktirefAmil[$x]."',  '".$penandaAmil[$x]."', 'tsetoranzis',  '".addslashes($uraiZisAmil[$x])."',  '".$txtJmlZisAmil[$x]."', 'BKM', '".date('Y-m-d H:i:s')."', 0)";
					
					$this->db->query($qzisAmil);

					$this->db->trans_commit();
				}else{
					//jurnal cabang		
					$qzis="insert into ak_jurnal (bulan, tahun, id_cab, tanggal, idperkdebet, idperkkredit, nobuktiref, penanda, sumber_data, keterangan, jumlah, jenis, waktuentri, status_validasi)  values('$bln', '$thn', ".$this->session->userdata('auth')->ID_CABANG.", '$tanggal', '".$idrk_pusat."',  '".$cbZisKredit[$x]."',  '".$nobuktiref[$x]."',  '".$penanda[$x]."', 'tsetoranzis',  '".addslashes($uraiZis[$x])."',  '".$txtJmlZis[$x]."', 'BKM', '".date('Y-m-d H:i:s')."', 0)";
					
					$this->db->query($qzis);
					$this->db->trans_commit();

					//jurnal ke pusat
					$qzis_pusat="insert into ak_jurnal (bulan, tahun, id_cab, tanggal, idperkdebet, idperkkredit, nobuktiref, penanda, sumber_data, keterangan, jumlah, jenis, waktuentri, status_validasi)  values('$bln', '$thn', 1, '$tanggal', '".$cbZisDebet[$x]."',  '".$idrk_cabang."',  '".$nobuktiref[$x]."',  '".$penanda[$x]."', 'tsetoranzis',  '".addslashes($uraiZis[$x])."',  '".$txtJmlZis[$x]."', 'BKM', '".date('Y-m-d H:i:s')."', 0)";
					
					$this->db->query($qzis_pusat);

					//amil 
					//jurnal cabang		
					$qzisAmil="insert into ak_jurnal (bulan, tahun, id_cab, tanggal, idperkdebet, idperkkredit, nobuktiref, penanda, sumber_data, keterangan, jumlah, jenis, waktuentri, status_validasi)  values('$bln', '$thn', ".$this->session->userdata('auth')->ID_CABANG.", '$tanggal', '".$idrk_pusat."',  '".$cbZisKreditAmil[$x]."',  '".$nobuktirefAmil[$x]."',  '".$penandaAmil[$x]."', 'tsetoranzis',  '".addslashes($uraiZisAmil[$x])."',  '".$txtJmlZisAmil[$x]."', 'BKM', '".date('Y-m-d H:i:s')."', 0)";
					
					$this->db->query($qzisAmil);
					$this->db->trans_commit();

					//jurnal ke pusat
					$qzisamil_pusat="insert into ak_jurnal (bulan, tahun, id_cab, tanggal, idperkdebet, idperkkredit, nobuktiref, penanda, sumber_data, keterangan, jumlah, jenis, waktuentri, status_validasi)  values('$bln', '$thn', 1, '$tanggal', '".$cbZisDebetAmil[$x]."',  '".$idrk_cabang."',  '".$nobuktirefAmil[$x]."',  '".$penandaAmil[$x]."', 'tsetoranzis',  '".addslashes($uraiZisAmil[$x])."',  '".$txtJmlZis[$x]."', 'BKM', '".date('Y-m-d H:i:s')."', 0)";
					
					$this->db->query($qzisamil_pusat);

					$this->db->trans_commit();
				}
				$resultstr.=$qzis."<br>";
				
			}


			//update status pos setoran zis=1
			$strUp="update lazlmi.tsetoranzis_head set status_post_sia=1, tgl_post_sia='$tanggal' where status_entri='setor' and tgl_setor='$tanggal_setor' and id_cabang=".$this->session->userdata('auth')->ID_CABANG; //where ?
			$this->db->query($strUp);
			$this->db->trans_commit();

			$resultstr.=$strUp."<br>";

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
		if (sizeof($pengunci)>0){
			$tanggalkunci=$pengunci->tanggal;
			if ($tanggalkunci>$tgl1 || $tanggalkunci>date('Y-m-d')){
				$stsKunci=1;
			}
		}
		
		$html="";
		$html.='<form name="frmZis" id="frmZis">';
		$html.='<div class="alert alert-info alert-dismissable">';
       
		$html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>';
		//$html.="<b>Info : </b> <br>Tanggal BKM  : $tgl1 s.d $tgl2 <br>Tanggal Kunci jurnal :$tanggalkunci<br>Tanggal Jurnal ; ".date('Y-m-d')."<br><b>Status&nbsp; : &nbsp;$pilihan</b>";
		$html.="<b>Info : </b> <br>Tanggal BKM  : $tgl1 s.d $tgl2 <br>Tanggal Jurnal ; ".date('Y-m-d')."<br><b>Status&nbsp; : &nbsp;$pilihan</b>";
		$html.="</div>";

		$html.='<div style="overflow:auto"><table class="table table-striped table-bordered table-hover">';
		$html.='<tr><th style="width: 10%">No</th><th >Sisi</th><th >Jurnal</th><th >Nominal</th></tr>';

		//PROSES
		//donasi get cabang
		$strDonasi="SELECT  COUNT(id) donasi_id,  (SELECT cabang_id FROM donatur WHERE id=donasi.donatur_id) kode_cab FROM donasi WHERE bkm_id IN  (SELECT DISTINCT id FROM bkm WHERE( bkm_date BETWEEN '$tgl1'  AND '$tgl2') AND STATUS='VALIDATED' AND sts_post_sia=0) GROUP BY kode_cab";
		
		$qryDonasi=$this->donasi_db->query($strDonasi)->result();
		
		foreach($qryDonasi as $row){
			$rscab=$this->gate_db->query("select kota from mst_cabang where id_cabang=".$row->kode_cab)->row();
			
			$html.="<tr>";
			$html.="<td colspan=4>Cabang : ".$row->kode_cab." - ".$rscab->kota."</td>";
			$html.="</tr>";
					
			//detil bkm (debet)
			$strBkm="SELECT distinct bank_id, SUM(nominal) nominal, created  FROM bkm WHERE( bkm_date BETWEEN '$tgl1' AND '$tgl2') AND STATUS='VALIDATED' AND sts_post_sia=0 AND id IN (SELECT DISTINCT  bkm_id FROM donasi WHERE donatur_id IN (SELECT DISTINCT id FROM donatur WHERE cabang_id=".$row->kode_cab." )) GROUP BY bank_id";
			
			$qryBkm=$this->donasi_db->query($strBkm)->result();
			$i=1;
			foreach($qryBkm as $rowBkm){
				$sqlcab=$this->gate_db->query("select  idacc from mst_bank where id=".$rowBkm->bank_id);	//name, account_number, idacc
				//$rscab=($sqlcab->num_rows()>=1?$sqlcab->row():"");	//name, account_number, idacc
				$bankacc="";
				$item_bank="COA Bank belum disetting, id bank".$rowBkm->bank_id;
				if ($sqlcab->num_rows()>=1){
					$rscab=$sqlcab->row();
					$bankacc=trim($rscab->idacc);
					if (!empty($bankacc)){
					$rsakun=$this->db->query("select  * from ak_rekeningperkiraan where idacc='".$bankacc."'")->row();	//name, account_number, idacc
					$item_bank=$rsakun->idacc.' - '.$rsakun->nama;
					}else{$item_bank="COA Bank belum disetting, id bank".$rowBkm->bank_id;}
				}
				
				$html.="<tr>";	
				if ($i==1){
				$html.="<td >".$i."</td>";
				$html.="<td >Debet</td>";	
				}else{
					$html.="<td colspan=2>&nbsp;</td>";	
				}
				//$html.="<td >".$rowBkm->bkm_date."</td>";
				$html.='<td >'.$item_bank.'</td>';
				$html.='<td><input type="text" name="txtJmlZisDebet[]" id="txtJmlZisDebet[]" class="form-control"   onblur="blurObj(this)" onclick="clickObj(this)"  value='.$rowBkm->nominal.'></td>';
				$html.="</tr>";
				$i++;
			} 
			
			
			//detil_donasi
			$strDetil=" SELECT  jenis_donasi_id,created, (SELECT title FROM jenis_donasi WHERE id=detail_donasi.`jenis_donasi_id`) nmdonasi , (SELECT idacc_zis FROM jenis_donasi WHERE  id=detail_donasi.`jenis_donasi_id`) idacc_zis , (SELECT idacc_amil FROM jenis_donasi WHERE id=detail_donasi.`jenis_donasi_id`) idacc_amil ,(SELECT persen_amil FROM jenis_donasi WHERE id=detail_donasi.`jenis_donasi_id`) persen_amil , SUM(paid) jml  FROM detail_donasi  WHERE donasi_id IN ( SELECT DISTINCT id FROM donasi WHERE bkm_id IN (SELECT DISTINCT id FROM bkm WHERE( bkm_date BETWEEN '$tgl1'  AND '$tgl2') AND STATUS='VALIDATED' AND sts_post_sia=0)  AND donatur_id IN (SELECT id FROM donatur WHERE cabang_id=".$row->kode_cab.") ) GROUP BY jenis_donasi_id";
			$j=1;
			$qryDetil=$this->donasi_db->query($strDetil)->result();
			foreach($qryDetil as $rowDetil){
				$id_cab=(strlen($row->kode_cab)<=1?"0".$row->kode_cab:$row->kode_cab);
				$idacc_zis= str_replace('xx',$id_cab,$rowDetil->idacc_zis);
				$idacc_amil= str_replace('xx',$id_cab,$rowDetil->idacc_amil);
				$rsakun=$this->db->query("select  * from ak_rekeningperkiraan where idacc='".$idacc_zis."'")->row();
				$rsakunAmil=$this->db->query("select  * from ak_rekeningperkiraan where idacc='".$idacc_amil."'")->row();
				$persen_amil= $rowDetil->persen_amil;
				$jmlzis=$rowDetil->jml * ((100-$persen_amil)/100);
				$jmlamil=$rowDetil->jml * ($persen_amil/100);
				$html.="<tr>";	
				if ($j==1){
				$html.="<td >".$j."</td>";
				$html.="<td >Kredit</td>";	
				}else{
					$html.="<td colspan=2>&nbsp;</td>";	
				}
				//$html.="<td >".strftime("%Y-%m-%d",strtotime($rowDetil->created))."</td>";
				$html.='<td >'.$rsakun->idacc.' - '.$rsakun->nama.'</td>';
				$html.='<td><input type="text" name="txtJmlZisKredit[]" id="txtJmlZisKredit[]" class="form-control"   onblur="blurObj(this)" onclick="clickObj(this)"  value='.round($jmlzis,2).'></td>';
				$html.="</tr>";
				//amil
				$html.="<tr>";	
				$html.="<td colspan=2>&nbsp;</td>";	
				//$html.="<td >".strftime("%Y-%m-%d",strtotime($rowDetil->created))."</td>";
				$html.='<td >'.$rsakunAmil->idacc.' - '.$rsakunAmil->nama.'</td>';
				$html.='<td><input type="text" name="txtJmlZisKreditAmil[]" id="txtJmlZisKreditAmil[]" class="form-control"   onblur="blurObj(this)" onclick="clickObj(this)"  value='.round($jmlamil,2).'></td>';
				$html.="</tr>";
				$j++;
				
			}
			
		}

		

	   $html.="</table><br>";
	   $html.='<input type="button" class="btn btn-primary" id="btsimpan" name="btsimpan"  value="Save Post" onclick="simpanForm()">';
	   $html.='<input type="hidden" id="tgljurnal" name="tgljurnal"  value="'.date('Y-m-d').'" >';
	   $html.='<input type="hidden" id="idbkm" name="idbkm"  value="" >';
	   $html.="<br></form>";
		
		$respon["html"]=$html;
		$respon["status"]="success";
		
		
		echo json_encode($respon);
		
	}
	
}
