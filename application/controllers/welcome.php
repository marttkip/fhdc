<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require('authentication.php');

class Welcome extends authentication {

	function __construct()
	{
		parent::__construct();
		
		$this->load->library('grocery_CRUD');
	}
	
	public function control_panel($personnel_id){
		
		$data['personnel_id'] = $personnel_id;
		$this->load->view("control_panel", $data);
	}
	
	public function index()
	{
		$this->load->view('login');
	}
	
	public function index2($message)
	{
		$data['message'] = $message;
		$this->load->view('login', $data);
	}
	
	function _example_output($output = null)
	{
		$this->load->view('administration.php',$output);	
	}
	
		function change_password($output = null)
	{
		$this->load->view('change_passowrd.php',$output);	
	}
	function _example_output3($output = null)
	{
		$this->load->view('accounts.php',$output);	
	}
	
	function _example_output2($output = null)
	{
		$this->load->view('clerk.php',$output);	
	}
	function doc_schedule()
	{
		$this->load->view('show_shcedule.php');	
	}
		function staff_dependants()
	{
		$this->load->view('staff_dependants.php');	
	}
	
		function staff_sbs()
	{
		$this->load->view('sbs.php');	
	}
	function new_staff_dependants()
	{
		$this->load->view('new_dependant.php');	
	}
	function nurse($output = null)
	{
		$this->load->view('nurse.php',$output);	
	}

	public function login()
	{
		$table = "personnel";
		$where = "personnel.personnel_username = '".addslashes($_POST['username'])."'";
		$items = "personnel.personnel_password, personnel.personnel_id";
		$order = "personnel_password";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		foreach ($result as $row)
		{
			$password = $row->personnel_password;
		}
		
		if(empty($password)){
			$this->index2("Username or Password is incorrect");
		}
		
		elseif($password == md5($_POST['password'])){
		
			$_SESSION['personnel_id'] = $row->personnel_id;
			$this->control_panel($_SESSION['personnel_id']);
		
			$session_log_insert = array(
        		"personnel_id" => $_SESSION['personnel_id'], 
        		"session_name_id" => 1
    		);
			$table = "session";
			$this->load->model('database', '',TRUE);
			$this->database->insert_entry($table, $session_log_insert);
		}
				
		elseif($password != md5($_POST['password'])){
			$this->index2("Your Passowrd is incorrect");
		}
		else{
			$this->index2("You have not been assigned to a department");
		}
	}
	
	public function logout()
	{
		$session_log_insert = array(
        	"personnel_id" => $_SESSION['personnel_id'], 
        	"session_name_id" => 2
    	);
		$table = "session";
		$this->load->model('database', '',TRUE);
		$this->database->insert_entry($table, $session_log_insert);
		$_SESSION['personnel_id'] = NULL;
		$this->session->sess_destroy();
		$this->index();
	}
	
	public function patient_number()
	{
  		$table = "patients";
		$items = "MAX(patient_number) AS number";
		$order = "number";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries($table, $items, $order);
		
		if(count($result) > 0){
			
			foreach ($result as $row2)
			{
				$number = $row2->number;
				$number++;
			}
		}
		return $number;
	}
	public function add_dependant($primary_key)
	{
		$this->update_company_names();
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
 
 		$crud->where(array('patient_status' => 0, 'patients.visit_type_id' => 3, 'dependant_id'=>$primary_key));
        $crud->set_subject('Patient');
        $crud->set_table('patients');
		
		$crud->add_action('Lab Visit', base_url('img/new/icon-48-content.png'), 'welcome/initiate_lab');		
		$crud->add_action('Pharmacy Visit', base_url('img/new/icon-48-upload.png'), 'welcome/initiate_pharmacy');
		$crud->add_action('Visit', base_url('img/new/icon-48-category-add.png'), 'welcome/initiate_visit');
		$crud->add_action('Add Dependant', base_url('img/new/kid.png'), 'welcome/add_dependant');
		//$crud->add_action('Add Insurance', base_url('img/new/insurance-icon.png'), 'welcome/add_insurance');
		//$crud->fields('title_id', 'patient_surname', 'patient_othernames', 'patient_date_of_birth', 'patient_picture', 'civil_status_id', 'patient_postalcode', 'patient_town', 'patient_phone1', 'patient_phone2', 'patient_email', 'patient_national_id', 'religion_id', 'gender_id','patient_kin_othernames', 'patient_kin_sname', 'patient_kin_phonenumber1', 'patient_kin_phonenumber2','relationship_id','Languages','Insurance','insurance_id');
		$crud->set_relation('gender_id','gender', '{gender_name}');
		$crud->set_relation('title_id','title', '{title_name}');
		$crud->set_relation('visit_type_id','visit_type', '{visit_type_name}');
		$crud->set_relation('civil_status_id','civil_status', '{civil_status_name}');
		$crud->set_relation('religion_id','religion', '{religion_name}');
		$crud->set_relation('relationship_id','relationship', '{relationship_name}');	
		
		$crud->set_relation_n_n('Languages', 'patient_language', 'language','patient_id', 'language_id', 'language_name', 'count');
		$crud->required_fields('patient_surname', 'patient_othernames', 'gender_id');
		$crud->columns('patient_number', 'patient_date', 'title_id', 'patient_surname', 'patient_othernames', 'patient_date_of_birth', 'patient_phone1', 'patient_email', 'gender_id');
		$crud->set_field_upload('patient_picture', 'assets/uploads/files');
		$crud->unset_fields('patient_date', 'patient_status', 'nurse_notes', 'patient_number', 'strath_no', 'patient_bloodgroup','dependant_id','strath_type_id');
		$crud->set_relation_n_n('Insurance', 'patient_insurance', 'company_insuarance','patient_id', 'company_insurance_id', '{company_name} - {insurance_company_name}', 'count');
        $crud->display_as('patient_number','Clinic No.');
        $crud->display_as('patient_othernames','Other Names');
        $crud->display_as('patient_surname','Surname');
        $crud->display_as('patient_date_of_birth','Age');
       $crud->callback_column('patient_date_of_birth',array($this,'dob'));
        $crud->display_as('patient_phone1','Contact');
        $crud->display_as('title_id','Title');
        $crud->display_as('visit_type_id','Patient Type');
        $crud->display_as('patient_email','Email');
        $crud->display_as('patient_sex','Gender');
        $crud->display_as('patient_date','Registration Date');
        $crud->display_as('gender_id','Gender');
        $crud->display_as('visit_type_id','Patient_type');
        $crud->display_as('civil_status_id','Civil Status');
        $crud->display_as('religion_id','Religion');
        $crud->display_as('relationship_id','Kin Relationship');
		$crud->field_type('visit_type_id', 'hidden', 3);
		$crud->field_type('visit_type_id', 'hidden', 3);
		$crud->field_type('visit_type_id', 'hidden', 3);
		$crud->callback_add_field('insurance_id',array($this,'insurance_id_field'));
		$_SESSION['table'] = 'patients';
		$crud->callback_after_insert(array($this,'insert_log'));
		$crud->callback_after_update(array($this,'update_log'));
		$crud->callback_delete(array($this,'delete_patient'));
        $crud->field_type('dependant_id', 'hidden', $primary_key);
        $output = $crud->render();
 
        $this->_example_output2($output);
	}
	
