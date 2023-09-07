<?php
class M_request_surat extends CI_Model {

    public function __construct(){
        parent::__construct();
	}

    function show_data($number,$offset,$Search,$By){
        $this->db->select("DATE_FORMAT(TglApprove,'%Y-%m-%d') as TglApprove,Id,NoDokumen,TglSurat,Perihal,Authorss,Status,Direktorat,Kepada,DirektoratText,NoSurat");
        $this->db->like($By, $Search); 
        $this->db->order_by("Status ASC, Id DESC"); 
        if($this->session->userdata('KodeLevel') == 2){
            $KodeDir = $this->session->userdata('KodeDirektorat');
            $this->db->where("Direktorat", $KodeDir);
        }
        $data=array();
        return $query = $this->db->get('e_office_request_surat',$number,$offset)->result();
    }

    function save_data($data){
        return $this->db->insert("e_office_request_surat", $data);
    }

    function jumlah_data(){
        if($this->session->userdata('KodeLevel') == 2){
            $KodeDir = $this->session->userdata('KodeDirektorat');
            $this->db->where("Direktorat", $KodeDir);
        }
        return $query = $this->db->get('e_office_request_surat')->num_rows();
    }

    function get_data($Id){
        return $query = $this->db->get_where('e_office_request_surat', array('Id' => $Id))->result()[0];
    }

    function get_data_sk($Id){
        return $query = $this->db->get_where('e_office_surat_keluar', array('Id' => $Id))->result()[0];
    }

    function get_jenis(){
        $this->db->select("Kode, Jenis");
        return $query = $this->db->get('e_office_jenis_surat')->result();
    }

    function get_nomor_surat($data){
        $Kode = $data['Kode'];
        $Tahun = substr($data['TglSurat'],0,4);
        $this->db->select("NoSurat");
        $this->db->from("e_office_surat_keluar");
        $this->db->where("KodeJenisSurat", $Kode);
        $this->db->where("DATE_FORMAT(TglSurat,'%Y')", $Tahun);
        $this->db->order_by("Id","DESC");
        $this->db->limit("1");
        $query = $this->db->get();
        $row = $query->num_rows();
        if($row > 0){
            $rs = $query->result()[0];
            $pecah = explode("/",$rs->NoSurat);
            $new_number = $pecah[0]+1;
            $last_page = $pecah[2];
            if($new_number > 20){
                $last_page = $last_page + 1;
                $NomorSurat = "1/".$Kode."/".$last_page."/ISMA-".$Tahun;
                return $NomorSurat;
            }else{
                $NomorSurat = $new_number."/".$Kode."/".$last_page."/ISMA-".$Tahun;
                return $NomorSurat;
            }
        }else{
            if($Kode != ""){
                $NomorSurat = "1/".$Kode."/1/ISMA-".$Tahun;
                return $NomorSurat;
            }else{
                return "";
            }
        }
    }

    function update_data($data,$Id){
        $this->db->where('Id', $Id);
        $update = $this->db->update('e_office_request_surat', $data);
        if($update){ return true; }else{ return false;}   
    }

    function hapus_data($Id){
        $this->db->where('Id', $Id);
        $delete = $this->db->delete('e_office_request_surat');
        if($delete){ return true; }else{ return false;}
    }

    function save_data_approve($data){
        $this->db->insert("e_office_surat_keluar", $data);
        return $this->db->insert_id();
    }
    

       

}
?>