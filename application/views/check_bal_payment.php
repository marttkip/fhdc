<?php 
$patient_id=$this->uri->segment(3);
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
class tender{
	function mobile_payments(){
		$sql= "SELECT *FROM payment_method";	
		//echo $sql;
		$get= new Database;
		$rs=$get->select($sql);
		return $rs;			
		}
	function max_visit($patient_id){
		$sql= "SELECT MAX(visit_id) FROM visit WHERE patient_id =$patient_id";	
		echo $sql;
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
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<style>
body
{
 font-family:New Century Schoolbook, serif;
 color:#234F51;
	font-size: 15px;
}
.append1 {
    border: 2px solid #DADADA;
    border-radius: 7px 40px 7px 40px;
    float: inherit;
    font-size: 20px;
    margin-top: 0;
    max-width: 100%;
    padding: 5px;
    width: 350px;
    height: 40px;
}
.append1s {
    border: 2px solid #DADADA;
    border-radius: 7px 40px 7px 40px;
    margin-left: 230px;
    font-size: 20px;
    margin-bottom: -30px;
    max-width: 350px;
    padding: 2px;
    width: auto;
    height: 50px;
    margin-top: 10px;
}
.butt-sell{
    border: 1px solid #DADADA;
    border-radius: 36px 7px 35px 7px;
    float: left;
    font-size: 20px;
    margin-top: 0px;
    padding: 5px;
    width: 100px;
}

input:focus { 
    outline:none;
    border-color:#F6F;
    box-shadow:0 0 10px #F6F;
}
.append1:focus { 
    outline:none;
    border-color:#F6F;
    box-shadow:0 0 10px #F6F;
}
.append1s:focus { 
    outline:none;
    border-color:#F6F;
    box-shadow:0 0 10px #F6F;
}
</style>
<style>

.design13{
background-image:url("<?php echo site_url('../../../point_of_sale/images/icons/gradient2.png')?>");
border:5px solid #88de85;
	padding:9px;
   -webkit-border-top-left-radius: 8px;
	-moz-border-radius-topleft: 8px;
	-webkit-border-bottom-right-radius: 8px;
	-moz-border-radius-bottomright: 8px;
	color: #093;
	font-size:30px;
	font-weight:bold;
	font-style:italic;
	}
.design11{
background-image:url("<?php echo site_url('../../../point_of_sale/images/icons/gradient1.png')?>");
border:5px solid red;
padding:9px;
   -webkit-border-top-left-radius: 8px;
	-moz-border-radius-topleft: 8px;
	-webkit-border-bottom-right-radius: 8px;
	-moz-border-radius-bottomright: 8px;
	font-size:30px;
	font-weight:bold;
	font-style:italic;
color: red;
}
.design10{
padding:4px;
	font-size:15px;
	font-weight:bold;

color: blue;
}
</style>
<body OnLoad="document.tender.code1.focus();" onkeyup="whichButton(event);">
<br />
<br />
<?php
$patient_id=$this->uri->segment(3);
$balance=$this->uri->segment(4);
//echo $balance;
echo  "<strong style='font-size: 22px; padding-right:15px'>--------------MAKE PAYMENT FOR A PRIOR INVOICE------------- </strong></br></br>";

echo  "<strong style='color:#03F; font-size: 18px; padding-right:15px'>Outstanding Balance is (".$balance.")</strong>";

?>
<form id="tender" name="tender" method="post" action="http://sagana/hms/index.php/welcome/check_balance/<?php echo $patient_id; ?>">
<tr>
<td>
<label style="font-size: 16px; padding-right:15px">  SELECT PAYMENT METHOD</label></td>
<td> <select onChange="toggleField('myTF')" id="code3"  name="code3"class="append1" > 
<option class="append1" value="0" selected="selected"> -----Select Type -----</option>
<?php
$get= new tender;
$rs= $get->mobile_payments();
$num_rows= mysql_num_rows($rs);

 for ($b=0; $b< $num_rows; $b++){
	$payment_method= mysql_result($rs, $b, 'payment_method'); 
	$payment_method_id = mysql_result($rs, $b, 'payment_method_id'); 
?>		
<option value="<?php echo $payment_method_id;?>" class="append1"> <?php echo $payment_method ?></option>		
<?php }
?>
</select> </td>
</tr>
<tr> <td> <input onKeyUp="doMath('tax','myTF', '<?php echo $balance; ?>');"  type="text" placeholder="Payment" id="myTF" class="append1s" name="myTF" size="25" style="display:none;" > </td></tr><br />
<br />
<tr>
<td><input type="submit" name="pay" id="pay" /> </td> <td> <input  size="15" class="design11" value="" name="tax" id="tax" type="text"  /> </td></tr>
 
</form>

</body>
</html>