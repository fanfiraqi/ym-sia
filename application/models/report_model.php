<?php
			
class report_model extends MY_Model {
	
	function __construct()
	{
		parent::__construct();	
		 $this->gate_db=$this->load->database('gate', TRUE);
	}

	public function getAccName($idacc=''){
		$str="select nama from ak_rekeningperkiraan where idacc='$idacc' ";
		$query=$this->db->query($str)->row();		
		return $query->nama;
	}

	public function getInitVal($idacc=''){
		$str="select initval from ak_rekeningperkiraan where idacc='$idacc' ";
		$query=$this->db->query($str)->row();		
		return $query->initval;
	}
	public function getSaldoLalu($idacc='', $bln, $thn){
		$str="select ifnull(sum(jumlah),0) jmldebet from ak_jurnal where idperkdebet='$idacc' and status_validasi=1 and concat(tahun, bulan)<".$thn.$bln;
		$query=$this->db->query($str)->row();

		$str2="select ifnull(sum(jumlah),0) jmlkredit from ak_jurnal where idperkkredit='$idacc' and status_validasi=1 and concat(tahun, bulan)<".$thn.$bln;
		$query2=$this->db->query($str2)->row();
		$saldo=$query->jmldebet-$query2->jmlkredit;
		return $saldo;
	}
	public function getSaldoLaluWithInterval($idacc='', $tgl1){
		$str="select ifnull(sum(jumlah),0) jmldebet from ak_jurnal where idperkdebet='$idacc' and tanggal<'".$tgl1."' and status_validasi=1";
		$query=$this->db->query($str)->row();

		$str2="select ifnull(sum(jumlah),0) jmlkredit from ak_jurnal where idperkkredit='$idacc' and tanggal<'".$tgl1."' and status_validasi=1";
		$query2=$this->db->query($str2)->row();
		$saldo=$query->jmldebet-$query2->jmlkredit;
		return $saldo;
	}
	public function getsaldo($idacc){
		$str="select ifnull(sum(initval),0) saldoawal from ak_rekeningperkiraan where  idacc  ='".$idacc."'";
		$query=$this->db->query($str)->row();

		$str1="select ifnull(sum(jumlah),0) jmldebet from ak_jurnal where idperkdebet ='".$idacc."' and jenis in ('BKM', 'BKK') and tanggal<='".date('Y-m-d')."' and status_validasi=1 ";
		$query1=$this->db->query($str1)->row();

		$str2="select ifnull(sum(jumlah),0) jmlkredit from ak_jurnal where idperkkredit ='".$idacc."' and jenis in ('BKM', 'BKK')  and tanggal<='".date('Y-m-d')."' and status_validasi=1";		
		$query2=$this->db->query($str2)->row();

		$saldo=($query->saldoawal+$query1->jmldebet)-$query2->jmlkredit;
		return $saldo;

	}
	public function getInitValCashFlow($id_cab){
		$str="select sum(initval) saldoawal from ak_rekeningperkiraan where  status=1 and ((idacc  like '1.1.1.%') or  (idacc  like   '1.1.2.%' ))  and id_cab=".$id_cab;
		$query=$this->db->query($str)->row();		
		return $query->saldoawal;
	}
	public function getSaldoLaluCashFlow($bln, $thn, $wilFilter){
		$str="select ifnull(sum(jumlah),0) jmldebet from ak_jurnal where idperkdebet in (select distinct idacc from ak_rekeningperkiraan where status=1 and ((idacc  like '1.1.1.%') or  (idacc  like   '1.1.2.%' ))) and jenis in ('BKM', 'BKK') and status_validasi=1 and concat(tahun, bulan)<".$thn.$bln." and id_cab=".$wilFilter;
		$query=$this->db->query($str)->row();

		$str2="select ifnull(sum(jumlah),0) jmlkredit from ak_jurnal where idperkkredit in (select distinct idacc from ak_rekeningperkiraan where status=1 and ((idacc  like '1.1.1.%') or  (idacc  like   '1.1.2.%' ))) and jenis in ('BKM', 'BKK') and status_validasi=1 and concat(tahun, bulan)<".$thn.$bln." and id_cab=".$wilFilter;
		
		$query2=$this->db->query($str2)->row();
		$saldo=$query->jmldebet-$query2->jmlkredit;
		//return $str."<br>".$str2;
		return $saldo;
	}

