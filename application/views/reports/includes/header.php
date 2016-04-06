
<!DOCTYPE html>
<html lang="en">
<input type="hidden" id="baseurl" value="<?php echo base_url();?>"/>
<head>
	<meta charset="utf-8">
	<title>Reports</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
 	<link type="text/css" rel="stylesheet" href="<?php echo base_url("css/interface.css"); ?>" />
	<link type="text/css" rel="stylesheet" href="<?php echo base_url("css/bootstrap.css"); ?>" />

	<link href="<?php echo base_url();?>assets/fuel/dist/css/fuelux.css" rel="stylesheet">

	<script src="<?php echo base_url();?>assets/fuel/lib/require.js"></script>
	<script src="<?php echo base_url();?>jquery/jquery-2.1.1.min.js"></script>

	<script>
		var link = $('#baseurl').val();
		
		$( document ).ready(function() {
			requirejs.config({
				config: {
					moment: {
						noGlobal: true
					}
				},
				paths: {
					jquery: link+'assets/fuel/lib/jquery-1.9.1.min',
					underscore: 'http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.3.3/underscore-min',
					bootstrap: link+'assets/fuel/lib/bootstrap/js',
					fuelux: link+'assets/fuel/src',
					moment: link+'assets/fuel/lib/moment', // comment out if you dont want momentjs to be default
					sample2: link+'assets/fuel/sample/datasource',
					sample3: link+'assets/fuel/sample/datasourceTree'
				}
			});
	
			require(['jquery', 'sample2', 'sample3', 'fuelux/all'], function ($, StaticDataSource, DataSourceTree) {
				
				// DATEPICKER
				$('#datepicker1').datepicker();
	
				$('#datepicker1').on('changed', function( event, data ) {
					console.log( 'datepicker change event fired' );
				});
	
				$('#datepicker1').on('inputParsingFailed', function() {
					console.log( 'datepicker inputParsingFailed event fired' );
				});
	
				$('#datepicker-enable').on('click', function() {
					$('#datepicker1').datepicker('enable');
				});
	
				$('#datepicker-disabled').on('click', function() {
					$('#datepicker1').datepicker('disable');
				});
	
				$('#datepicker-logFormattedDate').on('click', function() {
					console.log( $('#datepicker1').datepicker('getFormattedDate') );
				});
	
				$('#datepicker-logDateUnix').on('click', function() {
					console.log( $('#datepicker1').datepicker('getDate', { unix: true } ) );
				});
	
				$('#datepicker-logDateObj').on('click', function() {
					console.log( $('#datepicker1').datepicker('getDate') );
				});
	
				$('#datepicker-setDate').on('click', function() {
					var futureDate = new Date(+new Date() + ( 7 * 24 * 60 * 60 * 1000 ) );
					$('#datepicker1').datepicker('setDate', futureDate );
					console.log( $('#datepicker1').datepicker('getDate') );
				});
				
				$.ajax({
					type:'GET',
					url: link + "index.php/reports/get_all_patients",
					cache:false,
					contentType: false,
					processData: false,
					dataType: 'json',
					success:function(result){
						// DATAGRID
						var dataSource = new StaticDataSource({
							columns: [
								{
									property: 'visit_date',
									label: 'Visit Date',
									sortable: true
								},
								{
									property: 'visit_type_name',
									label: 'Patient Type',
									sortable: true
								},
								{
									property: 'patient_surname',
									label: 'Surname',
									sortable: true
								},
								{
									property: 'patient_othernames',
									label: 'Other Names',
									sortable: true
								},
								{
									property: 'strath_no',
									label: 'Strath No.',
									sortable: true
								}
							],
							data: result.patients,
							delay: 250
						});
			
						$('#MySelectStretchGrid').datagrid({
							dataSource: dataSource,
							stretchHeight: true,
							noDataFoundHTML: '<b>Sorry, nothing to display.</b>',
							enableSelect: true,
							primaryKey: 'patient_id',
							multiSelect: true
						});
					},
					error: function(xhr, status, error) {
						//alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
						alert("Sorry. No patients for that day");
					}
				});
			});
		});
	</script>

	<style type="text/css">
        .span3 a.thumbnail{text-align: center;}
        .span3 a.thumbnail:hover{text-decoration:none;}
		
		.page_title {
			font-family: Baskerville,"Palatino Linotype",Palatino,"Century Schoolbook L","Times New Roman",serif;
			font-size: 24px;
			text-align: center;
			text-transform: capitalize;
			letter-spacing:0.1px
		}
		
		.datepicker input[type="text"]{height: 30px;}
    </style>
