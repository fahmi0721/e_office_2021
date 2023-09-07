<?php
class M_surat_masuk extends CI_Model {

    public function __construct(){
        parent::__construct();
        
	}
    

    function cek_duplicate($NoSurat){
        $this->db->where('NoSurat',$NoSurat);
        return $this->db->count_all_results('e_office_surat_masuk');
    }

     function getMaster($data){
        $this->db->or_where_in('Kode', $data);
        return $query = $this->db->get('e_office_mster_data')->result();
    }
    
    function get_temp_dari($Dari){
        $this->db->select("Dari as label");
        $this->db->like('Dari', $Dari);
        $this->db->group_by("Dari");
        $this->db->limit(10);
        return $query = $this->db->get('e_office_surat_masuk')->result();
    }

    function get_baju_surat($tahun){
        $this->db->select("BajuSurat");
        $this->db->where("DATE_FORMAT(TglMasukSurat,'%Y')",$tahun);
        $this->db->order_by("Id DESC");
        $r = $this->db->get('e_office_surat_masuk')->row();
        if(!empty($r->BajuSurat)){
            return $r->BajuSurat + 1;
        }else{
            return  1;
        }
        
    }

    function save_data($data){
        $insert = $this->db->insert("e_office_surat_masuk", $data);
        if($insert){ return true; }else{ return false;}   
    }

    function show_data($number,$offset,$Search,$By){
        $this->db->like($By, $Search); 
        $this->db->order_by('Id', 'DESC');
        return $query = $this->db->get('e_office_surat_masuk',$number,$offset)->result();
    }
    function jumlah_data(){
        return $query = $this->db->get('e_office_surat_masuk')->num_rows();
    }
    function jumlah_disposisi($NoDokumenSurat){
        $this->db->where('NoDokumenSurat',$NoDokumenSurat);
        return $query = $this->db->get('e_office_disposisi')->num_rows();
    }

    function get_data($Id){
        return $query = $this->db->get_where('e_office_surat_masuk', array('Id' => $Id))->row();
    }

    function get_file($Id){
        return $query = $this->db->get_where('e_office_surat_masuk', array('Id' => $Id))->row();
    }
    function update_data($data,$Id){
        $this->db->where('Id', $Id);
        $update = $this->db->update('e_office_surat_masuk', $data);
        if($update){ return true; }else{ return false;}   
    }
    function hapus_data($Id){
        $this->db->where('Id', $Id);
        $delete = $this->db->delete('e_office_surat_masuk');
        if($delete){ return true; }else{ return false;}
    }

    /** DISPOSISI */
    function save_data_disposisi($data){
        $insert = $this->db->insert("e_office_disposisi", $data);
        if($insert){ return true; }else{ return false;}   
    }

    function get_data_disposisi($NoDokumenSurat){
        $this->db->where('NoDokumenSurat',$NoDokumenSurat);
        $this->db->order_by('Id', 'DESC');
        return $query = $this->db->get('e_office_disposisi')->result();
    }


    function get_data_disposisi_byone($Id){
        return $query = $this->db->get_where('e_office_disposisi', array('Id' => $Id))->result()[0];
    }

    function update_data_disposisi($data,$Id){
        $this->db->where('Id', $Id);
        $update = $this->db->update("e_office_disposisi", $data);
        if($update){ return true; }else{ return false;}   
    }

    function delete_disposisi($Id){
        $this->db->where('Id', $Id);
        $delete = $this->db->delete('e_office_disposisi');
        if($delete){ return true; }else{ return false;}
    }

    function get_data_all($data){
        $start_date = $data['Dari'];
        $end_date = $data['Sampai'];
        $this->db->select("*");
        $this->db->where('TglSurat BETWEEN "'. date('Y-m-d', strtotime($start_date)). '" and "'. date('Y-m-d', strtotime($end_date)).'"');
        return $query = $this->db->get("e_office_surat_masuk")->result();
    }
    
    

       

}
?>