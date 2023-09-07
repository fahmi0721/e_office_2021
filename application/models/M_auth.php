<?php
class M_auth extends CI_Model {

    public function __construct(){
        parent::__construct();
	}
    function cek_login(){
        if(empty($this->session->userdata('is_login'))){
            echo "<script>alert('Harus login dulu!')</script>";
			redirect('auth');
		}
    }

    function cek_hak_akses($modul){
        $data[0] = array();
        $data[1] = array("users","jenis_surat","klasifikasi");
        $data[2] = array("users","jenis_surat","klasifikasi","disposisi_notadinas","disposisi_suratmasuk","tambah_surat_masuk",'edit_surat_masuk','hapus_surat_masuk','approve_request_surat',"surat_keluar_up");
        $Level = $this->session->userdata('KodeLevel');
        if(in_array($modul,$data[$Level])){
            redirect("page_404");
        }
    }

    function login_user($username,$password){
        $query = $this->db->get_where('e_office_users',array('Username'=>$username));
        if($query->num_rows() > 0)
        {
            $Direktorat = array("SDM","Operasi","Keuangan");
            $Level = array("Admin","TU","Pelaksana");
            $data_user = $query->row();
            if (password_verify($password, $data_user->Password)) {
                $this->session->set_userdata('Username',$username);
				$this->session->set_userdata('Nama',$data_user->Nama);
				$this->session->set_userdata('Direktorat',$Direktorat[$data_user->Direktorat]);
                $this->session->set_userdata('KodeDirektorat',$data_user->Direktorat);
                $this->session->set_userdata('Level',$Level[$data_user->Level]);
				$this->session->set_userdata('KodeLevel',$data_user->Level);
				$this->session->set_userdata('is_login',TRUE);
                return TRUE;
            } else {
                return FALSE;
            }
        }
        else
        {
            return FALSE;
        }
	}
    
    

       

}
?>