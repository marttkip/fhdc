<?php 
$visit_id=$this->uri->segment(3);

//helpers
$this->load->helper('html');
$this->load->helper('url');
$this->load->helper('form');

//css files
echo link_tag('css/bootstrap.css');
echo link_tag('css/bootstrap.min.css');
$Medicine_allergies="";$chest_trouble="";$heart_problems="";$diabetic="";$epileptic="";$rheumatic_fever="";$elongated_bleeding="";$jaundice="";$hepatitis="";$asthma="";$eczema="";$cancer=""; $treatment=""; $prior_treatment=""; $alcohol="";	$smoke="";

class Database{

    private $connect;

    function  __construct() {
         //connect to database
        $this->connect=mysql_connect("localhost", "sumc_hms", "Oreo2014#")
                    or die("Unable to connect to MySQL".mysql_error());

        //selecting a database
        $selected = mysql_select_db("sumc", $this->connect)
                    or die("Could not select database".mysql_error());
    }

    function insert($sqlstatement){

        mysql_query($sqlstatement)
        or die ("unable to save ".mysql_error());

          mysql_close($this->connect);
    }

    function select($sql){

        $rs = mysql_query($sql)
        or die ("unable to Select ".mysql_error());

        return $rs;
    }
}
class dental_vitals{
function save_dental_vital($visit_id,$visit_major_reason,$serious_illness,$serious_illness_xplain,$treatment,$treatment_hospital,$treatment_doctor,$Food_allergies,$Regular_treatment,$Recent_medication,$Medicine_allergies,$chest_trouble,$heart_problems,$diabetic,$epileptic,$rheumatic_fever,$elongated_bleeding,$jaundice,$hepatitis,$asthma,$eczema,$cancer,$women_pregnant,$pregnancy_month,$additional_infor,$smoke,$alcohol,$prior_treatment){
	$sql="INSERT INTO `sumc`.`dental_vitals` (`visit_id`, `visit_major_reason`, `serious_illness`, `serious_illness_xplain`, `treatment`, `treatment_hospital`, `treatment_doctor`, `Food_allergies`, `Regular_treatment`, `Recent_medication`, `Medicine_allergies`, `chest_trouble`, `heart_problems`, `diabetic`, `epileptic`, `rheumatic_fever`, `elongated_bleeding`,`jaundice`, `hepatitis`, `asthma`, `eczema`, `cancer`, `women_pregnant`, `pregnancy_month`, `additional_infor`,`prior_treatment`,`smoke`,`alcohol`) VALUES ('$visit_id','$visit_major_reason','$serious_illness','$serious_illness_xplain','$treatment','$treatment_hospital','$treatment_doctor','$Food_allergies','$Regular_treatment','$Recent_medication','$Medicine_allergies','$chest_trouble','$heart_problems','$diabetic','$epileptic','$rheumatic_fever','$elongated_bleeding','$jaundice','$hepatitis','$asthma','$eczema','$cancer','$women_pregnant','$pregnancy_month','$additional_infor','$prior_treatment','$smoke','$alcohol')";
	//echo $sql;
	$save= new Database;
	$save->insert($sql);
}

function select_current_vitals($visit_id){
	$sql= "select * from dental_vitals where visit_id='$visit_id'";
	$get= new Database;
	$rs= $get->select($sql);
	return $rs;	
	}
function update_vitals($visit_id,$visit_major_reason,$serious_illness,$serious_illness_xplain,$treatment,$treatment_hospital,$treatment_doctor,$Food_allergies,$Regular_treatment,$Recent_medication,$Medicine_allergies,$chest_trouble,$heart_problems,$diabetic,$epileptic,$rheumatic_fever,$elongated_bleeding,$jaundice,$hepatitis,$asthma,$eczema,$cancer,$women_pregnant,$pregnancy_month,$additional_infor,$dental_vitals_id,$smoke,$alcohol,$prior_treatment){
	
$sql="UPDATE `sumc`.`dental_vitals` SET `visit_major_reason` = '$visit_major_reason',`serious_illness` = '$serious_illness',
`serious_illness_xplain` = '$serious_illness_xplain',`treatment` = '$treatment',`treatment_hospital` = '$treatment_hospital',`treatment_doctor` = '$treatment_doctor',
`Food_allergies` = '$Food_allergies',`Regular_treatment` = '$Regular_treatment',`Recent_medication` = '$Recent_medication',`Medicine_allergies` = '$Recent_medication',`chest_trouble` = '$chest_trouble',`heart_problems` = '$heart_problems',`diabetic` = '$diabetic',`epileptic` = 'NO',`rheumatic_fever` = '$rheumatic_fever',`elongated_bleeding` = '$elongated_bleeding',`jaundice` = '$jaundice',`hepatitis` = '$hepatitis',`asthma` = '$asthma',`eczema` = '$eczema',`cancer` = '$cancer',`women_pregnant` = '$women_pregnant',`pregnancy_month` = '$pregnancy_month',`additional_infor` = '$additional_infor',`smoke` = '$smoke',`alcohol` = '$alcohol',`prior_treatment` = '$prior_treatment' WHERE `dental_vitals`.`dental_vitals_id` ='$dental_vitals_id'";

$save= new Database;
	$save->insert($sql);
}
function update_visit($visit_id){
	
$sql="UPDATE `sumc`.`visit` SET `dental_visit` = '1', nurse_visit=1 WHERE `visit`.`visit_id` =$visit_id";
$save= new Database;
	$save->insert($sql);
}
}
$prior_treatment="";
	$alcohol="";
	$smoke="";
