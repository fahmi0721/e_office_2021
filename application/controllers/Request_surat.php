<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request_surat extends CI_Controller {

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
			$this->load->model('m_request_surat');
			$this->load->model('m_auth');
			$this->load->model('m_logs');
			$this->m_auth->cek_login();
			$this->m_auth->cek_hak_akses('request_surat');
	}
		
	public function index(){
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		$this->load->view('modul/request_surat/view');
		$this->load->view('_template/footer');
	}

	public function show_data(){
		$JumRow = $this->m_request_surat->jumlah_data();
		$RowPage = $this->input->post('Row');
		$By = $this->input->post('By');
		$Page = $this->input->post('Page');
		$Search = $this->input->post('Search');
		$offset=($Page - 1) * $RowPage;
		$JumPage = ceil($JumRow/$RowPage);
		$data['data'] = $this->m_request_surat->show_data($RowPage,$offset,$Search,$By);
		$data['jumlah_data'] = $JumRow;
		$data['jumlah_page'] = $JumPage;
		$data['NoAwal'] = $offset+1;
		echo json_encode($data);
	}

	public function tambah(){
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		$this->load->view('modul/request_surat/tambah');
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

	public function save_approve(){
		$r = array();
		try {
			$Id = $this->input->post('IdRequest');
			$data['NoDokumen'] = "SK-".date("Ymd")."-".date("His");
			$data['NoSurat'] = $this->input->post('NoSurat');
			$data['Kepada'] = $this->input->post('Kepada');
			$data['Perihal'] = $this->input->post('Perihal');
			$data['TglSurat'] = $this->input->post('TglSurat');
			$data['Keterangan'] = $this->input->post('Keterangan');
			$data['Authorss'] = $this->input->post('Authorss');
			$data['AuthorssApp'] = $this->session->userdata('Nama');
			$data['TglCreate'] = $this->input->post('TglCreate');
			$data['TglCreateApp'] = date("Y-m-d H:i:s");
			$data['KodeJenisSurat'] = $this->input->post('KodeJenisSurat');
			$r = array();
			$newName = time();
			$upl = $this->upload_file($newName);
			if($upl['status'] == "sukses"){
				$FileName = $upl['data']['file_name'];
				$data['Status'] = '1';
				$data['File'] = $FileName;
				$save_and_get_id = $this->m_request_surat->save_data_approve($data);
				$dt['NoSurat'] = $data['NoSurat'];
				$dt['TglApprove'] = $data['TglCreateApp'];
				$dt['IdSuratKeluar'] = $save_and_get_id;
				$dt['SuratKeluar'] = json_encode($this->m_request_surat->get_data_sk($save_and_get_id));
				$this->m_request_surat->update_data($dt,$Id);
				$r['status'] = "sukses";
				$r['pesan'] = "Data request nomor surat dengan perihal ".$data['Perihal']." berhasil dibuatkan nomor dengan nomor surat ".$data['NoSurat'];
				
				/** UPDATE LOGS */
				$logs['Authorss'] = $this->session->userdata('Nama');
				$logs['Pesan'] = $r['pesan'];
				$logs['Modul'] = "NomorSurat";
				$logs['Type'] = "success";
				$logs['Tgl'] = date("Y-m-d H:i:s");
				$this->m_logs->save_logs($logs);
				echo json_encode($r);
			}else{
				$data['Status'] = '0';
				$save_and_get_id = $this->m_request_surat->save_data_approve($data);
				$dt['NoSurat'] = $data['NoSurat'];
				$dt['TglApprove'] = $data['TglCreateApp'];
				$dt['Status'] = "1";
				$dt['IdSuratKeluar'] = $save_and_get_id;
				$dt['SuratKeluar'] = json_encode($this->m_request_surat->get_data_sk($save_and_get_id));
				$this->m_request_surat->update_data($dt,$Id);
				$r['status'] = "sukses";
				$r['pesan'] = "Data request nomor surat dengan perihal ".$data['Perihal']." berhasil dibuatkan nomor dengan nomor surat ".$data['NoSurat'];
				
				/** UPDATE LOGS */
				$logs['Authorss'] = $this->session->userdata('Nama');
				$logs['Pesan'] = $r['pesan'];
				$logs['Modul'] = "NomorSurat";
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
			$logs['Modul'] = "NomorSurat";
			$logs['Type'] = "error";
			$logs['Tgl'] = date("Y-m-d H:i:s");
			$this->m_logs->save_logs($logs);
			echo json_encode($r);
		}
	}

	public function edit(){
		$Id = $this->uri->segment(3);
		$data['data'] = $this->m_request_surat->get_data($Id);
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		$this->load->view('modul/request_surat/edit',$data);
		$this->load->view('_template/footer');
	}
	public function get_nomor_surat(){
		try {
			$data['Kode'] = $this->input->post('Kode');
			$data['TglSurat'] = $this->input->post('TglSurat');
			$NomorSurat = $this->m_request_surat->get_nomor_surat($data);
			$r['status'] = "sukses";
			$r['pesan'] = $NomorSurat;
			echo json_encode($r);
		} catch (Throwable $th) {
			$r['status'] = "sukses";
			$r['pesan'] = $th;
			echo json_encode($r);
		}
		

	}

	public function approve(){
		$this->m_auth->cek_hak_akses('approve_request_surat');
		$Id = $this->uri->segment(3);
		$data['data'] = $this->m_request_surat->get_data($Id);
		$data['jenis_surat'] = $this->m_request_surat->get_jenis();
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		$this->load->view('modul/request_surat/approve',$data);
		$this->load->view('_template/footer');
	}

	public function save(){
		$r = array();
		try {
			$data['Kepada'] = strtoupper($this->input->post('Kepada'));
			$data['NoDokumen'] = "RQ-".date("Ymd")."-".date("His");
			$data['Perihal'] = $this->input->post('Perihal');
			$data['Status'] = '0';
			$data['Authorss'] = $this->session->userdata('Nama');
			$data['Direktorat'] = $this->session->userdata('KodeDirektorat');
			$data['DirektoratText'] = $this->session->userdata('Direktorat');
			$data['TglSurat'] = $this->input->post('TglSurat');
			$data['TglCreate'] = date("Y-m-d H:i:s");
			$save = $this->m_request_surat->save_data($data);
			$r['status'] = "sukses";
			$r['pesan'] = "Data request nomor surat dengan perihal ".$data['Perihal']." berhasil di masukkan kedalam sistem";
			
			/** UPDATE LOGS */
			$logs['Authorss'] = $this->session->userdata('Nama');
			$logs['Pesan'] = $r['pesan'];
			$logs['Modul'] = "RequestSurat";
			$logs['Type'] = "success";
			$logs['Tgl'] = date("Y-m-d H:i:s");
			$this->m_logs->save_logs($logs);
			echo json_encode($r);
			
		} catch (PDOException $e) {
			$r['status'] = "gagal";
			$r['pesan'] = "System Error : ".$e->getMessage();
			/** UPDATE LOGS */
			$logs['Authorss'] = $this->session->userdata('Nama');
			$logs['Pesan'] = $r['pesan'];
			$logs['Modul'] = "RequestSurat";
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
			$data['Kepada'] = strtoupper($this->input->post('Kepada'));
			$data['Perihal'] = $this->input->post('Perihal');
			$data['TglSurat'] = $this->input->post('TglSurat');
			$update = $this->m_request_surat->update_data($data,$Id);
			$r['status'] = "sukses";
			$r['pesan'] = "Data request nomor surat dengan perihal ".$data['Perihal']." berhasil diubah kedalam sistem";
			/** UPDATE LOGS */
			$logs['Authorss'] = $this->session->userdata('Nama');
			$logs['Pesan'] = $r['pesan'];
			$logs['Modul'] = "RequestSurat";
			$logs['Type'] = "success";
			$logs['Tgl'] = date("Y-m-d H:i:s");
			$this->m_logs->save_logs($logs);
			echo json_encode($r);
			
		} catch (PDOException $e) {
			$r['status'] = "gagal";
			$r['pesan'] = "System Error : ".$e->getMessage();
			/** UPDATE LOGS */
			$logs['Authorss'] = $this->session->userdata('Nama');
			$logs['Pesan'] = $r['pesan'];
			$logs['Modul'] = "RequestSurat";
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
			$dt = $this->m_request_surat->get_data($Id);
			$res = $this->m_request_surat->hapus_data($Id);
			$result['status'] = "sukses";
			$result['pesan'] = "Data request nomor surat dengan kode ".$dt->NoDokumen." berhasil dihapus dalam sistem";
			/** UPDATE LOGS */
			$logs['Authorss'] = $this->session->userdata('Nama');
			$logs['Pesan'] = $result['pesan'];
			$logs['Modul'] = "RequestSurat";
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
			$logs['Modul'] = "RequestSurat";
			$logs['Type'] = "error";
			$logs['Tgl'] = date("Y-m-d H:i:s");
			$this->m_logs->save_logs($logs);
			echo json_encode($result);
		}
	}

}
