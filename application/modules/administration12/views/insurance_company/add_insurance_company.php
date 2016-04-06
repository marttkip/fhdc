<div class="row">
    <div class="col-lg-12">
     <a href="<?php echo site_url();?>/administration/all-insurance-company" class="btn btn-primary pull-right">Back to Insurance Company</a>
    </div>
</div>
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
            <div class="center-align">
            <!-- Adding Errors -->
            <?php
            if(isset($error)){
                echo '<div class="alert alert-danger"> Oh snap! Change a few things up and try submitting again. </div>';
            }
            
            $validation_errors = validation_errors();
            
            if(!empty($validation_errors))
            {
                echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
            }
            ?>
            
            <?php echo form_open(site_url().'/administration/all-insurance-company', array("class" => "form-horizontal", "role" => "form"));?>
             <div class="row">
                <div class="row ">
                    <div class="col-lg-12">
                        <!-- post category -->
                        <!-- First Name -->
                        <div class="form-group">
                            <label class="col-lg-4 control-label">Insurance Company Name</label>
                            <div class="col-lg-6">
                            	<input type="text" class="form-control" name="insurance_company_name" placeholder="Insurance Company Name" value="<?php echo set_value('insurance_company_name');?>">
                            </div>
                        </div>
                        
                    </div>
                    
                      
                    </div>
                </div>
                <div class="row">
                    <div class="form-actions center-align">
                        <button class="submit btn btn-success" type="submit">
                            Add a new Insurance Company
                        </button>
                    </div>
                </div>
                        <br />
            <?php echo form_close();?>
		</div>
    </div>
</div>
</div>
