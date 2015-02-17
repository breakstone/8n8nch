<?php
	// config
	if(!file_exists('./../_config/config.php')) 
	{
		die('[index.php] _config/config.php not found');
	}
	
	require_once './../_config/config.php';
	require_once './../_includes/functions.php';
	//传入参数整理
	$email = cleanInput($_POST['email']);
	$password = cleanInput($_POST['password']);
	$reward = loginC($email,$password);
	if($reward){
		//加入cookie
		if(isset($_POST["remember"])&&$_POST["remember"]=="1"){
			setcookie("8n8nuser",$email,time()+315360000,"/",".8n8n.co.jp");
			setcookie("8n8npw",md5($password),time()+315360000,"/",".8n8n.co.jp");
		}
		if($reward == "user_false"||$reward == "company_false"){//假登陆，status为0
			//假登陆
			header('Location:'.APP_URL.'login/false');
		}elseif($reward == "user_ok"){
			//个人验证成功
			if(isset($_GET["url"])&&$_GET["url"]!=""){//看是否有跳转路径,有的话跳入进入之前的路径
				$url = str_replace("_", "/", $_GET["url"]);
				header('Location:'.APP_URL.$url);
			}else{
				//没有跳入mypage
				header('Location:'.APP_URL.'people/show/user_'.$_SESSION["kiwa_userid"].'/');
			}
		}elseif($reward == "company_ok"){
			if(isset($_GET["url"])&&$_GET["url"]!=""){//看是否有跳转路径,有的话跳入进入之前的路径
				$url = str_replace("_", "/", $_GET["url"]);
				header('Location:'.APP_URL.$url);
			}else{//没有跳入mypage
				header('Location:'.APP_URL.'people/show/company_'.$_SESSION["kiwa_companyid"].'/');
			}
		}
	}else{
		//数据库不匹配
		header('Location:'.APP_URL.'login/error/?e='.$email);
	}
?>