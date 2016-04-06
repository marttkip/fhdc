<div class="row">
    <div class="col-md-4">
        <!-- Widget -->
        <div class="widget boxed">
            <!-- Widget head -->
            <div class="widget-head">
                <h4 class="pull-left"><i class="icon-reorder"></i>Select debtor</h4>
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
                    echo form_open("administration/reports/select_debtor", array("class" => "form-horizontal"));
                    ?>
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Debtor: </label>
                        
                        <div class="col-lg-8">
                            <select class="form-control" name="insurance_company_id">
                                <?php
                                    if($insurance_company_query->num_rows() > 0)
                                    {
                                        foreach($insurance_company_query->result() as $row):
                                            $insurance_company_name2 = $row->insurance_company_name;
                                            $insurance_company_id2= $row->insurance_company_id;
                                            ?><option value="<?php echo $insurance_company_id2; ?>" ><?php echo $insurance_company_name2; ?></option><?php	
                                        endforeach;
                                    }
                                ?>
                            </select>
                        </div>
                    </div>        
                    
                    <div class="center-align">
                        <button type="submit" class="btn btn-info btn-lg">Search</button>
                    </div>

                    <?php echo form_close();?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <!-- Widget -->
        <div class="widget boxed">
            <!-- Widget head -->
            <div class="widget-head">
                <h4 class="pull-left"><i class="icon-reorder"></i>Search debtor</h4>
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
                    echo form_open("administration/reports/search_debtors/".$insurance_company_id, array("class" => "form-horizontal"));
                    ?>
                    <div class="row">
                    	<div class="col-md-4">
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Batch no.: </label>
                                
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="batch_no" placeholder="Batch no.">
                                </div>
                            </div>
                    	</div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Batch date from: </label>
                                
                                <div class="col-lg-8">
                                    <div id="datetimepicker1" class="input-append">
                                        <input data-format="yyyy-MM-dd" class="form-control" type="text" name="batch_date_from" placeholder="Batch date from">
                                        <span class="add-on" style="cursor:pointer;">
                                            &nbsp;<i data-time-icon="icon-time" data-date-icon="icon-calendar">
                                            </i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                    	</div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Batch date to: </label>
                                
                                <div class="col-lg-8">
                                    <div id="datetimepicker_other_patient" class="input-append">
                                        <input data-format="yyyy-MM-dd" class="form-control" type="text" name="batch_date_to" placeholder="Batch date to">
                                        <span class="add-on" style="cursor:pointer;">
                                            &nbsp;<i data-time-icon="icon-time" data-date-icon="icon-calendar">
                                            </i>
                                        </span>
                                    </div>
                                </div>
                            </div>
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
    </div>
</div>