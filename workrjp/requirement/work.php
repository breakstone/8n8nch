<?php
	if(isset($_POST["review"])&&$_POST["review"]==1){
		### review画面修改
	
		//求人标题
		$smarty->assign('job_title', $_POST['job_title']);
		//工作内容
		$contents = str_replace("<br>","\r\n",$_POST["contents"]);
		$smarty->assign('contents', $contents);
		//招收对象
		$requirements = str_replace("<br>","\r\n",$_POST["requirements"]);
		$smarty->assign('requirements', $requirements);
		$smarty->assign('lianxi', $_POST["lianxi"]);
		$smarty->assign('shoufei', $_POST["shoufei"]);
		if(isset($_POST['numbers'])&&$_POST['numbers']!=""){
			//招收人数
			$smarty->assign('numbers', $_POST['numbers']);
		}
// 		$smarty->assign('over_date', $_POST['over_date']);

		$smarty->assign('zz', $_POST["zz"]);
		$smarty->assign('w_d', $_POST["w_d"]);
		
		//根据地域得出城市
		$prefs = getPref($_POST['areaid']);
		$smarty->assign('prefSearch', $prefs);
		//根据业种得出职种
		$types = getTypes($_POST['kinds']);
		$smarty->assign('types', $types);
		//希望工作地
		$smarty->assign('areaid', $_POST['areaid']);
		$smarty->assign('pref', $_POST['pref']);
		if(isset($_POST['ensn'])&&$_POST['ensn']!=""){
			$smarty->assign('ensn', $_POST['ensn']);
		}
		if(isset($_POST['eki'])&&$_POST['eki']!=""){
			$smarty->assign('eki', $_POST['eki']);
		}
	
		//业种・职业选择
		$smarty->assign('kinds', $_POST['kinds']);
		$smarty->assign('select_types', $_POST['types']);
		//薪资选择
		$smarty->assign('moneyid', $_POST['moneyid']);
		//雇用形式选择
		$smarty->assign('employid', $_POST['employid']);
		//工作时间
		$smarty->assign('hopeid', $_POST['hopeid']);
		if(isset($_POST['conditions'])&&$_POST['conditions']!=""){
			//其他条件
			$smarty->assign('conditions', $_POST['conditions']);
		}
	}
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
	if(isMobile()){
		$smarty->display('mobile/Requirement/work.html');
	}else{
		$smarty->display('Requirement/work.html');
	}
	
	
	//固定引入参数
// 	$smarty->assign('BASE_URL', APP_URL);
// 	$smarty->assign('THEME', THEME);
// 	$smarty->display('Requirement/live_old.html');
?>