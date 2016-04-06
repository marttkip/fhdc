<?php

class Invoices_model extends CI_Model 
{
	/*
	*	Retrieve all invoices
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_invoices($table, $where, $per_page, $page, $order, $order_method)
	{
		//retrieve all invoices
		$this->db->from($table);
		$this->db->where($where);
		$this->db->order_by($order, $order_method);
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	/*
	*	Retrieve an invoice
	*
	*/
	public function get_invoice($invoice_id)
	{
		$this->db->select('*');
		$this->db->where('invoices.invoice_status = invoice_status.invoice_status_id AND users.user_id = invoices.user_id AND invoices.invoice_id = '.$invoice_id);
		$query = $this->db->get('invoices, invoice_status, users');
		
		return $query;
	}
	
	/*
	*	Retrieve all invoice of an invoice
	*
	*/
	public function get_invoice_items($invoice_id)
	{
		$this->db->where('custom_invoice_id = '.$invoice_id);
		$query = $this->db->get('custom_invoice');
		
		return $query;
	}
	
	public function get_custom_invoice_items($custom_invoice_id)
	{
		$this->db->where('custom_invoice_item_status = 1 AND custom_invoice_id = '.$custom_invoice_id);
		$query = $this->db->get('custom_invoice_item');
		
		return $query;
	}
	
