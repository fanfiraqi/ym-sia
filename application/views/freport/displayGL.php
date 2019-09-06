<div class="row">
<div class="col-md-12">
 <div class="box box-info">
 <div class="box-body">
<?	
	$viewKop=$this->commonlib->tableKop('Wilayah '.$nmcabang->KOTA,$title, '00', '__','__', $this->session->userdata('param_company'), $this->session->userdata('logo'));
	echo $viewKop;
	//echo $strMaster;
	$pesan="";
	
		
	$str=($idaccBB=="empty"?"select * from ak_rekeningperkiraan where id_cab=".$wilayah." and level=5":"select * from ak_rekeningperkiraan where idacc='$idaccBB'");
	
	//echo $str;
	$sqlBB= $this->db->query($str);
	if ($sqlBB->num_rows<=0){
		?>
		<div class="alert alert-info alert-dismissable">
		<i class="fa fa-info"></i>
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		Data Akun perkiraan tidak ditemukan atau bukan data cabang Anda
		</div>
		<?
	}elseif ($sqlBB->num_rows<=1){
		$resList=$sqlBB->row();
		$idacc=$resList->idacc;
		$accNameDet=$resList->nama;
			//$idacc=$idaccBB;

		//tambah cek idacc isparent?
		$strCek="select count(*) cek from ak_rekeningperkiraan where idparent='".$idacc."'";
		$resCek= $this->db->query($strCek)->row();	
		if ($resCek->cek<=0){
		 ?>
			<table  border="0" cellspacing=0 class="mytable mytable-bordered" align="center" width="95%">
				<tr ><td colspan=6><b><div style="color:#885244;font-size:11pt"><?php echo "$idacc &nbsp;:&nbsp;".strtoupper($accNameDet)?></div></b></td></tr>
			</table>
			<table  border="0" cellspacing=0 class="mytable mytable-bordered" align="center" width="95%">				
				<tr><th class="pojokKiriAtas" width="5%">No</th><th width="10%">Tanggal</th> <th>Keterangan</th><th width="15%">Debet</th><th width="15%">Kredit</th><th class="pojokKananAtas" width="20%">Saldo</th ></tr>
				<?
				
				//saldo bulan lalu = initval + bln-1
				$initval=$this->report_model->getInitVal($idacc);
				$saldoLalu=$this->report_model->getSaldoLaluWithInterval($idacc, $tgl1);
				$jumlah=$initval+$saldoLalu;
				echo "<tr><td>1</td><td>-</td><td>Saldo Periode Lalu</td>";
				echo "<td>Rp.&nbsp;".number_format(($jumlah>0?$jumlah:"0"),2,',','.')."</td>";
				echo "<td>Rp.&nbsp;".number_format(($jumlah>0?"0":$jumlah),2,',','.')."</td>";
				echo "<td>";
						if ($jumlah<=0){
							echo "( Rp.&nbsp;".number_format(($jumlah*-1),2,',','.').")";
						}else{
							echo "Rp.&nbsp;".number_format($jumlah,2,',','.');
						}
						echo "</td></tr>";
				//current bln
				$strJurnal="select * from ak_jurnal where (idperkdebet='$idacc' or idperkkredit='$idacc') and (tanggal>='$tgl1' and tanggal<='$tgl2') and status_validasi=1  order by tanggal";
				$resListJurnal= $this->db->query($strJurnal)->result();
				$debet=0;
				$kredit=0;
				if (sizeof($resListJurnal )<=0){
					echo "<tr><td colspan=6 style=\"text-align:center\">Belum ada transaksi </td></tr>";
				}else{
					$i=2;
					
					foreach ($resListJurnal as $detilJurnal){
						echo "<tr valign=top>";
						echo "<td>$i</td>";
						echo "<td>".strftime('%d %B %Y', strtotime($detilJurnal->tanggal))."</td>";
						echo "<td>".$detilJurnal->keterangan."</td>";
						
						echo "<td>Rp.&nbsp;";
							if ($detilJurnal->idperkdebet==$idacc){
								echo number_format($detilJurnal->jumlah,2,',','.');
								$jumlah+=$detilJurnal->jumlah;
								$debet+=$detilJurnal->jumlah;
								}
							echo "</td>";
							echo "<td>Rp.&nbsp;";
							if ($detilJurnal->idperkkredit==$idacc){
								echo number_format($detilJurnal->jumlah,2,',','.');
								$jumlah-=$detilJurnal->jumlah;
								$kredit+=$detilJurnal->jumlah;
							}
							echo "</td>";
						echo "<td>";
						if ($jumlah<=0){
							echo "( Rp.&nbsp;".number_format(($jumlah*-1),2,',','.').")";
						}else{
							echo "Rp.&nbsp;".number_format($jumlah,2,',','.');
						}
						echo "</td>";
						echo "</tr>";
						$i++;
					}
				}
				?>
				<tr><th colspan="3"  class="pojokKiriBawah"><I>Saldo Akhir <?php echo strtoupper($accNameDet)?></I></th>
				<th><?php echo "Rp.&nbsp;".number_format($debet,2,',','.')?></th>
				<th><?php echo "Rp.&nbsp;".number_format($kredit,2,',','.')?></th>
				<th class="pojokKananBawah"><b><?php 
					if ($jumlah<=0){
							echo "( Rp.&nbsp;".number_format(($jumlah*-1),2,',','.').")";
						}else{
							echo "Rp.&nbsp;".number_format($jumlah,2,',','.');
					}?></b></th></tr>
				</table>	<br>
			<?	
		}else{
			//isparent=true
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
?><br>
<table  border="1" cellspacing=0 class="mytable mytable-bordered" align="center" width="95%">
<tr ><td colspan=5><b><div style="color:#885244;font-size:11pt"><?php echo "$idacc &nbsp;:&nbsp;".strtoupper($accNameDet)?></div></b></td><td><b><div style="color:#885244;font-size:11pt; text-align:right"><?php echo "Rp.&nbsp;".number_format(($jumlahParentTotal>0?$jumlahParentTotal:"0"),2,',','.')?></div></b></td></tr>
</table><br>
<?	
			foreach ($resSub as $detilresSub){	//loop lvl 5
					$idaccsub=$detilresSub->idacc;
					$accNameDetSub=$this->report_model->getAccName($idaccsub);
?>
				<table  border="0" cellspacing=0 class="mytable mytable-bordered" align="center" width="95%">				
				<tr><th class="pojokKiriAtas" width="5%">&nbsp;</th><th colspan="4"><?php echo $idaccsub." : ".strtoupper($accNameDetSub)?></th><th class="pojokKananAtas" >&nbsp;</th ></tr>
				<tr><th  width="5%">No</th><th width="10%">Tanggal</th> <th>Keterangan</th><th width="15%">Debet</th><th width="15%">Kredit</th><th  width="20%">Saldo</th ></tr>
				<?
				
				//saldo bulan lalu = initval + bln-1
				$initval=$this->report_model->getInitVal($detilresSub->idacc);
				$saldoLalu=$this->report_model->getSaldoLaluWithInterval($detilresSub->idacc, $tgl1);
				$jumlah=$initval+$saldoLalu;
				echo "<tr><td>1</td><td>-</td><td>Saldo Periode Lalu</td>";
				echo "<td>Rp.&nbsp;".number_format(($jumlah>0?$jumlah:"0"),2,',','.')."</td>";
				echo "<td>Rp.&nbsp;".number_format(($jumlah>0?"0":$jumlah),2,',','.')."</td>";
				echo "<td>";
						if ($jumlah<=0){
							echo "( Rp.&nbsp;".number_format(($jumlah*-1),2,',','.').")";
						}else{
							echo "Rp.&nbsp;".number_format($jumlah,2,',','.');
						}
						echo "</td></tr>";
				//current bln
				$strJurnal="select * from ak_jurnal where (idperkdebet='$detilresSub->idacc' or idperkkredit='$detilresSub->idacc') and (tanggal>='$tgl1' and tanggal<='$tgl2') and status_validasi=1  order by tanggal";
				$resListJurnal= $this->db->query($strJurnal)->result();
				$debet=0;
				$kredit=0;
				if (sizeof($resListJurnal )<=0){
					echo "<tr><td colspan=6 style=\"text-align:center\">Belum ada transaksi </td></tr>";
				}else{
					$i=2;
					
					foreach ($resListJurnal as $detilJurnal){
						echo "<tr valign=top>";
						echo "<td>$i</td>";
						echo "<td>".strftime('%d %B %Y', strtotime($detilJurnal->tanggal))."</td>";
						echo "<td>".$detilJurnal->keterangan."</td>";
						
						echo "<td>Rp.&nbsp;";
							if ($detilJurnal->idperkdebet==$detilresSub->idacc){
								echo number_format($detilJurnal->jumlah,2,',','.');
								$jumlah+=$detilJurnal->jumlah;
								$debet+=$detilJurnal->jumlah;
								}
							echo "</td>";
							echo "<td>Rp.&nbsp;";
							if ($detilJurnal->idperkkredit==$detilresSub->idacc){
								echo number_format($detilJurnal->jumlah,2,',','.');
								$jumlah-=$detilJurnal->jumlah;
								$kredit+=$detilJurnal->jumlah;
							}
							echo "</td>";
						echo "<td>";
						if ($jumlah<=0){
							echo "( Rp.&nbsp;".number_format(($jumlah*-1),2,',','.').")";
						}else{
							echo "Rp.&nbsp;".number_format($jumlah,2,',','.');
						}
						echo "</td>";
						echo "</tr>";
						$i++;
					}
				}
				?>
				<tr><th colspan="3"  class="pojokKiriBawah"><I>Saldo Akhir <?php echo strtoupper($accNameDetSub)?></I></th>
				<th><?php echo "Rp.&nbsp;".number_format($debet,2,',','.')?></th>
				<th><?php echo "Rp.&nbsp;".number_format($kredit,2,',','.')?></th>
				<th class="pojokKananBawah"><b><?php 
					if ($jumlah<=0){
							echo "( Rp.&nbsp;".number_format(($jumlah*-1),2,',','.').")";
						}else{
							echo "Rp.&nbsp;".number_format($jumlah,2,',','.');
					}?></b></th></tr>
				</table>	<br>
				<?
			}
		}	//end isparent


}else{

	$resList=$sqlBB->result();
	foreach($resList as $row){
		$idacc=$row->idacc;
		$accNameDet=$row->nama;
		?>
		<table  border="0" cellspacing=0 class="mytable mytable-bordered" align="center" width="95%">
				<tr ><td colspan=6><b><div style="color:#885244;font-size:11pt"><?php echo "$idacc &nbsp;:&nbsp;".strtoupper($accNameDet)?></div></b></td></tr>
			</table>
			<table  border="0" cellspacing=0 class="mytable mytable-bordered" align="center" width="95%">				
				<tr><th class="pojokKiriAtas" width="5%">No</th><th width="10%">Tanggal</th> <th>Keterangan</th><th width="15%">Debet</th><th width="15%">Kredit</th><th class="pojokKananAtas" width="20%">Saldo</th ></tr>
				<?
				
				//saldo bulan lalu = initval + bln-1
				$initval=$this->report_model->getInitVal($idacc);
				$saldoLalu=$this->report_model->getSaldoLaluWithInterval($idacc, $tgl1);
				$jumlah=$initval+$saldoLalu;
				echo "<tr><td>1</td><td>-</td><td>Saldo Periode Lalu</td>";
				echo "<td>Rp.&nbsp;".number_format(($jumlah>0?$jumlah:"0"),2,',','.')."</td>";
				echo "<td>Rp.&nbsp;".number_format(($jumlah>0?"0":$jumlah),2,',','.')."</td>";
				echo "<td>";
						if ($jumlah<=0){
							echo "( Rp.&nbsp;".number_format(($jumlah*-1),2,',','.').")";
						}else{
							echo "Rp.&nbsp;".number_format($jumlah,2,',','.');
						}
						echo "</td></tr>";
				//current bln
				$strJurnal="select * from ak_jurnal where (idperkdebet='$idacc' or idperkkredit='$idacc') and (tanggal>='$tgl1' and tanggal<='$tgl2') and status_validasi=1  order by tanggal";
				$resListJurnal= $this->db->query($strJurnal)->result();
				$debet=0;
				$kredit=0;
				if (sizeof($resListJurnal )<=0){
					echo "<tr><td colspan=6 style=\"text-align:center\">Belum ada transaksi </td></tr>";
				}else{
					$i=2;
					
					foreach ($resListJurnal as $detilJurnal){
						echo "<tr valign=top>";
						echo "<td>$i</td>";
						echo "<td>".strftime('%d %B %Y', strtotime($detilJurnal->tanggal))."</td>";
						echo "<td>".$detilJurnal->keterangan."</td>";
						
						echo "<td>Rp.&nbsp;";
							if ($detilJurnal->idperkdebet==$idacc){
								echo number_format($detilJurnal->jumlah,2,',','.');
								$jumlah+=$detilJurnal->jumlah;
								$debet+=$detilJurnal->jumlah;
								}
							echo "</td>";
							echo "<td>Rp.&nbsp;";
							if ($detilJurnal->idperkkredit==$idacc){
								echo number_format($detilJurnal->jumlah,2,',','.');
								$jumlah-=$detilJurnal->jumlah;
								$kredit+=$detilJurnal->jumlah;
							}
							echo "</td>";
						echo "<td>";
						if ($jumlah<=0){
							echo "( Rp.&nbsp;".number_format(($jumlah*-1),2,',','.').")";
						}else{
							echo "Rp.&nbsp;".number_format($jumlah,2,',','.');
						}
						echo "</td>";
						echo "</tr>";
						$i++;
					}
				}
				?>
				<tr><th colspan="3"  class="pojokKiriBawah"><I>Saldo Akhir <?php echo strtoupper($accNameDet)?></I></th>
				<th><?php echo "Rp.&nbsp;".number_format($debet,2,',','.')?></th>
				<th><?php echo "Rp.&nbsp;".number_format($kredit,2,',','.')?></th>
				<th class="pojokKananBawah"><b><?php 
					if ($jumlah<=0){
							echo "( Rp.&nbsp;".number_format(($jumlah*-1),2,',','.').")";
						}else{
							echo "Rp.&nbsp;".number_format($jumlah,2,',','.');
					}?></b></th></tr>
				</table>	<br>
		<?
	}
}
?>
</div>
</div>
</div>
</div>
<?
$blnIdk=$this->arrIntBln;
if ($display==0){
	//$param=$tgl1."_".$tgl2."_1_".$idaccBB;
	$param=$tgl1."_".$tgl2."_1_".$idaccBB."_".$sesLogin;
	if ($sesLogin=='center'){
		$param=$tgl1."_".$tgl2."_1_".$idaccBB."_".$sesLogin."_".$wilayah;
	}
?>
<br>
<div class="row" style="text-align:center">
	<div class="col-md-12">	<input type="hidden" name="param" id="param" value="<?=$param?>">
		<!-- <button  id="btToExcel" class="btn btn-success">Cetak Xls</button>&nbsp; -->
		<a href="<?=base_url('rptBukuBesar/result/'.$param)?>" class="btn btn-success">Cetak/Download</a><br>		
	</div>
</div>	
<?	

}?>

<script>
$('#btToExcel').click(function(){		
		//var form_data = $('#myformkel').serialize();
		//alert($('#param').val());
		$().showMessage('Sedang diproses.. Harap tunggu..');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('rptBukuBesar/toExcel');?>',
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