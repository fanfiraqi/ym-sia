<div class="control-group"><a href="javascript:void(0)" id="btcreate" class="btn btn-primary">Tambah Item</a></div>
<div id="errorHandler" class="alert alert-danger no-display"></div>
<?php echo form_open(null,array('class'=>'form-horizontal','id'=>'myform'));?>

<div class="control-group" class="span8 m-wrap">
	<h4><u><label id="lbltitle">Tambah Item Master RKAT Baru</label></u></h4>
</div>
<div class="row">
	<div class="col-md-10">
		<div class="form-group"><label  class="col-sm-4 control-label">Kelompok</label>
			<div class="col-sm-6"><input type="hidden" name="kelompok" id="kelompok" value="<?php echo $kelompok?>"><label  class="control-label" id="strKelompok"><?php echo strtoupper($strKelompok)?></label></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-10">
		<div class="form-group"><label  class="col-sm-4 control-label">Level Item</label>
			<div class="col-sm-8"><input type="hidden" name="level" id="level" value="<?php echo $level_pilih?>"><label  class="control-label" id="lbllevel"><?php echo $level_pilih?></label></div>
		</div>
	</div>
</div>

<!-- <div id="divShowLevel" class="no-display"> -->
<?	//set trigger change
	$trigger1="";
	$trigger2="";
	if ($level_pilih >= 3){ //cb lvl 1 trigger to 2
		$trigger1 = ' onchange="getLevel2()"';
	}

	if ($level_pilih == 4){ //cb lvl 2 trigger to 3
		$trigger2 = ' onchange="getLevel3()"';
	}
	
	if ($level_pilih > 1){ 	
	?>
	<div class="row">
		<div class="col-md-10">
			<div class="form-group"><label  class="col-sm-4 control-label">Item Parent Level 1</label>
				<div class="col-sm-8"><?php	echo form_dropdown('parent_level1',$cblevel1,'','id="parent_level1" class="form-control"'.$trigger1);
					?></div>
			</div>
		</div>
	</div> 
		<? if ($level_pilih > 2){ ?>
		<div class="row">
			<div class="col-md-10">
				<div class="form-group"><label  class="col-sm-4 control-label">Item Parent  Level 2</label>
					<div class="col-sm-8"><?php	echo form_dropdown('parent_level2',$cblevel2,'','id="parent_level2" class="form-control"'.$trigger2);
						?></div>
				</div>
			</div>
		</div>
			<? if ($level_pilih > 3){ ?>
				<div class="row">
					<div class="col-md-10">
						<div class="form-group"><label  class="col-sm-4 control-label">Item Parent Level 3</label>
							<div class="col-sm-8"><?php	echo form_dropdown('parent_level3',$cblevel3,'','id="parent_level3" class="form-control"');
								?></div>
						</div>
					</div>
				</div>
			<? } ?>
		<? } ?>
		 
<? } ?>

<div class="row">
	<div class="col-md-10">
		<div class="form-group"><label class="col-sm-4 control-label">Nama Item</label>
			<div class="col-sm-8"><?=form_input(array('name'=>'nama','id'=>'nama','class'=>'form-control'));?></div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-10">
		<div class="form-group"><label  class="col-sm-4 control-label">Padanan Kode Rekening Akuntansi</label>
			<div class="col-sm-4"><?=form_input(array('name'=>'idacc','id'=>'idacc','class'=>'form-control'));?>	</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-10">
		<div class="form-group"><label class="col-sm-4 control-label">Status ditampilkan</label>
			<div class="col-sm-4"><?=form_dropdown('status',array('1'=>'Aktif','0'=>'Tidak Aktif'),'','id="status" class="form-control"');?>	</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-10"><label class="col-sm-4 control-label"></label>
	<input type="hidden" name="id" id="id">
	<input type="hidden" name="state" id="state" value="add">
	<input type="button" class="btn btn-primary" id="btsimpan" value="Simpan Baru">
	<button type="button" class="btn btn-default" id="btcancel">Batal</button>
	</div>
</div>

<?php echo form_close();?>
<hr />
<br>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="dataTables-rkat">
		<thead>
			<tr>
				<th>ID</th>
				<th>KELOMPOK</th>				
				<th>NAMA ITEM</th>				
				<th>LEVEL</th>
				<th>ID PARENT</th>
				<th>KODE ACC</th>
				<th>STATUS</th>
				<th>ACTION</th>
			</tr>
		</thead>
		<tbody>
			
		</tbody>
	</table>
