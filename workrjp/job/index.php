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
		//主页显示工作信息
		case 'index';
			require_once 'jsearch.php';
			break;
		//显示临时工作信息
		case 'ls';
			require_once 'job_ls.php';
			break;
		//详细查看一个临时工作信息
		case 'lsshow';
			require_once 'jshow_ls.php';
			break;
		//详细查看一个工作信息
		case 'show';
			require_once 'jshow.php';
			break;
		//更新工作信息
		case 'update';
			require_once 'job_update.php';
			break;
		//更新工作信息
		case 'update_ls';
			require_once 'job_update_ls.php';
			break;
		//提交工作信息
		case 'updatedo';
			require_once 'job_updatedo.php';
			break;
		//提交工作信息
		case 'update_lsdo';
			require_once 'job_update_lsdo.php';
			break;
		//删除工作信息
		case 'del';
			require_once 'job_del.php';
			break;
		//删除临时工作信息
		case 'del_ls';
			require_once 'job_del_ls.php';
			break;
		case 'answerdo';
			require_once 'answerdo.php';
			break;
		case 'answertado';
			require_once 'answertado.php';
			break;
			
		case 'answerdols';
			require_once 'answerdols.php';
			break;
		case 'answertadols';
		require_once 'answertadols.php';
			break;
		case 'msearch';
			require_once 'msearch.php';
			break;
		default:
			//本页处理
			if($page==""){
				require_once 'jsearch.php';
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