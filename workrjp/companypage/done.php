<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	
	
	switch ($id){
		case 'error':
			$smarty->assign('h1', "Error");
			$smarty->assign('message', "非常抱歉，由于系统错误，请您与管理中心联系");
			break;
		case 'addok':
			$smarty->assign('h1', "添加成功");
			$message = "求人发表成功，3秒后页面将自动跳转至求人管理！\n";
			$message .= "<script language=\"javascript\">setTimeout(function (){this.location.href = \"".APP_URL."companypage/findpeople/\" }, 3000);</script>";
			$smarty->assign('message', $message);
			break;
		case 'updateok':
			$smarty->assign('h1', "修改成功");
			$message = "求人信息修改成功，3秒后页面将自动跳转至求人管理！\n";
			$message .= "<script language=\"javascript\">setTimeout(function (){this.location.href = \"".APP_URL."companypage/findpeople/\" }, 3000);</script>";
			$smarty->assign('message', $message);
			break;
		case 'companyok':
			$smarty->assign('h1', "修改成功");
			$message = "企业简历修改成功，3秒后页面将自动跳转至企业简历管理！\n";
			$message .= "<script language=\"javascript\">setTimeout(function (){this.location.href = \"".APP_URL."companypage/\" }, 3000);</script>";
			$smarty->assign('message', $message);
			break;
		case 'remessage_ok':
			$smarty->assign('h1', "返信成功");
			$message = "返信成功，3秒后页面将自动跳转至信箱管理！\n";
			$message .= "<script language=\"javascript\">setTimeout(function (){this.location.href = \"".APP_URL."companypage/cmessage/\" }, 3000);</script>";
			$smarty->assign('message', $message);
			break;
		case 'message_send_ok':
			$smarty->assign('h1', "送信成功");
			$message = "送信成功！\n";
			$smarty->assign('message', $message);
			break;
		case 'message_send_no':
			$smarty->assign('h1', "送信失败");
			$message = "送信失败，请联系管理员！\n";
			$smarty->assign('message', $message);
			break;
		case 'jobregistok':
			$smarty->assign('h1', "投递简历成功");
			$message = "投递简历成功，3秒后页面将自动跳转至工作页面！\n";
			$message .= "<script language=\"javascript\">setTimeout(function (){this.location.href = \"".APP_URL."job/\" }, 3000);</script>";
			$smarty->assign('message', $message);
			break;
	}
	//固定引入参数
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	if(isMobile()){
		$smarty->display('mobile/Companypage/done.html');
	}else{
		$smarty->display('Companypage/done.html');
	}
	
?>