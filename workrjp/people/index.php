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
			require_once 'psearch.php';
			break;
		case 'done';
			require_once 'done.php';
			break;
		//详细查看一个工作信息
		case 'show';
			require_once 'pshow.php';
			break;
		//企业简历
		case 'company_pr';
			require_once 'company_pr.php';
			break;
		//生活互助动态
		case 'live';
			require_once 'news_live.php';
			break;
		//招贤纳才动态
		case 'job';
			require_once 'news_job.php';
			break;
		//闲聊动态
		case 'bbs';
			require_once 'news_bbs.php';
			break;
		//评价留言
		case 'msg';
			require_once 'msg.php';
			break;
		//评价留言
		case 'user_pr';
			require_once 'user_pr.php';
			break;
		//删除 评价留言
		case 'msg_del';
			require_once 'msg_delete.php';
			break;
		//回复留言，评价
		case 'msgdo';
			require_once '../control/people_msgC.php';
			break;
		//商品展示，个人相册
		case 'photo';
			require_once 'photo.php';
			break;
		//添加商品展示
		case 'productadd';
			require_once 'productadd.php';
			break;
		//商品展示
		case 'photoshow';
			require_once 'photoshow.php';
			break;
		//删除商品，图片
		case 'photo_del';
			require_once 'photo_delete.php';
			break;
		//添加商品展示，个人相册
		case 'p_adddo';
			require_once '../control/people_pC.php';
			break;
		//修改颜色
		case 'page_set';
			require_once 'page_set.php';
			break;
		//好友界面
		case 'friend';
			require_once 'friend.php';
			break;
		//匹配界面
		case 'match';
			require_once 'match.php';
			break;
		//发布状态
		case 'statusdo';
			require_once 'status_do.php';
			break;
		//发布工作信息
		case 'sendwork';
		require_once 'sendwork.php';
		break;
		//发布闲聊
		case 'sendbbs';
		require_once 'sendbbs.php';
		break;
		case 'userinfo';
		require_once 'userinfo.php';
		break;
		//删除状态
		case 'status_delete';
		require_once 'status_delete.php';
		break;
		
		//抽奖活动
		case 'activity';
		require_once 'activity.php';
		break;
		
		default:
			//本页处理
			if($page==""){
				require_once 'psearch.php';
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