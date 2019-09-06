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
$nmcabang=$this->db->query('select KOTA from mst_cabang where ID_CABANG='.$wilFilter)->row();
$ketcabang=($wilFilter>1?'CABANG ':'').' '.(sizeof($nmcabang)>0?$nmcabang->KOTA:'');
$viewKop=$this->commonlib->tableKop('KEU-ACCOUNTING<BR>'.$ketcabang,$title, '00', '__','__', $this->session->userdata('param_company'),					$this->session->userdata('logo'));
	echo $viewKop;	
?><br>
<div align=center>
<table  border="0" cellspacing=0 class="mytable mytable-bordered" align="center" width="95%">
<tr><th class="pojokKiriAtas" width="5%">&nbsp;</th><th COLSPAN=4></th> <th class="pojokKananAtas" width="5%">&nbsp;</th ></tr>
<tr><td></td><td><b>AKTIVITAS OPERASI</b></td><td></td><td></td><td></td><td></td></tr>
<? 

$str="select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkkredit in (select distinct idacc from ak_rekeningperkiraan where idacc like '4.%'  and id_cab=".$wilFilter." and status=1 ) and bulan='$bln' and tahun='$thn' and status_validasi=1 and  ( isjurnalbalik='off' or isjurnalbalik='0')";
$rsJumlahZIS=$this->db->query($str)->row();
$jumlah=$rsJumlahZIS->jml;
//error disini krn problem akun pusat-cabang
?>
<tr><td></td><td>Penerimaan Kas dari Pelanggan (ZIS : <?php echo strtoupper($ketcabang)?>)</td><td align=right><?php echo "Rp. ".number_format($jumlah,2,',','.')?></td><td></td><td></td><td></td></tr>
<?	
$rsJumlahExpense=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkdebet in (select distinct idacc from ak_rekeningperkiraan where status=1 and id_cab=".$wilFilter." and idacc like '5.%' and upper(nama) not like '%PENYUSUTAN%') and bulan='$bln' and tahun='$thn' and status_validasi=1 and  ( isjurnalbalik='off' or isjurnalbalik='0')")->row();
$jumlah=$jumlah-$rsJumlahExpense->jml;
?>
<tr><td></td><td>Pembayaran Kas Kepada Pemasok dan Karyawan & Untuk Beban Usaha (Expenses)</td><td align=right><?php echo "( Rp. ".number_format($rsJumlahExpense->jml,2,',','.')." )"?></td><td></td><td></td><td></td></tr>
<?	
$rsJumlahPiutangDiKredit=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkkredit in (SELECT DISTINCT idacc from ak_rekeningperkiraan WHERE (idacc LIKE '1.1.07%' OR idacc LIKE '1.1.08%' OR idacc LIKE '1.1.09%' OR idacc LIKE '1.1.10%' OR idacc LIKE '1.1.14%')  AND idacc NOT IN ( SELECT idacc from ak_rekeningperkiraan WHERE (idacc LIKE '1.1.01%' OR idacc LIKE '1.1.02%')) AND LEVEL >2 AND STATUS=1 AND id_cab=".$wilFilter."  ) and bulan='$bln' and tahun='$thn' and status_validasi=1 and  ( isjurnalbalik='off' or isjurnalbalik='0')")->row();
$jumlah=$jumlah+$rsJumlahPiutangDiKredit->jml;
?>
<tr><td></td><td>Penerimaan Pembayaran Piutang & Uang Muka</td><td align=right><?php echo " Rp. ".number_format($rsJumlahPiutangDiKredit->jml,2,',','.')?></td><td></td><td></td><td></td></tr>
<?	
$rsJumlahPiutang=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkdebet in (SELECT DISTINCT idacc from ak_rekeningperkiraan WHERE (idacc LIKE '1.1.07%' OR idacc LIKE '1.1.08%' OR idacc LIKE '1.1.09%' OR idacc LIKE '1.1.10%' OR idacc LIKE '1.1.14%')  AND idacc NOT IN ( SELECT idacc from ak_rekeningperkiraan WHERE (idacc LIKE '1.1.01%' OR idacc LIKE '1.1.02%')) AND LEVEL >2 AND STATUS=1 AND id_cab=".$wilFilter."  ) and bulan='$bln' and tahun='$thn' and status_validasi=1 and  ( isjurnalbalik='off' or isjurnalbalik='0')")->row();
$jumlah=$jumlah-$rsJumlahPiutang->jml;
?>
<tr><td></td><td>Pemberian Piutang & Uang Muka</td><td align=right><?php echo "( Rp. ".number_format($rsJumlahPiutang->jml,2,',','.')." )"?></td><td></td><td></td><td></td></tr>

