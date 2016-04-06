<?php

$patient_id = $this->nurse_model->get_patient_id($visit_id);


$get_medical_rs = $this->nurse_model->get_hpco_notes($visit_id);
$num_rows = count($get_medical_rs);
//echo $num_rows;

if($num_rows > 0){
	foreach ($get_medical_rs as $key2) :
		$hpco = $key2->hpco_description;
	endforeach;
	
echo
'
	<div class="row">
		<div class="col-md-12">
	        <div class="row">
	            <div class="col-md-12">

	              <!-- Widget -->
	              <div class="widget boxed">
	                    <!-- Widget head -->
	                    <div class="widget-head">
	                      <h4 class="pull-left"><i class="icon-reorder"></i>Hp C/O</h4>
	                      <div class="widget-icons pull-right">
	                        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	                        <a href="#" class="wclose"><i class="icon-remove"></i></a>
	                      </div>
	                      <div class="clearfix"></div>
	                    </div>             

	               		<!-- Widget content -->
	                    <div class="widget-content">
	                        <div class="padd">
			                	<div class="col-md-12">
				                	<div class="form-group">
				                		<div class="col-lg-12">
											<textarea id="hpco" rows="5" cols="50" class="form-control col-md-6" >'.$hpco.'</textarea>
										</div>
									</div>
								</div>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
';
}

