<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	require_once '../_includes/mobile.php';

	$today = date("Y-m-d");
	$create_date = date("Y-m-d H:i:s");
	//没有Session 跳转到登录页面
	if(@$_SESSION['kiwa_userid'] !=""){
		$user_id = $_SESSION['kiwa_userid'];
		$user_type = "user";
	}
	if(@$_SESSION['kiwa_companyid'] !=""){
		$user_id = $_SESSION['kiwa_companyid'];
		$user_type = "company";
	}
	
	//每天人数
	$event1 = 5;
	$smarty->assign('event1', $event1);
	
	if(isset($_POST["sb1"])){
		//已报人数
		$check_sql = "select count(*) from dtb_online_kiwaevent where work_date = '$today' and event = 1 and baoming_type = 1 and del_flg = 0";
		$count = $db->QueryItem($check_sql);
		if($count < $event1){
			$sqls = "select count(*) from dtb_online_kiwaevent where user_id = '$user_id' and work_date = '$today' and event = 1 and baoming_type = 1";
			$ct = $db->QueryItem($sqls);
			if($ct == 0){
				$sql = "insert into dtb_online_kiwaevent set user_id = '$user_id' , work_date = '$today' , event = 1 , create_date = '$create_date'";
				$db->Execute($sql);
				//发送站内短信
				$message_title="临时工作活动通知";
				$message_content="尊敬的用户，您好！\r\n您已成功报名今日的临时工作！\r\n我们担当会尽快跟您联系！\r\n我们提示您，需要提前1小时15到指定车站！\r\n带上相关证件（在留卡，护照，学生证，保险证，银行存折，印章）！\r\n友情提示:袜子和长裤是必须的，女生不允许穿丝袜子！\r\n友情提示:工厂气温10℃左右，请带好当日晚餐！";
				message_send_do("000001",$user_id,$message_title,$message_content);
			}
			header('Location:'.APP_URL.'online/signup/#bm');
		}else{
			echo "<script language='javascript'>alert('报名已满，请替补！');</script>";
		}
	}
	
	if(isset($_POST["sb1_tibu"])){
		$sqls = "select count(*) from dtb_online_kiwaevent where user_id = '$user_id' and work_date = '$today' and event = 1";
		$ct = $db->QueryItem($sqls);
		if($ct == 0){
			$sql = "insert into dtb_online_kiwaevent set user_id = '$user_id' , work_date = '$today' , event = 1 , baoming_type = 2 , create_date = '$create_date'";
			$db->Execute($sql);
		}
		header('Location:'.APP_URL.'online/signup/#bm');
	}
	
	if(@$user_id!=""){
		$sql_ol = "select * from dtb_online where user_id = '$user_id' and del_flg = 0";
		$ol = $db->QueryRow($sql_ol);
		$smarty->assign('ol', $ol);
	
		//查看某人今天是否报名，每天只能报名一次
		$sql_see = "select event from dtb_online_kiwaevent where user_id = '$user_id' and work_date='$today' and del_flg = 0 and baoming_type = 1";
		$baoming = $db->QueryItem($sql_see);
		$smarty->assign('baoming', $baoming);
		
		//一周只能报名2次，只能报名10次
		$weektime = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-7,date("Y")));
		$week_sql = "select count(*) from dtb_online_kiwaevent where work_date >= '$weektime' and work_date <= '$today' and  user_id = '$user_id' and del_flg = 0 and baoming_type = 1";
		$count = $db->QueryItem($week_sql);
		if($count > 1){
			$smarty->assign('count', 1);
		}
		$sql_10 = "select count(*) from dtb_online_kiwaevent where user_id = '$user_id' and del_flg = 0 and baoming_type = 1";
		$count = $db->QueryItem($sql_10);
		if($count > 9){
			$smarty->assign('count', 1);
		}
		
	}
	
	//滚动信息
	$sql_gun2 = "select * from dtb_online where del_flg = 0 and type='kiwa' and checkflag = 1 order by create_date desc";
	$gun2 = $db->QueryArray($sql_gun2);
	$smarty->assign('gun2', $gun2);
	
	//--------------------------------------------------------------//
	
	//已报人数
	$c1_sql = "select count(*) from dtb_online_kiwaevent where work_date = '$today' and event = 1 and baoming_type = 1 and del_flg = 0";
	$c1 = $db->QueryItem($c1_sql);
	$smarty->assign('c1', $c1);
	//备用人数
	$c1_sql_beiyong = "select count(*) from dtb_online_kiwaevent where work_date = '$today' and event = 1 and baoming_type = 2 and del_flg = 0";
	$c1_beiyong = $db->QueryItem($c1_sql_beiyong);
	$smarty->assign('c1_beiyong', $c1_beiyong);
	
	//报名开始时间
	$starttime = $today." 12:00:00";
	$overtime = $today." 14:00:00";
	$now = date("Y-m-d H:i:s");
	if($now >= $starttime && $now <= $overtime){
		$smarty->assign('go', "ok");
	}else{
		$smarty->assign('go', "no");
	}
	
	if($now > $overtime){
		$es = date("m/d/Y",mktime(0,0,0,date("m"),date("d")+1,date("Y")));
		$event_start = $es." 12:00:00";
		$smarty->assign('eword', "距离下次报名还有");
	}else{
		$es = date("m/d/Y");
		$event_start = $es." 12:00:00";
		$smarty->assign('eword', "今天活动开始");
	}
	$smarty->assign('event_start', "$event_start");
	
	$event_time = "2014-07-29 00:00:01";
	if($now <= $event_time){
		$smarty->assign('eventgo', "no");
	}
	
	//固定引入参数
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	if(isMobile()){
		$smarty->display('Online/kiwa_signupmobile.html');
	}else{
		$smarty->display('Online/kiwa_signup.html');
	}
?>