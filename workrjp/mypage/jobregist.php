<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	
	//没有Session 跳转到登录页面
	if($_SESSION['kiwa_username']==""||$_SESSION['kiwa_userid']==""){
		header('Location:'.APP_URL.'login/');
	}else{
		$id = cleanInput($id);
		$job = showJob($id);
		$user = getUser($_SESSION['kiwa_userid']);
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
		$smarty->assign('job', $job);
		$smarty->assign('user', $user);
		$smarty->assign('eduction', $eduction);
		$smarty->assign('companyfrom', $companyfrom);
		$smarty->assign('areas', $areas);
		$smarty->assign('money', $money);
		$smarty->assign('employ', $employ);
		$smarty->assign('hope', $hope);
		
		//工作标签
		$sql_jb = "select * from mtb_job_biao";
		$biao = $db->QueryArray($sql_jb);
		$smarty->assign('biao', $biao);
		
		
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
			
		
		$smarty->assign('THEME', THEME);
		$smarty->assign('BASE_URL', APP_URL);
		
		$smarty->assign('USERNAME', $_SESSION['kiwa_username']);
		$smarty->assign('USERID', $_SESSION['kiwa_userid']);
		//注册个方法
		$smarty->register_function('getname','getName');
		if(isMobile()){
		 $smarty->display('mobile/Mypage/jobregist.html');
		}else{
		$smarty->display('Mypage/jobregist.html');
		}
			
	}
?>