<?php
	###################################
	# パスワードを忘れの画面
	###################################
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	
	$ipd = getIP();
	$create_date = date("Y-m-d H:i:s");
	if(isset($_POST["djqq"])&&$_POST["djqq"]!=""){
		$qq = cleanInput($_POST["qq"]);
		
		$sql="insert into lx_lianluo (type,content,ip,create_date)values('QQ','$qq','$ipd','$create_date')";
		if($db->Execute($sql)){
			header('Location:'.APP_URL.'s/contact/');
		}
	}
	
	if(isset($_POST["djemail"])&&$_POST["djemail"]!=""){
		$email = cleanInput($_POST["email"]);
		$sql="insert into lx_lianluo (type,content,ip,create_date)values('EMAIL','$email','$ipd','$create_date')";
		if($db->Execute($sql)){
			header('Location:'.APP_URL.'s/contact/');
		}
	}
	
	if(isset($_POST["djtel"])&&$_POST["djtel"]!=""){
		$tel = cleanInput($_POST["tel"]);
		$sql="insert into lx_lianluo (type,content,ip,create_date)values('TEL','$tel','$ipd','$create_date')";
		if($db->Execute($sql)){
			header('Location:'.APP_URL.'s/contact/');
		}
	}
	
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	$smarty->display('S/contact.html');
?>