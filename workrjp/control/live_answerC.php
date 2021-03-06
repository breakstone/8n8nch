<?php
	// config
	if(!file_exists('./../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	
	require_once './../_config/config.php';
	require_once './../_includes/functions.php';
	
	//发表工作
	if(isset($_POST['live_id']) && isset($_POST['answer_content']) && ((isset($_SESSION['kiwa_username'])&&isset($_SESSION['kiwa_userid'])) || (isset($_SESSION['kiwa_companyname'])&&isset($_SESSION['kiwa_companyid'])))){
		//发表求职
		$live_id = cleanInput($_POST['live_id']);
		$answer_content = $_POST['answer_content'];
		
		global $db;
		$live_id = $db->real_escape_string($live_id);
		$answer_content = $answer_content;
		
		if(isset($_SESSION['kiwa_userid'])&&$_SESSION['kiwa_userid']!=""){
			$answer_user_id = $_SESSION['kiwa_userid'];
			$answer_user_type = "user";
		}
		if(isset($_SESSION['kiwa_companyid'])&&$_SESSION['kiwa_companyid']!=""){
			$answer_user_id = $_SESSION['kiwa_companyid'];
			$answer_user_type = "company";
		}
		$create_date = date("Y-m-d H:i:s");
		
		$sql = "insert into dtb_lives_answer set live_id = '$live_id',answer_user_id='$answer_user_id',answer_user_type='$answer_user_type',answer_content='$answer_content',create_date='$create_date'";
		$return = $db->Execute($sql);
		if(!$return){
			if(!$return) echo "发表回复 失败！" ;exit();
		}else{
			$aid = $db->insert_id;
			$sql_inid = "select count(*) from dtb_lives_answer where live_id = '$live_id' and del_flg = 0";
			$in_id = $db->QueryItem($sql_inid);
			//页数   10是一页显示10条的意思
			$pid = ceil($in_id/10);
			
			//给发布者的信箱发送信息
			$sql_s = "select user_id from dtb_lives where live_id = '$live_id'";
			$user = $db->QueryRow($sql_s);
			$message_to = $user["user_id"];
			if($answer_user_id!=$user["user_id"]){
				
				$message_from = "000001";
				$message_title = "系统消息,有人回复您的需求！";
				$message_content = "用户您好，您发表的生活需求，已经有人回复！\r\n 内容: $answer_content \r\n  详细情况-> ".APP_URL."live/show/$live_id/?pid=$pid#$aid";
				$now = date("Y-m-d H:i:s");
				$message_id = date("dHis").rand(1000,9999);
				$sql_m = "insert into dtb_message set message_id='$message_id', message_from='$message_from',message_to='$message_to',message_title='$message_title',message_content='$message_content',create_date='$now'";
				$db->Execute($sql_m);
			}
			//积分处理
			pointsDo($answer_user_id, $answer_user_type, 2);
			header('Location:'.APP_URL.'live/show/'.$_POST[live_id].'/?pid='.$pid.'#'.$aid);
		}
	}else{
		//没有参数跳到错误页面
		header('Location:'.APP_URL.'live/done/error');
	}
?>