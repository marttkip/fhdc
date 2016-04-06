<?php 
error_reporting(E_ALL & ~E_DEPRECATED); 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include "welcome.php";
class Accounts extends CI_Controller {

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
	
	function accounts($output = null)
	{
		$this->load->view('accounts.php',$output);	
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
		return $value;
	}
	
	public function accounts_History()
	{
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		
		$crud->where(array("close_card" => 1));
        $crud->set_subject('Visit');
        $crud->set_table('visit');
		$crud->columns('visit_time', 'patient_id', 'Doctor', 'Consultation', 'Pharmacy', 'Laboratory', 'Procedures', 'Total', 'Payments', 'Balance');
		$crud->fields('visit_date', 'Doctor', 'patient_id');
		$crud->set_relation_n_n('Doctor', 'schedule', 'personnel', 'schedule_id', 'personnel_id', 'Dr. {personnel_fname} {personnel_surname}');
		$crud->add_action('Receipt', base_url('img/new/icon-48-menu.png'), 'accounts/receipt_history');
		$crud->add_action('Invoice', base_url('img/new/icon-48-stats.png'), 'accounts/invoice_history');
		$crud->add_action('Payments', base_url('img/new/icon-48-content.png'), 'accounts/payments_history');
  		$crud->callback_column('patient_id',array($this,'patient_names'));
		$crud->callback_column('Consultation',array($this,'consultation'));
		$crud->callback_column('Pharmacy',array($this,'pharmacy'));
		$crud->callback_column('Laboratory',array($this,'laboratory'));
		$crud->callback_column('Procedures',array($this,'procedures'));
		$crud->callback_column('Payments',array($this,'payments2'));
		$crud->callback_column('Balance',array($this,'balance'));
		$crud->callback_column('Total',array($this,'total'));
		$crud->callback_column('Age',array($this,'calculate_age'));
		$crud->callback_column('Gender',array($this,'calculate_gender'));
        $crud->display_as('visit_time','Time In');
        $crud->display_as('visit_date','Visit Date');
        $crud->display_as('visit_time_out','Time Out');
        $crud->display_as('patient_id','Patient');
		$crud->required_fields('visit_date', 'Doctor');
		$crud->unset_add();
		$crud->unset_delete();
		$crud->unset_edit();
        
        $output = $crud->render();
 
        $this->accounts($output);
	}
	
