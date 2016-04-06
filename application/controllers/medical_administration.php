<?php session_start();
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Medical_administration extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->database();
		$this->load->helper('url');
		
		$this->load->library('grocery_CRUD');	
	}
	
	public function index()
	{
		//echo "HELLO WORLD";
	}
	
	function _example_output($output = null)
	{
		$this->load->view('medical_admin.php',$output);	
	}
	
	public function diseases()
    {
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		
        $crud->set_subject('Disease');
        $crud->set_table('diseases');
		$crud->required_fields('diseases_code', 'diseases_name');
        $crud->display_as('consultation_type_id','Consultation Type');
        $crud->display_as('visit_type','Visit Type');
        $crud->display_as('charge','Amount');
		$crud->unset_delete();
        
        $output = $crud->render();
 
        $this->_example_output($output);
    }
	
	public function family_disease()
    {
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		
        $crud->set_subject('Disease/Condition');
        $crud->set_table('family_disease');
		$crud->required_fields('family_disease_name');
        $crud->display_as('family_disease_name','Disease/Condition');
        
        $output = $crud->render();
 
        $this->_example_output($output);
    }
	
	public function vaccines()
    {
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		
        $crud->set_subject('Vaccine');
        $crud->set_table('vaccine');
		$crud->required_fields('vaccine');
        
        $output = $crud->render();
 
        $this->_example_output($output);
    }
	
	public function symptoms()
    {
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		
        $crud->set_subject('Symptom');
        $crud->set_table('symptoms');
		$crud->required_fields('symptoms_name');
        $crud->display_as('symptoms_name','Symptom');
        
        $output = $crud->render();
 
        $this->_example_output($output);
    }
	
	public function objective_findings()
    {
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		
        $crud->set_subject('Finding');
        $crud->set_table('objective_findings');
		$crud->columns('objective_findings_class_id', 'objective_findings_name');
		$crud->set_relation('objective_findings_class_id', 'objective_findings_class', 'objective_findings_class_name');
		$crud->required_fields('objective_findings_name', 'objective_findings_class_id');
        $crud->display_as('objective_findings_name','Finding');
        $crud->display_as('objective_findings_class_id','Class');
        
        $output = $crud->render();
 
        $this->_example_output($output);
    }
	
	public function vitals()
    {
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		
        $crud->set_subject('Vital');
        $crud->set_table('vitals');
		$crud->columns('vitals_group_id', 'vitals_name');
		$crud->set_relation('vitals_group_id', 'vitals_group', 'vitals_group_name');
		$crud->required_fields('vitals_group_id', 'vitals_name');
        $crud->display_as('vitals_name','Vital');
        $crud->display_as('vitals_group_id','Group');
		$crud->add_action('Limits', base_url('img/new/icon-48-info.png'), 'medical_administration/vitals_limit');
        
        $output = $crud->render();
 
        $this->_example_output($output);
    }
	
	public function vitals_limit($primary_ley)
    {
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		
		$crud->where('vitals_range.vitals_id', $primary_ley);
        $crud->set_subject('Limit');
        $crud->set_table('vitals_range');
		$crud->set_relation('vitals_id', 'vitals', 'vitals_name');
		$crud->required_fields('vitals_range_name', 'vitals_range_range');
		$crud->unset_fields('vitals_class_id');
		$crud->unset_columns('vitals_class_id');
        $crud->display_as('vitals_range_name','Limit Name');
        $crud->display_as('vitals_id','Vital');
        $crud->display_as('vitals_range_range','Limit');
		$crud->field_type('vitals_id', 'hidden', $primary_ley);
        
        $output = $crud->render();
 
        $this->_example_output($output);
    }
}