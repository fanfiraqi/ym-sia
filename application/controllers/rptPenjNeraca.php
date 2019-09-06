<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class rptPenjNeraca extends MY_App {
	var $branch = array();
	function __construct()
	{
		parent::__construct();
		$this->load->model('report_model');
		$this->config->set_item('mymenu', 'menuTiga');
		$this->config->set_item('mysubmenu', 'menuTigaDelapan');
		$this->load->helper('file');
		$this->load->library('CI_Pdf');
		$this->auth->authorize();
	}
	
	public function index()
	{
		//$akunKas=$this->divTreeKas($this->common_model->comboGetrptPenjNeraca()->result_array(), '1-1100');
		$this->template->set('pagetitle','Filter Laporan Penjelasan Neraca');		
		//$data['akunKas'] = $akunKas;
		$data['arrBulan'] = $this->arrBulan2;
		$data['arrThn'] = $this->getYearArr();
		$data["jenis"]="neraca";
		$data["action"]="rptPenjNeraca/result";
		$this->template->load('default','freport/filter',$data);
	}
	
	public function result($param=null){
		$header=$this->commonlib->reportHeader();
		$footer=$this->commonlib->reportFooter();		
		$blnStr=$this->arrBulan2;
		if ($param!=null){
			$arr=explode("_",$param);
			$thn=$arr[0];
			$bln=$arr[1];
			$display=$arr[2];			
			$sesLogin=$arr[3];
			$wilayah=$arr[4];
					
			
		}else{
			$display=$this->input->post('display');
			$thn=$this->input->post('cbTahun');
			$bln=$this->input->post('cbBulan');
			$sesLogin=$this->input->post('sesLogin');
			if ($sesLogin=='center'){
					$wilayah=$this->input->post('wilayah');
			}else{
				$wilayah=$this->session->userdata('auth')->ID_CABANG;
			}
			
		}
		
		$data['sesLogin']=$sesLogin;
		if ($sesLogin=='center'){
			$data['wilayah']=$wilayah;
		}
		$cab_pilih=($sesLogin=='center'?$wilayah:$this->session->userdata('auth')->ID_CABANG);
		$rsCab=$this->gate_db->query("select KOTA from mst_cabang where ID_CABANG=".$cab_pilih)->row();
		$data['nmcabang']=$rsCab;
		$title='Laporan Penjelasan Neraca '.$rsCab->KOTA.' '.$blnStr[$bln]." ".$thn;
		$data['title']=$title;
		$data['display']=$display;
		$data['thn']=$thn;
		$data['bln']=$bln;		
		$data['strBulan']=$blnStr[$bln];

		$namafile="penjneraca_".$rsCab->KOTA.$thn.$bln;
		if ($display==0){
			$this->template->set('pagetitle',$title.' (View/Cetak)');		
			$this->template->load('default','freport/displayPenjNeraca',$data);
		}else{
			$html=$header;
			$html.=$this->load->view('freport/displayPenjNeraca', $data, true);
			$html.=$footer;
			$this->ci_pdf->pdf_create_report($html, $namafile, 'a4', 'portrait');
		}

	}
	
	
	public function toExcel(){
		$blnStr=$this->arrBulan2;
		$arr=explode("_",$this->input->post('param'));
			$thn=$arr[0];
			$bln=$arr[1];
			$display=$arr[2];			
			$strBulan=$blnStr[$bln];
			$sesLogin=$arr[3];
			if ($sesLogin=='center'){
					$wilayah=$arr[4];
			}
		
			$cab_pilih=($sesLogin=='center'?$wilayah:$this->session->userdata('auth')->ID_CABANG);
			$rsCab=$this->gate_db->query("select KOTA from mst_cabang where ID_CABANG=".$cab_pilih)->row();
		
		$objPHPExcel = new PHPExcel();
		// Set document properties
		$objPHPExcel->getProperties()->setCreator("amanahsolution.com")
									 ->setLastModifiedBy("amanahsolution.com")
									 ->setTitle("Office 2007 XLSX Report Generator Document")
									 ->setSubject("Office 2007 XLSX Report Generator Document")
									 ->setDescription("Report Generator document for Office 2007 XLSX, generated by PHP classes.")
									 ->setKeywords("office 2007 openxml php")
									->setCategory("Report Generator Document");
		
		$objPHPExcel->setActiveSheetIndex(0);

		
		//logo
		$company=$this->session->userdata('param_company');
		$logo=$this->session->userdata('logo');
		$title='Laporan Penjelasa Neraca Bulan '.$blnStr[$bln]." ".$thn;
		$namafile="lapPenjelasanNeraca_".$thn.$bln;

		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('Logo');
		$objDrawing->setDescription('Logo');
		$objDrawing->setPath('assets/img/'.$logo);
		$objDrawing->setHeight(60);
		$objDrawing->setCoordinates('A1');
		$objDrawing->setOffsetX(0);		
		$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
		
		// Rename worksheet (worksheet, not filename)
		$objPHPExcel->getActiveSheet()->setTitle('Lap_PenjelasanNeraca'.$thn.$bln);
		 
		// Header
		$objPHPExcel->getActiveSheet()->mergeCells('C1:AF1');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', $company);
		$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(20);
		$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
		
		$objPHPExcel->getActiveSheet()->mergeCells('C2:AF2');
		$objPHPExcel->getActiveSheet()->setCellValue('C2', $title);
		$objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->setSize(14);
		$objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);
		
		$objPHPExcel->getActiveSheet()->mergeCells('C3:AF3');
		$objPHPExcel->getActiveSheet()->setCellValue('C3', 'Diterbitkan Tgl : '.date('d F Y'));
		$objPHPExcel->getActiveSheet()->getStyle('C3')->getFont()->setSize(12);
		$setHeaderRow=array(
					'font'    => array(
						'bold'      => true
					),
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					),
					'borders' => array(
						'allborders'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN,
							'color' => array('argb' => '000000')
						)				
					),
					'fill' => array(
						'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
						'rotation'   => 90,
						'startcolor' => array(
							'argb' => 'FFA0A0A0'
						),
						'endcolor'   => array(
							'argb' => 'FFFFFFFF'
						)
					)
				);

