<?php 

//helpers
$this->load->helper('html');
$this->load->helper('url');
$this->load->helper('form');

//css files

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
 
<?php 
foreach($css_files as $file): ?>
    <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
 
<?php endforeach; ?>
<?php foreach($js_files as $file): ?>
 
    <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
 	<link type="text/css" rel="stylesheet" href="<?php echo base_url("css/interface.css"); ?>" />
<link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
<title>Administration</title>
</head>
<body>
  <div id="header" class="container">
    	<div id="logo">
			<h1><a href="#">SUMC</a></h1>
			<p><a href="http://www.strathmore.edu">administration</a></p>
		</div>
</div>
	<!-- end #header -->
<div class="row-fluid">
    	<div class="span2 navbar-inner">
        
        	    <ul class="nav nav-list">
               	  	<li><a href='<?php echo site_url('administration')?>'>Dashboard</a></li>
    				<li class="nav-header">Administration</li>
               	  	<li><a href='<?php echo site_url('administration/personnel')?>'>Personnel</a></li>
                    
               	  	<li><a href='<?php echo site_url('administration/services')?>'>Services</a></li>
               	  	<li><a href='<?php echo site_url('administration/patient_type')?>'>Patient Type</a></li>
                    <li><a href='<?php echo site_url('administration/add_credit')?>'>Set Patient Allowance</a></li>
                    <li><a href='<?php echo site_url('administration/supportstaff')?>'>Strathmore Staff</a></li>
               	  	<!--<li><a href='<?php echo site_url('administration/consultation_types')?>'>Consultation Types</a></li>
               	  	<li><a href='<?php echo site_url('administration/procedure_charges')?>'>Procedure Charges</a></li>-->
                    <li><a href='<?php echo site_url('administration/companies')?>'>Company</a></li>
                 	 <li><a href='<?php echo site_url('administration/insurance_company')?>'>Insurance Company</a></li>
    				<li class="nav-header">Reports</li>
               	  	<!--<li><a href='<?php echo site_url('administration/personnel')?>'>Accounts</a></li>
               	  	<li><a href='<?php echo site_url('administration/consultation_charges')?>'>Visits</a></li>-->
               	  	<li><a href='<?php echo base_url('data/reports/reports.php')?>' target="_blank">Accounts</a></li>
               	  	<li><a href='<?php echo base_url('data/reports/cash_reports.php')?>' target="_blank">Cash Reports</a></li>
               	  	<!--<li><a href='<?php echo base_url('data/reports/creditors.php')?>' target="_blank">Creditors</a></li>-->
               	  	<li><a href='<?php echo base_url('data/reports/debtors.php')?>' target="_blank">Debtors</a></li>
               	  	<li><a href='<?php echo base_url('data/reports/expenses.php')?>' target="_blank">Expenses</a></li>
               	  	<li><a href='<?php echo base_url('index.php/reports/patient_reports')?>'>Patients</a></li>
               	  	<li><a href='<?php echo base_url('data/reports/summary.php')?>' target="_blank">Summary</a></li>
                  
    				<li class="nav-header">Logs</li>
               	  	<li><a href='<?php echo site_url('administration/personnel')?>'>Login Sessions</a></li>
               	  	<li><a href='<?php echo site_url('administration/consultation_charges')?>'>Usage</a></li>
                  
    				<li class="nav-header">Export</li>
               	  	<li><a href='<?php echo base_url('/data/export/invoice.php')?>' target="_blank">Invoices</a></li>
                    
    				<li class="nav-header">My Account</li>
    			       <?php 
					
				//	echo 'JJ'.$_SESSION['personnel_id'];
					if (!empty($_SESSION['personnel_id'])){
					?>	
						<li><a href='<?php echo site_url('welcome/control_panel/'.$_SESSION['personnel_id'])?>'>Control Panel</a></li>	
					<?php	}
					else {
						?>	
					<li><a href='<?php echo site_url('')?>'>Control Panel</a></li>
					<?php
						}
					
					?>
    				<li><a href='<?php echo site_url('welcome/logout')?>'>Logout</a></li>
    				<li><a href='#'>Change Password</a></li>
    			</ul>
        </div>
    	<div class="span10">
  			<?php echo $output; ?>
        </div>
</div>
 </div>
				<!-- end wrapper -->
				<div id="footer">
					<p>Copyright &copy; 2012 Sitename.com. All rights reserved. Design by <a href="http://www.freecsstemplates.org">james brian studio</a>.</p>
				</div>
</body>
</html>
