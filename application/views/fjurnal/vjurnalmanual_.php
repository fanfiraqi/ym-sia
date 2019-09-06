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
				<!-- <th class="text-center" width="130">Tanggal </th> -->
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
							'id'=>'coa[]',
							'name'=>'coa[]',							
							'onfocus'=>'getIndex()',
							'class'=>'form-control clsCoa  col-sm-5 '
						);
						echo form_input($coa).'<input type="hidden" name="id_cab[]" id="id_cab[]">';
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
							'class'=>'form-control'
						);
						echo form_input($debet);
					?>
				</td>
				<td class="form-inline">
					<?php
						$kredit = array(
							'name'=>'kredit[]',
							'value'=>0,
							'class'=>'form-control'
						);
						echo form_input($kredit);
					?>
				</td>
				<td>
					
					<a href="javascript:void(0)" onclick="delrow(this)" class="btn btn-danger"><i class="fa fa-trash-o"></i></a> 
				</td>
			</tr>
		</tbody>
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
	function addRow(tableID) {

			var table = document.getElementById(tableID);

			var rowCount = table.rows.length;
			var row = table.insertRow(rowCount);

			var cell1 = row.insertCell(0);
			var element1 = document.createElement("input");
			element1.type = "checkbox";
			element1.name="chkbox[]";
			cell1.appendChild(element1);

			var cell2 = row.insertCell(1);
			cell2.innerHTML = rowCount + 1;

			var cell3 = row.insertCell(2);
			var element2 = document.createElement("input");
			element2.type = "text";
			element2.name = "txtbox[]";
			cell3.appendChild(element2);


		}

		function deleteRow(tableID) {
			try {
			var table = document.getElementById(tableID);
			var rowCount = table.rows.length;

			for(var i=0; i<rowCount; i++) {
				var row = table.rows[i];
				var chkbox = row.cells[0].childNodes[0];
				if(null != chkbox && true == chkbox.checked) {
					table.deleteRow(i);
					rowCount--;
					i--;
				}


			}
			}catch(e) {
				alert(e);
			}
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

	function delkasmasuk(idx){
			var pilih=confirm('Hapus Transaksi Jurnal = '+idx+ ' dilanjutkan ?');
			if (pilih==true) {
					$.ajax({
					type	: "POST",
					url		: "<?php echo base_url('jurnalmanual/delkasmasuk');?>",
					data	: "idx="+idx,
					timeout	: 3000,  
					success	: function(res){
						alert("data berhasil dihapus");
						window.location.reload();
						}
					
				});
			}
		}
	function editkasmasuk(obj){
		var id = $(obj).attr('data-id');
		$('#myformkel input[name="state"]').val(id);		
		$('#lbltitle').text('Edit Data Jurnal ');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('jurnalmanual/getkasmasuk');?>',
			data: {
				id:id
			},
			dataType: 'json',
			success: function(msg) {
					
				if(msg.status =='success'){
					console.log(msg.data);
					
					$('#lbltahun').val(msg.periode);
					//$('#lblnotrans').val(msg.data.notransaksi);
					$('#tglJurnal').val(msg.data.tanggal);	
					$('#textDebet').val(msg.data.akundebet);
					$('#textKredit').val(msg.data.akunkredit);
					$('#akundebet').val(msg.data.idperkdebet);
					$('#akunkredit').val(msg.data.idperkkredit);
					$('#ket').val(msg.data.keterangan);
					$('#jumlah').val(msg.data.jumlah);
					$('#bukti').val(msg.data.nobuktiref);
					if (msg.data.isanggaran=='on'){
						$('#ckOpsi').prop('checked', true);
					}else{
						$('#ckOpsi').prop('checked', false);
					}
					$("#divformkel").fadeSlide("show");
					
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				$().showMessage('Terjadi kesalahan.<br />'+	textStatus + ' - ' + errorThrown ,'danger',2000);
			},
			cache: false
		});
	}
	

	function getIndex(oo){
     var inputs = document.getElementById('coa');
     for (var i = 0 ; i<inputs.length ; i++){
        if(oo == inputs[i]){
          alert('Index of triggered element is: ' + i);
        }   
    }
	}

	$(".clsCoa").autocomplete({
	minLength: 2,
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
		$(".clsCoa").val(ui.item.id);
		$(".id_cab").val(ui.item.id_cab);
		
	}
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

