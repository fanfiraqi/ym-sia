
   
   <div class="panel panel-default card-view form-horizontal">
        <div class="panel-heading">
		<div class="pull-left">
			<h6 class="panel-title txt-dark"><label id="lbltitle">Jurnal Penggajian</label></h6>
		</div>
		
		<div class="clearfix"></div>
	</div>
	<div class="panel-wrapper collapse in">
	<div  class="panel-body row pa-0">
<br>
	<div class="row ">
			<div class="col-xs-12">
				<div class="form-group"><label class="col-sm-4 control-label">JENIS PENGGAJIAN</label>
					<div class="col-sm-8"><?=form_dropdown('jenis',$jenis,'','id="jenis" class="form-control" ');?></div>
				</div>
			</div>
		</div>

		<div class="row" id = "slide_out" >
			<div class="col-xs-12">
				<div class="form-group"><label  class="col-sm-4 control-label">BULAN</label>
					<div class="col-sm-8"><?=form_dropdown('cbBulan',$arrBulan,date('m'),'id="cbBulan" class="form-control"');?>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="form-group"><label  class="col-sm-4 control-label">TAHUN</label>
					<div class="col-sm-8"><?=form_dropdown('cbTahun',$arrThn, date('Y'),'id="cbTahun" class="form-control"');?></div>
				</div>
			</div>
		</div>
	
	<div class="row ">
			<div class="col-xs-12">  <label  class="col-sm-4 control-label">&nbsp;</label>
			<div class="col-sm-8"><input type="button" class="btn btn-primary" id="btpilih" value="Lanjut" >
			</div>
		</div>
	</div>
	<br>
	<div id="divRes" class="no-display" style="overflow:auto;"></div>


	</div>
</div>
<script>

$('#jenis').change(function(){
		if($(this).val()=="thr"){
			$("#slide_out").fadeSlide("hide");
		}else{
			$("#slide_out").fadeSlide("show");
		}
	});

	
$("#btpilih").click(function() {
		var jenis=$( "#jenis" ).val();		
		var bln=$( "#cbBulan" ).val();		
		var thn=$( "#cbTahun" ).val();		
		var pilihan=$("#pilihan").val();		
		display(jenis, bln, thn);
	});
function display(jenis, bln, thn){
		$().showMessage('Sedang diproses.. Harap tunggu..','info');
		
		var myurl='';
		
		myurl='<?php echo base_url('penggajian/generateData');?>';
		$.ajax({
			type: 'POST',
			url: myurl,
			data: { jenis:jenis,bln:bln, thn:thn},				
			dataType: 'json',
			success: function(msg) {
				console.log(msg);
				if (msg.status=='success')
				{
					$( "#divRes" ).html(msg.html);					
					$( "#divRes" ).fadeSlide("show");
					$().showMessage('Completed.','success',1000);
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				//$().showMessage('Terjadi kesalahan.<br />'+	textStatus + ' - ' + errorThrown ,'danger');
				bootbox.alert("Terjadi kesalahan. Data gagal ditampilkan."+	textStatus + " - " + errorThrown );
			},
			cache: false
		});
	}

function updateForm(){
		//alert("masuk");
		var form_data = $('#frmpenyaluran').serialize();
		$().showMessage('Sedang diproses.. Harap tunggu..');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('penggajian/updateJurnal');?>',
			data: form_data,				
			dataType: 'json',
			success: function(msg) {
				 $("#errorHandler").html('&nbsp;').hide();
				 console.log(msg);
				if(msg.status =='success'){
					$().showMessage('Data transaksi jurnal berhasil diupdate.','success',1000);
					$("#divRes").fadeSlide("hide");					
					
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
	}
function simpanForm(){
		//alert("masuk");
		var form_data = $('#frmgaji').serialize();
		$().showMessage('Sedang diproses.. Harap tunggu..');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('penggajian/saveJurnal');?>',
			data: form_data,				
			dataType: 'json',
			success: function(msg) {
				// $("#errorHandler").html('&nbsp;').hide();
				 console.log(msg);
				if(msg.status =='success'){
					$().showMessage('Data transaksi jurnal berhasil disimpan.','success',1000);
					$("#divRes").fadeSlide("hide");					
					
				} else {
					$().showMessage('Terjadi kesalahan. Data gagal disimpan.','danger',700);
					//$("#errorHandler").html(msg.errormsg).show();
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				bootbox.alert("Terjadi kesalahan. Data gagal disimpan."+	textStatus + " - " + errorThrown );
			},
			cache: false
		});
	}

</script>