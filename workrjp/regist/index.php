<?php
	/**regist**/
	###################################
	# 登録の画面
	###################################
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	require_once '../_includes/mobile.php';
	
	switch($page){
		//个人注册确认画面
		case 'review': 
			require_once 'review.php';
			break;
		//个人注册处理
		case 'registdo':
			require_once '../control/registC.php';
			break;
		//注册处理
		case 'done':
			require_once 'done.php';
			break;
		//公司注册
		case 'company':
			require_once 'companyregist.php';
			break;
		//注册确认页面
		case 'companyreview':
			require_once 'companyreview.php';
			break;
		//注册处理页面
		case 'companyregistdo':
			require_once '../control/companyregistC.php';
			break;
		//注册选择页面
		case 'choose':
			require_once 'sentaku.php';
			break;
		//帮帮网使用协议
		case 'terms':
			require_once 'terms.php';
			break;
		//個人情報の取扱いに関して
		case 'perfams':
			require_once 'perfams.php';
			break;
		//留言规则
		case 'answer':
			require_once 'answer.php';
			break;
		case 'email_register':
			require_once 'email_register.php';
			break;
		case 'chose':
			require_once 'chose.php';
			break;
		case 'xz':
			require_once 'xz.php';
			break;
					
		//以上为跳转其他页面
		####################################################################################
		//以下为本页操作
				
		//本页
		default:
			
			if($page==""){
				//固定引入参数
				$smarty->assign('BASE_URL', APP_URL);
				$smarty->assign('THEME', THEME);
				
				if(isset($_GET['emailerror'])){
					//邮箱二次校验返回错误信息
					$smarty->assign('_error', "※这个邮箱地址不能使用！");
					$smarty->assign('name01', $_GET['name01']);
					$smarty->assign('name02', $_GET['name02']);
					$smarty->assign('kana01', @$_GET['kana01']);
					$smarty->assign('kana02', @$_GET['kana02']);
					$smarty->assign('email', $_GET['email']);
					
					$smarty->assign('privacy', "checked='checked'");
				}elseif(isset($_POST['email'])&&isset($_POST['password'])){
					//review.php页面返回的值
					
					$smarty->assign('name01', $_POST['name01']);
					$smarty->assign('name02', $_POST['name02']);
					$smarty->assign('kana01', @$_POST['kana01']);
					$smarty->assign('kana02', @$_POST['kana02']);
					$smarty->assign('email', $_POST['email']);
					$smarty->assign('password', $_POST['password']);
					
					$smarty->assign('privacy', "checked='checked'");
				}
				
				if(isMobile()){
					$smarty->display('mobile/Regist/regist.html');
				}else{
					$smarty->display('Regist/regist.html');
				}
				
			}else{
				//固定引入参数
				$smarty->assign('BASE_URL', APP_URL);
				$smarty->assign('THEME', THEME);
				$smarty->display('error.html');
			}
	}
	
	
	
?>