<div class="control-group"><a href="javascript:void(0)" id="btcreate" class="btn btn-primary">Tambah Transaksi</a></div><br>
<div id="errorHandler" class="alert alert-danger no-display"></div>
<?php 

echo form_open(null,array('class'=>'form-horizontal','id'=>'myformkel'));
?>
<div id="divformkel" class="no-display">
<div class="panel panel-default card-view"><div class="panel-heading"><label id="lbltitle">Tambah Transaksi  Non Kas</label></div>
		<div class="panel-body">

<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">Periode Aktif</label>
			<div class="col-sm-4"><label id="lbltahun"><?=$periode?></label></div>
		</div>
	</div>
</div>

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
<!-- tambahan -->
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">Opsi</label>
			<div class="col-sm-8 form-inline"><input type="checkbox" name="ckOpsi" id="ckOpsi" class="form-control"><label class="control-label">&nbsp;&nbsp;Centang Jika Jurnal Balik</label></div>
		</div>
	</div>
</div>

<!-- DEBET -->
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">Akun Debet</label>
			<div class="col-sm-8"><?=form_input(array('name'=>'textDebet','id'=>'textDebet','class'=>'form-control'));?><input type="hidden" name="akundebet" id="akundebet"></div>
		</div>
	</div>
</div>
<!-- KREDIT -->
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">Akun Kredit</label>
			<div class="col-sm-8"><?=form_input(array('name'=>'textKredit','id'=>'textKredit','class'=>'form-control'));?><input type="hidden" name="akunkredit" id="akunkredit"></div>
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
			<div class="col-sm-4"><?=form_input(array('name'=>'jumlah','id'=>'jumlah','class'=>'form-control','onkeypress'=>"return numericVal(this,event)", "onblur"=>"blurObj(this)", "onclick"=>"clickObj(this)", 'value'=>0));?></div>
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
	<div class="col-md-8"><label class="col-sm-4 control-label"></label>
		<div class="col-sm-4">
		<input type="hidden" name="tahun" id="tahun" value="<?=$periode?>">
		<!-- <input type="hidden" name="id" id="id" value="<?=date('YmdHis')?>"> -->
		<input type="hidden" name="state" id="state" value="add">
		<input type="button" class="btn btn-primary" id="btsimpankel" value="Simpan">
		<button type="button" class="btn btn-default" id="btcancelkel">Batal</button>
		</div>
	</div>
</div> 

</div>
</div>

</div><!-- end divformkel -->

<?php echo form_close();?>  

<hr/>
<!-- <div class="row">
	<div class="col-md-8">
		<div class="form-group">
        <label class="col-sm-4 control-label">Pilih Tanggal Jurnal	</label>
        <div class=" col-sm-4 input-group">        
        <input type="text" name="tgl2" id="tgl2" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask placeholder="yyyy-mm-dd" value="<?=date('Y-m-d')?>"/>
		<div class="input-group-addon">
       <span class="fa fa-calendar" id="bttgl"></span>
        </div> -->
       <!--  </div>/.input group -->
        <!--</div> /.form group -->
	
	<!-- </div>
	<div class=" col-sm-4 input-group"><input type="button" class="btn btn-primary" id="btcari" value="Cari"></div>	
</div> -->
<div class="row">
	<div class="col-md-12">
		<div class="form-group col-md-4">
        <label class="col-sm-5 control-label">Dari Tanggal</label>
        <div class=" col-sm-7 input-group">        
        <input type="text" name="tgl1" id="tgl1" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask placeholder="yyyy-mm-dd" value="<?=date('Y-m-d')?>"/>
		<div class="input-group-addon">
        <span class="fa fa-calendar" id="bttgl1"></span>
        </div>
        </div><!-- /.input group -->	
		</div><!-- /.form group -->
		
		<div class="form-group  col-md-4">
		<label class="col-sm-5 control-label">Sampai</label>
        <div class=" col-sm-7 input-group">        
        <input type="text" name="tgl2" id="tgl2" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask placeholder="yyyy-mm-dd" value="<?=date('Y-m-d')?>"/>
		<div class="input-group-addon">
        <span class="fa fa-calendar" id="bttgl2"></span>
        </div>
        </div><!-- /.input group -->	
	 </div><!-- /.form group -->
		
		<div class="col-md-4 input-group" ><label class="col-md-1 control-label"></label>
			<input type="button" class="btn btn-primary" id="btcari" value="Cari">
		</div>


	</div>
</div>	

<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="dataTables">
		<thead>
			<tr>
				<th>NO</th>
				<th>TGL JURNAL</th>				
				<th>KETERANGAN</th>
				<th>DEBET</th>
				<th>KREDIT</th>
				<th>BUKTI</th>
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
			"fnServerParams": function ( aoData ) {
				aoData.push( { "name": "tanggal1", "value": $('#tgl1').val() }, { "name": "tanggal2", "value": $('#tgl2').val() })
			},
			"aoColumns": [
				{"mData": "NOMER" },
				{"mData": "TANGGAL" },
				{"mData": "KETERANGAN" },
				{"mData": "DEBET" },
				{"mData": "KREDIT" },
				{"mData": "BUKTI" },
				{"mData": "ACTION", "sortable":false }
			],
			"sAjaxSource": "<?php echo base_url('jurnalnonkas/json_data');?>"
		});

		

    });
	$( "#btcari" ).click(function(){
		$('#dataTables').dataTable().fnReloadAjax();
	});
	$('#btcreate').click(function(){
		$("#divformkel").fadeSlide("show");
		$('#state').val('add');
		$('#lbltitle').text('Tambah Transaksi Jurnal Non Kas');
		$('#textDebet').removeAttr('disabled');
		$('#textKredit').removeAttr('disabled');	
		$('#akundebet').removeAttr('disabled');
		$('#akunkredit').removeAttr('disabled');
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
					//$('#lblnotrans').val(msg.data.notransaksi);					
					//$('#id').val(msg.data.notransaksi);					
					$('#tglJurnal').val(msg.data.tanggal);			
					$('#textDebet').val(msg.data.akundebet);
					$('#textKredit').val(msg.data.akunkredit);
					$('#akundebet').val(msg.data.idperkdebet);
					$('#akunkredit').val(msg.data.idperkkredit);
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

	$("#textDebet").autocomplete({
	minLength: 2,
	source:
	function(req, add){
		$.ajax({
			url: "<?php echo base_url('jurnalnonkas/getAkunNonKas'); ?>",
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
		$("#akundebet").val(ui.item.id);
		
	}
});

$("#textKredit").autocomplete({
	minLength: 2,
	source:
	function(req, add){
		$.ajax({
			url: "<?php echo base_url('jurnalnonkas/getAkunNonKas'); ?>",
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
		$("#akunkredit").val(ui.item.id);
		
	}
});

$( "#tgl1" ).datepicker({
		dateFormat: 'yy-mm-dd',
		onSelect: function(date) {
            alert(date)
        }
		});
$("#bttgl1").click(function() {
		$("#tgl1").datepicker("show");
	});
$( "#tgl2" ).datepicker({dateFormat: 'yyyy-mm-dd'});
$("#bttgl2").click(function() {
		$("#tgl2").datepicker("show");
	});
$( "#tglJurnal" ).datepicker({
		dateFormat: 'yy-mm-dd',
		onSelect: function(date) {
            alert(date)
        }
		});
$("#bttgl").click(function() {
		$("#tglJurnal").datepicker("show");
	});
    </script>