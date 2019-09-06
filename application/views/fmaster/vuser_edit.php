<?php 
$role=$this->session->userdata('auth')->ROLE;
errorHandler(); 
?>
<?php echo form_open('pengguna/edit',array('class'=>'form-horizontal','id'=>'myform'));?>
<?php echo form_hidden('id',$this->session->userdata('auth')->ID);?>

<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">USERNAME</label>
			<div class="col-sm-8"><?=form_input(array('name'=>'username','id'=>'username','class'=>'form-control', 'value'=>$role=$this->session->userdata('auth')->USERNAME, 'disabled'=>'true'));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label class="col-sm-4 control-label">PASSWORD</label>
			<div class="col-sm-8"><?=form_password(array('name'=>'password','id'=>'password','class'=>'form-control'));?></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="form-group"><label  class="col-sm-4 control-label">NAMA</label>
			<div class="col-sm-8"><?=form_input(array('name'=>'nama','id'=>'nama','class'=>'form-control','value'=>$role=$this->session->userdata('auth')->NAMA));?></div>
		</div>
	</div>
</div>
<? if (trim($role)=='Admin' || trim($role)=='admin'){ ?>
<div class="row">
	<div class="col-md-8"><div class="form-group">
		<label for="sex" class="col-sm-4 control-label">ROLE AKSES</label>
			<div class="col-sm-4">
				<?php $data = array('Kasir'=>'Kasir Pusat','Kasir Cabang'=>'Kasir Cabang', 'Manager Keuangan'=>'Manager Keuangan', 'Direktur Keuangan'=>'Direktur Keuangan','Admin'=>'Admin Program');
				if ($role=='Admin'){
						echo form_dropdown('role',$data,$row->ROLE,'id="role" class="form-control"');
					}else{
						echo '<B>'.$data[$role].'</B>'; 
					}
					
					
				?>
			</div>
			<div class="col-sm-4">

				<?php 
				if ($role=='Admin'){
						echo form_dropdown('cabang',$cabang,'','id="cabang" class="form-control"');
					}else{
						echo form_dropdown('cabang',$row->ID_CABANG,'','id="cabang" class="form-control" disabled');
						//echo '<B>'.$data[$row->ISACTIVE].'</B>'; 
					}
				
				?>
			</div>
	</div>
	</div>
</div>

<div class="row">
	<div class="col-md-8">
		<div class="form-group">
			<label for="sex" class="col-sm-4 control-label">STATUS</label>
			<div class="col-sm-4">
				<?php $data = array('1'=>'Aktif', '0'=>'Tidak Aktif');
					//echo form_dropdown('status',$data,'','id="status" class="form-control"  ');
					if ($role=='Admin'){
						echo form_dropdown('status',$data,$row->ISACTIVE,'id="status" class="form-control"');
					}else{
						echo '<B>'.$data[$row->ISACTIVE].'</B>'; 
					}
				?>
			</div>
		</div>
	</div>
</div>
<?}?>
<div class="row">
	<div class="col-md-12">
	<input type="submit" class="btn btn-primary" id="btsimpankel" value="Simpan">
	<button type="button" class="btn btn-default" id="btcancelkel">Batal</button>
	</div>
	</div> <hr/>

<?php echo form_close();?>

<script type="text/javascript">
$('#myform').submit(function(event) {

		$(this).saveForm('<?php echo base_url('pengguna/edit/'.$row->ID);?>','<?php echo base_url('pengguna/edit/'.$row->ID);?>');

	event.preventDefault();
});


$(document).ready(function(){
});


</script>