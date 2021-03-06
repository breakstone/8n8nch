<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	
	//发表回复
	if(isset($_POST['job_ls_id']) && isset($_POST['answer_content']) && ((isset($_SESSION['kiwa_username'])&&isset($_SESSION['kiwa_userid'])) || (isset($_SESSION['kiwa_companyname'])&&isset($_SESSION['kiwa_companyid'])))){
		//发表求职
		$job_id = cleanInput($_POST['job_ls_id']);
		
		global $db;
		$job_id = $db->real_escape_string($job_id);
		$answer_content = $_POST['answer_content'];
		
		if(isset($_SESSION['kiwa_userid'])&&$_SESSION['kiwa_userid']!=""){
			$answer_user_id = $_SESSION['kiwa_userid'];
			$answer_user_type = "user";
		}
		if(isset($_SESSION['kiwa_companyid'])&&$_SESSION['kiwa_companyid']!=""){
			$answer_user_id = $_SESSION['kiwa_companyid'];
			$answer_user_type = "company";
		}
		$create_date = date("Y-m-d H:i:s");
		
		$sql = "insert into dtb_job_ls_answer set job_ls_id = '$job_id',answer_user_id='$answer_user_id',answer_user_type='$answer_user_type',answer_content='$answer_content',create_date='$create_date'";
// 		echo 
		$return = $db->Execute($sql);
		if(!$return){
			if(!$return) echo "发表回复 失败！" ;exit();
		}else{
			$aid = $db->insert_id;
			$sql_inid = "select count(*) from dtb_job_ls_answer where job_ls_id = '$job_id' and del_flg = 0";
			$in_id = $db->QueryItem($sql_inid);
			
			//回复顶贴
			$now = date("Y-m-d H:i:s");
			$sql_update = "update dtb_job_ls set lastanswer_date='$now' where job_ls_id = '$job_id' and del_flg = 0";
			$db->Execute($sql_update);
			
			//页数   10是一页显示10条的意思
			$pid = ceil($in_id/10);
			//给发布者的信箱发送信息
			$sql_s = "select user_id from dtb_job_ls where job_ls_id = '$job_id'";
			$user = $db->QueryRow($sql_s);
			$message_to = $user["user_id"];
			if($answer_user_id != $user["user_id"]){
				$message_from = "000001";
				$message_title = "系统消息,有人回复您的工作！";
				$message_content = "用户您好，您发表的工作信息，已经有人回复！\r\n 内容: $answer_content \r\n  详细情况-> ".APP_URL."job/lsshow/$job_id/?pid=$pid#$aid";
				$now = date("Y-m-d H:i:s");
				$message_id = date("dHis").rand(1000,9999);
				$sql_m = "insert into dtb_message set message_id='$message_id', message_from='$message_from',message_to='$message_to',message_title='$message_title',message_content='$message_content',create_date='$now'";
				$db->Execute($sql_m);
			//	pointsDo($answer_user_id, $answer_user_type, 2);
			}
			header('Location:'.APP_URL.'job/lsshow/'.$_POST[job_ls_id].'/?pid='.$pid.'#'.$aid);
		}
	}else{
		//没有参数跳到错误页面
		header('Location:'.APP_URL.'job/done/error');
	}
?>