<tr><td></td><td><b>Kas dihasilkan dari Operasi</b></td><td></td><td align=right><b>
<?php echo ($jumlah<0?"( Rp. ".number_format($jumlah,2,',','.')." )":"Rp. ".number_format($jumlah,2,',','.'))?></b></td><td></td><td></td></tr>
<tr><td COLSPAN=6>&nbsp;</td></tr>

<!-- <?
$rsJumlahPL=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkkredit in (select distinct idacc from ak_rekeningperkiraan where   status=1  and id_cab=".$wilFilter."  and idacc like '8-%') and bulan='$bln' and tahun='$thn' and status_validasi=1 and  ( isjurnalbalik='off' or isjurnalbalik='0')")->row();
$jumlah=$jumlah+$rsJumlahPL->jml;
?>
<tr><td></td><td>Pendapatan (Beban) Di Luar Usaha </td><td align=right><?php echo "Rp. ".number_format($rsJumlahPL->jml,2,',','.')?></td><td></td><td></td><td></td></tr>
<?	$rsJumlahExpenseOther=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkdebet in (select distinct idacc from ak_rekeningperkiraan where status=1 and id_cab=".$wilFilter."  and idacc like '9-%') and bulan='$bln' and tahun='$thn' and status_validasi=1 and  ( isjurnalbalik='off' or isjurnalbalik='0')")->row();
$jumlahLain=$rsJumlahPL->jml-$rsJumlahExpenseOther->jml;
$jumlah=$jumlah-$rsJumlahExpenseOther->jml;
?>
<tr><td></td><td>Beban Lain-lain</td><td align=right><?php echo "( Rp. ".number_format($rsJumlahExpenseOther->jml,2,',','.')." )"?></td><td></td><td></td><td></td></tr>
<tr><td></td><td><b>Kas dihasilkan dari Lain-lain</b></td><td></td><td align=right><b>
<?php echo ($jumlahLain<0?"( Rp. ".number_format($jumlahLain,2,',','.')." )":"Rp. ".number_format($jumlahLain,2,',','.'))?></b></td><td></td><td></td></tr>
<tr><td COLSPAN=6>&nbsp;</td></tr>
 -->
<tr><td></td><td><b>Jumlah Kas Bersih dari Aktivitas Operasi</b></td><td></td><td></td><td align=right><b>
<?php echo ($jumlah<0?"( Rp. ".number_format($jumlah,2,',','.')." )":"Rp. ".number_format($jumlah,2,',','.'))?></b></td><td></td></tr>
<tr><td COLSPAN=6>&nbsp;</td></tr>
<tr><td COLSPAN=6>&nbsp;</td></tr>

<tr><td></td><td><b>AKTIVITAS INVESTASI</b></td><td></td><td></td><td></td><td></td></tr>
<?
$rsJumlahModal=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkkredit in (select distinct idacc from ak_rekeningperkiraan where  status=1 and id_cab=".$wilFilter."  and idacc like '3.%') and bulan='$bln' and tahun='$thn' and status_validasi=1 and  ( isjurnalbalik='off' or isjurnalbalik='0')")->row();
$jumlah=$jumlah+$rsJumlahModal->jml;
?>
<tr><td></td><td>Penerimaan Investasi</td><td align=right><?php echo "Rp. ".number_format($rsJumlahModal->jml,2,',','.')?></td><td></td><td></td><td></td></tr>
<?
$rsJumlahJualAset=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkkredit in (select distinct idacc from ak_rekeningperkiraan where  status=1 and id_cab=".$wilFilter."  and idacc like '1.2.01%' and upper(nama) not like '%AKUM%') and bulan='$bln' and tahun='$thn' and status_validasi=1 and  ( isjurnalbalik='off' or isjurnalbalik='0')")->row();
$jumlah=$jumlah+$rsJumlahJualAset->jml;
?>
<tr><td></td><td>Penjualan Asset</td><td align=right><?php echo "Rp. ".number_format($rsJumlahJualAset->jml,2,',','.')?></td><td></td><td></td><td></td></tr>
<?
$rsJumlahBeliAset=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkdebet in (select distinct idacc from ak_rekeningperkiraan where status=1  and id_cab=".$wilFilter."  and idacc like '1.2.01%' and upper(nama) not like '%AKUM%') and bulan='$bln' and tahun='$thn' and status_validasi=1 and  ( isjurnalbalik='off' or isjurnalbalik='0')")->row();
$jumlah=$jumlah-$rsJumlahBeliAset->jml;

