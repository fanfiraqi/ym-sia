<div id="errorHandler" class="alert alert-danger no-display"></div>
<div class="panel panel-default card-view">
        <div class="panel-heading"><label id="lbltitle">Filter</label></div>
		<div class="panel-body">

<div class="row">
	<div class="col-md-12">
		<div class="form-group">
        <label for="nama" class="col-sm-2   control-label">PILIH PERIODE JURNAL</label>
		<div class="col-sm-2"><?=form_dropdown('cbBulan',$arrBulan,date('m'),'id="cbBulan" class="form-control"');?></div>	
		<div class="col-sm-2"><?=form_dropdown('cbTahun',$arrThn, date('Y'),'id="cbTahun" class="form-control"');?></div>
		<!-- <label class="col-sm-2 control-label">Status Jurnal	</label><div class=" col-sm-2"><?=form_dropdown('pilihan',array("New"=>"New", "Posted"=>"Posted"),'','id="pilihan" class="form-control"');?> </div> -->
		<div class="col-sm-2"><a href="javascript:void(0)" id="btcreate" class="btn btn-primary">Pilih</a></div>
        </div><!-- /.form group -->
		
	</div>
</div>
</div>
</div>
<div id="divRes" class="no-display">

</div>

<script>
    $(document).ready(function() {
		
    });
	$("#btcreate").click(function() {
		$().showMessage('Sedang diproses.. Harap tunggu..','info');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('penyusutan/generateData');?>',
			data: { bln:$('#cbBulan').val(),thn:$('#cbTahun').val(), pilihan:$('#pilihan').val() },				
			dataType: 'json',
			success: function(msg) {
				console.log(msg);
				
				if (msg.status=='success')
				{	$( "#errorHandler" ).fadeSlide("hide");
					if (msg.sts!='1')	{
						$( "#errorHandler" ).html(msg.pesan);
						$( "#errorHandler" ).fadeSlide("show");
					}
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
	});
	
	
	function simpanForm(){
		//alert("masuk");
		var form_data = $('#frmSusut').serialize();
		$().showMessage('Sedang diproses.. Harap tunggu..');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('penyusutan/savePenyusutan');?>',
			data: form_data,				
			dataType: 'json',
			success: function(msg) {
				 $("#errorHandler").html('&nbsp;').hide();
				 console.log(msg);
				if(msg.status =='success'){
					$().showMessage('Data transaksi jurnal berhasil disimpan.','success',1000);
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

	function delpenyusutan(idx){
			var pilih=confirm('Hapus Transaksi Jurnal = '+idx+ ' dilanjutkan ?');
			if (pilih==true) {
					$.ajax({
					type	: "POST",
					url		: "<?php echo base_url('penyusutan/delpenyusutan');?>",
					data	: "idx="+idx,
					timeout	: 3000,  
					success	: function(res){
						alert("data berhasil dihapus");
						window.location.reload();
						}
					
				});
			}
		}
    </script>