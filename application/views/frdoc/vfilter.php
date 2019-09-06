<?php echo form_open('rkat_docstatus/result',array('class'=>'form-horizontal','id'=>'myform'));?>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label  class="col-sm-4 control-label">Pilih Jenis Dokumen</label>
			<div class="col-sm-8"><?php	echo form_dropdown('jenis_doc',array('rkat'=>'RKAT (RENCANA KEUANGAN ANGGARAN TAHUNAN)', 'rpb'=>'RPB (RENCANA PENGELUARAN BULANAN)', 'rpm'=>'RPM (RENCANA PENGELUARAN MINGGUAN)'),'','id="jenis_doc" class="form-control"');
				?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group ">
			<label for="nama" class="col-sm-4   control-label">Tahun</label>
			<div class="col-sm-6 form-inline"><?=form_dropdown('cbTahun',$arrThn, date('Y'),'id="cbTahun" class="form-control"');?>&nbsp;&nbsp;
			<label  class="control-label">Bulan</label>
			<?=form_dropdown('cbBulan',$arrBulan, date('m'),'id="cbBulan" class="form-control" ');?></div>
	</div>
	</div>
</div>

<div class="row">
	<div class="col-md-8">
	<div class="form-group"><label  class="col-sm-4 control-label">&nbsp;</label>
	<div class="col-sm-8">
	<?php 
			$btsubmit = array(
					'name'=>'btsubmit',
					'id'=>'btsubmit',
					'value'=>'Next',
					'class'=>'btn btn-primary'
				);
			echo form_submit($btsubmit);?>
	</div>
	</div>
	</div>
</div>
<?php echo form_close();?>
<script type="text/javascript">
$(document).ready(function(){
	$('#cbBulan').attr('disabled',true);
	$('#jenis_doc').change(function(){
		
		if ($('#jenis_doc').val()=="rkat"){
			$('#cbBulan').attr('disabled', true);
		}else{
			$('#cbBulan').removeAttr('disabled');
		}

	});
});

</script>
