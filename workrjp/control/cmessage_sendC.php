<?php
	// config
	if(!file_exists('./../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	
	require_once './../_config/config.php';
	require_once './../_includes/functions.php';
	//发表工作
	if(isset($_POST['message_from'])&&isset($_POST['message_to'])&&isset($_SESSION['kiwa_companyname'])&&isset($_SESSION['kiwa_companyid'])){
		
		//企业简历
		$message_from = cleanInput($_POST['message_from']);
		$message_to = cleanInput($_POST['message_to']);
		$message_title = cleanInput($_POST['message_title']);
		$message_content = cleanInput($_POST['message_content']);
		
		
		
		//发信息
		$return = message_send_do($message_from,$message_to,$message_title,$message_content);
		if($return){
			//没有参数跳到错误页面
			header('Location:'.APP_URL.'companypage/done/message_send_ok/');
		}else{
			header('Location:'.APP_URL.'companypage/done/message_send_no/');
		}
	}else{
		//没有参数跳到错误页面
		header('Location:'.APP_URL.'companypage/done/error/');
	}
?>