	public function getSaldoLaluDashboard(){
		$str="select ifnull(sum(jumlah),0) jmldebet from ak_jurnal where idperkdebet in (select distinct idacc from ak_rekeningperkiraan where status=1 and ((idacc  like '1.1.1.%') or  (idacc  like   '1.1.2.%' ))) and jenis in ('BKM', 'BKK') and tanggal<'".date('Y-m-d')."' and  status_validasi=1 and id_cab=".$this->session->userdata('auth')->id_cabang;
		$query=$this->db->query($str)->row();

		$str2="select ifnull(sum(jumlah),0) jmlkredit from ak_jurnal where idperkkredit in (select distinct idacc from ak_rekeningperkiraan where status=1 and ((idacc  like '1.1.1.%') or  (idacc  like   '1.1.2.%' ))) and jenis in ('BKM', 'BKK')  and tanggal<'".date('Y-m-d')."' and  status_validasi=1 and id_cab=".$this->session->userdata('auth')->id_cabang;		
		$query2=$this->db->query($str2)->row();

		$saldo=$query->jmldebet-$query2->jmlkredit;
		return $saldo;
	}
	public function getInitValCashFlowUserKasir(){
		
		$idacc='1.1.01';
		
		$str="select sum(initval) saldoawal from ak_rekeningperkiraan where  idacc  like '$idacc%' and id_cab=".$this->session->userdata('auth')->id_cabang;
		$query=$this->db->query($str)->row();		
		return $query->saldoawal;
	}
	public function getSaldoLaluDashboardUserKasir(){
		$idacc='1.1.01';
		
		$str="select ifnull(sum(jumlah),0) jmldebet from ak_jurnal where idperkdebet in (select distinct idacc from ak_rekeningperkiraan where status=1 and idacc  like '$idacc%') and jenis in ('BKM', 'BKK') and tanggal<'".date('Y-m-d')."' and status_validasi=1 and id_cab=".$this->session->userdata('auth')->id_cabang;
		$query=$this->db->query($str)->row();

		$str2="select ifnull(sum(jumlah),0) jmlkredit from ak_jurnal where idperkkredit in (select distinct idacc from ak_rekeningperkiraan where status=1 and idacc  like '$idacc%')  and jenis in ('BKM', 'BKK')  and tanggal<'".date('Y-m-d')."' and status_validasi=1 and id_cab=".$this->session->userdata('auth')->id_cabang;		
		$query2=$this->db->query($str2)->row();

		$saldo=$query->jmldebet-$query2->jmlkredit;
		$strx=$str."<br>".$str2;
		//return $strx;
		return $saldo;
	}
	public function kasMasukHariIni(){
		$str="select ifnull(sum(jumlah),0) jmldebet from ak_jurnal where idperkdebet in (select distinct idacc from ak_rekeningperkiraan where status=1 and ((idacc  like '1.1.1.%') or  (idacc  like   '1.1.2.%' ))) and status_validasi=1 and jenis in ('BKM', 'BKK') and tanggal='".date('Y-m-d')."' and id_cab=".$this->session->userdata('auth')->id_cabang;
		$query=$this->db->query($str)->row();
		$saldo=$query->jmldebet;
		return $saldo;
	}
	public function kasKeluarHariIni(){
		$str2="select ifnull(sum(jumlah),0) jmlkredit from ak_jurnal where idperkkredit in (select distinct idacc from ak_rekeningperkiraan where status=1 and ((idacc  like '1.1.1.%') or  (idacc  like   '1.1.2.%' ))) and status_validasi=1 and jenis in ('BKM', 'BKK')  and tanggal='".date('Y-m-d')."' and id_cab=".$this->session->userdata('auth')->id_cabang;
		
		$query2=$this->db->query($str2)->row();
		$saldo=$query2->jmlkredit;
		return $saldo;
	}
	public function getNeracaValue($idL3='', $bln, $thn){
		//L3 => CHILD L4 & L5, get saldo awal & sum(jumlah) transaksi
		$initval=0;
		$jmldebet=0;
		$jmlkredit=0;
		//cek L4 
		$str4="select * from ak_rekeningperkiraan where idparent='".$idL3."' and status=1 and id_cab=".$this->session->userdata('auth')->id_cabang;
		$query4 =$this->db->query($str4)->result();
		if (sizeof($query4)<=0){	//jika tidak punya Lvl4
		}else{
			//loop L4
			foreach ($query4 as $level4){	//sudah difilter id_cab dari lvl 3 (lihat atas)
				$str5="select count(*) jmlcek from ak_rekeningperkiraan where idparent='".$level4->idacc."' and status=1";
				$query5 =$this->db->query($str5)->row();
				
				if ($query5->jmlcek>0){
					//get initval & ak_jurnal L5
					$strI="select ifnull(sum(initval),0) jmlInit from ak_rekeningperkiraan where idparent='".$level4->idacc."' ";
					$queryI=$this->db->query($strI)->row();
					$initval+=$queryI->jmlInit;

					//jmldebet
					$str="select ifnull(sum(jumlah),0) jmldebet from ak_jurnal where idperkdebet in (select distinct idacc from ak_rekeningperkiraan where idparent='".$level4->idacc."') and status_validasi=1 and concat(tahun, bulan)<=".$thn.$bln;
					$query=$this->db->query($str)->row();
					$jmldebet+=$query->jmldebet;

					//jmlkredit
					$str2="select ifnull(sum(jumlah),0) jmlkredit from ak_jurnal where idperkkredit in (select distinct idacc from ak_rekeningperkiraan where idparent='".$level4->idacc."') and status_validasi=1 and concat(tahun, bulan)<=".$thn.$bln;
					$query2=$this->db->query($str2)->row();
					$jmlkredit+=$query2->jmlkredit;

				}else{
					//get initval & ak_jurnal L4
					$strI="select initval from ak_rekeningperkiraan where idacc='".$level4->idacc."' ";
					$queryI=$this->db->query($strI)->row();
					$initval+=$queryI->initval;

					//jmldebet
					$str="select ifnull(sum(jumlah),0) jmldebet from ak_jurnal where idperkdebet='".$level4->idacc."' and status_validasi=1 and concat(tahun, bulan)<=".$thn.$bln;
					$query=$this->db->query($str)->row();
					$jmldebet+=$query->jmldebet;

					//jmlkredit
					$str2="select ifnull(sum(jumlah),0) jmlkredit from ak_jurnal where idperkkredit='".$level4->idacc."' and status_validasi=1 and concat(tahun, bulan)<=".$thn.$bln;
					$query2=$this->db->query($str2)->row();
					$jmlkredit+=$query2->jmlkredit;
				}

			}
		}
		
		
		$saldo=($initval+$jmldebet)-$jmlkredit;
		return $saldo;
	}
	public function getLabaRugiValue($idL3='', $bln, $thn){
		//L3 => CHILD L4 & L5, get saldo awal & sum(jumlah) transaksi
		//$initval=0;
		$blnIdk=$this->arrIntBln;
		$thnCase=$thn;
		$jmldebet=0;	//Biaya
		$jmlkredit=0;	//pendapatan
		$strP="SELECT SUM(jumlah) AS hasilP FROM ak_jurnal WHERE idperkkredit IN (SELECT DISTINCT idacc FROM ak_rekeningperkiraan WHERE kelompok='P' and id_cab=".$this->session->userdata('auth')->id_cabang.") and status_validasi=1";
		$strB="SELECT SUM(jumlah) AS hasilB FROM ak_jurnal WHERE idperkdebet IN (SELECT DISTINCT idacc FROM ak_rekeningperkiraan WHERE kelompok='B' and id_cab=".$this->session->userdata('auth')->id_cabang.")  and status_validasi=1";
		switch (substr($idL3,0,4)){
			case "3-81":	//Surplus s.d tahun lalu				
				$where=" and tahun<'$thn'";
				break;
			case "3-82":	//Surplus s.d bulan lalu
				$where=" and (concat(tahun,bulan)>='".$thn."01' and concat(tahun,bulan)<'".$thn.$bln."')";
				break;
			/*case "3-9100":	//Surplus (Defisit) Tahun berjalan				
				$where=" and tahun='$thn'";
				break;
			case "3-9200":	//Surplus (Defisit) Bulan berjalan
				$where=" and bulan='$bln' and tahun='$thn'";
				break;*/
		}
		$strP.=$where;
		$strB.=$where;
		$queryP=$this->db->query($strP)->row(); $hasilP=$queryP->hasilP;
		$queryB=$this->db->query($strB)->row(); $hasilB=$queryB->hasilB;
		$saldo=$hasilP-$hasilB;
		return $saldo;
	}

