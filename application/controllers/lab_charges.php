<?php session_start();
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lab_charges extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->database();
		$this->load->helper('url');
		
		$this->load->library('grocery_CRUD');	
	}
	
	public function index()
	{
		echo "HELLO WORLD";
	}
	
	public function control_panel(){
		$this->load->view("control_panel");
	}
	
	function tests($output = null)
	{
		$this->load->view('tests.php',$output);	
	}
	
	public function test_list()
    {
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
 
        $crud->set_subject('Test');
        $crud->set_table('lab_test');
		$crud->columns('lab_test_class_id', 'lab_test_name', 'lab_test_units', 'lab_test_price', 'lab_test_malelowerlimit', 'lab_test_malelupperlimit', 'lab_test_femalelowerlimit', 'lab_test_femaleupperlimit');
		$crud->fields('lab_test_class_id', 'lab_test_name', 'lab_test_units', 'lab_test_price', 'lab_test_malelowerlimit', 'lab_test_malelupperlimit', 'lab_test_femalelowerlimit', 'lab_test_femaleupperlimit');
    	$crud->set_relation('lab_test_class_id', 'lab_test_class', '{lab_test_class_name}');
		$crud->add_action('Formats', base_url('img/new/icon-48-media.png'), 'lab_charges/test_format');
		$crud->required_fields('lab_test_class_id', 'lab_test_name', 'lab_test_price');
        $crud->display_as('lab_test_malelupperlimit','Male Upper Limit');
        $crud->display_as('lab_test_femalelowerlimit','Female Lower Limit');
        $crud->display_as('lab_test_class_id','Class');
        $crud->display_as('lab_test_name','Test');
        $crud->display_as('lab_test_units','Units');
        $crud->display_as('lab_test_price','Price');
        $crud->display_as('lab_test_malelowerlimit','Male Lower Limit');
        $crud->display_as('lab_test_femaleupperlimit','Female Upper Limit');
        
        $output = $crud->render();
 
        $this->tests($output);
    }
	
	public function test_format($primary_key)
    {
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		
		$crud->where(array("lab_test_format.lab_test_id" => $primary_key));
 
        $crud->set_subject('Format');
        $crud->set_table('lab_test_format');
    	$crud->set_relation('lab_test_id', 'lab_test', '{lab_test_name}');
		$crud->required_fields('lab_test_formatname');
        $crud->display_as('lab_test_format_malelowerlimit','Male Lower Limit');
        $crud->display_as('lab_test_format_maleupperlimit','Male Upper Limit');
        $crud->display_as('lab_test_format_femalelowerlimit','Female Lower Limit');
        $crud->display_as('lab_test_format_femaleupperlimit','Female Upper Limit');
        $crud->display_as('lab_test_id','Lab Test');
        $crud->display_as('lab_test_formatname','Format');
        $crud->display_as('lab_test_format_units','Units');
		$crud->field_type('lab_test_id', 'hidden', $primary_key);
        
        $output = $crud->render();
 
        $this->tests($output);
    }
	
	public function classes()
    {
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
 
        $crud->set_subject('Class');
        $crud->set_table('lab_test_class');
		$crud->required_fields('lab_test_class_name');
        $crud->display_as('lab_test_class_name','Class');
        
        $output = $crud->render();
 
        $this->tests($output);
    }
}
