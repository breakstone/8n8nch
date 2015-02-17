<?php
	/**regist**/
	###################################
	# 登録の画面
	###################################
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	
	
	//注册页面传过来参数
	if(isset($_POST['name01'])&&isset($_POST['name02'])&&isset($_POST['email'])&&isset($_POST['password'])){
		//安全过滤
		$name01 = cleanInput($_POST['name01']);
		$name02 = cleanInput($_POST['name02']);
		//$kana01 = cleanInput($_POST['kana01']);
		//$kana02 = cleanInput($_POST['kana02']);
		$email = cleanInput($_POST['email']);
		$password = cleanInput($_POST['password']);
		
		//对email进行二次校验,成功显示
		if(emailRegist($email)){
			$smarty->assign('Cname01', $name01);
			$smarty->assign('Cname02', $name02);
			//$smarty->assign('Ckana01', $kana01);
			//$smarty->assign('Ckana02', $kana02);
			$smarty->assign('Cemail', $email);
			$smarty->assign('Cpassword', $password);
			
			//固定引入参数
			$smarty->assign('BASE_URL', APP_URL);
			$smarty->assign('THEME', THEME);
			if(isMobile()){
				$smarty->display('mobile/Regist/review.html');
			}else{
				$smarty->display('Regist/review.html');
			}
				
		}else{
			//email二次校验不成功，返回错误，数据库里已经注册过了
			$url="name01=$name01&name02=$_POST[name02]&email=$_POST[email]";
			header('Location:'.APP_URL.'regist/?emailerror&'.$url);
		}
		
	}else{
		//没有参数跳回注册页面
		header('Location:'.APP_URL.'regist/');
	}
	
?>