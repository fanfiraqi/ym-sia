<div class="row">
<div class="col-md-12">
 <div class="box box-info">
 <div class="box-body">
<?	
	$wilFilter='';
	if ($sesLogin=='center'){
		$wilFilter=$wilayah;
		if ($wilayah=='1'){
			$wilFilter="konsolidasi";
		}
	}else{
		$wilFilter=$this->session->userdata('auth')->ID_CABANG;
	}
	$ketcabang="";
	if ($wilFilter!="konsolidasi"){
		$str='select KOTA from mst_cabang where ID_CABANG='.$wilFilter;
		$nmcabang=$this->gate_db->query($str)->row();
		$ketcabang=($wilFilter>1?'CABANG ':'').' '.(sizeof($nmcabang)>0?$nmcabang->KOTA:'PUSAT (KONSOLIDASI)');
	}else{
		$ketcabang='PUSAT (KONSOLIDASI)';
	}
	$viewKop=$this->commonlib->tableKop('KEU-ACCOUNTING<BR>'.$ketcabang,$title, '00', '__','__', $this->session->userdata('param_company'), $this->session->userdata('logo'));
	echo $viewKop;
	//echo $strMaster;
$str="SELECT `idacc`, `nama`, `kelompok`, `level`,idparent, IF(`kelompok`='A','Aktiva','Pasiva') AS jenis
		FROM ak_rekeningperkiraan 
		WHERE  `kelompok` = 'M' AND `level`=2 and status=1 
		ORDER BY  `kelompok`,idacc";
$query=$this->db->query($str)->result();

?><br>
<div align=center>
<table  border="0" cellspacing=0 class="mytable mytable-bordered"  align="center" width="85%">
<tr valign=top><th class="pojokKiriAtas" ></th><th colspan=4 ><div align=center>PERKIRAAN </div></th><th class="pojokKananAtas" colspan=2><div align=center>JUMLAH</div></th ></tr>
<?	$i=1;
	$jenis="";
	$jumlah=array();	
	$jumlahP=0;
	$jumlahB=0;
	$saldoAkhir=0;
	foreach ($query as $level2){	//idacc kel M level 2	
		$digitZis=substr($level2->idacc,2,1);
		//$digitZis=substr($level2->idacc,2,1);
		echo "<tr><td>&nbsp;</td><td colspan=6><b>".$level2->idacc." - ".strtoupper($level2->nama)."</b></td></tr>";
		echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td colspan=3><b>Penerimaan</b></td><td colspan=2>&nbsp;</td></tr>";
		
		
		//level 3
		$strPL3="select * from ak_rekeningperkiraan where idparent='4.".$digitZis."' and level=3 order by  idacc";	
		$resListPL3= $this->db->query($strPL3)->result();
		$jumlahPLevel3=0;
		$saldolAwal=0;
		if (sizeof ($resListPL3)<=0){
		echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td colspan=5>Rekening Perkiraan Pendapatan ZIS Tidak ada atau bukan data Cabang Anda</td></tr>";
		}else{	
			
			foreach ($resListPL3 as $detilPL3){
				$strP="SELECT ifnull(SUM(jumlah),0) AS hasilP FROM ak_jurnal WHERE idperkkredit IN (SELECT DISTINCT idacc FROM ak_rekeningperkiraan WHERE   kelompok='P' and idacc like '".$detilPL3->idacc.".%' ".($wilFilter=="konsolidasi"?"":" and id_cab=$wilFilter").") and status_validasi=1 and (concat(tahun,bulan) = '".$thn.$bln."' ) and  ( isjurnalbalik='off' or isjurnalbalik='0')";
				echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
				echo "<td colspan=2><b>".$detilPL3->nama."</b></td>";//value level 5 
				
				$queryP=$this->db->query($strP)->row();
				$jumlahP+=$queryP->hasilP;
				$jumlahPLevel3+=$queryP->hasilP;
				echo "<td style=\"text-align:right\">Rp.&nbsp;".number_format($queryP->hasilP,2,',','.')."</td><td>&nbsp;</td>";
				echo "</tr>";
			}
		}
		echo "<tr><td>&nbsp;</td><td colspan=5 style=\"text-align:right\"><b><i>Jumlah Penerimaan  </i></b></td><td style=\"text-align:right\"><b>Rp.&nbsp;".number_format($jumlahPLevel3,2,',','.')."</b></td></tr>";

		
		echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td colspan=3 ><b>Penyaluran</b></td><td colspan=2>&nbsp;</td></tr>";
		//level 3
		$strBL3="select * from ak_rekeningperkiraan where idparent='5.".$digitZis."' and level=3 order by  idacc";	
		$resListBL3= $this->db->query($strBL3)->result();
		if (sizeof ($resListBL3)<=0){
		echo "<tr><td colspan=5>Rekening Perkiraan Biaya/Penyaluran Tidak ada atau bukan data Cabang Anda</td></tr>";
		}else{		
			$jumlahBLevel3=0;
			foreach ($resListBL3 as $detilBL3){
				echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
				echo "<td colspan=2><b>".$detilBL3->nama."</b></td>";
				//value level 5 
				$strB="SELECT ifnull(SUM(jumlah),0) AS hasilB FROM ak_jurnal WHERE idperkdebet IN (SELECT DISTINCT idacc FROM ak_rekeningperkiraan WHERE   kelompok='B' and idacc like '".$detilBL3->idacc.".%' ".($wilFilter=="konsolidasi"?"":" and id_cab=$wilFilter").") and status_validasi=1 and (concat(tahun,bulan) = '".$thn.$bln."' ) and  ( isjurnalbalik='off' or isjurnalbalik='0')";
				$queryB=$this->db->query($strB)->row();
				$jumlahB+=$queryB->hasilB;
				$jumlahBLevel3+=$queryB->hasilB;
				echo "<td  style=\"text-align:right\">Rp.&nbsp;".number_format($queryB->hasilB,2,',','.')."</td><td>&nbsp;</td>";
				echo "</tr>";

				
			}
		}
		echo "<tr><td>&nbsp;</td><td colspan=5 style=\"text-align:right\"><b><i>Jumlah Penyaluran</i></b></td><td style=\"text-align:right\"><b>Rp.&nbsp;".number_format($jumlahBLevel3,2,',','.')."</b></td></tr>";
		echo "<tr><td>&nbsp;</td><th colspan=5 style=\"text-align:right\"><b><i>Surplus (Defisit)</i></b></th><th style=\"text-align:right\"><b>Rp.&nbsp;".number_format(($jumlahPLevel3-$jumlahBLevel3),2,',','.')."</b></th></tr>";
		//saldo awal akun M
		$strInitM="select ifnull(sum(initval),0) saldo from ak_rekeningperkiraan where level=5 and  idacc  in (select idacc from ak_rekeningperkiraan where idacc like '".$level2->idacc.".%' ".($wilFilter=="konsolidasi"?"":" and id_cab=$wilFilter").") ";	//ZISAW
		$queryInitM=$this->db->query($strInitM)->row();
		$InitM=$queryInitM->saldo;
		
		//+ jurnal sblm periode pilih
		$strPLalu="SELECT ifnull(SUM(jumlah),0) AS hasilP FROM ak_jurnal WHERE idperkkredit IN (SELECT DISTINCT idacc FROM ak_rekeningperkiraan WHERE   kelompok='P' and idacc like '4.".$digitZis.".%' ".($wilFilter=="konsolidasi"?"":" and id_cab=$wilFilter").") and status_validasi=1 and (concat(tahun,bulan) < '".$thn.$bln."' ) and  ( isjurnalbalik='off' or isjurnalbalik='0')";
		$queryPLalu=$this->db->query($strPLalu)->row();
		$jmlLaluP=$queryPLalu->hasilP;

		
		//+ jurnal sblm periode pilih
		$strBLalu="SELECT ifnull(SUM(jumlah),0) AS hasilB FROM ak_jurnal WHERE idperkdebet IN (SELECT DISTINCT idacc FROM ak_rekeningperkiraan WHERE   kelompok='B' and idacc like '5.".$digitZis.".%' ".($wilFilter=="konsolidasi"?"":" and id_cab=$wilFilter").") and status_validasi=1 and (concat(tahun,bulan) < '".$thn.$bln."' ) and  ( isjurnalbalik='off' or isjurnalbalik='0')";
		$queryBLalu=$this->db->query($strBLalu)->row();
		$jmlLaluB=$queryBLalu->hasilB;
		
		$saldolAwal=($InitM+$jmlLaluP) - $jmlLaluB;
		echo "<tr><td>&nbsp;</td><th colspan=5 style=\"text-align:right\"><b><i>SALDO AWAL</i></b></th><th style=\"text-align:right\"><b>Rp.&nbsp;".number_format($saldolAwal,2,',','.')."</b></th></tr>";
		
		$saldo=$saldolAwal+($jumlahPLevel3-$jumlahBLevel3);
		echo "<tr><td>&nbsp;</td><th colspan=5 style=\"text-align:right\"><b><i>SALDO AKHIR</i></b></th><th style=\"text-align:right\"><b>Rp.&nbsp;".number_format($saldo,2,',','.')."</b></td></tr>";
		echo "<tr><td colspan=7>&nbsp;</td></tr>";
		$saldoAkhir+=$saldo;
		$i++;	
		
	}
	echo "<tr><td colspan=7>&nbsp;</td></tr>";
	echo "<tr><td >&nbsp;</td><th colspan=5 ><b>Jumlah Saldo Dana Zakat, Infaq/Shadaqoh, Amil, wakaf  s.d Periode $strBulan $thn</b></th><th  ><div align=right><b>Rp.&nbsp;".number_format($saldoAkhir,2,',','.')."</b></div></th></tr>";
	
