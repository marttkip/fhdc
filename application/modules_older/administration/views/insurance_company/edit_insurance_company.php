<div class="row">
    <div class="col-md-12">

      <!-- Widget -->
      <div class="widget boxed">
        <!-- Widget head -->
        <div class="widget-head">

          <h4 class="pull-left"><i class="icon-reorder"></i>Add insurance company </h4>
          <div class="widget-icons pull-right">
         
            <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
            <a href="#" class="wclose"><i class="icon-remove"></i></a>
          </div>
          <div class="clearfix"></div>
        </div>             

        <!-- Widget content -->
        <div class="widget-content">
          <div class="padd">
            <!-- Adding Errors -->
            <?php
            if(isset($error)){
                echo '<div class="alert alert-danger"> Oh snap! Change a few things up and try submitting again. </div>';
            }
			$insurance_company_name = $insurance_company[0]->insurance_company_name;
			$insurance_company_status = $insurance_company[0]->insurance_company_status;
			$insurance_company_id = $insurance_company[0]->insurance_company_id;
            
            $validation_errors = validation_errors();
            
            if(!empty($validation_errors))
            {
                echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
				$insurance_company_name = set_value('insurance_company_name');
				$insurance_company_status = set_value('insurance_company_status');
            }
            
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
            ?>
            
            <?php echo form_open('administration/edit-insurance-company/'.$insurance_company_id, array("class" => "form-horizontal", "role" => "form"));?>
            <!-- First Name -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Insurance Company  Name</label>
                <div class="col-lg-4">
                	<input type="text" class="form-control" name="insurance_company_name" placeholder="Insurance Company Name" value="<?php echo $insurance_company_name;?>">
                </div>
            </div>
            
            <div class="form-actions center-align">
                <button class="submit btn btn-primary" type="submit">
                    Edit Insurance Company
                </button>
            </div>
            <br />
            <?php echo form_close();?>
		</div>
    </div>
</div>
</div>