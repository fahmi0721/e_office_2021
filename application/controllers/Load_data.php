<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Load_data extends CI_Controller {

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
			$this->load->model('m_load_data');
			$this->load->model('m_auth');
			$this->m_auth->cek_login();
	}
	public function nota_dinas(){
		$Id = $this->uri->segment(3);
		$file = $this->m_load_data->get_nota_dinas($Id);
		$filepath = "./public/file/nota_dinas/".$file->FileSurat;
		$this->output
			->set_content_type('application/pdf')
			->set_output(file_get_contents($filepath));
	}

	public function disposisi(){
		$Id = $this->uri->segment(3);
		$file = $this->m_load_data->get_disposisi($Id);
		$filepath = "./public/file/disposisi/".$file->File;
		$this->output
		->set_content_type('application/pdf')
		->set_output(file_get_contents($filepath));
	}

	public function surat_masuk(){
		$Id = $this->uri->segment(3);
		$file = $this->m_load_data->get_surat_masuk($Id);
		$filepath = "./public/file/surat_masuk/".$file->FileSurat;
		$this->output
		->set_content_type('application/pdf')
		->set_output(file_get_contents($filepath));
	}

	public function surat_keluar(){
		$Id = $this->uri->segment(3);
		$file = $this->m_load_data->get_surat_keluar($Id);
		$filepath = "./public/file/surat_keluar/".$file->File;
		$this->output
		->set_content_type('application/pdf')
		->set_output(file_get_contents($filepath));
	}

	
}
