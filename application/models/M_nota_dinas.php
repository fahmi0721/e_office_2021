<?php
class M_nota_dinas extends CI_Model {

    public function __construct(){
        parent::__construct();
        
	}
    
    function getMaster(){
        $this->db->where("plug","Y");
        return $query = $this->db->get('e_office_mster_data')->result();
    }


    function cek_duplicate($NoSurat){
        $this->db->where('NoSurat',$NoSurat);
        return $this->db->count_all_results('e_office_nota_dinas');
    }
    function get_dari($Kode){
        $this->db->select("Nama");
        $this->db->where('Kode',$Kode);
        $this->db->where('plug',"Y");
        $r = $this->db->get('e_office_mster_data')->row();
        if(!empty($r->Nama)){
            return $r->Nama;
        }
    }
    
    function getNomorSurat($tahun){
        $bulanNow = date("m");
        $KodeDari = array("B2","B3","B1");
        $iKode = $KodeDari[$this->session->userdata('KodeDirektorat')];
        $this->db->select('NoSurat');
        $this->db->where('KodeDari', $iKode);
        $this->db->where("DATE_FORMAT(TglSurat,'%Y')", $tahun);
        $this->db->order_by('Id','DESC');
        $r = $this->db->get('e_office_nota_dinas')->row();
        if(!empty($r->NoSurat)){
            $NomorSurat = $r->NoSurat;
            $PisahSlash = explode("/",$NomorSurat);
            $PisahTitik = explode(".",$PisahSlash[0]);
            $NomorNota = intval($PisahTitik[1])+1;
            $Nomor = "ND.".$NomorNota."/".sprintf("%02d",$bulanNow)."/".$iKode."-".$tahun;
            return $Nomor;
        }else{
            $Nomor = "ND.1/".sprintf("%02d",$bulanNow)."/".$iKode."-".$tahun;
            return $Nomor;
        }
        
    }

    function getNomorSuratSdm($tahun,$iKode){
        $bulanNow = date("m");
        $this->db->select('NoSurat');
        $this->db->where('KodeDari', $iKode);
        $this->db->where("DATE_FORMAT(TglSurat,'%Y')", $tahun);
        $this->db->order_by('Id','DESC');
        $r = $this->db->get('e_office_nota_dinas')->row();
        if(!empty($r->NoSurat)){
            $NomorSurat = $r->NoSurat;
            $PisahSlash = explode("/",$NomorSurat);
            $PisahTitik = explode(".",$PisahSlash[0]);
            $NomorNota = intval($PisahTitik[1])+1;
            $Nomor = "ND.".$NomorNota."/".sprintf("%02d",$bulanNow)."/".$iKode."-".$tahun;
            return $Nomor;
        }else{
            $Nomor = "ND.1/".sprintf("%02d",$bulanNow)."/".$iKode."-".$tahun;
            return $Nomor;
        }
        
    }

    function save_data($data){
        $insert = $this->db->insert("e_office_nota_dinas", $data);
        if($insert){ return true; }else{ return false;}   
    }

    function show_data($number,$offset,$Search,$By){
        $KodeDari = array("B2","B3","B1");
        $iKode = $KodeDari[$this->session->userdata('KodeDirektorat')];
        $this->db->like($By, $Search); 
        if($this->session->userdata('KodeLevel') == "2"){
            $this->db->where('KodeDari',$iKode);
        }
        $this->db->order_by('Id', 'DESC');
        return $query = $this->db->get('e_office_nota_dinas',$number,$offset)->result();
    }
    function jumlah_data(){
        $KodeDari = array("B2","B3","B1");
        $iKode = $KodeDari[$this->session->userdata('KodeDirektorat')];
        if($this->session->userdata('KodeLevel') == "2"){
            $this->db->where('KodeDari',$iKode);
        }
        return $query = $this->db->get('e_office_nota_dinas')->num_rows();
    }
    function jumlah_disposisi($NoDokumenSurat){
        $this->db->where('NoDokumenSurat',$NoDokumenSurat);
        return $query = $this->db->get('e_office_disposisi')->num_rows();
    }

    function get_data($Id){
        $this->db->select("*");
        $this->db->select("DATE_FORMAT(TglCreate,'%Y-%m-%d') as TglMasukSurat");
        return $query = $this->db->get_where('e_office_nota_dinas', array('Id' => $Id))->result()[0];
    }

    function get_file($Id){
        
        return $query = $this->db->get_where('e_office_nota_dinas', array('Id' => $Id))->result()[0];
    }
    function update_data($data,$Id){
        $this->db->where('Id', $Id);
        $update = $this->db->update('e_office_nota_dinas', $data);
        if($update){ return true; }else{ return false;}   
    }
    function hapus_data($Id){
        $this->db->where('Id', $Id);
        $delete = $this->db->delete('e_office_nota_dinas');
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
    
    /**
     * CETAK ALL NOTA DINAS
     */

     function get_data_all($data){
        $start_date = $data['Dari'];
        $end_date = $data['Sampai'];
        $master = $data['master'];
        $this->db->select("*");
        $this->db->select("DATE_FORMAT(TglCreate,'%Y-%m-%d') as TglMasukSurat");
        $this->db->where('TglSurat BETWEEN "'. date('Y-m-d', strtotime($start_date)). '" and "'. date('Y-m-d', strtotime($end_date)).'"');
        if($master != "all"){
            $this->db->where('LEFT(NoDokumen,2) = "'.$master.'"');
        }
        return $query = $this->db->get("e_office_nota_dinas")->result();
    }
    

       

}
?>