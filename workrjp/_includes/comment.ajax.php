<?php
if($_GET['ajax']==""){
	echo "warning:your ip is deny!";
}else{
	require_once '../_config/config.php';
	require_once 'functions.php';
	header('Content-type: text/xml;charset=utf-8');
	header("cache-control:no-cache,must-revalidate");
	echo "<?xml version='1.0' encoding='utf-8'?>";
	echo "<response>";
	global $db;
	
	if($_GET['ajax']=="comment"){//注册邮箱ajax校验
		//传入参数处理
		$comment_tent = cleanInput($_GET['comment_tent']);
		$comment_tent = $db->real_escape_string($comment_tent);
		$now = date("Y-m-d H:i:s");
		if(isset($_SESSION["kiwa_userid"])){
			$userid = $_SESSION["kiwa_userid"];
			$username = $_SESSION["kiwa_username"];
		}
		if(isset($_SESSION["kiwa_companyid"])){
			$userid = $_SESSION["kiwa_companyid"];
			$username = $_SESSION["kiwa_companyname"];
		}
		$sql="insert into dtb_comment(user_id,user_name,user_comment,create_date)values('$userid','$username','$comment_tent','$now')";
		
		if($db->Execute($sql)){
			echo "<cok>1</cok>";
		}else{
			echo "<cok>0</cok>";
		}
	}
	echo "</response>";
}

?>