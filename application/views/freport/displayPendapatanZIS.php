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
?><br>
<div align=center>
<table  border="0" cellspacing=0 class="mytable mytable-bordered" align="center" width="95%">
<tr><th class="pojokKiriAtas" width="5%">&nbsp;</th><th COLSPAN=6></th> <th class="pojokKananAtas" width="5%">&nbsp;</th ></tr>
<tr><td></td><td colspan=7><b>NAMA CABANG <?=" : ".strtoupper($nmcabang->KOTA)?></b></td></tr>
<tr><td></td><td colspan=7><b>PERIODE <?=" : ".$strbulan." ".$thn?></td></b></tr>
<tr><td></td><td colspan=7>&nbsp;</td></b></tr>
<? 

$str="select * from ak_rekeningperkiraan WHERE id_cab=".$wilFilter." and status=1 and idacc like '4.%' and level=5 order by nama";
$rsZIS=$this->db->query($str)->result();
//echo "<tr><td colspan=5>".$str."</td></tr>";
?>
<tr><td></td><td></td><td colspan=5><b>Pendapatan </b></td><td align=right></td></tr>
<tr><td></td><td></td><td></td><td colspan=4><b>Pendapatan Dana </b></td><td align=right></td></tr>
<?	
$jumTotl=0;
if (sizeof ($rsZIS)<=0){
	echo "<tr><td colspan=8>Rekening Perkiraan Pendapatan ZIS Tidak ada atau bukan data Cabang Anda</td></tr>";
}else{
	
	foreach ($rsZIS as $detil){
		//$strJumlah="SELECT ifnull(SUM(jumlah),0) jmlzis from ak_jurnal WHERE sumber_data IN ('tsetoranzis','tinsidentil')  AND jenis='BKM' AND idperkkredit ='". $detil->idacc."' and status_validasi=1 and bulan='$bln' and tahun='$thn' and  ( isjurnalbalik='off' or isjurnalbalik='0')";
		$strJumlah="SELECT ifnull(SUM(jumlah),0) jmlzis from ak_jurnal WHERE sumber_data='penghimpunan' AND idperkkredit ='". $detil->idacc."' and status_validasi=1 and bulan='$bln' and tahun='$thn' and  ( isjurnalbalik='off' or isjurnalbalik='0')";
		$rsJmlZIS=$this->db->query($strJumlah)->row();
		if ($rsJmlZIS->jmlzis>0){
		echo "<tr>";
		echo "<td></td><td></td><td></td><td></td>";
		echo "<td colspan=2>".$detil->nama."</td>";
		echo "<td style='text-align:right'>Rp.&nbsp;".number_format($rsJmlZIS->jmlzis,2,',','.')."</td>";
		echo "<td></td>";
		echo "</tr>";
		//echo "<tr><td colspan=8>$strJumlah</td></tr>";
		}
		$jumTotl+=$rsJmlZIS->jmlzis;
	}
	echo "<tr><td></td><td></td><td></td><td colspan=3><b>Total Pendapatan Dana </b></td><td></td><td align=right><b>Rp.&nbsp;".number_format($jumTotl,2,',','.')."</b></td></tr>";
}
//$idrk_pusat=(strlen($this->session->userdata('auth')->ID_CABANG)>1?'2.1.03.':'2.1.03.0').$this->session->userdata('auth')->ID_CABANG.'.02';	
//setor ke pusat
//$idrk_pusat=(strlen($this->session->userdata('auth')->ID_CABANG)>1?'2.1.03.':'2.1.03.0').$this->session->userdata('auth')->ID_CABANG.'.02';
//tunai tanpa bank 1.1.1.01.01

$idrk_pusat=(strlen($wilFilter)>1?'2.1.3.':'2.1.3.0').$wilFilter.'.03';	
$strSetor="SELECT ifnull(SUM(jumlah),0) jmlsetor from ak_jurnal WHERE  sumber_data='penghimpunan' and idperkdebet IN(SELECT idacc from ak_rekeningperkiraan WHERE idacc = '$idrk_pusat') and status_validasi=1 and bulan='$bln' and tahun='$thn' and  ( isjurnalbalik='off' or isjurnalbalik='0')" ;
$rsSetor=$this->db->query($strSetor)->row();

$strSisaTunai="SELECT ifnull(SUM(jumlah),0) sisa from ak_jurnal WHERE  sumber_data='penghimpunan' and idperkdebet = '1.1.1.01.01' and status_validasi=1 and bulan='$bln' and tahun='$thn' and  ( isjurnalbalik='off' or isjurnalbalik='0') and keterangan like '%".$nmcabang->KOTA."%'" ;
$rsSisaTunai=$this->db->query($strSisaTunai)->row();

$harusSetor=$jumTotl-($rsSetor->jmlsetor-$rsSisaTunai->sisa);
echo "<tr><td colspan=7></td><td></td></tr>";	
echo "<tr><td></td><td></td><td colspan=5><b>Setoran Ke Pusat</b></td><td align=right></td></tr>";

echo "<tr><td></td><td></td><td></td><td></td><td colspan=3>Ditransfer langsung ke rek Pusat</td><td style='text-align:right'><b><u>Rp.&nbsp;".number_format( ($rsSetor->jmlsetor-$rsSisaTunai->sisa),2,',','.')."</u></b></td></tr>";

echo "<tr><td></td><td></td><td colspan=5><b>Sisa yang harus disetor ke Pusat</b></td><td align=right><b>Rp.&nbsp;".number_format($harusSetor,2,',','.')."</b></td></tr>";
?>
</table>
<table  border="0" cellspacing=0 class="mytable mytable-bordered" align="center" width="95%">
<tr><td colspan=6>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td colspan=5><?php echo $nmcabang->KOTA.", ".strftime('%d %B %Y')?></td></tr>
<tr><td colspan=6>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td colspan=2 style="text-align:center">Disusun Oleh</td><td colspan=2 style="text-align:center">Mengetahui</td></tr>
<tr><td colspan=6>&nbsp;</td></tr>
<tr><td colspan=6>&nbsp;</td></tr>
<tr><td colspan=6>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td colspan=2 style="text-align:center">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
<td colspan=2 style="text-align:center" >(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td></tr>

</table>
<div align=center>
</div>


</div></div>
</div></div>
<? if ($display==0){ 	
	$param=$thn."_".$bln."_1_".$sesLogin;
	if ($sesLogin=='center'){
		$param=$thn."_".$bln."_1_".$sesLogin."_".$wilayah;
	}
	?>
<br>
<div class="row" style="text-align:center">
	<div class="col-md-12">	<input type="hidden" name="param" id="param" value="<?=$param?>">
		<button  id="btToExcel" class="btn btn-success"  >Cetak Xls</button>&nbsp;
		<a href="<?=base_url('rptPendapatanZIS/result/'.$param)?>" class="btn btn-success">Cetak/Download</a><br>		
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
			url: '<?php echo base_url('rptPendapatanZIS/toExcel');?>',
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