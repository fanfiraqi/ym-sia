<div class="row">
<div class="col-md-12">
 <div class="box box-info">
 <div class="box-body">
<?	
	$viewKop=$this->commonlib->tableKop(strtoupper($nmcabang->KOTA),$title, '00', '__','__', $this->session->userdata('param_company'), $this->session->userdata('logo'));
	echo $viewKop;
	
?>

</div>
</div>
</div>
</div>
<? $this ->load->view('freport/displayBankIsi'); ?>

