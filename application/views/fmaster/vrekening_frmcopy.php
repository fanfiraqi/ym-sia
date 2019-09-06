<?php echo form_open(null,array('class'=>'form-horizontal','id'=>'myform'));?>
<div class="row">	
	<div class="col-xs-12">
		<div class="panel panel-default"><div class="panel-heading">Salin Akun rekening perkiraan 
		</div><div class="panel-body">
		
		<div class="row">
			<div class="col-xs-12">
				<div class="form-group"><label class="col-sm-4 control-label">Pilih cabang sumber</label>
					<div class="col-sm-8"><?=form_dropdown('cab_source',$cab_source,'','id="cab_source" class="form-control"');?></div>
				</div>
			</div>
		</div>	
		
		<div class="row">
			<div class="col-xs-12">
				<div class="form-group"><label class="col-sm-4 control-label">Pilih cabang tujuan</label>
					<div class="col-sm-8"><?=form_dropdown('cab_dest',$cab_dest,'','id="cab_dest" class="form-control"');?></div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-xs-12"><label class="col-sm-4 control-label">&nbsp;</label>
				
					<?php 
					$btsubmit = array(
							'name'=>'btcopy',
							'id'=>'btcopy',
							'value'=>'Proses',
							'class'=>'btn btn-primary'
						);
					echo form_submit($btsubmit);?> 
			</div>
		</div>

		
		</div></div>
	</div>
</div>
<hr />

<?php echo form_close();?>
<script type="text/javascript">
$('#btcopy').click(function(e){
	e.preventDefault();
	 
		$().showMessage('Sedang diproses.. Harap tunggu..');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('rekeningakun/copyAkun');?>',
			data: {cab_source:$("#cab_source").val(), cab_dest:$("#cab_dest").val()},				
			dataType: 'json',
			success: function(msg) {
				 console.log(msg);
				if(msg.status =='success_insert'){
					$().showMessage('Data Akun Perkiraan berhasil disalin.','success',2000);
					setInterval(window.location.reload(), 4000);
				} else if(msg.status =='coa_exist'){
					$().showMessage('Kode COA Akuntansi Cabang Tujuan sudah ada','danger',700);
				}else {
					$().showMessage('Terjadi kesalahan. Data gagal disimpan.','danger',700);
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				bootbox.alert("Terjadi kesalahan. Data gagal disimpan."+	textStatus + " - " + errorThrown );
			},
			cache: false
		});
			
	});

	</script>

