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
	

	
// 	if(@$user_id!=""){
		
// 		$sql_ol = "select * from dtb_online where user_id = '$user_id' and type='guanxi' and del_flg = 0";
// 		$ol = $db->QueryRow($sql_ol);
// 		$smarty->assign('ol', $ol);
	
// 		//查看某人今天是否报名，每天只能报名一次
// 		$sql_see = "select event from dtb_online_guanxievent where user_id = '$user_id' and del_flg = 0 and baoming_type = 1";
// 		$baoming = $db->QueryItem($sql_see);
// 		$smarty->assign('baoming', $baoming);
// 	}
	
	//滚动信息
	$sql_gun2 = "select * from dtb_online where del_flg = 0 and type='guanxi' and checkflag = 1";
	$gun2 = $db->QueryArray($sql_gun2);
	$smarty->assign('gun2', $gun2);
	$today = date("Y-m-d");
	
	$time = $today." 15:00:00";
	
	if(strtotime("now")>=strtotime($time)){
		$startday = date("Y-m-d",strtotime("+1 day",strtotime($today)));
	}else{
		$startday = $today;
	}
	/*if($today < strtotime("2014-12-20 15:00:00")){
		$startday = "2014-12-20  02:00:00";
	}*/
	
	$day1 = date("Y-m-d",strtotime("+1 day",strtotime($startday)));
	$day2 = date("Y-m-d",strtotime("+2 day",strtotime($startday)));
	$day3 = date("Y-m-d",strtotime("+3 day",strtotime($startday)));
	$day4 = date("Y-m-d",strtotime("+4 day",strtotime($startday)));
	$day5 = date("Y-m-d",strtotime("+5 day",strtotime($startday)));
	$day6 = date("Y-m-d",strtotime("+6 day",strtotime($startday)));
	$day7 = date("Y-m-d",strtotime("+7 day",strtotime($startday)));
	/////
	$baday=array('',$day1, $day2, $day3, $day4, $day5, $day6, $day7);
	
	//未来七日
	$smarty->assign('day1', $day1);
	$smarty->assign('day2', $day2);
	$smarty->assign('day3', $day3);
	$smarty->assign('day4', $day4);
	$smarty->assign('day5', $day5);
	$smarty->assign('day6', $day6);
	$smarty->assign('day7', $day7);
	//星期几
	$week = array("日", "月", "火", "水", "木", "金", "土");
	$datetime = new DateTime($day1);
	$w1 = (int)$datetime->format('w');
	$smarty->assign('week1', $week[$w1]);
	$datetime = new DateTime($day2);
	$w2 = (int)$datetime->format('w');
	$smarty->assign('week2', $week[$w2]);
	$datetime = new DateTime($day3);
	$w3 = (int)$datetime->format('w');
	$smarty->assign('week3', $week[$w3]);
	$datetime = new DateTime($day4);
	$w4 = (int)$datetime->format('w');
	$smarty->assign('week4', $week[$w4]);
	$datetime = new DateTime($day5);
	$w5 = (int)$datetime->format('w');
	$smarty->assign('week5', $week[$w5]);
	$datetime = new DateTime($day6);
	$w6 = (int)$datetime->format('w');
	$smarty->assign('week6', $week[$w6]);
	$datetime = new DateTime($day7);
	$w7 = (int)$datetime->format('w');
	$smarty->assign('week7', $week[$w7]);
	
	$weekarr=array('',$w1, $w2, $w3, $w4, $w5, $w6, $w7);
	//---------------------------------------------------------
	// 活动二  处理程序
	//---------------------------------------------------------
	//每天人数
	for ($i=1;$i<8;$i++)
	{
		if($weekarr[$i]==1||$weekarr[$i]==5)
		{
			$smarty->assign('event2_'.$i, 18);
		}
		if($weekarr[$i]==2||$weekarr[$i]==3||$weekarr[$i]==4)
		{
			$smarty->assign('event2_'.$i, 16);
		}
		if($weekarr[$i]==0||$weekarr[$i]==6)
		{
			$smarty->assign('event2_'.$i, 17);
		}
	}	
	for ($i=2;$i<3;$i++)
	{
		for ($j=1;$j<8;$j++)
		{
			
			$post='wd'.$i.'_'.$j;
			if(isset($_POST[$post]))
			{
				if (week2($user_id,$baday[$j]))
				{
					if (weeky($user_id,$baday[$j]))
						{
							if (baomingre($user_id, $baday[$j]))
							{
					
									if ($i==2)
									{
										wd($user_id,$baday[$j],$i);
										header('Location:'.APP_URL.'online/guanxi/#bm');
									}
					
							}else{
								echo '<script>
									alert("尊敬的用户您好！\n同一天只能报名一个活动。");
									';
								echo '</script>';
							}
						}else{
							echo '<script>
							 alert("尊敬的用户您好！\n不可以连续两天报名。");
							';
							echo '</script>';
						}
				}else{
						echo '<script>
						alert("尊敬的用户您好！\n七天之内只可以报名两次。");
						';
						echo '</script>';
					}
			}
			
		}
	}
	//$event1_day="2014-12-29";
	/*for ($i=1;$i<3;$i++)
	{
		$post='sb'.$i;
		if(isset($_POST[$post])){
			
				if (week2($user_id,$event1_day))
					{
						if (weeky($user_id,$event1_day))
							{
								if (baomingre($user_id, $event1_day))
								{
									if ($i==1)
									{
										wd($user_id,$event1_day,$i);
										header('Location:'.APP_URL.'online/guanxi/#bm');
									}
									if ($i==2)
									{
										wd($user_id,$event1_day,3);
										header('Location:'.APP_URL.'online/guanxi/#bm');
									}
								}else{
									echo '<script>
										alert("尊敬的用户您好！\n同一天只能报名一个活动。");
										';
									echo '</script>';
								}
							}else{
								echo '<script>
								 alert("尊敬的用户您好！\n不可以连续两天报名。");
								';
								echo '</script>';
							}
					}else{
							echo '<script>
							alert("尊敬的用户您好！\n七天之内只可以报名两次。");
							';
							echo '</script>';
						}
		}
	}
	
	for ($i=1;$i<3;$i++)
	{
		$post='sb'.$i.'_tibu';
		if(isset($_POST[$post]))
		{	
			if ($i==1)
			{
				wd_tibu($user_id,$event1_day,1);
			}
			if ($i==2)
			{
				wd_tibu($user_id,$event1_day,3);
			}
		
			header('Location:'.APP_URL.'online/guanxi/#bm');
		}
	}
*/
	
	if(@$user_id!="")
	{
		$sql_ol = "select * from dtb_online where user_id = '$user_id' and type='guanxi' and  del_flg = 0";
		$ol = $db->QueryRow($sql_ol);
		$smarty->assign('ol', $ol);
	
			for ($i=2;$i<3;$i++)
			{
				for ($j=1;$j<8;$j++)
				{
				$smarty->assign('baoming'.$i.'_'.$j, baoming($user_id,$baday[$j],$i));
					
				}
			}
		
		$smarty->assign('event_baoming1',baoming($user_id,$event1_day,1));
		$smarty->assign('event3_baoming',baoming($user_id,$event1_day,3));
	}
	//wd_tibu
	for ($i=2;$i<3;$i++)
	{
		for ($j=1;$j<8;$j++)
		{
				$post='wd'.$i.'_'.$j.'_tibu';
			if(isset($_POST[$post]))
			{
				wd_tibu($user_id,$baday[$j],$i);
				header('Location:'.APP_URL.'online/guanxi/#bm');
			}
		}
	}
