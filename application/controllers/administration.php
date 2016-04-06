<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require('authentication.php');

class Administration extends authentication {

	function __construct()
	{
		parent::__construct();
		
		$this->load->database();
		$this->load->helper('url');
		
		$this->load->model('reports_model');
		$this->load->library('grocery_CRUD');	
	}
	
	public function index()
	{
		//echo "HELLO WORLD";
		$this->load->view('dashboard');
	}
	
	function _example_output($output = null)
	{
		$this->load->view('admin.php',$output);	
	}
	
	public function reset_password($primary_key)
	{
		$table = "personnel";
		$key = "personnel_id";
		$key_value = $primary_key;
		$items = array(
        	"personnel_password" => md5(123456)
    	);
		$this->load->model('database', '', TRUE);
		$this->database->update_entry2($table, $key, $key_value, $items); 
		?>
			<script type="text/javascript">
				window.alert("Reset Successfull");
			</script>
		<?php
		$this->personnel();
	}	
	public function item_format(){
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		 $crud->set_subject('Check box Format');
        $crud->set_table('item_format');
		$crud->display_as('item_format_name','Name');
		$crud->add_action('Set Checkbox Name', base_url('img/new/icon-48-install.png'), 'administration/format');
		$output = $crud->render();
 		$this->_example_output($output);
		
		}
	public function format(){
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		 $crud->set_subject('Check box');
        $crud->set_table('format');
		$crud->display_as('format_name','Name');
		$crud->set_relation('item_format_id', 'item_format', 'item_format_name');
		$crud->display_as('format_value','Value');
		$output = $crud->render();
 		$this->_example_output($output);
		
		}
	public function medical_exam_categories(){
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
	    $crud->set_subject('medical_exam_categories');
        $crud->set_table('medical_exam_categories');
		$crud->display_as('mec_name','medical_exam_category');
		$crud->display_as('format_value','Value');
		$output = $crud->render();
 		$this->_example_output($output);
		
		}
	public function cat_items(){
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
	    $crud->set_subject('Category Items');
        $crud->set_table('cat_items');
		$crud->set_relation('mec_id','medical_exam_categories','mec_name');
		$crud->set_relation('item_format_id', 'item_format', 'item_format_name');
		$crud->display_as('cat_item_name','Name of Category');
		$output = $crud->render();
 		$this->_example_output($output);
		
		}
	
	
	public function personnel()
    {
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		
 		$crud->where('personnel_status',0);
        $crud->set_subject('Personnel');
        $crud->set_table('personnel');
    	$crud->set_relation('title_id', 'title', 'title_name');
    	$crud->set_relation('job_title_id', 'job_title', 'job_title_name');
		$crud->set_relation('authorise', 'permissions', 'permissions_name');
    	$crud->set_relation('gender_id', 'gender', 'gender_name');
		$crud->set_relation('civilstatus_id', 'civil_status', 'civil_status_name');
    	$crud->set_relation('kin_relationship_id', 'kin_relationship', 'kin_relationship_name');
    	$crud->set_relation_n_n('Roles', 'personnel_department', 'departments', 'personnel_id', 'department_id', 'departments_name', 'count');
		$crud->add_action('Reset Password', base_url('img/new/icon-48-install.png'), 'administration/reset_password');
		$crud->columns('personnel_onames', 'personnel_fname', 'personnel_email', 'job_title_id', 'personnel_phone', 'title_id', 'gender_id', 'personnel_username', 'Roles');
		$crud->unset_fields('personnel_status');
		$crud->required_fields('personnel_ref', 'personnel_onames','personnel_fname');
        $crud->display_as('personnel_onames','Other Names');
        $crud->display_as('personnel_fname','First Name');
		$crud->display_as('personnel_surname','Personell Natioinal ID');
		$crud->display_as('personnel_staff_id','Personell Staff Number');
        $crud->display_as('personnel_dob','Date of Birth');
        $crud->display_as('personnel_email','Email');
        $crud->display_as('personnel_phone','Phone');
        $crud->display_as('personnel_address','Address');
        $crud->display_as('job_title_id','Job Title');
		$crud->display_as('authorise','Authorise Cash Payments');
        $crud->display_as('personnel_locality','Town');
        $crud->display_as('civilstatus_id','Marital Status');
        $crud->display_as('personnel_title','Title');
        $crud->display_as('personnel_sex','Gender');
        $crud->display_as('personnel_username','Username');
        $crud->display_as('personnel_kin_fname','Kin First Name');
        $crud->display_as('personnel_kin_onames','Kin Other Names');
        $crud->display_as('personnel_kin_address','kin Address');
        $crud->display_as('personnel_kin_contact','Kin Phone');
        $crud->display_as('kin_relationship_id','Kin Relationship');
        $crud->display_as('title_id','Title');
        $crud->display_as('gender_id','Gender');
		$crud->field_type('personnel_password', 'hidden', 'e10adc3949ba59abbe56e057f20f883e');
        
        $output = $crud->render();
 
        $this->_example_output($output);
    }
		public function supportstaff()
    {
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		
 		//$crud->where('personnel_status',0);
        $crud->set_subject('Support Staff');
        $crud->set_table('support_staff');
    	//$crud->set_relation('title_id', 'title', 'title_name');
    	//$crud->set_relation('job_title_id', 'job_title', 'job_title_name');
		//$crud->set_relation('authorise', 'permissions', 'permissions_name');
    	//$crud->set_relation('gender_id', 'gender', 'gender_name');
		//$crud->set_relation('civilstatus_id', 'civil_status', 'civil_status_name');
    	//$crud->set_relation('kin_relationship_id', 'kin_relationship', 'kin_relationship_name');
    	//$crud->set_relation_n_n('Roles', 'personnel_department', 'departments', 'personnel_id', 'department_id', 'departments_name', 'count');
		//$crud->add_action('Reset Password', base_url('img/new/icon-48-install.png'), 'administration/reset_password');
		//$crud->columns('personnel_onames', 'personnel_fname', 'personnel_email', 'job_title_id', 'personnel_phone', 'title_id', 'gender_id', 'personnel_username', 'Roles');
		//$crud->unset_fields('personnel_status');
		$crud->required_fields('Surname', 'Other_names','gender');
        $crud->display_as('Surname','Surname');
        $crud->display_as('Other_names','Other Names');
        $crud->display_as('gender','Gender');
		$crud->display_as('National_id','Identity/Passport Number');
        $output = $crud->render();
 
        $this->_example_output($output);
    }
	