if (isset($_POST['submit'])){


	$visit_major_reason= $_POST['reason'];
	//$treatment= $_POST['treatment'];
	$treatment_hospital= $_POST['hospital'];
	$treatment_doctor=$_POST['doctor'];
	$Food_allergies=$_POST['food_allergies'];
	$Regular_treatment=$_POST['regular_treatment'];
	$Recent_medication=$_POST['medication_description'];
	$Medicine_allergies=$_POST['medicine_allergies'];
	
	$prior_treatment=$_POST['prior_treatment'];
	$alcohol=$_POST['alcohol'];
	$smoke=$_POST['smoke'];

$women_pregnant=$_POST['preg'];
$pregnancy_month=$_POST['months'];
$serious_illness=$_POST['illness'];
$serious_illness_xplain=$_POST['illness_exp'];
$additional_infor=$_POST['additional'];

$save= new dental_vitals;
$save->save_dental_vital($visit_id,$visit_major_reason,$serious_illness,$serious_illness_xplain,$treatment,$treatment_hospital,$treatment_doctor,$Food_allergies,$Regular_treatment,$Recent_medication,$Medicine_allergies,$chest_trouble,$heart_problems,$diabetic,$epileptic,$rheumatic_fever,$elongated_bleeding,$jaundice,$hepatitis,$asthma,$eczema,$cancer,$women_pregnant,$pregnancy_month,$additional_infor,$smoke,$alcohol,$prior_treatment);	
	}
	if (isset($_POST['submit1'])){


	$visit_major_reason= $_POST['reason'];
	//$treatment= $_POST['treatment'];
	$treatment_hospital= $_POST['hospital'];
	$treatment_doctor=$_POST['doctor'];
	$Food_allergies=$_POST['food_allergies'];
	$Regular_treatment=$_POST['regular_treatment'];
	$Recent_medication=$_POST['medication_description'];
	$Medicine_allergies=$_POST['medicine_allergies'];
	$prior_treatment=$_POST['prior_treatment'];
	$alcohol=$_POST['alcohol'];
	$smoke=$_POST['smoke'];

	
$women_pregnant=$_POST['preg'];
$pregnancy_month=$_POST['months'];
$serious_illness=$_POST['illness'];
$serious_illness_xplain=$_POST['illness_exp'];
$additional_infor=$_POST['additional'];

$save= new dental_vitals;
$save->save_dental_vital($visit_id,$visit_major_reason,$serious_illness,$serious_illness_xplain,$treatment,$treatment_hospital,$treatment_doctor,$Food_allergies,$Regular_treatment,$Recent_medication,$Medicine_allergies,$chest_trouble,$heart_problems,$diabetic,$epileptic,$rheumatic_fever,$elongated_bleeding,$jaundice,$hepatitis,$asthma,$eczema,$cancer,$women_pregnant,$pregnancy_month,$additional_infor,$smoke,$alcohol,$prior_treatment);	

$save1= new dental_vitals;
$save1->update_visit($visit_id);
?>
<script>
window.location.href="<?php echo site_url('nurse/nurse_queue'); ?>";
</script>
<?php
	}
	
