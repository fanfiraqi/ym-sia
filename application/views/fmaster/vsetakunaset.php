<div class="control-group"><a href="javascript:void(0)" id="btcreate" class="btn btn-primary">Tambah Data</a></div>
<div id="errorHandler" class="alert alert-danger no-display"></div>
<br>
<div id="divformkel" class="no-display">
<?php echo form_open(null,array('class'=>'form-horizontal','id'=>'myform'));?>

<div class="row">
	<div class="col-md-8">
		<div class="form-group">
			<label for="sex" class="col-sm-4 control-label">Pilih Cabang</label>
			<div class="col-sm-4">
				<?php echo form_dropdown('cabang',$cabang,'','id="cabang" class="form-control"');?>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">Kelompok Aset</label>
			<div class="col-sm-8"><?=form_dropdown('kelompok',$kelompok,'','id="kelompok" class="form-control"');?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">Group Aset</label>
			<div class="col-sm-8"><?=form_dropdown('grup',$grup,'','id="grup" class="form-control"');?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">COA Aset</label>
			<div class="col-sm-8"><?=form_dropdown('coa_aset',$aset,'','id="coa_aset" class="form-control"');?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label  class="col-sm-4 control-label">COA Akumulasi</label>
			<div class="col-sm-8"><?=form_dropdown('coa_akum',$akum,'','id="coa_akum" class="form-control"');?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label  class="col-sm-4 control-label">COA Beban Penyusutan</label>
			<div class="col-sm-8"><?=form_dropdown('coa_susut',$susut,'','id="coa_susut" class="form-control"');?></div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label  class="col-sm-4 control-label">Persen Penyusutan</label>
			<div class="col-sm-8"><?=form_input(array('name'=>'persen','id'=>'persen','class'=>'form-control'));?>	</div>
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
		<input type="hidden" name="id" id="id">
		<input type="hidden" name="state" id="state" value="add">
		<input type="button" class="btn btn-primary" id="btsimpan" value="Simpan">
		<button type="button" class="btn btn-default" id="btcancel">Batal</button>
	</div>
</div>

<?php echo form_close();?>
</div> <!-- divformkel -->
<hr/>
<div class="row">
	<div class="col-sm-6">
		<div class="form-group">
			<label for="cabang" class="col-sm-4 control-label">Cabang</label>
			<div class="col-sm-8">
				<?php
				if ($this->session->userdata('auth')->id_cabang=="1"){
					echo form_dropdown('cabangfilter',$cabang,'','id="cabangfilter" class="form-control" ');
				}else{
					echo '<input type="hidden" name="cabangfilter" id ="cabangfilter" value="'.$cabang->id_cabang.'"/>';
					echo '<label  class="col-sm-4 control-label">'.$cabang->kota.'</label>';
				}
				?>
			</div>
		</div>
	</div>	
</div>
<hr/>

