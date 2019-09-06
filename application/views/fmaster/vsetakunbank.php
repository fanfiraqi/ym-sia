<div id="errorHandler" class="alert alert-danger no-display"></div>

<?php echo form_open(null,array('class'=>'form-horizontal','id'=>'frmSetAkun'));?>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="dataTables-list">
		<thead>
			<tr>
				<th>ID</th>
				<th>NAMA BANK</th>
				<th>NO. REKENING</th>
				<th>NAMA REKENING</th>
				<th>COA BANK</th>
			</tr>
		</thead>
		<tbody>
<?	
$i=1;
foreach ($row as $rs){
	echo "<tr>";
	echo "<td>".$i."</td>";
	echo "<td>".$rs->name."</td>";
	echo "<td>".$rs->account_number."</td>";
	echo "<td>".$rs->account_name."</td>";
	echo '<td><input type="hidden" name="txtid[]" id="txtid[]" value="'.$rs->id.'"><input type="text" name="txtidacc[]" id="txtidacc[]" value="'.$rs->idacc.'"></td>';

	echo "</tr>";
	$i++;
}

?>			
		</tbody>
	</table>
</div><br>
<div class="row">
	<div class="col-md-12">
		<input type="button" class="btn btn-primary" id="btSimpan" value="Simpan">	
		
	</div>
</div>
</form>
<!-- /.table-responsive -->
<script>
    $(document).ready(function() {
       
    });



	$('#btSimpan').click(function(){		
		var form_data = $('#frmSetAkun').serialize();
		$().showMessage('Sedang diproses.. Harap tunggu..');
		$.ajax({
			type: 'POST',
			url: "<?php echo base_url('setakun/saveAkunBank');?>",
			data: form_data,				
			dataType: 'json',
			success: function(msg) {
				 $("#errorHandler").html('&nbsp;').hide();
				 console.log(msg);
				if(msg.status =='success'){
					$().showMessage('Data berhasil disimpan.','success',1000);
					window.location.reload();				
				} else{
					bootbox.alert("Terjadi kesalahan. "+ msg.errormsg+". Data gagal disimpan.");				
					$("#errorHandler").html(msg.errormsg).show();
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				bootbox.alert("Terjadi kesalahan. Data gagal disimpan."+	textStatus + " - " + errorThrown );
			},
			cache: false
		});
	});

    </script>