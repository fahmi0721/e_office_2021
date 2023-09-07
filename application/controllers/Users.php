<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

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
			$this->load->model('user_model');
			$this->load->model('m_auth');
			$this->load->model('m_logs');
			$this->m_auth->cek_login();
			$this->m_auth->cek_hak_akses('users');
	}
		
	public function index(){
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		$this->load->view('modul/users/users_view');
		$this->load->view('_template/footer');
	}

	public function show_data(){
		$JumRow = $this->user_model->jumlah_data();
		$RowPage = $this->input->post('Row');
		$Page = $this->input->post('Page');
		$Search = $this->input->post('Search');
		$offset=($Page - 1) * $RowPage;
		$JumPage = ceil($JumRow/$RowPage);
		$data['data'] = $this->user_model->show_data($RowPage,$offset,$Search);
		$data['jumlah_data'] = $JumRow;
		$data['jumlah_page'] = $JumPage;
		$data['NoAwal'] = $offset+1;
		echo json_encode($data);
	}

	public function tambah(){
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		$this->load->view('modul/users/users_tambah');
		$this->load->view('_template/footer');
	}

	public function edit(){
		$Id = $this->uri->segment(3);
		$data['data'] = $this->user_model->get_data($Id);
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		$this->load->view('modul/users/users_edit',$data);
		$this->load->view('_template/footer');
	}

	public function save(){
		$r = array();
		try {
			$data['Nama'] = strtoupper($this->input->post('Nama'));
			$data['Direktorat'] = $this->input->post('Direktorat');
			$data['Username'] = $this->input->post('Username');
			$data['Password'] = password_hash("e-office.".$this->input->post('Password'),PASSWORD_DEFAULT);
			$data['Status'] = $this->input->post('Status');
			$data['Level'] = $this->input->post('Level');
			$DuplicateUser = $this->user_model->cek_duplicate($data['Username']);
			if($DuplicateUser <= 0){
				$save = $this->user_model->save_data($data);
				$r['status'] = "sukses";
				$r['pesan'] = "Data user a.n ".ucfirst($data['Nama'])." berhasil di masukkan kedalam sistem";
				
				/** UPDATE LOGS */
				$logs['Authorss'] = $this->session->userdata('Nama');
				$logs['Pesan'] = $r['pesan'];
				$logs['Modul'] = "User";
				$logs['Type'] = "success";
				$logs['Tgl'] = date("Y-m-d H:i:s");
				$this->m_logs->save_logs($logs);


				echo json_encode($r);
			}else{
				$r['status'] = "gagal";
				$r['pesan'] = "Data username ".$data['Username']." telah tersedia dalam sistem. silahkan masukkan username yang lain";
				/** UPDATE LOGS */
				$logs['Authorss'] = $this->session->userdata('Nama');
				$logs['Pesan'] = $r['pesan'];
				$logs['Modul'] = "User";
				$logs['Type'] = "error";
				$logs['Tgl'] = date("Y-m-d H:i:s");
				$this->m_logs->save_logs($logs);
				echo json_encode($r);
			}
		} catch (PDOException $e) {
			$r['status'] = "gagal";
			$r['pesan'] = "System Error : ".$e->getMessage();
			echo json_encode($r);
		}
		
		
	}

	public function update(){
		$r = array();
		try {
			$Id = $this->input->post('Id');
			$data['Nama'] = strtoupper($this->input->post('Nama'));
			$data['Direktorat'] = $this->input->post('Direktorat');
			$data['Username'] = $this->input->post('Username');
			if(!empty($this->input->post('Password'))){
				$data['Password'] = password_hash("e-office.".$this->input->post('Password'),PASSWORD_DEFAULT);
			}
			$data['Status'] = $this->input->post('Status');
			$data['Level'] = $this->input->post('Level');
			$update = $this->user_model->update_data($data,$Id);
			$r['status'] = "sukses";
			$r['pesan'] = "Data user a.n ".ucfirst($data['Nama'])." berhasil diubah kedalam sistem";
			/** UPDATE LOGS */
			$logs['Authorss'] = $this->session->userdata('Nama');
			$logs['Pesan'] = $r['pesan'];
			$logs['Modul'] = "User";
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
			$logs['Modul'] = "User";
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
			$usr = $this->user_model->get_data($Id);
			$res = $this->user_model->hapus_data($Id);
			$result['status'] = "sukses";
			$result['pesan'] = "Data user a.n ".$usr->Nama." berhasil dihapus dalam sistem";
			/** UPDATE LOGS */
			$logs['Authorss'] = $this->session->userdata('Nama');
			$logs['Pesan'] = $r['pesan'];
			$logs['Modul'] = "User";
			$logs['Type'] = "success";
			$logs['Tgl'] = date("Y-m-d H:i:s");
			$this->m_logs->save_logs($logs);
			echo json_encode($result);
		} catch (PDOException $e) {
			$result['status'] = "gagal";
			$result['pesan'] = $e->getMessage();
			/** UPDATE LOGS */
			$logs['Authorss'] = $this->session->userdata('Nama');
			$logs['Pesan'] = $r['pesan'];
			$logs['Modul'] = "User";
			$logs['Type'] = "error";
			$logs['Tgl'] = date("Y-m-d H:i:s");
			$this->m_logs->save_logs($logs);
			echo json_encode($result);
		}
	}

}
