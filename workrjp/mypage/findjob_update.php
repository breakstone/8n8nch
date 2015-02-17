<?php
	//得到求职信息
	$people = showPeople($id);
	if($people['user_id']==$_SESSION['kiwa_userid']){//判断是不是发表信息的人
		//得到个人简历
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
		
		//根据地域得出城市
		$prefs = getPref($people['area_cd']);
		$smarty->assign('prefSearch', $prefs);
		
		//线路的处理
		if($people['line_num']!=""){
			$smarty->assign('line_num', explode(",",$people['line_num']));
		}
		//车站的处理
		if($people['station_cd']!=""){
			$smarty->assign('station_cd', explode(",",$people['station_cd']));
		}
		
		//根据业种得出职种
		$types = getTypes($people['kindsID']);
		$smarty->assign('types', $types);
		//发表求职的职种处理
		$smarty->assign('people_types', explode(",",$people['typesID']));
		
		$smarty->assign('people', $people);
		$smarty->assign('user', $user);
		$smarty->assign('eduction', $eduction);
		$smarty->assign('companyfrom', $companyfrom);
		$smarty->assign('areas', $areas);
		$smarty->assign('money', $money);
		$smarty->assign('employ', $employ);
		$smarty->assign('hope', $hope);
		
		
		//注册个方法
		$smarty->register_function('getname','getName');
		//固定引入参数
		//mypage页面信息查询
		$smarty->assign('THEME', THEME);
		$smarty->assign('BASE_URL', APP_URL);
		
		$smarty->assign('USERNAME', $_SESSION['kiwa_username']);
		$smarty->assign('USERID', $_SESSION['kiwa_userid']);
		$smarty->display('Mypage/findjob_update.html');
	}else{
		//没有参数跳到错误页面
		header('Location:'.APP_URL.'mypage/done/error');
	}
?>