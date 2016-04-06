<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include "welcome.php";
class Pharmacy extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->database();
		$this->load->helper('url');
		
		$this->load->library('grocery_CRUD');	
	}
	
	public function index()
	{
		echo "HELLO WORLD";
	}
	
	public function control_panel(){
		$this->load->view("control_panel");
	}
	
	public function clerk_control(){
		$this->load->view("clerk_control");
	}
	
	function pharmacy($output = null)
	{
		$this->load->view('stock.php',$output);	
	}
	
	public function get_patient($patient_id)
	{
		$table = "patients";
		$where = "patient_id = $patient_id";
		$items = "*";
		$order = "patient_surname";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	
	public function calculate_time($value, $row)
	{
  		$value = $this->dateDiff(date('y-m-d  h:i'), $row->visit_time, 'hour');
		
		return $row->visit_time;
	}
	
		public function patient_names($value, $row)
	{
		$visit_id = $row->visit_id;
		
		$table = "patients, visit";
		$where = "visit.visit_id = $visit_id AND visit.patient_id = patients.patient_id";
		$items = "patients.strath_no, patients.patient_surname, patients.patient_othernames, patients.visit_type_id";
		$order = "patient_surname";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		$strath_no="";
			$visit_type="";
			$secondname="";
			$name ="";
		if(count($result) > 0){
			
			foreach ($result as $row):
				$strath_no = $row->strath_no;
				$visit_type = $row->visit_type_id;
			endforeach;
		}
		
		if($visit_type == 1){
			$rs4 = $this->search_student($strath_no);
			$num_rows = mysql_num_rows($rs4);	
	if($num_rows > 0){
			$secondname = mysql_result($rs4, 0, "Other_names");
			
			$name = mysql_result($rs4, 0, "Surname");
		}
		else{
			$name = "Student Number ".$row->strath_no." Doesnt Exist in Our Records";
		}
		}
		
		else if($visit_type == 2){
		$name ="";		 //connect to database
        $connect = mysql_connect("localhost", "sumc_hms", "Oreo2014#")
                    or die("Unable to connect to MySQL".mysql_error());

        //selecting a database
        mysql_select_db("sumc", $connect)
                    or die("Could not select database".mysql_error()); 
		$sqlq = "select * from patients where strath_no='$row->strath_no' and dependant_id !=0";
		//echo $sqlq;
		$rsq = mysql_query($sqlq)
        or die ("unable to Select ".mysql_error());
		$num_rowsp=mysql_num_rows($rsq);
		
	//	echo NUMP.$num_rowsp;
		if($num_rowsp >0){
		$rsq = $this->search_staff_dependants($row->strath_no);
		$num_rowsq = mysql_num_rows($rsq);
		
		if($num_rowsq > 0)
		{
			$name = mysql_result($rsq, 0, "names").' '.mysql_result($rsq, 0, "other_names");
		}
		
		else
		{
			$name = 'Could not find Dependant';
		}
		}
		else{
			$rs = $this->search_staff($row->strath_no);
		$num_rows = mysql_num_rows($rs);
		
		$name = mysql_result($rs, 0, "Surname");
		$secondname = mysql_result($rs, 0, "Other_names");
		
		}}
		else if($visit_type == 3){
			$name = $row->patient_othernames;
			$secondname =$row->patient_surname;
		}
		return $secondname." ".$name;
	}
	function search_staff_dependants($strath_no){
		
		 //connect to database
        $connect = mysql_connect("localhost", "sumc_hms", "Oreo2014#")
                    or die("Unable to connect to MySQL".mysql_error());

        //selecting a database
        mysql_select_db("sumc", $connect)
                    or die("Could not select database".mysql_error());
					
	$sqlq = "select * from staff_dependants where staff_dependants_id='$strath_no'";
		
		//echo $sqlq;
	        $rsq = mysql_query($sqlq)
        or die ("unable to Select ".mysql_error());
		
		return $rsq;
	}
	public function patient_name2($value, $row)
	{
		$result = $this->get_patient($value);
		if(count($result) > 0){
			foreach ($result as $row2):
				$patient_surname = $row2->patient_surname;
				$patient_othernames = $row2->patient_othernames;
			endforeach;
		}
		
		$value = $patient_surname." ".$patient_othernames;
		
		return $value;
	}
	
	public function calculate_age($value, $row)
	{
		$patient_date_of_birth="";
		$result = $this->get_patient($row->patient_id);
		if(count($result) > 0){
			foreach ($result as $row):
				$patient_date_of_birth = $row->patient_date_of_birth;
			endforeach;
		}
		
		$value = $this->dateDiff(date('y-m-d  h:i'), $patient_date_of_birth." 00:00", 'year');
		
		return $value;
	}
	
	public function calculate_gender($value, $row)
	{
		$result = $this->get_patient($row->patient_id);
		
		//if staff or student
		if($value == 0){
			if(count($result) > 0){
				foreach ($result as $row):
					$visit_type = $row->visit_type_id;
					$strath_no = $row->strath_no;
				endforeach;
			}
			
			//if student 
			$visit_type=""; $gender="";
			if($visit_type == 1){
				$get = new Welcome;
				$rs4 = $get->search_student($strath_no);
			$num_rows = mysql_num_rows($rs4);	
	if($num_rows > 0){
			$gender = mysql_result($rs4, 0, "gender");
			
	
		}
		else{
			$name = "Student Number ".$row->strath_no." Doesnt Exist in Our Records";
		}
			}
			
			//if staff
			else if($visit_type == 2){
				$rs2 = $this->search_staff($strath_no);
				$gender = mysql_result($rs2, 0, "Gender");
			}
			
			//if other
			else if($visit_type == 3){
				
				if(count($result) > 0){
					foreach ($result as $row):
						$patient_gender = $row->gender_id;
					endforeach;
		
					$table = "gender";
					$where = "gender_id = $patient_gender";
					$items = "gender_name";
					$order = "gender_name";
		
					$this->load->model('database', '', TRUE);
					$result2 = $this->database->select_entries_where($table, $where, $items, $order);
					if(count($result2) > 0){
						foreach ($result2 as $row2):
							$gender = $row2->gender_name;
						endforeach;
					}
			
					else{
						$gender = "";
					}
					$value = $gender;
				}
		
				else{
					$value = "";
				}
			}
			$value = $gender;
		}
		
		//if outsider
		else{
		
			if(count($result) > 0){
				foreach ($result as $row):
					$patient_gender = $row->gender_id;
				endforeach;
		
				$table = "gender";
				$where = "gender_id = $patient_gender";
				$items = "gender_name";
				$order = "gender_name";
		
				$this->load->model('database', '', TRUE);
				$result2 = $this->database->select_entries_where($table, $where, $items, $order);
				if(count($result2) > 0){
					foreach ($result2 as $row2):
						$gender = $row2->gender_name;
					endforeach;
				}
			
				else{
					$gender = "";
				}
				$value = $gender;
			}
		
			else{
				$value = "";
			}
		}
		return $value;
	}
	
	public function time_difference($higher_time, $lower_time)
	{
		$seconds = strtotime($higher_time) - strtotime($lower_time);
		$hours = $seconds/3600;
		$hours_rounded = intval(($seconds/3600));
		$minutes = ($hours - $hours_rounded) * 60;
		$minutes_rounded = intval($minutes);
		$ms = ($minutes - $minutes_rounded) * 60;
		$ms_rounded = intval($ms);
		return $hours_rounded.":".$minutes_rounded.":".$ms_rounded;
	}
	
	public function waiting_time($value, $row)
	{
		$visit_id = $row->visit_id;
		$table = "visit";
		$where = "visit_id = ".$visit_id;
		$items = "visit_time, visit_time_out";
		$order = "visit_time";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0){
			foreach ($result as $row2):
				$visit_time = $row2->visit_time;
				$visit_time_out = $row2->visit_time_out;
			endforeach;
		}
		
		if($visit_time_out == "0000-00-00 00:00:00"){
			$time1 = date('y-m-d  H:i:s');
		}
		else{
			$time1 = $visit_time_out;
		}
		
		$time_difference = $this->time_difference($time1, $visit_time);
		return $time_difference;
	}
	
	public function service_charge($value, $row)
	{ $service_charge_name="";
		$pres_id = $row->prescription_id;
		
		$table = "pres, visit_charge, service_charge";
		$where = "pres.visit_charge_id = visit_charge.visit_charge_id AND visit_charge.service_charge_id = service_charge.service_charge_id AND pres.prescription_id = ".$pres_id;
		$items = "service_charge.service_charge_name,service_charge.service_charge_id";
		$order = "service_charge_id";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0){
			foreach ($result as $row):
				$service_charge_name = $row->service_charge_name;
			endforeach;
		}
		
		return $service_charge_name;
	}
		
	function search_student($strath_no){
	 //connect to database
        $connect = mysql_connect("localhost", "sumc_hms", "Oreo2014#")
                    or die("Unable to connect to MySQL".mysql_error());

        //selecting a database
        mysql_select_db("sumc", $connect)
                    or die("Could not select database".mysql_error());
		
		$sql = "select * from student where student_Number=$strath_no";
		//echo $sql;
	        $rs4 = mysql_query($sql)
        or die ("unable to Select ".mysql_error());
		
		$rows = mysql_num_rows($rs4);
		return $rs4;
	}
	
	function search_staff($strath_no){
		
		 //connect to database
        $connect = mysql_connect("localhost", "sumc_hms", "Oreo2014#")
                    or die("Unable to connect to MySQL".mysql_error());

        //selecting a database
        mysql_select_db("sumc", $connect)
                    or die("Could not select database".mysql_error());
		
		$sql = "select * from staff where Staff_Number='$strath_no'";
	        $rs = mysql_query($sql)
        or die ("unable to Select ".mysql_error());
		
		return $rs;
	}
	
	public function pharmacy_queue()
	{
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		
		$crud->where(array("close_card" => 0, "pharmarcy" => 6));
        $crud->set_subject('Visit');
        $crud->set_table('visit');
		$crud->columns('visit_date', 'Patient', 'visit_time', 'Waiting Time', 'Doctor', 'patient_id');
		$crud->fields('visit_date', 'Doctor', 'patient_id');
  		$crud->callback_column('Patient',array($this,'patient_names'));
		//$crud->set_relation_n_n('Doctor', 'schedule', 'personnel', 'schedule_id', 'personnel_id', 'Dr. {personnel_fname} {personnel_surname}');
		$crud->add_action('Prescription', base_url('img/new/icon-48-content.png'), 'pharmacy/prescription1');
		$crud->add_action('Send to Accounts', base_url('img/new/icon-48-upload.png'), 'pharmacy/send_to_accounts');
		$crud->callback_column('Waiting Time',array($this,'waiting_time'));
		$crud->callback_column('Age',array($this,'calculate_age'));
		$crud->callback_column('Gender',array($this,'calculate_gender'));
        $crud->display_as('visit_time','Time In');
        $crud->display_as('visit_date','Visit Date');
        $crud->display_as('visit_time_out','Time Out');
		$crud->required_fields('visit_date', 'Doctor');
		$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_delete();
        
        $output = $crud->render();
 
        $this->pharmacy($output);
	}
	
	public function from_reception()
	{
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		
		$crud->where(array("close_card" => 0, "pharmarcy" => 5));
        $crud->set_subject('Visit');
        $crud->set_table('visit');
		$crud->columns('visit_date', 'Patient', 'visit_time', 'Waiting Time', 'personnel_id', 'patient_id');
		$crud->fields('visit_date', 'Doctor', 'patient_id');
  		$crud->callback_column('Patient',array($this,'patient_names'));
		//$crud->set_relation_n_n('Doctor', 'schedule', 'personnel', 'schedule_id', 'personnel_id', 'Dr. {personnel_fname} {personnel_surname}');
				$crud->set_relation("personnel_id", "personnel", "Dr. {personnel_fname} {personnel_onames}");
		$crud->add_action('Prescription', base_url('img/new/icon-48-content.png'), 'pharmacy/prescription1');
		$crud->add_action('Send to Accounts', base_url('img/new/icon-48-upload.png'), 'pharmacy/send_to_accounts');
		$crud->callback_column('Waiting Time',array($this,'waiting_time'));
		$crud->callback_column('Age',array($this,'calculate_age'));
		$crud->callback_column('Gender',array($this,'calculate_gender'));
        $crud->display_as('visit_time','Time In');
        $crud->display_as('visit_date','Visit Date');
        $crud->display_as('visit_time_out','Time Out');
		$crud->required_fields('visit_date', 'Doctor');
		$crud->unset_add();
		$crud->unset_delete();
        
        $output = $crud->render();
 
        $this->pharmacy($output);
	}
	
	public function get_drug($value, $row)
	{
		//$employee_id = $row->personnel_id;
		$table = "visit_charge, service_charge";
		$where = "visit_charge.service_charge_id = service_charge.service_charge_id AND visit_charge.visit_charge_id = $value";
		$items = "service_charge_name";
		$order = "service_charge_name";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0){
			
			foreach ($result as $row2)
			{
				$service_charge_name = $row2->service_charge_name;
			}
		}
		
		return $service_charge_name;
	}
	
	public function prescription($primary_key)
	{
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
			
	//	echo $query;
		$query2="SELECT visit_charge_id FROM visit_charge WHERE visit_id =$primary_key";
		//echo $query2;
		$result2= mysql_query($query2);
		$row2 = mysql_num_rows($result2);
		$names = array();
		
			$query="SELECT visit_charge_id FROM visit_charge WHERE visit_id =$primary_key";
			
		//echo H.$row2;
if($row2==0){		

$names=0;
	$crud->or_where('pres.visit_charge_id',$names);
}
else {
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)){
	//echo $row;
	$names=$row['visit_charge_id'];
	$crud->or_where('pres.visit_charge_id',$names);
}}       $crud->set_subject('Drug');
        $crud->set_table('pres');
		$crud->columns('Drug','visit_charge_id','drug_consumption_id', 'prescription_quantity', 'drug_times_id', 'drug_duration_id','prescription_startdate', 'prescription_finishdate', 'prescription_substitution', 'Instructions', 'Warnings');
		//$crud->callback_column('Drug',array($this,'get_drugs'));
		$crud->fields('visit_charge_id', 'drug_consumption_id', 'prescription_quantity', 'drug_times_id', 'drug_duration_id','prescription_startdate', 'prescription_finishdate', 'prescription_substitution', 'Instructions', 'Warnings');
		$crud->callback_column('Drug',array($this,'service_charge'));
		$crud->set_relation('visit_charge_id', 'visit_charge', '{visit_charge_id}');
		$crud->set_relation('drug_times_id', 'drug_times', '{drug_times_name}');
		$crud->set_relation('drug_duration_id', 'drug_duration', '{drug_duration_name}');
		$crud->set_relation('drug_consumption_id', 'drug_consumption', '{drug_consumption_name}');
    	$crud->set_relation_n_n('Instructions', 'prescription_instructions', 'instructions', 'pres_id', 'instructions_id', 'instructions_name','count');
    	$crud->set_relation_n_n('Warnings', 'prescription_warnings', 'warnings', 'pres_id', 'warnings_id', 'warnings_name','count');
		$crud->field_type('visit_charge_id', 'hidden', $primary_key);
        $crud->display_as('prescription_quantity','Quantity');
        $crud->display_as('prescription_substitution','Substitution');
        $crud->display_as('prescription_startdate','Start Date');
        $crud->display_as('prescription_finishdate','Finish Date');
        $crud->display_as('drug_times_id','Times');
        $crud->display_as('drug_duration_id','Duration');
        $crud->display_as('drug_consumption_id','Method');
		$crud->unset_delete();
		$crud->unset_add();
        
        $output = $crud->render();
 
        $this->pharmacy($output);
	}
		public function prescription1($output = null)
	{
				$this->load->view('pharmacy/prescription.php',$output);	
	}
	public function compute_quantity($pst){
	
		$post1=$pst['prescription_quantity'];
		$start=$pst['prescription_startdate'];
		$finish=$pst['prescription_finishdate'];
		$drug_times_id=$pst['drug_times_id'];
		$visit_charge_id=$pst['visit_charge_id'];
		$drugs=$pst['drugs'];
	
		$query2="SELECT * FROM `drug_times` WHERE drug_times_id =$drug_times_id";
		echo $query2;
		$result3= mysql_query($query2);
		$row3 = mysql_fetch_array($result3);
		$time=$row3['numerical_value'];
		
$date = date_create($finish);
$your_date= date_format($date, 'Y-m-d');
  $your_date3 = strtotime($your_date);
	
$date1 = date_create($start);
$your_date1= date_format($date1, 'Y-m-d');
 $your_date2 = strtotime($your_date1);
 
    $datediff = $your_date3 -$your_date2;
    $qtty= floor($datediff/(60*60*24));
	$quantity_fin=$qtty*$time*$post1;
if($qtty>=30){
		$y=$qtty/30;
		
		}
		else{
		$y=$qtty*30;	
			
			}
			
			$tt=array_values($drugs);
			?>
            <script>
			alert('<?php echo $tt; ?>');
			</script>
            <?php

 }
	/*public function insert_visit_charge($primary_key){
			$session_log_insert = array(
        		"visit_id" => $primary_key['visit_charge_id'],
				"service_charge_id"=>'(select service_chsrge_id from service_charge where service_charge_name='.$primary_key['service_charge'].')'
				//"visit_charge_units"=>$primary_key['prescription_quantity']
				
    		);
			
			$table = "visit_charge";
			$this->load->model('database', '',TRUE);
			$this->database->insert_entry($table, $session_log_insert);		
			$ff=print_r($session_log_insert);
			?>
            <script>
			alert('<?php echo $ff; ?>');
			</script>
            <?php
	
			return $primary_key;
		
		}*/
	
	public function get_drugs($primary_key)
	{
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
			$crud->where(array("service_charge.service_charge_id" => "(SELECT service_charge_id FROM visit_charge WHERE visit_id = ".$primary_key.")"));
		$crud->set_subject('pres');
		$crud->set_table('service_charge');
		$crud->columns('service_charge_id','drug_id','service_charge_name');
		$crud->fields('service_charge_id','drug_id','service_charge_name');
	
		$crud->field_type('drug_id','Drug id');
		$crud->display_as('service_charge_id','hidden', $primary_key);
		$crud->display_as('service_charge_name','Service Charge Name');	        
        $output = $crud->render(); 
        $this->pharmacy($output);
		
	}
	public function send_to_accounts($primary_key)
	{
		$delete = array(
        	"pharmarcy" => 7
    	);
		$table = "visit";
		$key = $primary_key;
		$this->load->model('database', '',TRUE);
		$this->database->update_entry($table, $delete, $key);
		
		$this->pharmacy_queue();
	}
	
	public function dateDiff($time1, $time2, $interval) {
    // If not numeric then convert texts to unix timestamps
    if (!is_int($time1)) {
      $time1 = strtotime($time1);
    }
    if (!is_int($time2)) {
      $time2 = strtotime($time2);
    }
 
    // If time1 is bigger than time2
    // Then swap time1 and time2
    if ($time1 > $time2) {
      $ttime = $time1;
      $time1 = $time2;
      $time2 = $ttime;
    }
 
    // Set up intervals and diffs arrays
    $intervals = array('year','month','day','hour','minute','second');
    if (!in_array($interval, $intervals)) {
      return false;
    }
 
    $diff = 0;
    // Create temp time from time1 and interval
    $ttime = strtotime("+1 " . $interval, $time1);
    // Loop until temp time is smaller than time2
    while ($time2 >= $ttime) {
      $time1 = $ttime;
      $diff++;
      // Create new temp time from time1 and interval
      $ttime = strtotime("+1 " . $interval, $time1);
    }
 
    return $diff;
  }
}
