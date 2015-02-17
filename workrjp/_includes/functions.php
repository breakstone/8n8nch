<?php
##################################################################
#
#	★以下为各个control层使用的函数
# 
##################################################################
##########################################
# login.php データベースを照合
##########################################
function loginC($email,$password){
	global $db;
	//核对数据库---成功:set_Session,失败:返回
	$email = $db->real_escape_string($email);
	$password = $db->real_escape_string($password);

	//密码加密
	$password = md5($password);
	// status : 登陆flag,0:假登陆1:真登陆   del_flg : 删除flag
	$sql_user = "select user_id,name01,name02,status,login_date
					from `dtb_user`
					where `email` = '$email'
					and `password` = '$password'
					and `del_flg` = 0";
	
	$sql_company = "select company_id,company_name,status,login_date 
						from `dtb_companyuser` 
						where `company_email` = '$email' 
						and `password` = '$password' 
						and `del_flg` = 0";
	$ipd = getIP();
	if($db->QueryRow($sql_user)){//★判断个人
		$array = $db->QueryRow($sql_user);
		//积分处理
		$today = date("Y-m-d");
		if($array['login_date'] != $today){
			pointsDo($array['user_id'], "user", 10);
			$sql_u="update dtb_user set login_date='$today',ip='$ipd' where user_id='$array[user_id]'";
			$db->Execute($sql_u);
		}
		
		if($array['status']!=1){//判断是否假登陆
			$_SESSION['kiwa_userid'] = $array['user_id'];
			$_SESSION['kiwa_username'] = $array['name01']." ".$array['name02'];
			//return "user_false";
			return "user_ok";
		}else{
			//设定 Session---用户名，用户ID
			$_SESSION['kiwa_userid'] = $array['user_id'];
			$_SESSION['kiwa_username'] = $array['name01']." ".$array['name02'];
			return "user_ok";
		}
		
	}elseif($db->QueryRow($sql_company)){//★判断企业
		$array = $db->QueryRow($sql_company);
		//积分处理
		$today = date("Y-m-d");
		if($array['login_date'] != $today){
			pointsDo($array['company_id'], "company", 10);
			$sql_u="update dtb_companyuser set login_date='$today',ip='$ipd' where company_id='$array[user_id]'";
			$db->Execute($sql_u);
		}

		if($array['status']!=1){//判断是否假登陆
			$_SESSION['kiwa_companyid'] = $array['company_id'];
			$_SESSION['kiwa_companyname'] = $array['company_name'];
			//return "company_false";
			return "company_ok";
		}else{
			//设定 Session---用户名，用户ID
			$_SESSION['kiwa_companyid'] = $array['company_id'];
			$_SESSION['kiwa_companyname'] = $array['company_name'];
			return "company_ok";
		}
	}else{
		return false;
	}
}

##########################################
# forget.php パスワードを忘れ　メールを送信
##########################################
function forgetC($email,$URL){
	//根据email查询出密码
	global $db;

	// del_flg:删除flag
	$sql = "select name01,name02,password,user_id
	from dtb_user
	where email = '$email'
	and del_flg = 0";
	
	if($db->QueryRow($sql)){
		$Row = $db->QueryRow($sql);
		//导入邮件配置函数
		$mail = mailTo();
		$mail->AddAddress("$email", "");//收件人地址,格式是AddAddress("收件人email","收件人姓名")

		$mail->Subject = "パスワードの再通知"; //邮件标题
		$mail->Body = "
$Row[name01]　$Row[name02] 
尊敬的会员，您的找回密码请求已处理，
请点击以下URL连接打开画面，按照指示继续下一步手续

".$URL."login/pwlink/".$Row[user_id]."/".$Row[password]."

如过您采取浏览器输入栏直接输入的方式，请您复制URL到浏览器的地址输入栏。

帮帮网运营担当"; //邮件内容

		if(!$mail->Send()){
			echo "邮件发送失败. <p>";
			echo "错误原因: " . $mail->ErrorInfo;
			exit;
		}
		return true;
	}else{
		$sql = "select company_name,password,company_id
			from dtb_user
			where company_email = '$email'
			and del_flg = 0";
			if($db->QueryRow($sql)){
				$Row = $db->QueryRow($sql);
				//导入邮件配置函数
				$mail = mailTo();
				$mail->AddAddress("$email", "");//收件人地址,格式是AddAddress("收件人email","收件人姓名")
			
				$mail->Subject = "パスワードの再通知"; //邮件标题
				$mail->Body = "
$Row[company_name]　
尊敬的会员，您的找回密码请求已处理，
请点击以下URL连接打开画面，按照指示继续下一步手续
		
".$URL."login/pwlink/".$Row[company_id]."/".$Row[password]."
		
如过您采取浏览器输入栏直接输入的方式，请您复制URL到浏览器的地址输入栏。
		
帮帮网运营担当"; //邮件内容
			
				if(!$mail->Send()){
					echo "邮件发送失败. <p>";
					echo "错误原因: " . $mail->ErrorInfo;
					exit;
				}
				return true;
		}else{
			return false;
		}
	}

}

##########################################
# pwlink.php パスワードを変更
##########################################
function pwlinkC($user_id,$password){
	global $db;
	//参数处理
	$user_id = $db->real_escape_string($user_id);
	$password = $db->real_escape_string($password);
	$password = md5($password);
	$date = date("Y-m-d H:m:s");
	
	$sql="update dtb_user
			set `password` = '$password',`update_date` = '$date'
			where `user_id` = '$user_id'";

	if($db->Execute($sql)){
		return true;
	}else{
		return false;
	}
}

##########################################
# regist.php 个人注册
##########################################
function registC($name01,$name02,$kana01,$kana02,$email,$password,$URL){
	global $db;
	$name01 = $db->real_escape_string($name01);
	$name02 = $db->real_escape_string($name02);
	$kana01 = $db->real_escape_string($kana01);
	$kana02 = $db->real_escape_string($kana02);
	$email = $db->real_escape_string($email);
	$password = $db->real_escape_string($password);
	
	$user_id = date("dHis").rand(1000,9999);
	$create_date = date("Y-m-d H:i:s");
	$password = md5($password);
	$ipd = getIP();
	
	$sql_see = "select create_date from dtb_user where ip='$ipd' order by create_date desc";
	$cd = $db->QueryItem($sql_see);
	if($cd==""||strtotime(date("Y-m-d H:i:s"))-strtotime($cd) > 600){
		$sql = "insert into dtb_user
		(user_id,name01,name02,points,kana01,kana02,email,password,status,user_photo_url,create_date,ip)
		values
		('$user_id','$name01','$name02',50,'$kana01','$kana02','$email','$password',0,'upload/img/1.png','$create_date','$ipd')";
	
		if($db->Execute($sql)){
			//登陆后存入session
			$_SESSION['kiwa_userid'] = $user_id;
			$_SESSION['kiwa_username'] = $name01." ".$name02;
			// 存入成功，发邮件
			// 导入邮件配置函数
	// 		$mail = mailTo();
	// 		$mail->AddAddress("$email", "");//收件人地址,格式是AddAddress("收件人email","收件人姓名")
	
	// 		$mail->Subject = "会员登陆URL"; //邮件标题
	// 		$mail->Body =
	// 		$name01."　".$name02." 
	// 尊敬的会员，你好，本邮件是帮帮网信息平台向执行过注册手续的会员自动发送。
	// 为了您的信息安全，请您点击一下URL连接完成最后的会员注册操作。
	
	// ".$URL."regist/done/key/".passport_encrypt($user_id)."
	
	// 如过您采取浏览器输入栏直接输入的方式，请您复制URL到浏览器的地址输入栏。
	
	// 帮帮网运营担当"; //邮件内容
	
	// 		if(!$mail->Send()){
	// 			echo "邮件发送失败. <p>";
	// 			echo "错误原因: " . $mail->ErrorInfo;
	// 			exit;
	// 		}
			
			//发送站内短信
			$message_title="系统消息";
			$message_content="尊敬的用户，您好！\r\n欢迎使用帮帮网平台！\r\n此为系统自动送信，请勿返信，谢谢合作！";
			message_send_do("000001",$user_id,$message_title,$message_content);
			
			return true;
		}else{
			return false;
		}
	}else{
		echo "每10分钟只能注册一个用户！";
	}
}