// 	//每天人数
// 	$event1 =8;
// 	$smarty->assign('event1', $event1);
	
	//已报人数
// 	$c1_sql = "select count(*) from dtb_online_guanxievent where event = 1 and baoming_type = 1 and del_flg = 0";
// 	$c1 = $db->QueryItem($c1_sql);
// 	$smarty->assign('c1', $c1);
// 	//备用人数
// 	$c1_sql_beiyong = "select count(*) from dtb_online_guanxievent where event = 1 and baoming_type = 2 and del_flg = 0";
// 	$c1_beiyong = $db->QueryItem($c1_sql_beiyong);
// 	$smarty->assign('c1_beiyong', $c1_beiyong);
	
// 	//每天人数
// 	$event3 =8;
// 	$smarty->assign('event3', $event3);
	
// 	//已报人数
// 	$c3_sql = "select count(*) from dtb_online_guanxievent where event = 3 and baoming_type = 1 and del_flg = 0";
// 	$c3 = $db->QueryItem($c3_sql);
// 	$smarty->assign('c3', $c3);
// 	//备用人数
// 	$c3_sql_beiyong = "select count(*) from dtb_online_guanxievent where event = 3 and baoming_type = 2 and del_flg = 0";
// 	$c3_beiyong = $db->QueryItem($c3_sql_beiyong);
// 	$smarty->assign('c3_beiyong', $c3_beiyong);
	//活动二已报名人数
	for ($i=1;$i<8;$i++)
	{
		if($weekarr[$i]==1||$weekarr[$i]==5)
		{
		$smarty->assign('c2_'.$i, yb($baday[$i],2)+3);
		}
		if($weekarr[$i]==2||$weekarr[$i]==3||$weekarr[$i]==4)
		{
		$smarty->assign('c2_'.$i, yb($baday[$i],2)+1);
		}
		if($weekarr[$i]==0||$weekarr[$i]==6)
		{
		$smarty->assign('c2_'.$i, yb($baday[$i],2)+2);
		}
	}

	//备用人数
	for ($i=2;$i<3;$i++)
	{
		for ($j=1;$j<8;$j++)
		{
			$smarty->assign('c'.$i.'_'.$j.'_beiyong', by($baday[$j],$i));
		}
	}
	
	
	//--------------------------------------------------------------//
	//判断是否超过十次
	if(@$user_id!=""){
		$sql_10 = "select count(*) from dtb_online_guanxievent where user_id = '$user_id' and del_flg = 0 and baoming_type = 1";
		$count = $db->QueryItem($sql_10);
		if($count > 7){
		$smarty->assign('count10', $count);
	}
	}
	//固定引入参数
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	if(isMobile()){
		$smarty->display('Online/kiwa_guanximobile.html');
	}else{
		$smarty->display('Online/kiwa_guanxi.html');
	}
	//$smarty->display('Online/kiwa_event2mobile.html');
