<!-- search -->
<?php echo $this->load->view('search/doctors', '', TRUE);?>
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
					$grand_total += $total;
					$patients_total += $patients;
					
					echo '
						<tr>
							<td>'.$count.'</td>
							<td>'.$personnel_fname.' '.$personnel_onames.'</td>
							<td>'.number_format($total, 0).'</td>
							<td>'.$patients.'</td>
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
							<td>'.number_format($grand_total, 0).'</td>
							<td>'.$patients_total.'</td>
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