<div class="row">
    <div class="col-md-12">
      <a href="<?php echo site_url();?>/reception/all-patients" class="btn btn-primary pull-left">  Patients List</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
      <a href="<?php echo site_url();?>/reception/all-patients" class="pull-right">  Patients List</a>
    </div>
    <div class="col-md-12">

      <!-- Widget -->
      <div class="widget boxed">
        <!-- Widget head -->
        <div class="widget-head">
          <h4 class="pull-left"><i class="icon-reorder"></i>Add Patient</h4>
          <div class="widget-icons pull-right">
            <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
            <a href="#" class="wclose"><i class="icon-remove"></i></a>
          </div>
          <div class="clearfix"></div>
        </div>             

        <!-- Widget content -->
        <div class="widget-content">
          <div class="padd">
          <div class="center-align">
          	<?php
            	$error = $this->session->userdata('error_message');
            	$validation_error = validation_errors();
    				  $success = $this->session->userdata('success_message');
    				
    				if(!empty($error))
    				{
    					echo '<div class="alert alert-danger">'.$error.'</div>';
    					$this->session->unset_userdata('error_message');
    				}
    				
    				if(!empty($validation_error))
    				{
    					echo '<div class="alert alert-danger">'.$validation_error.'</div>';
    				}
    				
    				if(!empty($success))
    				{
    					echo '<div class="alert alert-success">'.$success.'</div>';
    					$this->session->unset_userdata('success_message');
    				}
			     ?>
          </div>
			<div class="tabbable" style="margin-bottom: 18px;">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#other" data-toggle="tab">Patient</a></li>
              </ul>
              <div class="tab-content" style="padding-bottom: 9px; border-bottom: 1px solid #ddd;">
                <div class="tab-pane active" id="other">
                  
                  <?php echo $this->load->view("patients/other", '', TRUE);?>
                  
                </div>
                
              </div>
            </div>
          </div>
        </div>
        <!-- Widget ends -->

      </div>
    </div>
  </div>