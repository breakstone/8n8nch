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
	
	
	#######################################################
	#  匹配数据
	#######################################################
	$skills = explode(",", $user["skill"]);
	
	if(@$skills[0]!=""){
		$sql_job = "select * from dtb_jobs where level like '%$skills[0]%' and del_flg=0 order by rand() limit 0 , 10";
		$job_1 = $db->QueryArray($sql_job);
		
		$smarty->assign('skill_1', $skills[0]);
		$smarty->assign('job_1', $job_1);
	}
	if(@$skills[1]!=""){
		$sql_job = "select * from dtb_jobs where level like '%$skills[1]%' and del_flg=0 order by rand() limit 0 , 10";
		$job_2 = $db->QueryArray($sql_job);
		
		$smarty->assign('skill_2', $skills[1]);
		$smarty->assign('job_2', $job_2);
	}
	if(@$skills[2]!=""){
		$sql_job = "select * from dtb_jobs where level like '%$skills[2]%' and del_flg=0 order by rand() limit 0 , 10";
		$job_3 = $db->QueryArray($sql_job);
		
		$smarty->assign('skill_3', $skills[2]);
		$smarty->assign('job_3', $job_3);
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
		$smarty->display('mobile/Mypage/mypage.html');
	}else{
		$smarty->display('Mypage/mypage.html');
	}
?>