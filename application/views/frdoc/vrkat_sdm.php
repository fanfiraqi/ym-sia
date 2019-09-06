<?php
	$rowheader=$this->db->query("select * from rkat_pdg_header where id_header=".$id_head)->row();
	$kelompok='SDM';
	$tahun=$rowheader->tahun;
	$rscab = $this->db->query("select KOTA from mst_cabang where id_cabang=".$rowheader->id_cabang)->row();
	$nmcabang=$rscab->KOTA;
?>
<?php echo form_open('rkat/save_php_entri',array('class'=>'form-horizontal','id'=>'myform'));?>
<div class="row" style="text-align:center">
	<div class="col-md-12">
		<div class="form-group">
				<label class="control-label"><b>LEMBAGA MANAJEMEN INFAQ</b></label><br>
				<label class="control-label"><b>CABANG <?php echo strtoupper($nmcabang)?></b></label><br>
				<label class="control-label">RKAT BAGIAN SDM DAN UMUM TAHUN <?php echo $tahun?></label>
				
		</div>
	</div>
</div>
<div class="row" style="overflow:scroll; height:550px">
	<div class="col-md-12">
<?	$strL1="select * from mst_item_rkat where level=1 and kelompok='$kelompok' and isactive=1 order by id";
	$rsL1=$this->db->query($strL1)->result();
	
	echo '<table class="table table-bordered" width="60%">';
	echo '<thead><tr><th  >No</th><th colspan=4  >Keterangan</th>';
	for ($i=1; $i<=sizeof($arrBulan);$i++){
		echo "<th  width=\"20\">".$arrBulan[$i]."</th>";
	}
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	$rowx=1;
	if (sizeof($rsL1)>0){
		$i=1;
		foreach ($rsL1 as $row1){	//DISPLAY LEVEL 1
			echo "<tr>";
			echo "<td>".$i."</td>";
			echo "<td colspan=4><b>".str_replace(' ','&nbsp;',$row1->nama_item)."</b></td>";
			echo "<td colspan=12>&nbsp;</td>";
			echo "</tr>";
			//level 2
			$strL2="select * from mst_item_rkat where level=2 and idparent=".$row1->id." and isactive=1 order by id";
			$rsL2=$this->db->query($strL2)->result();
			if (sizeof($rsL2)>0){
				$j=1;
				foreach ($rsL2 as $row2){	//DISPLAY LEVEL 2
					echo "<tr>";
					echo "<td>".$i.".".$j."</td>";
					echo "<td></td><td colspan=3>".$row2->nama_item."</td>";
					echo "<td colspan=12>&nbsp;</td>";
					echo "</tr>";
					$j++;
					//level 3
					$strL3="select * from mst_item_rkat where level=3 and idparent=".$row2->id." and isactive=1 order by id";
					$rsL3=$this->db->query($strL3)->result();					
					if (sizeof($rsL3)>0){
						$k=1;
						
						foreach ($rsL3 as $row3){	//DISPLAY LEVEL 3
							//level 4
							$strL4="select * from mst_item_rkat where level=4 and idparent=".$row3->id." and isactive=1 order by id";
							$rsL4=$this->db->query($strL4)->result();
							//cek if lvl 3 has child, if no cek lvl 3 id is on the rkat selected tahun & id_cabang
							if (sizeof($rsL4)>0){
								echo "<tr>";
								echo "<td>".$i.".".$j.".".$k."</td>";
								echo "<td></td><td></td><td colspan=2>".$row3->nama_item."</td>";
								echo "<td colspan=12>&nbsp;</td>";
								echo "</tr>";

								$l=1;
								foreach ($rsL4 as $row4){	//DISPLAY LEVEL 4
									$rsVal=$this->db->query("select * from rkat_sdm_detil where id_header=".$rowheader->id_header." and id_item=".$row4->id)->row();
									if (sizeof($rsVal)>0){
										echo "<tr>";
										echo "<td>".$i.".".$j.".".$k."</td>";
										echo "<td></td><td></td><td></td><td>".$row4->nama_item."</td>";
										//item rkat
										for ($x=1; $x<=sizeof($arrBulan);$x++){											
											$nom=$x."_nom";
											echo "<td><input type='hidden' name='idrkat_".$rowx."' id='idrkat_".$rowx."' value='".$row4->id."'> ".form_input(array('name'=>'txtnom_'.$rowx.'_'.$x,'id'=>'txtnom_'.$rowx.'_'.$x,'class'=>'form-control','onkeypress'=>"return numericVal(this,event)",  'style'=> 'width:100px', 'value'=> (sizeof($rsVal)>0? $rsVal->$nom : 0 )  ))."</td>";										
										}
										echo "</tr>";
										$l++;
									}
									$rowx++;
								}

							}else{	// no child
								$rsVal=$this->db->query("select * from rkat_sdm_detil where id_header=".$rowheader->id_header." and id_item=".$row3->id)->row();
								if (sizeof($rsVal)>0){
									echo "<tr>";
									echo "<td>".$i.".".$j.".".$k."</td>";
									echo "<td></td><td></td><td colspan=2>".$row3->nama_item."</td>";
									for ($x=1; $x<=sizeof($arrBulan);$x++){
										$bdn=$x."_bdn";
										$org=$x."_org";
										$nom=$x."_nom";
										echo "<td><input type='hidden' name='idrkat_".$rowx."' id='idrkat_".$rowx."' value='".$row3->id."'>".form_input(array('name'=>'txtnom_'.$rowx.'_'.$x,'id'=>'txtnom_'.$rowx.'_'.$x,'class'=>'form-control' ,'onkeypress'=>"return numericVal(this,event)",  'style'=> 'width:100px', 'value'=> (sizeof($rsVal)>0? $rsVal->$nom:0 )  ))."</td>";									
									}
									$rowx++;
									echo "</tr>";
									
								}

							}

							$k++;
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
<br>

<?php echo form_close();?>

<script type="text/javascript">
 $(document).ready(function() {  

 });
</script>
