<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class penyusutan extends MY_App {
	var $branch = array();
	

	function __construct()
	{
		parent::__construct();
		//$this->load->model('penyusutan_model');
		$this->config->set_item('mymenu', 'menuTiga');
		$this->config->set_item('mysubmenu', 'menuTigaEmpat');
		$this->auth->authorize();

	}
	
	public function index()
	{	$data['arrBulan'] = $this->arrBulan2;
		$data['arrThn'] = $this->getYearArr();
		$this->template->set('breadcrumbs','<li><a href="#">Jurnal</a></li><li><a href="#">Penyusutan Aset</a></li>');
		$this->template->set('pagetitle','Jurnal Penyusutan Aset');		
		$this->template->load('default','fjurnal/vpenyusutan',$data);
		
	}
	
	
	public function generateData(){
		$sts="";
		$strPost="";
		$postSts="";
		$html="";
		$pesan='';
		$jml=0;
		$blnStr=$this->arrBulan2;
		$bln=$this->input->post("bln");
		$thn=$this->input->post("thn");
		$pilihan=$this->input->post("pilihan");
		//cek 
		if ($thn.$bln < date ('Ym')){
			//cek jurnal
			$scek="select count(*) JML from ak_jurnal where bulan='$bln' and tahun='$thn' and jenis='BNK' and nobuktiref in (select ACCFIXEDASSET from ak_fixed_asset_setting where isactive=1 and id_cab=".$this->session->userdata('auth')->ID_CABANG.")  and id_cab=".$this->session->userdata('auth')->ID_CABANG;
			$rsCek=$this->db->query($scek)->row();
			if ($rsCek->JML>0){
				$sts='2';
				$pesan="Jurnal Penyusutan Periode $bln $thn sudah diposting. Silakan Cek status validasi status jurnal penyusutan periode ini";
			}else{
				$sts='1';
			}
		}elseif($thn.$bln == date('Ym')){
			if (date('d')>=27){
				$sts='1';
			}else{
				$pesan='Sekarang tanggal : '.date('d-m-Y').', jurnal penyusutan baru dapat dilakukan setelah tanggal 27 setiap bulannya';
				$sts='0';
			}
			
		}elseif ($thn.$bln > date ('Ym')){
			$pesan='Sekarang tanggal : '.date('d-m-Y').', periode pilihan belum memasuki masanya';
			$sts='0';
			//blm diproses jika bulan lalu tp status new (blm ada di jurnal) hrs di cek kemungkinan masuk menjadi akumset saldo awal
		}

		if ($sts=='1' || $sts=='2'){
			//$str="select fixed_asset_setting.*, (select nama from rekeningperkiraan where idacc=fixed_asset_setting.accFixedAsset) namaakun from fixed_asset_setting where isactive=1 ".($this->session->userdata('auth')->ID_CABANG==0?"":" and id_cab=".$this->session->userdata('auth')->ID_CABANG);

			$str="select ak_fixed_asset_setting.*, DATE_FORMAT(tgl_perolehan, '%d') firstdate, (select nama from ak_rekeningperkiraan where idacc=ak_fixed_asset_setting.accFixedAsset) namaakun from ak_fixed_asset_setting where isactive=1 and id_cab=".$this->session->userdata('auth')->ID_CABANG;
			$pesan.=$str;
			$resList= $this->db->query($str)->result();
			$html.="<br><form name=\"frmSusut\" id=\"frmSusut\"><table class=\"table table-bordered\">";
			$html.="<div class=\"alert alert-info alert-dismissable\"><i class=\"fa fa-info\"></i><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button><b>Info : </b> Periode terpilih : <b> ".$blnStr[$bln]." ".$thn." </b><input type=\"hidden\" id=\"bln\" name=\"bln\" value=\"$bln\"><input type=\"hidden\" id=\"thn\" name=\"thn\" value=\"$thn\"></div>";
			$html.="<tr><th >No</th><th >Nama Aset</th><th >Tgl Perolehan</th><th >Nilai Perolehan Total</th><th >Lama susut Total</th><th >Sisa Usia susut</th><th >Nilai Susut/bln</th><th>Status Jurnal</th></tr>";
			if (sizeof($resList )<=0){
				$html.="<tr><td colspan=7 style=\"text-align:center\">Data setting master akun penyusutan belum ada</td></tr>";
			}else{
				$i=1;
				
				foreach ($resList as $detil){
					//$strPost="select * from jurnal where bulan='$bln' and tahun='$thn' and jenis='BNK' and nobuktiref='".$detil->accFixedAsset."' ".($this->session->userdata('auth')->ID_CABANG==0?"":" and id_cab=".$this->session->userdata('auth')->ID_CABANG);

					$strPost="select * from ak_jurnal where bulan='$bln' and tahun='$thn' and jenis='BNK' and sumber_data='susut' and nobuktiref='".$detil->accFixedAsset."'  and id_cab=".$this->session->userdata('auth')->ID_CABANG;
					$valPost="";
					$resPost= $this->db->query($strPost)->row();
					

					if (sizeof($resPost )<=0){
						$stsPost="New";
						$valPost="";
					}else{
						$valPost='<a href="javascript:void(0)" onclick="delpenyusutan('.$resPost->notransaksi.')">&nbsp;Hapus&nbsp;<i class="fa fa-trash-o" title="Delete Jurnal"></i>&nbsp;?</a>';
						$stsPost="Posted";
					}
					

					//cek tgl perolehan, krn masuk saldo awal akumulasi-> nunggu cut off -.asumsi tgl terakhir
					$d=cal_days_in_month(CAL_GREGORIAN,$bln,$thn);
					$tgl1=$thn."-".$bln."-".$d;
					$interval = date_diff(date_create($detil->tgl_perolehan), date_create($tgl1));

					//tentang usia aset -> entri setting fixed aset info : lama_susut_sd_saat_ini, lama_sisa_susut + jml bln di jurnal susut 
					//$usiaAset=($interval->format("%m")<=0 && $interval->format("%d")>=27?1:$interval->format("%m"));
					$strCekUsiaDiJurnal="select ifnull(sum(jumlah),0) jml, ifnull(count(*),0) cnt from ak_jurnal where (idperkdebet='".$detil->accSusutAkm."' or idperkkredit='".$detil->accSusutAkm."') and sumber_data='susut' and concat(tahun,bulan)<='".$thn.$bln."' and status_validasi=1";
					$pesan.=$strCekUsiaDiJurnal;
					$resCek= $this->db->query($strCekUsiaDiJurnal)->row();
					$usiaAset=$detil->lama_susut_sd_saat_ini;
					$sisaUsia=$detil->lama_sisa_susut;
					if (sizeof($resCek )<=0){							
						$usiaAset=$detil->lama_susut_sd_saat_ini;
						$sisaUsia=$detil->lama_sisa_susut;
					}else{								
						$usiaAset+=$resCek->cnt;
						$sisaUsia-=$resCek->cnt;
					}
					
					//if ($sisaUsia<=0) $sts=3;
					$pesan.=$usiaAset."#sisaUsia=".$sisaUsia."#".$detil->firstdate;
					//$html.= "<tr><td colspan=6>Usia aset : $usiaAset, $d, perolehan : ".$detil->tgl_perolehan.", tgl1:".$tgl1.", interval : $usiaAset, ". $interval->format("%m %d")."</td></tr>";
					if  ($thn.$bln <= substr($detil->tgl_perolehan,0,4).substr($detil->tgl_perolehan,5,2)){
						$html.="<tr valign=top>";
						$html.="<td>$i</td><td>".$detil->namaakun."</td><td colspan=5>Periode pilih lebih kecil atau sama dengan tanggal perolehan barang";
						$html.="</td>";
						$html.="</tr>";
						//$pesan="Periode pilih lebih kecil atau sama dengan tanggal perolehan barang";
					}else if ( $sisaUsia<=0){
						$html.="<tr valign=top>";
						$html.="<td>$i</td><td>".$detil->namaakun."</td><td colspan=5>Usia Ekonomis Aset telah habis";
						$html.="</td>";
						$html.="</tr>";
					}else{
						if ($usiaAset<=0 && $detil->firstdate<27  ){
							$html.="<tr valign=top>";
							$html.="<td>$i</td><td>".$detil->namaakun."</td><td colspan=5>Usia Aset belum 1 bulan";
							$html.="</td>";
							$html.="</tr>";
						}else if($usiaAset>=0 || $detil->firstdate>27  ){
							$pesan.="masuk";
							$jml+=1;
							$totalUsia=$detil->lama_susut_sd_saat_ini+$detil->lama_sisa_susut;
							$susutBln=$detil->nilai_perolehan_saat_ini/$detil->lama_sisa_susut;
							$uraian="Penyusutan ".$detil->namaakun." ".$blnStr[$bln]." ".$thn;
							$html.="<tr valign=top>";
							$html.="<td>$i</td>";
							$html.="<td>".$detil->namaakun."</td>";
							$html.="<td>".$detil->tgl_perolehan."</td>";
							$html.="<td align=right>Rp. ".number_format(($totalUsia * $susutBln),2,',','.')."</td>";	//Nilai Perolehan Total
							$html.="<td >".$totalUsia."</td>";	//Lama susut Total	
							$html.="<td >".$sisaUsia."</td>";	//sisa	
							$html.="<td align=right>Rp. ".number_format($susutBln,2,',','.')."</td>";	//Nilai Susut/bln
							//$html.="<td>".($sts==1?$stsPost:($sts==2?$stsPost.", ".$valPost:$stsPost=3));
							$html.="<td>".($sts==1?$stsPost:$stsPost.", ".$valPost);
							$html.="<input type=\"hidden\" id=\"accFixedAsset[]\" name=\"accFixedAsset[]\" value=\"".$detil->accFixedAsset."\">";
							$html.="<input type=\"hidden\" id=\"accSusutAkm[]\" name=\"accSusutAkm[]\" value=\"".$detil->accSusutAkm."\">";
							$html.="<input type=\"hidden\" id=\"accSusutBiaya[]\" name=\"accSusutBiaya[]\" value=\"".$detil->accSusutBiaya."\">";
							$html.="<input type=\"hidden\" id=\"stsPost[]\" name=\"stsPost[]\" value=\"".$stsPost."\">";
							$html.="<input type=\"hidden\" id=\"uraian[]\" name=\"uraian[]\" value=\"".$uraian."\">";
							$html.="<input type=\"hidden\" id=\"susut[]\" name=\"susut[]\" value=\"".round($susutBln, 2)."\">";
							$html.="</td>";
							$html.="</tr>";
						}
					}
					$i++;
				}
			}
			
			$html.="</table>";
			if ($sts==1 ){
				//if ($jml>0){
				$html.="<div class=\"row\"><div class=\"col-md-7\">";
				$html.="<input type=\"button\" class=\"btn btn-primary\" id=\"btsimpan\" name=\"btsimpan\"  value=\"Save Post\" onclick=\"simpanForm()\">";
				$html.="<input type=\"hidden\" id=\"tanggal\" name=\"tanggal\" value=\"".date('Y-m-d')."\">";		
				$html.="</div></div>";
				//}else{
				//	$html.="<div class=\"row\"><div class=\"col-md-7\">Belum ada data akun yang mulai masuk masa penyusutan</div></div>";
				//}
			}
			$html.="</form>";		

		}
			$respon["str"]=$str;
			$respon["sts"]=$sts;
			$respon["status"]="success";
			$respon["html"]=$html;
			$respon["pesan"]=$pesan;
			echo json_encode($respon);
	}

	public function generateData_lama(){
		$sts="";
		$strPost="";
		$postSts="";
		$html="";
		$pesan='';
		$jml=0;
		$blnStr=$this->arrBulan2;
		$bln=$this->input->post("bln");
		$thn=$this->input->post("thn");
		$pilihan=$this->input->post("pilihan");
		//cek 
		if ($thn.$bln == date('Ym') && date('d')<25){
			$pesan='Sekarang tanggal : '.date('d-m-Y').', jurnal penyusutan baru dapat dilakukan setelah tanggal 25 setiap bulannya';
			$sts='2';
		}elseif($thn.$bln > date('Ym')){
			$pesan='Sekarang tanggal : '.date('d-m-Y').', periode pilihan belum memasuki masanya';
			$sts='0';
		}else{
			$sts='1';
			//blm diproses jika bulan lalu tp status new (blm ada di jurnal) hrs di cek kemungkinan masuk menjadi akumset saldo awal
		}

		if ($sts=='1'){
			//$str="select fixed_asset_setting.*, (select nama from rekeningperkiraan where idacc=fixed_asset_setting.accFixedAsset) namaakun from fixed_asset_setting where isactive=1 ".($this->session->userdata('auth')->ID_CABANG==0?"":" and id_cab=".$this->session->userdata('auth')->ID_CABANG);

			$str="select ak_fixed_asset_setting.*, (select nama from ak_rekeningperkiraan where idacc=ak_fixed_asset_setting.accFixedAsset) namaakun from ak_fixed_asset_setting where isactive=1 and id_cab=".$this->session->userdata('auth')->ID_CABANG;

			$resList= $this->db->query($str)->result();
			$html.="<br><form name=\"frmSusut\" id=\"frmSusut\"><table class=\"table table-bordered\">";
			$html.="<div class=\"alert alert-info alert-dismissable\"><i class=\"fa fa-info\"></i><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button><b>Info : </b> Periode terpilih : <b> ".$blnStr[$bln]." ".$thn." </b><input type=\"hidden\" id=\"bln\" name=\"bln\" value=\"$bln\"><input type=\"hidden\" id=\"thn\" name=\"thn\" value=\"$thn\"></div>";
			$html.="<tr><th >No</th><th >Nama Aset</th><th >Tgl Perolehan</th><th >Nilai Perolehan</th><th >Lama/usia(bln)</th><th >Nilai Susut/bln</th><th>Status Jurnal</th></tr>";
			if (sizeof($resList )<=0){
				$html.="<tr><td colspan=7 style=\"text-align:center\">Data setting master akun penyusutan belum ada</td></tr>";
			}else{
				$i=1;
				
				foreach ($resList as $detil){
					//$strPost="select * from jurnal where bulan='$bln' and tahun='$thn' and jenis='BNK' and nobuktiref='".$detil->accFixedAsset."' ".($this->session->userdata('auth')->ID_CABANG==0?"":" and id_cab=".$this->session->userdata('auth')->ID_CABANG);

					$strPost="select * from ak_jurnal where bulan='$bln' and tahun='$thn' and jenis='BNK' and nobuktiref='".$detil->accFixedAsset."'  and id_cab=".$this->session->userdata('auth')->ID_CABANG;

					$resPost= $this->db->query($strPost)->row();
					if (sizeof($resPost )<=0){
						$stsPost="New";
					}else{
						$stsPost="Posted";
					}
					
					//cek tgl perolehan, krn masuk saldo awal akumulasi-> nunggu cut off -.asumsi tgl terakhir
					$d=cal_days_in_month(CAL_GREGORIAN,$bln,$thn);
					$tgl1=$thn."-".$bln."-".$d;
					$interval = date_diff(date_create($tgl1), date_create($detil->tgl_perolehan));
					$usiaAset=($interval->format("%d")<=30?1:$interval->format("%m"));
					//$html.= "<tr><td colspan=6>Usia aset : $usiaAset, $d, perolehan : ".$detil->tgl_perolehan.", tgl1:".$tgl1.", interval : $usiaAset</td></tr>";
					if ($usiaAset>0 && $interval->format("%d")>27){
						$jml+=1;
						$uraian="Penyusutan ".$detil->namaakun." ".$blnStr[$bln]." ".$thn;
						$html.="<tr valign=top>";
						$html.="<td>$i</td>";
						$html.="<td>".$detil->namaakun."</td>";
						$html.="<td>".$detil->tgl_perolehan."</td>";
						$html.="<td align=right>Rp. ".number_format($detil->nilai_perolehan_saat_ini,2,',','.')."</td>";
						$html.="<td >".$detil->lama_susut."/".$usiaAset."</td>";		
						$html.="<td align=right>Rp. ".number_format($detil->nilai_perolehan_saat_ini/$detil->lama_susut,2,',','.')."</td>";
						$html.="<td>$stsPost";
						$html.="<input type=\"hidden\" id=\"accFixedAsset[]\" name=\"accFixedAsset[]\" value=\"".$detil->accFixedAsset."\">";
						$html.="<input type=\"hidden\" id=\"accSusutAkm[]\" name=\"accSusutAkm[]\" value=\"".$detil->accSusutAkm."\">";
						$html.="<input type=\"hidden\" id=\"accSusutBiaya[]\" name=\"accSusutBiaya[]\" value=\"".$detil->accSusutBiaya."\">";
						$html.="<input type=\"hidden\" id=\"stsPost[]\" name=\"stsPost[]\" value=\"".$stsPost."\">";
						$html.="<input type=\"hidden\" id=\"uraian[]\" name=\"uraian[]\" value=\"".$uraian."\">";
						$html.="<input type=\"hidden\" id=\"susut[]\" name=\"susut[]\" value=\"".round($detil->nilai_perolehan_saat_ini/$detil->lama_susut, 2)."\">";
						$html.="</td>";
						$html.="</tr>";
					}
					$i++;
				}
			}
			
			$html.="</table>";
			if ($jml>0){
				if ($thn.$bln >= date('Ym')){
				$html.="<div class=\"row\"><div class=\"col-md-7\">";
				$html.="<input type=\"button\" class=\"btn btn-primary\" id=\"btsimpan\" name=\"btsimpan\"  value=\"Save Post\" onclick=\"simpanForm()\">";
				$html.="<input type=\"hidden\" id=\"tanggal\" name=\"tanggal\" value=\"".date('Y-m-d')."\">";		
				$html.="</div></div>";
				}
			}else{
				$html.="<div class=\"row\"><div class=\"col-md-7\">Belum ada data akun yang mulai masuk masa penyusutan</div></div>";
			}
			$html.="</form>";		

		}
			$respon["strPost"]=$strPost;
			$respon["sts"]=$sts;
			$respon["status"]="success";
			$respon["html"]=$html;
			$respon["pesan"]=$pesan;
			echo json_encode($respon);
	}
	
		
	public function savePenyusutan(){
		if ($this->input->is_ajax_request()){
			$resultstr="";
			try {
			$respon = new StdClass();
			
			$bln=$this->input->post("bln");
			$thn=$this->input->post("thn");

			$accFixedAsset=$this->input->post('accFixedAsset');
			$accSusutAkm=$this->input->post('accSusutAkm');
			$accSusutBiaya=$this->input->post('accSusutBiaya');
			$stsPost=$this->input->post('stsPost');
			$susut=$this->input->post('susut');	
			$uraian=$this->input->post('uraian');	
			$tanggal=$this->input->post('tanggal');	
			if ($thn.$bln!=date('Ym')){
				$tanggal=$thn.'-'.$bln.'-28';
			}
			
			$qzis="";
			for ($x=0; $x<sizeof($accFixedAsset);$x++){
				//$x=$pilih[$y];
				//query zis		
				if ($stsPost[$x]=='New'){
				$qzis="insert into ak_jurnal (bulan, tahun, id_cab, tanggal, idperkdebet, idperkkredit, nobuktiref, sumber_data, keterangan, jumlah, jenis, waktuentri)  values('$bln', '$thn', ".$this->session->userdata('auth')->ID_CABANG.", '$tanggal', '".$accSusutBiaya[$x]."',  '".$accSusutAkm[$x]."',  '".$accFixedAsset[$x]."', 'susut',  '".$uraian[$x]."',  '".$susut[$x]."', 'BNK', '".date('Y-m-d H:i:s')."')";
				
				$this->db->query($qzis);
				$this->db->trans_commit();
				}
				$resultstr.=$qzis."<br>";
				
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
	
	public function delpenyusutan(){
		$id=$this->input->post('idx');
		$res = $this->common_model->delkasmasuk($id);	//function delkasmasuk bisa untuk hapus transaksi jurnal berdasarkan id
		return $res;
	}
	
		
	
}
