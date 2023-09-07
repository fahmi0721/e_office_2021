<?php
class M_surat_keluar extends CI_Model {

    public function __construct(){
        parent::__construct();
	}

    function show_data($number,$offset,$Search,$By){
        $this->db->select("*, DATE_FORMAT(TglCreateApp,'%Y-%m-%d') as TglCreateApp");
        $this->db->like($By, $Search); 
        $this->db->order_by("Id DESC , LEFT(NoSurat,1) DESC"); 
        $data=array();
        return $query = $this->db->get('e_office_surat_keluar',$number,$offset)->result();
    }

    function jumlah_data($Search,$By){
        $this->db->like($By, $Search); 
        return $query = $this->db->get('e_office_surat_keluar')->num_rows();
    }

    function get_data($Id){
        return $query = $this->db->get_where('e_office_surat_keluar', array('Id' => $Id))->result()[0];
    }

    function get_file($Id){
        $this->db->select("File");
        return $query = $this->db->get_where('e_office_surat_keluar', array('Id' => $Id))->row();
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
        $update = $this->db->update('e_office_surat_keluar', $data);
        if($update){ return true; }else{ return false;}   
    }

    function update_data_rq($data,$NoSurat){
        $this->db->where('NoSurat', $NoSurat);
        $update = $this->db->update('e_office_request_surat', $data);
        if($update){ return true; }else{ return false; }   
    }

    function hapus_data($Id){
        $this->db->where('Id', $Id);
        $delete = $this->db->delete('e_office_surat_keluar');
        if($delete){ return true; }else{ return false;}
    }

    function save_data($data){
        return $this->db->insert("e_office_surat_keluar", $data);
    }

    function get_data_all($data){
        $start_date = $data['Dari'];
        $end_date = $data['Sampai'];
        $jenis_surat = $data['jenis_surat'];
        $this->db->select("*");
        $this->db->where('TglSurat BETWEEN "'. date('Y-m-d', strtotime($start_date)). '" and "'. date('Y-m-d', strtotime($end_date)).'"');
        if($jenis_surat != "all"){
            $this->db->where('KodeJenisSurat = "'.$jenis_surat.'"');
        }
        return $query = $this->db->get("e_office_surat_keluar")->result();
    }
    

       

}
?>