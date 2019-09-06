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

	$viewKop=$this->commonlib->tableKop('KEU-ACCOUNTING<BR>'.$ketcabang,$title, '00', '__','__', $this->session->userdata('param_company'), $this->session->userdata('logo'));
	echo $viewKop;
	//echo $strMaster;
	
?>
<br>
<div align=center>
<table  border="0" cellspacing=0 class="mytable mytable-bordered" align="center" width="95%">
<tr><th class="pojokKiriAtas" width="5%">NO</th><th colspan=3>PERKIRAAN</th><th class="pojokKananAtas" width="25%" colspan=3>JUMLAH</th ></tr>
<tr><td COLSPAN=4><b>PENDAPATAN ZIS</b></td><td COLSPAN=3>&nbsp;</td></tr>
<?	$str="select * from ak_rekeningperkiraan where idparent='4-1000' and id_cab=".$wilFilter." order by  idacc";	
	$resList= $this->db->query($str)->result();
	
	if (sizeof ($resList)<=0){
		echo "<tr><td colspan=5>Rekening Perkiraan Pendapatan ZIS Tidak ada atau bukan data Cabang Anda</td></tr>";
	}else{
		
		foreach ($resList as $detil){
			echo "<tr>";
			echo "<td></td>";
			echo "<td colspan=6><b>".$detil->nama."</b></td>";			
			echo "</tr>";
			$i=1;
			$jmlIncome=0;
			$str2="select * from ak_rekeningperkiraan where idparent='".$detil->idacc."' order by nama";
			$resList2= $this->db->query($str2)->result();
			//echo "<tr><td colspan=7>$str2</td></tr>";
			foreach ($resList2 as $detil2){
				$rsJumlahDebet=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkdebet ='".$detil2->idacc."' and bulan='$bln' and tahun='$thn' and status_validasi=1")->row();
				$rsJumlahKredit=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkkredit ='".$detil2->idacc."' and bulan='$bln' and tahun='$thn' and status_validasi=1")->row();
				$jumlahP=$rsJumlahKredit->jml - $rsJumlahDebet->jml;
				if ($jumlahP <>0){
					echo "<tr>";
					echo "<td></td>";
					echo "<td>$i</td>";
					echo "<td colspan=2>".$detil2->nama."</td>";
					
					echo "<td align=right>Rp.&nbsp;".number_format($jumlahP,2,',','.')."</td>";
					echo "<td colspan=3>&nbsp;</td>";
					echo "</tr>";
					$jmlIncome+=$jumlahP;
					$i++;
				}
			}
		}

	}
	$jumlah=$jmlIncome;

?>
<tr><td></td><td colspan="3"><I><b>Total Pendapatan ZIS</b></I></td><td style='text-align:right' ><b><?php echo "Rp.&nbsp;".number_format($jmlIncome,2,',','.')?></b></td><td  colspan=2>&nbsp;</td></tr>

