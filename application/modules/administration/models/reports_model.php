<?php

class Reports_model extends CI_Model 
{
	public function get_queue_total($date = NULL, $where = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		if($where == NULL)
		{
			$where = 'visit.visit_id = visit_department.visit_id AND visit.close_card = 0 AND visit.visit_date = \''.$date.'\'';
		}
		
		else
		{
			$where .= ' AND visit.visit_id = visit_department.visit_id AND visit.close_card = 0 AND visit.visit_date = \''.$date.'\' ';
		}
		
		$this->db->select('COUNT(visit.visit_id) AS queue_total');
		$this->db->where($where);
		$query = $this->db->get('visit, visit_department');
		
		$result = $query->row();
		
		return $result->queue_total;
	}
	
	public function get_daily_balance($date = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		//select the user by email from the database
		$this->db->select('SUM(visit_charge_units*visit_charge_amount) AS total_amount');
		$this->db->where('visit_charge_timestamp LIKE \''.$date.'%\'');
		$this->db->from('visit_charge');
		$query = $this->db->get();
		
		$result = $query->row();
		
		return $result->total_amount;
	}
	
	public function get_patients_total($date = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		$this->db->select('COUNT(visit_id) AS patients_total');
		$this->db->where('visit_date = \''.$date.'\'');
		$query = $this->db->get('visit');
		
		$result = $query->row();
		
		return $result->patients_total;
	}
	
	public function get_all_payment_methods()
	{
		$this->db->select('*');
		$query = $this->db->get('payment_method');
		
		return $query;
	}
	
	public function get_payment_method_total($payment_method_id, $date = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		$this->db->select('SUM(amount_paid) AS total_paid');
		$this->db->where('payments.visit_id = visit.visit_id AND payment_method_id = '.$payment_method_id.' AND visit_date = \''.$date.'\'');
		$query = $this->db->get('payments, visit');
		
		$result = $query->row();
		
		return $result->total_paid;
	}
	
	public function get_all_visit_types()
	{
		$this->db->select('*');
		$query = $this->db->get('visit_type');
		
		return $query;
	}
	
	public function get_visit_type_total($visit_type_id, $date = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		$where = 'visit_date = \''.$date.'\' AND visit_type = '.$visit_type_id;
		
		$this->db->select('COUNT(visit_id) AS visit_total');
		$this->db->where($where);
		$query = $this->db->get('visit');
		
		$result = $query->row();
		
		return $result->visit_total;
	}
	
	public function get_all_service_types()
	{
		$this->db->select('*');
		$query = $this->db->get('service');
		
		return $query;
	}
	
	public function get_service_total($service_id, $date = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		
		$table = 'visit_charge, service_charge';
		
		$where = 'visit_charge_timestamp LIKE \''.$date.'%\' AND visit_charge.service_charge_id = service_charge.service_charge_id AND service_charge.service_id = '.$service_id;
		
		$visit_search = $this->session->userdata('all_departments_search');
		if(!empty($visit_search))
		{
			$where = 'visit_charge.service_charge_id = service_charge.service_charge_id AND service_charge.service_id = '.$service_id.' AND visit.visit_id = visit_charge.visit_id'. $visit_search;
			$table .= ', visit';
		}
		
		$this->db->select('SUM(visit_charge_units*visit_charge_amount) AS service_total');
		$this->db->where($where);
		$query = $this->db->get($table);
		
		$result = $query->row();
		$total = $result->service_total;;
		
		if($total == NULL)
		{
			$total = 0;
		}
		
		return $total;
	}
	
	public function get_all_appointments($date = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		$where = 'visit.visit_delete = 0 AND patients.patient_delete = 0 AND visit.visit_type = visit_type.visit_type_id AND visit.patient_id = patients.patient_id AND visit.appointment_id = 1 AND visit.close_card = 2 AND visit.visit_date >= \''.$date.'\'';
		
		$this->db->select('visit.*, visit_type.visit_type_name, patients.*');
		$this->db->where($where);
		$query = $this->db->get('visit, visit_type, patients');
		
		return $query;
	}
	
	public function get_doctor_appointments($personnel_id, $date = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		$where = 'visit.visit_delete = 0 AND patients.patient_delete = 0 AND visit.visit_type = visit_type.visit_type_id AND visit.patient_id = patients.patient_id AND visit.appointment_id = 1 AND visit.close_card = 2 AND visit.visit_date >= \''.$date.'\' AND visit.personnel_id = '.$personnel_id;
		
		$this->db->select('visit.*, visit_type.visit_type_name, patients.*');
		$this->db->where($where);
		$query = $this->db->get('visit, visit_type, patients');
		
		return $query;
	}
	
