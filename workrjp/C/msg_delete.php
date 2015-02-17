<?php
	require_once '_includes/functions.php';
	require_once '_includes/SubPages.php';
	//getMessage_from($_SESSION['kiwa_userid']);
	//getMessage_to($_SESSION['kiwa_userid']);
		
	$msg = getMsg("Id=$id",0, 1);
	$type = $_REQUEST["type"];
	$peopleid = $msg[0]["people_id"];
	//删除信件的是发信人或者收信人，其他人不许删除
	if($msg[0]['msg_user_id']==$_SESSION['kiwa_userid']||$msg[0]['msg_user_id']==$_SESSION['kiwa_companyid']){
		
		$sql = "update dtb_people_msg set del_flg = 1 where Id='$id'";
		$db->Execute($sql);
		
		header('Location:/msg/');
	}else{
		header('Location:'.APP_URL.'people/error/');
	}
?>