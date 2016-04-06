
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
                                <span>Total Expense</span>
                                <h3>KES<?php echo number_format($total_expense,2);?></h3>
                            </div>
                            <span id="queue_total"></span>
                        </li>
                        <li>
                            <div class="summary">
                                 <a href="<?php echo site_url()?>/administration/new_expense" class="btn btn-sm btn-success">Add a New Expense </a>
                            </div>
                            <!--<span id="payment_methods" style="height:60px;"></span>-->
                        </li>
                    </ul>
                </div>
                <!-- Page header ends -->
            </div>
          </div>

   
          