	public function accounts_queue()
	{
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		$crud->where(array("close_card" => 0));
        $crud->set_subject('Visit');
        $crud->set_table('visit');
		//$crud->columns('visit_time', 'patient_id', 'Doctor', 'Consultation', 'Pharmacy', 'Laboratory', 'Procedures', 'Total', 'Payments', 'Balance');
		$crud->columns('visit_time', 'patient_id', 'personnel_id', 'Invoice_Total', 'Payments', 'Balance');
		//$crud->set_relation("personnel_id", "personnel", "Dr. {personnel_fname} {personnel_onames}");
		$crud->add_action('Receipt', base_url('img/new/icon-48-menu.png'), 'accounts/receipt');
		$crud->add_action('Invoice', base_url('img/new/icon-48-stats.png'), 'accounts/invoice');
		$crud->add_action('Payments', base_url('img/new/icon-48-content.png'), 'accounts/payments');
		$crud->add_action('End Visit', base_url('img/new/icon-48-alert.png'), 'accounts/end_visit');
  		$crud->callback_column('patient_id',array($this,'patient_names'));
		/*$crud->callback_column('Consultation',array($this,'consultation'));
		$crud->callback_column('Pharmacy',array($this,'pharmacy'));
		$crud->callback_column('Laboratory',array($this,'laboratory'));
		$crud->callback_column('Procedures',array($this,'procedures'));*/
		$crud->callback_column('Payments',array($this,'payments2'));
		$crud->callback_column('Balance',array($this,'balance'));
		$crud->callback_column('Invoice_Total',array($this,'total'));
		$crud->callback_column('Age',array($this,'calculate_age'));
		$crud->callback_column('Gender',array($this,'calculate_gender'));
        $crud->display_as('visit_time','Time In');
        $crud->display_as('visit_date','Visit Date');
        $crud->display_as('visit_time_out','Time Out');
        $crud->display_as('patient_id','Patient');
		$crud->required_fields('visit_date', 'Doctor');
		$crud->unset_add();
		$crud->unset_delete();
		$crud->unset_edit();
        
        $output = $crud->render();
 
        $this->accounts($output);
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
		
		//echo $sqlq;
	        $rsq = mysql_query($sqlq)
        or die ("unable to Select ".mysql_error());
		
		return $rsq;
	}
	public function total($value, $row)
	{ $total=""; $temp="";
		 //connect to database
        $connect = mysql_connect("localhost", "sumc_hms", "Oreo2014#")
                    or die("Unable to connect to MySQL".mysql_error());

        //selecting a database
        mysql_select_db("sumc", $connect)
                    or die("Could not select database".mysql_error());
					
		$id = $row->visit_id;
		//identify patient/visit type
		$sql= "SELECT visit_type,patient_id, visit_id FROM visit WHERE visit_id = '$id'";
	//echo $sql;
    $rs21 = mysql_query($sql)
        or die ("unable to Select ".mysql_error());
$num_type= mysql_num_rows($rs21);
$visit_t = mysql_result($rs21, 0 ,"visit_type");
$patient_id = mysql_result($rs21, 0 ,"patient_id");

		$sqlf= "SELECT * FROM visit_type WHERE  visit_type_id= $visit_t"; //echo $sqlf;
    $rs21f = mysql_query($sqlf)
        or die ("unable to Select ".mysql_error());
$num_type0= mysql_num_rows($rs21f);
$visit_type_name = mysql_result($rs21f, 0 ,"visit_type_name");
////echo VT.$visit_type_name;
if ($visit_type_name=="Insurance")
{
$sql1s= "SELECT visit_charge.visit_charge_amount,visit_charge.visit_charge_units,visit_charge.service_charge_id,service_charge.service_id
FROM visit_charge, service_charge
WHERE service_charge.service_charge_id = visit_charge.service_charge_id
AND visit_charge.visit_id =$id";
//echo $sql1s;
    $rs1s = mysql_query($sql1s)
        or die ("unable to Select ".mysql_error());
$num_type1s= mysql_num_rows($rs1s);
	for($a =0; $a < $num_type1s; $a++){
		$service_id1  = mysql_result($rs1s, $a, "service_id");
		$visit_charge_amount  = mysql_result($rs1s, $a, "visit_charge_amount");
		$visit_charge_units  = mysql_result($rs1s, $a, "visit_charge_units");
		$discounted_value="";
			
$sql1= "SELECT * FROM insurance_discounts WHERE insurance_id = (SELECT company_insurance_id FROM `patient_insurance` where patient_id =$patient_id) and service_id=$service_id1";
//echo $sql1;
    $rs1 = mysql_query($sql1)
        or die ("unable to Select ".mysql_error());
$num_type1= mysql_num_rows($rs1);

$percentage = mysql_result($rs1,0, "percentage");
$amount = mysql_result($rs1, 0, "amount");
//echo Percent.$percentage .'			'.VCU.$visit_charge_units.'			'.PAT.$patient_id;
$penn=((100-$percentage)/100);
//echo 'yyy'.$penn;
			//echo 'PENN'.$penn*$visit_charge_amount;
$discounted_value="";	
		if($percentage==0){
			$discounted_value=$amount;	
		$sum = $visit_charge_amount -$discounted_value;			
	
		}
		elseif($amount==0){
			$discounted_value=$percentage;
			$sum = $visit_charge_amount *((100-$discounted_value)/100);
			$penn=((100-$discounted_value)/100);
			//echo 'PENN'.$penn*$visit_charge_amount;
//echo AMTf.$sum.'		'.$discounted_value;
		}
		elseif(($amount==0)&&($percentage==0)){
			
			$sum=$visit_charge_amount;
			
			}
		//echo AMT.$sum .'			'.VCU.$visit_charge_units;
		//$total = $total + ($amount*$visit_charge_units);
		
		$total=($sum*$visit_charge_units)+$temp;	$temp=$total;
				
	}return $total;}
else{
		 //connect to database
        $connect = mysql_connect("localhost", "sumc_hms", "Oreo2014#")
                    or die("Unable to connect to MySQL".mysql_error());

        //selecting a database
        mysql_select_db("sumc", $connect)
                    or die("Could not select database".mysql_error());
					
		$id = $row->visit_id;
		//identify patient/visit type
		$sql= "SELECT visit_type,patient_id, visit_id FROM visit WHERE visit_id = '$id'";
	//echo $sql;
    $rs21 = mysql_query($sql)
        or die ("unable to Select ".mysql_error());
$num_type= mysql_num_rows($rs21);
$visit_t = mysql_result($rs21, 0 ,"visit_type");
$patient_id = mysql_result($rs21, 0 ,"patient_id");

		$sqlf= "SELECT * FROM visit_type WHERE  visit_type_id= $visit_t"; //echo $sqlf;
    $rs21f = mysql_query($sqlf)
        or die ("unable to Select ".mysql_error());
$num_type0= mysql_num_rows($rs21f);
$visit_type_name = mysql_result($rs21f, 0 ,"visit_type_name");
////echo VT.$visit_type_name;
if ($visit_type_name!="Insurance")
{$total="";
 

$sql1s= "SELECT visit_charge.visit_charge_amount,visit_charge.visit_charge_units,visit_charge.service_charge_id,service_charge.service_id
FROM visit_charge, service_charge
WHERE service_charge.service_charge_id = visit_charge.service_charge_id
AND visit_charge.visit_id =$id";
//echo $sql1s;
    $rs1s = mysql_query($sql1s)
        or die ("unable to Select ".mysql_error());
$num_type1s= mysql_num_rows($rs1s);
	for($a =0; $a < $num_type1s; $a++){
		$service_id1  = mysql_result($rs1s, $a, "service_id");
		$visit_charge_amount  = mysql_result($rs1s, $a, "visit_charge_amount");
		$visit_charge_units  = mysql_result($rs1s, $a, "visit_charge_units");
		//$discounted_value="";
$amount=$visit_charge_amount*$visit_charge_units;
		$total = $total + $amount;
				
	}return $total;}
}
	
	}
	
