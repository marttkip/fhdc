<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once "./application/modules/auth/controllers/auth.php";


class Insurance_company extends auth {
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('insurance_company_model');
	}
    
	/*
	*	Default action is to show all the insurance_company
	*/
	public function index() 
	{
		$where = 'insurance_company_id > 0';
		$table = 'insurance_company';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url().'administration/all-insurance-company';
		$config['total_rows'] = $this->insurance_company_model->count_items($table, $where);
		$config['uri_segment'] = 4;
		$config['per_page'] = 20;
		$config['num_links'] = 5;
		
		
		$config['full_tag_open'] = '<ul class="pagination pull-right">';
		$config['full_tag_close'] = '</ul>';
		
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		
		$config['next_tag_open'] = '<li>';
		$config['next_link'] = 'Next';
		$config['next_tag_close'] = '</span>';
		
		$config['prev_tag_open'] = '<li>';
		$config['prev_link'] = 'Prev';
		$config['prev_tag_close'] = '</li>';
		
		$config['cur_tag_open'] = '<li class="active">';
		$config['cur_tag_close'] = '</li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data["links"] = $this->pagination->create_links();
		$query = $this->insurance_company_model->get_all_insurance_company($table, $where, $config["per_page"], $page);
		
		if ($query->num_rows() > 0)
		{
			$v_data['insurance_company'] = $query;
			$v_data['page'] = $page;
			$data['content'] = $this->load->view('insurance_company/all_insurance_company', $v_data, true);
		}
		
		else
		{
			$data['content'] = '<a href="'.site_url().'/administration/add-insurance-company" class="btn btn-success pull-right">Add insurance company</a> There are no insurance company added yet';
		}
		$data['title'] = 'All insurance company';
		
		$data['sidebar'] = 'admin_sidebar';
		
		
		$this->load->view('auth/template_sidebar', $data);
	}
    
	/*
	*
	*	Add a new insurance_company page
	*
	*/
	public function add_insurance_company() 
	{
		//form validation rules
		$this->form_validation->set_rules('insurance_company_name', 'insurance company Name', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//check if insurance_company has valid login credentials
			if($this->insurance_company_model->add_insurance_company())
			{
				redirect('administration/all-insurance-company');
			}
			
			else
			{
				$data['error'] = 'Unable to add insurance_company. Please try again';
			}
		}
		
		//open the add new insurance_company page
		$data['title'] = 'Add new insurance_company';
		$data['content'] = $this->load->view('insurance_company/add_insurance_company', '', TRUE);
		$data['sidebar'] = 'admin_sidebar';
		
		
		$this->load->view('auth/template_sidebar', $data);
	}
    
	/*
	*
	*	Edit an existing insurance_company page
	*	@param int $insurance_company_id
	*
	*/
	public function edit_insurance_company($insurance_company_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('insurance_company_name', 'insurance company Name', 'xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//check if insurance_company has valid login credentials
			if($this->insurance_company_model->edit_insurance_company($insurance_company_id))
			{
				$this->session->set_userdata('success_message', 'insurance company edited successfully');
				redirect('administration/all-insurance-company');
				
			}
			
			else
			{
				$data['error'] = 'Unable to add insurance company. Please try again';
			}
		}
		
		//open the add new insurance_company page
		$data['title'] = 'Edit insurance company';
		
		//select the insurance_company from the database
		$query = $this->insurance_company_model->get_insurance_company($insurance_company_id);
		if ($query->num_rows() > 0)
		{
			$v_data['insurance_company'] = $query->result();
			$data['content'] = $this->load->view('insurance_company/edit_insurance_company', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'insurance company does not exist';
		}
		
		$data['sidebar'] = 'admin_sidebar';
		
		
		$this->load->view('auth/template_sidebar', $data);
	}
    
	/*
	*
	*	Delete an existing insurance_company page
	*	@param int $insurance_company_id
	*
	*/
	public function delete_insurance_company($insurance_company_id) 
	{
		if($this->insurance_company_model->delete_insurance_company($insurance_company_id))
		{
			$this->session->set_userdata('success_message', 'insurance company has been deleted');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'insurance company could not be deleted');
		}
		
		redirect('administration/all-insurance-company');
	}
    
	/*
	*
	*	Activate an existing insurance_company page
	*	@param int $insurance_company_id
	*
	*/
	public function activate_insurance_company($insurance_company_id) 
	{
		if($this->insurance_company_model->activate_insurance_company($insurance_company_id))
		{
			$this->session->set_userdata('success_message', 'insurance company has been activated');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'insurance company could not be activated');
		}
		
		redirect('administration/all-insurance-company');
	}
    
	/*
	*
	*	Deactivate an existing insurance_company page
	*	@param int $insurance_company_id
	*
	*/
	public function deactivate_insurance_company($insurance_company_id) 
	{
		if($this->insurance_company_model->deactivate_insurance_company($insurance_company_id))
		{
			$this->session->set_userdata('success_message', 'insurance company has been disabled');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'insurance company could not be disabled');
		}
		
		redirect('administration/all-insurance-company');
	}
	
	
}
?>