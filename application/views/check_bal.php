<?php
//helpers
$this->load->helper('html');
$this->load->helper('url');
$this->load->helper('form');

//css files
$p_id=$this->uri->segment(3);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link type="text/css" rel="stylesheet" href="<?php echo base_url("css/interface.css"); ?>" />
<script src="<?php echo base_url("js/pop.js"); ?>"> </script>
<script src="<?php echo base_url("js/jquery-1.6.1.min.js"); ?>"> </script>
<title>Patient Balance</title>
<style>
#overlay_form {
	background: none repeat scroll 0 0 #00FFFF;
	border-radius: 40px 13px 40px 13px;
    border: 5px solid  	#008080;
    height: auto;
    padding: 0px;
    position: absolute;
    width: 708px;
}
#pop {
    border: 1px solid gray;
    border-radius: 5px 5px 5px 5px;
    display: block;
    margin: 0 auto;
    padding: 6px;
    text-align: center;
    text-decoration: none;
    width: 65px;
}
</style>
</head>
<body onkeyup="whichButton(event);"> 
<?php
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

class check_balance{
	function get_visit_type_id($p_id){
			$sql= "select  patients.visit_type_id, visit.visit_id  from patients, visit where visit.patient_id='$p_id' and patients.patient_id='$p_id'";
			// echo $sql;
			$get = new Database();
			$rs = $get->select($sql);
			return $rs;
	}
	function get_visit_charge($visit_id){
			$sql= "select visit_charge_amount, visit_charge_timestamp  from visit_charge where visit_id='$visit_id'";
			// echo $sql;
			$get = new Database();
			$rs = $get->select($sql);
			return $rs;
	}
	function get_credit_amount($visit_type_id){
			$sql= "select  account_credit, Amount, efect_date from account_credit where visit_type_id='$visit_type_id'";
			// echo $sql;
			$get = new Database();
			$rs = $get->select($sql);
			return $rs;
	}
		function get_visit_type_name($visit_type_id){
			$sql= "select  	visit_type_id,visit_type_name from visit_type where visit_type_id='$visit_type_id'";
			// echo $sql;
			$get = new Database();
			$rs = $get->select($sql);
			return $rs;
	}
			function get_visit_payment($visit_id){
			$sql= "select amount_paid from payments where visit_id='$visit_id'";
			// echo $sql;
			$get = new Database();
			$rs = $get->select($sql);
			return $rs;
	}
			function max_visit($p_id){
		$sql= "SELECT MAX(visit_id) FROM visit WHERE patient_id =$p_id";	
		///echo $sql;
		$get= new Database;
		$rs=$get->select($sql);
		return $rs;			
		}
		
function save_visit_sypmtom($visit_id,$payment_method_id,$amount_paid){
		$sql = "INSERT INTO payments (visit_id,payment_method_id,amount_paid) VALUES ($visit_id,$payment_method_id,$amount_paid)";
		echo $sql;
		$save = new Database;
		$save->insert($sql);
	}
				function min_visit($visit_id,$payment_method_id,$amount_paid){
		$sql= "SELECT MIN(time),payment_id FROM payments WHERE payment_method_id=$payment_method_id and visit_id=$visit_id and amount_paid=$amount_paid";	
		echo $sql;
		$get= new Database;
		$rs=$get->select($sql);
		return $rs;			
		}
	
	}


	
?>
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
        <div id="overlay_form" style="display:none">

