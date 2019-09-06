<div class="row" style="text-align:center">
	<div class="col-md-12">
		<div class="form-group">
				<label class="control-label"><b>LEMBAGA MANAJEMEN INFAQ</b></label><br>
				<label class="control-label"><b>DAFTAR DOKUMEN RENCANA ANGGARAN</b></label><br>
				<label class="control-label"><?php echo $strJenis?></label>	<br>			
				<label class="control-label"><?php echo $strPeriode ?></label>				
				
		</div>
	</div>
</div>
<div class="alert alert-success">
	<span style="font-size: 20px"><i class="glyphicon glyphicon-thumbs-down red"></i></span> <label class="control-label">&nbsp;&nbsp;:&nbsp;Belum dientri</label><br>
	<span style="font-size: 20px"><i class="glyphicon glyphicon-pencil green"></i></span> <label class="control-label">&nbsp;&nbsp;:&nbsp;Status Entri edit</label><br>	
	<span style="font-size: 20px"><i class="glyphicon glyphicon-remove red"></i></span> <label class="control-label">&nbsp;&nbsp;:&nbsp;Belum divalidasi/approve</label><br>
    <span style="font-size: 20px"><i class="glyphicon glyphicon-ok green"></i></span>  <label class="control-label">&nbsp;&nbsp;:&nbsp;Sudah divalidasi/Approve</label><br>              
    <span style="font-size: 20px"><i class="glyphicon glyphicon glyphicon-zoom-in blue"></i></span><label class="control-label">&nbsp;&nbsp;:&nbsp;Link view dokumen</label>
    
</div>
<div class="row" style="overflow:scroll; ">
	<div class="col-md-12">
<?	
	switch ($jenis_doc){
		case "rkat": 
			$nmtablephp="rkat_php_header";
			$nmtablepdg="rkat_pdg_header";
			$nmtablesdm="rkat_sdm_header";
			break;
		case "rpb":
			$nmtablephp="rpb_php_header";
			$nmtablepdg="rpb_pdg_header";
			$nmtablesdm="rpb_sdm_header";
			break;
		case "rpm":
			$nmtablephp="rpm_php_header";
			$nmtablepdg="rpm_pdg_header";
			$nmtablesdm="rpm_sdm_header";
			break;
	}
	echo '<table class="table table-bordered" width="60%">';
	echo '<thead><tr><th rowspan=2>NO</th><th rowspan=2>CABANG/WILAYAH</th>'.($jenis_doc=='rkat'?'<th COLSPAN=3>PENGHIMPUNAN (PHP)</th>':'').'<th COLSPAN=3>PENDAYAGUNAAN(PDG)</th><th COLSPAN=3>BEBAN UMUM & OPERASIONAL(SDM)</th>';	
	echo '</tr>';
	echo '<tr>';	
	if ($jenis_doc=='rkat'){
	echo '<TH>Entri</TH><TH>Approve</TH><TH>View</TH>';
	}
	echo '<TH>Entri</TH><TH>Validasi</TH><TH>View</TH>';
	echo '<TH>Entri</TH><TH>Approve</TH><TH>View</TH>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	$i=1;
	foreach ($cabang as $row){
		echo "<tr>";
		echo "<td>$i</td>";
		echo "<td>".$row->KOTA."</td>";
		if ($jenis_doc=='rkat'){	//PHP			
			$rsphp=$this->db->query("select * from ".$nmtablephp." where id_cabang=".$row->ID_CABANG." and tahun='$tahun'")->row();
			if (sizeof($rsphp)>0){							
				echo "<Td>".($rsphp->status_entri=="new" || $rsphp->status_entri=="edit"?'<span style="font-size: 20px"><i class="glyphicon glyphicon-pencil green"></i></span>':'<span style="font-size: 20px"><i class="glyphicon glyphicon-lock red"></i></span>')."</Td>";
				echo "<Td>".($rsphp->status_validasi=='1'?'<span style="font-size: 20px"><i class="glyphicon glyphicon-ok green"></i></span> ':'<span style="font-size: 20px"><i class="glyphicon glyphicon-remove red"></i></span>')."</td>";
				echo '<Td><a href="javascript:void(0)" onclick="viewPHP(this)" data-id_head="'.$rsphp->id_header.'" data-jenis_doc="'.$jenis_doc.'" title="RKAT Penghimpunan"><span style="font-size: 20px"><i class="glyphicon glyphicon glyphicon-zoom-in blue"></i></span></a></Td>';
			}else{
				echo '<td><span style="font-size: 20px"><i class="glyphicon glyphicon-thumbs-down red"></i></span> </td><td><span style="font-size: 20px"><i class="glyphicon glyphicon-remove red"></i></span></td><td>-</td>';
			}
		}
		//PDG
		$rspdg=$this->db->query("select * from ".$nmtablepdg." where id_cabang=".$row->ID_CABANG." and tahun='$tahun' ".($jenis_doc!='rkat'?" and bulan='$bulan'":""))->row();
			if (sizeof($rspdg)>0){
				echo "<Td>".($rspdg->status_entri=="new" || $rspdg->status_entri=="edit"?'<span style="font-size: 20px"><i class="glyphicon glyphicon-pencil green"></i></span>':'<span style="font-size: 20px"><i class="glyphicon glyphicon-lock red"></i></span>')."</Td>";
				if ($jenis_doc=='rkat'){
					echo "<Td>".($rspdg->status_validasi=='1'?'<span style="font-size: 20px"><i class="glyphicon glyphicon-ok green"></i></span> ':'<span style="font-size: 20px"><i class="glyphicon glyphicon-remove red"></i></span>')."</td>";
				}else{
					echo "<Td>".($rspdg->status_approve=='Telah Disetujui'?'<span style="font-size: 20px"><i class="glyphicon glyphicon-ok green"></i></span> ':'<span style="font-size: 20px"><i class="glyphicon glyphicon-remove red"></i></span>')."</td>";
				}
				echo '<Td><a href="javascript:void(0)" onclick="viewPDG(this)" data-id_head="'.$rspdg->id_header.'" data-jenis_doc="'.$jenis_doc.'" title="'.strtoupper($jenis_doc).' Pengeluaran Pendayagunaan"><span style="font-size: 20px"><i class="glyphicon glyphicon glyphicon-zoom-in blue"></i></span></a></Td>';
			}else{
				echo '<td><span style="font-size: 20px"><i class="glyphicon glyphicon-thumbs-down red"></i></span> </td><td><span style="font-size: 20px"><i class="glyphicon glyphicon-remove red"></i></span></td><td>-</td>';
			}
		//SDM
		$rssdm=$this->db->query("select * from ".$nmtablesdm." where id_cabang=".$row->ID_CABANG." and tahun='$tahun'".($jenis_doc!='rkat'?" and bulan='$bulan'":""))->row();
			if (sizeof($rssdm)>0){
				echo "<Td>".($rssdm->status_entri=="new" || $rssdm->status_entri=="edit"?'<span style="font-size: 20px"><i class="glyphicon glyphicon-pencil green"></i></span>':'<span style="font-size: 20px"><i class="glyphicon glyphicon-lock red"></i></span>')."</Td>";
				if ($jenis_doc=='rkat'){
					echo "<Td>".($rssdm->status_validasi=='1'?'<span style="font-size: 20px"><i class="glyphicon glyphicon-ok green"></i></span> ':'<span style="font-size: 20px"><i class="glyphicon glyphicon-remove red"></i></span>')."</td>";
				}else{
					echo "<Td>".($rssdm->status_approve=='Telah Disetujui'?'<span style="font-size: 20px"><i class="glyphicon glyphicon-ok green"></i></span> ':'<span style="font-size: 20px"><i class="glyphicon glyphicon-remove red"></i></span>')."</td>";
				}
				echo '<Td><a href="javascript:void(0)" onclick="viewSDM(this)" data-id_head="'.$rssdm->id_header.'" data-jenis_doc="'.$jenis_doc.'" title="'.strtoupper($jenis_doc).' Pengeluaran SDM dan UMUM"><span style="font-size: 20px"><i class="glyphicon glyphicon glyphicon-zoom-in blue"></i></span></a></Td>';
			}else{
				echo '<td><span style="font-size: 20px"><i class="glyphicon glyphicon-thumbs-down red"></i></span> </td><td><span style="font-size: 20px"><i class="glyphicon glyphicon-remove red"></i></span></td><td>-</td>';
			}

		echo "</tr>";
		$i++;
	}
	echo '</tbody>';
	echo '</table>';

