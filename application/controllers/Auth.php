<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

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
			$this->load->helper('url'); 
			$this->load->model('m_auth');
			$this->load->model('m_logs');
	}
	public function index()
	{
		$this->load->view('login');
	}

	public function proses(){
		$Username = $this->input->post('Username');
		$Password = "e-office.".$this->input->post('Password');
		if($this->m_auth->login_user($Username,$Password)){$logs['Authorss'] = $this->session->userdata('Nama');
			$logs['Pesan'] = "User a.n ".$this->session->userdata('Nama')." berhasil masuk sistem";
			$logs['Modul'] = "Login";
			$logs['Type'] = "success";
			$logs['Tgl'] = date("Y-m-d H:i:s");
			$this->m_logs->save_logs($logs);
			redirect('/');
			
		}else{
			$this->session->set_flashdata('error','Username / Password salah');
			$this->m_auth->login_user($Username,$Password);
			redirect('auth');
		}
	}

	public function logout(){
		$this->session->unset_userdata('Username');
		$this->session->unset_userdata('Nama');
		$this->session->unset_userdata('Direktorat');
		$this->session->unset_userdata('KodeDirektorat');
		$this->session->unset_userdata('Level');
		$this->session->unset_userdata('KodeLevel');
		$this->session->unset_userdata('is_login');
		redirect('auth');
	}
	
}
