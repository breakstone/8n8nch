<?php
	//人を探す
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/mobile.php';
	
	switch($page){
		//主页显示工作信息
		case 'index';
			require_once 'bbs.php';
			break;
		case 'send';
			require_once 'send.php';
			break;
		case 'senddo';
			require_once 'senddo.php';
			break;
		case 'update';
			require_once 'bbs_update.php';
			break;
		case 'updatedo';
			require_once 'bbs_updatedo.php';
			break;
		case 'show';
			require_once 'show.php';
			break;
		case 'answerdo';
			require_once 'answerdo.php';
			break;
		case 'answertado';
			require_once 'answertado.php';
			break;
		case 'del';
			require_once 'del.php';
			break;
		case 'done';
			require_once 'done.php';
			break;
		case 'mbbs';
			require_once 'mbbs.php';
			break;
		default:
			//本页处理
			if($page==""){
				require_once 'bbs.php';
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