<?php 
$this->load->helper('html');
$this->load->helper('url');

echo meta(array('name' => 'viewport', 'content' => 'width=device-width', 'initial-scale' => '1.0'));
echo link_tag('css/twoColLiqLtHdr.css');
echo link_tag('css/jquery-ui-1.8.18.custom.css');
echo link_tag('css/bootstrap.css');
echo link_tag('css/bootstrap.min.css');

?>
<html lang="en">
<head>
	<title>Vitals</title>
    <script type="text/javascript" src="<?php echo base_url('js/script.js');?>"></script>
	<script type='text/javascript' src='<?php echo base_url('js/jquery.js');?>'></script>
	<script type="text/javascript" src="<?php echo base_url('js/jquery-1.7.1.min.js');?>"></script>
	<script type='text/javascript' src='<?php echo base_url('js/jquery-ui-1.8.18.custom.min.js');?>'></script>
</head>

<body onLoad="vitals_interface()">
    
	<div id="vitals"></div>
</body>
</html>