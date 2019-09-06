<div id="errorHandler" class="alert alert-danger no-display"></div>
<?php echo form_open('pengunci/saveForm',array('class'=>'form-horizontal','id'=>'myform'));?>
<div class="alert alert-success alert-block">
<ul>
	<li>Form Pengunci adalah fasilitas untuk mengunci transaksi berdasarkan tanggal yang disetting
	<li>Yang Berhak mengunci adalah yang memiliki akses admin, Staf Accounting, dan Manager Keuangan
	<li>Tanggal yang disetting adalah sebagai patokan bahwa dari tanggal tersebut sampai transaksi pada tanggal sebelum pengunci tidak bisa diedit oleh kasir dan Staf Accounting, jika ada transaksi pada tanggal sebelum pengunci butuh diedit maka hanya manager yang bisa melakukan editing transaksi
	<li>Proses setting tanggal pengunci (Closing) tidak bisa dilakukan jika masih ada transaksi yang belum divalidasi
</ul>
</div>
<div class="row">
			<div class="col-md-8">
				<div class="form-group"><label class="col-sm-4 control-label">Penguncian</label>
					<div class="col-sm-4"><?=form_dropdown('wilayah',array('Pusat'=>'Pusat', 'Cabang'=>'Cabang'),'','id="wilayah" class="form-control"');?></div>
				</div>
			</div>
		</div>	
<div class="row">
	<div class="col-md-8">
		<div class="form-group">
        <label class="col-sm-4 control-label">Tanggal Pengunci	</label>
        <div class=" col-sm-4 input-group">        
        <input type="text" name="tglKunci" id="tglKunci" class="form-control" placeholder="yyyy-mm-dd" value="<?=(sizeof($res)>0?$res->tanggal:date('Y-m-d'))?>"/>
		<div class="input-group-addon">
        <span class="fa fa-calendar" id="bttgl"></span>
        </div>
        </div><!-- /.input group -->		
        </div><!-- /.form group -->
		
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">Pihak Pengunci Terakhir</label>
			<div class="col-sm-4"><input type="text" name="pengunci" id="pengunci" class="form-control" value="<?=(sizeof($res)>0?$res->pengunci:$this->session->userdata('auth')->NAMA)?>" readonly></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">Waktu Penguncian terakhir</label>
			<div class="col-sm-4"><input type="text" name="waktu" id="waktu" class="form-control" value="<?=(sizeof($res)>0?$res->updatetime:date('Y-m-d H:i:s'))?>" readonly></div>
		</div>
	</div>
</div>



<div class="row">
	<div class="col-md-8">	
	<label class="col-sm-4 control-label"></label>
	<input type="hidden" name="state" id="state" value="1">
	<input type="button" class="btn btn-primary" id="btsimpan" value="Simpan">
	<!--<input type="reset" class="btn btn-default" id="btcancel">-->
	</div>
</div>

<?php echo form_close();?>


<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="tabelku">
		<thead>
			<tr>
				<th>ID</th>
				<th>Penguncian</th>
				<th>Tanggal</th>
				<th>Pengunci Terakhir</th>
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
		 $('#tabelku').dataTable({
			"bProcessing": true,
			"bServerSide": true,
			"iDisplayLength": 25,
			"aoColumns": [
				{"mData": "id" },
				{"mData": "penguncian" },
				{"mData": "tanggal" },
				{"mData": "pengunci" },
				{"mData": "ACTION", "sortable":false }
			],
			"sAjaxSource": "<?php echo base_url('pengunci/json');?>"
		});
		
    });
	function editParams(obj){
		var id = $(obj).attr('data-id');
		
		$('#myformkel input[name="state"]').val(id);
		
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('pengunci/getParams');?>',
			data: {
				id:id
			},
			dataType: 'json',
			success: function(msg) {
				if(msg.status =='success'){
					console.log(msg.data);
					$('#wilayah').val(msg.data.penguncian);
					$('#tglKunci').val(msg.data.tanggal);								
					$('#pengunci').val(msg.data.pengunci);
					$("#divformkel").fadeSlide("show");					
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				$().showMessage('Terjadi kesalahan.<br />'+	textStatus + ' - ' + errorThrown ,'danger',2000);
			},
			cache: false
		});
	}
	$( "#tglKunci" ).datepicker({
		dateFormat: 'yy-mm-dd',
		onSelect: function(date) {
           // alert(date)
        }
		});
	$("#bttgl").click(function() {
		$("#tglKunci").datepicker("show");
	});	

	
	$('#btcancel').click(function(){
		$('#myform').reset();
	});

	
	$('#btsimpan').click(function(){		
		var form_data = $('#myform').serialize();
		$().showMessage('Sedang diproses.. Harap tunggu..');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('pengunci/saveForm');?>',
			data: form_data,				
			dataType: 'json',
			success: function(msg) {
				 $("#errorHandler").html('&nbsp;').hide();
				 console.log(msg);
				if(msg.status =='success'){
					$().showMessage('Data Penguncian/Closing berhasil disimpan.','success',2000);
					$("#divformkel").fadeSlide("hide");
					window.location.reload();
					
				} else {
					$().showMessage('Data gagal disimpan. '+ msg.errormsg,'danger',2000);
					//bootbox.alert('Penguncian/Closing gagal. '+ msg.errormsg);
					$("#errorHandler").html(msg.errormsg).show();
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				bootbox.alert("Terjadi kesalahan. Data gagal disimpan."+	textStatus + " - " + errorThrown );
			},
			cache: false
		});
		//$().showMessage('Data pembelian berhasil disimpan, data order akan dikirim melalui sms','success',1000);
	});

	
</script>