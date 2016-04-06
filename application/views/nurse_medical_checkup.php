<?php
//helpers
$this->load->helper('html');
$this->load->helper('url');
$this->load->helper('form');

//css files
//css files
echo link_tag('css/bootstrap.css');
echo link_tag('css/bootstrap.min.css');
$p_id=$this->uri->segment(3);
?>
<!DOCTYPE html>
<html lang="en">
<head>
 	<link type="text/css" rel="stylesheet" href="<?php echo base_url("css/interface.css"); ?>" />
	<title>Mecical Examination</title>
    <script type="text/javascript" src="<?php echo base_url('js/script.js');?>"></script>
	<script type='text/javascript' src='<?php echo base_url('js/jquery.js');?>'></script>
	<script type="text/javascript" src="<?php echo base_url('js/jquery-1.7.1.min.js');?>"></script>
	<script type='text/javascript' src='<?php echo base_url('js/jquery-ui-1.8.18.custom.min.js');?>'></script>

<script src="<?php echo base_url("js/jquery-1.6.1.min.js"); ?>"> </script>

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

<body onkeyup="whichButton(event);" onLoad="checkup_interface1(<?php echo $p_id;?>)"> 


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

class checkup{
	function medical_exam_categories(){
			$sql= "SELECT * FROM `medical_exam_categories`";
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
		//echo $sql;
		$save = new Database;
		$save->insert($sql);
	}
				function min_visit($visit_id,$payment_method_id,$amount_paid){
		$sql= "SELECT MIN(time),payment_id FROM payments WHERE payment_method_id=$payment_method_id and visit_id=$visit_id and amount_paid=$amount_paid";	
		//echo $sql;
		$get= new Database;
		$rs=$get->select($sql);
		return $rs;			
		}
		
	function mec_med($mec_id){
		$sql= "select distinct item_format_id from  cat_items where mec_id=$mec_id";	
		//echo $sql;
		$get= new Database;
		$rs=$get->select($sql);
		return $rs;			
		}
		
	function format_id($item_format_id){
		$sql= "select * from  format where item_format_id=$item_format_id";	
	//	echo $sql;
		$get= new Database;
		$rs=$get->select($sql);
		return $rs;			
		}
		
function get_cat_items($item_format_id, $mec_id){
	$sql="SELECT cat_items.cat_item_name, cat_items.cat_items_id, cat_items.item_format_id, format.format_name, format.format_id FROM cat_items, format WHERE cat_items.item_format_id = format.item_format_id 
	AND cat_items.item_format_id =$item_format_id AND mec_id =$mec_id";
		//echo $sql;
		$get= new Database;
		$rs=$get->select($sql);
		return $rs;	
	
	}
	function cat_items($item_format_id, $mec_id){
	$sql="SELECT cat_items.cat_item_name, cat_items.cat_items_id FROM cat_items WHERE cat_items.item_format_id =$item_format_id AND mec_id =$mec_id";
	//	echo $sql;
		$get= new Database;
		$rs=$get->select($sql);
		return $rs;	
	
	}
		function cat_items2($cat_items_id,$format_id,$visit_id){
	$sql="SELECT *  FROM medical_checkup_results WHERE cat_id=$cat_items_id and format_id =$format_id and visit_id=$visit_id ";
	//	echo $sql;
		$get= new Database;
		$rs=$get->select($sql);
		return $rs;	
	
	}
			function get_illness($p_id,$mec_id){
	$sql="SELECT *  FROM med_check_text_save where med_id='$mec_id' and visit_id=$p_id";
	//echo $sql;
		$get= new Database;
		$rs=$get->select($sql);
		return $rs;	
	
	}
	}


	
?>
	<div id="header" class="container">
    	<div id="logo">
			<h1><a href="#">SUMC</a></h1><br>
