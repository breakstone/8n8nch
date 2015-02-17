<?php
// config
if(!file_exists('./../_config/config.php'))
{
	die('[index.php] _config/config.php not found');
}

require_once './../_config/config.php';
require_once './../_includes/functions.php';

if($_GET["email"] != ""){
	$email = $_GET["email"];
	$sql_u = "select * from dtb_user where email = '$email' and del_flg = 0";
	$row_u = $db->QueryRow($sql_u);
	
	$sql_c = "select * from dtb_companyuser where company_email='$email' and del_flg=0";
	$row_c = $db->QueryRow($sql_c);
	if($row_u){
		$name01 = $row_u["name01"];
		$name02 = $row_u["name02"];
		$user_id = $row_u["user_id"];
	}else{
		$name01 = $row_c["company_name"];
		$name02 = "";
		$user_id = $row_c["company_id"];
	}
	//导入邮件配置函数
	$mail = mailTo();
	$mail->AddAddress("$email", "");//收件人地址,格式是AddAddress("收件人email","收件人姓名")
	
	$mail->Subject = "帮帮网邮箱认证URL"; //邮件标题
	$mail->Body =
	$name01."　".$name02."
尊敬的会员，你好，本邮件是帮帮网信息平台向执行过注册手续的会员自动发送。
为了您的信息安全，请您点击一下URL连接完成最后的会员注册操作。
	
".APP_URL."regist/done/key/".passport_encrypt($user_id)."
	
如过您采取浏览器输入栏直接输入的方式，请您复制URL到浏览器的地址输入栏。
	
帮帮网运营担当"; //邮件内容
	
	if(!$mail->Send()){
		echo "邮件发送失败. <p>";
		echo "错误原因: " . $mail->ErrorInfo;
		exit;
	}else{
		header('Location:'.APP_URL.'regist/done/email_register_do/');
	}
}

?>