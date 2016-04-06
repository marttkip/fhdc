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
 
 	<link type="text/css" rel="stylesheet" href="<?php echo base_url("css/bootstrap.css"); ?>" />
 	<link type="text/css" rel="stylesheet" href="<?php echo base_url("css/interface.css"); ?>" />
 	<link type="text/css" rel="stylesheet" href="<?php echo base_url("css/jquery-ui-1.8.18.custom.css"); ?>" />
    <link type="text/css" rel="stylesheet" href="<?php echo base_url("css/jquery.ui.timepicker.css");?>"/>
	<title>Reception</title>
    <script type="text/javascript" src="<?php echo base_url("js/script.js");?>"></script>
    <script type="text/javascript" src="<?php echo base_url("js/jquery.ui.widget.js");?>"></script>
    <script type="text/javascript" src="<?php echo base_url("js/jquery.js");?>"></script>
    <script type="text/javascript" src="<?php echo base_url("js/jquery-1.7.1.min.js");?>"></script>
    <script type="text/javascript" src="<?php echo base_url("js/jquery.ui.core.js");?>"></script>
    <script type="text/javascript" src="<?php echo base_url("js/jquery.ui.datepicker.js");?>"></script>
       <script type="text/javascript" src="<?php echo base_url("js/jquery.ui.timepicker.js");?>"></script>
<script type="text/javascript" charset="utf-8">

	$(function(){
				
				//date picker
				$( "#datepicker" ).datepicker();
				$( "#format" ).change(function() {
					$( "#datepicker" ).datepicker( "option", "dateFormat", $( this ).val() );
				});
				
				$('#timepicker').timepicker();
			});
  $(document).ready(function() {
                $('#timepicker_start').timepicker({
                    showLeadingZero: false,
                    onHourShow: tpStartOnHourShowCallback,
                    onMinuteShow: tpStartOnMinuteShowCallback
                });
                $('#timepicker_end').timepicker({
                    showLeadingZero: false,
                    onHourShow: tpEndOnHourShowCallback,
                    onMinuteShow: tpEndOnMinuteShowCallback
                });
            });

            function tpStartOnHourShowCallback(hour) {
                var tpEndHour = $('#timepicker_end').timepicker('getHour');
                // all valid if no end time selected
                if ($('#timepicker_end').val() == '') { return true; }
                // Check if proposed hour is prior or equal to selected end time hour
                if (hour <= tpEndHour) { return true; }
                // if hour did not match, it can not be selected
                return false;
            }
            function tpStartOnMinuteShowCallback(hour, minute) {
                var tpEndHour = $('#timepicker_end').timepicker('getHour');
                var tpEndMinute = $('#timepicker_end').timepicker('getMinute');
                // all valid if no end time selected
                if ($('#timepicker_end').val() == '') { return true; }
                // Check if proposed hour is prior to selected end time hour
                if (hour < tpEndHour) { return true; }
                // Check if proposed hour is equal to selected end time hour and minutes is prior
                if ( (hour == tpEndHour) && (minute < tpEndMinute) ) { return true; }
                // if minute did not match, it can not be selected
                return false;
            }

            function tpEndOnHourShowCallback(hour) {
                var tpStartHour = $('#timepicker_start').timepicker('getHour');
                // all valid if no start time selected
                if ($('#timepicker_start').val() == '') { return true; }
                // Check if proposed hour is after or equal to selected start time hour
                if (hour >= tpStartHour) { return true; }
                // if hour did not match, it can not be selected
                return false;
            }
            function tpEndOnMinuteShowCallback(hour, minute) {
                var tpStartHour = $('#timepicker_start').timepicker('getHour');
                var tpStartMinute = $('#timepicker_start').timepicker('getMinute');
                // all valid if no start time selected
                if ($('#timepicker_start').val() == '') { return true; }
                // Check if proposed hour is after selected start time hour
                if (hour > tpStartHour) { return true; }
                // Check if proposed hour is equal to selected start time hour and minutes is after
                if ( (hour == tpStartHour) && (minute > tpStartMinute) ) { return true; }
                // if minute did not match, it can not be selected
                return false;
			}
			
			function check_date(){
     var datess=document.getElementById("datepicker").value;
     if(datess){
	  $('#show_doctor').fadeToggle(1000); return false;
	 }
	 else{
		 
		 alert('Select Date First')
		 }
			}
		function load_schedule(){
			   var datess=document.getElementById("datepicker").value;
			 var doctor=document.getElementById("doctor").value;
			var url="http://sagana/hms/index.php/welcome/doc_schedule/"+doctor+"/"+datess;
			//alert(url);
			  $('#doctors_schedule').load(url);
		$('#doctors_schedule').fadeIn(1000); return false;	
		}
 
function getXMLHTTP() {
	 //fuction to return the xml http object
		var xmlhttp=false;	
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}
		 	
		return xmlhttp;
	}
	
	
	
	function getCity(strURL) {		
		
		var req = getXMLHTTP();
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('citydiv').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}
				
	}
	function checks(patient_type){

		var patient_type=document.getElementById('patient_type').value;
		if(patient_type==0){
		alert('Ensure you have selected The patient type');
		}
		
		}
</script>
        <style>
#show_doctor{
align:center;
color:#FFF;
min-width: 380px;
	min-height: 380px;
	border:5px;
	border:#F00;
	border-radius:10px 10px 10px 10px;
