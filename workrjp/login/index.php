<?php
	/**login**/
	###################################
	# ログイン画面
	###################################
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/mobile.php';
	
	if(@$_SESSION['kiwa_companyid']=="" && @$_SESSION['kiwa_userid']==""){
		if(!empty($_COOKIE["8n8nuser"])&&!empty($_COOKIE["8n8npw"])){
			$cookie_user = $_COOKIE["8n8nuser"];
			$cookie_pw = $_COOKIE["8n8npw"];
			$sql_u="select user_id,name01,name02 from dtb_user where email = '$cookie_user' and password = '$cookie_pw'";
			$sql_c="select company_id from dtb_companyuser where email = '$cookie_user' and password = '$cookie_pw'";
			$row_u = $db->QueryRow($sql_u);
			$row_c = $db->QueryRow($sql_c);
			if($row_u != ""){
				$_SESSION['kiwa_userid'] = $row_u['user_id'];
				$_SESSION['kiwa_username'] = $row_u['name01']." ".$row_u['name02'];
			}elseif($row_c != ""){
				$_SESSION['kiwa_companyid'] = $row_c['company_id'];
				$_SESSION['kiwa_companyname'] = $row_c['company_name'];
			}
		}
	}
	if(isset($_SESSION['kiwa_username'])||isset($_SESSION['kiwa_userid'])){
		//有session 跳转到mypage页面
		if($page=="false"){//邮箱没有认证
			//邮箱没有认证
			$sql_email = "select email from dtb_user where user_id='$_SESSION[kiwa_userid]' and del_flg=0";
			$email = $db->QueryItem($sql_email);
			$smarty->assign('email', $email);
			$smarty->assign('BASE_URL', APP_URL);
			$smarty->assign('THEME', THEME);
			$smarty->display('Login/email_identify.html');
		}else{
			header('Location:'.APP_URL.'mypage/');
		}
	}elseif(isset($_SESSION['kiwa_companyname'])||isset($_SESSION['kiwa_companyid'])){
		//有session 跳转到companypage页面
		if($page=="false"){//邮箱没有认证
			//邮箱没有认证
			$sql_email = "select company_email from dtb_companyuser where company_id='$_SESSION[kiwa_companyid]' and del_flg=0";
			$email = $db->QueryItem($sql_email);
			$smarty->assign('email', $email);
			$smarty->assign('BASE_URL', APP_URL);
			$smarty->assign('THEME', THEME);
			$smarty->display('Login/email_identify.html');
		}else{
			header('Location:'.APP_URL.'companypage/');
		}
	}else{
		//没有Session 跳转到登录页面
		switch($page){
			//loginC.php
			case 'logindo':
				require_once '../control/loginC.php';
				break;
			//forgetC.php
			case 'forgetdo':
				require_once '../control/forgetC.php';
				break;
			//forget.php パスワードを忘れ
			case 'forget':
				require_once 'forget.php';
				break;
			//down.php お知らせ画面
			case 'done':
				require_once 'done.php';
				break;
			case 'pwlink':
				require_once 'pwlink.php';
				break;
			case 'pwlinkdo':
				require_once '../control/pwlinkC.php';
				break;
				
				
			//以上为跳转其他页面
			####################################################################################
			//以下为本页操作
				
			//本页
			default:
				if($page==""){
					if(isset($_GET["url"])&&$_GET["url"]!=""){
						$smarty->assign('url', $_GET["url"]);
					}
					$smarty->assign('BASE_URL', APP_URL);
					$smarty->assign('THEME', THEME);
					if(isMobile()){
						$smarty->display('mobile/Login/login.html');
					}else{
						$smarty->display('Login/login.html');
					}
						
				}elseif($page=="error"){
					//校验错误返回
					$smarty->assign('_error', "※ 邮箱地址或密码错误");
					$smarty->assign('BASE_URL', APP_URL);
					$smarty->assign('THEME', THEME);
					if(isMobile()){
						$smarty->display('mobile/Login/login.html');
					}else{
						$smarty->display('Login/login.html');
					}
					
				}else{
					//没有匹配参数，跳出错页面
					$smarty->assign('BASE_URL', APP_URL);
					$smarty->assign('THEME', THEME);
					$smarty->display('error.html');
				}				
		}
	}
		
		
	
	
?>