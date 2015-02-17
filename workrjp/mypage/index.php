<?php
	/**mypage**/
	###################################
	# マイページ画面
	###################################
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	require_once '../_includes/mobile.php';
	
	
	//没有Session 跳转到登录页面
	if($_SESSION['kiwa_username']==""||$_SESSION['kiwa_userid']==""){
		header('Location:'.APP_URL.'login/');
	}else{
		switch ($page){
			case 'jobregist':
				require_once 'jobregist.php';
				break;
			case 'jobregistdo':
				require_once '../control/jobregistC.php';
				break;
			//主页显示工作信息
			case 'index';
				require_once 'mypage.php';
				break;
				
			//主页显示工作信息
			case 'j';
				require_once 'mypagej.php';
				break;
			//推荐人才
			case 'r';
				require_once 'mypager.php';
				break;
			//求职管理
			case 'findjob':
				require_once 'findjob.php';
				break;
			//-----------------------------------------------
				//求职管理-添加
				case 'findjob_add':
					require_once 'findjob_add.php';
					break;
				//求职管理-添加-确认画面
				case 'findjob_add_review':
					require_once 'findjob_add_review.php';
					break;
				//求职管理-添加完成
				case 'adddo':
					require_once '../control/findjob_addC.php';
					break;
				//求职管理-修改
				case 'findjob_update':
					require_once 'findjob_update.php';
					break;
				//求职管理-修改完成
				case 'findjob_update_do':
					require_once '../control/findjob_updateC.php';
					break;
				//求职管理-删除
				case 'findjob_delete':
					require_once 'findjob_delete.php';
					break;
			//-----------------------------------------------
			//已投简历
			case 'myrequirement':
				require_once 'myrequirement.php';
				break;
				case 'myrequirement_delete':
					require_once 'myrequirement_delete.php';
					break;
				case 'myrequirement_up':
					require_once 'myrequirement_up.php';
					break;
			//Job收藏夹
			case 'favorite':
				require_once 'favorite.php';
				break;
			//-----------------------------------------------
				//删除工作收藏
				case 'favorite_delete':
				require_once 'favorite_delete.php';
				break;
			//-----------------------------------------------
			//个人简历
			case 'personal':
				require_once 'personal.php';
				break;
			//-----------------------------------------------
				//修改确认
				case 'personal_view':
					require_once 'personal_view.php';
					break;
				//修改完成
				case 'personaldo':
					require_once '../control/personalC.php';
					break;
			//-----------------------------------------------
			
			//修改头像
			case 'photo':
				require_once 'photo.php';
				break;
				
			//-----------------------------------------------
			//个人信箱
			case 'pmessage':
				require_once 'pmessage.php';
				break;
				case 'pmessage_send':
					require_once 'pmessage_send.php';
					break;	
				case 'pmessage_show':
					require_once 'pmessage_show.php';
					break;
				case 'pmessage_delete':
					require_once 'pmessage_delete.php';
					break;
				case 'pmessage_re':
					require_once 'pmessage_re.php';
					break;
				case 'pmessageSend':
					require_once 'pmessageSend.php';
					break;
				case 'pmessage_senddo':
					require_once '../control/pmessage_sendC.php';
			//注册信息返回
			case 'done':
				require_once 'done.php';
				break;
			default:
				//本页处理
				if($page==""){
					require_once 'mypage.php';
					break;
				}else{
					//エラー页面
					//固定引入参数
					$smarty->assign('BASE_URL', APP_URL);
					$smarty->assign('THEME', THEME);
					$smarty->display('error.html');
				}
		}
		
	}
?>