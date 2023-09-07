<?php
class M_jenis_surat extends CI_Model {

    public function __construct(){
        parent::__construct();
	}
  
    function save_data($data){
        $insert = $this->db->insert("e_office_jenis_surat", $data);
        if($insert){ return true; }else{ return false;}   
    }

    function cek_duplicate($Kode){
        $this->db->where('Kode',$Kode);
        return $this->db->count_all_results('e_office_jenis_surat');
    }

    function show_data($number,$offset,$Search,$By){
        $this->db->like($By, $Search); 
        return $query = $this->db->get('e_office_jenis_surat',$number,$offset)->result();
    }

    function jumlah_data(){
        return $query = $this->db->get('e_office_jenis_surat')->num_rows();
    }

    function get_data($Id){
        return $query = $this->db->get_where('e_office_jenis_surat', array('Id' => $Id))->result()[0];
    }

    function update_data($data,$Id){
        $this->db->where('Id', $Id);
        $update = $this->db->update('e_office_jenis_surat', $data);
        if($update){ return true; }else{ return false;}   
    }

    function hapus_data($Id){
        $this->db->where('Id', $Id);
        $delete = $this->db->delete('e_office_jenis_surat');
        if($delete){ return true; }else{ return false;}
    }
    

       

}
?>