?>
</table>
</div>
</div>
</div>
</div>
</div>
<?	
 if ($display==0){
	//$param=$thn."_".$bln."_1";
	$param=$thn."_".$bln."_1_".$sesLogin;
	if ($sesLogin=='center'){
		$param=$thn."_".$bln."_1_".$sesLogin."_".$wilayah;
	}
?>
<br>
<div class="row" style="text-align:center">
	<div class="col-md-12">	<input type="hidden" name="param" id="param" value="<?=$param?>">
		<!-- <button  id="btToExcel" class="btn btn-success"  >Cetak Xls</button>&nbsp; -->
		<a href="<?=base_url('rptPerubahanDana/result/'.$param)?>" class="btn btn-success">Cetak/Download</a><br>		
	</div>
</div>	
<?}?>
<script>
$('#btToExcel').click(function(){		
		//var form_data = $('#myformkel').serialize();
		//alert($('#param').val());
		$().showMessage('Sedang diproses.. Harap tunggu..');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('rptPerubahanDana/toExcel');?>',
			data: {param:$('#param').val()},				
			dataType: 'json',
			success: function(data,  textStatus, jqXHR){						
					window.open('<?=base_url("'+data.isi+'")?>','_blank');
					$().showMessage('Sukses.','success',1000);
				} ,
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				//$().showMessage('Terjadi kesalahan.<br />'+	textStatus + ' - ' + errorThrown ,'danger');
				bootbox.alert("Terjadi kesalahan. Data gagal disimpan."+	textStatus + " - " + errorThrown );
			},
			cache: false
		});
		//$().showMessage('Data pembelian berhasil disimpan, data order akan dikirim melalui sms','success',1000);
	});
</script>
