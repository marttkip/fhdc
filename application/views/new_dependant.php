<?php 	
 //helpers
$this->load->helper('html');
$this->load->helper('url');
$this->load->helper('form');



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
<title>Staff Dependants</title>

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
                    <li><a href='<?php  echo site_url('welcome/insurance')?>'>Insurance</a></li>
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

    	<div class="span10"><?php
$primary_key=$this->uri->segment(3);
	$query="SELECT * FROM patients WHERE patient_id =$primary_key";
			$result = mysql_query($query) or die(mysql_error());
		$num_rows=mysql_num_rows($result);
		$strath_no=mysql_result($result, 0, 'strath_no');
		
	//	echo LK.$contact;
			//connecting to staff dbase to get staff_stsem_id
			//connect to database
        $connect = mysql_connect("localhost", "sumc_hms", "Oreo2014#")
                    or die("Unable to connect to MySQL".mysql_error());

        //selecting a database
        mysql_select_db("strathmore_population", $connect)
                    or die("Could not select database".mysql_error());
		
		$sql = "select * from staff where Staff_Number='$strath_no'";
		//echo TT.$sql;
	    $rs = mysql_query($sql)
        or die ("unable to Select ".mysql_error());
		$num_rows=mysql_num_rows($rs);
		$stf_id=mysql_result($rs, 0, 'staff_system_id');
		$title=mysql_result($rs, 0, 'title');
		$Surname=mysql_result($rs, 0, 'Surname');
		$Other_names=mysql_result($rs, 0,'Other_names');
		$contact=mysql_result($rs, 0, 'contact');
		//		echo K.$num_rows;
		
	echo "<table width='auto' border='0' align='center'>
  <tr> <td><a href='http://sagana/hms/index.php/welcome/staff_dependants/$primary_key'><input name='' type='button' value='Back to List'/> </a></td>
  </tr></table><div class='navbar-inner2'><p style='text-align:center; color:#fff;'>Dependants of ".$title."    ".$Surname ."     ".$Other_names.    "  Staff Number: ".$strath_no."</p></div>";
$error="";
$name="";
	$datepicker="";
	$gender="";
	$relation="";
	$occupation="";
if(isset($_POST['dept'])){
	$name=$_POST['name'];
	$datepicker=$_POST['datepicker'];
	$gender=$_POST['gender'];
	$relation=$_POST['relation'];
	$occupation=$_POST['occupation'];
	
	
	if(($datepicker=="")|| ($name=="")|| ($gender=="")){
		$error="Ensure the Date of birth, Fullnames and Gender are Not empty!!!";
		}
		else {
			$sql = "insert into staff_dependants (names, DOB, Gender, occupation, relation ,staff_id) values ('$name','$datepicker','$gender','$relation','$occupation','$stf_id')";
		//echo TT.$sql;
	    $rs = mysql_query($sql)
        or die ("unable to Select ".mysql_error());	
		$name="";
	$datepicker="";
	$gender="";
	$relation="";
	$occupation="";
			}
	
}
?>
<div style="padding:0 3px 2px;font-family:Lucida Sans Unicode, Lucida Grande, sans-serif; monospace;font-size:16px;color:red;-webkit-border-radius:3px;-moz-border-radius:3px; border::cyan; border-radius:10px 10px 10px 10px;"> <?php echo $error;?> </div>
<form action="http://sagana/hms/index.php/welcome/new_staff_dependants/<?php echo $primary_key ?>" method="post">
<label>Full names </label> <input type="text" name="name" id="name" placeholder="Full names" value="<?php echo $name; ?>"> 
<label>Date of Birth </label> <input type="text" name="datepicker" id="datepicker" placeholder="Date of Birth" value="<?php echo $datepicker ?>"> 
<label>Gender </label> <select  name="gender" id="gender"> 
<option value="Female"> Female </option>
<option value="Male"> Male </option>
</select>
<label>Relationship </label>  <input type="text" name="relation" id="relation" placeholder="Relation: spouse, sibling, son" value="<?php echo $relation;?>"> 
<label>Occupation </label>  <input type="text" name="occupation" id="occupation" placeholder="occupation" value="<?php echo $occupation;?>"> 

<input type="submit" value="Save Dependent" name="dept">
</form>      </div>  </div><!-- end wrapper -->
	
   			
</body>
</html>