	public function getNeracaValuePenj($idcc='', $bln, $thn){
		//L3 => CHILD L4 & L5, get saldo awal & sum(jumlah) transaksi
		$initval=0;
		$jmldebet=0;
		$jmlkredit=0;
		
		$strI="select initval, kelompok from ak_rekeningperkiraan where idacc='$idcc' ";
		$queryI=$this->db->query($strI)->row();
		$initval+=$queryI->initval;
		//jika saldo awal rekening pasiva
		if ($queryI->kelompok=='K' || $queryI->kelompok=='M'){
			$initval=$initval*-1;
		}

		//jmldebet
		$str="select ifnull(sum(jumlah),0) jmldebet from ak_jurnal where idperkdebet='$idcc' and status_validasi=1 and concat(tahun, bulan)<=".$thn.$bln;
		$query=$this->db->query($str)->row();
		$jmldebet+=$query->jmldebet;

		//jmlkredit
		$str2="select ifnull(sum(jumlah),0) jmlkredit from ak_jurnal where idperkkredit='$idcc' and status_validasi=1 and concat(tahun, bulan)<=".$thn.$bln;
		$query2=$this->db->query($str2)->row();
		$jmlkredit+=$query2->jmlkredit;
			
		$saldo=($initval+$jmldebet)-$jmlkredit;
		return $saldo;
	}
	
	
	public function getNeracaValueL4($idcc='', $bln, $thn){
		// L4 & L5, get saldo awal & sum(jumlah) transaksi
		$initval=0;
		$jmldebet=0;
		$jmlkredit=0;
		$cekstr="";
		
			
		//cek child
		$cek=$this->db->query("select count(*) jml from ak_rekeningperkiraan where idparent='$idcc'")->row();
		if($cek->jml>0){
			$strIx="select distinct kelompok, ifnull(sum(initval),0) rekap from ak_rekeningperkiraan where idacc in (select idacc from ak_rekeningperkiraan where idparent='$idcc') group by kelompok ";
			$queryIx=$this->db->query($strIx)->row();
			$rekap=$queryIx->rekap;
			if ($queryIx->kelompok=='K' || $queryIx->kelompok=='M'){
				$rekap=$rekap*-1;
			}
			$initval+=$rekap;

			//jmldebet
			$str5="select ifnull(sum(jumlah),0) jmldebet from ak_jurnal where idperkdebet in (select idacc from ak_rekeningperkiraan where idparent='$idcc')  and status_validasi=1 and concat(tahun, bulan)<='".$thn.$bln."'";
			$query5=$this->db->query($str5)->row();
			$jmldebet+=$query5->jmldebet;

			//jmlkredit
			$str6="select ifnull(sum(jumlah),0) jmlkredit from ak_jurnal where idperkkredit in (select idacc from ak_rekeningperkiraan where idparent='$idcc') and status_validasi=1 and concat(tahun, bulan)<='".$thn.$bln."'";
			$query6=$this->db->query($str6)->row();
			$jmlkredit+=$query6->jmlkredit;

			$cekstr.=$strIx."<br>".$str5."<br>".$str6;
		}else{

			$strI="select initval, kelompok from ak_rekeningperkiraan where idacc='$idcc' ";
			$queryI=$this->db->query($strI)->row();
			$initval+=$queryI->initval;
			//jika saldo awal rekening pasiva
			if ($queryI->kelompok=='K' || $queryI->kelompok=='M'){
				$initval=$initval*-1;
			}

			//jmldebet
			$str="select ifnull(sum(jumlah),0) jmldebet from ak_jurnal where idperkdebet='$idcc' and status_validasi=1 and concat(tahun, bulan)<=".$thn.$bln;
			$query=$this->db->query($str)->row();
			$jmldebet+=$query->jmldebet;

			//jmlkredit
			$str2="select ifnull(sum(jumlah),0) jmlkredit from ak_jurnal where idperkkredit='$idcc' and status_validasi=1 and concat(tahun, bulan)<=".$thn.$bln;
			$query2=$this->db->query($str2)->row();
			$jmlkredit+=$query2->jmlkredit;

			$cekstr.=$strI."<br>".$str."<br>".$str2;
		}

		$saldo=($initval+$jmldebet)-$jmlkredit;
		return $saldo;
		//return $cekstr;
	}