<tr><td COLSPAN=7>&nbsp;</td></tr>
<tr><td COLSPAN=7><b>BIAYA-BIAYA</b></td></tr>
<? //level 2 = header
	$strL2="select * from ak_rekeningperkiraan where level=2 and kelompok='B' and idacc<>'9-1000' order by  idacc";	
	$resList= $this->db->query($strL2)->result();
	$jmlExpense=0;
	
	foreach ($resList as $detil){
		$jmlLvl2=0;
		echo "<tr><td>&nbsp;</td><td colspan=3><b>".$detil->nama."</b></td><td colspan=3>&nbsp;</td></tr>";
		$strL3="select * from ak_rekeningperkiraan where idparent='".$detil->idacc."' and kelompok='B' order by  idacc";			
		$resListL3= $this->db->query($strL3)->result();
		//echo "<tr><td colspan=7>$strL3</td></tr>";
		foreach ($resListL3 as $detilL3){
			$jmlAkumL3=0;
			echo "<tr><td></td>";
			echo "<td width=\"30\"></td>";
			echo "<td colspan=2 ><b>".$detilL3->nama."</b></td>";
			echo "<td colspan=3></td></tr>";
			$strL4="select * from ak_rekeningperkiraan where idparent='".$detilL3->idacc."'  and id_cab=".$wilFilter."  order by  idacc";
			$resListL4= $this->db->query($strL4)->result();
			//echo "<tr><td colspan=7>$strL4</td></tr>";
								
				foreach ($resListL4 as $detilL4){
					$strCekMax4="select count(*) jcek from ak_rekeningperkiraan where idparent='".$detilL4->idacc."'   and id_cab=".$wilFilter."  order by  idacc";
					$resCekMax4= $this->db->query($strCekMax4)->row();
					if ($resCekMax4->jcek>0){
						echo "<tr><td></td>";
						echo "<td width=\"30\"></td>";
						echo "<td colspan=2 ><b>".$detilL4->nama."</b></td>";
						echo "<td colspan=3></td></tr>";
						$strL5="select * from ak_rekeningperkiraan where idparent='".$detilL4->idacc."'  order by  idacc";
						$resListL5= $this->db->query($strL5)->result();
						$i=1;
						foreach ($resListL5 as $detilL5){
							$rsJumlahDebet=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkdebet ='".$detilL5->idacc."' and bulan='$bln' and tahun='$thn'  and status_validasi=1")->row();
							$rsJumlahKredit=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkkredit ='".$detilL5->idacc."' and bulan='$bln' and tahun='$thn'  and status_validasi=1")->row();
							$jumlahB=$rsJumlahDebet->jml - $rsJumlahKredit->jml;
							if ($jumlahB>0){
								echo "<tr><td></td>";
								echo "<td width=\"30\"></td>";
								echo "<td width=\"30\">$i</td>";
								echo "<td >".$detilL5->nama."</td>";
								echo "<td >Rp.&nbsp;".number_format($jumlahB,2,',','.')."</td>";
								$jmlAkumL3+=$jumlahB;
								$jmlExpense+=$jumlahB;
								
								echo "<td colspan=2></td></tr>";
								$i++;
							}
						}
					$jmlLvl2+=$jmlAkumL3;
					}else{

						$rsJumlahDebet=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkdebet ='".$detilL4->idacc."' and bulan='$bln' and tahun='$thn'  and status_validasi=1")->row();
						$rsJumlahKredit=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkkredit ='".$detilL4->idacc."' and bulan='$bln' and tahun='$thn'  and status_validasi=1")->row();
						$jumlahB= $rsJumlahDebet->jml - $rsJumlahKredit->jml;
							if ($jumlahB > 0){
								echo "<tr><td></td>";
								echo "<td width=\"30\"></td>";
								echo "<td width=\"30\"></td>";
								echo "<td >".$detilL4->nama."</td>";
								echo "<td >Rp.&nbsp;".number_format($jumlahB,2,',','.')."</td>";
								$jmlAkumL3+=$jumlahB;
								$jmlExpense+=$jumlahB;
								
								echo "<td colspan=2></td></tr>";								
							}
							$jmlLvl2+=$jmlAkumL3;

					}

				}
				
				echo "<tr><th></th><th></th><th colspan=\"2\"><I><b>Total ".$detilL3->nama."</b></I></th><th align=right>Rp.&nbsp;".number_format($jmlAkumL3,2,',','.')."</th><th colspan=2>&nbsp;</th></tr>";
		}
		echo "<tr><th></th><th colspan=\"3\"><b>Total ".strtoupper($detil->nama)."</b></th><th align=right>Rp.&nbsp;".number_format($jmlLvl2,2,',','.')."</th><th colspan=2>&nbsp;</th></tr>";
	}
echo "<tr><th colspan=\"5\"><b>TOTAL BIAYA</b></th><th align=right>Rp.&nbsp;".number_format($jmlExpense,2,',','.')."</th><th >&nbsp;</th></tr>";
	//Laba kotor
