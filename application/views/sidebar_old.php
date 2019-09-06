`<?
$setMenu=$this->config->item('mymenu');
$setSubMenu=$this->config->item('mysubmenu');
$role=$this->session->userdata('auth')->ROLE;
?>
<div id="sidebar" class="sidebar responsive ace-save-state">
				<script type="text/javascript">
					try{ace.settings.loadState('sidebar')}catch(e){}
				</script>

				<div class="sidebar-shortcuts" id="sidebar-shortcuts">
					<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
					<?	if ($role=="superadmin" || $role=="administrator_sia"){ ?>
						<?php echo anchor('rekeningakun/index','<i class="ace-icon fa fa-cogs"></i>', array('class'=>'btn  btn-purple'));?>
						<? } ?>
						<?php echo anchor('cabang/index','<i class="ace-icon fa fa-pencil"></i>', array('class'=>'btn btn-info'));?>
						<?php echo anchor('rptBukuBesar/index','<i class="ace-icon glyphicon  fa fa-print "></i>', array('class'=>'btn btn-warning'));?>
						<?php echo anchor('rptPerubahanDana/index','<i class="ace-icon fa fa-signal"></i>', array('class'=>'btn btn-success'));?>
						
						
					</div>

					<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
						<span class="btn btn-success"></span>

						<span class="btn btn-info"></span>

						<span class="btn btn-warning"></span>

						<span class="btn btn-danger"></span>
					</div>
				</div><!-- /.sidebar-shortcuts -->

				<ul class="nav nav-list">
			
					 <!-- <li class="nav-header">Main</li> -->
                        <li><?php echo anchor('dashboard/index','<i class="menu-icon fa fa-home"></i> <span class="menu-text"> Dashboard </span>','class="ajax-link"');?></li>
						
						<?php if ($role=="superadmin" || $role=="administrator_sia" || $role=="Accounting" || $role=="Manager" ){?>
						<li class=" <?=($setMenu=="menuSatu"?"active open":"")?>">
                            <a href="#" class="dropdown-toggle"><i class="menu-icon fa fa-cog"></i><span> Pengaturan</span><b class="arrow fa fa-angle-down"></b></a>
                            <ul  class="submenu">
							<?php if ($role=="superadmin" || $role=="administrator_sia" || $role=="Manager"){ 
								 if ($role=="superadmin" || $role=="administrator_sia"){?>
                                <li <?=($setSubMenu=="menuSatuSatu"?'class="active"':'')?>><?php echo anchor('setting/parameter','<i class="menu-icon fa fa-circle-arrow-right"></i> Parameter');?></li>
                               <li <?=($setSubMenu=="menuSatuDua"?'class="active"':'')?>><?php echo anchor('pengguna/index','<i class="menu-icon fa fa-circle-arrow-right"></i> Pengguna');?></li>
								<?}?>
								<li <?=($setSubMenu=="menuSatuTujuh"?'class="active"':'')?>><?php echo anchor('cabang/index','<i class="menu-icon fa fa-circle-arrow-right"></i> Cabang');?></li>
                                <li <?=($setSubMenu=="menuSatuTiga"?'class="active"':'')?>> <?php echo anchor('periode/index','<i class="menu-icon fa fa-circle-arrow-right"></i> Periode Buku');?></li>
                                <li <?=($setSubMenu=="menuSatuEmpat"?'class="active"':'')?>> <?php echo anchor('rekeningakun/index','<i class="menu-icon fa fa-circle-arrow-right"></i> Rekening Perkiraan ');?></li>
								 <li <?=($setSubMenu=="menuSatuDelapan"?'class="active"':'')?>> <?php echo anchor('setSaldo/index','<i class="menu-icon fa fa-circle-arrow-right"></i> Set Saldo Awal');?></li>
								<? } ?>                               
                                <li <?=($setSubMenu=="menuSatuEnam"?'class="active"':'')?>> <?php echo anchor('fixedasset/index','<i class="menu-icon fa fa-circle-arrow-right"></i> Fixed Asset Management');?></li>
                            </ul>
                        </li>
                        <?	}	?>
						
						
						<?php if ($role!="DirekturAll" && $role!="Direktur"  && $role!="Staff Keuangan" ){?>
						<li class=" <?=($setMenu=="menuTiga"?"active":"")?>">
                            <a href="#" class="dropdown-toggle"><i class="menu-icon fa fa-list-alt"></i><span> Jurnal</span><b class="arrow fa fa-angle-down"></b></a>
                            <ul  class="submenu">
							<?php if ($role=="superadmin" || $role=="administrator_sia" ||  $role=="Manager"){?>
                                <li <?=($setSubMenu=="menuTigaEnam"?'class="active"':'')?>><?php echo anchor('pengunci/index','<i class="menu-icon fa fa-circle-arrow-right"></i> Pengunci Transaksi');?></li>
							<? } ?>
							<?php if ($role=="Accounting" || substr($role, 0, 5)=="Kasir"){?>
							<!-- modul jurnal zis sementara dinonaktifkan -->
							 <?php if ($role=="Accounting" || $role=="Kasir"){?>
                                <li <?=($setSubMenu=="menuTigaSatu"?'class="active"':'')?>><?php echo anchor('pendapatanzis/index','<i class="menu-icon fa fa-circle-arrow-right"></i> Pendapatan ZIS');?></li>
							<? }?>

                                <li <?=($setSubMenu=="menuTigaDua"?'class="active"':'')?>><?php echo anchor('kasmasuk/index','<i class="menu-icon fa fa-circle-arrow-right"></i> Penerimaan Kas & Bank Umum');?></li>
                                <li <?=($setSubMenu=="menuTigaTiga"?'class="active"':'')?>><?php echo anchor('kaskeluar/index','<i class="menu-icon fa fa-circle-arrow-right"></i> Pengeluaran Kas & Bank');?></li>
								<?php if ($role=="Accounting"){?>	<!-- selain kasir -->
								 <li <?=($setSubMenu=="menuTigaLima"?'class="active"':'')?>><?php echo anchor('jurnalnonkas/index','<i class="menu-icon fa fa-circle-arrow-right"></i> Jurnal Umum');?></li>
                                <li <?=($setSubMenu=="menuTigaEmpat"?'class="active"':'')?>><?php echo anchor('penyusutan/index','<i class="menu-icon fa fa-circle-arrow-right"></i> Penyusutan Fixed Asset');?></li>
								<li <?=($setSubMenu=="menuTigaTujuh"?'class="active"':'')?>><?php echo anchor('validasijurnal/index','<i class="menu-icon fa fa-circle-arrow-right"></i> Validasi Jurnal');?></li>
									<? } ?>
							<? } ?>
                            </ul>
                        </li>
						 <?	}	?>
						
						<li class=" <?=($setMenu=="menuEmpat"?"active":"")?>">
                            <a href="#" class="dropdown-toggle"><i class="menu-icon fa fa-envelope"></i><span> Buku Piutang</span><b class="arrow fa fa-angle-down"></b></a>
                            <ul  class="submenu">
                                <li <?=($setSubMenu=="menuEmpatSatu"?'class="active"':'')?>><?php echo anchor('pinjaman/index','<i class="menu-icon fa fa-circle-arrow-right"></i> Pinjaman');?></li>
								<li <?=($setSubMenu=="menuEmpatDua"?'class="active"':'')?>><?php echo anchor('angsuran/index','<i class="menu-icon fa fa-circle-arrow-right"></i> Bayar Angsuran');?></li>
                            </ul>
                        </li>

						<li class=" <?=($setMenu=="menuLima"?"active":"")?>">
                            <a href="#" class="dropdown-toggle"><i class="menu-icon fa fa-print"></i><span> Laporan</span><b class="arrow fa fa-angle-down"></b></a>
                            <ul  class="submenu">
								<li <?=($setSubMenu=="menuLima1"?'class="active"':'')?>><?php echo anchor('rptPiutang/index','<i class="menu-icon fa fa-circle-arrow-right"></i> Data Pinjaman');?></li>
								<li <?=($setSubMenu=="menuLima2"?'class="active"':'')?>><?php echo anchor('rptBank/index','<i class="menu-icon fa fa-circle-arrow-right"></i> Bank Register');?></li>
                                <li <?=($setSubMenu=="menuLima3"?'class="active"':'')?>><?php echo anchor('rptCash/index','<i class="menu-icon fa fa-circle-arrow-right"></i> Cash Register');?></li>								
								<li <?=($setSubMenu=="menuLima4"?'class="active"':'')?>><?php echo anchor('rptBukuBesar/index','<i class="menu-icon fa fa-circle-arrow-right"></i> Buku Besar');?></li>								
								<li <?=($setSubMenu=="menuLima5"?'class="active"':'')?>><?php echo anchor('rptPendapatanZIS/index','<i class="menu-icon fa fa-circle-arrow-right"></i> L. Pendapatan ZIS');?></li>
							<? if ($this->session->userdata('auth')->id_cabang > 1) {	?>
								<li <?=($setSubMenu=="menuLima6"?'class="active"':'')?> ><?php echo anchor('rptRealisasi/index','<i class="menu-icon fa fa-circle-arrow-right"></i> L. Realisasi Anggaran');?></li>
							<? }else if ($this->session->userdata('auth')->ROLE=="Admin"){ ?>
								<li <?=($setSubMenu=="menuLima6"?'class="active"':'')?> ><?php echo anchor('rptRealisasi/index','<i class="menu-icon fa fa-circle-arrow-right"></i> L. Realisasi Anggaran');?></li>
							<? } ?>
								<li <?=($setSubMenu=="menuLima7"?'class="active"':'')?>><?php echo anchor('rptCashFlow/index','<i class="menu-icon fa fa-circle-arrow-right"></i> L. Arus Kas');?></li>
								<li <?=($setSubMenu=="menuLima8"?'class="active"':'')?>><?php echo anchor('rptPerubahanDana/index','<i class="menu-icon fa fa-circle-arrow-right"></i> L. Perubahan Dana');?></li>
								<li <?=($setSubMenu=="menuLima9"?'class="active"':'')?>><?php echo anchor('rptAsset/index','<i class="menu-icon fa fa-circle-arrow-right"></i> L. Aset Kelolaan');?></li>

								<li <?=($setSubMenu=="menuLima10"?'class="active"':'')?>><?php echo anchor('rptPosisi/index','<i class="menu-icon fa fa-circle-arrow-right"></i> L. Posisi Keuangan');?></li>	
								<li <?=($setSubMenu=="menuLima12"?'class="active"':'')?>><?php echo anchor('rptPenjNeraca/index','<i class="menu-icon fa fa-circle-arrow-right"></i> Penj L. Posisi Keuangan');?></li>	
								
								<li <?=($setSubMenu=="menuLima11"?'class="active"':'')?>><?php echo anchor('rptPosisiKonsolidasi/index','<i class="menu-icon fa fa-circle-arrow-right"></i> L. Posisi Keuangan Konsolidasi');?></li>
							 </ul>
                        </li>
				</ul><!-- /.nav-list -->

				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>
			</div>