<br>
<p><a href="http://www.strathmore.edu">Mecical Examination</a></p>
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
					
					?>	<li><a href='<?php echo site_url('welcome/logout')?>'>Logout</a></li>
    				<li><a href='#'>Change Password</a></li>
    			</ul>
        </div>
     	<div class="span10">
        	<div id="patient_details"></div>
        
        <?php 	$mec_result="";
			$get2 = new checkup;
					$rs2 = $get2->medical_exam_categories();
					$rows = mysql_num_rows($rs2);
					for ($a=0; $a < $rows; $a++){
						
							$mec_name= mysql_result($rs2, $a, 'mec_name');
							$mec_id= mysql_result($rs2, $a, 'mec_id');
							
							$get21 = new checkup;
					$rs21 = $get21-> get_illness($p_id,$mec_id);
					$rows1 = mysql_fetch_array($rs21);
					$mec_result= $rows1['infor'];
					
						if($mec_name=="Family History"){
							?>      
<ol id="new-nav"></ol>
        <script>
$("#new-nav").load("http://sagana/hms/data/nurse/family_history.php?visit_id=<?php echo $p_id;?>");
</script>
   <?php 
							}
							else if(($mec_name=="Present Illness")||($mec_name=="Past Illness")) {	echo '
  <div class="navbar-inner"><p style="text-align:center; color:#0e0efe; ">'.$mec_name.'</p>
  
 </div>';
					
							?>
  <textarea  align="center" name="gg<?php echo $mec_id ?>"  id="gg<?php echo $mec_id ?>"   rows="5" placeholder="<?php echo $mec_name ?>" onKeyUp="save_illness('<?php echo $mec_id ?>','<?php echo $p_id ?>')" > <?php echo $mec_result; ?></textarea>

<?php							}
							else if(($mec_name=="Physiological History")||($mec_name=="General Physical Examination")||($mec_name=="Head Physical Examination")||($mec_name=="Neck Physical Examination")||($mec_name=="Cardiovascular System Physical Examination")||($mec_name=="Respiratory System Physical Examination")||($mec_name=="Abdomen Physical Examination")||($mec_name=="Nervous System Physical Examination")) {	echo '
  <div class="navbar-inner"><p style="text-align:center; color:#0e0efe;">'.$mec_name.'</p> </div>
  <table> ';
  
					
					
  					$get4 = new checkup;
					$rs4 = $get4->mec_med($mec_id);
					$rows4 = mysql_num_rows($rs4);
					$ab=0;
					for($a4=0; $a4 < $rows4; $a4++){
						$item_format_id=mysql_result($rs4, $a4, 'item_format_id');
						$ab++;
						
					 $get6 = new checkup;
					$rs6 = $get6-> cat_items($item_format_id, $mec_id);
					$rows6 = mysql_num_rows($rs6);
						for($a6=0; $a6< $rows6; $a6++){
						$cat_item_name=mysql_result($rs6, $a6, 'cat_item_name');
						$cat_items_id1 =mysql_result($rs6, $a6, 'cat_items_id');
						
						?><tr> <td  width="100px"><?php echo $cat_item_name; ?> </td>
                      <?php  
                        $get7 = new checkup;
					$rs7 = $get7-> get_cat_items($item_format_id, $mec_id);
					$rows7 = mysql_num_rows($rs7); 
					for($a7=0; $a7< $rows7; $a7++){
						$cat_item_name=mysql_result($rs7, $a7, 'cat_item_name');
						$cat_items_id =mysql_result($rs7, $a7, 'cat_items_id');
						$item_format_id1 =mysql_result($rs7, $a7, 'item_format_id');
						$format_name =mysql_result($rs7, $a7, 'format_name');
						$format_id =mysql_result($rs7, $a7, 'format_id');
						
						if($cat_items_id==$cat_items_id1)
						{
							if($item_format_id1== $item_format_id){
						   $get8 = new checkup;
					$rs8 = $get8-> cat_items2($cat_items_id,$format_id,$p_id);
					//echo $rows8;
					$rows8 = mysql_num_rows($rs8);
					if  ($rows8>0){	?>
                            <td width="100px"> <?php echo '<strong>'.$format_name.'</strong>'; ?>  <br><input checked type="checkbox" value="" name="" onClick="del_medical_exam('<?php echo $cat_items_id; ?>','<?php echo $format_id ; ?>','<?php echo $p_id; ?>')"></td>
                            <?php } else { ?>
                            <td width="100px"> <?php echo '<strong>'.$format_name.'</strong>'; ?>  <br><input type="checkbox" value="" name="" onClick="medical_exam('<?php echo $cat_items_id; ?>','<?php echo $format_id ; ?>','<?php echo $p_id; ?>')"></td>
							<?php	}
												
							}}}}?></tr><?php } ?>
            </table>   <?php } 
														}
	?>
      <?php  
	  
	  			$get21 = new checkup;
				$rs21 = $get21-> get_illness($p_id,"Further");
					$rows1 = mysql_fetch_array($rs21);
					$mec_result= $rows1['infor'];
	  ?>
      <div class="navbar-inner"><p style="text-align:center; color:#0e0efe;">Further Details</p></div>
        <textarea placeholder="further Details "  align="center"  name="gg<?php echo 'Further' ?>" id="gg<?php echo 'Further' ?>" rows="5" onKeyUp="save_illness('<?php echo 'Further' ?>','<?php echo $p_id ?>')"   >  <?php echo $mec_result; ?></textarea>
        
        
      <div id="plan"></div>
   
      <div class="navbar-inner"><p style="text-align:center; color:#0e0efe;">Conclusions</p></div>
         <?php  
	  				$get21 = new checkup;
					$rs21 = $get21-> get_illness($p_id,"Medically");
					$rows1 = mysql_fetch_array($rs21);
					$mec_result= $rows1['infor'];
		?> Medically Fit : 
		<?php if($mec_result=='no'){
		 ?>
         NO:<input type="checkbox" value="no" checked  name="ggg<?php echo 'Medically' ?>" id="ggg<?php echo 'Medically' ?>" onclick="save_no('<?php echo 'Medically'?>','<?php echo $p_id ?>')" >  
         <?php }
		 else {
		 ?>
         NO:<input type="checkbox" value="no"  name="ggg<?php echo 'Medically' ?>" id="ggg<?php echo 'Medically' ?>" onclick="save_no('<?php echo 'Medically'?>','<?php echo $p_id ?>')" >  
         <?php			 
			 }
			 
		if($mec_result=='yes'){
		 ?>
         YES:<input type="checkbox" value="yes" checked  name="gg<?php echo 'Medically' ?>" id="gg<?php echo 'Medically' ?>" onclick="save_illness('<?php echo 'Medically'?>','<?php echo $p_id ?>')" >  
         <?php }
		 else {
		 ?>
         YES:<input type="checkbox" value="yes"  name="gg<?php echo 'Medically' ?>" id="gg<?php echo 'Medically' ?>" onclick="save_illness('<?php echo 'Medically'?>','<?php echo $p_id ?>')" >  
         <?php			 
			 }
	  				$get21 = new checkup;
					$rs21 = $get21-> get_illness($p_id,"conclusion");
					$rows1 = mysql_fetch_array($rs21);
					$mec_result= $rows1['infor'];
?>
        <textarea  align="center" placeholder="Comments if Any"  name="gg<?php echo 'conclusion'?>" id="gg<?php echo 'conclusion'?>" rows="5" onKeyUp="save_illness('<?php echo 'conclusion'?>','<?php echo $p_id ?>')"   >  <?php echo $mec_result; ?></textarea>
   <div align="center"> <a onClick="window.open('<?php echo base_url("data/print_med_check.php?visit_id=".$p_id)?>','Popup','height=900,width=1200,,scrollbars=yes,'+'directories=yes,location=yes,menubar=yes,'+'resizable=no status=no,history=no top = 50 left = 100');
" href="#"> <input type="button" class="btn btn-large" align="center" value="Print Checkup"> </a></div>
        </div>
         
        </div>
        </div>
              
<!-- end wrapper -->
				<div id="footer">
					<p>Copyright &copy; 2012 Sitename.com. All rights reserved. Design by <a href="http://www.freecsstemplates.org">james brian studio</a>.</p>
				</div>
</body>
</html>