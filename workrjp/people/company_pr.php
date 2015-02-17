<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	
	$company = getCompany($id);
	if($company){
		//得到高级设置
		$sql = "select * from dtb_people_page where del_flg = 0 and user_id='$id'";
		$company_page = $db->QueryRow($sql);
		$smarty->assign('company_page', $company_page);
		//注册个根据id查找name的方法
		$smarty->register_function("getname","getName");
		//企业信息
		$smarty->assign('company', $company);
		//好友
		$sqlall = "select * from dtb_favorite_list where user_id = '$company[company_id]' and favorite_id != '$company[company_id]' and (flag='user' or flag='company') and del_flg = 0 order by create_date desc";
		$friends = $db->QueryArray($sqlall);
		$smarty->assign('friend', $friends);
		//固定引入参数
		$smarty->assign('BASE_URL', APP_URL);
		$smarty->assign('THEME', THEME);
		$smarty->display('People/company_pr.html');
			
	}else{
		//没有找到
		$smarty->assign('h1', "信息提示");
		$smarty->assign('message', "非常抱歉，您指定的企业信息已不存在！感谢您的关注！");
		$smarty->assign('BASE_URL', APP_URL);
		$smarty->assign('THEME', THEME);
		$smarty->display('People/done.html');
	}
?>