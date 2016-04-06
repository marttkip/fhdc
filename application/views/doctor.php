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
<style type='text/css'>


</style>
<title>Doctor</title>
</head>
<body>
  <div id="header" class="container">
    	<div id="logo">
			<h1><a href="#">SUMC</a></h1>
			<p><a href="http://www.strathmore.edu">doctor</a></p>
		</div>
</div>
	<!-- end #header -->
<div class="row-fluid">
    	<div class="span2 navbar-inner">
        
        	    <ul class="nav nav-list">
    				<li class="nav-header">Patients</li>
                    <li><a href='<?php echo site_url('doctor/doctor_queue')?>'>Doctor's Queue</a></li>
                    <li><a href='<?php echo site_url('doctor/from_lab_queue')?>'>Laboratory Queue</a></li>
                    <li><a href='<?php echo site_url('nurse/general_queue/2')?>'>General Queue</a></li>
                       <li><a href='<?php echo site_url('doctor/appointment_list')?>'>Appointment List</a></li>
    				<li class="nav-header">My Account</li>
    				<li><a href='<?php echo site_url('welcome/control_panel/'.$_SESSION['personnel_id'])?>'>Control Panel</a></li>
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