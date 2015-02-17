<?php
	require_once '../_includes/functions.php';
	require_once '../_includes/SubPages.php';
	//getMessage_from($_SESSION['kiwa_companyid']);
	//getMessage_to($_SESSION['kiwa_companyid']);
		
	$message = getMessage($id);
	//查看信件的是发信人或者收信人，其他人不许看
	if($message['message_from']==$_SESSION['kiwa_companyid']||$message['message_to']==$_SESSION['kiwa_companyid']){
		
		//收件刃打开信件--修改已读flag
		if($message['message_to']==$_SESSION['kiwa_companyid']){
			message_read_flag($id);
		}
		
		$smarty->assign('message', $message);
		//信件内容--回车处理
		$message_content = str_replace("\r\n","<br>",$message['message_content']);
		
		$message_content = preg_replace('/(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)/', '<A href="\\1\\2" target="_blank">\\1\\2</A>', $message_content);

		$message_content = htmlspecialchars_decode($message_content);
		
		$smarty->assign('message_content', $message_content);
		$smarty->register_function('getname','getName');
		//固定引入参数
		//mypage页面信息查询
		$smarty->assign('THEME', THEME);
		$smarty->assign('BASE_URL', APP_URL);
		$smarty->assign('USERNAME', $_SESSION['kiwa_companyname']);
		$smarty->assign('USERID', $_SESSION['kiwa_companyid']);
		if(isMobile()){
			$smarty->display('mobile/Companypage/cmessage_show.html');
		}else{
			$smarty->display('Companypage/cmessage_show.html');
		}
		
	}else{
		header('Location:'.APP_URL.'companypage/done/error/');
	}
?>