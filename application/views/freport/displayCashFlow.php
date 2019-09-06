<div class="row">
<div class="col-md-12">
 <div class="box box-info">
 <div class="box-body">
<?	
$wilFilter='';
if ($sesLogin=='center'){
	$wilFilter=$wilayah;
}else{
	$wilFilter=$this->session->userdata('auth')->ID_CABANG;
}
$nmcabang=$this->gate_db->query('select KOTA from mst_cabang where ID_CABANG='.$wilFilter)->row();
$ketcabang=($wilFilter>1?'CABANG ':'').' '.(sizeof($nmcabang)>0?$nmcabang->KOTA:'');
$viewKop=$this->commonlib->tableKop('KEU-ACCOUNTING<BR>'.$ketcabang,$title, '00', '__','__', $this->session->userdata('param_company'),					$this->session->userdata('logo'));
echo $viewKop;	
$jumlah=0;
?><br>
<div align=center>
<table  border="0" cellspacing=0 class="mytable mytable-bordered" align="center" width="95%">
<tr><th class="pojokKiriAtas" width="5%">&nbsp;</th><th COLSPAN=4></th> <th class="pojokKananAtas" width="5%">&nbsp;</th ></tr>
<tr><td></td><td colspan=5><b>AKTIVITAS OPERASI</b></td></tr>
<tr><td></td><td colspan=5><b>Penerimaan</b> </td></tr>
<?	$rsPerkPend=$this->db->query("SELECT * FROM `ak_rekeningperkiraan` WHERE `kelompok` = 'P' AND `level` = '2' and status=1")->result();
	if (sizeof($rsPerkPend)>0){
		foreach($rsPerkPend as $row){
			$str="select ifnull(sum(jumlah),0) jml from ak_jurnal where jenis ='BNK' AND  idperkkredit in (select distinct idacc from ak_rekeningperkiraan where level=5 and idacc like '".$row->idacc."%'  and id_cab=".$wilFilter." and status=1 ) and bulan='$bln' and tahun='$thn' and status_validasi=1 and  ( isjurnalbalik='off' or isjurnalbalik='0')";
			$rsJumlahPerkPend=$this->db->query($str)->row();
			$jumlah+=$rsJumlahPerkPend->jml;
			echo "<tr><td></td><td></td>";
			echo "<td>".$row->idacc." - ".$row->nama."</td>";
			echo "<td style=\"text-align:right\">Rp. ".number_format($rsJumlahPerkPend->jml,2,',','.')."</td>";
			echo "<td colspan=2></td>";
			echo "</tr>";
		}
	}

	$rsPerkPiutang=$this->db->query("SELECT * FROM `ak_rekeningperkiraan` WHERE idacc like '1.1%' AND nama like '%Piutang%' and level=3  and status=1" )->result();
	if (sizeof($rsPerkPiutang)>0){
		foreach($rsPerkPiutang as $row){
			$str="select ifnull(sum(jumlah),0) jml from ak_jurnal where jenis <>'BNK' AND  idperkkredit in (select distinct idacc from ak_rekeningperkiraan where  level=5 and idacc like '".$row->idacc."%'  and id_cab=".$wilFilter." and status=1 ) and bulan='$bln' and tahun='$thn' and status_validasi=1 and  ( isjurnalbalik='off' or isjurnalbalik='0')";
			$rsJumlahPiutangDiKredit=$this->db->query($str)->row();
			$jumlah+=$rsJumlahPiutangDiKredit->jml;
			echo "<tr><td></td><td></td>";
			echo "<td>".$row->idacc." - Penerimaan Pembayaran ".$row->nama."</td>";
			echo "<td style=\"text-align:right\">Rp. ".number_format($rsJumlahPiutangDiKredit->jml,2,',','.')."</td>";
			echo "<td colspan=2></td>";
			echo "</tr>";
		}
	}
