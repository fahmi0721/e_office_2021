<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nota_dinas extends CI_Controller {

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
			$this->load->model('m_nota_dinas');
			$this->load->model('m_auth');
			$this->load->model('m_logs');
			$this->m_auth->cek_login();
			$this->m_auth->cek_hak_akses('nota_dinas');
	}
		
	public function index(){
		
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		$this->load->view('modul/nota_dinas/view');
		$this->load->view('_template/footer');
	}

	public function tambah_khusus(){
		$data['tahun'] = $this->LoadTahun();
		$data['kepada'] = $this->m_nota_dinas->getMaster();
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		$this->load->view('modul/nota_dinas_sdm/tambah',$data);
		$this->load->view('_template/footer');
	}

	public function show_data(){
		$JumRow = $this->m_nota_dinas->jumlah_data();
		$RowPage = $this->input->post('Row');
		$By = $this->input->post('By');
		$Page = $this->input->post('Page');
		$Search = $this->input->post('Search');
		$offset=($Page - 1) * $RowPage;
		$JumPage = ceil($JumRow/$RowPage);
		$data['data'] = $this->m_nota_dinas->show_data($RowPage,$offset,$Search,$By);
		$data['jumlah_data'] = $JumRow;
		$data['jumlah_page'] = $JumPage;
		$data['NoAwal'] = $offset+1;
		echo json_encode($data);
	}

	private function LoadTahun(){
		$start = 2021;
		$thun = array();
		for($i=date("Y"); $i >= $start; $i--){
			$thun[] = $i;
		}
		return $thun;
	}

	public function get_nomor_surat_sdm(){
		
		if(!empty($this->input->post('Tahun')) && !empty($this->input->post('Dari'))){
			$Kode = $this->input->post('Dari');
			$Kode = explode("#",$Kode);
			$Kode = $Kode[0];
			$Tahun = $this->input->post('Tahun');
			echo $this->m_nota_dinas->getNomorSuratSdm($Tahun,$Kode);
		}else{
			echo "";
		}
	}

	public function get_nomor_surat(){
		if(!empty($this->input->post('Tahun'))){
			$Tahun = $this->input->post('Tahun');
			echo $this->m_nota_dinas->getNomorSurat($Tahun);
		}else{
			echo $this->m_nota_dinas->getNomorSurat(date("Y"));
		}
	}

	public function tambah(){
		$data['tahun'] = $this->LoadTahun();
		$data['kepada'] = $this->m_nota_dinas->getMaster();
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		$this->load->view('modul/nota_dinas/tambah',$data);
		$this->load->view('_template/footer');
		
	}

	public function edit(){
		$data['kepada'] = $this->m_nota_dinas->getMaster();
		$Id = $this->uri->segment(3);
		$r = $this->m_nota_dinas->get_data($Id);
		$r->Tahun = substr($r->NoSurat,-4);
		$data['data'] = $r;
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		$this->load->view('modul/nota_dinas/edit',$data);
		$this->load->view('_template/footer');
	}

	private function upload_file($new_name){
		$config['upload_path']          = './public/file/nota_dinas';
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

	public function save(){
		$KodeDari = array("B2","B3","B1");
        $iKode = $KodeDari[$this->session->userdata('KodeDirektorat')];
		$r = array();
		$newName = time();
		$upl = $this->upload_file($newName);
		if($upl['status'] == "sukses"){
			try {
				$FileName = $upl['data']['file_name'];
				$data['Dari'] = $this->m_nota_dinas->get_dari($iKode);
				$data['NoDokumen'] = $iKode."-".date("Ymd")."-".date("His");
				$data['TglSurat'] = $this->input->post('TglSurat');
				$data['FileSurat'] = $FileName;
				$data['Kepada'] = $this->input->post('Kepada');
				$data['NoSurat'] = $this->input->post('NoSurat');
				$data['Perihal'] = $this->input->post('Perihal');
				$data['Authorss'] = $this->input->post('Authorss');
				$data['Direktorat'] = $this->session->userdata('KodeDirektorat');
				$data['Keterangan'] = $this->input->post('Keterangan');
				$data['KodeDari'] = $iKode;
				$data['TglCreate'] = date("Y-m-d H:i:s");
				$DuplicateData = $this->m_nota_dinas->cek_duplicate($data['NoSurat']);
				if($DuplicateData <= 0){
					$save = $this->m_nota_dinas->save_data($data);
					$r['status'] = "sukses";
					$r['pesan'] = "Data nota dinas dengan nomor surat ".$data['NoSurat']." berhasil di masukkan kedalam sistem";
					
					/** UPDATE LOGS */
					$logs['Authorss'] = $this->session->userdata('Nama');
					$logs['Pesan'] = $r['pesan'];
					$logs['Modul'] = "NotaDinas";
					$logs['Type'] = "success";
					$logs['Tgl'] = date("Y-m-d H:i:s");
					$this->m_logs->save_logs($logs);					
					echo json_encode($r);
				}else{
					$r['status'] = "gagal";
					$r['pesan'] = "Data nota diasn dengan nama : ".$data['NoSurat']." telah tersedia dalam sistem. silahkan masukkan kode yang lain";
					/** UPDATE LOGS */
					$logs['Authorss'] = $this->session->userdata('Nama');
					$logs['Pesan'] = $r['pesan'];
					$logs['Modul'] = "NotaDinas";
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
				$logs['Modul'] = "NotaDinas";
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
			$logs['Modul'] = "NotaDinas";
			$logs['Type'] = "error";
			$logs['Tgl'] = date("Y-m-d H:i:s");
			$this->m_logs->save_logs($logs);
			echo json_encode($r);
		}
	}

	public function save_sdm(){
		$r = array();
		$newName = time();
		$upl = $this->upload_file($newName);
		if($upl['status'] == "sukses"){
			try {
				$iKode = explode("#",$this->input->post('Dari'));
				$FileName = $upl['data']['file_name'];
				$data['Dari'] = $iKode[1];
				$data['NoDokumen'] = $iKode[0]."-".date("Ymd")."-".date("His");
				$data['TglSurat'] = $this->input->post('TglSurat');
				$data['FileSurat'] = $FileName;
				$data['Kepada'] = $this->input->post('Kepada');
				$data['NoSurat'] = $this->input->post('NoSurat');
				$data['Perihal'] = $this->input->post('Perihal');
				$data['Authorss'] = $this->input->post('Authorss');
				$data['Direktorat'] = $this->session->userdata('KodeDirektorat');
				$data['Keterangan'] = $this->input->post('Keterangan');
				$data['KodeDari'] = $iKode[0];
				$data['TglCreate'] = date("Y-m-d H:i:s");
				$DuplicateData = $this->m_nota_dinas->cek_duplicate($data['NoSurat']);
				if($DuplicateData <= 0){
					$save = $this->m_nota_dinas->save_data($data);
					$r['status'] = "sukses";
					$r['pesan'] = "Data nota dinas dengan nomor surat ".$data['NoSurat']." berhasil di masukkan kedalam sistem";
					
					/** UPDATE LOGS */
					$logs['Authorss'] = $this->session->userdata('Nama');
					$logs['Pesan'] = $r['pesan'];
					$logs['Modul'] = "NotaDinas";
					$logs['Type'] = "success";
					$logs['Tgl'] = date("Y-m-d H:i:s");
					$this->m_logs->save_logs($logs);					
					echo json_encode($r);
				}else{
					$r['status'] = "gagal";
					$r['pesan'] = "Data nota diasn dengan nama : ".$data['NoSurat']." telah tersedia dalam sistem. silahkan masukkan kode yang lain";
					/** UPDATE LOGS */
					$logs['Authorss'] = $this->session->userdata('Nama');
					$logs['Pesan'] = $r['pesan'];
					$logs['Modul'] = "NotaDinas";
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
				$logs['Modul'] = "NotaDinas";
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
			$logs['Modul'] = "NotaDinas";
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
				$FileLama = $this->m_nota_dinas->get_file($Id);
				$path = "./public/file/nota_dinas/".$FileLama->FileSurat;
				if(file_exists($path)){
					unlink($path);
				}
				$data['TglSurat'] = $this->input->post('TglSurat');
				$data['Kepada'] = $this->input->post('Kepada');
				$data['Perihal'] = $this->input->post('Perihal');
				$data['Keterangan'] = $this->input->post('Keterangan');
				$FileName = $upl['data']['file_name'];
				$data['FileSurat'] = $FileName;
				$save = $this->m_nota_dinas->update_data($data,$Id);
				$r['status'] = "sukses";
				$r['pesan'] = "Data nota dinas dengan nomor surat ".$FileLama->NoSurat." berhasil diubah dan memperbarui file surat kedalam sistem";
				
				/** UPDATE LOGS */
				$logs['Authorss'] = $this->session->userdata('Nama');
				$logs['Pesan'] = $r['pesan'];
				$logs['Modul'] = "NotaDinas";
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
				$logs['Modul'] = "NotaDinas";
				$logs['Type'] = "error";
				$logs['Tgl'] = date("Y-m-d H:i:s");
				$this->m_logs->save_logs($logs);
				echo json_encode($r);
			}
		}else{
			$Id = $this->input->post('Id');
			$FileLama = $this->m_nota_dinas->get_file($Id);
			$data['TglSurat'] = $this->input->post('TglSurat');
			$data['Kepada'] = $this->input->post('Kepada');
			$data['Perihal'] = $this->input->post('Perihal');
			$data['Keterangan'] = $this->input->post('Keterangan');
			$this->m_nota_dinas->update_data($data,$Id);
			$r['status'] = "sukses";
			$r['pesan'] = "Data nota dinas dengan nomor surat ".$FileLama->NoSurat." berhasil diubah kedalam sistem";
			$logs['Authorss'] = $this->session->userdata('Nama');
			$logs['Pesan'] = $r['pesan'];
			$logs['Modul'] = "NotaDinas";
			$logs['Type'] = "success";
			$logs['Tgl'] = date("Y-m-d H:i:s");
			$this->m_logs->save_logs($logs);
			echo json_encode($r);
		}
			
		
		
	}

	public function delete(){
		$result = array();
		try {
			$Id = $this->input->post('Id');
			$FileLama = $this->m_nota_dinas->get_file($Id);
			$path = "./public/file/nota_dinas/".$FileLama->FileSurat;
			if(file_exists($path)){
				unlink($path);
			}
			
			$dt = $this->m_nota_dinas->get_data($Id);
			$res = $this->m_nota_dinas->hapus_data($Id);
			$result['status'] = "sukses";
			$result['pesan'] = "Data nota dinas dengan nomor surat ".$dt->NoSurat." berhasil dihapus dalam sistem";
			/** UPDATE LOGS */
			$logs['Authorss'] = $this->session->userdata('Nama');
			$logs['Pesan'] = $result['pesan'];
			$logs['Modul'] = "NotaDinas";
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
			$logs['Modul'] = "NotaDinas";
			$logs['Type'] = "error";
			$logs['Tgl'] = date("Y-m-d H:i:s");
			$this->m_logs->save_logs($logs);
			echo json_encode($result);
		}
	}
	/** DISPOSISI */
	public function tambah_disposisi(){
		$this->m_auth->cek_hak_akses('disposisi_notadinas');
		$Id = $this->uri->segment(3);
		$data['data'] = $this->m_nota_dinas->get_data($Id);
		$data['kepada'] = $this->m_nota_dinas->getMaster();
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		$this->load->view('modul/nota_dinas/tambah_disposisi',$data);
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
		$this->m_auth->cek_hak_akses('disposisi_notadinas');
		$IdNotaDinas = $this->input->post('IdNotaDinas');
		$NotaDinas = $this->m_nota_dinas->get_data($IdNotaDinas);
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
				$this->m_nota_dinas->save_data_disposisi($data);
				$this->load_all_disposisi($NotaDinas->NoDokumen,$NotaDinas->Id);
				$r['status'] = "sukses";
				$r['pesan'] = "Disposisi nota dinas dengan nomor dokumen ".$data['NoDokumenSurat']." berhasil di masukkan kedalam sistem";
				
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
		$IdNotaDinas = $this->uri->segment(3);
		$r = $this->m_nota_dinas->get_data($IdNotaDinas);
		$data['NoSurat'] = $r->NoSurat;
		$data['IdNotaDinas'] = $IdNotaDinas;
		$data['data'] = $this->m_nota_dinas->get_data_disposisi($r->NoDokumen);
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		$this->load->view('modul/nota_dinas/view_disposisi',$data);
		$this->load->view('_template/footer');
		
	}

	public function edit_disposisi(){
		$this->m_auth->cek_hak_akses('disposisi_notadinas');
		$IdNotaDinas = $this->uri->segment(3);
		$Id = $this->uri->segment(4);
		$r = $this->m_nota_dinas->get_data($IdNotaDinas);
		$data['NoSurat'] = $r->NoSurat;
		$data['IdNotaDinas'] = $IdNotaDinas;
		$data['data'] = $this->m_nota_dinas->get_data_disposisi_byone($Id);
		$data['Kepada'] = explode(",",$data['data']->Kepada);
		$data['Disposisi'] = explode(",",$data['data']->Disposisi);
		$data['kepada'] = $this->m_nota_dinas->getMaster();
		$data['datas'] = $r;
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		$this->load->view('modul/nota_dinas/edit_disposisi',$data);
		$this->load->view('_template/footer');
		// echo "<pre>";
		// print_r($data);
		
	}
	public function update_disposisi(){
		$this->m_auth->cek_hak_akses('disposisi_notadinas');
		$Id = $this->input->post('Id');
		$IdNotaDinas = $this->input->post('IdNotaDinas');
		$NotaDinas = $this->m_nota_dinas->get_data($IdNotaDinas);
		$data['NoDokumen'] = $this->input->post('NoDokumen');
		$data['Catatan'] = $this->input->post('Catatan');
		$data['Tgl'] = $this->input->post('Tgl');
		$data['Dari'] = $this->input->post('Dari');
		$data['Disposisi'] = implode(",",$this->input->post('Disposisi'));
		$data['Kepada'] = implode(",",$this->input->post('Kepada'));
		$newName = time();
		$upl = $this->upload_file_disposisi($newName);
		if($upl['status'] == "sukses"){
			$FileLama = $this->m_nota_dinas->get_data_disposisi_byone($Id);
			$path = "./public/file/disposisi/".$FileLama->File;
			if(file_exists($path)){
				unlink($path);
			}
			$FileName = $upl['data']['file_name'];
			try {
				$data['File'] = $FileName;
				$this->m_nota_dinas->update_data_disposisi($data,$Id);
				$this->load_all_disposisi($NotaDinas->NoDokumen,$NotaDinas->Id);
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
			$this->m_nota_dinas->update_data_disposisi($data,$Id);
			$this->load_all_disposisi($NotaDinas->NoDokumen,$NotaDinas->Id);
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
		$IdNotaDinas = $this->uri->segment(3);
		$Id = $this->uri->segment(4);
		$r = $this->m_nota_dinas->get_data($IdNotaDinas);
		$data['NoSurat'] = $r->NoSurat;
		$data['IdNotaDinas'] = $IdNotaDinas;
		$data['data'] = $this->m_nota_dinas->get_data_disposisi_byone($Id);
		$data['Kepada'] = explode(",",$data['data']->Kepada);
		$data['Disposisi'] = explode(",",$data['data']->Disposisi);
		$data['kepada'] = $this->m_nota_dinas->getMaster();
		$data['datas'] = $r;
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		$this->load->view('modul/nota_dinas/detail_disposisi',$data);
		$this->load->view('_template/footer');
		// echo "<pre>";
		// print_r($data);
		
	}

	public function delete_disposisi(){
		$this->m_auth->cek_hak_akses('disposisi_notadinas');
		$IdNotaDinas = $this->uri->segment(3);
		$NotaDinas = $this->m_nota_dinas->get_data($IdNotaDinas);
		$Id = $this->uri->segment(4);
		$FileLama = $this->m_nota_dinas->get_data_disposisi_byone($Id);
		$path = "./public/file/disposisi/".$FileLama->File;
		if(file_exists($path)){
			unlink($path);
		}
		try {
			$this->m_nota_dinas->delete_disposisi($Id);
			$this->load_all_disposisi($NotaDinas->NoDokumen,$NotaDinas->Id);
			/** UPDATE LOGS */
			$logs['Authorss'] = $this->session->userdata('Nama');
			$logs['Pesan'] = "Disposisi dengan nomor dokumen ".$FileLama->NoDokumen." berhasil dihapus";
			$logs['Modul'] = "Disposisi";
			$logs['Type'] = "success";
			$logs['Tgl'] = date("Y-m-d H:i:s");
			$this->m_logs->save_logs($logs);					
			redirect("/nota_dinas/view_disposisi/".$IdNotaDinas);
		} catch (PDOEception $e) {
			/** UPDATE LOGS */
			$logs['Authorss'] = $this->session->userdata('Nama');
			$logs['Pesan'] = $e->getMessage();
			$logs['Modul'] = "Disposisi";
			$logs['Type'] = "error";
			$logs['Tgl'] = date("Y-m-d H:i:s");
			$this->m_logs->save_logs($logs);					
			redirect("/nota_dinas/view_disposisi/".$IdNotaDinas);
		}
	
		
	}
	
	private function load_all_disposisi($NoDokumen,$IdNotaDinas){
		$jumlah_data = count($this->m_nota_dinas->get_data_disposisi($NoDokumen), COUNT_RECURSIVE);
		if($jumlah_data > 0){
			$data =  json_encode($this->m_nota_dinas->get_data_disposisi($NoDokumen));
			$encript = base64_encode($data);
			$r['Disposisi'] = $encript;
			$this->m_nota_dinas->update_data($r,$IdNotaDinas);
		}else{
			$r['Disposisi'] = "";
			$this->m_nota_dinas->update_data($r,$IdNotaDinas);
		}
	}

	

}
