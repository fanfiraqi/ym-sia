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

$str="select ifnull(sum(jumlah),0) jml from ak_jurnal WHERE id_cab=".$wilFilter." AND isanggaran='on' AND periodanggaran='".$thn.$bln."' and status_validasi=1";
$rsAngg=$this->db->query($str)->row();
$jumlahAnggaran=$rsAngg->jml;
//error disini krn problem akun pusat-cabang
?>
<tr><td></td><td colspan=6><b>Anggaran Dari Pusat </b></td><td align=right></td></tr>
<tr><td></td><td></td><td colspan=4>Hutang Afiliasi dari Pusat </td><td align=right><?php echo "Rp. ".number_format($jumlahAnggaran,0,',','.')?></td><td align=right></td></tr>
<tr><td></td><td></td><td colspan=4><b>Total Subsidi</b> </td><td align=right><b><?php echo "Rp. ".number_format($jumlahAnggaran,0,',','.')?></b></td><td align=right></td></tr>

<tr><td></td><td colspan=7>&nbsp;</td></b></tr>
<tr><td></td><td colspan=6><b>REALISASI BIAYA </b></td><td align=right></td></tr>

<? //level 2 = header
	$strL2="select * from ak_rekeningperkiraan where level=2 and kelompok='B'  order by  idacc";	
	$resList= $this->db->query($strL2)->result();
	$jmlExpense=0;
	$jmlIncome=0;
	foreach ($resList as $detil){
		$jmlLvl2=0;
		echo "<tr><td>&nbsp;</td><td colspan=4><b>".$detil->nama."</b></td><td colspan=3>&nbsp;</td></tr>";
		$strL3="select * from ak_rekeningperkiraan where idparent='".$detil->idacc."' and kelompok='B' and upper(nama) not like '%PENYUSUTAN%' order by  idacc";			
		$resListL3= $this->db->query($strL3)->result();
		//echo "<tr><td colspan=7>$strL3</td></tr>";
		foreach ($resListL3 as $detilL3){
			$jmlAkumL3=0;
			echo "<tr><td></td>";
			echo "<td width=\"30\"></td>";
			echo "<td colspan=3 ><b>".$detilL3->nama."</b></td>";
			echo "<td colspan=3></td></tr>";
			$strL4="select * from ak_rekeningperkiraan where idparent='".$detilL3->idacc."' and upper(nama) not like '%PENYUSUTAN%' and id_cab=".$wilFilter."  order by  idacc";
			$resListL4= $this->db->query($strL4)->result();
			//echo "<tr><td colspan=7>$strL4</td></tr>";
								
				foreach ($resListL4 as $detilL4){
					
					//biaya ada level max 4

					$strCekMax4="select count(*) jcek from ak_rekeningperkiraan where idparent='".$detilL4->idacc."'   and id_cab=".$wilFilter."  order by  idacc";
					$resCekMax4= $this->db->query($strCekMax4)->row();
					if ($resCekMax4->jcek>0){

						echo "<tr><td></td>";
						echo "<td width=\"30\"></td>";
						echo "<td colspan=3 ><b>".$detilL4->nama."</b></td>";
						echo "<td colspan=3></td></tr>";
						$strL5="select * from ak_rekeningperkiraan where idparent='".$detilL4->idacc."' and upper(nama) not like '%PENYUSUTAN%'  order by  idacc";
						$resListL5= $this->db->query($strL5)->result();
						$i=1;
						foreach ($resListL5 as $detilL5){
							$rsJumlahDebet=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkdebet ='".$detilL5->idacc."' and bulan='$bln' and tahun='$thn'  and status_validasi=1")->row();
							$rsJumlahKredit=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkkredit ='".$detilL5->idacc."' and bulan='$bln' and tahun='$thn'  and status_validasi=1")->row();
							$jumlahbiaya=($rsJumlahDebet->jml) - $rsJumlahKredit->jml;
							if ($jumlahbiaya > 0){
								echo "<tr><td></td>";
								echo "<td width=\"30\"></td>";
								echo "<td width=\"30\">$i</td>";
								echo "<td colspan=2>".$detilL5->nama."</td>";
								echo "<td >Rp.&nbsp;".number_format($jumlahbiaya,2,',','.')."</td>";
								$jmlAkumL3+=$jumlahbiaya;
								$jmlExpense+=$jumlahbiaya;
								
								echo "<td colspan=2></td></tr>";
								$i++;
							}
						}
						$jmlLvl2+=$jmlAkumL3;
					}else{
						$rsJumlahDebet=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkdebet ='".$detilL4->idacc."' and bulan='$bln' and tahun='$thn'  and status_validasi=1")->row();
						$rsJumlahKredit=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkkredit ='".$detilL4->idacc."' and bulan='$bln' and tahun='$thn'  and status_validasi=1")->row();
						$jumlahbiaya=($rsJumlahDebet->jml) - $rsJumlahKredit->jml;
							if ($jumlahbiaya > 0){
								echo "<tr><td></td>";
								echo "<td width=\"30\"></td>";
								echo "<td width=\"30\"></td>";
								echo "<td colspan=2>".$detilL4->nama."</td>";
								echo "<td >Rp.&nbsp;".number_format($jumlahbiaya,2,',','.')."</td>";
								$jmlAkumL3+=$jumlahbiaya;
								$jmlExpense+=$jumlahbiaya;
								
								echo "<td colspan=2></td></tr>";								
							}
							$jmlLvl2+=$jmlAkumL3;
					}

				}
				
				echo "<tr><td></td><td></td><th colspan=\"4\"><I><b>Total ".$detilL3->nama."</b></I></th><th align=right>Rp.&nbsp;".number_format($jmlAkumL3,2,',','.')."</th><th >&nbsp;</th></tr>";
		}
		echo "<tr><td></td><th colspan=\"5\"><b>Total ".strtoupper($detil->nama)."</b></th><th align=right >Rp.&nbsp;".number_format($jmlLvl2,2,',','.')."</th><th >&nbsp;</th></tr>";
	}


