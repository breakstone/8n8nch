<?php
	// config
	if(!file_exists('_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '_config/config.php';
	require_once '_includes/functions.php';
	require_once '_includes/SubPages.php';
	
	$yuming = str_replace(".8n8n.co.jp","",$_SERVER['HTTP_HOST']);
	
	$sql="select * from dtb_2domain where yuming='$yuming' and del_flg = 0";
	$url = $db->QueryRow($sql);
	
	//-----------------分页处理开始----------------------
	if(isset($_REQUEST["page"])){
		$page = $_REQUEST["page"];
	}else{
		$page = 1;
	}
	//每页显示几条;//得到当前是第几页;
	$show_num=20;$pageCurrent=$page;
	$all_nums = getObjectNum("dtb_lives","user_id='$url[user_id]'");
	if(strlen($url["user_id"]) <= 12){
		//注册个根据id查找name的方法
		$smarty->register_function("getname","getName");
		if($url["user_type"] == "company"){
			$company = getCompany($url["user_id"]);
			//企业信息
			if($company){
				//得到高级设置
				$sql = "select * from dtb_people_page where del_flg = 0 and user_id='$url[user_id]'";
				$company_page = $db->QueryRow($sql);
				$smarty->assign('company_page', $company_page);
				
				$subPages = new SubPages($show_num,$all_nums,$pageCurrent,10,APP_URL."?page=");
				//-----------------分页处理完了----------------------
				//利用limit查询数据库---select * from table limit $start,$end
				$start = $subPages->getStart($show_num,$pageCurrent);//配合分页得到起始数
				$end = $subPages->getEnd($all_nums,$show_num,$pageCurrent);//配合分页得到结束数
				$smarty->assign('subPages',$subPages->showPageStr());
				
				$lives = getLives("user_id='$url[user_id]'",$start, $end);
				$smarty->assign('lives', $lives);
				
				
				$smarty->assign('company', $company);
				//区分company,user
				$smarty->assign('flag', $url["user_type"]);
				//固定引入参数
				$smarty->assign('BASE_URL', APP_URL);
				$smarty->assign('THEME', THEME);
				$smarty->display('C/default/company_live.html');
			}else{
				//没有找到
				$smarty->assign('h1', "信息提示");
				$smarty->assign('message', "非常抱歉，您指定的企业信息的登载已经结束。感谢您的关注！");
				$smarty->assign('BASE_URL', APP_URL);
				$smarty->assign('THEME', THEME);
				$smarty->display('People/done.html');
			}
		}
		if($url["user_type"] == "user"){
			$user = getUser($url["user_id"]);
			if($user){
				$subPages = new SubPages($show_num,$all_nums,$pageCurrent,10,"?page=");
				//-----------------分页处理完了----------------------
				//利用limit查询数据库---select * from table limit $start,$end
				$start = $subPages->getStart($show_num,$pageCurrent);//配合分页得到起始数
				$end = $subPages->getEnd($all_nums,$show_num,$pageCurrent);//配合分页得到结束数
				$smarty->assign('subPages',$subPages->showPageStr());
				
				$lives = getLives("user_id='$url[user_id]'",$start, $end);
				$smarty->assign('lives', $lives);
				
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
				$smarty->assign('flag', $url["user_type"]);
				//注册函数
				$smarty->register_function('getname','getName');
				//固定引入参数
				$smarty->assign('BASE_URL', APP_URL);
				$smarty->assign('THEME', THEME);
				$smarty->display('People/people_live.html');
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
		//id不合法跳入error
		//固定引入参数
		$smarty->assign('BASE_URL', APP_URL);
		$smarty->assign('THEME', THEME);
		$smarty->display('error.html');
	}
?>