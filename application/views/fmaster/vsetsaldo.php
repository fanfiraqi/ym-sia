<?
$wilayah=$cabang=$this->common_model->comboGetMasterCabang();
?>
<?php echo form_open(null,array('class'=>'form-horizontal','id'=>'myform'));?>
<div class="row">
		<div class="col-md-8">
			<div class="form-group"><label class="col-md-2 control-label">Wilayah</label>
				<div class="col-sm-8"><?=form_dropdown('wilayah',$wilayah,'','id="wilayah" name="wilayah" class="form-control"');?>			
				</div>
			</div>
		</div>
	</div>
 <div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-md-2 control-label">Akun Level 1 </label>
			<div class="col-sm-8"><?=form_dropdown('level1',$level1,'','id="level1" class="form-control" ');?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-md-2 control-label">Akun Level 2 </label>
			<div class="col-sm-8"><?=form_dropdown('level2',$level2,'','id="level2" class="form-control" disabled');?>
			<input type="hidden" name="kelompok" id="kelompok" value="A">
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-md-2 control-label">Akun Level 3 </label>
			<div class="col-sm-8"><?=form_dropdown('level3',$level3,'','id="level3" class="form-control" disabled');?>
			
			</div>
		</div>
	</div>
</div> 

<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-md-2 control-label">&nbsp;</label>
			<div class="col-sm-8">			
		<input type="button" class="btn btn-primary" id="btOk" value="Pilih">	</div>
		</div>
	</div>
</div>

<hr/>

<?php echo form_close();?>  

<br>
<div class="table-responsive">
<?php echo form_open(null,array('class'=>'form-horizontal','id'=>'frmSetSaldo'));?>
	<table class="table table-striped table-bordered table-hover" id="dataTables-cab">
		<thead>
			<tr>
				<th>IDACC</th>
				<th>NAMA AKUN</th>				
				<th>LEVEL</th>				
				<th>ID PARENT</th>				
				<th>CABANG</th>				
				<th>SALDO AWAL</th>
			</tr>
		</thead>
		<tbody>
			
		</tbody>
	</table>
</form>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="form-group">	<input type="button" class="btn btn-primary" id="btSimpan" value="Simpan">	</div>
		
	</div>
</div>




<script>
$(document).ready(function() {
	 $('#dataTables-cab').dataTable({
			"bProcessing": true,
			"bServerSide": true,
			"iDisplayLength": 25,
			"bFilter": false,
			"fnServerParams": function ( aoData ) {
					aoData.push({ "name": "lvl3", "value": $('#level3').val() }, { "name": "cab", "value": $('#wilayah').val() });
				},
			"aoColumns": [
				{"mData": "IDACC" },
				{"mData": "NAMA" },
				{"mData": "LEVEL" },
				{"mData": "IDPARENT" },
				{"mData": "ID_CAB" },
				{"mData": "SALDO" }
			],
			"sAjaxSource": "<?php echo base_url('setSaldo/json_data');?>"
		});
	
});

$('#level1').change(function(){
		var idParent=$(this).val();
		$('#level2').removeAttr('disabled');
		$('#level3').removeAttr('disabled');
		switch(idParent.substr(0,1)){
			case '1': $('#kelompok').val('A'); break;
			case '2': $('#kelompok').val('K'); break;
			case '3': $('#kelompok').val('M'); break;
			case '4': $('#kelompok').val('P'); break;
			case '5': $('#kelompok').val('B'); break;
			default : $('#kelompok').val('L'); break;
		}

		$.ajax({
		url: "<?php echo base_url('rekeningakun/comboLevel2'); ?>",
		dataType: 'json',
		type: 'POST',
		data: {level1:$(this).val()},
		success: function(respon){
			$('#level2').find('option').remove().end();
			if (respon.status==1){
				var item = respon.data;
				for (opt=0;opt<item.length;opt++){
					$('#level2').append('<option value="'+item[opt].idacc+'" >'+item[opt].nama+'</option>');
				}
				$('#level2').trigger('change');
				
			}
		}
	});

	//});
	}).trigger('change');

$('#level2').change(function(){
		var idParentLvl2=$(this).val();
	
	$.ajax({
			url: "<?php echo base_url('rekeningakun/comboLevel3'); ?>",
			dataType: 'json',
			type: 'POST',
			data: {level2:$(this).val()},
			success: function(respon){
				$('#level3').find('option').remove().end();
				if (respon.status==1){
					var item = respon.data;
					for (opt=0;opt<item.length;opt++){
						$('#level3').append('<option value="'+item[opt].idacc+'" >'+item[opt].nama+'</option>');
					}
					$('#level3').trigger('change');
					
					}
				}
			});

		//});
	}).trigger('change');


$('#btOk').click(function(){
	$('#dataTables-cab').dataTable().fnReloadAjax();
});

$('#btSimpan').click(function(){		
		var form_data = $('#frmSetSaldo').serialize();
		$().showMessage('Sedang diproses.. Harap tunggu..');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('setSaldo/saveSaldo');?>',
			data: form_data,				
			dataType: 'json',
			success: function(msg) {				 
				 //console.log(msg);
				if(msg.status =='success'){
					$().showMessage('Data Saldo Awal berhasil disimpan.','success',1000);					
					$('#dataTables-cab').dataTable().fnReloadAjax();
					
				} else {
					$().showMessage('Terjadi kesalahan. Data gagal disimpan.','danger',700);
					$("#errorHandler").html(msg.errormsg).show();
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				$().showMessage('Terjadi kesalahan.<br />'+	textStatus + ' - ' + errorThrown ,'danger');
			},
			cache: false
		});
	});
</script>
