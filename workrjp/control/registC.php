<?php
	// config
	if(!file_exists('./../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	
	require_once './../_config/config.php';
	require_once './../_includes/functions.php';
	
	if(isset($_POST['name01'])&&$_POST['name01']!=""){
		//进行注册
		$email = cleanInput($_POST['email']);
		if(emailRegist($email)){
			$name01 = cleanInput($_POST['name01']);
			$name02 = cleanInput($_POST['name02']);
			$kana01 = cleanInput($_POST['kana01']);
			$kana02 = cleanInput($_POST['kana02']);
			$email = cleanInput($_POST['email']);
			$password = cleanInput($_REQUEST['password']);
			
			if(registC($name01,$name02,$kana01,$kana02,$email,$password,APP_URL)){
				
				//仮登録完成
				header('Location:'.APP_URL.'regist/done/karegist');
			}else{
				//仮登録失敗
				header('Location:'.APP_URL.'regist/done/error');
			}
		}else{
			//邮箱二次校验不成功
			header('Location:'.APP_URL.'regist/?emailerror');
		}
	}else{
		//没有参数跳到错误页面
		header('Location:'.APP_URL.'regist/done/error');
	}
	
?>