<?php 
	$queue_total = number_format($this->reports_model->get_queue_total(), 0, '.', ',');
	
	$patients_today = number_format($this->reports_model->get_patients_total(), 0, '.', ',');
	$where = 'expenses_id > 0 AND expense_status = 0 AND dateofissue = "'.date('Y-m-d').'"';
	$total_expenses = $this->administration_model->get_expense_total_amount($where);
	$where_suppluers = 'suppliers_id > 0 AND supplier_status = 0 AND dateofissue = "'.date('Y-m-d').'"';
	$total_suppliers = $this->administration_model->get_supplier_total_amount($where_suppluers);

	$total_days_expense = $total_expenses + $total_suppliers;



	$total_debits = $this->administration_model->get_sum_days_debit_notes();
	$total_credits = $this->administration_model->get_sum_days_credit_notes();
	$total_collection = $this->administration_model->get_sum_days_cash_notes();
	$total_collection = $this->administration_model->get_sum_days_cash_notes();

	$total_cash = ($total_collection + $total_debits) + $total_credits;


	$daily_balance = $this->administration_model->get_sum_of_days_invoices();

 ?>
		<div class="row">
            <div class="col-md-12">
                <!-- Page header start -->
                <div class="page-header">
                    <div class="page-title">
                        <h3>Dashboard</h3>
                        <span>
                        <?php 
						//salutation
						if(date('a') == 'am')
						{
							echo 'Good morning, ';
						}
						
						else if((date('H') >= 12) && (date('H') < 17))
						{
							echo 'Good afternoon, ';
						}
						
						else
						{
							echo 'Good evening, ';
						}
						echo $this->session->userdata('first_name');
						?>
                        </span>
                    </div>
                    <ul class="page-stats">
                        <li>
                            <div class="summary">
                                <span>Todays' Cash</span>
                                <h3><?php echo $total_cash;?></h3>
                            </div>
                            <span id="queue_total"></span>
                        </li>
                        <li>
                            <div class="summary">
                                <span>Todays' Cashout</span>
                                <h3><?php echo number_format($total_days_expense,2);?></h3>
                            </div>
                            <span id="queue_total"></span>
                        </li>
                        <li>
                            <div class="summary">
                                <span>Todays' Cashin</span>
                                <h3>KSH <?php echo $daily_balance;?></h3>
                            </div>
                            <!--<span id="payment_methods" style="height:60px;"></span>-->
                        </li>
                         <li>
                            <div class="summary">
                                <span>Todays' collection</span>
                                <h3>KSH <?php echo $daily_balance;?></h3>
                            </div>
                            <!--<span id="payment_methods" style="height:60px;"></span>-->
                        </li>
                    </ul>
                </div>
                <!-- Page header ends -->
            </div>
          </div>

          <div class="row statistics">
              <div class="col-md-6 col-sm-6">
                  <ul class="today-datas">
                      <!-- List #1 -->
                      <li class="overall-datas">
                          <!-- Graph -->
                          <h5>Expenses</h5>
			                <table class="table table-striped table-hover table-condensed">
			                	<thead>
			                    	<tr>
			                        	<th>Expenses title</th>
			                            <th>Amount collected</th>
			                        </tr>
			                    </thead>
			                    <tbody>
			                       
			                        <tr>
			                            <th>Day's Expense</th>
			                             <td>KES <?php echo number_format($total_expenses,2)?></td>
			                        </tr>
			                        <tr>
			                            <th>Suppliers Expense</th>
			                            <td>KES <?php echo number_format($total_suppliers,2)?></td>
			                        </tr>
			                        <tr>
			                            <th>Total days' expense</th>
			                            <td>KES <?php echo number_format($total_days_expense,2)?></td>
			                        </tr>
			                    </tbody>
			                </table>
			                <!-- Text -->
			            
                          <div class="clearfix"></div>
                      </li>
                  </ul>
              </div>
              <div class="col-md-6 col-sm-6">
                  <ul class="today-datas">
                      <li class="overall-datas" >
                           <!-- Graph -->
                          <h5>Revenue</h5>
			                <table class="table table-striped table-hover table-condensed">
			                	<thead>
			                    	<tr>
			                        	<th>Expenses title</th>
			                            <th>Amount collected</th>
			                        </tr>
			                    </thead>
			                    <tbody>
			                       
			                        
			                        <tr>
			                            <th>Doctors renumeration (40%) of collection)</th>
			                            <td>KES <?php echo number_format(0.4 * $daily_balance,2)?></td>
			                        </tr>
			                        <tr>
			                            <th> Cashout (Expenses + Suppliers)</th>
			                            <td>KES <?php echo number_format($total_suppliers + $total_expenses,2)?></td>
			                        </tr>
			                        <tr>
			                            <th>Total Amount (Days collection - (40% + Cashout))</th>
			                             <td>KES <?php echo number_format($daily_balance - ($total_days_expense +(0.4 * $daily_balance)),2)?></td>
			                        </tr>
			                    </tbody>
			                </table>
			                <!-- Text -->
                          <div class="clearfix"></div>
                      </li>
                      
                  </ul>
              </div>
          </div>
        <div class="row">
              <div class="col-md-12 col-sm-12">

			      <!-- Widget -->
			      <div class="widget boxed">
			        <!-- Widget head -->
			        <div class="widget-head">
			          <h4 class="pull-left"><i class="icon-reorder"></i>Doctor's Collection for <?php echo date('Y-m-d');?></h4>
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
						$date_from = date('Y-m-d');
						$date_to = date('Y-m-d');
						if($doctor_results->num_rows() > 0)
						{
						$count = 0;
						
							echo  
								'
									<a href="'.site_url().'/administration/reports/doctor_reports_export/'.$date_from.'/'.$date_to.'" class="btn btn-success">Export</a>
									<table class="table table-hover table-bordered table-striped table-responsive col-md-12">
									  <thead>
										<tr>
										  <th>#</th>
										  <th>Doctor\'s name</th>
										  <th>Total collection</th>
										  <th>Patients seen</th>
										  <th>40%</th>
										  <th>Actions</th>
										</tr>
									</thead>
									<tbody>
								';
								$result = $doctor_results->result();
								$grand_total = 0;
								$patients_total = 0;
								
								foreach($result as $res)
								{
									$personnel_id = $res->personnel_id;
									$personnel_onames = $res->personnel_onames;
									$personnel_fname = $res->personnel_fname;
									$count++;
									
									//get service total
									$total = $this->reports_model->get_total_collected($personnel_id, $date_from, $date_to);
									$patients = $this->reports_model->get_total_patients($personnel_id, $date_from, $date_to);
									$percentage = 0.4 * $total;
									$grand_total += $total;
									$patients_total += $patients;
									$grand_percentage = 0.4 * $grand_total;
									
									echo '
										<tr>
											<td>'.$count.'</td>
											<td>'.$personnel_fname.' '.$personnel_onames.'</td>
											<td>'.number_format($total, 0).'</td>
											<td>'.$patients.'</td>
											<td>'.number_format($percentage, 0).'</td>
											<td>
												<a href="'.site_url().'/administration/reports/doctor_patients_export/'.$personnel_id.'/'.$date_from.'/'.$date_to.'" class="btn btn-success">Patients</a>
											</td>
										</tr>
									';
								}
								
								echo 
								'
									
										<tr>
											<td colspan="2">Total</td>
											<td><span class="bold">'.number_format($grand_total, 0).'</span></td>
											<td><span class="bold" >'.$patients_total.' patients</span></td>
											<td> <span class="bold">'.number_format($grand_percentage,0).'</span></td>
											<td></td>
										</tr>
									</tbody>
								</table>
								';
						}
						?>
			          </div>
			          
			          <div class="widget-foot">
			            
			                <div class="clearfix"></div> 
			            
			            </div>
			        </div>
			        <!-- Widget ends -->

			      </div>
              </div>
         </div>
          
        <script type="text/javascript">
			var config_url = $('#config_url').val();

