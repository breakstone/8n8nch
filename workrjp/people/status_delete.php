<?php
	require_once '../_includes/functions.php';
	require_once '../_includes/SubPages.php';
	//getMessage_from($_SESSION['kiwa_userid']);
	//getMessage_to($_SESSION['kiwa_userid']);
		
	$sql = "select user_id,user_type from dtb_status where del_flg = 0 and status_id=".$id;
	$user=$db->QueryRow($sql);
	//删除信件的是发信人或者收信人，其他人不许删除
	if($user['user_id']==$_SESSION['kiwa_userid']||$user['user_id']==$_SESSION['kiwa_companyid']){
		
		$sql = "update dtb_status set del_flg = 1 where status_id='$id'";
		$db->Execute($sql);
		$sql = "update dtb_log set del_flg = 1 where type_id='$id'";
		$db->Execute($sql);
		header('Location:'.APP_URL.'people/show/'.$user['user_type'].'_'.$user['user_id'].'/');
	}else{
		header('Location:'.APP_URL.'people/error/');
	}
?>