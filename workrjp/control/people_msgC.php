<?php
	// config
	if(!file_exists('./../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	
	require_once './../_config/config.php';
	require_once './../_includes/functions.php';
	
	//发表工作
	if(isset($_POST['people_id']) && isset($_POST['msg_content']) && ((isset($_SESSION['kiwa_username'])&&isset($_SESSION['kiwa_userid'])) || (isset($_SESSION['kiwa_companyname'])&&isset($_SESSION['kiwa_companyid'])))){
		//发表求职
		$people_id = cleanInput($_POST['people_id']);
		$msg_content = cleanInput($_POST['msg_content']);
		$type=$_POST['type'];
		
		global $db;
		$people_id = $db->real_escape_string($people_id);
		$msg_content = $db->real_escape_string($msg_content);
		
		if(isset($_SESSION['kiwa_userid'])&&$_SESSION['kiwa_userid']!=""){
			$msg_user_id = $_SESSION['kiwa_userid'];
			$msg_user_type = "user";
		}
		if(isset($_SESSION['kiwa_companyid'])&&$_SESSION['kiwa_companyid']!=""){
			$msg_user_id = $_SESSION['kiwa_companyid'];
			$msg_user_type = "company";
		}
		$create_date = date("Y-m-d H:i:s");
		
		$sql = "insert into dtb_people_msg set people_id = '$people_id',msg_user_id='$msg_user_id',msg_user_type='$msg_user_type',msg_content='$msg_content',create_date='$create_date'";
		$return = $db->Execute($sql);
		if(!$return){
			if(!$return) echo "回复/评价 失败！" ;exit();
		}else{
			if($msg_user_id != $people_id){
				//给发布者的信箱发送信息
				if($_REQUEST["re_id"]==""){
					$message_to = $people_id;
					$message_from = "000001";
					$message_title = "有人给您留言！";
					$message_content = "用户您好，有人在您的空间里给您留言！\r\n 内容: $msg_content \r\n  您的空间地址-> ".APP_URL."people/msg/".$type."_".$people_id."/";
					$now = date("Y-m-d H:i:s");
					$message_id = date("dHis").rand(1000,9999);
					$sql_m = "insert into dtb_message set message_id='$message_id', message_from='$message_from',message_to='$message_to',message_title='$message_title',message_content='$message_content',create_date='$now'";
					$db->Execute($sql_m);
				}else{
					$message_to = $_REQUEST["re_id"];
					$message_from = "000001";
					$message_title = "有人回复了您的留言！";
					$message_content = "用户您好，有人回复了您的留言！\r\n 内容: $msg_content \r\n  查看地址-> ".APP_URL."people/msg/".$type."_".$people_id."/";
					$now = date("Y-m-d H:i:s");
					$message_id = date("dHis").rand(1000,9999);
					$sql_m = "insert into dtb_message set message_id='$message_id', message_from='$message_from',message_to='$message_to',message_title='$message_title',message_content='$message_content',create_date='$now'";
					$db->Execute($sql_m);
				}
				$type = $_REQUEST["type"];
				//积分处理
				pointsDo($answer_user_id, $answer_user_type, 2);
			}
			header('Location:'.APP_URL.'people/msg/'.$type.'_'.$people_id.'/');
		}
	}else{
		//没有参数跳到错误页面
		header('Location:'.APP_URL.'live/done/error');
	}
?>