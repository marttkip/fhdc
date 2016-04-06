<?php 
 $v_data['module'] = $module;
echo $this->load->view('search/supplier_search', $v_data, TRUE);?>
 <!-- <div class="row">
	<div class="col-md-12">
		<div class="pull-right">
		 <a href="<?php echo site_url()?>/administration/new_supplier" class="btn btn-sm btn-success">Add a New supplier </a>

		</div>
	</div>
</div> -->
<?php echo $this->load->view('dashboard/supplier_summary', '', TRUE);?>
<div class="row">
    <div class="col-md-12">

      <!-- Widget -->
      <div class="widget boxed">
        <!-- Widget head -->
        <div class="widget-head">
          <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title;?> </h4>
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
		$error = $this->session->userdata('service_error_message');
		$success = $this->session->userdata('service_success_message');
		
		if(!empty($success))
		{
			echo '
				<div class="alert alert-success">'.$success.'</div>
			';
			$this->session->unset_userdata('service_success_message');
		}
		
		if(!empty($error))
		{
			echo '
				<div class="alert alert-danger">'.$error.'</div>
			';
			$this->session->unset_userdata('service_error_message');
		}
		$search = $this->session->userdata('supplier_search');
		
		if(!empty($search))
		{
			echo '<a href="'.site_url().'/administration/close_supplier_search/'.$module.'" class="btn btn-warning">Close Search</a>';
		}
		$result = '';
		
		//if users exist display them
		if ($query->num_rows() > 0)
		{
			$count = $page;
			
			$result .= 
				'
					<table class="table table-hover table-bordered ">
					  <thead>
						<tr>
						  <th>#</th>
						  <th>supplier Name</th>
						  <th>Amount</th>
						  <th>Recepient</th>
						  <th>Reason</th>
						  <th>Date of issue</th>
						  <th>Date recorded</th>
						  <th colspan="3">Actions</th>
						</tr>
					  </thead>
					  <tbody>
				';
			
			foreach ($query->result() as $row)
			{
				
				$suppliers_id = $row->suppliers_id;
				$reason = $row->reason;
				$recepient = $row->recepient;
				$amount = $row->amount;
				$date = $row->date;
				$dateofissue = $row->dateofissue;
				$supplier_status = $row->supplier_status;
				$supplier_name = $row->supplier_name;
				
				$count++;
				$result .= 
					'
						<tr>
							<td>'.$count.'</td>
							<td>'.$supplier_name.'</td>
							<td>'.$amount.'</td>
							<td>'.$recepient.'</td>
							<td>'.$reason.'</td>
							<td>'.$date.'</td>
							<td>'.$dateofissue.'</td>
							<td><a href="'.site_url().'/administration/edit_supplier/'.$suppliers_id.'" class="btn btn-sm btn-info"> Edit </a></td>
							<td><a href="'.site_url().'/administration/delete_service/'.$suppliers_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete this service?\'"> Delete </a></td>
						</tr> 
					';
			}
			
			$result .= 
			'
						  </tbody>
						</table>
			';
		}
		
		else
		{
			$result .= "There are no services";
		}
		
		echo $result;
?>
          </div>
          
          <div class="widget-foot">
                                
				<?php if(isset($links)){echo $links;}?>
            
                <div class="clearfix"></div> 
            
            </div>
        </div>
        <!-- Widget ends -->

      </div>
    </div>
  </div>