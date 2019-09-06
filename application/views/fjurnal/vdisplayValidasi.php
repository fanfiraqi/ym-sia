<div class="alert alert-info alert-dismissable">
<i class="fa fa-info"></i>
<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
<? if ($pilihan=='tanggal'){?>
<b>Info : </b> Tanggal Jurnal terpilih : <?php echo $tglJurnal?> 
<?}else{
	echo "<b>Info : </b>";
	echo "<br>Menampilkan Semua Transaksi yang belum divalidasi sesuai filter";
	echo "<br>";
}?>
</div>
<div class="row">
	<div class="col-md-12">
<table class="table table-bordered">
<tr><th>No</th><th>Keterangan</th><th>Debet</th><th>Kredit</th><th>Waktu Entri</th><th width="10%">Bukti / Kuitansi</th><th>Action</th></tr>
<?	$strRes="select (select nama from ak_rekeningperkiraan where idacc=ak_jurnal.idperkdebet) akundebet, (select nama from ak_rekeningperkiraan where idacc=ak_jurnal.idperkkredit) akunkredit,ak_jurnal.* from ak_jurnal where sumber_data='".$jenis."' ".($pilihan=='validasi'?" and status_validasi=0 ":" and tanggal='".$tglJurnal."' ".($this->session->userdata('auth')->ID_CABANG>1?" and id_cab=".$this->session->userdata('auth')->ID_CABANG:""));

//echo "<tr><td colspan=7>$strRes</td></tr>";
	$rsRes=$this->db->query($strRes)->result();
	if ($rsRes<=0){
		echo "<tr><td colspan=7>Tidak Ada transaksi ".$jenis_trans." pada tanggal ".$tglJurnal."</td></tr>";
	}else{
		$i=1;			
		foreach($rsRes as $row){
			
			echo "<tr valign=top>";
			echo "<td>$i</td>";
			echo "<td>".$row->akundebet."<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$row->akunkredit."<br>( ".$row->keterangan." )</td>";
			echo "<td>".number_format($row->jumlah,2,',','.')."</td>";
			echo "<td>&nbsp;".number_format($row->jumlah,2,',','.')."</td>";
			echo "<td>".$row->waktuentri."</td>";
			echo "<td>".$row->nobuktiref."</td>";
			echo "<td>".($row->status_validasi==0?'<a href="javascript:void(0)" onclick="validation(this)" data-id="'.$row->notransaksi.'" class="btn btn-primary"><i class="fa fa-save" title="Proses Validasi"></i> Validasi</a>':"<b>validated</b><br>By ".$this->session->userdata('auth')->NAMA."<br> At ".$row->tgl_validasi)."</td>";
			echo "</tr>";
			$i++;
			// <button class="btn btn-primary"><i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
		}
	}

?>
</table>	
	</div>
</div>
<!-- Button --><br>
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label class="col-md-4 control-label">&nbsp;</label>
			<div class="col-sm-8">
			<input type="hidden" name="sumber_data" id="sumber_data" value="<?=$jenis?>">
			<input type="hidden" name="pilihan" id="pilihan" value="<?=$pilihan?>">			
			<input type="hidden" name="tanggal" id="tanggal" value="<?=$tglJurnal?>">
			
			<button class="btn btn-primary" id="btAll"><i class="glyphicon glyphicon-edit glyphicon-white"></i> Validasi Semua</button>&nbsp;
			<input type="button" class="btn btn-danger" id="btback" value="Kembali" onclick="backTo('<?=base_url('validasijurnal/index')?>');return false;">	</div>
		</div>
	</div>
</div>

<script>
function validation(obj){
		var id = $(obj).attr('data-id');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('validasijurnal/getValidation');?>',
			data: {
				id:id				
			},
			dataType: 'json',
			success: function(msg) {
				console.log(msg);
				if(msg.status =='success'){
					$().showMessage("Transaksi "+id+" berhasil divalidasi.",'success',1500);
					
				}else{
					$().showMessage("Transaksi "+id+" gagal divalidasi.",'success',1500);
				}
				setInterval(window.location.reload(), 2000);
			},
			cache: false
		});
	}

	$('#btAll').click(function(){	
	$.ajax({
			type: 'POST',
			url: '<?php echo base_url('validasijurnal/validate_all');?>',
			data: "sumber_data="+ $("#sumber_data").val()+"&pilihan="+ $("#pilihan").val()+"&tanggal="+ $("#tanggal").val(),				
			dataType: 'json',
			success: function(msg) {
				 console.log(msg);
				if(msg.status =='success'){
					$().showMessage('Semua Transaksi berhasil divalidasi.','success',2000);
					setInterval(window.location.reload(), 3000);
				} else {
					$().showMessage('Terjadi kesalahan. Data gagal disimpan.','danger',1000);
					
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				bootbox.alert("Terjadi kesalahan. Data gagal disimpan."+	textStatus + " - " + errorThrown );
			},
			cache: false
		});
});
</script>
