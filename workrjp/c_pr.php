<?php
	// config
	if(!file_exists('_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '_config/config.php';
	require_once '_includes/functions.php';
	
	$page = str_replace(".8n8n.co.jp","",$_SERVER['HTTP_HOST']);
	$sql="select * from dtb_2domain where yuming='$page' and del_flg = 0";
	$url = $db->QueryRow($sql);
	
	$company = getCompany($url["user_id"]);
	if($company){
		//得到高级设置
		$sql = "select * from dtb_people_page where del_flg = 0 and user_id='$url[user_id]'";
		$company_page = $db->QueryRow($sql);
		$smarty->assign('company_page', $company_page);
		//注册个根据id查找name的方法
		$smarty->register_function("getname","getName");
		//企业信息
		$smarty->assign('company', $company);
		//固定引入参数
		$smarty->assign('BASE_URL', APP_URL);
		$smarty->assign('THEME', THEME);
		$smarty->display('C/default/company_pr.html');
			
	}else{
		//没有找到
		$smarty->assign('h1', "信息提示");
		$smarty->assign('message', "非常抱歉，您指定的企业信息已不存在！感谢您的关注！");
		$smarty->assign('BASE_URL', APP_URL);
		$smarty->assign('THEME', THEME);
		$smarty->display('People/done.html');
	}
?>