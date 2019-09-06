<?		
	$viewKop=$this->commonlib->tableKop(strtoupper($nmcabang->KOTA),$title, '00', '__','__', $this->session->userdata('param_company'), $this->session->userdata('logo'));
	echo $viewKop;
?>
<br>
<table class="mytable mytable-bordered">
    <thead><tr><th colspan=2>DATA PERSONAL</th></tr></thead>
    <tbody>
	<tr><td width="35%">NAMA PEMINJAM</td><td><?=$resMaster->NAMA;?></td></tr>      
	<tr><td>JUMLAH PINJAMAN</td><td>Rp.&nbsp;<?=number_format($resMaster->JUMLAH,0,',','.')?></td></tr>      
	<tr><td>LAMA ANGSURAN</td><td><?=$resMaster->LAMA;?></td></tr>      
	<tr><td>TANGGAL MEMINJAM</td><td><?=strftime('%d %B %Y',strtotime($resMaster->TGL_PINJAM));?></td></tr>      
	<tr><td>STATUS PINJAMAN</td><td><?=$resMaster->STATUS;?></td></tr>    
</tbody>
</table>
<table class="table table-bordered">
    <thead><tr><th >NO</th><th >ANGS. KE</th><th >TGL BAYAR</th><th >JML BAYAR</th><th >STATUS</th></tr></thead>
    <tbody>
<?	$jmlBayar=0;
	$jmlCicil=0;
	$i=1;
	if (sizeof($resDetil)>0){
		foreach ($resDetil as $detil){
			$jmlBayar+=$detil->JML_BAYAR;
			$jmlCicil+=($detil->JML_BAYAR<=0?0:1);
			$status=($detil->JML_BAYAR<=0?"Belum Lunas":"Lunas");
			echo "<tr>";
			echo "<td>$i</td>";
			echo "<td>".$detil->CICILAN_KE."</td>";
			echo "<td>".($detil->TGL_BAYAR==""?'-':strftime('%d %B %Y',strtotime($detil->TGL_BAYAR)))."</td>";
			echo "<td STYLE=\"text-align:right\">Rp. ".number_format($detil->JML_BAYAR,0,',','.')."</td>";
			echo "<td>".$status."</td>";
			echo "</tr>";
			$i++;
		}
	}else{
		echo "<tr>";
		echo "<td colspan=5>Belum Ada cicilan</td>";
		echo "</tr>";
	}
	echo "<tr><th>&nbsp;</th><th colspan=2>Jumlah Bayar</th><th STYLE=\"text-align:right\">Rp. ".number_format($jmlBayar,0,',','.')."</th><th>&nbsp;</th></tr>";
	echo "<tr><th>&nbsp;</th><th colspan=2>Sudah Dicicil </th><th colspan=2>".$jmlCicil." Kali"."</th></tr>";
	echo "<tr><th>&nbsp;</th><th colspan=2>Kekurangan </th><th colspan=2>".($resMaster->LAMA-$jmlCicil)." Kali, Sebesar : Rp.".number_format(($resMaster->JUMLAH-$jmlBayar),0,',','.')."</th></tr>";
?>
	</tbody>
</table><br>
<? if ($display==0){
	$param=$id_head."_1";
?>
<div class="row" style="text-align:center">
	<div class="col-md-12">	
		<a href="<?=base_url('rptPiutang/personalLoan/'.$param)?>" class="btn btn-success">Print</a><br>
		<!-- <button id="btPrint_dp" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Cetak Data Pribadi">Print Data Pribadi</button> -->
	</div>
</div>	
<?}?>