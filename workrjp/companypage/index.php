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
	if($_SESSION['kiwa_companyname']==""||$_SESSION['kiwa_companyid']==""){
		header('Location:'.APP_URL.'login/');
	}else{
	switch ($page){
			//主页显示工作信息
			case 'index';
				require_once 'companypage.php';
				break;
			case 'j';
				require_once 'companypagej.php';
				break;
			//更改头像
			case 'photo';
				require_once 'photo.php';
				break;
			//求人管理
			case 'findpeople':
				require_once 'findpeople.php';
				break;
				//-----------------------------
				//添加求人
				case 'findpeople_add':
					require_once 'findpeople_add.php';
					break;
				//添加求人view
				case 'findpeople_add_review':
					require_once 'findpeople_add_review.php';
					break;
				//adddo
				case 'adddo':
					require_once '../control/findpeople_addC.php';
					break;
				//修改求人信息
				case 'findpeople_update':
					require_once 'findpeople_update.php';
					break;
				//updatedo
				case 'updatedo':
					require_once '../control/findpeople_updateC.php';
					break;
				//删除求人信息
				case 'findpeople_delete':
					require_once 'findpeople_delete.php';
					break;
				//-----------------------------
			//应募管理
			case 'myrequirement':
				require_once 'myrequirement.php';
				break;
				case 'myrequirement_delete':
					require_once 'myrequirement_delete.php';
					break;
				case 'myrequirement_up':
					require_once 'myrequirement_up.php';
					break;
				//查看应募者的信息
				case 'lvli':
					require_once 'lvli.php';
					break;
				//删除应募者信息
				case 'getnote_delete':
					require_once 'getnote_delete.php';
					break;
			//People收藏夹
			case 'favorite':
				require_once 'favorite.php';
				break;
				//favorite_delete 删除收藏
				case 'favorite_delete':
					require_once 'favorite_delete.php';
					break;
			//企业简历
			case 'company':
				require_once 'company.php';
				break;
				case 'companydo':
					require_once '../control/companyC.php';
					break;
			//-----------------------------------------------
			//企业信箱
			case 'cmessage':
				require_once 'cmessage.php';
				break;
				
				case 'cmessage_show':
					require_once 'cmessage_show.php';
					break;
				case 'cmessage_delete':
					require_once 'cmessage_delete.php';
					break;
				case 'cmessage_re':
					require_once 'cmessage_re.php';
					break;
				case 'cmessage_send':
					require_once 'cmessage_send.php';
					break;
				case 'cmessageSend':
					require_once 'cmessageSend.php';
					break;
				case 'cmessage_senddo':
					require_once '../control/cmessage_sendC.php';
			//注册信息返回
			case 'done':
				require_once 'done.php';
				break;
			case 'jobregist':
				require_once 'jobregist.php';
				break;
			case 'jobregistdo':
				require_once '../control/com_jobregistC.php';
				break;
			default:
			//本页处理
			if($page==""){
				require_once 'companypage.php';
				break;
			}else{
				//エラー页面
				$smarty->assign('BASE_URL', APP_URL);
				$smarty->assign('THEME', THEME);
				$smarty->display('error.html');
			}
		}
	}
?>