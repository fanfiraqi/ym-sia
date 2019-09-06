
<div class="panel panel-default card-view">
        <div class="panel-heading">
			<div class="pull-left"><h6 class="panel-title txt-dark"><label id="lbltitle">View Hierarki Akun</label></h6></div>
			<div class="clearfix"></div>
		</div>
	<div class="panel-wrapper collapse in">
	<div  class="panel-body row pa-0">
	<br>
	<div class="row">
		<div class="col-md-8">
			<div class="form-group">
				<label for="sex" class="col-sm-4 control-label">Pilih Akun Wilayah/Cabang</label>
				<div class="col-sm-4">
					<?php echo form_dropdown('cabang_view',$cabang,'','id="cabang_view" class="form-control"');?>
				</div>
			</div>
		</div>
	</div>

	</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
	<div class="table-responsive">
		<table  class="table table-striped table-bordered table-hover responsive" id="dataTables_view"> 
			<thead>
			<tr><th>Kode Akun</th>
			<th > Nama Perkiraan</th> 
			<th>Kelompok</th>
			<th>Level</th>
			<th>ID Parent</th>
			<th>Saldo Awal</th></tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
	</div>
</div>



<script>
    $(document).ready(function() {
        $('#dataTables_view').dataTable({
			"bProcessing": true,
			"bServerSide": true,
			"iDisplayLength": 25,
			"bJQueryUI": true,			
			"sPaginationType": "full_numbers",
			"fnServerParams": function ( aoData ) {
				aoData.push({ "name": "cabang", "value": $('#cabang_view').val()} 
				);
			},
			"aoColumns": [
				{"mData": "IDACC" },
				{"mData": "NAMA" },
				{"mData": "KELOMPOK" },
				{"mData": "LEVEL" },
				{"mData": "IDPARENT" },
				{"mData": "SALDO_AWAL" , "sortable":false},
			],
			"sAjaxSource": "<?php echo base_url('rekeningakun/json_data_view');?>",
			"autoWidth": true
		});

		//tbl.columns.adjust().draw();

    });
	$('#cabang_view').change(function(){
		$('#dataTables_view').dataTable().fnReloadAjax();

	});
</script>