##########################################
# regist.php 企业注册
##########################################
function companyregistC($company_name,$company_manager,$foundation_date,$company_money,$company_url,$zip01,$zip02,$add_pref,$addr01,$addr02,$company_form,$tel01,$tel02,$tel03,$fax01,$fax02,$fax03,$company_email,$password,$areaid,$pref,$kinds,$kindsname,$types_str,$typesname_str,$skills_str,$ensn_str,$eki_str){
	global $db;
	$company_name = $db->real_escape_string($company_name);
	$company_manager = $db->real_escape_string($company_manager);
	$foundation_date = $db->real_escape_string($foundation_date);
	$company_money = $db->real_escape_string($company_money);
	$company_url = $db->real_escape_string($company_url);
	$zip01 = $db->real_escape_string($zip01);
	$zip02 = $db->real_escape_string($zip02);
	$pref_cd = $db->real_escape_string($pref);
	$addr01 = $db->real_escape_string($addr01);
	$addr02 = $db->real_escape_string($addr02);
	
	$areaid = $db->real_escape_string($areaid);
	$pref = $db->real_escape_string($add_pref);
	$kinds = $db->real_escape_string($kinds);
	$kindsname = $db->real_escape_string($kindsname);
	$types_str = $db->real_escape_string($types_str);
	$typesname_str = $db->real_escape_string($typesname_str);
	$skills_str = $db->real_escape_string($skills_str);
	$ensn_str = $db->real_escape_string($ensn_str);
	$eki_str = $db->real_escape_string($eki_str);
	
	$tel01 = $db->real_escape_string($tel01);
	$tel02 = $db->real_escape_string($tel02);
	$tel03 = $db->real_escape_string($tel03);
	$fax01 = $db->real_escape_string($fax01);
	$fax02 = $db->real_escape_string($fax02);
	$fax03 = $db->real_escape_string($fax03);
	$company_email = $db->real_escape_string($company_email);
	$password = $db->real_escape_string($password);
	
	$company_id = date("dHis").rand(1000,9999);
	$create_date = date("Y-m-d H:i:s");
	$password = md5($password);
	$company_form = implode("_",$company_form);
	
	if($addr01!=""){
		$info_flg=1;
	}else{
		$info_flg=0;
	}
	$ipd = getIP();
	$sql_see = "select create_date from dtb_companyuser where ip='$ipd' order by create_date desc";
	$cd = $db->QueryItem($sql_see);
	
	//if($cd==""||strtotime(date("Y-m-d H:i:s"))-strtotime($cd) > 600){
	$sql = "insert into dtb_companyuser
	(company_id,company_name,company_manager,points,zip01,zip02,pref,addr01,addr02,tel01,tel02,tel03,fax01,fax02,fax03,company_form,foundation_date,company_money,company_email,password,company_url,status,create_date,skill,kindsID,kindsname,typesID,typesname,area_cd,pref_cd,line_num,station_cd,info_flg,ip)
	values
	('$company_id','$company_name','$company_manager',50,'$zip01','$zip02','$pref','$addr01','$addr02','$tel01','$tel02','$tel03','$fax01','$fax02','$fax03','$company_form','$foundation_date','$company_money','$company_email','$password','$company_url',0,'$create_date','$skills_str','$kinds','$kindsname','$types_str','$typesname_str','$areaid','$pref_cd','$ensn_str','$eki_str',$info_flg,'$ipd')";
	
	if($db->Execute($sql)){
		//登陆后存入session
		$_SESSION['kiwa_companyid'] = $company_id;
		$_SESSION['kiwa_companyname'] = $company_name;
		//发送站内短信
		$message_title="系统消息";
		$message_content="尊敬的用户，您好！\r\n欢迎使用帮帮网平台！\r\n此为系统自动送信，请勿返信，谢谢合作！";
		message_send_do("000001",$company_id,$message_title,$message_content);
		
		//积分处理
		if($info_flg==1){
			pointsDo($company_id, "company", 50);
		}
		
		return true;
	}else{
		return false;
	}
	//}else{
	//	echo "每10分钟只能注册一个用户！";
	//}
}

##########################################
# findjob_add.php 发表求职
##########################################
function findjob_add($user_id,$name01,$birth,$people_title,$kindsID,$kindsname,$typesID,$typesname,$contents,$employ_mothod,$employ_mothod_name,$area_cd,$pref_cd,$pref_name,$line_num,$station_cd,$station_name,$hope_date,$hope_date_name,$moneyid,$money_name,$educationid,$educationname,$nowstatus,$accept_mail){
	global $db;
	$people_id = date("is").rand(1000,9999);
	$user_id = $db->real_escape_string($user_id);
	$name01 = $db->real_escape_string($name01);
	$birth = $db->real_escape_string($birth);
	$people_title = $db->real_escape_string($people_title);
	$kindsID = $db->real_escape_string($kindsID);
	$kindsname = $db->real_escape_string($kindsname);
	$typesID = $db->real_escape_string($typesID);
	$typesname = $db->real_escape_string($typesname);
	$contents = $db->real_escape_string($contents);
	$employ_mothod = $db->real_escape_string($employ_mothod);
	$employ_mothod_name = $db->real_escape_string($employ_mothod_name);
	$area_cd = $db->real_escape_string($area_cd);
	$pref_cd = $db->real_escape_string($pref_cd);
	$pref_name = $db->real_escape_string($pref_name);
	$line_num = $db->real_escape_string($line_num);
	$station_cd = $db->real_escape_string($station_cd);
	$station_name = $db->real_escape_string($station_name);
	$hope_date = $db->real_escape_string($hope_date);
	$hope_date_name = $db->real_escape_string($hope_date_name);
	$moneyid = $db->real_escape_string($moneyid);
	$money_name = $db->real_escape_string($money_name);
	$educationid = $db->real_escape_string($educationid);
	$educationname = $db->real_escape_string($educationname);
	$nowstatus = $db->real_escape_string($nowstatus);
	$accept_mail = $db->real_escape_string($accept_mail);
	$now = date("Y-m-d H:i:s");
	$sql = "insert into dtb_peoples set people_id = '$people_id',user_id = '$user_id',name01='$name01',	birth='$birth',
			people_title='$people_title',kindsID='$kindsID',kindsname='$kindsname',typesID='$typesID',typesname='$typesname',
			contents='$contents',employed_method='$employ_mothod',employed_method_name='$employ_mothod_name',area_cd='$area_cd',pref_cd='$pref_cd',pref_name='$pref_name',line_num='$line_num',station_cd='$station_cd',station_name='$station_name',
			hope_date='$hope_date',hope_date_name='$hope_date_name',moneyid='$moneyid',money_name='$money_name',eductionid='$educationid',eductionname='$educationname',nowstatus=$nowstatus,
			accept_mail='$accept_mail',create_date='$now'";
	if($db->Execute($sql)){
		return true;
	}else{
		return false;
	}
}

##########################################
# findjob_update.php 修改求职信息
##########################################
function findjob_update($people_id,$user_id,$name01,$birth,$people_title,$kindsID,$kindsname,$typesID,$typesname,$contents,$employ_mothod,$employ_mothod_name,$area_cd,$pref_cd,$pref_name,$line_num,$station_cd,$station_name,$hope_date,$hope_date_name,$moneyid,$money_name,$educationid,$educationname,$nowstatus,$accept_mail){
	global $db;
	$people_id = $db->real_escape_string($people_id);
	$user_id = $db->real_escape_string($user_id);
	$name01 = $db->real_escape_string($name01);
	$birth = $db->real_escape_string($birth);
	$people_title = $db->real_escape_string($people_title);
	$kindsID = $db->real_escape_string($kindsID);
	$kindsname = $db->real_escape_string($kindsname);
	$typesID = $db->real_escape_string($typesID);
	$typesname = $db->real_escape_string($typesname);
	$contents = $db->real_escape_string($contents);
	$employ_mothod = $db->real_escape_string($employ_mothod);
	$employ_mothod_name = $db->real_escape_string($employ_mothod_name);
	$area_cd = $db->real_escape_string($area_cd);
	$pref_cd = $db->real_escape_string($pref_cd);
	$pref_name = $db->real_escape_string($pref_name);
	$line_num = $db->real_escape_string($line_num);
	$station_cd = $db->real_escape_string($station_cd);
	$station_name = $db->real_escape_string($station_name);
	$hope_date = $db->real_escape_string($hope_date);
	$hope_date_name = $db->real_escape_string($hope_date_name);
	$moneyid = $db->real_escape_string($moneyid);
	$money_name = $db->real_escape_string($money_name);
	$educationid = $db->real_escape_string($educationid);
	$educationname = $db->real_escape_string($educationname);
	$nowstatus = $db->real_escape_string($nowstatus);
	$accept_mail = $db->real_escape_string($accept_mail);
	$now = date("Y-m-d H:i:s");
	$sql = "update dtb_peoples set name01='$name01',birth='$birth',
			people_title='$people_title',kindsID='$kindsID',kindsname='$kindsname',typesID='$typesID',typesname='$typesname',
			contents='$contents',employed_method='$employ_mothod',employed_method_name='$employ_mothod_name',area_cd='$area_cd',pref_cd='$pref_cd',pref_name='$pref_name',line_num='$line_num',station_cd='$station_cd',station_name='$station_name',
			hope_date='$hope_date',hope_date_name='$hope_date_name',moneyid='$moneyid',money_name='$money_name',eductionid='$educationid',eductionname='$educationname',nowstatus=$nowstatus,
			accept_mail='$accept_mail',update_date='$now' where people_id = '$people_id' and user_id = '$user_id' and del_flg = 0";
	if($db->Execute($sql)){
		return true;
	}else{
		return false;
	}
}

##########################################
# findjob_delete.php 删除求职信息
##########################################
function findjob_delete($people_id,$user_id){
	global $db;
	$people_id = cleanInput($people_id);
	$people_id = $db->real_escape_string($people_id);
	$user_id = cleanInput($user_id);
	$user_id = $db->real_escape_string($user_id);
	
	$sql = "update dtb_peoples set del_flg = 1 where people_id='$people_id' and user_id = '$user_id'";
	if($db->Execute($sql)){
		return true;
	}else{
		return false;
	}
}

##########################################
# findjob_add.php 个人简历更新
##########################################
function user_update($user_id,$zip01,$zip02,$pref,$addr01,$addr02,$eki,$email,$per_status,$tel01,$tel02,$tel03,$tel_flag,$sex,$birth,$zhuanye,$last_education,$job_experiencetimes,$job_experience,$job_nowstatus,$zige,$mypr){
	global $db;
	$user_id = $db->real_escape_string($user_id);
	$zip01 = $db->real_escape_string($zip01);
	$zip02 = $db->real_escape_string($zip02);
	$pref = $db->real_escape_string($pref);
	$addr01 = $db->real_escape_string($addr01);
	$addr02 = $db->real_escape_string($addr02);
	$eki = $db->real_escape_string($eki);
	$email = $db->real_escape_string($email);
	$per_status = $db->real_escape_string($per_status);
	$tel01 = $db->real_escape_string($tel01);
	$tel02 = $db->real_escape_string($tel02);
	$tel03 = $db->real_escape_string($tel03);
	$tel_flag = $db->real_escape_string($tel_flag);
	$sex = $db->real_escape_string($sex);
	$birth = $db->real_escape_string($birth);
	$zhuanye = $db->real_escape_string($zhuanye);
	$last_education = $db->real_escape_string($last_education);
	$job_experiencetimes = $db->real_escape_string($job_experiencetimes);
	$job_experience = $db->real_escape_string($job_experience);
	$job_nowstatus = $db->real_escape_string($job_nowstatus);
	$zige = $db->real_escape_string($zige);
	$mypr = $db->real_escape_string($mypr);
	
	$now = date("Y-m-d H:i:s");
	$sql = "update dtb_user set zip01='$zip01',zip02='$zip02',pref='$pref',addr01='$addr01',addr02='$addr02',eki='$eki',email='$email',per_status=$per_status,
			tel01='$tel01',tel02='$tel02',tel03='$tel03',tel_flag=$tel_flag,sex=$sex,birth='$birth',zhuanye='$zhuanye',last_education='$last_education',
			job_experiencetimes='$job_experiencetimes',job_experience='$job_experience',job_nowstatus=$job_nowstatus,zige='$zige',mypr='$mypr',update_date='$now',info_flg=1 
			where user_id='$user_id' and del_flg = 0";
	
	if($db->Execute($sql)){
		return true;
	}else{
		return false;
	}
}

