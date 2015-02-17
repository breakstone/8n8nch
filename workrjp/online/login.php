<?php
// config
if(!file_exists('../_config/config.php'))
{
	die('[index.php] _config/config.php not found');
}
require_once '../_config/config.php';
require_once '../_includes/functions.php';

//注册个根据id查找name的方法
$smarty->register_function("getname","getName");

if(@$_SESSION['kiwa_companyid']=="141113009425"){//喜和工业
	$today = date("Y-m-d");
	if(isset($_POST["del"])){
		$id = $_POST["del"];
		$sql_online = "update dtb_online_kiwaevent set del_flg = 1 where Id='$id'";
		$db->Execute($sql_online);
		header('Location:'.APP_URL.'online/login/');
	}
	//活动一，面厂临时工作
	/*$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$today' and dtb_online_kiwaevent.event = 1 and dtb_online_kiwaevent.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_online = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_online', $kiwa_online);*/
	$startday = date("Y-m-d",strtotime("2014-09-30 00:00:00"));
	
	if(strtotime("now")>=strtotime("2014-09-30 00:00:00"))
	{
		//$startday = date("Y-m-d",strtotime("+1 day",strtotime($today)));
		$startday = $today;
	}
	
	//新活动，一周计算
	$day1 = date("Y-m-d",strtotime("+1 day",strtotime($startday)));
	$day2 = date("Y-m-d",strtotime("+2 day",strtotime($startday)));
	$day3 = date("Y-m-d",strtotime("+3 day",strtotime($startday)));
	$day4 = date("Y-m-d",strtotime("+4 day",strtotime($startday)));
	$day5 = date("Y-m-d",strtotime("+5 day",strtotime($startday)));
	$day6 = date("Y-m-d",strtotime("+6 day",strtotime($startday)));
	$day7 = date("Y-m-d",strtotime("+7 day",strtotime($startday)));
	$day8 = date("Y-m-d",strtotime("+8 day",strtotime($startday)));
	$smarty->assign('today', $startday);
	$smarty->assign('day1', $day1);
	$smarty->assign('day2', $day2);
	$smarty->assign('day3', $day3);
	$smarty->assign('day4', $day4);
	$smarty->assign('day5', $day5);
	$smarty->assign('day6', $day6);
	$smarty->assign('day7', $day7);
	$smarty->assign('day8', $day8);
	
	$baday=array($startday,$day1, $day2, $day3, $day4, $day5, $day6, $day7,$day8);
	//$today= date("Y-m-d",strtotime("-1 day",strtotime($startday)));
	/////////////////////
	//活动一
	//今天
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$today' and dtb_online_kiwaevent.event = 1 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event1_0 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event1_0', $kiwa_event1_0);
	//day1
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$day1' and dtb_online_kiwaevent.event = 1 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event1_1 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event1_1', $kiwa_event1_1);
	//day2
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$day2' and dtb_online_kiwaevent.event = 1 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event1_2 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event1_2', $kiwa_event1_2);
	//day3
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$day3' and dtb_online_kiwaevent.event = 1 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event1_3 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event1_3', $kiwa_event1_3);
	//day4
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$day4' and dtb_online_kiwaevent.event = 1 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event1_4 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event1_4', $kiwa_event1_4);
	//day5
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$day5' and dtb_online_kiwaevent.event = 1 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event1_5 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event1_5', $kiwa_event1_5);
	//day6
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$day6' and dtb_online_kiwaevent.event = 1 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event1_6 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event1_6', $kiwa_event1_6);
	//day7
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$day7' and dtb_online_kiwaevent.event = 1 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event1_7 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event1_7', $kiwa_event1_7);
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$day8' and dtb_online_kiwaevent.event = 1 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event1_8 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event1_8', $kiwa_event1_8);
	/////////////////////
	//活动二
	//今天
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$today' and dtb_online_kiwaevent.event = 2 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event2_0 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event2_0', $kiwa_event2_0);
	//day1
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$day1' and dtb_online_kiwaevent.event = 2 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event2_1 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event2_1', $kiwa_event2_1);
	//day2
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$day2' and dtb_online_kiwaevent.event = 2 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event2_2 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event2_2', $kiwa_event2_2);
	//day3
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$day3' and dtb_online_kiwaevent.event = 2 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event2_3 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event2_3', $kiwa_event2_3);
	//day4
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$day4' and dtb_online_kiwaevent.event = 2 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event2_4 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event2_4', $kiwa_event2_4);
	//day5
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$day5' and dtb_online_kiwaevent.event = 2 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event2_5 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event2_5', $kiwa_event2_5);
	//day6
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$day6' and dtb_online_kiwaevent.event = 2 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event2_6 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event2_6', $kiwa_event2_6);
	//day7
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$day7' and dtb_online_kiwaevent.event = 2 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event2_7 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event2_7', $kiwa_event2_7);
	//day8
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$day8' and dtb_online_kiwaevent.event = 2 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event2_8 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event2_8', $kiwa_event2_8);
	/////////////////////////////////////////////////////////////////////
	//活动三
	//今天
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$today' and dtb_online_kiwaevent.event = 3 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event3_0 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event3_0', $kiwa_event3_0);
	//day1
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$day1' and dtb_online_kiwaevent.event = 3 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event3_1 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event3_1', $kiwa_event3_1);
	//day2
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$day2' and dtb_online_kiwaevent.event = 3 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event3_2 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event3_2', $kiwa_event3_2);
	//day3
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$day3' and dtb_online_kiwaevent.event = 3 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event3_3 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event3_3', $kiwa_event3_3);
	//day4
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$day4' and dtb_online_kiwaevent.event = 3 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event3_4 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event3_4', $kiwa_event3_4);
	//day5
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$day5' and dtb_online_kiwaevent.event = 3 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event3_5 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event3_5', $kiwa_event3_5);
	//day6
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$day6' and dtb_online_kiwaevent.event = 3 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event3_6 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event3_6', $kiwa_event3_6);
	//day7
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$day7' and dtb_online_kiwaevent.event = 3 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event3_7 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event3_7', $kiwa_event3_7);
	//day8
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$day8' and dtb_online_kiwaevent.event = 3 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event3_8 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event3_8', $kiwa_event3_8);
	
	$startday_4="2015-01-29";
	$day1_4=$startday_4;
	$day2_4 = date("Y-m-d",strtotime("+1 day",strtotime($startday_4)));
	$day3_4 = date("Y-m-d",strtotime("+2 day",strtotime($startday_4)));
	$day4_4 = date("Y-m-d",strtotime("+3 day",strtotime($startday_4)));
	//活动四
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$day1_4' and dtb_online_kiwaevent.event = 4 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event3_5 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event4_0', $kiwa_event3_5);
	//day6
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$day2_4' and dtb_online_kiwaevent.event = 4 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event3_6 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event4_1', $kiwa_event3_6);
	//day7
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$day3_4' and dtb_online_kiwaevent.event = 4 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event3_7 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event4_2', $kiwa_event3_7);
	
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$day4_4' and dtb_online_kiwaevent.event = 4 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event4_3 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event4_3', $kiwa_event4_3);
	
	for ($j=0;$j<3;$j++){
		$smarty->assign('kiwa_event4_'.$j, xianshi($baday[$j],"4"));
		//echo $baday[$j];
	}
	
	//活动五
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='2015-02-02' and dtb_online_kiwaevent.event = 5 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event5_0 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event5_0', $kiwa_event5_0);
	$smarty->assign('event5_name', "新町便当厂白班");
	
	
	//活动六
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='2015-02-02' and dtb_online_kiwaevent.event = 6 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event6_0 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event6_0', $kiwa_event6_0);
	
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='2015-02-03' and dtb_online_kiwaevent.event = 6 and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event6_1 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event6_1', $kiwa_event6_1);
	
	$smarty->assign('event6_name', "新町便当厂夜班");
	

	$smarty->assign('event4_name', "カネショク");
	$smarty->assign('event3_name', "芳野台夜班");
	
	
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	$smarty->display('Online/kiwasee.html');
	
}elseif(@$_SESSION['kiwa_companyid']=="071445274801"){//ways
	
	$sql_online = "select * from dtb_online where del_flg = 0 and type = 'ways'";
	$ways_online = $db->QueryArray($sql_online);
	$smarty->assign('ways_online', $ways_online);
	
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	$smarty->display('Online/wayssee.html');
	
	
}elseif(@$_SESSION['kiwa_companyid']=="161559139290"){//ess
	
	$sql_online = "select * from dtb_online where del_flg = 0 and type = 'ess'";
	$ess_online = $db->QueryArray($sql_online);
	$smarty->assign('ess_online', $ess_online);
	
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	$smarty->display('Online/esssee.html');
	
}elseif(@$_SESSION['kiwa_companyid']=="000001"){//8n8n
	
	if(isset($_POST["check_ok"])){
		$user_id = $_POST["check_ok"];
		$sql_online = "update dtb_online set checkflag = 1 where user_id='$user_id' and del_flg = 0 and type = 'kiwa'";
		$db->Execute($sql_online);
		header('Location:'.APP_URL.'online/login/');
	}
	
	$sql_online = "select * from dtb_online where del_flg = 0 and type = 'kiwa'";
	$b_online = $db->QueryArray($sql_online);
	$smarty->assign('b_online', $b_online);
	
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	$smarty->display('Online/8n8nsee.html');
	
}elseif(@$_SESSION['kiwa_userid']=="031758092846"){//庞涛
	
	$sql_online = "select * from dtb_online where del_flg = 0 and type = 'kiwa_net' order by pinyin desc";
	//echo $sql_online;
	$kiwa_net = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_net', $kiwa_net);
	
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	$smarty->display('Online/kiwa_netsee.html');
	
}elseif( @$_SESSION['kiwa_companyid']=="021144338093"){//传单||@$_SESSION['kiwa_companyid']=="151007451666" 
	if(isset($_POST["del"])){
		$id = $_POST["del"];
		$sql_online = "update dtb_online_kiwaevent set del_flg = 1 where Id='$id'";
		$db->Execute($sql_online);
		header('Location:'.APP_URL.'online/login/');
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
	for ($j=1;$j<11;$j++)
	{
		$smarty->assign('kiwa_event1_'.$j, xianshi($baday[$j],"cd"));
	}
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	$smarty->display('Online/cd_event_see.html');
	
}elseif(@$_SESSION['kiwa_companyid']=="151007451666"){
	$today = date("Y-m-d");
	if(isset($_POST["del"])){
		$id = $_POST["del"];
		$sql_online = "update dtb_online_guanxievent set del_flg = 1 where Id='$id'";
		$db->Execute($sql_online);
		header('Location:'.APP_URL.'online/login/');
	}

	$startday = date("Y-m-d",strtotime("2014-12-20 00:00:00"));
	
	if(strtotime("now")>=strtotime("2014-12-20 00:00:00"))
	{
		//$startday = date("Y-m-d",strtotime("+1 day",strtotime($today)));
		$startday = $today;
	}
	//新活动，一周计算
	$day1 = date("Y-m-d",strtotime("+1 day",strtotime($startday)));
	$day2 = date("Y-m-d",strtotime("+2 day",strtotime($startday)));
	$day3 = date("Y-m-d",strtotime("+3 day",strtotime($startday)));
	$day4 = date("Y-m-d",strtotime("+4 day",strtotime($startday)));
	$day5 = date("Y-m-d",strtotime("+5 day",strtotime($startday)));
	$day6 = date("Y-m-d",strtotime("+6 day",strtotime($startday)));
	$day7 = date("Y-m-d",strtotime("+7 day",strtotime($startday)));
	$day8 = date("Y-m-d",strtotime("+8 day",strtotime($startday)));
	$smarty->assign('today', $startday);
	$smarty->assign('day1', $day1);
	$smarty->assign('day2', $day2);
	$smarty->assign('day3', $day3);
	$smarty->assign('day4', $day4);
	$smarty->assign('day5', $day5);
	$smarty->assign('day6', $day6);
	$smarty->assign('day7', $day7);
	$smarty->assign('day8', $day8);
/////////////////////
//活动二
//今天
	$sql_online = "select * from dtb_online,dtb_online_guanxievent where dtb_online_guanxievent.user_id = dtb_online.user_id and type = 'guanxi' and work_date='$today' and dtb_online_guanxievent.event = 2 and dtb_online_guanxievent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_guanxievent.create_date";
	$kiwa_event2_0 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event2_0', $kiwa_event2_0);
	//day1
	$sql_online = "select * from dtb_online,dtb_online_guanxievent where dtb_online_guanxievent.user_id = dtb_online.user_id and type = 'guanxi' and work_date='$day1' and dtb_online_guanxievent.event = 2 and dtb_online_guanxievent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_guanxievent.create_date";
	$kiwa_event2_1 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event2_1', $kiwa_event2_1);
	//day2
	$sql_online = "select * from dtb_online,dtb_online_guanxievent where dtb_online_guanxievent.user_id = dtb_online.user_id and type = 'guanxi' and work_date='$day2' and dtb_online_guanxievent.event = 2 and dtb_online_guanxievent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_guanxievent.create_date";
	$kiwa_event2_2 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event2_2', $kiwa_event2_2);
	//day3
	$sql_online = "select * from dtb_online,dtb_online_guanxievent where dtb_online_guanxievent.user_id = dtb_online.user_id and type = 'guanxi' and work_date='$day3' and dtb_online_guanxievent.event = 2 and dtb_online_guanxievent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_guanxievent.create_date";
	$kiwa_event2_3 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event2_3', $kiwa_event2_3);
	//day4
	$sql_online = "select * from dtb_online,dtb_online_guanxievent where dtb_online_guanxievent.user_id = dtb_online.user_id and type = 'guanxi' and work_date='$day4' and dtb_online_guanxievent.event = 2 and dtb_online_guanxievent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_guanxievent.create_date";
	$kiwa_event2_4 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event2_4', $kiwa_event2_4);
	//day5
	$sql_online = "select * from dtb_online,dtb_online_guanxievent where dtb_online_guanxievent.user_id = dtb_online.user_id and type = 'guanxi' and work_date='$day5' and dtb_online_guanxievent.event = 2 and dtb_online_guanxievent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_guanxievent.create_date";
	$kiwa_event2_5 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event2_5', $kiwa_event2_5);
	//day6
	$sql_online = "select * from dtb_online,dtb_online_guanxievent where dtb_online_guanxievent.user_id = dtb_online.user_id and type = 'guanxi' and work_date='$day6' and dtb_online_guanxievent.event = 2 and dtb_online_guanxievent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_guanxievent.create_date";
	$kiwa_event2_6 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event2_6', $kiwa_event2_6);
	//day7
	$sql_online = "select * from dtb_online,dtb_online_guanxievent where dtb_online_guanxievent.user_id = dtb_online.user_id and type = 'guanxi' and work_date='$day7' and dtb_online_guanxievent.event = 2 and dtb_online_guanxievent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_guanxievent.create_date";
	$kiwa_event2_7 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event2_7', $kiwa_event2_7);
	//day8
	$sql_online = "select * from dtb_online,dtb_online_guanxievent where dtb_online_guanxievent.user_id = dtb_online.user_id and type = 'guanxi' and work_date='$day8' and dtb_online_guanxievent.event = 2 and dtb_online_guanxievent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_guanxievent.create_date";
	$kiwa_event2_8 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event2_8', $kiwa_event2_8);
	
	$event_day="2014-12-29";
	$sql_online = "select * from dtb_online,dtb_online_guanxievent where dtb_online_guanxievent.user_id = dtb_online.user_id and type = 'guanxi' and work_date='$event_day' and dtb_online_guanxievent.event = 1 and dtb_online_guanxievent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_guanxievent.create_date";
	$kiwa_event1 = $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event1', $kiwa_event1);
	
	$sql_online = "select * from dtb_online,dtb_online_guanxievent where dtb_online_guanxievent.user_id = dtb_online.user_id and type = 'guanxi' and work_date='$event_day' and dtb_online_guanxievent.event = 3 and dtb_online_guanxievent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_guanxievent.create_date";
	$kiwa_event3= $db->QueryArray($sql_online);
	$smarty->assign('kiwa_event3', $kiwa_event3);
	$smarty->assign('event_day', $event_day);
	
	$smarty->assign('event2_name', "肉厂");
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	$smarty->display('Online/guanxi_see.html');
	
}else{
	echo "你没有资格！";
	exit;
}
function  xianshi($day,$event)
{
	global $db;
	$sql_online = "select * from dtb_online,dtb_online_kiwaevent where dtb_online_kiwaevent.user_id = dtb_online.user_id and type = 'kiwa' and work_date='$day' and dtb_online_kiwaevent.event = '$event' and dtb_online_kiwaevent.del_flg = 0 and dtb_online.del_flg = 0 order by dtb_online_kiwaevent.create_date";
	$kiwa_event1_0 = $db->QueryArray($sql_online);
	return $kiwa_event1_0;
}

?>