$jumlah-=$jmlExpense;
echo "<tr><th colspan=\"4\"><b>LABA KOTOR</b></th><th >&nbsp;</th><th align=right>Rp.&nbsp;".($jumlah<0?"(".number_format($jumlah*-1,2,',','.').")":number_format($jumlah,2,',','.'))."</th><th >&nbsp;</th></tr>";
echo "<tr><td colspan=7>&nbsp;</td></tr>";
?>


<tr><td COLSPAN=4><b>PENDAPATAN LAIN-LAIN</b></td><td COLSPAN=3>&nbsp;</td></tr>
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
			//echo "<td></td>";
			echo "</tr>";
			$strB="select * from ak_rekeningperkiraan where idparent='".$detil->idacc."'  order by idacc";	
			$resListB= $this->db->query($strB)->result();
			//echo "<tr><td colspan=7>$strB</td></tr>";
			
			foreach ($resListB as $detilB){	//level3
				echo "<tr>";
				echo "<td></td>";
				echo "<td></td>";
				echo "<td colspan=5>".$detilB->nama."</td>";
				//echo "<td></td>";
				echo "</tr>";
				
				$strL3="select * from ak_rekeningperkiraan where idparent='".$detilB->idacc."' and id_cab=".$wilFilter."  order by idacc";	
				$resList3= $this->db->query($strL3)->result();	
				//echo "<tr><td colspan=7>$strL4</td></tr>";
				$i=1;
				foreach ($resList3 as $detilLvl3){	//level4					
					$strL4="select * from ak_rekeningperkiraan where idparent='".$detilLvl3->idacc."' and id_cab=".$wilFilter."  order by idacc";	
					$resList4= $this->db->query($strL4)->result();	
					foreach ($resList4 as $detilLvl4){	//level5	
						$rsJumlahKredit=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkkredit ='".$detilLvl4->idacc."' and bulan='$bln' and tahun='$thn' and status_validasi=1")->row();
						$rsJumlahDebet=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkdebet ='".$detilLvl4->idacc."' and bulan='$bln' and tahun='$thn' and status_validasi=1")->row();
						$jumlahPL=$rsJumlahKredit->jml - $rsJumlahDebet->jml;
						//$rsJumlah=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkkredit ='".$detilLvl4->idacc."' and bulan='$bln' and tahun='$thn' and status_validasi=1")->row();
						if ($jumlahPL<>0){
							echo "<tr>";
							echo "<td></td>";
							echo "<td></td>";	
							echo "<td colspan=2>$i.&nbsp;".$detilLvl4->nama."</td>";			
							echo "<td align=right>Rp.&nbsp;".number_format($jumlahPL,2,',','.')."</td>";	
							echo "<td >&nbsp;</td>";
							echo "<td >&nbsp;</td>";
							//echo "<td >&nbsp;</td>";
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
			echo "<td colspan=3><b>Sub Total ".$detil->nama."</b></td>";
			echo "<td align=right><b>Rp.&nbsp;".number_format($jmlIncomeLain2,2,',','.')."</b></td>";
			echo "<td  >&nbsp;</td>";
			echo "<td  >&nbsp;</td>";
			echo "</tr>";
		}
	}
		?>
		<tr><td></td><th colspan="4"><I><b>Total Pendapatan Lain-lain</b></I></th><th align=right ><?php echo "Rp.&nbsp;".number_format($jmlIncomeLain2,2,',','.')?></th><th >&nbsp;</th></tr>
		<tr><td COLSPAN=7>&nbsp;</td></tr>

		<?
	

 
	//biaya lain2 9-1101
	echo "<tr><td COLSPAN=7><b>BIAYA LAIN-LAIN</b></td></tr>";
	$strLain="select * from ak_rekeningperkiraan where idparent='9-1000'  order by  idacc";
	$resListLain= $this->db->query($strLain)->result();
	
		foreach ($resListLain as $detilLain){			
			echo "<tr><td></td>";
			echo "<td colspan=3>".$detilLain->nama."</td>";			
			echo "<td colspan=3></td></tr>";
			$strLain2="select * from ak_rekeningperkiraan where idparent='".$detilLain->idacc."'  and id_cab=".$wilFilter." order by  idacc";
			$resListLain2= $this->db->query($strLain2)->result();
			
			foreach ($resListLain2 as $detilLain2){				
				echo "<tr><td></td>";
				echo "<td width=\"30\"></td>";
				echo "<td colspan=2><b>".$detilLain2->nama."</b></td>";				
				echo "<td colspan=3></td></tr>";
				$jmlLain=0;
				$i=1;
				$strLain3="select * from ak_rekeningperkiraan where idparent='".$detilLain2->idacc."'   order by  idacc";
				$resListLain3= $this->db->query($strLain3)->result();
				foreach ($resListLain3 as $detilLain3){
					$rsJumlahDebet=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkdebet ='".$detilLain3->idacc."' and bulan='$bln' and tahun='$thn'  and status_validasi=1")->row();
					$rsJumlahKredit=$this->db->query("select ifnull(sum(jumlah),0) jml from ak_jurnal where idperkkredit='".$detilLain3->idacc."' and bulan='$bln' and tahun='$thn'  and status_validasi=1")->row();
					$jumlahBL= $rsJumlahDebet->jml - $rsJumlahKredit->jml;
					if ($jumlahBL>0){
						echo "<tr><td></td>";
						echo "<td width=\"30\"></td>";
						echo "<td width=\"30\">$i</td>";
						echo "<td >".$detilLain3->nama."</td>";
						echo "<td >Rp.&nbsp;".number_format($jumlahBL,2,',','.')."</td>";
						$jmlLain+=$jumlahBL;
						$jmlExpense+=$jumlahBL;
						echo "<td colspan=2></td></tr>";
						$i++;
					}
				}
			}
		}
	echo "<tr><th></th><th colspan=\"3\"><b>Total Biaya Lain-lain</b></th><th align=right>Rp.&nbsp;".number_format($jmlLain,2,',','.')."</th><th colspan=2>&nbsp;</th></tr>";

	echo "<tr><td COLSPAN=7>&nbsp;</td></tr>";
	//echo "<tr><th colspan=\"5\"><b>TOTAL PENGELUARAN</b></th><th align=right>Rp.&nbsp;".number_format($jmlExpense,0,',','.')."</th><th >&nbsp;</th></tr>";
	
	$net=$jmlIncome-$jmlExpense;

	echo "<tr><td COLSPAN=7>&nbsp;</td></tr>";
	echo "<tr><th colspan=\"6\" class=\"pojokKiriBawah\"><b>NET SURPLUS/(DEFICIT) </b></th><th width=\"15%\" align=right class=\"pojokKananBawah\">";
				if ($net<=0){
							echo "( Rp.&nbsp;".number_format(($net*-1),2,',','.').")";
						}else{
							echo "Rp.&nbsp;".number_format($net,2,',','.');
					}
	echo "</th></tr>";
?>
</table>
</div>
</div>
</div>
</div>
</div>
<? if ($display==0){
	//$param=$thn."_".$bln."_1";
	$param=$thn."_".$bln."_1_".$sesLogin;
	if ($sesLogin=='center'){
		$param=$thn."_".$bln."_1_".$sesLogin."_".$wilayah;
	}
?>
<br>
<div class="row" style="text-align:center">
	<div class="col-md-12">	<input type="hidden" name="param" id="param" value="<?=$param?>">
		<button  id="btToExcel" class="btn btn-success" >Cetak Xls</button>&nbsp;
		<a href="<?=base_url('rptLabaRugi/result/'.$param)?>" class="btn btn-success">Cetak/Download</a><br>		
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
			url: '<?php echo base_url('rptLabaRugi/toExcel');?>',
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