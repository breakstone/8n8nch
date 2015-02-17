<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	require_once '../_includes/SubPages.php';
	
	
		$user = getUser($id);
		if($user){
			if($user["want_day"]!=""){
				$want_day = explode(",", $user["want_day"]);
				$smarty->assign('want_day', $want_day);
			}
			//人信息
			$smarty->assign('users', $user);
			if($user['sex']==1){
				$usersex="男性";
			}else{
				$usersex="女性";
			}
			$smarty->assign('usersex', $usersex);
			//年齢計算
			$today = date('Ymd');
			$birthday=$user['birth'];
    		$birthday = date('Ymd',strtotime($birthday));
    		$age=floor(($today-$birthday)/10000);
			$smarty->assign('age', $age);
			//区分company,user
			$smarty->assign('flag', $id[0]);
			//注册函数
			$smarty->register_function('getname','getName');
			//固定引入参数
			$smarty->assign('BASE_URL', APP_URL);
			$smarty->assign('THEME', THEME);
			$smarty->display('People/people_pr.html');
			
		}else{
			//没有找到
			$smarty->assign('h1', "信息提示");
			$smarty->assign('message', "非常抱歉，您指定的人才信息的登载已经结束。感谢您的关注！");
			
			$smarty->assign('BASE_URL', APP_URL);
			$smarty->assign('THEME', THEME);
			$smarty->display('People/done.html');
		}
?>