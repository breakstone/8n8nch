<?php
	require_once '_includes/functions.php';
	require_once '_includes/SubPages.php';
	//getMessage_from($_SESSION['kiwa_userid']);
	//getMessage_to($_SESSION['kiwa_userid']);
		
	$photo = getPhoto("Id=$id",0, 1);
	$type = $photo[0]['user_type'];
	$userid = $photo[0]['user_id'];
	//删除信件的是发信人或者收信人，其他人不许删除
	if($photo[0]['user_id']==$_SESSION['kiwa_userid']||$photo[0]['user_id']==$_SESSION['kiwa_companyid']){
		
		$sql = "update dtb_people_photo set del_flg = 1 where Id='$id'";
		$db->Execute($sql);
		
		header('Location:/photo/');
	}else{
		header('Location:'.APP_URL.'people/error/');
	}
?>