</head>
<body>
	<div id="header" class="container">
		<div id="logo">
			<h1><a href="#">SUMC</a></h1>
			<p><a href="http://www.strathmore.edu">administration</a></p>
		</div>
	</div>
	
	<div class="row-fluid">
    	<div class="span2 navbar-inner">
        
			<ul class="nav nav-list">
				<li class="nav-header">Administration</li>
				<li><a href='<?php echo site_url('administration/personnel')?>'>Personnel</a></li>
				
				<li><a href='<?php echo site_url('administration/services')?>'>Services</a></li>
				<li><a href='<?php echo site_url('administration/patient_type')?>'>Patient Type</a></li>
				<li><a href='<?php echo site_url('administration/add_credit')?>'>Set Patient Allowance</a></li>
				<li><a href='<?php echo site_url('administration/supportstaff')?>'>Strathmore Staff</a></li>
				<!--<li><a href='<?php echo site_url('administration/consultation_types')?>'>Consultation Types</a></li>
				<li><a href='<?php echo site_url('administration/procedure_charges')?>'>Procedure Charges</a></li>-->
				<li><a href='<?php echo site_url('administration/companies')?>'>Company</a></li>
				 <li><a href='<?php echo site_url('administration/insurance_company')?>'>Insurance Company</a></li>
				<li class="nav-header">Financial Reports</li>
				<!--<li><a href='<?php echo site_url('administration/personnel')?>'>Accounts</a></li>
				<li><a href='<?php echo site_url('administration/consultation_charges')?>'>Visits</a></li>-->
				<li><a href='<?php echo base_url('data/reports/reports.php')?>' target="_blank">Accounts</a></li>
				<li><a href='<?php echo base_url('data/reports/cash_reports.php')?>' target="_blank">Cash Reports</a></li>
				<!--<li><a href='<?php echo base_url('data/reports/creditors.php')?>' target="_blank">Creditors</a></li>-->
				<li><a href='<?php echo base_url('data/reports/debtors.php')?>' target="_blank">Debtors</a></li>
				<li><a href='<?php echo base_url('data/reports/expenses.php')?>' target="_blank">Expenses</a></li>
               	  	<li><a href='<?php echo base_url('index.php/reports/patient_reports')?>'>Patients</a></li>
				<li><a href='<?php echo base_url('data/reports/summary.php')?>' target="_blank">Summary</a></li>
			  
				<li class="nav-header">Logs</li>
				<li><a href='<?php echo site_url('administration/personnel')?>'>Login Sessions</a></li>
				<li><a href='<?php echo site_url('administration/consultation_charges')?>'>Usage</a></li>
			  
				<li class="nav-header">Export</li>
				<li><a href='<?php echo base_url('/data/export/invoice.php')?>' target="_blank">Invoices</a></li>
				
				<li class="nav-header">My Account</li>
				   <?php 
				
			//	echo 'JJ'.$_SESSION['personnel_id'];
				if (!empty($_SESSION['personnel_id'])){
				?>	
					<li><a href='<?php echo site_url('welcome/control_panel/'.$_SESSION['personnel_id'])?>'>Control Panel</a></li>	
				<?php	}
				else {
					?>	
				<li><a href='<?php echo site_url('')?>'>Control Panel</a></li>
				<?php
					}
				
				?>
				<li><a href='<?php echo site_url('welcome/logout')?>'>Logout</a></li>
				<li><a href='#'>Change Password</a></li>
			</ul>
        </div>
    	<div class="span10">