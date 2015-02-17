<?php
	require_once '../_includes/functions.php';
	require_once '../_includes/SubPages.php';
	$where = "user_id = '$_SESSION[kiwa_companyid]'";
	
	if(isset($_REQUEST["Which_Show"])&&$_REQUEST["Which_Show"]!=""){
		$smarty->assign('Which_Show', $_REQUEST["Which_Show"]);
		if($_REQUEST["Which_Show"] == 1){
			$smarty->assign('show', "生活服务");
			$where .= "and flag = 'live'";
		}
		if($_REQUEST["Which_Show"] == 2){
			$smarty->assign('show', "求人招聘");
			$where .= "and flag = 'job'";
		}
		if($_REQUEST["Which_Show"] == 3){
			$smarty->assign('show', "人才/企业库");
			$where .= "and (flag = 'user' or flag = 'company')";
		}
	}else{
		$smarty->assign('Which_Show', 1);
		$smarty->assign('show', "生活服务");
		$where .= "and flag = 'live'";
	}
	
	//排序处理
	if($id==""){$id="date";}
	$smarty->assign('order', $id);
	//分页处理
	if($extra == ""){$extra = 1;}
	//总条数
	$all_nums = getObjectNum("dtb_favorite_list",$where);
	
	//每页显示几条;//得到当前是第几页;
  	$show_num=10;$pageCurrent = $extra;
  	$subPages = new SubPages($show_num,$all_nums,$pageCurrent,10,APP_URL."companypage/favorite/$id/");
	//-----------------分页处理完了----------------------
	//利用limit查询数据库---select * from table limit $start,$end
	$start = $subPages->getStart($show_num,$pageCurrent);//配合分页得到起始数
	$end = $subPages->getEnd($all_nums,$show_num,$pageCurrent);//配合分页得到结束数
	$favorite = getFevorite($where,$start,$end,$id);
	
	//前台分页
	$smarty->assign('subPages',$subPages->showPageStr());
	//总数
	$smarty->assign('all', $all_nums);
	//list
	$smarty->assign('favorite', $favorite);
	$smarty->register_function('getallfavorite','getAllFavorite');
	$smarty->register_function('getname','getName');
	//固定引入参数
	//mypage页面信息查询
	$smarty->assign('THEME', THEME);
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('COMPANYNAME', $_SESSION['kiwa_companyname']);
	$smarty->assign('COMPANYID', $_SESSION['kiwa_companyid']);
	
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
		$smarty->display('mobile/Companypage/favorite.html');
	}else{
		$smarty->display('Companypage/favorite.html');
	}
	

	
function getAllFavorite($id){
	extract($id);
	global $db;
	if($name == "live_type"){
		$sql = "select service_name from dtb_lives where live_id = '$value'";
	}
	if($name == "live_title"){//条件
		$sql = "select live_title from dtb_lives where live_id = '$value'";
	}
	if($name == "user_name"){
		if($type == "user"){
			$sql = "select concat(name01,name02) from dtb_user where user_id = '$value'";
			$ty="个人";
		}
		if($type == "company"){
			$sql = "select company_name from dtb_companyuser where company_id = '$value'";
			$ty="企业";
		}
		$name = $db->QueryItem($sql);
		return $name."($ty)";
	}
	if($name == "user_name"){
		if($type == "user"){
			$sql = "select concat(name01,name02) from dtb_user where user_id = '$value'";
			$ty="个人";
		}
		if($type == "company"){
			$sql = "select company_name from dtb_companyuser where company_id = '$value'";
			$ty="企业";
		}
		$name = $db->QueryItem($sql);
		return $name."($ty)";
	}
	if($name == "user_types"){
		if($type == "user"){
			$sql = "select typesname from dtb_user where user_id = '$value'";
		}
		if($type == "company"){
			$sql = "select typesname from dtb_companyuser where company_id = '$value'";
		}
	}
	if($name == "user_skill"){
		if($type == "user"){
			$sql = "select skill from dtb_user where user_id = '$value'";
		}
		if($type == "company"){
			$sql = "select skill from dtb_companyuser where company_id = '$value'";
		}
	}
	if($name == "job_user"){
		$sql = "select company_name from dtb_jobs where job_id = '$value'";
	}
	if($name == "job_title"){
		$sql = "select job_title from dtb_jobs where job_id = '$value'";
	}

	$name = $db->QueryItem($sql);
	return $name;
}
?>