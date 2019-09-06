<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class penggajian extends MY_App {
	var $branch = array();
	

	function __construct()
	{
		parent::__construct();
		//$this->load->model('rekening_model');
		$this->config->set_item('mymenu', 'menuTiga');
		
		$this->gate_db=$this->load->database('gate', TRUE);
		$this->payroll_db=$this->load->database('hrd_gaji', TRUE);
		$this->auth->authorize();

	}
	
	public function index()
	{	$this->config->set_item('mysubmenu', 'menuTigaSembilan');
		/*$options = array(
                  'laz'  => 'Staff Dalam LAZ ',
                  'tasharuf'  => 'Staff Dalam Tasharuf ',
                  'zisco_transport'    => 'Zisco Transport',
                  'zisco_bonus'    => 'Zisco Bonus',                  
                  'kacab_bonus'    => 'Kacab Bonus',
				  'non_sistem'    => 'Non Sistem',
				  'thr_laz'    => 'THR Staff Dalam LAZ',
				  'thr_tasharuf'    => 'THR Staff Dalam Tasharuf',
				  'thr_zisco'    => 'THR Zisco',
				  'thr_non_sistem'    => 'THR Non Sistem'
                );*/
		$options = array(
                  'bulanan'  => 'Gaji Bulanan',
                  'thr'  => 'THR'
                );
		$data['arrBulan'] = $this->arrBulan2;
		$data['arrIntBln'] = $this->arrIntBln;
		$data['arrThn'] = $this->getYearArr();
		$data['jenis'] = $options;
		$this->template->set('breadcrumbs','<li><a href="#">Jurnal</a></li><li><a href="#">Jurnal Penggajian</a></li>');
		$this->template->set('pagetitle','Posting Jurnal Penggajian');		
		$this->template->load('default','fjurnal/vpenggajian',$data);
		
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
			$tanggal=$this->input->post('tgljurnal');		//tanggal jurnal	
			//$tanggal_setor=$this->input->post('tanggal_setor');		//tanggal setor	
			$bln=substr($tanggal, 5, 2);
			$thn=substr($tanggal, 0, 4);

						
					
			$jenis=$this->input->post('jenis');
			$nobuktiref=$this->input->post('nobuktiref');
			$cbDebet=$this->input->post('cbDebet');	
			$cbKredit=$this->input->post('cbKredit');		
			$id_validasi=$this->input->post('id_validasi');		
			
			
			
			$txtJml=$this->input->post('txtJml');
			$txtUrai=$this->input->post('txtUrai');	

			$resultstr="";		
			for ($x=0; $x<sizeof($cbDebet);$x++){
				
					//jurnal cabang zis		 
					$qzis="insert into ak_jurnal (bulan, tahun, id_cab, tanggal, idperkdebet, idperkkredit, nobuktiref, penanda, sumber_data, keterangan, jumlah, jenis, waktuentri, status_validasi)  values('$bln', '$thn', 1, '$tanggal', '".$cbDebet[$x]."',  '".$cbKredit[$x]."',  '".$nobuktiref[$x]."',  '".$nobuktiref[$x]."', 'penggajian',  '".addslashes($txtUrai[$x])."',  '".$txtJml[$x]."', 'BKK', '".date('Y-m-d H:i:s')."', 0)";
					
					
					$this->db->query($qzis);
					$this->db->trans_commit();

					//update status pos setoran zis=1
					$strUp="update ".($jenis[$x]=="thr"?"thr_validasi":"gaji_validasi")." set sts_post_sia=1, tgl_post_sia='".date('Y-m-d')."', post_sia_by='".$this->session->userdata('auth')->id."' where id=".$id_validasi[$x]; //where ?
					$this->payroll_db->query($strUp);				

					$this->payroll_db->trans_commit();
					$resultstr.=$qzis."<br>".$strUp;
				
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
		$arrTable= array(
                  'laz'  => 'gaji_staff',
                  'tasharuf'  => 'gaji_staff',
                  'zisco_transport'    => 'gaji_zisco_transport',
                  'zisco_bonus'    => 'gaji_zisco_bonus',                  
                  'kacab_bonus'    => 'gaji_kacab_bonus',
				  'non_sistem'    => 'gaji_non_sistem',
				  'thr_laz'    => 'thr_staff',
				  'thr_tasharuf'    => 'thr_staff',
				  'thr_zisco'    => 'thr_zisco',
				  'thr_non_sistem'    => 'thr_nonsistem'
                );
		
		$jenis=$this->input->post("jenis");
		$thn=$this->input->post("thn");
		$akunBank=$this->common_model->comboGetAkunBankGaji();
		$akunBiaya=$this->common_model->comboGetAkunBiayaGaji();
		$akunGaji='5.5.1.01.01';
		$str="";$bln="";
		if ($jenis=="bulanan"){
			$bln=$this->input->post("bln");
			$str="select * from gaji_validasi where tahun='$thn' and bulan='$bln' and validasi=1 order by id";
		}else{//thr
			$str="select * from thr_validasi where tahun='$thn' and validasi=1 order by id";
			$bln=date('m');
		}
		$sql=$this->payroll_db->query($str);
		//$pilihan=$this->input->post("pilihan");



		$pilihan="New";

		$pengunci=$this->db->query("select * from ak_pengunci where id=1")->row();
		$tanggalkunci='';
		$stsKunci=0;
		//$akunKas=$this->common_model->comboGetAkunKasZIS();
		/*if (sizeof($pengunci)>0){
			$tanggalkunci=$pengunci->tanggal;
			if ($tanggalkunci>$tgl1 || $tanggalkunci>date('Y-m-d')){
				$stsKunci=1;
			}
		}*/
		
		$html="";
		$html.='<form name="frmgaji" id="frmgaji">';
		$html.='<div class="alert alert-info alert-dismissable">';
       
		$html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>';
		$html.="<b>Info : </b> <br>Tanggal Jurnal ; ".date('Y-m-d')."<br>";
		$html.="Periode Gaji : ".$thn.$bln;
		$html.='<input type="hidden" value="'.$bln.'" name="bln" id="bln" >';
		$html.='<input type="hidden" value="'.$thn.'" name="thn" id="thn" >';
		$html.='</div>';

		$html.='<div style="overflow:auto"><table class="table table-striped table-bordered table-hover">';
		$html.='<tr><th style="width: 10%">No</th><th >Jenis Gaji</th><th >Wilayah</th><th >Jurnal</th><th >Nominal</th><th >Uraian</th></tr>';
		if ($sql->num_rows<=0){
			$html.='<tr><td colspan=5>Data Penggajian Belum ada</td></tr>';
		}else{
			$rsGaji=$sql->result();
			$i=1;
			foreach($rsGaji as $rs){
				$html.='<tr VALIGN=top>';
				$html.='<td >'.$i.'</td>';
				$html.='<td >'.$rs->JENIS.'</td>';
				$html.='<td >';
					if ($rs->JENIS=="laz"||$rs->JENIS=="tasharuf"){
						$html.=$rs->WILAYAH;
					}elseif ($rs->JENIS=="zisco_bonus"){
						
						if ($rs->WILAYAH!="" || !empty($rs->WILAYAH)){
							$rsmstcab=$this->gate_db->query("select kota from mst_cabang where id_cabang=".$rs->WILAYAH)->row();
							$html.=$rsmstcab->kota;
						}else{
							$html.="Semua Cabang";
						}
						
					}else{
						$html.="Semua Cabang";
					}
				$html.='</td>';
				$html.='<td >Debet : '.form_dropdown('cbDebet[]',$akunBiaya,$akunGaji,'id="cbDebet[]" class="form-control"');
				$html.='Kredit : '.form_dropdown('cbKredit[]',$akunBank,'','id="cbKredit[]" class="form-control"');
				$html.='</td>';
				$strGaji="SELECT ifnull(SUM(total ),0) total FROM ".$arrTable[$rs->JENIS]." where id_validasi=".$rs->ID;
				$rsTotGaji=$this->payroll_db->query($strGaji)->row();
				$jmlGaji=$rsTotGaji->total;
				$html.='<td ><input type="text" name="txtJml[]" id="txtJml[]" class="form-control"  onblur="blurObj(this)" onclick="clickObj(this)" value="'.$jmlGaji.'"></td>';
				$html.='<td><textarea cols="35" rows="3" name="txtUrai[]" id="txtUrai[]" class="form-control">Penggajian '.$rs->JENIS.' Periode : '.$thn.$bln.'</textarea>';
				$html.='<input type="hidden" value="'.$thn.$bln.'_'.$rs->JENIS.'_'.$rs->WILAYAH.'" name="nobuktiref[]" id="nobuktiref[]" >';
				$html.='<input type="hidden" value="'.$jenis.'" name="jenis[]" id="jenis[]" >';
				$html.='<input type="hidden" value="'.$rs->ID.'" name="id_validasi[]" id="id_validasi[]" >';
				$html.='</td >';
				$html.='</tr>';
				$i++;
			}
		}
						
			
	
		

		

	   $html.="</table><br>";
	   $html.='<input type="button" class="btn btn-primary" id="btsimpan" name="btsimpan"  value="Save Post" onclick="simpanForm()">';
	   $html.='<input type="hidden" id="tgljurnal" name="tgljurnal"  value="'.date('Y-m-d').'" >';
	   
	   $html.="<br></form>";
		
		$respon["html"]=$html;
		$respon["status"]="success";
		
		
		echo json_encode($respon);
		
	}
	
}