?>
<tr><td></td><td colspan=3><b>Jumlah Penerimaan</b></td><td colspan=2><b>Rp&nbsp;<?php echo number_format($jumlah,2,',','.')?></b></td></tr>
<tr><td></td><td colspan=5><b>Pengeluaran</b></td></tr>
<?	$jumB=0;
	$rsPerkBiaya=$this->db->query("SELECT * FROM `ak_rekeningperkiraan` WHERE `kelompok` = 'B' AND `level` = '3'  and status=1")->result();
	if (sizeof($rsPerkBiaya)>0){
		foreach($rsPerkBiaya as $row){
			$str="select ifnull(sum(jumlah),0) jml from ak_jurnal where jenis <>'BNK' AND  idperkdebet in (select distinct idacc from ak_rekeningperkiraan where idacc like '".$row->idacc."%'  and id_cab=".$wilFilter." and status=1 ) and bulan='$bln' and tahun='$thn' and status_validasi=1 and  ( isjurnalbalik='off' or isjurnalbalik='0')";
			$rsJumlahPerkBiaya=$this->db->query($str)->row();
			$jumlah-=$rsJumlahPerkBiaya->jml;
			$jumB+=$rsJumlahPerkBiaya->jml;
			echo "<tr><td></td><td></td>";
			echo "<td>".$row->idacc." - ".$row->nama."</td>";
			echo "<td style=\"text-align:right\">Rp. ".number_format($rsJumlahPerkBiaya->jml,2,',','.')."</td>";
			echo "<td colspan=2></td>";
			echo "</tr>";
		}
	}

	$rsPerkPengPiutang=$this->db->query("SELECT * FROM `ak_rekeningperkiraan` WHERE idacc like '1.1%' AND nama like '%Piutang%' and level=3  and status=1")->result();
	if (sizeof($rsPerkPengPiutang)>0){
		foreach($rsPerkPengPiutang as $row){
			$str="select ifnull(sum(jumlah),0) jml from ak_jurnal where jenis <>'BNK' AND  idperkdebet in (select distinct idacc from ak_rekeningperkiraan where idacc like '".$row->idacc."%'  and id_cab=".$wilFilter." and status=1 ) and bulan='$bln' and tahun='$thn' and status_validasi=1 and  ( isjurnalbalik='off' or isjurnalbalik='0')";
			$rsJumlahPiutangDiDebet=$this->db->query($str)->row();
			$jumlah-=$rsJumlahPiutangDiDebet->jml;
			$jumB+=$rsJumlahPiutangDiDebet->jml;
			echo "<tr><td></td><td></td>";
			echo "<td>".$row->idacc." - Pemberian ".$row->nama."</td>";
			echo "<td style=\"text-align:right\">Rp. ".number_format($rsJumlahPiutangDiDebet->jml,2,',','.')."</td>";
			echo "<td colspan=2></td>";
			echo "</tr>";
		}
	}

?>
<tr><td></td><td colspan=3><b>Jumlah Pengeluaran</b></td><td colspan=2><b>Rp <?php echo number_format($jumB,2,',','.')?></b></td></tr>
<tr><td></td><td colspan=4><b>Surplus (Defisit) Kas dari Aktivitas Operasi</b></td><td align=right><b>Rp&nbsp;<?php echo number_format($jumlah,2,',','.')?></b></td></tr>
<tr><td COLSPAN=6>&nbsp;</td></tr>
<tr><td COLSPAN=6>&nbsp;</td></tr>

