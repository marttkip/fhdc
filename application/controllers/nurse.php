<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include "welcome.php";
class Nurse extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->database();
		$this->load->helper('url');
		
		$this->load->library('grocery_CRUD');	
	}
	
	public function index()
	{
		//echo "HELLO WORLD";
	}
	
	public function control_panel(){
		$this->load->view("control_panel");
	}
	
	public function clerk_control(){
		$this->load->view("clerk_control");
	}
	
	function nurse($output = null)
	{
		$this->load->view('nurse.php',$output);	
	}
	
	function doctor($output = null)
	{
		$this->load->view('doctor.php',$output);	
	}
		function dental_($output = null)
	{
		$this->load->view('dental/dental.php',$output);	
	}
	function lab($output = null)
	{
		$this->load->view('laboratory.php',$output);	
	}
	public function send_to_pharmacy($primary_key)
	{
		$delete = array(
        		"nurse_visit" => 1,
        	"pharmarcy" => 6,
			"doc_visit" => 1
    	);
		$table = "visit";
		$key = $primary_key;
		$this->load->model('database', '',TRUE);
		$this->database->update_entry($table, $delete, $key);
		
		$this->nurse_queue();
	}
		public function send_to_pharmacy_lab($primary_key)
	{
		$delete = array(
        		"nurse_visit" => 1,
        	"pharmarcy" => 6,
			"doc_visit" => 1,
			"lab_visit" => 0
    	);
		$table = "visit";
		$key = $primary_key;
		$this->load->model('database', '',TRUE);
		$this->database->update_entry($table, $delete, $key);
		
		$this->from_lab_queue();
	}
	
	public function send_to_labs($primary_key)
	{
		$delete = array(
        		"nurse_visit" => 1,
			 "lab_visit" => 12,
			 "doc_visit" => 1
    	);
		$table = "visit";
		$key = $primary_key;
		$this->load->model('database', '',TRUE);
		$this->database->update_entry($table, $delete, $key);
		
		$this->nurse_queue();
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
	
	public function from_lab_queue()
	{
			$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		
		$crud->where(array("close_card" => 0, "doc_visit" => 1, "nurse_visit" => 1, "lab_visit" => 22));
        $crud->set_subject('Visit');
        $crud->set_table('visit');
		$crud->columns('visit_date', 'Patient', 'visit_time', 'Waiting Time', 'Doctor');
		$crud->callback_column('Patient',array($this,'patient_names'));
		//$crud->set_relation('patient_id', 'patients', '{patient_surname} {patient_othernames}');
		$crud->set_relation_n_n('Doctor', 'schedule', 'personnel', 'schedule_id', 'personnel_id', 'Dr. {personnel_fname} {personnel_surname}');
		$crud->add_action('Vitals', base_url('img/new/icon-48-menu-add.png'), 'doctor/vitals');
		$crud->add_action('Lifestyle', base_url('img/new/icon-48-menu.png'), 'doctor/lifestyle');
		$crud->add_action('SOAP', base_url('img/new/icon-48-media.png'), 'doctor/soap');
		$crud->add_action('Patient History', base_url('img/new/icon-48-calendar.png'), 'doctor/patient_history');
		$crud->add_action('Medical Checkup', base_url('img/new/icon-48-stats.png'), 'welcome/med_check');
			$crud->add_action('To Pharmacy', base_url('img/new/icon-48-install.png'), 'nurse/send_to_pharmacy_lab');
		//$crud->add_action('To Lab', base_url('img/new/icon-48-preview.png'), 'doctor/send_to_lab');
		//$crud->add_action('To Pharmacy', base_url('img/new/icon-48-install.png'), 'doctor/send_to_pharmacy');
		$crud->callback_column('Age',array($this,'calculate_age'));
		$crud->callback_column('Gender',array($this,'calculate_gender'));
		$crud->callback_column('Waiting Time',array($this,'waiting_time'));
        $crud->display_as('visit_time','Time In');
        $crud->display_as('visit_date','Visit Date');
        $crud->display_as('visit_time_out','Time Out');
        $crud->display_as('patient_id','Patient');
		$crud->required_fields('visit_date', 'Doctor');
		$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_delete();
        
        $output = $crud->render();
 
        $this->nurse($output);
	}
	
	public function calculate_time($value, $row)
	{
  		$value = $this->dateDiff(date('y-m-d  h:i'), $row->visit_time, 'hour');
		
		return $row->visit_time;
	}
	
	public function procedure_cost($value, $row)
	{
		$table = "procedure, visit, visit_procedure, patients";
		$where = "visit.visit_id = ".$_SESSION['visit_id']." AND visit.patient_id = patients.patient_id AND visit.visit_id = visit_procedure.visit_id AND visit_procedure.procedure_id = procedure.procedure_id";
		$items = "procedure.students, procedure.staff, procedure.outsiders, patients.visit_type_id";
		$order = "patient_surname";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0){
			foreach ($result as $row2):
				$visit_type_id = $row2->visit_type_id;
				
				if($visit_type_id == 1){
					
					$price = $row2->staff;
				}
				
				else if($visit_type_id == 2){
					
					$price = $row2->students;
				}
				
				else{
					
					$price = $row2->outsiders;
				}
				
			endforeach;
		}
		
		$value = $price * $row->units;
		
		return $value;
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
		$result = $this->get_patient($row->patient_id);
		if(count($result) > 0){
			foreach ($result as $row):
				$patient_date_of_birth = $row->patient_date_of_birth;
			endforeach;
		}
		$patient_date_of_birth="";
		$value = $this->dateDiff(date('y-m-d  h:i'), $patient_date_of_birth." 00:00", 'year');
		
		return $value;
	}
	
	public function calculate_gender($value, $row)
	{
		$result = $this->get_patient($row->patient_id);
		$visit_type ="";
		$gender="";
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
				//$gender = mysql_result($rs2, 0, "Gender");
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
        	"nurse_visit" => 1
    	);
		$table = "visit";
		$key = $primary_key;
		$this->load->model('database', '',TRUE);
		$this->database->update_entry($table, $delete, $key);
		
		$this->nurse_queue();
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

		if($num_rows > 0)
		{
			$name = mysql_result($rs, 0, "Other_Names");	
		}
		
		else
		{
			$name = 'Could not find Staff';
		}
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
		////echo $sql;
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
		
		////echo $sqlq;
	        $rsq = mysql_query($sqlq)
        or die ("unable to Select ".mysql_error());
		
		return $rsq;
	}
	public function nurse_queue()
	{
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		
		$crud->where(array("close_card" => 0, "nurse_visit" => 0));
        $crud->set_subject('Visit');
        $crud->set_table('visit');
		$crud->columns('visit_date', 'Patient', 'visit_time', 'Waiting Time', 'personnel_id', 'patient_id');
		$crud->fields('visit_date', 'Doctor', 'patient_id');
  		$crud->callback_column('Patient',array($this,'patient_names'));
		//$crud->set_relation("personnel_id", "personnel", "Dr. {personnel_fname} {personnel_onames}");
		$crud->add_action('Vitals', base_url('img/new/icon-48-stats.png'), 'nurse/vitals');
		$crud->add_action('Dental Vitals', base_url('img/new/tooth1.jpeg'), 'doctor/dental');
		$crud->add_action('Lifestyle', base_url('img/new/icon-48-menu.png'), 'nurse/lifestyle');
		$crud->add_action('Patient History', base_url('img/new/icon-48-calendar.png'), 'nurse/patient_history');
		$crud->add_action('SOAP', base_url('img/new/icon-48-module.png'), 'nurse/soap');
		$crud->add_action('Send to Doctor', base_url('img/new/icon-48-upload.png'), 'nurse/send_to_doctor');
		
		$crud->add_action('To Lab', base_url('img/new/icon-48-preview.png'), 'nurse/send_to_labs');
		$crud->add_action('To Pharmacy', base_url('img/new/icon-48-install.png'), 'nurse/send_to_pharmacy');
		
		$crud->add_action('Medical Checkup', base_url('img/new/icon-48-stats.png'), 'welcome/med_check_nurse');
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
 
        $this->nurse($output);
	}
	
	public function patient_history($primary_key)
	{
		
		$table = "visit";
		$where = "visit_id = ".$primary_key;
		$items = "patient_id";
		$order = "patient_id";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0){
			foreach ($result as $row2):
				$patient_id = $row2->patient_id;
			endforeach;
		}
		
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		
		$crud->where(array("close_card" => 1, "patient_id" => $patient_id));
        $crud->set_subject('Visit');
        $crud->set_table('visit');
		$crud->columns('visit_date', 'Patient', 'visit_time', 'Waiting Time', 'Doctor', 'patient_id');
		$crud->fields('visit_date', 'Doctor', 'patient_id');
  		$crud->callback_column('Patient',array($this,'patient_names'));
		$crud->set_relation_n_n('Doctor', 'schedule', 'personnel', 'schedule_id', 'personnel_id', 'Dr. {personnel_fname} {personnel_surname}');
		$crud->add_action('Vitals', base_url('img/new/icon-48-stats.png'), 'nurse/vitals');
		$crud->add_action('Lifestyle', base_url('img/new/icon-48-menu.png'), 'nurse/lifestyle');
		$crud->add_action('SOAP', base_url('img/new/icon-48-module.png'), 'nurse/soap');
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
 
        $this->nurse($output);
	}
	
	public function general_queue($id)
	{
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		
		$crud->where(array("close_card" => 0));
        $crud->set_subject('Visit');
        $crud->set_table('visit');
		$crud->columns('visit_date', 'Patient', 'visit_time', 'Waiting Time', 'personnel_id', 'patient_id');
		$crud->fields('visit_date', 'Doctor', 'patient_id');
  		$crud->callback_column('Patient',array($this,'patient_names'));
		//$crud->set_relation_n_n('Doctor', 'schedule', 'personnel', 'schedule_id', 'personnel_id', 'Dr. {personnel_fname} {personnel_surname}');
		//$crud->set_relation("personnel_id", "personnel", "Dr. {personnel_fname} {personnel_onames}");
		$crud->callback_column('Waiting Time',array($this,'waiting_time'));
		$crud->callback_column('Age',array($this,'calculate_age'));
		$crud->callback_column('Gender',array($this,'calculate_gender'));
        $crud->display_as('visit_time','Time In');
        $crud->display_as('visit_date','Visit Date');
        $crud->display_as('visit_time_out','Time Out');
		$crud->required_fields('visit_date', 'Doctor');
		
		if(($id == 1) || ($id == 2)){
			
			$crud->add_action('Vitals', base_url('img/new/icon-48-stats.png'), 'nurse/vitals');
			$crud->add_action('Lifestyle', base_url('img/new/icon-48-menu.png'), 'nurse/lifestyle');
					$crud->add_action('Dental Vitals', base_url('img/new/tooth1.jpeg'), 'doctor/dental');
			$crud->add_action('Patient History', base_url('img/new/icon-48-calendar.png'), 'nurse/patient_history');
			$crud->add_action('Medical Checkup', base_url('img/new/icon-48-stats.png'), 'welcome/med_check');
		}
		if($id == 1){
			$crud->add_action('SOAP', base_url('img/new/icon-48-module.png'), 'nurse/soap');
		}
		if($id == 2){
			$crud->add_action('SOAP', base_url('img/new/icon-48-media.png'), 'doctor/soap');
		}
		if($id == 3){
			//$crud->add_action('Tests', base_url('img/new/icon-48-media.png'), 'laboratory/test');
			//$crud->add_action('Tests', base_url('img/new/icon-48-media.png'), 'laboratory/test2');
			$crud->add_action('Tests', base_url('img/new/icon-48-media.png'), 'laboratory/test3');
			$crud->add_action('History', base_url('img/new/icon-48-calendar.png'), 'laboratory/test_history');
			$crud->add_action('To Doctor', base_url('img/new/icon-48-upload.png'), 'laboratory/send_to_doctor');
		}
		if($id == 4){
		$crud->add_action('Prescription', base_url('img/new/icon-48-content.png'), 'pharmacy/prescription1');
		}
		if($id==5){
			$crud->add_action('Dentist', base_url('img/new/iii'), 'doctor/dental_interface');
		$crud->add_action('Dental Vitals', base_url('img/new/iii'), 'doctor/dental_vitals');
			}
		//$crud->callback_column('Waiting Time',array($this,'calculate_time'));
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
 
        if($id == 2){
			$this->doctor($output);
		}
 
        else if($id == 3){
			$this->lab($output);
		}
		
		else if($id == 1){
			$this->nurse($output);
		}
		
		else if($id == 4){
			$this->pharmacy($output);
		}
		else if($id == 5){
			$this->dental_($output);
		}
	}
	
	public function soap($primary_key)
	{
		$data['visit_id'] = $primary_key;
		$this->load->view('nurse/soap', $data);	
	}
	
	public function vitals($primary_key)
	{
		$data['visit_id'] = $primary_key;
		$this->load->view('nurse/vitals', $data);
	}
	public function medical_checkup($primary_key)
	{
		$data['visit_id'] = $primary_key;
		$this->load->view('nurse/medical_checkup', $data);
	}
	
	public function lifestyle($primary_key)
	{
		$table = "patients, visit";
		$where = "visit.patient_id = patients.patient_id AND visit.visit_id = $primary_key";
		$items = "patients.patient_id";
		$order = "patient_id";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		if(count($result) > 0){
			foreach ($result as $row):
				$patient_id = $row->patient_id;
			endforeach;
		}
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		
		$crud->where(array("lifestyle.patient_id" => $patient_id));
        $crud->set_subject('Lifestyle');
        $crud->set_table('lifestyle');
		$crud->set_relation('excersise_id', 'excersise', '{excersise_name}');
		$crud->set_relation('excersise_duration_id', 'excersise_duration', '{excersise_duration_name}');
		$crud->set_relation('sleep_id', 'sleep', '{sleep_name}');
		$crud->set_relation('meals_id', 'meals', '{meals_name}');
		$crud->set_relation('coffee_id', 'coffee', '{coffee_name}');
		$crud->set_relation('housing_id', 'housing', '{housing_name}');
		$crud->set_relation('education_id', 'education', '{education_name}');
		$crud->callback_column('patient_id',array($this,'patient_name2'));
		$crud->field_type('patient_id', 'hidden', $patient_id);
        $crud->display_as('patient_id','Patient');
        $crud->display_as('excersise_id','Excersise');
        $crud->display_as('excersise_duration_id','Excersise Duration');
        $crud->display_as('sleep_id','Sleep');
        $crud->display_as('meals_id','Meals');
        $crud->display_as('coffee_id','Coffee');
        $crud->display_as('housing_id','Housing');
        $crud->display_as('education_id','Education');
        $crud->display_as('lifestyle_diet','Diet');
        $crud->display_as('lifestyle_drugs','Drugs');
        $crud->display_as('lifestyle_alcohol_percentage','Alcohol %');
        $crud->display_as('lifestyle_alcohol_quantity','Alcohol Qty');
		$crud->unset_delete();
        
        $output = $crud->render();
 
        $this->nurse($output);
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

	public function appointment_list()
	{
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		
		$crud->where(array("appointment_id" => 1, "close_card" => 2));
        $crud->set_subject('Appointment');
        $crud->set_table('visit');
		$crud->columns('visit_date', 'patient_id', 'time_start', 'time_end', 'personnel_id');
		$crud->fields('visit_date', 'personnel_id', 'patient_id','appointment_id','close_card');
		//$crud->set_relation('patient_id', 'patients', '{patient_surname} {patient_othernames}');
			$crud->callback_column('patient_id',array($this,'patient_names'));
		$crud->set_relation('personnel_id','personnel','Dr. {personnel_onames} {personnel_fname}');
		//$crud->field_type('patient_pp','dropdown',array($this,'patient_names'));
			
  		//$crud->callback_column('patient_id',array($this,'patient_names'));
		//$crud->add_action('Initiate Visit', base_url('img/new/icon-48-notice.png'), 'welcome/initiate_visit_appointment');
        $crud->display_as('time_end','End Time');
        $crud->display_as('visit_date','Visit Date');
        $crud->display_as('time_start','Time Start');
        $crud->display_as('patient_id','Patient');
		$crud->required_fields('personnel_id', 'Doctor');
		//$crud->field_type('appointment_id', 'hidden', 1);
		//$crud->field_type('close_card', 'hidden', 2);
		$crud->unset_delete();
		$crud->unset_add();
		$crud->unset_edit();
        
        $output = $crud->render();
 
         $this->nurse($output);
	}
	
}