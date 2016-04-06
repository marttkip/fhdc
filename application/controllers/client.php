<?php session_start(); if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {
	
	public function index()
	{
		session_unset();
		$this->load->view('login');
	}
	
	public function client_registration($active)
	{
		$data['query'] = $this->get_personnel();
		$data['query3'] = $this->insuarance_company();
		$data['query4'] = $this->get_client_policy_types();
		$data['query5'] = $this->get_documents();
		
		if($_SESSION['current_client'] != NULL){
			$data['query6'] = $this->get_client_policy_documents();
		}
		$data['query7'] = $this->search_client();
		$data['query8'] = $active;
		$data['query9'] = $_SESSION['current_client_name'];
		
		$this->load->view('client/client', $data);
	}
	
	public function administration()
	{
		$data['query'] = $this->view_personnel();
		$data['query2'] = $this->get_policies();
		$data['query3'] = $this->get_policies2();
		$data['query4'] = $this->insuarance_companies();
		$data['query5'] = $this->get_client_policy_options();
		$data['query6'] = $this->get_employment_level();
		$data['query7'] = $this->search_commission();
		$data['query8'] = $this->search_personnel_commission();
		$this->load->view('administration/administration', $data);
	}
	
	public function view_personnel()
	{
		$table = "personnel";
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries($table); 
		
		return $result;
	}
	
	public function register_personnel()
	{
		$table = "personnel";
		$this->load->model('database', '', TRUE);
		$this->database->insert_entry($table); 
		$this->administration();
	}
	
	public function update_personnel()
	{
		$table = "personnel";
		$key = "personnel_id";
		$this->load->model('database', '', TRUE);
		$this->database->update_entry($table, $key); 
		$this->administration();
	}
	
	public function get_policies()
	{
		$table = "insuarance_policy, insuarance_company, client_policy_type";
		
		$where ='insuarance_company.insuarance_company_id = insuarance_policy.insuarance_company_id AND client_policy_type.insuarance_policy_id = insuarance_policy.insuarance_policy_id';
		
		$items = "client_policy_type.client_policy_type_id, client_policy_type.client_policy_type_name, insuarance_company.insuarance_company_name, insuarance_company.insuarance_company_id, insuarance_policy.insuarance_policy_id, insuarance_policy.insuarance_policy_name, insuarance_policy.insuarance_policy_description";
		
		$order = "insuarance_company.insuarance_company_name, insuarance_policy.insuarance_policy_name, client_policy_type.client_policy_type_name";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order); 
		//$result = $this->database->select_join();
		return $result;
	}
	
	public function get_policies2()
	{
		$table = "insuarance_policy, insuarance_company";
		
		$where ='insuarance_company.insuarance_company_id = insuarance_policy.insuarance_company_id AND insuarance_policy.insuarance_policy_id NOT IN (SELECT insuarance_policy_id FROM client_policy_type)';
		
		$items = "insuarance_company.insuarance_company_name, insuarance_company.insuarance_company_id, insuarance_policy.insuarance_policy_id, insuarance_policy.insuarance_policy_name, insuarance_policy.insuarance_policy_description";
		
		$order = "insuarance_company.insuarance_company_name, insuarance_policy.insuarance_policy_name";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order); 

		return $result;
	}
	
	public function register_policy()
	{
		$table = "insuarance_policy";
		$this->load->model('database', '', TRUE);
		$this->database->insert_entry($table); 
		$this->administration();
	}
	
	public function get_personnel()
	{
		
		$table = "personnel";
		
		$where ="personnel_type = 'Salesperson'";
		
		$items = "*";
		
		$order = "personnel_fname";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	
	public function register_client()
	{
		if($_SESSION['current_client'] != NULL){
			
			$table = "client";
			$key = "client_id";
			$key_value = $_SESSION['current_client'];
			
			$this->load->model('database', '', TRUE);
			$this->database->update_entry2($table, $key, $key_value); 
		}
		
		else{
			$table = "client";
			$this->load->model('database', '', TRUE);
			$this->database->insert_entry($table); 
			$_SESSION['current_client_name'] = $this->input->post("client_surname", TRUE)." ".$this->input->post("client_firstname", TRUE);
		}
				
		$active["step1_active"] = "";
		$active["step1_content_active"] = "tab-pane";
			
		$active["step2_active"] = "active";
		$active["step2_content_active"] = "tab-pane active";
		
		$active["step3_active"] = "";
		$active["step3_content_active"] = "tab-pane";
				
		$active["step4_active"] = "";
		$active["step4_content_active"] = "tab-pane";
		
		$this->client_registration($active);
	}
	
	public function insuarance_company()
	{
		$table = "insuarance_company";
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries($table); 
		
		return $result;
	}
	
	public function register_client_policy()
	{
		
		$table = "client_policy";
		$this->load->model('database', '', TRUE);
		$this->database->insert_entry_client_policy($table); 
		
		//if(!empty($_POST['client_options_value'])){
			$table = "client_options";
			$this->load->model('database', '', TRUE);
			$this->database->insert_entry_policy_options($table);
		//}
		$active["step1_active"] = "";
		$active["step1_content_active"] = "tab-pane";
			
		$active["step2_active"] = "";
		$active["step2_content_active"] = "tab-pane";
		
		$active["step3_active"] = "active";
		$active["step3_content_active"] = "tab-pane active";
				
		$active["step4_active"] = "";
		$active["step4_content_active"] = "tab-pane";
		
		$this->client_registration($active);
	}
	
	public function get_client_policy_types()
	{
		$table = "client_policy_type";
		$order = "client_policy_type_name";
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_order($table, $order); 
		
		return $result;
	}
	
	public function get_documents()
	{
		$table = "documents";
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries($table); 
		
		return $result;
	}
	
	public function get_client_policy_documents()
	{
		$where = "client_id = ".$_SESSION['current_client'];
		$items = "*";
		$order = "documents_id";
		$table = "client_policy_documents";
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order); 
		
		return $result;
	}
	
	public function register_documents()
	{
		
		// Temporary file name stored on the server
     	$tmpName  = $_FILES['client_policy_documents_name']['tmp_name'];  
       
     if(!empty($tmpName)){
		// Read the file 
		$fp = fopen($tmpName, 'r');
		$photo = fread($fp, filesize($tmpName));
		$photo = addslashes($photo);
      	fclose($fp);
		
		$documents_id = addslashes($_POST['documents_id']);
		
			$items = array(
   				'documents_id' => $documents_id,
   				'client_policy_documents_name' => $photo,
   				'client_id' => $_SESSION['current_client']
			);
		
		$table = "client_policy_documents";
		$this->load->model('database', '', TRUE);
		$this->database->insert_entry_documents($table, $items);
				
		$active["step1_active"] = "";
		$active["step1_content_active"] = "tab-pane";
			
		$active["step2_active"] = "";
		$active["step2_content_active"] = "tab-pane";
		
		$active["step3_active"] = "active";
		$active["step3_content_active"] = "tab-pane active";
				
		$active["step4_active"] = "";
		$active["step4_content_active"] = "tab-pane";
		
		$this->client_registration($active);
	 }
	}
	
	public function login()
	{
		$_SESSION['current_client_name'] = NULL;
		$_SESSION['current_client'] = NULL;
		$table = "personnel";
		$where = "personnel_username = '".addslashes($_POST['personnel_username'])."'";
		$items = "*";
		$order = "personnel_username";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		foreach ($result as $row)
		{
			$password = $row->personnel_password;
			$personnel_type = $row->personnel_type;
		}
		
		if($password == md5($_POST['personnel_password'])){
			
			if($personnel_type == "Administrator"){
				$this->administration();
			}
			
			else if($personnel_type == "Clerk"){
				$_SESSION['current_client'] = NULL;
				
				$active["step1_active"] = "active";
				$active["step1_content_active"] = "tab-pane active";
				
				$active["step2_active"] = "";
				$active["step2_content_active"] = "tab-pane";
				
				$active["step3_active"] = "";
				$active["step3_content_active"] = "tab-pane";
				
				$active["step4_active"] = "";
				$active["step4_content_active"] = "tab-pane";
				
				$this->client_registration($active);
			}
			
			else if($personnel_type == "Salesperson"){
				
				$this->index();
			}
		}
		
		else{
			$this->index();
		}
	}
	
	public function insuarance_companies()
	{
		$table = "insuarance_company";
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries($table); 
		
		return $result;
	}
	
	public function register_company()
	{
		$table = "insuarance_company";
		$this->load->model('database', '', TRUE);
		$this->database->insert_entry($table); 
		$this->administration();
	}
	
	public function add_policy_breakdown()
	{
		$table = "client_policy_type";
		$this->load->model('database', '', TRUE);
		$this->database->insert_entry($table); 
		$this->administration();
	}
	
	public function edit_insurance_policy()
	{
		$table = "insuarance_policy";
		$key = "insuarance_policy_id";
		$this->load->model('database', '', TRUE);
		$this->database->update_insurance_entry($table, $key); 
		$this->administration();
	}
	
	public function edit_policy_breakdown()
	{
		$table = "client_policy_type";
		$key = "client_policy_type_id";
		$this->load->model('database', '', TRUE);
		$this->database->update_client_policy_type_entry($table, $key); 
		$this->administration();
	}
	
	public function edit_company()
	{
		$table = "insuarance_company";
		$key = "insuarance_company_id";
		$this->load->model('database', '', TRUE);
		$this->database->update_insuarance_company_entry($table, $key); 
		$this->administration();
	}
	
	public function search_client()
	{
		if((!empty($_POST['client_number'])) && ($_SESSION['current_client'] == NULL)){
			
			$table = "client";
			$where = "client_number = '".addslashes($_POST['client_number'])."'";
			$items = "*";
			$order = "client_firstname";
		
			$this->load->model('database', '', TRUE);
			$result = $this->database->select_entries_where($table, $where, $items, $order);
		}
		
		else{
			$table = "client";
			$this->load->model('database', '', TRUE);
			$result = $this->database->select_entries($table); 
		}
		return $result;
	}
	
	public function add_policy_breakdown_description()
	{
		$table = "client_policy_options";
		$this->load->model('database', '', TRUE);
		$this->database->insert_entry($table); 
		$this->administration();
	}
	
	public function get_client_policy_options()
	{
		$table = "client_policy_options";
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries($table);
		return $result;
	}
	
	public function add_breakdown_options()
	{
		$table = "policy_options";
		$this->load->model('database', '', TRUE);
		$this->database->insert_entry($table); 
		$this->administration();
	}
	
	public function register_employment_level()
	{
		$table = "employment_level";
		$this->load->model('database', '', TRUE);
		$this->database->insert_entry($table); 
		$this->administration();
	}
	
	public function get_employment_level()
	{
		$table = "employment_level";
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries($table);
		return $result;
	}
	
	public function search_commission(){
		
		if((!empty($_POST['year'])) && (!empty($_POST['month']))){
			$year = $this->input->post('year', TRUE);
			$month = $this->input->post('month', TRUE);
		}
		
		else{
			$year = date("y");
			$month = date("m");
		}
		
		if($month == "January"){
			$month = "01";
		}
		else if($month == "February"){
			$month = "02";
		}
		else if($month == "March"){
			$month = "03";
		}
		else if($month == "April"){
			$month = "04";
		}
		else if($month == "May"){
			$month = "05";
		}
		else if($month == "June"){
			$month = "06";
		}
		else if($month == "July"){
			$month = "07";
		}
		else if($month == "August"){
			$month = "08";
		}
		else if($month == "September"){
			$month = "09";
		}
		else if($month == "October"){
			$month = "10";
		}
		else if($month == "November"){
			$month = "11";
		}
		else if($month == "December"){
			$month = "12";
		}
		$date = $year."-".$month;
		
		$table = "personnel, client_policy_type, client_policy, client_options, client_policy_options";
		
		$where ="client_policy.salesperson_id = personnel.personnel_id AND client_policy.client_policy_id = client_options.client_policy_id AND client_options.client_policy_options_id = client_policy_options.client_policy_options_id AND client_policy_options.client_policy_type_id = client_policy_type.client_policy_type_id AND client_policy.client_policy_date_added LIKE '%$date%'";
		
		$items = "client_policy_type.client_policy_type_cost, personnel.personnel_onames, personnel.personnel_fname, client_policy_type.client_policy_type_id, client_policy.client_policy_id";
		
		$order = "personnel.personnel_fname, personnel.personnel_onames";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order); 
		
		return $result;
	}
	
	public function search_personnel_commission(){
		
		$table = "personnel, employment_level, salesperson_level";
		
		$where ="salesperson_level.personnel_id = personnel.personnel_id AND salesperson_level.employment_level_id = employment_level.employment_level_id";
		
		$items = "personnel.personnel_onames, personnel.personnel_fname, employment_level.employment_level_commission, salesperson_level.salesperson_level_date";
		
		$order = "personnel.personnel_fname, personnel.personnel_onames, salesperson_level.salesperson_level_date";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
}