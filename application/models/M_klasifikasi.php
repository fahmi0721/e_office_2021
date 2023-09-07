<?php
class M_klasifikasi extends CI_Model {

    public function __construct(){
        parent::__construct();
	}
  
    function save_data($data){
        $insert = $this->db->insert("e_office_mster_data", $data);
        if($insert){ return true; }else{ return false;}   
    }

    function cek_duplicate($Kode){
        $this->db->where('Kode',$Kode);
        $this->db->where('plug',"Y");
        return $this->db->count_all_results('e_office_mster_data');
    }

    function show_data($number,$offset,$Search,$By){
        $this->db->like($By, $Search); 
        return $query = $this->db->get('e_office_mster_data',$number,$offset)->result();
    }

    function jumlah_data(){
        return $query = $this->db->get('e_office_mster_data')->num_rows();
    }

    function get_data($Id){
        return $query = $this->db->get_where('e_office_mster_data', array('Id' => $Id))->result()[0];
    }

    function update_data($data,$Id){
        $this->db->where('Id', $Id);
        $update = $this->db->update('e_office_mster_data', $data);
        if($update){ return true; }else{ return false;}   
    }

    function hapus_data($Id){
        $this->db->where('Id', $Id);
        $delete = $this->db->delete('e_office_mster_data');
        if($delete){ return true; }else{ return false;}
    }
    

       

}
?>