//Isi
$str="SELECT `idacc`, `nama`, `kelompok`, `level`,idparent, IF(`kelompok`='A','Aktiva','Pasiva') AS jenis
		from ak_rekeningperkiraan 
		WHERE  `kelompok` IN ('A','K', 'M') AND `level`=2 and status=1 
		ORDER BY  `kelompok`,idacc";
$query=$this->db->query($str)->result();
$row=5;
		$objPHPExcel->getActiveSheet()->mergeCells('C5:G5');
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, 'PERKIRAAN')
					->setCellValue('H'.$row, 'JUMLAH');
		$objPHPExcel->getActiveSheet()->getStyle('C'.$row.':H'.$row)->applyFromArray($setHeaderRow);
		$row++;

	$i=1;
	$jenis="";
	$jumlah=array();
	
	foreach ($query as $level2){
		if ($jenis!=$level2->jenis){
			$i=1;
			if ($jenis!=""){
				$objPHPExcel->getActiveSheet()->mergeCells('C'.$row.':G'.$row);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, 'TOTAL '.strtoupper($jenis))->setCellValue('H'.$row,"Rp. ".number_format($jumlah[$jenis],2,',','.'));
				$objPHPExcel->getActiveSheet()->getStyle('C'.$row.':H'.$row)->applyFromArray($setHeaderRow);
				$row+=2;	
				
			}
		}
		if ($i==1){
			$jenis=$level2->jenis;
			$jumlah[$jenis]=0;
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, strtoupper($level2->jenis));				
			$row++;
			
		}
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, strtoupper($level2->nama));				
		$row++;
		
		//loop level 3 & get value
		$str3="select * from ak_rekeningperkiraan where level=3 and idparent='".$level2->idacc."' and status=1 order by idacc";
		$query3=$this->db->query($str3)->result();
		$jumlahL2=0;
		foreach ($query3 as $level3){
				//level 4 & 5 
				//---------------------------------------------------------------------
			$str="select * from ak_rekeningperkiraan where idparent='".$level3->idacc."'  ".($this->session->userdata('auth')->ID_CABANG==0?'':" and id_cab=".$this->session->userdata('auth')->ID_CABANG)."  and status=1";
			$resList= $this->db->query($str)->result();
				if (sizeof ($resList)<=0){	//level 3
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, strtoupper($level3->nama));
					$row++;
							
				}else{
					//cek jika punya child maka child yg ditampilkan
					foreach ($resList as $detil4){
						//level4
						$idacc=$detil4->idacc;
						$accNameDet=$this->report_model->getAccName($idacc);						
						$strCekMax4="select count(*) jcek from ak_rekeningperkiraan where idparent='".$idacc."'   order by  idacc";
						$resCekMax4= $this->db->query($strCekMax4)->row();

						if ($resCekMax4->jcek>0){	
							$strL5="select * from ak_rekeningperkiraan where idparent='".$idacc."' and status=1";
							$resSub= $this->db->query($strL5)->result();	
							$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, strtoupper($accNameDet));
							$row++;
							
							//level 5
								foreach ($resSub as $detilresSub){		//level 5
										$idaccsub=$detilresSub->idacc;
										if (substr($idaccsub,0,3)=='3-8' || substr($idaccsub,0,3)=='3-9' ){
											$neracaValue=$this->report_model->getLabaRugiValuePenj($idaccsub,  $detilresSub->id_cab, $bln, $thn);
										}else{
										//get neraca value
											$neracaValue=$this->report_model->getNeracaValuePenj($idaccsub, $bln, $thn);								
											if ($jenis=='Pasiva'){
												$neracaValue=$neracaValue*-1;
											}
										}
										$jumlah[$jenis]+=$neracaValue;
										$jumlahL2+=$neracaValue;
										
										if ($neracaValue!=0){
											$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $idaccsub." - ".strtoupper($detilresSub->nama));
											$objPHPExcel->getActiveSheet()->setCellValue('F'.$row,($neracaValue<=0? "( Rp. ".number_format(($neracaValue*-1),2,',','.').")" : "Rp. ".number_format($neracaValue,2,',','.')));
											$row++;										
										}
									}
								
						}else{
																
									if (substr($idacc,0,3)=='3-8' || substr($idacc,0,3)=='3-9'  ){
										$neracaValue=$this->report_model->getLabaRugiValuePenj($idacc, $detil4->id_cab, $bln, $thn);
									}else{
									//get neraca value
										$neracaValue=$this->report_model->getNeracaValuePenj($idacc, $bln, $thn);
										if ($jenis=='Pasiva'){
											$neracaValue=$neracaValue*-1;
										}
									}
										
									$jumlah[$jenis]+=$neracaValue;
									$jumlahL2+=$neracaValue;
									$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $idaccsub." - ".strtoupper($accNameDet));
									$objPHPExcel->getActiveSheet()->setCellValue('E'.$row,($neracaValue<=0? "( Rp. ".number_format(($neracaValue*-1),2,',','.').")" : "Rp. ".number_format($neracaValue,2,',','.')));
									$row++;	
								//echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td colspan=3>".strtoupper($accNameDet)."</td><td align=right>".($neracaValue<=0? "( Rp.&nbsp;".number_format(($neracaValue*-1),2,',','.').")" : "Rp.&nbsp;".number_format($neracaValue,2,',','.'))."</td><td>&nbsp;</td></tr>";
							}
			//
					}
				}
				//---------------------------------------------------------------------
							
		}
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, 'TOTAL '.strtoupper($level2->nama))->setCellValue('H'.$row, ($jumlahL2<=0?"( ".number_format(($jumlahL2*-1),2,',','.')." )":number_format($jumlahL2,2,',','.')));
		$objPHPExcel->getActiveSheet()->getStyle('C'.$row)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$row)->getFont()->setBold(true);
		$row+=2;
		
		$i++;	
		
	}
	$objPHPExcel->getActiveSheet()->mergeCells('C'.$row.':G'.$row);
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, 'TOTAL '.strtoupper($level2->jenis))->setCellValue('H'.$row, "Rp. ".number_format($jumlah[$jenis],2,',','.'));
	$objPHPExcel->getActiveSheet()->getStyle('C'.$row.':H'.$row)->applyFromArray($setHeaderRow);

	


		$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&B '.$title.' &RPrinted on &D');
		$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

		// Set page orientation and size
		//echo date('H:i:s') , " Set page orientation and size" , EOL;
		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		// Redirect output to a client�s web browser (Excel2007)
		//clean the output buffer
		ob_end_clean();
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('assets/report/'.$namafile.'.xlsx');
		//write_file($path."/".$fileName,$out);
		$data['isi']="assets/report/".$namafile.".xlsx";
		echo json_encode($data);
		
	}
}
