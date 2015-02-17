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
		case 'kefu';
			require_once 'kefu.php';
			break;
		case 'mobile';
			require_once 'mobile.php';
			break;
		case 'points';
			require_once 'points.php';
			break;
		case 'aboutus';
			require_once 'aboutus.php';
			break;
		case 'wanerj';
			require_once 'wanerj.php';
			break;
		case 'wanerm1';
			require_once 'wanerm1.php';
			break;
		case 'wanerm2';
			require_once 'wanerm2.php';
			break;
		case 'wanerm3';
			require_once 'wanerm3.php';
			break;
		case 'wanerm4';
			require_once 'wanerm4.php';
			break;
		case 'wanerm5';
			require_once 'wanerm5.php';
			break;
		case 'wantj';
			require_once 'wantj.php';
			break;
		case 'wantr';
			require_once 'wantr.php';
			break;
		case 'wantp';
			require_once 'wantp.php';
			break;
		case 'sendj';
			require_once 'sendj.php';
			break;
		case 'sendl';
			require_once 'sendl.php';
			break;
		case 'peopleregist';
			require_once 'peopleregist.php';
			break;
		case 'companyregist';
			require_once 'companyregist.php';
			break;
		default:
			//本页处理
			if($page==""){
				require_once 'index.php';
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