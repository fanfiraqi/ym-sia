

<?php echo form_open('validasijurnal/display',array('class'=>'form-horizontal','id'=>'myform'));?>

<div class="row">
	<div class="col-md-8">
		<div class="form-group">
				<label for="nama" class="col-md-4   control-label">Jenis Jurnal</label>
					<div class="col-sm-4"><?=form_dropdown('cbjenis',$arrJenis,'','id="cbjenis" class="form-control"');?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group">
				<label for="nama" class="col-md-4   control-label">Berdasarkan :</label>					
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-2"></div>
	<div class="col-md-4">
		<div class="input-group ">
			<span class="input-group-addon"><input type="radio" name="pilihan" id="pilihan1" value="validasi" checked ></span>	
			<label class="input-group-addon">Status Belum divalidasi saja (semua tanggal)</label>
		</div>
	</div>
</div>

<br>
<div class="row">
	<div class="col-md-2"></div>
	<div class="col-md-8">
		<div class="input-group ">
			<span class="input-group-addon"><input type="radio"  name="pilihan" id="pilihan2" value="tanggal" ></span>	
			<label class="input-group-addon">Tanggal Jurnal Tertentu</label>
			<div class=" col-md-4 input-group">       
				<input type="text" name="tgl1" id="tgl1" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask placeholder="yyyy-mm-dd" value="<?=date('Y-m-d')?>"/>
				<div class="input-group-addon"><span class="fa fa-calendar" id="bttgl1"></span></div>
			</div>
			</div> 
	</div>
</div>
<br>

<!-- Button -->
<div class="row">
	<div class="col-md-2"></div>
	<div class="col-md-4">
		<div class="form-group"><label class="col-md-2 control-label">&nbsp;</label>
			<div class="col-sm-4"><input type="submit" class="btn btn-primary" id="btOk" value="Lanjutkan">	</div>
		</div>
	</div>
</div>

<hr/>

<?php echo form_close();?>  

<script>
$( "#tgl1" ).datepicker({
		dateFormat: 'yyyy-mm-dd',
		onSelect: function(date) {
            alert(date)
        }
		});
$("#bttgl1").click(function() {
		$("#tgl1").datepicker("show");
	});

</script>
