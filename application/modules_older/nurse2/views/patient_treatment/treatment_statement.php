 
<div class="row">
    <div class="col-md-12">

      <!-- Widget -->
      <div class="widget boxed">
        <!-- Widget head -->
        <div class="widget-head">
          <h4 class="pull-left"><i class="icon-reorder"></i>Patient History</h4>
          <div class="widget-icons pull-right">
            <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
            <a href="#" class="wclose"><i class="icon-remove"></i></a>
          </div>
          <div class="clearfix"></div>
        </div>             

        <!-- Widget content -->
        <div class="widget-content">
          <div class="padd">
          
<?php
		$search = $this->session->userdata('visit_search');
		
		if(!empty($search))
		{
			echo '<a href="'.site_url().'/nurse/close_queue_search" class="btn btn-warning">Close Search</a>';
		}
		$result = '';
	
		//if users exist display them
		if ($query->num_rows() > 0)
		{
			$count = $page;
			
			$result .= 
				'
					<table class="table table-hover table-bordered ">
					  <thead>
						<tr>
						  <th>#</th>
						  <th>Visit Date</th>
						  <th>Doctor notes</th>
						  <th>Waiting time</th>
						  <th>Doctor</th>
						</tr>
					  </thead>
					  <tbody>
				';
			
			$personnel_query = $this->personnel_model->get_all_personnel();
			$count = 1;
			foreach ($query->result() as $row)
			{
				$visit_date = date('jS M Y',strtotime($row->visit_date));
				$visit_time = date('H:i a',strtotime($row->visit_time));


				

				$visit_id1 = $row->visit_id;
				$waiting_time = $this->nurse_model->waiting_time($visit_id1);
				$patient_id = $row->patient_id;
				$personnel_id = $row->personnel_id;
				$dependant_id = $row->dependant_id;
				$strath_no = $row->strath_no;
				$created_by = $row->created_by;
				$modified_by = $row->modified_by;
				$visit_type_id = $row->visit_type_id;
				$visit_type = $row->visit_type;
				$created = $row->patient_date;
				$last_modified = $row->last_modified;
				$last_visit = $row->last_visit;
				$notes = $this->nurse_model->get_doctors_visit_notes($visit_id1);
				//staff & dependant
				if($visit_type == 2)
				{
					//dependant
					if($dependant_id > 0)
					{
						$patient_type = $this->reception_model->get_patient_type($visit_type_id, $dependant_id);
						$visit_type = 'Dependant';
						$dependant_query = $this->reception_model->get_dependant($strath_no);
						
						if($dependant_query->num_rows() > 0)
						{
							$dependants_result = $dependant_query->row();
							
							$patient_othernames = $dependants_result->other_names;
							$patient_surname = $dependants_result->names;
							$patient_date_of_birth = $dependants_result->DOB;
							$relationship = $dependants_result->relation;
							$gender = $dependants_result->Gender;
						}
						
						else
						{
							$patient_othernames = '<span class="label label-important">Dependant not found</span>';
							$patient_surname = '';
							$patient_date_of_birth = '';
							$relationship = '';
							$gender = '';
						}
					}
					
					//staff
					else
					{
						$patient_type = $this->reception_model->get_patient_type($visit_type_id, $dependant_id);
						$staff_query = $this->reception_model->get_staff($strath_no);
						$visit_type = 'Staff';
						
						if($staff_query->num_rows() > 0)
						{
							$staff_result = $staff_query->row();
							
							$patient_surname = $staff_result->Surname;
							$patient_othernames = $staff_result->Other_names;
							$patient_date_of_birth = $staff_result->DOB;
							$patient_phone1 = $staff_result->contact;
							$gender = $staff_result->gender;
						}
						
						else
						{
							$patient_othernames = '<span class="label label-important">Staff not found</span>';
							$patient_surname = '';
							$patient_date_of_birth = '';
							$relationship = '';
							$gender = '';
							$patient_type = '';
						}
					}
				}
				
				//student
				else if($visit_type == 1)
				{
					$student_query = $this->reception_model->get_student($strath_no);
					$patient_type = $this->reception_model->get_patient_type($visit_type_id);
					$visit_type = 'Student';
					
					if($student_query->num_rows() > 0)
					{
						$student_result = $student_query->row();
						
						$patient_surname = $student_result->Surname;
						$patient_othernames = $student_result->Other_names;
						$patient_date_of_birth = $student_result->DOB;
						$patient_phone1 = $student_result->contact;
						$gender = $student_result->gender;
					}
					
					else
					{
						$patient_othernames = '<span class="label label-important">Student not found</span>';
						$patient_surname = '';
						$patient_date_of_birth = '';
						$relationship = '';
						$gender = '';
					}
				}
				
				//other patient
				else
				{
					$patient_type = $this->reception_model->get_patient_type($visit_type_id);
					
					if($visit_type == 3)
					{
						$visit_type = 'Other';
					}
					else if($visit_type == 4)
					{
						$visit_type = 'Insurance';
					}
					else
					{
						$visit_type = 'General';
					}
					
					$patient_othernames = $row->patient_othernames;
					$patient_surname = $row->patient_surname;
					$title_id = $row->title_id;
					$patient_date_of_birth = $row->patient_date_of_birth;
					$civil_status_id = $row->civil_status_id;
					$patient_address = $row->patient_address;
					$patient_post_code = $row->patient_postalcode;
					$patient_town = $row->patient_town;
					$patient_phone1 = $row->patient_phone1;
					$patient_phone2 = $row->patient_phone2;
					$patient_email = $row->patient_email;
					$patient_national_id = $row->patient_national_id;
					$religion_id = $row->religion_id;
					$gender_id = $row->gender_id;
					$patient_kin_othernames = $row->patient_kin_othernames;
					$patient_kin_sname = $row->patient_kin_sname;
					$relationship_id = $row->relationship_id;
				}
				
				//creators and editors
				if($personnel_query->num_rows() > 0)
				{
					$personnel_result = $personnel_query->result();
					
					foreach($personnel_result as $adm)
					{
						$personnel_id2 = $adm->personnel_id;
						
						if($personnel_id == $personnel_id2)
						{
							$doctor = $adm->personnel_fname;
							break;
						}
						
						else
						{
							$doctor = '-';
						}
					}
				}
				
				else
				{
					$doctor = '-';
				}

				//  get the visit assessment

				$assesment_rs = $this->nurse_model->get_assessment($visit_id1);
				 $assesment_num_rows = count($assesment_rs);
				$assessment = '';
				if($assesment_num_rows > 0){
					foreach ($assesment_rs as $key_assessment):
					$visit_assessment = $key_assessment->visit_assessment;
					endforeach;
					$assessment .="".$visit_assessment."";
					
				}
				else
				{
					$assessment .='-';
				}
				// end of the visit assessment


				//  start of plan
				$plan_rs = $this->nurse_model->get_plan($visit_id1);
				$plan_num_rows = count($plan_rs);
				$plan = "";
				if($plan_num_rows > 0){
					foreach ($plan_rs as $key_plan):
						$visit_plan = $key_plan->visit_plan;
					endforeach;
					$plan .="".$visit_plan."";
					
				}
				else
				{
					$plan .="-";
				}
				// end of plan

				// start of diagnosis
				$diagnosis_rs = $this->nurse_model->get_diagnosis($visit_id1);
				$diagnosis_num_rows = count($diagnosis_rs);
				//echo $num_rows;
				$patient_diagnosis = "";
				if($diagnosis_num_rows > 0){
					foreach ($diagnosis_rs as $diagnosis_key):
						$diagnosis_id = $diagnosis_key->diagnosis_id;
						$diagnosis_name = $diagnosis_key->diseases_name;
						$diagnosis_code = $diagnosis_key->diseases_code;
						$patient_diagnosis .="".$diagnosis_code."-".$diagnosis_name."<br>";
						
					endforeach;
				}
				else
				{
					$patient_diagnosis .= "-";
				}
				// end of diagnosis
				
				$notes = '';
				$get_medical_rs = $this->nurse_model->get_hpco_notes($visit_id1);
				$num_rows = count($get_medical_rs);
				//echo $num_rows;

				if($num_rows > 0){
					foreach ($get_medical_rs as $key2) :
						$hpco = $key2->hpco_description;
					endforeach;

					$notes .= '<span class="bold">Hp C/O</span> '.$hpco.'';
				}
				else
				{
					$notes .= '';
				}
				
				$get_histories_rs = $this->nurse_model->get_histories_notes($visit_id1);
				$history_num_rows = count($get_histories_rs);
				//echo $history_num_rows;

				if($history_num_rows > 0){
					foreach ($get_histories_rs as $key3) :
						$past_dental_history = $key3->past_dental_history;
						$past_medical_history = $key3->past_medical_history;
					endforeach;
					$notes .= '<p><span class="bold">PM Hx</span> '.$past_medical_history.'</p>';
					$notes .= '<p><span class="bold">PD Hx</span> '.$past_dental_history.'</p>';
				}
				else
				{
					$notes .='';
				}

				$get_oc_rs = $this->nurse_model->get_oc_notes($visit_id1);
				$oc_num_rows = count($get_oc_rs);
				//echo $oc_num_rows;

				if($oc_num_rows > 0){
					foreach ($get_oc_rs as $key2) :
						$soft_tissue = $key2->soft_tissue;
						$decayed = $key2->decayed;
						$filled = $key2->filled;
						$missing = $key2->missing;
					endforeach;
					$notes .= '<p><span class="bold">Soft Tissue</span> '.$soft_tissue.'</p>';
					$notes .= '<p><span class="bold">Hard Tissue</span></p>';
					$notes .= '<p><span class="bold">Decayed : </span> '.$decayed.'</p>';
					$notes .= '<p><span class="bold">Filled :</span> '.$filled.'</p>';
					$notes .= '<p><span class="bold">Filled :</span> '.$missing.'</p>';
				}
				else
				{
					$notes .='';
				}

				$get_inves_rs = $this->nurse_model->get_investigations_notes($visit_id1);
				$invest_num_rows = count($get_inves_rs);
				//echo $invest_num_rows;

				if($invest_num_rows > 0){
					foreach ($get_inves_rs as $key4) :
						$investigation = $key4->investigation;
					endforeach;
					$notes .= '<p><span class="bold">Investigations : </span> '.$investigation.'</p>';
				}
				else
				{
					$notes .= '';
				}
				$get_findings_rs = $this->nurse_model->get_findings_notes($visit_id1);
				$find_num_rows = count($get_findings_rs);
				//echo $find_num_rows;

				if($find_num_rows > 0){
					foreach ($get_findings_rs as $key4) :
						$findings = $key4->finding_description;
					endforeach;
					$notes .= '<p><span class="bold">Findings : </span> '.$findings.'</p>';
				}
				else
				{
					$notes .= '';
				}

				$get_plan_rs = $this->nurse_model->get_plan_notes($visit_id1);
				$plannum_rows = count($get_plan_rs);
				//echo $plannum_rows;

				if($plannum_rows > 0){
					foreach ($get_plan_rs as $key2) :
						$plan_description = $key2->plan_description;
					endforeach;
					$notes .= '<p><span class="bold">Plan : </span> '.$plan_description.'</p>';
				}
				else
				{
					$notes .= '';
				}

				$get_rx_rs = $this->nurse_model->get_rxdone_notes($visit_id1);
				$rs_num_rows = count($get_rx_rs);
				//echo $rs_num_rows;

				if($rs_num_rows > 0){
					foreach ($get_rx_rs as $key6) :
						$rx_description = $key6->rx_description;
					endforeach;
					$notes .= '<p><span class="bold">Rx Done : </span> '.$rx_description.'</p>';
				}
				else
				{
					$notes .= '';
				}
				$get_tca_rs = $this->nurse_model->get_tca_notes($visit_id1);
				$tca_num_rows = count($get_tca_rs);
				//echo $tca_num_rows;

				if($tca_num_rows > 0){
					foreach ($get_tca_rs as $key7):
						$tca_description = $key7->tca_description;
					endforeach;
					$notes .= '<p><span class="bold">TCA : </span> '.$tca_description.'</p>';
				}
				else
				{
					$notes .= '';
				}
				
				
				$result .= 
					'
						<tr>
							<td>'.$count.'</td>
							<td>'.$visit_date.'</td>
							<td>'.$notes.'</td>
							<td align=center>'.$waiting_time.'</td>
							
							<td>'.$doctor.'</td>
						</tr> 
					';
					$count++;
			}
			
			$result .= 
			'
						  </tbody>
						</table>
			';
		}
		
		else
		{
			$result .= "There are no patients";
		}
		
		echo $result;
?>
          </div>
          
          <div class="widget-foot">
                                
				<?php if(isset($links)){echo $links;}?>
            
                <div class="clearfix"></div> 
            
            </div>
        </div>
        <!-- Widget ends -->

      </div>
    </div>
  </div>