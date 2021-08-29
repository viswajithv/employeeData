<?php
class Employee_model extends CI_Model{
    public function get_employee_data(){
        //$this->db->where("")
        $query = $this->db->get("employee_data");
        return $query->result_array();
    }

    public function insert_data($data){
        $this->db->insert('employee_data',$data);
    }
}