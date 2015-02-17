<?php
	###################################
	# パスワードを忘れの画面
	###################################
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	//exit($id);
	if($id=="kiwa_ess")
	{
		$map="mapkiwa_ess.html";
	}elseif ($id=="kiwa"){
		$map="mapkiwa.html";
	}elseif ($id=="ess"){
		$map="mapess.html";
	}elseif ($id=="ways"){
		$map="mapways.html";
	}else{
		exit("你输入错误");
	}
	
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	$smarty->display('Online/'.$map);
?>