<?php
	require_once '../_includes/functions.php';
	require_once '../_includes/SubPages.php';
	if(isset($_POST["rem_id"])&&$_POST["rem_id"]!=""){
		
		$message = getMessage($_POST["rem_id"]);
		//返信的是发信人或者收信人，其他人不许返信
		if($message['message_from']==$_SESSION['kiwa_userid']||$message['message_to']==$_SESSION['kiwa_userid']){
			
$content = $_POST["re_message"]."\r\n"."\r\n".
"－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－\r\n
发信人: ".
	getName(array("name"=>"username","value"=>$message['message_from'])).
	getName(array("name"=>"company_name","value"=>$message['message_from']))."\r
发信时间: ".$message['create_date']."\r
To: ".
	getName(array("name"=>"username","value"=>$message['message_to'])).
	getName(array("name"=>"company_name","value"=>$message['message_to']))."\r
标题: ".$message['message_title']."\r\n\r\n".$message['message_content'];
			
			if($message['message_from'] == $_SESSION['kiwa_userid']){
				$message_to = $message['message_to'];
			}else{
				$message_to = $message['message_from'];
			}
			
			if(reMessage($_SESSION['kiwa_userid'],$message_to,$message['message_title'],$content,$_POST["rem_id"])){
				header('Location:'.APP_URL.'mypage/done/remessage_ok/');
			}else{
				header('Location:'.APP_URL.'mypage/done/error/');
			}
			
		}else{
			header('Location:'.APP_URL.'mypage/done/error/');
		}
	}else{
		header('Location:'.APP_URL.'mypage/done/error/');
	}
?>