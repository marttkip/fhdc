<?php 	
 //helpers
$this->load->helper('html');
$this->load->helper('url');
$this->load->helper('form');

	//connect to database
        $connect = mysql_connect("localhost", "sumc_hms", "Oreo2014#")
                    or die("Unable to connect to MySQL".mysql_error());

        //selecting a database
        mysql_select_db("strathmore_population", $connect)
                    or die("Could not select database".mysql_error());
		
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link type="text/css" rel="stylesheet" href="<?php echo base_url("css/jquery-ui-1.8.18.custom.css"); ?>" />
 	<link type="text/css" rel="stylesheet" href="<?php echo base_url("css/interface.css"); ?>" />
   
    <script type="text/javascript" src="<?php echo base_url("js/script.js");?>"></script>
    <script type="text/javascript" src="<?php echo base_url("js/jquery.ui.widget.js");?>"></script>
    <script type="text/javascript" src="<?php echo base_url("js/jquery.js");?>"></script>
    <script type="text/javascript" src="<?php echo base_url("js/jquery-1.7.1.min.js");?>"></script>
    <script type="text/javascript" src="<?php echo base_url("js/jquery.ui.core.js");?>"></script>
    <script type="text/javascript" src="<?php echo base_url("js/jquery.ui.datepicker.js");?>"></script>
	<script type="text/javascript" charset="utf-8">

			$(function(){
				
				//date picker
				$( "#datepicker" ).datepicker();
				$( "#format" ).change(function() {
					$( "#datepicker" ).datepicker( "option", "dateFormat", $( this ).val() );
				});
			});

	</script>
 	<link type="text/css" rel="stylesheet" href="<?php echo base_url("css/bootstrap.min.css"); ?>" />
<title>SBS Staff</title>

</head>
<body>  
  <div id="header" class="container">
    	<div id="logo">
			<h1><a href="#">SUMC</a></h1>
			<p><a href="http://www.strathmore.edu">Reception</a></p>
		</div>
	</div>
	<!-- end #header -->
<div class="row-fluid">
    	<div class="span2 navbar-inner">
        
        	    <ul class="nav nav-list">
    				<li class="nav-header">Patients</li>
               
                    <li><a href='<?php echo site_url('welcome/patient_registration')?>'>Outsiders</a></li>
                    <li><a href='<?php echo site_url('welcome/staff')?>'>Staff</a></li>
                    <li><a href='<?php  echo site_url('welcome/students')?>'>Students</a></li>
                    <li><a href='<?php  echo site_url('welcome/appointment_list')?>'>Appointment List</a></li>
                    <li><a href='<?php  echo site_url('welcome/visit_list')?>'>Ongoing Visits</a></li>
                    <li><a href='<?php  echo site_url('welcome/visit_history')?>'>Visit History</a></li>
                    <li><a href='<?php echo site_url('welcome/staff_sbs')?>'>SBS Staff</a></li>
                    
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
    				<li><a href='<?php  echo site_url('welcome/logout')?>'>Logout</a></li>
    				<li><a href='#'>Change Password</a></li>
    			</ul>
        </div>

    	<div class="span10">
        <div class='navbar-inner2'><p style='text-align:center; color:#fff;'>Add New SBS Staff</p></div>
<div style="padding:0 3px 2px;font-family:Lucida Sans Unicode, Lucida Grande, sans-serif; monospace;font-size:16px;color:red;-webkit-border-radius:3px;-moz-border-radius:3px; border::cyan; border-radius:10px 10px 10px 10px;"> </div>
<form action="http://sagana/hms/index.php/welcome/staff_sbs" method="post">
<label>Full names </label> <input type="text" name="name" id="name" placeholder="Full names" value=""> 
<label>Date of Birth </label> <input type="text" name="datepicker" id="datepicker" placeholder="Date of Birth" value=""> 
<label>Gender </label> <select name="gender" id="gender"> 
<option value="F"> Female </option>
<option value="M"> Male </option>
</select>
<label>Staff Type</label> <select name="type" id="type"> 
<option value="">----Select Staff Type---</option>
<option value="sbs">SBS</option>
<option value="housekeeping"> HOUSEKEEPING </option>
</select>
<label> Contact</label>  <input type="text" name="contact" id="contact" placeholder="contact" value=""> 
<label>Staff Number </label>  <input type="text" name="staff_number" id="staff_number" placeholder="Staff Number" value=""> 

<input type="submit" value="SAVE SBS STAFF" name="submit" id="submit">
</form>      </div>  </div><!-- end wrapper -->
	
    <?php
	if (isset($_POST['submit'])){
		$name= $_POST['name'];
		$datepicker= $_POST['datepicker'];
		$gender= $_POST['gender'];
		$type= $_POST['type'];
		$contact= $_POST['contact'];
		$staff_number= $_POST['staff_number'];
		
		$sql="INSERT INTO `strathmore_population`.`staff` (`Surname`, `Other_names`, `DOB`, `contact`, `gender`, `Staff_Number`, `staff_system_id`, `sbs`) VALUES ('','$name','$datepicker','$contact','$gender','$staff_number','','$type')";
	
		 $rs = mysql_query($sql)
        or die ("unable to Select ".mysql_error());	
		
			 }	
	
	?>
   			
</body>
</html>