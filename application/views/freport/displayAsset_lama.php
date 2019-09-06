<div class="row">
<div class="col-md-12">
 <div class="box box-info">
 <div class="box-body"  style="overflow:scroll; height:600px">
<?	
	$wilFilter='';
	if ($sesLogin=='center'){
		$wilFilter=$wilayah;
	}else{
		$wilFilter=$this->session->userdata('auth')->ID_CABANG;
	}
	$nmcabang=$this->db->query('select KOTA from mst_cabang where ID_CABANG='.$wilFilter)->row();
	$ketcabang=($wilFilter>1?'CABANG ':'').' '.(sizeof($nmcabang)>0?$nmcabang->KOTA:'');

	$viewKop=$this->commonlib->tableKop('KEU-ACCOUNTING<BR>'.$ketcabang,$title, '00', '__','__', $this->session->userdata('param_company'), $this->session->userdata('logo'));
	echo $viewKop;
	//echo $strMaster;
?>
<div align=center >
<table  border="1" cellspacing=0 class="mytable mytable-bordered"  align="center" width="95%">
<tr valign=top><th class="pojokKiriAtas">No</th><th width="25%" colspan=3>Nama</th> <th>Tgl&nbsp;Perolehan</th><th>NP Total</th><th>NP&nbsp;Saat&nbsp;Setting</th><th >Lama&nbsp;Susut&nbsp;s.d&nbsp;periode&nbsp;pilih</th><th >Lama&nbsp;Sisa&nbsp;Susut</th><th >Susut&nbsp;@Bulan</th><th >Akumulasi</th><th >Nilai Buku</th><th class="pojokKananAtas">Status</th ></tr>

<?	
$str="select * from ak_rekeningperkiraan where level=3 and idparent='1.2' and status=1 order by idacc";
$query=$this->db->query($str)->result();

if (sizeof($query )<=0){
	echo "<tr><td colspan=12 style=\"text-align:center\">Rekening Fixed Asset tidak ada </td></tr>";
}else{
	$k=1;
	foreach ($query as $level3){
		echo "<tr valign=top>";
		echo "<td>$k</td>";
		echo "<td colspan=12>".$level3->nama."</td></tr>";
		$str4="select * from ak_rekeningperkiraan where idparent='".$level3->idacc."'  and id_cab=".$wilFilter." and upper(nama) not like '%AKUM%' order by idacc";
		$query4 =$this->db->query($str4)->result();
		
		if (sizeof($query4 )<=0){
			echo "<tr><td colspan=13 style=\"text-align:center\">Rekening Fixed Asset Level 4 tidak ada </td></tr>";
		}else{
			$j=1;
			foreach ($query4 as $level4){
				echo "<tr valign=top>";				
				echo "<td>".$k.".".$j."</td>";	
				echo "<td>&nbsp;</td>";
				echo "<td colspan=11>".$level4->idacc." - ".str_replace(" ", "&nbsp;", $level4->nama)."</td></tr>";
				$str5="select * from ak_rekeningperkiraan where idparent='".$level4->idacc."' and   upper(nama) not like '%AKUM%'";
				$query5 =$this->db->query($str5)->result();
				if (sizeof($query5 )<=0){
					echo "<tr><td colspan=12 style=\"text-align:center\">Rekening Fixed Asset Level 5 tidak ada </td></tr>";
				}else{
					$i=1;					
					foreach ($query5 as $level5){
						$strSeting="select * from ak_fixed_asset_setting where accFixedAsset='".$level5->idacc."'";
						$resSeting= $this->db->query($strSeting)->row();
						if (sizeof($resSeting )<=0){
							$tglP="-";
							$NP_total=0;
							$NP_saat_ini=0;
							$lama_sd_saat_ini=0;
							$lama_sisa=0;
							$susut_bln=0;
							$akm=0;
							$nilaibuku=0;
							$sts='Belum di seting';
						}else{
							$strCek="select sum(jumlah) jml, count(*) cnt from ak_jurnal where (idperkdebet='".$resSeting->accSusutAkm."' or idperkkredit='".$resSeting->accSusutAkm."') and sumber_data='susut' and concat(tahun,bulan)<='".$thn.$bln."' and status_validasi=1";
							$resCek= $this->db->query($strCek)->row();
							$cnt=0;
							if (sizeof($resCek )<=0){							
								$cnt=0;
							}else{								
								$cnt=$resCek->cnt;
							}

							//lama susut s.d saat ini =+ n kali jurnal, lama sisa - n kali jurnal
							$tglP=$resSeting->tgl_perolehan;							
							$NP_saat_ini=$resSeting->nilai_perolehan_saat_ini;
							$lama_sd_saat_ini = ($resSeting->lama_susut_sd_saat_ini + $cnt);
							$lama_sisa =( $resSeting->lama_sisa_susut - $cnt);
							$susut_bln=($resSeting->nilai_perolehan_saat_ini/$resSeting->lama_sisa_susut);							
							$akm=($susut_bln * $lama_sd_saat_ini);
							$NP_total=$susut_bln * ($lama_sd_saat_ini+$lama_sisa);							
							$nilaibuku=$NP_total-$akm;														
							$sts=($resSeting->isactive==1?"Asset Exist":"Asset dihapus");
						}
						echo "<tr valign=top>";						
						echo "<td>".$k.".".$j.".".$i."</td>";
						echo "<td>&nbsp;</td>";
						echo "<td>&nbsp;</td>";
						echo "<td>".$level5->idacc." - ".str_replace(" ", "&nbsp;", $level5->nama)."</td>";
						echo "<td>".($tglP=='-'?$tglP:strftime("%d %B %Y", strtotime($tglP)))."</td>";
						echo "<td align=center>Rp.&nbsp;".number_format($NP_total,2,',','.')."</td>";
						echo "<td align=center>Rp.&nbsp;".number_format($NP_saat_ini,2,',','.')."</td>";
						echo "<td align=center>$lama_sd_saat_ini</td>";		
						echo "<td align=center>$lama_sisa</td>";						
						echo "<td align=center>Rp.&nbsp;".number_format($susut_bln,2,',','.')."</td>";
						echo "<td align=center>Rp.&nbsp;".number_format($akm*-1,2,',','.')."</td>";
						echo "<td align=center>Rp.&nbsp;".number_format($nilaibuku,2,',','.')."</td>";
						echo "<td align=center>$sts</td>";
						echo "</tr>";
						$i++;
					}
				}
				$j++;
			}
		}
		$k++;
	}
}

echo "</table></div></div>
</div>
</div>
</div>";
 if ($display==0){
	//$param=$thn."_".$bln."_1";
	$param=$thn."_".$bln."_1_".$sesLogin;
	if ($sesLogin=='center'){
		$param=$thn."_".$bln."_1_".$sesLogin."_".$wilayah;
	}
?>

<br>
<div class="row" style="text-align:center">
	<div class="col-md-12">		<input type="hidden" name="param" id="param" value="<?=$param?>">
		<button  id="btToExcel" class="btn btn-success">Cetak Xls</button>&nbsp;
		<a href="<?=base_url('rptAsset/result/'.$param)?>" class="btn btn-success">Cetak/Download</a><br>		
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
			url: '<?php echo base_url('rptAsset/toExcel');?>',
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
