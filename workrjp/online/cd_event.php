<?php
	//人を探す
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	require_once '../_includes/mobile.php';
	//没有Session 跳转到登录页面
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
	$day1 = "2014-10-08";
	$day2 = date("Y-m-d",strtotime("+1 day",strtotime($day1)));
	$day3 = date("Y-m-d",strtotime("+2 day",strtotime($day1)));
	$day4 = date("Y-m-d",strtotime("+3 day",strtotime($day1)));
	$day5 = date("Y-m-d",strtotime("+4 day",strtotime($day1)));
	$day6 = date("Y-m-d",strtotime("+5 day",strtotime($day1)));
	$day7 = date("Y-m-d",strtotime("+6 day",strtotime($day1)));
	$day8 = date("Y-m-d",strtotime("+7 day",strtotime($day1)));
	$day9 = date("Y-m-d",strtotime("+8 day",strtotime($day1)));
	$day10 = date("Y-m-d",strtotime("+9 day",strtotime($day1)));
	/////
	$baday=array('',$day1, $day2, $day3, $day4, $day5, $day6, $day7,$day8,$day9,$day10);
	//未来七日
	$smarty->assign('day1', $day1);
	$smarty->assign('day2', $day2);
	$smarty->assign('day3', $day3);
	$smarty->assign('day4', $day4);
	$smarty->assign('day5', $day5);
	$smarty->assign('day6', $day6);
	$smarty->assign('day7', $day7);
	$smarty->assign('day8', $day8);
	$smarty->assign('day9', $day9);
	$smarty->assign('day10', $day10);
	//星期几
	$week = array("日", "月", "火", "水", "木", "金", "土");
	$datetime = new DateTime($day1);
	$w = (int)$datetime->format('w');
	$smarty->assign('week1', $week[$w]);
	$datetime = new DateTime($day2);
	$w = (int)$datetime->format('w');
	$smarty->assign('week2', $week[$w]);
	$datetime = new DateTime($day3);
	$w = (int)$datetime->format('w');
	$smarty->assign('week3', $week[$w]);
	$datetime = new DateTime($day4);
	$w = (int)$datetime->format('w');
	$smarty->assign('week4', $week[$w]);
	$datetime = new DateTime($day5);
	$w = (int)$datetime->format('w');
	$smarty->assign('week5', $week[$w]);
	$datetime = new DateTime($day6);
	$w = (int)$datetime->format('w');
	$smarty->assign('week6', $week[$w]);
	$datetime = new DateTime($day7);
	$w = (int)$datetime->format('w');
	$smarty->assign('week7', $week[$w]);
	$datetime = new DateTime($day8);
	$w = (int)$datetime->format('w');
	$smarty->assign('week8', $week[$w]);
	$datetime = new DateTime($day9);
	$w = (int)$datetime->format('w');
	$smarty->assign('week9', $week[$w]);
	$datetime = new DateTime($day10);
	$w = (int)$datetime->format('w');
	$smarty->assign('week10', $week[$w]);
	//每天人数
	$event1_1 = 10;
	$event1_2 = 10;
	$event1_3 = 10;
	$event1_4 = 0;
	$event1_5 = 0;
	$event1_6 = 10;
	$event1_7 = 10;
	$event1_8 = 10;
	$event1_9 = 10;
	$event1_10 = 10;
	$smarty->assign('event1_1', $event1_1);
	$smarty->assign('event1_2', $event1_2);
	$smarty->assign('event1_3', $event1_3);
	$smarty->assign('event1_4', $event1_4);
	$smarty->assign('event1_5', $event1_5);
	$smarty->assign('event1_6', $event1_6);
	$smarty->assign('event1_7', $event1_7);
	$smarty->assign('event1_8', $event1_8);
	$smarty->assign('event1_9', $event1_9);
	$smarty->assign('event1_10', $event1_10);
	
	for ($j=1;$j<11;$j++)
	{
		
			$post='wd1'.'_'.$j;
			if(isset($_POST[$post]))
			{
				wd($user_id,$baday[$j],'cd');
				header('Location:'.APP_URL.'online/cdevent/#bm');
			}
	}
	
	//查看某人当日是否报名，每天只能报名一次
	if(@$user_id!="")
	{
		$sql_ol = "select * from dtb_online where user_id = '$user_id' and del_flg = 0";
		$ol = $db->QueryRow($sql_ol);
		$smarty->assign('ol', $ol);

		
			for ($j=1;$j<11;$j++)
			{
				$smarty->assign('baoming1'.'_'.$j, baoming($user_id,$baday[$j],'cd'));
					
			}
		

	}

	//已报人数
	
		for ($j=1;$j<11;$j++)
		{
			$smarty->assign('c1'.'_'.$j, yb($baday[$j],'cd'));
		}
	
	//备用人数
	
		for ($j=1;$j<11;$j++)
		{
			$smarty->assign('c1'.'_'.$j.'_beiyong', by($baday[$j],'cd'));
		}
	
	
	
	//固定引入参数
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	$smarty->display('Online/cd_event.html');
	
	
	
	//查看该用户是否今天报名
	function baoming($user_id,$day,$event){
		global $db;
		$sql_see = "select event from dtb_online_kiwaevent where user_id = '$user_id' and work_date='$day' and del_flg = 0  and event = '$event'";
		$baoming = $db->QueryItem($sql_see);
	
		return $baoming;
	}
	//已报人数
	function yb($day,$event){
		global $db;
	
		$c1_sql = "select count(*) from dtb_online_kiwaevent where work_date = '$day' and event = '$event' and baoming_type = 1 and del_flg = 0";
		$c1_1 = $db->QueryItem($c1_sql);
		return $c1_1;
	
	}
	//备用人数
	function by($day,$event){
		global $db;
	
		$c1_sql_beiyong = "select count(*) from dtb_online_kiwaevent where work_date = '$day' and event = '$event' and baoming_type = 2 and del_flg = 0";
		$c1_1_beiyong = $db->QueryItem($c1_sql_beiyong);
		return $c1_1_beiyong;
	
	}
	//wd
	function wd($user_id,$day,$event){
		global $db;
		if(!empty($user_id))
		{
			$create_date = date("Y-m-d H:i:s");
				
			$sqls = "select count(*) from dtb_online_kiwaevent where user_id = '$user_id' and work_date = '$day' and event = '$event' and baoming_type = 1 and del_flg = 0";
			$ct = $db->QueryItem($sqls);
			if($ct == 0){
				$sql = "insert into dtb_online_kiwaevent set user_id = '$user_id' , work_date = '$day' , event = '$event' , create_date = '$create_date'";
				$db->Execute($sql);
				//发送站内短信
				$message_title="临时工作活动通知";
				$message_content="尊敬的用户，您好！\r\n您已成功报名{$day}的临时工作！\r\n我们担当会尽快跟您联系！";
				message_send_do("000001",$user_id,$message_title,$message_content);
			}
		}
	}
	//wd_tibu
	function wd_tibu($user_id,$day,$event){
		global $db;
		if(!empty($user_id))
		{
			$create_date = date("Y-m-d H:i:s");
			$sqls = "select count(*) from dtb_online_kiwaevent where user_id = '$user_id' and work_date = '$day' and event = '$event' and del_flg = 0";
			$ct = $db->QueryItem($sqls);
			if($ct == 0){
				$sql = "insert into dtb_online_kiwaevent set user_id = '$user_id' , work_date = '$day' , event = '$event' , baoming_type = 2 , create_date = '$create_date'";
				$db->Execute($sql);
			}
		}
	
	}
?>