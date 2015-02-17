<?php
	require_once '../_includes/functions.php';
	require_once '../_includes/SubPages.php';
	//getMessage_from($_SESSION['kiwa_userid']);
	//getMessage_to($_SESSION['kiwa_userid']);
	if($id == ""){$id = 1;}
	
	$messageids=$_REQUEST["messageid"];
	if (isset($messageids)&&$messageids!=""){
		foreach($messageids as $messageid){
			$message = getMessage($messageid);
			//删除信件的是发信人或者收信人，其他人不许删除
			if($message['message_from']==$_SESSION['kiwa_companyid']){
				pmessage_delete($messageid,"from_del_flg");
			}else{
				header('Location:'.APP_URL.'mypage/done/error/');
			}
		}
	}
	$where = "message_from = '$_SESSION[kiwa_companyid]'";
	$all_nums = getFromMessageNum("dtb_message",$where);
	//总条数

	//每页显示几条;//得到当前是第几页;
  	$show_num=10;$pageCurrent = $id;
  	$subPages = new SubPages($show_num,$all_nums,$pageCurrent,10,APP_URL."companypage/cmessageSend/");
	//-----------------分页处理完了----------------------
	//利用limit查询数据库---select * from table limit $start,$end
	$start = $subPages->getStart($show_num,$pageCurrent);//配合分页得到起始数
	$end = $subPages->getEnd($all_nums,$show_num,$pageCurrent);//配合分页得到结束数
	$message = getMessage_SendM($where,$start,$end);
	
	//前台分页
	$smarty->assign('subPages',$subPages->showPageStr());
	//总数-收信数
	$smarty->assign('all_nums', $all_nums);
	$smarty->assign('message', $message);
	
	
	$smarty->register_function('getname','getName');
	//固定引入参数
	//mypage页面信息查询
	$smarty->assign('THEME', THEME);
	$smarty->assign('BASE_URL', APP_URL);
	
	$smarty->assign('USERNAME', $_SESSION['kiwa_companyname']);
	$smarty->assign('USERID', $_SESSION['kiwa_companyid']);
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
		$smarty->display('mobile/Companypage/cmessageSend.html');
	}else{
		$smarty->display('Companypage/cmessageSend.html');
	}
	
?>