	public function getLabaRugiValueL4($idacc='', $id_cab, $bln, $thn){
		//L4, get saldo awal & sum(jumlah) transaksi
		
		$blnIdk=$this->arrIntBln;
		$thnCase=$thn;
		$jmldebet=0;	//Biaya
		$jmlkredit=0;	//pendapatan
		
		$cek=$this->db->query("select count(*) jml from ak_rekeningperkiraan where idparent='$idacc'")->row();
		
			$strIx="select ifnull(sum(initval),0) saldo from ak_rekeningperkiraan where idacc  ".($cek->jml>0?" in (select idacc from ak_rekeningperkiraan where idparent='$idacc') ":" ='$idacc'  ");
			$queryIx=$this->db->query($strIx)->row();
			$initval=$queryIx->saldo;
				
			$idP="";
			$idB="";
			switch (substr($idacc,0,3)){
				case "3.1":	//Saldo dana zakat				
					$idP="4.1";
					$idB="5.1";
					break;
				case "3.2":	//Saldo dana infak tak terikat
					$idP="4.2";
					$idB="5.2";
					break;
				case "3.3":	//Saldo dana infak terikat				
					$idP="4.3";
					$idB="5.3";
					break;
				case "3.4":	//Saldo dana wakaf 
					$idP="4.4";
					$idB="5.4";
					break;
				case "3.5":	//Saldo dana Amil
					$idP="4.5";
					$idB="5.5";
					break;
				case "3.6":	//Saldo dana non halal
					$idP="4.6";
					$idB="5.6";
					break;
			}

			/*$strP="SELECT ifnull(SUM(jumlah),0) AS hasilP FROM ak_jurnal WHERE idperkkredit IN (SELECT DISTINCT idacc FROM ak_rekeningperkiraan WHERE   kelompok='P' and idacc like '4.".$digitZis.".%' and id_cab=$id_cab) and status_validasi=1";
			

			$strB="SELECT ifnull(SUM(jumlah),0) AS hasilB FROM ak_jurnal WHERE idperkdebet IN (SELECT DISTINCT idacc FROM ak_rekeningperkiraan WHERE  kelompok='B' and idacc like '5.".$digitZis.".%' and id_cab=$id_cab)  and status_validasi=1";*/
			
			$strP="SELECT SUM(jumlah) AS hasilP FROM ak_jurnal WHERE idperkkredit IN (SELECT DISTINCT idacc FROM ak_rekeningperkiraan WHERE kelompok='P'  and id_cab=$id_cab and idacc like '".$idP."%') and status_validasi=1";

			$strB="SELECT SUM(jumlah) AS hasilB FROM ak_jurnal WHERE idperkdebet IN (SELECT DISTINCT idacc FROM ak_rekeningperkiraan WHERE kelompok='B' and id_cab=$id_cab and idacc like '".$idB."%')  and status_validasi=1";
			$where=" and (concat(tahun,bulan) <= '".$thn.$bln."' ) ";
			//$where=" and (concat(tahun,bulan) = '".$thn.$bln."' ) ";

			
			$strP.=$where;
			$strB.=$where;
			$queryP=$this->db->query($strP)->row(); $hasilP=$queryP->hasilP;
			$queryB=$this->db->query($strB)->row(); $hasilB=$queryB->hasilB;
			$saldo=$initval+($hasilP-$hasilB);
		
		return $saldo;
		//return $idacc."#".$digitZis."<br>"."select count(*) jml from ak_rekeningperkiraan where idparent='$idacc'"."<br>".$strIx."<br>".$strP."<br>".$strB;
	}
	
	
	 
