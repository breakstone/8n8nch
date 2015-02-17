<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	require_once '../_includes/SubPages.php';
	
	$user = getUser($_SESSION["kiwa_userid"]);
	$smarty->assign('user', $user);
	
	$jobs = getJobs("company_id = '$_SESSION[kiwa_userid]'",0,12);
	
	
	
	if(isset($_REQUEST["job_service"])&&$_REQUEST["job_service"]!=""){
		$job_id = $_REQUEST["job_service"];
	}else{
		$job_id = $jobs[0]["job_id"];
	}
	$smarty->assign('job_id', $job_id);
	
	if(isset($_REQUEST["flg"])&&$_REQUEST["flg"]!=""){
		if($_REQUEST["flg"]==1){
			$smarty->assign('lactive', "active");
			$smarty->assign('ldispaly', "block;");
			$smarty->assign('jactive', "");
			$smarty->assign('jdispaly', "none;");
		}
		if($_REQUEST["flg"]==2){
			$smarty->assign('lactive', "");
			$smarty->assign('ldispaly', "none;");
			$smarty->assign('jactive', "active");
			$smarty->assign('jdispaly', "block;");
		}
	}else{
		$smarty->assign('lactive', "active");
		$smarty->assign('ldispaly', "block;");
		$smarty->assign('jactive', "");
		$smarty->assign('jdispaly', "none;");
	}
	
	
	$smarty->assign('jobs', $jobs);
	#######################################################
	#  匹配数据
	#######################################################
	$jobp = getJobs("job_id = '$job_id'",0,1);
	
	# 职种，业种
	if($jobp[0]["kindsID"]!=""){
		$kid = $jobp[0]["kindsID"];
		
		$kindsID = "kindsID = '$kid'";
	}else{
		$kindsID = "";
	}
	if($jobp[0]["typesID"]!=""){
		$tid = explode(",", $jobp[0]["typesID"]);
		$typesID = "and (";
		foreach ($tid as $v){
			$typesID .= "typesID like '%$v%' or ";
		}
		$typesID = rtrim($typesID,"or ");
		$typesID .= ")";
	}else{
		$typesID = "";
	}
	
	$sql_jobp_u = "select * from dtb_user where $kindsID $typesID and user_id != '$_SESSION[kiwa_userid]' and del_flg=0 order by rand() limit 0 , 5";
	$job_array_u = $db->QueryArray($sql_jobp_u);
// 	$sql_jobp_c = "select * from dtb_companyuser where $kindsID $typesID and del_flg=0 limit 0 , 5";
// 	$job_array_c = $db->QueryArray($sql_jobp_c);
	
	if(count($job_array_u)==0&&count($job_array_c)==0){
		$sql_jobp_u = "select * from dtb_user where $kindsID and user_id != '$_SESSION[kiwa_userid]' and del_flg=0 order by rand() limit 0 , 5";
		$job_array_u = $db->QueryArray($sql_jobp_u);
// 		$sql_jobp_c = "select * from dtb_companyuser where $kindsID and del_flg=0 limit 0 , 5";
// 		$job_array_c = $db->QueryArray($sql_jobp_c);
	}
	$smarty->assign('job_array_u', $job_array_u);
// 	$smarty->assign('job_array_c', $job_array_c);
	
	
	$skills = explode(",", $jobp[0]["level"]);
	
	if(@$skills[0]!=""){
		$sql_user = "select * from dtb_user where skill like '%$skills[0]%' and del_flg=0 order by rand() limit 0 , 10";
		$user_1 = $db->QueryArray($sql_user);
	
		$smarty->assign('skill_1', $skills[0]);
		$smarty->assign('user_1', $user_1);
	}
	if(@$skills[1]!=""){
		$sql_job = "select * from dtb_user where skill like '%$skills[1]%' and del_flg=0 order by rand() limit 0 , 10";
		$user_2 = $db->QueryArray($sql_job);
	
		$smarty->assign('skill_2', $skills[1]);
		$smarty->assign('user_2', $user_2);
	}
	if(@$skills[2]!=""){
		$sql_job = "select * from dtb_user where skill like '%$skills[2]%' and del_flg=0 order by rand() limit 0 , 10";
		$user_3 = $db->QueryArray($sql_job);
	
		$smarty->assign('skill_3', $skills[2]);
		$smarty->assign('user_3', $user_3);
	}
	
	
	
	//固定引入参数
	//mypage页面信息查询
	$smarty->assign('now', date("Y-m-d"));
	$smarty->assign('THEME', THEME);
	$smarty->assign('BASE_URL', APP_URL);
	
	$smarty->assign('USERNAME', $_SESSION['kiwa_username']);
	$smarty->assign('USERID', $_SESSION['kiwa_userid']);
	//得到未读message个数
	$um = unreadMessage($_SESSION['kiwa_userid']);
	$smarty->assign('unread', $um);
	//生活需求个数
	$sql_live="select count(*) from dtb_lives where user_id = '$_SESSION[kiwa_userid]' and del_flg = 0";
	$live_um = $db->QueryItem($sql_live);
	$smarty->assign('live_um', $live_um);
	//招贤纳才个数
	$sql_job="select count(*) from dtb_jobs where company_id = '$_SESSION[kiwa_userid]' and del_flg = 0";
	$job_um = $db->QueryItem($sql_job);
	$smarty->assign('job_um', $job_um);
	//收藏人才企业数
	$sql_fa_u="select count(*) from dtb_favorite_list where user_id = '$_SESSION[kiwa_userid]' and (flag='user' or flag='company') and del_flg = 0";
	$fa_um = $db->QueryItem($sql_fa_u);
	$smarty->assign('fa_um', $fa_um);
	//收藏生活互助数
	$sql_fa_l="select count(*) from dtb_favorite_list where user_id = '$_SESSION[kiwa_userid]' and (flag='live') and del_flg = 0";
	$fa_um_l = $db->QueryItem($sql_fa_l);
	$smarty->assign('fa_um_l', $fa_um_l);
	//收藏人才企业数
	$sql_fa_j="select count(*) from dtb_favorite_list where user_id = '$_SESSION[kiwa_userid]' and (flag='job') and del_flg = 0";
	$fa_um_j = $db->QueryItem($sql_fa_j);
	$smarty->assign('fa_um_j', $fa_um_j);
	//授信箱个数
	$sql_message_fa="select count(*) from dtb_message where message_to = '$_SESSION[kiwa_userid]' and del_flg = 0";
	$message_um_f = $db->QueryItem($sql_message_fa);
	$smarty->assign('message_um_f', $message_um_f);
	//送信箱个数
	$sql_message_s="select count(*) from dtb_message where message_from = '$_SESSION[kiwa_userid]' and from_del_flg = 0";
	$message_um_s = $db->QueryItem($sql_message_s);
	$smarty->assign('message_um_s', $message_um_s);
	
	if(isMobile()){
		$smarty->display('mobile/Mypage/mypager.html');
	}else{
		$smarty->display('Mypage/mypager.html');
	}
?>