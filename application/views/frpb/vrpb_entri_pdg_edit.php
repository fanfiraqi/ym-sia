<div class="alert alert-success">
	Keterangan : <br>
	&nbsp;&nbsp;1. New = Belum Pernah disimpan, <br>
	&nbsp;&nbsp;2. Edit = Sudah ada data yang disimpan, dan ada data baru sebagai update<br>
	&nbsp;&nbsp;3. View/Disabled = Melewati Tahun aktif<br>
	
</div>
<?php echo form_open('rpb/save_pdg_entri',array('class'=>'form-horizontal','id'=>'myform'));?>
<div class="row" style="text-align:center">
	<div class="col-md-12">
		<div class="form-group">
				<label class="control-label"><b>LEMBAGA MANAJEMEN INFAQ</b></label><br>
				<label class="control-label"><b>CABANG <?php echo strtoupper($nmcabang)?></b></label><br>
				<label class="control-label">RPB PENDAYAGUNAAN PERIODE  <?php echo $arrBulan[$bulan]." ".$tahun?></label>
				
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
	echo '<thead><tr><th rowspan=2>No</th><th colspan=4 >Departemen/Pogram </th><th rowspan=2>Status</th><th colspan=3>Jumlah RPB Cabang</th><th rowspan=2>Total Harga</th><th rowspan=2>Total Nominal</th><th colspan=3>Jumlah RPB Approved</th>';	
	echo '</tr>';
	echo '<tr>';
	echo '<th colspan=4>Kegiatan/Rincian</th>';
	echo '<th>Unit</th><th>Satuan</th><th>Harga(Rp)</th>';
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
								//item rkat di level 3, $k=row-nya, txtlbg_row_bln = txtlbg_$k_$x
								
								//status=edit => get nominal dari table rpb_pdg_detil
								$strRpb="select * from rpb_pdg_detil where id_header=(select distinct id_header from rpb_pdg_header where id_cabang=".$id_cabang." AND tahun='".$tahun."' and bulan='$bulan') and id_item=".$row3->id;
								$rsValRpb=$this->db->query($strRpb)->row();
								if (sizeof($rsValRpb)>0){
									$c_status=$rsValRpb->status;
									$c_unit=$rsValRpb->unit_cab;
									$c_satuan=$rsValRpb->satuan_cab;
									$c_jumlah=$rsValRpb->jml_cab;
									$a_unit=$rsValRpb->unit_appv;
									$a_satuan=$rsValRpb->satuan_appv;
									$a_jumlah=$rsValRpb->jml_appv;
								}else{
								//get nominal dari tabel entri rkat
									$str="SELECT * FROM rkat_pdg_detil WHERE id_header= (SELECT DISTINCT id_header FROM rkat_pdg_header WHERE id_cabang=".$id_cabang." AND tahun='".$tahun."') and id_item=".$row3->id;
									$rsVal=$this->db->query($str)->row();
									$intBln=intval($bulan);	//kolom value bulan									
									$org=$intBln."_org";	//unit
									$jml=$intBln."_jml";	// harga dari rkat
									$c_status="";
									$c_unit=$rsVal->$org;									
									$c_satuan=	($c_unit==0?0: round(($rsVal->$jml/$c_unit),2))	;
									$c_jumlah=$rsVal->$jml;
									$a_unit=$c_unit;
									$a_satuan=$c_satuan;
									$a_jumlah=$c_jumlah;
								
								}

								echo "<td>".form_input(array('name'=>'txtstatus_'.$rowx,'id'=>'txtstatus_'.$rowx,'class'=>'form-control' , 'style'=> 'width:100px', 'value'=>$c_status))."</td>";
								echo "<td><input type='hidden' name='idrkat_".$rowx."' id='idrkat_".$rowx."' value='".$row3->id."'>".form_input(array('name'=>'txtcabunit_'.$rowx,'id'=>'txtcabunit_'.$rowx,'class'=>'form-control', 'onkeypress'=>"return numericVal(this,event)", 'value'=> $c_unit , 'style'=> 'width:50px'))."</td>";
								echo "<td>".form_input(array('name'=>'txtcabsatuan_'.$rowx,'id'=>'txtcabsatuan_'.$rowx,'class'=>'form-control' ,'onkeypress'=>"return numericVal(this,event)", 'value'=>$c_satuan, 'style'=> 'width:100px'))."</td>";
								echo "<td>".form_input(array('name'=>'txtcabjml_'.$rowx,'id'=>'txtcabjml_'.$rowx,'class'=>'form-control' ,'onkeypress'=>"return numericVal(this,event)", 'value'=>$c_jumlah, 'style'=> 'width:100px'))."</td>";							
								
								

								echo "<td></td>";
								echo "<td></td>";

								//kolom approve hanya utl role admin/accounting cab-0 atau cab=1-pusat
								$readonly=(($this->session->userdata('auth')->ID_CABANG<=1 && ($this->session->userdata('auth')->ROLE=="Admin" || $this->session->userdata('auth')->ROLE=="Accounting")) ? "": "readonly");
								

								echo '<td><input type="text" name="txtappvunit_'.$rowx.'" id="txtappvunit_'.$rowx.'" class="form-control" onkeypress="return numericVal(this,event)" value="'.($readonly==""? $a_unit : 0).'" style="width:50px" '.$readonly.'></td>';
								echo '<td><input type="text" name="txtappvsatuan_'.$rowx.'" id="txtappvsatuan_'.$rowx.'" class="form-control" onkeypress="return numericVal(this,event)" value="'.($readonly==""? $a_satuan : 0).'" style="width:100px" '.$readonly.'></td>';
								echo '<td><input type="text" name="txtappvjml_'.$rowx.'" id="txtappvjml_'.$rowx.'" class="form-control" onkeypress="return numericVal(this,event)" value="'.($readonly==""? $a_jumlah : 0).'" style="width:100px" '.$readonly.'></td>';
								$rowx++;
							}
							echo "</tr>";
							$k++;
							
							if (sizeof($rsL4)>0){
								$l=1;
								foreach ($rsL4 as $row4){	//DISPLAY LEVEL 4
									$rsVal=$this->db->query("select * from rpb_pdg_detil where id_header=".$rowheader->id_header." and id_item=".$row4->id)->row();
									echo "<tr>";
									echo "<td>".$i.".".$j.".".$k."</td>";
									echo "<td></td><td></td><td></td><td>".$row4->nama_item."</td>";
									//item rkat
									//status=edit => get nominal dari table rpb_pdg_detil
								$strRpb="select * from rpb_pdg_detil where id_header=(select distinct id_header from rpb_pdg_header where id_cabang=".$id_cabang." AND tahun='".$tahun."' and bulan='$bulan') and id_item=".$row4->id;
								$rsValRpb=$this->db->query($strRpb)->row();
								if (sizeof($rsValRpb)>0){
									$c_status=$rsValRpb->status;
									$c_unit=$rsValRpb->unit_cab;
									$c_satuan=$rsValRpb->satuan_cab;
									$c_jumlah=$rsValRpb->jml_cab;
									$a_unit=$rsValRpb->unit_appv;
									$a_satuan=$rsValRpb->satuan_appv;
									$a_jumlah=$rsValRpb->jml_appv;
								}else{
								//get nominal dari tabel entri rkat
									$str="SELECT * FROM rkat_pdg_detil WHERE id_header= (SELECT DISTINCT id_header FROM rkat_pdg_header WHERE id_cabang=".$id_cabang." AND tahun='".$tahun."') and id_item=".$row3->id;
									$rsVal=$this->db->query($str)->row();
									$intBln=intval($bulan);	//kolom value bulan									
									$org=$intBln."_org";	//unit
									$jml=$intBln."_jml";	// harga dari rkat
									$c_status="";
									$c_unit=$rsVal->$org;
									$c_satuan=	($c_unit==0?0: round(($rsVal->$jml/$c_unit),2))	;
									$c_jumlah=$rsVal->$jml;
									$a_unit=$c_unit;
									$a_satuan=$c_satuan;
									$a_jumlah=$c_jumlah;
								
								}

								echo "<td>".form_input(array('name'=>'txtstatus_'.$rowx,'id'=>'txtstatus_'.$rowx,'class'=>'form-control' , 'style'=> 'width:100px', 'value'=>$c_status))."</td>";
								echo "<td><input type='hidden' name='idrkat_".$rowx."' id='idrkat_".$rowx."' value='".$row4->id."'>".form_input(array('name'=>'txtcabunit_'.$rowx,'id'=>'txtcabunit_'.$rowx,'class'=>'form-control', 'onkeypress'=>"return numericVal(this,event)", 'value'=> $c_unit , 'style'=> 'width:50px'))."</td>";
								echo "<td>".form_input(array('name'=>'txtcabsatuan_'.$rowx,'id'=>'txtcabsatuan_'.$rowx,'class'=>'form-control' ,'onkeypress'=>"return numericVal(this,event)", 'value'=>$c_satuan, 'style'=> 'width:100px'))."</td>";
								echo "<td>".form_input(array('name'=>'txtcabjml_'.$rowx,'id'=>'txtcabjml_'.$rowx,'class'=>'form-control' ,'onkeypress'=>"return numericVal(this,event)", 'value'=>$c_jumlah, 'style'=> 'width:100px'))."</td>";							
								
								

								echo "<td></td>";
								echo "<td></td>";

								//kolom approve hanya utl role admin/accounting cab-0 atau cab=1-pusat
								$readonly=(($this->session->userdata('auth')->ID_CABANG<=1 && ($this->session->userdata('auth')->ROLE=="Admin" || $this->session->userdata('auth')->ROLE=="Accounting")) ? "": "readonly");
								

								echo '<td><input type="text" name="txtappvunit_'.$rowx.'" id="txtappvunit_'.$rowx.'" class="form-control" onkeypress="return numericVal(this,event)" value="'.($readonly==""? $a_unit : 0).'" style="width:50px" '.$readonly.'></td>';
								echo '<td><input type="text" name="txtappvsatuan_'.$rowx.'" id="txtappvsatuan_'.$rowx.'" class="form-control" onkeypress="return numericVal(this,event)" value="'.($readonly==""? $a_satuan : 0).'" style="width:100px" '.$readonly.'></td>';
								echo '<td><input type="text" name="txtappvjml_'.$rowx.'" id="txtappvjml_'.$rowx.'" class="form-control" onkeypress="return numericVal(this,event)" value="'.($readonly==""? $a_jumlah : 0).'" style="width:100px" '.$readonly.'></td>';
									
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
<div class="row">
	<div class="col-md-12">
<? if ($sts!="disabled" ){ ?>
	<input type="button" class="btn btn-primary" id="btsimpan" value="Simpan" <?php echo ($stsappv=="Telah Disetujui"?" disabled ":"")?>>
<?	//tombol validasi utk admin/user accounting hanya untuk form edit 
if ($sts=="edit"){
	if ($this->session->userdata('auth')->ID_CABANG<=1 && ($this->session->userdata('auth')->ROLE=="Admin" || $this->session->userdata('auth')->ROLE=="Accounting")){
		echo '<input type="button" class="btn btn-primary" id="btvalidasi" value="Proses Approve">';
	}
}
?>
	<input type="hidden" name="jmlRow" id="jmlRow" value="<?=($rowx-1)?>">	
	<input type="hidden" name="tahun" id="tahun" value="<?=$tahun?>">	
	<input type="hidden" name="bulan" id="bulan" value="<?=$bulan?>">
	<input type="hidden" name="id_cabang" id="id_cabang" value="<?=$id_cabang?>">
	<input type="hidden" name="id_head" id="id_head" value="<?=$rowheader->id_header?>">
	<input type="hidden" name="kelompok" id="kelompok" value="<?=$kelompok?>">
	<input type="hidden" name="sts" id="sts" value="<?=$sts?>">
<? }?>
	</div>
</div>
<?php echo form_close();?>

<script type="text/javascript">
 $(document).ready(function() {  
	

 });
$('#btvalidasi').click(function(){	
	var result=confirm("Apakah nominal approve sudah lengkap dan sudah dilakukan proses simpan sebelumnya ?");
	//alert(result);
	if (result==true)	{
	
	$().showMessage('Sedang diproses.. Harap tunggu..');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('rpb/approve_pdg_form');?>',
			data: { id_head : $("#id_head").val() },				
			dataType: 'json',
			success: function(msg) {
				// $("#errorHandler").html('&nbsp;').hide();
				 console.log(msg);
				if(msg.status =='success'){
					$().showMessage('Data RPB Pendayagunaan berhasil diapprove.','success',2000);
					window.location.reload();					
					
				} else {
					$().showMessage('Terjadi kesalahan. Data gagal divalidasi.','danger',700);
					
					//$("#errorHandler").html(msg.errormsg).show();
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				bootbox.alert("Terjadi kesalahan. Data gagal disimpan."+	textStatus + " - " + errorThrown );
			},
			cache: false
		});
	}
});
$('#btsimpan').click(function(){		
		var form_data = $('#myform').serialize();
		$().showMessage('Sedang diproses.. Harap tunggu..');
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url('rpb/save_pdg_entri');?>',
			data: form_data,				
			dataType: 'json',
			success: function(msg) {
				// $("#errorHandler").html('&nbsp;').hide();
				 console.log(msg);
				if(msg.status =='success'){
					$().showMessage('Data RPB Pendayagunaan berhasil disimpan.','success',2000);
					window.location.reload();					
					
				} else {
					$().showMessage('Terjadi kesalahan. Data gagal disimpan.','danger',700);
					
					//$("#errorHandler").html(msg.errormsg).show();
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				bootbox.alert("Terjadi kesalahan. Data gagal disimpan."+	textStatus + " - " + errorThrown );
			},
			cache: false
		});
	});
</script>
