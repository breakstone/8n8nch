<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	
	
	switch ($id){
		case 'email_register_do':
			$smarty->assign('h1', "邮箱认证");
			$smarty->assign('message', "请查看您的邮箱，系统已经给您发送一封确认邮件！");
			break;
		case 'error':
			$smarty->assign('h1', "Error");
			$smarty->assign('message', "非常抱歉，由于系统错误，请您与管理中心联系");
			break;
		case 'karegist':
			//假登陆
			$smarty->assign('h1', "友情提示,注册成功");
			$smarty->assign('message', "
							<a href='".APP_URL."mypage/' style=';font-size:15px;color:#3A5FCD;'> -> 进入我的地盘</a>");
			$smarty->assign('personal', "<span style='font-size:15px;'>感谢您的注册！</span><br><span style='font-size:15px;color:red;'>您想找适合您的工作吗？您想让企业发现你，主动联系你吗？<br>
										只需完善个人简历，加入人才秀，系统就会自动匹配适合你的企业与工作，机会是留给有准备的人，赶快加入帮帮网人才秀吧！！！
										<br>
							<a href='".APP_URL."mypage/personal/' style='font-size:15px;color:#3A5FCD;'> -> 填写我的简历</a></span>&nbsp;&nbsp;&nbsp;<br>");
			break;
		case 'company_karegist':
			//$smarty->assign('img', "<div><img src=".APP_URL."_templates/".THEME."/img/companys2.png></div><br><center><a href='".APP_URL."requirement/'><img src=".APP_URL."_templates/".THEME."/img/mffburw_hover.gif></center></a>");
			$smarty->assign('h1', "企业注册成功");
			$smarty->assign('message', "感谢您的注册！<br>
							您已完成注册，但邮件还未认证,请您注意!<br>
							<a href='".APP_URL."companypage/'> -> 进入我的地盘</a>");
		
			break;
		case 'key':
			//邮件过来的链接处理
			$user_id = passport_decrypt($extra);
			$user_id = cleanInput($user_id);
			
			if(registOver($user_id)){
				//登陆成功
				$smarty->assign('h1', "友情提示");
				$smarty->assign('message', "您已经成功注册我们的网站<br>登录请点<a href='".APP_URL."login/'>这里</a>");
			}else{
				//登陆失败，没有找到用户或update失败
				$smarty->assign('h1', "Error");
				$smarty->assign('message', "URL不正确，或更新失败。");
			}
			break;
		
	}
	//固定引入参数
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	if(isMobile()){
		$smarty->display('mobile/Regist/done.html');
	}else{
		$smarty->display('Regist/done.html');
	}
	
?>