echo "<tr><td>&nbsp;</td><td colspan=4><b>AKTIFITAS INVESTASI</b></td><td colspan=3>&nbsp;</td></tr>";
$rsJumlahBeliAset=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkdebet in (select distinct idacc from ak_rekeningperkiraan where status=1  and id_cab=".$wilFilter."  and idacc like '1-3%' and upper(nama) not like '%AKUM%') and bulan='$bln' and tahun='$thn' and status_validasi=1")->row();
$jmlExpense=$jmlExpense+$rsJumlahBeliAset->jml;

?>
<tr><td></td><td></td><td></td><td>Pembelian Semua Aset Bersih </td><td align=right><?php echo " Rp. ".number_format($rsJumlahBeliAset->jml,2,',','.')?></td><td colspan=3></td></tr>
<?
$rsJumlahKeluarInvestasi=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkdebet in (select distinct idacc from ak_rekeningperkiraan where status=1  and id_cab=".$wilFilter."  and idacc like '1-4%') and bulan='$bln' and tahun='$thn' and status_validasi=1")->row();
$jmlExpense=$jmlExpense+$rsJumlahKeluarInvestasi->jml;
//$jumlahInvestasi=($rsJumlahModal->jml+$rsJumlahJualAset->jml)-($rsJumlahBeliAset->jml+$rsJumlahKeluarInvestasi->jml);
?>
<tr><td></td><td></td><td></td><td>Pengeluaran Investasi</td><td align=right><?php echo " Rp. ".number_format($rsJumlahKeluarInvestasi->jml,2,',','.')?></td><td colspan=3></td></tr>
<?

echo "<tr><td></td><th colspan=\"4\"><b>Total Aktifitas Investasi</b></th><th align=right >Rp.&nbsp;".number_format(($rsJumlahBeliAset->jml+$rsJumlahKeluarInvestasi->jml),2,',','.')."</th><th COLSPAN=2>&nbsp;</th></tr>";
echo "<tr><th></th><th colspan=\"5\"><b>TOTAL BIAYA</b></th><th align=right>Rp.&nbsp;".number_format($jmlExpense,2,',','.')."</th><th >&nbsp;</th></tr>";
	
//$jumlah-=$jmlExpense;

