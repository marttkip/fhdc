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
    <script src="<?php echo base_url("js/script.js"); ?>"> </script>  
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
		
			//connecting to dependants in staff_population debase to get specifics	
	//	echo $query;
		$query2="SELECT * FROM staff_dependants WHERE staff_id =$stf_id";
		//echo $query2;
		//	echo TT.$query2;
		$result2= mysql_query($query2)   or die ("unable to Select ".mysql_error());
		$row2 = mysql_num_rows($result2);
		//echo R2.$row2;
		
		        $connect = mysql_connect("localhost", "sumc_hms", "Oreo2014#")
                    or die("Unable to connect to MySQL".mysql_error());

        //selecting a database
        mysql_select_db("sumc", $connect)
                    or die("Could not select database".mysql_error());
		
	echo "<table width='auto' border='0' align='center'>
  <tr> <td><a href='http://sagana/hms/index.php/welcome/new_staff_dependants/$primary_key'><input name='' type='button' value='Add New Dependent'/> </a></td>
  </tr></table>
<div class='navbar-inner2'><p style='text-align:center; color:#fff;'>Dependants of ".$title."    ".$Surname ."     ".$Other_names.    "  Staff Number: ".$strath_no."</p></div>
	
	
	<table class='table table-striped table-hover table-condensed'>
		 <tr>
		 	<th>Patient Names</th>
			<th>Occuptaion</th>
			<th>Relation</th>
			<th>Contact</th>
			
	</tr>";
for($a=0; $a<$row2; $a++){
			$names=mysql_result($result2, $a, 'names');
			$occupation=mysql_result($result2, $a, 'occupation');
			$relation=mysql_result($result2, $a, 'relation');
			$staff_id=mysql_result($result2, $a, 'staff_id');	
			$staff_dependants_id=mysql_result($result2, $a, 'staff_dependants_id');
			
						
	echo "<tr> <td>".$names."</td>  <td>".$occupation."</td>  <td>".$relation."</td>  <td>".$contact."</td>   <td>".$contact."</td>  <td><input name='prescribe' type='submit' Value='To Lab' onclick='check_patient(1,$staff_dependants_id,$strath_no);'> <input name='prescribe' type='button' value='To Pharmacy' onclick='check_patient(2,$staff_dependants_id,$strath_no);'> <input name='prescribe' type='button' value='To Nurse' onclick='check_patient(3,$staff_dependants_id,$strath_no);'></td>  </tr>";
	
	
	
	}
	

?>      </div>  </div><!-- end wrapper -->
				
</body>
</html>