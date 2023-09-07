<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat_keluar extends CI_Controller {

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
			$this->load->helper(array('form', 'url','file'));
			$this->load->model('m_surat_keluar');
			$this->load->model('m_auth');
			$this->load->model('m_logs');
			$this->m_auth->cek_login();
	}
		
	public function index(){
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		$this->load->view('modul/surat_keluar/view');
		$this->load->view('_template/footer');
	}

	public function show_data(){
		
		$RowPage = $this->input->post('Row');
		$By = $this->input->post('By');
		$Page = $this->input->post('Page');
		$Search = $this->input->post('Search');
		$JumRow = $this->m_surat_keluar->jumlah_data($Search,$By);
		$offset=($Page - 1) * $RowPage;
		$JumPage = ceil($JumRow/$RowPage);
		$data['data'] = $this->m_surat_keluar->show_data($RowPage,$offset,$Search,$By);
		$data['jumlah_data'] = $JumRow;
		$data['jumlah_page'] = $JumPage;
		$data['NoAwal'] = $offset+1;
		echo json_encode($data);
	}

	public function tambah(){
		$this->m_auth->cek_hak_akses('surat_keluar_up');
		$data['jenis_surat'] = $this->m_surat_keluar->get_jenis();
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		$this->load->view('modul/surat_keluar/tambah',$data);
		$this->load->view('_template/footer');
	}

	private function upload_file($new_name){
		$config['upload_path']          = './public/file/surat_keluar';
		$config['allowed_types']        = 'pdf';
		$config['file_name'] 			= $new_name;
	
		$this->load->library('upload', $config);
	
		if ( ! $this->upload->do_upload('File')){
			$error = array('error' => $this->upload->display_errors(),'status'=>'error');
			return $error;
		}else{
			$data = array('data' => $this->upload->data(),'status'=>'sukses');
			return $data;
		}
	}


	public function edit(){
		$this->m_auth->cek_hak_akses('surat_keluar_up');
		$data['jenis_surat'] = $this->m_surat_keluar->get_jenis();
		$Id = $this->uri->segment(3);
		$data['data'] = $this->m_surat_keluar->get_data($Id);
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		$this->load->view('modul/surat_keluar/edit',$data);
		$this->load->view('_template/footer');
	}
	public function get_nomor_surat(){
		try {
			$data['Kode'] = $this->input->post('Kode');
			$data['TglSurat'] = $this->input->post('TglSurat');
			$NomorSurat = $this->m_surat_keluar->get_nomor_surat($data);
			$r['status'] = "sukses";
			$r['pesan'] = $NomorSurat;
			echo json_encode($r);
		} catch (Throwable $th) {
			$r['status'] = "sukses";
			$r['pesan'] = $th;
			echo json_encode($r);
		}
		

	}

	public function save(){
		$r = array();
		try {
			$data['Kepada'] = strtoupper($this->input->post('Kepada'));
			$data['NoDokumen'] = "SK-".date("Ymd")."-".date("His");
			$data['Perihal'] = $this->input->post('Perihal');
			$data['NoSurat'] = $this->input->post('NoSurat');
			$data['Keterangan'] = $this->input->post('Keterangan');
			$data['Status'] = '1';
			$data['Authorss'] = $this->session->userdata('Nama');
			$data['AuthorssApp'] = $this->session->userdata('Nama');
			$data['TglCreateApp'] = date("Y-m-d H:i:s");
			$data['TglSurat'] = $this->input->post('TglSurat');
			$data['TglCreate'] = date("Y-m-d H:i:s");
			$data['KodeJenisSurat'] = $this->input->post('KodeJenisSurat');
			$newName = time();
			$upl = $this->upload_file($newName);
			if($upl['status'] == "sukses"){
				$FileName = $upl['data']['file_name'];
				$data['File'] = $FileName;
				$save = $this->m_surat_keluar->save_data($data);
				$r['status'] = "sukses";
				$r['pesan'] = "Data surat keluar dengan perihal ".$data['Perihal']." berhasil di masukkan kedalam sistem";
					/** UPDATE LOGS */
				$logs['Authorss'] = $this->session->userdata('Nama');
				$logs['Pesan'] = $r['pesan'];
				$logs['Modul'] = "SuratKeluar";
				$logs['Type'] = "success";
				$logs['Tgl'] = date("Y-m-d H:i:s");
				$this->m_logs->save_logs($logs);
				echo json_encode($r);
			}else{
				$r['status'] = "gagal";
				$r['pesan'] = "Data surat keluar dengan perihal ".$data['Perihal']." gagal di masukkan kedalam sistem.".$upl['error'];
					/** UPDATE LOGS */
				$logs['Authorss'] = $this->session->userdata('Nama');
				$logs['Pesan'] = $r['pesan'];
				$logs['Modul'] = "SuratKeluar";
				$logs['Type'] = "success";
				$logs['Tgl'] = date("Y-m-d H:i:s");
				$this->m_logs->save_logs($logs);
				echo json_encode($r);
			}

			
			
			
		} catch (PDOException $e) {
			$r['status'] = "gagal";
			$r['pesan'] = "System Error : ".$e->getMessage();
			/** UPDATE LOGS */
			$logs['Authorss'] = $this->session->userdata('Nama');
			$logs['Pesan'] = $r['pesan'];
			$logs['Modul'] = "SuratKeluar";
			$logs['Type'] = "error";
			$logs['Tgl'] = date("Y-m-d H:i:s");
			$this->m_logs->save_logs($logs);
			echo json_encode($r);
		}
		
		
	}

	public function update(){
		$r = array();
		try {
			$Id = $this->input->post('Id');
			$data['Kepada'] = $this->input->post('Kepada');
			$data['Perihal'] = $this->input->post('Perihal');
			$data['TglSurat'] = $this->input->post('TglSurat');
			$data['Keterangan'] = $this->input->post('Keterangan');
			$r = array();
			$newName = time();
			$upl = $this->upload_file($newName);
			if($upl['status'] == "sukses"){
				$FileName = $upl['data']['file_name'];
				$FileTemp = $this->m_surat_keluar->get_file($Id);
				$path = "./public/file/surat_keluar/".$FileTemp->File;
				if(file_exists($path) && $FileTemp->File != ""){
					unlink($path);
				}				
				$data['Status'] = '1';
				$data['File'] = $FileName;
				$dt['SuratKeluar'] = json_encode($this->m_surat_keluar->get_data($Id));
				$NoSurat = $this->input->post('NoSurat');
				$this->m_surat_keluar->update_data($data,$Id);
				$this->m_surat_keluar->update_data_rq($dt,$NoSurat);
				$r['status'] = "sukses";
				$r['pesan'] = "Data surat keluar  dengan perihal ".$data['Perihal']." berhasil diubah dalam sistem";
				
				/** UPDATE LOGS */
				$logs['Authorss'] = $this->session->userdata('Nama');
				$logs['Pesan'] = $r['pesan'];
				$logs['Modul'] = "SuratKeluar";
				$logs['Type'] = "success";
				$logs['Tgl'] = date("Y-m-d H:i:s");
				$this->m_logs->save_logs($logs);
				echo json_encode($r);
			}else{
				$FileTemp = $this->m_surat_keluar->get_file($Id);
				$path = "./public/file/surat_keluar/".$FileTemp->File;
				if(file_exists($path) && $FileTemp->File != ""){
					$data['Status'] = '1';
				}else{
					$data['Status'] = '0';
				}
				$this->m_surat_keluar->update_data($data,$Id);
				$dt['SuratKeluar'] = json_encode($this->m_surat_keluar->get_data($Id));
				$NoSurat = $this->input->post('NoSurat');
				$this->m_surat_keluar->update_data_rq($dt,$NoSurat);
				$r['status'] = "sukses";
				$r['pesan'] = "Data surat keluar dengan perihal ".$data['Perihal']." berhasil diupdate";
				
				/** UPDATE LOGS */
				$logs['Authorss'] = $this->session->userdata('Nama');
				$logs['Pesan'] = $r['pesan'];
				$logs['Modul'] = "SuratKeluar";
				$logs['Type'] = "success";
				$logs['Tgl'] = date("Y-m-d H:i:s");
				$this->m_logs->save_logs($logs);
				echo json_encode($r);
			}
		} catch (PDOException $e) {
			$r['status'] = "gagal";
			$r['pesan'] = "System Error : ".$e->getMessage();
			/** UPDATE LOGS */
			$logs['Authorss'] = $this->session->userdata('Nama');
			$logs['Pesan'] = $r['pesan'];
			$logs['Modul'] = "SuratKeluar";
			$logs['Type'] = "error";
			$logs['Tgl'] = date("Y-m-d H:i:s");
			$this->m_logs->save_logs($logs);
			echo json_encode($r);
		}
		
		
		
	}

	public function delete(){
		$result = array();
		try {
			$Id = $this->input->post('Id');
			$dt = $this->m_surat_keluar->get_data($Id);
			$res = $this->m_surat_keluar->hapus_data($Id);
			$rs['NoSurat'] = null;
			$rs['IdSuratKeluar'] = null;
			$rs['SuratKeluar'] = null;
			$rs['Status'] = '0';
			$this->m_surat_keluar->update_data_rq($rs,$dt->NoSurat);
			$path = "./public/file/surat_keluar/".$dt->File;
			if(file_exists($path) && $dt->File != ""){
				unlink($path);
			}
			$result['status'] = "sukses";
			$result['pesan'] = "Data surat keluar dengan kode ".$dt->NoDokumen." berhasil dihapus dalam sistem";
			/** UPDATE LOGS */
			$logs['Authorss'] = $this->session->userdata('Nama');
			$logs['Pesan'] = $result['pesan'];
			$logs['Modul'] = "SuratKeluar";
			$logs['Type'] = "success";
			$logs['Tgl'] = date("Y-m-d H:i:s");
			$this->m_logs->save_logs($logs);
			echo json_encode($result);
		} catch (PDOException $e) {
			$result['status'] = "gagal";
			$result['pesan'] = $e->getMessage();
			/** UPDATE LOGS */
			$logs['Authorss'] = $this->session->userdata('Nama');
			$logs['Pesan'] = $result['pesan'];
			$logs['Modul'] = "SuratKeluar";
			$logs['Type'] = "error";
			$logs['Tgl'] = date("Y-m-d H:i:s");
			$this->m_logs->save_logs($logs);
			echo json_encode($result);
		}
	}

}
