<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	require_once '../_includes/SubPages.php';
	
	$today = date("Y-m-d");
	$hour = date("H");
	$smarty->assign('hour',$hour);
	$yestoday = date("Y-m-d",strtotime("-1 day"));
	$bfyestoday = date("Y-m-d",strtotime("-2 day"));
	$smarty->assign('today',$today);
	$smarty->assign('yestoday',$yestoday);
	$smarty->assign('bfyestoday',$bfyestoday);
	
	$id = explode("_",$id);
	//-----------------分页处理开始----------------------
	if(isset($_REQUEST["page"])){
		$page = $_REQUEST["page"];
	}else{
		$page = 1;
	}
	//每页显示几条;//得到当前是第几页;
	$show_num=20;$pageCurrent=$page;
	
	$job_nums = getObjectNum("dtb_jobs","company_id='$id[1]'");
	$bbs_nums = getObjectNum("dtb_bbs","user_id='$id[1]'");
	$live_nums = getObjectNum("dtb_lives","user_id='$id[1]'");
	
	$smarty->assign('job_nums', $job_nums);
	$smarty->assign('bbs_nums', $bbs_nums);
	$smarty->assign('live_nums', $live_nums);
	if(strlen($id[1]) <= 12){
		//注册个根据id查找name的方法
		$smarty->register_function("getname","getName");
		if($id[0] == "company"){
			$company = getCompany($id[1]);
			//企业信息
			if($company){
				//得到高级设置
				$sql = "select * from dtb_people_page where del_flg = 0 and user_id='$id[1]'";
				$company_page = $db->QueryRow($sql);
				$smarty->assign('company_page', $company_page);
				
				$smarty->assign('company', $company);
				//好友
				$sqlall = "select * from dtb_favorite_list where user_id = '$company[company_id]' and favorite_id != '$company[company_id]' and (flag='user' or flag='company') and del_flg = 0 order by create_date desc";
				$friends = $db->QueryArray($sqlall);
				$smarty->assign('friend', $friends);
				$subPages = new SubPages($show_num,$bbs_nums,$pageCurrent,10,APP_URL."people/bbs/company_$id[1]/?page=");
				//-----------------分页处理完了----------------------
				//利用limit查询数据库---select * from table limit $start,$end
				$start = $subPages->getStart($show_num,$pageCurrent);//配合分页得到起始数
				$end = $subPages->getEnd($bbs_nums,$show_num,$pageCurrent);//配合分页得到结束数
				$smarty->assign('subPages',$subPages->showPageStr());
				
				$bbs = getBbs("user_id='$id[1]'",$start, $end);
				$smarty->assign('bbs', $bbs);
				//区分company,user
				$smarty->assign('flag', $id[0]);
				//固定引入参数
				$smarty->assign('BASE_URL', APP_URL);
				$smarty->assign('THEME', THEME);
				$smarty->display('People/company_bbs.html');
			}else{
				//没有找到
				$smarty->assign('h1', "企业信息");
				$smarty->assign('message', "非常抱歉，您指定的企业信息的登载已经结束。感谢您的关注！");
				$smarty->assign('BASE_URL', APP_URL);
				$smarty->assign('THEME', THEME);
				$smarty->display('People/done.html');
			}
		}
		if($id[0] == "user"){
			$user = getUser($id[1]);
			if($user){
				$subPages = new SubPages($show_num,$bbs_nums,$pageCurrent,10,APP_URL."people/bbs/user_$id[1]/?page=");
				//-----------------分页处理完了----------------------
				//利用limit查询数据库---select * from table limit $start,$end
				$start = $subPages->getStart($show_num,$pageCurrent);//配合分页得到起始数
				$end = $subPages->getEnd($bbs_nums,$show_num,$pageCurrent);//配合分页得到结束数
				$smarty->assign('subPages',$subPages->showPageStr());
				
				$bbs = getBbs("user_id='$id[1]'",$start, $end);
				$smarty->assign('bbs', $bbs);
				
				if (empty($bbs)){
					
					$bbs_tj = getBbs("",0,10);
					$smarty->assign('bbs_tj', $bbs_tj);
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
				$smarty->display('People/people_bbs.html');
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