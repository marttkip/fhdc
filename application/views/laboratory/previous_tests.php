<?php 

//helpers
$this->load->helper('html');
$this->load->helper('url');
$this->load->helper('form');

//css files
echo link_tag('css/bootstrap.css');
echo link_tag('css/bootstrap.min.css');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
 <link type="text/css" rel="stylesheet" href="<?php echo base_url("css/interface.css"); ?>" />
	<title>Test</title>
    <script type="text/javascript" src="<?php echo base_url('js/script.js');?>"></script>
	<script type='text/javascript' src='<?php echo base_url('js/jquery.js');?>'></script>
	<script type="text/javascript" src="<?php echo base_url('js/jquery-1.7.1.min.js');?>"></script>
	<script type='text/javascript' src='<?php echo base_url('js/jquery-ui-1.8.18.custom.min.js');?>'></script>
</head>
<body onLoad="get_previous_tests(0)">
<div id="header" class="container">
    	<div id="logo">
			<h1><a href="#">SUMC</a></h1>
			<p><a href="http://www.strathmore.edu">Previous Tests</a></p>
		</div>
	</div>
	<!-- end #header -->
<div class="row-fluid">
    	<div class="span2 navbar-inner">
        
        	    <ul class="nav nav-list">
    				<li class="nav-header">Patients</li>
                  <li><a href='<?php echo site_url('laboratory/lab_queue')?>'>Laboratory Queue</a></li>
                    <li><a href='<?php echo site_url('nurse/general_queue/3')?>'>General Queue</a></li>
                    <li><a href='<?php echo site_url('laboratory/previous_tests')?>'>Test History</a></li> 
                      	<li><a href='<?php echo base_url('data/reports/lab.php')?>' target="_blank">Lab Reports</a></li>
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
  			<div id="previous_test_results"></div>
        </div>
    </div>
 </div>
				<!-- end wrapper -->
				<div id="footer">
					<p>Copyright &copy; 2012 Sitename.com. All rights reserved. Design by <a href="http://www.freecsstemplates.org">james brian studio</a>.</p>
				</div>
</body>
</html>-i