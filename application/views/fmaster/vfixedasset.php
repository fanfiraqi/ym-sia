<div class="control-group">

<a href="javascript:void(0)" id="btcreate" class="btn btn-primary">Tambah Asset</a>
<!-- <a href="javascript:void(0)" id="btjual" class="btn btn-primary">Penjualan/Disposal Asset</a> -->
</div><br>
<div id="errorHandler" class="alert alert-danger no-display"></div><br>
<div class="alert alert-info alert-dismissable">
<i class="fa fa-info"></i>
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
<b>Mohon dibaca petunjuk berikut sebelum melakukan entri : </b> 
<ol>
	<li>Sebelum menambah seting penyusutan fixed asset, pastikan rekening akun sudah dibuat di master rekening akuntansi dan merupakan satu paket (Akun Aktiva, Akun Akumulasi, dan Akun Biaya Penyusutan)</li>
	<li>Item Detil Fixed Asset yang dikelola adalah level 5. Contoh : 1-32xx.xxx Mobil ZZZ (akun Aset), 1-32xx.xxx Akumulasi Mobil ZZZ, 6-2xxx.xxx Biaya Penyusutan Mobil ZZZ</li>
	<li> Ketentuan Tambah Asset : 
		<ul>
			<li>Tambah Aset bisa aset existing maupun pengadaan baru, namun jika pengadaan baru tetap transaksi pembelian/pengadaan dicatat di posting jurnal umum</li>
			<li>Tanggal Perolehan : diisi sesuai tanggal Riil Asset diadakan (dibeli)</li>
			<li>Akun Fixed Asset, Akumulasi, dan biaya penyusutan : sesuai dengan akun yang telah dibuat di master rekening perkiraan</li>
			<li>Nilai Perolehan saat ini : Diisi nilai perolehan aset ketika dientri, jika saldo awal aset sudah diseting nilainya muncul otomatis pada saat textbox 'Akun Fixed Aset' dipilih (Berlaku untuk form tambah)</li>
			<li>Lama Penyusutan s.d saat ini : Jumlah bulan dari tanggal perolehan yang telah berlalu</li>
			<li>Lama Sisa Penyusutan : Jumlah bulan dari saat entri sampai habis masa ekonomis aset</li>
			
		</ul>
	</li>	
	<li>Untuk penjualan Asset hanya menyimpan informasi perubahan status dan data penjualan, Untuk posting jurnalnya harus dientri di Jurnal Umum</li>
	
</ol></div>
<div id="divformkel" class="no-display"><!-- divformkel -->
<?php echo form_open(null,array('class'=>'form-horizontal','id'=>'myform'));?>
<div class="row">
<div class="col-md-10">
 <div class="box box-info">
 <div class="box-body">
