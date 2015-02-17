<?php
	// config
	if(!file_exists('./../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	
	require_once './../_config/config.php';
	require_once './../_includes/functions.php';
	
	$password = cleanInput($_POST['password']);
	$user_id = cleanInput($_POST['user_id']);
	
	if(pwlinkC($user_id,$password)){
		//密码更改成功
		header('Location:'.APP_URL.'login/done/upseko');
	}else{
		//更改不成功
		header('Location:'.APP_URL.'login/done/');
	}
	
?>