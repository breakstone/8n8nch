<?php
	###################################
	# パスワードを忘れの画面
	###################################
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	
	$ipd = getIP();
	$create_date = date("Y-m-d H:i:s");
	if(isset($_POST["baoming"])&&$_POST["baoming"]!=""){
		$name = cleanInput($_POST["name"]);
		$piyin = cleanInput($_POST["piyin"]);
		$sex = cleanInput($_POST["sex"]);
		$by = cleanInput($_POST["by"]);
		$bm = cleanInput($_POST["bm"]);
		$address = cleanInput($_POST["address"]);
		$tel = cleanInput($_POST["tel"]);
		$email = cleanInput($_POST["email"]);
		$qq = cleanInput($_POST["qq"]);
		$lianxi = cleanInput($_POST["lianxi"]);
		$xueli = cleanInput($_POST["xueli"]);
		$school = cleanInput($_POST["school"]);
		$sy = cleanInput($_POST["sy"]);
		$sm = cleanInput($_POST["sm"]);
		$now = cleanInput($_POST["now"]);
		$wanty = cleanInput($_POST["wanty"]);
		$studytime = cleanInput($_POST["studytime"]);
		$jpn = cleanInput($_POST["jpn"]);
		$jtest = cleanInput($_POST["jtest"]);
		$other = cleanInput($_POST["other"]);
		$guanxi = cleanInput($_POST["guanxi"]);
		$workwhat = cleanInput($_POST["workwhat"]);
		$shouru = cleanInput($_POST["shouru"]);
		$danbao = cleanInput($_POST["danbao"]);
		
		
		$sql="insert into lx_user(name,pinyin,sex,birthday,address,tel,email,lianxi,xueli,school,biye_time,now,wanty,jpn,jtest,other,guanxi,workwhat,shouru,danbao,ip,create_date)values
		('$name','$piyin','$sex','$by 年 $bm 月','$address','$tel','$email','$lianxi','$xueli','$school','$sy 年 $sm 月','$now','$wanty','$jpn','$jtest','$other','$guanxi','$workwhat','$shouru','$danbao','$ipd','$create_date')";
		
		if($db->Execute($sql)){
			header('Location:'.APP_URL.'s/signup/');
		}
	}
	
	
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	$smarty->display('S/signup.html');
?>