##########################################
# personal.php 个人简历更新
##########################################
function personal_update($user_id,$name01,$name02,$zip01,$zip02,$add_pref,$addr01,$addr02,$eki,$email,$password,$per_status,$tel01,$tel02,$tel03,$tel_flag,$sex,$birth,$zhuanye,$last_education,$job_experiencetimes,$job_experience,$job_nowstatus,$zige,$mypr,$areaid,$pref,$kinds,$types_str,$skills_str,$ensn_str,$eki_str,$kindsname,$typesname_str,$join,$day){
	global $db;
	$user_id = $db->real_escape_string($user_id);
	$name01 = $db->real_escape_string($name01);
	$name02 = $db->real_escape_string($name02);
	$zip01 = $db->real_escape_string($zip01);
	$zip02 = $db->real_escape_string($zip02);
	$add_pref = $db->real_escape_string($add_pref);
	$addr01 = $db->real_escape_string($addr01);
	$addr02 = $db->real_escape_string($addr02);
	$eki = $db->real_escape_string($eki);
	$email = $db->real_escape_string($email);
	$per_status = $db->real_escape_string($per_status);
	$tel01 = $db->real_escape_string($tel01);
	$tel02 = $db->real_escape_string($tel02);
	$tel03 = $db->real_escape_string($tel03);
	$tel_flag = $db->real_escape_string($tel_flag);
	$sex = $db->real_escape_string($sex);
	$birth = $db->real_escape_string($birth);
	$zhuanye = $db->real_escape_string($zhuanye);
	$last_education = $db->real_escape_string($last_education);
	$job_experiencetimes = $db->real_escape_string($job_experiencetimes);
	$job_experience = $db->real_escape_string($job_experience);
	$job_nowstatus = $db->real_escape_string($job_nowstatus);
	$zige = $db->real_escape_string($zige);
	$mypr = $db->real_escape_string($mypr);
	$day = $db->real_escape_string($day);
	$areaid = $db->real_escape_string($areaid);
	$pref = $db->real_escape_string($pref);
	$kinds = $db->real_escape_string($kinds);
	$types_str = $db->real_escape_string($types_str);
	$skills_str = $db->real_escape_string($skills_str);
	$ensn_str = $db->real_escape_string($ensn_str);
	$eki_str = $db->real_escape_string($eki_str);
	$kindsname = $db->real_escape_string($kindsname);
	$typesname_str = $db->real_escape_string($typesname_str);
	//积分处理
	$sql_s = "select info_flg,user_photo_url from dtb_user where user_id='$user_id'";
	$row = $db->QueryRow($sql_s);
	if($row["info_flg"] == 0){
		pointsDo($user_id, "user", 50);
	}
	//默认头像
	if($sex == 2&&$row["user_photo_url"]=="upload/img/1.png"){
		$photo = "upload/img/2.png";
		$sqlp = ",user_photo_url='$photo' ";
	}else{
		$sqlp = "";
	}
	
	//对邮件,密码进行判断
	$sqlsee = "select password from dtb_user where user_id = '$user_id' and del_flg = 0";
	$row = $db->QueryRow($sqlsee);
	if($row['password'] == $password){
		$password =$row['password'];
	}else{
		$password = md5($password);
	}
	
	$now = date("Y-m-d H:i:s");
	$sql = "update dtb_user set name01='$name01',name02='$name02',zip01='$zip01',zip02='$zip02',pref='$add_pref',addr01='$addr01',addr02='$addr02',eki='$eki',email='$email',password='$password',per_status=$per_status,
			tel01='$tel01',tel02='$tel02',tel03='$tel03',tel_flag=$tel_flag,sex=$sex,birth='$birth',zhuanye='$zhuanye',last_education='$last_education',want_day='$day',
			job_experiencetimes='$job_experiencetimes',job_experience='$job_experience',job_nowstatus=$job_nowstatus,zige='$zige',mypr='$mypr',update_date='$now',
			skill='$skills_str',kindsID='$kinds',kindsname='$kindsname',typesID='$types_str',typesname='$typesname_str',area_cd='$areaid',pref_cd='$pref',line_num='$ensn_str',station_cd='$eki_str',info_flg=$join $sqlp 
			where user_id='$user_id' and del_flg = 0";
	
	if($db->Execute($sql)){
		$message_title="推荐工作，尽在【我的地盘】";
		$message_content="尊敬的用户，您好！\r\n欢迎使用帮帮网平台！\r\n请您关注【我的地盘】，这里可能有您适合的工作信息！\r\n
				详情请点击 -> http://8n8n.co.jp/mypage/";
		
		message_send_do("000001",$user_id,$message_title,$message_content);
		return true;
	}else{
		return false;
	}
}

##########################################
# favorite_delete.php 删除收藏工作
##########################################
function favorite_delete($id,$user_id){
	global $db;
	$id = cleanInput($id);
	$id = $db->real_escape_string($id);
	
	$sql = "update dtb_favorite_list set del_flg = 1 where Id = $id and user_id = '$user_id'";
	if($db->Execute($sql)){
		return true;
	}else{
		return false;
	}
}

##########################################
# 企业 删除收藏People
##########################################
function companyfavorite_delete($id,$company_id){
	global $db;
	$id = cleanInput($id);
	$id = $db->real_escape_string($id);
	
	$sql = "update dtb_favorite_list set del_flg = 1 where Id = $id and user_id = '$company_id'";
	if($db->Execute($sql)){
		return true;
	}else{
		return false;
	}
}

##########################################
# jobregist.php 提交个人简历
##########################################
function jobregist($user_id,$job_id,$job_title,$company_id,$company_name,$toCompany_pr){
// 	global $db;
// 	$user_id = cleanInput($user_id);
// 	$user_id = $db->real_escape_string($user_id);
// 	$job_id = cleanInput($job_id);
// 	$job_id = $db->real_escape_string($job_id);
// 	$job_title = cleanInput($job_title);
// 	$job_title = $db->real_escape_string($job_title);
// 	$company_id = cleanInput($company_id);
// 	$company_id = $db->real_escape_string($company_id);
// 	$company_name = cleanInput($company_name);
// 	$company_name = $db->real_escape_string($company_name);
// 	$toCompany_pr = cleanInput($toCompany_pr);
// 	$toCompany_pr = $db->real_escape_string($toCompany_pr);
// 	$now = date("Y-m-d H:i:s");
// 	$sqlsee = "select Id from dtb_job_lvli where del_flg = 0 and user_id = '$user_id' and job_id = '$job_id'";
// 	$item = $db->QueryItem($sqlsee);
// 	if($item){
// 		$sql = "update dtb_job_lvli set toCompany_pr='$toCompany_pr',create_date='$now' where Id = $item and user_id = '$user_id' and job_id = '$job_id' and del_flg = 0";
// 	}else{
// 		$sql = "insert into dtb_job_lvli set user_id = '$user_id',job_id='$job_id',job_title='$job_title',company_id='$company_id',company_name='$company_name',toCompany_pr='$toCompany_pr',create_date='$now'";
// 	}
// 	if($db->Execute($sql)){
// 		return true;
// 	}else{
// 		return false;
// 	}
	
	$message_title = "我要应聘 《 $job_title 》 这个工作！";
	$message_content = $toCompany_pr;
	
	message_send_do($user_id, $company_id, $message_title, $message_content);
	
	return ture;
}

