<?php
	/**パスワードを変更**/
	###################################
	# パスワードを変更画面
	###################################
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	
	
	//page为url的第一个参数，id为url第二个参数，extra为url第三个参数
	if($id!=""&&$extra!=""){
		
		$id = cleanInput($id);
		$extra = cleanInput($extra);
		//データベースを照合
		$row = pwlink($id,$extra);
		
		if($row){
			$smarty->assign('username', $row['name01']."　".$row['name02']." 様");
			$smarty->assign('user_id', $row['user_id']);
			
			$smarty->assign('BASE_URL', APP_URL);
			$smarty->assign('THEME', THEME);
			if(isMobile()){
				$smarty->display('mobile/Login/pwlink.html');
			}else{
				$smarty->display('Login/pwlink.html');
			}
	
		}else{
			//URLのキーはデータベースのデータより、違います　　エラー
			$smarty->assign("error", "不能更改。<br>未能成功的原因，可能有以下几种。<br><br>1.邮件中记载的URL·关键字已经过了有效期限。<br>2.您代开邮箱中的URL的时候，URL不正确");
			
			$smarty->assign('BASE_URL', APP_URL);
			$smarty->assign('THEME', THEME);
			$smarty->display('error.html');
		}
	}
?>