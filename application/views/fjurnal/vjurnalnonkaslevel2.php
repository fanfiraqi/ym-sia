<div class="control-group"><a href="javascript:void(0)" id="btcreate" class="btn btn-primary">Tambah Transaksi</a>
</div>
<div id="errorHandler" class="alert alert-danger no-display"></div>
<?php 

echo form_open(null,array('class'=>'form-horizontal','id'=>'myformkel'));
?>
<div id="divformkel" class="no-display">
<div class="control-group" class="span8 m-wrap">
	<h4><u><label id="lbltitle">Tambah Transaksi  Non Kas</label></u></h4>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">Periode Aktif</label>
			<div class="col-sm-4"><label id="lbltahun"><?=$periode?></label></div>
		</div>
	</div>
</div>
<!-- <div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">No Transaksi</label>
			<div class="col-sm-6"><label id="lblnotrans"><?=date('YmdHis')?></label></div>
		</div>
	</div>
</div> -->
<div class="row">
	<div class="col-md-8">
		<div class="form-group">
        <label class="col-sm-4 control-label">Tanggal Jurnal	</label>
        <div class=" col-sm-4 input-group">        
        <input type="text" name="tglJurnal" id="tglJurnal" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask placeholder="yyyy-mm-dd" value="<?=date('Y-m-d')?>"/>
		<div class="input-group-addon">
       <span class="fa fa-calendar" id="bttgl"></span>
        </div>
        </div><!-- /.input group -->
        </div><!-- /.form group -->
	
	</div>
</div>


<!-- DEBET -->
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label class="col-sm-3 control-label">Akun Debet</label>
			<div class="col-sm-3"><?=form_dropdown('level1_D',$level1,'','id="level1_D" class="form-control"');?></div>
			<div class="col-sm-3"><?=form_dropdown('level2_D',$level2,'','id="level2_D" class="form-control"');?></div>
			<div class="col-sm-3"><?=form_dropdown('level3_D',$level3NonKas,'','id="level3_D" class="form-control"');?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label class="col-sm-3 control-label">&nbsp;</label>
			<div class="col-sm-3"><?=form_dropdown('level4_D',$level4,'','id="level4_D" class="form-control"');?></div>
			<div class="col-sm-3"><?=form_dropdown('level5_D',$level5,'','id="level5_D" class="form-control"');?></div>
		</div>
	</div>
</div>


<!-- KREDIT -->
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label class="col-sm-3 control-label">Akun Kredit</label>
			<div class="col-sm-3"><?=form_dropdown('level1_K',$level1,'','id="level1_K" class="form-control"');?></div>
			<div class="col-sm-3"><?=form_dropdown('level2_K',$level2,'','id="level2_K" class="form-control"');?></div>
			<div class="col-sm-3"><?=form_dropdown('level3_K',$level3NonKas,'','id="level3_K" class="form-control"');?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label class="col-sm-3 control-label">&nbsp;</label>
			<div class="col-sm-3"><?=form_dropdown('level4_K',$level4,'','id="level4_K" class="form-control"');?></div>
			<div class="col-sm-3"><?=form_dropdown('level5_K',$level5,'','id="level5_K" class="form-control"');?></div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">Uraian / Memo</label>
			<div class="col-sm-6"><?=form_textarea(array('name'=>'ket','id'=>'ket','class'=>'form-control'));?></div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">Jumlah</label>
			<div class="col-sm-4"><?=form_input(array('name'=>'jumlah','id'=>'jumlah','class'=>'form-control','onkeypress'=>"return numericVal(this,event)", 'value'=>0));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">Bukti/Reff</label>
			<div class="col-sm-4"><?=form_input(array('name'=>'bukti','id'=>'bukti','class'=>'form-control'));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
	<input type="hidden" name="tahun" id="tahun" value="<?=$periode?>">
	<!-- <input type="hidden" name="id" id="id" value="<?=date('YmdHis')?>"> -->
	<input type="hidden" name="state" id="state" value="add">
	<input type="button" class="btn btn-primary" id="btsimpankel" value="Simpan">
	<button type="button" class="btn btn-default" id="btcancelkel">Batal</button>
	</div>
	</div> 
</div><!-- end modal -->
<?php echo form_close();?>  
<br>
<hr/>
<div class="row">
	<div class="col-md-8">
		<div class="form-group">
        <label class="col-sm-4 control-label">Pilih Tanggal Jurnal	</label>
        <div class=" col-sm-4 input-group">        
        <input type="text" name="tgl2" id="tgl2" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask placeholder="yyyy-mm-dd" value="<?=date('Y-m-d')?>"/>
		<div class="input-group-addon">
       <span class="fa fa-calendar" id="bttgl"></span>
        </div>
        </div><!-- /.input group -->
        </div><!-- /.form group -->
	
	</div>
	<div class=" col-sm-4 input-group"><input type="button" class="btn btn-primary" id="btcari" value="Cari"></div>	
