<?php

class insurance_company_model extends CI_Model 
{
	/*
	*	Count all items from a table
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function count_items($table, $where, $limit = NULL)
	{
		if($limit != NULL)
		{
			$this->db->limit($limit);
		}
		$this->db->from($table);
		$this->db->where($where);
		return $this->db->count_all_results();
	}
	
	/*
	*	Retrieve all insurance_company
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_insurance_company($table, $where, $per_page, $page)
	{
		//retrieve all insurance_company
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('insurance_company_id');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	/*
	*	Retrieve all administrators
	*
	*/
	public function get_all_administrators()
	{
		$this->db->from('insurance_company');
		$this->db->select('*');
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Retrieve all front end insurance_company
	*
	*/
	public function get_all_front_end_insurance_company()
	{
		$this->db->from('insurance_company');
		$this->db->select('*');
		$this->db->where('insurance_company_level_id = 2');
		$query = $this->db->get();
		
		return $query;
	}
	
	
	/*
	*	Add a new insurance_company to the database
	*
	*/
	public function add_insurance_company()
	{
		$data = array(
				'insurance_company_name'=>$this->input->post('insurance_company_name'),
				'created_by'=>$this->session->userdata('user_id'),
				'created'=>date('Y-m-d H:i:s'),
				'insurance_company_status'=>0
			);
			
		if($this->db->insert('insurance_company', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Add a new front end insurance_company to the database
	*
	*/
	public function add_frontend_insurance_company()
	{
		$data = array(
				'first_name'=>ucwords(strtolower($this->input->post('first_name'))),
				'other_names'=>ucwords(strtolower($this->input->post('other_names'))),
				'email'=>$this->input->post('email'),
				'password'=>md5($this->input->post('password')),
				'phone'=>$this->input->post('phone'),
				'created'=>date('Y-m-d H:i:s'),
				'insurance_company_level_id'=>2,
				'activated'=>1
			);
			
		if($this->db->insert('insurance_company', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Edit an existing insurance_company
	*	@param int $insurance_company_id
	*
	*/
	public function edit_insurance_company($insurance_company_id)
	{
		$data = array(
				'insurance_company_name'=>$this->input->post('insurance_company_name'),
				'insurance_company_status'=>1
			);
		
		//check if insurance_company wants to update their password
		
		
		$this->db->where('insurance_company_id', $insurance_company_id);
		
		if($this->db->update('insurance_company', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Edit an existing insurance_company
	*	@param int $insurance_company_id
	*
	*/
	public function edit_frontend_insurance_company($insurance_company_id)
	{
		$data = array(
				'first_name'=>ucwords(strtolower($this->input->post('first_name'))),
				'other_names'=>ucwords(strtolower($this->input->post('last_name'))),
				'phone'=>$this->input->post('phone')
			);
		
		//check if insurance_company wants to update their password
		$pwd_update = $this->input->post('admin_insurance_company');
		if(!empty($pwd_update))
		{
			if($this->input->post('old_password') == md5($this->input->post('current_password')))
			{
				$data['password'] = md5($this->input->post('new_password'));
			}
			
			else
			{
				$this->session->set_insurance_companydata('error_message', 'The current password entered does not match your password. Please try again');
			}
		}
		
		$this->db->where('insurance_company_id', $insurance_company_id);
		
		if($this->db->update('insurance_company', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Edit an existing insurance_company's password
	*	@param int $insurance_company_id
	*
	*/
	public function edit_password($insurance_company_id)
	{
		if($this->input->post('slug') == md5($this->input->post('current_password')))
		{
			if($this->input->post('new_password') == $this->input->post('confirm_password'))
			{
				$data['password'] = md5($this->input->post('new_password'));
		
				$this->db->where('insurance_company_id', $insurance_company_id);
				
				if($this->db->update('insurance_company', $data))
				{
					$return['result'] = TRUE;
				}
				else{
					$return['result'] = FALSE;
					$return['message'] = 'Oops something went wrong and your password could not be updated. Please try again';
				}
			}
			else{
					$return['result'] = FALSE;
					$return['message'] = 'New Password and Confirm Password don\'t match';
			}
		}
		
		else
		{
			$return['result'] = FALSE;
			$return['message'] = 'You current password is not correct. Please try again';
		}
		
		return $return;
	}
	
	/*
	*	Retrieve a single insurance_company
	*	@param int $insurance_company_id
	*
	*/
	public function get_insurance_company($insurance_company_id)
	{
		//retrieve all insurance_company
		$this->db->from('insurance_company');
		$this->db->select('*');
		$this->db->where('insurance_company_id = '.$insurance_company_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Retrieve a single insurance_company by their email
	*	@param int $email
	*
	*/
	public function get_insurance_company_by_email($email)
	{
		//retrieve all insurance_company
		$this->db->from('insurance_company');
		$this->db->select('*');
		$this->db->where('email = \''.$email.'\'');
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing insurance_company
	*	@param int $insurance_company_id
	*
	*/
	public function delete_insurance_company($insurance_company_id)
	{
		if($this->db->delete('insurance_company', array('insurance_company_id' => $insurance_company_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated insurance_company
	*	@param int $insurance_company_id
	*
	*/
	public function activate_insurance_company($insurance_company_id)
	{
		$data = array(
				'insurance_company_status' => 0
			);
		$this->db->where('insurance_company_id', $insurance_company_id);
		
		if($this->db->update('insurance_company', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated insurance_company
	*	@param int $insurance_company_id
	*
	*/
	public function deactivate_insurance_company($insurance_company_id)
	{
		$data = array(
				'insurance_company_status' => 1
			);
		$this->db->where('insurance_company_id', $insurance_company_id);
		
		if($this->db->update('insurance_company', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Reset a insurance_company's password
	*	@param string $email
	*
	*/
	public function reset_password($email)
	{
		//reset password
		$result = md5(date("Y-m-d H:i:s"));
		$pwd2 = substr($result, 0, 6);
		$pwd = md5($pwd2);
		
		$data = array(
				'password' => $pwd
			);
		$this->db->where('email', $email);
		
		if($this->db->update('insurance_company', $data))
		{
			//email the password to the insurance_company
			$insurance_company_details = $this->insurance_company_model->get_insurance_company_by_email($email);
			
			$insurance_company = $insurance_company_details->row();
			$insurance_company_name = $insurance_company->first_name;
			
			//email data
			$receiver['email'] = $this->input->post('email');
			$sender['name'] = 'Fad Shoppe';
			$sender['email'] = 'info@fadshoppe.com';
			$message['subject'] = 'You requested a password change';
			$message['text'] = 'Hi '.$insurance_company_name.'. Your new password is '.$pwd;
			
			//send the insurance_company their new password
			if($this->email_model->send_mail($receiver, $sender, $message))
			{
				return TRUE;
			}
			
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
}
?>