<?php
	// config
	if(!file_exists('./../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	
	require_once './../_config/config.php';
	require_once './../_includes/functions.php';
	
	$email = cleanInput($_POST['email']);
	if(forgetC($email,APP_URL)){
		//邮件发送成功
		header('Location:'.APP_URL.'login/done/soxinseko');
	}else{
		//邮件数据库校验失败
		header('Location:'.APP_URL.'login/forget/error');
	}
?>