<?php
	if(isset($_POST["review"])&&$_POST["review"]==1){
		### review画面修改
		
		//根据地域得出城市
		$prefs = getPref($_POST['areaid']);
		$smarty->assign('prefSearch', $prefs);
		//根据业种得出职种
		$types = getTypes($_POST['kinds']);
		$smarty->assign('types', $types);
		//创建假user
		$user = array(
					"birth" => $_POST['birth'],
					"sex" => $_POST['sex'],
					"email" => $_POST['email'],
					"per_status" => $_POST['per_status'],
					"zige" => $_POST['zige'],
					"mypr" => $_POST['pr'],
					"eki" => $_POST['zj_eki'],
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
		//求职标题
		$smarty->assign('people_title', $_POST['people_title']);
		//求职描述
		$smarty->assign('people_pr', $_POST['people_pr']);
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
		//希望的工作时间
		$smarty->assign('hopeid', $_POST['hopeid']);
	}else{
		### 直接进来
		//得到个人简历
		$user = getUser($_SESSION['kiwa_userid']);
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
	$smarty->display('Mypage/findjob_add.html');
?>