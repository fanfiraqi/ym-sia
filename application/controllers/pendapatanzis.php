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
			$tanggal=$tanggal_setor=date('Y-m-d');		//tanggal jurnal	
			//$tanggal_setor=$this->input->post('tanggal_setor');		//tanggal setor	
			$bln=substr($tanggal, 5, 2);
			$thn=substr($tanggal, 0, 4);
			
			$id_bank=$this->input->post('id_bank');

			//pusat			
			$pusat_urai=$this->input->post('pusat_urai');
			$txtjml_debet=$this->input->post('txtjml_debet');
			$pusat_debet_coa=$this->input->post('pusat_debet_coa');	
			$pusat_kredit_rk=$this->input->post('pusat_kredit_rk');	

			//cabang
			$txtjml_kredit=$this->input->post('txtjml_kredit');
			$cabang_debet_rk=$this->input->post('cabang_debet_rk');	
			$cabang_kredit_coa=$this->input->post('cabang_kredit_coa');
			$cabang_urai=$this->input->post('cabang_urai');
			$id_cabang=$this->input->post('id_cabang');
			$resultstr="";
			
			
			for ($x=0; $x<sizeof($txtjml_debet);$x++){
				
					//jurnal cabang		
					$qzis="insert into ak_jurnal (bulan, tahun, id_cab, tanggal, idperkdebet, idperkkredit, nobuktiref, penanda, sumber_data, keterangan, jumlah, jenis, waktuentri, status_validasi)  values('$bln', '$thn', ".$id_cabang[$x].", '$tanggal', '".$cabang_debet_rk[$x]."',  '".$cabang_kredit_coa[$x]."',  '',  '', 'penghimpunan',  '".addslashes($cabang_urai[$x])."',  '".$txtjml_kredit[$x]."', 'BNK', '".date('Y-m-d H:i:s')."', 0)";
					
					$this->db->query($qzis);
					$this->db->trans_commit();

					//jurnal ke pusat
					$qzis_pusat="insert into ak_jurnal (bulan, tahun, id_cab, tanggal, idperkdebet, idperkkredit, nobuktiref, penanda, sumber_data, keterangan, jumlah, jenis, waktuentri, status_validasi)  values('$bln', '$thn', 1, '$tanggal', '".$pusat_debet_coa[$x]."',  '".$pusat_kredit_rk[$x]."',  '',  '', 'penghimpunan',  '".addslashes($pusat_urai[$x])."',  '".$txtjml_debet[$x]."', 'BKM', '".date('Y-m-d H:i:s')."', 0)";
					
					$this->db->query($qzis_pusat);	
					$this->db->trans_commit();

					//update status pos setoran zis=1
					$strUp="update bkm set sts_post_sia=1, tgl_post_sia='".date('Y-m-d')."', user_post_sia='".$this->session->userdata('auth')->id."' where cabang_id=".$id_cabang[$x]." and bank_id=".$id_bank[$x]; //where ?
					$this->donasi_db->query($strUp);				

					$this->donasi_db->trans_commit();
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
		//$html.='<tr><th style="width: 10%">No</th><th >Sisi</th><th >Jurnal</th><th >Nominal</th></tr>';
		$html.='<tr><th style="width: 10%">No</th><th >Sisi</th><th >Pusat</th><th >Cabang</th><th >Nominal</th><th >Keterangan</th></tr>';
		//PROSES
		$strCabang="SELECT DISTINCT  cabang_id FROM bkm
		WHERE (  DATE(DATE_FORMAT(store_date, '%Y-%m-%d'))  BETWEEN STR_TO_DATE('$tgl1', '%Y-%m-%d' ) AND STR_TO_DATE('$tgl2'  , '%Y-%m-%d' ))
	    AND `STATUS`='VALIDATED' AND sts_post_sia=0  ORDER BY bank_id ";
	    
		$qry_cabang=$this->donasi_db->query($strCabang)->result();
	//	echo "<tr><td colspan=5>$strCabang</td></tr>";
		if (sizeof($qry_cabang)>0){
		foreach($qry_cabang as $rescabang){

			$res_idcabang=$rescabang->cabang_id;
			
			
			//data cabang
			$rscab=$this->gate_db->query("select kota from mst_cabang where id_cabang=".$res_idcabang)->row();
			$nmcabang=$rscab->kota;
			$idcabang=$res_idcabang;
			$strBkm="SELECT DISTINCT bank_id FROM bkm WHERE (  DATE(DATE_FORMAT(store_date, '%Y-%m-%d'))  BETWEEN STR_TO_DATE('$tgl1', '%Y-%m-%d' ) AND STR_TO_DATE('$tgl2'  , '%Y-%m-%d' ))
			and `STATUS`='VALIDATED' AND sts_post_sia=0  and cabang_id=".$res_idcabang." ORDER BY bank_id ";
			$qryBank_cabang=$this->donasi_db->query($strBkm)->result();
			/*$html.="<tr>";
			$html.="<td colspan=6>Cabang : ".$res_idcabang." - ".$nmcabang."</td>";
			$html.="</tr>";*/
			$i=1;
		foreach($qryBank_cabang as $row){			
		    $akunKas=$this->common_model->comboGetAkunKasZIS();
			//data COA bank
			$sqlcab=$this->gate_db->query("select  idacc from mst_bank where id=".$row->bank_id);
			$bankacc="";
				$item_bank="COA Bank belum disetting, id bank".$row->bank_id;
				if ($sqlcab->num_rows()>=1){
					$rscab=$sqlcab->row();
					$bankacc=trim($rscab->idacc);
					if (!empty($bankacc)){
						$sqlbank=$this->db->query("select  * from ak_rekeningperkiraan where idacc='".$bankacc."'");
						if ($sqlbank->num_rows()>=1){
							$rsakun=$sqlbank->row();	//name, account_number, idacc
							$item_bank=$rsakun->idacc.' - '.$rsakun->nama;
						}
					}else{$item_bank="COA Bank belum disetting, id bank".$row->bank_id;}
				}
			$cab_2d=(strlen($res_idcabang)<=1?"0".$res_idcabang:$res_idcabang);
			//combo akun kredit penerimaan cabang
			$akunPenerimaan=$this->common_model->comboGetAkunZisCabang($res_idcabang);
		    $strDonasi="SELECT j.title, j.type, j.idacc_zis, d.*, (select bkm_id from donasi where id=d.donasi_id) bkm_id
		    FROM detail_donasi d, jenis_donasi j
			WHERE donasi_id IN (SELECT DISTINCT id FROM donasi WHERE bkm_id IN (
			    SELECT DISTINCT id FROM bkm WHERE `STATUS`='VALIDATED' AND sts_post_sia=0 
			    AND bank_id=".$row->bank_id." AND cabang_id= ".$res_idcabang." 
			    and (  DATE(DATE_FORMAT(store_date, '%Y-%m-%d'))  BETWEEN STR_TO_DATE('$tgl1', '%Y-%m-%d' ) AND STR_TO_DATE('$tgl2'  , '%Y-%m-%d' )) )) 
			    AND j.id=d.jenis_donasi_id  ";
			$qryDonasi=$this->donasi_db->query($strDonasi)->result();
			
			
			if ($row->bank_id!=0){  //CEK IF TUNAI (NON BANK)
		
			//data R/K cabang, dan R/K pusat (milik cabang)
			$rk_cabang="1.1.7.01.".$cab_2d;		//punya pusat
			$rk_pusat="2.1.3.".$cab_2d.".03";	//2.1.3.xx.03 R/K Pusat Amil punya cabang
			$rsakun_rk_cabang=$this->db->query("select  * from ak_rekeningperkiraan where idacc='".$rk_cabang."'")->row();	//name, account_number, idacc
			$rsakun_rk_pusat=$this->db->query("select  * from ak_rekeningperkiraan where idacc='".$rk_pusat."'")->row();	//name, account_number, idacc
			$nm_rk_cabang= (sizeof($rsakun_rk_cabang)<=0?"uncorrect":$rk_cabang.' - '.$rsakun_rk_cabang->nama);
			$nm_rk_pusat= (sizeof($rsakun_rk_pusat)<=0?"uncorrect":$rk_pusat.' - '.$rsakun_rk_pusat->nama);

		   /* $html.="<tr>";
			$html.="<td colspan=6> $strDonasi</td>";
			$html.="</tr>";*/
			foreach($qryDonasi as $rowDonasi){
					$coaKredit=str_replace('xx', $cab_2d, $rowDonasi->idacc_zis);
					//$coaKredit=$rowDonasi->idacc_zis;
					$pusat_urai="R/K Penerimaan ZIS dari cabang ".$nmcabang;
					$html.="<tr valign=top>";	
					$html.='<td ><input type="hidden" name="jns_post[]" id="jns_post[]" class="form-control"  value="RK">'.$i.'</td>';
					$html.='<input type="hidden" name="nobuktiref[]" id="nobuktiref[]" class="form-control"  value='.$rowDonasi->bkm_id.'></td>';
					$html.='<td >Debet<input type="hidden" name="id_cabang[]" id="id_cabang[]" class="form-control"  value='.$idcabang.'><input type="hidden" name="id_bank[]" id="id_bank[]" class="form-control"  value='.$row->bank_id.'>'."</td>";
					$html.='<td >'.form_dropdown('pusat_debet_coa[]',$akunKas,$bankacc,'id="pusat_debet_coa[]" class="form-control"').'</td>';	//Pusat
					$html.='<td >'.$nm_rk_pusat.'<input type="hidden" name="cabang_debet_rk[]" id="cabang_debet_rk[]"  value="'.$rk_pusat.'"></td>';	//cabang						
					
					$html.='<td ><input type="text" name="txtjml_debet[]" id="txtjml_debet[]" class="form-control"   onblur="blurObj(this)" onclick="clickObj(this)"  value='.$rowDonasi->paid.'>
					</td>';
					$html.='<td >'.form_textarea(array('name'=>'pusat_urai[]','id'=>'pusat_urai[]','class'=>'form-control', 'value'=>$pusat_urai)).'</td>';
					$html.="</tr>";

					//kredit input by tgl trans
					$cabang_urai="R/K Pusat Penerimaan ZIS cabang ".$nmcabang;
					$html.="<tr>";	
					$html.="<td ></td>";
					$html.="<td >Kredit</td>";
					$html.='<td >'.$nm_rk_cabang.'<input type="hidden" name="pusat_kredit_rk[]" id="pusat_kredit_rk[]"  value="'.$rk_cabang.'"></td>';	//cabang
					$html.='<td >'.$coaKredit.form_dropdown('cabang_kredit_coa[]',$akunPenerimaan,$coaKredit,'id="cabang_kredit_coa[]" class="form-control"').'</td>';					
					$html.='<td><input type="text" name="txtjml_kredit[]" id="txtjml_kredit[]" class="form-control"   onblur="blurObj(this)" onclick="clickObj(this)"  value='.$rowDonasi->paid.'></td>';
					$html.='<td >'.form_textarea(array('name'=>'cabang_urai[]','id'=>'cabang_urai[]','class'=>'form-control', 'value'=>$cabang_urai)).'</td>';
					$html.="</tr>";
					$i++;
				}
			}else { //TUNAI NON BANK 
			    
			    /*$strDonasi="SELECT j.title, j.type, j.idacc_zis, d.*, (select bkm_id from donasi where id=d.donasi_id) bkm_id
    		    FROM detail_donasi d, jenis_donasi j
    			WHERE donasi_id IN (SELECT DISTINCT id FROM donasi WHERE bkm_id IN (
    			    SELECT DISTINCT id FROM bkm WHERE `STATUS`='VALIDATED' AND sts_post_sia=0 
    			    AND bank_id=".$row->bank_id." AND cabang_id= ".$res_idcabang." 
    			    and (  DATE(DATE_FORMAT(store_date, '%Y-%m-%d'))  BETWEEN STR_TO_DATE('$tgl1', '%Y-%m-%d' ) AND STR_TO_DATE('$tgl2'  , '%Y-%m-%d' )) )) 
    			    AND j.id=d.jenis_donasi_id  ";
    			$qryDonasi=$this->donasi_db->query($strDonasi)->result();*/
			   $idaccBank="1.1.1.".$cab_2d.'.01';
		       $akunKas=$this->common_model->comboGetAkunKasZIS_Cabang($res_idcabang);
		       //$cek=$this->common_model->comboGetAkunKasZIS_Cabang($res_idcabang);
		       $cabang_urai="R/K Pusat Penerimaan ZIS cabang ".$nmcabang;
		       $pusat_urai= $cabang_urai;
    			/*$html.="<tr>";
    			$html.="<td colspan=6>$strDonasi</td>";
    			$html.="</tr>";*/
    			
			foreach($qryDonasi as $rowDonasi){
					$coaKredit=str_replace('xx', $cab_2d, $rowDonasi->idacc_zis);
					//$coaKredit=$rowDonasi->idacc_zis;
				
					$html.="<tr valign=top>";	
					$html.="<td >".$i."</td>";
					$html.="<td >Debet</td>";
					$html.='<td ><input type="hidden" name="id_bank[]" id="id_bank[]" class="form-control"  value='.$row->bank_id.'></td>';
					$html.='<input type="hidden" name="nobuktiref[]" id="nobuktiref[]" class="form-control"  value='.$rowDonasi->bkm_id.'></td>';
					$html.='<td >'.form_dropdown('cabang_debet[]',$akunKas,$idaccBank,'id="cabang_debet[]" class="form-control"').'</td>';	//cabang					
					
					$html.='<td ><input type="text" name="txtjml_debet[]" id="txtjml_debet[]" class="form-control"   onblur="blurObj(this)" onclick="clickObj(this)"  value='.$rowDonasi->paid.'>
					</td>';
					$html.='<td >'.form_textarea(array('name'=>'pusat_urai[]','id'=>'pusat_urai[]','class'=>'form-control', 'value'=>$pusat_urai)).'</td>';
					$html.="</tr>";

					//kredit input by tgl trans
					$cabang_urai="R/K Pusat Penerimaan ZIS cabang ".$nmcabang;
					$html.="<tr>";	
					$html.="<td ></td>";
					$html.="<td >Kredit</td>";
					$html.='<td ></td>';	
					$html.='<td >'.$coaKredit.form_dropdown('cabang_kredit_coa[]',$akunPenerimaan,$coaKredit,'id="cabang_kredit_coa[]" class="form-control"').'</td>';					
					$html.='<td><input type="text" name="txtjml_kredit[]" id="txtjml_kredit[]" class="form-control"   onblur="blurObj(this)" onclick="clickObj(this)"  value='.$rowDonasi->paid.'></td>';
					$html.='<td >'.form_textarea(array('name'=>'cabang_urai[]','id'=>'cabang_urai[]','class'=>'form-control', 'value'=>$cabang_urai)).'</td>';
					$html.="</tr>";
					$i++;
				}
			    
			}
		}
		}
		}else{
			$html.='<div class="row"><div class="col-md-12" style="text-align:center">Data tidak ditemukan </div></div>';
		}
		

	   $html.="</table><br>";
	   $html.='<input type="button" class="btn btn-primary" id="btsimpan" name="btsimpan"  value="Save Post" onclick="simpanForm()">';
	   $html.='<input type="hidden" id="tgljurnal" name="tgljurnal"  value="'.date('Y-m-d').'" >';
	   //$html.='<input type="hidden" id="idbkm" name="idbkm"  value="" >';
	   $html.="<br></form>";
		
		$respon["html"]=$html;
		$respon["status"]="success";
		
		
		echo json_encode($respon);
		
	}
	
}