<div class="control-group" class="span8 m-wrap">
	<h4><u><label id="lbltitle">Tambah Data Set Susut Fixed Asset Baru</label></u></h4>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group">
        <label class="col-sm-3 control-label">Tanggal Perolehan/Beli	</label>
        <div class=" col-sm-3 input-group">        
        <input type="text" name="tgl_perolehan" id="tgl_perolehan" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask placeholder="yyyy-mm-dd" value="<?=date('Y-m-d')?>"/>
		<div class="input-group-addon">
        <span class="fa fa-calendar" id="bttgl"></span>
        </div>
        </div><!-- /.input group -->		
        </div><!-- /.form group -->
		
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label class="col-sm-3 control-label">Akun Fixed Asset </label>
			<div class="col-sm-6"><?=form_input(array('name'=>'textAsset','id'=>'textAsset','class'=>'form-control'));?><input type="hidden" name="akunAsset" id="akunAsset"></div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label class="col-sm-3 control-label">Akun Akumulasi</label>
			<div class="col-sm-4"><?=form_input(array('name'=>'textAkum','id'=>'textAkum','class'=>'form-control'));?><input type="hidden" name="akunAkum" id="akunAkum"></div>
			
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label class="col-sm-3 control-label">Akun Biaya Penyusutan</label>
			<div class="col-sm-6"><?=form_input(array('name'=>'textSusut','id'=>'textSusut','class'=>'form-control'));?><input type="hidden" name="akunSusut" id="akunSusut"></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label  class="col-sm-3 control-label">Nilai Perolehan saat ini</label>
			<div class="col-sm-3"><?=form_input(array('name'=>'np_saat_ini','id'=>'np_saat_ini','class'=>'form-control', 'value'=>0));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label  class="col-sm-3 control-label">Lama Penyusutan s.d saat ini</label>
			<div class="col-sm-3"><?=form_input(array('name'=>'lama_saat_ini','id'=>'lama_saat_ini','class'=>'form-control','onkeypress'=>"return numericVal(this,event)", 'value'=>0));?></div><label  class="col-sm-2">Bulan</label>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label  class="col-sm-3 control-label">Lama Sisa Penyusutan</label>
			<div class="col-sm-3"><?=form_input(array('name'=>'lama_sisa','id'=>'lama_sisa','class'=>'form-control','onkeypress'=>"return numericVal(this,event)", 'value'=>0));?></div><label  class="col-sm-2">Bulan</label>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label  class="col-sm-3 control-label">Lama Penyusutan total</label>
			<div class="col-sm-3"><?=form_input(array('name'=>'lama_total','id'=>'lama_total','class'=>'form-control', 'readonly'=>'true', 'background-color'=>'lightgrey','onkeypress'=>"return numericVal(this,event)", 'value'=>0));?></div><label  class="col-sm-2">Bulan</label>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label  class="col-sm-3 control-label">Nilai Penyusutan @bulan</label>
			<div class="col-sm-3"><?=form_input(array('name'=>'susut_perbulan','id'=>'susut_perbulan','class'=>'form-control','onkeypress'=>"return numericVal(this,event)", 'value'=>0, 'readonly'=>'true', 'background-color'=>'lightgrey'));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label  class="col-sm-3 control-label">Akumulasi Penyusutan s.d saat ini</label>
			<div class="col-sm-3"><?=form_input(array('name'=>'nakum_saat_ini','id'=>'nakum_saat_ini','class'=>'form-control','onkeypress'=>"return numericVal(this,event)", 'value'=>0, 'readonly'=>'true', 'background-color'=>'lightgrey'));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label  class="col-sm-3 control-label">Nilai Perolehan Total</label>
			<div class="col-sm-3"><?=form_input(array('name'=>'np_total','id'=>'np_total','class'=>'form-control','onkeypress'=>"return numericVal(this,event)", 'value'=>0, 'readonly'=>'true', 'background-color'=>'lightgrey'));?></div>
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
<!-- <div class="row">
	<div class="col-md-12">
		<div class="form-group"><label class="col-sm-3 control-label">Pilihan Transaksi</label>
			<div class="col-sm-3"><?=form_dropdown('pilihan',array('1'=>'Asset Existing','0'=>'Pengadaan Baru'),'','id="pilihan" class="form-control"');?>	</div>
			<div class="col-sm-6" id ="divPilihan" class="no-display"><label class="col-sm-3 control-label">Akun Kredit </label><div class="col-sm-6">
			<?=form_input(array('name'=>'textKredit','id'=>'textKredit','class'=>'form-control'));?><input type="hidden" name="akunKredit" id="akunKredit"><input type="hidden" name="kelompokAkunKredit" id="kelompokAkunKredit">
			</div>
			</div>
		</div>
	</div>
</div> -->
<div class="row">
	<div class="col-md-12">
		<div class="form-group"><label class="col-sm-3 control-label">Status</label>
			<div class="col-sm-3"><?=form_dropdown('status',array('1'=>'Aktif','0'=>'Tidak Aktif'),'','id="status" class="form-control"');?>	</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
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

<div id="divformjual" class="no-display"><!-- divformjual -->
<?php echo form_open(null,array('class'=>'form-horizontal','id'=>'myformJual'));?>
<div class="row">
<div class="col-md-6">
 <div class="box box-info">
 <div class="box-body">
