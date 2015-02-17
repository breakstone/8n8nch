<?php
	if(isset($_POST["review"])&&$_POST["review"]==1){
		### review画面修改
		//创建假user
		$user = array(
					"name01" => $_POST['name01'],
					"name02" => $_POST['name02'],
					"birth" => $_POST['birth'],
					"sex" => $_POST['sex'],
					"email" => $_POST['email'],
					"per_status" => $_POST['per_status'],
					"zige" => $_POST['zige'],
					"mypr" => $_POST['pr'],
					"eki" => $_POST['eki'],
					"zip01" => $_POST['zip01'],"zip02" => $_POST['zip02'],
					"pref" => $_POST['add_pref'],
					"addr01" => $_POST['addr01'],"addr02" => $_POST['addr02'],
					"tel01" => $_POST['tel01'],"tel02" => $_POST['tel02'],"tel03" => $_POST['tel03'],
					"tel_flag"=> $_POST['tel_flag'],
					"last_education" => $_POST['last_education'],
					"zhuanye" => $_POST['zhuanye'],
					"job_experiencetimes" => $_POST['job_experiencetimes'],
					"job_nowstatus" => $_POST['job_nowstatus'],
					"job_experience" => $_POST['job_experience']
				);
	}else{
		### 直接进来
		//得到个人简历
		$user = getUser($_SESSION['kiwa_userid']);
	}
	if($id=="imgerror"){
		$smarty->assign('error', "上传图片格式不正确");
	}
	//得到最终学历
	$eduction = getEduction();
	//get得到业种数据
	$companyfrom = getCompanyfrom();
	//get得到地域数据
	$areas = getPref();
	//get給付
	$money = getMoney();
	//get雇用形態
	$employ = getEmploy_method();
	//get就业希望期间
	$hope = getHope_date();
	//get得到生活服务内容
	$life_service = getLifeservice();
	$smarty->assign('life_service', $life_service);
	
	//工作标签
	$sql_jb = "select * from mtb_job_biao";
	$biao = $db->QueryArray($sql_jb);
	$smarty->assign('biao', $biao);
	
	//解决缓存，图片不更新的方法
	$catch_img = rand(0,100);
	$smarty->assign("catch_img",$catch_img);
	
	if($user["area_cd"]!=""){
		$pref = getPref($user["area_cd"]);
		$smarty->assign('prefSearch', $pref);
	}
	//根据业种得出职种
	if($user["kindsID"]!=""){
		$types = getTypes($user["kindsID"]);
		$smarty->assign('types', $types);
	}
	if($user["typesID"]!=""){
		$stypes = explode(",", $user["typesID"]);
		$smarty->assign('select_types', $stypes);
	}
	if($user["skill"]!=""){
		$skill = explode(",", $user["skill"]);
		$smarty->assign('skill', $skill);
	}
	if($user["line_num"]!=""){
		$line_num = explode(",", $user["line_num"]);
		$smarty->assign('ensn', $line_num);
	}
	
	if($user["station_cd"]!=""){
		$station_cd = explode(",", $user["station_cd"]);
		$smarty->assign('eki', $station_cd);
	}
	
	
	$smarty->assign('user', $user);
	$smarty->assign('eduction', $eduction);
	$smarty->assign('companyfrom', $companyfrom);
	$smarty->assign('areas', $areas);
	$smarty->assign('money', $money);
	$smarty->assign('employ', $employ);
	$smarty->assign('hope', $hope);

	if($user["want_day"]!=""){
		$want_day = explode(",", $user["want_day"]);
		$smarty->assign('want_day', $want_day);
	}
	
	//固定引入参数
	//mypage页面信息查询
	$smarty->assign('THEME', THEME);
	$smarty->assign('BASE_URL', APP_URL);
	
	$smarty->assign('USERNAME', $_SESSION['kiwa_username']);
	$smarty->assign('USERID', $_SESSION['kiwa_userid']);
	
	//注册个方法
	$smarty->register_function('getname','getName');
	//得到未读message
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
		$smarty->display('mobile/Mypage/personal.html');
	}else{
		$smarty->display('Mypage/personal.html');
	}
?>