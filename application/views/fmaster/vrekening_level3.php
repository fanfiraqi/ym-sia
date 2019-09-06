<br><div class="control-group"><a href="javascript:void(0)" id="btcreate3" class="btn btn-primary">Tambah Level 3</a>
</div>
<div id="errorHandler3" class="alert alert-danger no-display"></div>
<?php 
$level1=$this->common_model->comboGetLevel1(' [ Pilih Akun Induk/Level 1 ] ');
$level2=$this->common_model->comboGetLevel2(' [ Pilih Akun Level 2 ] ');
echo form_open_multipart(null,array('class'=>'form-horizontal','id'=>'myformLevel3'));
?>
<div id="divformLvl_3" class="no-display">
<div class="control-group" >
	<h4><u><label id="lbltitle_3">Tambah Akun Level 3</label></u></h4>
</div>

<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">Akun Header/Level 1 </label>
			<div class="col-sm-8"><?=form_dropdown('level1_3',$level1,'','id="level1_3" class="form-control"');?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">Akun Induk/Level 2 </label>
			<div class="col-sm-8"><?=form_dropdown('level2_3',$level2,'','id="level2_3" class="form-control"');?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label  class="col-sm-4 control-label">ID Akun Level 3</label>
			<div class="col-sm-4"><?=form_input(array('name'=>'idacc_3','id'=>'idacc_3','class'=>'form-control'));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label  class="col-sm-4 control-label">Nama Akun Level 3</label>
			<div class="col-sm-8"><?=form_input(array('name'=>'nama_3','id'=>'nama_3','class'=>'form-control'));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label  class="col-sm-4 control-label">Saldo Awal</label>
			<div class="col-sm-6"><?=form_input(array('name'=>'saldoawal_3','id'=>'saldoawal_3','class'=>'form-control','onkeypress'=>"return numericVal(this,event)",  "onclick"=>"clickObj(this)", 'value'=>0));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group">
			<label for="sex" class="col-sm-4 control-label">Pemilik Akun Wilayah/Cabang</label>
			<div class="col-sm-4">
				<?php echo form_dropdown('cabang_3',$cabang,'','id="cabang_3" class="form-control"');?>
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
					echo form_dropdown('status_3',$data,'','id="status_3" class="form-control"');
				?>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label  class="col-sm-4 control-label">Keterangan</label>
			<div class="col-sm-6"><?=form_textarea(array('name'=>'ket_3','id'=>'ket_3','class'=>'form-control'));?>	</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
	<input type="hidden" name="state_3" id="state_3" value="add">
	<input type="hidden" name="kelompok_3" id="kelompok_3" value="add">
	<input type="button" class="btn btn-primary" id="btsimpanLvl_3" value="Simpan">
	<button type="button" class="btn btn-default" id="btcancelLvl_3">Batal</button>
	</div>
	</div> <hr/>
</div><!-- end modal -->

