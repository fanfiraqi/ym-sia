<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="dataTables-example">
		<thead>
			<tr>
				<th>Deskripsi</th>
				<th>Shortcode</th>
				<th>Nama</th>
				<th>No. Rekening</th>
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
        $('#dataTables-example').dataTable({
			"bProcessing": true,
			"bServerSide": true,
			"sSearch":false,
			"aoColumns": [
				{"mData": "DESCRIPTION" },
				{"mData": "NAME" },
				{"mData": "VALUE1" },
				{"mData": "VALUE2" }
			],
			"sAjaxSource": "<?php echo base_url('setting/json_data');?>"
		});
    });
    </script>