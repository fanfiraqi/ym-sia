<?php echo form_open('rkat_item/master_create',array('class'=>'form-horizontal','id'=>'myform'));?>
<div class="row">
	<div class="col-md-6">
		<div class="form-group"><label  class="col-sm-4 control-label">Pilih Kelompok</label>
			<div class="col-sm-8"><?php	echo form_dropdown('kelompok',array('PHP'=>'Penghimpunan', 'PDG'=>'Pengeluaran Pendayagunaan', 'SDM'=>'Pengeluaran SDM dan UMUM'),'','id="kelompok" class="form-control"');
				?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="form-group"><label  class="col-sm-4 control-label">Level Item</label>
			<div class="col-sm-4"><?php	echo form_dropdown('level',array('1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4'),'','id="level" class="form-control"');
				?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
	<div class="control-group"><label  class="col-sm-4 control-label"></label>
	<?php 
			$btsubmit = array(
					'name'=>'btsubmit',
					'id'=>'btsubmit',
					'value'=>'Kelola Item',
					'class'=>'btn btn-primary'
				);
			echo form_submit($btsubmit);?>
	<a href="javascript:void(0)" id="btview" onclick="displayAll()" class="btn btn-primary">View All Kelompok Item</a></div>
	</div>
</div>
<?php echo form_close();?>
<script type="text/javascript">
$(document).ready(function(){

});
function displayAll(){		
		$.ajax({
			url: "<?php echo base_url('rkat_item/viewItems'); ?>",
			dataType: 'html',
			type: 'POST',
			data: {ajax:'true', kelompok: $('#kelompok').val() },
			success:
				function(data){
					bootbox.dialog({
					  message: data,
					  title: "Tampilan/View Item-Item RKAT",
					  buttons: {						
						main: {
						  label: "OK",
						  className: "btn-warning",
						  callback: function() {
							console.log("Primary button");
						  }
						}
					  }
					});
				}
		});
		
	}

</script>