//Get patients per day for the last 7 days
$.ajax({
	type:'POST',
	url: config_url+"/administration/charts/latest_patient_totals",
	cache:false,
	contentType: false,
	processData: false,
	dataType: "json",
	success:function(data){
		
		var bars = data.bars;
		var days_total = bars.split(',').map(function(item) {
			return parseInt(item, 10);
		});
		
		$("#patients_per_day").sparkline(days_total, {
			type: 'bar',
			height: data.highest_bar,
			barWidth: 4,
			barColor: '#fff'});
	},
	error: function(xhr, status, error) {
		alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
	}
});

//Get Revenue for the individual revenue types
$.ajax({
	type:'POST',
	url: config_url+"/administration/charts/queue_total",
	cache:false,
	contentType: false,
	processData: false,
	dataType: "json",
	success:function(data){
		
		var bars = data.bars;
		var queue_total = bars.split(',').map(function(item) {
			return parseInt(item, 10);
		});
		
		$("#queue_total").sparkline(queue_total, {
			type: 'bar',
			height: data.highest_bar,
			barWidth: 4,
    		barColor: '#E25856'});
	},
	error: function(xhr, status, error) {
		alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
	}
});

//Get payment methods
$.ajax({
	type:'POST',
	url: config_url+"/administration/charts/payment_methods",
	cache:false,
	contentType: false,
	processData: false,
	dataType: "json",
	success:function(data){
		
		var bars = data.bars;
		var queue_total = bars.split(',').map(function(item) {
			return parseInt(item, 10);
		});
		
		$("#payment_methods").sparkline(queue_total, {
			type: 'bar',
			height: data.highest_bar,
			barWidth: 4,
    		barColor: '#94B86E'});
	},
	error: function(xhr, status, error) {
		alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
	}
});
		</script>