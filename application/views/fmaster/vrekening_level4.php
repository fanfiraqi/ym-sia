<br><div class="control-group"><a href="javascript:void(0)" id="btcreate4" class="btn btn-primary">Tambah Level 4</a>
</div>
<div id="errorHandler4" class="alert alert-danger no-display"></div>
<?php 
$level1=$this->common_model->comboGetLevel1(' [ Pilih Akun Induk/Level 1 ] ');
$level2=$this->common_model->comboGetLevel2(' [ Pilih Akun Level 2 ] ');
$level3=$this->common_model->comboGetLevel3(' [ Pilih Akun Level 3 ] ');
echo form_open_multipart(null,array('class'=>'form-horizontal','id'=>'myformLevel4'));
?>
<div id="divformLvl_4" class="no-display">
<div class="control-group" >
	<h4><u><label id="lbltitle_4">Tambah Akun Level 4</label></u></h4>
</div>

<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">Akun Header/Level 1 </label>
			<div class="col-sm-8"><?=form_dropdown('level1_4',$level1,'','id="level1_4" class="form-control"');?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">Akun Header/Level 2 </label>
			<div class="col-sm-8"><?=form_dropdown('level2_4',$level2,'','id="level2_4" class="form-control"');?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">Akun Induk/Level 3 </label>
			<div class="col-sm-8"><?=form_dropdown('level3_4',$level3,'','id="level3_4" class="form-control"');?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label  class="col-sm-4 control-label">ID Akun Level 4</label>
			<div class="col-sm-4"><?=form_input(array('name'=>'idacc_4','id'=>'idacc_4','class'=>'form-control'));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label  class="col-sm-4 control-label">Nama Akun Level 4</label>
			<div class="col-sm-8"><?=form_input(array('name'=>'nama_4','id'=>'nama_4','class'=>'form-control'));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label  class="col-sm-4 control-label">Saldo Awal</label>
			<div class="col-sm-6"><?=form_input(array('name'=>'saldoawal_4','id'=>'saldoawal_4','class'=>'form-control','onkeypress'=>"return numericVal(this,event)",  "onclick"=>"clickObj(this)", 'value'=>0));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group">
			<label for="sex" class="col-sm-4 control-label">Pemilik Akun Wilayah/Cabang</label>
			<div class="col-sm-4">
				<?php echo form_dropdown('cabang_4',$cabang,'','id="cabang_4" class="form-control"');?>
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
					echo form_dropdown('status_4',$data,'','id="status_4" class="form-control"');
				?>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label  class="col-sm-4 control-label">Keterangan</label>
			<div class="col-sm-6"><?=form_textarea(array('name'=>'ket_4','id'=>'ket_4','class'=>'form-control'));?>	</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12"><!-- <input type="hidden" name="id" id="id"> -->
	<input type="hidden" name="state_4" id="state_4" value="add">
	<input type="hidden" name="kelompok_4" id="kelompok_4" value="add">
	<input type="button" class="btn btn-primary" id="btsimpanLvl_4" value="Simpan">
	<button type="button" class="btn btn-default" id="btcancelLvl_4">Batal</button>
	</div>
	</div> <hr/>
</div><!-- end modal -->

