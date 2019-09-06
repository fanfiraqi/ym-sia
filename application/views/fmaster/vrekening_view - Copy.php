<div class="row">
	<div class="col-md-8">
		<div class="form-group">
			<label for="sex" class="col-sm-4 control-label">View Akun Wilayah/Cabang</label>
			<div class="col-sm-4">
				<?php echo form_dropdown('cabang_view',$cabang,'','id="cabang_view" class="form-control"');?>
			</div>
		</div>
	</div>
</div>
<div class="panel panel-default card-view">
        <div class="panel-heading">
			<div class="pull-left"><h6 class="panel-title txt-dark"><label id="lbltitle">View Hierarki Akun</label></h6></div>
			<div class="clearfix"></div>
		</div>
	<div class="panel-wrapper collapse in">
	<div  class="panel-body row pa-0">
	</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12">
	<div class="table-responsive">
		<table  class="table table-striped table-bordered table-hover responsive" > 
		<thead>
		<tr><th>Kode Akun</th>
		<th colspan=4> Nama Perkiraan</th> 
		<th>Kelompok</th>
		<th>Level</th>
		<th>ID Parent</th>
		<th>Saldo Awal</th></tr>
		</thead>
		<tbody>
<?

	$str="select * from ak_rekeningperkiraan where level=1";
	$resList= $this->db->query($str)->result();
	foreach ($resList as $level1){
		echo "<tr><td>".$level1->idacc."</td><td colspan=4><b>".strtoupper($level1->nama)."</b></td>";
		echo "<td>".$level1->kelompok."</td>";
		echo "<td>".$level1->level."</td>";
		echo "<td>".$level1->idparent."</td>";
		echo "<td>".$level1->initval."</td>";
		echo "</tr>";
		$strL2="select * from ak_rekeningperkiraan where level=2 and idparent='".$level1->idacc."'";
		$resListL2= $this->db->query($strL2)->result();
		foreach ($resListL2 as $level2){
			echo "<tr><td>".$level2->idacc."</td><td>&nbsp;</td><td colspan=3><b>".$level2->nama."</b></td>";
			echo "<td>".$level2->kelompok."</td>";
			echo "<td>".$level2->level."</td>";
			echo "<td>".$level2->idparent."</td>";
			echo "<td>".$level2->initval."</td>";
			echo "</tr>";

			$strL3="select * from ak_rekeningperkiraan where level=3 and idparent='".$level2->idacc."'";
			$resListL3= $this->db->query($strL3)->result();
			foreach ($resListL3 as $level3){
				echo "<tr><td>".$level3->idacc."</td><td>&nbsp;</td><td>&nbsp;</td><td colspan=2><b>".$level3->nama."</b></td>";
				echo "<td>".$level3->kelompok."</td>";
				echo "<td>".$level3->level."</td>";
				echo "<td>".$level3->idparent."</td>";
				echo "<td>".$level3->initval."</td>";
				echo "</tr>";

				$strL4="select * from ak_rekeningperkiraan where level=4 and idparent='".$level3->idacc."'";
				$resListL4= $this->db->query($strL4)->result();
				foreach ($resListL4 as $level4){
					echo "<tr><td>".$level4->idacc."</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td >".$level4->nama."</td>";
					echo "<td>".$level4->kelompok."</td>";
					echo "<td>".$level4->level."</td>";
					echo "<td>".$level4->idparent."</td>";
					echo "<td>".$level4->initval."</td>";
					echo "</tr>";
				}
			}
		}

	}

?>
		</tbody>
	</table>
	</div>
	</div>
</div>



<script>
    $(document).ready(function() {
        $('#dataTables_5').dataTable({
			"bProcessing": true,
			"bServerSide": true,
			"iDisplayLength": 25,
			"bJQueryUI": true,
			"sPaginationType": "full_numbers",
			"aoColumns": [
				{"mData": "IDACC" },
				{"mData": "NAMA" },
				{"mData": "KELOMPOK" },
				{"mData": "LEVEL" },
				{"mData": "IDPARENT" },
				{"mData": "CABANG" , "sortable":false},
				{"mData": "SALDO" , "sortable":false},
				{"mData": "STATUS" },
				{"mData": "ACTION", "sortable":false }
			],
			"sAjaxSource": "<?php echo base_url('rekeningakun/json_data_level5');?>"
		});

    });
	
</script>