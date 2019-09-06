<?
$setMenu=$this->config->item('mymenu');
$setSubMenu=$this->config->item('mysubmenu');
$role=$this->session->userdata('auth')->ROLE;
?>
 <div class="col-sm-2 col-lg-2">
            <div class="sidebar-nav" style="overflow:scroll; height:80%;">
                <div class="nav-canvas">
                    <div class="nav-sm nav nav-stacked">

                    </div>
                    <ul class="nav nav-pills nav-stacked main-menu">
                        <!-- <li class="nav-header">Main</li> -->
                        <li><?php echo anchor('dashboard/index','<i class="glyphicon glyphicon-home"></i> Dashboard','class="ajax-link"');?></li>
						
						<?php if ($role=="Admin" || $role=="Accounting" || $role=="Manager" ){?>
						<li class="accordion <?=($setMenu=="menuSatu"?"active":"")?>">
                            <a href="#"><i class="glyphicon glyphicon-cog"></i><span> Pengaturan</span><span class="caret"></span></a>
                            <ul class="nav nav-pills nav-stacked">
							<?php if ($role=="Admin" || $role=="Manager"){ 
								 if ($role=="Admin"){?>
                                <li <?=($setSubMenu=="menuSatuSatu"?'class="active"':'')?>><?php echo anchor('setting/parameter','<i class="glyphicon glyphicon-circle-arrow-right"></i> Parameter');?></li>
                               <li <?=($setSubMenu=="menuSatuDua"?'class="active"':'')?>><?php echo anchor('pengguna/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> Pengguna');?></li>
								<?}?>
								<li <?=($setSubMenu=="menuSatuTujuh"?'class="active"':'')?>><?php echo anchor('cabang/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> Cabang');?></li>
                                <li <?=($setSubMenu=="menuSatuTiga"?'class="active"':'')?>> <?php echo anchor('periode/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> Periode Buku');?></li>
                                <li <?=($setSubMenu=="menuSatuEmpat"?'class="active"':'')?>> <?php echo anchor('rekeningakun/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> Rekening Perkiraan ');?></li>
								 <li <?=($setSubMenu=="menuSatuDelapan"?'class="active"':'')?>> <?php echo anchor('setSaldo/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> Set Saldo Awal');?></li>
								<? } ?>                               
                                <li <?=($setSubMenu=="menuSatuEnam"?'class="active"':'')?>> <?php echo anchor('fixedasset/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> Fixed Asset Management');?></li>
                            </ul>
                        </li>
                        <?	}	?>
						
						<li class="accordion <?=($setMenu=="menuDua"?"active":"")?>">
                            <a href="#"><i class="glyphicon  glyphicon-file"></i><span> Rencana Anggaran</span><span class="caret"></span></a>
                            <ul class="nav nav-pills nav-stacked">							
								<li  <?=($setSubMenu=="menuDuaSatu"?'class="active"':'')?>><?php echo anchor('rkat_item/master','<i class="glyphicon glyphicon-circle-arrow-right"></i> Set Master Item');?></li>
                                <li  <?=($setSubMenu=="menuDuaDua"?'class="active"':'')?>><?php echo anchor('rkat/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> RKAT');?></li>								
                                <li <?=($setSubMenu=="menuDuaTiga"?'class="active"':'')?>><?php echo anchor('rpb/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> RPB');?></li>								
								 <li <?=($setSubMenu=="menuDuaEmpat"?'class="active"':'')?>><?php echo anchor('rpm/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> RPM');?></li>
							<? if ($this->session->userdata('auth')->ID_CABANG<=1 && ($this->session->userdata('auth')->ROLE=="Admin" || $this->session->userdata('auth')->ROLE=="Accounting")){	?>
                                <li <?=($setSubMenu=="menuDuaLima"?'class="active"':'')?>> <?php echo anchor('rkat_docstatus/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> Status Dokumen');?></li>                               
								<? } ?>
                            </ul>
                        </li>

						<?php if ($role!="DirekturAll" && $role!="Direktur"  && $role!="Staff Keuangan" ){?>
						<li class="accordion <?=($setMenu=="menuTiga"?"active":"")?>">
                            <a href="#"><i class="glyphicon glyphicon-list-alt"></i><span> Jurnal</span><span class="caret"></span></a>
                            <ul class="nav nav-pills nav-stacked">
							<?php if ($role=="Admin" ||  $role=="Manager"){?>
                                <li <?=($setSubMenu=="menuTigaEnam"?'class="active"':'')?>><?php echo anchor('pengunci/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> Pengunci Transaksi');?></li>
							<? } ?>
							<?php if ($role=="Accounting" || substr($role, 0, 5)=="Kasir"){?>
							<!-- modul jurnal zis sementara dinonaktifkan -->
							 <?php if ($role=="Accounting" || $role=="Kasir"){?>
                                <li <?=($setSubMenu=="menuTigaSatu"?'class="active"':'')?>><?php echo anchor('pendapatanzis/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> Pendapatan ZIS');?></li>
							<? }?>

                                <li <?=($setSubMenu=="menuTigaDua"?'class="active"':'')?>><?php echo anchor('kasmasuk/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> Penerimaan Kas & Bank Umum');?></li>
                                <li <?=($setSubMenu=="menuTigaTiga"?'class="active"':'')?>><?php echo anchor('kaskeluar/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> Pengeluaran Kas & Bank');?></li>
								<?php if ($role=="Accounting"){?>	<!-- selain kasir -->
								 <li <?=($setSubMenu=="menuTigaLima"?'class="active"':'')?>><?php echo anchor('jurnalnonkas/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> Jurnal Umum');?></li>
                                <li <?=($setSubMenu=="menuTigaEmpat"?'class="active"':'')?>><?php echo anchor('penyusutan/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> Penyusutan Fixed Asset');?></li>
								<li <?=($setSubMenu=="menuTigaTujuh"?'class="active"':'')?>><?php echo anchor('validasijurnal/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> Validasi Jurnal');?></li>
									<? } ?>
							<? } ?>
                            </ul>
                        </li>
						 <?	}	?>
						
						<li class="accordion <?=($setMenu=="menuEmpat"?"active":"")?>">
                            <a href="#"><i class="glyphicon glyphicon-envelope"></i><span> Buku Pembantu Piutang</span><span class="caret"></span></a>
                            <ul class="nav nav-pills nav-stacked">
                                <li <?=($setSubMenu=="menuEmpatSatu"?'class="active"':'')?>><?php echo anchor('pinjaman/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> Pinjaman');?></li>
								<li <?=($setSubMenu=="menuEmpatDua"?'class="active"':'')?>><?php echo anchor('angsuran/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> Bayar Angsuran');?></li>
                            </ul>
                        </li>

						<li class="accordion <?=($setMenu=="menuLima"?"active":"")?>">
                            <a href="#"><i class="glyphicon glyphicon-print"></i><span> Laporan</span><span class="caret"></span></a>
                            <ul class="nav nav-pills nav-stacked">
								<li <?=($setSubMenu=="menuLima1"?'class="active"':'')?>><?php echo anchor('rptPiutang/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> Data Pinjaman');?></li>
								<li <?=($setSubMenu=="menuLima2"?'class="active"':'')?>><?php echo anchor('rptBank/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> Bank Register');?></li>
                                <li <?=($setSubMenu=="menuLima3"?'class="active"':'')?>><?php echo anchor('rptCash/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> Cash Register');?></li>								
								<li <?=($setSubMenu=="menuLima4"?'class="active"':'')?>><?php echo anchor('rptBukuBesar/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> Buku Besar');?></li>								
								<li <?=($setSubMenu=="menuLima5"?'class="active"':'')?>><?php echo anchor('rptPendapatanZIS/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> L. Pendapatan ZIS');?></li>
							<? if ($this->session->userdata('auth')->ID_CABANG > 1) {	?>
								<li <?=($setSubMenu=="menuLima6"?'class="active"':'')?> ><?php echo anchor('rptRealisasi/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> L. Realisasi Anggaran');?></li>
							<? }else if ($this->session->userdata('auth')->ROLE=="Admin"){ ?>
								<li <?=($setSubMenu=="menuLima6"?'class="active"':'')?> ><?php echo anchor('rptRealisasi/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> L. Realisasi Anggaran');?></li>
							<? } ?>
								<li <?=($setSubMenu=="menuLima7"?'class="active"':'')?>><?php echo anchor('rptCashFlow/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> L. Arus Kas');?></li>
								<li <?=($setSubMenu=="menuLima8"?'class="active"':'')?>><?php echo anchor('rptPerubahanDana/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> L. Perubahan Dana');?></li>
								<li <?=($setSubMenu=="menuLima9"?'class="active"':'')?>><?php echo anchor('rptAsset/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> L. Aset Kelolaan');?></li>

								<li <?=($setSubMenu=="menuLima10"?'class="active"':'')?>><?php echo anchor('rptPosisi/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> L. Posisi Keuangan');?></li>	
								<li <?=($setSubMenu=="menuLima12"?'class="active"':'')?>><?php echo anchor('rptPenjNeraca/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> Penj L. Posisi Keuangan');?></li>	
								
								<li <?=($setSubMenu=="menuLima11"?'class="active"':'')?>><?php echo anchor('rptPosisiKonsolidasi/index','<i class="glyphicon glyphicon-circle-arrow-right"></i> L. Posisi Keuangan Konsolidasi');?></li>
							 </ul>
                        </li>


				   </ul>                    
                </div>
            </div>
			<div  style="position:fixed; top:95%;z-index:1080;"><label  style="text-align:center; font-size:x-small;">User Firefox Browser<br>&copy; <a href="http://amanahsolution.com" target="_blank">amanahsolution.com</a> 2015</label></div>
        </div>