<div class="control-group" class="span8 m-wrap">
	<h4><u><label id="jual_lbltitle">Form Penjualan/Disposal Fixed Asset</label></u></h4>
</div>
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

<br>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="dataTables">
		<thead>
			<tr>
				<th>ID</th>
				<th>AKUN ASET</th>				
				<th>AKUN AKUM</th>				
				<th>AKUN BIAYA</th>
				<th>NP&nbsp;s.d&nbsp;SAAT&nbsp;INI</th>
				<th>LAMA&nbsp;SISA&nbsp;(BLN)</th>
				<th>TGL PEROLEHAN</th>
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
		$("#divPilihan").fadeSlide("hide");
		$("[data-mask]").inputmask();
        $('#dataTables').dataTable({
			"bProcessing": true,
			"bServerSide": true,
			"iDisplayLength": 25,
			"aoColumns": [
				{"mData": "ID" },
				{"mData": "ASET" },
				{"mData": "AKUM" },
				{"mData": "BIAYA" },
				{"mData": "NILAI" },
				{"mData": "LAMA" },
				{"mData": "TGL" },
				{"mData": "ISACTIVE" },
				{"mData": "ACTION", "sortable":false }
			],
			"sAjaxSource": "<?php echo base_url('fixedasset/json_data');?>"
		});

		

    });	
	
	
	$( "#tgl_perolehan" ).datepicker({
		dateFormat: 'yyyy-mm-dd',
		onSelect: function(date) {
            alert(date)
        }
		});
	$("#bttgl").click(function() {
		$("#tgl_perolehan").datepicker("show");
	});
	/*$("#pilihan").click(function() {
		if ($("#pilihan").val()=='1'){
			$("#divPilihan").fadeSlide("hide");
		}else{
			$("#divPilihan").fadeSlide("show");

		}
	});
*/
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
			url: '<?php echo base_url('fixedasset/saveSellAsset');?>',
			//url: '<?php echo base_url('fixedasset/viewPostJurnal');?>',
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
	$('#btsimpan').click(function(){		
		var form_data = $('#myform').serialize();
		$().showMessage('Sedang diproses.. Harap tunggu..');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('fixedasset/savefixedasset');?>',
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
					url		: "<?php echo base_url('fixedasset/deltruefixedasset');?>",
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
					url		: "<?php echo base_url('fixedasset/ubahStatus');?>",
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
	$("#np_saat_ini").blur(function(){
		var num = $('#np_saat_ini').maskMoney('unmasked')[0];
		$('#np_saat_ini').val(num);
		$('#np_saat_ini').maskMoney('destroy');
		//alert($('#jumlah').val());
		
	});
	$('#np_saat_ini').click(function(){
		$("#np_saat_ini").maskMoney({thousands:'.', decimal:',', allowZero:true, });
		$("#np_saat_ini").maskMoney('mask', parseFloat($("#np_saat_ini").val()));
	})
	
	//======================================================
	
	//======================================================
	/*$("#nilai").blur(function(){
		var num = $('#nilai').maskMoney('unmasked')[0];
		$('#nilai').val(num);
		//alert($('#jumlah').val());
		
	});*/
	$("#harga_jual").maskMoney({thousands:'.', decimal:',', allowZero:true});
	$("#harga_jual").blur(function(){
		var num = $('#harga_jual').maskMoney('unmasked')[0];
		$('#harga_jual').val(num);
		//alert($('#jumlah').val());
		
	});
	$("#harga_jual").maskMoney({thousands:'.', decimal:',', allowZero:true});
	
	function editfixedasset(obj){
		var id = $(obj).attr('data-id');
		$('#myform input[name="state"]').val(id);		
		
		$.ajax({
			type: 'POST',
			url:  "<?php echo base_url('fixedasset/getFixedAsetSet'); ?>",
			data: {
				id:id
			},
			dataType: 'json',
			success: function(msg) {
				
				if(msg.status =='success'){
					console.log(msg.data);
					$('#lbltitle').text('Edit Data Susut Fixed Asset');
					//$('#lbltahun').val(msg.periode);
					$('#tgl_perolehan').val(msg.data.tgl_perolehan);	
					$('#textAsset').val(msg.data.akunasset);
					$('#textAkum').val(msg.data.akunakum);
					$('#textSusut').val(msg.data.akunsusut);
					$('#akunAsset').val(msg.data.accFixedAsset);
					$('#akunAkum').val(msg.data.accSusutAkm);
					$('#akunSusut').val(msg.data.accSusutBiaya);
					$('#np_saat_ini').val(msg.data.nilai_perolehan_saat_ini);
					

					$('#lama_saat_ini').val(msg.data.lama_susut_sd_saat_ini);
					$('#lama_sisa').val(msg.data.lama_sisa_susut);
					$('#lama_total').val(parseInt(msg.data.lama_sisa_susut) + parseInt(msg.data.lama_susut_sd_saat_ini));
					var susut = parseFloat(msg.data.nilai_perolehan_saat_ini) /parseFloat(msg.data.lama_sisa_susut); 
					$('#susut_perbulan').val( susut.toFixed(2) );
					var nakum= susut * msg.data.lama_susut_sd_saat_ini;
					$('#nakum_saat_ini').val( nakum.toFixed(2) );					
					var total = susut * $('#lama_total').val();
					$('#np_total').val( total.toFixed(2) );
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
	$("#textKredit").autocomplete({
	minLength: 2,
	source:
	function(req, add){
		$.ajax({
			url: "<?php echo base_url('fixedasset/getAkunKredit'); ?>",
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
		$("#akunKredit").val(ui.item.id);
		$("#kelompokAkunKredit").val(ui.item.kelompok);
		
	}
});

$('#lama_saat_ini').blur(function(){
	$("#lama_total").val(parseFloat($('#lama_sisa').val())+parseFloat($(this).val()));
	var nakum=parseFloat($(this).val()) * parseFloat($('#susut_perbulan').val());
	$("#nakum_saat_ini").val( nakum.toFixed(2) );
	var total=parseFloat($('#susut_perbulan').val()) * $("#lama_total").val();
	$("#np_total").val( total.toFixed(2) );
});

$('#lama_sisa').blur(function(){
	$("#lama_total").val(parseFloat($('#lama_saat_ini').val())+parseFloat($(this).val()));
	var susut=parseFloat($("#np_saat_ini").val()) / parseFloat($(this).val());
	$('#susut_perbulan').val(  susut.toFixed(2) );	
	$('#lama_saat_ini').blur();

});


	$("#textAsset").autocomplete({
	minLength: 2,
	source:
	function(req, add){
		$.ajax({
			url: "<?php echo base_url('fixedasset/getAkunAsset'); ?>",
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
		$("#akunAsset").val(ui.item.id);
		if ($("#state").val()=="add"){
			$("#np_saat_ini").val(ui.item.initval);
		}else{
			$("#np_saat_ini").val('0');
		}
		
		
	}
});

$("#textAkum").autocomplete({
	minLength: 2,
	source:
	function(req, add){
		$.ajax({
			url: "<?php echo base_url('fixedasset/getAkunAkum'); ?>",
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
		$("#akunAkum").val(ui.item.id);
		
	}
});

$("#textSusut").autocomplete({
	minLength: 2,
	source:
	function(req, add){
		$.ajax({
			url: "<?php echo base_url('fixedasset/getAkunSusut'); ?>",
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
		$("#akunSusut").val(ui.item.id);
		
	}
});

$("#jual_textAsset").autocomplete({
	minLength: 2,
	source:
	function(req, add){
		$.ajax({
			url: "<?php echo base_url('fixedasset/getFixedToSell'); ?>",
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
			url: "<?php echo base_url('fixedasset/getNilaiSisa'); ?>",
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