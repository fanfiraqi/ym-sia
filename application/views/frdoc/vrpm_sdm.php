<?php
	$rowheader=$this->db->query("select * from rpm_pdg_header where id_header=".$id_head)->row();
	$kelompok='SDM';
	$tahun=$rowheader->tahun;
	$bulan=$rowheader->bulan;
	$sts=$rowheader->status_entri;
	$stsappv=$rowheader->status_approve;
	$tglappv=$rowheader->tgl_approve;
	$rscab = $this->db->query("select KOTA from mst_cabang where id_cabang=".$rowheader->id_cabang)->row();
	$nmcabang=$rscab->KOTA;
	$id_cabang=$rowheader->id_cabang;
?>
<?php echo form_open('rkat/save_pdg_entri',array('class'=>'form-horizontal','id'=>'myform'));?>
<div class="row" style="text-align:center">
	<div class="col-md-12">
		<div class="form-group">
				<label class="control-label"><b>LEMBAGA MANAJEMEN INFAQ</b></label><br>
				<label class="control-label"><b>CABANG <?php echo strtoupper($nmcabang)?></b></label><br>
				<label class="control-label">RENCANA PENGELUARAN MINGGUAN (RPM) SDM, BEBAN UMUM DAN ADMINSITRASI PERIODE  <?php echo $arrBulan[$bulan]." ".$tahun?></label>
				
		</div>
	</div>
</div>
<div class="row" >
	<div class="col-md-12">
	<label class="control-label">Status Entri :  <?php echo $sts?></label><br>
	<label class="control-label">Status Approve :  <?php echo $stsappv?></label><br>
	<label class="control-label">Tanggal Approve :  <?php echo $tglappv?></label><br>
	</div>
</div>
<div class="row" style="overflow:scroll; height:550px">
	<div class="col-md-12">
