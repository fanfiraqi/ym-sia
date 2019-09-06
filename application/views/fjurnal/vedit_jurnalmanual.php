<div id="errorHandler" class="alert alert-danger no-display"></div>
<?php 
echo form_open("jurnalmanual/edit",array('class'=>'form-horizontal','id'=>'myformkel'));
?>

<div class="panel panel-default card-view"><div class="panel-heading"><label id="lbltitle">Edit Posting Jurnal</label></div>
		<div class="panel-body">	
<div class="row">
	<div class="col-md-6">
		<div class="form-group">
				<label for="nama" class="col-md-4   control-label">Jenis Jurnal</label>
					<div class="col-sm-8"><?php echo $arrJenis[$row->sumber_data]?></div>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
			<label for="cabang" class="col-sm-4 control-label">Cabang </label>
			<div class="col-sm-8"><input type="hidden" name="cabang" id ="cabang" value="<?php echo $row->id_cab?>"/>
					<label  class="col-sm-4 control-label"><?php echo $cabang->kota?></label></div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-6">
		<div class="form-group">
        <label class="col-sm-4 control-label">Tanggal Jurnal	</label>
        <div class=" col-sm-4 input-group">        
        <input type="text" name="tglJurnal" id="tglJurnal" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask placeholder="yyyy-mm-dd" value="<?php echo $row->tanggal?>"/>
		<div class="input-group-addon">
        <span class="fa fa-calendar" id="bttgl"></span>
        </div>
        </div><!-- /.input group -->
        </div><!-- /.form group -->
	
	</div>
	<div class="col-md-6">
		<div class="form-group"><label class="col-sm-4 control-label">No. Kuitansi/BKM/BBM</label>
			<div class="col-sm-8"><?=form_input(array('name'=>'bukti','id'=>'bukti','class'=>'form-control', 'value'=>$row->nobuktiref));?></div>
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
			<td><input type="text" id="coa_1" name="coa[]"  class="form-control clsCoa  col-sm-5" value="<?php echo $resAkunDebet->nama?>">
			<input type="hidden" name="idcoa[]" id="idcoa_1" value="<?php echo $resAkunDebet->idacc?>">
			<input type="hidden" class="id_cab" name="idcab[]" id="idcab_1" value="<?php echo $row->id_cab?>"></td>
			<td ><input type="text" id="penjelasan_1" name="penjelasan[]"  class="form-control col-sm-5" value="<?php echo $row->penanda?>"></td>
			<td class="form-inline"><input tYpe="text" id="debet_1" name="debet[]"  class="form-control debet" value="<?php echo $row->jumlah?>"></td>
			<td class="form-inline"><input tYpe="text" id="kredit_1" name="kredit[]"  class="form-control kredit" value="0"></td>
			<td></td>
			</tr>
			<tr class="info">
			<td><input type="text" id="coa_2" name="coa[]"  class="form-control clsCoa  col-sm-5" value="<?php echo $resAkunKredit->nama?>">
			<input type="hidden" name="idcoa[]" id="idcoa_2" value="<?php echo $resAkunKredit->idacc?>">
			<input type="hidden" class="id_cab" name="idcab[]" id="idcab_2" value="<?php echo $row->id_cab?>"></td>
			<td ><input type="text" id="penjelasan_2" name="penjelasan[]"  class="form-control col-sm-5" value="<?php echo $row->penanda_kredit?>"></td>
			<td class="form-inline"><input tYpe="text" id="debet_2" name="debet[]"  class="form-control debet" value="0"></td>
			<td class="form-inline"><input tYpe="text" id="kredit_2" name="kredit[]"  class="form-control kredit" value="<?php echo $row->jumlah?>"></td>
			<td></td>
			</tr>
		</tbody>
		<tfoot>
		<tr class="info">
		<th  colspan="2"></th>
				<th class="text-center" ><input type="text" name="txtSumDebet" id="txtSumDebet"  class="form-control" readonly value="<?php echo $row->jumlah?>" style="font-weight:bold;"></th>
				<th class="text-center" ><input type="text" name="txtSumKredit" id="txtSumKredit"   class="form-control" readonly value="<?php echo $row->jumlah?>" style="font-weight:bold;"></th>
				<th class="text-center"  width="48" ><label class="col-sm-4 control-label" id="lbl_balance" style="font-wight:bold"><?php echo "Balanced"?></label></th>
				</tr>
		</tfoot>
	</table>
		
	</div>
</div>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="form-group"><label class="col-sm-4 control-label">Uraian / Memo</label>
			<div class="col-sm-8"><?=form_textarea(array('name'=>'ket','id'=>'ket','class'=>'form-control', 'value'=>$row->keterangan));?></div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-6"><label class="col-sm-4 control-label"></label>
		<div class="col-sm-8">
		<input type="hidden" name="sumber_data" id="sumber_data" value="<?php echo $row->sumber_data?>">
		<input type="hidden" name="jenis" id="jenis" value="<?php echo $row->jenis?>">
		<input type="hidden" name="state" id="state" value="edit">
		<input type="hidden" name="notrans" id="notrans" value="<?php echo $row->notransaksi?>">
		<input type="button" class="btn btn-primary" id="btsimpankel" value="Update">
		<button type="button" class="btn btn-default" id="btcancelkel">Batal</button>
		</div>
	</div>
</div> 

</div>
</div>




<?php echo form_close();?>  


<script>
    $(document).ready(function() {
     	
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
	
	$(this).autocomplete({
		autoFocus: true,	      	
        minLength: 1,
        source: 
		function( req, add ) {	 
            $.ajax({
                url:'<?php echo base_url('jurnalmanual/getAkunAll'); ?>',
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



	$('#btcancelkel').click(function(){
		backTo("<?php echo base_url('jurnalmanual/index')?>");return false;
	});
/*$('#myformkel').submit(function(event) {
	$(this).saveForm('<?php echo base_url('jurnalmanual/edit/'.$row->notransaksi);?>','<?php echo base_url('jurnalmanual');?>');
	event.preventDefault();
});*/
	$('#btsimpankel').click(function(){		
		//event.preventDefault();
		var form_data = $('#myformkel').serialize();
		$().showMessage('Sedang diproses.. Harap tunggu..');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('jurnalmanual/edit');?>',
			data: form_data,				
			dataType: 'json',
			success: function(msg) {
				 $("#errorHandler").html('&nbsp;').hide();
				 console.log(msg);
				if(msg.status =='success'){
					$().showMessage('Data transaksi jurnal berhasil disimpan.','success',3000);
					setInterval(window.location.href="<?php echo base_url('jurnalmanual/index')?>", 6000);
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

