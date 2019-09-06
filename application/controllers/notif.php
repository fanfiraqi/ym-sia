<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class notif extends MY_App {

	function __construct()
	{
		parent::__construct();
		$this->auth->authorize();
	}
	
	
	function index(){
		//$role=$this->session->userdata('auth')->ROLE;
		$role=$this->config->item('myrole');
		$respon = array();
		$respon['status'] = 0;
		
		// query cek jurnal blm validasi jurnal masing2
		$query = $this->db->query("select count(*) cnt from ak_jurnal where status_validasi=0 and sumber_data='penghimpunan' ".($this->session->userdata('auth')->id_cabang==1?'':" and id_cab=".$this->session->userdata('auth')->ID_CABANG))->row();
		if ($query->cnt > 0){
			$respon['status'] = 1;
			$respon['data']['penghimpunan'] = array(
				'label' => 'Penghimpunan Pendapatan ZIS ',
				'text' => 'Jurnal Pendapatan ZIS',
				'count'=> $query->cnt,
				'url'=>(in_array($role, [ 1, 6, 28, 40]) ? (in_array($role, [ 1, 6, 28, 40]) ? base_url('validasijurnal/index'):""):"")
			);
		}
		$query = $this->db->query("select count(*) cnt from ak_jurnal where status_validasi=0 and sumber_data='penyaluran'".($this->session->userdata('auth')->id_cabang==1?'':" and id_cab=".$this->session->userdata('auth')->ID_CABANG))->row();
		if ($query->cnt > 0){
			$respon['status'] = 1;
			$respon['data']['penyaluran'] = array(
				'label' => 'Program Penyaluran',
				'text' => 'Jurnal Program Penyaluran',
				'count'=> $query->cnt,
				'url'=>(in_array($role, [ 1, 6, 28, 40]) ? base_url('validasijurnal/index'):"")
			);
		}
		$query = $this->db->query("select count(*) cnt from ak_jurnal where status_validasi=0 and sumber_data='penggajian'".($this->session->userdata('auth')->id_cabang==1?'':" and id_cab=".$this->session->userdata('auth')->ID_CABANG))->row();
		if ($query->cnt > 0){
			$respon['status'] = 1;
			$respon['data']['penggajian'] = array(
				'label' => 'Penggajian',
				'text' => 'Jurnal Penggajian',
				'count'=> $query->cnt,
				'url'=>(in_array($role, [ 1, 6, 28, 40]) ? base_url('validasijurnal/index'):"")
			);
		}
		$query = $this->db->query("select count(*) cnt from ak_jurnal where status_validasi=0 and sumber_data='kasmasuk'".($this->session->userdata('auth')->id_cabang==1?'':" and id_cab=".$this->session->userdata('auth')->ID_CABANG))->row();
		if ($query->cnt > 0){
			$respon['status'] = 1;
			$respon['data']['kasmasuk'] = array(
				'label' => 'Pemasukan Kas/Bank',
				'text' => 'Jurnal Pemasukan Kas/Bank',
				'count'=> $query->cnt,
				'url'=>(in_array($role, [ 1, 6, 28, 40]) ? base_url('validasijurnal/index'):"")
			);
		}
		$query = $this->db->query("select count(*) cnt from ak_jurnal where status_validasi=0 and sumber_data='kaskeluar'".($this->session->userdata('auth')->id_cabang==1?'':" and id_cab=".$this->session->userdata('auth')->ID_CABANG))->row();
		if ($query->cnt > 0){
			$respon['status'] = 1;
			$respon['data']['kaskeluar'] = array(
				'label' => 'Kas Keluar',
				'text' => 'Jurnal Kas Keluar ',
				'count'=> $query->cnt,
				'url'=>(in_array($role, [ 1, 6, 28, 40]) ? base_url('validasijurnal/index'):"")
			);
		}
		$query = $this->db->query("select count(*) cnt from ak_jurnal where status_validasi=0 and sumber_data='jurnalumum'".($this->session->userdata('auth')->id_cabang==1?'':" and id_cab=".$this->session->userdata('auth')->ID_CABANG))->row();
		if ($query->cnt > 0){
			$respon['status'] = 1;
			$respon['data']['jurnalumum'] = array(
				'label' => 'Jurnal Non Kas/Bank',
				'text' => 'Jurnal Non Kas/Bank',
				'count'=> $query->cnt,
				'url'=>(in_array($role, [ 1, 6, 28, 40]) ? base_url('validasijurnal/index'):"")
			);
		}

		
		
		if (in_array($role, [1,6,8, 18, 26,28,35,48,58])){

			if ($respon['status']==1){
			?>
			
			<ul class="dropdown-menu dropdown-alerts" id="notifitem">
			<?php 
				foreach ($respon['data'] as $data=>$item){
			?>
				<li>
					<a href="<?php echo $item['url'];?>">
						<div>
							<i class="fa fa-comment fa-fw"></i> <?php echo $item['text'];?>
							<span class="pull-right small"><?php echo $item['count'];?></span>
						</div>
					</a>
				</li>
			<?php } ?>
			</ul>
			<?php
			} else {
				echo '';
			}
		}

		if ($respon['status']==1){
			?>
			
			<ul  class="dropdown-menu alert-dropdown" data-dropdown-in="bounceIn" data-dropdown-out="bounceOut">
			<li>
				<div class="notification-box-head-wrap">
				<span class="notification-box-head pull-left inline-block">notifications</span>
				<div class="clearfix"></div>
				<hr class="light-grey-hr ma-0"/>
				</div>
			</li>
			<li>
			<div class="streamline message-nicescroll-bar">
			<?php 
				foreach ($respon['data'] as $data=>$item){
			?>
				
					<div class="sl-item">
					<a href="<?php echo $item['url'];?>">
					<div class="icon bg-green"><i class="zmdi zmdi-flag"></i></div>
					<div class="sl-content">
						<span class="inline-block capitalize-font  pull-left truncate head-notifications"><?php echo $item['label'];?></span>
						<span class="inline-block font-11  pull-right notifications-time"><?php echo $item['count'];?></span>
						<div class="clearfix"></div><p class="truncate"><?php echo $item['text'];?></p>					
					</div>
					</a>	
					</div>
					<hr class="light-grey-hr ma-0"/>
								
				
			<?php } ?>
			</div>
			</li>
			</ul>
			<?php
			} else {
				echo '';
			}

	}
	
}
