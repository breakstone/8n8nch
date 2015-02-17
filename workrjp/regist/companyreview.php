<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	
	//注册页面传过来参数
	if(isset($_POST['company_name'])&&isset($_POST['company_manager'])&&isset($_POST['password'])&&isset($_POST['company_email'])){
		//安全过滤
		$company_name = cleanInput($_POST['company_name']);
		$company_manager = cleanInput($_POST['company_manager']);
		$foundation_dateYear = cleanInput($_POST['foundation_dateYear']);
		$foundation_dateMonth = cleanInput($_POST['foundation_dateMonth']);
		$company_money = cleanInput($_POST['company_money']);
		$company_url = cleanInput($_POST['company_url']);
		
		$areaid = cleanInput($_POST['areaid']);
		$pref = cleanInput($_POST['pref']);
		$types_str="";$typesname_str="";$kindsname="";$skills_str="";$ensn_str="";$eki_str="";
// 		$kinds = $_POST['kinds'];
// 		foreach ($_POST['types'] as $t){
// 			$types_str .= $t.",";
// 			$row = getKindTypename($t);
// 			$typesname_str .= $row["typesname"].",";
// 			$kindsname = $row["kindsname"];
// 		}
// 		$types_str = rtrim($types_str,",");
// 		$typesname_str = rtrim($typesname_str,",");
		
		foreach ($_POST['skills'] as $s){
			$skills_str .= $s.",";
		}
		$skills_str = rtrim($skills_str,",");
		
		if(isset($_POST['ensn'])){
			$ensn = $_POST['ensn'];
		}else{
			$ensn = "";
		}
		
		if(isset($_POST['eki'])){
			$eki = $_POST['eki'];
		}else{
			$eki = "";
		}
		
		$zip01 = cleanInput($_POST['zip01']);
		$zip02 = cleanInput($_POST['zip02']);
		$add_pref = getName(array("name"=>"pref","value"=>$pref));
		$addr01 = cleanInput($_POST['addr01']);
		$addr02 = cleanInput($_POST['addr02']);
		
		$tel01 = cleanInput($_POST['tel01']);
		$tel02 = cleanInput($_POST['tel02']);
		$tel03 = cleanInput($_POST['tel03']);
		$fax01 = cleanInput($_POST['fax01']);
		$fax02 = cleanInput($_POST['fax02']);
		$fax03 = cleanInput($_POST['fax03']);
		$company_email = cleanInput($_POST['company_email']);
		$password = cleanInput($_POST['password']);
		
		//对email进行二次校验,成功显示
		if(emailRegist($company_email)){
			$smarty->assign('company_name', $company_name);
			$smarty->assign('company_manager', $company_manager);
			$smarty->assign('foundation_dateYear', $foundation_dateYear);
			$smarty->assign('foundation_dateMonth', $foundation_dateMonth);
			$smarty->assign('company_money', $company_money);
			$smarty->assign('company_url', $company_url);
			$smarty->assign('zip01', $zip01);
			$smarty->assign('zip02', $zip02);
			$smarty->assign('add_pref', $add_pref);
			$smarty->assign('addr01', $addr01);
			$smarty->assign('addr02', $addr02);
			
			$smarty->assign('areaid', $areaid);
			$smarty->assign('pref', $pref);
// 			$smarty->assign('kinds', $kinds);
// 			$smarty->assign('kindsname', $kindsname);
// 			$smarty->assign('types_str', $types_str);
// 			$smarty->assign('typesname_str', $typesname_str);
			$smarty->assign('ensn', $ensn);
			$smarty->assign('eki', $eki);
			$smarty->assign('skills_str', $skills_str);
			
			//职业种处理 多选框
			$smarty->assign('tel01', $tel01);
			$smarty->assign('tel02', $tel02);
			$smarty->assign('tel03', $tel03);
			$smarty->assign('fax01', $fax01);
			$smarty->assign('fax02', $fax02);
			$smarty->assign('fax03', $fax03);
			$smarty->assign('company_email', $company_email);
			$smarty->assign('password', $password);
			
			//注册个方法
			$smarty->register_function('getname','getName');
			//固定引入参数
			$smarty->assign('BASE_URL', APP_URL);
			$smarty->assign('THEME', THEME);
			if(isMobile()){
				$smarty->display('mobile/Regist/companyreview.html');
			}else{
				$smarty->display('Regist/companyreview.html');
			}
			//$smarty->display('Regist/companyreview.html');
			
		}else{
			//email二次校验不成功，返回错误，数据库里已经注册过了
			$company_form = implode("-",$company_form);
			$url="c_n=$company_name&c_m=$company_manager&f_dY=$foundation_dateYear&f_dM=$foundation_dateMonth&
			c_my=$company_money&c_u=$company_url&zip=$zip01-$zip02&add_pref=$add_pref&addr01=$addr01&addr02=$addr02&
			c_f=$company_form&tel01=$tel01&tel02=$tel02&tel03=$tel03&fax01=$fax01&fax02=$fax02&fax03=$fax03&
			c_e=$company_email";
			header('Location:'.APP_URL.'regist/company/?emailerror&'.$url);
		}
		
	}else{
		//没有参数跳回注册页面
		header('Location:'.APP_URL.'regist/company');
	}
?>