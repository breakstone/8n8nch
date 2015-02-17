<?php
	// config
	if(!file_exists('_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '_config/config.php';
	require_once '_includes/functions.php';
	require_once '_includes/SubPages.php';
	
	$sql = "select * from dtb_people_photo where del_flg = 0 and Id='$id' order by create_date";
	$photo = $db->QueryRow($sql);
	$smarty->assign('photo', $photo);
	if($photo){
		//注册个根据id查找name的方法
		$smarty->register_function("getname","getName");
		if($photo["user_type"] == "company"){
			$company = getCompany($photo["user_id"]);
			//企业信息
			if($company){
				//得到高级设置
				$sql = "select * from dtb_people_page where del_flg = 0 and user_id='$photo[user_id]'";
				$company_page = $db->QueryRow($sql);
				$smarty->assign('company_page', $company_page);
				
				$smarty->assign('company', $company);
				//区分company,user
				$smarty->assign('flag', $photo["user_type"]);
				//固定引入参数
				$smarty->assign('BASE_URL', APP_URL);
				$smarty->assign('THEME', THEME);
				$smarty->display('C/default/company_photoshow.html');
			}else{
				//没有找到
				$smarty->assign('h1', "信息提示");
				$smarty->assign('message', "非常抱歉，您指定的企业信息的登载已经结束。感谢您的关注！");
				$smarty->assign('BASE_URL', APP_URL);
				$smarty->assign('THEME', THEME);
				$smarty->display('People/done.html');
			}
		}else if($photo["user_type"] == "user"){
			$user = getUser($photo["user_id"]);
			if($user){
					
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
				$smarty->assign('flag', $photo["user_type"]);
				//注册函数
				$smarty->register_function('getname','getName');
				//固定引入参数
				$smarty->assign('BASE_URL', APP_URL);
				$smarty->assign('THEME', THEME);
				$smarty->display('C/people_photoshow.html');
			}else{
				//没有找到
				$smarty->assign('h1', "信息提示");
				$smarty->assign('message', "非常抱歉，您指定的人才信息的登载已经结束。感谢您的关注！");
					
				$smarty->assign('BASE_URL', APP_URL);
				$smarty->assign('THEME', THEME);
				$smarty->display('People/done.html');
			}
		}
	}else{
		$smarty->assign('BASE_URL', APP_URL);
		$smarty->assign('THEME', THEME);
		$smarty->display('error.html');
	}

?>