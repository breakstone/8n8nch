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
	//滚动信息
	$sql_gun2 = "select * from dtb_online where del_flg = 0 and type='kiwa' and checkflag = 1 order by create_date desc";
	$gun2 = $db->QueryArray($sql_gun2);
	$smarty->assign('gun2', $gun2);
	
	$keyword = cleanInput('免费');
	$where= "job_title like '%$keyword%'";
	$job1 = getJobs($where,0,20);
	$smarty->assign('job1', $job1);
	//一周计算
	$startday = date("Y-m-d",strtotime("2014-09-30 00:00:00"));
	
	//$today="2014-12-17";
	$time = $today." 15:00:00";
	if(strtotime("now")>=strtotime($time)){
		$startday = date("Y-m-d",strtotime("+1 day",strtotime($today)));
	}else{
		$startday = $today;
	}
	
	
	$day1 = date("Y-m-d",strtotime("+1 day",strtotime($startday)));
	$day2 = date("Y-m-d",strtotime("+2 day",strtotime($startday)));
	$day3 = date("Y-m-d",strtotime("+3 day",strtotime($startday)));
	$day4 = date("Y-m-d",strtotime("+4 day",strtotime($startday)));
	$day5 = date("Y-m-d",strtotime("+5 day",strtotime($startday)));
	$day6 = date("Y-m-d",strtotime("+6 day",strtotime($startday)));
	$day7 = date("Y-m-d",strtotime("+7 day",strtotime($startday)));

	/////
	$baday=array($startday,$day1, $day2, $day3, $day4, $day5, $day6, $day7);
	//未来七日
	$smarty->assign('day0', $startday);
	$smarty->assign('day1', $day1);
	$smarty->assign('day2', $day2);
	$smarty->assign('day3', $day3);
	$smarty->assign('day4', $day4);
	$smarty->assign('day5', $day5);
	$smarty->assign('day6', $day6);
	$smarty->assign('day7', $day7);
	//星期几
	$week = array("日", "月", "火", "水", "木", "金", "土");
	
	$datetime = new DateTime($startday);
	$w0 = (int)$datetime->format('w');
	$smarty->assign('week0', $week[$w0]);
	
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
	
	$weekarr=array($w0,$w1, $w2, $w3, $w4, $w5, $w6, $w7);
	//---------------------------------------------------------
	// 活动一  处理程序
	//---------------------------------------------------------	
	for ($i=1;$i<8;$i++){
		if($weekarr[$i]==1||$weekarr[$i]==5){
				$smarty->assign('event1_'.$i, 50);
		}
		if($weekarr[$i]==2||$weekarr[$i]==3||$weekarr[$i]==4){
			$smarty->assign('event1_'.$i, 50);
		}
		if($weekarr[$i]==0||$weekarr[$i]==6){
				$smarty->assign('event1_'.$i, 50);
		}
		if($baday[$i]>"2014-12-31"){
			if($weekarr[$i]==1||$weekarr[$i]==5){
				$smarty->assign('event1_'.$i, 16);
			}
			if($weekarr[$i]==2||$weekarr[$i]==3||$weekarr[$i]==4){
				$smarty->assign('event1_'.$i, 18);
			}
			if($weekarr[$i]==0||$weekarr[$i]==6){
				$smarty->assign('event1_'.$i, 17);
			}
		}
	}
	//---------------------------------------------------------
	// 活动二  处理程序
	//---------------------------------------------------------
	//每天人数
	for ($i=1;$i<8;$i++){
		if($weekarr[$i]==1||$weekarr[$i]==5){
			$smarty->assign('event2_'.$i, 4);
		}
		if($weekarr[$i]==2||$weekarr[$i]==3||$weekarr[$i]==4){
			$smarty->assign('event2_'.$i, 3);
		}
		if($weekarr[$i]==0||$weekarr[$i]==6){
			$smarty->assign('event2_'.$i, 5);
		}
	}
	//--------------------------------------------------------------//
	//---------------------------------------------------------
	// 活动三  处理程序
	//---------------------------------------------------------
	//每天人数
	for ($i=0;$i<7;$i++){
		if($weekarr[$i]==3||$weekarr[$i]==5){
			$smarty->assign('event3_'.$i, 14);
		}
		if($weekarr[$i]==2||$weekarr[$i]==4){
			$smarty->assign('event3_'.$i, 12);
		}
		if($weekarr[$i]==1||$weekarr[$i]==0||$weekarr[$i]==6){
			$smarty->assign('event3_'.$i,13);
		}
		if($baday[$i]>"2014-12-31"){
			$smarty->assign('event3_'.$i, 0);
		}
	}
	
	// 活动四 处理程序
	$startday_4="2015-01-29";
	$day2_4 = date("Y-m-d",strtotime("+1 day",strtotime($startday_4)));
	$day3_4 = date("Y-m-d",strtotime("+2 day",strtotime($startday_4)));
	$day4_4 = date("Y-m-d",strtotime("+3 day",strtotime($startday_4)));
	$baday_4=array('',$startday_4,$day2_4, $day3_4,$day4_4);
	
	// 活动6  处理程序
	$baday_6 = array("","2015-02-02","2015-02-03");
	
	//未来七日
	$smarty->assign('day1_4', $startday_4);
	$smarty->assign('day2_4', $day2_4);
	$smarty->assign('day3_4', $day3_4);
	$smarty->assign('day4_4', $day4_4);
	//---------------------------------------------------------
	$datetime = new DateTime($startday_4);
	$w1 = (int)$datetime->format('w');
	$smarty->assign('week1_4', $week[$w1]);
	$datetime = new DateTime($day2_4);
	$w2 = (int)$datetime->format('w');
	$smarty->assign('week2_4', $week[$w2]);
	$datetime = new DateTime($day3_4);
	$w3 = (int)$datetime->format('w');
	$smarty->assign('week3_4', $week[$w3]);
	$datetime = new DateTime($day4_4);
	$w4 = (int)$datetime->format('w');
	$smarty->assign('week4_4', $week[$w4]);
	$weekarr_4=array('',$w1, $w2, $w3, $w4);
	
	$now=strtotime("now");

	$time1="2015-01-28 15:00:00"   ;
	
	if($now>=strtotime($time1)){
		$smarty->assign('day1_4', 0);
	}
	$time2="2015-01-29 15:00:00";
	if($now>=strtotime($time2)){
		$smarty->assign('day2_4', 0);
	}
	
	$time3="2015-01-30 15:00:00";
	if($now>=strtotime($time3)){
		$smarty->assign('day3_4', 0);
	}
	$time4="2015-01-31 15:00:00";
	if($now>=strtotime($time4)){
		$smarty->assign('day4_4', 0);
	}
	$smarty->assign('event4_1', 11);
	$smarty->assign('event4_2', 11);
	$smarty->assign('event4_3', 16);
	$smarty->assign('event4_4', 17);
	
	$smarty->assign('event5_1', 10);
	
	$smarty->assign('event6_1', 5);
	$smarty->assign('event6_2', 5);
	//报名1 POST
	for ($i=1;$i<3;$i++){
		for ($j=1;$j<8;$j++){	
			$post='wd'.$i.'_'.$j;
			if(isset($_POST[$post])){
				if (week2($user_id,$baday[$j])){
					if (weeky($user_id,$baday[$j])){
						if ($i==1){
							wd($user_id,$baday[$j],$i);
							header('Location:'.APP_URL.'online/event2/#bm');
						}
						if($i==2){
							$sql_sex = "select sex from dtb_online where user_id = '$user_id'";
							$sex1 = $db->QueryRow($sql_sex);
							$sql_userid =  "select user_id from dtb_online_kiwaevent where work_date = '$baday[$j]' and del_flg = 0 and baoming_type = 1 and event = 2";
							$userid= $db->QueryRow($sql_userid);
							$userid=$userid['user_id'];
							if (!empty($userid)){
								$sql_sex2 = "select sex from dtb_online where user_id = '$userid'";
								$sex2 = $db->QueryRow($sql_sex2);
								if($sex2['sex'] =='女' && $sex1['sex']=='女'){
									echo '<script>
											alert("尊敬的用户您好！\n由于该现场只需要一名女生,\n今天已报一名'.$sex2['sex'].'生,\n请选择其他日期或其他现场。");
													';
									echo '</script>';
								}else{
									wd($user_id,$baday[$j],$i);
									header('Location:'.APP_URL.'online/event2/#bm');
								}
							}else{
								wd($user_id,$baday[$j],$i);
								header('Location:'.APP_URL.'online/event2/#bm');
							}
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
	//报名3 POST
	for ($i=3;$i<4;$i++){
		for ($j=0;$j<7;$j++){
			$post='wd'.$i.'_'.$j;
			if(isset($_POST[$post])){
				if (week2($user_id,$baday[$j])){
					if (weeky($user_id,$baday[$j])){
						if ($i==3){
							wd($user_id,$baday[$j],$i);
							header('Location:'.APP_URL.'online/event2/#bm');
						}
					}else{
						echo '<script>
							 alert("尊敬的用户您好！\n不可以连续两天报名。");
								';
						echo '</script>';
					}
				}else{
					echo '<script>
							alert("尊敬的用户您好！\n七天之内只可以报名两次。");';
					echo '</script>';
				}
			}
				
		}
	}
	//报名4 POST
	for($i = 4; $i < 5; $i ++) {
		for($j = 1; $j < 5; $j ++) {
			$post = 'wd' . $i . '_' . $j;
			if (isset ( $_POST [$post] )) {
				if (week2 ($user_id,$baday_4[$j] )) {
					if (weeky ($user_id,$baday_4[$j])) {
						wd ($user_id,$baday_4[$j], $i );
						header ( 'Location:' . APP_URL . 'online/event2/#bm' );
					} else {
						echo '<script>
								alert("尊敬的用户您好！\n不可以连续两天报名。");
								';
						echo '</script>';
					}
				} else {
					echo '<script>
							alert("尊敬的用户您好！\n七天之内只可以报名两次。");';
					echo '</script>';
				}
			}
		}
	}
	
	//活动5 post
	if(isset($_POST["wd5_1"])){
		if (week2 ($user_id,"2015-02-02")) {
			if (weeky ($user_id,"2015-02-02")) {
				wd ($user_id,"2015-02-02",5);
				header ( 'Location:' . APP_URL . 'online/event2/#bm' );
			} else {
				echo '<script>alert("尊敬的用户您好！\n不可以连续两天报名。");';
				echo '</script>';
			}
		} else {
			echo '<script>alert("尊敬的用户您好！\n七天之内只可以报名两次。");';
			echo '</script>';
		}
	}
	
	//活动6 POST
	for($i = 6; $i < 7; $i ++) {
		for($j = 1; $j < 3; $j ++) {
			$post = 'wd' . $i . '_' . $j;
			if (isset ( $_POST [$post] )) {
				if (week2($user_id,$baday_6[$j])) {
					if (weeky($user_id,$baday_6[$j])) {
						wd($user_id,$baday_6[$j], $i);
						header('Location:' . APP_URL . 'online/event2/#bm');
					} else {
						echo '<script>alert("尊敬的用户您好！\n不可以连续两天报名。");';
						echo '</script>';
					}
				} else {
					echo '<script>alert("尊敬的用户您好！\n七天之内只可以报名两次。");';
					echo '</script>';
				}
			}
		}
	}
	
	//wd_tibu
	for ($i=1;$i<3;$i++){
		for ($j=1;$j<8;$j++){
			$post='wd'.$i.'_'.$j.'_tibu';
			if(isset($_POST[$post])){
				if ($i==4){
					wd_tibu($user_id,$baday[$j],$i);
					header('Location:'.APP_URL.'online/event2/#bm');
				}else{
					wd_tibu($user_id,$baday[$j],$i);
					header('Location:'.APP_URL.'online/event2/#bm');
				}
					
			}
		}
	}
	//替补3
	for ($i=3;$i<4;$i++){
		for ($j=0;$j<7;$j++){
			$post='wd'.$i.'_'.$j.'_tibu';
			if(isset($_POST[$post])){
				if ($i==3){
					wd_tibu($user_id,$baday[$j],$i);
					header('Location:'.APP_URL.'online/event2/#bm');
				}
			}
		}
	}
	//替补4
	for($i = 4; $i < 5; $i ++) {
		for($j = 1; $j < 5; $j ++) {
			$post = 'wd'.$i.'_'.$j.'_tibu';
			if (isset ($_POST[$post])) {
				wd_tibu ($user_id,$baday_4[$j], $i );
				header ( 'Location:' . APP_URL . 'online/event2/#bm' );

			}
		}
	}
	
	//替补5
	for($i = 5; $i < 6; $i ++) {
		for($j = 1; $j < 2; $j ++) {
			$post = 'wd'.$i.'_'.$j.'_tibu';
			if (isset ($_POST[$post])) {
				wd_tibu ($user_id,"2015-02-02", $i );
				header ( 'Location:' . APP_URL . 'online/event2/#bm' );
	
			}
		}
	}
	//替补6
	for($i = 6; $i < 7; $i ++) {
		for($j = 1; $j < 3; $j ++) {
			$post = 'wd'.$i.'_'.$j.'_tibu';
			if (isset ($_POST[$post])) {
				wd_tibu ($user_id,$baday_6[$j], $i );
				header ( 'Location:' . APP_URL . 'online/event2/#bm' );
			}
		}
	}
	
	//查看某人当日是否报名，每天只能报名一次
	if(@$user_id!=""){
		$sql_ol = "select * from dtb_online where user_id = '$user_id' and type='kiwa' and  del_flg = 0";
		$ol = $db->QueryRow($sql_ol);
		$smarty->assign('ol', $ol);

		for ($i=1;$i<3;$i++){
			for ($j=1;$j<8;$j++){
				$smarty->assign('baoming'.$i.'_'.$j, baoming($user_id,$baday[$j],$i));
			}
		}
		for ($i=3;$i<4;$i++){
			for ($j=0;$j<7;$j++){
				$smarty->assign('baoming'.$i.'_'.$j, baoming($user_id,$baday[$j],$i));
			}
		}
		for ($i=4;$i<5;$i++){
			for ($j=1;$j<5;$j++){
				$smarty->assign('baoming'.$i.'_'.$j, baoming($user_id,$baday_4[$j],$i));
			}
		}
		//活动5报名
		$smarty->assign('baoming5_1', baoming($user_id,"2015-02-02",5));
		//活动6报名
		for ($i=6;$i<7;$i++){
			for ($j=1;$j<3;$j++){
				$smarty->assign('baoming'.$i.'_'.$j, baoming($user_id,$baday_6[$j],$i));
			}
		}
	}
	//活动一已报名人数
	for ($i=1;$i<8;$i++){
		if($weekarr[$i]==1||$weekarr[$i]==4){
			$smarty->assign('c1_'.$i, yb($baday[$i],1)+25);
		}
		if($weekarr[$i]==3){
			$smarty->assign('c1_'.$i, yb($baday[$i],1)+28);
		}
		if($weekarr[$i]==2||$weekarr[$i]==5){
			$smarty->assign('c1_'.$i, yb($baday[$i],1)+27);
		}
		if($weekarr[$i]==0||$weekarr[$i]==6){
			$smarty->assign('c1_'.$i, yb($baday[$i],1)+30);
		}
		if($baday[$i]>"2014-12-31"){
			if($weekarr[$i]==1||$weekarr[$i]==5){
				$smarty->assign('c1_'.$i, yb($baday[$i],1)+6);
			}
			if($weekarr[$i]==2||$weekarr[$i]==3||$weekarr[$i]==4){
				$smarty->assign('c1_'.$i, yb($baday[$i],1)+8);
			}
			if($weekarr[$i]==0||$weekarr[$i]==6){
				$smarty->assign('c1_'.$i, yb($baday[$i],1)+7);
			}
		}
	}
	//活动二已报名人数
	for ($i=1;$i<8;$i++){
		if($weekarr[$i]==1||$weekarr[$i]==5){
			$smarty->assign('c2_'.$i, yb($baday[$i],2)+2);
		}
		if($weekarr[$i]==2||$weekarr[$i]==3||$weekarr[$i]==4){
			$smarty->assign('c2_'.$i, yb($baday[$i],2)+1);
		}
		if($weekarr[$i]==0||$weekarr[$i]==6){
			$smarty->assign('c2_'.$i, yb($baday[$i],2)+3);
		}
	}
	//活动三已报名人数
	for ($i=0;$i<7;$i++){
		if($weekarr[$i]==3||$weekarr[$i]==5){
			$smarty->assign('c3_'.$i, yb($baday[$i],3)+4);
		}
		if($weekarr[$i]==2||$weekarr[$i]==4){
			$smarty->assign('c3_'.$i,yb($baday[$i],3)+2);
		}
		if($weekarr[$i]==1||$weekarr[$i]==0||$weekarr[$i]==6){
			$smarty->assign('c3_'.$i, yb($baday[$i],3)+3);
		}
	}
	// 活动四已报名人数
	for($i = 1; $i < 5; $i ++) {
		if($i==1||$i==2){
			$smarty->assign ( 'c4_'.$i, yb ($baday_4[$i],4)+6);
		}
		if($i==3){
			$smarty->assign ( 'c4_'.$i, yb ($baday_4[$i],4)+7);
		}
		if($i==4){
			$smarty->assign ( 'c4_'.$i, yb ($baday_4[$i],4)+8);
		}
	}

	// 活动五已报名人数
	for($i = 1; $i < 2; $i ++) {
		if($i==1){
			$smarty->assign ( 'c5_'.$i,yb("2015-02-02",5));
		}
	}
	
	// 活动六已报名人数
	for($i = 1; $i < 3; $i ++) {
		if($i==1){
			$smarty->assign ( 'c6_'.$i,yb($baday_6[$i],6));
		}
		if($i==2){
			$smarty->assign ( 'c6_'.$i,yb($baday_6[$i],6)+5);
		}
	}

	//备用1人数
	for ($i=1;$i<3;$i++){
		for ($j=1;$j<8;$j++){
			$smarty->assign('c'.$i.'_'.$j.'_beiyong', by($baday[$j],$i));
		}
	}
	//备用3人数
	for ($i=3;$i<4;$i++){
		for ($j=0;$j<7;$j++){
			$smarty->assign('c'.$i.'_'.$j.'_beiyong', by($baday[$j],$i));
		}
	}
	//备用4人数
	for($i = 4; $i < 5; $i ++) {
		for($j = 1; $j < 5; $j ++) {
			
			$smarty->assign ('c'.$i.'_'.$j.'_beiyong', by($baday_4[$j], $i));
		}
	}
	//备用5
	$smarty->assign ('c5_1_beiyong', by("2015-02-02", 5));
	
	//备用6人数
	for($i = 6; $i < 7; $i ++) {
		for($j = 1; $j < 3; $j ++) {
			$smarty->assign ('c'.$i.'_'.$j.'_beiyong', by($baday_6[$j], $i));
		}
	}
	
	//--------------------------------------------------------------//
		
	//判断是否超过十次
	if(@$user_id!=""){
		$sql_10 = "select count(*) from dtb_online_kiwaevent where user_id = '$user_id' and del_flg = 0 and baoming_type = 1";
		$count = $db->QueryItem($sql_10);
		if($count > 7){
			$smarty->assign('count10', $count);
		}
	}

	//固定引入参数
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	if(isMobile()){
		$smarty->display('Online/kiwa_event2mobile.html');
	}else{
		$smarty->display('Online/kiwa_event2.html');
	}
	//$smarty->display('Online/kiwa_event2mobile.html');
	//一周只能报名2次
	function week2($user_id,$day){
		global $db;
		
		$weekstart=date("Y-m-d",strtotime("-6 day",strtotime($day)));
		$weekend=date("Y-m-d",strtotime("+6 day",strtotime($day)));
		$week_sql = "select count(*) from dtb_online_kiwaevent where work_date >= '$weekstart' and work_date <= '$weekend' and  user_id = '$user_id' and del_flg = 0 and baoming_type = 1";
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
// 		$week_sql = "select count(*) from dtb_online_kiwaevent where work_date >= '$weekstart' and work_date <= '$weekend' and  user_id = '$user_id' and del_flg = 0 and baoming_type = 1";
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
		$week_sql = "select count(*) from dtb_online_kiwaevent where  work_date = '$day' and  user_id = '$user_id' and del_flg = 0 and baoming_type = 1";
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