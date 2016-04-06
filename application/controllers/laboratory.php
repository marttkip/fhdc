<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include "welcome.php";
class Laboratory extends CI_Controller {

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
	
	function lab($output = null)
	{
		$this->load->view('laboratory.php',$output);	
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
	
	public function patient_name($value, $row)
	{
		$table = "patients, visit";
		$where = "visit.patient_id = patients.patient_id AND visit.visit_id = $value";
		$items = "patients.patient_surname, patients.patient_othernames";
		$order = "patient_surname";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0){
			foreach ($result as $row):
				$patient_surname = $row->patient_surname;
				$patient_othernames = $row->patient_othernames;
			endforeach;
		}
		
		$value = $patient_surname." ".$patient_othernames;
		
		return $value;
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
		if(($patient_date_of_birth!='0000-00-00') ||($patient_date_of_birth!='')){
		$value = $this->dateDiff(date('y-m-d  h:i'), $patient_date_of_birth." 00:00", 'year');
		}
		return $value;
	}
	
	public function calculate_gender($value, $row)
	{
		$result = $this->get_patient($row->patient_id);
		$visit_type="";
		$strath_no=""; $gender="";
		//if staff or student
		if($value == 0){
			if(count($result) > 0){
				foreach ($result as $row):
					$visit_type = $row->visit_type_id;
					$strath_no = $row->strath_no;
				endforeach;
			}
			
			//if student
			
			if($visit_type == 1){
				$get = new Welcome;
				$rs4 = $get->search_student($strath_no);
		
			$num_rows = mysql_num_rows($rs4);	
	if($num_rows > 0){
			$gender = mysql_result($rs4, 0, "gender");
			}}
			
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
	
	public function send_to_doctor($primary_key)
	{
		$delete = array(
        	"lab_visit" => 22
    	);
		$table = "visit";
		$key = $primary_key;
		$this->load->model('database', '',TRUE);
		$this->database->update_entry($table, $delete, $key);
		
		$this->lab_queue();
	}
	
	public function send_to_accounts($primary_key)
	{
		$delete = array(
        	"lab_visit" => 6
    	);
		$table = "visit";
		$key = $primary_key;
		$this->load->model('database', '',TRUE);
		$this->database->update_entry($table, $delete, $key);
		
		$this->from_reception();
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
		if($num_rowsp>0){
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
		else
		{		
		$rs = $this->search_staff($row->strath_no);
		$num_rows = mysql_num_rows($rs);

			$name = mysql_result($rs, 0, "Other_Names");	
		}	return $name;}
		else if($visit_type == 3){
			$name = $row->patient_othernames;
			$secondname =$row->patient_surname;
		}
		return $secondname." ".$name;
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
	public function lab_queue()
	{
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		
		$crud->where(array("close_card" => 0, "lab_visit" => 12));
        $crud->set_subject('Visit');
        $crud->set_table('visit');
		$crud->columns('visit_date', 'Patient', 'visit_time', 'Waiting Time', 'personnel_id', 'patient_id');
		$crud->fields('visit_date', 'Doctor', 'patient_id');
  		$crud->callback_column('Patient',array($this,'patient_names'));
		//$crud->set_relation("personnel_id", "personnel", "Dr. {personnel_fname} {personnel_onames}");
		$crud->add_action('Tests', base_url('img/new/icon-48-media.png'), 'laboratory/test');
		$crud->add_action('History', base_url('img/new/icon-48-calendar.png'), 'laboratory/test_history');
		$crud->add_action('To Doctor', base_url('img/new/icon-48-upload.png'), 'laboratory/send_to_doctor');
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
 
        $this->lab($output);
	}
	
	public function previous_tests()
	{
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		$where = "lab_visit = 22 OR lab_visit = 6";
		$crud->where($where);
        $crud->set_subject('Visit');
        $crud->set_table('visit');
		$crud->columns('visit_date', 'Patient', 'visit_time', 'Waiting Time', 'Doctor', 'patient_id');
		$crud->fields('visit_date', 'Doctor', 'patient_id');
  		$crud->callback_column('Patient',array($this,'patient_names'));
		$crud->set_relation_n_n('Doctor', 'schedule', 'personnel', 'schedule_id', 'personnel_id', 'Dr. {personnel_fname} {personnel_surname}');
		$crud->add_action('Print', base_url('img/new/icon-48-media.png'), 'laboratory/print_test');
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
 
        $this->lab($output);
	}
	
	public function print_test($primary_key)
	{
		?>
        	<script type="text/javascript">
				window.open("<?php echo base_url("data/lab/print_test.php?visit_id=".$primary_key)?>","Popup","height=900,width=1200,,scrollbars=yes,"+"directories=yes,location=yes,menubar=yes,"+"resizable=no status=no,history=no top = 50 left = 100");
			</script>
        <?php
		$this->previous_tests();
	}
	
	public function print_test2($primary_key)
	{
		?>
        	<script type="text/javascript">
				window.open("<?php echo base_url("data/lab/print_test.php?visit_id=".$primary_key)?>","Popup","height=900,width=1200,,scrollbars=yes,"+"directories=yes,location=yes,menubar=yes,"+"resizable=no status=no,history=no top = 50 left = 100");
			</script>
        <?php
		$this->test_history($primary_key);
	}
	
	public function from_reception()
	{
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		
		$crud->where(array("close_card" => 0, "lab_visit" => 5));
        $crud->set_subject('Visit');
        $crud->set_table('visit');
		$crud->columns('visit_date', 'Patient', 'visit_time', 'Waiting Time', 'personnel_id', 'patient_id');
		$crud->fields('visit_date', 'Doctor', 'patient_id');
  		$crud->callback_column('Patient',array($this,'patient_names'));
		$crud->set_relation("personnel_id", "personnel", "Dr. {personnel_fname} {personnel_onames}");
		$crud->add_action('Tests', base_url('img/new/icon-48-media.png'), 'laboratory/test2');
		$crud->add_action('Send to Accounts', base_url('img/new/icon-48-upload.png'), 'laboratory/send_to_accounts');
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
 
        $this->lab($output);
	}
	
	public function test_history($primary_key)
	{
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		$where = "visit_id = (SELECT visit_id FROM visit WHERE patient_id = (SELECT patient_id FROM visit WHERE visit_id = ".$primary_key.")) AND (lab_visit = 22 OR lab_visit = 6)";
		$crud->where($where);
        $crud->set_subject('Visit');
        $crud->set_table('visit');
		$crud->columns('visit_date', 'patient_id', 'visit_time', 'Waiting Time', 'Doctor');
		$crud->fields('visit_date', 'Doctor', 'patient_id');
		$crud->set_relation('patient_id', 'patients', '{patient_surname} {patient_othernames}');
		$crud->set_relation_n_n('Doctor', 'schedule', 'personnel', 'schedule_id', 'personnel_id', 'Dr. {personnel_fname} {personnel_surname}');
		$crud->add_action('Print', base_url('img/new/icon-48-media.png'), 'laboratory/print_test2');
		$crud->callback_column('Age',array($this,'calculate_age'));
		$crud->callback_column('Gender',array($this,'calculate_gender'));
        $crud->display_as('visit_time','Time In');
        $crud->display_as('visit_date','Visit Date');
        $crud->display_as('visit_time_out','Time Out');
        $crud->display_as('patient_id','Patient');
		$crud->required_fields('visit_date', 'Doctor');
		$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_delete();
        
        $output = $crud->render();
 
        $this->lab($output);
	}
	
	public function test($primary_key)
	{
		$data['visit_id'] = $primary_key;
		$data['visit'] = 1;
		$this->load->view('laboratory/test', $data);	
	}
	
	public function test2($primary_key)
	{
		$data['visit_id'] = $primary_key;
		$data['visit'] = 2;
		$this->load->view('laboratory/test', $data);	
	}
	public function test3($primary_key)
	{
		$data['visit_id'] = $primary_key;
		$data['visit'] = 3;
		$this->load->view('laboratory/test', $data);	
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
