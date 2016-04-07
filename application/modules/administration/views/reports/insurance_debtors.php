<!-- search -->
<?php echo $this->load->view('search/insurance_search', '', TRUE);?>
<!-- end search -->

<?php //echo $this->load->view('cash_statistics', '', TRUE);?>
	
<div class="row">
    <div class="col-md-12">

      <!-- Widget -->
      <div class="widget boxed">
        <!-- Widget head -->
        <div class="widget-head">
          <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title;?></h4>
          <div class="widget-icons pull-right">
            <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
            <a href="#" class="wclose"><i class="icon-remove"></i></a>
          </div>
          <div class="clearfix"></div>
        </div>             

        <!-- Widget content -->
        <div class="widget-content">
          <div class="padd"> 
<?php
		
		$result = '<a href="'.site_url().'/administration/reports/print_insurance_debtors" class="btn btn-sm btn-success pull-right" target="_blank">Print Report</a>
		<a href="'.site_url().'/administration/reports/export_insurance_debtors" class="btn btn-sm btn-success pull-right" target="_blank">Export Reports</a>';
		$search =$this->session->userdata('insurance_search');
		if(!empty($search))
		{
				echo '<a href="'.site_url().'/administration/reports/close_insurance_debtors_search" class="btn btn-sm btn-warning pull-left">Close Search</a>';
		}
		
		//if users exist display them
		if ($query->num_rows() > 0)
		{
			$count = $page;
			
			$result .= 
				'
					<table class="table table-hover table-bordered table-striped table-responsive col-md-12">
					  <thead>
						<tr>
						  <th>#</th>
						  <th>Visit Date</th>
						  <th>Patient Number</th>
						  <th>Patient Name</th>
						  <th>Insurance Company</th>
						  <th>Patient Insurance Number</th>
						  <th>Procedures</th>
						  <th>Amount</th>
						  <th>Paid</th>
						  <th>Balance</th>
						</tr>
					  </thead>
					  <tbody>
			';
			foreach ($query->result() as $row)
			{
				
				
				$count++;
				$total_invoiced = 0;
				$amount_paid= 0;
				$insurance_company_name=$row->insurance_company_name;
				//$payment_created = date('jS M Y',strtotime($row->payment_created));
				//$time = date('H:i a',strtotime($row->time));
				$patient_id = $row->patient_id;
				$insurance_number=$row->patient_insurance_number;
				$visit_date=$row->visit_date;
				$visit_id = $row->visit_id;
				$patient_id = $row->patient_id;
				$patient_othernames = $row->patient_othernames;
				$patient_surname = $row->patient_surname;
				$patient_number=$row->patient_number;
				//$transaction_code = $row->transaction_code;
				//$service_name = $row->service_name;
				//$created_by = $row->personnel_fname.' '.$row->personnel_onames;
				
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
				if($balance > 0){
					$item_invoiced_rs = $this->accounts_model->get_patient_visit_charge_items($visit_id);
					$procedures = '';
					 if(count($item_invoiced_rs) > 0){
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
				$result .= 
						'
							<tr>
								<td>'.$count.'</td>
								<td>'.$visit_date.'</td>
								<td>'.$patient_number.'</td>
								<td>'.$patient_surname.' '.$patient_othernames.'</td>
								<td>'.$insurance_company_name.'</td>
								<td>'.$insurance_number.'</td>
								<td>'.$procedures.'</td>
								<td>'.number_format($total_invoice, 2).'</td>
								<td>'.number_format($total_payments, 2).'</td>
								<td>'.$balance.'</td>
							</tr> 
					';
				}
			}
			
			$result .= 
			'
						  </tbody>
						</table>
			';
		
		}
		
		else
		{
			$result .= "There are no payments";
		}
		
		echo $result;
?>
          </div>
          
          <div class="widget-foot">
                                
				<?php if(isset($links)){echo $links;}?>
            
                <div class="clearfix"></div> 
            
            </div>
        </div>
        </div>
        </div>
        </div>
 