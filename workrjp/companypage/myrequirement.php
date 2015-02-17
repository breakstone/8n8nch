<?php
	require_once '../_includes/functions.php';
	require_once '../_includes/SubPages.php';
	
	$ws = "";$pages="";
	if(isset($_REQUEST["Which_Show"])&&$_REQUEST["Which_Show"]!=""){
		$smarty->assign('Which_Show', $_REQUEST["Which_Show"]);
		$ws = $_REQUEST["Which_Show"];
		if($_REQUEST["Which_Show"] == 1){
			$smarty->assign('show', "生活服务");
			$table = "dtb_lives";
			$where = "user_id = '$_SESSION[kiwa_companyid]'";
		}
		if($_REQUEST["Which_Show"] == 2){
			$smarty->assign('show', "求人招聘");
			$table = "dtb_jobs";
			$where = "company_id = '$_SESSION[kiwa_companyid]'";
		}
	}else{
		$smarty->assign('Which_Show', 1);
		$smarty->assign('show', "生活服务");
		$table = "dtb_lives";
		
		$where = "user_id = '$_SESSION[kiwa_companyid]'";
	}
	//排序处理
	if($id==""){$id="date";}
	$smarty->assign('order', $id);
	//分页处理
	if(isset($_REQUEST["pages"])&&$_REQUEST["pages"] != ""){
		$pages = $_REQUEST["pages"];
	}else{
		$pages = 1;
	}
	//总条数
	$all_nums = getObjectNum($table,$where);
	
	//每页显示几条;//得到当前是第几页;
  	$show_num=10;$pageCurrent = $pages;
  	$subPages = new SubPages($show_num,$all_nums,$pageCurrent,10,APP_URL."companypage/myrequirement/$id/?Which_Show=$ws&pages=");
	//-----------------分页处理完了----------------------
	//利用limit查询数据库---select * from table limit $start,$end
	$start = $subPages->getStart($show_num,$pageCurrent);//配合分页得到起始数
	$end = $subPages->getEnd($all_nums,$show_num,$pageCurrent);//配合分页得到结束数
	
	if($table == "dtb_lives"){
		$requirement = getLives($where,$start,$end);
	}else{
		$requirement = getJobs($where,$start,$end);
	}
	//前台分页
	$smarty->assign('subPages',$subPages->showPageStr());
	//总数
	$smarty->assign('all', $all_nums);
	//list
	$smarty->assign('requirement', $requirement);
	
	$smarty->register_function('getname','getName');
	//固定引入参数
	//mypage页面信息查询
	$smarty->assign('THEME', THEME);
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('USERNAME', $_SESSION['kiwa_companyname']);
	$smarty->assign('USERID', $_SESSION['kiwa_companyid']);
	//得到积分
	$sql_points="select points from dtb_companyuser where company_id = '$_SESSION[kiwa_companyid]' and del_flg = 0";
	$points = $db->QueryItem($sql_points);
	$smarty->assign('points', $points);
	//得到未读message
	$um = unreadMessage($_SESSION['kiwa_companyid']);
	$smarty->assign('unread', $um);
	//生活需求个数
	$sql_live="select count(*) from dtb_lives where user_id = '$_SESSION[kiwa_companyid]' and del_flg = 0";
	$live_um = $db->QueryItem($sql_live);
	$smarty->assign('live_um', $live_um);
	//招贤纳才个数
	$sql_job="select count(*) from dtb_jobs where company_id = '$_SESSION[kiwa_companyid]' and del_flg = 0";
	$job_um = $db->QueryItem($sql_job);
	$smarty->assign('job_um', $job_um);
	//收藏人才企业数
	$sql_fa_u="select count(*) from dtb_favorite_list where user_id = '$_SESSION[kiwa_companyid]' and (flag='user' or flag='company') and del_flg = 0";
	$fa_um = $db->QueryItem($sql_fa_u);
	$smarty->assign('fa_um', $fa_um);
	//收藏生活互助数
	$sql_fa_l="select count(*) from dtb_favorite_list where user_id = '$_SESSION[kiwa_companyid]' and (flag='live') and del_flg = 0";
	$fa_um_l = $db->QueryItem($sql_fa_l);
	$smarty->assign('fa_um_l', $fa_um_l);
	//收藏人才企业数
	$sql_fa_j="select count(*) from dtb_favorite_list where user_id = '$_SESSION[kiwa_companyid]' and (flag='job') and del_flg = 0";
	$fa_um_j = $db->QueryItem($sql_fa_j);
	$smarty->assign('fa_um_j', $fa_um_j);
	//授信箱个数
	$sql_message_fa="select count(*) from dtb_message where message_to = '$_SESSION[kiwa_companyid]' and del_flg = 0";
	$message_um_f = $db->QueryItem($sql_message_fa);
	$smarty->assign('message_um_f', $message_um_f);
	//送信箱个数
	$sql_message_s="select count(*) from dtb_message where message_from = '$_SESSION[kiwa_companyid]' and from_del_flg = 0";
	$message_um_s = $db->QueryItem($sql_message_s);
	$smarty->assign('message_um_s', $message_um_s);
	if(isMobile()){
		$smarty->display('mobile/Companypage/myrequirement.html');
	}else{
		$smarty->display('Companypage/myrequirement.html');
	}

?>