	public function getLabaRugiValuePenj($idacc='', $id_cab, $bln, $thn){
		//idacc = lvl4 akun kelompok M, 3-%
		
		$blnIdk=$this->arrIntBln;
		$thnCase=$thn;
		$jmldebet=0;	//Biaya
		$jmlkredit=0;	//pendapatan
		$strIx="select ifnull(sum(initval),0) saldo from ak_rekeningperkiraan where idacc ='$idacc'  ";
		$queryIx=$this->db->query($strIx)->row();
		$initval=$queryIx->saldo;
		$idP="";
		$idB="";
		switch (substr($idacc,0,3)){
			case "3.1":	//Saldo dana zakat				
				$idP="4.1";
				$idB="5.1";
				break;
			case "3.2":	//Saldo dana infak tak terikat
				$idP="4.2";
				$idB="5.2";
				break;
			case "3.3":	//Saldo dana infak terikat				
				$idP="4.3";
				$idB="5.3";
				break;
			case "3.4":	//Saldo dana wakaf 
				$idP="4.4";
				$idB="5.4";
				break;
			case "3.5":	//Saldo dana Amil
				$idP="4.5";
				$idB="5.5";
				break;
			case "3.6":	//Saldo dana non halal
				$idP="4.6";
				$idB="5.6";
				break;
		}

		$strP="SELECT SUM(jumlah) AS hasilP FROM ak_jurnal WHERE idperkkredit IN (SELECT DISTINCT idacc FROM ak_rekeningperkiraan WHERE kelompok='P'  and id_cab=$id_cab and idacc like '".$idP."%') and status_validasi=1";

		$strB="SELECT SUM(jumlah) AS hasilB FROM ak_jurnal WHERE idperkdebet IN (SELECT DISTINCT idacc FROM ak_rekeningperkiraan WHERE kelompok='B' and id_cab=$id_cab and idacc like '".$idB."%')  and status_validasi=1";
		$where=" and (concat(tahun,bulan) <= '".$thn.$bln."' ) ";

		
		$strP.=$where;
		$strB.=$where;
		$queryP=$this->db->query($strP)->row(); $hasilP=$queryP->hasilP;
		$queryB=$this->db->query($strB)->row(); $hasilB=$queryB->hasilB;
		$saldo=$initval+($hasilP-$hasilB);
		//$saldo=$hasilP-$hasilB;
		return $saldo;
	}
}