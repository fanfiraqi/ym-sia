<? $role=$this->config->item('myrole'); ?>
<div class="row">                        
<div class="col-lg-12  col-xs-12">
<div class="panel panel-default card-view">
	<div class="panel-heading"><div class="pull-left"><h6 class="panel-title txt-dark">Beranda SIM-Akuntansi</h6></div><div class="clearfix"></div></div>
	
	<div class="panel-wrapper collapse in">
		<div class="panel-body">
			<div class="streamline user-activity">
				<div class="sl-item">
					<a href="javascript:void(0)">
						<div class="sl-avatar avatar avatar-sm avatar-circle">
						<img class="img-responsive img-circle" src="<?php echo base_url('assets/img/logo.png');?>" alt="avatar"/>
						</div>
					<div class="sl-content">
					<p class="inline-block"><span class="capitalize-font txt-success mr-5 weight-500">Selamat Datang <b><?php echo $this->session->userdata('auth')->name?></b></span></p>
					<span class="block txt-grey font-12 capitalize-font"><?php echo date('d M Y')?></span>
					</div>
					</a>
				</div><!-- sl-item -->
				<?php if (in_array($role, [1,6,8,9,25, 26, 40,48,58])){?>    
	
				<div class="sl-item">
					<a href="javascript:void(0)">
						<div class="sl-avatar avatar avatar-sm avatar-circle">
						<img class="img-responsive img-circle" src="<?php echo base_url('assets/img/logo.png');?>" alt="avatar"/>
						</div>
					<div class="sl-content">
					<p class="inline-block"><span class="capitalize-font txt-success mr-5 weight-500"> <b>Posisi Kas (Tunai & Bank) Hari ini</b></span></p>
					<span class="block txt-grey font-12 capitalize-font"><?php echo date('d M Y')?></span>
					<? 
//sudah cek cabang
$initval=0;
$saldoLalu=0;

	$initval=$this->report_model->getInitValCashFlow($this->session->userdata('auth')->ID_CABANG);
	$saldoLalu=$this->report_model->getSaldoLaluDashboard();


$jumlah=$initval+$saldoLalu;

?>   
*Nominal adalah dari jurnal-jurnal yang sudah divalidasi. 
		<div class="table-responsive">
                                        <!-- .table - Uses sparkline charts-->
                                        <table class="table table-striped">
                                            <tr>
                                                <th>Keterangan</th>
                                                <th>Debet</th>
                                                <th>Kredit</th>
                                                <th>Saldo</th>
                                            </tr>
                                            <tr>
                                                <td>Saldo Awal</td>
                                                <td><?php echo ($jumlah<0?"":"".number_format($jumlah,0,',','.'))?></td>
                                                <td><?php echo ($jumlah<0?"".number_format($jumlah,0,',','.'):"")?></td>
                                                <td><?php echo ($jumlah<0?"( ".number_format($jumlah,0,',','.')." )":"".number_format($jumlah,0,',','.'))?></td>
                                            </tr>
<?	$kasMasuk=$this->report_model->kasMasukHariIni();
	$jumlah=$jumlah+$kasMasuk;

											
										?>
                                            <tr>
                                                <td>Kas Masuk Hari Ini</td>
                                                <td><?php echo "".number_format($kasMasuk,0,',','.')?></td>
                                                <td>&nbsp;</td>
                                                <td><?php echo ($jumlah<0?"( ".number_format($jumlah,0,',','.')." )":"".number_format($jumlah,0,',','.'))?></td>
                                            </tr>
<?
$kasKeluar=$this->report_model->kasKeluarHariIni();
$jumlah=$jumlah-$kasKeluar;
$session['saldoKas']=$jumlah;


$this->session->set_userdata($session);
?>
                                            <tr>
                                                <td>Kas Keluar Hari Ini</td>
												<td>&nbsp;</td>
                                                <td><?php echo "".number_format($kasKeluar,0,',','.')?></td> 
												<td><?php echo ($jumlah<0?"( ".number_format($jumlah,0,',','.')." )":"".number_format($jumlah,0,',','.'))?></td>
                                            </tr>
                                            <tr>
                                                <td>Saldo Terakhir s.d <?php echo date ('d M Y')?></td>
                                                <td></td>
                                                <td></td>
                                                <td><?php echo ($jumlah<0?"(".number_format($jumlah,0,',','.')." )":"".number_format($jumlah,0,',','.'))?></td>
                                            </tr>
                                           
                                        </table>
            </div>
					</div>
					</a>
				</div><!-- sl-item -->
<?} ?>

			</div><!-- streamline user-activity -->
		</div><!-- panel-body -->
	</div><!-- panel-wrapper collapse in -->
	
</div>

</div><!-- new -->
