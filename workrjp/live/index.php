<?php
	//仕事を探す
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/mobile.php';
	
	switch($page){
		//主页显示需求信息
		case 'index';
			require_once 'lsearch.php';
			break;
		//详细查看一个需求信息
		case 'show';
			require_once 'lshow.php';
			break;
		//删除工作信息
		case 'del';
		require_once 'live_del.php';
		break;
		//回复需求
		case 'answerdo';
			require_once '../control/live_answerC.php';
			break;
		//回复TA
		case 'answertado';
			require_once '../control/live_answertaC.php';
			break;
		
		case 'mlive';
			require_once 'mlive.php';
			break;
				
		//详细查看一个需求信息
		case 's1';
			require_once 'lsearch.php';
			break;
		case 's2';
			require_once 'lsearch.php';
			break;
		case 's3';
			require_once 'lsearch.php';
			break;
		case 's4';
			require_once 'lsearch.php';
			break;
		case 's5';
			require_once 'lsearch.php';
			break;
		case 's6';
			require_once 'lsearch.php';
			break;
		case 's7';
			require_once 'lsearch.php';
			break;
		case 's8';
			require_once 'lsearch.php';
			break;
		case 's9';
			require_once 'lsearch.php';
			break;
		case 's10';
			require_once 'lsearch.php';
			break;
		case 's11';
			require_once 'lsearch.php';
			break;
		case 's12';
			require_once 'lsearch.php';
			break;
		case 's13';
			require_once 'lsearch.php';
			break;
		case 's14';
			require_once 'lsearch.php';
			break;
		case 's15';
			require_once 'lsearch.php';
			break;
		case 's16';
			require_once 'lsearch.php';
			break;
		case 's17';
			require_once 'lsearch.php';
			break;
		case 's18';
			require_once 'lsearch.php';
			break;
		case 's19';
			require_once 'lsearch.php';
			break;
		case 's20';
			require_once 'lsearch.php';
			break;
		case 's21';
			require_once 'lsearch.php';
			break;
		case 's22';
			require_once 'lsearch.php';
			break;
		case 's23';
			require_once 'lsearch.php';
			break;
		case 's24';
			require_once 'lsearch.php';
			break;
		case 's25';
			require_once 'lsearch.php';
			break;
		case 's26';
			require_once 'lsearch.php';
			break;
		case 's27';
			require_once 'lsearch.php';
			break;
		case 's28';
			require_once 'lsearch.php';
			break;
		case 's29';
			require_once 'lsearch.php';
			break;
		case 's30';
			require_once 'lsearch.php';
			break;
		case 's31';
			require_once 'lsearch.php';
			break;
			
		default:
			//本页处理
			if($page==""){
				require_once 'lsearch.php';
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