	public function patient_registration()
	{
		$this->update_company_names();
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
 
 		$crud->where(array('patient_status' => 0, 'patients.visit_type_id' => 3));
        $crud->set_subject('Patient');
        $crud->set_table('patients');
		
		$crud->add_action('Lab Visit', base_url('img/new/icon-48-content.png'), 'welcome/initiate_lab');		
		$crud->add_action('Pharmacy Visit', base_url('img/new/icon-48-upload.png'), 'welcome/initiate_pharmacy');
		$crud->add_action('Visit', base_url('img/new/icon-48-category-add.png'), 'welcome/initiate_visit');
		$crud->add_action('Add Dependant', base_url('img/new/kid.png'), 'welcome/add_dependant');
		//$crud->add_action('Add Insurance', base_url('img/new/insurance-icon.png'), 'welcome/add_insurance');
		//$crud->fields('title_id', 'patient_surname', 'patient_othernames', 'patient_date_of_birth', 'patient_picture', 'civil_status_id', 'patient_postalcode', 'patient_town', 'patient_phone1', 'patient_phone2', 'patient_email', 'patient_national_id', 'religion_id', 'gender_id','patient_kin_othernames', 'patient_kin_sname', 'patient_kin_phonenumber1', 'patient_kin_phonenumber2','relationship_id','Languages','Insurance','insurance_id');
		$crud->set_relation('gender_id','gender', '{gender_name}');
		$crud->set_relation('title_id','title', '{title_name}');
		$crud->set_relation('visit_type_id','visit_type', '{visit_type_name}');
		$crud->set_relation('civil_status_id','civil_status', '{civil_status_name}');
		$crud->set_relation('religion_id','religion', '{religion_name}');
		$crud->set_relation('relationship_id','relationship', '{relationship_name}');	
		
		$crud->set_relation_n_n('Languages', 'patient_language', 'language','patient_id', 'language_id', 'language_name', 'count');
		$crud->required_fields('patient_surname', 'patient_othernames', 'gender_id');
		$crud->columns('patient_number', 'patient_date', 'title_id', 'patient_surname', 'patient_othernames', 'patient_date_of_birth', 'patient_phone1', 'patient_email', 'gender_id');
		$crud->set_field_upload('patient_picture', 'assets/uploads/files');
		$crud->unset_fields('patient_date', 'patient_status', 'nurse_notes', 'patient_number', 'strath_no', 'patient_bloodgroup','dependant_id','strath_type_id');
		$crud->set_relation_n_n('Insurance', 'patient_insurance', 'company_insuarance','patient_id', 'company_insurance_id', '{company_name} - {insurance_company_name}', 'count');
        $crud->display_as('patient_number','Clinic No.');
        $crud->display_as('patient_othernames','Other Names');
        $crud->display_as('patient_surname','Surname');
        $crud->display_as('patient_date_of_birth','Age');
       $crud->callback_column('patient_date_of_birth',array($this,'dob'));
        $crud->display_as('patient_phone1','Contact');
        $crud->display_as('title_id','Title');
        $crud->display_as('visit_type_id','Patient Type');
        $crud->display_as('patient_email','Email');
        $crud->display_as('patient_sex','Gender');
        $crud->display_as('patient_date','Registration Date');
        $crud->display_as('gender_id','Gender');
        $crud->display_as('visit_type_id','Patient_type');
        $crud->display_as('civil_status_id','Civil Status');
        $crud->display_as('religion_id','Religion');
        $crud->display_as('relationship_id','Kin Relationship');
		$crud->field_type('visit_type_id', 'hidden', 3);
		$crud->field_type('visit_type_id', 'hidden', 3);
		$crud->field_type('visit_type_id', 'hidden', 3);
		$crud->callback_add_field('insurance_id',array($this,'insurance_id_field'));
		$_SESSION['table'] = 'patients';
		$crud->callback_after_insert(array($this,'insert_log'));
		$crud->callback_after_update(array($this,'update_log'));
		$crud->callback_delete(array($this,'delete_patient'));
        
        $output = $crud->render();
 
        $this->_example_output2($output);
	}
	function insurance_id_field()
{
  return '<input type="text" maxlength="50" value="" name="insurance_id" id="insurance_id">';
}
	
	public function dob($date)
	{
	//$margin = $date['patient_date_of_birth'];
	if($date == 0 ){
		
		return "";
		}else{
 list($year,$month,$day) = explode("-",$date);
 
    $year_diff  = date("Y") - $year;
    $month_diff = date("m") - $month;
    $day_diff   = date("d") - $day;
    if ($day_diff < 0 || $month_diff < 0) $year_diff--;
    return round( $year_diff,0);
		}
	}
	public function staff()
	{
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
 
 		$crud->where(array('patient_status' => 0, 'patients.visit_type_id' => 2));
        $crud->set_subject('Patient');
        $crud->set_table('patients');
		$crud->add_action('Lab Visit', base_url('img/new/icon-48-content.png'), 'welcome/initiate_lab');
		$crud->add_action('Pharmacy Visit', base_url('img/new/icon-48-upload.png'), 'welcome/initiate_pharmacy');
		$crud->add_action('Visit', base_url('img/new/icon-48-category-add.png'), 'welcome/initiate_visit');
		$crud->add_action('Add Dependant', base_url('img/new/kid.png'), 'welcome/staff_dependants');
		$crud->set_relation('visit_type_id','visit_type', '{visit_type_name}');
		$crud->set_relation('civil_status_id','civil_status', '{civil_status_name}');
		$crud->set_relation('religion_id','religion', '{religion_name}');
		$crud->set_relation('relationship_id','relationship', '{relationship_name}');
		$crud->set_relation_n_n('Languages', 'patient_language', 'language','patient_id', 'language_id', 'language_name', 'count');
		$crud->required_fields('patient_surname', 'patient_othernames', 'gender_id');
		$crud->columns('patient_number', 'title_id', 'Staff-Dependant relation', 'patient_surname', 'patient_othernames', 'patient_date_of_birth', 'patient_phone1', 'gender_id', 'strath_no');
		$crud->set_field_upload('patient_picture', 'assets/uploads/files');
		$crud->fields('strath_no', 'visit_type_id');
		$crud->required_fields('strath_no');
  		$crud->callback_column('patient_othernames',array($this,'staff_fname'));
  		$crud->callback_column('patient_surname',array($this,'staff_surname'));
  		$crud->callback_column('title_id',array($this,'staff_title'));
  		$crud->callback_column('patient_date_of_birth',array($this,'staff_dob'));
  		$crud->callback_column('patient_phone1',array($this,'staff_phone'));
  		//$crud->callback_column('patient_email',array($this,'staff_email'));
  		$crud->callback_column('gender_id',array($this,'staff_gender'));
		$crud->callback_column('Staff-Dependant relation',array($this,'relation'));
        $crud->display_as('patient_number','Clinic No.');
        $crud->display_as('patient_othernames','Other Names');
        $crud->display_as('patient_surname','Surame');
        $crud->display_as('patient_date_of_birth','Date of Birth');
        $crud->display_as('patient_phone1','Contact');
        $crud->display_as('title_id','Title');
        $crud->display_as('visit_type_id','Patient Type');
        $crud->display_as('patient_email','Email');
        $crud->display_as('patient_sex','Gender');
        $crud->display_as('patient_date','Registration Date');
        $crud->display_as('gender_id','Gender');
        $crud->display_as('visit_type_id','Patient_type');
        $crud->display_as('civil_status_id','Civil Status');
        $crud->display_as('religion_id','Religion');
        $crud->display_as('relationship_id','Kin Relationship');
        $crud->display_as('strath_no','Staff Number');
		$crud->field_type('visit_type_id', 'hidden', 2);
				
		
		$_SESSION['table'] = 'patients';
		$crud->callback_after_insert(array($this,'insert_log'));
		$crud->callback_after_update(array($this,'update_log'));
		$crud->callback_delete(array($this,'delete_patient'));

		$crud->callback_before_insert(array($this,'check_available_staff'));
        
        $output = $crud->render();
 
        $this->_example_output2($output);
	}
	public function check_available_staff($post_array){
		$staff_id=$post_array['strath_no'];
		
		//connect to database
        $connect = mysql_connect("localhost", "sumc_hms", "Oreo2014#")
                    or die("Unable to connect to MySQL".mysql_error());

        //selecting a database
        mysql_select_db("strathmore_population", $connect)
                    or die("Could not select database".mysql_error());
		
		$sql = "select * from staff where Staff_Number='$staff_id'";
	    $rs = mysql_query($sql)
        or die ("unable to Select ".mysql_error());
		$num_rows=mysql_num_rows($rs);
		
		if($num_rows==0){
	
			
		 //connect to database
        $connect = mysql_connect("192.168.170.16", "medical", "Med_centre890")
                    or die("Unable to connect to MySQL".mysql_error());

        //selecting a database
        mysql_select_db("hr", $connect)
                    or die("Could not select database".mysql_error());
		
			
			$sql1 = "select `employee`.`Employee_ID` AS `E_ID`, `employee`.`Employee_Code` AS `Employee_Code`,`employee`.`ID_No` AS `ID_No`,`employee`.`Title` AS `Title`,`employee`.`Surname` AS `Surname`,`employee`.`Other_Name` AS `Other_Name`,`employee`.`Gender` AS `Gender`,`employee`.`DOB` AS `DOB`,`employee`.`Nationality` AS `Nationality`,`employee`.`Marital_Status` AS `Marital_Status`,`dept`.`Dept` AS `Dept`,`emp_post`.`Post` AS `Post`,`contact`.`Tel_1` AS `Tel_1`,`contact`.`Address_2` AS `Address_2`,`contact`.`Postal_Code` AS `Postal_Code`,`contact`.`Email` AS `Email`,`contact`.`City` AS `City` from (((`employee` join `emp_post` on((`employee`.`Employee_ID` = `emp_post`.`Employee_ID`))) join `contact` on((`employee`.`Contact_ID` = `contact`.`Contact_ID`))) join `dept` on((`employee`.`Dept_ID` = `dept`.`Dept_ID`))) where `Employee_Code=$staff_id";
				echo $sql1.'<br />';
        $rs1 = mysql_query($sql1)
		
        or die ("unable to Select ".mysql_error());
		$row2 = mysql_num_rows($rs1);
		for($a=0; $a< $row2; $a++){
		    $E_ID=mysql_result($rs1, $a,'E_ID');
			$Employee_Code=mysql_result($rs1, $a,'Employee_Code');
			$ID_No=mysql_result($rs1, $a,'ID_No');
			$DOB=mysql_result($rs1, $a,'DOB');
			$Surname1=mysql_result($rs1, $a,'Surname');
			$Other_Name1=mysql_result($rs1, $a,'Other_Name');
			$Nationality=mysql_result($rs1, $a,'Nationality');
			$Marital_Status=mysql_result($rs1, $a,'Marital_Status');
			$Email=mysql_result($rs1, $a,'Email');
			$Gender=mysql_result($rs1, $a,'Gender');	$Title=mysql_result($rs1, $a,'Title');	$Tel_1=mysql_result($rs1, $a,'Tel_1');
			
			
		echo	$E_ID.'-->'.$Surname.'-->'.$Other_Name.'-->'.$E_ID.'-->'.$Employee_Code.'-->'.$DOB.'-->'.$Tel_1.'<br />';
		
		
		 //connect to database
        $connect = mysql_connect("localhost", "sumc_hms", "Oreo2014#")
                    or die("Unable to connect to MySQL".mysql_error());

        //selecting a database
        mysql_select_db("strathmore_population", $connect)
                    or die("Could not select database".mysql_error());
		
			$Surname=str_replace("'", "", "$Surname1");
			$Other_Name=str_replace("'", "", "$Other_Name1");
			$sql2 = "insert into staff (title,Surname,Other_names,DOB,contact,gender,Staff_Number,staff_system_id) values('$Title','$Surname','$Other_Name','$DOB','$Tel_1','$Gender','$Employee_Code','$E_ID')";
				echo $sql2.'<br />';
		     $rs2 = mysql_query($sql2)  or  die ("unable to Select ".mysql_error());
      
		
	}
            
				$sql2 = "delete * from patients where  	strath_no='$staff_id'";
	    $rs2 = mysql_query($sql2)
        or die ("unable to Select ".mysql_error());
			
				}
		else{
			
			
			}	
		
		}
		public function check_available_student($post_array){
		$student_id=$post_array['strath_no'];
			
		//connect to database
        $connect = mysql_connect("localhost", "sumc_hms", "Oreo2014#")
                    or die("Unable to connect to MySQL".mysql_error());

        //selecting a database
        mysql_select_db("strathmore_population", $connect)
                    or die("Could not select database".mysql_error());
		
		$sql = "select * from student where student_Number='$student_id'";
		////echo $sql;
	    $rs = mysql_query($sql)
        or die ("unable to Select ".mysql_error());
		$num_rows=mysql_num_rows($rs);
		
		if($num_rows==0){
			
         $conn = oci_connect('AMS_QUERIES',' MuYaibu1','192.168.170.228:1521/STRATHMO');
	
		if (!$conn) {
		
			$e = oci_error();
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			?>
        <script>
		window.alert('<?php echo 'Unable to connect student server right now, please try registering student later'; ?>');
		
		</script>
        <?php	
		$sql2 = "delete * from patients where  	strath_no='$student_id'";
	    $rs2 = mysql_query($sql2)
        or die ("unable to Select ".mysql_error());
		}
		else{
		
		$sql = "SELECT * FROM   GAOWNER.VIEW_STUDENT_DETAILS WHERE STUDENT_NO='$student_id'  ";
	
		$rs4 = OCIParse($conn, $sql);
   		OCIExecute($rs4, OCI_DEFAULT);
		$rows = oci_num_rows($rs4);	
		$t=0;
				while (OCIFetch($rs4)) {
					$t++;
			$name1=ociresult($rs4, "SURNAME");
			$dob=ociresult($rs4, "DOB");
			$gender=ociresult($rs4, "GENDER");		
			$oname1=ociresult($rs4, "OTHER_NAMES");
				$STUDENT_NO=ociresult($rs4, "STUDENT_NO");
			$COURSES=ociresult($rs4, "COURSES");
			$GUARDIAN_NAME1=ociresult($rs4, "GUARDIAN_NAME");
	$MOBILE_NO=ociresult($rs4, "MOBILE_NO");
		$EMAIL=ociresult($rs4, "EMAIL");
		$FACULTIES=ociresult($rs4, "FACULTIES");
		 //connect to database
        $connect = mysql_connect("localhost", "sumc_hms", "Oreo2014#")
                    or die("Unable to Connect To Student Server".mysql_error());

        //selecting a database
        mysql_select_db("strathmore_population", $connect)
                    or die("Could not select database".mysql_error());

	
			$name=str_replace("'", "", "$name1");
			$oname=str_replace("'", "", "$oname1");
			$GUARDIAN_NAME=str_replace("'", "", "$GUARDIAN_NAME1");
			$sql2 = "INSERT INTO `strathmore_population`.`student` (`title`, `Surname`, `Other_names`, `DOB`, `contact`, `gender`, `student_Number`, `courses`, `GUARDIAN_NAME`)
			
 VALUES ('', '$name', '$oname', '$dob', '$MOBILE_NO', '$gender', '$STUDENT_NO', '$COURSES', '$GUARDIAN_NAME')";
				echo $sql2.'<br />';
		  $rs2 = mysql_query($sql2)  or  die ("unable to Select ".mysql_error());
  //echo 'TT'.$t;    
}
//echo $t;

}
			
				}
		else{
			
			
			}	
		
		}
	