?>
<tr><td></td><td>Pembelian Semua Aset Bersih </td><td align=right><?php echo "( Rp. ".number_format($rsJumlahBeliAset->jml,2,',','.').")"?></td><td></td><td></td><td></td></tr>
<?
$rsJumlahKeluarInvestasi=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkdebet in (select distinct idacc from ak_rekeningperkiraan where status=1  and id_cab=".$wilFilter."  and idacc like '1.2.03%') and bulan='$bln' and tahun='$thn' and status_validasi=1 and  ( isjurnalbalik='off' or isjurnalbalik='0')")->row();
$jumlah=$jumlah-$rsJumlahKeluarInvestasi->jml;
$jumlahInvestasi=($rsJumlahModal->jml+$rsJumlahJualAset->jml)-($rsJumlahBeliAset->jml+$rsJumlahKeluarInvestasi->jml);
?>
<tr><td></td><td>Pengeluaran Investasi</td><td align=right><?php echo "( Rp. ".number_format($rsJumlahKeluarInvestasi->jml,2,',','.').")"?></td><td></td><td></td><td></td></tr>
<tr><td></td><td><b>Jumlah Kas Bersih dari Aktivitas Investasi</b></td><td></td><td></td><td align=right><b>
<?php echo ($jumlahInvestasi<0?"( Rp. ".number_format($jumlahInvestasi,2,',','.')." )":"Rp. ".number_format($jumlahInvestasi,2,',','.'))?></b></td><td></td></tr>
<tr><td COLSPAN=6>&nbsp;</td></tr>
<tr><td COLSPAN=6>&nbsp;</td></tr>
<tr><td></td><td><b>AKTIVITAS PENDANAAN</b></td><td></td><td></td><td></td><td></td></tr>
<?
$rsJumlahTerimaHutang=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkkredit in (select distinct idacc from ak_rekeningperkiraan where  status=1  and id_cab=".$wilFilter."  and idacc like '2.%') and bulan='$bln' and tahun='$thn' and status_validasi=1 and  ( isjurnalbalik='off' or isjurnalbalik='0')")->row();
$jumlah=$jumlah+$rsJumlahTerimaHutang->jml;
?>
<tr><td></td><td>Penerimaan Liabilitas/Hutang </td><td align=right><?php echo " Rp. ".number_format($rsJumlahTerimaHutang->jml,2,',','.')?></td><td></td><td></td><td></td></tr>
<?
$rsJumlahHutang=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkdebet in (select distinct idacc from ak_rekeningperkiraan where  status=1  and id_cab=".$wilFilter."  and idacc like '2.%') and bulan='$bln' and tahun='$thn' and status_validasi=1 and  ( isjurnalbalik='off' or isjurnalbalik='0')")->row();
$jumlah=$jumlah-$rsJumlahHutang->jml;
?>
<tr><td></td><td>Pembayaran Liabilitas/Hutang </td><td align=right><?php echo "( Rp. ".number_format($rsJumlahHutang->jml,2,',','.').")"?></td><td></td><td></td><td></td></tr>
<tr><td></td><td><b>Jumlah Kas Bersih dari Aktivitas Pendanaan</b></td><td></td><td></td><td align=right><u><b>
<?php 
	$aktPendanaan=$rsJumlahTerimaHutang->jml-$rsJumlahHutang->jml;
	if ($aktPendanaan<0){
		echo "( Rp. ".number_format($aktPendanaan,2,',','.')." )";
	}else{
		echo " Rp. ".number_format($aktPendanaan,2,',','.');
	}
?>
</b></u></td><td></td></tr>
<tr><td COLSPAN=6>&nbsp;</td></tr>
<tr><td COLSPAN=6>&nbsp;</td></tr>
<tr><td></td><td><b>KENAIKAN (PENURUNAN) NETO DALAM KAS DAN SETARA KAS</b></td><td></td><td></td><td align=right><b>
<?php echo ($jumlah<0?"( Rp. ".number_format($jumlah,2,',','.')." )":"Rp. ".number_format($jumlah,2,',','.'))?></b></td><td></td></tr>
<? $initval=$this->report_model->getInitValCashFlow();
$saldoLalu=$this->report_model->getSaldoLaluCashFlow($bln, $thn, $wilFilter);
$jumlahAwal=$initval+$saldoLalu;
$saldoAkhir=$jumlah+$jumlahAwal;
//echo "<tr><td colspan=6>$saldoLalu</td></tr>";
?>
<tr><td></td><td><b>KAS DAN SETARA KAS PADA AWAL BULAN</b></td><td></td><td></td><td align=right><b>
<?php echo ($jumlahAwal<0?"( Rp. ".number_format($jumlahAwal,2,',','.')." )":"Rp. ".number_format($jumlahAwal,2,',','.'))?></b></td><td></td></tr>

<tr><td></td><td><b><h4>KAS DAN SETARA KAS PADA AKHIR BULAN</h4></b></td><td></td><td></td><td align=right><h4><b>
<?php echo ($saldoAkhir<0?"( Rp. ".number_format($saldoAkhir,2,',','.')." )":"Rp. ".number_format($saldoAkhir,2,',','.'))?></b></h4></td><td></td></tr>
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
		<button  id="btToExcel" class="btn btn-success">Cetak Xls</button>&nbsp;
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