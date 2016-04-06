<?php session_start();   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/auth/controllers/auth.php";

class Invoices extends auth
{	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('reception/reception_model');
		$this->load->model('invoices_model');
		$this->load->model('database');
		$this->load->model('accounts/accounts_model');
	}
	
	public function custom_invoices($order = 'custom_invoice_created', $order_method = 'DESC')
	{
		$where = 'custom_invoice.custom_invoice_id > 0';
		$table = 'custom_invoice';
		$search = $this->session->userdata('custom_invoice_search');
		$search_tables = $this->session->userdata('custom_invoice_search_tables');
		
		if(!empty($search))
		{
			$where .= $search;
		}
		
		if(!empty($search_tables))
		{
			$table .= $search_tables;
		}
		$segment = 6;
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url().'administration/invoices/custom_invoices/'.$order.'/'.$order_method;
		$config['total_rows'] = $this->reception_model->count_items($table, $where);
		$config['uri_segment'] = $segment;
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
		
		$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
        $data["links"] = $this->pagination->create_links();
		$query = $this->invoices_model->get_all_invoices($table, $where, $config["per_page"], $page, $order, $order_method);
		
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		$v_data['query'] = $query;
		$v_data['personnel_query'] = $this->personnel_model->get_all_personnel();
		$v_data['title'] = 'Custom invoices';
		$v_data['order_method'] = $order_method;
		$v_data['page'] = $page;
		$data['content'] = $this->load->view('invoices/all_invoices', $v_data, true);
		
		$data['sidebar'] = 'admin_sidebar';
		
		$this->load->view('auth/template_sidebar', $data);
	}
    
	/*
	*
	*	Add a new invoice
	*
	*/
	public function add_invoice() 
	{
		//form validation rules
		$this->form_validation->set_rules('custom_invoice_debtor', 'Debtor', 'required|xss_clean');
		$this->form_validation->set_rules('custom_invoice_debtor_contacts', 'Debtor contacts', 'xss_clean');
		$this->form_validation->set_rules('payable_by', 'Quantity', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			$custom_invoice_id = $this->invoices_model->add_invoice();
			//update order
			if($custom_invoice_id > 0)
			{
				redirect('administration/invoices/add_invoice_items/'.$custom_invoice_id);
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not add invoice. Please try again');
			}
		}
		
		else
		{
			$this->session->set_userdata('error_message', validation_errors().' Please try again');
		}
		
		redirect('administration/invoices/custom_invoices');
	}
	
	public function add_invoice_items($custom_invoice_id) 
	{
		//get invoice details
		$v_data['custom_invoice_query'] = $this->invoices_model->get_invoice_items($custom_invoice_id);
		$v_data['custom_invoice_id'] = $custom_invoice_id;
		
		//form validation rules
		$this->form_validation->set_rules('custom_invoice_item_description', 'Description', 'required|xss_clean');
		$this->form_validation->set_rules('custom_invoice_item_cost', 'Cost', 'required|xss_clean');
		$this->form_validation->set_rules('custom_invoice_item_quantity', 'Quantity', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			$custom_invoice_item_id = $this->invoices_model->add_custom_invoice_items($custom_invoice_id);
			//update order
			if($custom_invoice_item_id > 0)
			{
				$this->session->set_userdata('success_message', 'Items added successfully');
				redirect('administration/invoices/add_invoice_items/'.$custom_invoice_id);
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not add invoice items. Please try again');
			}
		}
		
		else
		{
			$validation_errors = validation_errors();
			
			if(!empty($validation_errors))
			{
				$this->session->set_userdata('error_message', validation_errors().' Please try again');
			}
		}
		
		//get custom invoice items
		$where = 'custom_invoice_id = '.$custom_invoice_id;
		$table = 'custom_invoice_item';
		$query = $this->invoices_model->get_custom_invoice_items($custom_invoice_id);
		$v_data['query'] = $query;
		$v_data['personnel_query'] = $this->personnel_model->get_all_personnel();
		$v_data['title'] = 'Add custom invoice items';
		$data['content'] = $this->load->view('invoices/add_invoice', $v_data, true);
		
		$data['sidebar'] = 'admin_sidebar';
		
		$this->load->view('auth/template_sidebar', $data);
	}
	
	public function print_invoice($custom_invoice_id)
	{
		$data['custom_invoice_query'] = $this->invoices_model->get_invoice_items($custom_invoice_id);
		$data['personnel_query'] = $this->personnel_model->get_all_personnel();
		$query = $this->invoices_model->get_custom_invoice_items($custom_invoice_id);
		$data['query'] = $query;
		$data['custom_invoice_id'] = $custom_invoice_id;
		$this->load->view('invoices/print_invoice', $data);
	}
	
	/*
	*	Delete an existing invoice item
	*	@param int $custom_invoice_item_id
	*
	*/
	public function delete_invoice_item($custom_invoice_item_id, $custom_invoice_id)
	{
		if($this->invoices_model->delete_invoice_item($custom_invoice_item_id))
		{
			$this->session->set_userdata('success_message', 'Item deleted successfully');
		}
		else
		{
			$this->session->set_userdata('error_message', 'Item not deleted. Please try again');
		}
		
		redirect('/administration/invoices/add_invoice_items/'.$custom_invoice_id);
	}
}
?>