	public function students()
	{
	$crud = new grocery_CRUD(); $crud->set_theme('datatables');
 	$crud->where(array('patient_status' => 0, 'patients.visit_type_id' => 1));
        $crud->set_subject('Patient');
        $crud->set_table('patients');
		$crud->add_action('Lab Visit', base_url('img/new/icon-48-content.png'), 'welcome/initiate_lab');
		$crud->add_action('Pharmacy Visit', base_url('img/new/icon-48-upload.png'), 'welcome/initiate_pharmacy');
		$crud->add_action('Visit', base_url('img/new/icon-48-category-add.png'), 'welcome/initiate_visit');
		$crud->add_action('Check Balance', base_url('img/new/icon-48-plugin.png'), 'welcome/check_balance');
		$crud->set_relation('visit_type_id','visit_type', '{visit_type_name}');
		$crud->set_relation('civil_status_id','civil_status', '{civil_status_name}');
		$crud->set_relation('religion_id','religion', '{religion_name}');
		$crud->set_relation('relationship_id','relationship', '{relationship_name}');
		$crud->set_relation_n_n('Languages', 'patient_language', 'language','patient_id', 'language_id', 'language_name', 'count');
		$crud->required_fields('patient_surname', 'patient_othernames', 'gender_id');
		$crud->columns('patient_number', 'patient_date', 'title_id', 'patient_surname', 'patient_othernames', 'patient_date_of_birth', 'patient_phone1', 'patient_email', 'gender_id', 'strath_no');
		$crud->set_field_upload('patient_picture', 'assets/uploads/files');
		$crud->fields('strath_no', 'visit_type_id');
		$crud->required_fields('strath_no');
  		 $crud->callback_column('patient_othernames',array($this,'student_fname'));
  		 $crud->callback_column('patient_surname',array($this,'student_surname'));
  		$crud->callback_column('title_id',array($this,'student_title'));
  		 $crud->callback_column('patient_date_of_birth',array($this,'student_dob'));
  		$crud->callback_column('patient_phone1',array($this,'student_phone'));
  		$crud->callback_column('patient_email',array($this,'student_email'));
  		 $crud->callback_column('gender_id',array($this,'student_gender'));
		// $crud->callback_column('Account',array($this,'charges_visit'));
        $crud->display_as('patient_number','Clinic No.');
        $crud->display_as('patient_othernames','Other Names');
        $crud->display_as('patient_surname','Surame');
        $crud->display_as('patient_date_of_birth','Date of Birth');
        $crud->display_as('patient_phone1','Contact');
        $crud->display_as('title_id','Title');
        $crud->display_as('visit_type_id','Patient Type');
        $crud->display_as('patient_email','Email');
        $crud->display_as('patient_sex','Gender');
        $crud->display_as('patient_date','Registration Date');
        $crud->display_as('gender_id','Gender');
        $crud->display_as('visit_type_id','Patient_type');
        $crud->display_as('civil_status_id','Civil Status');
        $crud->display_as('religion_id','Religion');
        $crud->display_as('relationship_id','Kin Relationship');
        $crud->display_as('strath_no','Student Number');
		$crud->field_type('visit_type_id', 'hidden', 1);
		$crud->callback_before_insert(array($this,'check_available_student'));
		$_SESSION['table'] = 'patients';
		$crud->callback_after_insert(array($this,'insert_log'));
		$crud->callback_after_update(array($this,'update_log'));
		$crud->callback_delete(array($this,'delete_patient'));
		
        $output = $crud->render();
 
        $this->_example_output2($output);
	}
	function check_balance($output = null){
		
		$this->load->view('check_bal.php', $output);
    }
	function make_payment($output = null){
		
		$this->load->view('check_bal_payment.php', $output);
    }
		function med_check($output = null){
		
		$this->load->view('medical exam.php', $output);
    }
		function med_check_nurse($output = null){
		
		$this->load->view('nurse_medical_checkup.php', $output);
    }
function search_student($strath_no){
	 //connect to database
        $connect = mysql_connect("localhost", "sumc_hms", "Oreo2014#")
                    or die("Unable to connect to MySQL".mysql_error());

        //selecting a database
        mysql_select_db("strathmore_population", $connect)
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
        mysql_select_db("strathmore_population", $connect)
                    or die("Could not select database".mysql_error());
		
		$sql = "select * from staff where Staff_Number='$strath_no'";
		////echo $sql;
	        $rs = mysql_query($sql)
        or die ("unable to Select ".mysql_error());
		
		return $rs;
	}
	function search_staff_dependants($strath_no){
		
		 //connect to database
        $connect = mysql_connect("localhost", "sumc_hms", "Oreo2014#")
                    or die("Unable to connect to MySQL".mysql_error());

        //selecting a database
        mysql_select_db("strathmore_population", $connect)
                    or die("Could not select database".mysql_error());
					
	$sqlq = "select * from staff_dependants where staff_dependants_id='$strath_no'";
		
		////echo $sqlq;
	        $rsq = mysql_query($sqlq)
        or die ("unable to Select ".mysql_error());
		
		return $rsq;
	}
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function dependants_name($value, $row)
	{
		$rsq = $this->search_staff_dependants($row->strath_no);
		$num_rowsq = mysql_num_rows($rsq);
		if($num_rowsq > 0){
			$names = mysql_result($rsq, 0, "names");
		}
		else{
			$names = "Staff Number ".$row->strath_no." Doesnt Exist in Our Records";
		}
		return $names;
	}
	