?>
<tr><td COLSPAN=8>&nbsp;</td></tr>
<tr><td></td><td COLSPAN=4><b>PENDAPATAN LAIN-LAIN</b></td><td COLSPAN=4>&nbsp;</td></tr>
<?	$str="select * from ak_rekeningperkiraan where idparent='8-0000'  order by idacc ASC";	
	$resList= $this->db->query($str)->result();
	
	if (sizeof ($resList)<=0){
		echo "<tr><td colspan=7>Rekening Perkiraan Pendapatan Lain-lain Tidak ada</td></tr>";
	}else{
		$jmlIncomeLain2=0;
		foreach ($resList as $detil){	//lvl2
			echo "<tr>";
			echo "<td></td>";
			echo "<td colspan=6><b>".$detil->nama."</b></td>";	
			echo "<td></td>";
			echo "</tr>";
			$strB="select * from ak_rekeningperkiraan where idparent='".$detil->idacc."'  order by idacc";	
			$resListB= $this->db->query($strB)->result();
			//echo "<tr><td colspan=7>$strB</td></tr>";
			
			foreach ($resListB as $detilB){	//level3
				echo "<tr>";
				echo "<td></td>";
				echo "<td></td>";
				echo "<td colspan=5>".$detilB->nama."</td>";
				echo "<td></td>";
				echo "</tr>";
				
				$strL3="select * from ak_rekeningperkiraan where idparent='".$detilB->idacc."' and id_cab=".$wilFilter."  order by idacc";	
				$resList3= $this->db->query($strL3)->result();	
				//echo "<tr><td colspan=7>$strL4</td></tr>";
				$i=1;
				foreach ($resList3 as $detilLvl3){	//level3					
					$strL4="select * from ak_rekeningperkiraan where idparent='".$detilLvl3->idacc."' and id_cab=".$wilFilter."  order by idacc";	
					$resList4= $this->db->query($strL4)->result();	
					//echo "<tr><td colspan=7>$strL4</td></tr>";
					foreach ($resList4 as $detilLvl4){	//level5						
						$rsJumlahKredit=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkkredit ='".$detilLvl4->idacc."' and bulan='$bln' and tahun='$thn' and status_validasi=1")->row();
						$rsJumlahDebet=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkdebet ='".$detilLvl4->idacc."' and bulan='$bln' and tahun='$thn' and status_validasi=1")->row();
						$jumlahPL=$rsJumlahKredit->jml - $rsJumlahDebet->jml;
						if ($jumlahPL<>0){
							echo "<tr>";
							echo "<td></td>";
							echo "<td></td>";						
							echo "<td></td>";						
							echo "<td colspan=2>$i.&nbsp;".$detilLvl4->nama."</td>";			
							echo "<td align=right>Rp.&nbsp;".number_format($jumlahPL,2,',','.')."</td>";	
							echo "<td >&nbsp;</td>";
							echo "<td >&nbsp;</td>";
							echo "</tr>";
							
							$jmlIncomeLain2+=$jumlahPL;
							$jmlIncome+=$jumlahPL;
							$i++;
						}
					}
					
				}

			}

			echo "<tr>";
			echo "<td></td>";			
			echo "<td colspan=4><b>Sub Total ".$detil->nama."</b></td>";
			echo "<td align=right><b>Rp.&nbsp;".number_format($jmlIncomeLain2,2,',','.')."</b></td>";
			echo "<td  >&nbsp;</td>";
			echo "<td  >&nbsp;</td>";
			echo "</tr>";
		}
		?>
		<tr><td></td><th colspan="5"><I><b>Total Pendapatan Lain-lain</b></I></th><th align=right ><?php echo "Rp.&nbsp;".number_format($jmlIncomeLain2,2,',','.')?></th><th >&nbsp;</th></tr>
		<tr><td COLSPAN=8>&nbsp;</td></tr>

		<?

	
		?>
		<!-- <tr><td></td><th colspan="5"><I><b>Total Pendapatan Lain-lain</b></I></th><th align=right ><?php echo "Rp.&nbsp;".number_format($jmlIncomeLain2,2,',','.')?></th><th >&nbsp;</th></tr> -->
		<? $jumlahAnggaran+=$jmlIncomeLain2; ?>
		<tr><th></th><th colspan="5"><I><b>Total Pendapatan Keseluruhan</b></I></th><th style="text-align:right"><?php echo "Rp.&nbsp;".number_format($jumlahAnggaran,2,',','.')?></th><th>&nbsp;</th></tr>
		<tr><td COLSPAN=8>&nbsp;</td></tr>

		<?

	}
	
	//biaya lain2 9-1101
	echo "<tr><td >&nbsp;</td><td COLSPAN=7><b>BIAYA LAIN-LAIN</b></td></tr>";
	$strLain="select * from ak_rekeningperkiraan where idparent='9-1000'  order by  idacc";
	$resListLain= $this->db->query($strLain)->result();
	$jmlLain=0;
		foreach ($resListLain as $detilLain){			
			echo "<tr><td></td>";
			echo "<td colspan=4>".$detilLain->nama."</td>";			
			echo "<td colspan=3></td></tr>";
			$strLain2="select * from ak_rekeningperkiraan where idparent='".$detilLain->idacc."' and id_cab=".$wilFilter." order by  idacc";
			$resListLain2= $this->db->query($strLain2)->result();
			
			foreach ($resListLain2 as $detilLain2){				
				echo "<tr><td></td>";
				echo "<td width=\"30\"></td>";
				echo "<td colspan=3><b>".$detilLain2->nama."</b></td>";				
				echo "<td colspan=3></td></tr>";
				$jmlLain=0;
				$i=1;
				$strLain3="select * from ak_rekeningperkiraan where idparent='".$detilLain2->idacc."'   order by  idacc";
				$resListLain3= $this->db->query($strLain3)->result();
				foreach ($resListLain3 as $detilLain3){
					$rsJumlahDebetBL=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkdebet ='".$detilLain3->idacc."' and bulan='$bln' and tahun='$thn'  and status_validasi=1")->row();
					$rsJumlahKreditBL=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkkredit ='".$detilLain3->idacc."' and bulan='$bln' and tahun='$thn'  and status_validasi=1")->row();
					$jumlahBL=($rsJumlahDebetBL->jml) - $rsJumlahKreditBL->jml;
					if ($jumlahBL>0){
						echo "<tr><td></td>";
						echo "<td width=\"30\"></td>";
						echo "<td width=\"30\">$i</td>";
						echo "<td colspan=2>".$detilLain3->nama."</td>";
						echo "<td >Rp.&nbsp;".number_format($jumlahBL,2,',','.')."</td>";
						$jmlLain+=$jumlahBL;
						$jmlExpense+=$jumlahBL;
						echo "<td colspan=2></td></tr>";
						$i++;
					}
				}
			}
		}
	echo "<tr><td></td><th colspan=\"4\"><b>Total Biaya Lain-lain</b></th><th align=right>Rp.&nbsp;".number_format($jmlLain,2,',','.')."</th><th colspan=2>&nbsp;</th></tr>";

	$saldo=$jumlahAnggaran-$jmlExpense;
?>
<tr><th></th><th colspan="5"><I><b>Total Biaya Keseluruhan</b></I></th><th style="text-align:right"><?php echo "Rp.&nbsp;".number_format($jmlExpense,2,',','.')?></th><th>&nbsp;</th></tr>
		<tr><td COLSPAN=8>&nbsp;</td></tr>
<tr><td></td><td colspan=5><b><h4>SALDO AKHIR BULAN</h4></b></td><td align=right><h4><b><?="Rp.&nbsp;".number_format($saldo,2,',','.')?></b></h4></td><td></td></tr>
<tr><th class="pojokKiriBawah">&nbsp;</th><th COLSPAN=6></th><th class="pojokKananBawah" width="5%">&nbsp;</th></tr>
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
		<button  id="btToExcel" class="btn btn-success"  >Cetak Xls</button>&nbsp;
		<a href="<?=base_url('rptRealisasi/result/'.$param)?>" class="btn btn-success">Cetak/Download</a><br>		
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
			url: '<?php echo base_url('rptRealisasi/toExcel');?>',
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