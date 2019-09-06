<div class="control-group"><a href="javascript:void(0)" id="btcreate" class="btn btn-primary">Tambah Periode</a>
</div><br>
<div id="errorHandler" class="alert alert-danger no-display"></div>
<?php 
echo form_open_multipart(null,array('class'=>'form-horizontal','id'=>'myformkel'));
?>
<div id="divformkel" class="no-display">
<div class="panel panel-default card-view">
        <div class="panel-heading">
									<div class="pull-left">
										<h6 class="panel-title txt-dark"><label id="lbltitle">Tambah Data Periode Buku Baru</label></h6>
									</div>
									
									<div class="clearfix"></div>
								</div>
	<div class="panel-wrapper collapse in">
	<div  class="panel-body row pa-0">

<br>
<div class="row">
	<div class="col-md-8">
		<div class="form-group "><label class="col-sm-4 control-label">Tahun Buku</label>
			<div class="col-sm-4"><?=form_input(array('name'=>'tahun','id'=>'tahun','class'=>'form-control'));?></div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-8">
		<div class="form-group">
        <label class="col-sm-4 control-label">Tanggal Mulai	</label>
        <div class=" col-sm-4 input-group">        
        <input type="text" name="tgl1" id="tgl1" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask placeholder="yyyy-mm-dd"/>
		<div id="bttglawal" class="input-group-addon">
        <i class="fa fa-calendar"></i>
        </div>
        </div><!-- /.input group -->
        </div><!-- /.form group -->
	
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group">
        <label class="col-sm-4 control-label">Tanggal Selesai	</label>
        <div class=" col-sm-4 input-group">        
        <input type="text" name="tgl2" id="tgl2" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask placeholder="yyyy-mm-dd"/>
		<div id="bttglawal2" class="input-group-addon">
        <i class="fa fa-calendar"></i>
        </div>
        </div><!-- /.input group -->
        </div><!-- /.form group -->
	
	</div>
</div>


<div class="row">
	<div class="col-md-8">
		<div class="form-group">
			<label for="status" class="col-sm-4 control-label">Status</label>
			<div class="col-sm-4">
				<?php $data = array('1'=>'Aktif', '0'=>'Tidak Aktif');
					echo form_dropdown('status',$data,'','id="status" class="form-control"');
				?>
			</div>
		</div>
	</div>
</div>

</div><!-- panel-wrapper -->
</div> <!-- panel-default -->
<br>
<div class="row">
	<div class="col-md-12"><input type="hidden" name="id" id="id">
	<input type="hidden" name="state" id="state" value="add">
	<input type="button" class="btn btn-primary" id="btsimpankel" value="Simpan">
	<button type="button" class="btn btn-default" id="btcancelkel">Batal</button>
	</div>
	</div><!-- end modal -->
<br>
</div><!-- panel-default -->

<?php echo form_close();?>  


</div> <!-- divformkel -->

<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="dataTables">
		<thead>
			<tr>
				<th>ID</th>
				<th>TAHUN BUKU</th>				
				<th>TGL MULAI</th>
				<th>TGL SELESAI</th>
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
		//$("[data-mask]").inputmask();
        $('#dataTables').dataTable({
			"bProcessing": true,
			"bServerSide": true,
			"iDisplayLength": 25,
			"bJQueryUI": true,
			"sPaginationType": "full_numbers",
			"aoColumns": [
				{"mData": "ID" },
				{"mData": "TAHUN" },
				{"mData": "TGL1" },
				{"mData": "TGL2" },
				{"mData": "ISACTIVE" },
				{"mData": "ACTION", "sortable":false }
			],
			"sAjaxSource": "<?php echo base_url('periode/json_data');?>"
		});

		

    });


	$('#btcreate').click(function(){
		$("#divformkel").fadeSlide("show");
		$('#state').val('add');
		$('#lbltitle').text('Tambah Data Priode Baru');
		$('#tahun').removeAttr('readonly');
		$('#myformkel').reset();
		
		
	});
	$('#btcancelkel').click(function(){
		$("#divformkel").fadeSlide("hide");
	});
	
	$('#btsimpankel').click(function(){		
		var form_data = $('#myformkel').serialize();
		$().showMessage('Sedang diproses.. Harap tunggu..');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('periode/savePeriode');?>',
			data: form_data,				
			dataType: 'json',
			success: function(msg) {
				 $("#errorHandler").html('&nbsp;').hide();
				 console.log(msg);
				if(msg.status =='success'){
					$().showMessage('Data periode berhasil disimpan.','success',1000);
					$("#divformkel").fadeSlide("hide");
					$('#dataTables').dataTable().fnReloadAjax();
					
				} else {
					$().showMessage('Terjadi kesalahan. Data gagal disimpan.','danger',700);
					//bootbox.alert("Terjadi kesalahan. Data gagal disimpan.");
					$("#errorHandler").html(msg.errormsg).show();
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				//$().showMessage('Terjadi kesalahan.<br />'+	textStatus + ' - ' + errorThrown ,'danger');
				bootbox.alert("Terjadi kesalahan. Data gagal disimpan."+	textStatus + " - " + errorThrown );
			},
			cache: false
		});
		//$().showMessage('Data pembelian berhasil disimpan, data order akan dikirim melalui sms','success',1000);
	});

	function delPeriode(idx, str){
			var pilih=confirm('Hapus Periode Buku Tahun = '+str+ ' dilanjutkan ?');
			if (pilih==true) {
					$.ajax({
					type	: "POST",
					url		: "<?php echo base_url('periode/delPeriode');?>",
					data	: "idx="+idx,
					timeout	: 3000,  
					success	: function(res){
						alert("data berhasil dihapus");
						window.location.reload();
						}
					
				});
			}
		}
	function editPeriode(obj){
		var id = $(obj).attr('data-id');
		$('#myformkel input[name="state"]').val(id);		
		$('#lbltitle').text('Edit Data periode');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('periode/getPeriode');?>',
			data: {
				id:id
			},
			dataType: 'json',
			success: function(msg) {
					$('#tahun').attr('readonly', true);
				if(msg.status =='success'){
					console.log(msg.data);
					
					$('#tahun').val(msg.data.thnbuku);
					$('#tgl1').val(msg.data.tglawal);					
					$('#tgl2').val(msg.data.tglakhir);					
					$('#status').val(msg.data.isactive);
					$("#divformkel").fadeSlide("show");
					
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				$().showMessage('Terjadi kesalahan.<br />'+	textStatus + ' - ' + errorThrown ,'danger',2000);
			},
			cache: false
		});
	}

	$( "#tgl1" ).datepicker({
		//minDate: 'today',
		dateFormat: 'yy-mm-dd',
		onSelect: function( selectedDate ) {
			
		}
	});
	$("#bttglawal").click(function() {
		$("#tgl1").datepicker("show");
	});

	$( "#tgl2" ).datepicker({
		//minDate: 'today',
		dateFormat: 'yy-mm-dd',
		onSelect: function( selectedDate ) {
			
		}
	});
	$("#bttglawal2").click(function() {
		$("#tgl2").datepicker("show");
	});
    </script>