	public function dependants_occupation($value, $row)
	{
		$rsq = $this->search_staff_dependants($row->strath_no);
		$num_rowsq = mysql_num_rows($rsq);
		
		if($num_rowsq > 0){
			for($a=0; $a<$num_rowsq; $a++){
			$occupation = mysql_result($rsq, $a, "occupation");
			
			}
			
		}
		else{
			$occupation = "Staff Number ".$row->strath_no." Doesnt Exist in Our Records";
		}
		return $occupation;
	}
	
	public function dependants_relation($value, $row)
	{
		$rsq = $this->search_staff_dependants($row->strath_no);
		$num_rowsq = mysql_num_rows($rsq);
		if($num_rowsq > 0){
			$relation = mysql_result($rsq, 0, "relation");
		}
		else{
			$relation = "Staff Number ".$row->strath_no." Doesnt Exist in Our Records";
		}
		return $relation;
	}
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function staff_fname($value, $row)
	{
			$name="";		 //connect to database
        $connect = mysql_connect("localhost", "sumc_hms", "Oreo2014#")
                    or die("Unable to connect to MySQL".mysql_error());

        //selecting a database
        mysql_select_db("sumc", $connect)
                    or die("Could not select database".mysql_error()); 
		$sqlq = "select * from patients where strath_no='$row->strath_no' and dependant_id !=0";
		////echo $sqlq;
		$rsq = mysql_query($sqlq)
        or die ("unable to Select ".mysql_error());
		$num_rowsp=mysql_num_rows($rsq);
		
	//	////echo NUMP.$num_rowsp;
		if($num_rowsp>0){
		$rsq = $this->search_staff_dependants($row->strath_no);
		$num_rowsq = mysql_num_rows($rsq);
		
			$name = mysql_result($rsq, 0, "names");
		}		
		else
		{		
		$rs = $this->search_staff($row->strath_no);
		$num_rows = mysql_num_rows($rs);

			$name = mysql_result($rs, 0, "Other_Names");	
		}	return $name;
	}
	
		public function relation($value, $row)
	{
			$relation ="";		 //connect to database
        $connect = mysql_connect("localhost", "sumc_hms", "Oreo2014#")
                    or die("Unable to connect to MySQL".mysql_error());

        //selecting a database
        mysql_select_db("sumc", $connect)
                    or die("Could not select database".mysql_error()); 
		$sqlq = "select * from patients where strath_no='$row->strath_no' and dependant_id !=0";
		//////echo $sqlq;
		$rsq = mysql_query($sqlq)
        or die ("unable to Select ".mysql_error());
		$num_rowsp=mysql_num_rows($rsq);
		
	//	////echo NUMP.$num_rowsp;
		if($num_rowsp>0){
		$rsq = $this->search_staff_dependants($row->strath_no);
		$num_rowsq = mysql_num_rows($rsq);
	
			$relation = mysql_result($rsq, 0, "relation");
		
		}	return $relation;
	}
	
	public function staff_surname($value, $row)
	{
						$name ="";		 //connect to database
        $connect = mysql_connect("localhost", "sumc_hms", "Oreo2014#")
                    or die("Unable to connect to MySQL".mysql_error());

        //selecting a database
        mysql_select_db("sumc", $connect)
                    or die("Could not select database".mysql_error()); 
		$sqlq = "select * from patients where strath_no='$row->strath_no' and dependant_id !=0";
		////echo $sqlq;
		$rsq = mysql_query($sqlq)
        or die ("unable to Select ".mysql_error());
		$num_rowsp=mysql_num_rows($rsq);
		
	//	////echo NUMP.$num_rowsp;
		if($num_rowsp>0){
		$rsq = $this->search_staff_dependants($row->strath_no);
		$num_rowsq = mysql_num_rows($rsq);
		}
		else{
			$rs = $this->search_staff($row->strath_no);
		$num_rows = mysql_num_rows($rs);
		
			$name = mysql_result($rs, 0, "Surname");
		}
			
		return $name;
		
		
}
	
	public function staff_title($value, $row)
	{
				$relation ="";		 //connect to database
        $connect = mysql_connect("localhost", "sumc_hms", "Oreo2014#")
                    or die("Unable to connect to MySQL".mysql_error());

        //selecting a database
        mysql_select_db("sumc", $connect)
                    or die("Could not select database".mysql_error()); 
		$sqlq = "select * from patients where strath_no='$row->strath_no' and dependant_id !=0";
		////echo $sqlq;
		$rsq = mysql_query($sqlq)
        or die ("unable to Select ".mysql_error());
		$num_rowsp=mysql_num_rows($rsq);
		
	//	////echo NUMP.$num_rowsp;
		if($num_rowsp>0){
		$rsq = $this->search_staff_dependants($row->strath_no);
		$num_rowsq = mysql_num_rows($rsq);
		}
		else{
			$rs = $this->search_staff($row->strath_no);
		$num_rows = mysql_num_rows($rs);
		
			$relation = mysql_result($rs, 0, "title");
		}
		return $relation;
		
	}
	
	public function staff_dob($value, $row)
	{
		$name ="";		 //connect to database
        $connect = mysql_connect("localhost", "sumc_hms", "Oreo2014#")
                    or die("Unable to connect to MySQL".mysql_error());

        //selecting a database
        mysql_select_db("sumc", $connect)
                    or die("Could not select database".mysql_error()); 
		$sqlq = "select * from patients where strath_no='$row->strath_no' and dependant_id !=0";
		////echo $sqlq;
		$rsq = mysql_query($sqlq)
        or die ("unable to Select ".mysql_error());
		$num_rowsp=mysql_num_rows($rsq);
		
	//	////echo NUMP.$num_rowsp;
		if($num_rowsp>0){
		$rsq = $this->search_staff_dependants($row->strath_no);
		$num_rowsq = mysql_num_rows($rsq);
		
	$name = mysql_result($rsq, 0, "DOB");
		}
		else{
			$rs = $this->search_staff($row->strath_no);
		$num_rows = mysql_num_rows($rs);
		
			$name = mysql_result($rs, 0, "DOB");
		}
		return $name;
	}
	
