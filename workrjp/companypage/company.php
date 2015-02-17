<?php
	if($id=="imgerror"){
		$smarty->assign('error', "上传图片格式不正确");
	}
	//得到企业简历
	$company = getCompany($_SESSION['kiwa_companyid']);
	$smarty->assign('company', $company);
	
	//get得到业种数据
	$companyfrom = getCompanyfrom();
	$smarty->assign('companyfrom', $companyfrom);
	//企业形态处理
	$company_form = explode("_",$company["company_form"]);
	
	//企业分类
	$sql_comp = "select * from mtb_company_type";
	$qi = $db->QueryArray($sql_comp);
	$smarty->assign('qi', $qi);
	
	$smarty->assign('company_form', $company_form);
	//判断企业宣传照片
	if($company["company_photo_url"]!=""){
		if(file_exists("../".$company["company_photo_url"])){
			$smarty->assign('img_status', 1);
		}else{
			$smarty->assign('img_status', "");
		}
	}
	//解决缓存，图片不更新的方法
	$catch_img = rand(0,100);
	$smarty->assign("catch_img",$catch_img);
	
	//get得到地域数据
	$areas = getPref();
	$smarty->assign('areas', $areas);
	if($company["area_cd"]!=""){
		$pref = getPref($company["area_cd"]);
		$smarty->assign('prefSearch', $pref);
	}
	//根据业种得出职种
	if($company["kindsID"]!=""){
		$types = getTypes($company["kindsID"]);
		$smarty->assign('types', $types);
	}
	if($company["typesID"]!=""){
		$stypes = explode(",", $company["typesID"]);
		$smarty->assign('select_types', $stypes);
	}
	//get得到生活服务内容
	$life_service = getLifeservice();
	$smarty->assign('life_service', $life_service);
	if($company["skill"]!=""){
		$skill = explode(",", $company["skill"]);
		$smarty->assign('skill', $skill);
	}
	
	if($company["line_num"]!=""){
		$line_num = explode(",", $company["line_num"]);
		$smarty->assign('ensn', $line_num);
	}
	
	if($company["station_cd"]!=""){
		$station_cd = explode(",", $company["station_cd"]);
		$smarty->assign('eki', $station_cd);
	}
	
	//固定引入参数
	//mypage页面信息查询
	$smarty->assign('THEME', THEME);
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('COMPANYNAME', $_SESSION['kiwa_companyname']);
	$smarty->assign('COMPANYID', $_SESSION['kiwa_companyid']);
	//注册个方法
	$smarty->register_function('getname','getName');
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
		$smarty->display('mobile/Companypage/company.html');
	}else{
		$smarty->display('Companypage/company.html');
	}
	
?>