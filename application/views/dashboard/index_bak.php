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
                                                <td>Saldo Lalu</td>
                                                <td><?php echo ($jumlah<0?"":"Rp. ".number_format($jumlah,2,',','.'))?></td>
                                                <td><?php echo ($jumlah<0?"Rp. ".number_format($jumlah,2,',','.'):"")?></td>
                                                <td><?php echo ($jumlah<0?"( Rp. ".number_format($jumlah,2,',','.')." )":"Rp. ".number_format($jumlah,2,',','.'))?></td>
                                            </tr>
<?	$kasMasuk=$this->report_model->kasMasukHariIni();
	$jumlah=$jumlah+$kasMasuk;

											
										?>
                                            <tr>
                                                <td>Kas Masuk Hari Ini</td>
                                                <td><?php echo "Rp. ".number_format($kasMasuk,2,',','.')?></td>
                                                <td>&nbsp;</td>
                                                <td><?php echo ($jumlah<0?"( Rp. ".number_format($jumlah,2,',','.')." )":"Rp. ".number_format($jumlah,2,',','.'))?></td>
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
                                                <td><?php echo "Rp. ".number_format($kasKeluar,2,',','.')?></td> 
												<td><?php echo ($jumlah<0?"( Rp. ".number_format($jumlah,2,',','.')." )":"Rp. ".number_format($jumlah,2,',','.'))?></td>
                                            </tr>
                                            <tr>
                                                <td>Saldo Terakhir s.d <?php echo date ('d M Y')?></td>
                                                <td></td>
                                                <td></td>
                                                <td><?php echo ($jumlah<0?"( Rp. ".number_format($jumlah,2,',','.')." )":"Rp. ".number_format($jumlah,2,',','.'))?></td>
                                            </tr>
                                           
                                        </table>
            </div>
					</div>
					</a>
				</div><!-- sl-item -->
<?}
	 if (in_array($role, [1,6,8,9,25, 26, 40,48,58])){?>    
<div class="sl-item">
					<a href="javascript:void(0)">
						<div class="sl-avatar avatar avatar-sm avatar-circle">
						<img class="img-responsive img-circle" src="<?php echo base_url('assets/img/logo.png');?>" alt="avatar"/>
						</div>
					<div class="sl-content">
					<p class="inline-block"><span class="capitalize-font txt-success mr-5 weight-500">Notifikasi Pendapatan Setoran ZIS</span></p>
					<span class="block txt-grey font-12 capitalize-font"><?php echo date('d M Y')?></span>
<? /*$resZIS=$this->db->query("SELECT DISTINCT tanggal FROM tsetoranzis WHERE tanggal NOT IN
(SELECT DISTINCT tanggal FROM jurnal WHERE sumber_data='tsetoranzis'  and id_cab=".$this->session->userdata('auth')->ID_CABANG.") and substring(AGTID,1,3)='$agtid'")->result();*/
$resZIS=array();
if (sizeof($resZIS)<=0){
	?>
	 
            <div class="alert alert-success">
				<i class="fa fa-info"></i>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <b>Pemberitahuan :</b> Belum Ada Transaksi Setoran ZIS yang perlu posting ke Jurnal
				
			</div>
	
	<?
}else{
		?>
	    <div class="alert alert-danger">
				<i class="fa fa-warning"></i>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <b>Pemberitahuan :</b>Terdapat <b><?php echo sizeof($resZIS)?></b> Hari/Tanggal Transaksi Setoran ZIS yang belum diposting/divalidasi ke Jurnal Akuntansi. Untuk memproses transaksi, pilih menu "Jurnal" -> "Pendapatan ZIS" dan pilih  transaksi sesuai tanggal di bawah ini. 
			</div>
			
	<div class="row">
		<div class="col-md-10">
		 <div class="box ">
			<div class="box-header">
            <h3 class="box-title">Tanggal Tersebut antara lain :</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-warning btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button class="btn btn-warning btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
            </div> <!-- box-header -->
            <div class="box-body">
	<?
			echo "<ol>";
			foreach($resZIS as $rs){
				echo "<li>".$rs->tanggal."</li>";
			}
			echo "</ol>";
?>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

<?
		}

		?>  

					</div>
					</a>
				</div><!-- sl-item -->

<?} ?>

			</div><!-- streamline user-activity -->
		</div><!-- panel-body -->
	</div><!-- panel-wrapper collapse in -->
	
</div>

</div><!-- new -->


