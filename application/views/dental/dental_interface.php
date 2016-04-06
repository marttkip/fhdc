<?php
$visit_id=$this->uri->segment(3);
$visit_type="";
//helpers
$this->load->helper('html');
$this->load->helper('url');
$this->load->helper('form');

$temp="";
$total="";
$total1="";

//css files
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


class dental{
    public function procedure($visit_type){
       
        $sql="select * from service_charge where service_id=9 and visit_type_id=$visit_type order by service_charge_name ASC";
        $get= new Database;
        $rs=$get->select($sql);
        return $rs;       
        }
    public function get_visit($visit_id){
       
        $sql="select visit_type,patient_insurance_id from visit where visit_id=$visit_id";
        $get= new Database;
        $rs=$get->select($sql);
        return $rs;       
    }

        public function get_dental_charges($visit_id){
       
        $sql="select service_charge.service_charge_name,service_charge.service_charge_id,visit_charge.visit_charge_id, visit_charge.visit_charge_amount from visit_charge,service_charge where visit_id=$visit_id and visit_charge.service_charge_id=service_charge.service_charge_id";
        $get= new Database;
        $rs=$get->select($sql);
        return $rs;       
       
        }
       
        public function remove_from_dental_queue($visit_id){
       
        $sql="UPDATE `sumc`.`visit` SET `dental_visit` = '0' WHERE `visit`.`visit_id` =$visit_id";
//    echo $sql;
$save= new Database;
    $save->insert($sql);   
       
        }
            public function get_service_id($service_charge_id){
       
        $sql="select service_id from service_charge where service_charge_id=$service_charge_id";
        //echo $sql;
$get= new Database;
        $rs=$get->select($sql);
        return $rs;       
       
        }
    public function delete_procedure($vcid){
        $sql="delete from visit_charge where visit_charge_id=$vcid";
        //echo $sql;
$save= new Database;
    $save->insert($sql);           
        }
        public function select_dental($visit_id){
       
        $sql="select * from dental_doctor_visit where visit_id=$visit_id ";
    //    echo $sql;
        $get= new Database;
        $rs=$get->select($sql);
        return $rs;           
       
        }
   
        public function save_dental_xray($visit_id,$pic){
       
        $sql="INSERT INTO `sumc`.`denta_xray` (`denta_xray_name` ,`denta_xray_visit_id`) VALUES ('$pic', '$visit_id'); ";
    echo $sql;
$save= new Database;
    $save->insert($sql);           
       
        }
                public function select_dental_xray($visit_id){
       
        $sql="select * from `sumc`.`denta_xray` where denta_xray_visit_id=$visit_id ";
    //    echo $sql;
        $get= new Database;
        $rs=$get->select($sql);
        return $rs;           
       
        }
    }

$get= new dental;
$rs=$get->get_visit($visit_id);
$num_rows=mysql_num_rows($rs);
$visit_type= mysql_result($rs, 0, 'visit_type');
$patient_insurance_id= mysql_result($rs, 0, 'patient_insurance_id');

$visit_type_name="";
if ($visit_type==1){
    $visit_type_name='Students';
   
    }
else if($visit_type==2){
   
    $visit_type_name='Staff';
}
else if($visit_type==3){
   
    $visit_type_name='Other';
}
else {
    $visit_type_name='Insurance';

}

//echo 'PK'.$visit_type;

$get1= new dental;
$rs1=$get1->procedure($visit_type);
$num_rows1=mysql_num_rows($rs1);


$getz= new dental;
$rsz=$getz->select_dental($visit_id);
$num_rowsz=mysql_num_rows($rsz);

//echo 'PP'.$num_rowsz;
$get2= new dental;
$rs2=$get2->get_dental_charges($visit_id);
$num_rows2=mysql_num_rows($rs2);


