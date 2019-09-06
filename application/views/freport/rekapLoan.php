<?		
	$viewKop=$this->commonlib->tableKop(strtoupper($nmcabang->KOTA),$title, '00', '__','__', $this->session->userdata('param_company'), $this->session->userdata('logo'));
	echo $viewKop;
?>
<br>
<table class="mytable mytable-bordered" >
    <thead><tr><th >NO</th><th >NAMA</th><th >JENIS&nbsp;PEMINJAM</th><th >JML PINJAMAN</th><th >KEPERLUAN</th><th >CICILAN </th><th >SUDAH DICICIL</th><th >KEKURANGAN</th><th >STATUS</th></tr></thead>
    <tbody>
<?	$jmlBayar=0;
	$jmlCicil=0;
	$i=1;
	if (sizeof($resMaster)>0){
	foreach ($resMaster as $detil){
		$kurang=$detil->JUMLAH-$detil->SDH_BAYAR;
		//$jmlCicil+=($detil->JML_BAYAR<=0?0:1);
		//$status=($detil->JML_BAYAR<=0?"Belum Lunas":"Lunas");
		echo "<tr>";
		echo "<td>$i</td>";
		echo "<td>".str_replace(" ","&nbsp;",$detil->NAMA)."</td>";
		echo "<td>".$detil->JNS_PEMINJAM."</td>";
		echo "<td STYLE=\"text-align:right\">Rp.&nbsp;".number_format($detil->JUMLAH,0,',','.')."</td>";
		echo "<td>".$detil->KEPERLUAN."</td>";
		echo "<td>".$detil->LAMA."&nbsp;x&nbsp;".$detil->JML_CICILAN."</td>";
		echo "<td>".$detil->JML_ANGS."&nbsp;kali<br>&nbsp;total&nbsp;bayar&nbsp;Rp.&nbsp;".number_format($detil->SDH_BAYAR,0,',','.')."</td>";
		echo "<td>".($detil->LAMA-$detil->JML_ANGS)."&nbsp;kali<br>&nbsp;sebesar&nbsp;Rp.&nbsp;".number_format($kurang,0,',','.')."</td>";
		//echo "<td>".($detil->TGL_BAYAR==""?'-':strftime('%d %B %Y',strtotime($detil->TGL_BAYAR)))."</td>";
		echo "<td >".str_replace(" ","&nbsp;",$detil->STATUS)."</td>";
		echo "</tr>";
		$i++;
	}
	}else{
		echo "<tr><td colspan=9 align=\"center\">Belum ada data pinjaman</td></tr>";
	}
	
?>
	</tbody>
</table><br>
<? if ($display==0){
	$jns_status=($jns_status=="Belum Lunas"?"Belum_lunas":$jns_status);
	$param=$jns_status."_1";
?>
<div class="row" style="text-align:center">
	<div class="col-md-12">	
		<a href="<?=base_url('rptPiutang/rekapLoan/'.$param)?>" class="btn btn-success">Cetak/Download</a><br>
		<!-- <button id="btPrint_dp" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Cetak Data Pribadi">Print Data Pribadi</button> -->
	</div>
</div>	
<?}?>