<tr><td></td><td colspan=5><b>AKTIVITAS INVESTASI</b></td></tr>
<tr><td></td><td colspan=5><b>Penerimaan Investasi</b></td></tr>
<?	$rsInvestasi=$this->db->query("SELECT * FROM `ak_rekeningperkiraan` WHERE `kelompok` = 'M' AND `level` = '2' and status=1")->result();
	$jumPenerimaanInvestasi=0;
	if (sizeof($rsInvestasi)>0){
		foreach($rsInvestasi as $row){
			$str="select ifnull(sum(jumlah),0) jml from ak_jurnal where jenis <>'BNK' AND  idperkkredit in (select distinct idacc from ak_rekeningperkiraan where idacc like '".$row->idacc."%'  and id_cab=".$wilFilter." and status=1 ) and bulan='$bln' and tahun='$thn' and status_validasi=1 and  ( isjurnalbalik='off' or isjurnalbalik='0')";
			$rsJumlahInvestasi=$this->db->query($str)->row();
			$jumlah+=$rsJumlahInvestasi->jml;
			$jumPenerimaanInvestasi+=$rsJumlahInvestasi->jml;
			echo "<tr><td></td><td></td>";
			echo "<td>".$row->idacc." - Penerimaan Investasi : ".$row->nama."</td>";
			echo "<td style=\"text-align:right\">Rp. ".number_format($rsJumlahInvestasi->jml,2,',','.')."</td>";
			echo "<td colspan=2></td>";
			echo "</tr>";
		}
	}
	//jual aset tetap saja + investasi sukuk (kelompok A)
	$rsJualAset=$this->db->query("  SELECT * FROM `ak_rekeningperkiraan` WHERE idacc like '1.2.%' AND `level` = '3' and upper(nama) not like '%AKUM%' and status=1  ")->result();	
	if (sizeof($rsJualAset)>0){
		foreach($rsJualAset as $row){			
			echo "<tr><td></td><td></td>";
			echo "<td><b>".$row->idacc." - Penjualan Aset : ".$row->nama."</b></td>";
			echo "<td></td>";
			//echo "<td style=\"text-align:right\">Rp. ".number_format($rsJumlahJualAset->jml,2,',','.')."</td>";
			echo "<td colspan=2></td>";
			echo "</tr>";
			$rsJualAsetTetap5=$this->db->query("SELECT * FROM `ak_rekeningperkiraan` WHERE idparent like '".$row->idacc."%' and level=5 and  upper(nama) not like '%AKUM%'  and id_cab=".$wilFilter." and status=1 ")->result();	
			foreach($rsJualAsetTetap5 as $row5){
				$str="select ifnull(sum(jumlah),0) jml from ak_jurnal where jenis <>'BNK' AND  idperkkredit in (select distinct idacc from ak_rekeningperkiraan where idacc like '".$row5->idacc."%'  and id_cab=".$wilFilter." and status=1 ) and bulan='$bln' and tahun='$thn' and status_validasi=1 and  ( isjurnalbalik='off' or isjurnalbalik='0')";
				$rsJumlahJualAset=$this->db->query($str)->row();
				$jumlah+=$rsJumlahJualAset->jml;
				$jumPenerimaanInvestasi+=$rsJumlahJualAset->jml;
				//if ($rsJumlahJualAset->jml>0){
					echo "<tr><td></td><td></td>";
					echo "<td>".$row5->idacc." - Penjualan Aset : ".$row5->nama."</td>";
					echo "<td style=\"text-align:right\">Rp. ".number_format($rsJumlahJualAset->jml,2,',','.')."</td>";
					echo "<td colspan=2></td>";
					echo "</tr>";
				//}
			}
		}
	}