//一周只能报名2次
	function week2($user_id,$day){
		global $db;
		
		$weekstart=date("Y-m-d",strtotime("-6 day",strtotime($day)));
		$weekend=date("Y-m-d",strtotime("+6 day",strtotime($day)));
		$week_sql = "select count(*) from dtb_online_guanxievent where work_date >= '$weekstart' and work_date <= '$weekend' and  user_id = '$user_id' and del_flg = 0 and baoming_type = 1";
		$count = $db->QueryItem($week_sql);
		if($count > 1){
			return false;
		}
		else {
			return true;
		}
	
		
	}
	function weeky($user_id,$day){
		global $db;
		
		return true;
	
// 		$weekstart=date("Y-m-d",strtotime("-1 day",strtotime($day)));
// 		$weekend=date("Y-m-d",strtotime("+1 day",strtotime($day)));
// 		$week_sql = "select count(*) from dtb_online_guanxievent where work_date >= '$weekstart' and work_date <= '$weekend' and  user_id = '$user_id' and del_flg = 0 and baoming_type = 1";
// 		$count = $db->QueryItem($week_sql);
// 		if($count >= 1){
// 			return false;
// 		}else {
// 			return true;
// 		}
	}
	//同一天不可以报名重复报名
	function baomingre($user_id,$day){
		global $db;
		$week_sql = "select count(*) from dtb_online_guanxievent where  work_date = '$day' and  user_id = '$user_id' and del_flg = 0 and baoming_type = 1";
		$count = $db->QueryItem($week_sql);
		if($count == 1){
			return false;
		}
		else {
			return true;
		}
	}
	//七天报名超过两次的
	function baoming($user_id,$day,$event){
		global $db;
		$sql_see = "select event from dtb_online_guanxievent where user_id = '$user_id' and work_date='$day' and del_flg = 0  and event = '$event'";
		$baoming = $db->QueryItem($sql_see);
		
		return $baoming;
	}
	//已报人数
	function yb($day,$event){
		global $db;

		$c1_sql = "select count(*) from dtb_online_guanxievent where work_date = '$day' and event = '$event' and baoming_type = 1 and del_flg = 0";
		$c1_1 = $db->QueryItem($c1_sql);
		return $c1_1;
	
	}
	//备用人数
	function by($day,$event){
		global $db;
	
		$c1_sql_beiyong = "select count(*) from dtb_online_guanxievent where work_date = '$day' and event = '$event' and baoming_type = 2 and del_flg = 0";
		$c1_1_beiyong = $db->QueryItem($c1_sql_beiyong);
		return $c1_1_beiyong;
	
	}
	//wd
	function wd($user_id,$day,$event){
		global $db;
		if(!empty($user_id))
		{
			$create_date = date("Y-m-d H:i:s");
			
			$sqls = "select count(*) from dtb_online_guanxievent where user_id = '$user_id' and work_date = '$day' and event = '$event' and baoming_type = 1 and del_flg = 0";
			$ct = $db->QueryItem($sqls);
			if($ct == 0){
			$sql = "insert into dtb_online_guanxievent set user_id = '$user_id' , work_date = '$day' , event = '$event' , create_date = '$create_date'";
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
			$sqls = "select count(*) from dtb_online_guanxievent where user_id = '$user_id' and work_date = '$day' and event = '$event' and del_flg = 0";
			$ct = $db->QueryItem($sqls);
			if($ct == 0){
				$sql = "insert into dtb_online_guanxievent set user_id = '$user_id' , work_date = '$day' , event = '$event' , baoming_type = 2 , create_date = '$create_date'";
				$db->Execute($sql);
			}
		}
		
	}
?>