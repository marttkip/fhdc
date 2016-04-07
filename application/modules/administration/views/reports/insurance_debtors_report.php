<!DOCTYPE html>
<html lang="en">

    <head>
        <title>FHDC | Receipt</title>
        <!-- For mobile content -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- IE Support -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Bootstrap -->
        <link href="<?php echo base_url();?>assets/bluish/style/bootstrap.css" rel="stylesheet" media="all">
        <style type="text/css">
          .receipt_spacing{letter-spacing:0px; font-size: 12px; margin-left:20px; margin-right:20px;}
    			.center-align{margin:0 auto; text-align:center;}
    			
    			.receipt_bottom_border{border-bottom: #888888 medium solid;margin-left:0px; margin-right:0px;margin-bottom: 5px;}
    			.row .col-md-12 table {
    				border:solid #000 !important;
    				border-width:1px 0 0 1px !important;
    				font-size:10px;
    			}
    			
    			.row .col-md-12 th, .row .col-md-12 td {
    				border:solid #000 !important;
    				border-width:0 1px 1px 0 !important;
    			}
    			.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td
    			{
    				 padding: 2px;
    			}
    			
    			.row .col-md-12 .title-item{float:left;width: 130px; font-weight:bold; text-align:right; padding-right: 20px;}
    			.title-img{
    			  float: left;
      			padding-left: 30px;
      			width: 200px;
      			height: 70px;
      			/*margin-top: -43px;*/
          }
        </style>
    </head>
    <body class="receipt_spacing">
    	<div class="row receipt_bottom_border" style=" min-height: 50px;">
        <div class="col-md-4">
          	<img src="<?php echo base_url();?>images/logo.jpg"  class="title-img"/>
        </div>
  			<div class="col-md-4" >
  				<strong>
  					<p class="center-align"> Family Health Dental Clinic</p>
  					<p class="center-align"> P.O. Box 15 00200, KNH</p> 
  					<p class="center-align">Tel. 0718 501 426 or 0734 863 792</p>
  					<p class="center-align"> E-mail: familyhealthdentalclinic@gmail.com</p> 
  					
  				</strong>
  			</div>
        <div class="col-md-4" >
        </div>
      </div>
        
    	<div class="row" >
        	<div class="col-md-12 center-align">
            	<strong>INSURANCE COMPANIES</strong>
            </div>
        </div>
   
    	<div class="row">
          <div class="col-md-12">
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
			 <?php
			    $result = '';
				$count = 0;
				foreach ($query->result() as $row)
				{
				$count++;
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
				{	
					$insurance_number =' - ';
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
					//var_dump($query->num_rows()); die();
				}
			}
			
			$result .= 
			'
						  </tbody>
						</table>
			';
		
	
		echo $result;
?>
                                </tbody>
                              </table>
            </div>
        </div>
        
    	<div class="" style="font-style:italic; font-size:10px;">
        	<div style="float:left; margin:0 10px 0 10px;">
            	Downloaded by: <?php echo $served_by; ?>
            </div>
        	<div style="float:right; margin:0 10px 0 10px;">
            	<?php echo $today; ?> Thank you
            </div>
        </div>
    </body>
    
</html>