	public function get_all_sessions($date = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		$where = 'personnel.personnel_id = session.personnel_id AND session.session_name_id = session_name.session_name_id AND session_time LIKE \''.$date.'%\'';
		
		$this->db->select('session_name_name, session_time, personnel_fname, personnel_onames');
		$this->db->where($where);
		$this->db->order_by('session_time', 'DESC');
		$query = $this->db->get('session, session_name, personnel');
		
		return $query;
	}
	
	/*
	*	Retrieve visits
	*	@param string $table
	* 	@param string $where
	*	@param int $per_page
	* 	@param int $page
	*
	*/
	public function get_all_visits($table, $where, $per_page, $page, $order = NULL)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('visit.*, (visit.visit_time_out - visit.visit_time) AS waiting_time, patients.visit_type_id, patients.visit_type_id, patients.patient_othernames, patients.patient_surname, patients.dependant_id, patients.strath_no,patients.patient_national_id');
		$this->db->where($where);
		$this->db->order_by('visit.visit_date, visit.visit_time','DESC');
		$this->db->group_by('visit.visit_id');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	/*
	*	Retrieve all active services
	*
	*/
	public function get_all_active_services()
	{
		//retrieve all users
		$this->db->from('service');
		$this->db->where('service_delete = 1');
		$this->db->order_by('service_name','ASC');
		$query = $this->db->get();
		
		return $query;
	}
	/*
	*	Retrieve all active services
	*
	*/
	public function get_all_active_payment_method()
	{
		//retrieve all users
		$this->db->from('payment_method');
		$this->db->where('payment_method_id < 6');
		$this->db->order_by('payment_method_id','ASC');
		$query = $this->db->get();
		
		return $query;
	}
	
	
	/*
	*	Retrieve all visit payments
	*
	*/
	public function get_all_visit_payments($visit_id)
	{
		//retrieve all users
		$this->db->from('payments');
		$this->db->select('SUM(payments.amount_paid) AS total_paid');
		$this->db->where('visit_id', $visit_id);
		// $this->db->group_by('visit_id');
		$query = $this->db->get();
		
		$cash = $query->row();
		
		if($cash->total_paid > 0)
		{
			return $cash->total_paid;
		}
		
		else
		{
			return 0;
		}
	}
	
	/*
	*	Retrieve all service charges
	*
	*/
	public function get_all_visit_charges($visit_id, $service_id)
	{
		//retrieve all users
		$this->db->from('visit_charge, service_charge');
		$this->db->select('SUM(visit_charge.visit_charge_amount * visit_charge.visit_charge_units) AS total_invoiced');
		$this->db->where('visit_charge.visit_id = '.$visit_id.' AND service_charge.service_id = '.$service_id.' AND visit_charge.service_charge_id = service_charge.service_charge_id');
		$query = $this->db->get();
		
		$cash = $query->row();
		
		if($cash->total_invoiced > 0)
		{
			return $cash->total_invoiced;
		}
		
		else
		{
			return 0;
		}
	}
	
	public function get_all_payment_values($visit_id,$payment_method_id)
	{
		# code...
		//retrieve all users
		$this->db->from('payments');
		$this->db->select('SUM(amount_paid) AS total_paid');
		$this->db->where('visit_id = '.$visit_id.' AND payment_method_id = '.$payment_method_id.'');
		$query = $this->db->get();
		
		$cash = $query->row();
		
		if($cash->total_paid > 0)
		{
			return $cash->total_paid;
		}
		
		else
		{
			return 0;
		}
	}
	/*
	*	Retrieve total revenue
	*
	*/
	public function get_total_services_revenue($where, $table)
	{
		//invoiced
		$this->db->from($table.', visit_charge, service_charge');
		$this->db->select('SUM(visit_charge.visit_charge_amount * visit_charge.visit_charge_units) AS total_invoiced');
		$this->db->where($where.' AND visit.visit_id = visit_charge.visit_id AND visit_charge.service_charge_id = service_charge.service_charge_id');
		$query = $this->db->get();
		
		$cash = $query->row();
		$total_invoiced = $cash->total_invoiced;
		
		if($total_invoiced > 0)
		{
			
		}
		
		else
		{
			$total_invoiced = 0;
		}
		
		return $total_invoiced;
	}
	/*
	*	Retrieve total visits
	*
	*/
	public function get_total_visits($where, $table)
	{
		$this->db->from($table);
		$this->db->where($where);
		$total = $this->db->count_all_results();
		
		return $total;
	}
	
