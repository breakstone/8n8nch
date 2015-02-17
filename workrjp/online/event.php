<?php
	//人を探す
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	require_once '../_includes/mobile.php';
	//没有Session 跳转到登录页面
	$sql_gun2 = "select * from dtb_bbs where del_flg = 0 and bbs_type='就职经验-参加活动'  order by create_date desc";
	$bbs = $db->QueryArray($sql_gun2);
	//$bbs = getBbs($where);
	$smarty->assign('bbs', $bbs);
	//固定引入参数
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	if(isMobile()){
		$smarty->assign('mobile', '1');
		$smarty->display('Online/event.html');
	}else{
		$smarty->assign('mobile', '0');
		$smarty->display('Online/event.html');
	}
?>