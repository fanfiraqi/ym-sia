<div class="control-group"><a href="javascript:void(0)" id="btcreate" class="btn btn-primary">Tambah Pinjaman</a></div><br>
<div id="errorHandler" class="alert alert-danger no-display"></div>
<?php echo form_open(null,array('class'=>'form-horizontal','id'=>'myform'));?>

<div id="divformkel" class="no-display " >
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">TRANSAKSI CABANG</label>
			<div class="col-sm-8"><?php echo strtoupper($nmCabang)?></div>
		</div>
	</div>
</div>
<div class="row" >
	<div class="col-md-8">
		<div class="form-group" ><label class="col-sm-4 control-label">NAMA LENGKAP</label>
			<div class="col-sm-8"><?=form_input(array('name'=>'nama','id'=>'nama','class'=>'form-control'));?></div>
		</div>
	</div>
</div>
<div class="row" >
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">JENIS PEMINJAM</label>
			<div class="col-sm-5"><?php	echo form_dropdown('jnspeminjam',array('Karyawan'=>'Karyawan', 'Non-Karyawan'=>'Non-Karyawan'),'','id="jnspeminjam" class="form-control"');
				?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label  class="col-sm-4 control-label">JENIS KELAMIN</label>
			<div class="col-sm-5"><?php	echo form_dropdown('jnskelamin',array('Pria'=>'Pria', 'Wanita'=>'Wanita'),'','id="jnskelamin" class="form-control"');
				?>	</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label  class="col-sm-4 control-label">NO. IDENTITAS(KTP/SIM)</label>
			<div class="col-sm-8"><?=form_input(array('name'=>'noidentitas','id'=>'noidentitas','class'=>'form-control'));?>	</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label  class="col-sm-4 control-label">ALAMAT</label>
			<div class="col-sm-8"><?=form_input(array('name'=>'alamat','id'=>'alamat','class'=>'form-control'));?>	</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">TELEPON</label>
			<div class="col-sm-8"><?=form_input(array('name'=>'telepon','id'=>'telepon','class'=>'form-control'));?>	</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">HP</label>
			<div class="col-sm-8"><?=form_input(array('name'=>'hp','id'=>'hp','class'=>'form-control'));?>	</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">JUMLAH PINJAMAN</label>
			<div class="col-sm-5"><?=form_input(array('name'=>'jumlah','id'=>'jumlah','class'=>'form-control','onkeypress'=>"return numericVal(this,event)", 'onblur'=>"blurObj(this)",'onclick'=>"clickObj(this)", "value"=>0));?> </div><label class="control-label">*Angka saja</label>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label  class="col-sm-4 control-label">LAMA PINJAMAN</label>
			<div class="col-sm-5 "><?=form_input(array('name'=>'lama','id'=>'lama','class'=>'form-control'));?> </div><label class="control-label">x ANGSURAN</label>
		</div>
	</div>
</div>
<div class="row" >
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">PEMBAYARAN VIA</label>
			<div class="col-sm-6"><?php	echo form_dropdown('accvia',$accvia,'','id="accvia" class="form-control"');
				?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label  class="col-sm-4 control-label">TANGGAL PINJAMAN</label>
			<div class="col-sm-5">
			<div class="input-group">
			<?=form_input(array('name'=>'tanggal','id'=>'tanggal','class'=>'form-control', 'value'=>date('Y-m-d'), 'readonly'=>true));?>
			<div class="input-group-addon"><span id="bttglawal" class="fa fa-calendar"></span></div>
			</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">KEPERLUAN</label>
			<div class="col-sm-8"><?=form_textarea(array('name'=>'keperluan','id'=>'keperluan','class'=>'form-control'));?>	</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group">
			<label class="col-sm-4 control-label">MENGETAHUI</label>
			<div class="col-sm-3"><?=form_input(array('name'=>'mengetahui','id'=>'mengetahui','class'=>'form-control'));?> </div>
			<label class="col-sm-2 control-label">MENYETUJUI</label>
			<div class="col-sm-3"><?=form_input(array('name'=>'menyetujui','id'=>'menyetujui','class'=>'form-control'));?> </div>
		</div>
	</div>
</div>
<hr />
<div class="row">
	<div class="col-md-8">
	<input type="hidden" name="id" id="id">
	<input type="hidden" name="jumlah_lama" id="jumlah_lama">
	<input type="hidden" name="accvia_lama" id="accvia_lama">
	<input type="hidden" name="state" id="state" value="add">
	<input type="submit" class="btn btn-primary" id="btsimpankel" value="Simpan">
	<button type="button" class="btn btn-default" id="btcancelkel">Batal</button>
	</div>