/*
	$rsBagiHasilInvest=$this->db->query("SELECT * FROM `ak_rekeningperkiraan` WHERE idacc like '4.3.04%' and id_cab=".$wilFilter." and level=5 and status=1 and upper(nama) not like '%AKUM%' and status=1")->result();	
	if (sizeof($rsBagiHasilInvest)>0){
		foreach($rsBagiHasilInvest as $row){
			$str="select ifnull(sum(jumlah),0) jml from ak_jurnal where jenis <>'BNK' AND  idperkkredit in (select distinct idacc from ak_rekeningperkiraan where idacc like '".$row->idacc."%'  and id_cab=".$wilFilter." and status=1 ) and bulan='$bln' and tahun='$thn' and status_validasi=1 and  ( isjurnalbalik='off' or isjurnalbalik='0')";
			$rsJumlahBagiHasilInvest=$this->db->query($str)->row();
			$jumlah+=$rsJumlahBagiHasilInvest->jml;
			$jumPenerimaanInvestasi+=$rsJumlahBagiHasilInvest->jml;
			echo "<tr><td></td><td></td>";
			echo "<td>".$row->idacc." - ".$row->nama."</td>";
			echo "<td style=\"text-align:right\">Rp. ".number_format($rsJumlahBagiHasilInvest->jml,2,',','.')."</td>";
			echo "<td colspan=2></td>";
			echo "</tr>";
		}
	}
	if ($wilFilter=='1'){	//pusat
		$rsBagiHasilInvest=$this->db->query("SELECT * FROM `ak_rekeningperkiraan` WHERE idacc like '4.%.99.01.01' and id_cab=1 and level=5 and status=1 and upper(nama) not like '%AKUM%' and status=1")->result();	
		if (sizeof($rsBagiHasilInvest)>0){
			foreach($rsBagiHasilInvest as $row){
				$str="select ifnull(sum(jumlah),0) jml from ak_jurnal where jenis <>'BNK' AND  idperkkredit in (select distinct idacc from ak_rekeningperkiraan where idacc like '".$row->idacc."%'  and id_cab=".$wilFilter." and status=1 ) and bulan='$bln' and tahun='$thn' and status_validasi=1 and  ( isjurnalbalik='off' or isjurnalbalik='0')";
				$rsJumlahBagiHasilInvest=$this->db->query($str)->row();
				$jumlah+=$rsJumlahBagiHasilInvest->jml;
				$jumPenerimaanInvestasi+=$rsJumlahBagiHasilInvest->jml;
				echo "<tr><td></td><td></td>";
				echo "<td>".$row->idacc." - ".$row->nama."</td>";
				echo "<td style=\"text-align:right\">Rp. ".number_format($rsJumlahBagiHasilInvest->jml,2,',','.')."</td>";
				echo "<td colspan=2></td>";
				echo "</tr>";
			}
		}
	}

*/
?>
<tr><td></td><td colspan=3><b>Jumlah Penerimaan Investasi</b></td><td colspan=2><b>Rp&nbsp;<?php echo number_format($jumPenerimaanInvestasi,2,',','.')?></b></td></tr>
<tr><td></td><td colspan=5><b>Pengeluaran Investasi </b></td></tr>
<?	
	$rsInvestasi=$this->db->query("SELECT * FROM `ak_rekeningperkiraan` WHERE `kelompok` ='M' AND `level` = '2' and status=1")->result();
	$jumPengeluaranInvestasi=0;
	if (sizeof($rsInvestasi)>0){
		foreach($rsInvestasi as $row){
			$str="select ifnull(sum(jumlah),0) jml from ak_jurnal where jenis <>'BNK' AND  idperkdebet in (select distinct idacc from ak_rekeningperkiraan where idacc like '".$row->idacc."%'   and id_cab=".$wilFilter." and status=1 ) and bulan='$bln' and tahun='$thn' and status_validasi=1 and  ( isjurnalbalik='off' or isjurnalbalik='0')";
			$rsJumlahInvestasi=$this->db->query($str)->row();
			$jumlah-=$rsJumlahInvestasi->jml;
			$jumPengeluaranInvestasi+=$rsJumlahInvestasi->jml;
			echo "<tr><td></td><td></td>";
			echo "<td>".$row->idacc." - Pengeluaran Investasi : ".$row->nama."</td>";
			echo "<td style=\"text-align:right\">Rp. ".number_format($rsJumlahInvestasi->jml,2,',','.')."</td>";
			echo "<td colspan=2></td>";
			echo "</tr>";
		}
	}

	$rsBeliAset=$this->db->query(" SELECT * FROM `ak_rekeningperkiraan` WHERE idacc like '1.2.%' AND `level` = '3' and upper(nama) not like '%AKUM%' and status=1")->result();	
	
	if (sizeof($rsBeliAset)>0){
		foreach($rsBeliAset as $row){
			
			echo "<tr><td></td><td></td>";
			echo "<td><b>".$row->idacc." - Pengadaan Aset : ".$row->nama."</b></td>";
			echo "<td></td>";
			//echo "<td style=\"text-align:right\">Rp. ".number_format($rsJumlahBeliAset->jml,2,',','.')."</td>";
			echo "<td colspan=2></td>";
			echo "</tr>";

			$rsBeliAset5=$this->db->query("SELECT * FROM `ak_rekeningperkiraan` WHERE idparent like '".$row->idacc."%' and level=5 and  upper(nama) not like '%AKUM%'  and id_cab=".$wilFilter." and status=1 ")->result();	
			foreach($rsBeliAset5 as $row5){
				$str="select ifnull(sum(jumlah),0) jml from ak_jurnal where jenis <>'BNK' AND  idperkdebet in (select distinct idacc from ak_rekeningperkiraan where idacc like '".$row5->idacc."%'  and id_cab=".$wilFilter." and status=1 ) and bulan='$bln' and tahun='$thn' and status_validasi=1 and  ( isjurnalbalik='off' or isjurnalbalik='0')";
				$rsJumlahBeliAset=$this->db->query($str)->row();
				$jumlah-=$rsJumlahBeliAset->jml;
				$jumPengeluaranInvestasi+=$rsJumlahBeliAset->jml;
				//if ($rsJumlahJualAset->jml>0){
					echo "<tr><td></td><td></td>";
					echo "<td>".$row5->idacc." - Pengadaan Aset : ".$row5->nama."</td>";
					echo "<td style=\"text-align:right\">Rp. ".number_format($rsJumlahBeliAset->jml,2,',','.')."</td>";
					echo "<td colspan=2></td>";
					echo "</tr>";
				//}

			}
		}
	}
