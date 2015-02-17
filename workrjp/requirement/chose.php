<?php
	// config
	if(!file_exists('../_config/config.php'))
	{
		die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	
	$areas = getPref();
	//get得到生活服务内容
	$life_service = getLifeservice();
	$smarty->assign('life_service', $life_service);
	$smarty->assign('areas', $areas);
	global $db;
	$sql_1 = "select * from mtb_life_service where funlei = '房产相关' order by rank";
	$fangchan = $db->QueryArray($sql_1);
	$sql_2 = "select * from mtb_life_service where funlei = '跳蚤市场' order by rank";
	$tiaozao = $db->QueryArray($sql_2);
	$sql_3 = "select * from mtb_life_service where funlei = '车辆服务' order by rank";
	$cheliang = $db->QueryArray($sql_3);
	$sql_4 = "select * from mtb_life_service where funlei = '家政服务' order by rank";
	$jiazheng = $db->QueryArray($sql_4);
	$sql_5 = "select * from mtb_life_service where funlei = '生活相关' order by rank";
	$shenghuo = $db->QueryArray($sql_5);
	$sql_6 = "select * from mtb_life_service where funlei = '美食旅游' order by rank";
	$meishi = $db->QueryArray($sql_6);
	$sql_7 = "select * from mtb_life_service where funlei = '设计相关' order by rank";
	$sheji = $db->QueryArray($sql_7);
	$sql_8 = "select * from mtb_life_service where funlei = '法律帮助' order by rank";
	$falv = $db->QueryArray($sql_8);
	
	$smarty->assign('fangchan', $fangchan);
	$smarty->assign('tiaozao', $tiaozao);
	$smarty->assign('cheliang', $cheliang);
	$smarty->assign('jiazheng', $jiazheng);
	$smarty->assign('shenghuo', $shenghuo);
	$smarty->assign('meishi', $meishi);
	$smarty->assign('sheji', $sheji);
	$smarty->assign('falv', $falv);
	
	//注册个根据id查找name的方法
	$smarty->register_function("getname","getName");
	
	//固定引入参数
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	
	if(isMobile()){
		$smarty->display('mobile/Requirement/chose.html');
	}else{
		$smarty->display('Requirement/chose.html');
	}
	
?>