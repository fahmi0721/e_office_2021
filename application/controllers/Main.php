<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

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
			$this->load->model('m_dashboard',"md");
			$this->m_auth->cek_login();
	}
	public function index()
	{
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		// if($this->session->userdata('KodeLevel') == '2'){
		// 	$KodeDirektorat = $this->session->userdata('KodeDirektorat');
		// 	$data['rq_surat'] = $this->md->load_request_surat_today_by_user($KodeDirektorat);
		// 	$data['sm'] = $this->md->jumlah_sm();
		// 	$data['nd'] = $this->md->jumlah_nd();
		// 	$data['sk'] = $this->md->jumlah_sk();
		// 	$data['rq'] = $this->md->jumlah_rq();
		// 	$this->load->view('dashboard/pelaksana',$data);
		// }else{
		// 	$this->load->view('main');
		// }
			$this->load->view('main');
		
		
		$this->load->view('_template/footer');
	}

	public function page_404()
	{
		$this->load->view('_template/header');
		$this->load->view('_template/sidebar');
		$this->load->view('page_404');
		$this->load->view('_template/footer');
	}
}