$get= new dental_vitals;
$rs=$get->select_current_vitals($visit_id);
$num_rows= mysql_num_rows($rs);

//echo $visit_id;
//echo $num_rows;
if($num_rows>0){
$dental_vitals_id=mysql_result($rs, 0, 'dental_vitals_id');
//echo TT.$dental_vitals_id;
	$visit_major_reason=mysql_result($rs, 0, 'visit_major_reason');
	$serious_illness =mysql_result($rs, 0, 'serious_illness');
	$serious_illness_xplain=mysql_result($rs, 0, 'serious_illness_xplain');
	$treatment=mysql_result($rs, 0, 'treatment');
	$treatment_hospital=mysql_result($rs, 0, 'treatment_hospital');
	$treatment_doctor =mysql_result($rs, 0, 'treatment_doctor');
	$Food_allergies =mysql_result($rs, 0, 'Food_allergies');
	$Regular_treatment =mysql_result($rs, 0, 'Regular_treatment');
	$Recent_medication=mysql_result($rs, 0, 'Recent_medication');
	$Medicine_allergies =mysql_result($rs, 0, 'Medicine_allergies');
	$chest_trouble =mysql_result($rs, 0, 'chest_trouble');
	$heart_problems =mysql_result($rs, 0, 'heart_problems');
	$diabetic =mysql_result($rs, 0, 'diabetic');
	$epileptic =mysql_result($rs, 0, 'epileptic');
	$rheumatic_fever=mysql_result($rs, 0, 'rheumatic_fever');
	$elongated_bleeding =mysql_result($rs, 0, 'elongated_bleeding');
	$explain_bleeding =mysql_result($rs, 0, 'explain_bleeding');
	$jaundice =mysql_result($rs, 0, 'jaundice');
	$hepatitis =mysql_result($rs, 0, 'hepatitis');
	$asthma =mysql_result($rs, 0, 'asthma');
	$eczema=mysql_result($rs, 0, 'eczema');
	$cancer=mysql_result($rs, 0, 'cancer');
	$women_pregnant=mysql_result($rs, 0, 'women_pregnant');
	$pregnancy_month=mysql_result($rs, 0, 'pregnancy_month');
	$additional_infor=mysql_result($rs, 0, 'additional_infor');
		$prior_treatment=mysql_result($rs, 0, 'prior_treatment');
	$alcohol=mysql_result($rs, 0, 'alcohol');
	$smoke=mysql_result($rs, 0, 'smoke');
} 
if (isset($_POST['update'])){


	$visit_major_reason= $_POST['reason'];
	$treatment_hospital= $_POST['hospital'];
	$treatment_doctor=$_POST['doctor'];
	$Food_allergies=$_POST['food_allergies'];
	$Regular_treatment=$_POST['regular_treatment'];
	$Recent_medication=$_POST['medication_description'];
	$Medicine_allergies=$_POST['medicine_allergies'];
	$prior_treatment=$_POST['prior_treatment'];
	$alcohol=$_POST['alcohol'];
	$smoke=$_POST['smoke'];
$women_pregnant=$_POST['preg'];
$pregnancy_month=$_POST['months'];
$serious_illness=$_POST['illness'];
$serious_illness_xplain=$_POST['illness_exp'];
$additional_infor=$_POST['additional'];


$save= new dental_vitals;
$save->update_vitals($visit_id,$visit_major_reason,$serious_illness,$serious_illness_xplain,$treatment,$treatment_hospital,$treatment_doctor,$Food_allergies,$Regular_treatment,$Recent_medication,$Medicine_allergies,$chest_trouble,$heart_problems,$diabetic,$epileptic,$rheumatic_fever,$elongated_bleeding,$jaundice,$hepatitis,$asthma,$eczema,$cancer,$women_pregnant,$pregnancy_month,$additional_infor,$dental_vitals_id,$smoke,$alcohol,$prior_treatment);	
	}
