<?
$wilayah=$cabang=$this->common_model->comboGetMasterCabang();
//utk laporan selain neraca konsolidasi, maka laporan dari register s.d laba rugi adalah dari masing2 cabang, jika user admin/manager (id_cab=0) tetap hanya bisa milih laporan cabang, bukan laporan konsolidasi (rek akun level 1-3)
 
echo form_open($action,array('class'=>'form-horizontal','id'=>'myform'));

switch ($jenis){
		case "posisikonsolidasi":
?>
<div class="row">
	<div class="col-md-8">
		<div class="form-group">
				<label for="nama" class="col-md-2   control-label">BULAN</label>
					<div class="col-sm-4"><?=form_dropdown('cbBulan',$arrBulan,date('m'),'id="cbBulan" class="form-control"');?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group">
			<label for="nama" class="col-md-2   control-label">TAHUN</label>
			<div class="col-sm-4"><?=form_dropdown('cbTahun',$arrThn, date('Y'),'id="cbTahun" class="form-control"');?></div>
	</div>
	</div>
</div>
<?	
			break;

		case "rekapjurnal":
		case "aktifitas":
		case "aset":
		case "cashflow":
		case "labarugi":
		case "perubahanDana":
		case "posisi":
		case "neraca":
		case "realisasi":
?>
<div class="row">
	<div class="col-md-8">
		<div class="form-group">
				<label for="nama" class="col-md-2   control-label">BULAN</label>
					<div class="col-sm-4"><?=form_dropdown('cbBulan',$arrBulan,date('m'),'id="cbBulan" class="form-control"');?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group">
			<label for="nama" class="col-md-2   control-label">TAHUN</label>
			<div class="col-sm-4"><?=form_dropdown('cbTahun',$arrThn, date('Y'),'id="cbTahun" class="form-control"');?></div>
	</div>
	</div>
</div>
<?			
if ($this->session->userdata('auth')->ID_CABANG==1){
	?>
	<div class="row">
		<div class="col-md-8">
			<div class="form-group"><label class="col-md-2 control-label">Wilayah</label>
				<div class="col-sm-8"><?=form_dropdown('wilayah',$wilayah,'','id="wilayah"  class="form-control"');?>			
				</div>
			</div>
		</div>
	</div>
<?	
	}
	
		break;


	case "cashregister":
	case "bank":
	case "bukubesar":
			?>
<div class="row">
	<div class="col-md-12">
		<div class="form-group col-md-6">
        <label class="col-md-3 control-label">Dari Tanggal</label>
        <div class=" col-md-4 input-group">        
        <input type="text" name="tgl1" id="tgl1" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask placeholder="yyyy-mm-dd" value="<?=date('Y-m-d')?>"/>
		<div class="input-group-addon">
        <span class="fa fa-calendar" id="bttgl1"></span>
        </div>
        </div><!-- /.input group -->	
		</div><!-- /.form group -->
		
		<div class="form-group  col-md-6">
		<label class="col-md-3 control-label">Sampai</label>
        <div class=" col-md-4 input-group">        
        <input type="text" name="tgl2" id="tgl2" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask placeholder="yyyy-mm-dd" value="<?=date('Y-m-d')?>"/>
		<div class="input-group-addon">
        <span class="fa fa-calendar" id="bttgl2"></span>
        </div>
        </div><!-- /.input group -->	
	 </div><!-- /.form group -->

	</div>
</div>		
		<?			
	if ($this->session->userdata('auth')->ID_CABANG==1){
		?>
		<div class="row">
			<div class="col-md-8">
				<div class="form-group"><label class="col-md-2 control-label">Wilayah</label>
					<div class="col-sm-8"><?=form_dropdown('wilayah',$wilayah,'','id="wilayah" class="form-control"');?>			
					</div>
				</div>
			</div>
		</div>
	<?	
		}
			break;
		default:
			break;
	}
	



switch ($jenis){
	case "cashregister":
		//echo var_dump($akunKas);
	?>       
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-md-2 control-label">Akun Kas </label>
			<div class="col-sm-6"><?=form_input(array('name'=>'nmakunKas','id'=>'nmakunKas','class'=>'form-control'));?><input type="hidden" name="akunKas" id="akunKas"></div>
		</div>
	</div>
</div>			
<?		break;
	case "bank":
	?>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-md-2 control-label">Akun Bank </label>
			<div class="col-sm-8"><?=form_input(array('name'=>'nmakunBank','id'=>'nmakunBank','class'=>'form-control'));?><input type="hidden" name="akunBank" id="akunBank" value=""></div>
		</div>
	</div>
</div>			
<?
			break;
		case "bukubesar":
			?>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-md-2 control-label">Nama/Kode Akun </label>
			<div class="col-sm-8"><?=form_input(array('name'=>'akunBB','id'=>'akunBB','class'=>'form-control'));?><input type="hidden" name="id_akunBB" id="id_akunBB"></div>
		</div>
	</div>
</div>
		<?
			break;
		default:
			break;
	}
	
?>
<!-- Button -->
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-md-2 control-label">&nbsp;</label>
			<div class="col-sm-8">
			<input type="hidden" name="display" id="display" value="0">
			<input type="hidden" name="sesLogin" id="sesLogin" value="<?php echo ($this->session->userdata('auth')->ID_CABANG==1?"center":"cabang")?>">
		<input type="submit" class="btn btn-primary" id="btOk" value="Lanjutkan">	</div>
		</div>
	</div>
</div>

<hr/>