</div>

<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="dataTables">
		<thead>
			<tr>
				<th>NOTRANS</th>
				<th>TGL JURNAL</th>				
				<th>KETERANGAN</th>
				<th>DEBET</th>
				<th>KREDIT</th>
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
		$("[data-mask]").inputmask();
        $('#dataTables').dataTable({
			"bProcessing": true,
			"bServerSide": true,
			"iDisplayLength": 25,
			"fnServerParams": function ( aoData ) {
				aoData.push( { "name": "tanggal", "value": $('#tgl2').val() })
			},
			"aoColumns": [
				{"mData": "NOTRANSAKSI" },
				{"mData": "TANGGAL" },
				{"mData": "KETERANGAN" },
				{"mData": "DEBET" },
				{"mData": "KREDIT" },
				{"mData": "ACTION", "sortable":false }
			],
			"sAjaxSource": "<?php echo base_url('jurnalnonkas/json_data');?>"
		});

		

    });

	$( "#tgl2" ).datepicker({dateFormat: 'yyyy-mm-dd'});
	$("#bttgl").click(function() {
		$("#tgl2").datepicker("show");

	});
	$( "#btcari" ).click(function(){
		$('#dataTables').dataTable().fnReloadAjax();
	});
	$('#btcreate').click(function(){
		$("#divformkel").fadeSlide("show");
		$('#state').val('add');
		$('#lbltitle').text('Tambah Transaksi Jurnal Non Kas');
		$('#level1_K').removeAttr('disabled');
		$('#level2_K').removeAttr('disabled');
		$('#level3_K').removeAttr('disabled');
		$('#level4_K').removeAttr('disabled');
		$('#level5_K').removeAttr('disabled');
		$('#level1_D').removeAttr('disabled');
		$('#level2_D').removeAttr('disabled');
		$('#level3_D').removeAttr('disabled');
		$('#level4_D').removeAttr('disabled');
		$('#level5_D').removeAttr('disabled');
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
			url: '<?php echo base_url('jurnalnonkas/savejurnalnonkas');?>',
			data: form_data,				
			dataType: 'json',
			success: function(msg) {
				 $("#errorHandler").html('&nbsp;').hide();
				 console.log(msg);
				if(msg.status =='success'){
					$().showMessage('Data Transaksi Jurnal Non Kas berhasil disimpan.','success',1000);
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

	function deljurnalnonkas(idx){
			var pilih=confirm('Hapus Transaksi Jurnal Non Kas = '+idx+ ' dilanjutkan ?');
			if (pilih==true) {
					$.ajax({
					type	: "POST",
					url		: "<?php echo base_url('jurnalnonkas/deljurnalnonkas');?>",
					data	: "idx="+idx,
					timeout	: 3000,  
					success	: function(res){
						alert("data berhasil dihapus");
						window.location.reload();
						}
					
				});
			}
		}
	function editjurnalnonkas(obj){
		var id = $(obj).attr('data-id');
		$('#myformkel input[name="state"]').val(id);		
		$('#lbltitle').text('Edit Transaksi Jurnal Non Kas ');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('jurnalnonkas/getjurnalnonkas');?>',
			data: {
				id:id
			},
			dataType: 'json',
			success: function(msg) {
				if(msg.status =='success'){
					console.log(msg.data);
					$('#lbltahun').val(msg.periode);
					$('#lblnotrans').val(msg.data.notransaksi);					
					//$('#id').val(msg.data.notransaksi);					
					$('#tglJurnal').val(msg.data.tanggal);					
					$('#level1_D').attr('disabled', true);
					$('#level2_D').attr('disabled', true);
					$('#level3_D').attr('disabled', true);
					$('#level4_D').attr('disabled', true);
					$('#level5_D').attr('disabled', true);
					$('#level1_K').attr('disabled', true);
					$('#level2_K').attr('disabled', true);
					$('#level3_K').attr('disabled', true);
					$('#level4_K').attr('disabled', true);
					$('#level5_K').attr('disabled', true);
					$('#ket').val(msg.data.keterangan);
					$('#jumlah').val(msg.data.jumlah);
					$('#bukti').val(msg.data.nobuktiref);
					$("#divformkel").fadeSlide("show");
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				$().showMessage('Terjadi kesalahan.<br />'+	textStatus + ' - ' + errorThrown ,'danger',2000);
			},
			cache: false
		});
	}

	//DEBETTT

	$('#level1_D').change(function(){
		var idParent=$(this).val();
		$.ajax({
		url: "<?php echo base_url('rekeningakun/comboLevel2_nonkas'); ?>",
		dataType: 'json',
		type: 'POST',
		data: {level1:$(this).val()},
		success: function(respon){
			$('#level2_D').find('option').remove().end();
			if (respon.status==1){
				var item = respon.data;
				for (opt=0;opt<item.length;opt++){
					$('#level2_D').append('<option value="'+item[opt].idacc+'" >'+item[opt].nama+'</option>');
				}
				$('#level2_D').trigger('change');
				
				}
			}
		});

	//});
	}).trigger('change');

	$('#level2_D').change(function(){
		var idParentLvl2=$(this).val();
		

	$.ajax({
			url: "<?php echo base_url('rekeningakun/comboLevel3_nonkas'); ?>",
			dataType: 'json',
			type: 'POST',
			data: {level2:$(this).val()},
			success: function(respon){
				$('#level3_D').find('option').remove().end();
				if (respon.status==1){
					var item = respon.data;
					for (opt=0;opt<item.length;opt++){
						$('#level3_D').append('<option value="'+item[opt].idacc+'" >'+item[opt].nama+'</option>');
					}
					$('#level3_D').trigger('change');
					
					}
				}
			});

		//});
	}).trigger('change');
	
	$('#level3_D').change(function(){
		
    $.ajax({
			url: "<?php echo base_url('rekeningakun/comboLevel4'); ?>",
			dataType: 'json',
			type: 'POST',
			data: {level3:$(this).val()},
			success: function(respon){
				$('#level4_D').find('option').remove().end();
				if (respon.status==1){
					var item = respon.data;
					for (opt=0;opt<item.length;opt++){
						$('#level4_D').append('<option value="'+item[opt].idacc+'" >'+item[opt].nama+'</option>');
					}
					$('#level4_D').trigger('change');
					
					}
				}
			});

		//});
	}).trigger('change');

	$('#level4_D').change(function(){
	
    $.ajax({
			url: "<?php echo base_url('rekeningakun/comboLevel5'); ?>",
			dataType: 'json',
			type: 'POST',
			data: {level4:$('#level4_D').val()},
			success: function(respon){
				$('#level5_D').find('option').remove().end();
				if (respon.status==1){
					var item = respon.data;
					for (opt=0;opt<item.length;opt++){
						$('#level5_D').append('<option value="'+item[opt].idacc+'" >'+item[opt].nama+'</option>');
					}
					$('#level5_D').trigger('change');
					
					}
				}
			});

		//});
	}).trigger('change');

	//KREDITTT

	$('#level1_K').change(function(){
		var idParent=$(this).val();
		$.ajax({
		url: "<?php echo base_url('rekeningakun/comboLevel2_nonkas'); ?>",
		dataType: 'json',
		type: 'POST',
		data: {level1:$(this).val()},
		success: function(respon){
			$('#level2_K').find('option').remove().end();
			if (respon.status==1){
				var item = respon.data;
				for (opt=0;opt<item.length;opt++){
					$('#level2_K').append('<option value="'+item[opt].idacc+'" >'+item[opt].nama+'</option>');
				}
				$('#level2_K').trigger('change');
				
				}
			}
		});

	//});
	}).trigger('change');

	$('#level2_K').change(function(){
		var idParentLvl2=$(this).val();
		

	$.ajax({
			url: "<?php echo base_url('rekeningakun/comboLevel3_nonkas'); ?>",
			dataType: 'json',
			type: 'POST',
			data: {level2:$(this).val()},
			success: function(respon){
				$('#level3_K').find('option').remove().end();
				if (respon.status==1){
					var item = respon.data;
					for (opt=0;opt<item.length;opt++){
						$('#level3_K').append('<option value="'+item[opt].idacc+'" >'+item[opt].nama+'</option>');
					}
					$('#level3_K').trigger('change');
					
					}
				}
			});

		//});
	}).trigger('change');
	
	$('#level3_K').change(function(){
		
    $.ajax({
			url: "<?php echo base_url('rekeningakun/comboLevel4'); ?>",
			dataType: 'json',
			type: 'POST',
			data: {level3:$(this).val()},
			success: function(respon){
				$('#level4_K').find('option').remove().end();
				if (respon.status==1){
					var item = respon.data;
					for (opt=0;opt<item.length;opt++){
						$('#level4_K').append('<option value="'+item[opt].idacc+'" >'+item[opt].nama+'</option>');
					}
					$('#level4_K').trigger('change');
					
					}
				}
			});

		//});
	}).trigger('change');

	$('#level4_K').change(function(){
	
    $.ajax({
			url: "<?php echo base_url('rekeningakun/comboLevel5'); ?>",
			dataType: 'json',
			type: 'POST',
			data: {level4:$('#level4_K').val()},
			success: function(respon){
				$('#level5_K').find('option').remove().end();
				if (respon.status==1){
					var item = respon.data;
					for (opt=0;opt<item.length;opt++){
						$('#level5_K').append('<option value="'+item[opt].idacc+'" >'+item[opt].nama+'</option>');
					}
					$('#level5_K').trigger('change');
					
					}
				}
			});

		//});
	}).trigger('change');
    </script>