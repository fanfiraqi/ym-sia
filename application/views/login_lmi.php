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
    <title>SI-AKUNTANSI   <?php if (isset($pagetitle)) echo ' - '.$pagetitle;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Charisma, a fully featured, responsive, HTML5, Bootstrap admin template.">
    <meta name="author" content="Muhammad Usman">

    <!-- The styles -->
    <link id="bs-css" href="<?php echo base_url('assets/css/bootstrap-cerulean.min.css');?>" rel="stylesheet">

    <link href="<?php echo base_url('assets/css/charisma-app.css');?>" rel="stylesheet">   
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

    <!-- jQuery -->
    <script src="<?php echo base_url('assets/bower_components/jquery/jquery.min.js');?>"></script>
	<!-- external javascript -->
<script src="<?php echo base_url('assets/bower_components/bootstrap/dist/js/bootstrap.min.js');?>"></script>

<!-- library for cookie management -->
<script src="<?php echo base_url('assets/js/jquery.cookie.js');?>"></script>
<!-- calender plugin -->
<script src="<?php echo base_url('assets/bower_components/moment/min/moment.min.js');?>"></script>
<script src="<?php echo base_url('assets/bower_components/fullcalendar/dist/fullcalendar.min.js');?>"></script>
<!-- data table plugin -->
<script src="<?php echo base_url('assets/js/jquery.dataTables.min.js');?>"></script>

<!-- select or dropdown enhancer -->
<script src="<?php echo base_url('assets/bower_components/chosen/chosen.jquery.min.js');?>"></script>
<!-- plugin for gallery image view -->
<script src="<?php echo base_url('assets/bower_components/colorbox/jquery.colorbox-min.js');?>"></script>
<!-- notification plugin -->
<script src="<?php echo base_url('assets/js/jquery.noty.js');?>"></script>
<!-- library for making tables responsive -->
<script src="<?php echo base_url('assets/bower_components/responsive-tables/responsive-tables.js');?>"></script>
<!-- tour plugin -->
<script src="<?php echo base_url('assets/bower_components/bootstrap-tour/build/js/bootstrap-tour.min.js');?>"></script>
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
<!-- application script for Charisma demo -->
<script src="<?php echo base_url('assets/js/charisma.js');?>"></script>
<!-- <script src="<?php echo base_url('assets/js/main.script.js');?>"></script>
 -->


    <!-- The fav icon -->
    <link rel="shortcut icon" href="<?php echo base_url('assets/img/favicon.ico');?>">

</head>

<body>
<div class="ch-container">
    <div class="row">
        
    <div class="row">
        <div class="col-md-12 center login-header">
            <h2>Welcome to SIA Login Page <br><?php echo strtoupper($this->session->userdata('param_company'))?>  </h2>
        </div>
        <!--/span-->
    </div><!--/row-->

    <div class="row">
        <div class="well col-md-5 center login-box">
            <div class="alert alert-info">
                Please login with your Username and Password.
            </div>
			
            <!-- <form class="form-horizontal" action="#" method="post"> -->
                <fieldset>
                    <div class="input-group input-group-lg">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user red"></i></span>
                        <input type="text" class="form-control" name="username" id="username"  placeholder="Username">
                    </div>
                    <div class="clearfix"></div><br>

                    <div class="input-group input-group-lg">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock red"></i></span>
                        <input type="password" name="password"  id="password" class="form-control" placeholder="Password">
                    </div>
                    <div class="clearfix"></div>

                    <div class="input-prepend">
                        <label class="remember" for="remember"><input type="checkbox" id="remember"> Remember me</label>
                    </div>
                    <div class="clearfix"></div>

                    <p class="center col-md-5">
                        <button  class="btn btn-primary" onclick="dologin()">Login</button>
                    </p>
                </fieldset>
				<div id="formmsg" class="no-display alert alert-danger"></div>
            <!-- </form> -->
        </div>
        <!--/span-->
    </div><!--/row-->
</div><!--/fluid-row-->

</div><!--/.fluid-container-->

<script type="text/javascript">
function dologin(){
	$('#formmsg').html('').fadeOut();
	var username = $('#username').val();
	var password = $('#password').val();
	
	if (username=='' || password==''){
		$('#formmsg').html('Username dan Password harus diisi.').fadeIn();
	} else {
		//alert("<?php echo base_url('/'); ?>");
		$.ajax({
			url: "<?php echo base_url('pengguna/dologin'); ?>",
			dataType: 'json',
			type: 'POST',
			data: {username:username,password:password},
			success:   
			function(data){
				console.log(data);				
				if(data.response =="true"){
					//alert('masuk');
					window.location.replace('<?php echo base_url('/');?>');
				} else {
					$('#formmsg').html('Username atau Password salah').fadeIn();
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				
				alert('Terjadi kesalahan.<br />'+	textStatus + ' - ' + errorThrown );
			},
		});
	}
}
$('input').keydown(function(event) {
		if(event.keyCode == 13) {
		  dologin();
		}
	});
</script>

</Body >
</Html >


