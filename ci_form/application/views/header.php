<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Master-Multi-Detail</title>

<link href="<?php echo base_url('css/admin.css');?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('css/jquery-ui-1.8.16/css/ui-lightness/jquery-ui-1.8.16.custom.css');?>" rel="stylesheet" type="text/css" />
<?php
//test for http / https for non hosted files
$http = 'http';
if(isset($_SERVER['HTTPS']))
{
	$http .= 's';
}
?>
<script type="text/javascript" src="<?php echo base_url('css/jquery-ui-1.8.16/js/jquery-1.6.2.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('css/jquery-ui-1.8.16/js/jquery-ui-1.8.16.custom.min.js');?>"></script>
<link href='<?php echo $http;?>://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css' />

<link href="<?php echo base_url('js/jquery/colorbox/colorbox.css');?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url('js/jquery/colorbox/jquery.colorbox-min.js');?>"></script>

<script type="text/javascript" src="<?php echo base_url('js/jquery/tiny_mce/tiny_mce.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/jquery/tiny_mce/tiny_mce_init.php');?>"></script>

<script type="text/javascript">
$(document).ready(function(){
	buttons();
	$("#gc_tabs").tabs();
	$('.datepicker').datepicker({dateFormat: 'yy-mm-dd'});
});
function buttons()
{
	$('.list_buttons').buttonset();
	$('.button_set').buttonset();
	$('.button').button();
}
</script>

</head>
<body>
<div id="wrapper">
	
	<div id="container">
		
		<div id="page_content">
			<?php
			//lets have the flashdata overright "$message" if it exists
			if($this->session->flashdata('message'))
			{
				$message	= $this->session->flashdata('message');
			}
			
			if($this->session->flashdata('error'))
			{
				$error	= $this->session->flashdata('error');
			}
			
			if(function_exists('validation_errors') && validation_errors() != '')
			{
				$error	= validation_errors();
			}
			?>
		
			<?php if (!empty($message)): ?>
			<div class="ui-state-highlight ui-corner-all" style="padding:10px; margin-bottom:10px;"> 
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
				<strong>Note:</strong> <?php echo $message; ?></p>
			</div>
			<?php endif; ?>
		
			<?php if (!empty($error)): ?>
			<div class="ui-state-error ui-corner-all" style="padding:10px; margin-bottom:10px;"> 
				<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> 
				<strong>Alert:</strong> <?php echo $error; ?></p>
			</div>
			<?php endif; ?>
			
			<div id="js_error_container" class="ui-state-error ui-corner-all" style="display:none; padding:10px; margin-bottom:10px;"> 
				<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> 
				<strong>Alert:</strong> <span id="js_error"></span> </p>
			</div>