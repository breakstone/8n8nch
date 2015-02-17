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
		case 'over';
			require_once 'over.php';
			break;
		case 'login';
			require_once 'login.php';
			break;
		//越南人在线填写工作时间
		case 'yue';
			require_once 'yue.php';
			break;
		//喜和在线面试系统
		case 'kiwa';
			require_once 'kiwa.php';
			break;
		//喜和在线面试系统
		case 'kiwa2';
			require_once 'kiwa2.php';
			break;
		//喜和在线面试系统
		case 'kiwa3';
			require_once 'kiwa3.php';
			break;
		//喜和在线面试系统
		case 'kiwa4';
			require_once 'kiwa4.php';
			break;
		//喜和在线面试系统
		case 'kiwa5';
			require_once 'kiwa5.php';
			break;
		//ess在线面试系统
		case 'ess';
			require_once 'ess.php';
			break;
		//ways在线面试系统
		case 'ways';
			require_once 'ways.php';
			break;
		//ways在线面试系统
		case 'kiwa_net';
		require_once 'kiwa_net.php';
		break;
		case 'kiwa_netsee';
		require_once 'kiwa_netsee.php';
		break;
		
			
		//关西在线面试系统
		case 'guanxi';
			require_once 'kiwa_guanxi.php';
			break;
		case 'guanxiover';
			require_once 'guanxiover.php';
			break;
		//关西在线面试系统
		case 'gxm';
			require_once 'guanxi.php';
			break;
		case 'signup';
			require_once 'kiwa_signup.php';
			break;
		case 'kiwaover';
			require_once 'kiwaover.php';
			break;
		case 'event2';
			require_once 'kiwa_event2.php';
			break;
			//传单活动
		case 'cdevent';
			require_once 'cd_event.php';
			break;
		//征文活动
		case 'event';
			require_once 'event.php';
			break;
			//喜和工业周年庆
		case 'kiwa_znq';
			require_once 'kiwa_znq.php';
			break;
		//喜和工业涨薪通知
		case 'kiwa_zxtz';
			require_once 'kiwa_zxtz.php';
			break;
		case 'map';
			require_once 'map.php';
			break;
		default:
			//本页处理
			if($page==""){
				require_once 'yue.php';
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