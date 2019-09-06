<div class="row">
<div class="col-md-12">
 <div class="box box-info">
 <div class="box-body">
<?	
	$viewKop=$this->commonlib->tableKop(strtoupper($nmcabang->KOTA),$title, '00', '__','__', $this->session->userdata('param_company'), $this->session->userdata('logo'));
	echo $viewKop;
	//echo $strMaster;
	
	//tambah cek idacc isparent?
		$strCek="select count(*) cek from ak_rekeningperkiraan where idparent='".$idacc."'";
		$resCek= $this->db->query($strCek)->row();	
if ($resCek->cek<=0){
		 ?>
<br>
<div class="alert alert-info alert-dismissable">
<i class="fa fa-info"></i>
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
<b>Rekening Perkiraan : <?php echo "[ ".$idacc." ]&nbsp;".strtoupper($accName);?></b> 
</div><br>
<div align=center>
<table  border="0" cellspacing=0 class="mytable mytable-bordered" align="center" width="95%">
<tr><th class="pojokKiriAtas" width="5%">No</th><th width="10%">Tanggal</th> <th>Keterangan</th><th width="15%">Debet</th><th width="15%">Kredit</th><th class="pojokKananAtas" width="20%">Saldo</th ></tr>
<?
$blnIdk=$this->arrIntBln;
//saldo bulan lalu = initval + bln-1
$initval=$this->report_model->getInitVal($idacc);
$saldoLalu=$this->report_model->getSaldoLaluWithInterval($idacc, $tgl1);
$jumlah=$initval+$saldoLalu;
echo "<tr><td>1</td><td>-</td><td>Saldo Periode Lalu</td><td align=right>Rp. ".number_format(($jumlah>0?$jumlah:"0"),2,',','.')."</td><td align=right>Rp. ".number_format(($jumlah>0?"0":$jumlah),2,',','.')."</td><td align=right>Rp. ".number_format($jumlah,2,',','.')."</td></tr>";
//current bln
$str="select * from ak_jurnal where (idperkdebet='$idacc' or idperkkredit='$idacc') and (tanggal>='$tgl1' and tanggal<='$tgl2') and status_validasi=1 order by tanggal";
$resList= $this->db->query($str)->result();
if (sizeof($resList )<=0){
	echo "<tr><td colspan=6 style=\"text-align:center\">Belum ada transaksi  atau transaksi belum divalidasi</td></tr>";
}else{
	$i=2;
	foreach ($resList as $detil){
		echo "<tr valign=top>";
		echo "<td>$i</td>";
		echo "<td>".strftime('%d %B %Y', strtotime($detil->tanggal))."</td>";
		echo "<td>".$detil->keterangan."</td>";
		echo "<td align=right>Rp. ".($detil->idperkdebet==$idacc ?number_format($detil->jumlah,2,',','.'):0)."</td>";
		echo "<td align=right>Rp. ".($detil->idperkkredit==$idacc ?number_format($detil->jumlah,2,',','.'):0)."</td>";	
		if ($detil->idperkdebet==$idacc){	//idacc debet
			$jumlah+=$detil->jumlah;
		}else{
			$jumlah-=$detil->jumlah;
		}
		//$jumlah=($detil->jenis=="BKM"?($jumlah+$detil->jumlah):($jumlah-$detil->jumlah));
		echo "<td align=right>Rp. ".number_format($jumlah,2,',','.')."</td>";
		echo "</tr>";
		$i++;
	}
}
?>
<tr><th colspan="5"  class="pojokKiriBawah"><I>Saldo Akhir Kas</I></th><th class="pojokKananBawah" align=right><H4><?php echo "Rp. ".number_format($jumlah,2,',','.')?></H4></th></tr>
</table>
</div>
<?
} else{
$strSub="select * from ak_rekeningperkiraan where idparent='".$idacc."'";
			$resSub= $this->db->query($strSub)->result();

			//menghitung total parent
			$jumlahParentTotal=0;
			foreach ($resSub as $detilresSub){
				$initval=$this->report_model->getInitVal($detilresSub->idacc);
				$saldoLalu=$this->report_model->getSaldoLaluWithInterval($detilresSub->idacc, $tgl1);
				$jumlahParent=$initval+$saldoLalu;

				$strJurnalParent="select * from ak_jurnal where (idperkdebet='$detilresSub->idacc' or idperkkredit='$detilresSub->idacc') and (tanggal>='$tgl1' and tanggal<='$tgl2') and status_validasi=1  order by tanggal";
				$resListJurnalParent= $this->db->query($strJurnalParent)->result();
				$debetParent=0;
				$kreditParent=0;
				if (sizeof($resListJurnalParent )<=0){
					//echo "<tr><td colspan=6 style=\"text-align:center\">Belum ada transaksi </td></tr>";
				}else{
					foreach ($resListJurnalParent as $detilJurnalParent){
						if ($detilJurnalParent->idperkdebet==$detilresSub->idacc){
								//echo number_format($detilJurnalParent->jumlah,2,',','.');
								$jumlahParent+=$detilJurnalParent->jumlah;
								$debetParent+=$detilJurnalParent->jumlah;
						}
						if ($detilJurnalParent->idperkkredit==$detilresSub->idacc){
								//echo number_format($detilJurnalParent->jumlah,2,',','.');
								$jumlahParent-=$detilJurnalParent->jumlah;
								$kreditParent+=$detilJurnalParent->jumlah;
						}
						

					}

				}
				$jumlahParentTotal+=$jumlahParent;

			}
	 ?>
<br>
<div class="alert alert-info alert-dismissable">
<i class="fa fa-info"></i>
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
<b>Rekening Perkiraan : <?php echo "[ ".$idacc." ]&nbsp;".strtoupper($accName)."  &nbsp;&nbsp;&nbsp; Rp. ".number_format($jumlahParentTotal,2,',','.');?></b>
</div><br>
<?
	foreach ($resSub as $detilresSub){	//loop lvl anak
		$idaccsub=$detilresSub->idacc;
		$accNameDetSub=$this->report_model->getAccName($idaccsub);	 
	 ?>

<div align=center>
<table  border="0" cellspacing=0 class="mytable mytable-bordered" align="center" width="95%">
<tr><th class="pojokKiriAtas" width="5%">&nbsp;</th><th colspan=4><?php echo "[ ".$idaccsub." ] ".strtoupper($accNameDetSub)?></th><th class="pojokKananAtas" >&nbsp;</th ></tr>
<tr><th width="5%">No</th><th width="10%">Tanggal</th> <th>Keterangan</th><th width="15%">Debet</th><th width="15%">Kredit</th><th  width="20%">Saldo</th ></tr>
<?
$blnIdk=$this->arrIntBln;
//saldo bulan lalu = initval + bln-1
$initval=$this->report_model->getInitVal($idaccsub);
$saldoLalu=$this->report_model->getSaldoLaluWithInterval($idaccsub, $tgl1);
$jumlah=$initval+$saldoLalu;
echo "<tr><td>1</td><td>-</td><td>Saldo Periode Lalu</td><td align=right>Rp. ".number_format(($jumlah>0?$jumlah:"0"),2,',','.')."</td><td align=right>Rp. ".number_format(($jumlah>0?"0":$jumlah),2,',','.')."</td><td align=right>Rp. ".number_format($jumlah,2,',','.')."</td></tr>";
//current bln
$str="select * from ak_jurnal where (idperkdebet='$idaccsub' or idperkkredit='$idaccsub') and (tanggal>='$tgl1' and tanggal<='$tgl2') and status_validasi=1 order by tanggal";
$resList= $this->db->query($str)->result();
if (sizeof($resList )<=0){
	echo "<tr><td colspan=6 style=\"text-align:center\">Belum ada transaksi  atau transaksi belum divalidasi</td></tr>";
}else{
	$i=2;
	foreach ($resList as $detil){
		echo "<tr valign=top>";
		echo "<td>$i</td>";
		echo "<td>".strftime('%d %B %Y', strtotime($detil->tanggal))."</td>";
		echo "<td>".$detil->keterangan."</td>";
		echo "<td align=right>Rp. ".($detil->idperkdebet==$idaccsub ?number_format($detil->jumlah,2,',','.'):0)."</td>";
		echo "<td align=right>Rp. ".($detil->idperkkredit==$idaccsub ?number_format($detil->jumlah,2,',','.'):0)."</td>";
		if ($detil->idperkdebet==$idaccsub){	//idacc debet
			$jumlah+=$detil->jumlah;
		}else{
			$jumlah-=$detil->jumlah;
		}
		//$jumlah=($detil->jenis=="BKM"?($jumlah+$detil->jumlah):($jumlah-$detil->jumlah));
		echo "<td align=right>Rp. ".number_format($jumlah,2,',','.')."</td>";
		echo "</tr>";
		$i++;
	}
}
?>
<tr><th colspan="5"  class="pojokKiriBawah"><I>Saldo Akhir Kas</I></th><th class="pojokKananBawah" align=right><H4><?php echo "Rp. ".number_format($jumlah,2,',','.')?></H4></th></tr>
</table>
</div><br>
<?
	 }
}

?>
</div>
</div>
</div>
</div>
<? 
if ($display==0){
	$param=$tgl1."_".$tgl2."_1_".$idacc."_".$sesLogin;
	if ($sesLogin=='center'){
		$param=$tgl1."_".$tgl2."_1_".$idacc."_".$sesLogin."_".$wilayah;
	}
?>

<br>
<div class="row" style="text-align:center">
	<div class="col-md-12">	<input type="hidden" name="param" id="param" value="<?=$param?>">
		<!-- <button  id="btToExcel" class="btn btn-success">Cetak Xls</button>&nbsp; --><a href="<?=base_url('rptCash/result/'.$param)?>" class="btn btn-success">Cetak/Download</a><br>		
	</div>
</div>	
<?	}	?>






<script>
$('#btToExcel').click(function(){		
		//var form_data = $('#myformkel').serialize();
		//alert($('#param').val());
		$().showMessage('Sedang diproses.. Harap tunggu..');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('rptCash/toExcel');?>',
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