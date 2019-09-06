<div class="row">
<div class="col-md-12">
 <div class="box box-info">
 <div class="box-body"  style="overflow:scroll; height:600px">
<?	
	$wilFilter=$wilayah;

	$nmcabang=$this->gate_db->query('select KOTA from mst_cabang where ID_CABANG='.$wilFilter)->row();
	$ketcabang=($wilFilter>1?'CABANG ':'').' '.(sizeof($nmcabang)>0?$nmcabang->KOTA:'');

	$viewKop=$this->commonlib->tableKop('KEU-ACCOUNTING<BR>'.$ketcabang,$title, '00', '__','__', $this->session->userdata('param_company'), $this->session->userdata('logo'));
	echo $viewKop;
	//echo $strMaster;
?>
<div align=center >
<table  border="1" cellspacing=0 class="mytable mytable-bordered"  align="center" width="95%">
<tr valign=top><th rowspan=2>No</th><th  colspan=4 rowspan=2>Keterangan</th> <th colspan=2>Saldo Awal</th><th colspan=2>Penambahan</th><th colspan=2>Pengurangan</th><th  rowspan=2>Akumulasi Penyusutan</th><th  rowspan=2>Akumulasi Penyisihan</th><th colspan=2>Saldo Akhir</th></tr>
<tr><th>Kuantitas</th><th>Rp</th><th>Kuantitas</th><th>Rp</th><th>Kuantitas</th><th>Rp</th><th>Kuantitas</th><th>Rp</th></tr>

<?	
$str="select * from ak_rekeningperkiraan where level=3 and idparent='1.2' and   upper(nama) not like '%AKUM%' and status=1 order by idacc";
$query=$this->db->query($str)->result();

if (sizeof($query )<=0){
	echo "<tr><td colspan=15 style=\"text-align:center\">Rekening Fixed Asset tidak ada </td></tr>";
}else{
	$k=1;
	foreach ($query as $level3){
		echo "<tr valign=top>";
		echo "<td>$k</td>";
		echo "<td colspan=16>".$level3->idacc." - ".$level3->nama."</td></tr>";
		$str4="select * from ak_rekeningperkiraan where idparent='".$level3->idacc."'  and id_cab=".$wilFilter." and upper(nama) not like '%AKUM%' order by idacc";
		$query4 =$this->db->query($str4)->result();
		
		if (sizeof($query4 )<=0){
			echo "<tr><td colspan=15 style=\"text-align:center\">Rekening Fixed Asset Level 4 tidak ada </td></tr>";
		}else{
			$j=1;
			foreach ($query4 as $level4){
				echo "<tr valign=top>";				
				echo "<td>".$k.".".$j."</td>";	
				echo "<td>&nbsp;</td>";
				echo "<td colspan=15>".$level4->idacc." - ".str_replace(" ", "&nbsp;", $level4->nama)."</td></tr>";
				$str5="select * from ak_rekeningperkiraan where idparent='".$level4->idacc."' and   upper(nama) not like '%AKUM%'";	//level 5 
				$query5 =$this->db->query($str5)->result();
				if (sizeof($query5 )<=0){
					echo "<tr><td colspan=15 style=\"text-align:center\">Rekening Fixed Asset Level 5 tidak ada </td></tr>";
				}else{
					$i=1;					
					foreach ($query5 as $level5){
						$strSeting="select * from ak_set_aset where id_aset='".$level5->idacc."' and id_cab=".$wilFilter;
						$sql= $this->db->query($strSeting);
						if ($sql->num_rows()>0){
							$resSeting= $sql->row();
							echo "<tr valign=top>";						
							echo "<td>".$k.".".$j.".".$i."</td>";
							echo "<td>&nbsp;</td>";
							echo "<td>&nbsp;</td>";
							echo "<td colspan=2>".$level5->idacc." - ".str_replace(" ", "&nbsp;", $level5->nama)."</td>";
							echo "<td colspan=10>&nbsp;</td>";
							echo "</tr>";

							//baris utk list aset itemnya
							$strItem="select * from ak_asset_list where kelompok='".$resSeting->kelompok."' and grup='".$resSeting->grup."' and id_cab=".$resSeting->id_cab;
							$sqlitem= $this->db->query($strItem);
							if ($sqlitem->num_rows()>0){
								$resitem= $sqlitem->result();
								$p=1;
								$Qawal=1;
								$Qpenambahan=0;	
								$Npenambahan=0;
								$Qpengurangan=0;	
								$Npengurangan=0;
								$AkmSusut=0;
								$AkmSisih=0;
								$Qsaldo=0;
								$Nsaldo=0;
								foreach ($resitem as $row){
									echo "<tr valign=top>";						
									echo "<td>&nbsp;</td>";
									echo "<td>&nbsp;</td>";
									echo "<td>&nbsp;</td>";
									echo "<td>".$p."</td>";
									echo "<td>".str_replace(" ", "&nbsp;", $row->nama_aset)."</td>";
									echo "<td >".$Qawal."</td>";	
									echo "<td >".$row->nilai_perolehan."</td>";
									echo "<td >".$Qpenambahan."</td>";	
									$strAdd="select sum(jumlah) jml from ak_jurnal where idperkdebet='".$resSeting->id_aset."'  and concat(tahun,bulan)<='".$thn.$bln."' and status_validasi=1";
									$sqlAdd= $this->db->query($strAdd)->row;
									$Npenambahan=(sizeof($sqlAdd)>0?$sqlAdd->jml:0);
									$strAkum="select sum(jumlah) jml from ak_jurnal where idperkdebet='".$resSeting->id_akum."'  and concat(tahun,bulan)<='".$thn.$bln."' and status_validasi=1";
									$sqlAkum= $this->db->query($strAkum)->row;
									$AkmSusut=(sizeof($sqlAkum)>0?$sqlAkum->jml:0);
									echo "<td >".$Npenambahan."</td>";
									echo "<td >".$Qpengurangan."</td>";	
									echo "<td >".$Npengurangan."</td>";
									echo "<td >".$AkmSusut."</td>";
									echo "<td >".$AkmSisih."</td>";
									$Qsaldo=($Qawal+$Qpenambahan)-$Qpengurangan;
									$Nsaldo=($row->nilai_perolehan+$Npenambahan)-$Npengurangan;
									echo "<td >".$Qsaldo."</td>";
									echo "<td >".$Nsaldo."</td>";
									
									echo "</tr>";
									$p++;
								}
							}else{
								echo '<tr><td colspan=15 style="text-align:center">Data Item aset '.$level5->idacc." - ".str_replace(" ", "&nbsp;", $level5->nama).' belum ada </td></tr>';
							}

						}else{
							echo '<tr><td colspan=15 style="text-align:center">Data Setting kelompok aset '.$level5->idacc." - ".str_replace(" ", "&nbsp;", $level5->nama).' belum ada </td></tr>';
						}
						
						
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
		<!-- <button  id="btToExcel" class="btn btn-success">Cetak Xls</button>&nbsp; -->
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