##########################################
# findpeople_add.php 提交求人信息
##########################################
function findpeople_add($kiwa_companyid,$kiwa_companyname,$usertype,$now,$job_title,$kindsID,$kindsname,$typesID,$typesname,$contents,$employ_mothod,$employ_mothod_name,$area_cd,$pref_cd,$pref_name,$line_num,$station_cd,$station_name,$hope_date,$hope_date_name,$moneyid,$money_name,$requirements,$lianxi,$numbers,$over_date,$condition_id,$condition_name,$zz_name,$w_d){
	global $db;
	$job_id = date("is").rand(1000,9999);
	$kiwa_companyid = $db->real_escape_string($kiwa_companyid);
	$kiwa_companyname = $db->real_escape_string($kiwa_companyname);
	$kindsID = $db->real_escape_string($kindsID);
	$kindsname = $db->real_escape_string($kindsname);
	$typesID = $db->real_escape_string($typesID);
	$typesname = $db->real_escape_string($typesname);
	$contents = $db->real_escape_string($contents);
	$employ_mothod = $db->real_escape_string($employ_mothod);
	$employ_mothod_name = $db->real_escape_string($employ_mothod_name);
	$area_cd = $db->real_escape_string($area_cd);
	$pref_cd = $db->real_escape_string($pref_cd);
	$pref_name = $db->real_escape_string($pref_name);
	$line_num = $db->real_escape_string($line_num);
	$station_cd = $db->real_escape_string($station_cd);
	$station_name = $db->real_escape_string($station_name);
	
	$zz_name = $db->real_escape_string($zz_name);
	$w_d = $db->real_escape_string($w_d);
	
	$hope_date = $db->real_escape_string($hope_date);
	$hope_date_name = $db->real_escape_string($hope_date_name);
	$moneyid = $db->real_escape_string($moneyid);
	$money_name = $db->real_escape_string($money_name);
	$requirements = $db->real_escape_string($requirements);
	$lianxi = $db->real_escape_string($lianxi);
	$numbers = $db->real_escape_string($numbers);
	$condition_id = $db->real_escape_string($condition_id);
	$condition_name = $db->real_escape_string($condition_name);
	$now = date("Y-m-d H:i:s");
	
	$sql_see = "select create_date from dtb_jobs where company_id='$kiwa_companyid' order by create_date desc";
	$cd = $db->QueryItem($sql_see);
	if($cd==""||strtotime(date("Y-m-d H:i:s"))-strtotime($cd) > 600){
	
		$sql = "insert into dtb_jobs set job_id = '$job_id',company_id = '$kiwa_companyid',company_name='$kiwa_companyname',user_type='$usertype',information_date='$now',level='$zz_name',hope_week_day=$w_d,
				job_title='$job_title',kindsID='$kindsID',kindsname='$kindsname',typesID='$typesID',typesname='$typesname',
				contents='$contents',employed_method='$employ_mothod',employed_method_name='$employ_mothod_name',area_cd='$area_cd',pref_cd='$pref_cd',pref_name='$pref_name',line_num='$line_num',station_cd='$station_cd',station_name='$station_name',
				hope_date='$hope_date',hope_date_name='$hope_date_name',moneyid='$moneyid',money_name='$money_name',requirements='$requirements',lianxi='$lianxi',numbers='$numbers',condition_id='$condition_id',condition_name='$condition_name',over_date='$over_date',create_date='$now',answer_date='$now'";
		
		if($db->Execute($sql)){
			$message_title="推荐人才，尽在【我的地盘】";
			if($usertype == "user"){
				$message_content="尊敬的用户，您好！\r\n欢迎使用帮帮网平台发布工作信息！\r\n请您关注【我的地盘】，这里可能有您要招聘的人才！\r\n
						详情请点击 -> http://8n8n.co.jp/mypage/r/";
			}else{
				$message_content="尊敬的用户，您好！\r\n欢迎使用帮帮网平台发布工作信息！\r\n请您关注【我的地盘】，这里可能有您要招聘的人才！\r\n
						详情请点击 -> http://8n8n.co.jp/companypage/";
			}
			
			message_send_do("000001",$kiwa_companyid,$message_title,$message_content);
			
			//存日志表
			dolog($kiwa_companyid, $usertype, "job", $job_id, $job_title, "insert");
			
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

##########################################
# findpeople_update.php 修改求人信息
##########################################
function findpeople_update($job_id,$kiwa_companyid,$kiwa_companyname,$now,$job_title,$kindsID,$kindsname,$typesID,$typesname,$contents,$employ_mothod,$employ_mothod_name,$area_cd,$pref_cd,$pref_name,$line_num,$station_cd,$station_name,$hope_date,$hope_date_name,$moneyid,$money_name,$requirements,$numbers,$over_date,$condition_id,$condition_name){
	global $db;
	$job_id = $db->real_escape_string($job_id);
	$kiwa_companyid = $db->real_escape_string($kiwa_companyid);
	$kiwa_companyname = $db->real_escape_string($kiwa_companyname);
	$kindsID = $db->real_escape_string($kindsID);
	$kindsname = $db->real_escape_string($kindsname);
	$typesID = $db->real_escape_string($typesID);
	$typesname = $db->real_escape_string($typesname);
	$contents = $db->real_escape_string($contents);
	$employ_mothod = $db->real_escape_string($employ_mothod);
	$employ_mothod_name = $db->real_escape_string($employ_mothod_name);
	$area_cd = $db->real_escape_string($area_cd);
	$pref_cd = $db->real_escape_string($pref_cd);
	$pref_name = $db->real_escape_string($pref_name);
	$line_num = $db->real_escape_string($line_num);
	$station_cd = $db->real_escape_string($station_cd);
	$station_name = $db->real_escape_string($station_name);
	
	$hope_date = $db->real_escape_string($hope_date);
	$hope_date_name = $db->real_escape_string($hope_date_name);
	$moneyid = $db->real_escape_string($moneyid);
	$money_name = $db->real_escape_string($money_name);
	$requirements = $db->real_escape_string($requirements);
	$numbers = $db->real_escape_string($numbers);
	$condition_id = $db->real_escape_string($condition_id);
	$condition_name = $db->real_escape_string($condition_name);
	$now = date("Y-m-d H:i:s");
	$sql = "update dtb_jobs set job_title='$job_title',kindsID='$kindsID',kindsname='$kindsname',typesID='$typesID',typesname='$typesname',
			contents='$contents',employed_method='$employ_mothod',employed_method_name='$employ_mothod_name',area_cd='$area_cd',pref_cd='$pref_cd',pref_name='$pref_name',line_num='$line_num',station_cd='$station_cd',station_name='$station_name',
			hope_date='$hope_date',hope_date_name='$hope_date_name',moneyid='$moneyid',money_name='$money_name',requirements='$requirements',numbers='$numbers',condition_id='$condition_id',condition_name='$condition_name',over_date='$over_date',update_date='$now' 
			where job_id = '$job_id' and company_id = '$kiwa_companyid' and del_flg=0";
	
	if($db->Execute($sql)){
		return true;
	}else{
		return false;
	}
}

##########################################
# findpeople_delete.php 删除求人信息
##########################################
function findpeople_delete($job_id,$company_id){
	global $db;
	$job_id = cleanInput($job_id);
	$people_id = $db->real_escape_string($job_id);
	$company_id = cleanInput($company_id);
	$company_id = $db->real_escape_string($company_id);
	$now = date("Y-m-d H:i:s");
	
	$sql = "update dtb_jobs set del_flg = 1,update_date = '$now' where job_id='$job_id' and company_id = '$company_id'";
	if($db->Execute($sql)){
		return true;
	}else{
		return false;
	}
}

##########################################
# getnote_delete.php 删除收到的简历
##########################################
function getnote_delete($id,$kiwa_companyid){
	global $db;
	$id = cleanInput($id);
	$id = $db->real_escape_string($id);
	$kiwa_companyid = cleanInput($kiwa_companyid);
	$kiwa_companyid = $db->real_escape_string($kiwa_companyid);
	$now = date("Y-m-d H:i:s");
	
	$sql = "update dtb_job_lvli set company_del_flg = 1 where Id='$id' and company_id = '$kiwa_companyid'";
	
	if($db->Execute($sql)){
		return true;
	}else{
		return false;
	}
}

##########################################
# sendnote_delete.php 删除收发的简历
##########################################
function sendnote_delete($id,$userid){
	global $db;
	$id = cleanInput($id);
	$id = $db->real_escape_string($id);
	$userid = cleanInput($userid);
	$userid = $db->real_escape_string($userid);
	$now = date("Y-m-d H:i:s");
	
	$sql = "update dtb_job_lvli set del_flg = 1 where Id='$id' and user_id = '$userid'";
	
	if($db->Execute($sql)){
		return true;
	}else{
		return false;
	}
}

##########################################
# company_update.php 修改企业简历
##########################################
function company_update($company_id,$company_manager,$foundation_date,$company_money,$company_email,$password,$right_email,$per_status,$company_url,$zip01,$zip02,$add_pref,$addr01,$addr02,$tel01,$tel02,$tel03,$fax01,$fax02,$fax03,$employees_num,$company_text,$areaid,$pref,$kinds,$types_str,$skills_str,$ensn_str,$eki_str,$kindsname,$typesname_str){
	global $db;
	$company_manager = $db->real_escape_string($company_manager);
	$foundation_date = $db->real_escape_string($foundation_date);
	$company_money = $db->real_escape_string($company_money);
	$company_email = $db->real_escape_string($company_email);
	$password = $db->real_escape_string($password);
	$right_email = $db->real_escape_string($right_email);
	$per_status = $db->real_escape_string($per_status);
	$company_url = $db->real_escape_string($company_url);
	$zip01 = $db->real_escape_string($zip01);
	$zip02 = $db->real_escape_string($zip02);
	$add_pref = $db->real_escape_string($add_pref);
	$addr01 = $db->real_escape_string($addr01);
	$addr02 = $db->real_escape_string($addr02);
	
	$tel01 = $db->real_escape_string($tel01);
	$tel02 = $db->real_escape_string($tel02);
	$tel03 = $db->real_escape_string($tel03);
	$fax01 = $db->real_escape_string($fax01);
	$fax02 = $db->real_escape_string($fax02);
	$fax03 = $db->real_escape_string($fax03);
	
	$areaid = $db->real_escape_string($areaid);
	$pref = $db->real_escape_string($pref);
	$kinds = $db->real_escape_string($kinds);
	$types_str = $db->real_escape_string($types_str);
	$kindsname = $db->real_escape_string($kindsname);
	$typesname_str = $db->real_escape_string($typesname_str);
	$skills_str = $db->real_escape_string($skills_str);
	$ensn_str = $db->real_escape_string($ensn_str);
	$eki_str = $db->real_escape_string($eki_str);
	
	$employees_num = $db->real_escape_string($employees_num);
	$company_text = $db->real_escape_string($company_text);
	
	$now = date("Y-m-d H:i:s");
	//对邮件,密码进行判断
	$sqlsee = "select company_email,password,info_flg from dtb_companyuser where company_id = '$company_id' and del_flg = 0";
	$row = $db->QueryRow($sqlsee);
	if($row['password'] == $password){
		$password =$row['password'];
	}else{
		$password = md5($password);
	}
	
	$sql = "update dtb_companyuser set company_manager='$company_manager',foundation_date='$foundation_date',company_money=$company_money,zip01='$zip01',zip02='$zip02',pref='$add_pref',addr01='$addr01',addr02='$addr02',
			employees_num='$employees_num',password='$password',company_url='$company_url',company_text='$company_text',
			right_email='$right_email',per_status=$per_status,tel01='$tel01',tel02='$tel02',tel03='$tel03',fax01='$fax01',
			fax02='$fax02',fax03='$fax03',update_date='$now',kindsID='$kinds',kindsname='$kindsname',typesID='$types_str',
			typesname='$typesname_str',area_cd='$areaid',pref_cd='$pref',line_num='$ensn_str',station_cd='$eki_str',skill='$skills_str',info_flg=1
			where company_id='$company_id' and del_flg = 0";
	
	if($db->Execute($sql)){
		if($row['info_flg']==0){
			pointsDo($company_id,"company",50);
		}
		return true;
	}else{
		return false;
	}
}


function company_img_update($company_id,$company_photo_url){
	global $db;
	$sql="update dtb_companyuser set company_photo_url = '$company_photo_url' where company_id='$company_id' and del_flg =0";
	if($db->Execute($sql)){
		return true;
	}else{
		return false;
	}
}

function user_img_update($user_id,$user_photo_url){
	global $db;
	$sql="update dtb_user set user_photo_url = '$user_photo_url' where user_id='$user_id' and del_flg =0";
	if($db->Execute($sql)){
		return true;
	}else{
		return false;
	}
}

function live_img_update($live_id,$live_photo){
	global $db;
	$sql="update dtb_lives set live_photo = '$live_photo' where live_id='$live_id' and del_flg =0";
	if($db->Execute($sql)){
		return true;
	}else{
		return false;
	}
}

function bbs_img_update($bbs_id,$bbs_photo){
	global $db;
	$sql="update dtb_bbs set bbs_photo = '$bbs_photo' where bbs_id='$bbs_id' and del_flg =0";
	if($db->Execute($sql)){
		return true;
	}else{
		return false;
	}
}
//更新上传图片路径
function bbs_img_update_html5($bbs_id,$bbs_photo){
	global $db;
	$sql="update dtb_bbs set bbs_photo_html5 = '$bbs_photo' where bbs_id='$bbs_id' and del_flg =0";
	if($db->Execute($sql)){
		return true;
	}else{
		return false;
	}
}

##########################################
# pmessage_delete.php 删除收到的信
##########################################
function pmessage_delete($id,$flag){
	global $db;
	$id = cleanInput($id);
	$id = $db->real_escape_string($id);
	$flag = cleanInput($flag);
	$flag = $db->real_escape_string($flag);
	$now = date("Y-m-d H:i:s");
	
	$sql = "update dtb_message set $flag = 1 where message_id='$id'";
	
	if($db->Execute($sql)){
		return true;
	}else{
		return false;
	}
}

##########################################
# cmessage_send.php 发送信件
##########################################
function message_send_do($message_from,$message_to,$message_title,$message_content){
	global $db;
	$message_from = cleanInput($message_from);
	$message_from = $db->real_escape_string($message_from);
	$message_to = cleanInput($message_to);
	$message_to = $db->real_escape_string($message_to);
	$message_title = cleanInput($message_title);
	$message_title = $db->real_escape_string($message_title);
	$message_content = cleanInput($message_content);
	$message_content = $db->real_escape_string($message_content);
	$now = date("Y-m-d H:i:s");
	$message_id = date("dHis").rand(1000,9999);
	$sql = "insert into dtb_message set message_id='$message_id', message_from='$message_from',message_to='$message_to',
			message_title='$message_title',message_content='$message_content',create_date='$now'";
	
	if($db->Execute($sql)){
		return true;
	}else{
		return false;
	}
	
}
//----------------------------------------------------------------
##################################################################
#
#	★以下为各个view层使用的函数
# 
##################################################################
##########################################
# pwlink.php 邮件返回，更改密码
##########################################
function pwlink($id,$pw){
	global $db;
	$id = $db->real_escape_string($id);
	$pw = $db->real_escape_string($pw);
	//数据库比较 del_flg:删除flag
	$sql = "select user_id,name01,name02 
				from dtb_user 
				where `user_id` = '$id' 
				and `password` = '$pw' 
				and `del_flg` = 0";
	
	$Row = $db->QueryRow($sql);
	if($Row){
		return $Row;
	}else{
		return false;
	}
}

#################################################
# review.php/companyreview.php 个人企业，邮件二次校验
#################################################
function emailRegist($email){
	global $db;
	$email = $db->real_escape_string($email);
	$sql_user="select Id from dtb_user where `email` = '$email' and del_flg = 0";
	$sql_company="select Id from dtb_companyuser where `company_email` = '$email' and del_flg = 0";
	if($db->QueryItem($sql_user)){
		//个人表里有email不让注册
		return false;
	}else{
		if($db->QueryItem($sql_company)){
			//企业表里有email不让注册
			return false;
		}
		//没有继续注册
		return true;
	}
}

function pointsDo($userid,$usertype,$point){
	global $db;
	$sql="";
	if($usertype=="company"){
		$sql_c = "select points from dtb_companyuser where `company_id` = '$userid'";
		$points = $db->QueryItem($sql_c);
		$p = $points + $point;
		$sql = "update dtb_companyuser set points = $p where `company_id` = '$userid'";
	}elseif($usertype=="user"){
		$sql_s = "select points from dtb_user where `user_id` = '$userid'";
		$points = $db->QueryItem($sql_s);
		$p = $points + $point;
		$sql = "update dtb_user set points = $p where `user_id` = '$userid'";
	}
	$db->Execute($sql);
}

##########################################
# regist/down.php 处理邮件认证
##########################################
function registOver($user_id){
	global $db;
	$sql="";
	$user_id = $db->real_escape_string($user_id);
	$sql_see_u = "select Id,points from dtb_user where `user_id` = '$user_id' and `status` = 0";
	$see_u = $db->QueryRow($sql_see_u);
	$date = date("Y-m-d H:m:s");
	if($see_u["Id"]!=""){
		$points = $see_u["points"]+50;
		$sql="update dtb_user
					set `status` = 1,`update_date` = '$date',points=$points
					where `user_id` = '$user_id'";
	}else{
		$sql_see_c = "select Id,points from dtb_companyuser where `company_id` = '$user_id' and `status` = 0";
		$see_c = $db->QueryRow($sql_see_c);
		if($see_c["Id"]!=""){
			$points = $see_c["points"]+50;
			$sql="update dtb_companyuser
					set `status` = 1,`update_date` = '$date',points=$points
					where `company_id` = '$user_id'";
		}
	}
	
	if($sql!=""){
		if($db->Execute($sql)){
			return true;
		}else{
			return false;
		}
	}else{
		return true;
	}
}

##########################################
# 从数据库里得到业种
##########################################
function getCompanyfrom($kids=""){
	global $db;
	if($kids==""){//取出全部职种
		$sql = "select kindsname,kindsID
				from mtb_kinds_types 
				group by kindsname
				order by kindsID";
	}else{//取出部分选中的职种
		$where = "";//初始化
		foreach ($kids as $k=>$value){
			$kid = $db->real_escape_string($value);
			if($k!=0){
				$where .=" or ";
			}
			$where .= "kindsID = '$kid'";
		}
		$sql = "select kindsname,kindsID
				from mtb_kinds_types 
				where $where 
				group by kindsname 
				order by kindsID";
	}
	if($db->QueryArray($sql)){
		$arraylist = $db->QueryArray($sql);
		return $arraylist;
	}else{
		return false;
	}
}

##########################################
# 从数据库里得到地域
# 参数:
# $area_cd为地域的查询id
##########################################
function getPref($area_cd=""){
	global $db;
	if($area_cd==""){//取出所有地域
		$sql = "select area_cd,area_name from mtb_pref group by area_name order by rank";
	}else{//根据参数地域id查询出县城
		$area_cd = cleanInput($area_cd);
		$area_cd = $db->real_escape_string($area_cd);
		
		$sql = "select pref_cd,name from mtb_pref where area_cd = '$area_cd' order by rank";
	}
	$array = $db->QueryArray($sql);
	if($array){
		return $array;
	}else{
		return false;
	}
	
}

##########################################
# 从数据库里得到指定id的职种
##########################################
function getLifeservice($serviceid=""){
	global $db;
	if($serviceid==""){
		$sql = "select * from mtb_life_service order by rank";
		$array = $db->QueryArray($sql);
	}else{
		$serviceid = cleanInput($serviceid);
		$serviceid = $db->real_escape_string($serviceid);
		$sql = "select * from mtb_life_service where service_id = '$serviceid'";
		$array = $db->QueryRow($sql);
	}
	if($array){
		return $array;
	}else{
		return false;
	}
}

##########################################
# 从数据库里得到指定id的职种
##########################################
function getTypes($kindid){
	global $db;
	$kindid = cleanInput($kindid);
	$kindid = $db->real_escape_string($kindid);
	$sql = "select typesID,typesname from mtb_kinds_types where kindsID = '$kindid' order by rank";
	$array = $db->QueryArray($sql);
	if($array){
		return $array;
	}else{
		return false;
	}
}

##########################################
# 从数据库里得到給付信息
##########################################
function getMoney(){
	global $db;
	$sql = "select * from mtb_money order by rank";
	$array = $db->QueryArray($sql);
	if($array){
		return $array;
	}else{
		return false;
	}
}

##########################################
# 从数据库里得到雇用形態
##########################################
function getEmploy_method(){
	global $db;
	$sql = "select * from mtb_employed_method order by rank";
	$array = $db->QueryArray($sql);
	if($array){
		return $array;
	}else{
		return false;
	}
}

##########################################
# 从数据库里得到就業希望期間
##########################################
function getHope_date(){
	global $db;
	$sql = "select * from mtb_hope_date order by rank";
	$array = $db->QueryArray($sql);
	if($array){
		return $array;
	}else{
		return false;
	}
}

##########################################
# 从数据库里得到检索其他条件
##########################################
function getCondition(){
	global $db;
	$sql = "select * from mtb_condition order by rank";
	$array = $db->QueryArray($sql);
	if($array){
		return $array;
	}else{
		return false;
	}
}

##########################################
# 查询工作信息 一推
##########################################
function getJobs($where="",$start,$end){
	global $db;
	if($where == ""){
		$sql = "select * 
					from dtb_jobs 
					where del_flg = 0 order by answer_date desc limit $start , $end";
	}else{
		$sql = "select *
					from dtb_jobs 
					where del_flg = 0 and $where order by answer_date desc limit $start , $end";
	}
	$array = $db->QueryArray($sql);
	if($array){
		return $array;
	}else{
		return false;
	}
}

##########################################
# 查询生活服务信息 一推
##########################################
function getLives($where="",$start,$end){
	global $db;
	if($where == ""){
		$sql = "select * 
					from dtb_lives 
					where del_flg = 0 order by create_date desc limit $start , $end";
	}else{
		$sql = "select *
					from dtb_lives 
					where del_flg = 0 and $where order by create_date desc limit $start , $end";
	}
	$array = $db->QueryArray($sql);
	
	if($array){
		return $array;
	}else{
		return false;
	}
}

##########################################
# 查询bbs信息
##########################################
function getBbs($where="",$start,$end){
	global $db;
	if($where == ""){
		$sql = "select *
		from dtb_bbs
		where del_flg = 0 order by answer_date desc limit $start , $end";
	}else{
		$sql = "select *
		from dtb_bbs
		where del_flg = 0 and $where order by answer_date desc limit $start , $end";
	}
	$array = $db->QueryArray($sql);
	
	if($array){
		return $array;
	}else{
		return false;
	}
}

##########################################
# 查询主页回复及评价
##########################################
function getMsg($where="",$start,$end){
	global $db;
	if($where == ""){
		$sql = "select *
		from dtb_people_msg
		where del_flg = 0 order by create_date desc limit $start , $end";
	}else{
		$sql = "select *
		from dtb_people_msg
		where del_flg = 0 and $where order by create_date desc limit $start , $end";
	}
	$array = $db->QueryArray($sql);
	if($array){
		return $array;
	}else{
		return false;
	}
}

##########################################
# 查询主页回复及评价
##########################################
function getPhoto($where="",$start,$end){
	global $db;
	if($where == ""){
		$sql = "select *
		from dtb_people_photo
		where del_flg = 0 order by create_date desc limit $start , $end";
	}else{
		$sql = "select *
		from dtb_people_photo
		where del_flg = 0 and $where order by create_date desc limit $start , $end";
	}
	$array = $db->QueryArray($sql);
	if($array){
		return $array;
	}else{
		return false;
	}
}

##########################################
# 查询工作信息 一条
##########################################
function showJob($job_id){
	global $db;
	$job_id = cleanInput($job_id);
	$job_id = $db->real_escape_string($job_id);
	
	$sql = "select * from dtb_jobs where job_id = '$job_id' and del_flg = 0";
	$row = $db->QueryRow($sql);
	if($row){
		return $row;
	}else{
		return false;
	}
}

##########################################
# 查询生活服务 一条
##########################################
function showLive($live_id){
	global $db;
	$job_id = cleanInput($live_id);
	$job_id = $db->real_escape_string($live_id);
	
	$sql = "select * from dtb_lives where live_id = '$live_id' and del_flg = 0";
	$row = $db->QueryRow($sql);
	if($row){
		return $row;
	}else{
		return false;
	}
}

##########################################
# 查询生活服务 一条 的 所有回复
##########################################
function getAnswers($live_id,$start,$end){
	global $db;
	$job_id = cleanInput($live_id);
	$job_id = $db->real_escape_string($live_id);
	
	$sql = "select * from dtb_lives_answer where live_id = '$live_id' and del_flg = 0 order by create_date limit $start , $end";
	$row = $db->QueryArray($sql);
	if($row){
		return $row;
	}else{
		return false;
	}
}


##########################################
# 查询公司信息 一条
##########################################
function getCompany($company_id){
	global $db;
	$company_id = cleanInput($company_id);
	$company_id = $db->real_escape_string($company_id);
	$sql = "select * from dtb_companyuser where company_id = '$company_id' and del_flg = 0";
	$row = $db->QueryRow($sql);
	if($row){
		return $row;
	}else{
		return false;
	}
}

##########################################
# 查询人材企业库的信息
##########################################
function getAlluser($where="",$start,$end){
	global $db;
	if($where == ""){
		$sql_user = "select * from dtb_user where del_flg = 0 order by create_date desc limit $start , $end";
	}else{
		$sql_user = "select * from dtb_user where del_flg = 0 and $where order by create_date desc limit $start , $end";
	}
	$array = $db->QueryArray($sql_user);
	//两个数组合并，企业用户优先
	if($array){
		return $array;
	}else{
		return false;
	}
}
##########################################
# 查询人材企业库的信息
##########################################
function getAllcompanyuser($where="",$start,$end){
	global $db;
	if($where == ""){
		$sql_companyuser = "select * from dtb_companyuser where del_flg = 0 order by create_date desc limit $start , $end";
	}else{
		$sql_companyuser = "select * from dtb_companyuser where del_flg = 0 and $where order by create_date desc limit $start , $end";
	}

	$array = $db->QueryArray($sql_companyuser);
	//两个数组合并，企业用户优先
	if($array){
		return $array;
	}else{
		return false;
	}
}


##########################################
# 查询收藏夹 一推
##########################################
function getFevorite($where="",$start,$end,$order){
	global $db;
	$sql = "select * from dtb_favorite_list   
				where $where and del_flg = 0 limit $start , $end";
	$array = $db->QueryArray($sql);
	if($array){
		return $array;
	}else{
		return false;
	}
}

##########################################
# 个人-查询递交简历 一推
##########################################
function getJianli($where="",$start,$end){
	global $db;
	$sql = "select * from dtb_job_lvli 
				where $where and del_flg = 0 order by create_date desc limit $start , $end";
	$array = $db->QueryArray($sql);
	if($array){
		return $array;
	}else{
		return false;
	}
}
##########################################
# 企业-查询递交简历 一推
##########################################
function getCompanyJianli($where="",$start,$end){
	global $db;
	$sql = "select * from dtb_job_lvli 
				where $where and company_del_flg = 0 order by create_date desc limit $start , $end";
	$array = $db->QueryArray($sql);
	if($array){
		return $array;
	}else{
		return false;
	}
}

##########################################
# 查询递交简历总数
##########################################
function getLvliNum($table,$where){
	global $db;
	if($where == ""){
		$sql = "select count(*) from $table where company_del_flg = 0";
	}else{
		$sql = "select count(*) from $table where company_del_flg = 0 and $where";
	}
	return $db->QueryItem($sql);
}

##########################################
# 查询信箱--收信箱
##########################################
function getMessage_GetM($where="",$start,$end){
	global $db;
	$sql = "select * from dtb_message 
				where $where and del_flg = 0 order by create_date desc limit $start , $end";
	$array = $db->QueryArray($sql);
	if($array){
		return $array;
	}else{
		return false;
	}
}

##########################################
# 查询信箱--发信箱
##########################################
function getMessage_SendM($where="",$start,$end){
	global $db;
	$sql = "select * from dtb_message 
				where $where and from_del_flg = 0 order by create_date desc limit $start , $end";
	$array = $db->QueryArray($sql);
	if($array){
		return $array;
	}else{
		return false;
	}
}

##########################################
# 查询信箱--发信箱总数
##########################################
function getFromMessageNum($table,$where){
	global $db;
	if($where == ""){
		$sql = "select count(*) from $table where from_del_flg = 0";
	}else{
		$sql = "select count(*) from $table where from_del_flg = 0 and $where";
	}
	return $db->QueryItem($sql);
}

##########################################
# 返信操作
##########################################
function reMessage($message_from,$message_to,$message_title,$re_message,$rem_id){
	global $db;
	$message_from = cleanInput($message_from);
	$message_from = $db->real_escape_string($message_from);
	$message_to = cleanInput($message_to);
	$message_to = $db->real_escape_string($message_to);
	$message_title = cleanInput($message_title);
	$message_title = $db->real_escape_string($message_title);
	$rem_id = cleanInput($rem_id);
	$rem_id = $db->real_escape_string($rem_id);
	$now = date("Y-m-d H:i:s");
	$message_id = date("dHis").rand(1000,9999);
	$sql="insert into dtb_message set message_id='$message_id',message_from='$message_from',message_to='$message_to',
			message_title='Re:$message_title',message_content='$re_message',re_message_id='$rem_id',create_date='$now'";
	$return = $db->Execute($sql);
	return $return;
}

##########################################
# 取得服务名字 根据service_id
##########################################
function getService($service_id){
	global $db;
	$service_id = cleanInput($service_id);
	$service_id = $db->real_escape_string($service_id);
	$sql="select * from mtb_life_service where service_id = '$service_id'";
	$row = $db->QueryRow($sql);
	return $row;
}

##########################################
# 通用 --> 增加访问次数
##########################################
function add_see_page($user_id,$user_type,$see_page){
	global $db;
	$user_id = cleanInput($user_id);
	$user_id = $db->real_escape_string($user_id);
	$sp = $see_page+1;
	//访问数加1
	if($user_type == "company"){
		$sql_isp = "update dtb_companyuser set see_page=$sp where company_id = '$user_id'";
	}else
	if($user_type == "user"){
		$sql_isp = "update dtb_user set see_page=$sp where user_id = '$user_id'";
	}
	$db->Execute($sql_isp);
}


##########################################
# 通用 --> 根据县得到线路
##########################################
function getLine($pref_cd){
	global $db;
	$sql = "select mtb_line.line_num,mtb_line.line_name from mtb_station,mtb_line where mtb_station.line_num = mtb_line.line_num and mtb_station.pref_cd = $pref_cd group by line_name";
	$array = $db->QueryArray($sql);
	$str = "";
	for($i=0;$i<count($array);$i++){
		$str .= '<div class="parents">
					<h4 class="close"><a href="javascript:void(0);" onclick="showStation('.$i.','.$pref_cd.','.$array[$i]['line_num'].')">'.$array[$i]['line_name'].'</a></h4>
					<div class="pt05a">
						<dl id="station_'.$i.'"></dl>
					</div>
				</div>';
	}
	return $str;
}
##########################################
# 通用 --> 根据线路得到车站
##########################################
function getStation($pref_cd,$lineid,$index){
	global $db;
	$sql = "select station_cd,station_name from mtb_station where line_num = '$lineid' and pref_cd = $pref_cd";
	$array = $db->QueryArray($sql);
	$str = "";
	foreach($array as $k){
		$str .= "<li><label><input type='checkbox' class='level-2e' id='$k[station_cd]' name='$k[station_cd]' value='2:$lineid:$k[station_name]:eki[]:$index'>$k[station_name]</label></li>";
	}
	return $str;
}
##########################################
# 通用 --> 根据ID得到各种名字
##########################################
function getName($id){
	extract($id);
	global $db;
	if($name == "condition"){//条件
		$sql = "select name from mtb_condition where Id = $value";
	}
	if($name == "area"){//县城
		$sql = "select area_name from mtb_pref where pref_cd = $value";
	}
	if($name == "pref"){//县城
		$sql = "select name from mtb_pref where pref_cd = $value";
	}
	if($name == "ensn"){//线路
		$sql = "select line_name from mtb_line where line_num = $value";
	}
	if($name == "eki"){//车站
		$sql = "select station_name from mtb_station where station_cd = $value";
	}
	if($name == "kind"){//业种
		$sql = "select kindsname from mtb_kinds_types where typesID = '$value'";
	}
	if($name == "type"){//业种
		$sql = "select typesname from mtb_kinds_types where typesID = '$value'";
	}
	if($name == "money"){//薪资
		$sql = "select concat(name,moneyname) from mtb_money where moneyid = '$value'";
	}
	if($name == "employ"){//雇佣形式
		$sql = "select name from mtb_employed_method where Id = '$value'";
	}
	if($name == "hope"){//希望的工作时间
		$sql = "select name from mtb_hope_date where id = '$value'";
	}
	if($name == "education"){//学历
		$sql = "select eductionname from mtb_eduction where eductionid = '$value'";
	}
	if($name == "favorite"){
		$sql = "select pref_name,hope_date_name,money_name,employed_method_name from dtb_jobs where job_id = '$value'";
		$row = $db->QueryRow($sql);
		$name = "<td>$row[employed_method_name]</td><td>$row[money_name]</td>";
		return $name;
	}
	if($name == "company_favorite"){
		$sql = "select birth,kindsname,typesname,employed_method_name,pref_name,money_name from dtb_peoples where people_id = '$value'";
		$row = $db->QueryRow($sql);
		$age = date("Y-m-d")-$row['birth'];
		$name = "<td>$age 歳</td><td class='alLeft' nowrap='nowrap'>$row[typesname]</td><td>$row[pref_name]</td><td>$row[money_name]</td>";
		return $name;
	}
	if($name == "username"){
		$sql = "select concat(name01,name02) from dtb_user where user_id = '$value' and del_flg = 0";
	}
	if($name == "company_name"){
		$sql = "select company_name from dtb_companyuser where company_id = '$value' and del_flg = 0";
	}
	//新加pr
	if($name == "company_pr"){
		$sql = "select company_text from dtb_companyuser where company_id = '$value' and del_flg = 0";
	}
	if($name == "userpr"){
		$sql = "select mypr from dtb_user where user_id = '$value' and del_flg = 0";
	}
	if($name == "user_photo"){
		$sql = "select user_photo_url from dtb_user where user_id = '$value' and del_flg = 0";
		$name = $db->QueryItem($sql);
		if($name == ""){
			return "upload/img/nopicture.png";
		}else{
			return $name;
		}
	}
	if($name == "company_photo"){
		$sql = "select company_photo_url from dtb_companyuser where company_id = '$value' and del_flg = 0";
		$name = $db->QueryItem($sql);
		if($name == ""){
			return "upload/img/nopicture.npg";
		}else{
			return $name;
		}
	}
	if($name == "bbs_num"){
		$sql = "select count(*) from dtb_bbs_answer where bbs_id = '$value' and del_flg = 0";
	}
	//新加
	if($name == "job_num"){
		$sql = "select count(*) from dtb_jobs_answer where job_id = '$value' and del_flg = 0";
	}
	if($name == "live_num"){
		$sql = "select count(*) from dtb_lives_answer where live_id = '$value' and del_flg = 0";
	}
	//获取状态的图片路径
	if($name == "status_photo"){
		$sql = "select status_photo from dtb_status where status_id = '$value' and del_flg = 0";
	}
	//判断第几楼
	if($name == "answer_lou"){
		$sql="select job_ls_id from dtb_job_ls_answer where Id='$value'";
		$job_ls_id=$db->QueryItem($sql);
		$sql_lou = "select orderNo from (select *,(@rowNum:=@rowNum+1) as orderNo  from dtb_job_ls_answer ,(Select (@rowNum :=0)) b where job_ls_id='$job_ls_id' and del_flg = 0 order by create_date)
					k where k.Id=$value";
		$lou=$db->QueryItem($sql_lou);
		return $lou;
	}
	if($name == "url_pic"){
		$url_pic = get_pic($value);
		return $url_pic;
	}
	//截取图片路径

	if($name == "photo_explode"){
		$photo=explode(',',$value);
		return $photo[0];
	}
	$name = $db->QueryItem($sql);
	return $name;
}

function get_pic($pic_url) {
	//获取图片二进制流
	$data=CurlGet($pic_url);

	//利用正则表达式得到图片链接
	$pattern_src = '/<img\s.*?>/';
	$num = preg_match_all($pattern_src, $data, $match_src);
	
	if($match_src[0][3]!=""){
		$str = $match_src[0][3];
		$style='<img style="width: 140px;"';
		$str = preg_replace('/<img/', $style,$str);
	}else{
		return "<img style='width: 80px;' src='http://8n8n.co.jp/_templates/ep/img/bbs.jpg'>";
	}

	return $str;
}
//抓取网页内容
function CurlGet($url){
	$url=str_replace('&','&',$url);
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
	$values = curl_exec($curl);
	curl_close($curl);
	return $values;
}

##########################################
# 通用 --> 得到查询总条数
##########################################
function getObjectNum($table,$where){
	global $db;
	if($where == ""){
		$sql = "select count(*) from $table where del_flg = 0";
	}else{
		$sql = "select count(*) from $table where del_flg = 0 and $where";
	}
	
	return $db->QueryItem($sql);
}
##########################################
# 通用 --> 入力框参数过滤
##########################################
function cleanInput($input){
	if (!get_magic_quotes_gpc()){    // 判断magic_quotes_gpc是否打开  
		$input = addslashes($input);    // 进行过滤 
	}
	$input = str_replace("%", "\%", $input);   // 把 '%'过滤掉
	$input = htmlspecialchars($input,ENT_QUOTES);
	return $input;
}

##########################################
# 通用 --> 加密函数
##########################################
function passport_encrypt($txt) {
	$key = 28;//密钥です
	srand((double)microtime() * 1000000);
	$encrypt_key = md5(rand(0, 32000));
	$ctr = 0;
	$tmp = '';
	for($i = 0;$i < strlen($txt); $i++) {
		$ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
		$tmp .= $encrypt_key[$ctr].($txt[$i]
		^ $encrypt_key[$ctr++]);
	}
	return base64_encode(passport_key($tmp, $key));
}

##########################################
# 通用 --> 解密函数
##########################################
function passport_decrypt($txt) {
	$key = 28;//密钥です
	$txt = passport_key(base64_decode($txt), $key);
	$tmp = '';
	for($i = 0;$i < strlen($txt); $i++) {
		$md5 = $txt[$i];
		@$tmp .= $txt[++$i] ^ $md5;
	}
	return $tmp;
}

##########################################
# 通用 --> 加密解密中间函数
##########################################
function passport_key($txt, $encrypt_key) {
	$encrypt_key = md5($encrypt_key);
	$ctr = 0;
	$tmp = '';
	for($i = 0; $i < strlen($txt); $i++) {
		$ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
		$tmp .= $txt[$i] ^ $encrypt_key[$ctr++];
	}
	return $tmp;
}

##########################################
# 通用 --> logout函数
##########################################
function logOut(){
	$_SESSION = array();
	if(session_destroy()){
		setcookie("8n8nuser","",-86400,"/",".8n8n.co.jp");
		setcookie("8n8npw","",-86400,"/",".8n8n.co.jp");
		return true;
	}else{
		return false;
	}
}

##########################################
# 通用 --> 邮件配置函数，只配置一次，记住一点重复的代码不要写
##########################################
function mailTo(){
	require("class.phpmailer.php"); //下载的文件必须放在该文件所在目录

	$mail = new PHPMailer(); //建立邮件发送类
	$mail->IsSMTP(); // 使用SMTP方式发送  以下配置SMTP
	$mail->SMTPAuth = true; // 启用SMTP验证功能
	$mail->Host = 'ssl://smtp.gmail.com:465'; // 您的企业邮局域名
	#$mail->Host = MAILHOST;
	$mail->Port = MAILPORT;
	#$mail->SMTPDebug = true;
	$mail->Username = MAILUSER; // 邮局用户名(请填写完整的email地址)
	$mail->Password = MAILPASSWORD; // 邮局密码
	$mail->From = MAILFROM; //邮件发送者email地址
	$mail->FromName = MAILFROMNAME;
	//$mail->AddAttachment("/var/tmp/file.tar.gz"); // 添加附件
	//$mail->IsHTML(true); // set email format to HTML //是否使用HTML格式

	return $mail;
}

##########################################
# 从数据库里得到最終学歴
##########################################
function getEduction(){
	global $db;
	$sql = "select * from mtb_eduction order by rank";
	$array = $db->QueryArray($sql);
	if($array){
		return $array;
	}else{
		return false;
	}
}
##########################################
# 查询人信息 一条
##########################################
function showPeople($people_id){
	global $db;
	$people_id = cleanInput($people_id);
	$people_id = $db->real_escape_string($people_id);
	
	$sql = "select * from dtb_peoples where people_id = '$people_id' and del_flg = 0";
	$row = $db->QueryRow($sql);
	if($row){
		return $row;
	}else{
		return false;
	}
}
##########################################
# 查询ユーザー信息 一条
##########################################
function getUser($user_id){
	global $db;
	$user_id = cleanInput($user_id);
	$user_id = $db->real_escape_string($user_id);
	$sql = "select * from dtb_user where user_id = '$user_id' and del_flg = 0";
	$row = $db->QueryRow($sql);
	if($row){
		return $row;
	}else{
		return false;
	}
}
##########################################
# 查询職種業種name
##########################################
function getKindTypename($id){
	global $db;
	$id = cleanInput($id);
	$id = $db->real_escape_string($id);
	$sql = "select kindsname,typesname from mtb_kinds_types where typesID = '$id'";
	$row = $db->QueryRow($sql);
	if($row){
		return $row;
	}else{
		return false;
	}
}
##########################################
# 查询投掷简历--一条
##########################################
function getLvli($id){
	global $db;
	$id = cleanInput($id);
	$id = $db->real_escape_string($id);
	$sql = "select * from dtb_job_lvli where Id = '$id' and del_flg = 0";
	
	$row = $db->QueryRow($sql);
	if($row){
		return $row;
	}else{
		return false;
	}
}
##########################################
# 查询工作目前的状态，删除，过期等
##########################################
function getJobStatus($id){
	extract($id);
	global $db;
	if($name="status"){
		$sql = "select * from dtb_jobs where job_id = '$value'";
		$row = $db->QueryRow($sql);
		$now = date("Y-m-d");
		if($row['del_flg'] == 0 && $now <= $row['over_date'] && $row){
			return "招聘中";
		}else{
			return "招聘终止";
		}
	}
}

# 修改信件--标记为已读
function message_read_flag($id){
	global $db;
	$id = cleanInput($id);
	$id = $db->real_escape_string($id);
	
	$sql = "update dtb_message set read_flag = 1 where message_id='$id'";
	$db->Execute($sql);
}

# 显示信件
function getMessage($id){
	global $db;
	$id = cleanInput($id);
	$id = $db->real_escape_string($id);
	$sql = "select * from dtb_message where message_id = '$id'";
	$row = $db->QueryRow($sql);
	if($row){
		return $row;
	}else{
		return false;
	}
}

##########################################
# 通用 --> 日志表
##########################################
function dolog($user_id,$user_type,$type,$type_id,$type_title,$do){
	global $db;
	
	$user_id = $user_id;
	$user_type = $user_type;
	$type = $type;
	$type_id = $type_id;
	$type_title = $type_title;
	
	$create_date = date("Y-m-d H:i:s");
	
	if($do == "insert"){
		$sql = "insert into dtb_log set user_id='$user_id',user_type='$user_type',type='$type',type_id='$type_id',type_title='$type_title',create_date='$create_date'";
	}
	if($do == "delete"){
		$sql = "update dtb_log set del_flg=1 where user_id='$user_id' and type_id='$type_id'";
	}
	$row = $db->Execute($sql);
}

##########################################
# 通用 --> 得到IP
##########################################
function getIP(){
	global $ip;
	if (getenv("HTTP_CLIENT_IP"))
		$ip = getenv("HTTP_CLIENT_IP");
	else if(getenv("HTTP_X_FORWARDED_FOR"))
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	else if(getenv("REMOTE_ADDR"))
		$ip = getenv("REMOTE_ADDR");
	else $ip = "Unknow";
	return $ip;
}

##########################################
# 通用 --> 站内短信，未读信件
##########################################
function unreadMessage($message_to){
	global $db;
	$message_to = cleanInput($message_to);
	$message_to = $db->real_escape_string($message_to);
	$sql = "select count(*) from dtb_message where message_to = '$message_to' and read_flag = 0 and del_flg = 0";
	$item = $db->QueryItem($sql);
	if($item){
		return $item;
	}else{
		return false;
	}
}

##########################################
# 通用 --> 企业发送求人以后，系统自动匹配5个人，给企业发送站内短信推荐
##########################################
function people_recommend($kinds,$types,$areaid,$pref,$ensn,$eki,$moneyid,$employid,$hopedateid,$job_title,$url){
	//业种 职种 区域 市 线路 车站 薪金 雇佣形式 勤务时间
	global $db;
	//职种处理
	$yezhong = "";
	if($types!=""){
		$yezhong="and (";
		foreach($types as $k){
			$yezhong .= "typesID like '%$k%'";
			$yezhong .= " or ";
		}
		$yezhong = rtrim($yezhong,"or ");
		$yezhong .= ")";
	}
	//线路处理
	$line="";
	if($ensn!=""){
		$line="and (";
		foreach($ensn as $l){
			$line .= "line_num like '%$l%'";
			$line .= " or ";
		}
		$line = rtrim($line,"or ");
		$line .= ")";
	}
	//车站处理
	$chezhan="";
	if($eki!=""){
		$chezhan = "and (";
		foreach($eki as $e){
			//如果线路里的车站做处理
			$line_eki = substr($e,0,5);
			
			$chezhan .= "station_cd like '%$e%'";
			$chezhan .= " or ";
			$chezhan .= "line_num like '%$line_eki%'";
			$chezhan .= " or ";
		}
		$chezhan = rtrim($chezhan,"or ");
		$chezhan .= ")";
	}
	$sql = "select people_id,name01 from dtb_peoples where del_flg = 0 and kindsID = '$kinds' and area_cd = '$areaid' and pref_cd = '$pref'
			and moneyid = '$moneyid' and employed_method = '$employid' and hope_date = '$hopedateid' 
			$yezhong $line $chezhan limit 0,5";
	$array = $db->QueryArray($sql);
	
	if(!empty($array)){
		$message_title="系统推荐 求人:".$job_title;
		$message_content="$_SESSION[kiwa_companyname] 様\r\n
根据您的求人的条件，系统自动为您匹配的数据信息，以下是体统推荐的人选：\r\n
<a href=".$url."people/show/".$array[0]["people_id"]."/ target='_blank'>".$array[0]["name01"]."</a>
<a href=".$url."people/show/".$array[1]["people_id"]."/ target='_blank'>".$array[1]["name01"]."</a>
<a href=".$url."people/show/".$array[2]["people_id"]."/ target='_blank'>".$array[2]["name01"]."</a>
<a href=".$url."people/show/".$array[3]["people_id"]."/ target='_blank'>".$array[3]["name01"]."</a>
<a href=".$url."people/show/".$array[4]["people_id"]."/ target='_blank'>".$array[4]["name01"]."</a>\r\n
此为系统自动配信，请勿回复，谢谢合作！\r\n帮帮网运营团";

		message_send_do("000001",$_SESSION["kiwa_companyid"],$message_title,$message_content);
	}
	
}
##########################################
# send_work_ls.php 提交临时工作求人信息
##########################################
function send_work_ls($userid,$username,$usertype,$job_title,$contents,$areaid,$pref_cd,$prefname,$line_num,$station_cd,$station_name,$lianxi,$starttime,$endtime){
	global $db;
	$job_ls_id = date("is").rand(1000,9999);

	$userid = $db->real_escape_string($userid);
	$username = $db->real_escape_string($username);
	$job_title = $db->real_escape_string($job_title);
	$contents = $db->real_escape_string($contents);

	$area_cd = $db->real_escape_string($areaid);
	$pref_cd = $db->real_escape_string($pref_cd);
	$pref_name = $db->real_escape_string($prefname);
	$line_num = $db->real_escape_string($line_num);
	$station_cd = $db->real_escape_string($station_cd);
	$station_name = $db->real_escape_string($station_name);
	$lianxi = $db->real_escape_string($lianxi);

	$starttime = $db->real_escape_string($starttime);
	$endtime = $db->real_escape_string($endtime);

	$now = date("Y-m-d H:i:s");

	$sql_see = "select create_date from dtb_job_ls where user_id='$userid' order by create_date desc";
	$cd = $db->QueryItem($sql_see);

	if($cd==""||strtotime(date("Y-m-d H:i:s"))-strtotime($cd) > 600){

		$sql = "insert into dtb_job_ls set job_ls_id = '$job_ls_id',user_id = '$userid',user_name='$username',user_type='$usertype',job_ls_title='$job_title',
		contents='$contents',area_cd='$area_cd',pref_cd='$pref_cd',pref_name='$pref_name',line_num='$line_num',station_cd='$station_cd',station_name='$station_name',
		lianxi='$lianxi',date_end='$endtime',date_star='$starttime',create_date='$now',lastanswer_date='$now'";
		if($db->Execute($sql)){
			$message_title="推荐人才，尽在【我的地盘】";
			if($usertype == "user"){
				$message_content="尊敬的用户，您好！\r\n欢迎使用帮帮网平台发布工作信息！\r\n请您关注【我的地盘】，这里可能有您要招聘的人才！\r\n
						详情请点击 -> http://8n8n.co.jp/mypage/r/";
			}else{
				$message_content="尊敬的用户，您好！\r\n欢迎使用帮帮网平台发布工作信息！\r\n请您关注【我的地盘】，这里可能有您要招聘的人才！\r\n
						详情请点击 -> http://8n8n.co.jp/companypage/";
			}
			message_send_do("000001",$kiwa_companyid,$message_title,$message_content);
			//存日志表
			dolog($userid,$usertype, "jobls", $job_ls_id, $job_title, "insert");
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}
##########################################
# 查询工作信息 一推
##########################################
function getAllInfo($where="",$start,$end,$table){
	global $db;
	if($where == ""){
		$sql = "select *
		from $table
		where del_flg = 0 order by lastanswer_date desc limit $start , $end";
	}else{
		$sql = "select *
		from $table
		where del_flg = 0 and $where order by lastanswer_date desc limit $start , $end";
	}
	$array = $db->QueryArray($sql);
	if($array){
		return $array;
	}else{
		return false;
	}
}
##########################################
# 查询临时工作信息 一条
##########################################
function showAInfo($job_ls_id,$table,$key){
	global $db;
	
	$job_ls_id = cleanInput($job_ls_id);
	$job_ls_id = $db->real_escape_string($job_ls_id);
	
	$sql = "select * from $table where $key = '$job_ls_id' and del_flg = 0";
	$row = $db->QueryRow($sql);
	if($row){
		return $row;
	}else{
		return false;
	}
}
##########################################
# send_work_ls.php 更新临时工作求人信息
##########################################
function  update_work_ls($job_ls_id,$job_title,$contents,$areaid,$pref_cd,$prefname,$line_num,$station_cd,$station_name,$lianxi,$starttime,$endtime){
	global $db;
	$job_title = $db->real_escape_string($job_title);
	$contents = $db->real_escape_string($contents);
	
	$area_cd = $db->real_escape_string($areaid);
	$pref_cd = $db->real_escape_string($pref_cd);
	$pref_name = $db->real_escape_string($prefname);
	$line_num = $db->real_escape_string($line_num);
	$station_cd = $db->real_escape_string($station_cd);
	$station_name = $db->real_escape_string($station_name);
	$lianxi = $db->real_escape_string($lianxi);
	
	$starttime = $db->real_escape_string($starttime);
	$endtime = $db->real_escape_string($endtime);
	$now = date("Y-m-d H:i:s");
		$sql = "update dtb_job_ls set job_ls_title='$job_title',
		contents='$contents',area_cd='$area_cd',pref_cd='$pref_cd',pref_name='$pref_name',line_num='$line_num',station_cd='$station_cd',station_name='$station_name',
		lianxi='$lianxi',date_end='$endtime',date_star='$starttime',update_date='$now' where job_ls_id = '$job_ls_id' and del_flg=0";
		if($db->Execute($sql)){
			//存日志表
			//dolog_update("jobls", $job_ls_id, $job_title);
			return true;
		}else{
			return false;
		}
}
##########################################
# 通用 --> 更新日志表
##########################################
function dolog_update($type,$type_id,$type_title){
	global $db;
	
	$type = $type;
	$type_id = $type_id;
	$type_title = $type_title;
	
	$sql = "update dtb_log set type_title='$type_title' where type='$type' and type_id='$type_id'";
	
	$row = $db->Execute($sql);
}
?>