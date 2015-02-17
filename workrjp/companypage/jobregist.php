<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	//没有Session 跳转到登录页面
	if($_SESSION['kiwa_companyname']==""||$_SESSION['kiwa_companyid']==""){
		header('Location:'.APP_URL.'login/');
	}else{
		$id = cleanInput($id);
		$job = showJob($id);
		$company = getCompany($_SESSION['kiwa_companyid']);
		//得到最终学历
		$eduction = getEduction();

		$smarty->assign('job', $job);
		$smarty->assign('company', $company);
		$smarty->assign('eduction', $eduction);
		
		//get得到业种数据
		$companyfrom = getCompanyfrom();
		$smarty->assign('companyfrom', $companyfrom);
		//企业形态处理
		$company_form = explode("_",$company["company_form"]);
		$smarty->assign('company_form', $company_form);
		//判断企业宣传照片
		if($company["company_photo_url"]!=""){
			if(file_exists("../".$company["company_photo_url"])){
				$smarty->assign('img_status', 1);
			}else{
				$smarty->assign('img_status', "");
			}
		}
		
		//企业分类
		$sql_comp = "select * from mtb_company_type";
		$qi = $db->QueryArray($sql_comp);
		$smarty->assign('qi', $qi);
		
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
		//注册个方法
		$smarty->register_function('getname','getName');
		
		$smarty->assign('THEME', THEME);
		$smarty->assign('BASE_URL', APP_URL);
		
		$smarty->assign('COMPANYNAME', $_SESSION['kiwa_companyname']);
		$smarty->assign('COMPANYID', $_SESSION['kiwa_companyid']);
		$smarty->display('Companypage/jobregist.html');
	}
?>