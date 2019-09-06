<br><div class="control-group"><a href="javascript:void(0)" id="btcreate5" class="btn btn-primary">Tambah Level 5</a>
</div>
<div id="errorHandler5" class="alert alert-danger no-display"></div>
<?php 
$level1=$this->common_model->comboGetLevel1(' [ Pilih Akun Induk/Level 1 ] ');
$level2=$this->common_model->comboGetLevel2(' [ Pilih Akun Level 2 ] ');
$level3=$this->common_model->comboGetLevel3(' [ Pilih Akun Level 3 ] ');
$level4=$this->common_model->comboGetLevel4(' [ Pilih Akun Level 4 ] ');
echo form_open_multipart(null,array('class'=>'form-horizontal','id'=>'myformLevel5'));
?>
<div id="divformLvl_5" class="no-display">
<div class="control-group" >
	<h4><u><label id="lbltitle_5">Tambah Akun Level 5</label></u></h4>
</div>

<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">Akun Header/Level 1 </label>
			<div class="col-sm-8"><?=form_dropdown('level1_5',$level1,'','id="level1_5" class="form-control"');?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">Akun Header/Level 2 </label>
			<div class="col-sm-8"><?=form_dropdown('level2_5',$level2,'','id="level2_5" class="form-control"');?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">Akun Header/Level 3 </label>
			<div class="col-sm-8"><?=form_dropdown('level3_5',$level3,'','id="level3_5" class="form-control"');?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">Akun Induk/Level 4 </label>
			<div class="col-sm-8"><?=form_dropdown('level4_5',$level4,'','id="level4_5" class="form-control"');?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label  class="col-sm-4 control-label">ID Akun Level 5</label>
			<div class="col-sm-4"><?=form_input(array('name'=>'idacc_5','id'=>'idacc_5','class'=>'form-control'));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label  class="col-sm-4 control-label">Nama Akun Level 5</label>
			<div class="col-sm-8"><?=form_input(array('name'=>'nama_5','id'=>'nama_5','class'=>'form-control'));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label  class="col-sm-4 control-label">Saldo Awal</label>
			<div class="col-sm-6"><?=form_input(array('name'=>'saldoawal_5','id'=>'saldoawal_5','class'=>'form-control','onkeypress'=>"return numericVal(this,event)",  "onclick"=>"clickObj(this)", 'value'=>0));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group">
			<label for="sex" class="col-sm-4 control-label">Pemilik Akun Wilayah/Cabang</label>
			<div class="col-sm-4">
				<?php echo form_dropdown('cabang_5',$cabang,'','id="cabang_5" class="form-control"');?>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group">
			<label for="sex" class="col-sm-4 control-label">Status</label>
			<div class="col-sm-4">
				<?php $data = array('1'=>'Aktif', '0'=>'Tidak Aktif');
					echo form_dropdown('status_5',$data,'','id="status_5" class="form-control"');
				?>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label  class="col-sm-4 control-label">Keterangan</label>
			<div class="col-sm-6"><?=form_textarea(array('name'=>'ket_5','id'=>'ket_5','class'=>'form-control'));?>	</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12"><!-- <input type="hidden" name="id" id="id"> -->
	<input type="hidden" name="state_5" id="state_5" value="add">
	<input type="hidden" name="kelompok_5" id="kelompok_5" value="add">
	<input type="button" class="btn btn-primary" id="btsimpanLvl_5" value="Simpan">
	<button type="button" class="btn btn-default" id="btcancelLvl_5">Batal</button>
	</div>
	</div> <hr/>
</div><!-- end modal -->

