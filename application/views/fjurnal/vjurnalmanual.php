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
			<td><input type="text" id="coa_1" name="coa[]"  class="form-control clsCoa  col-sm-5">
			<input type="hidden" name="idcoa[]" id="idcoa_1">
			<input type="hidden" class="id_cab" name="idcab[]" id="idcab_1"></td>
			<td ><input type="text" id="penjelasan_1" name="penjelasan[]"  class="form-control col-sm-5"></td>
			<td class="form-inline"><input tYpe="text" id="debet_1" name="debet[]"  class="form-control debet" value="0"></td>
			<td class="form-inline"><input tYpe="text" id="kredit_1" name="kredit[]"  class="form-control kredit" value="0"></td>
			<td><a href="javascript:void(0)" onclick="delrow(this)" class="btn btn-danger"><i class="fa fa-trash-o"></i></a></td>
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
		<div class="col-sm-8">
		<input type="hidden" name="tahun" id="tahun" value="<?=$periode?>">
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

<div class="row form-horizontal">
	<div class="col-sm-6">
		<div class="form-group">
			<label for="cabang" class="col-sm-4 control-label">Cabang </label>
			<div class="col-sm-8">
			<?php
				if ($this->session->userdata('auth')->id_cabang=="1"){
					echo form_dropdown('cabangfilter',$cabang,'1','id="cabangfilter" class="form-control" ');
				}else{
					echo '<input type="hidden" name="cabangfilter" id ="cabangfilter" value="'.$cabang->id_cabang.'"/>';
					echo '<label  class="col-sm-4 control-label">'.$cabang->kota.'</label>';
				}
				?>
				
			</div>
		</div>
	</div>	
	<div class="col-md-6">
		<div class="form-group">
				<label for="nama" class="col-md-4   control-label">Jenis Jurnal</label>
					<div class="col-sm-8"><?=form_dropdown('cbjenis_filter',$arrJenisFilter,'','id="cbjenis_filter" class="form-control"');?></div>
		</div>
	</div>
</div>	

<div class="row form-horizontal">
	<div class="col-md-6">

		<div class="form-group " >
        <label class="col-sm-4 control-label" >Dari Tanggal</label>
        <div class=" col-sm-8 input-group" >        
        <input type="text" name="tgl1" id="tgl1" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask placeholder="yyyy-mm-dd" value="<?=date('Y-m-d')?>"/>
		<div class="input-group-addon">
        <span class="fa fa-calendar" id="bttgl1"></span>
        </div>
        </div><!-- /.input group -->	
		</div><!-- /.form group -->
	</div>	
	<div class="col-md-6">
		<div class="form-group  form-inline">
		<label class="col-sm-4 control-label">Sampai</label>
        <div class=" col-sm-6 input-group">        
        <input type="text" name="tgl2" id="tgl2" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask placeholder="yyyy-mm-dd" value="<?=date('Y-m-d')?>"/>
		<div class="input-group-addon">
        <span class="fa fa-calendar" id="bttgl2"></span>
        </div>
        </div><!-- /.input group -->	
		<input type="button" class="btn btn-primary" id="btcari" value="Cari">
		 </div><!-- /.form group -->
		

		
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
				aoData.push( { "name": "tanggal1", "value": $('#tgl1').val() }, { "name": "tanggal2", "value": $('#tgl2').val() }, { "name": "cabang", "value": $('#cabangfilter').val() }, { "name": "jenis", "value": $('#cbjenis_filter').val() })
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

		
	$(document).on('focus', '.clsCoa', handleAutocomplete);

    });
function getId(element){
    var id, idArr;
    id = element.attr('id');
    idArr = id.split("_");
    return idArr[idArr.length - 1];
}

