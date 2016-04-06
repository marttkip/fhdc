<?php session_start();   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Authentication extends CI_Controller {
	
	function __construct()
	{
		parent:: __construct();
		
		$this->load->database();
		
		$this->load->model('login_model');
		
		//user has logged in
		if($this->login_model->check_login())
		{
		}
		
		//user has not logged in
		else
		{
			redirect('login');
		}
	}
}
?>