	public function consultation_charges()
    {
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		
        $crud->set_subject('Charge');
        $crud->set_table('consultation_charge_2');
		$crud->columns('consultation_type_id', 'visit_type', 'charge');
    	$crud->set_relation('visit_type', 'visit_type', 'visit_type_name');
    	$crud->set_relation('consultation_type_id', 'consultation_type', 'consultation_type_name');
		$crud->required_fields('visit_type', 'charge','consultation_type_id');
        $crud->display_as('consultation_type_id','Consultation Type');
        $crud->display_as('visit_type','Visit Type');
        $crud->display_as('charge','Amount');
        
        $output = $crud->render();
 
        $this->_example_output($output);
    }
	
	public function consultation_types()
    {
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		
        $crud->set_subject('Type');
        $crud->set_table('consultation_type');
		$crud->required_fields('consultation_type_name');
        $crud->display_as('consultation_type_name','Consultation Type');
        
        $output = $crud->render();
 
        $this->_example_output($output);
    }
	
	public function procedure_charges()
    {
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		
        $crud->set_subject('Procedure');
        $crud->set_table('procedure');
		$crud->required_fields('procedures', 'staff', 'students', 'outsiders');
        $crud->display_as('consultation_type_name','Consultation Type');
        
        $output = $crud->render();
 
        $this->_example_output($output);
    }
	 public function insurance_company()
	{
		
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		$crud->where(array("status" => 0));
        $crud->set_subject('Insurance Company');
        $crud->set_table('insurance_company');
		$crud->unset_columns('status');
		$crud->unset_fields('status');
		$crud->required_fields('company_name');
		$crud->add_action('Insurance Discounts', base_url('img/new/icon-48-install.png'), 'administration/insurance_discounts');
        
        $output = $crud->render();
 
        $this->_example_output($output);
	}
	 public function insurance_discounts($primary_key)
    {    //echo LL.$primary_key;
        $crud = new grocery_CRUD(); $crud->set_theme('datatables');
        $crud->where('insurance_id',$primary_key);
        $crud->set_subject('Insurance Discounts');
        $crud->set_table('insurance_discounts');
            $crud->columns('service_id', 'insurance_id', 'percentage', 'amount');
        $crud->set_relation('service_id','service','service_name');
        $crud->set_relation('insurance_id','insurance_company','insurance_company_name');
        $crud->field_type('insurance_id', 'hidden',$primary_key);
        //$crud->unset_fields('insurance_id');
        $output = $crud->render();
 
        $this->_example_output($output);
    }
   

	
	public function companies()
	{
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		$crud->where(array("status" => 0));
        $crud->set_subject('Company');
        $crud->set_table('companies');
		$crud->set_relation_n_n('Insurance', 'company_insuarance', 'insurance_company', 'companies_id', 'insurance_company_id', 'insurance_company_name', 'count');
        
        $output = $crud->render();
 
        $this->_example_output($output);
	}
	
