<div class="control-group"><a href="javascript:void(0)" id="btcreate" class="btn btn-primary">Tambah Asset</a></div><br>
<div id="errorHandler" class="alert alert-danger no-display"></div><br>

<div id="divformkel" class="no-display"><!-- divformkel -->
<?php echo form_open(null,array('class'=>'form-horizontal','id'=>'myform'));?>
<div class="row">
<div class="col-md-10">
 <div class="panel panel-default card-view">
 <div class="panel-heading">
									<div class="pull-left">
										<h6 class="panel-title txt-dark"><label id="lbltitle">Tambah Data Set Susut Fixed Asset Baru</label></h6>
									</div>
									<div class="clearfix"></div>
								</div>
 <div class="panel-body">



<div class="row">
	<div class="col-md-12">
		<div class="form-group">
			<label for="sex" class="col-sm-3 control-label">Cabang</label>
			<div class="col-sm-4">
				 
				<?php
				if ($this->session->userdata('auth')->id_cabang=="1"){
					echo form_dropdown('cabang',$cabang,'','id="cabang" class="form-control" ');
				}else{
					echo '<input type="hidden" name="cabang" id ="cabang" value="'.$cabang->id_cabang.'"/>';
					echo '<label  class="col-sm-4 control-label">'.$cabang->kota.'</label>';
				}
				?>
				
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label class="col-sm-3 control-label">Kelompok Aset</label>
			<div class="col-sm-8"><?=form_dropdown('kelompok',$kelompok,'','id="kelompok" class="form-control"');?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label class="col-sm-3 control-label">Group Aset</label>
			<div class="col-sm-8"><?=form_dropdown('grup',$grup,'','id="grup" class="form-control"');?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label  class="col-sm-3 control-label">Nama Aset</label>
			<div class="col-sm-8"><?=form_input(array('name'=>'nama_aset','id'=>'nama_aset','class'=>'form-control'));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group">
        <label class="col-sm-3 control-label">Tanggal Perolehan	</label>
        <div class=" col-sm-3 input-group">        
        <input type="text" name="tgl_perolehan" id="tgl_perolehan" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask placeholder="yyyy-mm-dd" value="<?=date('Y-m-d')?>"/>
		<div class="input-group-addon"><span class="fa fa-calendar" id="bttgl"></span></div>
        </div><!-- /.input group -->		
        </div><!-- /.form group -->
		
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label  class="col-sm-3 control-label">Nilai Perolehan </label>
			<div class="col-sm-3"><?=form_input(array('name'=>'nilai_perolehan','id'=>'nilai_perolehan','class'=>'form-control', 'value'=>0));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label  class="col-sm-3 control-label">Persen Susut @Tahun (%)</label>
			<div class="col-sm-3"><?=form_input(array('name'=>'persen_susut','id'=>'persen_susut','class'=>'form-control','onkeypress'=>"return numericVal(this,event)",'readonly'=>'true', 'value'=>0));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label  class="col-sm-3 control-label">Umur Ekonomis (Tahun)</label>
			<div class="col-sm-3"><?=form_input(array('name'=>'umur','id'=>'umur','class'=>'form-control','onkeypress'=>"return numericVal(this,event)", 'readonly'=>'true','value'=>0));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label  class="col-sm-3 control-label">Nilai Residu</label>
			<div class="col-sm-3"><?=form_input(array('name'=>'nilai_residu','id'=>'nilai_residu','class'=>'form-control', 'onkeypress'=>"return numericVal(this,event)", 'value'=>0));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label  class="col-sm-3 control-label">Nilai Penyusutan</label>
			<div class="col-sm-3"><?=form_input(array('name'=>'nilai_susut','id'=>'nilai_susut','class'=>'form-control','onkeypress'=>"return numericVal(this,event)", 'value'=>0, 'readonly'=>'true', 'background-color'=>'lightgrey'));?></div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label class="col-sm-3 control-label">Uraian / Catatan</label>
			<div class="col-sm-6"><?=form_textarea(array('name'=>'ket','id'=>'ket','class'=>'form-control'));?></div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label class="col-sm-3 control-label">Status</label>
			<div class="col-sm-3"><?=form_dropdown('status',array('1'=>'Aktif','0'=>'Tidak Aktif'),'','id="status" class="form-control"');?>	</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
	<input type="hidden" name="id" id="id">
	<input type="hidden" name="state" id="state" value="add">
	<input type="button" class="btn btn-primary" id="btsimpan" value="Simpan">
	<button type="button" class="btn btn-default" id="btcancel">Batal</button>
	</div>
