
   
   <div class="panel panel-default card-view">
        <div class="panel-heading">
		<div class="pull-left">
			<h6 class="panel-title txt-dark"><label id="lbltitle">Jurnal Penggajian</label></h6>
		</div>
		
		<div class="clearfix"></div>
	</div>
	<div class="panel-wrapper collapse in">
	<div  class="panel-body row pa-0">
<br>
<div class="row">
	<div class="col-md-8 form-inline" >
		<div class="form-group">
        <label class="col-md-6" >Pilih Tanggal Mulai </label>
        <div class=" col-md-6 input-group" >        
        <input type="text" name="tglJurnal" id="tglJurnal" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask placeholder="yyyy-mm-dd" value="<?=date('Y-m-d')?>"/>
		<div id="bttgl1" class="input-group-addon"> <span class="fa fa-calendar" id="bttgl1"></span> </div>
        </div><!-- /.input group -->
		</div><!-- /.form group -->
		<div class="form-group">
		<label class="col-md-4" >s.d </label>
        <div class=" col-md-8 input-group" >        
        <input type="text" name="tglJurnal2" id="tglJurnal2" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask placeholder="yyyy-mm-dd" value="<?=date('Y-m-d')?>"/>
		<div id="bttgl1" class="input-group-addon">
        <span class="fa fa-calendar" id="bttgl2"></span>
        </div>
        </div><!-- /.input group -->	
        
		</div><!-- /.form group -->
		
	</div>
	<!-- <label class="col-md-1" >Status </label>
	<div class=" col-md-2" >   
		<?=form_dropdown('pilihan',array("New"=>"New", "Posted"=>"Posted"),'','id="pilihan" class="form-control"');?>
		
    </div> -->
	<div class=" col-sm-2">   
	<input type="button" class="btn btn-primary" id="btpilih" value="Lanjut" >
	</div>
</div>
	
	<br>

	<div id="divRes" class="no-display" style="overflow:auto;"></div>


	</div>
</div>
<script>
$( "#tglJurnal" ).datepicker({
		dateFormat: 'yy-mm-dd',
		onSelect: function(date) {
            
        }
		});

$("#bttgl1").click(function() {
		$("#tglJurnal").datepicker("show");
	});

$( "#tglJurnal2" ).datepicker({
		dateFormat: 'yy-mm-dd',
		onSelect: function(date) {
           
        }
		});

$("#bttgl2").click(function() {
		$("#tglJurnal2").datepicker("show");
	});
	
$("#btpilih").click(function() {
		var tgl1=$( "#tglJurnal" ).val();		
		var tgl2=$( "#tglJurnal2" ).val();		
		var pilihan=$("#pilihan").val();		
		display(tgl1, tgl2, pilihan);
	});
function display(tgl1, tgl2,  pilihan){
		$().showMessage('Sedang diproses.. Harap tunggu..','info');
		
		var myurl='';
		/*if (pilihan=='New')	{
			myurl='<?php echo base_url('penggajian/generateData');?>';
		}else{
			myurl='<?php echo base_url('penggajian/generateDataPosted');?>';
		}*/
		myurl='<?php echo base_url('penggajian/generateData');?>';
		$.ajax({
			type: 'POST',
			url: myurl,
			data: { tgl1:tgl1,tgl2:tgl2, pilihan:pilihan },				
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
		var form_data = $('#frmpenyaluran').serialize();
		$().showMessage('Sedang diproses.. Harap tunggu..');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('penggajian/saveJurnal');?>',
			data: form_data,				
			dataType: 'json',
			success: function(msg) {
				 $("#errorHandler").html('&nbsp;').hide();
				 //console.log(msg);
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

</script>