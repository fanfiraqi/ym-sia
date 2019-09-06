<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Sistem Informasi Akuntansi <?php if (isset($pagetitle)) echo ' - '.$pagetitle;?></title>

		<meta name="description" content="overview &amp; stats" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		
		<link rel="icon" href="<?php echo base_url('assets/images/favicon.ico');?>" type="image/x-icon" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css');?>" />
		<link rel="stylesheet" href="<?php echo base_url('assets/font-awesome/4.5.0/css/font-awesome.min.css');?>" />
		<link rel="stylesheet" href="<?php echo base_url('assets/css/jquery-ui.min.css');?>" />

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="<?php echo base_url('assets/css/fonts.googleapis.com.css');?>" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?php echo base_url('assets/css/ace.min.css');?>" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="<?php echo base_url('assets/css/ace-part2.min.css');?>" class="ace-main-stylesheet" />
		<![endif]-->
		<link rel="stylesheet" href="<?php echo base_url('assets/css/ace-skins.min.css');?>" />
		<link rel="stylesheet" href="<?php echo base_url('assets/css/ace-rtl.min.css');?>" />

		<!-- ace settings handler -->
		<script src="<?php echo base_url('assets/js/ace-extra.min.js');?>"></script>

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script src="<?php echo base_url('assets/js/jquery-2.1.4.min.js');?>"></script>

		<!-- <![endif]-->

		<!--[if IE]>
		<script src="<?php echo base_url('assets/js/jquery-1.11.3.min.js');?>"></script>
		<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo base_url("assets/js/jquery.mobile.custom.min.js");?>'>"+"<"+"/script>");
		</script>
		<script src="<?php echo base_url('assets/js/bootstrap.min.js');?>"></script>
		

		<!-- page specific plugin scripts -->
		<script src="<?php echo base_url('assets/js/jquery.dataTables.min.js');?>"></script>
		<script src="<?php echo base_url('assets/js/jquery.dataTables.bootstrap.min.js');?>"></script>
		<script src="<?php echo base_url('assets/js/dataTables.buttons.min.js');?>"></script>
		<script src="<?php echo base_url('assets/js/buttons.flash.min.js');?>"></script>
		<script src="<?php echo base_url('assets/js/buttons.html5.min.js');?>"></script>
		<script src="<?php echo base_url('assets/js/buttons.print.min.js');?>"></script>
		<script src="<?php echo base_url('assets/js/buttons.colVis.min.js');?>"></script>
		<script src="<?php echo base_url('assets/js/dataTables.select.min.js');?>"></script>
		<script src="<?php echo base_url('assets/js/bootbox.js');?>"></script>

		<!-- ace scripts -->
		<script src="<?php echo base_url('assets/js/ace-elements.min.js');?>"></script>
		<script src="<?php echo base_url('assets/js/ace.min.js');?>"></script>
		<!-- page specific plugin scripts -->
		<script src="<?php echo base_url('assets/js/jquery-ui.min.js');?>"></script>
		<script src="<?php echo base_url('assets/js/jquery.maskedinput.min.js');?>"></script>

		<!-- private -->
		<script src="<?php echo base_url('assets/js/main.scripts.js');?>"></script>
		<script src="<?php echo base_url('assets/js/jquery.price_format.js');?>"></script>
		
	</head>

	<body class="no-skin">
		<?php echo $this->load->view('header');?>

		<div class="main-container ace-save-state" id="main-container">
			<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>

			<?php echo $this->load->view('sidebar');?>

			<div class="main-content">
				<div class="main-content-inner">
					<div class="breadcrumbs ace-save-state" id="breadcrumbs">
						<!--<ul class="breadcrumb">
							<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Dashboard</li>
						</ul> /.breadcrumb -->
						<!--
						<div class="nav-search" id="nav-search">
							<form class="form-search">
								<span class="input-icon">
									<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
									<i class="ace-icon fa fa-search nav-search-icon"></i>
								</span>
							</form>
						</div> /.nav-search -->
					</div>

					<div class="page-content">
						<div class="ace-settings-container" id="ace-settings-container">
							<div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
								<i class="ace-icon fa fa-cog bigger-130"></i>
							</div>

							<div class="ace-settings-box clearfix" id="ace-settings-box">
								<div class="pull-left width-50">
									<div class="ace-settings-item">
										<div class="pull-left">
											<select id="skin-colorpicker" class="hide">
												<option data-skin="no-skin" value="#438EB9">#438EB9</option>
												<option data-skin="skin-1" value="#222A2D">#222A2D</option>
												<option data-skin="skin-2" value="#C6487E">#C6487E</option>
												<option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
											</select>
										</div>
										<span>&nbsp; Choose Skin</span>
									</div>

									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-navbar" autocomplete="off" />
										<label class="lbl" for="ace-settings-navbar"> Fixed Navbar</label>
									</div>

									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-sidebar" autocomplete="off" />
										<label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
									</div>

									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-breadcrumbs" autocomplete="off" />
										<label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
									</div>

									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" autocomplete="off" />
										<label class="lbl" for="ace-settings-rtl"> Right To Left (rtl)</label>
									</div>

									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-add-container" autocomplete="off" />
										<label class="lbl" for="ace-settings-add-container">
											Inside
											<b>.container</b>
										</label>
									</div>
								</div><!-- /.pull-left -->

								<div class="pull-left width-50">
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-hover" autocomplete="off" />
										<label class="lbl" for="ace-settings-hover"> Submenu on Hover</label>
									</div>

									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-compact" autocomplete="off" />
										<label class="lbl" for="ace-settings-compact"> Compact Sidebar</label>
									</div>

									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-highlight" autocomplete="off" />
										<label class="lbl" for="ace-settings-highlight"> Alt. Active Item</label>
									</div>
								</div><!-- /.pull-left -->
							</div><!-- /.ace-settings-box -->
						</div><!-- /.ace-settings-container -->

						<div class="page-header">
						<?php if (isset($breadcrumbs)) 
											echo $breadcrumbs; 
										else 
											echo "&nbsp;";
						?>								
						</div><!-- /.page-header -->

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<?php echo $contents; ?>

								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			<div class="footer">
				<div class="footer-inner">
					<div class="footer-content">
						<span class="bigger-120">
							<span class="blue bolder">Sikos</span>
							Application 
						</span>&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;
						<span class="action-buttons">
							<?php echo anchor('dashboard/eula','<i class="menu-icon fa 	fa-gavel"></i> <span class="menu-text"> Licensing Agreement</span>','class="ajax-link"');?>
							&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;
							<a href="http://www.amanahsolution.com" target="_blank">Amanah Solution&trade;</a>&nbsp;&nbsp;&nbsp;&copy; 2012-<?php echo date('Y')?>
						</span>
					</div>
				</div>
			</div>

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		

		<!-- <![endif]-->

		<!--[if IE]>
<script src="<?php echo base_url('assets/js/jquery-1.11.3.min.js');?>"></script>
<![endif]-->
		
		<!-- page specific plugin scripts -->

		<!--[if lte IE 8]>
		  <script src="<?php echo base_url('assets/js/excanvas.min.js');?>"></script>
		<![endif]
		
		<script src="<?php echo base_url('assets/js/jquery.ui.touch-punch.min.js');?>"></script>
		<script src="<?php echo base_url('assets/js/jquery.easypiechart.min.js');?>"></script>
		<script src="<?php echo base_url('assets/js/jquery.sparkline.index.min.js');?>"></script>
		<script src="<?php echo base_url('assets/js/jquery.flot.min.js');?>"></script>
		<script src="<?php echo base_url('assets/js/jquery.flot.pie.min.js');?>"></script>
		<script src="<?php echo base_url('assets/js/jquery.flot.resize.min.js');?>"></script>-->

		

		
	</body>
</html>
