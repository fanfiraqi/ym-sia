<div id="navbar" class="navbar navbar-default          ace-save-state">
			<div class="navbar-container ace-save-state" id="navbar-container">
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
					<span class="sr-only">Toggle sidebar</span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>
				</button>

				<div class="navbar-header pull-left">
					<a href="index.html" class="navbar-brand">
						<small>
							<i class="fa fa-bar-chart-o"></i>
							SIA Admin
						</small>
					</a>
				</div>

				<div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">
				
						<li class="light-blue dropdown-modal">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<img class="nav-user-photo" src="<?php echo base_url('assets/images/avatars/'.$this->session->userdata('auth')->avatar);?>" alt="<?php echo $this->session->userdata('auth')->name."'s Photo"?>" />
								<span class="user-info">
									<small>Welcome,</small>
									<?php echo $this->session->userdata('auth')->name?>
								</span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<!-- <li>
									<a href="#">
										<i class="ace-icon fa fa-cog"></i>
										Settings
									</a>
								</li> -->
					<? if ($this->session->userdata('auth')->ROLE == "superadmin" || $this->session->userdata('auth')->ROLE == "administrator_sia") { ?>
								<li>
									<?php echo anchor('pengguna/index','<i class="ace-icon fa fa-user"></i>Profile');?>									
								</li>
					<?}?>
								<li class="divider"></li>

								<li>
									<a href="<?php echo base_url('dashboard/logout');?>"><i class="ace-icon fa fa-power-off"></i>Logout</a>
									
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div><!-- /.navbar-container -->
		</div>