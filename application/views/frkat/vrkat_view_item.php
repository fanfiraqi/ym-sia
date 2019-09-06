<div class="row">
	<div class="col-md-6">
		<div class="form-group">
				<label for="nama" class="col-md-4   control-label">KELOMPOK ITEM RKAT&nbsp;:</label>
					<div class="col-sm-8 form-inline"><b><?php echo strtoupper($nmkelompok)?></b></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
<?	$strL1="select * from mst_item_rkat where level=1 and kelompok='$kelompok' and isactive=1 order by id";
	$rsL1=$this->db->query($strL1)->result();
	echo '<table class="table table-bordered" width="60%">';
	echo '<thead><tr><th>No</th><th colspan=4>Keterangan</th></tr></thead>';
	echo '<tbody>';
	if (sizeof($rsL1)>0){
		$i=1;
		foreach ($rsL1 as $row1){
			echo "<tr>";
			echo "<td>".$i."</td>";
			echo "<td colspan=4>".$row1->nama_item."</td>";
			echo "</tr>";
			//level 2
			$strL2="select * from mst_item_rkat where level=2 and idparent=".$row1->id." and isactive=1 order by id";
			$rsL2=$this->db->query($strL2)->result();
			if (sizeof($rsL2)>0){
				$j=1;
				foreach ($rsL2 as $row2){
					echo "<tr>";
					echo "<td>".$i.".".$j."</td>";
					echo "<td></td><td colspan=3>".$row2->nama_item."</td>";
					echo "</tr>";
					$j++;
					//level 3
					$strL3="select * from mst_item_rkat where level=3 and idparent=".$row2->id." and isactive=1 order by id";
					$rsL3=$this->db->query($strL3)->result();
					if (sizeof($rsL3)>0){
						$k=1;
						foreach ($rsL3 as $row3){
							echo "<tr>";
							echo "<td>".$i.".".$j.".".$k."</td>";
							echo "<td></td><td></td><td colspan=2>".$row3->nama_item."</td>";
							echo "</tr>";
							$k++;

							//level 4
							$strL4="select * from mst_item_rkat where level=4 and idparent=".$row3->id." and isactive=1 order by id";
							$rsL4=$this->db->query($strL4)->result();
							if (sizeof($rsL4)>0){
								$l=1;
								foreach ($rsL4 as $row4){
									echo "<tr>";
									echo "<td>".$i.".".$j.".".$k."</td>";
									echo "<td></td><td></td><td></td><td>".$row4->nama_item."</td>";
									echo "</tr>";
									$l++;
								}
							}
						}
					}

				}
			}
		$i++;

		}

	}else{
		echo '<tr><td colspan=5>Item RKAT belum di set</td></tr>';

	}
		echo '</tbody>';
		echo '</table> ';
?>
	</div>
</div>