<?php
class M_dashboard extends CI_Model {

    public function __construct(){
        parent::__construct();
	}
    

    function load_request_surat_today_by_user($Direktorat){
        $this->db->where("Direktorat",$Direktorat);
        $this->db->where("DATE_FORMAT(TglCreate,'%Y-%m-%d') =",date("Y-m-d"));
        return $query = $this->db->get('e_office_request_surat',0,10)->result(); 
    }
    
    function jumlah_sm(){
        $this->db->where("DATE_FORMAT(TglSurat, '%Y') =",date("Y"));
        return $query = $this->db->get('e_office_surat_masuk')->num_rows();
    }

    function jumlah_nd(){
        $YearNow = date("Y");
        if($this->session->userdata('KodeLevel') == "2"){
            $KodeDari = array("B2","B3","B1");
            $iKode = $KodeDari[$this->session->userdata('KodeDirektorat')];
            $this->db->where("KodeDari",$iKode);
            
        }
        $this->db->where("DATE_FORMAT(TglSurat, '%Y') =",$YearNow);
        return $query = $this->db->get('e_office_nota_dinas')->num_rows();
    }

    function jumlah_sk(){
        $YearNow = date("Y");
        $this->db->where("DATE_FORMAT(TglSurat, '%Y') = ",$YearNow);
        return $query = $this->db->get('e_office_surat_keluar')->num_rows();
    }

    function jumlah_rq(){
        if($this->session->userdata('KodeLevel') == "2"){
            $iKode = $this->session->userdata('KodeDirektorat');
            $this->db->where("Direktorat",$iKode);
        }
        $YearNow = date("Y");
        $this->db->where("DATE_FORMAT(TglSurat, '%Y') = ",$YearNow);
        return $query = $this->db->get('e_office_request_surat')->num_rows();
    }
    
    

       

}
?>