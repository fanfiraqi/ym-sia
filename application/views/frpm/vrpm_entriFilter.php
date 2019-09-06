<?php echo form_open('rpm/entri',array('class'=>'form-horizontal','id'=>'myform'));?>
<?			
	if ($this->session->userdata('auth')->ID_CABANG<=1 && ($this->session->userdata('auth')->ROLE=="Admin" || $this->session->userdata('auth')->ROLE=="Accounting")){		//id_cabang==0 atau user admin atau user accounting pusat
		?>
		<div class="row">
			<div class="col-md-8">
				<div class="form-group"><label class="col-sm-4 control-label">Cabang</label>
					<div class="col-sm-8">					
					<?=form_dropdown('id_cabang',$cabang,'','id="id_cabang" class="form-control"');?>			
					</div>
				</div>
			</div>
		</div>
<?	
		}else{?>
			<div class="row">
			<div class="col-md-8">
				<div class="form-group"><label class="col-sm-4 control-label">Cabang</label>
					<div class="col-sm-8">
					<input type="hidden" name="id_cabang" id="id_cabang" value="<?php echo $id_cabang?>">					
					<label class="control-label"><?php echo "<b>".strtoupper($namakota)."</b>"?></label>
								
					</div>
				</div>
			</div>
		</div>
		<? } ?>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label  class="col-sm-4 control-label">Pilih Kelompok</label>
			<div class="col-sm-8"><?php	echo form_dropdown('kelompok',array('PDG'=>'Pengeluaran Pendayagunaan', 'SDM'=>'Pengeluaran SDM dan UMUM'),'','id="kelompok" class="form-control"');
				?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group ">
			<label for="nama" class="col-sm-4   control-label">Tahun</label>
			<div class="col-sm-6 form-inline"><?=form_dropdown('cbTahun',$arrThn, date('Y'),'id="cbTahun" class="form-control"');?>&nbsp;&nbsp;
			<label  class="control-label">Bulan</label>
			<?=form_dropdown('cbBulan',$arrBulan, date('m'),'id="cbBulan" class="form-control"');?></div>
	</div>
	</div>
</div>

<div class="row">
	<div class="col-md-8">
	<div class="form-group"><label  class="col-sm-4 control-label">&nbsp;</label>
	<div class="col-sm-8">
	<?php 
			$btsubmit = array(
					'name'=>'btsubmit',
					'id'=>'btsubmit',
					'value'=>'Next',
					'class'=>'btn btn-primary'
				);
			echo form_submit($btsubmit);?>
	</div>
	</div>
	</div>
</div>
<?php echo form_close();?>
<script type="text/javascript">
$(document).ready(function(){
});

</script>