</div>

		</div>
        </div>
</div>
</div>
<?php echo form_close();?>
<hr />
</div><!-- divformkel -->


<div class="row">
	<div class="col-md-6">
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
	</div>	<hr/>
<div class="row">
<div class="col-md-12 table-responsive">
	<table class="table table-striped table-bordered table-hover" id="dataTables">
		<thead>
			<tr>
				<th>ID</th>
				<th>KELOMPOK</th>				
				<th>GROUP</th>				
				<th>NAMA ASET</th>				
				<th>TGL PEROLEHAN</th>
				<th>NILAI PEROLEHAN</th>
				<th>% SUSUT</th>
				<th>UMUR (THN)</th>
				<th>STATUS</th>
				<th>ACTION</th>
			</tr>
		</thead>
		<tbody>
			
		</tbody>
	</table>
</div>
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
			"fnServerParams": function ( aoData ) {
					aoData.push( { "name": "cabang", "value": $('#cabangfilter').val() });
				},
			"aoColumns": [
				{"mData": "ID" },
				{"mData": "KELOMPOK" },
				{"mData": "GRUP" },
				{"mData": "NAMA_ASET" },
				{"mData": "TGL_PEROLEHAN" },
				{"mData": "NILAI_PEROLEHAN" },
				{"mData": "PERSEN_SUSUT_THN" },
				{"mData": "UMUR_EKONOMIS" },
				{"mData": "ISACTIVE" },
				{"mData": "ACTION", "sortable":false }
			],
			"sAjaxSource": "<?php echo base_url('aset/json_data');?>"
		});

		

    });	
	$('#cabangfilter').change(function(){
		$('#dataTables').dataTable().fnReloadAjax();

	});
	$('#nilai_perolehan').change(function(){
		var umur=($("#umur").val() <=0 ? 0:$("#umur").val());
		var nilai =parseFloat($(this).val()) / parseFloat(umur) ;
		$("#nilai_susut").val(Math.round(nilai));
	});
	$('#grup').change(function(){
		$('#kelompok').trigger('change');
	});
	$('#kelompok').change(function(){
		//get persen
		$.ajax({
			url: "<?php echo base_url('aset/getPersen'); ?>",
			dataType: 'json',
			type: 'POST',
			data: {kelompok:$(this).val(), cabang: $('#cabang').val(), grup: $('#grup').val()},
			success: function(respon){
				if (respon.status=='success'){
					$("#persen_susut").val(respon.persen);
					$("#umur").val(Math.round(100/parseInt(respon.persen)));
				}else{
					$("#persen_susut").val(0);
					$("#umur").val(0);
				}
				}
			});
	});
	$( "#tgl_perolehan" ).datepicker({
		dateFormat: 'yy-mm-dd',
		onSelect: function(date) {
            //alert(date)
        }
		});
	$("#bttgl").click(function() {
		$("#tgl_perolehan").datepicker("show");
	});

	$('#btcreate').click(function(){
		$("#divformkel").fadeSlide("show");
		$('#state').val('add');
		$('#lbltitle').text('Tambah Data Susut Fixed Asset Baru');
		//$('#pilihan').removeAttr('disabled');
		$('#myform').reset();
		
		
	});
	$('#btcancel').click(function(){
		$("#divformkel").fadeSlide("hide");
	});
	
	
	$('#btsimpan').click(function(){		
		var form_data = $('#myform').serialize();
		$().showMessage('Sedang diproses.. Harap tunggu..');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('aset/saveAsset');?>',
			data: form_data,				
			dataType: 'json',
			success: function(msg) {
				 $("#errorHandler").html('&nbsp;').hide();
				 console.log(msg);
				if(msg.status =='success'){
					$().showMessage('Data Susut berhasil disimpan.','success',1000);
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

function deltruefixedasset(idx, sts, str){
			var pilih=confirm('Apakah data Susut '+str+ ' akan dihapus ?');
			if (pilih==true) {
					$.ajax({
					type	: "POST",
					url		: "<?php echo base_url('aset/deltruefixedasset');?>",
					data	: "idx="+idx+"&status="+sts,
					timeout	: 3000,  
					success	: function(res){
						//alert(res);
						alert("data berhasil dihapus");
						window.location.reload();
						}
					
				});
			}

		}
	function delfixedasset(idx, sts, str){
			var pilih=confirm('Apakah data Susut '+str+ ' akan '+(sts=='1'?"dinon-aktifkan":"diaktifkan")+' ?');
			if (pilih==true) {
					$.ajax({
					type	: "POST",
					url		: "<?php echo base_url('aset/ubahStatus');?>",
					data	: "idx="+idx+"&status="+sts,
					timeout	: 3000,  
					success	: function(res){
						//alert(res);
						alert("data berhasil "+(sts=='1'?"dinon-aktifkan":"diaktifkan"));
						window.location.reload();
						}
					
				});
			}

		}
	
	
	function editfixedasset(obj){
		var id = $(obj).attr('data-id');
		$('#myform input[name="state"]').val(id);		
		
		$.ajax({
			type: 'POST',
			url:  "<?php echo base_url('aset/getAset'); ?>",
			data: {
				id:id
			},
			dataType: 'json',
			success: function(msg) {
				
				if(msg.status =='success'){
					console.log(msg.data);
					$('#lbltitle').text('Edit Data Susut Fixed Asset');		
					$('#cabang').val(msg.data.id_cab);
					$('#kelompok').val(msg.data.kelompok);	
					$('#grup').val(msg.data.grup);	
					$('#nama_aset').val(msg.data.nama_aset);	
					$('#tgl_perolehan').val(msg.data.tgl_perolehan);	
					$('#nilai_perolehan').val(msg.data.nilai_perolehan);
					
					$('#persen_susut').val(msg.data.persen_susut_thn);
					$('#umur').val(msg.data.umur_ekonomis);
					$('#nilai_residu').val(msg.data.nilai_residu);
					$('#nilai_susut').val(msg.data.nilai_susut_thn);					
					$('#ket').val(msg.data.keterangan);
					$('#status').val(msg.data.isactive);
					//$('#pilihan').attr('disabled',true);
					$("#divformkel").fadeSlide("show");
					
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				$().showMessage('Terjadi kesalahan.<br />'+	textStatus + ' - ' + errorThrown ,'danger',2000);
			},
			cache: false
		});
	}
	



$("#jual_textAsset").autocomplete({
	minLength: 2,
	source:
	function(req, add){
		$.ajax({
			url: "<?php echo base_url('aset/getFixedToSell'); ?>",
			dataType: 'json',
			type: 'POST',
			data: req,
			success:   
			function(data){
				if(data.response =="true"){
					add(data.message);
				}
			}
		});
	},
	select:
	function(event, ui) {		
		$("#jual_idakunAsset").val(ui.item.id);
		$("#id_susut").val(ui.item.id_susut);
		$("#jual_tgl_perolehan").val(ui.item.tgl_perolehan);
		$("#jual_nilai").val( (ui.item.nilai).toFixed(2) );	//total NP
		$("#jual_lama").val(ui.item.lama);	//total lama
		$("#jual_status").val(ui.item.status);
		//get nilai buku & sisa umur
		
		$.ajax({
			url: "<?php echo base_url('aset/getNilaiSisa'); ?>",
			dataType: 'json',
			type: 'POST',
			data: { idakum:ui.item.idakum },
			success:   
			function(data){
				console.log(data);
				if(data.status =="success"){
					//add(data.message);
					var usia=parseInt(ui.item.lamabln) + parseInt(data.item.cnt);
					//alert(usia);
					$("#jual_usia").val(usia);
					var susut=parseFloat(ui.item.nilai)/parseInt(ui.item.lama);
					var jual_nilaibuku=parseFloat(ui.item.nilai)-( parseInt(usia) * parseFloat(susut));
					$("#jual_nilaibuku").val(jual_nilaibuku.toFixed(2));
				
				}
			}
		});
		
		
		$("#idtr_susut").val(ui.item.idtr_susut);
		
	}
});

</script>