</div>
    	<div class="span10">
  			<?php // echo $p_id;
			$temp_amount="";
			$total_amount="";
				
				$total_visit_charge_amount= "";
				$temp_visit_charge_amount= "";
			$Amount=  "";		
			$visit_type_name="";
			$effect_date="";
			$date="";
					$get2 = new check_balance;
					$rs2 = $get2->get_visit_type_id($p_id);
					$rows = mysql_num_rows($rs2);
						$visit_id= "";
					$visit_type_id= "";
					for ($a= 0; $a < $rows; $a++){
					$visit_id= mysql_result($rs2, $a, 'visit_id');
					$visit_type_id= mysql_result($rs2, $a, 'visit_type_id');
					// echo $visit_id;
					// echo $visit_type_id.'</br>';
					
					$get6= new check_balance;
					$rs6 = $get6->get_visit_payment($visit_id);
					$rows6 = mysql_num_rows($rs6);
					for ($d=0; $d < $rows6; $d++){
						$amount_paid= mysql_result($rs6, $d, 'amount_paid');
						$total_amount=$amount_paid+$temp_amount;
						$temp_amount=$total_amount;
						}
					
							$get5 = new check_balance;
					$rs5 = $get5->get_visit_type_name($visit_type_id);
					$rows5 = mysql_num_rows($rs5);
					$visit_type_name= mysql_result($rs5, 0, 'visit_type_name');
					
					
					$get3 = new check_balance;
					$rs3 = $get3->get_visit_charge($visit_id);
					$rows3 = mysql_num_rows($rs3);
						
						
			
					$get4 = new check_balance;
					$rs4 = $get4->get_credit_amount($visit_type_id);
					$rows4 = mysql_num_rows($rs4);
					$account_credit=  "";
							
							$effect_date= "";
							for ($c=0; $c < $rows4; $c++){
							$account_credit= mysql_result($rs4, $c, 'account_credit');
							$Amount= mysql_result($rs4, $c, 'Amount');
							$effect_date= mysql_result($rs4, $c, 'efect_date');
							
							 echo $account_credit;
							// echo $Amount;
							// echo E.$effect_date.'</br>';
						 
						for ($b= 0; $b < $rows3; $b++){
							$visit_charge_amount= mysql_result($rs3, $b, 'visit_charge_amount');
							 $date= mysql_result($rs3, $b, 'visit_charge_timestamp');
							 
							 // echo m.$visit_charge_amount;
							 // echo d.$date.'</br>';
$new_date2 = date('Y-m-d H:m:s',strtotime('+0 days',strtotime($date))); 
// echo newt2.$new_date2;							
$date = $effect_date; 	
$new_date = date('Y-m-d H:m:s',strtotime('+365 days',strtotime($date))); 
// echo newt.$new_date;
	

if (($new_date2 >= $effect_date)&&($new_date2 <= $new_date)){
$total_visit_charge_amount=$visit_charge_amount+$temp_visit_charge_amount;
	$temp_visit_charge_amount=$total_visit_charge_amount;
}
	
	}							
							}}
		/*echo $visit_type_name.'</br>';
		echo ($Amount-$temp_visit_charge_amount);
		 echo $temp_visit_charge_amount;	*/
		 
		 $bal= $total_amount + ($Amount-$temp_visit_charge_amount);?>  
         
         <table align="center">
         <tr> <th width="120px"> Patient Type </th> <th width="400px"> Total Expenses incurred Between <?php  if($date==""){
			 
			 }
			 else { echo $date.'----'.$new_date; } ?> </th> <th width="120px"> Balance from set Credit of (<?php echo $Amount;?>).</th>  <th width="120px"> Total Payments Made.</th></tr>
         <tr> <td width="120px"> <?php echo $visit_type_name;?> </td><td width="400px" align="center"><?php echo $temp_visit_charge_amount?>  </td><td width="120px"><?php echo ($Amount-$temp_visit_charge_amount) ?> </td> <td align="center">  <?php echo $total_amount;?></td></tr>
         <?php if($bal >=0) {?>
         <tr style="color:#03F; font-size:18px; font-style:oblique;">
         <td width="120px"> </td><td width="400px" align="center"></td><td width="120px"> Credit Balance </td> <td align="center">   <?php echo $total_amount + ($Amount-$temp_visit_charge_amount);?></td></tr>
         
         <?php }
		 
		 else {
			  ?> <tr style="color:#F00; font-size:18px; font-style:oblique;">
         <td width="120px"> </td><td width="400px" align="center"></td><td width="120px"> Outstanding Balance </td> <td align="center">   <?php $tttt= $total_amount + ($Amount-$temp_visit_charge_amount);
			echo $tttt;
?></td></tr> 
			 
			<?php }
			$totsl=$total_amount + ($Amount-$temp_visit_charge_amount);
		
			 ?>
            
            
            <tr style="font-size:18px;font-style:oblique;">
         <td width="120px"> <a style="color:#03F;" href="http://sagana/hms/index.php/welcome/initiate_visit/<?PHP echo $p_id; ?> ">INITIATE VISIT </a></td><td width="400px" align="center"><a style="color:#03F;" href="http://sagana/hms/index.php/welcome/initiate_pharmacy/<?PHP echo $p_id;?> "> INITIATE PHARMACY VISIT</a></td><td width="120px"><a style="color:#03F;" href="http://sagana/hms/index.php/welcome/initiate_lab/<?PHP echo $p_id;?> ">INITIATE LAB VISIT </a> </td><td> <a href="#" onClick="popup('<?php echo $p_id; ?>', '<?php echo $totsl; ?>'); alert('<?php echo $p_id; ?>')" > MAKE PAYMENT </a></td> </tr> 
         </table>  
         
                 
        </div>
    </div>
 </div>
 <?php
 

$get1= new check_balance();
$rs1= $get1->max_visit($p_id);
$num_rows1= mysql_num_rows($rs1);
$vv= mysql_result($rs1,0,'MAX(visit_id)');
//echo pp.$vv; 

if(isset($_POST['pay'])){
	$payment_method_id= ($_POST['code3']);
	$amount_paid= ($_POST['myTF']);
	
 $get1z= new check_balance();
$rs1z= $get1z->min_visit($visit_id,$payment_method_id,$amount_paid);
$num_rows1z= mysql_num_rows($rs1z);
$payment_id= mysql_result($rs1z,0,'payment_id');

echo $payment_id;

	$update_obj= new check_balance();
$update_objz= $update_obj->save_visit_sypmtom($vv,$payment_method_id,$amount_paid);
?>
<script>
//alert('<?php //echo $payment_id; ?>');
window.open("http://192.168.172.39/hms/data/accounts/print_receipt2.php?payment_id=<?php echo $payment_id ?>","Popup","height=500,width=800,scrollbars=yes,"+"directories=yes,location=yes,menubar=yes,"+"resizable=no status=no,history=no top = 50 left = 100");
</script>
<?php
//header('Location: http://192.168.172.39/hms/index.php/welcome/check_balance/'.$p_id.'');




	}
?>
<!-- end wrapper -->
				<div id="footer">
					<p>Copyright &copy; 2012 Sitename.com. All rights reserved. Design by <a href="http://www.freecsstemplates.org">james brian studio</a>.</p>
				</div>
</body>
</html>