function handleAutocomplete(){
	currentEle = $(this);
	rowNo = getId(currentEle);
	var jenis=$("#cbjenis").val();
	var myurl="";
	if (jenis=="kasmasuk" || jenis=="kaskeluar"){
		myurl="getAkunAll";
	}else{
		myurl="getAkunNonKas";
	}
	$(this).autocomplete({
		autoFocus: true,	      	
        minLength: 1,
        source: 
		function( req, add ) {	 
            $.ajax({
                url:'<?php echo base_url('jurnalmanual/'); ?>'+'/'+myurl,
                method: 'POST',
                dataType: 'json',
                data: req,
                success: function(data){
                    if(data.response =="true"){
						add(data.message);
					}
                }
            });
        },
       
        select: function( event, ui ) {
           $(this).val(ui.item.id);
            $("#idcab_"+rowNo ).val(ui.item.id_cab);
            $("#idcoa_"+rowNo ).val(ui.item.id);
			
        }		      	
    });

}

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
	var coa_kredit="";

	$('#tblembur tbody').find('input.kredit').each(function(){		
		vkredit=vkredit+parseFloat($(this).val());
		if ($(this).val()>0 && $("#cbjenis").val()=="kaskeluar"){
			//get coa kredit kaskeluar
			var noidx=getId($(this));
			coa_kredit="idcoa_"+noidx;
		}
		
	});
	$('#txtSumKredit').val(parseFloat(vkredit));
	if (parseFloat($('#txtSumDebet').val())==parseFloat($('#txtSumKredit').val()) ) {
		$("#lbl_balance").html('Balanced');
		$("#btsimpankel").removeAttr("disabled");
	}else{
		$("#lbl_balance").html('Not Balanced');
		$("#btsimpankel").attr("disabled","disabled");
	}
	
	if ($("#cbjenis").val()=="kaskeluar"){	//saldo kas+bank cabang /pusat
		var saldo;
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('jurnalmanual/getSaldoKas');?>',			
			dataType: 'json',
			data : {idacc : $("#"+coa_kredit).val()},
			success: function(msg) {
				if(msg.status =='success'){
					saldo=msg.jml;
					if (parseFloat(vkredit)< saldo )
					{
						$('#btsimpankel').removeAttr('disabled');
					}else{
						bootbox.alert("Saldo Kas tidak mencukupi, posisi saldo akun saat ini : Rp. "+saldo);
						$('#btsimpankel').attr('disabled', true);
					}
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				$().showMessage('Terjadi kesalahan.<br />'+	textStatus + ' - ' + errorThrown ,'danger',2000);
			},
			cache: false
		});
	}
	
});

function addrow(){
	 var tbl=$('#tblembur tbody');
	 var rowCount=$('#tblembur tbody tr').length+1;
	 if ($("#cbjenis").val()=="jurnalumum" || $("#cbjenis").val()=="susut") {
		 if (rowCount<=2)	 {
			  tbl.append(formHtml(rowCount));
		 }else{
			 bootbox.alert("Jurnal NonKas/Bank atau Jurnal Penyusutan hanya diperbolehkan sepasang");
		 }
	 }else {
		 tbl.append(formHtml(rowCount));
	 }

}
function formHtml(idx){
	var html="";
	html+='<tr class="info">';
	html+='		<td><input type="text" id="coa_'+idx+'" name="coa[]"  class="form-control clsCoa  col-sm-5">';
	html+='		<input type="hidden" class="id_cab" name="idcab[]" id="idcab_'+idx+'"><input type="hidden" name="idcoa[]" id="idcoa_'+idx+'"></td>';
	html+='		<td ><input type="text" id="penjelasan_'+idx+'" name="penjelasan[]"  class="form-control col-sm-5"></td>';
	html+='		<td class="form-inline"><input tYpe="text" id="debet_'+idx+'" name="debet[]"  class="form-control debet" value="0"></td>';
	html+='		<td class="form-inline"><input tYpe="text" id="kredit_'+idx+'" name="kredit[]"  class="form-control kredit" value="0"></td>';
	html+='		<td><a href="javascript:void(0)" onclick="delrow(this)" class="btn btn-danger"><i class="fa fa-trash-o"></i></a></td>';
	html+='		</tr>';
	return  html;
}


function delrow(obj){	
	if($('#tblembur tbody').children().length > 1){
		$(obj).parent().parent().remove();
	}
}


$('#btcreate').click(function(){
		$("#divformkel").fadeSlide("show");
		$('#state').val('add');
		$('#lbltitle').text('Tambah Posting Jurnal');		
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
					$().showMessage('Data transaksi jurnal berhasil disimpan.','success',3000);
					setInterval(window.location.reload(), 6000);
					//$("#divformkel").fadeSlide("hide");
					//$('#dataTables').dataTable().fnReloadAjax();
					
				} else {
					$().showMessage('Terjadi kesalahan. Data gagal disimpan.','danger',700);
					$("#errorHandler").html(msg.errormsg).show();
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				bootbox.alert("Terjadi kesalahan. Data gagal disimpan."+	textStatus + " - " + errorThrown );
			},
			cache: false
		});
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

function deljurnal(idx){
			var pilih=confirm('Hapus Transaksi Jurnal = '+idx+ ' dilanjutkan ?');
			if (pilih==true) {
					$.ajax({
					type	: "POST",
					url		: "<?php echo base_url('jurnalmanual/deljurnal');?>",
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

