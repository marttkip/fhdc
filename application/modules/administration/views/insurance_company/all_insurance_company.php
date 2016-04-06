<?php	
		$result = '<div class="row">
    					<div class="col-lg-12">
    					<a href="'.site_url().'/administration/add-insurance-company" class="btn btn-success pull-right">Add insurance company</a>
    					</div>
    				</div>';
		$result .='<div class="row">
					    <div class="col-md-12">

					      <!-- Widget -->
					      <div class="widget boxed">
					        <!-- Widget head -->
					        <div class="widget-head">

					          <h4 class="pull-left"><i class="icon-reorder"></i>All insurance company </h4>
					          <div class="widget-icons pull-right">
					         
					            <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
					            <a href="#" class="wclose"><i class="icon-remove"></i></a>
					          </div>
					          <div class="clearfix"></div>
					        </div>             

					        <!-- Widget content -->
					        <div class="widget-content">
					        	<div class="padd">
            						<div class="center-align">';
									$success = $this->session->userdata('success_message');
									
									if(!empty($success))
									{
										echo '<div class="alert alert-success"> <strong>Success!</strong> '.$success.' </div>';
										$this->session->unset_userdata('success_message');
									}
									
									$error = $this->session->userdata('error_message');
									
									if(!empty($error))
									{
										echo '<div class="alert alert-danger"> <strong>Oh snap!</strong> '.$error.' </div>';
										$this->session->unset_userdata('error_message');
									}
									
									//if users exist display them

									if ($insurance_company->num_rows() > 0)
									{
										$count = $page;
										
										$result .= 
										'
											<table class="table table-hover table-bordered ">
											  <thead>
												<tr>
												  <th>#</th>
												  <th>Name</th>
												  <th>Status</th>
												  <th colspan="5">Actions</th>
												</tr>
											  </thead>
											  <tbody>
										';
										foreach ($insurance_company->result() as $row)
										{
											$insurance_company_id = $row->insurance_company_id;
											$insurance_company_name = $row->insurance_company_name;
											//create deactivated status display
											if($row->insurance_company_status == 0)
											{
												$status = '<span class="label label-success">Active</span>';
												$button = '<a class="btn btn-info" href="'.site_url().'/administration/deactivate-insurance-company/'.$insurance_company_id.'" onclick="return confirm(\'Do you want to deactivate '.$insurance_company_name.'?\');">Deactivate</a>';
												
											}
											//create activated status display
											else if($row->insurance_company_status == 1)
											{
												$status = '<span class="label label-important">Deactivated</span>';
												$button = '<a class="btn btn-info" href="'.site_url().'/administration/activate-insurance-company/'.$insurance_company_id.'" onclick="return confirm(\'Do you want to activate '.$insurance_company_name.'?\');">Activate</a>';
											}
											$count++;
											$result .= 
											'
												<tr>
													<td>'.$count.'</td>
													<td>'.$insurance_company_name.'</td>
													<td>'.$status.'</td>
													<td><a href="'.site_url().'/administration/edit-insurance-company/'.$insurance_company_id.'" class="btn btn-sm btn-success">Edit</a></td>
													<td>'.$button.'</td>
													<td><a href="'.site_url().'/administration/delete-insurance-company/'.$insurance_company_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$insurance_company_name.'?\');">Delete</a></td>
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
										$result .= "There are no insurance company listed yet";
									}
								$result .= '</div>
								</div>
								</div>';
									
									echo $result;
?>