	public function staff_phone($value, $row)
	{
			$name ="";		 //connect to database
        $connect = mysql_connect("localhost", "sumc_hms", "Oreo2014#")
                    or die("Unable to connect to MySQL".mysql_error());

        //selecting a database
        mysql_select_db("sumc", $connect)
                    or die("Could not select database".mysql_error()); 
		$sqlq = "select * from patients where strath_no='$row->strath_no' and dependant_id !=0";
		////echo $sqlq;
		$rsq = mysql_query($sqlq)
        or die ("unable to Select ".mysql_error());
		$num_rowsp=mysql_num_rows($rsq);
		
	//	////echo NUMP.$num_rowsp;
		if($num_rowsp>0){
		$rsq = $this->search_staff_dependants($row->strath_no);
		$num_rowsq = mysql_num_rows($rsq);

		//	$relation = mysql_result($rsq, 0, "relation");
		}
		else{
			$rs = $this->search_staff($row->strath_no);
		$num_rows = mysql_num_rows($rs);
	
			$name = mysql_result($rs, 0, "contact");
		}
		
		return $name;
	
	}
	
	public function staff_email($value, $row)
	{
		$rs = $this->search_staff($row->strath_no);
		$num_rows = mysql_num_rows($rs);
		if($num_rows > 0){
			$name = mysql_result($rs, 0, "Email");
		}
		else{
			$name = "Staff Number ".$row->strath_no." Doesnt Exist in Our Records";
		}
		return $name;
	}
	
	public function staff_gender($value, $row)
	
	{
		$name ="";		 //connect to database
        $connect = mysql_connect("localhost", "sumc_hms", "Oreo2014#")
                    or die("Unable to connect to MySQL".mysql_error());

        //selecting a database
        mysql_select_db("sumc", $connect)
                    or die("Could not select database".mysql_error()); 
		$sqlq = "select * from patients where strath_no='$row->strath_no' and dependant_id !=0";
		////echo $sqlq;
		$rsq = mysql_query($sqlq)
        or die ("unable to Select ".mysql_error());
		$num_rowsp=mysql_num_rows($rsq);
		
	//	////echo NUMP.$num_rowsp;
		if($num_rowsp>0){
		$rsq = $this->search_staff_dependants($row->strath_no);
		$num_rowsq = mysql_num_rows($rsq);
		//	$relation = mysql_result($rsq, 0, "relation");
		}
		else{
			$rs = $this->search_staff($row->strath_no);
		$num_rows = mysql_num_rows($rs);
	
			$name = mysql_result($rs, 0, "Gender");
	}
			return $name;
		}
	
	public function student_fname($value, $row)
	{ 
		$rs4 = $this->search_student($row->strath_no);
		$num_rows = mysql_num_rows($rs4);
		$name="";
	if($num_rows > 0){
			$name = mysql_result($rs4, 0, "Other_names");
		}
		else{
			$name = "Student Number ".$row->strath_no." Doesnt Exist in Our Records";
		}
		return $name;
	}
	
	public function student_surname($value, $row)
	{
		$rs4 = $this->search_student($row->strath_no);
		$name="";
		$num_rows = mysql_num_rows($rs4);
		
	if($num_rows > 0){
			$name = mysql_result($rs4, 0, "Surname");
		}
		else{
			$name = "Student Number ".$row->strath_no." Doesnt Exist in Our Records";
		}
		return $name;
	}
	
	public function student_title($value, $row)
	{
		$name = "";
		return $name;
	}
	
	public function student_dob($value, $row)
	{
		$rs4 = $this->search_student($row->strath_no);
		$dob="";
		$num_rows = mysql_num_rows($rs4);
		
	if($num_rows > 0){
			$dob = mysql_result($rs4, 0, "DOB");
		}
		else{
			$dob= "Student Number ".$row->strath_no." Doesnt Exist in Our Records";
		}
		return $dob;
	}
	
	public function student_phone($value, $row)
	{
		$name = "";
		return $name;
	}
	
	public function student_email($value, $row)
	{
		$name = "";
		return $name;
	}
	
	public function student_gender($value, $row)
	{
		$rs4 = $this->search_student($row->strath_no);
		$gender="";
		$num_rows = mysql_num_rows($rs4);
		
	if($num_rows > 0){
			$gender = mysql_result($rs4, 0, "gender");
		}
		else{
			$gender = "Student Number ".$row->strath_no." Doesnt Exist in Our Records";
		}
		return $gender;
	}
	
	public function visit_list()
	{
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		
		$crud->where(array("close_card" => 0));
        $crud->set_subject('Visit');
        $crud->set_table('visit');
		$crud->add_action('End Visit', base_url('img/new/icon-48-language.png'), 'welcome/end_visit');
		$crud->columns('visit_date', 'patient_id', 'visit_type', 'visit_time', 'visit_time_out', 'personnel_id');
		$crud->fields('visit_date', 'Doctor', 'patient_id');
		$crud->set_relation('visit_type', 'visit_type', '{visit_type_name}');
		//$crud->set_relation_n_n('Doctor', 'schedule', 'personnel', 'schedule_id', 'personnel_id', 'Dr. {personnel_fname} {personnel_surname}');
  		$crud->callback_column('patient_id',array($this,'patient_names'));
		$crud->set_relation("personnel_id", "personnel", "Dr. {personnel_fname} {personnel_onames}");
        $crud->display_as('visit_time','Time In');
        $crud->display_as('visit_date','Visit Date');
        $crud->display_as('visit_time_out','Time Out');
        $crud->display_as('patient_id','Patient');
        $crud->display_as('personnel_id','Doctor');
		$crud->required_fields('visit_date', 'Doctor');
		$crud->unset_delete();
		$crud->unset_add();
		$crud->unset_edit();
        
        $output = $crud->render();
		$this->_example_output2($output);
	}
	
