<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_details extends CI_Controller {
	public $emp_structure;
	public $emp_data;
	public $emp_date;

	public function __construct() {
        parent::__construct();
		$this->emp_structure = [
			'emp_name',
			'emp_code',
			'emp_depart',
			'emp_dob',
			'emp_join'
		];
		$this->emp_data = [
			'Employee Name',
			'Employee Code',
			'Employee Department',
			'Employee DOB',
			'Employee Join Date'
		];
		$this->emp_date = [
			'Employee DOB',
			'Employee Join Date'
		];
    }

	public function index(){
		$this->load->model("employee_model");
		$employee_data = $this->employee_model->get_employee_data();
		$this->data['employee_data']= $employee_data;
		$this->load->view("view_data",$this->data);
	}

	public function mapping_data(){
		
		if($this->input->post()){
			if(isset($_FILES['emp_upload'])){
				if ($_FILES["emp_upload"]["error"] > 0) {
					echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
				}else{
					
					$csv_file = $_FILES['emp_upload'];
					$uploaded_file_name = date('hiss') . $csv_file['name'];
					if (strtolower(pathinfo($uploaded_file_name, PATHINFO_EXTENSION)) != 'csv') 
						die(json_encode(array(
							'status' => false,
							'message' => 'Please upload a csv file.'
						)));
					
					
					$destination_file = FCPATH . 'assets/csv/uploads/' . $uploaded_file_name;
					if(!move_uploaded_file($csv_file['tmp_name'], $destination_file)) {
						die(json_encode(array(
							'status' => false,
							'message' => 'file upload is failed'
						)));
					}
					$csv_file = array_map('str_getcsv', file($destination_file));
					unlink($destination_file);
					$csv_headings = $csv_file[0];
					unset($csv_file[0]);
					$csv_contents= $csv_file;
					if(sizeof($csv_headings) < 5){
						die(json_encode(array(
							'status' => false,
							'message' => 'File requires minimum 5 columns'
						)));
					}

					if(sizeof($csv_contents) > 20){
						die(json_encode(array(
							'status' => false,
							'message' => 'File exceutes maximum of 20 rows'
						)));
					}

					$data = [
						'uploaded_data'	 => $csv_headings,
						'data_structure' => $this->emp_data,
					];

					$html = $this->load->view("mapping_data", $data, true);

					die(json_encode(array(
						'status' => true,
						'data'	 => $html
					)));
					
					
				}
			}
		}
	}

	public function validate_data(){
		if($this->input->post()){
			$post_data = $this->security->xss_clean($this->input->post());
			$validation_data = 0;
			$validation_msg  = "";
			$csv_file = $_FILES['emp_upload'];
			$uploaded_file_name = date('hiss') . $csv_file['name'];
			$destination_file = FCPATH . 'assets/csv/uploads/' . $uploaded_file_name;
			if(!move_uploaded_file($csv_file['tmp_name'], $destination_file)) {
				die(json_encode(array(
					'status' => false,
					'message' => 'file upload is failed'
				)));
			}
			$csv_file = array_map('str_getcsv', file($destination_file));
			unlink($destination_file);
			$csv_headings = $csv_file[0];
			$csv_first_data = $csv_file[1];
			$required_heading = $this->emp_data[$post_data['data_position']];
            
			$selected_heading = $csv_headings[$post_data['field_position']];
			$selected_value	  = $csv_first_data[$post_data['field_position']];
			if(empty($selected_value)){
				$validation_data = 1;
				$validation_msg  = $selected_heading. " field is empty";
			}else{
				if(in_array($required_heading, $this->emp_date)){
					$verify_data = str_replace('/', '-', $selected_value);
					if ($this->isDate($verify_data) === false) {
						$validation_data = 1;
						$validation_msg  = $required_heading. " requires a date format";
					}
				}
			}

			die(json_encode(array(
				'status' => true,
				'validation'	 => $validation_data,
				'validation_msg' => $validation_msg,
				'selected_value' => $selected_value
			)));
		}
	}

	public function upload_data(){
		$this->load->model("employee_model");
		if($this->input->post()){
			$post_data = $this->security->xss_clean($this->input->post());
			$csv_file = $_FILES['emp_upload'];
			$uploaded_file_name = date('hiss') . $csv_file['name'];
			$destination_file = FCPATH . 'assets/csv/uploads/' . $uploaded_file_name;
			if(!move_uploaded_file($csv_file['tmp_name'], $destination_file)) {
				die(json_encode(array(
					'status' => false,
					'message' => 'file upload is failed'
				)));
			}
			$csv_files = array_map('str_getcsv', file($destination_file));
			unlink($destination_file);
			$uploaded_data = $post_data['uploaded_data'];
			$flipped_data =array_flip($uploaded_data);
			ksort($flipped_data);
			
			$required_headings = array_combine($this->emp_data, $uploaded_data);
			unset($csv_files[0]);
			if(!empty($csv_files)){
				foreach($csv_files as $csv_file){
					$data = array_combine($flipped_data, $csv_file);
					ksort($data);
					$emplyee_data = array_combine($this->emp_structure, $data);
					$insert_data = [
						'emp_name' => $emplyee_data['emp_name'],
						'emp_code' => $emplyee_data['emp_code'],
						'emp_depart' => $emplyee_data['emp_depart'],
						'emp_dob' => date("Y-m-d", strtotime(str_replace('/', '-', $emplyee_data['emp_dob']))),
						'emp_join' => date("Y-m-d", strtotime(str_replace('/', '-', $emplyee_data['emp_join'])))
					];
					
					$this->employee_model->insert_data($insert_data);
				}
			}
			die(json_encode(array(
				'status' => true,
				'message' => "Employees are uploaded successfully."
			)));
		}
	}

    function isDate($value) 
    {
        if (!$value) {
            return false;
        }
        try {
            new \DateTime($value);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
