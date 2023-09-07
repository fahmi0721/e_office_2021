<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat_masuk extends CI_Controller {

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
	function __construct(){
			parent::__construct();
			$this->load->helper(array('form', 'url','file'));
			$this->load->model('m_surat_masuk');
			$this->load->model('m_auth');
			$this->load->model('m_logs');
			$this->m_auth->cek_login();
			$this->m_auth->cek_hak_akses('surat_masuk');
	}
		
	public function index(){
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		$this->load->view('modul/surat_masuk/view');
		$this->load->view('_template/footer');
	}

	public function show_data(){
		$JumRow = $this->m_surat_masuk->jumlah_data();
		$RowPage = $this->input->post('Row');
		$By = $this->input->post('By');
		$Page = $this->input->post('Page');
		$Search = $this->input->post('Search');
		$offset=($Page - 1) * $RowPage;
		$JumPage = ceil($JumRow/$RowPage);
		$data['data'] = $this->m_surat_masuk->show_data($RowPage,$offset,$Search,$By);
		$data['jumlah_data'] = $JumRow;
		$data['jumlah_page'] = $JumPage;
		$data['NoAwal'] = $offset+1;
		echo json_encode($data);
	}

	function get_temp_dari(){
		$term = $this->input->get("term",TRUE);
		$data = $this->m_surat_masuk->get_temp_dari($term);
		echo json_encode($data);
	}


	public function tambah(){
		$this->m_auth->cek_hak_akses('tambah_surat_masuk');
		$data['kepada'] = $this->m_surat_masuk->getMaster(array("S1","S2","S3"));
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		$this->load->view('modul/surat_masuk/tambah',$data);
		$this->load->view('_template/footer');
		
	}

	public function edit(){
		$this->m_auth->cek_hak_akses('edit_surat_masuk');
		$data['kepada'] = $this->m_surat_masuk->getMaster(array("S1","S2","S3"));
		$Id = $this->uri->segment(3);
		$r = $this->m_surat_masuk->get_data($Id);
		$data['data'] = $r;
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		$this->load->view('modul/surat_masuk/edit',$data);
		$this->load->view('_template/footer');
	}

	private function upload_file($new_name){
		$config['upload_path']          = './public/file/surat_masuk';
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

	// public function coba(){
	// 	print_r($r = $this->m_surat_masuk->get_temp_dari("sss"));
	// }

	public function save(){
		$r = array();
		$newName = time();
		$upl = $this->upload_file($newName);
		if($upl['status'] == "sukses"){
			try {
				$FileName = $upl['data']['file_name'];
				$data['Dari'] = strtoupper($this->input->post('Dari'));
				$data['NoDokumen'] = "SM-".date("Ymd")."-".date("His");
				$data['TglSurat'] = $this->input->post('TglSurat');
				$data['TglMasukSurat'] = $this->input->post('TglMasukSurat');
				$data['FileSurat'] = $FileName;
				$data['Kepada'] = strtoupper($this->input->post('Kepada'));
				$data['NoSurat'] = $this->input->post('NoSurat');
				$data['Perihal'] = $this->input->post('Perihal');
				$data['Authorss'] = $this->input->post('Authorss');
				$data['BajuSurat'] = $this->m_surat_masuk->get_baju_surat(substr($data['TglMasukSurat'],0,4));
				$data['Keterangan'] = $this->input->post('Keterangan');
				$data['TglCreate'] = date("Y-m-d H:i:s");
				$DuplicateData = $this->m_surat_masuk->cek_duplicate($data['NoSurat']);
				if($DuplicateData <= 0){
					$save = $this->m_surat_masuk->save_data($data);
					$r['status'] = "sukses";
					$r['pesan'] = "Data surat masuk dengan nomor surat ".$data['NoSurat']." berhasil di masukkan kedalam sistem";
					
					/** UPDATE LOGS */
					$logs['Authorss'] = $this->session->userdata('Nama');
					$logs['Pesan'] = $r['pesan'];
					$logs['Modul'] = "SuratMasuk";
					$logs['Type'] = "success";
					$logs['Tgl'] = date("Y-m-d H:i:s");
					$this->m_logs->save_logs($logs);					
					echo json_encode($r);
				}else{
					$r['status'] = "gagal";
					$r['pesan'] = "Data surat masuk dengan nomor surat : ".$data['NoSurat']." telah tersedia dalam sistem. silahkan masukkan nomor yang lain";
					/** UPDATE LOGS */
					$logs['Authorss'] = $this->session->userdata('Nama');
					$logs['Pesan'] = $r['pesan'];
					$logs['Modul'] = "SuratMasuk";
					$logs['Type'] = "error";
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
				$logs['Modul'] = "SuratMasuk";
				$logs['Type'] = "error";
				$logs['Tgl'] = date("Y-m-d H:i:s");
				$this->m_logs->save_logs($logs);
				echo json_encode($r);
			}
		}else{
			$r['status'] = "error";
			$r['pesan'] = $upl['error'];
			$logs['Authorss'] = $this->session->userdata('Nama');
			$logs['Pesan'] = $upl['error'];
			$logs['Modul'] = "SuratMasuk";
			$logs['Type'] = "error";
			$logs['Tgl'] = date("Y-m-d H:i:s");
			$this->m_logs->save_logs($logs);
			echo json_encode($r);
		}
	}

	public function update(){
		$r = array();
		$newName = time();
		$upl = $this->upload_file($newName);
		if($upl['status'] == "sukses"){
			try {
				$Id = $this->input->post('Id');
				$FileLama = $this->m_surat_masuk->get_file($Id);
				$path = "./public/file/surat_masuk/".$FileLama->FileSurat;
				if(file_exists($path)){
					unlink($path);
				}
				$FileName = $upl['data']['file_name'];
				$data['Dari'] = strtoupper($this->input->post('Dari'));
				$data['TglSurat'] = $this->input->post('TglSurat');
				$data['TglMasukSurat'] = $this->input->post('TglMasukSurat');
				$data['FileSurat'] = $FileName;
				$data['Kepada'] = strtoupper($this->input->post('Kepada'));
				$data['NoSurat'] = $this->input->post('NoSurat');
				$data['Perihal'] = $this->input->post('Perihal');
				$data['Keterangan'] = $this->input->post('Keterangan');
				$save = $this->m_surat_masuk->update_data($data,$Id);
				$r['status'] = "sukses";
				$r['pesan'] = "Data surat masuk dengan nomor surat ".$FileLama->NoSurat." berhasil diubah dan memperbarui file surat kedalam sistem";
				
				/** UPDATE LOGS */
				$logs['Authorss'] = $this->session->userdata('Nama');
				$logs['Pesan'] = $r['pesan'];
				$logs['Modul'] = "SuratMasuk";
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
				$logs['Modul'] = "SuratMasuk";
				$logs['Type'] = "error";
				$logs['Tgl'] = date("Y-m-d H:i:s");
				$this->m_logs->save_logs($logs);
				echo json_encode($r);
			}
		}else{
			$Id = $this->input->post('Id');
			$FileLama = $this->m_surat_masuk->get_file($Id);
			$data['Dari'] = strtoupper($this->input->post('Dari'));
			$data['TglSurat'] = $this->input->post('TglSurat');
			$data['TglMasukSurat'] = $this->input->post('TglMasukSurat');
			$data['Kepada'] = strtoupper($this->input->post('Kepada'));
			$data['NoSurat'] = $this->input->post('NoSurat');
			$data['Perihal'] = $this->input->post('Perihal');
			$data['Keterangan'] = $this->input->post('Keterangan');
			$this->m_surat_masuk->update_data($data,$Id);
			$r['status'] = "sukses";
			$r['pesan'] = "Data nota dinas dengan nomor surat ".$FileLama->NoSurat." berhasil diubah kedalam sistem";
			$logs['Authorss'] = $this->session->userdata('Nama');
			$logs['Pesan'] = $r['pesan'];
			$logs['Modul'] = "SuratMasuk";
			$logs['Type'] = "success";
			$logs['Tgl'] = date("Y-m-d H:i:s");
			$this->m_logs->save_logs($logs);
			echo json_encode($r);
		}
			
		
		
	}

	public function delete(){
		$this->m_auth->cek_hak_akses('hapus_surat_masuk');
		$result = array();
		try {
			$Id = $this->input->post('Id');
			$FileLama = $this->m_surat_masuk->get_file($Id);
			$path = "./public/file/surat_masuk/".$FileLama->FileSurat;
			if(file_exists($path)){
				unlink($path);
			}
			
			$res = $this->m_surat_masuk->hapus_data($Id);
			$result['status'] = "sukses";
			$result['pesan'] = "Data surat masuk dengan nomor surat ".$FileLama->NoSurat." berhasil dihapus dalam sistem";
			/** UPDATE LOGS */
			$logs['Authorss'] = $this->session->userdata('Nama');
			$logs['Pesan'] = $result['pesan'];
			$logs['Modul'] = "SuratMausk";
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
			$logs['Modul'] = "SuratMausk";
			$logs['Type'] = "error";
			$logs['Tgl'] = date("Y-m-d H:i:s");
			$this->m_logs->save_logs($logs);
			echo json_encode($result);
		}
	}

	/** DISPOSISI */
	public function tambah_disposisi(){
		$this->m_auth->cek_hak_akses('disposisi_suratmasuk');
		$Id = $this->uri->segment(3);
		$data['data'] = $this->m_surat_masuk->get_data($Id);
		$data['kepada'] = $this->m_surat_masuk->getMaster(array("S1","S2","S3"));
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		$this->load->view('modul/surat_masuk/tambah_disposisi',$data);
		$this->load->view('_template/footer');
		
	}

	private function upload_file_disposisi($new_name){
		$config['upload_path']          = './public/file/disposisi';
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

	public function save_disposisi(){
		$this->m_auth->cek_hak_akses('disposisi_suratmasuk');
		$IdSuratMasuk = $this->input->post('IdSuratMasuk');
		$SuratMasuk = $this->m_surat_masuk->get_data($IdSuratMasuk);
		$data['Catatan'] = $this->input->post('Catatan');
		$data['NoDokumen'] = "DP-".date("Ymd").date("His");
		$data['NoDokumenSurat'] = $this->input->post('NoDokumenSurat');
		$data['Tgl'] = $this->input->post('Tgl');
		$data['Authorss'] = $this->input->post('Authorss');
		$data['TglCreate'] = date("Y-m-d H:i:s");
		$data['Dari'] = $this->input->post('Dari');
		$data['Disposisi'] = implode(",",$this->input->post('Disposisi'));
		$data['Kepada'] = implode(",",$this->input->post('Kepada'));
		$newName = time();
		$upl = $this->upload_file_disposisi($newName);
		if($upl['status'] == "sukses"){
			$FileName = $upl['data']['file_name'];
			try {
				$data['File'] = $FileName;
				$this->m_surat_masuk->save_data_disposisi($data);
				$this->load_all_disposisi($SuratMasuk->NoDokumen,$SuratMasuk->Id);
				$r['status'] = "sukses";
				$r['pesan'] = "Disposisi surat masuk dengan nomor dokumen ".$data['NoDokumenSurat']." berhasil di masukkan kedalam sistem";
				
				/** UPDATE LOGS */
				$logs['Authorss'] = $this->session->userdata('Nama');
				$logs['Pesan'] = $r['pesan'];
				$logs['Modul'] = "Disposisi";
				$logs['Type'] = "success";
				$logs['Tgl'] = date("Y-m-d H:i:s");
				$this->m_logs->save_logs($logs);					
				echo json_encode($r);
			} catch (PDOEception $e) {
				/** UPDATE LOGS */
				$logs['Authorss'] = $this->session->userdata('Nama');
				$logs['Pesan'] = $e->getMessage();
				$logs['Modul'] = "Disposisi";
				$logs['Type'] = "error";
				$logs['Tgl'] = date("Y-m-d H:i:s");
				$this->m_logs->save_logs($logs);					
				echo json_encode($r);
			}
		}else{
			$r['status'] = "error";
			$r['pesan'] = $upl['error'];
			$logs['Authorss'] = $this->session->userdata('Nama');
			$logs['Pesan'] = $upl['error'];
			$logs['Modul'] = "Disposisi";
			$logs['Type'] = "error";
			$logs['Tgl'] = date("Y-m-d H:i:s");
			$this->m_logs->save_logs($logs);
			echo json_encode($r);
		}
		
	}

	public function view_disposisi(){
		$IdSuratMasuk = $this->uri->segment(3);
		$r = $this->m_surat_masuk->get_data($IdSuratMasuk);
		$data['NoSurat'] = $r->NoSurat;
		$data['IdSuratMasuk'] = $IdSuratMasuk;
		$data['data'] = $this->m_surat_masuk->get_data_disposisi($r->NoDokumen);
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		$this->load->view('modul/surat_masuk/view_disposisi',$data);
		$this->load->view('_template/footer');
		
	}

	public function edit_disposisi(){
		$this->m_auth->cek_hak_akses('disposisi_suratmasuk');
		$IdSuratMasuk = $this->uri->segment(3);
		$Id = $this->uri->segment(4);
		$r = $this->m_surat_masuk->get_data($IdSuratMasuk);
		$data['NoSurat'] = $r->NoSurat;
		$data['IdSuratMasuk'] = $IdSuratMasuk;
		$data['data'] = $this->m_surat_masuk->get_data_disposisi_byone($Id);
		$data['Kepada'] = explode(",",$data['data']->Kepada);
		$data['Disposisi'] = explode(",",$data['data']->Disposisi);
		$data['kepada'] = $this->m_surat_masuk->getMaster(array("S1","S2","S3"));
		$data['datas'] = $r;
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		$this->load->view('modul/surat_masuk/edit_disposisi',$data);
		$this->load->view('_template/footer');
		
		
	}
	public function update_disposisi(){
		$this->m_auth->cek_hak_akses('disposisi_suratmasuk');
		$Id = $this->input->post('Id');
		$IdSuratMasuk = $this->input->post('IdSuratMasuk');
		$SuratMasuk = $this->m_surat_masuk->get_data($IdSuratMasuk);
		$data['NoDokumen'] = $this->input->post('NoDokumen');
		$data['Catatan'] = $this->input->post('Catatan');
		$data['Tgl'] = $this->input->post('Tgl');
		$data['Dari'] = $this->input->post('Dari');
		$data['Disposisi'] = implode(",",$this->input->post('Disposisi'));
		$data['Kepada'] = implode(",",$this->input->post('Kepada'));
		$newName = time();
		$upl = $this->upload_file_disposisi($newName);
		if($upl['status'] == "sukses"){
			$FileLama = $this->m_surat_masuk->get_data_disposisi_byone($Id);
			$path = "./public/file/disposisi/".$FileLama->File;
			if(file_exists($path)){
				unlink($path);
			}
			$FileName = $upl['data']['file_name'];
			try {
				$data['File'] = $FileName;
				$this->m_surat_masuk->update_data_disposisi($data,$Id);
				$this->load_all_disposisi($SuratMasuk->NoDokumen,$SuratMasuk->Id);
				$r['status'] = "sukses";
				$r['pesan'] = "Disposisi dengan nomor dokumen ".$data['NoDokumen']." berhasil di ubah dan diganti file disposisinya kedalam sistem";
				
				/** UPDATE LOGS */
				$logs['Authorss'] = $this->session->userdata('Nama');
				$logs['Pesan'] = $r['pesan'];
				$logs['Modul'] = "Disposisi";
				$logs['Type'] = "success";
				$logs['Tgl'] = date("Y-m-d H:i:s");
				$this->m_logs->save_logs($logs);					
				echo json_encode($r);
			} catch (PDOEception $e) {
				/** UPDATE LOGS */
				$logs['Authorss'] = $this->session->userdata('Nama');
				$logs['Pesan'] = $e->getMessage();
				$logs['Modul'] = "Disposisi";
				$logs['Type'] = "error";
				$logs['Tgl'] = date("Y-m-d H:i:s");
				$this->m_logs->save_logs($logs);					
				echo json_encode($r);
			}
		}else{
			$this->m_surat_masuk->update_data_disposisi($data,$Id);
			$this->load_all_disposisi($SuratMasuk->NoDokumen,$SuratMasuk->Id);
			$r['status'] = "sukses";
			$r['pesan'] = "Disposisi dengan nomor dokumen ".$data['NoDokumen']." berhasil di ubah kedalam sistem";
			
			/** UPDATE LOGS */
			$logs['Authorss'] = $this->session->userdata('Nama');
			$logs['Pesan'] = $r['pesan'];
			$logs['Modul'] = "Disposisi";
			$logs['Type'] = "success";
			$logs['Tgl'] = date("Y-m-d H:i:s");
			$this->m_logs->save_logs($logs);					
			echo json_encode($r);
		}
		
	}

	public function detail_disposisi(){
		$IdSuratMasuk = $this->uri->segment(3);
		$Id = $this->uri->segment(4);
		$r = $this->m_surat_masuk->get_data($IdSuratMasuk);
		$data['NoSurat'] = $r->NoSurat;
		$data['IdSuratMasuk'] = $IdSuratMasuk;
		$data['data'] = $this->m_surat_masuk->get_data_disposisi_byone($Id);
		$data['Kepada'] = explode(",",$data['data']->Kepada);
		$data['Disposisi'] = explode(",",$data['data']->Disposisi);
		$data['kepada'] = $this->m_surat_masuk->getMaster(array("S1","S2","S3"));
		$data['datas'] = $r;
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		$this->load->view('modul/surat_masuk/detail_disposisi',$data);
		$this->load->view('_template/footer');
		
	}

	public function delete_disposisi(){
		$this->m_auth->cek_hak_akses('disposisi_suratmasuk');
		$IdSuratMasuk = $this->uri->segment(3);
		$SuratMasuk = $this->m_surat_masuk->get_data($IdSuratMasuk);
		$Id = $this->uri->segment(4);
		$FileLama = $this->m_surat_masuk->get_data_disposisi_byone($Id);
		$path = "./public/file/disposisi/".$FileLama->File;
		if(file_exists($path)){
			unlink($path);
		}
		try {
			$this->m_surat_masuk->delete_disposisi($Id);
			$this->load_all_disposisi($SuratMasuk->NoDokumen,$SuratMasuk->Id);
			/** UPDATE LOGS */
			$logs['Authorss'] = $this->session->userdata('Nama');
			$logs['Pesan'] = "Disposisi dengan nomor dokumen ".$FileLama->NoDokumen." berhasil dihapus";
			$logs['Modul'] = "Disposisi";
			$logs['Type'] = "success";
			$logs['Tgl'] = date("Y-m-d H:i:s");
			$this->m_logs->save_logs($logs);					
			redirect("/surat_masuk/view_disposisi/".$IdSuratMasuk);
		} catch (PDOEception $e) {
			/** UPDATE LOGS */
			$logs['Authorss'] = $this->session->userdata('Nama');
			$logs['Pesan'] = $e->getMessage();
			$logs['Modul'] = "Disposisi";
			$logs['Type'] = "error";
			$logs['Tgl'] = date("Y-m-d H:i:s");
			$this->m_logs->save_logs($logs);					
			redirect("/nota_dinas/view_disposisi/".$IdSuratMasuk);
		}
	
		
	}
	
	private function load_all_disposisi($NoDokumen,$IdNotaDinas){
		$this->m_auth->cek_hak_akses('disposisi_suratmasuk');
		$jumlah_data = count($this->m_surat_masuk->get_data_disposisi($NoDokumen), COUNT_RECURSIVE);
		if($jumlah_data > 0){
			$data =  json_encode($this->m_surat_masuk->get_data_disposisi($NoDokumen));
			$encript = base64_encode($data);
			$r['Disposisi'] = $encript;
			$this->m_surat_masuk->update_data($r,$IdNotaDinas);
		}else{
			$r['Disposisi'] = "";
			$this->m_surat_masuk->update_data($r,$IdNotaDinas);
		}
	}

	

}
