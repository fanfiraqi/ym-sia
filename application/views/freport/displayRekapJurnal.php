<div class="row">
<div class="col-md-12">
 <div class="box box-info">
 <div class="box-body">
<?	
	$viewKop=$this->commonlib->tableKop(strtoupper($nmcabang->KOTA),$title, '00', '__','__', $this->session->userdata('param_company'), $this->session->userdata('logo'));
	echo $viewKop;
	
	$str="select ak_jurnal.*, (select nama from ak_rekeningperkiraan where idacc=ak_jurnal.idperkdebet) akundebet, (select nama from ak_rekeningperkiraan where idacc=ak_jurnal.idperkkredit) akunkredit from ak_jurnal where tahun='$thn' and bulan='$bln' and id_cab= ".$wilayah;
		$res= $this->db->query($str)->result();	
?>
<div align=center>
<table  border="0" cellspacing=0 class="mytable mytable-bordered" align="center" width="95%">
<thead>
<tr><th  width="5%">No</th><th width="10%">Tanggal</th> <th width="10%">No. Bukti</th><th>Keterangan</th><th>Ref</th><th width="15%">Debet</th><th width="15%">Kredit</th><th >Status</th></tr>
</thead>
<tbody>
<?	
$i=1;$jumlah=0;
	foreach($res as $row){
	echo "<tr>";
	echo "<td>$i</td>";
	echo "<td>".$row->tanggal."</td>";
	echo "<td>".$row->nobuktiref."</td>";
	echo "<td>".str_replace(" ","&nbsp;",$row->akundebet)."<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$row->akunkredit."<br>( ".$row->keterangan." )</td>";
	echo "<td>".$row->idperkdebet."<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$row->idperkkredit."</td>";
	echo "<td>Rp.&nbsp;".number_format($row->jumlah,2,',','.')."<br>0</td>";
	echo "<td>0<br>Rp.&nbsp;".number_format($row->jumlah,2,',','.')."</td>";
	echo "<td>".($row->status_validasi==1?"Valid":"Belum Valid")."</td>";
	echo "</tr>";
	$jumlah+=$row->jumlah;
	$i++;
}

echo "<tr><th colspan=5></th><th><b>Rp.&nbsp;".number_format($jumlah,2,',','.')."</b></th><th><b>Rp.&nbsp;".number_format($jumlah,2,',','.')."</b></th><th></th></tr>";
echo "</tbody>";

?>
</table>
</div>

</div>
</div>
</div>
</div>
<? 
if ($display==0){
	$param=$thn."_".$bln."_1_".$sesLogin;
	if ($sesLogin=='center'){
		$param=$thn."_".$bln."_1_".$sesLogin."_".$wilayah;
	}
?>

<br>
<div class="row" style="text-align:center">
	<div class="col-md-12">	<input type="hidden" name="param" id="param" value="<?=$param?>">
		<!-- <button  id="btToExcel" class="btn btn-success">Cetak Xls</button>&nbsp; --><a href="<?=base_url('rptRekapJurnal/result/'.$param)?>" class="btn btn-success">Cetak/Download</a><br>		
	</div>
</div>	




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
<?	}	?>