<div class="control-group"><a href="javascript:void(0)" id="btcreate" class="btn btn-primary">Tambah </a></div><br>
<div id="errorHandler" class="alert alert-danger no-display"></div>
<?php 
echo form_open(null,array('class'=>'form-horizontal','id'=>'myformkel'));
?>
<div id="divformkel" class="no-display">
<div class="panel panel-default card-view"><div class="panel-heading"><label id="lbltitle">Tambah Posting Jurnal</label></div>
		<div class="panel-body">	
<div class="row">
	<div class="col-md-6">
		<div class="form-group">
				<label for="nama" class="col-md-4   control-label">Jenis Jurnal</label>
					<div class="col-sm-8"><?=form_dropdown('cbjenis',$arrJenis,'','id="cbjenis" class="form-control"');?></div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group"><label class="col-sm-4 control-label">Periode Aktif</label>
			<div class="col-sm-4"><label id="lbltahun"><?=$periode?></label></div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-6">
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
	<div class="col-md-6">
		<div class="form-group"><label class="col-sm-4 control-label">No. Kuitansi/BKM/BBM</label>
			<div class="col-sm-8"><?=form_input(array('name'=>'bukti','id'=>'bukti','class'=>'form-control'));?></div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12">
		<div class="table-responsive">
	<table class="table table-bordered" id="tblembur">
		<thead>
			<tr>
				<th  class="text-center  col-sm-5">COA</th>
     			<th  class="text-center  col-sm-5">Penjelasan</th>
				<th class="text-center" >Debet</th>
				<th class="text-center" >Kredit</th>
				<th class="text-center"  width="48" ></th>
			</tr>
			
		</thead>
		<tbody>
			<tr class="info">
				
				<td>
					<?php
						$coa = array(
							'id'=>'coa_1',
							'name'=>'coa[]',							
							'onkeyup'=>"myauto('coa_1', 1)",
							'class'=>'form-control clsCoa  col-sm-5'
						);
						echo form_input($coa).'<input type="hidden" class="id_cab" name="id_cab[]" id="id_cab_0">';
					?>
				</td>
				<td >
					<?php
						$penjelasan = array(
							'name'=>'penjelasan[]',
							'class'=>'form-control  col-sm-5'
						);
						echo form_input($penjelasan);
					?>
				</td>
				<td class="form-inline">
					<?php
						$debet = array(
							'name'=>'debet[]',
							'value'=>0,
							'class'=>'form-control debet' 
						);
						echo form_input($debet);
					?>
				</td>
				<td class="form-inline">
					<?php
						$kredit = array(
							'name'=>'kredit[]',
							'value'=>0,
							'class'=>'form-control kredit'
						);
						echo form_input($kredit);
					?>
				</td>
				<td>
					
					<a href="javascript:void(0)" onclick="delrow(this)" class="btn btn-danger"><i class="fa fa-trash-o"></i></a> 
				</td>
			</tr>
		</tbody>
		<tfoot>
		<tr class="info">
		<th  colspan="2"></th>
				<th class="text-center" ><input type="text" name="txtSumDebet" id="txtSumDebet"  class="form-control" readonly value="0" style="font-weight:bold;"></th>
				<th class="text-center" ><input type="text" name="txtSumKredit" id="txtSumKredit"   class="form-control" readonly value="0" style="font-weight:bold;"></th>
				<th class="text-center"  width="48" ><label class="col-sm-4 control-label" id="lbl_balance" style="font-wight:bold"></label></th>
				</tr>
		</tfoot>
	</table>
		<div class="pull-right"><a href="javascript:void(0)" onclick="addrow()" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Baris</a></div>
	</div>
</div>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="form-group"><label class="col-sm-4 control-label">Uraian / Memo</label>
			<div class="col-sm-8"><?=form_textarea(array('name'=>'ket','id'=>'ket','class'=>'form-control'));?></div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-6"><label class="col-sm-4 control-label"></label>
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

<div class="row">
	<div class="col-md-12">
		<div class="form-group col-md-4" >
        <label class="col-sm-5 control-label" >Dari Tanggal</label>
        <div class=" col-sm-7 input-group" >        
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
			"sAjaxSource": "<?php echo base_url('jurnalmanual/json_data');?>"
		});

		

    });
/*$(document).on('keyup', '.clsCoa', function(){
	$(this).autocomplete();
});*/

$(document).on('change', '.debet', function(){
	//var values = $("input[name='debet[]']").map(function(){return $(this).val();}).get();
	var values=0;
	$('#tblembur tbody').find('input.debet').each(function(){		
		values=values+parseFloat($(this).val());		
	});
	$('#txtSumDebet').val(values);
	if (parseFloat($('#txtSumDebet').val())==parseFloat($('#txtSumKredit').val()) ) {
		$("#lbl_balance").html('Balanced');
		$("#btsimpankel").removeAttr("disabled");
	}else{
		$("#lbl_balance").html('Not Balanced');
		$("#btsimpankel").attr("disabled","disabled");
	}

});

