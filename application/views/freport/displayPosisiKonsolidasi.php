<div class="row">
<div class="col-md-12">
 <div class="box box-info">
 <div class="box-body">
<?	
	
	$viewKop=$this->commonlib->tableKop('KEU-ACCOUNTING<br>KONSOLIDASI',$title, '00', '__','__', $this->session->userdata('param_company'), $this->session->userdata('logo'));
	echo $viewKop;
	//echo $strMaster;
$str="SELECT `idacc`, `nama`, `kelompok`, `level`,idparent, IF(`kelompok`='A','Aktiva','Pasiva') AS jenis
		from ak_rekeningperkiraan 
		WHERE  `kelompok` IN ('A','K', 'M') AND `level`=2 and status=1 
		ORDER BY  `kelompok`,idacc";
$query=$this->db->query($str)->result();

?><br>
<div align=center>
<table  border="1" cellspacing=0 class="mytable mytable-bordered"  align="center" width="85%">
<tr valign=top><th class="pojokKiriAtas" ></th><th colspan=2 ><div align=center>PERKIRAAN</div></th><th class="pojokKananAtas" colspan=2><div align=center>JUMLAH</div></th ></tr>
<?	$i=1;
	$jenis="";
	$jumlah=array();
	
	foreach ($query as $level2){
		if ($jenis!=$level2->jenis){
			$i=1;
			if ($jenis!=""){
				echo "<tr><th colspan=4><b>TOTAL&nbsp;".strtoupper($jenis)."</b></th><th ><div align=right><b>Rp.&nbsp;".number_format($jumlah[$jenis],2,',','.')."</b></div></th></tr>";
				echo "<tr><td colspan=5>&nbsp;</td></tr>";
				echo "<tr><td colspan=5>&nbsp;</td></tr>";
			}
		}
		if ($i==1){
			$jenis=$level2->jenis;
			$jumlah[$jenis]=0;
			echo "<tr><td colspan=3><b>".strtoupper($level2->jenis)."</b></td><td colspan=2>&nbsp;</td></tr>";
		}
		
		echo "<tr><td>&nbsp;</td><td colspan=2><b>".strtoupper($level2->nama)."</b></td><td colspan=2>&nbsp;</td></tr>";
		//loop level 3 & get value
		$str3="select * from ak_rekeningperkiraan where level=3 and idparent='".$level2->idacc."' and status=1 order by idacc";
		$query3=$this->db->query($str3)->result();
		$jumlahL2=0;
		foreach ($query3 as $level3){
			echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td >".strtoupper($level3->nama)."</td><td>&nbsp;</td>";
			$str4="select * from ak_rekeningperkiraan where idparent='".$level3->idacc."'   and status=1 order by idacc ASC";
			$resList4= $this->db->query($str4)->result();
			$jumlahL3=0;
			foreach ($resList4 as $level4){
				$strNya='';
				//echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>".strtoupper($level4->nama)."</td>";
							if (substr($level4->idacc,0,2)=='3.' ){
								$neracaValue=$this->report_model->getLabaRugiValueL4($level4->idacc, $level4->id_cab, $bln, $thn);
								//$strNya=$this->report_model->getLabaRugiValueL4Str($level4->idacc, $bln, $thn);
							}else{
							//get neraca value
								$neracaValue=$this->report_model->getNeracaValueL4($level4->idacc, $bln, $thn);
								if ($jenis=='Pasiva'){
									$neracaValue=$neracaValue*-1;
								}
							}
								
							$jumlah[$jenis]+=$neracaValue;
							$jumlahL2+=$neracaValue;
							$jumlahL3+=$neracaValue;
							//echo "<td align=right>".($neracaValue<=0? "( Rp.&nbsp;".number_format(($neracaValue*-1),2,',','.').")" : "Rp.&nbsp;".number_format($neracaValue,2,',','.'))."</td><td>&nbsp;</td></tr>";
			}
			echo "<td align=right>Rp. ".($jumlahL3<0?"(".number_format(($jumlahL3*-1),2,',','.').")":number_format($jumlahL3,2,',','.'))." </td></tr>";
		}
		echo "<tr><td>&nbsp;</td><th colspan=3><b><i>TOTAL&nbsp;".strtoupper($level2->nama)."</i></b></th><th style='text-align:right'><b>Rp. ".($jumlahL3<0?"(".number_format(($jumlahL2*-1),2,',','.').")":number_format($jumlahL2,2,',','.'))."</b></th></tr>";
		echo "<tr><td colspan=5>&nbsp;</td></tr>";
		$i++;	
		
	}

	echo "<tr><th colspan=4 ><b>TOTAL&nbsp;".strtoupper($level2->jenis)."</b></th><th  ><div align=right><b>Rp.&nbsp;".number_format($jumlah[$jenis],2,',','.')."</b></div></th></tr>";
	echo "<tr><th colspan=3 class=\"pojokKiriBawah\">&nbsp;</th><th colspan=2 class=\"pojokKananBawah\">&nbsp;</th></tr>";


?>
</table>
</div>
</div>
</div>
</div>
</div>
<?	


 if ($display==0){
	 $param=$thn."_".$bln."_1";
	/*$param=$thn."_".$bln."_1_".$sesLogin;
	if ($sesLogin=='center'){
		$param=$thn."_".$bln."_1_".$sesLogin."_".$wilayah;
	}*/
?>
<br>
<div class="row" style="text-align:center">
	<div class="col-md-12">	<input type="hidden" name="param" id="param" value="<?=$param?>">
		<button  id="btToExcel" class="btn btn-success"  >Cetak Xls</button>&nbsp;
		<a href="<?=base_url('rptPosisiKonsolidasi/result/'.$param)?>" class="btn btn-success">Cetak/Download</a><br>		
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
			url: '<?php echo base_url('rptPosisiKonsolidasi/toExcel');?>',
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