?>

<tr><td></td><td colspan=3><b>Jumlah Pengeluaran Investasi</b></td><td colspan=2><b>Rp&nbsp;<?php echo number_format($jumPengeluaranInvestasi,2,',','.')?></b></td></tr>

<tr><td></td><td colspan=4><b>Surplus (Defisit) Kas dari Aktivitas Investasi</b></td><td align=right><b>Rp&nbsp;<?php echo number_format($jumPenerimaanInvestasi-$jumPengeluaranInvestasi,2,',','.')?></b></td></tr>
<tr><td COLSPAN=6>&nbsp;</td></tr>
<tr><td COLSPAN=6>&nbsp;</td></tr>

<tr><td></td><td colspan=5><b>AKTIVITAS PENDANAAN</b></td></tr>
<tr><td></td><td colspan=5><b>Penerimaan Liabilitas/Hutang</b> </td></tr>
<?
	$rsPenerimaanHutang=$this->db->query("SELECT * FROM `ak_rekeningperkiraan` WHERE `kelompok` = 'K' AND `level` = '3' and status=1")->result();
	$jmlJumlahPenerimaanHutang=0;
	if (sizeof($rsPenerimaanHutang)>0){
		foreach($rsPenerimaanHutang as $row){
			$str="select ifnull(sum(jumlah),0) jml from ak_jurnal where jenis <>'BNK' AND  idperkkredit in (select distinct idacc from ak_rekeningperkiraan where idacc like '".$row->idacc."%'  and id_cab=".$wilFilter." and status=1 ) and bulan='$bln' and tahun='$thn' and status_validasi=1 and  ( isjurnalbalik='off' or isjurnalbalik='0')";
			$rsJumlahPenerimaanHutang=$this->db->query($str)->row();
			$jumlah+=$rsJumlahPenerimaanHutang->jml;
			$jmlJumlahPenerimaanHutang+=$rsJumlahPenerimaanHutang->jml;
			echo "<tr><td></td><td></td>";
			echo "<td>".$row->idacc." - ".$row->nama."</td>";
			echo "<td style=\"text-align:right\">Rp. ".number_format($rsJumlahPenerimaanHutang->jml,2,',','.')."</td>";
			echo "<td colspan=2></td>";
			echo "</tr>";
		}
	}


