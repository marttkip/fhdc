<?php
$personelle_id=$this->uri->segment(3);
$date=$this->uri->segment(4);
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

class doctor_schedule{
	function doctors_schedule($personelle_id,$date){
			$sql= "SELECT * FROM visit where personnel_id='$personelle_id' and visit_date >= '$date' and time_start!=0 and time_end!=0";
			//echo $sql;
			$get = new Database();
			$rs = $get->select($sql);
			return $rs;
	}
		function doctors_names($personelle_id){
			$sql= "SELECT * FROM personnel where personnel_id='$personelle_id'";
			//echo $sql;
			$get = new Database();
			$rs = $get->select($sql);
			return $rs;
	}
	
	}
	

$get= new doctor_schedule;
$rs= $get->doctors_schedule($personelle_id,$date);
$num_rows= mysql_num_rows($rs);

if($num_rows==0){
	$get1= new doctor_schedule;
$rs1= $get1->doctors_names($personelle_id);
$num_rows= mysql_num_rows($rs1);
$name= mysql_result($rs1, 0, "personnel_fname");
$name2= mysql_result($rs1, 0, "personnel_onames");
echo '<h4 style="font-family:Palatino Linotype;">Appointment List from '.$date.' Onwards for Doctor '.$name.'	'.$name2.' </h4>';

echo 'No appointments for'.$name.'		'.$name2.' on '.$date;
}
else{
	$get1= new doctor_schedule;
$rs1= $get1->doctors_names($personelle_id);
$num_rows= mysql_num_rows($rs1);
$name= mysql_result($rs1, 0, "personnel_fname");
$name2= mysql_result($rs1, 0, "personnel_onames");
echo '<h4 style="font-family:Palatino Linotype;">Appointment List from '.$date.' Onwards for Doctor '.$name.'	'.$name2.' </h4>';
echo '<table> <tr> <th>Visit Date</th> <th>Start Time</th> <th> End Time</th>';
for($n=0; $n<$num_rows; $n++){
	$time_end=mysql_result($rs,$n,'time_end');
	$time_start=mysql_result($rs,$n,'time_start');
	$visit_date=mysql_result($rs,$n,'visit_date');
echo '<tr> <td>'.$visit_date.' </td><td>'.$time_start.' <td>'.$time_end.'</td> </tr>';
//echo $visit_date.'||'.	   			   '                '.$time_start.'||'.	   			   '           '.$time_end."<br>";
}

	
}
?>