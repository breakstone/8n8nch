<?php
	//得到求职信息
	$job = showJob($id);
	if($job['company_id']==$_SESSION['kiwa_companyid']){//判断是不是发表信息的人
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
		//get各种条件
		$condition = getCondition();
		
		//根据地域得出城市
		$prefs = getPref($job['area_cd']);
		$smarty->assign('prefSearch', $prefs);
		//根据业种得出职种
		$types = getTypes($job['kindsID']);
		$smarty->assign('types', $types);
		
		//线路的处理
		if($job['line_num']!=""){
			$smarty->assign('line_num', explode(",",$job['line_num']));
		}
		//车站的处理
		if($job['station_cd']!=""){
			$smarty->assign('station_cd', explode(",",$job['station_cd']));
		}
		//发表求职的职种处理
		$smarty->assign('job_types', explode(",",$job['typesID']));
		//各种条件的处理
		$smarty->assign('job_condition', explode(",",$job['condition_id']));
		
		$smarty->assign('job', $job);
		$smarty->assign('companyfrom', $companyfrom);
		$smarty->assign('areas', $areas);
		$smarty->assign('money', $money);
		$smarty->assign('employ', $employ);
		$smarty->assign('hope', $hope);
		$smarty->assign('condition', $condition);
		
		//固定引入参数
		//mypage页面信息查询
		$smarty->assign('THEME', THEME);
		$smarty->assign('BASE_URL', APP_URL);
		
		//注册个方法
		$smarty->register_function('getname','getName');
		$smarty->assign('COMPANYNAME', $_SESSION['kiwa_companyname']);
		$smarty->assign('COMPANYID', $_SESSION['kiwa_companyid']);
		$smarty->display('Companypage/findpeople_update.html');
	}else{
		//没有参数跳到错误页面
		header('Location:'.APP_URL.'Companypage/done/error');
	}
?>