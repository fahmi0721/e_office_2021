<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_surat extends CI_Controller {

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
			$this->load->model('m_jenis_surat');
			$this->load->model('m_auth');
			$this->load->model('m_logs');
			$this->m_auth->cek_login();
			$this->m_auth->cek_hak_akses('jenis_surat');
	}
		
	public function index(){
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		$this->load->view('modul/jenis_surat/view');
		$this->load->view('_template/footer');
	}

	public function show_data(){
		$JumRow = $this->m_jenis_surat->jumlah_data();
		$RowPage = $this->input->post('Row');
		$By = $this->input->post('By');
		$Page = $this->input->post('Page');
		$Search = $this->input->post('Search');
		$offset=($Page - 1) * $RowPage;
		$JumPage = ceil($JumRow/$RowPage);
		$data['data'] = $this->m_jenis_surat->show_data($RowPage,$offset,$Search,$By);
		$data['jumlah_data'] = $JumRow;
		$data['jumlah_page'] = $JumPage;
		$data['NoAwal'] = $offset+1;
		echo json_encode($data);
	}

	public function tambah(){
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		$this->load->view('modul/jenis_surat/tambah');
		$this->load->view('_template/footer');
	}

	public function edit(){
		$Id = $this->uri->segment(3);
		$data['data'] = $this->m_jenis_surat->get_data($Id);
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		$this->load->view('modul/jenis_surat/edit',$data);
		$this->load->view('_template/footer');
	}

	public function save(){
		$r = array();
		try {
			$data['Jenis'] = strtoupper($this->input->post('Jenis'));
			$data['Kode'] = $this->input->post('Kode');
			$data['Authorss'] = $this->session->userdata('Nama');
			$data['Keterangan'] = $this->input->post('Keterangan');
			$data['TglCreate'] = date("Y-m-d H:i:s");
			$DuplicateData = $this->m_jenis_surat->cek_duplicate($data['Kode']);
			if($DuplicateData <= 0){
				$save = $this->m_jenis_surat->save_data($data);
				$r['status'] = "sukses";
				$r['pesan'] = "Data jenis surat dengan kode ".$data['Kode']." berhasil di masukkan kedalam sistem";
				
				/** UPDATE LOGS */
				$logs['Authorss'] = $this->session->userdata('Nama');
				$logs['Pesan'] = $r['pesan'];
				$logs['Modul'] = "JenisSurat";
				$logs['Type'] = "success";
				$logs['Tgl'] = date("Y-m-d H:i:s");
				$this->m_logs->save_logs($logs);


				echo json_encode($r);
			}else{
				$r['status'] = "gagal";
				$r['pesan'] = "Data jenis surat dengan kode : ".$data['Kode']." telah tersedia dalam sistem. silahkan masukkan kode yang lain";
				/** UPDATE LOGS */
				$logs['Authorss'] = $this->session->userdata('Nama');
				$logs['Pesan'] = $r['pesan'];
				$logs['Modul'] = "JenisSurat";
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
			$logs['Modul'] = "JenisSurat";
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
			$data['Jenis'] = strtoupper($this->input->post('Jenis'));
			$data['Kode'] = $this->input->post('Kode');
			$data['Keterangan'] = $this->input->post('Keterangan');
			$update = $this->m_jenis_surat->update_data($data,$Id);
			$r['status'] = "sukses";
			$r['pesan'] = "Data jenis surat dengan kode ".$data['Kode']." berhasil diubah kedalam sistem";
			/** UPDATE LOGS */
			$logs['Authorss'] = $this->session->userdata('Nama');
			$logs['Pesan'] = $r['pesan'];
			$logs['Modul'] = "JenisSurat";
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
			$logs['Modul'] = "JenisSurat";
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
			$dt = $this->m_jenis_surat->get_data($Id);
			$res = $this->m_jenis_surat->hapus_data($Id);
			$result['status'] = "sukses";
			$result['pesan'] = "Data jenis surat dengan kode ".$dt->Kode." berhasil dihapus dalam sistem";
			/** UPDATE LOGS */
			$logs['Authorss'] = $this->session->userdata('Nama');
			$logs['Pesan'] = $result['pesan'];
			$logs['Modul'] = "JnsSurat";
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
			$logs['Modul'] = "JnsSurat";
			$logs['Type'] = "error";
			$logs['Tgl'] = date("Y-m-d H:i:s");
			$this->m_logs->save_logs($logs);
			echo json_encode($result);
		}
	}

}
