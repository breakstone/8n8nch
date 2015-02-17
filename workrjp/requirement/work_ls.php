<?php
	require_once '../_includes/form.class.php';
	if((isset($_SESSION["kiwa_userid"])&&$_SESSION["kiwa_userid"]!="")||(isset($_SESSION["kiwa_companyid"])&&$_SESSION["kiwa_companyid"]!="")){
		//get得到业种数据
		// 	$companyfrom = getCompanyfrom();
		//get得到地域数据
		$areas = getPref();
		$smarty->assign('areas', $areas);
		//get給付
		// 	$money = getMoney();
		//get雇用形態
		// 	$employ = getEmploy_method();
		//get就业希望期间
		// 	$hope = getHope_date();
		//get各种条件
		// 	$condition = getCondition();
		
		// 	$smarty->assign('companyfrom', $companyfrom);
		
		// 	$smarty->assign('money', $money);
		// 	$smarty->assign('employ', $employ);
		// 	$smarty->assign('hope', $hope);
		// 	$smarty->assign('condition', $condition);
		// 	$start="";
		// 	if($start=="")
		//$start=time();
// 		$start=date("Y-m-d",time());
		$today = date("Y-m-d");
		$start=strtotime("$today 00:00:00");
		$smarty->assign("date_star", Form::date("starttime",date("Y-m-d",$start)));
		//$smarty->assign("date_star", Form::date("date_star"));
		$smarty->assign("date_end", Form::date("endtime",date("Y-m-d", "")));
		//固定引入参数
		//mypage页面信息查询
		$smarty->assign('THEME', THEME);
		$smarty->assign('BASE_URL', APP_URL);
		
		//注册个方法
		$smarty->register_function('getname','getName');
		if(isMobile()){
			$smarty->display('mobile/Requirement/work_ls.html');
		}else{
			$smarty->display('Requirement/work_ls.html');
		}
	}else{
		//固定引入参数
		header('Location:'.APP_URL.'login/?url=bbs_send');
	}

?>