<?php echo form_close();?>  
<br>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="dataTables_3">
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
        $('#dataTables_3').dataTable({
			"bProcessing": true,
			"bServerSide": true,
			"iDisplayLength": 25,
			"autoWidth": true,
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
			"sAjaxSource": "<?php echo base_url('rekeningakun/json_data_level3');?>"
		});
   });

	$("#saldoawal_3").blur(function(){
		blurObj(this);
	});
	$('#level2_3').change(function(){
		var idParentLvl2=$(this).val();
		if ($('#state_3').val()=='add'){			
			$('#idacc_3').val(idParentLvl2.substr(0,3)+'.');
		}
    });
	
	$('#level1_3').change(function(){
		var idParent=$(this).val();
		if ($('#state_3').val()=='add'){			
			$('#idacc_3').val(idParent.substr(0,2));
		}
		$("#cabang_3").attr('disabled','disabled');		
		switch(idParent.substr(0,1)){
			case '1': $('#kelompok_3').val('A'); break;
			case '2': $('#kelompok_3').val('K'); break;
			case '3': $('#kelompok_3').val('M'); break;
			case '4': $('#kelompok_3').val('P'); $("#cabang_3").removeAttr('disabled'); break;				
			case '5': $('#kelompok_3').val('B'); break;		
			default : $('#kelompok_3').val('L'); break;
		}

		$.ajax({
		url: "<?php echo base_url('rekeningakun/comboLevel2'); ?>",
		dataType: 'json',
		type: 'POST',
		data: {level1:$(this).val()},
		success: function(respon){
			$('#level2_3').find('option').remove().end();
			if (respon.status==1){
				var item = respon.data;
				for (opt=0;opt<item.length;opt++){
					$('#level2_3').append('<option value="'+item[opt].idacc+'" >'+item[opt].nama+'</option>');
				}
				$('#level2_3').trigger('change');
				
			}
		}
	});

	//});
	}).trigger('change');

	$('#btcreate3').click(function(){
		$("#divformLvl_3").fadeSlide("show");
		$('#state_3').val('add');
		$('#lbltitle_3').text('Tambah Akun Level 3');		
		$('#idacc_3').removeAttr('readonly');
		$('#myformLevel3').reset();
		
		
	});

	$('#btcancelLvl_3').click(function(){
		$("#divformLvl_3").fadeSlide("hide");
	});
	
	$('#btsimpanLvl_3').click(function(){		
		var form_data = $('#myformLevel3').serialize();
		$().showMessage('Sedang diproses.. Harap tunggu..');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('rekeningakun/savelevel3');?>',
			data: form_data,				
			dataType: 'json',
			success: function(msg) {
				 $("#errorHandler3").html('&nbsp;').hide();
				 console.log(msg);
				if(msg.status =='success'){
					$().showMessage('Data Akun Perkiraan berhasil disimpan.','success',1000);
					$("#divformLvl_3").fadeSlide("hide");
					$('#dataTables_3').dataTable().fnReloadAjax();
					
				} else {
					$().showMessage('Terjadi kesalahan. Data gagal disimpan.','danger',700);
					//bootbox.alert("Terjadi kesalahan. Data gagal disimpan.");
					$("#errorHandler3").html(msg.errormsg).show();
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

	function delLevel3(idx, str){
			var pilih=confirm('Hapus Akun Level 3 = ['+idx+'] "'+str+ '" dilanjutkan ?');
			if (pilih==true) {
					$.ajax({
					type	: "POST",
					url		: "<?php echo base_url('rekeningakun/delLevel3');?>",
					data	: "idx="+idx,
					timeout	: 3000,  
					success	: function(res){
						$().showMessage('data berhasil dihapus.','success',1000);
						
						$("#divformLvl_3").fadeSlide("hide");
						$('#dataTables_3').dataTable().fnReloadAjax();
					}
					
				});
			}
		}
	function editAkunLevel3(obj){
		$('#myformLevel3').reset();
		$('#level1_3').removeProp('selected');
		$('#level2_3').removeProp('selected');
		var id = $(obj).attr('data-id');
		var myVar;
		$('#myformLevel3 input[name="state_3"]').val(id);		
		$('#lbltitle_3').text('Edit Akun Level 3');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('rekeningakun/getAccDetail');?>',
			data: {
				id:id,
				level:3
			},
			dataType: 'json',
			success: function(msg) {
					$('#idacc_3').attr('readonly', true);
				if(msg.status =='success'){
					console.log(msg.data);					
					$('#idacc_3').val(msg.data.idacc);					
					$('#level1_3 option[value="' + msg.data.level1 + '"]').prop('selected', true);						
					$('#level1_3').change();					
					alert("Processing Finish");
					$('#level2_3 option[value="' + msg.data.idparent + '"]').prop('selected', true);								
					$('#nama_3').val(msg.data.nama);					
					$('#kelompok_3').val(msg.data.kelompok);					
					$('#saldoawal_3').val(msg.data.initval);	
					$('#cabang_3').val(msg.data.id_cab);
					$('#ket_3').val(msg.data.keterangan);					
					$('#status_3').val(msg.data.status);
					$("#divformLvl_3").fadeSlide("show");
						
					
				}
			},
			
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				$().showMessage('Terjadi kesalahan.<br />'+	textStatus + ' - ' + errorThrown ,'danger',2000);
			},
			cache: false
		});
			
	}
    </script>