	/*
	*	Retrieve total revenue
	*
	*/
	public function get_total_cash_collection($where, $table)
	{
		//payments
		$table_search = $_SESSION['all_transactions_tables'];
		if(!empty($table_search) && ($table_search != ', debtor_invoice_item'))
		{
			$this->db->from($table);
		}
		
		else
		{
			$this->db->from($table.', payments');
		}
		$this->db->select('SUM(payments.amount_paid) AS total_paid');
		$this->db->where($where.' AND visit.visit_id = payments.visit_id');
		$query = $this->db->get();
		
		$cash = $query->row();
		$total_paid = $cash->total_paid;
		if($total_paid > 0)
		{
		}
		
		else
		{
			$total_paid = 0;
		}
		
		return $total_paid;
	}
	
	/*
	*	Retrieve total revenue
	*
	*/
	public function get_normal_payments($where, $table)
	{
		//payments
		$table_search = $_SESSION['all_transactions_tables'];
		if(!empty($table_search) && ($table_search != ', debtor_invoice_item'))
		{
			$this->db->from($table);
		}
		
		else
		{
			$this->db->from($table.', payments');
		}
		$this->db->select('*');
		$this->db->where($where.' AND visit.visit_id = payments.visit_id');
		$query = $this->db->get();
		
		return $query;
	}
	
	public function get_payment_methods()
	{
		$this->db->select('*');
		$query = $this->db->get('payment_method');
		
		return $query;
	}
	
	function export_debt_transactions($debtor_invoice_id)
	{
		$where = ' AND visit.visit_id = debtor_invoice_item.visit_id AND debtor_invoice_item.debtor_invoice_id = '.$debtor_invoice_id;
		$table = ', debtor_invoice_item';
		$_SESSION['all_transactions_search'] = $where;
		$_SESSION['all_transactions_tables'] = $table;
		
		$this->export_transactions();
	}
	
	/*
	*	Export Transactions
	*
	*/
	function export_transactions()
	{
		$this->load->library('excel');
		$report = array();
		
		//get all transactions
		$where = 'visit.patient_id = patients.patient_id ';
		$table = 'visit, patients';
		if(isset($_SESSION['all_transactions_search']))
		{
			$visit_search = $_SESSION['all_transactions_search'];
		}
		
		if(isset($_SESSION['all_transactions_tables']))
		{
			$table_search = $_SESSION['all_transactions_tables'];
		}
		
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
		}
		
		$this->db->where($where);
		$this->db->order_by('visit_date', 'ASC');
		$this->db->select('visit.*, patients.visit_type_id, patients.visit_type_id, patients.patient_othernames, patients.patient_surname, patients.dependant_id, patients.strath_no,patients.patient_national_id,patients.dependant_id');
		$this->db->group_by('visit_id');
		$visits_query = $this->db->get($table);
		
		$title = 'Transactions export '.date('jS M Y H:i a');;
		