	/*
	*	Create invoice number
	*
	*/
	public function create_invoice_number()
	{
		//select product code
		$this->db->from('custom_invoice');
		$this->db->where("custom_invoice_number LIKE 'CINV".date('y')."/%'");
		$this->db->select('MAX(custom_invoice_number) AS number');
		$query = $this->db->get();
		$preffix = "CINV".date('y').'/';
		
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
	
	/*
	*	Create the total cost of an invoice
	*	@param int invoice_id
	*
	*/
	public function calculate_invoice_cost($invoice_id)
	{
		//select product code
		$this->db->from('invoice_item');
		$this->db->where('invoice_id = '.$invoice_id);
		$this->db->select('SUM(invoice_item_price * invoice_item_quantity) AS total_cost');
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{
			$result = $query->result();
			$total_cost =  $result[0]->total_cost;
		}
		
		else
		{
			$total_cost = 0;
		}
		
		return $total_cost;
	}
	
	/*
	*	Add a new invoice
	*
	*/
	public function add_invoice()
	{
		$invoice_number = $this->create_invoice_number();
		
		$data = array(
				'custom_invoice_number'=>$invoice_number,
				'payable_by'=>$this->input->post('payable_by'),
				'custom_invoice_debtor_contacts'=>$this->input->post('custom_invoice_debtor_contacts'),
				'custom_invoice_debtor'=>$this->input->post('custom_invoice_debtor'),
				'custom_invoice_created'=>date('Y-m-d H:i:s'),
				'custom_invoice_created_by'=>$this->session->userdata('personnel_id'),
				'custom_invoice_modified_by'=>$this->session->userdata('personnel_id')
			);
			
		if($this->db->insert('custom_invoice', $data))
		{
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Add a new invoice items
	*
	*/
	public function add_custom_invoice_items($custom_invoice_id)
	{
		$invoice_number = $this->create_invoice_number();
		
		$data = array(
				'custom_invoice_id'=>$custom_invoice_id,
				'custom_invoice_item_description'=>$this->input->post('custom_invoice_item_description'),
				'custom_invoice_item_cost'=>$this->input->post('custom_invoice_item_cost'),
				'custom_invoice_item_quantity'=>$this->input->post('custom_invoice_item_quantity')
			);
			
		if($this->db->insert('custom_invoice_item', $data))
		{
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an invoice
	*	@param int $invoice_id
	*
	*/
	public function _update_invoice($invoice_id)
	{
		$invoice_number = $this->create_invoice_number();
		
		$data = array(
				'user_id'=>$this->input->post('user_id'),
				'payment_method'=>$this->input->post('payment_method'),
				'invoice_status'=>1,
				'invoice_instructions'=>$this->input->post('invoice_instructions'),
				'modified_by'=>$this->session->userdata('user_id')
			);
		
		$this->db->where('invoice_id', $invoice_id);
		if($this->db->update('invoices', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*
	*	Retrieve all invoices
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_payment_methods()
	{
		//retrieve all invoices
		$this->db->from('payment_method');
		$this->db->select('*');
		$this->db->invoice_by('payment_method_name');
		$query = $this->db->get();
		
		return $query;
	}

	/*
	*	Retrieve all invoices
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_invoice_status()
	{
		//retrieve all invoices
		$this->db->from('invoice_status');
		$this->db->select('*');
		$this->db->invoice_by('invoice_status_name');
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Add a invoice product
	*
	*/
	public function add_product($invoice_id, $product_id, $quantity, $price)
	{
		//Check if item exists
		$this->db->select('*');
		$this->db->where('product_id = '.$product_id.' AND invoice_id = '.$invoice_id);
		$query = $this->db->get('invoice_item');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row();
			$qty = $result->quantity;
			
			$quantity += $qty;
			
			$data = array(
					'quantity'=>$quantity
				);
				
			$this->db->where('product_id = '.$product_id.' AND invoice_id = '.$invoice_id);
			if($this->db->update('invoice_item', $data))
			{
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
		
		else
		{
			$data = array(
					'invoice_id'=>$invoice_id,
					'product_id'=>$product_id,
					'quantity'=>$quantity,
					'price'=>$price
				);
				
			if($this->db->insert('invoice_item', $data))
			{
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
	}
	
	/*
	*	Update an invoice item
	*
	*/
	public function update_cart($invoice_item_id, $quantity)
	{
		$data = array(
					'quantity'=>$quantity
				);
				
		$this->db->where('invoice_item_id = '.$invoice_item_id);
		if($this->db->update('invoice_item', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Delete an existing invoice item
	*	@param int $product_id
	*
	*/
	public function delete_invoice_item($invoice_item_id)
	{
		$this->db->where(array('custom_invoice_item_id' => $invoice_item_id));
		if($this->db->update('custom_invoice_item', array('custom_invoice_item_status' => 0)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function request_cancel($invoice_number, $customer_id)
	{
		$this->db->where(array(
				'invoice_number' => $invoice_number,
				'customer_id' => $customer_id
			)
		);
		$data['invoice_status_id'] = 6;
		if($this->db->update('invoices', $data))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function get_customer($customer_id)
	{
		$this->db->where('customer_id', $customer_id);
		$query = $this->db->get('customer');
		
		return $query;
	}
	
	/*
	*
	*	Refund invoice
	*
	*/
	public function refund_invoice($invoice_number, $vendor_id)
	{
		$vendor_data = array();
		$invoice_items = array();
		$invoice_details = array();
		$created_invoices = '';
		
		$this->db->where(array('invoice_number' => $invoice_number, 'vendor_id' => $vendor_id));
		$query = $this->db->get('invoices');
		
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			
			$customer_id = $row->customer_id;
			$invoice_id = $row->invoice_id;
			$customer_query = $this->get_customer($customer_id);
			$customer_email = '';
			$customer_total = 0;
			$total_price = 0;
			$total_additional_price = 0;
			
			if($customer_query->num_rows() > 0)
			{
				$row = $customer_query->row();
				$customer_email = $row->customer_email;
			}
			$created_invoices .= $invoice_id.'-';
			
			//check number of invoice items
			$invoice_items = $this->invoices_model->get_invoice_items($invoice_id);
			$total_invoice_items = $invoice_items->num_rows();
			
			if($total_invoice_items > 0)
			{
				foreach($invoice_items->result() as $res)
				{
					$invoice_item_id = $res->invoice_item_id;
					$product_id = $res->product_id;
					$invoice_item_quantity = $res->invoice_item_quantity;
					$invoice_item_price = $res->invoice_item_price;
					$product_name = $res->product_name;
					$total_price += ($invoice_item_quantity * $invoice_item_price);
					
					//features
					$this->db->select('invoice_item_feature.*, product_feature.feature_value, product_feature.thumb, feature.feature_name');
					$this->db->where('product_feature.feature_id = feature.feature_id AND invoice_item_feature.product_feature_id = product_feature.product_feature_id AND invoice_item_feature.invoice_item_id = '.$invoice_item_id);
					$invoice_item_features = $this->db->get('invoice_item_feature, product_feature, feature');
					
					if($invoice_item_features->num_rows() > 0)
					{
						foreach($invoice_item_features->result() as $feat)
						{
							$product_feature_id = $feat->product_feature_id;
							$added_price = $feat->additional_price;
							$feature_name = $feat->feature_name;
							$feature_value = $feat->feature_value;
							$feature_image = $feat->thumb;
							$total_additional_price += $added_price;
						}
					}
					
					//create invoice items
					array_push($invoice_items, array(
							"name" => $product_name,
							"price" => ($total_price + $total_additional_price),
							"identifier" => $invoice_item_id
						)
					);
				}
			}
			$total = $total_price + $total_additional_price;
			//add vendor data to the vendor_data array
			array_push($vendor_data, array(
					'email' => $customer_email, 
					'amount' => $total
				)
			);
			array_push($invoice_details, array(
					'receiver' => $customer_email, 
					'invoiceData' => array(
						'item' => $invoice_items
					)
				)
			);
		}
		
		//create return data
		$return['vendor_data'] = $vendor_data;
		$return['invoice_details'] = $invoice_details;
		$return['created_invoices'] = $created_invoices;
		
		return $return;
	}
}