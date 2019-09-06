<div class="control-group">

<a href="javascript:void(0)" id="btjual" class="btn btn-primary">Penjualan/Disposal Asset</a> 
</div><br>
<div id="errorHandler" class="alert alert-danger no-display"></div><br>


<div id="divformjual" class="no-display"><!-- divformjual -->
<?php echo form_open(null,array('class'=>'form-horizontal','id'=>'myformJual'));?>
<div class="row">
<div class="col-md-10">
 <div class="panel panel-default card-view">
 <div class="panel-heading">
									<div class="pull-left">
										<h6 class="panel-title txt-dark"><label id="jual_lbltitle">Form Penjualan/Disposal Fixed Asset</label></h6>
									</div>
									<div class="clearfix"></div>
								</div>
 <div class="panel-body">

<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label class="col-sm-3 control-label">Nama Akun Fixed Asset </label>
			<div class="col-sm-9"><?=form_input(array('name'=>'jual_textAsset','id'=>'jual_textAsset','class'=>'form-control'));?><input type="hidden" name="jual_idakunAsset" id="jual_idakunAsset"></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label  class="col-sm-3 control-label">Tanggal Perolehan</label>
			<div class="col-sm-6"><?=form_input(array('name'=>'jual_tgl_perolehan','id'=>'jual_tgl_perolehan','class'=>'form-control', 'readonly'=>true));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label  class="col-sm-3 control-label">Nilai Perolehan Total (Rp.)</label>
			<div class="col-sm-9"><?=form_input(array('name'=>'jual_nilai','id'=>'jual_nilai','class'=>'form-control','readonly'=>true));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label  class="col-sm-3 control-label">Total Lama Penyusutan</label>
			<div class="col-sm-6"><?=form_input(array('name'=>'jual_lama','id'=>'jual_lama','class'=>'form-control','readonly'=>true));?></div><label  class="col-sm-2">Bulan</label>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label  class="col-sm-3 control-label">Sisa Usia Aset</label>
			<div class="col-sm-6"><?=form_input(array('name'=>'jual_usia','id'=>'jual_usia','class'=>'form-control','readonly'=>true));?></div><label  class="col-sm-2">Bulan</label>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label  class="col-sm-3 control-label">Nilai Buku (Rp.)</label>
			<div class="col-sm-9"><?=form_input(array('name'=>'jual_nilaibuku','id'=>'jual_nilaibuku','class'=>'form-control','readonly'=>true));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label class="col-sm-3 control-label">Status</label>
			<div class="col-sm-6"><?=form_input(array('name'=>'jual_status','id'=>'jual_status','class'=>'form-control','readonly'=>true));?>	</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
	<div class="form-group">
        <label class="col-sm-3 control-label">Tanggal Penjualan</label>
        <div class=" col-sm-6 input-group">        
        <input type="text" name="tgl_jual" id="tgl_jual" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask placeholder="yyyy-mm-dd" value="<?=date('Y-m-d')?>"/>
		<div class="input-group-addon">
        <span class="fa fa-calendar" id="bttgl_jual"></span>
        </div>
        </div><!-- /.input group -->		
        </div><!-- /.form group -->
		
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label  class="col-sm-3 control-label">Harga Jual</label>
			<div class="col-sm-9"><?=form_input(array('name'=>'harga_jual','id'=>'harga_jual','class'=>'form-control','onkeypress'=>"return numericVal(this,event)", 'value'=>0));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label class="col-sm-3 control-label">Alasan Dijual/Disposal</label>
			<div class="col-sm-9"><?=form_textarea(array('name'=>'alasan','id'=>'alasan','class'=>'form-control'));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label  class="col-sm-3 control-label">Mengetahui</label>
			<div class="col-sm-9"><?=form_input(array('name'=>'mengetahui','id'=>'mengetahui','class'=>'form-control'));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
	<input type="hidden" name="tglSystem" id="tglSystem" value="<?=date('d-m-Y')?>">
	<input type="hidden" name="idtr_susut" id="idtr_susut">
	<input type="hidden" name="jual_state" id="jual_state" value="add">
	<!-- <input type="button" class="btn btn-success" id="jual_btpreview" value="preview jurnal"> -->
	<input type="button" class="btn btn-primary" id="jual_btsimpan" value="simpan">
	<button type="button" class="btn btn-default" id="jual_btcancel">Batal</button>
	</div>
</div>

			</div>
		</div>
	</div>
	<!-- box 1 & 2 -->
