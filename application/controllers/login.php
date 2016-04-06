<?php session_start();   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	
	function __construct()
	{
		parent:: __construct();
		
		$this->load->database();
		$this->load->helper('url');
		
		$this->load->model('login_model');
		$this->load->model('reports_model');
		$this->load->model('database');
	}
	public function control_panel($personnel_id){
		
		$data['personnel_id'] = $personnel_id;
		$this->load->view("control_panel", $data);
	}
	
	public function index()
	{
		$this->login_user();
	}
    
	/*
	*
	*	Login a user
	*
	*/
	public function login_user() 
	{
		//form validation rules
		$this->form_validation->set_rules('username', 'Username', 'required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run() ==  FALSE)
		{
			$this->load->view('login');
		}
		
		else
		{
			//check if user has valid login credentials
			if($this->login_model->validate_user())
			{
				$this->control_panel($_SESSION['personnel_id']);
			}
			
			else
			{
				$data['error'] = 'Your username or password provided is incorrect. Please try again';
				$this->load->view('login', $data);
			}
		}
	}
	
	public function logout_admin()
	{
		$this->session->sess_destroy();
		redirect('login');
	}
	
	public function logout_user()
	{
		$this->session->sess_destroy();
		$this->session->set_userdata('front_success_message', 'Your have been signed out of your account');
		redirect('checkout');
	}
}
?>