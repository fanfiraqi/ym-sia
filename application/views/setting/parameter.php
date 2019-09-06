<?php errorHandler();
echo form_open_multipart('setting/savingParams',array('class'=>'form-horizontal','id'=>'myformkel'));
?>
<div id="divformkel" class="no-display">
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">NAMA PERUSAHAAN</label>
			<div class="col-sm-8"><?=form_input(array('name'=>'value1','id'=>'value1','class'=>'form-control'));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">DIVISI/DEPARTEMEN</label>
			<div class="col-sm-8"><?=form_input(array('name'=>'value3','id'=>'value3','class'=>'form-control'));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label  class="col-sm-4 control-label">DESKRIPSI/ALAMAT/TELP</label>
			<div class="col-sm-4"><?=form_textarea(array('name'=>'description','id'=>'description','class'=>'form-control', 'rows'=>5, 'cols'=>45));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">LOGO PERUSAHAAN</label>
			<div class="col-sm-5"><?=form_upload(array('name'=>'userfile','id'=>'userfile','class'=>'form-control'));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12"><input type="hidden" name="id" id="id">
	<input type="submit" class="btn btn-primary" id="btsimpankel" value="Simpan">
	<button type="button" class="btn btn-default" id="btcancelkel">Batal</button>
	</div>
	</div> <hr/>
</div><!-- end modal -->

<?php echo form_close();?>  
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="dataTables">
		<thead>
			<tr>
				<th>ID</th>
				<th>Nama Perusahaan</th>
				<th>Departemen</th>
				<th>Alamat</th>
				<th>Logo Perusahaan</th>				
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
				{"mData": "id" },
				{"mData": "value1" },
				{"mData": "value3" },
				{"mData": "description" },
				{"mData": "value2" },
				{"mData": "ACTION", "sortable":false }
			],
			"sAjaxSource": "<?php echo base_url('setting/json');?>"
		});

    });
$('#btcancelkel').click(function(){
		$("#divformkel").fadeSlide("hide");
	});
/*$('#myformkel').submit(function(event) {
	$(this).saveForm('<?php echo base_url('setting/savingParams');?>','<?php echo base_url('setting/parameter');?>');
	event.preventDefault();
});*/
function editParams(obj){
		var id = $(obj).attr('data-id');
		
		$('#myformkel input[name="state"]').val(id);
		
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('setting/getParams');?>',
			data: {
				id:id
			},
			dataType: 'json',
			success: function(msg) {
				if(msg.status =='success'){
					console.log(msg.data);
					$('#id').val(msg.data.id);
					$('#value1').val(msg.data.value1);								
					$('#value3').val(msg.data.value3);								
					//$('#value2').val(msg.data.value2);								
					$('#description').val(msg.data.description);								
					$("#divformkel").fadeSlide("show");					
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				$().showMessage('Terjadi kesalahan.<br />'+	textStatus + ' - ' + errorThrown ,'danger',2000);
			},
			cache: false
		});
	}

</script>