</div>
<!-- /.table-responsive -->
<script>
    $(document).ready(function() {
        $('#dataTables-rkat').dataTable({
			"bProcessing": true,
			"bServerSide": true,
			"iDisplayLength": 25,
			"fnServerParams": function ( aoData ) {
				aoData.push( { "name": "level", "value": $('#level').val() },
							{ "name": "kelompok", "value": $('#kelompok').val() });
			},
			"aoColumns": [
				{"mData": "ID" },
				{"mData": "KELOMPOK" },
				{"mData": "NAMA_ITEM" },
				{"mData": "LEVEL" },
				{"mData": "IDPARENT" },
				{"mData": "KODE_REKPER" },
				{"mData": "ISACTIVE" },
				{"mData": "ACTION", "sortable":false }
			],
			"sAjaxSource": "<?php echo base_url('rkat_item/json_data_lvl');?>"
		});
    });	
	
	$('#btcancel').click(function(){
		$('#myform').reset();
	});

	$('#btcreate').click(function(){		
		$('#state').val('add');
		$('#lbltitle').text('Tambah Item Master RKAT Baru');	
		$('#btsimpan').val('Simpan Baru');
		$('#myform').reset();
	});

	$('#btsimpan').click(function(){		
		var form_data = $('#myform').serialize();
		$().showMessage('Sedang diproses.. Harap tunggu..');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('rkat_item/saveItemMaster');?>',
			data: form_data,				
			dataType: 'json',
			success: function(msg) {
				 $("#errorHandler").html('&nbsp;').hide();
				 console.log(msg);
				if(msg.status =='success'){
					$().showMessage('Data Item Master RKAT berhasil disimpan.','success',1500);
					//$("#divformkel").fadeSlide("hide");
					$('#state').val('add');
					$('#lbltitle').text('Tambah Item Master RKAT Baru');	
					$('#btsimpan').val('Simpan Baru');
					$('#myform').reset();
					$('#dataTables-rkat').dataTable().fnReloadAjax();
					
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

	
	function editItem(obj){
		$('#myform').reset();
		var id = $(obj).attr('data-id');
		$('#myform input[name="state"]').val(id);		
		$('#lbltitle').text('Edit Data Master Item RKAT');
		$('#btsimpan').val('Update Item');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('rkat_item/getItem');?>',
			data: {
				id:id
			},
			dataType: 'json',
			success: function(msg) {
				
				if(msg.status =='success'){
					console.log(msg.data);					
					$('#kelompok').val(msg.data.kelompok);
					$('#strKelompok').val(msg.strKelompok);
					$('#level').val(msg.data.level);
					$('#lbllevel').text(msg.data.level);
					$('#nama').val(msg.data.nama_item);
					$('#idacc').val(msg.data.kode_rekper);
					$('#status').val(msg.data.isactive);
					
					//cek level
					switch (msg.data.level)	{
						case '1': break;
						case '2': 
							$('#parent_level1').val(msg.idparent);
							break;
						case '3': 
							$('#parent_level1').val(msg.idL1);
							$('#parent_level2').val(msg.idparent);
							break;
						case '4': 
							$('#parent_level1').val(msg.idL1);
							$('#parent_level2').val(msg.idL2);
							$('#parent_level2').trigger('change');
							alert("Processing...finish");
							$('#parent_level3').val(msg.idparent);
							
							
							//$('#parent_level3 option[value="' + msg.idparent + '"]').prop('selected', true);						
							//$('#parent_level3').trigger('change');
							
							break;
					
					}
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				$().showMessage('Terjadi kesalahan.<br />'+	textStatus + ' - ' + errorThrown ,'danger',2000);
			},
			cache: false
		});
	}

	function getLevel2(){
		var idparent = $("#parent_level1").val();
		var kelompok = $("#kelompok").val();
		$.ajax({
		url: "<?php echo base_url('rkat_item/getLevel2'); ?>",
		dataType: 'json',
		type: 'POST',
		data: {idparent:idparent , kelompok:kelompok},
		success: function(respon){
			$('#parent_level2').find('option').remove().end();
			if (respon.status==1){
				var item = respon.data;
				for (opt=0;opt<item.length;opt++){
					$('#parent_level2').append('<option value="'+item[opt].id+'" >'+item[opt].nama_item+'</option>');
				}

				$('#parent_level2').trigger('change');
				
				}
			}
		});
	}

	function getLevel3(){
		var idparent = $("#parent_level2").val();
		var kelompok = $("#kelompok").val();
		$.ajax({
		url: "<?php echo base_url('rkat_item/getLevel3'); ?>",
		dataType: 'json',
		type: 'POST',
		data: {idparent:idparent , kelompok:kelompok},
		success: function(respon){
			$('#parent_level3').find('option').remove().end();
			if (respon.status==1){
				var item = respon.data;
				for (opt=0;opt<item.length;opt++){
					$('#parent_level3').append('<option value="'+item[opt].id+'" >'+item[opt].nama_item+'</option>');
				}

				$('#parent_level3').trigger('change');
				
				}
			}
		});
	}
</script>