if (isset($_POST['update1'])){


	$visit_major_reason= $_POST['reason'];
	$treatment= $_POST['treatment'];
	$treatment_hospital= $_POST['hospital'];
	$treatment_doctor=$_POST['doctor'];
	$Food_allergies=$_POST['food_allergies'];
	$Regular_treatment=$_POST['regular_treatment'];
	$Recent_medication=$_POST['medication_description'];
	$Medicine_allergies=$_POST['medicine_allergies'];
		$prior_treatment=$_POST['prior_treatment'];
	$alcohol=$_POST['alcohol'];
	$smoke=$_POST['smoke'];
$women_pregnant=$_POST['preg'];
$pregnancy_month=$_POST['months'];
$serious_illness=$_POST['illness'];
$serious_illness_xplain=$_POST['illness_exp'];
$additional_infor=$_POST['additional'];


$save= new dental_vitals;
$save->update_vitals($visit_id,$visit_major_reason,$serious_illness,$serious_illness_xplain,$treatment,$treatment_hospital,$treatment_doctor,$Food_allergies,$Regular_treatment,$Recent_medication,$Medicine_allergies,$chest_trouble,$heart_problems,$diabetic,$epileptic,$rheumatic_fever,$elongated_bleeding,$jaundice,$hepatitis,$asthma,$eczema,$cancer,$women_pregnant,$pregnancy_month,$additional_infor,$dental_vitals_id,$smoke,$alcohol,$prior_treatment);	

$save1= new dental_vitals;
$save1->update_visit($visit_id);
?>
<script>
window.location.href="<?php echo site_url('nurse/nurse_queue'); ?>";
</script>
<?php
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <link type="text/css" rel="stylesheet" href="<?php echo base_url("css/bootstrap.css"); ?>" />
 	<link type="text/css" rel="stylesheet" href="<?php echo base_url("css/interface.css"); ?>" />
    <link type="text/css" rel="stylesheet" href="<?php echo base_url("css/style.css"); ?>" />	
	<title>Vitals</title>
    <script type="text/javascript" src="<?php echo base_url('js/script.js');?>"></script>
	<script type='text/javascript' src='<?php echo base_url('js/jquery.js');?>'></script>
	<script type="text/javascript" src="<?php echo base_url('js/jquery-1.7.1.min.js');?>"></script>
	<script type='text/javascript' src='<?php echo base_url('js/jquery-ui-1.8.18.custom.min.js');?>'></script>



</head>
<body>

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
					
					?><li><a href='<?php echo site_url('welcome/logout')?>'>Logout</a></li>
    				<li><a href='#'>Change Password</a></li>
    			</ul>
        </div>
    	<div class="span10">
    <ol id="new-nav1"></ol>
        <script>
$("#new-nav1").load("http://sagana/hms/data/nurse/nurse_data.php?visit_id=<?php echo $visit_id;?>");
</script>
       <style>
	   textarea {
  border: 1px solid #888; 
  font:"Lucida Sans Unicode", "Lucida Grande", sans-serif;
  font-size:175%;
  color:#00F;
  padding-top:5px;
}
	   
	   </style>    <?php if($num_rows==0){ ?>
           <form action="<?php echo site_url('doctor/dental/'.$visit_id)?>" method="post">
           	<div class="navbar-inner"><p style="text-align:center; color:#0e0efe;">Chief Complaint</p></div> 
           <textarea name="reason" id="reason" rows="7" placeholder="Reason for Visit" style="width: 560px; height: 170px;" required="required"  ></textarea>
                	<div class="navbar-inner"><p style="text-align:center; color:#0e0efe;">Serious Illness or operation</p></div> 
          
            <textarea name="hospital" id="hospital" rows="7" placeholder="Complain" style="width: 341px; height: 129px;" align="center" ></textarea>
            <textarea name="doctor" id="doctor" rows="7" placeholder="Hospital" style="width: 341px; height: 129px;" align="left" ></textarea>
            
              	<div class="navbar-inner"><p style="text-align:center; color:#0e0efe;">Allergies</p></div>        
 <table align='center'>
	 	<tr>
			<td>Food Allergies</td><td><textarea id='food_allergies' name='food_allergies' cols='100' rows='3'> </textarea></td>
			<td>Medicine Allergies</td><td><textarea id='medicine_allergies' name='medicine_allergies' cols='100' rows='3'></textarea></td>
		</tr>
		<tr>
			<td>Regular Treatment</td><td><textarea id='regular_treatment' name='regular_treatment' cols='100' rows='3'></textarea></td>
			<td>Recent Medication</td><td><textarea id='medication_description' name='medication_description' cols='100' rows='3'></textarea></td>
         </tr>	
	</table>


<ol id="new-nav"></ol>
        <script>
$("#new-nav").load("http://sagana/hms/data/nurse/family_history.php?visit_id=<?php echo $visit_id;?>");
</script>

Any Serious Illness? <select name="illness" id="illness" onChange="toggleFieldh('myTFh','illness')" > 
<option value="NO">NO </option>
<option value="YES">YES </option>

</select>
<div id="myTFh" name="myTFh" style='display:none;' >
Explain Illness
<textarea id="illness_exp" name="illness_exp" placeholder="Illness Name"> </textarea></div><br>

Is patient pregnant? <select name="preg" id="preg" onChange="toggleFieldX('myTFx','preg')"> 
<option value="NA">NOT APPLICABLE </option>
<option value="YES">YES </option>
<option value="NO">NO </option>
</select>
<div id="myTFx" name="myTFx" style='display:none;' >
How far Along (Months)
<textarea id="months" name="months" placeholder="How far Along Months"> </textarea></div>

  
	<div class="navbar-inner"><p style="text-align:center; color:#0e0efe;">Additional Information</p></div> 
           <textarea name="additional" id="additional" rows="7" placeholder="Additional Information" style="width: 560px; height: 170px; float:right;"  ></textarea>
           <div style="float:inherit;">
           Have You been Dissatisfied With Previous Treatment?   <strong> Yes:</strong>
           <input name="prior_treatment" type="radio" value="YES">  
            <strong>NO: </strong><input name="prior_treatment" type="radio" value="NO"></div><br>
                  <div style="float:inherit;">
            Alcohol Consupmtion   <strong> Yes:</strong><input name="alcohol" type="radio" value="YES">  
            <strong>NO: </strong><input name="alcohol" type="radio" value="NO"></div><br>
           <div style="float:inherit;">
           Smoke   <strong> Yes:</strong><input name="smoke" type="radio" value="YES">  
            <strong>NO: </strong><input name="smoke" type="radio" value="NO"></div>

<br>
<br>

<br><br>
<div class="navbar-inner"><p  style="text-align:center; color:red; font-size:180%; ">Kindly Let your Dentist Know if you have any new illness, or if any of the above details change durring the course of treatment</p></div> 


           <input type="submit" name="submit" id="submit" class="btn btn-large btn-primary" align="center" value="SAVE"/> 
           <input type="submit" name="submit1" id="submit1" align="center" class="btn btn-large btn-primary" style="width: 300px; " value="SAVE AND SEND TO DENTIST"/>
      </form>                      
    	<?php }
		else {?>
               <form action="<?php echo site_url('doctor/dental/'.$visit_id)?>" method="post">
                     	<div class="navbar-inner"><p style="text-align:center; color:#0e0efe;">Chief Complaint</p></div> 
           <textarea name="reason" id="reason" rows="7" placeholder="Reason for Visit" style="width: 560px; height: 170px;" required="required"  ><?php echo $visit_major_reason;?></textarea>
                	<div class="navbar-inner"><p style="text-align:center; color:#0e0efe;">Serious Illness or operation</p></div> 
            
            <textarea name="hospital" id="hospital" rows="7" placeholder="Complain" style="width: 341px; height: 129px;" align="center" ><?php echo $treatment_hospital?></textarea>
            <textarea name="doctor" id="doctor" rows="7" placeholder="Hospital" style="width: 341px; height: 129px;" align="left" ><?php echo $treatment_doctor ?></textarea>
              	<div class="navbar-inner"><p style="text-align:center; color:#0e0efe;">Allergies</p></div>        
 <table align='center'>
	 	<tr>
			<td>Food Allergies</td><td><textarea id='food_allergies' name='food_allergies' cols='100' rows='3'><?php echo $Food_allergies ?> </textarea></td>
			<td>Medicine Allergies</td><td><textarea id='medicine_allergies' name='medicine_allergies' cols='100' rows='3'><?php echo $Medicine_allergies?></textarea></td>
		</tr>
		<tr>
			<td>Regular Treatment</td><td><textarea id='regular_treatment' name='regular_treatment' cols='100' rows='3'> <?php echo $Regular_treatment ?></textarea></td>
			<td>Recent Medication</td><td><textarea id='medication_description' name='medication_description' cols='100' rows='3'> <?php echo  $Medicine_allergies ?></textarea></td>
         </tr>	
	</table>


<ol id="new-nav"></ol>
        <script>
$("#new-nav").load("http://sagana/hms/data/nurse/family_history.php?visit_id=<?php echo $visit_id;?>");
</script>


Any Serious Illness? <select name="illness" id="illness" onChange="toggleFieldh('myTFh','illness')" > 
<option value="NO">NO </option>
<option value="YES">YES </option>

</select>
<div id="myTFh" name="myTFh" style='display:none;' >
Explain Illness
<textarea id="illness_exp" name="illness_exp" placeholder="Illness Name"><?php echo $serious_illness_xplain; ?> </textarea></div><br>

Is patient pregnant? <select name="preg" id="preg" onChange="toggleFieldX('myTFx','preg')"> 
<option value="NA">NOT APPLICABLE </option>
<option value="YES">YES </option>
<option value="NO">NO </option>
</select>
<div id="myTFx" name="myTFx" style='display:none;'>
How far Along (Months)
<textarea id="months" name="months" placeholder="How far Along Months"> <?php  echo $pregnancy_month; ?></textarea></div>
           
	<div class="navbar-inner"><p style="text-align:center; color:#0e0efe;">Additional Information</p></div> 
  <textarea name="additional" id="additional" rows="7" placeholder="Additional Information" style="width: 560px; height: 170px; float:right;"  >
  <?php echo $additional_infor; ?></textarea>
           <div style="float:inherit;">
           Have You been Dissatisfied With Previous Treatment?  
           <?php if($prior_treatment=="YES") { ?> <strong> Yes:</strong><input name="prior_treatment" type="radio" value="YES" checked="checked">  
              <strong> No:</strong><input name="prior_treatment" type="radio" value="NO"> 
           <?php } elseif($prior_treatment=="NO") { ?> <strong> NO:</strong><input name="prior_treatment" type="radio" value="NO" checked="checked">  
           	   <strong> Yes:</strong><input name="prior_treatment" type="radio" value="YES"> 
           <?php } 
		   else { ?>
			   <strong> Yes:</strong><input name="prior_treatment" type="radio" value="YES">  
			   <strong> No:</strong><input name="prior_treatment" type="radio" value="NO">  
			<?php   }?> </div><br>
                  <div style="float:inherit;">
            Alcohol Consupmtion  
              <?php if($alcohol=="YES") { ?> <strong> Yes:</strong><input name="alcohol" type="radio" value="YES" checked="checked">  
             <strong>NO: </strong><input name="alcohol" type="radio" value="NO">
           <?php } elseif($alcohol=="NO") { ?> <strong> NO:</strong><input name="alcohol" type="radio" value="NO" checked="checked">  
            <strong> Yes:</strong><input name="alcohol" type="radio" value="YES"> 
           <?php } 
		   else { ?>
               <strong> Yes:</strong><input name="alcohol" type="radio" value="YES">  
            <strong>NO: </strong><input name="alcohol" type="radio" value="NO"> 
			<?php   }?>  </div><br>
           <div style="float:inherit;">
           Smoke  <?php if($smoke=="YES") { ?> <strong> Yes:</strong><input name="smoke" type="radio" value="YES" checked="checked">  
            <strong>NO: </strong><input name="smoke" type="radio" value="NO">
           <?php } elseif($smoke=="NO") { ?> <strong> NO:</strong><input name="smoke" type="radio" value="NO" checked="checked">  
            <strong>NO: </strong><input name="smoke" type="radio" value="YES">
           <?php } 
		   else { ?>
             
             <strong> Yes:</strong><input name="smoke" type="radio" value="YES">  
            <strong>NO: </strong><input name="smoke" type="radio" value="NO">
			<?php   }?>  </div>

<br>
<br>

<br><br>
<div class="navbar-inner"><p  style="text-align:center; color:red; font-size:180%; ">Kindly Let your Dentist Know if you have any new illness, or if any of the above details change durring the course of treatment</p></div> 


           <input type="submit" name="update" id="submit" class="btn btn-large btn-primary" align="center" value="UPDATE"/> 
           <input type="submit" name="update1" align="center" class="btn btn-large btn-primary" style="width: 300px; " value="UPDATE AND SEND TO DENTIST"/>
      </form>
        
        <?php
		}?>
        </div>
    </div>
 </div>
				<!-- end wrapper -->
				<div id="footer">
					<p>Copyright &copy; 2012 Sitename.com. All rights reserved. Design by <a href="http://www.freecsstemplates.org">james brian studio</a>.</p>
				</div>
</body>
</html>