display:none;
background-color:#000;
padding:1px;
opacity:0.95;
filter:alpha(opacity=100);
position:center;
z-index:1;
}
#doctors_schedule{
align:center;
color:#000;
min-width: 300px;
	min-height: 270px;
	border:5px;
	border:#F00;
	border-radius:10px 10px 10px 10px;
display:none;
background-color:#FFF;
padding:1px;
opacity:0.95;
filter:alpha(opacity=100);
position:center;
z-index:1;
}
    </style>
<link href="../../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
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
                    <li><a href='<?php echo site_url('welcome/students')?>'>Students</a></li>
                    <li><a href='<?php echo site_url('welcome/appointment_list')?>'>Appointment List</a></li>
                    <li><a href='<?php echo site_url('welcome/visit_list')?>'>Ongoing Visits</a></li>
                    <li><a href='<?php echo site_url('welcome/visit_history')?>'>Visit History</a></li>
                    
    				<li class="nav-header">My Account</li>
    				<li><a href='<?php echo site_url('welcome/control_panel/'.$_SESSION['personnel_id'])?>'>Control Panel</a></li>
    				<li><a href='<?php echo site_url('welcome/logout')?>'>Logout</a></li>
    				<li><a href='#'>Change Password</a></li>
    			</ul>
        </div>
    	<div class="span10">
           
        	<?php echo validation_errors(); ?>
        	<?php echo form_open("welcome/save_visit/".$patient_id);?>
            	<div class='navbar-inner'><p style='text-align:center; color:#0e0efe;'>Initiate Visit for <?php echo $patient;?></p></div>
                <table class="table table-stripped table-condensed table-hover">
                	<tr>
                    	<th>Visit Date</th>
                    	<th>Doctor</th>
                    	<th>Patient type</th>
                        <th>Consultation type</th>
                    	  
                    </tr>
                	<tr>
                    	 <td><input type="text" id="datepicker"  name="visit_date" size="15" autocomplete="off" placeholder="Visit Date" />    <strong>Appointment Details</strong><br>
     <a onclick="check_date()">[Show Doctor's Schedule]</a>
	<div id="show_doctor">  
    <select name="doctor" id="doctor" onChange="load_schedule()" >
    <option >----Select Doctor to View Schedule---</option>
                        	<?php
								if(count($doctor) > 0){
                            		foreach($doctor as $row):
										$fname = $row->personnel_fname;
										$onames = $row->personnel_onames;
										$personnel_id = $row->personnel_id;
										echo "<option value=".$personnel_id.">".$onames."</option>";
									endforeach;
								}
							?>
                            </select>
      <div  id="doctors_schedule"> 
      
      </div>
     </div>
          <label for="timepicker_start">Start time :</label>
        <input type="text" style="width: 70px" id="timepicker_start" name="timepicker_start" value="" />
        <label for="timepicker_end">end time :</label>
        <input type="text" style="width: 70px" id="timepicker_end" name="timepicker_end" value="" />

   
</td>
                    	<td>
                        	<select name="doctor">
                        	<?php
					                            	
								if(count($doctor) > 0){
                            		foreach($doctor as $row):
										$fname = $row->personnel_fname;
										$onames = $row->personnel_onames;
										$personnel_id = $row->personnel_id;
										echo "<option value=".$personnel_id.">".$onames."</option>";
									endforeach;
								}
							?>
								
							
                            </select>
                       </td>                 
                    	<td>
                       <select name="patient_type" id="patient_type"  onChange='insurance_company("patient_type","insured");getCity("http://sagana/hms/data/load_charges.php?patient_type="+this.value);' >
                            <option value="0">--- Select Patient Type---</option>
                        	<?php
								if(count($type) > 0){
                            		foreach($type as $row):
										$type_name = $row->visit_type_name;
										$type_id= $row->visit_type_id;
											?><option value="<?php echo $type_id; ?>" ><?php echo $type_name ?></option>
									<?php	
									endforeach;
								}
							?>
                            </select> <br>
           <div  id="insured" style="display:none;">
                       
               <strong>Insurance Name<br>
&   </strong>	<select name="patient_insurance_id">
                        <option value="">--- Select Insurance Company---</option>
                            <?PHP
                            if(count($patient_insurance) > 0){	
							foreach($patient_insurance as $row):
									$company_name = $row->company_name;
									$insurance_company_name = $row->insurance_company_name;
									$patient_insurance_id = $row->patient_insurance_id;
									echo "<option value=".$patient_insurance_id.">".$company_name." - ".$insurance_company_name."</option>";						endforeach;	} ?>
                                    </select>
			  <br>
			
			  <input name="insurance_id" id="insurance_id"  type="text" placeholder="Input Insurance Number">
				 
                      </div>
                        </td>
             <td>
                       <div id="citydiv"> <div id="checker"  onclick="checks('patient_type');" > <select name="service_charge_name">
	<option value='0'>Loading..</option>
        </select></div>
                             </div> </td>
                  </tr>
                </table>
                <table align="center">
                	<tr>
                    	<td><input type="submit" value="Initiate Visit" class="btn btn-large btn-primary"/></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
 </div>
				<!-- end wrapper -->
				<div id="footer">
					<p>Copyright &copy; 2012 Sitename.com. All rights reserved. Design by <a href="http://www.freecsstemplates.org">james brian studio</a>.</p>
				</div>
</body>
</html>