		if($visits_query->num_rows() > 0)
		{
			$count = 0;
			/*
				-----------------------------------------------------------------------------------------
				Document Header
				-----------------------------------------------------------------------------------------
			*/
			$row_count = 0;
			$report[$row_count][0] = '#';
			$report[$row_count][1] = 'Visit Date';
			$report[$row_count][2] = 'Patient';
			$report[$row_count][3] = 'Category';
			$report[$row_count][4] = 'Doctor';
			$current_column = 5 ;
			
			
			//get & display all services
			$services_query = $this->get_all_active_services();
			
			foreach($services_query->result() as $service)
			{
				$report[$row_count][$current_column] = $service->service_name;
				$current_column++;
			}
			$report[$row_count][$current_column] = 'Debit Note Total';
			$current_column++;
			$report[$row_count][$current_column] = 'Credit Note Total';
			$current_column++;
			$report[$row_count][$current_column] = 'Invoice Total';
			$current_column++;

			
			
			//get & display all services
			$payment_method_query = $this->get_all_active_payment_method();
			
			foreach($payment_method_query->result() as $paymentmethod)
			{
				$report[$row_count][$current_column] = $paymentmethod->payment_method;
				$current_column++;
			}
			$report[$row_count][$current_column] = 'Payments Total';
			$current_column++;
			$report[$row_count][$current_column] = 'Balance';
			$current_column++;
			//display all patient data in the leftmost columns
			foreach($visits_query->result() as $row)
			{
				$row_count++;
				$total_invoiced = 0;
				$visit_date = date('jS M Y',strtotime($row->visit_date));
				$visit_time = date('H:i a',strtotime($row->visit_time));
				if($row->visit_time_out != '0000-00-00 00:00:00')
				{
					$visit_time_out = date('H:i a',strtotime($row->visit_time_out));
				}
				else
				{
					$visit_time_out = '-';
				}
				$visit_id = $row->visit_id;
				$patient_id = $row->patient_id;
				$personnel_id = $row->personnel_id;
				$dependant_id = $row->dependant_id;
				$strath_no = $row->strath_no;
				$visit_type_id = $row->visit_type_id;
				$visit_type = $row->visit_type;
				$visit_type = $row->visit_type;
				if($row->dependant_id != 0)
				{
					$strath_no = $row->dependant_id;
				}
				else
				{
					$strath_no = $strath_no;
				}

				// this is to check for any credit note or debit notes
				$payments_value = $this->accounts_model->total_payments($visit_id);

				$invoice_total = $this->accounts_model->total_invoice($visit_id);

				$balance = $this->accounts_model->balance($payments_value,$invoice_total);
				// end of the debit and credit notes

				// total of debit and credit notes amounts
				$credit_note_amount = $this->accounts_model->get_sum_credit_notes($visit_id);
				$debit_note_amount = $this->accounts_model->get_sum_debit_notes($visit_id);
				// end of total debit and credit notes amount

				// get all the payment methods used in payments
				//$payment_type = $this->accounts_model->get_visit_payment_method($visit_id);
				// end of all payments details


				$patient = $this->reception_model->patient_names2($patient_id, $visit_id);

				$visit_type = $patient['visit_type'];
				$patient_type = $patient['patient_type'];
				$patient_othernames = $patient['patient_othernames'];
				$patient_surname = $patient['patient_surname'];
				$patient_date_of_birth = $patient['patient_date_of_birth'];
				$gender = $patient['gender'];
				$faculty = $patient['faculty'];
				
				//creators and editors
				$personnel_query = $this->personnel_model->get_all_personnel();
				if($personnel_query->num_rows() > 0)
				{
					$personnel_result = $personnel_query->result();
					
					foreach($personnel_result as $adm)
					{
						$personnel_id2 = $adm->personnel_id;
						
						if($personnel_id == $personnel_id2)
						{
							$doctor = $adm->personnel_onames.' '.$adm->personnel_fname;
							break;
						}
						
						else
						{
							$doctor = '-';
						}
					}
				}
				
				else
				{
					$doctor = '-';
				}
				
				$count++;
				$cash = $this->reports_model->get_all_visit_payments($visit_id);
				
				//display services charged to patient
				$total_invoiced2 = 0;
				foreach($services_query->result() as $service)
				{
					$service_id = $service->service_id;
					$visit_charge = $this->reports_model->get_all_visit_charges($visit_id, $service_id);
					$total_invoiced2 += $visit_charge;
				}
				
				//display all debtors
				$debtors = $this->session->userdata('debtors');
				// if($debtors == 'true' && (($cash - $total_invoiced2) > 0))
				if($debtors == 'true' && ($balance > 0))
				{
					//display the patient data
					$report[$row_count][0] = $count;
					$report[$row_count][1] = $visit_date;
					$report[$row_count][2] = $patient_surname.' '.$patient_othernames;
					$report[$row_count][3] = $visit_type;
					$report[$row_count][4] = $doctor;
					$current_column = 5;

					
					//display services charged to patient
					foreach($services_query->result() as $service)
					{
						$service_id = $service->service_id;
						$visit_charge = $this->reports_model->get_all_visit_charges($visit_id, $service_id);
						$total_invoiced += $visit_charge;
						
						$report[$row_count][$current_column] = $visit_charge;
						$current_column++;
					}
					$report[$row_count][$current_column] = $debit_note_amount;
					$current_column++;
					$report[$row_count][$current_column] = $credit_note_amount;
					$current_column++;
					$report[$row_count][$current_column] = $total_invoiced;
					$current_column++;
					// display amounts collected on every payment method
					foreach($payment_method_query->result() as $paymentmethod)
					{
						$payment_method_id = $paymentmethod->payment_method_id;
						$amount_paid = $this->reports_model->get_all_payment_values($visit_id, $payment_method_id);
						$report[$row_count][$current_column] = $amount_paid;
						$current_column++;
					}
					// //display total for the current visit

					$report[$row_count][$current_column] = $payments_value;
					$current_column++;
					$report[$row_count][$current_column] = $balance;
					$current_column++;
				}
				
				//display cash & all transactions
				else
				{
					//display the patient data
					$report[$row_count][0] = $count;
					$report[$row_count][1] = $visit_date;
					$report[$row_count][2] = $patient_surname.' '.$patient_othernames;
					$report[$row_count][3] = $visit_type;
					$report[$row_count][4] = $doctor;
					$current_column= 5;
					
					

					//display services charged to patient
					foreach($services_query->result() as $service)
					{
						$service_id = $service->service_id;
						$visit_charge = $this->reports_model->get_all_visit_charges($visit_id, $service_id);
						$total_invoiced += $visit_charge;
						
						$report[$row_count][$current_column] = $visit_charge;
						$current_column++;
					}
					$report[$row_count][$current_column] = $debit_note_amount;
					$current_column++;
					$report[$row_count][$current_column] = $credit_note_amount;
					$current_column++;
					$report[$row_count][$current_column] = $invoice_total;
					$current_column++;
					foreach($payment_method_query->result() as $paymentmethod)
					{
						$payment_method_id = $paymentmethod->payment_method_id;
						$amount_paid = $this->reports_model->get_all_payment_values($visit_id, $payment_method_id);
						$report[$row_count][$current_column] = $amount_paid;
						$current_column++;
					}
				
					//display total for the current visit
					
					$report[$row_count][$current_column] = $payments_value;
					$current_column++;
					$report[$row_count][$current_column] = $balance;
					$current_column++;
				}
			}
		}
		