<?	$strL1="select * from mst_item_rkat where level=1 and kelompok='$kelompok' and isactive=1 order by id";
	$rsL1=$this->db->query($strL1)->result();
	
	echo '<table class="table table-bordered" width="60%">';
	echo '<thead><tr><th rowspan=2>No</th><th colspan=4 >Departemen/Pogram </th><th rowspan=2>Status</th><th colspan=3>Jumlah Approved</th><th rowspan=2>Total Harga</th><th rowspan=2>Pekan I</th><th rowspan=2>Pekan II</th><th rowspan=2>Pekan III</th><th rowspan=2>Pekan IV</th>';
	echo '</tr>';
	echo '<tr>';
	echo '<th colspan=4>Kegiatan/Rincian</th>';
	echo '<th>Unit</th><th>Satuan</th><th>Harga(Rp)</th>';	
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
			echo "<td colspan=14>&nbsp;</td>";
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
					echo "<td colspan=14>&nbsp;</td>";
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
							
							echo "<tr>";
							echo "<td>".$i.".".$j.".".$k."</td>";
							echo "<td></td><td></td><td colspan=2>".$row3->nama_item."</td>";
							if (sizeof($rsL4)>0){
								echo "<td colspan=14>&nbsp;</td>";
							}else{
								//item rkat di level 3, $k=row-nya								
								//status=edit => get nominal langsung dari table RPM
								$strRpm="select * from rpm_sdm_detil where id_header=(select distinct id_header from rpm_sdm_header where id_cabang=".$id_cabang." AND tahun='".$tahun."' and bulan='$bulan') and id_item=".$row3->id;
								$rsValRpm=$this->db->query($strRpm)->row();
								$c_status=$a_unit=$a_satuan=$a_jumlah=$pekan_1=$pekan_2=$pekan_3=$pekan_4=0;
								if (sizeof($rsValRpm)>0){
									$c_status=$rsValRpm->status;
									$a_unit=$rsValRpm->unit_appv;
									$a_satuan=$rsValRpm->satuan_appv;
									$a_jumlah=$rsValRpm->jml_appv;
									$pekan_1=$rsValRpm->pekan_1;
									$pekan_2=$rsValRpm->pekan_2;
									$pekan_3=$rsValRpm->pekan_3;
									$pekan_4=$rsValRpm->pekan_4;
								}

								echo "<td>".form_input(array('name'=>'txtstatus_'.$rowx,'id'=>'txtstatus_'.$rowx,'class'=>'form-control' , 'style'=> 'width:100px', 'value'=>$c_status))."</td>";
								echo '<td><input type="hidden" name="idrkat_'.$rowx.'" id="idrkat_"'.$rowx.'" value="'.$row3->id.'"><input type="text" name="txtappvunit_'.$rowx.'" id="txtappvunit_'.$rowx.'" class="form-control" onkeypress="return numericVal(this,event)" value="'.$a_unit.'" style="width:50px" ></td>';
								echo '<td><input type="text" name="txtappvsatuan_'.$rowx.'" id="txtappvsatuan_'.$rowx.'" class="form-control" onkeypress="return numericVal(this,event)" value="'.$a_satuan.'" style="width:100px" ></td>';
								echo '<td><input type="text" name="txtappvjml_'.$rowx.'" id="txtappvjml_'.$rowx.'" class="form-control" onkeypress="return numericVal(this,event)" value="'.$a_jumlah.'" style="width:100px" ></td>';
								echo "<td></td>";

								
								echo '<td><input type="text" name="txtpekan1_'.$rowx.'" id="txtpekan1_'.$rowx.'" class="form-control" onkeypress="return numericVal(this,event)" value="'.$pekan_1.'" style="width:100px" ></td>';
								echo '<td><input type="text" name="txtpekan2_'.$rowx.'" id="txtpekan2_'.$rowx.'" class="form-control" onkeypress="return numericVal(this,event)" value="'.$pekan_2.'" style="width:100px" ></td>';
								echo '<td><input type="text" name="txtpekan3_'.$rowx.'" id="txtpekan3_'.$rowx.'" class="form-control" onkeypress="return numericVal(this,event)" value="'.$pekan_3.'" style="width:100px" ></td>';
								echo '<td><input type="text" name="txtpekan4_'.$rowx.'" id="txtpekan4_'.$rowx.'" class="form-control" onkeypress="return numericVal(this,event)" value="'.$pekan_4.'" style="width:100px" ></td>';

								$rowx++;
							}
							echo "</tr>";
							$k++;
							
							if (sizeof($rsL4)>0){
								$l=1;
								foreach ($rsL4 as $row4){	//DISPLAY LEVEL 4
									$rsVal=$this->db->query("select * from rpm_sdm_detil where id_header=".$rowheader->id_header." and id_item=".$row4->id)->row();
									echo "<tr>";
									echo "<td>".$i.".".$j.".".$k."</td>";
									echo "<td></td><td></td><td></td><td>".$row4->nama_item."</td>";
									//item rkat
									//status=edit => get nominal dari table rpm_sdm_detil
									$strRpm="select * from rpm_sdm_detil where id_header=(select distinct id_header from rpm_sdm_header where id_cabang=".$id_cabang." AND tahun='".$tahun."' and bulan='$bulan') and id_item=".$row4->id;
									$rsValRpm=$this->db->query($strRpm)->row();
									$c_status=$a_unit=$a_satuan=$a_jumlah=$pekan_1=$pekan_2=$pekan_3=$pekan_4=0;
									if (sizeof($rsValRpm)>0){
										$c_status=$rsValRpm->status;
										$a_unit=$rsValRpm->unit_appv;
										$a_satuan=$rsValRpm->satuan_appv;
										$a_jumlah=$rsValRpm->jml_appv;
										$pekan_1=$rsValRpm->pekan_1;
										$pekan_2=$rsValRpm->pekan_2;
										$pekan_3=$rsValRpm->pekan_3;
										$pekan_4=$rsValRpm->pekan_4;
									}

									echo "<td>".form_input(array('name'=>'txtstatus_'.$rowx,'id'=>'txtstatus_'.$rowx,'class'=>'form-control' , 'style'=> 'width:100px', 'value'=>$c_status))."</td>";
									echo '<td><input type="hidden" name="idrkat_'.$rowx.'" id="idrkat_"'.$rowx.'" value="'.$row4->id.'"><input type="text" name="txtappvunit_'.$rowx.'" id="txtappvunit_'.$rowx.'" class="form-control" onkeypress="return numericVal(this,event)" value="'.$a_unit.'" style="width:50px" ></td>';
									echo '<td><input type="text" name="txtappvsatuan_'.$rowx.'" id="txtappvsatuan_'.$rowx.'" class="form-control" onkeypress="return numericVal(this,event)" value="'.$a_satuan.'" style="width:100px" ></td>';
									echo '<td><input type="text" name="txtappvjml_'.$rowx.'" id="txtappvjml_'.$rowx.'" class="form-control" onkeypress="return numericVal(this,event)" value="'.$a_jumlah.'" style="width:100px" ></td>';
									echo "<td></td>";

									
									echo '<td><input type="text" name="txtpekan1_'.$rowx.'" id="txtpekan1_'.$rowx.'" class="form-control" onkeypress="return numericVal(this,event)" value="'.$pekan_1.'" style="width:100px" ></td>';
									echo '<td><input type="text" name="txtpekan2_'.$rowx.'" id="txtpekan2_'.$rowx.'" class="form-control" onkeypress="return numericVal(this,event)" value="'.$pekan_2.'" style="width:100px" ></td>';
									echo '<td><input type="text" name="txtpekan3_'.$rowx.'" id="txtpekan3_'.$rowx.'" class="form-control" onkeypress="return numericVal(this,event)" value="'.$pekan_3.'" style="width:100px" ></td>';
									echo '<td><input type="text" name="txtpekan4_'.$rowx.'" id="txtpekan4_'.$rowx.'" class="form-control" onkeypress="return numericVal(this,event)" value="'.$pekan_4.'" style="width:100px" ></td>';
									
									echo "</tr>";
									$l++;
									$rowx++;
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
<br>

<?php echo form_close();?>

<script type="text/javascript">
 $(document).ready(function() {  

 });
</script>
