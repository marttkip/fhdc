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
	<title>Vitals</title>
    <script type="text/javascript" src="<?php echo base_url('js/script.js');?>"></script>
	<script type='text/javascript' src='<?php echo base_url('js/jquery.js');?>'></script>
	<script type="text/javascript" src="<?php echo base_url('js/jquery-1.7.1.min.js');?>"></script>
	<script type='text/javascript' src='<?php echo base_url('js/jquery-ui-1.8.18.custom.min.js');?>'></script>
</head>
<body onLoad="vitals_interface(<?php echo $visit_id;?>)">
  <div id="header" class="container">
    	<div id="logo">
			<h1><a href="#">SUMC</a></h1>
			<p><a href="http://www.strathmore.edu">Vitals</a></p>
		</div>
	</div>
	<!-- end #header -->
<div class="row-fluid">
    	<div class="span2 navbar-inner">
        
        	    <ul class="nav nav-list">
    				<li class="nav-header">Patients</li>
                    <li><a href='<?php echo site_url('nurse/nurse_queue')?>'>Nurse's Queue</a></li>
                    <li><a href='<?php echo site_url('nurse/appointment_list')?>'>Laboratory Queue</a></li>
                    <li><a href='<?php echo site_url('nurse/visit_list')?>'>General Queue</a></li>
                    
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
        	<div id="patient_details"></div>
  			<div id="vitals"></div>
            <div id="procedures"></div>
            <div id="medication"></div>
            <div id="surgeries"></div>
            <div id="patient_vaccine"></div>
            <div id="previous_vitals"></div>
            <div id="history"></div>
            <div id="medical_checkup"></div>
            <div id="nurse_notes"></div>
            
            
    	<table align="center" border="0">
        	<tr>
                        <form class="form-search" action="<?php echo base_url("index.php/nurse/send_to_doctor/".$visit_id)?>"  method="post" >
                            <input type="submit" class="btn btn-large btn-primary" value="Send To Doctor"/>
                             
    					</form>
                        <form class="form-search" action="<?php echo base_url("index.php/nurse/send_to_pharmacy/".$visit_id)?>"  method="post" >
                            <input type="submit" class="btn btn-large btn-primary" value="Send To Pharmacy"/>
                             
    					</form>
                        <form class="form-search" action="<?php echo base_url("index.php/nurse/send_to_labs/".$visit_id)?>"  method="post" >
                           <input type="submit" class="btn btn-large btn-primary" value="Send To Laboratory"/>
                             
    					</form>
                       
                             
                    
            </tr>
        </table>
        </div>
    </div>
 </div>
				<!-- end wrapper -->
				<div id="footer">
					<p>Copyright &copy; 2012 Sitename.com. All rights reserved. Design by <a href="http://www.freecsstemplates.org">james brian studio</a>.</p>
				</div>
</body>
</html>