if(isset($_POST['update'])){
    $save1= new dental;
$save1->remove_from_dental_queue($visit_id);

?>
<script>
window.location.href="<?php echo site_url('doctor/dental_queue'); ?>";
</script>
<?php
   
}
if(isset($_POST['update1'])){

?>
<script>
window.location.href="<?php echo site_url('doctor/dental_queue'); ?>";
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
   
    <script type="text/javascript" src="<?php echo base_url('js/script.js');?>"></script>
    <script type='text/javascript' src='<?php echo base_url('js/jquery.js');?>'></script>
    <script type="text/javascript" src="<?php echo base_url('js/jquery-1.7.1.min.js');?>"></script>
    <script type='text/javascript' src='<?php echo base_url('js/jquery-ui-1.8.18.custom.min.js');?>'></script>
   

      <style>
	   textarea {
  border: 1px solid #888; 
  font:"Lucida Sans Unicode", "Lucida Grande", sans-serif;
  font-size:175%;
  color:#00F;
  padding-top:5px;
}
	   
	   </style>   

 <title>Dental </title>
</head>
<body onLoad="patient_details(<?php echo $visit_id; ?>); display_prescription(<?php echo $visit_id; ?>,2);">
  <div id="header" class="container">
        <div id="logo">
            <h1><a href="#">SUMC</a></h1>
            <p><a href="http://www.strathmore.edu">Dentist</a></p>
        </div>
    </div>
    <!-- end #header -->
<div class="row-fluid">
        <div class="span2 navbar-inner">
                <ul class="nav nav-list">
                    <li class="nav-header">Patients</li>
                    <li><a href='<?php echo site_url('doctor/dental_queue')?>'>Doctor's Queue</a></li>
                    <li><a href='<?php echo site_url('doctor/dental_reception_queue')?>'>From Reception</a></li>
                    <li><a href='<?php echo site_url('nurse/visit_list')?>'>General Queue</a></li>
                   
                    <li class="nav-header">My Account</li>
                    <li><a href='<?php echo site_url('welcome/control_panel/'.$_SESSION['personnel_id']); ?>'>Control Panel</a></li>
                    <li><a href='<?php echo site_url('welcome/logout')?>'>Logout</a></li>
                    <li><a href='#'>Change Password</a></li>
                </ul>
        </div>
        <div class="span10">
      <div id="patient_details"> </div>
             <div class="navbar-inner"><p style="text-align:center; color:#0e0efe;">Oral Hygiene</p> <a href="http://sagana/hms/index.php/doctor/print_test2/<?php echo $visit_id; ?>" ><input type="button" class='btn btn-primary' id="butt" name="butt" value="PRINT CONSENT FORM"></a> </div>
      <?php if ($num_rowsz>0){
         
$good_poor_fair=mysql_result($rsz, 0,'good_poor_fair');
$calculus=mysql_result($rsz, 0,'calculus');
$oral_exam=mysql_result($rsz, 0,'oral_exam');
$tongue=mysql_result($rsz, 0,'tongue');
$palate=mysql_result($rsz, 0,'palate');
$gingivae=mysql_result($rsz, 0,'gingivae');
$teeth=mysql_result($rsz, 0,'teeth');
$other_findings=mysql_result($rsz, 0,'other_findings');
$xray_exam=mysql_result($rsz, 0,'xray_exam');
$radiography=mysql_result($rsz, 0,'radiography');
$aesthetic_observation=mysql_result($rsz, 0,'aesthetic_observation');
$diagnosis=mysql_result($rsz, 0,'diagnosis');
     
      ?>
  
        Good : <?php
        if($good_poor_fair=="good"){
        ?>   
        <input name="oral" id="oral" onClick="save_dentalstuff('<?php echo $visit_id;?>','oral',11)" type="radio" value="good"  required="required" checked="checked">   
    <?php    }else{
        ?>   
        <input name="oral" id="oral" onClick="save_dentalstuff('<?php echo $visit_id;?>','oral',11)" type="radio" value="good" required="required">   
    <?php       
            }
       

        ?> Fair:
         <?php
        if($good_poor_fair=="fair"){
        ?>   
        <input name="oral" id="oral" onClick="save_dentalstuff('<?php echo $visit_id;?>','oral',11)" type="radio" value="fair"  required="required" checked="checked">   
    <?php    }else{
        ?>   
        <input name="oral" id="oral" onClick="save_dentalstuff('<?php echo $visit_id;?>','oral',11)" type="radio" value="fair"  required="required">   
    <?php       
            }
       
        ?>
        Poor:
        <?php
        if($good_poor_fair=="poor"){
        ?>   
        <input name="oral" id="oral" onClick="save_dentalstuff('<?php echo $visit_id;?>','oral',11)" type="radio" value="poor"  required="required" checked="checked">   
    <?php    }else{
        ?>   
        <input name="oral" id="oral" onClick="save_dentalstuff('<?php echo $visit_id;?>','oral',11)" type="radio" value="poor"  required="required">   
    <?php       
            }
       
        ?> <br>
<br>

        <strong>CALCULUS ::</strong>
         Heavy :
          <?php
        if($calculus=="Heavy"){
        ?>   
        <input name="calculus" id="calculus" onClick="save_dentalstuff('<?php echo $visit_id;?>','calculus',12)" type="radio" value="Heavy" checked="checked" required="required">   
    <?php    }else{
        ?>   
        <input name="calculus" id="calculus" onClick="save_dentalstuff('<?php echo $visit_id;?>','calculus',12)" type="radio" value="Heavy"  required="required">   
    <?php       
            }
       
        ?>  Moderate:
              <?php
        if($calculus=="Moderate"){
        ?>   
        <input name="calculus" id="calculus" onClick="save_dentalstuff('<?php echo $visit_id;?>','calculus',12)" type="radio" value="Moderate" checked="checked" required="required">   
    <?php    }else{
        ?>   
        <input name="calculus" id="calculus" onClick="save_dentalstuff('<?php echo $visit_id;?>','calculus',12)" type="radio" value="Moderate"  required="required">   
    <?php       
            }
       
        ?> Mild:             <?php
        if($calculus=="Mild"){
        ?>   
        <input name="calculus" id="calculus" onClick="save_dentalstuff('<?php echo $visit_id;?>','calculus',12)" type="radio" value="Mild" checked="checked" required="required">   
    <?php    }else{
        ?>   
        <input name="calculus" id="calculus" onClick="save_dentalstuff('<?php echo $visit_id;?>','calculus',12)" type="radio" value="Mild"  required="required">   
    <?php       
            }
       
        ?> None:             <?php
        if($calculus=="None"){
        ?>   
        <input name="calculus" id="calculus" onClick="save_dentalstuff('<?php echo $visit_id;?>','calculus',12)" type="radio" value="None" checked="checked" required="required">   
    <?php    }else{
        ?>   
        <input name="calculus" id="calculus" onClick="save_dentalstuff('<?php echo $visit_id;?>','calculus',12)" type="radio" value="None"  required="required">   
    <?php       
            }
       
        ?><br>

        
   <div class="navbar-inner"><p style="text-align:center; color:#0e0efe;">Oral Exam</p></div>
        
    <table align='center'>
         <tr>
            <td>Mucosa</td><td><textarea name="additional1" id="additional1" rows="5" onKeyUp="save_dentalstuff('<?php echo $visit_id;?>','additional1',1)" placeholder="Additional" style="width: 475px; height: 170px;"> <?php echo $oral_exam;?></textarea> </textarea></td>
           
            <td>Tongue</td><td><textarea name="additional2" id="additional2" rows="5" onKeyUp="save_dentalstuff('<?php echo $visit_id;?>','additional2',2)" placeholder="Additional Information" style="width: 475px; height: 170px;"  > <?php echo $tongue ?></textarea></td>
        </tr>
        <tr>
            <td>Palate</td><td><textarea name="additional3" id="additional3" rows="5" onKeyUp="save_dentalstuff('<?php echo $visit_id;?>','additional3',3)" placeholder="Additional Information" style="width: 475px; height: 170px;"  > <?php echo $palate ?></textarea></td>
           
         </tr>   
    </table>
     <div class="navbar-inner"><p style="text-align:center; color:#0e0efe;">Gingivae</p></div>
     <textarea name="additional4" id="additional4" rows="7" onKeyUp="save_dentalstuff('<?php echo $visit_id;?>','additional4',4)" placeholder="Additional Information" style="width: 560px; height: 170px;"  > <?php echo $gingivae ?></textarea>
          
           <div class="navbar-inner"><p style="text-align:center; color:#0e0efe;">Teeth</p></div>
           <textarea name="additional5" id="additional5" rows="7" onKeyUp="save_dentalstuff('<?php echo $visit_id;?>','additional5',5)" placeholder="Additional Information" style="width: 560px; height: 170px;"  > <?php echo $teeth ?></textarea>
          
    <!--        <div class="navbar-inner"><p style="text-align:center; color:#0e0efe;">Other Findings</p></div>
           <textarea name="additional6" id="additional6" rows="7" onKeyUp="save_dentalstuff('<?php echo $visit_id;?>','additional6',6)" placeholder="Additional Information" style="width: 560px; height: 170px;"  > <?php //echo $other_findings ?></textarea>-->
          
            <div class="navbar-inner"><p style="text-align:center; color:#0e0efe;">Xray Exam</p></div> 
             <form enctype="multipart/form-data" method="post" action="<?php echo 'http://sagana/hms/data/dental/save_xray.php?visit_id='.$visit_id?>">
          <input type="file" name="file" id="file" align="center" class="btn btn-large btn-primary" style="width: 300px;float:right;  " value="Select X-Ray Picture"/>
   <script>
      document.getElementById("file").onchange = function() {
       
    if(this.value) {
        document.getElementById("post_xray").disabled = false;
    } }
    </script>
       <input type="submit" disabled="disabled" name="post_xray" id="post_xray" align="center" class="btn-primary btn" style="float:right; " value="Upload X-Ray Picture"/>  
       </form>
           <textarea name="additional7" id="additional7" rows="7" onKeyUp="save_dentalstuff('<?php echo $visit_id;?>','additional7',7)" placeholder="Additional Information" style="width: 560px; height: 170px; float:left;"  > <?php echo $xray_exam ?></textarea>
      <div align="right" style="float:right;">
     
    <?php
        $getxr= new dental;
$rsxr=$getxr->select_dental_xray($visit_id);
$num_rowsxr=mysql_num_rows($rsxr);
for($xr=0; $xr < $num_rowsxr; $xr++){
    $denta_xray_name=mysql_result($rsxr, $xr, 'denta_xray_name');
    $denta_xray_id=mysql_result($rsxr, $xr, 'denta_xray_id');
   
//    echo site_url('../../../dental/puregold.jpg');
    ?>
  <a href="http://sagana/hms/dental/<?php echo $denta_xray_name  ?>" target="_blank"> <img src="http://sagana/hms/dental/<?php echo $denta_xray_name  ?>" alt="<?php echo $denta_xray_name;?>" title="<?php echo $denta_xray_name;?>" style="float:right;" height="210" width="210"> </a>
    <?php
    }
    ?>
       </div><br><br><br><br><br><br><br><br><br><br>

         <!--   <div class="navbar-inner"><p style="text-align:center; color:#0e0efe;">Radiography</p></div>
           <textarea name="additional8" id="additional8" rows="7" onKeyUp="save_dentalstuff('<?php //echo $visit_id;?>','additional8',8)" placeholder="Additional Information" style="width: 560px; height: 170px;"  > <?php //echo $radiography ?></textarea>-->
          
            <div class="navbar-inner"><p style="text-align:center; color:#0e0efe;">Notes/ Aesthetic Observations</p></div>
           <textarea name="additional9" id="additional9" rows="7" onKeyUp="save_dentalstuff('<?php echo $visit_id;?>','additional9',9)" placeholder="Additional Information" style="width: 560px; height: 170px;"  > <?php echo $aesthetic_observation ?></textarea>
          
            <div class="navbar-inner"><p style="text-align:center; color:#0e0efe;">Diagnosis</p></div>
           <textarea name="additional10" id="additional10" onKeyUp="save_dentalstuff('<?php echo $visit_id;?>','additional10',10)" rows="7" placeholder="Additional Information" style="width: 560px; height: 170px;"  > <?php echo $diagnosis ?></textarea>
       <?php }
      
       else {
           ?>
                <div class="navbar-inner"><p style="text-align:center; color:#0e0efe;">Oral Hygiene</p></div>
        Good : <input name="oral" id="oral" onClick="save_dentalstuff('<?php echo $visit_id;?>','oral',11)" type="radio" value="good" checked="checked" required="required"> Fair: <input name="oral" id="oral" onClick="save_dentalstuff('<?php echo $visit_id;?>','oral',11)" type="radio" value="fair" checked="checked" required="required"> Poor: <input name="oral" id="oral" onClick="save_dentalstuff('<?php echo $visit_id;?>','oral',11)" type="radio" value="poor" checked="checked" required="required"><br>
<br>

        <strong>CALCULUS ::</strong>
         Heavy : <input name="calculus" id="calculus" onClick="save_dentalstuff('<?php echo $visit_id;?>','calculus',12)" type="radio" value="Heavy" checked="checked" required="required"> Moderate: <input name="calculus" id="calculus" onClick="save_dentalstuff('<?php echo $visit_id;?>','calculus',12)" type="radio" value="Moderate" checked="checked" required="required"> Mild: <input name="calculus" id="calculus" onClick="save_dentalstuff('<?php echo $visit_id;?>','calculus',12)" type="radio" value="Mild" checked="checked" required="required"> None: <input name="calculus" id="calculus" onClick="save_dentalstuff('<?php echo $visit_id;?>','calculus',12)" type="radio" value="None" checked="checked" required="required"><br>

        
   <div class="navbar-inner"><p style="text-align:center; color:#0e0efe;">Oral Exam</p></div>
     
   <table align='center'>
         <tr>
            <td>Mucosa</td><td><textarea name="additional1" id="additional1" rows="5" onKeyUp="save_dentalstuff('<?php echo $visit_id;?>','additional1',1)" placeholder="Additional" style="width: 475px; height: 170px;"> <?php //echo $oral_exam;?></textarea> </textarea></td>
           
            <td>Tongue</td><td><textarea name="additional2" id="additional2" rows="5" onKeyUp="save_dentalstuff('<?php echo $visit_id;?>','additional2',2)" placeholder="Additional Information" style="width: 475px; height: 170px;"  > <?php //echo $tongue ?></textarea></td>
        </tr>
        <tr>
            <td>Palate</td><td><textarea name="additional3" id="additional3" rows="5" onKeyUp="save_dentalstuff('<?php echo $visit_id;?>','additional3',3)" placeholder="Additional Information" style="width: 475px; height: 170px;"  > <?php //echo $palate ?></textarea></td>
           
         </tr>   
    </table>
     <div class="navbar-inner"><p style="text-align:center; color:#0e0efe;">Gingivae</p></div>
     <textarea name="additional4" id="additional4" rows="7" onKeyUp="save_dentalstuff('<?php echo $visit_id;?>','additional4',4)" placeholder="Additional Information" style="width: 560px; height: 170px;"  > <?php //echo $gingivae ?></textarea>
          
           <div class="navbar-inner"><p style="text-align:center; color:#0e0efe;">Teeth</p></div>
           <textarea name="additional5" id="additional5" rows="7" onKeyUp="save_dentalstuff('<?php echo $visit_id;?>','additional5',5)" placeholder="Additional Information" style="width: 560px; height: 170px;"  > <?php ?></textarea>
          
        <!--    <div class="navbar-inner"><p style="text-align:center; color:#0e0efe;">Other Findings</p></div>
           <textarea name="additional6" id="additional6" rows="7" onKeyUp="save_dentalstuff('<?php //echo $visit_id;?>','additional6',6)" placeholder="Additional Information" style="width: 560px; height: 170px;"  > <?php ?></textarea>-->
          
            <div class="navbar-inner"><p style="text-align:center; color:#0e0efe;">Xray Exam/ Other Findings</p></div>
                   <form enctype="multipart/form-data" method="post" action="<?php echo 'http://sagana/hms/data/dental/save_xray.php?visit_id='.$visit_id?>">
          <input type="file" name="file" id="file" align="center" class="btn btn-large btn-primary" style="width: 300px;float:right;  " value="Select X-Ray Picture"/>
   <script>
      document.getElementById("file").onchange = function() {
       
    if(this.value) {
        document.getElementById("post_xray").disabled = false;
    } }
    </script>
       <input type="submit" disabled="disabled" name="post_xray" id="post_xray" align="center" class="btn-primary btn" style="float:right; " value="Upload X-Ray Picture"/>  
       </form>
           <textarea name="additional7" id="additional7" rows="7" onKeyUp="save_dentalstuff('<?php echo $visit_id;?>','additional7',7)" placeholder="Additional Information" style="width: 560px; height: 170px;"  > <?php ?></textarea>

         <!--   <div class="navbar-inner"><p style="text-align:center; color:#0e0efe;">Radiography</p></div>
           <textarea name="additional8" id="additional8" rows="7" onKeyUp="save_dentalstuff('<?php echo $visit_id;?>','additional8',8)" placeholder="Additional Information" style="width: 560px; height: 170px;"  > <?php ?></textarea>-->
          
            <div class="navbar-inner"><p style="text-align:center; color:#0e0efe;">Notes/ Aesthetic Observations</p></div>
           <textarea name="additional9" id="additional9" rows="7" onKeyUp="save_dentalstuff('<?php echo $visit_id;?>','additional9',9)" placeholder="Additional Information" style="width: 560px; height: 170px;"  > <?php ?></textarea>
          
            <div class="navbar-inner"><p style="text-align:center; color:#0e0efe;">Diagnosis</p></div>
           <textarea name="additional10" id="additional10" onKeyUp="save_dentalstuff('<?php echo $visit_id;?>','additional10',10)" rows="7" placeholder="Additional Information" style="width: 560px; height: 170px;"  > <?php ?></textarea>
           <?php } ?>   
<label><strong>Procedure</strong> </label>
<select id="procedure" name="procedure" style="width:30%" onChange="save_dental_procedure('<?php echo $visit_id ?>','procedure')">
            <option>SELECT PROCEDURE</option>
            <?php
            for($x=0; $x< $num_rows1; $x++){
            $service_charge_name=mysql_result($rs1, $x, 'service_charge_name');   
            $service_charge_id=mysql_result($rs1, $x, 'service_charge_id');
           $service_charge_amount=mysql_result($rs1, $x, 'service_charge_amount');

            ?>
            <option value="<?php echo $service_charge_id;?>"> <?php echo $service_charge_name.'   ---      ('.$service_charge_amount.')'; ?></option>
           
           <?php  }?>
             </select><br>
            
                   Patient Type: <?php echo $visit_type_name; ?>
             <table>
             <tr> <th> Procedure</th>  <th> Amount</th>  <th> Delete</th> </tr>
            <?php
             for($y=0; $y< $num_rows2; $y++){
                 $service_charge_id=mysql_result($rs2, $y, 'service_charge_id');
            $service_charge_name=mysql_result($rs2, $y, 'service_charge_name');               
            $visit_charge_amount=mysql_result($rs2, $y, 'visit_charge_amount');   
            $visit_charge_id=mysql_result($rs2, $y, 'visit_charge_id');
           
$get3= new dental;
$rs3=$get3->get_service_id($service_charge_id);
$num_rows3=mysql_num_rows($rs3);
$visit_charge_units=1;

$service_id= mysql_result($rs3, 0, "service_id");

//echo $service_id;
if($service_id==9){     

if($patient_insurance_id!=0){
    $sql1= "SELECT * FROM insurance_discounts WHERE insurance_id = (SELECT company_insurance_id FROM `patient_insurance` where patient_insurance_id =$patient_insurance_id) and service_id=9 ";
//echo $sql1;
    $rs1 = mysql_query($sql1)
        or die ("unable to Select ".mysql_error());
$num_type1= mysql_num_rows($rs1);

$percentage = mysql_result($rs1,0, "percentage");
$amount = mysql_result($rs1, 0, "amount");
$discounted_value="";   
        if($percentage==0){
            $discounted_value=$amount;   
            $amount = $visit_charge_amount *$discounted_value;           
            $total1=$amount;
    }
        elseif($amount==0){
            $discounted_value=$percentage;
            $amount = $visit_charge_amount *((100-$discounted_value)/100);
            //echo 'KK'.$visit_charge_amount;
            $total1=$amount * $visit_charge_units;
   
}
$total=$total1+$temp; $temp=$total;
}

else{
    $total1=$visit_charge_amount* $visit_charge_units;
    $total=$total1+$temp; $temp=$total;
   
    }
   

    ?> <form action="http://sagana/hms/index.php/doctor/dental_interface/<?php echo $visit_id; ?>/" method="post">
             <tr><td> <?php echo $service_charge_name; ?></td> <td><?php echo $total1; ?> </td>  <td>
             <input type="hidden" value="<?php echo $visit_charge_id ?>" name="vcid" id="vcid">
             <input type="submit" name="delete" id="delete" align="center" class="btn btn-large" style="width: 150px; " value="Delete"/> </td></tr>
        </form>    
             <?php
             if(isset($_POST['delete'])){
$vcid= $_POST['vcid'];
$save1= new dental;
$save1->delete_procedure($vcid);
?>
<script>
alert('<?php echo 'Successfully Deleted   '.$service_charge_name;?>');
window.location.href="http://sagana/hms/index.php/doctor/dental_interface/<?php echo $visit_id; ?>/";
</script>
<?php
   
}             }
        }     ?>
             <tr><td>.</td> <td><?php  echo $total; ?> </td> </tr>
             </table>
             <div class='navbar-inner'>
        <p style='text-align:center; color:#0e0efe;'>
            PRESCRIBE DRUGS<br/>
        <?php echo "
            <input type='button' class='btn btn-primary' value='Prescribe' onclick='open_window(1, ".$visit_id .")'/>
           
            <input type='button' class='btn btn-primary' value='Load Prescription' onclick='window.location.reload()'/><div>
   
    </div>";?>
            
             </div>
             <div id='prescription'> </div>
      
             <form action="http://sagana/hms/index.php/doctor/dental_interface/<?php echo $visit_id ?>" method="post">
               <input type="submit" name="update1" align="center" class="btn btn-large btn-primary" style="width: 300px; " value="BACK TO QUEUE"/>
              
               <input type="submit" name="update" align="center" class="btn btn-large btn-primary" style="width: 300px; " value="CLOSE DENTAL VISIT"/>
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