	public function balance($value, $row)
	{
		
		$value = $row->Payments - $row->Invoice_Total;
		if($value > 0){
		$value= '(-'.$value.')';
	}
	else{
		$value= -(1) * ($value);
		}
	
		return $value;
	}
	
		public function get_insurance_name($value, $row)
	{
		$id = $row->visit_id;
		$table = "visit";
		$where = "payments.visit_id = $id";
		$items = "payments.amount_paid";
		$order = "amount_paid";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		$total = 0;
		
		if(count($result) > 0){
			foreach ($result as $row2):
				$amount_paid = $row2->amount_paid;
				$total = $total + $amount_paid;
			endforeach;
		}
		
		else{
			$total = 0;
		}
		
		$value = $total;
		
		return $value;
	}
	public function payments2($value, $row)
	{
		$id = $row->visit_id;
		$table = "payments";
		$where = "payments.visit_id = $id";
		$items = "payments.amount_paid";
		$order = "amount_paid";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		$total = 0;
		
		if(count($result) > 0){
			foreach ($result as $row2):
				$amount_paid = $row2->amount_paid;
				$total = $total + $amount_paid;
			endforeach;
		}
		
		else{
			$total = 0;
		}
		
		$value = $total;
		
		return $value;
	}
	
	public function consultation($value, $row)
	{
		$id = $row->visit_id;
		$table = "consultation_charge_2, visit, consultation_type";
		$where = "visit.consultation_type_id = consultation_charge_2.consultation_type_id AND visit_id = '$id' AND consultation_charge_2.consultation_type_id = consultation_type.consultation_type_id AND consultation_charge_2.visit_type =visit.visit_type";
		$items = "consultation_charge_2.charge";
		$order = "charge";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0){
			foreach ($result as $row2):
				$charge = $row2->charge;
			endforeach;
		}
		
