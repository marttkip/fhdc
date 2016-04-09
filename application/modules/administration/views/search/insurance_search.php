<!-- Widget -->
<div class="widget boxed">
    <!-- Widget head -->
    <div class="widget-head">
        <h4 class="pull-left"><i class="icon-reorder"></i>Search Service Charge</h4>
        <div class="widget-icons pull-right">
            <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
            <a href="#" class="wclose"><i class="icon-remove"></i></a>
        </div>
    
        <div class="clearfix"></div>
    
    </div>             
    
    <!-- Widget content -->
   <div class="widget-content">
        <div class="padd">
            
            <?php echo form_open("administration/reports/search_insurance/".$module."", array("class" => "form-horizontal"));
			?>
              <div class="row">
                    <div class="col-md-4">
                          <div class="form-group">
                              <label class="col-lg-4 control-label">Insurance Company Name</label>
                             <div class="col-lg-8">
                            <select class="form-control" name="insurance_company_id">
                            	<option value="">---Select Insurance Company---</option>
                                <?php
                                    if(count($insurance_names) > 0){
                                        foreach($insurance_names as $row):
                                            $insurance_name = $row->insurance_company_name;
                                            $insurance_company_id= $row->insurance_company_id;
                                                ?><option value="<?php echo $insurance_company_id; ?>" ><?php echo $insurance_name ?></option>
                                        <?php	
                                        endforeach;
                                    }
                                ?>
                            </select>
                            </div>
                         </div>
                         </div>
                         <div class="row">
                         <div class="col-md-4">
                              <div class="form-group">
                              <label class="col-lg-4 control-label">Period</label>
                             <div class="col-lg-8">
                            <select class="form-control" name="debtors_period_id">
                            	<option value="">---Select Debtors Period---</option>
                                <?php
                                if(count($debtor_period) > 0){
                                        foreach($debtor_period as $key):
                                            $debtors_period_id = $key->debtors_period_id;
                                            $debtors_period_duration= $key->debtors_period_duration;
                                                ?><option value="<?php echo $debtors_period_id; ?>" ><?php echo $debtors_period_duration ?></option>
                                        <?php	
                                        endforeach;
                                    }
                                ?>
                                </select>
                          </div>
                   
                    </div>
              
            
            <div class="center-align">
                <button type="submit" class="btn btn-info btn-lg">Search</button>
            </div>
            <?php
            echo form_close();
            ?>
        </div>
    </div>
</div>