	public function services()
    {
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		
        $crud->set_subject('Service');
        $crud->set_table('service');
		$crud->required_fields('service_name');
        $crud->display_as('service_name','Service');
		$crud->add_action('Charges', base_url('img/new/icon-48-contacts.png'), 'administration/add_charges');
        
        $output = $crud->render();
 
        $this->_example_output($output);
    }
	
	public function add_charges($primary_key)
    {
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		
        $crud->set_subject('Charge');
        $crud->set_table('service_charge');
		$crud->where("service_charge.service_id", $primary_key);
		$crud->required_fields("visit_type_id", 'service_charge_amount');
		$crud->unset_fields('service_charge_status');
		$crud->unset_columns('service_charge_status');
		$crud->set_relation("visit_type_id", "visit_type", "visit_type_name");
		$crud->set_relation("service_id", "service", "service_name");
		$crud->set_relation("lab_test_id", "lab_test", "lab_test_name");
		$crud->set_relation("drug_id", "drugs", "drugs_name");
		  $crud->display_as('drug_id','Drug Name');
		    $crud->display_as('lab_test_id','Lab Test Name');
        $crud->display_as('service_charge_name','Charge Name');
        $crud->display_as('service_charge_amount','Amount');
        $crud->display_as('service_charge_status','Status');
        $crud->display_as('visit_type_id','Visit Type');
        $crud->display_as('service_id','Service');
		$crud->callback_column('service_charge_status',array($this,'callback_status'));
		//$crud->callback_before_insert(array($this,'update_status'));
		$crud->field_type('service_id', 'hidden', $primary_key);
        
        $output = $crud->render();
 
        $this->_example_output($output);
    }
	
	public function update_status($post_array)
	{
		$items = array(
        	"service_charge_status" => 1
    	);
		$table = "service_charge";
		$key = "service_charge_name";
		$key_value =$post_array('service_charge_name');
		$this->load->model('database', '',TRUE);
		$this->database->update_entry2($table, $key, $key_value, $items);
		
    	return $post_array;
	}
	
	public function callback_status($value, $row)
	{
  		if($value == 0){
			$value = "Current Status";
		}
		
		else{
			
			$value = "Previous Status";
		}
		
		return $value;
	}
	
	 public function patient_type()
	{
		
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
        $crud->set_subject('Type');
        $crud->set_table('visit_type');
		$crud->required_fields('visit_type_name');
		$crud->display_as('visit_type_name', 'Type');
      	$output = $crud->render();
 		$this->_example_output($output);
	}
	public function add_credit(){
				$crud = new grocery_CRUD(); $crud->set_theme('datatables');
        $crud->set_subject('Patient Type');
        $crud->set_table('account_credit');
		$crud->required_fields('visit_type_id','Amount','efect_date');
	    $crud->columns('visit_type_id','Amount','efect_date','expiry_date(after one year)');
		$crud->display_as('visit_type_id', 'Patient Type');
		$crud->display_as('Amount', 'Amount Allocated');
		$crud->display_as('efect_date', 'Effective From');
		$crud->set_relation('visit_type_id','visit_type', '{visit_type_name}');
        $crud->callback_column('expiry_date(after one year)',array($this,'expire'));
		         
        $output = $crud->render();
		  $this->_example_output($output);
		} 
		
	public function expire($value, $row){
		$effect_date=($row->efect_date);
$date = $effect_date; 
$new_date = date('m/d/Y',strtotime('+364 days',strtotime($date))); 
		return $new_date;
		
		}
}