<?php echo form_close();?>  
<br>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="dataTables_4">
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
        $('#dataTables_4').dataTable({
			"bProcessing": true,
			"bServerSide": true,
			"iDisplayLength": 25,			
			"bJQueryUI": true,
			"sPaginationType": "full_numbers",
			//"autoWidth": true,
			"aoColumns": [
				{"mData": "IDACC" },
				{"mData": "NAMA" },
				{"mData": "KELOMPOK" },
				{"mData": "LEVEL" },
				{"mData": "IDPARENT" },
				{"mData": "CABANG" , "sortable":false},
				{"mData": "SALDO", "sortable":false },
				{"mData": "STATUS" },
				{"mData": "ACTION", "sortable":false }
			],
			"sAjaxSource": "<?php echo base_url('rekeningakun/json_data_level4');?>"
		});

		

    });
	$("#saldoawal_4").blur(function(){
		blurObj(this);
	});

	$('#level2_4').change(function(){
		var idParentLvl2=$(this).val();
		if ($('#state_4').val()=='add'){			
			$('#idacc_4').val(idParentLvl2.substr(0,3)+'.');
		}

	$.ajax({
			url: "<?php echo base_url('rekeningakun/comboLevel3'); ?>",
			dataType: 'json',
			type: 'POST',
			data: {level2:$(this).val()},
			success: function(respon){
				$('#level3_4').find('option').remove().end();
				if (respon.status==1){
					var item = respon.data;
					for (opt=0;opt<item.length;opt++){
						$('#level3_4').append('<option value="'+item[opt].idacc+'" >'+item[opt].nama+'</option>');
					}
					$('#level3_4').trigger('change');
					
					}
				}
			});

		//});
	}).trigger('change');
	
	$('#level3_4').change(function(){
		var idParentLvl3=$(this).val();
		if ($('#state_4').val()=='add'){			
			$('#idacc_4').val(idParentLvl3.substr(0,6)+'.');
		}
    });
	
	$('#level1_4').change(function(){
		var idParent=$(this).val();
		if ($('#state_4').val()=='add'){			
			$('#idacc_4').val(idParent.substr(0,2));
		}
		
		switch(idParent.substr(0,1)){
			case '1': $('#kelompok_4').val('A'); break;
			case '2': $('#kelompok_4').val('K'); break;
			case '3': $('#kelompok_4').val('M'); break;
			case '4': $('#kelompok_4').val('P'); break;
			case '5': $('#kelompok_4').val('B'); break;	
			default : $('#kelompok_4').val('L'); break;
		}

		$.ajax({
		url: "<?php echo base_url('rekeningakun/comboLevel2'); ?>",
		dataType: 'json',
		type: 'POST',
		data: {level1:$(this).val()},
		success: function(respon){
			$('#level2_4').find('option').remove().end();
			if (respon.status==1){
				var item = respon.data;
				for (opt=0;opt<item.length;opt++){
					$('#level2_4').append('<option value="'+item[opt].idacc+'" >'+item[opt].nama+'</option>');
				}
				$('#level2_4').trigger('change');
				
				}
			}
		});

	//});
	}).trigger('change');

	$('#btcreate4').click(function(){
		$("#divformLvl_4").fadeSlide("show");
		$('#state_4').val('add');
		$('#lbltitle_4').text('Tambah Akun Level 4');		
		$('#idacc_4').removeAttr('readonly');
		$('#myformLevel4').reset();
		
		
	});

	$('#btcancelLvl_4').click(function(){
		$("#divformLvl_4").fadeSlide("hide");
	});
	
	$('#btsimpanLvl_4').click(function(){		
		var form_data = $('#myformLevel4').serialize();
		$().showMessage('Sedang diproses.. Harap tunggu..');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('rekeningakun/savelevel4');?>',
			data: form_data,				
			dataType: 'json',
			success: function(msg) {
				 $("#errorHandler4").html('&nbsp;').hide();
				 console.log(msg);
				if(msg.status =='success'){
					$().showMessage('Data Akun Perkiraan berhasil disimpan.','success',1000);
					$("#divformLvl_4").fadeSlide("hide");
					$('#dataTables_4').dataTable().fnReloadAjax();
					
				} else {
					$().showMessage('Terjadi kesalahan. Data gagal disimpan.','danger',700);
					//bootbox.alert("Terjadi kesalahan. Data gagal disimpan.");
					$("#errorHandler4").html(msg.errormsg).show();
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

	function dellevel4(idx, str){
			var pilih=confirm('Hapus Akun Level 4 = ['+idx+'] "'+str+ '" dilanjutkan ?');
			if (pilih==true) {
					$.ajax({
					type	: "POST",
					url		: "<?php echo base_url('rekeningakun/delLevel4');?>",
					data	: "idx="+idx,
					timeout	: 3000,  
					success	: function(res){
						$().showMessage('data berhasil dihapus.','success',1000);
						
						$("#divformLvl_4").fadeSlide("hide");
						$('#dataTables_4').dataTable().fnReloadAjax();
					}
					
				});
			}
		}
	function editAkunlevel4(obj){
		$('#myformLevel4').reset();
		$('#level1_4').removeProp('selected');
		$('#level2_4').removeProp('selected');
		$('#level3_4').removeProp('selected');
		var id = $(obj).attr('data-id');
		var myVar;
		$('#myformLevel4 input[name="state_4"]').val(id);		
		$('#lbltitle_4').text('Edit Akun Level 4');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('rekeningakun/getAccDetail');?>',
			data: {
				id:id,
				level:4
			},
			dataType: 'json',
			success: function(msg) {
					$('#idacc_4').attr('readonly', true);
				if(msg.status =='success'){
					console.log(msg.data);					
					$('#idacc_4').val(msg.data.idacc);					
					$('#level1_4 option[value="' + msg.data.level1 + '"]').prop('selected', true);						
					$('#level1_4').change();
					alert("Processing...");
					$('#level2_4 option[value="' + msg.data.level2 + '"]').prop('selected', true);						
					$('#level2_4').change();
					alert("Processing Finish");
					$('#level3_4 option[value="' + msg.data.idparent + '"]').prop('selected', true);								
					$('#nama_4').val(msg.data.nama);					
					$('#kelompok_4').val(msg.data.kelompok);					
					$('#saldoawal_4').val(msg.data.initval);	
					$('#cabang_4').val(msg.data.id_cab);
					$('#ket_4').val(msg.data.keterangan);					
					$('#status_4').val(msg.data.status);
					$("#divformLvl_4").fadeSlide("show");
						
					
				}
			},
			
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				$().showMessage('Terjadi kesalahan.<br />'+	textStatus + ' - ' + errorThrown ,'danger',2000);
			},
			cache: false
		});
			
	}
    </script>