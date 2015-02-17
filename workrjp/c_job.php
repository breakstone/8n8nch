<?php
	// config
	if(!file_exists('_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '_config/config.php';
	require_once '_includes/functions.php';
	require_once '_includes/SubPages.php';
	
	if(@$_SESSION['kiwa_companyid']=="" && @$_SESSION['kiwa_userid']==""){
		if(!empty($_COOKIE["8n8nuser"])&&!empty($_COOKIE["8n8npw"])){
			$cookie_user = $_COOKIE["8n8nuser"];
			$cookie_pw = $_COOKIE["8n8npw"];
			$sql_u="select user_id,name01,name02 from dtb_user where email = '$cookie_user' and password = '$cookie_pw'";
			$sql_c="select company_id from dtb_companyuser where email = '$cookie_user' and password = '$cookie_pw'";
			$row_u = $db->QueryRow($sql_u);
			$row_c = $db->QueryRow($sql_c);
			if($row_u != ""){
				$_SESSION['kiwa_userid'] = $row_u['user_id'];
				$_SESSION['kiwa_username'] = $row_u['name01']." ".$row_u['name02'];
			}elseif($row_c != ""){
				$_SESSION['kiwa_companyid'] = $row_c['company_id'];
				$_SESSION['kiwa_companyname'] = $row_c['company_name'];
			}
		}
	}
	
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
	$all_nums = getObjectNum("dtb_jobs","company_id='$url[user_id]'");
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
				
				$smarty->assign('company', $company);
				
				$subPages = new SubPages($show_num,$all_nums,$pageCurrent,10,"?page=");
				//-----------------分页处理完了----------------------
				//利用limit查询数据库---select * from table limit $start,$end
				$start = $subPages->getStart($show_num,$pageCurrent);//配合分页得到起始数
				$end = $subPages->getEnd($all_nums,$show_num,$pageCurrent);//配合分页得到结束数
				$smarty->assign('subPages',$subPages->showPageStr());
				
				$jobs = getJobs("company_id='$url[user_id]'",$start, $end);
				$smarty->assign('jobs', $jobs);
				
				
				//区分company,user
				$smarty->assign('flag', $url["user_type"]);
				//固定引入参数
				$smarty->assign('BASE_URL', APP_URL);
				$smarty->assign('THEME', THEME);
				$smarty->display('C/default/company_job.html');
			}else{
				//没有找到
				$smarty->assign('h1', "企业信息");
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
				
				$jobs = getJobs("company_id='$url[user_id]'",$start, $end);
				$smarty->assign('jobs', $jobs);
				
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
				$smarty->display('People/people_job.html');
			}else{
				//没有找到
				$smarty->assign('h1', "人才信息");
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