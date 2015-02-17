<?php
	/**regist**/
	###################################
	# 登録の画面
	###################################
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	
	//读取职种
	$smarty->assign('companyfroms', getCompanyfrom());
	
	//get得到地域数据
	$areas = getPref();
	$smarty->assign('areas', $areas);
	//get得到生活服务内容
	$life_service = getLifeservice();
	$smarty->assign('life_service', $life_service);
	//get得到业种数据
	$companyfrom = getCompanyfrom();
	$smarty->assign('companyfrom', $companyfrom);
	
	//企业分类
	$sql_comp = "select * from mtb_company_type";
	$qi = $db->QueryArray($sql_comp);
	$smarty->assign('qi', $qi);
	
	//固定引入参数
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	
	if(isset($_GET['emailerror'])){
		//邮箱二次校验返回错误信息
		$smarty->assign('_error', "※这个邮箱地址不能使用！");
		$smarty->assign('company_name', $_GET['c_n']);
		$smarty->assign('company_manager', $_GET['c_m']);
		$smarty->assign('foundation_dateYear', $_GET['f_dY']);
		$smarty->assign('foundation_dateMonth', $_GET['f_dM']);
		$smarty->assign('company_money', $_GET['c_my']);
		$smarty->assign('company_url', $_GET['c_u']);
		$zip = explode("-",$_GET['zip']);
		$smarty->assign('zip01', $zip[0]);
		$smarty->assign('zip02', $zip[1]);
		$smarty->assign('add_pref', $_GET['add_pref']);
		$smarty->assign('addr01', $_GET['addr01']);
		$smarty->assign('addr02', $_GET['addr02']);
		$company_from = explode("-",$_GET['c_f']);
		$smarty->assign('tel01', $_GET['tel01']);
		$smarty->assign('tel02', $_GET['tel02']);
		$smarty->assign('tel03', $_GET['tel03']);
		$smarty->assign('fax01', $_GET['fax01']);
		$smarty->assign('fax02', $_GET['fax02']);
		$smarty->assign('fax03', $_GET['fax03']);
		$smarty->assign('company_email', $_GET['c_e']);
		
		$smarty->assign('privacy', "checked='checked'");
	}elseif(isset($_POST['company_email'])&&isset($_POST['password'])){
		//companyreview.php页面返回的值
		$smarty->assign('company_name', $_POST['company_name']);
		$smarty->assign('company_manager', $_POST['company_manager']);
		$smarty->assign('foundation_dateYear', $_POST['foundation_dateYear']);
		$smarty->assign('foundation_dateMonth', $_POST['foundation_dateMonth']);
		$smarty->assign('company_money', $_POST['company_money']);
		$smarty->assign('company_url', $_POST['company_url']);
		$smarty->assign('zip01', $_POST['zip01']);
		$smarty->assign('zip02', $_POST['zip02']);
		$smarty->assign('add_pref', $_POST['add_pref']);
		$smarty->assign('addr01', $_POST['addr01']);
		$smarty->assign('addr02', $_POST['addr02']);
		
		$smarty->assign('areaid', $_POST['areaid']);
		
		if($_POST["areaid"]!=""){
			$pref = getPref($_POST["areaid"]);
			$smarty->assign('prefSearch', $pref);
		}
		$smarty->assign('area_cd', $_POST["areaid"]);
		$smarty->assign('pref_cd', $_POST["pref"]);
		//根据业种得出职种
		//if($_POST["kinds"]!=""){
		//	$types = getTypes($_POST["kinds"]);
		//	$smarty->assign('types', $types);
		//}
		//$smarty->assign('kinds', $_POST["kinds"]);
		//if($_POST["types_str"]!=""){
		//	$stypes = explode(",", $_POST["types_str"]);
		//	$smarty->assign('select_types', $stypes);
		//}
		if($_POST["skills_str"]!=""){
			$skill = explode(",", $_POST["skills_str"]);
			$smarty->assign('skill', $skill);
		}
		if(isset($_POST["ensn"])&&$_POST["ensn"]!=""){
			$smarty->assign('ensn', $_POST["ensn"]);
		}
		if(isset($_POST["eki"])&&$_POST["eki"]!=""){
			$smarty->assign('eki', $_POST["eki"]);
		}
		
		$smarty->assign('tel01', $_POST['tel01']);
		$smarty->assign('tel02', $_POST['tel02']);
		$smarty->assign('tel03', $_POST['tel03']);
		$smarty->assign('fax01', $_POST['fax01']);
		$smarty->assign('fax02', $_POST['fax02']);
		$smarty->assign('fax03', $_POST['fax03']);
		$smarty->assign('company_email', $_POST['company_email']);
		$smarty->assign('password', $_POST['password']);
		$smarty->assign('privacy', "checked='checked'");
	}
	
	
	//注册个方法
	$smarty->register_function('getname','getName');
	if(isMobile()){
		$smarty->display('mobile/Regist/companyregist.html');
	}else{
		$smarty->display('Regist/companyregist.html');
	}
	//$smarty->display('Regist/.html');
?>