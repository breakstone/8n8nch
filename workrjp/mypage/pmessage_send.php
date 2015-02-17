<?php
	require_once '../_includes/functions.php';
	require_once '../_includes/SubPages.php';
	if($id!=""){
		$smarty->assign('toid', $id);
		//信件内容--回车处理
		$smarty->register_function('getname','getName');
		//固定引入参数
		//mypage页面信息查询
		$smarty->assign('THEME', THEME);
		$smarty->assign('BASE_URL', APP_URL);
		$smarty->assign('USERNAME', $_SESSION['kiwa_username']);
		$smarty->assign('USERID', $_SESSION['kiwa_userid']);
		//$smarty->display('Mypage/pmessage_send.html');
		if(isMobile()){
			$smarty->display('mobile/Mypage/pmessage_send.html');
		}else{
			$smarty->display('Mypage/pmessage_send.html');
		}
	}else{
		header('Location:'.APP_URL.'companypage/done/error/');
	}
?>