else{
echo

'
	<div class="row">
		<div class="col-md-12">
	        <div class="row">
	            <div class="col-md-12">

	              <!-- Widget -->
	              <div class="widget boxed">
	                    <!-- Widget head -->
	                    <div class="widget-head">
	                      <h4 class="pull-left"><i class="icon-reorder"></i>Hp C/O</h4>
	                      <div class="widget-icons pull-right">
	                        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	                        <a href="#" class="wclose"><i class="icon-remove"></i></a>
	                      </div>
	                      <div class="clearfix"></div>
	                    </div>             

	               		<!-- Widget content -->
	                    <div class="widget-content">
	                        <div class="padd">
			                	<div class="col-md-12">
				                	<div class="form-group">
				                		<div class="col-lg-12">
											<textarea id="hpco" rows="5" cols="50" class="form-control col-md-6" ></textarea>
										</div>
									</div>
								</div>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div> ';
	}

	$get_histories_rs = $this->nurse_model->get_histories_notes($visit_id);
	$history_num_rows = count($get_histories_rs);
	//echo $history_num_rows;

	if($history_num_rows > 0){
		foreach ($get_histories_rs as $key3) :
			$past_dental_history = $key3->past_dental_history;
			$past_medical_history = $key3->past_medical_history;
		endforeach;
		
	echo
	'
		<div class="col-md-12">
	        <div class="row">
	            <div class="col-md-12">

	              <!-- Widget -->
	              <div class="widget boxed">
	                    <!-- Widget head -->
	                    <div class="widget-head">
	                      <h4 class="pull-left"><i class="icon-reorder"></i>Histories</h4>
	                      <div class="widget-icons pull-right">
	                        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	                        <a href="#" class="wclose"><i class="icon-remove"></i></a>
	                      </div>
	                      <div class="clearfix"></div>
	                    </div>             

	               		<!-- Widget content -->
	                    <div class="widget-content">
	                        <div class="padd">
			                	<div class="col-md-12">
				                	<div class="form-group">
				                		<label class="col-lg-2 control-label">PD Hx : </label>
				                		<div class="col-lg-10">
											<textarea id="past_dental_hx" rows="5" cols="50" class="form-control col-md-6" >'.$past_dental_history.'</textarea>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
				                		<label class="col-lg-2 control-label">PM Hx : </label>
										<div class="col-lg-10">
											<textarea id="past_medical_hx" rows="5" cols="50" class="form-control col-md-6" >'.$past_medical_history.'</textarea>
										</div>
									</div>
								</div>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	';
	}

	else{
	echo

	'

		<div class="col-md-12">
	        <div class="row">
	            <div class="col-md-12">

	              <!-- Widget -->
	              <div class="widget boxed">
	                    <!-- Widget head -->
	                    <div class="widget-head">
	                      <h4 class="pull-left"><i class="icon-reorder"></i>Histories</h4>
	                      <div class="widget-icons pull-right">
	                        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	                        <a href="#" class="wclose"><i class="icon-remove"></i></a>
	                      </div>
	                      <div class="clearfix"></div>
	                    </div>             

	               		<!-- Widget content -->
	                    <div class="widget-content">
	                        <div class="padd">
			                	<div class="col-md-12">
				                	<div class="form-group">
				                		<label class="col-lg-2 control-label">PD Hx : </label>
				                		<div class="col-lg-10">
											<textarea id="past_dental_hx" rows="5" cols="50" class="form-control col-md-6" ></textarea>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
				                		<label class="col-lg-2 control-label">PM Hx : </label>
										<div class="col-lg-10">
											<textarea id="past_medical_hx" rows="5" cols="50" class="form-control col-md-6" ></textarea>
										</div>
									</div>
								</div>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>';
	}

	$get_oc_rs = $this->nurse_model->get_oc_notes($visit_id);
	$oc_num_rows = count($get_oc_rs);
	//echo $oc_num_rows;

	if($oc_num_rows > 0){
		foreach ($get_oc_rs as $key2) :
			$soft_tissue = $key2->soft_tissue;
			$decayed = $key2->decayed;
			$filled = $key2->filled;
			$missing = $key2->missing;
		endforeach;
		
	echo
	'
		<div class="col-md-12">
	        <div class="row">
	            <div class="col-md-12">

	              <!-- Widget -->
	              <div class="widget boxed">
	                    <!-- Widget head -->
	                    <div class="widget-head">
	                      <h4 class="pull-left"><i class="icon-reorder"></i>O/C</h4>
	                      <div class="widget-icons pull-right">
	                        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	                        <a href="#" class="wclose"><i class="icon-remove"></i></a>
	                      </div>
	                      <div class="clearfix"></div>
	                    </div>             

	               		<!-- Widget content -->
	                    <div class="widget-content">
	                        <div class="padd">
			                	<div class="col-md-12">
				                	<div class="form-group">
				                		<label class="col-lg-2 control-label">Soft Tissue : </label>
				                		<div class="col-lg-10">
											<textarea id="soft_tissue" rows="5" cols="50" class="form-control col-md-6" >'.$soft_tissue.'</textarea>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
				                		<label class="col-lg-2 control-label">Hard Tissue : </label>
				                		<div class="col-md-10">
				                			<div class="col-md-12">
					                			<label class="col-lg-2 control-label">Decayed : </label>
												<div class="col-lg-10">
													<textarea id="decayed" rows="5" cols="50" class="form-control col-md-6" >'.$decayed.'</textarea>
												</div>
											</div>
											<div class="col-md-12">
												<label class="col-lg-2 control-label">Missing : </label>
												<div class="col-lg-10">
													<textarea id="missing" rows="5" cols="50" class="form-control col-md-6" >'.$missing.'</textarea>
												</div>
											</div>
											<div class="col-md-12">
												<label class="col-lg-2 control-label">Filled : </label>
												<div class="col-lg-10">
													<textarea id="filled" rows="5" cols="50" class="form-control col-md-6" >'.$filled.'</textarea>
												</div>
											</div>
										</div>
									</div>
								</div>

								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	';
	}

	else{
	echo

	'

	  <div class="col-md-12">
	        <div class="row">
	            <div class="col-md-12">

	              <!-- Widget -->
	              <div class="widget boxed">
	                    <!-- Widget head -->
	                    <div class="widget-head">
	                      <h4 class="pull-left"><i class="icon-reorder"></i>O/C</h4>
	                      <div class="widget-icons pull-right">
	                        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	                        <a href="#" class="wclose"><i class="icon-remove"></i></a>
	                      </div>
	                      <div class="clearfix"></div>
	                    </div>             

	               		<!-- Widget content -->
	                    <div class="widget-content">
	                        <div class="padd">
			                	<div class="col-md-12">
				                	<div class="form-group">
				                		<label class="col-lg-2 control-label">Soft Tissue : </label>
				                		<div class="col-lg-10">
											<textarea id="soft_tissue" rows="5" cols="50" class="form-control col-md-6" ></textarea>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
				                		<label class="col-lg-2 control-label">Hard Tissue : </label>
				                		<div class="col-md-10">
				                			<div class="col-md-12">
					                			<label class="col-lg-2 control-label">Decayed : </label>
												<div class="col-lg-10">
													<textarea id="decayed" rows="5" cols="50" class="form-control col-md-6" ></textarea>
												</div>
											</div>
											<div class="col-md-12">
												<label class="col-lg-2 control-label">Missing : </label>
												<div class="col-lg-10">
													<textarea id="missing" rows="5" cols="50" class="form-control col-md-6" ></textarea>
												</div>
											</div>
											<div class="col-md-12">
												<label class="col-lg-2 control-label">Filled : </label>
												<div class="col-lg-10">
													<textarea id="filled" rows="5" cols="50" class="form-control col-md-6" ></textarea>
												</div>
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>';
	}
	$get_inves_rs = $this->nurse_model->get_investigations_notes($visit_id);
	$invest_num_rows = count($get_inves_rs);
	//echo $invest_num_rows;

	if($invest_num_rows > 0){
		foreach ($get_inves_rs as $key4) :
			$investigation = $key4->investigation;
		endforeach;
		
	echo
	'
		<div class="col-md-12">
	        <div class="row">
	            <div class="col-md-12">

	              <!-- Widget -->
	              <div class="widget boxed">
	                    <!-- Widget head -->
	                    <div class="widget-head">
	                      <h4 class="pull-left"><i class="icon-reorder"></i>Investigation</h4>
	                      <div class="widget-icons pull-right">
	                        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	                        <a href="#" class="wclose"><i class="icon-remove"></i></a>
	                      </div>
	                      <div class="clearfix"></div>
	                    </div>             

	               		<!-- Widget content -->
	                    <div class="widget-content">
	                        <div class="padd">
								<div class="col-md-12">
									<div class="form-group">
										<div class="col-lg-12">
											<textarea id="investigations" rows="5" cols="50" class="form-control col-md-6" >'.$investigation.'</textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	';
	}

	else{
	echo

	'
		<div class="col-md-12">
	        <div class="row">
	            <div class="col-md-12">

	              <!-- Widget -->
	              <div class="widget boxed">
	                    <!-- Widget head -->
	                    <div class="widget-head">
	                      <h4 class="pull-left"><i class="icon-reorder"></i>Investigation</h4>
	                      <div class="widget-icons pull-right">
	                        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	                        <a href="#" class="wclose"><i class="icon-remove"></i></a>
	                      </div>
	                      <div class="clearfix"></div>
	                    </div>             

	               		<!-- Widget content -->
	                    <div class="widget-content">
	                        <div class="padd">
								<div class="col-md-12">
									<div class="form-group">
										<div class="col-lg-12">
											<textarea id="investigations" rows="5" cols="50" class="form-control col-md-6" ></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>';
	}
	$get_findings_rs = $this->nurse_model->get_findings_notes($visit_id);
	$find_num_rows = count($get_findings_rs);
	//echo $find_num_rows;

	if($find_num_rows > 0){
		foreach ($get_findings_rs as $key4) :
			$findings = $key4->finding_description;
		endforeach;
		
	echo
	'
		<div class="col-md-12">
	        <div class="row">
	            <div class="col-md-12">

	              <!-- Widget -->
	              <div class="widget boxed">
	                    <!-- Widget head -->
	                    <div class="widget-head">
	                      <h4 class="pull-left"><i class="icon-reorder"></i>Findings</h4>
	                      <div class="widget-icons pull-right">
	                        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	                        <a href="#" class="wclose"><i class="icon-remove"></i></a>
	                      </div>
	                      <div class="clearfix"></div>
	                    </div>             

	               		<!-- Widget content -->
	                    <div class="widget-content">
	                        <div class="padd">
								<div class="col-md-12">
									<div class="form-group">
										<div class="col-lg-12">
											<textarea id="findings" rows="5" cols="50" class="form-control col-md-6" >'.$findings.'</textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	';
	}

	else{
	echo

	'

		<div class="col-md-12">
	        <div class="row">
	            <div class="col-md-12">

	              <!-- Widget -->
	              <div class="widget boxed">
	                    <!-- Widget head -->
	                    <div class="widget-head">
	                      <h4 class="pull-left"><i class="icon-reorder"></i>Findings</h4>
	                      <div class="widget-icons pull-right">
	                        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	                        <a href="#" class="wclose"><i class="icon-remove"></i></a>
	                      </div>
	                      <div class="clearfix"></div>
	                    </div>             

	               		<!-- Widget content -->
	                    <div class="widget-content">
	                        <div class="padd">
								<div class="col-md-12">
									<div class="form-group">
										<div class="col-lg-12">
											<textarea id="findings" rows="5" cols="50" class="form-control col-md-6" ></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>';
	}
	$get_plan_rs = $this->nurse_model->get_plan_notes($visit_id);
	$plannum_rows = count($get_plan_rs);
	//echo $plannum_rows;

	if($plannum_rows > 0){
		foreach ($get_plan_rs as $key2) :
			$plan_description = $key2->plan_description;
		endforeach;
		
	echo
	'
		<div class="col-md-12">
	        <div class="row">
	            <div class="col-md-12">

	              <!-- Widget -->
	              <div class="widget boxed">
	                    <!-- Widget head -->
	                    <div class="widget-head">
	                      <h4 class="pull-left"><i class="icon-reorder"></i>Plan</h4>
	                      <div class="widget-icons pull-right">
	                        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	                        <a href="#" class="wclose"><i class="icon-remove"></i></a>
	                      </div>
	                      <div class="clearfix"></div>
	                    </div>             

	               		<!-- Widget content -->
	                    <div class="widget-content">
	                        <div class="padd">
								<div class="col-md-12">
									<div class="form-group">
										<div class="col-lg-12">
											<textarea id="plan" rows="5" cols="50" class="form-control col-md-6" >'.$plan_description.'</textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	';
	}

	else{
	echo

	'

		<div class="col-md-12">
	        <div class="row">
	            <div class="col-md-12">

	              <!-- Widget -->
	              <div class="widget boxed">
	                    <!-- Widget head -->
	                    <div class="widget-head">
	                      <h4 class="pull-left"><i class="icon-reorder"></i>Plan</h4>
	                      <div class="widget-icons pull-right">
	                        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	                        <a href="#" class="wclose"><i class="icon-remove"></i></a>
	                      </div>
	                      <div class="clearfix"></div>
	                    </div>             

	               		<!-- Widget content -->
	                    <div class="widget-content">
	                        <div class="padd">
								<div class="col-md-12">
									<div class="form-group">
										<div class="col-lg-12">
											<textarea id="plan" rows="5" cols="50" class="form-control col-md-6" ></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>';
	}
	$get_rx_rs = $this->nurse_model->get_rxdone_notes($visit_id);
	$rs_num_rows = count($get_rx_rs);
	//echo $rs_num_rows;

	if($rs_num_rows > 0){
		foreach ($get_rx_rs as $key6) :
			$rx_description = $key6->rx_description;
		endforeach;
		
	echo
	'
		<div class="col-md-12">
	        <div class="row">
	            <div class="col-md-12">

	              <!-- Widget -->
	              <div class="widget boxed">
	                    <!-- Widget head -->
	                    <div class="widget-head">
	                      <h4 class="pull-left"><i class="icon-reorder"></i>Rx Done </h4>
	                      <div class="widget-icons pull-right">
	                        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	                        <a href="#" class="wclose"><i class="icon-remove"></i></a>
	                      </div>
	                      <div class="clearfix"></div>
	                    </div>             

	               		<!-- Widget content -->
	                    <div class="widget-content">
	                        <div class="padd">
								<div class="col-md-12">
									<div class="form-group">
										<div class="col-lg-12">
											<textarea id="rx" rows="5" cols="50" class="form-control col-md-6" >'.$rx_description.'</textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	';
	}

	else{
	echo

	'
		<div class="col-md-12">
	        <div class="row">
	            <div class="col-md-12">

	              <!-- Widget -->
	              <div class="widget boxed">
	                    <!-- Widget head -->
	                    <div class="widget-head">
	                      <h4 class="pull-left"><i class="icon-reorder"></i>Rx Done </h4>
	                      <div class="widget-icons pull-right">
	                        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	                        <a href="#" class="wclose"><i class="icon-remove"></i></a>
	                      </div>
	                      <div class="clearfix"></div>
	                    </div>             

	               		<!-- Widget content -->
	                    <div class="widget-content">
	                        <div class="padd">
								<div class="col-md-12">
									<div class="form-group">
										<div class="col-lg-12">
											<textarea id="rx" rows="5" cols="50" class="form-control col-md-6" ></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>';
	}
	$get_tca_rs = $this->nurse_model->get_tca_notes($visit_id);
	$tca_num_rows = count($get_tca_rs);
	//echo $tca_num_rows;

	if($tca_num_rows > 0){
		foreach ($get_tca_rs as $key7):
			$tca_description = $key7->tca_description;
		endforeach;
		
	echo
	'
		<div class="col-md-12">
	        <div class="row">
	            <div class="col-md-12">

	              <!-- Widget -->
	              <div class="widget boxed">
	                    <!-- Widget head -->
	                    <div class="widget-head">
	                      <h4 class="pull-left"><i class="icon-reorder"></i>TCA</h4>
	                      <div class="widget-icons pull-right">
	                        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	                        <a href="#" class="wclose"><i class="icon-remove"></i></a>
	                      </div>
	                      <div class="clearfix"></div>
	                    </div>             

	               		<!-- Widget content -->
	                    <div class="widget-content">
	                        <div class="padd">
								<div class="col-md-12">
									<div class="form-group">
										<div class="col-lg-12">
											<textarea id="tca" rows="5" cols="50" class="form-control col-md-6" >'.$tca_description.'</textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	';
	}

	else{
	echo

	'
		<div class="col-md-12">
	        <div class="row">
	            <div class="col-md-12">

	              <!-- Widget -->
	              <div class="widget boxed">
	                    <!-- Widget head -->
	                    <div class="widget-head">
	                      <h4 class="pull-left"><i class="icon-reorder"></i>TCA</h4>
	                      <div class="widget-icons pull-right">
	                        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	                        <a href="#" class="wclose"><i class="icon-remove"></i></a>
	                      </div>
	                      <div class="clearfix"></div>
	                    </div>             

	               		<!-- Widget content -->
	                    <div class="widget-content">
	                        <div class="padd">
								<div class="col-md-12">
									<div class="form-group">
										<div class="col-lg-12">
											<textarea id="tca" rows="5" cols="50" class="form-control col-md-6" ></textarea>
										</div>
									</div>
								</div>
							
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
';
}

echo '

	<div class="col-md-12">
        <div class="form-group">
            <div class="col-lg-12">
                <div class="center-align">
                      <a hred="#" class="btn btn-large btn-info" onclick="save_all_soap('.$visit_id.')">Save Doctors Notes</a>
                  </div>
            </div>
        </div>
    </div>';
	
?>
