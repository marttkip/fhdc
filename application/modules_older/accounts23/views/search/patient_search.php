<!-- Widget -->
<div class="widget boxed">
    <!-- Widget head -->
    <div class="widget-head">
        <h4 class="pull-left"><i class="icon-reorder"></i>Search Visits</h4>
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
            echo form_open("accounts/search_visits/".$type_links, array("class" => "form-horizontal"));
            ?>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Visit Type: </label>
                        
                        <div class="col-lg-8">
                            <select class="form-control" name="visit_type_id">
                                <option value="">---Select Visit Type---</option>
                                <?php
                                    if(count($type) > 0){
                                        foreach($type as $row):
                                            $type_name = $row->visit_type_name;
                                            $type_id= $row->visit_type_id;
                                                ?><option value="<?php echo $type_id; ?>" ><?php echo $type_name ?></option>
                                        <?php   
                                        endforeach;
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-4 control-label">Doctor: </label>
                        
                        <div class="col-lg-8">
                            <select class="form-control" name="personnel_id">
                                <option value="">---Select Doctor---</option>
                                <?php
                                    if(count($doctors) > 0){
                                        foreach($doctors as $row):
                                            $fname = $row->personnel_fname;
                                            $onames = $row->personnel_onames;
                                            $personnel_id = $row->personnel_id;
                                            echo "<option value=".$personnel_id.">".$onames." ".$fname."</option>";
                                        endforeach;
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                   
                    
                </div>
                
                <div class="col-md-4">
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Visit Date: </label>
                        
                        <div class="col-lg-8">
                            <div id="datetimepicker1" class="input-append">
                                <input data-format="yyyy-MM-dd" class="form-control" type="text" name="visit_date" placeholder="Visit Date">
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
                        <label class="col-lg-4 control-label">Surname: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="surname" placeholder="Surname">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Other Names: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="othernames" placeholder="Other Names">
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