<?php
class M_load_data extends CI_Model {

    public function __construct(){
        parent::__construct();
        
	}
  
    function get_nota_dinas($Id){
        $this->db->select("FileSurat");
        return $query = $this->db->get_where('e_office_nota_dinas', array('Id' => $Id))->result()[0];
    }

    function get_disposisi($Id){
        $this->db->select("File");
        return $query = $this->db->get_where('e_office_disposisi', array('Id' => $Id))->result()[0];
    }

    function get_surat_masuk($Id){
        $this->db->select("FileSurat");
        return $query = $this->db->get_where('e_office_surat_masuk', array('Id' => $Id))->row();
    }

    function get_surat_keluar($Id){
        $this->db->select("File");
        return $query = $this->db->get_where('e_office_surat_keluar', array('Id' => $Id))->row();
    }

    

    

       

}
?>