	public function visit_history()
	{
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		//$crud->set_theme('datatables');
		
		$crud->where(array("close_card" => 1));
        $crud->set_subject('Visit');
        $crud->set_table('visit');
		$crud->columns('visit_date', 'patient_id', 'visit_time', 'visit_time_out', 'Doctor');
		$crud->fields('visit_date', 'Doctor', 'patient_id');
		//$crud->set_relation('patient_id', 'patients', '{patient_surname} {patient_othernames}');
		$crud->set_relation_n_n('Doctor', 'schedule', 'personnel', 'schedule_id', 'personnel_id', 'Dr. {personnel_fname} {personnel_surname}');
  		$crud->callback_column('patient_id',array($this,'patient_names'));
        $crud->display_as('visit_time','Time In');
        $crud->display_as('visit_date','Visit Date');
        $crud->display_as('visit_time_out','Time Out');
        $crud->display_as('patient_id','Patient');
		$crud->required_fields('visit_date', 'Doctor');
		$crud->unset_delete();
		$crud->unset_add();
		$crud->unset_edit();
        
        $output = $crud->render();
 
        $this->_example_output2($output);
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
		$crud->add_action('Initiate Visit', base_url('img/new/icon-48-notice.png'), 'welcome/initiate_visit_appointment');
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
 
        $this->_example_output2($output);
	}
	public function initiate_visit_appointment($primary_key){
			$delete = array(
        	"close_card" => 0
    	);
		$table = "visit";
		$key = $primary_key;
		
		$this->load->model('database', '',TRUE);
		$this->database->update_entry($table, $delete, $key);
		$this->visit_list();
		
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
		
		$name = mysql_result($rsq, 0, "names");
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
	
	public function patient_names2($patient_id)
	{
		$table = "patients";
		$where = "patient_id = $patient_id";
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
		
		$name = mysql_result($rsq, 0, "names");
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
	
	public function end_visit($primary_key)
	{
		$delete = array(
        	"close_card" => 1,
        	"visit_time_out" => date('y-m-d  H:i:s')
    	);
		$table = "visit";
		$key = $primary_key;
		$this->load->model('database', '',TRUE);
		$this->database->update_entry($table, $delete, $key);
		$this->visit_list();
	}
	
	public function get_visit_type_id($patient_id)
	{
		$table = "patients";
		$where = "patient_id = ".$patient_id."";
		$items = "visit_type_id";
		$order = "visit_type_id";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0){
			foreach ($result as $row):
				$visit_type_id = $row->visit_type_id;
			endforeach;
		}
		else{
			$visit_type_id = 0;
		}
		return $visit_type_id;
	}
	
	public function get_service_charges($patient_id)
	{
		$table = "service_charge";
		$where = "service_charge.service_id = 1 AND service_charge.visit_type_id = (SELECT visit_type_id FROM patients WHERE patient_id = $patient_id)";
		$items = "service_charge.service_charge_name, service_charge_id";
		$order = "service_charge_name";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	
	public function get_service_charge($id)
	{
		$table = "service_charge";
		$where = "service_charge_id = $id";
		$items = "service_charge_amount AS number";
		$order = "service_charge_amount";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		foreach ($result as $rs1):
			$visit_type2 = $rs1->number;
		endforeach;
		return $visit_type2;
		//////echo $table, $where, $items, $order;
	}
	
	public function get_service_charge_id($service_id, $visit_type_id, $service_charge_name)
	{
		$table = "service_charge";
		$where = "service_id = $service_id AND visit_type_id = $visit_type_id AND service_charge_name = '$service_charge_name'";
		$items = "service_charge_id AS number";
		$order = "service_charge_id";
		//$sql= "select service_charge_id AS number from service_charge where service_id = $service_id AND visit_type_id = $visit_type_id AND service_charge_name = '$service_charge_name' order service_charge_id";
	//	////echo PP.$sql;
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0){
			foreach ($result as $rs1):
				$visit_type2 = $rs1->number;
			endforeach;//////echo "vt2 = ".$visit_type2;
		}
		
		return $visit_type2;
	}
	
	public function save_visit($patient_id)
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('visit_date', 'Visit Date', 'required');
		
		$this->form_validation->set_rules('doctor', 'Doctor', 'required');
		
		$this->form_validation->set_rules('service_charge_name', 'Consultation Type', 'required');
		
		$patient_insurance_id = $this->input->post("patient_insurance_id");
			$patient_insurance_number = $this->input->post("insurance_id");
						$patient_type = $this->input->post("patient_type"); 
		if($patient_type==4){
		
				$this->form_validation->set_rules('patient_insurance_id', 'Patients Insurance', 'required');
				
					$this->form_validation->set_rules('insurance_id', 'Input Insurance Number', 'required');
				//$this->initiate_visit($patient_id);
			}
		if ($this->form_validation->run() == FALSE)
		{
			$this->initiate_visit($patient_id);
		}
		else
		{
			$doc_name = $this->input->post("doctor");

			$service_charge_id = $this->input->post("service_charge_name");
		
			$doc_rs = $this->get_doctor2($doc_name);
			foreach ($doc_rs as $rs1):
				$doctor_id = $rs1->personnel_id;
			endforeach;
			//$visit_type = $this->get_visit_type($type_name);
			$visit_date = $this->input->post("visit_date");
			$timepicker_start = $this->input->post("timepicker_start");
			$timepicker_end = $this->input->post("timepicker_end");
			
			$appointment_id;
			$close_card;	
			if(($timepicker_end=="")||($timepicker_start=="")){
			$appointment_id=0;	
			$close_card=0;
			}else{
			$appointment_id=1;
			$close_card=2;		
				}
	
			$this->save_visit_table($patient_id, $visit_date, $doc_name , $timepicker_start,$timepicker_end,$appointment_id, $patient_type, $patient_insurance_id,$patient_insurance_number,$close_card);
			$visit_id = $this->select_max("visit", "visit_id");
			///$service_charge_id = $this->get_service_charge_id(1, $visit_type, $service_charge_name);
			$service_charge = $this->get_service_charge($service_charge_id);
			$this->save_consultation_charge($visit_id, $service_charge_id, $service_charge);
			$this->visit_list();
		}
	}
	
	private function save_visit_table($patient_id, $visit_date, $doctor_id, $timepicker_start,$timepicker_end, $appointment_id, $patient_type, $patient_insurance_id,$patient_insurance_number,$close_card)
	{
		
			$insert = array(
        		"visit_date" => $visit_date,
        		"patient_id" => $patient_id,
        		"personnel_id" => $doctor_id,
        		"patient_insurance_id" => $patient_insurance_id,
				"patient_insurance_number" => $patient_insurance_number,
        		"visit_type" => $patient_type,
				"time_start"=>$timepicker_start,
				"time_end"=>$timepicker_end,
				"appointment_id"=>$appointment_id,
				"close_card"=>$close_card,
    		);
	
		
		$table = "visit";
		$this->load->model('database', '',TRUE);
		$this->database->insert_entry($table, $insert);
		
		
		return TRUE;
	}
	
	private function save_consultation_charge($visit_id, $service_charge_id, $service_charge)
	{
		$insert = array(
        	"visit_id" => $visit_id,
        	"service_charge_id" => $service_charge_id,
        	"visit_charge_amount" => $service_charge
    	);
		$table = "visit_charge";
		$this->load->model('database', '',TRUE);
		$this->database->insert_entry($table, $insert);
		
		return TRUE;
	}
	
	
	
	private function select_max($table, $field)
	{
		$items = "MAX(".$field.") AS number";
		$order = "number";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries($table, $items, $order);
		
		foreach ($result as $rs1):
			$visit_type2 = $rs1->number;
		endforeach;//////echo "vt2 = ".$visit_type2;
		return $visit_type2;
	}
	
