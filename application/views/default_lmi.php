<!DOCTYPE html>
<html lang="en">
<head>
    <!--
        ===
        This comment should NOT be removed.

        Charisma v2.0.0

        Copyright 2012-2014 Muhammad Usman
        Licensed under the Apache License v2.0
        http://www.apache.org/licenses/LICENSE-2.0

        http://usman.it
        http://twitter.com/halalit_usman
        ===
    -->
    <meta charset="utf-8">
    <title>SI-AKUNTANSI LMI  <?php if (isset($pagetitle)) echo ' - '.$pagetitle;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Charisma, a fully featured, responsive, HTML5, Bootstrap admin template.">
    <meta name="author" content="Muhammad Usman">

    <!-- The styles -->
    <link id="bs-css" href="<?php echo base_url('assets/css/bootstrap-cerulean.min.css');?>" rel="stylesheet">

   <link href="<?php echo base_url('assets/css/charisma-app.css');?>" rel="stylesheet">
   <link href="<?php echo base_url('assets/bower_components/fullcalendar/dist/fullcalendar.css');?>" rel='stylesheet'>
   <link href="<?php echo base_url('assets/bower_components/fullcalendar/dist/fullcalendar.print.css');?>" rel='stylesheet' media='print'>
   <link href="<?php echo base_url('assets/bower_components/chosen/chosen.min.css');?>" rel='stylesheet'>
   <link href="<?php echo base_url('assets/bower_components/colorbox/example3/colorbox.css');?>" rel='stylesheet'>
   <link href="<?php echo base_url('assets/bower_components/responsive-tables/responsive-tables.css');?>" rel='stylesheet'>
   <link href="<?php echo base_url('assets/bower_components/bootstrap-tour/build/css/bootstrap-tour.min.css');?>" rel='stylesheet'>
   <link href="<?php echo base_url('assets/css/jquery.noty.css');?>" rel='stylesheet'>
   <link href="<?php echo base_url('assets/css/noty_theme_default.css');?>" rel='stylesheet'>
   <link href="<?php echo base_url('assets/css/elfinder.min.css');?>" rel='stylesheet'>
   <link href="<?php echo base_url('assets/css/elfinder.theme.css');?>" rel='stylesheet'>
   <link href="<?php echo base_url('assets/css/jquery.iphone.toggle.css');?>" rel='stylesheet'>
   <link href="<?php echo base_url('assets/css/uploadify.css');?>" rel='stylesheet'>
   <link href="<?php echo base_url('assets/css/animate.min.css');?>" rel='stylesheet'>
   <link href="<?php echo base_url('assets/css/datepicker.css');?>" rel="stylesheet" type="text/css" />
   <link href="<?php echo base_url('assets/css/jquery-ui/jquery-ui-1.10.4.custom.min.css');?>" rel="stylesheet" type="text/css" />
		<!-- Morris charts -->
   <!-- font Awesome -->
        <link href="<?php echo base_url('assets/css/font-awesome.min.css');?>" rel="stylesheet" type="text/css" />
   <!-- DATA TABLES -->
	<link href="<?php echo base_url('assets/css/datatables/dataTables.bootstrap.css');?>" rel="stylesheet" type="text/css" />

    <!-- jQuery -->
    <script src="<?php echo base_url('assets/bower_components/jquery/jquery.min.js');?>"></script>
	<script src="<?php echo base_url('assets/bower_components/bootstrap/dist/js/bootstrap.min.js');?>"></script>

	<!-- library for cookie management -->
	 <script src="<?php echo base_url('assets/js/jquery.cookie.js');?>"></script> 
	<!-- calender plugin -->
	 <script src="<?php echo base_url('assets/bower_components/moment/min/moment.min.js');?>"></script> 
	 <!-- <script src="<?php echo base_url('assets/bower_components/fullcalendar/dist/fullcalendar.min.js');?>"></script> --> 
	
	<!-- data table plugin -->
	<script src="<?php echo base_url('assets/js/datatables/jquery.dataTables.js');?>"></script>
	<script src="<?php echo base_url('assets/js/datatables/dataTables.bootstrap.js');?>"></script>

	<!-- select or dropdown enhancer -->
	<script src="<?php echo base_url('assets/bower_components/chosen/chosen.jquery.min.js');?>"></script>
	<!-- plugin for gallery image view -->
	<script src="<?php echo base_url('assets/bower_components/colorbox/jquery.colorbox-min.js');?>"></script>
	<!-- notification plugin -->
	<script src="<?php echo base_url('assets/js/jquery.noty.js');?>"></script>
	<!-- library for making tables responsive -->
	<script src="<?php echo base_url('assets/bower_components/responsive-tables/responsive-tables.js');?>"></script>
	<!-- tour plugin -->
	<!-- <script src="<?php echo base_url('assets/bower_components/bootstrap-tour/build/js/bootstrap-tour.min.js');?>"></script> -->
	<!-- star rating plugin -->
	 <script src="<?php echo base_url('assets/js/jquery.raty.min.js');?>"></script> 
	<!-- for iOS style toggle switch -->
	<script src="<?php echo base_url('assets/js/jquery.iphone.toggle.js');?>"></script>
	<!-- autogrowing textarea plugin -->
	<script src="<?php echo base_url('assets/js/jquery.autogrow-textarea.js');?>"></script>
	<!-- multiple file upload plugin -->
	 <script src="<?php echo base_url('assets/js/jquery.uploadify-3.1.min.js');?>"></script> 
	<!-- history.js for cross-browser state change on ajax -->
	<script src="<?php echo base_url('assets/js/jquery.history.js');?>"></script>
	<script src="<?php echo base_url('assets/js/jquery-ui-1.10.4.custom.min.js');?>" type="text/javascript"></script>
	<script src="<?php echo base_url('assets/js/input-mask/jquery.inputmask.js');?>" type="text/javascript"></script>
	<script src="<?php echo base_url('assets/js/input-mask/jquery.inputmask.date.extensions.js');?>" type="text/javascript"></script>
	<script src="<?php echo base_url('assets/js/input-mask/jquery.inputmask.extensions.js');?>" type="text/javascript"></script>
	<script src="<?php echo base_url('assets/js/datepicker/bootstrap-datepicker.js');?>" type="text/javascript"></script>
	<!-- application script for Charisma demo -->	
	<script src="<?php echo base_url('assets/js/charisma.js');?>"></script>
	<script src="<?php echo base_url('assets/js/main.scripts.js');?>"></script> 
	<script src="<?php echo base_url('assets/js/jquery.maskMoney.js');?>" type="text/javascript"></script>
	<script src="<?php echo base_url('assets/js/bootbox.min.js');?>" type="text/javascript"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.price_format.js');?>"></script>
	
    <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="<?php echo base_url('assets/http://html5shim.googlecode.com/svn/trunk/html5.js');?>"></script>
    <![endif]-->

    <!-- The fav icon -->
    <link rel="shortcut icon" href="<?php echo base_url('assets/img/favicon.ico');?>">

</head>

<body>
    <!-- topbar starts -->
    <div class="navbar navbar-default" role="navigation">

        <div class="navbar-inner">
            <button type="button" class="navbar-toggle pull-left animated flip">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.html"> <span>SI-Akuntansi</span> <img alt="LMI Logo" src="<?php echo base_url('assets/img/logo20.png');?>" class="hidden-xs"/>
               </a>

           <?php echo $this->load->view('header');?>

        </div>
    </div>
    <!-- topbar ends -->
<div class="ch-container">
    <div class="row">
        
        <!-- left menu starts -->
       <?php echo $this->load->view('sidebar');?>
        <!--/span-->
        <!-- left menu ends -->

       

	<div id="content" class="col-lg-10 col-sm-10">
            <!-- content starts -->
		<div>
			<ul class="breadcrumb">
			<?php if (isset($breadcrumbs)) 
								echo $breadcrumbs; 
							else 
								echo "&nbsp;";
			?>		
			</ul>		
		</div>

		<?php echo $contents; ?>

		<!-- content ends -->
	</div><!--/#content.col-md-0-->
</div><!--/fluid-row-->
 
</div><!--/.fluid-container-->

<!-- external javascript -->

		
	



</body>
</html>
