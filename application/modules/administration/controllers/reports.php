<?php session_start();   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/auth/controllers/auth.php";

class Reports extends auth
{	

	
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('reception/reception_model');
		$this->load->model('reports_model');
		$this->load->model('database');
		$this->load->model('accounts/accounts_model');
	}
	
	public function cash_report($module = 'admin')
	{
		$_SESSION['all_transactions_search'] = NULL;
		$_SESSION['all_transactions_tables'] = NULL;
		
		$this->session->unset_userdata('search_title');
		
		$search = ' AND payments.visit_id = visit.visit_id AND payments.payment_type = 1';
		$table = ', payments';
		$_SESSION['all_transactions_search'] = $search;
		$_SESSION['all_transactions_tables'] = $table;
		
		$this->session->set_userdata('debtors', 'false');
		$this->session->set_userdata('page_title', 'Cash Report');
		
		redirect('administration/reports/all_transactions/'.$module);
	}
	
	public function print_insurance_debtors()
	{
		 $insurance_search = $this->session->userdata('insurance_search');
		$where = 'visit.patient_id = patients.patient_id AND patients.insurance_company_id = insurance_company.insurance_company_id';
		 if(!empty($insurance_search))
		 {
		 	$where .= $insurance_search;
		 }
		
		$table = 'visit, patients, insurance_company';
		$v_data['query'] = $this->reports_model->insurance_debtors_transactions_report($table,$where);
		
		echo $this->load->view('administration/reports/insurance_debtors_report', $v_data, true);
	}
	public function insurance_debtors_report($module = 'admin')
	{
		$_SESSION['all_transactions_search'] = NULL;
		$_SESSION['all_transactions_tables'] = NULL;
		
		$this->session->unset_userdata('search_title');
		
		$search = ' AND payments.visit_id = visit.visit_id AND payments.payment_type = 1';
		$table = ', payments';
		$_SESSION['all_transactions_search'] = $search;
		$_SESSION['all_transactions_tables'] = $table;
		
		$this->session->set_userdata('debtors', 'false');
		$this->session->set_userdata('page_title', 'Cash Report');
		
		redirect('administration/reports/insurance_debtors_transactions/'.$module);
	}
	
	public function all_reports($module = 'admin')
	{
		$_SESSION['all_transactions_search'] = NULL;
		$_SESSION['all_transactions_tables'] = NULL;
		
		$this->session->unset_userdata('search_title');
		
		$this->session->set_userdata('debtors', 'false2');
		$this->session->set_userdata('page_title', 'All Transactions');
		
		redirect('administration/reports/all_transactions/'.$module);
	}
	
	public function time_reports()
	{
		$this->session->unset_userdata('time_reports_search');
		$this->session->unset_userdata('time_reports_tables');
		
		$this->session->set_userdata('page_title', 'Time Reports');
		
		$this->all_time_reports();
	}
	
	public function debtors_report($insurance_company_id = 0)
	{
		$_SESSION['all_transactions_search'] = NULL;
		$_SESSION['all_transactions_tables'] = NULL;
		
		$this->session->unset_userdata('search_title');
		
		$this->session->set_userdata('page_title', 'Debtors report');
		
		redirect('administration/reports/debtors_report_data/'.$insurance_company_id);
	}
	
	public function all_transactions($module = 'admin')
	{
		$where = 'visit.patient_id = patients.patient_id ';
		$table = 'visit, patients';
		$visit_search = $_SESSION['all_transactions_search'];
		$table_search = $_SESSION['all_transactions_tables'];
		
		if(!empty($visit_search))
		{
			$where .= $visit_search;
		
			if(!empty($table_search))
			{
				$table .= $table_search;
			}
		}
		
		else
		{
			$where .= ' AND visit.visit_date = \''.date('Y-m-d').'\'';
			$this->session->set_userdata('search_title', ' Reports for '.date('jS M Y',strtotime(date('Y-m-d'))));
		}
		
		if($module == NULL)
		{
			$segment = 4;
		}
		else
		{
			$segment = 5;	
		}
		
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'/administration/reports/all_transactions/'.$module;
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
		
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
        $v_data["links"] = $this->pagination->create_links();
		$query = $this->reports_model->insurance_debtors_transactions();
		$type=$this->administration_model->search_patient_statement();
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$v_data['search'] = $visit_search;
		$v_data['total_patients'] = $config['total_rows'];
		$v_data['total_services_revenue'] = $this->reports_model->get_total_services_revenue($where, $table);
		$v_data['total_payments'] = $this->reports_model->get_total_cash_collection($where, $table);
		//var_dump($v_data['total_payments']);die();
		//total students debt
		$where2 = $where.' AND visit.visit_type = 1';
		$total_students_debt = $this->reports_model->get_total_services_revenue($where2, $table);
		//students debit notes
		$where2 = $where.' AND payments.payment_type = 2 AND visit.visit_type = 1';
		$student_debit_notes = $this->reports_model->get_total_cash_collection($where2, $table);
		//students credit notes
		$where2 = $where.' AND payments.payment_type = 3 AND visit.visit_type = 1';
		$student_credit_notes = $this->reports_model->get_total_cash_collection($where2, $table);
		$v_data['total_students_debt'] = ($total_students_debt + $student_debit_notes) - $student_credit_notes;
		
		//total staff debt
		$where2 = $where.' AND visit.visit_type = 2';
		$total_staff_debt = $this->reports_model->get_total_services_revenue($where2, $table);
		//students debit notes
		$where2 = $where.' AND payments.payment_type = 2 AND visit.visit_type = 2';
		$staff_debit_notes = $this->reports_model->get_total_cash_collection($where2, $table);
		//students credit notes
		$where2 = $where.' AND payments.payment_type = 3 AND visit.visit_type = 2';
		$staff_credit_notes = $this->reports_model->get_total_cash_collection($where2, $table);
		$v_data['total_staff_debt'] = ($total_staff_debt + $staff_debit_notes) - $staff_credit_notes;
		
		//total other debt
		$where2 = $where.' AND visit.visit_type = 3';
		$total_other_debt = $this->reports_model->get_total_services_revenue($where2, $table);
		//students debit notes
		$where2 = $where.' AND payments.payment_type = 2 AND visit.visit_type = 3';
		$other_debit_notes = $this->reports_model->get_total_cash_collection($where2, $table);
		//students credit notes
		$where2 = $where.' AND payments.payment_type = 3 AND visit.visit_type = 3';
		$other_credit_notes = $this->reports_model->get_total_cash_collection($where2, $table);
		$v_data['total_other_debt'] = ($total_other_debt + $other_debit_notes) - $other_credit_notes;
		
		//total insurance debt
		$where2 = $where.' AND visit.visit_type = 4';
		$total_insurance_debt = $this->reports_model->get_total_services_revenue($where2, $table);
		//students debit notes
		$where2 = $where.' AND payments.payment_type = 2 AND visit.visit_type = 4';
		$insurance_debit_notes = $this->reports_model->get_total_cash_collection($where2, $table);
		//students credit notes
		$where2 = $where.' AND payments.payment_type = 3 AND visit.visit_type = 4';
		$insurance_credit_notes = $this->reports_model->get_total_cash_collection($where2, $table);
		$v_data['total_insurance_debt'] = ($total_insurance_debt + $insurance_debit_notes) - $insurance_credit_notes;
		
		//all normal payments
		$where2 = $where.' AND payments.payment_type = 1';
		$v_data['normal_payments'] = $this->reports_model->get_normal_payments($where2, $table);
		$v_data['payment_methods'] = $this->reports_model->get_payment_methods($where2, $table);
		
		//normal payments
		$where2 = $where.' AND payments.payment_type = 1';
		$v_data['total_cash_collection'] = $this->reports_model->get_total_cash_collection($where2, $table);
		
		//debit notes
		$where2 = $where.' AND payments.payment_type = 2';
		$v_data['debit_notes'] = $this->reports_model->get_total_cash_collection($where2, $table);
		
		//credit notes
		$where2 = $where.' AND payments.payment_type = 3';
		$v_data['credit_notes'] = $this->reports_model->get_total_cash_collection($where2, $table);
		
		//count student visits
		$where2 = $where.' AND visit.visit_type = 1';
		$v_data['students'] = $this->reception_model->count_items($table, $where2);
		
		//count staff visits
		$where2 = $where.' AND visit.visit_type = 2';
		$v_data['staff'] = $this->reception_model->count_items($table, $where2);
		
		//count other visits
		$where2 = $where.' AND visit.visit_type = 3';
		$v_data['other'] = $this->reception_model->count_items($table, $where2);
		
		//count insurance visits
		$where2 = $where.' AND visit.visit_type = 4';
		$v_data['insurance'] = $this->reception_model->count_items($table, $where2);
		
		$data['title'] = $this->session->userdata('page_title');
		$v_data['title'] = $this->session->userdata('page_title');
		$v_data['debtors'] = $this->session->userdata('debtors');
		
		$v_data['services_query'] = $this->reports_model->get_all_active_services();
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['doctors'] = $this->reception_model->get_doctor();
		$type=$this->administration_model->search_patient_statement();
		$v_data['module'] = $module;
		
		$data['content'] = $this->load->view('reports/all_transactions', $v_data, true);
		
		if($module == "accounts")
		{
			$data['sidebar'] = 'accounts_sidebar';

		}
		else if($module == 'admin')
		{
			$data['sidebar'] = 'admin_sidebar';
		}
		else
		{
			$data['sidebar'] = 'accounts_sidebar';
		}
		
		$this->load->view('auth/template_sidebar', $data);
	}
	
	public function insurance_debtors_transactions($module = 'admin')
	{
	
		$segment = 5;
		 $insurance_search = $this->session->userdata('insurance_search');
		$where = 'visit.patient_id = patients.patient_id AND patients.insurance_company_id = insurance_company.insurance_company_id';
		 if(!empty($insurance_search))
		 {
		 	$where .= $insurance_search;
		 }
		
		$table = 'visit, patients, insurance_company';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'/administration/reports/insurance_debtors_transactions/'.$module;
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
		
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
        $v_data["links"] = $this->pagination->create_links();
		$query = $this->reports_model->insurance_debtors_transactions($table, $where, $config["per_page"], $page);
	
		$data['title'] = 'Patients Statements';
		$v_data['title'] = ' Patients Statements';
	
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$v_data['delete'] = 1;
		$v_data['module'] = $module;
		$v_data['insurance_names'] = $this->reports_model->get_insurance_name();
		$v_data['debtor_period']= $this->reports_model->get_debtor_period();
		$data['content'] = $this->load->view('reports/insurance_debtors', $v_data, true);
		if($module == NULL)
		{
			$data['sidebar'] = 'admin_sidebar';	
		}
		else if($module == 2)
		{
			$data['sidebar'] = 'reception_sidebar';
		}
		else if($module == 3)
		{
			$data['sidebar'] = 'accounts_sidebar';
		}
		else
		{
			$data['sidebar'] = 'reception_sidebar';
		}
		
		
		$this->load->view('auth/template_sidebar', $data);
	}
	public function insurance_search($module)
	{
		
		$insurance_company_id = $this->input->post('insurance_company_id');
		$debtors_period_id=$this->input->post('debtors_period_id');
		
		if(!empty($insurance_company_id))
		{
			$insurance_company_id = ' AND insurance_company.insurance_company_id LIKE \'%'.$insurance_company_id.'%\' ';
		}
		else
		{
			$insurance_name = '';
		}
		
		if(!empty($debtors_period_id))
		{
			$today =date('Y-m-d');
			
			if($debtors_period_id == 1)
			{
				// thirty day period
				$end_date = date('Y-m-d',strtotime('-1 month'));
			}
			else if($debtors_period_id == 2)
			{
				$end_date = date('Y-m-d', strtotime('-2 months'));
			}
			else if($debtors_period_id == 3)
			{
				$end_date = date('Y-m-d',strtotime('-3 months'));
			}
			else
			{
				$end_date = date('Y-m-d',strtotime('-3 months'));
			}
			
			if($debtors_period_id == 1 OR $debtors_period_id == 2 OR $debtors_period_id == 3)
			{
				$debtors_period_id = ' AND visit.visit_date BETWEEN \''.$end_date.'\' AND \''.$today.'\'';
			}
			else
			{
				$debtors_period_id = ' AND visit.visit_date > "'.$end_date.'" ';
			}
			
			
		}
		else
		{
			$debtors_period_id = '';
		}

		$search = $insurance_company_id.$debtors_period_id;
		$this->session->set_userdata('insurance_search', $search);
		
		$this->insurance_debtors_transactions($module);
	}
	public function close_insurance_debtors_search()
	{
		$this->session->unset_userdata('insurance_search');
		redirect('administration/reports/insurance_debtors_transactions/admin');
	}
	public function debtors_report_data($insurance_company_id, $order = 'debtor_invoice_created', $order_method = 'DESC')
	{
		//get bill to but from insurance company

		$v_data['insurance_company_query'] = $this->accounts_model->get_billing_methods();
		
		//select first debtor from query
		// echo $insurance_company_id;
		if($insurance_company_id == 0)
		{
			if($v_data['insurance_company_query']->num_rows() > 0)
			{
				$res = $v_data['insurance_company_query']->result();
				$insurance_company_id = $res[0]->insurance_company_id;
				$insurance_company_name = $res[0]->insurance_company_name;
			}
		}
		
		else
		{
			if($v_data['insurance_company_query']->num_rows() > 0)
			{
				$res = $v_data['insurance_company_query']->result();
				
				foreach($res as $r)
				{
					$insurance_company_id2 = $r->insurance_company_id;
					
					if($insurance_company_id == $insurance_company_id2)
					{
						$insurance_company_name = $r->insurance_company_name;
						break;
					}
				}
			}
		}
		
		if($insurance_company_id > 0)
		{
			$where = 'debtor_invoice.insurance_company_id = '.$insurance_company_id;
			$table = 'debtor_invoice';
			
			$visit_search = $_SESSION['all_transactions_search'];
			$table_search = $_SESSION['all_transactions_tables'];
			
			if(!empty($visit_search))
			{
				$where .= $visit_search;
			
				if(!empty($table_search))
				{
					$table .= $table_search;
				}
			}
			
			$segment = 7;
			
			//pagination
			$this->load->library('pagination');
			$config['base_url'] = site_url().'/administration/reports/debtors_report/'.$insurance_company_id.'/'.$order.'/'.$order_method;
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
			
			$config['cur_tag_open'] = '<li class="active"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$this->pagination->initialize($config);
			
			$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
			$v_data["links"] = $this->pagination->create_links();
			$query = $this->reports_model->get_all_debtors_invoices($table, $where, $config["per_page"], $page, $order, $order_method);
			
			$where .= ' AND debtor_invoice.debtor_invoice_id = debtor_invoice_item.debtor_invoice_id AND visit.visit_id = debtor_invoice_item.visit_id ';
			$table .= ', visit, debtor_invoice_item';
			$v_data['where'] = $where;
			$v_data['table'] = $table;
			
			if($order_method == 'DESC')
			{
				$order_method = 'ASC';
			}
			else
			{
				$order_method = 'DESC';
			}
			
			$v_data['order'] = $order;
			$v_data['order_method'] = $order_method;
			$v_data['insurance_company_name'] = $insurance_company_name;
			$v_data['insurance_company_id'] = $insurance_company_id;
			$v_data['query'] = $query;
			$v_data['page'] = $page;
			$v_data['search'] = $visit_search;
			$v_data['total_patients'] = $this->reports_model->get_total_visits($where, $table);
			
			$v_data['total_services_revenue'] = $this->reports_model->get_total_services_revenue($where, $table);
			$v_data['total_payments'] = $this->reports_model->get_total_cash_collection($where, $table);
			
			//total students debt
			$where2 = $where.' AND visit.visit_type = 1';
			$total_students_debt = $this->reports_model->get_total_services_revenue($where2, $table);
			//students debit notes
			$where2 = $where.' AND payments.payment_type = 2 AND visit.visit_type = 1';
			$student_debit_notes = $this->reports_model->get_total_cash_collection($where2, $table);
			//students credit notes
			$where2 = $where.' AND payments.payment_type = 3 AND visit.visit_type = 1';
			$student_credit_notes = $this->reports_model->get_total_cash_collection($where2, $table);
			$v_data['total_students_debt'] = ($total_students_debt + $student_debit_notes) - $student_credit_notes;
			
			//total staff debt
			$where2 = $where.' AND visit.visit_type = 2';
			$total_staff_debt = $this->reports_model->get_total_services_revenue($where2, $table);
			//students debit notes
			$where2 = $where.' AND payments.payment_type = 2 AND visit.visit_type = 2';
			$staff_debit_notes = $this->reports_model->get_total_cash_collection($where2, $table);
			//students credit notes
			$where2 = $where.' AND payments.payment_type = 3 AND visit.visit_type = 2';
			$staff_credit_notes = $this->reports_model->get_total_cash_collection($where2, $table);
			$v_data['total_staff_debt'] = ($total_staff_debt + $staff_debit_notes) - $staff_credit_notes;
			
			//total other debt
			$where2 = $where.' AND visit.visit_type = 3';
			$total_other_debt = $this->reports_model->get_total_services_revenue($where2, $table);
			//students debit notes
			$where2 = $where.' AND payments.payment_type = 2 AND visit.visit_type = 3';
			$other_debit_notes = $this->reports_model->get_total_cash_collection($where2, $table);
			//students credit notes
			$where2 = $where.' AND payments.payment_type = 3 AND visit.visit_type = 3';
			$other_credit_notes = $this->reports_model->get_total_cash_collection($where2, $table);
			$v_data['total_other_debt'] = ($total_other_debt + $other_debit_notes) - $other_credit_notes;
			
			//total insurance debt
			$where2 = $where.' AND visit.visit_type = 4';
			$total_insurance_debt = $this->reports_model->get_total_services_revenue($where2, $table);
			//students debit notes
			$where2 = $where.' AND payments.payment_type = 2 AND visit.visit_type = 4';
			$insurance_debit_notes = $this->reports_model->get_total_cash_collection($where2, $table);
			//students credit notes
			$where2 = $where.' AND payments.payment_type = 3 AND visit.visit_type = 4';
			$insurance_credit_notes = $this->reports_model->get_total_cash_collection($where2, $table);
			$v_data['total_insurance_debt'] = ($total_insurance_debt + $insurance_debit_notes) - $insurance_credit_notes;
			
			//all normal payments
			$where2 = $where.' AND payments.payment_type = 1';
			$v_data['normal_payments'] = $this->reports_model->get_normal_payments($where2, $table);
			$v_data['payment_methods'] = $this->reports_model->get_payment_methods($where2, $table);
			
			//normal payments
			$where2 = $where.' AND payments.payment_type = 1';
			$v_data['total_cash_collection'] = $this->reports_model->get_total_cash_collection($where2, $table);
			
			//debit notes
			$where2 = $where.' AND payments.payment_type = 2';
			$v_data['debit_notes'] = $this->reports_model->get_total_cash_collection($where2, $table);
			
			//credit notes
			$where2 = $where.' AND payments.payment_type = 3';
			$v_data['credit_notes'] = $this->reports_model->get_total_cash_collection($where2, $table);
			
			//count student visits
			$where2 = $where.' AND visit.visit_type = 1';
			$v_data['students'] = $this->reception_model->count_items($table, $where2);
			
			//count staff visits
			$where2 = $where.' AND visit.visit_type = 2';
			$v_data['staff'] = $this->reception_model->count_items($table, $where2);
			
			//count other visits
			$where2 = $where.' AND visit.visit_type = 3';
			$v_data['other'] = $this->reception_model->count_items($table, $where2);
			
			//count insurance visits
			$where2 = $where.' AND visit.visit_type = 4';
			$v_data['insurance'] = $this->reception_model->count_items($table, $where2);
			
			$data['title'] = $this->session->userdata('page_title');
			$v_data['title'] = $this->session->userdata('page_title');
			$v_data['debtors'] = $this->session->userdata('debtors');
			
			$v_data['services_query'] = $this->reports_model->get_all_active_services();
			$v_data['type'] = $this->reception_model->get_types();
			$v_data['doctors'] = $this->reception_model->get_doctor();
			//$v_data['module'] = $module;
			
			$data['content'] = $this->load->view('reports/debtors_report', $v_data, true);
			$type=$this->administration_model->search_patient_statement();
		}
		
		else
		{
			$data['title'] = $this->session->userdata('page_title');
			$data['content'] = 'Please add debtors first';
		}

		
			$data['sidebar'] = 'admin_sidebar';
		
		
		
		
		$this->load->view('auth/template_sidebar', $data);
	}

	public function select_debtor()
	{
		$insurance_company_id = $this->input->post('insurance_company_id');
		
		redirect('administration/reports/debtors_report/'.$insurance_company_id);
	}
	
	public function search_transactions($module = NULL)
	{
		$_SESSION['all_transactions_search'] = NULL;
		$_SESSION['all_transactions_tables'] = NULL;
		
		$this->session->unset_userdata('search_title');
		
		$visit_type_id = $this->input->post('visit_type_id');
		$personnel_id = $this->input->post('personnel_id');
		$visit_date_from = $this->input->post('visit_date_from');
		$visit_date_to = $this->input->post('visit_date_to');
		
		$search_title = 'Showing reports for: ';
		
		if(!empty($visit_type_id))
		{
			$visit_type_id = ' AND visit.visit_type = '.$visit_type_id.' ';
			
			$this->db->where('visit_type_id', $visit_type_id);
			$query = $this->db->get('visit_type');
			
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$search_title .= $row->visit_type_name.' ';
			}
		}
		
		
		
		if(!empty($personnel_id))
		{
			$personnel_id = ' AND visit.personnel_id = '.$personnel_id.' ';
			
			$this->db->where('personnel_id', $personnel_id);
			$query = $this->db->get('personnel');
			
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$search_title .= $row->personnel_fname.' '.$row->personnel_onames.' ';
			}
		}
		
		if(!empty($visit_date_from) && !empty($visit_date_to))
		{
			$visit_date = ' AND visit.visit_date BETWEEN \''.$visit_date_from.'\' AND \''.$visit_date_to.'\'';
			$search_title .= 'Visit date from '.date('jS M Y', strtotime($visit_date_from)).' to '.date('jS M Y', strtotime($visit_date_to)).' ';
		}
		
		else if(!empty($visit_date_from))
		{
			$visit_date = ' AND visit.visit_date = \''.$visit_date_from.'\'';
			$search_title .= 'Visit date of '.date('jS M Y', strtotime($visit_date_from)).' ';
		}
		
		else if(!empty($visit_date_to))
		{
			$visit_date = ' AND visit.visit_date = \''.$visit_date_to.'\'';
			$search_title .= 'Visit date of '.date('jS M Y', strtotime($visit_date_to)).' ';
		}
		
		else
		{
			$visit_date = '';
		}
		
		$search = $visit_type_id.$visit_date.$personnel_id;
		$visit_search = $this->session->userdata('all_transactions_search');
		
		if(!empty($visit_search))
		{
			$search .= $visit_search;
		}
		$_SESSION['all_transactions_search'] = $search;
		
		$this->session->set_userdata('search_title', $search_title);
		
		redirect('administration/reports/all_transactions/'.$module);
	}
	
	public function export_transactions()
	{
		$this->reports_model->export_transactions();
	}
	
	public function export_insurance_debtors()
	{
		$this->reports_model->export_insurance_debtors();
	}
	
	public function export_time_report()
	{
		$this->reports_model->export_time_report();
	}
	
	public function close_search()
	{
		$_SESSION['all_transactions_search'] = NULL;
		$_SESSION['all_transactions_search'] = NULL;
		$this->session->unset_userdata('search_title');
		
		$debtors = $this->session->userdata('debtors');
		
		if($debtors == 'true')
		{
			$this->debtors_report();
		}
		
		else if($debtors == 'false')
		{
			$this->cash_report();
		}
		
		else
		{
			$this->all_reports();
		}
	}
	
	public function department_reports()
	{
		//get all service types
		$v_data['services_result'] = $this->reports_model->get_all_service_types();
		$v_data['type'] = $this->reception_model->get_types();
		
		$data['title'] = 'Department Reports';
		$v_data['title'] = 'Department Reports';
		
		$data['content'] = $this->load->view('reports/department_reports', $v_data, true);
		
		
		$data['sidebar'] = 'admin_sidebar';
		
		
		$this->load->view('auth/template_sidebar', $data);
	}
	
	public function search_departments()
	{
		$visit_date_from = $this->input->post('visit_date_from');
		$visit_date_to = $this->input->post('visit_date_to');
		
		if(!empty($visit_date_from) && !empty($visit_date_to))
		{
			$visit_date = ' AND visit.visit_date BETWEEN \''.$visit_date_from.'\' AND \''.$visit_date_to.'\'';
		}
		
		else if(!empty($visit_date_from))
		{
			$visit_date = ' AND visit.visit_date = \''.$visit_date_from.'\'';
		}
		
		else if(!empty($visit_date_to))
		{
			$visit_date = ' AND visit.visit_date = \''.$visit_date_to.'\'';
		}
		
		else
		{
			$visit_date = '';
		}
		
		$search = $visit_date;
		
		$this->session->set_userdata('all_departments_search', $search);
		
		$this->department_reports();
	}
	
	public function all_time_reports()
	{
		$where = 'visit.patient_id = patients.patient_id AND visit.close_card = 1';
		$table = 'visit, patients';
		$visit_search = $this->session->userdata('time_reports_search');
		$table_search = $this->session->userdata('time_reports_tables');
		
		if(!empty($visit_search))
		{
			$where .= $visit_search;
		
			if(!empty($table_search))
			{
				$table .= $table_search;
			}
		}
		$segment = 4;
		
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'/administration/reports/all_time_reports';
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
		
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
        $v_data["links"] = $this->pagination->create_links();
		$query = $this->reports_model->get_all_visits($table, $where, $config["per_page"], $page, 'ASC');
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$v_data['search'] = $visit_search;
		$v_data['total_patients'] = $config['total_rows'];
		//$v_data['visit_departments'] = $this->reports_model->get_visit_departments($where, $table);
		
		//count student visits
		$where2 = $where.' AND visit.visit_type = 1';
		$v_data['students'] = $this->reception_model->count_items($table, $where2);
		
		//count staff visits
		$where2 = $where.' AND visit.visit_type = 2';
		$v_data['staff'] = $this->reception_model->count_items($table, $where2);
		
		//count other visits
		$where2 = $where.' AND visit.visit_type = 3';
		$v_data['other'] = $this->reception_model->count_items($table, $where2);
		
		//count insurance visits
		$where2 = $where.' AND visit.visit_type = 4';
		$v_data['insurance'] = $this->reception_model->count_items($table, $where2);
		
		$data['title'] = $this->session->userdata('page_title');
		$v_data['title'] = $this->session->userdata('page_title');
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['doctors'] = $this->reception_model->get_doctor();
		
		$data['content'] = $this->load->view('reports/time_reports', $v_data, true);
		
		
		$data['sidebar'] = 'admin_sidebar';
		
		
		$this->load->view('auth/template_sidebar', $data);
	}
	
	public function search_time()
	{
		$visit_type_id = $this->input->post('visit_type_id');
		$strath_no = $this->input->post('strath_no');
		$personnel_id = $this->input->post('personnel_id');
		$visit_date_from = $this->input->post('visit_date_from');
		$visit_date_to = $this->input->post('visit_date_to');
		
		if(!empty($visit_type_id))
		{
			$visit_type_id = ' AND visit.visit_type = '.$visit_type_id.' ';
		}
		
		if(!empty($strath_no))
		{
			$strath_no = ' AND patients.strath_no LIKE \'%'.$strath_no.'%\' ';
		}
		
		if(!empty($personnel_id))
		{
			$personnel_id = ' AND visit.personnel_id = '.$personnel_id.' ';
		}
		
		if(!empty($visit_date_from) && !empty($visit_date_to))
		{
			$visit_date = ' AND visit.visit_date BETWEEN \''.$visit_date_from.'\' AND \''.$visit_date_to.'\'';
		}
		
		else if(!empty($visit_date_from))
		{
			$visit_date = ' AND visit.visit_date = \''.$visit_date_from.'\'';
		}
		
		else if(!empty($visit_date_to))
		{
			$visit_date = ' AND visit.visit_date = \''.$visit_date_to.'\'';
		}
		
		else
		{
			$visit_date = '';
		}
		
		$search = $visit_type_id.$strath_no.$visit_date.$personnel_id;
		$visit_search = $this->session->userdata('time_reports_search');
		
		if(!empty($visit_search))
		{
			//$search .= $visit_search;
		}
		$this->session->set_userdata('time_reports_search', $search);
		
		$this->all_time_reports();
	}
	
	public function close_time_reports_search()
	{
		$this->session->unset_userdata('time_reports_search');
		$this->session->unset_userdata('time_reports_tables');
		
		$this->all_time_reports();
	}
	
	public function create_new_batch($insurance_company_id)
	{
		$this->form_validation->set_rules('invoice_date_from', 'Invoice date from', 'required|xss_clean');
		$this->form_validation->set_rules('invoice_date_to', 'Invoice date to', 'required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if($this->reports_model->add_debtor_invoice($insurance_company_id))
			{
				
			}
			
			else
			{
				
			}
		}
		
		else
		{
			$this->session->set_userdata("error_message", validation_errors());
		}
		//echo 'done '.$insurance_company_id;
		redirect('administration/reports/debtors_report/'.$insurance_company_id);
	}
	
	public function view_invoices($debtor_invoice_id)
	{
		$_SESSION['all_transactions_search'] = NULL;
		$_SESSION['all_transactions_tables'] = NULL;
		
		$this->session->unset_userdata('search_title');
		
		$search = ' AND debtor_invoice_item.visit_id = visit.visit_id AND debtor_invoice_item.debtor_invoice_id = '.$debtor_invoice_id;
		$table = ', debtor_invoice_item';
		
		//create title
		$this->db->where('insurance_company.insurance_company_id = debtor_invoice.insurance_company_id AND debtor_invoice.debtor_invoice_id = '.$debtor_invoice_id);
		$this->db->select('insurance_company_name, date_from, date_to');
		$query = $this->db->get('debtor_invoice, insurance_company');
		
		$row = $query->row();
		
		$insurance_company_name = $row->insurance_company_name;
		$date_from = date('jS M Y',strtotime($row->date_from));
		$date_to = date('jS M Y',strtotime($row->date_to));
		
		$search_title = 'Invoices for '.$insurance_company_name.' between '.$date_from.' and '.$date_to;
		
		$_SESSION['all_transactions_search'] = $search;
		$_SESSION['all_transactions_tables'] = $table;
		
		$this->session->set_userdata('search_title', $search_title);
		
		redirect('administration/reports/all_transactions');
	}
	
	public function export_debt_transactions($debtor_invoice_id)
	{
		$this->reports_model->export_debt_transactions($debtor_invoice_id);
	}
	
	public function invoice($debtor_invoice_id)
	{
		$where = 'debtor_invoice.debtor_invoice_id = '.$debtor_invoice_id.' AND debtor_invoice.insurance_company_id = insurance_company.insurance_company_id';
		$table = 'debtor_invoice, insurance_company';
		
		$data = array(
			'debtor_invoice_id'=>$debtor_invoice_id,
			'query' => $this->reports_model->get_debtor_invoice($where, $table),
			'personnel_query' => $this->personnel_model->get_all_personnel()
		);
			
		$where .= ' AND debtor_invoice.debtor_invoice_id = debtor_invoice_item.debtor_invoice_id AND visit.visit_id = debtor_invoice_item.visit_id ';
		$table .= ', visit, debtor_invoice_item';
		
		$data['where'] = $where;
		$data['table'] = $table;
		
		$this->load->view('reports/invoice', $data);
	}
	
	public function search_debtors($insurance_company_id)
	{
		$_SESSION['all_transactions_search'] = NULL;
		$_SESSION['all_transactions_tables'] = NULL;
		
		$this->session->unset_userdata('search_title');
		
		$date_from = $this->input->post('batch_date_from');
		$date_to = $this->input->post('batch_date_to');
		$batch_no = $this->input->post('batch_no');
		
		if(!empty($batch_no) && !empty($date_from) && !empty($date_to))
		{
			$search = ' AND debtor_invoice.batch_no LIKE \'%'.$batch_no.'%\' AND debtor_invoice.debtor_invoice_created >= \''.$date_from.'\' AND debtor_invoice.debtor_invoice_created <= \''.$date_to.'\'';
			$search_title = 'Showing invoices for batch no. '.$batch_no.' created between '.date('jS M Y',strtotime($date_from)).' and '.date('jS M Y',strtotime($date_to));
		}
		
		else if(!empty($batch_no) && !empty($date_from) && empty($date_to))
		{
			$search = ' AND debtor_invoice.batch_no LIKE \'%'.$batch_no.'%\' AND debtor_invoice.debtor_invoice_created LIKE \''.$date_from.'%\'';
			$search_title = 'Showing invoices for batch no. '.$batch_no.' created on '.date('jS M Y',strtotime($date_from));
		}
		
		else if(!empty($batch_no) && empty($date_from) && !empty($date_to))
		{
			$search = ' AND debtor_invoice.batch_no LIKE \'%'.$batch_no.'%\' AND debtor_invoice.debtor_invoice_created LIKE \''.$date_to.'%\'';
			$search_title = 'Showing invoices for batch no. '.$batch_no.' created on '.date('jS M Y',strtotime($date_to));
		}
		
		else if(empty($batch_no) && !empty($date_from) && !empty($date_to))
		{
			$search = ' AND debtor_invoice.debtor_invoice_created >= \''.$date_from.'\' AND debtor_invoice.debtor_invoice_created <= \''.$date_to.'\'';
			$search_title = 'Showing invoices created between '.date('jS M Y',strtotime($date_from)).' and '.date('jS M Y',strtotime($date_to));
		}
		
		else if(empty($batch_no) && !empty($date_from) && empty($date_to))
		{
			$search = ' AND debtor_invoice.debtor_invoice_created LIKE \''.$date_from.'%\'';
			$search_title = 'Showing invoices created created on '.date('jS M Y',strtotime($date_from));
		}
		
		else if(empty($batch_no) && empty($date_from) && !empty($date_to))
		{
			$search = ' AND debtor_invoice.debtor_invoice_created LIKE \''.$date_to.'%\'';
			$search_title = 'Showing invoices created created on '.date('jS M Y',strtotime($date_to));
		}
		else if(!empty($batch_no) && empty($date_from) && empty($date_to))
		{
			$search = ' AND debtor_invoice.batch_no LIKE \'%'.$batch_no.'%\'';
			$search_title = 'Showing invoices for batch no. '.$batch_no;
		}
		
		else
		{
			$search = '';
			$search_title = '';
		}
		
		
		$_SESSION['all_transactions_search'] = $search;
		
		$this->session->set_userdata('search_title', $search_title);
		
		redirect('administration/reports/debtors_report_data/'.$insurance_company_id);
	}
	
	public function close_debtors_search($insurance_company_id)
	{
		$_SESSION['all_transactions_search'] = NULL;
		$_SESSION['all_transactions_tables'] = NULL;
		
		$this->session->unset_userdata('search_title');
		redirect('administration/reports/debtors_report_data/'.$insurance_company_id);
	}
	public function doctor_reports($date_from = NULL, $date_to = NULL)
	{
		$_SESSION['all_transactions_search'] = NULL;
		$_SESSION['all_transactions_tables'] = NULL;
		
		//get all service types
		$v_data['doctor_results'] = $this->reports_model->get_all_doctors();
		
		if(!empty($date_from) && !empty($date_to))
		{
			$title = 'Doctors report from '.date('jS M Y',strtotime($date_from)).' to '.date('jS M Y',strtotime($date_to));
		}
		
		else if(empty($date_from) && !empty($date_to))
		{
			$title = 'Doctors report for '.date('jS M Y',strtotime($date_to));
		}
		
		else if(!empty($date_from) && empty($date_to))
		{
			$title = 'Doctors report for '.date('jS M Y',strtotime($date_from));
		}
		
		else
		{
			$date_from = date('Y-m-d');
			$title = 'Doctors report for '.date('jS M Y',strtotime($date_from));
		}
		
		$v_data['date_from'] = $date_from;
		$v_data['date_to'] = $date_to;
		
		$v_data['title'] = $title;
		$data['title'] = 'Doctor Reports';
		
		$data['content'] = $this->load->view('reports/doctor_reports', $v_data, true);
		
		
		$data['sidebar'] = 'admin_sidebar';
		
		
		$this->load->view('auth/template_sidebar', $data);
	}
	public function search_doctors()
	{
		$visit_date_from = $this->input->post('visit_date_from');
		$visit_date_to = $this->input->post('visit_date_to');
		
		redirect('/administration/reports/doctor_reports/'.$visit_date_from.'/'.$visit_date_to);
	}
	
	public function doctor_reports_export($date_from = NULL, $date_to = NULL)
	{
		$this->reports_model->doctor_reports_export($date_from, $date_to);
	}
	
	public function doctor_patients_export($personnel_id, $date_from = NULL, $date_to = NULL)
	{
		$_SESSION['all_transactions_search'] = NULL;
		$_SESSION['all_transactions_tables'] = NULL;
		
		$this->reports_model->doctor_patients_export($personnel_id, $date_from, $date_to);
	}
}
?>