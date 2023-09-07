<?php
class M_logs extends CI_Model {

    public function __construct(){
        parent::__construct();
	}
    

    function save_logs($data){
        $insert = $this->db->insert("e_office_logs", $data);
        if($insert){ return true; }else{ return false;}   
	}
    
    

       

}
?>