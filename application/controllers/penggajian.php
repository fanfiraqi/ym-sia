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
		$options = array(
                  'laz'  => 'Staff Dalam LAZ ',
                  'tasharuf'  => 'Staff Dalam Tasharuf ',
                  'zisco_transport'    => 'Zisco Transport',
                  'zisco_bonus'    => 'Zisco Bonus',                  
                  'kacab_bonus'    => 'Kacab Bonus',
				  'non_sistem'    => 'Non Sistem',
				  'thr_laz'    => 'THR Staff Dalam LAZ',
				  'thr_tasharuf'    => 'THR Staff Dalam Tasharuf',
				  'thr_zisco_transport'    => 'THR Zisco',
				  'thr_non_sistem'    => 'THR Non Sistem'
                );
		/*$options = array(
                  'bulanan'  => 'Gaji Bulanan',
                  'thr'  => 'THR'
                );*/
		$data['arrBulan'] = $this->arrBulan2;
		$data['arrIntBln'] = $this->arrIntBln;
		$data['arrThn'] = $this->getYearArr();
		$data['jenis'] = $options;
		$this->template->set('breadcrumbs','<li><a href="#">Jurnal</a></li><li><a href="#">Jurnal Penggajian</a></li>');
		$this->template->set('pagetitle','Posting Jurnal Penggajian');		
		$this->template->load('default','fjurnal/vpenggajian',$data);
		
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

			$jmlrow=$this->input->post('jmlrow');	
			$jenis=$this->input->post('jenis');	
			
	
					
			
			$resultstr="";	

			for ($x=1; $x<=$jmlrow;$x++){
				//pusat			
				$pusat_urai=$this->input->post('pusat_urai_'.$x);
				$txtjml_debet=$this->input->post('txtjml_debet_'.$x);
				$pusat_debet_rk=$this->input->post('pusat_debet_rk_'.$x);	
				$pusat_kredit_coa=$this->input->post('pusat_kredit_coa_'.$x);	
				$nobuktiref=$this->input->post('nobuktiref_'.$x);	
			
				//cabang
				$txtjml_kredit=$this->input->post('txtjml_kredit_'.$x);
				$cabang_debet_coa=$this->input->post('cabang_debet_coa_'.$x);	
				$cabang_kredit_rk=$this->input->post('cabang_kredit_rk_'.$x);
				$cabang_urai=$this->input->post('cabang_urai_'.$x);
				$id_cabang=$this->input->post('id_cabang_'.$x);
				$id_validasi=$this->input->post('id_validasi_'.$x);

					if (in_array($jenis, ['laz','tasharuf','thr_laz','thr_tasharuf' ]) && $id_cabang=="1"){
						//jurnal ke pusat
						$qzis_pusat="insert into ak_jurnal (bulan, tahun, id_cab, tanggal, idperkdebet, idperkkredit, nobuktiref, penanda,penanda_kredit, sumber_data, keterangan, jumlah, jenis, waktuentri, status_validasi)  values('$bln', '$thn', 1, '$tanggal', '".$pusat_debet_rk."',  '".$pusat_kredit_coa."',   '".$nobuktiref."',  '".addslashes($pusat_urai)."','".addslashes($pusat_urai)."', 'penggajian',  '".addslashes($pusat_urai)."',  '".$txtjml_debet."', 'BKK', '".date('Y-m-d H:i:s')."', 0)";
						
						$this->db->query($qzis_pusat);	
						$this->db->trans_commit();

						//update status pos setoran zis=1
						$strUp="update ".(substr($jenis, 1,3)=="thr"?"thr_validasi":"gaji_validasi")." set sts_post_sia=1, tgl_post_sia='".date('Y-m-d')."', post_sia_by='".$this->session->userdata('auth')->id."' where id=".$id_validasi; //where ?
						$this->payroll_db->query($strUp);				

						$this->payroll_db->trans_commit();
						$resultstr.=$x."masuk pusat(las,tas) ".$qzis_pusat."#".$strUp."<br>";
					}else{
					//jurnal cabang		
					
					if ($jenis=="zisco_bonus"){
					//zisco_bonus
						$thnzisco=$this->input->post('thn');
						$blnzisco=$this->input->post('bln');
						$dd=date('t',strtotime($thnzisco.'-'.$blnzisco.'-15'));
						$tanggal=$thnzisco.'-'.$blnzisco.'-'.$dd;
					}
					
					$qzis="insert into ak_jurnal (bulan, tahun, id_cab, tanggal, idperkdebet, idperkkredit, nobuktiref, penanda, penanda_kredit, sumber_data, keterangan, jumlah, jenis, waktuentri, status_validasi)  values('$bln', '$thn', ".$id_cabang.", '$tanggal', '".$cabang_debet_coa."',  '".$cabang_kredit_rk."',  '".$nobuktiref."',  '".addslashes($cabang_urai)."', '".addslashes($cabang_urai)."',  'penggajian',  '".addslashes($cabang_urai)."',  '".$txtjml_kredit."', 'BNK', '".date('Y-m-d H:i:s')."', 0)";
					
					$this->db->query($qzis);
					$this->db->trans_commit();
					
					//jurnal ke pusat
					$qzis_pusat="insert into ak_jurnal (bulan, tahun, id_cab, tanggal, idperkdebet, idperkkredit, nobuktiref, penanda,penanda_kredit, sumber_data, keterangan, jumlah, jenis, waktuentri, status_validasi)  values('$bln', '$thn', 1, '$tanggal', '".$pusat_debet_rk."',  '".$pusat_kredit_coa."',   '".$nobuktiref."',  '".addslashes($pusat_urai)."','".addslashes($pusat_urai)."', 'penggajian',  '".addslashes($pusat_urai)."',  '".$txtjml_debet."', 'BKK', '".date('Y-m-d H:i:s')."', 0)";
					
					$this->db->query($qzis_pusat);	
					$this->db->trans_commit();

					//update status pos setoran zis=1
					$strUp="update ".(substr($jenis, 1,3)=="thr"?"thr_validasi":"gaji_validasi")." set sts_post_sia=1, tgl_post_sia='".date('Y-m-d')."', post_sia_by='".$this->session->userdata('auth')->id."' where id=".$id_validasi; //where ?
					$this->payroll_db->query($strUp);				

					$this->payroll_db->trans_commit();
					$resultstr.="<br>".$x."pusat".$qzis_pusat."<br>cabang#".$qzis."<br>".$strUp;
					}

					
			}
			
			$respon->status = 'success';
			$respon->data=$resultstr."<br>".$jmlrow;			
			
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
				  'thr_zisco_transport'    => 'thr_zisco',
				  'thr_non_sistem'    => 'thr_nonsistem'
                );
		
		$jenis=$this->input->post("jenis");
		$thn=$this->input->post("thn");
		$akunBankGaji=$this->common_model->comboGetAkunBankGaji();
		$akunBiayaGaji=$this->common_model->comboGetAkunBiayaGaji();
		
		$str="";$bln="";
		if (substr($jenis, 1,3)=="thr"){
			$arrex=explode('_', $jenis);
			$str="select * from thr_validasi where tahun='$thn'  and jenis='".$arrex[1]."' and validasi=1 and sts_post_sia=0 order by id";
			$bln=date('m');			
		}else{//thr
			$bln=$this->input->post("bln");
			$str="select * from gaji_validasi where tahun='$thn' and bulan='$bln' and jenis='".$jenis."' and validasi=1 and sts_post_sia=0 order by id";			
		}
		$sql=$this->payroll_db->query($str);
		//$pilihan=$this->input->post("pilihan");



		$pilihan="New";

		$pengunci=$this->db->query("select * from ak_pengunci where id=1")->row();
		$tanggalkunci='';
		$stsKunci=0;
		$i=1;
		
		$html="";
		$html.='<form name="frmgaji" id="frmgaji">';
		$html.='<div class="alert alert-info alert-dismissable">';       
		$html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>';
		$html.="<b>Info : </b> <br>Tanggal Jurnal ; ".date('Y-m-d')."<br>";
		$html.="Periode Gaji : ".$thn.$bln;
		$html.="<br> Jenis Gaji : ".$jenis;
		$html.='<input type="hidden" value="'.$bln.'" name="bln" id="bln" >';
		$html.='<input type="hidden" value="'.$thn.'" name="thn" id="thn" >';
		$html.='</div>';

		$html.='<div style="overflow:auto"><table class="table table-striped table-bordered table-hover">';
		$html.='<tr><th style="width: 10%">No</th><th >Sisi</th><th >Pusat</th><th >Cabang</th><th >Nominal</th><th >Uraian</th></tr>';
	//	$html.="<tr><td colspan=7>".$str."</tr>";
		if ($sql->num_rows<=0){
			$html.='<tr><td colspan=7>Data Penggajian Belum ada atau belum divalidasi atau sudah diposting Jurnal</td></tr>';
		}else{
			
			$rsGaji=$sql->result();	//jml row bisa 2 bisa 1
			$i=1;
			if ($jenis=='zisco_bonus'){	//zisco bonus gaji_validasi=jml id cabang 
				foreach($rsGaji as $rs){
					//$html.='<tr><td colspan=7>select kota from mst_cabang where id_cabang='.$rs->WILAYAH.'</td></tr>';
					$rscab=$this->gate_db->query("select kota, id_cabang from mst_cabang where id_cabang=".$rs->WILAYAH)->row();
					$nmcabang=$rscab->kota;
					$idcabang=$rscab->id_cabang;
					$cab_2d=(strlen($idcabang)<=1?"0".$idcabang:$idcabang);
					$rk_cabang="1.1.7.01.".$cab_2d;		//punya pusat
					$rk_pusat="2.1.3.".$cab_2d.".03";	//2.1.3.xx.03 R/K Pusat Amil punya cabang
					$rsakun_rk_cabang=$this->db->query("select  * from ak_rekeningperkiraan where idacc='".$rk_cabang."'")->row();	//name, account_number, idacc
					$rsakun_rk_pusat=$this->db->query("select  * from ak_rekeningperkiraan where idacc='".$rk_pusat."'")->row();	//name, account_number, idacc
					$nm_rk_cabang= (sizeof($rsakun_rk_cabang)<=0?"uncorrect":$rk_cabang.' - '.$rsakun_rk_cabang->nama);
					$nm_rk_pusat= (sizeof($rsakun_rk_pusat)<=0?"uncorrect":$rk_pusat.' - '.$rsakun_rk_pusat->nama);
					$akunGajiCabang='5.5.1.'.$cab_2d.'.01';
					
					$strGaji="SELECT ifnull(SUM(total ),0) total FROM ".$arrTable[$rs->JENIS]." where id_validasi=".$rs->ID;
					$rsTotGaji=$this->payroll_db->query($strGaji)->row();
					$jmlGaji=$rsTotGaji->total;

					//$coaKredit=str_replace('xx', $cab_2d, $rowDonasi->idacc_zis);
					$pusat_urai="R/K Pengeluaran Kas untuk Biaya gaji cabang ".$nmcabang;
					$html.='<tr style="vertical-align:top !important">';	
					$html.="<td >".$i."</td>";
					
					$html.='<td >Debet<input type="hidden" name="id_cabang_'.$i.'" id="id_cabang_'.$i.'" class="form-control"  value="'.$idcabang.'"></td>';
					$html.='<td >'.$nm_rk_cabang.'<input type="hidden" name="pusat_debet_rk_'.$i.'" id="pusat_debet_rk_'.$i.'"  value="'.$rk_cabang.'"></td>';	//pusat					
					$html.='<td >'.form_dropdown('cabang_debet_coa_'.$i,$akunBiayaGaji,$akunGajiCabang,'id="cabang_debet_coa_'.$i.'" class="form-control"').'</td>';	//cabang					
					$html.='<td ><input type="text" name="txtjml_debet_'.$i.'" id="txtjml_debet_'.$i.'" class="form-control"   onblur="blurObj(this)" onclick="clickObj(this)"  value="'.$jmlGaji.'">
					</td>';
					$html.='<td >'.form_textarea(array('name'=>'pusat_urai_'.$i,'id'=>'pusat_urai_'.$i,'class'=>'form-control', 'value'=>$pusat_urai)).'</td>';
					$html.="</tr>";

					//kredit input by tgl trans
					$cabang_urai="R/K Pusat Biaya gaji cabang ".$nmcabang;
					$html.="<tr>";	
					$html.="<td ></td>";
					$html.="<td >Kredit</td>";	
					$html.='<td >'.form_dropdown('pusat_kredit_coa_'.$i,$akunBankGaji,'','id="pusat_kredit_coa_'.$i.'" class="form-control"').'</td>';	//Pusat
					$html.='<td >'.$nm_rk_pusat.'<input type="hidden" name="cabang_kredit_rk_'.$i.'" id="cabang_kredit_rk_'.$i.'"  value="'.$rk_pusat.'"></td>';	//cabang
						
					$html.='<td><input type="text" name="txtjml_kredit_'.$i.'" id="txtjml_kredit_'.$i.'" class="form-control"   onblur="blurObj(this)" onclick="clickObj(this)"  value="'.$jmlGaji.'"></td>';
					$html.='<td >'.form_textarea(array('name'=>'cabang_urai_'.$i,'id'=>'cabang_urai_'.$i,'class'=>'form-control', 'value'=>$cabang_urai)).'<input type="hidden" value="'.$thn.$bln.'_'.$rs->JENIS.'_'.$rs->WILAYAH.'" name="nobuktiref_'.$i.'" id="nobuktiref_'.$i.'" ><input type="hidden" value="'.$rs->ID.'" name="id_validasi_'.$i.'" id="id_validasi_'.$i.'" ></td>';
					$html.="</tr>";
					$i++;
				}
			}else{	//gaji_validasi, staff validasi 1-2 row in_array($jenis, ['laz','tasharuf','zisco_transport','kacab_bonus','non_sistem','thr_laz',''])
				//if (in_array($jenis,['laz','tasharuf','thr_laz','thr_tasharuf' ])){ //row 2		}
				foreach($rsGaji as $rs){
					//$html.='<tr><td colspan=7>select kota from mst_cabang where id_cabang='.$rs->WILAYAH.'</td></tr>';
					if ($rs->WILAYAH=="Pusat" || $rs->WILAYAH=="1"){
						$strGaji="SELECT ifnull(SUM(total ),0) total FROM ".$arrTable[$rs->JENIS]." where id_validasi=".$rs->ID;
						$rsTotGaji=$this->payroll_db->query($strGaji)->row();
						$jmlGaji=$rsTotGaji->total;
						//$coaKredit=str_replace('xx', $cab_2d, $rowDonasi->idacc_zis);
						$pusat_urai="Pengeluaran Kas untuk Biaya gaji ".(substr($jenis, 1,3)=="thr"?"THR":"")." Karyawan Pusat";
						$html.='<tr style="vertical-align:top !important">';	
						$html.="<td >".$i."</td>";
						$akunGajiPusat='5.5.1.01.01';
						$html.='<td >Debet<input type="hidden" name="id_cabang_'.$i.'" id="id_cabang_'.$i.'" class="form-control"  value="1"></td>';
						$html.='<td >Gaji Karyawan Pusat <input type="hidden" name="pusat_debet_rk_'.$i.'" id="pusat_debet_rk_'.$i.'"  value="'.$akunGajiPusat.'"></td>';	//pusat					
						$html.='<td ></td>';						
						$html.='<td ><input type="text" name="txtjml_debet_'.$i.'" id="txtjml_debet_'.$i.'" class="form-control"   onblur="blurObj(this)" onclick="clickObj(this)"  value="'.$jmlGaji.'">
						</td>';
						$html.='<td >'.form_textarea(array('name'=>'pusat_urai_'.$i,'id'=>'pusat_urai_'.$i,'class'=>'form-control', 'value'=>$pusat_urai)).'</td>';
						$html.="</tr>";

						//kredit input 
						$cabang_urai="Pengeluaran Kas untuk Biaya gaji ".(substr($jenis, 1,3)=="thr"?"THR":"")." Karyawan Pusat";
						$html.="<tr>";	
						$html.="<td ></td>";
						$html.="<td >Kredit</td>";	
						$html.='<td >'.form_dropdown('pusat_kredit_coa_'.$i,$akunBankGaji,'','id="pusat_kredit_coa_'.$i.'" class="form-control"').'</td>';	//Pusat
						$html.='<td ></td>';	
							
						$html.='<td><input type="text" name="txtjml_kredit_'.$i.'" id="txtjml_kredit_'.$i.'" class="form-control"   onblur="blurObj(this)" onclick="clickObj(this)"  value="'.$jmlGaji.'"></td>';
						$html.='<td >'.form_textarea(array('name'=>'cabang_urai_'.$i,'id'=>'cabang_urai_'.$i,'class'=>'form-control', 'value'=>$cabang_urai)).'<input type="hidden" value="'.$thn.$bln.'_'.$rs->JENIS.'_Pusat" name="nobuktiref_'.$i.'" id="nobuktiref_'.$i.'" ><input type="hidden" value="'.$rs->ID.'" name="id_validasi_'.$i.'" id="id_validasi_'.$i.'" ></td>';
						$html.="</tr>";
						$i++;

					}else{
						//get cabang, ada id_validasi 
					
					$strGaji="SELECT DISTINCT id_cabang,  ifnull(SUM(total ),0) total FROM ".$arrTable[$rs->JENIS]." where id_validasi=".$rs->ID." GROUP BY id_cabang ";
					$rsTotGaji=$this->payroll_db->query($strGaji)->result();
					
					foreach($rsTotGaji as $rsGajiCabang){
						$jmlGaji=$rsGajiCabang->total;

						$rscab=$this->gate_db->query("select kota, id_cabang from mst_cabang where id_cabang=".$rsGajiCabang->id_cabang)->row();
						$nmcabang=$rscab->kota;
						$idcabang=$rscab->id_cabang;
						$cab_2d=(strlen($idcabang)<=1?"0".$idcabang:$idcabang);
						$rk_cabang="1.1.7.01.".$cab_2d;		//punya pusat
						$rk_pusat="2.1.3.".$cab_2d.".03";	//2.1.3.xx.03 R/K Pusat Amil punya cabang
						$rsakun_rk_cabang=$this->db->query("select  * from ak_rekeningperkiraan where idacc='".$rk_cabang."'")->row();	//name, account_number, idacc
						$rsakun_rk_pusat=$this->db->query("select  * from ak_rekeningperkiraan where idacc='".$rk_pusat."'")->row();	//name, account_number, idacc
						$nm_rk_cabang= (sizeof($rsakun_rk_cabang)<=0?"uncorrect":$rk_cabang.' - '.$rsakun_rk_cabang->nama);
						$nm_rk_pusat= (sizeof($rsakun_rk_pusat)<=0?"uncorrect":$rk_pusat.' - '.$rsakun_rk_pusat->nama);
						$akunGajiCabang='5.5.1.'.$cab_2d.'.01';
						
						

						//$coaKredit=str_replace('xx', $cab_2d, $rowDonasi->idacc_zis);
						$pusat_urai="R/K Pengeluaran Kas untuk Biaya gaji ".(substr($jenis, 1,3)=="thr"?"THR":"")." cabang ".$nmcabang;
						$html.='<tr style="vertical-align:top !important">';	
						$html.="<td >".$i."</td>";
						
						$html.='<td >Debet<input type="hidden" name="id_cabang_'.$i.'" id="id_cabang_'.$i.'" class="form-control"  value="'.$idcabang.'"></td>';
						$html.='<td >'.$nm_rk_cabang.'<input type="hidden" name="pusat_debet_rk_'.$i.'" id="pusat_debet_rk_'.$i.'"  value="'.$rk_cabang.'"></td>';	//pusat					
						$html.='<td >'.form_dropdown('cabang_debet_coa_'.$i,$akunBiayaGaji,$akunGajiCabang,'id="cabang_debet_coa_'.$i.'" class="form-control"').'</td>';	//cabang					
						$html.='<td ><input type="text" name="txtjml_debet_'.$i.'" id="txtjml_debet_'.$i.'" class="form-control"   onblur="blurObj(this)" onclick="clickObj(this)"  value="'.$jmlGaji.'">
						</td>';
						$html.='<td >'.form_textarea(array('name'=>'pusat_urai_'.$i,'id'=>'pusat_urai_'.$i,'class'=>'form-control', 'value'=>$pusat_urai)).'</td>';
						$html.="</tr>";

						//kredit input by tgl trans
						$cabang_urai="R/K Pusat Biaya gaji cabang ".(substr($jenis, 1,3)=="thr"?"THR":"")." ".$nmcabang;
						$html.="<tr>";	
						$html.="<td ></td>";
						$html.="<td >Kredit</td>";	
						$html.='<td >'.form_dropdown('pusat_kredit_coa_'.$i,$akunBankGaji,'','id="pusat_kredit_coa_'.$i.'" class="form-control"').'</td>';	//Pusat
						$html.='<td >'.$nm_rk_pusat.'<input type="hidden" name="cabang_kredit_rk_'.$i.'" id="cabang_kredit_rk_'.$i.'"  value="'.$rk_pusat.'"></td>';	//cabang
							
						$html.='<td><input type="text" name="txtjml_kredit_'.$i.'" id="txtjml_kredit_'.$i.'" class="form-control"   onblur="blurObj(this)" onclick="clickObj(this)"  value="'.$jmlGaji.'"></td>';
						$html.='<td >'.form_textarea(array('name'=>'cabang_urai_'.$i,'id'=>'cabang_urai_'.$i,'class'=>'form-control', 'value'=>$cabang_urai)).'<input type="hidden" value="'.$thn.$bln.'_'.$rs->JENIS.'_'.$rs->WILAYAH.'_'.$rsGajiCabang->id_cabang.'" name="nobuktiref_'.$i.'" id="nobuktiref_'.$i.'" ><input type="hidden" value="'.$rs->ID.'" name="id_validasi_'.$i.'" id="id_validasi_'.$i.'" ></td>';
						$html.="</tr>";
						$i++;
						}
					}
				}
			}
			
			
		}
						
			
	
		

		

	   $html.="</table><br>";
	   $html.='<div class="row"><div class="col-md-12" style="text-align:center"><input type="button" class="btn btn-primary" id="btsimpan" name="btsimpan"  value="Save Post" onclick="simpanForm()">';
	   $html.='<input type="hidden" id="tgljurnal" name="tgljurnal"  value="'.date('Y-m-d').'" >';
	   $html.='<input type="hidden" id="jmlrow" name="jmlrow"  value="'.($i-1).'" >';
	   $html.='<input type="hidden" id="jenis" name="jenis"  value="'.$jenis.'" >';
	   $html.='<input type="hidden" id="sumber_data" name="sumber_data"  value="penggajian" >';
	   
	   $html.="</div></div><br></form>";
		
		$respon["html"]=$html;
		$respon["status"]="success";
		
		
		echo json_encode($respon);
		
	}
	
}