<?php echo form_close();?>  

<script>
$(document).ready(function() {
	
	//if ('<?php echo $this->session->userdata('auth')->ID_CABANG?>' !='0'){
		$('#akunKas').removeAttr('disabled');
		$('#akunBank').removeAttr('disabled');

	//}
});
$( "#tgl1" ).datepicker({
		dateFormat: 'yy-mm-dd',
		onSelect: function(date) {
            
        }
		});
$("#bttgl1").click(function() {
		$("#tgl1").datepicker("show");
	});
$( "#tgl2" ).datepicker({
		dateFormat: 'yy-mm-dd',
		onSelect: function(date) {
           
        }
		});
$("#bttgl2").click(function() {
		$("#tgl2").datepicker("show");
	});
$("#akunBB").autocomplete({
	minLength: 2,
	source:
	function(req, add){
		if (<?=$this->session->userdata('auth')->ID_CABANG?>==1) {
			wilx=$('#wilayah').val();
		}else{
			wilx=<?=$this->session->userdata('auth')->ID_CABANG?>;
		}
		$.ajax({
			url: "<?php echo base_url('rekeningakun/getAkunBB'); ?>",
			dataType: 'json',
			type: 'POST',
			data: {keyword:req.term, wilayah:wilx},
			//data: req,
			success:   
			function(data){
				console.log(req);
				//console.log(data);
				if(data.response =="true"){
					add(data.message);
				}
			}
		});
	},
	select:
	function(event, ui) {		
		$("#id_akunBB").val(ui.item.id);
		
	}
});

$("#nmakunKas").autocomplete({
	minLength: 2,
	source:
	function(req, add){
		if (<?=$this->session->userdata('auth')->ID_CABANG?>==1) {
			wilx=$('#wilayah').val();
		}else{
			wilx=<?=$this->session->userdata('auth')->ID_CABANG?>;
		}
		$.ajax({
			url: "<?php echo base_url('rekeningakun/getCashRegister'); ?>",
			dataType: 'json',
			type: 'POST',
			data: {keyword:req.term, wilayah:wilx},
			//data: req,
			success:   
			function(data){
				console.log(req);
				//console.log(data);
				if(data.response =="true"){
					add(data.message);
				}
			}
		});
	},
	select:
	function(event, ui) {		
		$("#akunKas").val(ui.item.id);
		
	}
});
$("#nmakunBank").autocomplete({
	minLength: 2,
	source:
	function(req, add){
		if (<?=$this->session->userdata('auth')->ID_CABANG?>==1) {
			wilx=$('#wilayah').val();
		}else{
			wilx=<?=$this->session->userdata('auth')->ID_CABANG?>;
		}
		$.ajax({
			url: "<?php echo base_url('rekeningakun/getBankRegister'); ?>",
			dataType: 'json',
			type: 'POST',
			data: {keyword:req.term, wilayah:wilx},
			//data: req,
			success:   
			function(data){
				console.log(req);
				//console.log(data);
				if(data.response =="true"){
					add(data.message);
				}
			}
		});
	},
	select:
	function(event, ui) {		
		$("#akunBank").val(ui.item.id);
		
	}
});
$('#wilayah').change(function(){
	var func='';
	switch('<?php echo $jenis?>'){
		case 'cashregister':
			if ($(this).val()=='0'){
				$('#akunKas').attr('disabled', true);
			}else{
				$('#akunKas').removeAttr('disabled');
				
			}
			func='fillCashReg';
			break;
		case 'bukubesar':
			/*if ($(this).val()=='0'){
				$('#level1').attr('disabled', true);
				$('#level2').attr('disabled', true);
				$('#level3').attr('disabled', true);
			}else{
				$('#level1').removeAttr('disabled');
				$('#level2').removeAttr('disabled');
				$('#level3').removeAttr('disabled');
				
			}*/
			func='';
			break;
		case 'bank':
			if ($(this).val()=='0'){
				$('#akunBank').attr('disabled', true);
			}else{
				$('#akunBank').removeAttr('disabled');
				
			}
			func='fillBankReg';
			break;
		default:
			break;
	}
	
	//alert($(this).val());
	if ($(this).val()!='0'){
		switch('<?php echo $jenis?>'){
			case 'cashregister':
				$.ajax({
				url: "<?php echo base_url('rekeningakun/fillCashReg'); ?>",
				dataType: 'json',
				type: 'POST',
				data: {wilayah:$(this).val()},
				success: function(respon){
					$('#akunKas').find('option').remove().end();
					if (respon.status==1){
						var item = respon.data;
						for (opt=0;opt<item.length;opt++){
							$('#akunKas').append('<option value="'+item[opt].idacc+'" >'+item[opt].idacc+' - '+item[opt].nama+'</option>');
						}
						$('#akunKas').trigger('change');
							
						}
					}
				});

		break;
		case 'bank':
			$.ajax({
			url: "<?php echo base_url('rekeningakun/fillBankReg'); ?>",
			dataType: 'json',
			type: 'POST',
			data: {wilayah:$(this).val()},
			success: function(respon){
				$('#akunBank').find('option').remove().end();
				if (respon.status==1){
					var item = respon.data;
					for (opt=0;opt<item.length;opt++){
						$('#akunBank').append('<option value="'+item[opt].idacc+'" >'+item[opt].idacc+' - '+item[opt].nama+'</option>');
					}
					$('#akunBank').trigger('change');
						
					}
				}
			});
			break;
		}
	
	}
});


</script>
