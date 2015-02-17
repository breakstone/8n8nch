<?php
	//仕事を探す
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	require_once '../_includes/mobile.php';
	
	switch($page){
		//主页显示需求信息
		case 'index';
			require_once 'chose.php';
			break;
		case 'live';
			require_once 'live.php';
			break;
		case 'save';
			require_once 'save.php';
			break;
		case 'saved';
			require_once 'saved.php';
			break;
		//工作
		case 'work';
			require_once 'work.php';
			break;
		//发布临时工作
		case 'workls';
			require_once 'work_ls.php';
			break;
		case 'work_review';
			require_once 'work_review.php';
			break;
		case 'work_lsdo';
			require_once '../control/work_lsdo.php';
			break;
		case 'workadddo':
			require_once '../control/work_addC.php';
			break;
		default:
			//本页处理
			if($page==""){
				require_once 'chose.php';
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