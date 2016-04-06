<?php session_start();
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->model('charts_model');
	}
	
	function load_head()
	{
		$this->load->view('reports/includes/header');
	}
	
	function load_footer()
	{
		$this->load->view('reports/includes/footer');
	}
	
	function patient_reports()
	{
		$this->load->helper("form");
		//get patients
		$patients = $this->select_patients();
		$staff = 0;
		$student = 0;
		$insurance = 0;
		$cash = 0;
		$other = 0;
		
		if(is_array($patients))
		{
			foreach($patients as $pat)
			{
				$patient = $pat->visit_type;
				/*
					-----------------------------------------------------------------------------------------
					count the number of a particular patient type
					1 student
					2 staff
					3 cash
					4 insurance
					-----------------------------------------------------------------------------------------
				*/
				if($patient == 1)
				{
					$student++;
				}
				
				else if($patient == 2)
				{
					$staff++;
				}
				
				else if($patient == 3)
				{
					$cash++;
				}
				
				else if($patient == 4)
				{
					$insurance++;
				}
				else
				{
					$other++;
				}
			}
		}
		
		$data['staff'] = $staff;
		$data['student'] = $student;
		$data['insurance'] = $insurance;
		$data['cash'] = $cash;
		
		$this->load_head();
		$this->load->view('reports/patients', $data);
		$this->load_footer();
	}
	
	function get_all_patients()
	{
		//get patients
		$patients = $this->select_patients();
		
		//get staff
		$staff_ = $this->database->select_entries('staff', 'Surname, Other_names, Staff_Number', 'Surname');
		
		//get students
		$student_ = $this->database->select_entries('student', 'Surname, Other_names, student_Number', 'Surname');
		
		if(is_array($patients))
		{
			$count = 0;
			
			foreach($patients as $pat)
			{
				$visit_type_id = $pat->visit_type;
				
				if($visit_type_id < 3){
					$strath_no = $pat->strath_no;
			
					//student
					if($visit_type_id == 1)
					{
						if(is_array($student_))
						{
							foreach($student_ as $stud)
							{
								$student_no = $stud->student_Number;
								
								if($student_no == $strath_no){
									$patient_surname = $stud->Surname;
									$patient_othernames = $stud->Other_names;
								}
							}
						}
						
						else
						{
							$patient_surname = '-';
							$patient_othernames = '-';
						}
					}
					
					//staff
					else if($visit_type_id == 2)
					{
						if(is_array($staff_))
						{
							foreach($staff_ as $stud)
							{
								$staff_no = $stud->Staff_Number;
								
								if($staff_no == $strath_no){
									$patient_surname = $stud->Surname;
									$patient_othernames = $stud->Other_names;
								}
							}
						}
						
						else
						{
							$patient_surname = '-';
							$patient_othernames = '-';
						}
					}
				}
				else{
					$strath_no = '-';
					$patient_surname = $pat->patient_surname;
					$patient_othernames = $pat->patient_othernames;
				}
				$return["patients"][$count] = 
				array(
					'visit_type_name' => $pat->visit_type_name,
					'visit_type' => $pat->visit_type,
					'visit_date' => $pat->visit_date,
					'patient_id' => $pat->patient_id,
					'patient_surname' => $patient_surname,
					'patient_othernames' => $patient_othernames,
					'strath_no' => $strath_no
				);
				$count++;
			}
			
			echo json_encode($return);
		}
		
		else{
			
			$return["no_patients"][0] = 
			array(
					'visit_type_name' => "",
					'visit_type' => "",
					'visit_date' => "",
					'patient_id' => "",
					'patient_surname' => "",
					'patient_othernames' => "",
					'strath_no' => ""
				);
			
			echo json_encode($return);
		}
	}
	
	function set_visit_date()
	{
		$originalDate = $_POST["visit_date"];
		$newDate = date("Y-m-d", strtotime($originalDate));
		
		$_SESSION['reports_visit_date'] = $newDate;
		$this->patient_reports();
	}
	
	function select_patients()
	{
		if(isset($_SESSION['reports_visit_date']))
		{
			//set to chosen date
			$visit_date = $_SESSION['reports_visit_date'];
		}
		
		else
		{
			//set to current date
			$visit_date = '2013-10-14';//date('Y-m-d');
		}
		
		$_SESSION['page_title'] = 'Patients Report for '.$visit_date;
		
		$table = 'visit_type, visit, patients';
		$where = "visit.patient_id = patients.patient_id AND visit_type.visit_type_id = visit.visit_type AND visit.visit_date = '".$visit_date."'";
		$items = 'patients.patient_id, visit_type.visit_type_name, visit.visit_type, visit.visit_date, patients.patient_surname, patients.patient_othernames, patients.strath_no';
		$order = 'visit_type_name';
		
		$patients = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $patients;
	}
	
	function export_patients()
	{
		$this->load->library('excel');
		//get patients
		$patients = $this->select_patients();
		
		//get staff
		$staff_ = $this->database->select_entries('staff', 'Surname, Other_names, Staff_Number', 'Surname');
		
		//get students
		$student_ = $this->database->select_entries('student', 'Surname, Other_names, student_Number', 'Surname');
		
		/*
			-----------------------------------------------------------------------------------------
			Excel header
			-----------------------------------------------------------------------------------------
		*/
		$row_count = 0;
		$report[$row_count][0] = 'Visit Date';
		$report[$row_count][1] = 'Patient Type';
		$report[$row_count][2] = 'Surname';
		$report[$row_count][3] = 'Other Names';
		$report[$row_count][4] = 'Strath No.';
		$row_count = 1;
		
		if(is_array($patients))
		{
			$count = 0;
			
			foreach($patients as $pat)
			{
				$visit_type_id = $pat->visit_type;
				
				if($visit_type_id < 3){
					$strath_no = $pat->strath_no;
			
					//student
					if($visit_type_id == 1)
					{
						if(is_array($student_))
						{
							foreach($student_ as $stud)
							{
								$student_no = $stud->student_Number;
								
								if($student_no == $strath_no){
									$patient_surname = $stud->Surname;
									$patient_othernames = $stud->Other_names;
								}
							}
						}
						
						else
						{
							$patient_surname = '-';
							$patient_othernames = '-';
						}
					}
					
					//staff
					else if($visit_type_id == 2)
					{
						if(is_array($staff_))
						{
							foreach($staff_ as $stud)
							{
								$staff_no = $stud->Staff_Number;
								
								if($staff_no == $strath_no){
									$patient_surname = $stud->Surname;
									$patient_othernames = $stud->Other_names;
								}
							}
						}
						
						else
						{
							$patient_surname = '-';
							$patient_othernames = '-';
						}
					}
				}
				else{
					$strath_no = '-';
					$patient_surname = $pat->patient_surname;
					$patient_othernames = $pat->patient_othernames;
				}
				
				$report[$row_count][0] = $pat->visit_date;
				$report[$row_count][1] = $pat->visit_type_name;
				$report[$row_count][2] = $patient_surname;
				$report[$row_count][3] = $patient_othernames;
				$report[$row_count][4] = $strath_no;
				
				$row_count++;
			}
		}
		
		//create the excel document
		$this->excel->addArray ( $report );
		$this->excel->generateXML ($_SESSION['page_title']);
	}
	
	function print_patients()
	{
		//get patients
		$patients = $this->select_patients();
		
		//get staff
		$staff_ = $this->database->select_entries('staff', 'Surname, Other_names, Staff_Number', 'Surname');
		
		//get students
		$student_ = $this->database->select_entries('student', 'Surname, Other_names, student_Number', 'Surname');
		
		/*
			-----------------------------------------------------------------------------------------
			Measurements of the page cells
			-----------------------------------------------------------------------------------------
		*/
		$pageH = 5;//height of an output cell
		$pageW = 200;//width of the output cell. Takes the entire width of the page
		$lineBreak = 5;//height between cells
		
		/*
			-----------------------------------------------------------------------------------------
			Begin creating the PDF in A4
			-----------------------------------------------------------------------------------------
		*/
		$this->load->library('fpdf');
		$this->fpdf->AliasNbPages();
		$this->fpdf->AddPage();
		
		/*
			-----------------------------------------------------------------------------------------
			Colors of frames, background and Text
			-----------------------------------------------------------------------------------------
		*/
		$this->fpdf->SetDrawColor(092, 123, 29);//color of borders
		$this->fpdf->SetFillColor(0, 232, 12);//color of shading
		//$this->fpdf->SetTextColor(092, 123, 29);//color of text
		$this->fpdf->SetFont('Times', 'B', 12);
		
		/*
			-----------------------------------------------------------------------------------------
			Title of the document.
			-----------------------------------------------------------------------------------------
		*/
		$this->fpdf->Cell(0, $pageH, $_SESSION['page_title'], "B", 1, 'C');
		$this->fpdf->Ln($lineBreak);
		
		/*
			-----------------------------------------------------------------------------------------
			Subtitle of the document.
			-----------------------------------------------------------------------------------------
		*/
		$this->fpdf->Cell($pageW/15, $pageH, '#', 'B', 0, 'C');
		$this->fpdf->Cell($pageW/6, $pageH, 'Visit Date', 'B', 0, 'C');
		$this->fpdf->Cell($pageW/5, $pageH, 'Patient Type', 'B', 0, 'C');
		$this->fpdf->Cell($pageW/6, $pageH, 'Surname', 'B', 0, 'C');
		$this->fpdf->Cell($pageW/4, $pageH, 'Other Names', 'B', 0, 'C');
		$this->fpdf->Cell($pageW/12, $pageH, 'Strath No.', 'B', 1, 'C');
		
		/*
			-----------------------------------------------------------------------------------------
			Text, font & fill details
			-----------------------------------------------------------------------------------------
		*/
		$this->fpdf->setFont('Times', '', 12);
		$this->fpdf->SetFillColor(174, 255, 187); //226, 225, 225

		/*
			-----------------------------------------------------------------------------------------
			Set width and height of every cell
			-----------------------------------------------------------------------------------------
		*/
		$pageH = 5;//height of an output cell
		$pageW = 200;//width of the output cell. Takes the entire width of the page
		$fill = FALSE;
		
		if(is_array($patients))
		{
			$count = 0;
			
			foreach($patients as $pat)
			{
				$visit_type_id = $pat->visit_type;
				
				if($visit_type_id < 3){
					$strath_no = $pat->strath_no;
			
					//student
					if($visit_type_id == 1)
					{
						if(is_array($student_))
						{
							foreach($student_ as $stud)
							{
								$student_no = $stud->student_Number;
								
								if($student_no == $strath_no){
									$patient_surname = $stud->Surname;
									$patient_othernames = $stud->Other_names;
								}
							}
						}
						
						else
						{
							$patient_surname = '-';
							$patient_othernames = '-';
						}
					}
					
					//staff
					else if($visit_type_id == 2)
					{
						if(is_array($staff_))
						{
							foreach($staff_ as $stud)
							{
								$staff_no = $stud->Staff_Number;
								
								if($staff_no == $strath_no){
									$patient_surname = $stud->Surname;
									$patient_othernames = $stud->Other_names;
								}
							}
						}
						
						else
						{
							$patient_surname = '-';
							$patient_othernames = '-';
						}
					}
				}
				else{
					$strath_no = '-';
					$patient_surname = $pat->patient_surname;
					$patient_othernames = $pat->patient_othernames;
				}
				
				$count++;
				
				$this->fpdf->Cell($pageW/15, $pageH, $count, 'B', 0,'L',$fill);
				$this->fpdf->Cell($pageW/6, $pageH, $pat->visit_date, 'B', 0, 'L',$fill);
				$this->fpdf->Cell($pageW/5, $pageH, $pat->visit_type_name, 'B', 0, 'L',$fill);
				$this->fpdf->Cell($pageW/6, $pageH, $patient_surname, 'B', 0, 'L',$fill);
				$this->fpdf->Cell($pageW/4, $pageH, $patient_othernames, 'B', 0, 'L',$fill);
				$this->fpdf->Cell($pageW/12, $pageH, $strath_no, 'B', 1, 'L',$fill);
				
				if(($count % 2) == 0){
					$fill = FALSE;
				}
				else{
					$fill = TRUE;
				}
			}
		}
			
		$this->fpdf->Output();
	}
}
