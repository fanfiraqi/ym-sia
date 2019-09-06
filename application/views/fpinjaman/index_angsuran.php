<!-- <p><?php echo anchor('pinjaman/create','Pembayaran Angsuran',array('id'=>'btsubmit','class'=>'btn btn-primary'));?> </p>
 -->
 <div class="panel panel-default">
 <div class="panel-heading">DETIL DATA PINJAMAN </div>
 <div class="panel-body form-horizontal">
 <!-- <?php echo form_open('angsuran/create',array('class'=>'form-horizontal','id'=>'myform'));?> -->
<div class="row">
	<div class="col-md-6">
		<div class="form-group"><label class="col-sm-4 control-label">KETIK NAMA PEMINJAM</label>
			<div class="col-sm-8"><?=form_input(array('name'=>'nama','id'=>'nama','class'=>'form-control'));?><input type="hidden" name="id_tr" id="id_tr"></div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group"><label class="col-sm-4 control-label">JUMLAH PINJAMAN</label>
			<div class="col-sm-8"><?=form_input(array('name'=>'jumlah','id'=>'jumlah','readonly'=>'true','class'=>'form-control'));?><input type="hidden" name="nik" id="nik"></div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="form-group"><label class="col-sm-4 control-label">KOTA CABANG</label>
			<div class="col-sm-8"><?=form_input(array('name'=>'cabang','id'=>'cabang','readonly'=>'true','class'=>'form-control'));?></div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group"><label class="col-sm-4 control-label">ANGSURAN</label>
			<div class="col-sm-8"><?=form_input(array('name'=>'lama','id'=>'lama','readonly'=>'true','class'=>'form-control'));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="form-group"><label  class="col-sm-4 control-label">TANGGAL PINJAM</label>
			<div class="col-sm-8"><?=form_input(array('name'=>'tanggal','id'=>'tanggal','readonly'=>'true','class'=>'form-control'));?></div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group"><label class="col-sm-4 control-label">STATUS ANGSURAN</label>
			<div class="col-sm-8"><?=form_input(array('name'=>'status','id'=>'status','readonly'=>'true','class'=>'form-control'));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="form-group"><label  class="col-sm-4 control-label">KEPERLUAN</label>
			<div class="col-sm-8"><?=form_input(array('name'=>'keperluan','id'=>'keperluan','readonly'=>'true','class'=>'form-control'));?></div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group"><label  class="col-sm-4 control-label">JENIS PEMINJAM</label>
			<div class="col-sm-8"><?=form_input(array('name'=>'jnspeminjam','id'=>'jnspeminjam','readonly'=>'true','class'=>'form-control'));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
	<div class="form-group"><label  class="col-sm-4 control-label"></label>
			<div class="col-sm-8"><button name="btnbayar" id="btnbayar" data-base="" data-url="" data-id="" onclick="detail(this)" class="btn btn-act btn-success" disabled>Bayar Angsuran <i class="fa fa-gear" title="Validasi"></i></button>			
			</div>
		</div>
	</div>
</div>

<!-- <?php echo form_close();?> -->
</div>
 </div>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="dataTables-example">
		<thead>
			<tr>
				<th>ID</th>
				<th>CICILAN KE</th>
				<th>TGL BAYAR</th>
				<th>JUMLAH</th>
				<th>STATUS</th>
				<!-- <th>Action</th> -->
			</tr>
		</thead>
		<tbody>
			
		</tbody>
	</table>
</div>
<!-- /.table-responsive -->
<script>
    $(document).ready(function() {      
    });

	$("#nama").autocomplete({
	minLength: 2,
	source:
	function(req, add){
		$.ajax({
			url: "<?php echo base_url('angsuran/getView'); ?>",
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
		var angs=parseFloat(ui.item.jumlah)/parseFloat(ui.item.lama);
		$("#id_tr").val(ui.item.id);  
		$("#cabang").val(ui.item.cabang);
		$("#keperluan").val(ui.item.keperluan);
		$("#tanggal").val(ui.item.tgl_pinjam);	
		$("#jumlah").val(ui.item.jumlah);	
		$("#lama").val(angs + ' x '+ui.item.lama + ' Kali');	
		$("#status").val(ui.item.status);	
		$("#jnspeminjam").val(ui.item.jns_peminjam);	
		if (ui.item.status=='Belum Lunas'){
			$("#btnbayar").prop('disabled', false);
		}else{
			$("#btnbayar").prop('disabled', true);
		}
		$("#btnbayar").attr('data-id', ui.item.id);
		$("#btnbayar").attr('data-url', "<?php echo base_url('angsuran/saveAngsuran');?>/"+ui.item.id);
		$("#btnbayar").attr('data-base', "<?php echo base_url()?>");
		loadList(ui.item.id);
	}
});


function loadList(pid){
	//alert(pid);
	 $('#dataTables-example').dataTable({
			"bProcessing": true,
			"bServerSide": true,
			"iDisplayLength": 25,
			"aoColumns": [
				{"mData": "ID" },
				{"mData": "CICILAN"},
				{"mData": "TGL"},
				{"mData": "JUMLAH"},				
				{"mData": "STATUS" }
				//{"mData": "ACTION", "sortable":false }
			],
			"sAjaxSource": "<?php echo base_url('angsuran/json_data');?>/"+pid
		});
}

function detail(obj){
		var id = $(obj).attr('data-id');
		$.ajax({
			url: "<?php echo base_url('angsuran/view/'); ?>/"+id,
			dataType: 'html',
			type: 'POST',
			data: {ajax:'true'},
			success:
				function(data){
					bootbox.dialog({
					  message: data,
					  title: "Bayar Cicilan/Angsuran",
					  buttons: {
						success: {
						  label: "Bayar",
						  className: "btn-success",
						  callback: function() {
							bayarAngsuran(obj);
						  }
						},
						main: {
						  label: "Cancel",
						  className: "btn-warning",
						  callback: function() {
							console.log("Primary button");
						  }
						}
					  }
					});
				}
		});
		
	}

function lunasi(idH, idC, jmlB){
			var pilih=confirm('Angsuran = '+idH+ ' cicilan ke = '+idC+' dilunasi ?');
			if (pilih==true) {
					$.ajax({
					type	: "POST",
					url		: "<?php echo base_url('angsuran/setLunasAngsuran');?>",
					data	: "idH="+idH+"&idC="+idC+"&jmlB="+jmlB,
					timeout	: 3000,  
					success	: function(res){
						alert("Angsuran berhasil diupdate");
						window.location.reload();
						}
					
				});
			}
		}
</script>