<!-- <div class="col-md-6">
 <div class="box box-info">
 <div class="box-body">
<div class="control-group" class="span8 m-wrap">
	<h4><u><label id="jual_lbltitle">Preview Posting Jurnal</label></u></h4>
</div>

<div id="divpreview" class="no-display">

</div>

		</div>
		</div>
	</div> -->
</div>
<?php echo form_close();?>
<hr />
</div><!-- divformjual -->

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
				<th>TGL JUAL</th>
				<th>NILAI JUAL</th>
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
				{"mData": "TGL_JUAL" },
				{"mData": "NILAI_JUAL" },
				{"mData": "ISACTIVE" },
				{"mData": "ACTION", "sortable":false }
			],
			"sAjaxSource": "<?php echo base_url('aset/json_data_dispose');?>"
		});

		

    });	
	
	$('#cabangfilter').change(function(){
		$('#dataTables').dataTable().fnReloadAjax();

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
	
	
	$('#btjual').click(function(){
		$("#divformjual").fadeSlide("show");
		$('#jual_state').val('add');
		$('#jual_lbltitle').text('Tambah Data Penjualan Fixed Asset');
		//$('#username').removeAttr('readonly');
		$('#myformJual').reset();
		
		
	});
	$('#jual_btcancel').click(function(){
		$("#divformjual").fadeSlide("hide");
	});
	$('#jual_btpreview').click(function(){	
		$("#divpreview").html("");
		var shtml="<table><tr><th>No</th><th>Tanggal</th><th>Keterangan</th><th>Debet</th><th>Kredit</th></tr>";
		var asset=$("#jual_textAsset").val();
		var tgl=$("#tglSystem").val();
		var usia=parseFloat($("#jual_usia").val());
		var susut=perolehan/parseFloat($("#jual_lama").val());
		var susut=perolehan/parseFloat($("#jual_lama").val());
		var nilaibuku=parseFloat($("#jual_nilaibuku").val());
		var kas=parseFloat($("#harga_jual").val());
		if (kas>nilaibuku){
			var laba=kas-nilaibuku;
			shtml+="<tr><td colspan=5>Keterangan Transaksi : Penjurnalan Laba</td></tr>";
			shtml+="<tr><td >1</td><td >"+tgl+"</td><td >Kas</td><td >"+kas+"</td><td ></td></tr>";
			shtml+="<tr><td >&nbsp;</td><td>&nbsp;</td><td >"+asset+"</td><td ></td><td >"+kas+"</td></tr>";
			shtml+="<tr><td >2</td><td >"+tgl+"</td><td >Akumulasi Peny "+asset+"</td><td >"+kas+"</td><td ></td></tr>";
			shtml+="<tr><td ></td><td ></td><td >Laba Penjualan Aktiva</td><td ></td><td >"+laba+"</td></tr>";
			
		}
		shtml+="</table>";

		$("#divpreview").html(shtml);
		$("#divpreview").fadeSlide("show");
	});
	$('#jual_btsimpan').click(function(){		

		var form_data = $('#myformJual').serialize();
		$().showMessage('Sedang diproses.. Harap tunggu..');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('aset/saveSellAsset');?>',
			//url: '<?php echo base_url('aset/viewPostJurnal');?>',
			data: form_data,				
			dataType: 'json',
			success: function(msg) {
				 $("#errorHandler").html('&nbsp;').hide();
				 console.log(msg);
				if(msg.status =='success'){
					$().showMessage('Data Penjualan berhasil disimpan.','success',1000);
					//$("#divformjual").fadeSlide("hide");
					//$('#dataTables').dataTable().fnReloadAjax();
					
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
	


$("#jual_textAsset").autocomplete({
	minLength: 2,
	source:
	function(req, add){
		$.ajax({
			url: "<?php echo base_url('aset/getAsetToSell'); ?>",
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
		var nilai=parseFloat(ui.item.nilai_perolehan) - ( parseFloat(ui.item.nilai_susut) * parseFloat(ui.item.lama));
		$("#jual_nilai").val(ui.item.nilai_perolehan );	//total NP
		$("#jual_lama").val(ui.item.lama);	//total lama
		$("#jual_status").val(ui.item.status);
		$("#jual_nilaibuku").val(Math.round(nilai));
		$("#jual_usia").val(parseInt(ui.item.umur)-parseInt(ui.item.lama));
	
		
		
		$("#idtr_susut").val(ui.item.idtr_susut);
		
	}
});

</script>