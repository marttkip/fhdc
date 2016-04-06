<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reception extends CI_Controller {
	
	function index()
	{
		redirect('welcome/patient_registration');
	}
}
?>