<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="dataTables-list">
		<thead>
			<tr>
				<th>ID</th>
				<th>KELOMPOK</th>
				<th>GROUP</th>
				<th>COA ASET</th>
				<th>COA AKUMULASI</th>
				<th>COA SUSUT</th>
				<th>% SUSUT</th>
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
        $('#dataTables-list').dataTable({
			"bProcessing": true,
			"bServerSide": true,
			"iDisplayLength": 25,
			"bJQueryUI": true,
			"sPaginationType": "full_numbers",
			"fnServerParams": function ( aoData ) {
				aoData.push({ "name": "cabang", "value": $('#cabangfilter').val()} 
				);
			},
			"aoColumns": [
				{"mData": "ID" },
				{"mData": "KELOMPOK"},
				{"mData": "GRUP"},
				{"mData": "ID_ASET"},				
				{"mData": "ID_AKUM" },
				{"mData": "ID_SUSUT" },
				{"mData": "PERSEN_SUSUT" },
				{"mData": "ACTION", "sortable":false }
			],
			"sAjaxSource": "<?php echo base_url('setakun/json_data_aset');?>"
		});
    });

	$('#cabang').change(function(){

	$.ajax({
			url: "<?php echo base_url('setakun/getAkunAset'); ?>",
			dataType: 'json',
			type: 'POST',
			data: {cabang:$(this).val(), kelompok: $('#kelompok').val()},
			success: function(respon){
				$('#coa_aset').find('option').remove().end();
				$('#coa_akum').find('option').remove().end();
				$('#coa_susut').find('option').remove().end();
				if (respon.status==1){
					var item = respon.dataAset;
					var item2 = respon.dataAkum;
					var item3 = respon.dataSusut;
					for (opt=0;opt<item.length;opt++){
						$('#coa_aset').append('<option value="'+item[opt].idacc+'" >'+ item[opt].idacc +' - ' + item[opt].nama+'</option>');
					}
					$('#coa_aset').trigger('change');

					for (opt=0;opt<item2.length;opt++){
						$('#coa_akum').append('<option value="'+item2[opt].idacc+'" >'+ item2[opt].idacc +' - ' +item2[opt].nama+'</option>');
					}
					$('#coa_akum').trigger('change');

					for (opt=0;opt<item3.length;opt++){
						$('#coa_susut').append('<option value="'+item3[opt].idacc+'" >'+ item3[opt].idacc +' - ' +item3[opt].nama+'</option>');
					}
					$('#coa_susut').trigger('change');
					
					}
				}
			});

		//});
	}).trigger('change');

	$('#kelompok').change(function(){

	$.ajax({
			url: "<?php echo base_url('setakun/getAkunAset'); ?>",
			dataType: 'json',
			type: 'POST',
			data: {kelompok:$(this).val(), cabang: $('#cabang').val()},
			success: function(respon){
				$('#coa_aset').find('option').remove().end();
				$('#coa_akum').find('option').remove().end();
				$('#coa_susut').find('option').remove().end();
				if (respon.status==1){
					var item = respon.dataAset;
					var item2 = respon.dataAkum;
					var item3 = respon.dataSusut;
					for (opt=0;opt<item.length;opt++){
						$('#coa_aset').append('<option value="'+item[opt].idacc+'" >'+ item[opt].idacc +' - ' + item[opt].nama+'</option>');
					}
					$('#coa_aset').trigger('change');

					for (opt=0;opt<item2.length;opt++){
						$('#coa_akum').append('<option value="'+item2[opt].idacc+'" >'+ item2[opt].idacc +' - ' +item2[opt].nama+'</option>');
					}
					$('#coa_akum').trigger('change');

					for (opt=0;opt<item3.length;opt++){
						$('#coa_susut').append('<option value="'+item3[opt].idacc+'" >'+ item3[opt].idacc +' - ' +item3[opt].nama+'</option>');
					}
					$('#coa_susut').trigger('change');
					
					}
				}
			});

		//});
	}).trigger('change');

	$('#cabangfilter').change(function(){
		$('#dataTables-list').dataTable().fnReloadAjax();

	});

	$('#btcreate').click(function(){
		$("#divformkel").fadeSlide("show");
		$('#state').val('add');
		$('#myform').reset();
		
		
	});
	$('#btcancel').click(function(){
		$("#divformkel").fadeSlide("hide");
	});



	function delThis(idx,  str){	//nik, nama
			var pilih=confirm('Apakah data   '+str+ ' akan dihapus ?');
			if (pilih==true) {
					$.ajax({
					type	: "POST",
					url		: "<?php echo base_url('ak_set_aset/delThis');?>",
					data	: "idx="+idx+"&proses=ak_set_aset"+"&field=id",
					timeout	: 3000,  
					success	: function(res){
						//alert(res);
						alert("data berhasil dihapus");
						window.location.reload();
						}
				});
			}
		}

	function editThis(obj){
		var id = $(obj).attr('data-id');	//id as nik
		$('#myform input[name="state"]').val(id);		
		//$('#lbltitle').text('Edit Data');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('setakun/editThis');?>',
			data: "id="+id+"&tabel=ak_set_aset"+"&field=id",
			dataType: 'json',
			success: function(msg) {
				
				if(msg.status =='success'){
					console.log(msg.data);
					
					$('#kelompok').val(msg.data.kelompok);
					$('#grup').val(msg.data.grup);
					$('#cabang').val(msg.data.id_cab);
					 $('#kelompok').trigger('change');
					setInterval(function(){ 
						$('#coa_aset').val(msg.data.id_aset);
						$('#coa_akum').val(msg.data.id_akum);
						$('#coa_susut').val(msg.data.id_susut);
					}, 3000);
					//alert(msg.data.id_aset);
					//$('#coa_aset').val(msg.data.id_aset);
					//$('#coa_akum').val(msg.data.id_akum);
					//$('#coa_susut').val(msg.data.id_susut);
					$('#persen').val(msg.data.persen_susut);
					$('#status').val(msg.data.active);
					$("#divformkel").fadeSlide("show");
					
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				$().showMessage('Terjadi kesalahan.<br />'+	textStatus + ' - ' + errorThrown ,'danger',2000);
			},
			cache: false
		});
	}
	

	$('#btsimpan').click(function(){		
		var form_data = $('#myform').serialize();
		
		$.ajax({
			type: 'POST',
			url: "<?php echo base_url('setakun/saveSetAset');?>",
			data: form_data,				
			dataType: 'json',
			success: function(msg) {
				 $("#errorHandler").html('&nbsp;').hide();
				 console.log(msg);
				if(msg.status =='success'){
					$().showMessage('Data berhasil disimpan.','success',1000);
					$("#divformkel").fadeSlide("hide");
					$('#dataTables-list').dataTable().fnReloadAjax();
					window.location.reload();				
				} else{
					bootbox.alert("Terjadi kesalahan. "+ msg.errormsg+". Data gagal disimpan.");				
					$("#errorHandler").html(msg.errormsg).show();
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				bootbox.alert("Terjadi kesalahan. Data gagal disimpan."+	textStatus + " - " + errorThrown );
			},
			cache: false
		});
	});

    </script>