		//create the excel document
		$this->excel->addArray ( $report );
		$this->excel->generateXML ($title);
	}
	
	function export_insurance_debtors()
	{
		$this->load->library('excel');
		$report = array();
		
		 $search_insurance = $this->session->userdata('search_insurance');
		// $where = '(visit_type_id <> 2 OR visit_type_id <> 1) AND patient_delete = '.$delete;
		$where = 'visit.patient_id = patients.patient_id AND patients.insurance_company_id = insurance_company.insurance_company_id';
		 if(!empty($search_insurance))
		 {
		 	$where .= $search_insurance;
		 }
		
		$table = 'visit, patients, insurance_company';
		
		$this->db->where($where);
		$this->db->order_by('visit_date', 'ASC');
		$this->db->select('*');
		$this->db->group_by('visit_id');
		$visits_query = $this->db->get($table);
		
		$title = 'Insuance Debtors Reports '.date('jS M Y H:i a');;
		
		if($visits_query->num_rows() > 0)
		{
			$count = 0;
			/*
				-----------------------------------------------------------------------------------------
				Document Header
				-----------------------------------------------------------------------------------------
			*/
			$row_count = 0;
			$report[$row_count][0] = '#';
			$report[$row_count][1] = 'Visit Date';
			$report[$row_count][2] = 'Patient Number';
			$report[$row_count][3] = 'Patient Name';
			$report[$row_count][4] = 'Insurance Company';
			$report[$row_count][5] = 'Patient Insurance Number';
			$report[$row_count][6] = 'Procedures';
			$report[$row_count][7] = 'Amount';
			$report[$row_count][8] = 'Paid';
			$report[$row_count][9] = 'Balance';
			$current_column = 10 ;
			
			$current_column++;

			
			foreach($visits_query->result() as $row)
			{
				
				$total_invoiced = 0;
				$amount_paid= 0;
				$insurance_company_name=$row->insurance_company_name;
				$patient_id = $row->patient_id;
				$insurance_number=$row->patient_insurance_number;
				$visit_date=$row->visit_date;
				$visit_id = $row->visit_id;
				$patient_id = $row->patient_id;
				$patient_othernames = $row->patient_othernames;
				$patient_surname = $row->patient_surname;
				$patient_number=$row->patient_number;
				
				if($insurance_number > 0)
				{
					echo $insurance_number;
				}
				else
				{	$insurance_number =' - ';
					}
				
				$total_invoice = $this->accounts_model->total_invoice($visit_id);
				$total_payments = $this->accounts_model->total_payments($visit_id);
				$balance= $total_invoice - $total_payments;
				if($balance > 0)
				{
					$count++;
					$item_invoiced_rs = $this->accounts_model->get_patient_visit_charge_items($visit_id);
					$procedures = '';
					 if(count($item_invoiced_rs) > 0)
					 {
							$s=0;
							foreach ($item_invoiced_rs as $key_items):
							
							  $s++;
							  
							  $service_charge_name = $key_items->service_charge_name;
							  $visit_charge_amount = $key_items->visit_charge_amount;
							  $service_name = $key_items->service_name;
							  $units = $key_items->visit_charge_units;
							   if(count($item_invoiced_rs) == $s)
							   {
								   $bound = '';
							   }
							   else{
								   $bound = ',';
							   }
							   $procedures .= $service_charge_name.''.$bound;
							   
							endforeach;
					 }
				
			$row_count++;
			$report[$row_count][0] = $count;
			$report[$row_count][1] = $visit_date;
			$report[$row_count][2] = $patient_number;
			$report[$row_count][3] = $patient_surname.$patient_othernames;
			$report[$row_count][4] = $insurance_company_name;
			$report[$row_count][5] = $insurance_number;
			$report[$row_count][6] = $procedures;
			$report[$row_count][7] = $total_invoice;
			$report[$row_count][8] = $total_payments;
			$report[$row_count][9] = $balance;
			$current_column = 10 ;

				}
			}
		}
		//create the excel document
		$this->excel->addArray ( $report );
		$this->excel->generateXML ($title);
	}
	
	
	/*
	*	Export Time report
	*
	*/
	function export_time_report()
	{
		$this->load->library('excel');
		$report = array();
		
		//get all transactions
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
		
		$this->db->where($where);
		$this->db->order_by('visit_date', 'ASC');
		$this->db->select('visit.*, patients.visit_type_id, patients.visit_type_id, patients.patient_othernames, patients.patient_surname, patients.dependant_id, patients.strath_no,patients.patient_national_id,patients.dependant_id');
		$visits_query = $this->db->get($table);
		
		$title = 'Time report export '.date('jS M Y H:i a');;
		
		if($visits_query->num_rows() > 0)
		{
			$count = 0;
			/*
				-----------------------------------------------------------------------------------------
				Document Header
				-----------------------------------------------------------------------------------------
			*/

			$row_count = 0;
			$report[$row_count][0] = '#';
			$report[$row_count][1] = 'Visit Date';
			$report[$row_count][2] = 'Patient';
			$report[$row_count][3] = 'Category';
			$report[$row_count][4] = 'Start Time';
			$report[$row_count][5] = 'End time';
			$report[$row_count][6] = 'Total Time (Days h:m:s)';
			//get & display all services
			
			//display all patient data in the leftmost columns
			foreach($visits_query->result() as $row)
			{
				$row_count++;
				$total_invoiced = 0;
				$visit_date = date('jS M Y',strtotime($row->visit_date));
				$visit_time = date('H:i a',strtotime($row->visit_time));
				if($row->visit_time_out != '0000-00-00 00:00:00')
				{
					$visit_time_out = date('H:i a',strtotime($row->visit_time_out));
					$seconds = strtotime($row->visit_time_out) - strtotime($row->visit_time);//$row->waiting_time;
					$days    = floor($seconds / 86400);
					$hours   = floor(($seconds - ($days * 86400)) / 3600);
					$minutes = floor(($seconds - ($days * 86400) - ($hours * 3600))/60);
					$seconds = floor(($seconds - ($days * 86400) - ($hours * 3600) - ($minutes*60)));
					
					//$total_time = date('H:i',(strtotime($row->visit_time_out) - strtotime($row->visit_time)));//date('H:i',$row->waiting_time);
					$total_time = $days.' '.$hours.':'.$minutes.':'.$seconds;
				}
				else
				{
					$visit_time_out = '-';
					$total_time = '-';
				}
					
				$visit_id = $row->visit_id;
				$patient_id = $row->patient_id;
				$visit_type_id = $row->visit_type_id;
				$visit_type = $row->visit_type;
				
				$patient = $this->reception_model->patient_names2($patient_id, $visit_id);
				$visit_type = $patient['visit_type'];
				$patient_type = $patient['patient_type'];
				$patient_othernames = $patient['patient_othernames'];
				$patient_surname = $patient['patient_surname'];
				$patient_date_of_birth = $patient['patient_date_of_birth'];
				$gender = $patient['gender'];
				$faculty = $patient['faculty'];
				$count++;
				
				//display the patient data
				$report[$row_count][0] = $count;
				$report[$row_count][1] = $visit_date;
				$report[$row_count][2] = $patient_surname.' '.$patient_othernames;
				$report[$row_count][3] = $visit_type;
				$report[$row_count][4] = $visit_time;
				$report[$row_count][5] = $visit_time_out;
				$report[$row_count][6] = $total_time;
					
				
				
			}
		}
		
		//create the excel document
		$this->excel->addArray ( $report );
		$this->excel->generateXML ($title);
	}
	
	/*
	*	Retrieve total revenue
	*
	*/
	public function get_visit_departments($where, $table)
	{
		//invoiced
		$this->db->from($table.', visit_department');
		$this->db->select('visit_department.*');
		$this->db->where($where.' AND visit.visit_id = visit_department.visit_id');
		$query = $this->db->get();
		
		return $query;
	}
	
	public function get_insurance_company()
	{
		//invoiced
		$this->db->from('insurance_company');
		$this->db->select('*');
		$this->db->order_by('insurance_company_name');
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Retrieve debtors_invoices
	*	@param string $table
	* 	@param string $where
	*	@param int $per_page
	* 	@param int $page
	*
	*/
	public function get_all_debtors_invoices($table, $where, $per_page, $page, $order, $order_method)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by($order, $order_method);
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	public function add_debtor_invoice($insurance_company_id)
	{
		$data = array(
			'debtor_invoice_created'=>date('Y-m-d H:i:s'),
			'debtor_invoice_created_by'=>$this->session->userdata('personnel_id'),
			'batch_no'=>$this->create_batch_number(),
			'insurance_company_id'=>$insurance_company_id,
			'debtor_invoice_modified_by'=>$this->session->userdata('personnel_id'),
			'date_from' => $this->input->post('invoice_date_from'),
			'date_to' => $this->input->post('invoice_date_to')
		);
		
		if($this->db->insert('debtor_invoice', $data))
		{
			$debtor_invoice_id = $this->db->insert_id();
			
			if($debtor_invoice_id > 0)
			{
				//get all invoices within the selected dates
				$this->db->where(
					array(
						'insurance_company_id' => $insurance_company_id,
						'visit_date >= ' => $this->input->post('invoice_date_from'),
						'visit_date <= ' => $this->input->post('invoice_date_to')
					)
				);
				$this->db->select('visit_id');
				$query = $this->db->get('visit');
				
				if($query->num_rows() > 0)
				{
					$invoice_data['debtor_invoice_id'] = $debtor_invoice_id;
					
					foreach($query->result() as $res)
					{
						$visit_id = $res->visit_id;
						
						$invoice_data['visit_id'] = $visit_id;
						
						if($this->db->insert('debtor_invoice_item', $invoice_data))
						{
						}
						
						else
						{
							$this->session->set_userdata('error_message', 'Unable to add details for visit ID '.$visit_id);
						}
					}
					$this->session->set_userdata('success_message', 'Batch added successfully');
					return TRUE;
				}
				
				else
				{
					$this->session->set_userdata('error_message', 'The selected date range does not contain any invoices');
					return FALSE;
				}
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'The selected date range does not contain any invoices');
				return FALSE;
			}
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Create batch number
	*
	*/
	public function create_batch_number()
	{
		//select product code
		$this->db->from('debtor_invoice');
		$this->db->where("batch_no LIKE 'BAT".date('y')."/%'");
		$this->db->select('MAX(batch_no) AS number');
		$query = $this->db->get();
		$preffix = "BAT".date('y').'/';
		
		if($query->num_rows() > 0)
		{
			$result = $query->result();
			$number =  $result[0]->number;
			$real_number = str_replace($preffix, "", $number);
			$real_number++;//go to the next number
			$number = $preffix.sprintf('%06d', $real_number);
		}
		else{//start generating receipt numbers
			$number = $preffix.sprintf('%06d', 1);
		}
		
		return $number;
	}
	
	public function calculate_debt_total($debtor_invoice_id, $where, $table)
	{
		$where .= ' AND debtor_invoice.debtor_invoice_id = '.$debtor_invoice_id;
		
		$total_services_revenue = $this->reports_model->get_total_services_revenue($where, $table);
		
		$where2 = $where.' AND payments.payment_type = 1';
		$total_cash_collection = $this->reports_model->get_total_cash_collection($where2, $table);
		
		return $total_services_revenue - $total_cash_collection;
	}
	
	public function get_debtor_invoice($where, $table)
	{
		$this->db->where($where);
		$query = $this->db->get($table);
		
		return $query;
	}


	public function get_all_doctors()
	{
		$this->db->select('*');
		$this->db->where('job_title_id = 2');
		$query = $this->db->get('personnel');
		
		return $query;
	}
	
	public function get_insurance_name()
	{
		$table = "insurance_company";
		$where = "insurance_company_id > 0";
		$items = "insurance_company_id, insurance_company_name";
		$order = "insurance_company_name";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
		public function get_debtor_period()
	{
		$table = "debtors_period";
		$where = "debtors_period_id > 0";
		$items = "debtors_period_id, debtors_period_duration";
		$order = "debtors_period_duration";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	public function insurance_debtors_transactions($table, $where, $per_page, $page)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('visit.visit_date' ,'desc');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	public function insurance_debtors_transactions_report($table,$where)
	{
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		
		$query=$this->db->get();
		return $query;
	}
	
	
	public function get_total_collected($doctor_id, $date_from = NULL, $date_to = NULL)
	{
		$table = 'visit_charge, visit';
		
		$where = 'visit_charge.visit_id = visit.visit_id AND visit.personnel_id = '.$doctor_id;
		
		$visit_search = $this->session->userdata('all_doctors_search');
		if(!empty($visit_search))
		{
			$where = 'visit_charge.visit_id = visit.visit_id AND visit.personnel_id = '.$doctor_id.' '. $visit_search;
		}
		
		if(!empty($date_from) && !empty($date_to))
		{
			$where .= ' AND (date >= \''.$date_from.'\' AND date <= \''.$date_to.'\') ';
		}
		
		else if(empty($date_from) && !empty($date_to))
		{
			$where .= ' AND date LIKE \''.$date_to.'\'';
		}
		
		else if(!empty($date_from) && empty($date_to))
		{
			$where .= ' AND date LIKE \''.$date_from.'\'';
		}
		
		$this->db->select('SUM(visit_charge_units*visit_charge_amount) AS service_total');
		$this->db->where($where);
		$query = $this->db->get($table);
		
		// $result = $query->row();
		// $total = $result[0]->service_total;
		
		if($query->num_rows() > 0)
		{

			foreach ($query->result() as $key):
				# code...
				$total = $key->service_total;

				if(!is_numeric($total))
				{
					return 0;
				}
				else
				{
					return $total;
				}
			endforeach;
		}
		else
		{
			return 0;
		}
		
	}

	public function get_total_patients($doctor_id, $date_from = NULL, $date_to = NULL)
	{
		$table = 'visit';
		
		$where = 'visit.personnel_id = '.$doctor_id;
		
		if(!empty($date_from) && !empty($date_to))
		{
			$where .= ' AND (visit_date >= \''.$date_from.'\' AND visit_date <= \''.$date_to.'\') ';
		}
		
		else if(empty($date_from) && !empty($date_to))
		{
			$where .= ' AND visit_date = \''.$date_to.'\'';
		}
		
		else if(!empty($date_from) && empty($date_to))
		{
			$where .= ' AND visit_date = \''.$date_from.'\'';
		}
		
		$this->db->where($where);
		$total = $this->db->count_all_results('visit');
		
		return $total;
	}
	
	/*
	*	Export Time report
	*
	*/
	function doctor_reports_export($date_from = NULL, $date_to = NULL)
	{
		$this->load->library('excel');
		$report = array();
		
		//export title
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
		
		//document ehader
		$row_count = 0;
		$report[$row_count][0] = '#';
		$report[$row_count][1] = 'Doctor\'s name';
		$report[$row_count][2] = 'Total collection';
		$report[$row_count][3] = 'Patients seen';
		
		//get all doctors
		$doctor_results = $this->reports_model->get_all_doctors();
		$result = $doctor_results->result();
		$grand_total = 0;
		$patients_total = 0;
		$count = 0;
		
		foreach($result as $res)
		{
			$personnel_id = $res->personnel_id;
			$personnel_onames = $res->personnel_onames;
			$personnel_fname = $res->personnel_fname;
			$count++;
			$row_count++;
			
			//get service total
			$total = $this->reports_model->get_total_collected($personnel_id, $date_from, $date_to);
			$patients = $this->reports_model->get_total_patients($personnel_id, $date_from, $date_to);
			$grand_total += $total;
			$patients_total += $patients;
			
			$report[$row_count][0] = $count;
			$report[$row_count][1] = $personnel_fname.' '.$personnel_onames;
			$report[$row_count][2] = number_format($total, 0);
			$report[$row_count][3] = $patients;
		}
		$row_count++;
		
		$report[$row_count][0] = '';
		$report[$row_count][1] = '';
		$report[$row_count][2] = number_format($grand_total, 0);
		$report[$row_count][3] = $patients_total;
		
		//create the excel document
		$this->excel->addArray ( $report );
		$this->excel->generateXML ($title);
	}
	
	function doctor_patients_export($personnel_id, $date_from = NULL, $date_to = NULL)
	{
		$where = ' AND visit.personnel_id = '.$personnel_id;
		
		if(!empty($date_from) && !empty($date_to))
		{
			$where .= ' AND (visit_date >= \''.$date_from.'\' AND visit_date <= \''.$date_to.'\') ';
		}
		
		else if(empty($date_from) && !empty($date_to))
		{
			$where .= ' AND visit_date = \''.$date_to.'\'';
		}
		
		else if(!empty($date_from) && empty($date_to))
		{
			$where .= ' AND visit_date = \''.$date_from.'\'';
		}
		$_SESSION['all_transactions_search'] = $where;
		
		$this->export_transactions();
	}
}