<?php
$this->load->helper('html');
$this->load->helper('url');
$this->load->helper('form');

echo meta(array('name' => 'viewport', 'content' => 'width=device-width', 'initial-scale' => '1.0'));
echo link_tag('css/style.css');
echo link_tag('css/bootstrap.css');
echo link_tag('css/bootstrap.min.css');

$image_properties = array(
          'src' => 'img/3D_art-168.jpg',
          'alt' => 'Me, demonstrating how to eat 4 slices of pizza at one time',
          'class' => 'img-rounded',
          /*'width' => '200',
          'height' => '200',*/
          'title' => 'That was quite a night',
          'rel' => 'lightbox',
);

$image_properties2 = array(
          'src' => 'img/3D_art-187.jpg',
          'alt' => 'Me, demonstrating how to eat 4 slices of pizza at one time',
          'class' => 'img-circle',
          /*'width' => '200',
          'height' => '200',*/
          'title' => 'That was quite a night',
          'rel' => 'lightbox',
);

$image_properties3 = array(
          'src' => 'img/3D_art-1458.jpg',
          'alt' => 'Me, demonstrating how to eat 4 slices of pizza at one time',
          'class' => 'img-polaroid',
          /*'width' => '200',
          'height' => '200',*/
          'title' => 'That was quite a night',
          'rel' => 'lightbox',
);

?>
<html lang="en">
<head>
<title>Login</title>
<!--<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel='stylesheet' type='text/css' media='all' href='css/bootstrap-responsive.css' />
<link rel='stylesheet' type='text/css' media='all' href='css/bootstrap-responsive.min.css' />-->
<!--<style type='text/css' media='all'>@import url('css/bootstrap.min.css');</style>

<link rel='stylesheet' type='text/css' media='all' href='<?php //echo base_url('/css/bootstrap.min.css');?>' />-->
<!--<style type='text/css' media='all'>@import url('css/bootstrap.css');</style>
<link rel='stylesheet' type='text/css' media='all' href='css/bootstrap.css' />-->

<script type="text/javascript" src="<?php echo base_url('js/bootstrap.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/jquery.poptrox-0.1.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/jquery-1.7.1.min.js');?>"></script>
</head>

<body>
	<div class="container-fluid">
    	
		<div class="row-fluid">
        	<div id="header" class="container">
				<div id="logo">
					<h1><a href="#">SUMC</a></h1>
					<p><a href="http://www.strathmore.edu">login</a></p>
				</div>
			</div>
			<!-- end #header -->
			<div id="wrapper">
            	<div id="page">
                	<div id="content">
                    	<div id="loginform">
                        	<?php 
							$validation_errors = validation_errors();
							
							if(!empty($validation_errors))
							{
								echo '
									<div class="alert alert-danger">
										'.$validation_errors.'
									</div>
								';
							}
							
							if(isset($error))
							{
								echo '
									<div class="alert alert-danger">
										'.$error.'
									</div>
								';
							}
							echo form_open("login/login_user");?>
                            	<table>
                                	<thead>
                                    	<tr>
                                        	<th>User Login</th>
                                       	</tr>
                                    </thead>
                                    <tbody>
                                    	<tr>
                                        	<td><input type="text" name="username" value="" placeholder="Username"> </td>
                                            <td><input type="password" name="password" value="" placeholder="password"> </td>
                                            <td><input id="loginbutton" type="submit" name="btnSubmit" value="Login"> </td>
                                        </tr>
                                        <tr>
                                        	<td> <!--error message--></td> 
                                            <td></td>
                                            <td></td>
                                        </tr>
                                            <script type="text/javascript">
												// $('.notification').flash(2000);
											</script>
										<tr>
											<td><a href="resetpassword.php" title="Forgot your password">Forgot your password</a> </td> 
                                            <td></td>
                                            <td></td>
										</tr>
									</tbody>
								</table>
                            	<table>
                                	<thead>
                                    	<tr>
                                        	<th><?php if(!empty($message)) echo $message;?></th>
                                       	</tr>
                                    </thead>
                                </table>
							</form>
						</div>
					</div>
				</div>
                
                </div>
				<!-- end wrapper -->
				<div id="footer">
					<p>Copyright &copy; 2012 Sitename.com. All rights reserved. Design by <a href="http://www.freecsstemplates.org">james brian studio</a>.</p>
				</div>
				<!-- end #footer -->
            </div>
        </div>
    </div>
</body>
</html>