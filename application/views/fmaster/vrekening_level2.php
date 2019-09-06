<br><div class="control-group"><a href="javascript:void(0)" id="btcreate" class="btn btn-primary">Tambah Level 2</a></div>
<div id="errorHandler" class="alert alert-danger no-display"></div>
<?php 
$level1=$this->common_model->comboGetLevel1(' [ Pilih Akun Induk/Level 1 ] ');
echo form_open_multipart(null,array('class'=>'form-horizontal','id'=>'myformkel'));
?>
<div id="divformkel" class="no-display">
<div class="control-group" >
	<h4><u><label id="lbltitle">Tambah Akun Level 2</label></u></h4>
</div>

<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">Akun Induk/Level 1 </label>
			<div class="col-sm-8"><?=form_dropdown('level1',$level1,'','id="level1" class="form-control"');?></div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label  class="col-sm-4 control-label">ID Akun Level 2</label>
			<div class="col-sm-4"><?=form_input(array('name'=>'idacc','id'=>'idacc','class'=>'form-control'));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label  class="col-sm-4 control-label">Nama Akun Level 2</label>
			<div class="col-sm-8"><?=form_input(array('name'=>'nama','id'=>'nama','class'=>'form-control'));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label  class="col-sm-4 control-label">Saldo Awal</label>
			<div class="col-sm-6"><?=form_input(array('name'=>'saldoawal','id'=>'saldoawal','class'=>'form-control','onkeypress'=>"return numericVal(this,event)",  "onclick"=>"clickObj(this)", 'value'=>0));?></div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-8">
		<div class="form-group">
			<label for="sex" class="col-sm-4 control-label">Status</label>
			<div class="col-sm-4">
				<?php $data = array('1'=>'Aktif', '0'=>'Tidak Aktif');
					echo form_dropdown('status',$data,'','id="status" class="form-control"');
				?>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label  class="col-sm-4 control-label">Keterangan</label>
			<div class="col-sm-6"><?=form_textarea(array('name'=>'ket','id'=>'ket','class'=>'form-control'));?>	</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
	<input type="hidden" name="state" id="state" value="add">
	<input type="hidden" name="kelompok" id="kelompok" value="add">
	<input type="button" class="btn btn-primary" id="btsimpankel" value="Simpan">
	<button type="button" class="btn btn-default" id="btcancelkel">Batal</button>
	</div>
</div>
	<hr/>

</div><!-- divformkel -->

<?php echo form_close();?>  
<br>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="dataTables">
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
        $('#dataTables').dataTable({
			"bProcessing": true,
			"bServerSide": true,
			"iDisplayLength": 25,			
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
			"sAjaxSource": "<?php echo base_url('rekeningakun/json_data_level2');?>"
		});

    });
	$("#saldoawal").blur(function(){
		blurObj(this);		
	});
	$('#level1').change(function(){
		var idParent=$(this).val();
		if ($('#state').val()=='add'){			
			$('#idacc').val(idParent+'.');
		}
		
		switch(idParent.substr(0,1)){
			case '1': $('#kelompok').val('A'); break;
			case '2': $('#kelompok').val('K'); break;
			case '3': $('#kelompok').val('M'); break;
			case '4': $('#kelompok').val('P'); break;
			case '5': $('#kelompok').val('B'); break;			
			default : $('#kelompok').val('L'); break;
		}
	});

	$('#btcreate').click(function(){
		$("#divformkel").fadeSlide("show");
		$('#state').val('add');
		$('#lbltitle').text('Tambah Akun Level 2');
		$('#idacc').removeAttr('readonly');
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
			url: '<?php echo base_url('rekeningakun/saveLevel2');?>',
			data: form_data,				
			dataType: 'json',
			success: function(msg) {
				 $("#errorHandler").html('&nbsp;').hide();
				 console.log(msg);
				if(msg.status =='success'){
					$().showMessage('Data Akun Perkiraan berhasil disimpan.','success',1000);
					$("#divformkel").fadeSlide("hide");
					$('#dataTables').dataTable().fnReloadAjax();
					
				} else {
					$().showMessage('Terjadi kesalahan. Data gagal disimpan.','danger',700);
					$("#errorHandler").html(msg.errormsg).show();
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				bootbox.alert("Terjadi kesalahan. Data gagal disimpan."+	textStatus + " - " + errorThrown );
			},
			cache: false
		});
	});

	function delLevel2(idx, str){
			var pilih=confirm('Hapus Akun Level 2 = ['+idx+'] "'+str+ '" dilanjutkan ?');
			if (pilih==true) {
					$.ajax({
					type	: "POST",
					url		: "<?php echo base_url('rekeningakun/delLevel2');?>",
					data	: "idx="+idx,
					timeout	: 3000,  
					success	: function(res){
						alert("data berhasil dihapus");
						$("#divformkel").fadeSlide("hide");
						$('#dataTables').dataTable().fnReloadAjax();
						}
					
				});
			}
		}
	function editAkunLevel2(obj){
		var id = $(obj).attr('data-id');
		$('#myformkel input[name="state"]').val(id);		
		$('#lbltitle').text('Edit Akun Level 2');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('rekeningakun/getAccDetail');?>',
			data: {
				id:id,
				level:2
			},
			dataType: 'json',
			success: function(msg) {
					$('#idacc').attr('readonly', true);
				if(msg.status =='success'){
					console.log(msg.data);
					
					$('#idacc').val(msg.data.idacc);
					$('#level1 option[value="' + msg.data.idparent + '"]').prop('selected', true);
					$('#nama').val(msg.data.nama);					
					$('#kelompok').val(msg.data.kelompok);					
					$('#saldoawal').val(msg.data.initval);					
					$('#ket').val(msg.data.keterangan);					
					$('#status').val(msg.data.status);
					$("#divformkel").fadeSlide("show");
					
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				$().showMessage('Terjadi kesalahan.<br />'+	textStatus + ' - ' + errorThrown ,'danger',2000);
			},
			cache: false
		});
	}
    </script>