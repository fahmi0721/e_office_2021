<?php
class User_model extends CI_Model {

    public function __construct(){
        parent::__construct();
	}
  
    function save_data($data){
        $insert = $this->db->insert("e_office_users", $data);
        if($insert){ return true; }else{ return false;}   
    }

    function cek_duplicate($Username){
        $this->db->where('Username',$Username);
        return $this->db->count_all_results('e_office_users');
    }

    function show_data($number,$offset,$Search){
        $this->db->like('Nama', $Search); 
        $this->db->or_like('Username', $Search);
        return $query = $this->db->get('e_office_users',$number,$offset)->result();
    }

    function jumlah_data(){
        return $query = $this->db->get('e_office_users')->num_rows();
    }

    function get_data($Id){
        return $query = $this->db->get_where('e_office_users', array('Id' => $Id))->result()[0];
    }

    function update_data($data,$Id){
        $this->db->where('Id', $Id);
        $update = $this->db->update('e_office_users', $data);
        if($update){ return true; }else{ return false;}   
    }

    function hapus_data($Id){
        $this->db->where('Id', $Id);
        $delete = $this->db->delete('e_office_users');
        if($delete){ return true; }else{ return false;}
    }
    

       

}
?>