$(document).on('change', '.kredit', function(){
	var vkredit=0;
	$('#tblembur tbody').find('input.kredit').each(function(){		
		vkredit=vkredit+parseFloat($(this).val());		
	});
	$('#txtSumKredit').val(parseFloat(vkredit));
	if (parseFloat($('#txtSumDebet').val())==parseFloat($('#txtSumKredit').val()) ) {
		$("#lbl_balance").html('Balanced');
		$("#btsimpankel").removeAttr("disabled");
	}else{
		$("#lbl_balance").html('Not Balanced');
		$("#btsimpankel").attr("disabled","disabled");
	}
});

function addrow(){
	var idk=$('#tblembur tbody').children().length;
	//alert(idk);
	$( ".tgllembur" ).datepicker('destroy');
	//$( "input.clsCoa" ).autocomplete("destroy");


	var clone = $('#tblembur tbody tr:first-child').clone(true);	
	clone.find('input').val('');
	clone.find('td').removeClass('has-error');
	clone.appendTo('#tblembur tbody');
	
	
	
	var k=1;

	$('#tblembur tbody').find('input.clsCoa').each(function(){		
		$(this).prop('id','coa_'+ k );
		$(this).prop("onkeyup","myauto('coa_"+ k +"', "+k+")");	
		k++;
	});
	
	var p=1;
	$('#tblembur tbody').find('input.id_cab').each(function(){		
		$(this).attr('id','id_cab_'+p);
		p++;
	});
	
	var m=1;
	$('#tblembur tbody').find('input.debet').each(function(){
		$(this).attr('id','debet_'+ m );
		if (m==(idk+1))
		{
			$(this).val('0');
		}		
		m++;
	});
	var t=1;
	$('#tblembur tbody').find('input.kredit').each(function(){
		$(this).attr('id','kredit_'+ t );
		if (t==(idk+1))
		{
			$(this).val('0');
		}		
		t++;
	});
	$( ".tgllembur" ).datepicker({
		minDate: 'today',
		dateFormat: 'dd-mm-yy'
	});

}

function delrow(obj){	
	if($('#tblembur tbody').children().length > 1){
		$(obj).parent().parent().remove();
	}
}

function myauto(oName, idx){
	
	var t=1;
	$('#tblembur tbody').find('input.clsCoa').each(function(){
		//$(this).attr('id','clsCoa_'+ t );
		if (t != idx){
			$(this).autocomplete("destroy");
		}		
		t++;
	});

	//$("#"+oName).autocomplete({
	$("input[type=text]").autocomplete({
		minLength: 0,
		source:
		function(req, add){
			$.ajax({
				url: "<?php echo base_url('jurnalmanual/getAkunAll'); ?>",
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
			$(this).val(ui.item.id);			
			//$("#id_cab_"+idx).val(ui.item.id_cab);
			
		}
	});
}

$( ".tgllembur" ).datepicker({
	minDate: 'today',
	dateFormat: 'dd-mm-yy'
});
$('#btcreate').click(function(){
		$("#divformkel").fadeSlide("show");
		$('#state').val('add');
		$('#lbltitle').text('Tambah Posting Jurnal');
			$('#textDebet').removeAttr('disabled');
		$('#textKredit').removeAttr('disabled');	
		$('#akundebet').removeAttr('disabled');
		$('#akunkredit').removeAttr('disabled');
		$('#myformkel').trigger("reset");
		$('#myformkel').reset();		
		
	});
	$( "#btcari" ).click(function(){
		$('#dataTables').dataTable().fnReloadAjax();
	});
	

	$('#btcancelkel').click(function(){
		$("#divformkel").fadeSlide("hide");
	});
	
	$('#btsimpankel').click(function(){		
		
		var form_data = $('#myformkel').serialize();
		$().showMessage('Sedang diproses.. Harap tunggu..');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('jurnalmanual/savePost');?>',
			data: form_data,				
			dataType: 'json',
			success: function(msg) {
				 $("#errorHandler").html('&nbsp;').hide();
				 console.log(msg);
				if(msg.status =='success'){
					$().showMessage('Data transaksi jurnal berhasil disimpan.','success',1000);
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


$( "#tgl1" ).datepicker({
		dateFormat: 'yy-mm-dd',
		onSelect: function(date) {
            //alert(date)
        }
		});

$("#bttgl1").click(function() {
		$("#tgl1").datepicker("show");
	});

$( "#tgl2" ).datepicker({dateFormat: 'yy-mm-dd'});
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