</div>
</div>
<?php echo form_close();?>
<br>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="dataTables">
		<thead>
			<tr>
				<th>ID</th>
				<th>NAMA</th>
				<th>JENIS</th>
				<th>TANGGAL</th>
				<th>TELP/HP</th>
				<th>JUMLAH </th>
				<th>LAMA</th>
				<th>STATUS</th>				
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			
		</tbody>
	</table>
</div>
<!-- /.table-responsive -->
<script>
    $(document).ready(function() {
        $('#dataTables').dataTable({
			"bProcessing": true,
			"bServerSide": true,
			"iDisplayLength": 25,
			"aoColumns": [
				{"mData": "ID" },
				{"mData": "NAMA"},
				{"mData": "JENIS"},
				{"mData": "TGL"},
				{"mData": "TELP_HP"},
				{"mData": "JUMLAH"},				
				{"mData": "LAMA" },
				{"mData": "STATUS" },				
				{"mData": "ACTION", "sortable":false }
			],
			"sAjaxSource": "<?php echo base_url('pinjaman/json_data');?>"
		});
    });
	$( "#tanggal" ).datepicker({
		dateFormat: 'yyyy-mm-dd',
		onSelect: function(date) {
            alert(date)
        }
		});

	$("#bttglawal").click(function() {
			$("#tanggal").datepicker("show");
		});

	$('#btsimpankel').click(function(event){	
		event.preventDefault();
		var form_data = $('#myform').serialize();
		$().showMessage('Sedang diproses.. Harap tunggu..');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('pinjaman/savePinjaman');?>',
			data: form_data,				
			dataType: 'json',
			success: function(msg) {
				$("#errorHandler").html('&nbsp;').hide();
				 console.log(msg);
				if(msg.status =='success'){
					$().showMessage('Data Pinjaman berhasil disimpan.','success',1000);
					$("#divformkel").fadeSlide("hide");
					$('#dataTables').dataTable().fnReloadAjax();
					
				} else {
					//$().showMessage('Terjadi kesalahan. Data gagal disimpan.','danger',700);
					bootbox.alert("Terjadi kesalahan. Data gagal disimpan."+msg.data);
					$("#errorHandler").html(msg.errormsg + msg.data).show();
					
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				//$().showMessage('Terjadi kesalahan.<br />'+	textStatus + ' - ' + errorThrown ,'danger');
				bootbox.alert("Terjadi kesalahan. Data gagal disimpan."+	textStatus + " - " + errorThrown );
			}
		});
	});
	function editPinjaman(obj){
		var id = $(obj).attr('data-id');
		$('#myform input[name="state"]').val(id);		
		$('#lbltitle').text('Edit Data cabang');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('pinjaman/getPinjaman');?>',
			data: {
				id:id
			},
			dataType: 'json',
			success: function(msg) {
				
				if(msg.status =='success'){
					console.log(msg.data);					
					$('#nama').val(msg.data.NAMA);
					$('#jnspeminjam').val(msg.data.JNS_PEMINJAM);
					$('#jnskelamin').val(msg.data.JK);
					$('#noidentitas').val(msg.data.NO_IDENTITAS);
					$('#alamat').val(msg.data.ALAMAT);					
					$('#telepon').val(msg.data.TELP);					
					$('#hp').val(msg.data.NO_HP);					
					$('#jumlah').val(msg.data.JUMLAH);					
					$('#jumlah_lama').val(msg.data.JUMLAH);					
					$('#lama').val(msg.data.LAMA);					
					$('#accvia').val(msg.data.ACC_BAYAR_VIA);					
					$('#accvia_lama').val(msg.data.ACC_BAYAR_VIA);					
					$('#tanggal').val(msg.data.TGL_PINJAM);					
					$('#keperluan').val(msg.data.KEPERLUAN);					
					$('#mengetahui').val(msg.data.MENGETAHUI);					
					$('#menyetujui').val(msg.data.MENYETUJUI);					
					//$('#status').val(msg.data.ISACTIVE);
					$("#divformkel").fadeSlide("show");
					
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				$().showMessage('Terjadi kesalahan.<br />'+	textStatus + ' - ' + errorThrown ,'danger',2000);
			},
			cache: false
		});
	}
	function delPinjaman(idx){
			var pilih=confirm('Hapus Data pinjaman = '+idx+ '?');
			if (pilih==true) {
					$.ajax({
					type	: "POST",
					url		: "<?php echo base_url('pinjaman/delPinjaman');?>",
					data	: "idx="+idx,
					timeout	: 3000,  
					success	: function(res){
						alert("data berhasil dihapus");
						window.location.reload();
						}
					
				});
			}
		}
	$('#btcreate').click(function(){
		$("#divformkel").fadeSlide("show");
		$('#state').val('add');
		//$('#lbltitle').text('Tambah Akun Level 2');
		//$('#idacc').removeAttr('readonly');
		$('#myformkel').reset();
		
		
	});
	$('#btcancelkel').click(function(){
		$("#divformkel").fadeSlide("hide");
	});
    </script>