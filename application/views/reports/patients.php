 
<h3 class="page_title"> <?php echo $_SESSION['page_title'];?>  </h3>
<div class="row-fluid">
    <div class="span3">
        <a href="#" class="thumbnail">
            <h4>Staff</h4>
            <?php echo $staff;?>
        </a>
    </div>
    <div class="span3">
        <a href="#" class="thumbnail">
            <h4>Students</h4>
            <?php echo $student;?>
        </a>
    </div>
    <div class="span3">
        <a href="#" class="thumbnail">
            <h4>Insurance</h4>
            <?php echo $insurance;?>
        </a>
    </div>
    <div class="span3">
        <a href="#" class="thumbnail">
            <h4>Cash</h4>
            <?php echo $cash;?>
        </a>
    </div>
</div>
    
<div class="fuelux">

    <!-- STRETCH DATAGRID WITH MULTISELECT -->
    <div style="width:100%;">
        <table id="MySelectStretchGrid" class="table table-bordered datagrid">

            <thead>
            <tr>
                <th>
                    <span class="datagrid-header-title">Patients</span>

                    <div class="datagrid-header-left">

                        <!-- DATEPICKER -->
                        <div>
                        	<?php echo form_open("reports/set_visit_date");?>
                            <div class="datepicker dropdown" id="datepicker1">
                                <div class="input-append">
                                    <div class="dropdown-menu"></div>
                                    <input type="text" class="span2" value="" name="visit_date" data-toggle="dropdown">
                                    <button type="button" class="btn" data-toggle="dropdown"><i class="icon-calendar"></i></button>
                                    <button type="submit" class="btn">Set Date</button>
                                </div>
                            </div>
                            <?php echo form_close();?>
                        </div>
                    </div>
                    
                    <div class="datagrid-header-right">
                        <div class="input-append search datagrid-search">
                            <input type="text" class="input-medium" placeholder="Search">
                            <button class="btn"><i class="icon-search"></i></button>
                        </div>
                    	<a href="<?php echo base_url()."index.php/reports/export_patients";?>" class="btn btn-success">Export</a>
                    	<a href="<?php echo base_url()."index.php/reports/print_patients";?>" target="_blank" class="btn btn-danger">Print</a>
                        <!--<div class="select filter" data-resize="auto">
                            <button data-toggle="dropdown" class="btn dropdown-toggle">
                                <span class="dropdown-label"></span>
                                <span class="caret"></span>
                            </button>
                             <ul class="dropdown-menu">
                                <li data-value="all" data-selected="true"><a href="#">All</a></li>
                                <li data-value="lt5m"><a href="#">Population &lt; 5M</a></li>
                                <li data-value="gte5m"><a href="#">Population &gt;= 5M</a></li>
                            </ul> 
                        </div>-->
                    </div>
                </th>
            </tr>
            </thead>

            <tfoot>
            <tr>
                <th>
                    <div class="datagrid-footer-left" style="display:none;">
                        <div class="grid-controls">
                            <span>
                                <span class="grid-start"></span> -
                                <span class="grid-end"></span> of
                                <span class="grid-count"></span>
                            </span>
                            <div class="select grid-pagesize" data-resize="auto">
                                <button data-toggle="dropdown" class="btn dropdown-toggle">
                                    <span class="dropdown-label"></span>
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li data-value="5" data-selected="true"><a href="#">5</a></li>
                                    <li data-value="10"><a href="#">10</a></li>
                                    <li data-value="20"><a href="#">20</a></li>
                                    <li data-value="50"><a href="#">50</a></li>
                                    <li data-value="100"><a href="#">100</a></li>
                                </ul>
                            </div>
                            <span>Per Page</span>
                        </div>
                    </div>
                    <div class="datagrid-footer-right" style="display:none;">
                        <div class="grid-pager">
                            <button type="button" class="btn grid-prevpage"><i class="icon-chevron-left"></i></button>
                            <span>Page</span>

                            <div class="input-append dropdown combobox">
                                <input class="span1" type="text" style="height:30px">
                                <button class="btn" data-toggle="dropdown"><i class="caret"></i></button>
                                <ul class="dropdown-menu"></ul>
                            </div>
                            <span>of <span class="grid-pages"></span></span>
                            <button type="button" class="btn grid-nextpage"><i class="icon-chevron-right"></i></button>
                        </div>
                    </div>
                </th>
            </tr>
            </tfoot>
        </table>
    </div>

    
</div>
        