?>	
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){

});
function viewPHP(obj){	
	var id_head = $(obj).attr('data-id_head');
	var jenis_doc = $(obj).attr('data-jenis_doc');
	var mytitle='';
	switch (jenis_doc){
	case "rkat" : mytitle='RKAT Penghimpunan';  break;
	case "rpb" : mytitle='RPB Penghimpunan';  break;
	case "rpm" : mytitle='RPM Penghimpunan';  break;
	
	}
		$.ajax({
			url: "<?php echo base_url('rkat_docstatus/viewPHP'); ?>",
			dataType: 'html',
			type: 'POST',
			data: {ajax:'true', jenis_doc: jenis_doc, id_head: id_head },
			success:
				function(data){
					bootbox.dialog({
					  message: data,
					  title: mytitle,
					  buttons: {						
						main: {
						  label: "OK",
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
	
	function viewPDG(obj){	
		var id_head = $(obj).attr('data-id_head');
		var jenis_doc = $(obj).attr('data-jenis_doc');
		var mytitle='';
		switch (jenis_doc){
		case "rkat" : mytitle='RKAT Pengeluaran Pendayagunaan';  break;
		case "rpb" : mytitle='RPB Pengeluaran Pendayagunaan';  break;
		case "rpm" : mytitle='RPM Pengeluaran Pendayagunaan';  break;
		
		}
			$.ajax({
				url: "<?php echo base_url('rkat_docstatus/viewPDG'); ?>",
				dataType: 'html',
				type: 'POST',
				data: {ajax:'true', jenis_doc: jenis_doc, id_head: id_head },
				success:
					function(data){
						bootbox.dialog({
						  message: data,
						  title: mytitle,
						  buttons: {						
							main: {
							  label: "OK",
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

	function viewSDM(obj){	
		var id_head = $(obj).attr('data-id_head');
		var jenis_doc = $(obj).attr('data-jenis_doc');
		var mytitle='';
		switch (jenis_doc){
		case "rkat" : mytitle='RKAT Pengeluaran SDM dan UMUM';  break;
		case "rpb" : mytitle='RPB Pengeluaran SDM dan UMUM';  break;
		case "rpm" : mytitle='RPM Pengeluaran SDM dan UMUM';  break;
		
		}
			$.ajax({
				url: "<?php echo base_url('rkat_docstatus/viewSDM'); ?>",
				dataType: 'html',
				type: 'POST',
				data: {ajax:'true', jenis_doc: jenis_doc, id_head: id_head },
				success:
					function(data){
						bootbox.dialog({
						  message: data,
						  title: mytitle,
						  buttons: {						
							main: {
							  label: "OK",
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
</script>
