<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Export_nota_dinas extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct()
	{
			parent::__construct();
			$this->load->helper('url'); 
			$this->load->model('m_nota_dinas','m_nd');
			$this->load->model('m_auth');
			$this->load->model('m_logs');
			$this->m_auth->cek_login();
			$this->m_auth->cek_hak_akses('klasifikasi');
	}
		
	public function index(){
		$data['master'] = $this->m_nd->getMaster();
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		$this->load->view('modul/eksport_nota_dinas/view',$data);
		$this->load->view('_template/footer');
	}

	public function proses(){
		$data = $this->input->get('data');
		$data = json_decode(base64_decode($data),true);
		if($data['aksi'] == "print"){
			$this->print($data);
		}else{
			$this->export($data);
		}
	}

	public function print($data){
		$iData = $this->m_nd->get_data_all($data);
		echo "<html>";
		echo "<head>";
		echo "<style>";
		echo "@media print {
			margin: 0;
		}
		@page{
			size: landscape;
		}
		";
		echo "</style>";
		echo "</head>";
		echo "<body>";
		echo "<center><h4>Daftar Nota Dinas</h4></center>";
		echo "<table style='width:100%' border='1px'  cellspacing='0'>";
		echo "<thead>";
		echo "<tr>";
			echo "<th>No</th>";
			echo "<th>No Dokumen</th>";
			echo "<th>No Surat</th>";
			echo "<th>Tanggal Surat</th>";
			echo "<th>Perihal</th>";
			// echo "<th>Di Tujukan</th>";
			echo "<th>Dari</th>";
		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";
			$No = 1;
			foreach($iData as $dt){
				echo "<tr>";
					echo "<td style='text-align:center; width:5%'>".$No."</td>";
					echo "<td style='width:15%'>".$dt->NoDokumen."</td>";
					echo "<td style='width:15%'>".$dt->NoSurat."</td>";
					echo "<td style='text-align:center; width:10%'>".$dt->TglSurat."</td>";
					echo "<td>".$dt->Perihal."</td>";
					// echo "<td>".$dt->Kepada."</td>";
					echo "<td>".$dt->Dari."</td>";
				echo "</tr>";
				$No++;
			}
		echo "</tbody>";
		echo "</table>";
		echo "</body>";
		echo "<script>";
		echo "window.print();";
		echo "</script>";
		echo "</html>";
	}

	public function export($data){
		$iData = $this->m_nd->get_data_all($data);
		header("Content-type: application/vnd-ms-excel");
    	header("Content-Disposition: attachment; filename=Daftar_Nota_dinas.xls");
		echo "<html>";
		echo "<head>";
		echo "<style>";
		// echo "@media print {
		// 	margin: 0;
		// }
		// @page{
		// 	size: landscape;
		// }
		// ";
		echo "</style>";
		echo "</head>";
		echo "<body>";
		echo "<center><h4>Daftar Nota Dinas</h4></center>";
		echo "<table style='width:100%' border='1px'  cellspacing='0'>";
		echo "<thead>";
		echo "<tr>";
			echo "<th>No</th>";
			echo "<th>No Dokumen</th>";
			echo "<th>No Surat</th>";
			echo "<th>Tanggal Surat</th>";
			echo "<th>Perihal</th>";
			// echo "<th>Di Tujukan</th>";
			echo "<th>Dari</th>";
		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";
			$No = 1;
			foreach($iData as $dt){
				echo "<tr>";
					echo "<td style='text-align:center; width:5%'>".$No."</td>";
					echo "<td style='width:15%'>".$dt->NoDokumen."</td>";
					echo "<td style='width:15%'>".$dt->NoSurat."</td>";
					echo "<td style='text-align:center; width:10%'>".$dt->TglSurat."</td>";
					echo "<td>".$dt->Perihal."</td>";
					// echo "<td>".$dt->Kepada."</td>";
					echo "<td>".$dt->Dari."</td>";
				echo "</tr>";
				$No++;
			}
		echo "</tbody>";
		echo "</table>";
		echo "</body>";
		echo "</html>";
	}

	
}