<?php echo form_close();?>  
<br>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="dataTables_5">
		<thead>
			<tr>
				<th>ID Akun</th>
				<th>Nama</th>				
				<th>Kelompok</th>
				<th>Level</th>
				<th>ID Parent</th>
				<th>Cabang</th>
				<th>Saldo Awal</th>
				<th>Status</th>
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
        $('#dataTables_5').dataTable({
			"bProcessing": true,
			"bServerSide": true,
			"iDisplayLength": 25,
			//"autoWidth": true,
			"bJQueryUI": true,
			"sPaginationType": "full_numbers",
			"aoColumns": [
				{"mData": "IDACC" },
				{"mData": "NAMA" },
				{"mData": "KELOMPOK" },
				{"mData": "LEVEL" },
				{"mData": "IDPARENT" },
				{"mData": "CABANG" , "sortable":false},
				{"mData": "SALDO" , "sortable":false},
				{"mData": "STATUS" },
				{"mData": "ACTION", "sortable":false }
			],
			"sAjaxSource": "<?php echo base_url('rekeningakun/json_data_level5');?>"
		});

		

    });
	$("#saldoawal_5").blur(function(){
		blurObj(this);
	});
	$('#level2_5').change(function(){
		var idParentLvl2=$(this).val();
		if ($('#state_5').val()=='add'){			
			$('#idacc_5').val(idParentLvl2.substr(0,3));
		}

	$.ajax({
			url: "<?php echo base_url('rekeningakun/comboLevel3'); ?>",
			dataType: 'json',
			type: 'POST',
			data: {level2:$(this).val()},
			success: function(respon){
				$('#level3_5').find('option').remove().end();
				if (respon.status==1){
					var item = respon.data;
					for (opt=0;opt<item.length;opt++){
						$('#level3_5').append('<option value="'+item[opt].idacc+'" >'+item[opt].nama+'</option>');
					}
					$('#level3_5').trigger('change');
					
					}
				}
			});

		//});
	}).trigger('change');
	
	$('#level3_5').change(function(){
		var idParentLvl3=$(this).val();
		if ($('#state_5').val()=='add'){			
			$('#idacc_5').val(idParentLvl3.substr(0,4));
		}
    $.ajax({
			url: "<?php echo base_url('rekeningakun/comboLevel4'); ?>",
			dataType: 'json',
			type: 'POST',
			data: {level3:$(this).val()},
			success: function(respon){
				$('#level4_5').find('option').remove().end();
				if (respon.status==1){
					var item = respon.data;
					for (opt=0;opt<item.length;opt++){
						$('#level4_5').append('<option value="'+item[opt].idacc+'" >'+item[opt].nama+'</option>');
					}
					$('#level4_5').trigger('change');
					
					}
				}
			});

		//});
	}).trigger('change');

	$('#level4_5').change(function(){
		var idParentLvl4=$(this).val();
		if ($('#state_5').val()=='add'){			
			//$('#idacc_5').val(idParentLvl4.substr(0,5));
			$('#idacc_5').val(idParentLvl4+'.');
		}
    });
	
	$('#level1_5').change(function(){
		var idParent=$(this).val();
		if ($('#state_5').val()=='add'){			
			$('#idacc_5').val(idParent.substr(0,2));
		}
		//alert(idParent.substr(0,1));
		switch(idParent.substr(0,1)){
			case '1': $('#kelompok_5').val('A'); break;
			case '2': $('#kelompok_5').val('K'); break;
			case '3': $('#kelompok_5').val('M'); break;
			case '4': $('#kelompok_5').val('P'); break;
			case '5': $('#kelompok_5').val('B'); break;	
			default : $('#kelompok_5').val('L'); break;
		}

		$.ajax({
		url: "<?php echo base_url('rekeningakun/comboLevel2'); ?>",
		dataType: 'json',
		type: 'POST',
		data: {level1:$(this).val()},
		success: function(respon){
			$('#level2_5').find('option').remove().end();
			if (respon.status==1){
				var item = respon.data;
				for (opt=0;opt<item.length;opt++){
					$('#level2_5').append('<option value="'+item[opt].idacc+'" >'+item[opt].nama+'</option>');
				}
				$('#level2_5').trigger('change');
				
				}
			}
		});

	//});
	}).trigger('change');

	$('#btcreate5').click(function(){
		$("#divformLvl_5").fadeSlide("show");
		$('#state_5').val('add');
		$('#lbltitle_5').text('Tambah Akun Level 5');		
		$('#idacc_5').removeAttr('readonly');
		$('#myformLevel5').reset();
		
		
	});

	$('#btcancelLvl_5').click(function(){
		$("#divformLvl_5").fadeSlide("hide");
	});
	
	$('#btsimpanLvl_5').click(function(){		
		var form_data = $('#myformLevel5').serialize();
		$().showMessage('Sedang diproses.. Harap tunggu..');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('rekeningakun/savelevel5');?>',
			data: form_data,				
			dataType: 'json',
			success: function(msg) {
				 $("#errorHandler5").html('&nbsp;').hide();
				 console.log(msg);
				if(msg.status =='success'){
					$().showMessage('Data Akun Perkiraan berhasil disimpan.','success',1000);
					$("#divformLvl_5").fadeSlide("hide");
					$('#dataTables_5').dataTable().fnReloadAjax();
					
				} else {
					$().showMessage('Terjadi kesalahan. Data gagal disimpan.','danger',700);
					//bootbox.alert("Terjadi kesalahan. Data gagal disimpan.");
					$("#errorHandler5").html(msg.errormsg).show();
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

	function delLevel5(idx, str){
			var pilih=confirm('Hapus Akun Level 5 = ['+idx+'] "'+str+ '" dilanjutkan ?');
			if (pilih==true) {
					$.ajax({
					type	: "POST",
					url		: "<?php echo base_url('rekeningakun/delLevel5');?>",
					data	: "idx="+idx,
					timeout	: 3000,  
					success	: function(res){
						$().showMessage('data berhasil dihapus.','success',1000);
						
						$("#divformLvl_5").fadeSlide("hide");
						$('#dataTables_5').dataTable().fnReloadAjax();
					}
					
				});
			}
		}
	function editAkunlevel5(obj){
		$('#myformLevel5').reset();
		$('#level1_5').removeProp('selected');
		$('#level2_5').removeProp('selected');
		$('#level3_5').removeProp('selected');
		$('#level4_5').removeProp('selected');
		var id = $(obj).attr('data-id');
		var myVar;
		$('#myformLevel5 input[name="state_5"]').val(id);		
		$('#lbltitle_5').text('Edit Akun Level 5');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('rekeningakun/getAccDetail');?>',
			data: {
				id:id,
				level:5
			},
			dataType: 'json',
			success: function(msg) {
					$('#idacc_5').attr('readonly', true);
				if(msg.status =='success'){
					console.log(msg.data);					
					$('#idacc_5').val(msg.data.idacc);					
					$('#level1_5 option[value="' + msg.data.level1 + '"]').prop('selected', true);						
					$('#level1_5').trigger('change');
					alert("Processing...");
					$('#level2_5 option[value="' + msg.data.level2 + '"]').prop('selected', true);						
					$('#level2_5').trigger('change');
					//alert("Processing...");
					//alert("Processing level2="+msg.data.level2);
					$('#level3_5 option[value="' + msg.data.level3 + '"]').prop('selected', true);						
					$('#level3_5').change();
					//alert("Processing level3="+msg.data.level3);
					$('#level4_5 option[value="' + msg.data.idparent + '"]').prop('selected', true);								
					//alert("Processing level4="+msg.data.idparent );
					$('#nama_5').val(msg.data.nama);					
					$('#kelompok_5').val(msg.data.kelompok);					
					$('#saldoawal_5').val(msg.data.initval);	
					$('#cabang_5').val(msg.data.id_cab);
					$('#ket_5').val(msg.data.keterangan);					
					$('#status_5').val(msg.data.status);
					$("#divformLvl_5").fadeSlide("show");
						
					
				}
			},
			
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				$().showMessage('Terjadi kesalahan.<br />'+	textStatus + ' - ' + errorThrown ,'danger',2000);
			},
			cache: false
		});
			
	}
    </script>