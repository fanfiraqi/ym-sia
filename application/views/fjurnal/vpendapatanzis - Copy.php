<div class="row">
	<div class="col-xs-12">
    <!-- Custom Tabs -->
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tetap" data-toggle="tab">Setoran Rutin</a></li>
                <li><a href="#insidentil" data-toggle="tab">Setoran Insidentil</a></li>
                
            </ul>
            <div class="tab-content">
				<div class="tab-pane active" id="tetap">
                <?php $this->load->view('fjurnal/vsetoranzis'); ?>
				</div><!-- /.tab-pane -->
				<div class="tab-pane" id="insidentil">
				<?php $this->load->view('fjurnal/vinsidentil'); ?>
				</div><!-- /.tab-pane -->
				
            </div><!-- /.tab-content -->
		</div><!-- nav-tabs-custom -->
	</div><!-- /.col -->
</div><!-- /.row-->

