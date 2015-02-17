<?php
	//人を探す
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	
	switch($page){
		//主页显示工作信息
		case 'done';
			require_once 'done.php';
			break;
		case 'contact';
			require_once 'contact.php';
			break;
		case 'signup';
			require_once 'signup.php';
			break;
		default:
			//本页处理
			if($page==""){
				require_once 'home.php';
				break;
			}else{
			//エラー页面
				//固定引入参数
				$smarty->assign('BASE_URL', APP_URL);
				$smarty->assign('THEME', THEME);
				$smarty->display('error.html');
			}
	}
?>