<?php 

//helpers
$this->load->helper('html');
$this->load->helper('url');
$this->load->helper('form');

//css files
$v_id=$this->uri->segment(3);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />

 	<link type="text/css" rel="stylesheet" href="<?php echo base_url("css/interface.css"); ?>" />
    
 	<link type="text/css" rel="stylesheet" href="<?php echo base_url("css/jquery-ui-1.8.18.custom.css"); ?>" />
    
 	<link type="text/css" rel="stylesheet" href="<?php echo base_url("css/bootstrap.min.css"); ?>" />
    
    
 	<link type="text/css" rel="stylesheet" href="<?php echo base_url("css/bootstrap.css"); ?>" />
    <script src="<?php echo base_url("js/script.js"); ?>"> </script>

<title>Pharmacy</title>
</head>
<body onLoad="patient_details(<?php echo $v_id; ?>); prescript(<?php echo $v_id; ?>);">
  <div id="header" class="container">
    	<div id="logo">
			<h1><a href="#">SUMC</a></h1>
			<p><a href="http://www.strathmore.edu">pharmacy</a></p>
		</div>
        
</div>
	<!-- end #header -->
<div class="row-fluid">
    	<div class="span2 navbar-inner">
        
        	    <ul class="nav nav-list">
    				<li class="nav-header">Patients</li>
                    <li><a href='<?php echo site_url('pharmacy/pharmacy_queue')?>'>Pharmacy Queue</a></li>
                    <li><a href='<?php echo site_url('pharmacy/from_reception')?>'>From Reception</a></li>
                    <li><a href='<?php echo site_url('nurse/general_queue/4')?>'>General Queue</a></li>
                    
    				<li class="nav-header">Inventory</li>
                   <li><a href='<?php echo base_url('data/reports/pharmacy')?>' target="_blank">Pharmacy Reports</a></li>
                    <li><a href='<?php echo site_url('stock/inventory')?>'>Inventory</a></li>
                   <li><a href='http://sagana/hms/index.php/administration/add_charges/4' target="_blank">Update Prices</a></li>
                    <li><a href='<?php echo site_url('stock/brands')?>'>Brands</a></li>
                    <li><a href='<?php echo site_url('stock/generics')?>'>Generics</a></li>
                    <li><a href='<?php echo site_url('stock/classes')?>'>Classes</a></li>
                    <li><a href='<?php echo site_url('stock/drug_type')?>'>Types</a></li>
                    <li><a href='<?php echo site_url('stock/container_type')?>'>Containers</a></li>
                    
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
  		<div id="patient_details"> </div>
        
        <div class='navbar-inner'>
		<p style='text-align:center; color:#0e0efe;'>
			Dispense Drugs<br/>
		<?php echo "
			<input type='button' class='btn btn-primary' value='Prescribe' onclick='open_window(1, ".$v_id.")'/>
			
			<input type='button' class='btn btn-primary' value='Load Prescription' onclick='location.reload();'/>
			
	<div id='prescription'> </div>
	</div>";?>

        </div>
</div>
 </div>
				<!-- end wrapper -->
				<div id="footer">
					<p>Copyright &copy; 2012 Sitename.com. All rights reserved. Design by <a href="http://www.freecsstemplates.org">james brian studio</a>.</p>
				</div>
</body>
</html>