	private function get_doctor2($doc_name)
	{
		$table = "personnel, job_title";
		$where = "job_title.job_title_id = personnel.job_title_id AND job_title.job_title_id = 2 AND personnel.personnel_onames = '$doc_name'";
		$items = "personnel.personnel_onames, personnel.personnel_fname, personnel.personnel_id";
		$order = "personnel_onames";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	
	private function get_doctor()
	{
		$table = "personnel, job_title";
		$where = "job_title.job_title_id = personnel.job_title_id AND job_title.job_title_id = 2";
		$items = "personnel.personnel_onames, personnel.personnel_fname, personnel.personnel_id";
		$order = "personnel_onames";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}	
	
	private function get_visit_type($type_name)
	{
		$table = "visit_type";
		$where = "visit_type_name = '$type_name'";
		$items = "visit_type_id";
		$order = "visit_type_id";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		//////echo count($result);
		foreach ($result as $rs1):
			$visit_type2 = $rs1->visit_type_id;
		endforeach;
		return $visit_type2;
	}
	
	private function get_types()
	{
		$table = "visit_type";
		$where = "visit_type_id > 0";
		$items = "visit_type_name,visit_type_id";
		$order = "visit_type_name";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	
	private function get_patient_insurance($patient_id)
	{
		$table = "patient_insurance, company_insuarance";
		$where = "patient_insurance.patient_id = $patient_id AND company_insuarance.company_insurance_id = patient_insurance.company_insurance_id";
		$items = "patient_insurance_id, company_name, insurance_company_name";
		$order = "company_name";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	
	private function get_company_insurance()
	{
		$table = "company_insurance";
		$where = "company_insurance.company_insurance_id > 0";
		$items = "*";
		$order = "company_insurance_id";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	
	
	public function close_card_status($value, $row)
	{
		if($value == 1){
			$status = "Previous";
		}
		else{
			$status = "Current";
		}
		return $status;
	}
	
	public function open_patient_list($post_array, $primary_key)
	{
		if ($_POST['consultation_type_id'] == 2) { 
			$delete = array("appointment_id" => 1, "close_card" => 2);
			$table = "visit";
			$key = $primary_key;
			$this->load->model('database', '',TRUE);
			$this->database->update_entry($table, $delete, $key);
		} 
		return;
	}
	
	public function end_visit2($primary_key)
	{
		$delete = array(
        	"visit_time_out" => date("h:i:s")
    	);
		$table = "visit";
		$key = $primary_key;
		$this->load->model('database', '',TRUE);
		$this->database->update_entry($table, $delete, $key);
		$this->initiate_visit($_SESSION['patient_id2']);
	}
	
	public function upload_documents()
	{
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
 
        $crud->set_subject('Document');
        $crud->set_table('client_policy_documents');
    	$crud->set_relation('client_id', 'client', '{client_firstname} {client_surname}');
    	$crud->set_relation('documents_id', 'documents', '{documents_name}');
		$crud->set_field_upload('client_policy_documents_name', 'assets/uploads/files');
        $crud->display_as('client_policy_documents_name','Upload');
        $crud->display_as('client_id','Client');
        $crud->display_as('documents_id','Document');
		$crud->required_fields('client_id', 'client_policy_documents_name', 'documents_id');
        
        $output = $crud->render();
 
        $this->_example_output2($output);
	}
	
	public function insert_log($post_array, $primary_key)
	{
		//save the patient_number
		$number = $this->patient_number();
		$table = "patients";
		$key = "patient_id";
		$key_value = $primary_key;
		$items = array(
        	"patient_number" => $number
    	);
		$this->load->model('database', '',TRUE);
		$this->database->update_entry2($table, $key, $key_value, $items);
		
		$table_id = $this->get_table_id($_SESSION['table']);
		$client_log_insert = array(
        	"log_key" => $primary_key,
        	"table_id" => $table_id,
        	"database_action_id" => 1,
        	"user_id" => $_SESSION['personnel_id']
    	);
		$table = "log";
		$this->load->model('database', '',TRUE);
		$this->database->insert_entry($table, $client_log_insert);
    	return true;
	}
	
	public function update_log($post_array, $primary_key)
	{
		$table_id = $this->get_table_id($_SESSION['table']);
		$client_log_insert = array(
        	"log_key" => $primary_key,
        	"table_id" => $table_id,
        	"database_action_id" => 2,
        	"user_id" => $_SESSION['personnel_id']
    	);
		$table = "log";
		$this->load->model('database', '',TRUE);
		$this->database->insert_entry($table, $client_log_insert);
		
		$client_log_insert = array(
        	$_SESSION['table']."_table_id" => $primary_key,
        	"database_action_id" => 2,
        	"personnel_id" => $_SESSION['personnel_id']
    	);
    	return true;
	}
	
	public function delete_log($primary_key)
	{
		$delete = array(
        	$_SESSION['table']."_status" => 1
    	);
		$table = $_SESSION['table'];
		$key = $primary_key;
		$this->load->model('database', '',TRUE);
		$this->database->update_entry($table, $delete, $key);
		
		$table_id = $this->get_table_id($_SESSION['table']);
		$client_log_insert = array(
        	"log_key" => $primary_key,
        	"table_id" => $table_id,
        	"database_action_id" => 3,
        	"user_id" => $_SESSION['personnel_id']
    	);
		$table = "log";
		$this->load->model('database', '',TRUE);
		$this->database->insert_entry($table, $client_log_insert);
 
    	return true;
	}
	
	public function delete_patient($primary_key)
	{
		//save the patient_number
		$number = $this->patient_number();
		$table = "patients";
		$key = "patient_id";
		$key_value = $primary_key;
		$items = array(
        	"patient_status" => 1
    	);
		$this->load->model('database', '',TRUE);
		$this->database->update_entry2($table, $key, $key_value, $items);
		
		$table_id = $this->get_table_id($_SESSION['table']);
		$client_log_insert = array(
        	"log_key" => $primary_key,
        	"table_id" => $table_id,
        	"database_action_id" => 3,
        	"user_id" => $_SESSION['personnel_id']
    	);
		$table = "log";
		$this->load->model('database', '',TRUE);
		$this->database->insert_entry($table, $client_log_insert);
 
    	return true;
	}
	public function change_password1(){
	
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
 		$crud->set_subject('Personnel');
        $crud->set_table('personnel');
    	$crud->where(array("personnel_id" => $_SESSION['personnel_id']));
	
		$crud->unset_fields('personnel_onames', 	'personnel_fname', 	'personnel_dob', 	'personnel_email', 	'personnel_phone', 	'personnel_address', 	'personnel_locality', 	'civilstatus_id', 	'title_id', 	'gender_id', 	'personnel_username',	'personnel_kin_fname', 	'personnel_kin_onames', 	'personnel_kin_contact', 	'personnel_kin_address', 	'kin_relationship_id', 	'personnel_status', 	'job_title_id', 	'personnel_surname', 	'personnel_staff_id', 	'authorise');
				$crud->columns('personnel_onames', 	'personnel_fname', 'personnel_kin_onames', 	'personnel_kin_contact', 	'personnel_kin_address', 	'kin_relationship_id', 	'personnel_status', 'personnel_surname', 	'personnel_staff_id', 	'authorise');
		$crud->unset_delete();
		$crud->unset_add();
		$crud->display_as('personnel_id','Personnel ID');
		   		
		$crud->fields('Password','password', 'password');
		$crud->fields('Confirm Password','passconf', 'passconf');

$crud->set_rules('password', 'Password', 'callback_valid_password');
$crud->set_rules('passconf', 'Password Confirmation', 'matches[password]');

$crud->callback_before_insert(array($this,'encrypt_password_callback'));
$crud->callback_before_update(array($this,'encrypt_password_callback'));
		
        $output = $crud->render();
        $this->_example_output2($output);
	}
	function valid_password($str) {
    //do some pw validation
    return TRUE;
}
	function encrypt_password_callback($post_array, $primary_key = null){
    if ($post_array['password'] <> '') {
        $this->User_model->set_password($post_array['username'], $post_array['password']);
    }
}
	public function get_table_id($table_name)
	{
		$table = "table";
		$where = "table_name = '$table_name'";
		$items = "table_id";
		$order = "table_id";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0){
			foreach ($result as $row):
				$table_id = $row->table_id;
			endforeach;
		}
		else{
			$items2 = array("table_name" => $table_name);
			$this->load->model('database', '', TRUE);
			$this->database->insert_entry($table, $items2);
		
			$this->load->model('database', '', TRUE);
			$result = $this->database->select_entries_where($table, $where, $items, $order);
		
			foreach ($result as $row):
				$table_id = $row->table_id;
			endforeach;
		}
		return $table_id;
	}
	
	public function personnel_log()
    {
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
 
        $crud->set_subject('Company');
        $crud->set_table('personnel_log');
    	$crud->set_relation('personnel_table_id', 'personnel', '{personnel_fname} {personnel_onames}');
    	$crud->set_relation('personnel_id', 'personnel', '{personnel_fname} {personnel_onames}');
    	$crud->set_relation('database_action_id', 'database_action', '{database_action_name}');
		$crud->unset_delete();
		$crud->unset_edit();
		$crud->unset_add();
        $crud->display_as('personnel_table_id','Personnel');
        $crud->display_as('personnel_id','Performed By');
        $crud->display_as('database_action_id','Action');
        $crud->display_as('personnel_log_date','Date Performed');        
        $output = $crud->render();
 
        $this->_example_output($output);
    }
	
	public function client_log()
    {
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
 
        $crud->set_subject('Client');
        $crud->set_table('client_log');
    	$crud->set_relation('client_table_id', 'client', '{client_firstname} {client_surname}');
    	$crud->set_relation('personnel_id', 'personnel', '{personnel_fname} {personnel_onames}');
    	$crud->set_relation('database_action_id', 'database_action', '{database_action_name}');
		$crud->unset_delete();
		$crud->unset_edit();
		$crud->unset_add();
        $crud->display_as('client_table_id','Client');
        $crud->display_as('personnel_id','Performed By');
        $crud->display_as('database_action_id','Action');
        $crud->display_as('client_log_date','Date Performed');
        
        $output = $crud->render();
 
        $this->_example_output($output);
    }
	
	public function client_policy_log()
    {
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
 
        $crud->set_subject('Policy');
        $crud->set_table('client_policy_log');
    	$crud->set_relation('client_policy_table_id', 'client_policy', 'Addded - {client_policy_date_added} :: Start - {client_policy_start_date} :: End - {client_policy_expiry_date}');
    	$crud->set_relation('personnel_id', 'personnel', '{personnel_fname} {personnel_onames}');
    	$crud->set_relation('database_action_id', 'database_action', '{database_action_name}');
		$crud->add_action('More', base_url('img/icon-48-stats.png'), 'welcome/more_log_details');
		$crud->unset_delete();
		$crud->unset_edit();
		$crud->unset_add();
        $crud->display_as('client_policy_id','Dates');
        $crud->display_as('personnel_id','Performed By');
        $crud->display_as('database_action_id','Action');
        $crud->display_as('client_policy_log_date','Date Performed');
        
        $output = $crud->render();
 
        $this->_example_output($output);
    }
	
	public function session_log()
    {
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
 
        $crud->set_subject('Session');
        $crud->set_table('session');
    	$crud->set_relation('session_name_id', 'session_name', '{session_name_name}');
    	$crud->set_relation('personnel_id', 'personnel', '{personnel_fname} {personnel_onames}');
		$crud->unset_delete();
		$crud->unset_edit();
		$crud->unset_add();
        $crud->display_as('session_name_id','Action');
        $crud->display_as('personnel_id','Performed By');
        $crud->display_as('session_time','Log Time');
        
        $output = $crud->render();
 
        $this->_example_output($output);
    }
	
	public function more_log_details($primary_key)
	{
		$table = "client_policy_log";
		$where = "client_policy_log_id = ".$primary_key;
		$items = "client_policy_table_id";
		$order = "client_policy_table_id";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		foreach ($result as $row)
		{
			$client_policy_id = $row->client_policy_table_id;
		}
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
 
 		$crud->where('client_policy_id', $client_policy_id);
        $crud->set_subject('Policies');
        $crud->set_table('client_policy');
    	$crud->set_relation('client_id', 'client', '{client_firstname} {client_surname}');
    	$crud->set_relation('personnel_id', 'personnel', '{personnel_fname} {personnel_onames}');
		$crud->set_relation('insuarance_policy_id','insuarance_policy', '{insuarance_policy_name}');
		$crud->set_relation('premium_payee_id','premium_payee', '{premium_payee_fname} {premium_payee_onames}');
		$crud->add_action('Less', base_url('img/icon-48-stats.png'), 'welcome/back_to_client_policy_log');
		$crud->unset_delete();
		$crud->unset_edit();
		$crud->unset_add();
		$crud->unset_columns('client_policy_status');
		$crud->unset_fields('client_policy_status', 'client_policy_date_added');
        $crud->display_as('client_id','Client');
        $crud->display_as('personnel_id','Salesperson');
        $crud->display_as('client_policy_start_date','Start Date');
        $crud->display_as('client_policy_expiry_date','Expiry Date');
        $crud->display_as('client_policy_signed_date','Signed Date');
        $crud->display_as('client_policy_date_added','Date Added');
        $crud->display_as('insuarance_policy_id','Policy');
        $crud->display_as('premium_payee_id','Premium Payee');
        
        $output = $crud->render();
 
        $this->_example_output($output);
	}
	
	public function back_to_client_policy_log($primary_key)
	{
        $this->client_policy_log();
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
	
	public function calender()
	{$prefs['template'] = '

   {table_open}<table border="0" cellpadding="0" cellspacing="0">{/table_open}

   {heading_row_start}<tr>{/heading_row_start}

   {heading_previous_cell}<th><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
   {heading_title_cell}<th colspan="{colspan}">{heading}</th>{/heading_title_cell}
   {heading_next_cell}<th><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell}

   {heading_row_end}</tr>{/heading_row_end}

   {week_row_start}<tr>{/week_row_start}
   {week_day_cell}<td>{week_day}</td>{/week_day_cell}
   {week_row_end}</tr>{/week_row_end}

   {cal_row_start}<tr>{/cal_row_start}
   {cal_cell_start}<td>{/cal_cell_start}

   {cal_cell_content}<a href="{content}">{day}</a>{/cal_cell_content}
   {cal_cell_content_today}<div class="highlight"><a href="{content}">{day}</a></div>{/cal_cell_content_today}

   {cal_cell_no_content}{day}{/cal_cell_no_content}
   {cal_cell_no_content_today}<div class="highlight">{day}</div>{/cal_cell_no_content_today}

   {cal_cell_blank}&nbsp;{/cal_cell_blank}

   {cal_cell_end}</td>{/cal_cell_end}
   {cal_row_end}</tr>{/cal_row_end}

   {table_close}</table>{/table_close}
';
$this->load->library('calendar', $prefs);
		$data = array(
               3  => 'http://example.com/news/article/2006/03/',
               7  => 'http://example.com/news/article/2006/07/',
               13 => 'http://example.com/news/article/2006/13/',
               26 => 'http://example.com/news/article/2006/26/'
             );

		////echo $this->calendar->generate(2006, 6, $data);
	}
	
	public function schedule_personnel($primary_key)
	{
		
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
 
 		$crud->where('client_policy_id', $client_policy_id);
        $crud->set_subject('Policies');
        $crud->set_table('client_policy');
    	$crud->set_relation('client_id', 'client', '{client_firstname} {client_surname}');
    	$crud->set_relation('personnel_id', 'personnel', '{personnel_fname} {personnel_onames}');
		$crud->set_relation('insuarance_policy_id','insuarance_policy', '{insuarance_policy_name}');
		$crud->set_relation('premium_payee_id','premium_payee', '{premium_payee_fname} {premium_payee_onames}');
		$crud->add_action('Less', base_url('img/icon-48-stats.png'), 'welcome/back_to_client_policy_log');
		$crud->unset_delete();
		$crud->unset_edit();
		$crud->unset_add();
		$crud->unset_columns('client_policy_status');
		$crud->unset_fields('client_policy_status', 'client_policy_date_added');
        $crud->display_as('client_id','Client');
        $crud->display_as('personnel_id','Salesperson');
        $crud->display_as('client_policy_start_date','Start Date');
        $crud->display_as('client_policy_expiry_date','Expiry Date');
        $crud->display_as('client_policy_signed_date','Signed Date');
        $crud->display_as('client_policy_date_added','Date Added');
        $crud->display_as('insuarance_policy_id','Policy');
        $crud->display_as('premium_payee_id','Premium Payee');
        
        $output = $crud->render();
 
        $this->_example_output($output);
	}
	
	function update_company_names(){
		
		$table = "company_insuarance";
		$where = "company_insurance_id > 0";
		$items = "*";
		$order = "company_insurance_id";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0){
			foreach ($result as $row):
				$company_insurance_id = $row->company_insurance_id;
				$company_id = $row->companies_id;
				$insurance_company_id = $row->insurance_company_id;
				$company_name = $row->company_name;
				$insurance_company_name = $row->insurance_company_name;
				$table = "company_insuarance";
				$key = "company_insurance_id";
				$key_value = $company_insurance_id;
				
				if(1 > 0){
					$company_name="";
					$table2 = "companies, insurance_company, company_insuarance";
					$where = "company_insuarance.companies_id = $company_id AND company_insuarance.insurance_company_id = $insurance_company_id AND company_insuarance.companies_id = companies.companies_id AND company_insuarance.insurance_company_id = insurance_company.insurance_company_id";
					$items = "companies.companies_name, insurance_company.insurance_company_name";
					$order = "companies_name";
		
					$this->load->model('database', '', TRUE);
					$result = $this->database->select_entries_where($table2, $where, $items, $order);
		
					if(count($result) > 0){
						foreach ($result as $row):
							$companies_name = $row->companies_name;
							$insurance_company_name = $row->insurance_company_name;
						endforeach;
					}
					$items = array(
					"insurance_company_name" => $insurance_company_name,
        				"company_name" => $company_name
        				
    				);
					$this->load->model('database', '', TRUE);
					$this->database->update_entry2($table, $key, $key_value, $items); 
				}
			endforeach;
		}
	}
	
	public function save_initiate_lab($primary_key)
	{
		//$visit_type_id = $this->get_visit_type_id($primary_key);
	$visit_type_id = $this->input->post("patient_type");
	$patient_insurance_number = $this->input->post("insurance_id");
	$patient_insurance_id = $this->input->post("patient_insurance_id");
		$insert = array(
        	"close_card" => 0,
        	"patient_id" => $primary_key,
        	"visit_type" => $visit_type_id,
			"patient_insurance_id" => $patient_insurance_id,
			"patient_insurance_number" => $patient_insurance_number,
        	"visit_date" => date("y-m-d"),
        	"lab_visit" => 5
    	);
		$table = "visit";
		$this->load->model('database', '',TRUE);
		$this->database->insert_entry($table, $insert);
    	$this->visit_list();
	}
	

	public function initiate_visit($primary_key)
	{
		$data["patient_id"] = $primary_key;
		$data['charge'] = $this->get_service_charges($primary_key);
		$data['doctor'] = $this->get_doctor();
		$data['type'] = $this->get_types();
		$data['patient'] = $this->patient_names2($primary_key);
		$data['patient_insurance'] = $this->get_patient_insurance($primary_key);
		$this->load->view("initiate_visit", $data);
	}
		
		public function initiate_lab($primary_key)
	{		$data["patient_id"] = $primary_key;
		$data['patient'] = $this->patient_names2($primary_key);
		$data['type'] = $this->get_types();
		$data['patient_insurance'] = $this->get_patient_insurance($primary_key);
		$this->load->view('initiate_lab.php',$data);	
	}
	
		public function initiate_pharmacy($primary_key)
	{		$data["patient_id"] = $primary_key;
		$data['patient'] = $this->patient_names2($primary_key);
		$data['type'] = $this->get_types();
		$data['patient_insurance'] = $this->get_patient_insurance($primary_key);
		$this->load->view('initiate_pharmacy.php',$data);	
	}
	
	public function save_initiate_pharmacy($primary_key)
	{
	//$visit_type_id = $this->get_visit_type_id($primary_key);
	$visit_type_id = $this->input->post("patient_type");
	?>
    <script>
	window.alert('<?php echo $visit_type_id; ?>');
	</script>
    <?php
	$patient_insurance_number = $this->input->post("insurance_id");
	$patient_insurance_id = $this->input->post("patient_insurance_id");
		$insert = array(
        	"close_card" => 0,
        	"patient_id" => $primary_key,
        	"visit_type" => $visit_type_id,
			"patient_insurance_id" => $patient_insurance_id,
			"patient_insurance_number" => $patient_insurance_number,
        	"visit_date" => date("y-m-d"),
        	"pharmarcy" => 5
    	);
		$table = "visit";
		$this->load->model('database', '',TRUE);
		$this->database->insert_entry($table, $insert);
    	$this->visit_list();
	}
}
