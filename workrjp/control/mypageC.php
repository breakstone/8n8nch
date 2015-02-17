<?php
	// config
	if(!file_exists('./../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	
	require_once './../_config/config.php';
	require_once './../_includes/functions.php';
	//logout处理
	if(isset($_REQUEST['logout'])){
		if(logOut()){
			header('Location:'.APP_URL.'login/');
		}
	}
	//发表工作
	if(isset($_GET['do'])&&$_GET['do']=="add"){
		echo "add";
	}
?>