		else{
			$charge = 0;
		}
		
		$value = $charge;
		
		return $value;
	}
	
	public function laboratory($value, $row)
	{
		$id = $row->visit_id;
		$table = "visit, lab_test, lab_visit";
		$where = "lab_visit.visit_id = $id AND visit.visit_id = $id AND lab_test.lab_test_id=lab_visit.lab_test_id";
		$items = "lab_test.lab_test_id,lab_test.lab_test_price,lab_visit.visit_id,lab_visit.lab_test_id, visit.visit_id";
		$order = "lab_test_price";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		$total = 0;
		
		if(count($result) > 0){
			foreach ($result as $row2):
				$price = $row2->lab_test_price;
				$total = $total + $price;
			endforeach;
		}
		
		else{
			$total = 0;
		}
		
		$value = $total;
		
		return $value;
	}
	
	public function pharmacy($value, $row)
	{
		$id = $row->visit_id;
		$table = "drugs, pres";
		$where = "pres.visit_id = '$id' AND drugs.drugs_id = pres.drugs_id";
		$items = "drugs.drugs_id, drugs.drugs_unitprice, pres.prescription_quantity, drugs.drugs_name";
		$order = "drugs_name";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		$total = 0;
		
		if(count($result) > 0){
			foreach ($result as $row2):
				$unit_price = ($row2->drugs_unitprice * 1.33) * 0.9;
				$rounded = round($unit_price, 0);
				$quantity = $row2->prescription_quantity;
				$total = $total + ($rounded * $quantity);
			endforeach;
		}
		
		else{
			$total = 0;
		}
		
		$value = $total;
		
		return $value;
	}
	
	public function procedures($value, $row)
	{
		//get the visit_type
		$id = $row->visit_id;
		$table = "visit";
		$where = "visit_id = $id";
		$items = "visit_type";
		$order = "visit_type";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0){
			foreach ($result as $row2):
				$visit_type = $row2->visit_type;
			endforeach;
		
			if($visit_type == 1){
				$type = "students";
			}
			elseif($visit_type == 2){
				$type = "staff";
			}	
			elseif($visit_type == 3){
				$type = "outsiders";
			}
			
			$table = "procedure, visit, visit_procedure";
			$items = "visit_procedure.units, procedure.outsiders";
			$where = "visit.visit_id = visit_procedure.visit_id AND procedure.procedure_id = visit_procedure.procedure_id AND visit.visit_id = $id";
			
			//$order ="outsiders";
		$type = "outsiders";
			$this->load->model('database', '', TRUE);
			$result = $this->database->select_entries_where($table, $where, $items, $order);
		
			$total = 0;
		
			if(count($result) > 0){
				foreach ($result as $row2):
					$charge = $row2->$type * $row2->units;
					$total = $total + $charge;

				endforeach;
			}
		}
		
		else{
			$total = 0;
		}
		
		$value = $total;
		
		return $value;
	}
	
	public function payments($primary_key)
	{
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		
		$crud->where(array("payments.visit_id" => $primary_key));
        $crud->set_subject('Payment');
        $crud->set_table('payments');
		$crud->columns('amount_paid', 'payment_method_id');
		$crud->fields('amount_paid', 'payment_method_id', 'visit_id');
		$crud->set_relation('payment_method_id', 'payment_method', '{payment_method}');
		$crud->add_action('Receipt', base_url('img/new/icon-48-menu.png'), 'accounts/receipt2');
		$crud->required_fields('amount_paid', 'payment_method_id');
		$crud->field_type('visit_id', 'hidden', $primary_key);
        $crud->display_as('amount_paid','Amount');
        $crud->display_as('payment_method_id','Payment Method');
		$crud->unset_edit();
		$crud->unset_delete();
        $output = $crud->render();
 
        $this->accounts($output);
	}
	
	public function payments_history($primary_key)
	{
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		
		$crud->where(array("payments.visit_id" => $primary_key));
        $crud->set_subject('Payment');
        $crud->set_table('payments');
		$crud->columns('amount_paid', 'payment_method_id');
		$crud->fields('amount_paid', 'payment_method_id', 'visit_id');
		$crud->set_relation('payment_method_id', 'payment_method', '{payment_method}');
		$crud->add_action('Receipt', base_url('img/new/icon-48-menu.png'), 'accounts/receipt3');
		$crud->required_fields('amount_paid', 'payment_method_id');
		$crud->field_type('visit_id', 'hidden', $primary_key);
        $crud->display_as('amount_paid','Amount');
        $crud->display_as('payment_method_id','Payment Method');
		$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_delete();
		
        $output = $crud->render();
 
        $this->accounts($output);
	}
	
	public function end_visit($primary_key)
	{
		$delete = array(
        	"close_card" => 1
    	);
		$table = "visit";
		$key = $primary_key;
		$this->load->model('database', '',TRUE);
		$this->database->update_entry($table, $delete, $key);
		
		$this->accounts_queue();
	}
	
	public function receipt($primary_key)
	{
		?>
        	<script type="text/javascript">
				window.open("<?php echo base_url("data/accounts/print_receipt.php?visit_id=".$primary_key)?>","Popup","height=900,width=1200,,scrollbars=yes,"+"directories=yes,location=yes,menubar=yes,"+"resizable=no status=no,history=no top = 50 left = 100");
			</script>
        <?php
		
		$this->accounts_queue();
	}
	
	public function receipt_history($primary_key)
	{
		?>
        	<script type="text/javascript">
				window.open("<?php echo base_url("data/accounts/print_receipt.php?visit_id=".$primary_key)?>","Popup","height=900,width=1200,,scrollbars=yes,"+"directories=yes,location=yes,menubar=yes,"+"resizable=no status=no,history=no top = 50 left = 100");
			</script>
        <?php
		
		$this->accounts_History();
	}
	
	public function receipt2($primary_key)
	{
		?>
        	<script type="text/javascript">
				window.open("<?php echo base_url("data/accounts/print_receipt2.php?payment_id=".$primary_key)?>","Popup","height=900,width=1200,,scrollbars=yes,"+"directories=yes,location=yes,menubar=yes,"+"resizable=no status=no,history=no top = 50 left = 100");
			</script>
        <?php
		$visit_id = $this->get_visit_id($primary_key);
		$this->payments($visit_id);
	}
	
	public function receipt3($primary_key)
	{
		?>
        	<script type="text/javascript">
				window.open("<?php echo base_url("data/accounts/print_receipt2.php?payment_id=".$primary_key)?>","Popup","height=900,width=1200,,scrollbars=yes,"+"directories=yes,location=yes,menubar=yes,"+"resizable=no status=no,history=no top = 50 left = 100");
			</script>
        <?php
		$visit_id = $this->get_visit_id($primary_key);
		$this->payments_history($visit_id);
	}
	  public function insurance_patients()
	{
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
        $crud->set_subject('patient_insurance');
        //$crud->set_table('insurance');
 		//$crud->where('patient_surname',' ');
		/*$crud->required_fields('patient_id','company_insurance_id');
		$crud->set_relation('patient_id', 'patients', '{patient_surname} {patient_othernames}');
		$crud->set_relation('company_insurance_id', 'companies', '{companies_name}');*/

		$crud->where(array("close_card" => 0));
        $crud->set_subject('Visit');
        $crud->set_table('visit');
		//$crud->columns('visit_time', 'patient_id', 'Doctor', 'Consultation', 'Pharmacy', 'Laboratory', 'Procedures', 'Total', 'Payments', 'Balance');
		$crud->columns('visit_time', 'patient_id', 'personnel_id', 'Invoice_Total', 'Payments', 'Balance');
		$crud->set_relation("personnel_id", "personnel", "Dr. {personnel_fname} {personnel_onames}");
		$crud->add_action('Receipt', base_url('img/new/icon-48-menu.png'), 'accounts/receipt');
		$crud->add_action('Invoice', base_url('img/new/icon-48-stats.png'), 'accounts/invoice');
		$crud->add_action('Payments', base_url('img/new/icon-48-content.png'), 'accounts/payments');
		$crud->add_action('End Visit', base_url('img/new/icon-48-alert.png'), 'accounts/end_visit');
  		$crud->callback_column('patient_id',array($this,'patient_names'));
		/*$crud->callback_column('Consultation',array($this,'consultation'));
		$crud->callback_column('Pharmacy',array($this,'pharmacy'));
		$crud->callback_column('Laboratory',array($this,'laboratory'));
		$crud->callback_column('Procedures',array($this,'procedures'));*/
		$crud->callback_column('Payments',array($this,'payments2'));
		$crud->callback_column('Balance',array($this,'balance'));
		$crud->callback_column('Invoice_Total',array($this,'total'));
		$crud->callback_column('Age',array($this,'calculate_age'));
		$crud->callback_column('Gender',array($this,'calculate_gender'));
		$crud->callback_column('Insurance',array($this,'get_insurance_name'));
        $crud->display_as('visit_time','Time In');
        $crud->display_as('visit_date','Visit Date');
        $crud->display_as('visit_time_out','Time Out');
        $crud->display_as('patient_id','Patient');
		$crud->required_fields('visit_date', 'Doctor');
		$crud->unset_add();
		$crud->unset_delete();
		$crud->unset_edit();
        
        $output = $crud->render();
 
        $this->accounts($output);
	}

	public function get_visit_id($payments_id)
	{
		$table = "payments";
		$where = "payment_id = $payments_id";
		$items = "visit_id";
		$order = "visit_id";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		foreach ($result as $row):
			$visit_id = $row->visit_id;
		endforeach;
		
		return $visit_id;
	}

		public function reports_invoice($primary_key)
	{
		?>
        	<script type="text/javascript">
				window.open("<?php echo base_url("data/accounts/print_invoice.php?visit_id=".$primary_key)?>","Popup","height=900,width=1200,,scrollbars=yes,"+"directories=yes,location=yes,menubar=yes,"+"resizable=no status=no,history=no top = 50 left = 100");
				
				window.location.href="http://sagana/hms/data/reports/reports.php";
			</script>
        <?php
		
		$this->accounts_queue();
	}
	public function invoice($primary_key)
	{
		?>
        	<script type="text/javascript">
				window.open("<?php echo base_url("data/accounts/print_invoice.php?visit_id=".$primary_key)?>","Popup","height=900,width=1200,,scrollbars=yes,"+"directories=yes,location=yes,menubar=yes,"+"resizable=no status=no,history=no top = 50 left = 100");
				window.location.href="<?php echo base_url("index.php/accounts/accounts_queue")?>";
			</script>
        <?php
		
		$this->accounts_queue();
	}
	
	public function invoice_history($primary_key)
	{
		?>
        	<script type="text/javascript">
				window.open("<?php echo base_url("data/accounts/print_invoice.php?visit_id=".$primary_key)?>","Popup","height=900,width=1200,,scrollbars=yes,"+"directories=yes,location=yes,menubar=yes,"+"resizable=no status=no,history=no top = 50 left = 100");
			</script>
        <?php
		
		$this->accounts_History();
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
  function add_field_callback_1()
{
    return '<input type="text" maxlength="50" value="211" name="amount" style="width:462px">';
}
  	public function expenses1(){
		
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
        $crud->set_subject('Expenses');
        $crud->set_table('expenses');
		
		$crud->set_relation('recipient','personnel', '{personnel_fname} {personnel_onames}', array("authorise" => !0));
		$crud->set_relation('reason','permissions', 'permissions_name');
		$crud->columns('personnel_id','amount','date');
		$crud->fields('personnel_id','recipient','reason','amount','date');
		$crud->callback_add_field('amount',array($this,'add_field_callback_1'));
		$crud->field_type('personnel_id', 'hidden', $_SESSION['personnel_id']);
			$crud->add_action('Print Voucher', base_url('img/new/icon-48-menu.png'), 'accounts/voucher');
        $output = $crud->render(); 
        $this->accounts($output);
				$crud->unset_edit();
		$crud->unset_delete();
		
	}
public function expenses()
    {
		$crud = new grocery_CRUD(); $crud->set_theme('datatables');
		
 		$crud->where(array("authorise" => !0));
        $crud->set_subject('Personnel');
        $crud->set_table('personnel');
    	$crud->set_relation('title_id', 'title', 'title_name');
    	$crud->set_relation('job_title_id', 'job_title', 'job_title_name');
		$crud->set_relation('authorise', 'permissions', 'permissions_name');
    	$crud->set_relation('gender_id', 'gender', 'gender_name');
		$crud->set_relation('civilstatus_id', 'civil_status', 'civil_status_name');
    	$crud->set_relation('kin_relationship_id', 'kin_relationship', 'kin_relationship_name');
    	$crud->set_relation_n_n('Roles', 'personnel_department', 'departments', 'personnel_id', 'department_id', 'departments_name', 'count');
		$crud->add_action('Reset Password', base_url('img/new/icon-48-install.png'), 'accounts/expenses1');
		$crud->columns('personnel_onames', 'personnel_fname', 'personnel_email', 'job_title_id', 'personnel_phone', 'title_id', 'gender_id', 'personnel_username', 'Roles');
		$crud->unset_fields('personnel_status');
		$crud->required_fields('personnel_ref', 'personnel_onames','personnel_fname');
        $crud->display_as('personnel_onames','Other Names');
        $crud->display_as('personnel_fname','First Name');
        $crud->display_as('personnel_dob','Date of Birth');
        $crud->display_as('personnel_email','Email');
        $crud->display_as('personnel_phone','Phone');
        $crud->display_as('personnel_address','Address');
        $crud->display_as('job_title_id','Job Title');
		$crud->display_as('authorise','Authorise Cash Payments');
        $crud->display_as('personnel_locality','Town');
        $crud->display_as('civilstatus_id','Marital Status');
        $crud->display_as('personnel_title','Title');
        $crud->display_as('personnel_sex','Gender');
        $crud->display_as('personnel_username','Username');
        $crud->display_as('personnel_kin_fname','Kin First Name');
        $crud->display_as('personnel_kin_onames','Kin Other Names');
        $crud->display_as('personnel_kin_address','kin Address');
        $crud->display_as('personnel_kin_contact','Kin Phone');
        $crud->display_as('kin_relationship_id','Kin Relationship');
        $crud->display_as('title_id','Title');
        $crud->display_as('gender_id','Gender');
		$crud->field_type('personnel_password', 'hidden', 'e10adc3949ba59abbe56e057f20f883e');
       $output = $crud->render(); 
        $this->accounts($output);
				$crud->unset_edit();
		$crud->unset_delete();
    }
		public function voucher($primary_key){
			
			?>
        	<script type="text/javascript">
				window.open("<?php  echo base_url("data/accounts/voucher.php?voucher_id=".$primary_key)?>","Popup","height=900,width=1200,,scrollbars=yes,"+"directories=yes,location=yes,menubar=yes,"+"resizable=no status=no,history=no top = 50 left = 100");
		window.location.href="<?php  echo base_url("index.php/accounts/expenses")?>";
			
			</script>
        <?php
		 	
			}
}