?>
<tr><td></td><td colspan=3><b>Jumlah Penerimaan Liabilitas/Hutang</b></td><td colspan=2><b>Rp&nbsp;<?php echo number_format($jmlJumlahPenerimaanHutang,2,',','.')?></b></td></tr>
<tr><td></td><td colspan=5><b>Pembayaran Liabilitas/Hutang</b> </td></tr>
<?
	$rsBayarHutang=$this->db->query("SELECT * FROM `ak_rekeningperkiraan` WHERE `kelompok` = 'K' AND `level` = '3' and status=1")->result();
	$jmlJumlahBayarHutang=0;
	if (sizeof($rsBayarHutang)>0){
		foreach($rsBayarHutang as $row){
			$str="select ifnull(sum(jumlah),0) jml from ak_jurnal where jenis <>'BNK' AND  idperkdebet in (select distinct idacc from ak_rekeningperkiraan where idacc like '".$row->idacc."%'  and id_cab=".$wilFilter." and status=1 ) and bulan='$bln' and tahun='$thn' and status_validasi=1 and  ( isjurnalbalik='off' or isjurnalbalik='0')";
			$rsJumlahBayarHutang=$this->db->query($str)->row();
			$jumlah-=$rsJumlahBayarHutang->jml;
			$jmlJumlahBayarHutang+=$rsJumlahBayarHutang->jml;
			echo "<tr><td></td><td></td>";
			echo "<td>".$row->idacc." - ".$row->nama."</td>";
			echo "<td style=\"text-align:right\">Rp. ".number_format($rsJumlahBayarHutang->jml,2,',','.')."</td>";
			echo "<td colspan=2></td>";
			echo "</tr>";
		}
	}
?>
<tr><td></td><td colspan=3><b>Jumlah Pembayaran Liabilitas/Hutang</b></td><td colspan=2><b>Rp&nbsp;<?php echo number_format($jmlJumlahBayarHutang,2,',','.')?></b></td></tr>
<tr><td></td><td colspan=4><b>Surplus (Defisit) Kas dari Aktivitas Pendanaan</b></td><td align=right><u><b>Rp&nbsp;<?php echo number_format($jmlJumlahPenerimaanHutang-$jmlJumlahBayarHutang,2,',','.')?></b></u></td></tr>
<tr><td COLSPAN=6>&nbsp;</td></tr>
<tr><td COLSPAN=6>&nbsp;</td></tr>

<tr><td></td><td colspan=4><b>KENAIKAN (PENURUNAN) NETO DALAM KAS DAN SETARA KAS</b></td><td align=right><b>
<?php echo ($jumlah<0?"( Rp.&nbsp;".number_format($jumlah,2,',','.')." )":"Rp.&nbsp;".number_format($jumlah,2,',','.'))?></b></td</tr>
<? $initval=$this->report_model->getInitValCashFlow($wilFilter);
$saldoLalu=$this->report_model->getSaldoLaluCashFlow($bln, $thn, $wilFilter);
$jumlahAwal=$initval+$saldoLalu;
$saldoAkhir=$jumlah+$jumlahAwal;
//echo "<tr><td colspan=6>$saldoLalu</td></tr>";
?>
<tr><td></td><td colspan=4><b>KAS DAN SETARA KAS PADA AWAL BULAN</b></td><td align=right><b>
<?php echo ($jumlahAwal<0?"( Rp.&nbsp;".number_format($jumlahAwal,2,',','.')." )":"Rp.&nbsp;".number_format($jumlahAwal,2,',','.'))?></b></td></tr>

<tr><td></td><td colspan=4><b><h4>KAS DAN SETARA KAS PADA AKHIR BULAN</h4></b></td><td align=right><h4><b>
<?php echo ($saldoAkhir<0?"( Rp.&nbsp;".number_format($saldoAkhir,2,',','.')." )":"Rp.&nbsp;".number_format($saldoAkhir,2,',','.'))?></b></h4></td></tr>
<tr><th class="pojokKiriBawah">&nbsp;</th><th COLSPAN=4></th><th class="pojokKananBawah" width="5%">&nbsp;</th></tr>
</table>
</div></div>
</div></div></div>
<? if ($display==0){ 	
	$param=$thn."_".$bln."_1_".$sesLogin;
	if ($sesLogin=='center'){
		$param=$thn."_".$bln."_1_".$sesLogin."_".$wilayah;
	}
	?>
<br>
<div class="row" style="text-align:center">
	<div class="col-md-12">	<input type="hidden" name="param" id="param" value="<?=$param?>">
		<!-- <button  id="btToExcel" class="btn btn-success">Cetak Xls</button>&nbsp; -->
		<a href="<?=base_url('rptCashFlow/result/'.$param)?>" class="btn btn-success">Cetak/Download</a><br>		
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
			url: '<?php echo base_url('rptCashFlow/toExcel');?>',
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
	});
</script>