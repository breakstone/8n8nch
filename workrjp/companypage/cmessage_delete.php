<?php
	require_once '../_includes/functions.php';
	require_once '../_includes/SubPages.php';
	//getMessage_from($_SESSION['kiwa_userid']);
	//getMessage_to($_SESSION['kiwa_userid']);
		
	$message = getMessage($id);
	//删除信件的是发信人或者收信人，其他人不许删除
	if($message['message_from']==$_SESSION['kiwa_companyid']||$message['message_to']==$_SESSION['kiwa_companyid']){
		
		if(isset($_GET["flag"])&&$_GET["flag"]=="send"){
			
			pmessage_delete($id,"from_del_flg");
			header('Location:'.APP_URL.'companypage/cmessageSend/');
		}else{
			
			pmessage_delete($id,"del_flg");
			header('Location:'.APP_URL.'companypage/cmessage/');
		}
	}else{
		header('Location:'.APP_URL.'companypage/done/error/');
	}
?>