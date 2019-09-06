<? 
$setMenu=$this->config->item('mymenu');
$mySubMenu=$this->config->item('mySubMenu');
$sess=$this->session->userdata('gate');


$role=$this->config->item('myrole');
/*
1	superadmin
6	administrator_sia
8	admin_keuangan
9	admin_perbankan
11	direktur_divisi
12	direktur_laz
13	direktur_lpp
18	gm_keuangan
19	gm_operasional
26	kepala_cabang
28	manager_accounting
35	pejabat_sementara_kacab
40	spv_accounting
41	spv_internal_audit
48	staf_accounting
49	staf_admin_keuangan_program
52	staff_audit_internal
57	staff_it
58	staff_keuangan
59	staff_keuangan_ro
25	kasir_tasharuf
*/
?>
<!-- Left Sidebar Menu -->
		<div class="fixed-sidebar-left">
			<ul class="nav navbar-nav side-nav nicescroll-bar">
			<li><hr class="light-grey-hr mb-10"/></li>
			<li class="navigation-header">
				<?php echo anchor("",'<div class="pull-left"><i class="fa fa-home mr-20"></i><span class="right-nav-text">Beranda</span></div>');?>
			</li>
			<li><hr class="light-grey-hr mb-10"/></li>
			<?php if (in_array($role, [1, 6, 28, 40])) {?>
			<li <?php echo ($setMenu=="menuSatu"?'class="open"':"")?> >
				<a  href="javascript:void(0);" data-toggle="collapse"  data-target="#pengaturan_dr" ><div class="pull-left"><i class="fa fa-gear mr-20"></i><span class="right-nav-text">Pengaturan</span></div><div class="pull-right"><i class="zmdi zmdi-caret-down"></i></div><div class="clearfix"></div></a>

				<ul id="pengaturan_dr" class="collapse collapse-level-1">
					<li ><?php echo anchor('periode/index','Periode Buku', ($mySubMenu=="menuSatuTiga"?"class='active-page'":""));?></li>
					<li><?php echo anchor('rekeningakun/index','Rekening Perkiraan', ($mySubMenu=="menuSatuEmpat"?"class='active-page'":""));?></li>
					<li><?php echo anchor('setSaldo/index','Set Saldo awal', ($mySubMenu=="menuSatuDelapan"?"class='active-page'":""));?></li>
					<li><?php echo anchor('setakun/aset','Set Akun Aset', ($mySubMenu=="menuSatuEnam"?"class='active-page'":""));?></li>
					<li><?php echo anchor('setakun/donasi','Set Akun Jenis Donasi', ($mySubMenu=="menuSatuTujuh"?"class='active-page'":""));?></li>
					<li><?php echo anchor('setakun/bank','Set Akun Master Bank', ($mySubMenu=="menuSatuDelapan"?"class='active-page'":""));?></li>
				</ul>
			<li><hr class="light-grey-hr mb-10"/></li>
			
			<? } ?>
			<?php if (in_array($role, [1,6,8, 18, 26,28,35,48,58])){?>
			
			<li <?php echo ($setMenu=="menuEmpat"?' class="open" ':"")?>>
					<a href="javascript:void(0);" data-toggle="collapse" data-target="#mn_dr"><div class="pull-left"><i class="fa fa-check-square-o mr-20"></i><span class="right-nav-text">Buku Pembantu Aset</span></div><div class="pull-right"><i class="zmdi zmdi-caret-down"></i></div><div class="clearfix"></div></a>					

						<ul id="mn_dr" class="collapse collapse-level-1">
							<li><?php echo anchor('aset/index','Kelola Aset', ($mySubMenu=="menuEmpatSatu"?"class='active-page'":""));?></li>
							<li ><?php echo anchor('aset/disposal','Disposal Aset', ($mySubMenu=="menuEmpatDua"?"class='active-page'":""));?></li>							
						</ul>
			</li>
			<?}?>

			<?php if (!in_array($role, [11, 12, 13,18,19])){?>
			<li><hr class="light-grey-hr mb-10"/></li>
			<li <?php echo ($setMenu=="menuTiga"?' class="open" ':"")?>>
				<a  href="javascript:void(0);" data-toggle="collapse" data-target="#pegawai_dr"><div class="pull-left"><i class="fa fa-sitemap fa-fw mr-20"></i><span class="right-nav-text">Jurnal</span></div><div class="pull-right"><i class="zmdi zmdi-caret-down"></i></div><div class="clearfix"></div></a>
		
				<ul id="pegawai_dr" class="collapse collapse-level-1">					
							<?php if (in_array($role, [1,6,26,28,40])){?>
							<li ><?php echo anchor('pengunci/index','Pengunci Transaksi', ($mySubMenu=="menuTigaEnam"?"class='active-page'":""));?></li>
							<? } ?>
							<?php if (in_array($role, [1,6,48, 25])) {?>
							<li ><?php echo anchor('pendapatanzis/index','Pendapatan ZIS', ($mySubMenu=="menuTigaSatu"?"class='active-page'":""));?></li>
							<li ><?php echo anchor('penyaluran/index','Penyaluran Program', ($mySubMenu=="menuTigadelapan"?"class='active-page'":""));?></li>
							<li ><?php echo anchor('penggajian/index','Penggajian', ($mySubMenu=="menuTigaSembilan"?"class='active-page'":""));?></li>
							<? } ?>
							<li ><?php echo anchor('jurnalmanual/index','Jurnal Manual', ($mySubMenu=="menuTigaEmpat"?"class='active-page'":""));?></li> 
							<!-- <li ><?php echo anchor('penyusutan/index','Penyusutan Asset', ($mySubMenu=="menuTigaEmpat"?"class='active-page'":""));?></li> 
							<li ><?php echo anchor('kasmasuk/index','Penerimaan Kas/Bank ', ($mySubMenu=="menuTigaDua"?"class='active-page'":""));?></li>
							<li ><?php echo anchor('kaskeluar/index','Pengeluaran Kas/Bank', ($mySubMenu=="menuTigaTiga"?"class='active-page'":""));?></li>
							<li ><?php echo anchor('jurnalnonkas/index','Non Kas/Bank', ($mySubMenu=="menuTigaLima"?"class='active-page'":""));?></li>	-->
							<?php if (in_array($role, [1,6,26,28,40])){?>
							<li ><?php echo anchor('validasijurnal/index','Validasi Jurnal', ($mySubMenu=="menuTigaTujuh"?"class='active-page'":""));?></li>
							<? } ?>
				</ul>		
					
			</li>	
			
			<?}?>	
			
			<?php if (in_array($role, [100])){?>
			<li><hr class="light-grey-hr mb-10"/></li>
			<li <?php echo ($setMenu=="menuEmpat"?' class="open" ':"")?>>
					<a href="javascript:void(0);" data-toggle="collapse" data-target="#mn_dr"><div class="pull-left"><i class="fa fa-check-square-o mr-20"></i><span class="right-nav-text">Buku Pembantu Piutang</span></div><div class="pull-right"><i class="zmdi zmdi-caret-down"></i></div><div class="clearfix"></div></a>					

						<ul id="mn_dr" class="collapse collapse-level-1">
							<li><?php echo anchor('pinjaman/index','Pinjaman', ($mySubMenu=="menuEmpatSatu"?"class='active-page'":""));?></li>
							<li ><?php echo anchor('angsuran/index','Bayar Angsuran', ($mySubMenu=="menuEmpatDua"?"class='active-page'":""));?></li>							
						</ul>
			</li>
			<?}?>
			
			<li><hr class="light-grey-hr mb-10"/></li>
			<li <?=($setMenu=="menuLima"?"class='open'":"")?>>
				
				<a href="javascript:void(0);" data-toggle="collapse" data-target="#reporting_dr"><div class="pull-left"><i class="fa fa-print mr-20"></i><span class="right-nav-text">Laporan</span></div><div class="pull-right"><i class="zmdi zmdi-caret-down"></i></div><div class="clearfix"></div></a>
		
				<ul id="reporting_dr" class="collapse collapse-level-1">
						<li ><?php echo anchor('rptRekapJurnal/index','Rekap Jurnal', ($mySubMenu=="menuLima1"?"class='active-page'":""));?></li>
						<li ><?php echo anchor('rptBank/index','Bank Register', ($mySubMenu=="menuLima2"?"class='active-page'":""));?></li>
						<li ><?php echo anchor('rptCash/index','Cash Register', ($mySubMenu=="menuLima3"?"class='active-page'":""));?></li>
						<!-- <li ><?php echo anchor('rptPiutang/index','Data Pinjaman', ($mySubMenu=="menuLima1"?"class='active-page'":""));?></li> -->
						<li ><?php echo anchor('rptBukuBesar/index','Buku Besar', ($mySubMenu=="menuLima4"?"class='active-page'":""));?></li>						
						<li ><?php echo anchor('rptPendapatanZIS/index','Pendapatan ZIS', ($mySubMenu=="menuLima5"?"class='active-page'":""));?></li>					
						<!-- <? if ($this->session->userdata('auth')->id_cabang > 1) {	?>
								<li ><?php echo anchor('rptRealisasi/index','Realisasi Anggaran', ($mySubMenu=="menuLima6"?"class='active-page'":""));?></li>						
						<? }else if (in_array($role, [1,6, 26])){ ?>
								<li ><?php echo anchor('rptRealisasi/index','Realisasi Anggaran', ($mySubMenu=="menuLima6"?"class='active-page'":""));?></li>
						<? } ?> -->
						<li ><?php echo anchor('rptCashFlow/index','Cash Flow', ($mySubMenu=="menuLima7"?"class='active-page'":""));?></li>	
						<?  if ($role<>25){ ?>
						
						<!-- <li ><?php echo anchor('rptAktifitas/index','Aktifitas(Laba Rugi)', ($mySubMenu=="menuLima13"?"class='active-page'":""));?></li> -->						
						<li ><?php echo anchor('rptPerubahanDana/index','Perubahan Dana', ($mySubMenu=="menuLima8"?"class='active-page'":""));?></li>						
						<li ><?php echo anchor('rptAsset/index','Asset Kelolaan', ($mySubMenu=="menuLima9"?"class='active-page'":""));?></li>						
						<li ><?php echo anchor('rptPosisi/index','Posisi Keuangan', ($mySubMenu=="menuLima10"?"class='active-page'":""));?></li>						
						<li ><?php echo anchor('rptPenjNeraca/index','Penjelasan Posisi Keuangan', ($mySubMenu=="menuLima12"?"class='active-page'":""));?></li>						
						<li ><?php echo anchor('rptPosisiKonsolidasi/index','Posisi Keuangan Konsolidasi', ($mySubMenu=="menuLima11"?"class='active-page'":""));?></li>	
						<li ><?php echo anchor('rptPenjNeracaKonsolidasi/index','Penjelasan Posisi Keuangan Konsolidasi', ($mySubMenu=="menuLima11"?"class='active-page'":""));?></li>	
						<? } ?>
				</ul>
			</li>
			<li><hr class="light-grey-hr mb-10"/></li>
			</ul>
			<div  style="position:fixed; top:95%;z-index:1080;margin-left:20px;"><label  style="text-align:center; font-size:x-small;"><b>Best Running with Firefox @2018</b></label></div> 
		</div>
		<!-- /Left Sidebar Menu -->