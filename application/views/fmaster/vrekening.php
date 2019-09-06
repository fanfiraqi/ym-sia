
<div class="row">
	<div class="col-xs-12">
    <!-- Custom Tabs 
		<div class="nav-tabs-custom">-->
			<ul class="nav nav-tabs">
				<li><a href="#copy_akun" class="active" data-toggle="tab">Buat akun cabang</a></li>
				<li ><a href="#level2" data-toggle="tab">Level 2</a></li>
                <li><a href="#level3" data-toggle="tab">Level 3</a></li>
                <li><a href="#level4" data-toggle="tab">Level 4</a></li>
                <li><a href="#level5" data-toggle="tab">Level 5</a></li>
				 <li><a href="#view" data-toggle="tab">View Hirarki</a></li>
				 
            </ul>
            <div class="tab-content">
				<div class="tab-pane fade active in" id="copy_akun">
                <?php $this->load->view('fmaster/vrekening_frmcopy'); ?>
				</div><!-- /.tab-pane -->
				<div class="tab-pane" id="level2">
                <?php $this->load->view('fmaster/vrekening_level2'); ?>
				</div><!-- /.tab-pane -->
				<div class="tab-pane" id="level3">
				<?php $this->load->view('fmaster/vrekening_level3'); ?>
				</div><!-- /.tab-pane -->
				<div class="tab-pane" id="level4">
				<?php $this->load->view('fmaster/vrekening_level4'); ?>
				</div><!-- /.tab-pane -->
				<div class="tab-pane" id="level5">
				<?php $this->load->view('fmaster/vrekening_level5'); ?>
				</div><!-- /.tab-pane -->
				<div class="tab-pane" id="view">
				<?php $this->load->view('fmaster/vrekening_view'); ?>
				</div><!-- /.tab-pane -->
            </div><!-- /.tab-content -->

		<!--</div> nav-tabs-custom -->
	</div><!-- /.col -->
</div><!-- /.row-->