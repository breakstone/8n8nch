<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	require_once '../_includes/SubPages.php';
	$user = getUser($_SESSION["kiwa_userid"]);
	$smarty->assign('user', $user);
	
	$lives = getLives("user_id = '$_SESSION[kiwa_userid]'",0,12);
	
	if(isset($_REQUEST["live_service"])&&$_REQUEST["live_service"]!=""){
		$live_id = $_REQUEST["live_service"];
	}else{
		$live_id = $lives[0]["live_id"];
	}
	$smarty->assign('live_id', $live_id);
	
	
	if(isset($_REQUEST["flg"])&&$_REQUEST["flg"]!=""){
		if($_REQUEST["flg"]==1){
			$smarty->assign('lactive', "active");
			$smarty->assign('ldispaly', "block;");
			$smarty->assign('jactive', "");
			$smarty->assign('jdispaly', "none;");
		}
		if($_REQUEST["flg"]==2){
			$smarty->assign('lactive', "");
			$smarty->assign('ldispaly', "none;");
			$smarty->assign('jactive', "active");
			$smarty->assign('jdispaly', "block;");
		}
	}else{
		$smarty->assign('lactive', "active");
		$smarty->assign('ldispaly', "block;");
		$smarty->assign('jactive', "");
		$smarty->assign('jdispaly', "none;");
	}
	
	
	$smarty->assign('lives', $lives);
	#######################################################
	#  匹配数据
	#######################################################
	$livep = getLives("live_id = '$live_id'",0,1);
	
	//供需匹配
	if($livep[0]["live_want"]==0){
		$live_want = 1;
	}elseif($livep[0]["live_want"]==1){
		$live_want = 0;
	}
	//分类匹配
	$service_id = $livep[0]["service_id"];
	//区域
	if($livep[0]["area_cd"]!=""){
		$ac = $livep[0]["area_cd"];
		$area_cd = "and area_cd='$ac'";
	}else{
		$area_cd = "";
	}
	//县城
	if($livep[0]["pref_cd"]!=""){
		$pc = $livep[0]["pref_cd"];
		$pref_cd = "and pref_cd='$pc'";
	}else{
		$pref_cd = "";
	}
	//路线
	if($livep[0]["line_num"]!=""){
		$ln = explode(",",$livep[0]["line_num"]);
		$line_num = "and (";
		foreach ($ln as $v){
			$line_num .= "line_num like '%$v%' or ";
		}
		$line_num = rtrim($line_num,"or ");
		$line_num .= ")";
	}else{
		$line_num = "";
	}
	//駅
	if($livep[0]["station_cd"]!=""){
		$sc = explode(",",$livep[0]["station_cd"]);
		$station_cd = "and (";
		foreach ($sc as $v){
			$station_cd .= "station_cd like '%$v%' or ";
		}
		$station_cd = rtrim($station_cd,"or ");
		$station_cd .= ")";
	}else{
		$station_cd = "";
	}
	//类别
	if($livep[0]["type"]!=""){
		$tp = explode(",",$livep[0]["type"]);
		$type = "and (";
		foreach ($tp as $v){
			$type .= "type like '%$v%' or ";
		}
		$type = rtrim($type,"or ");
		$type .= ")";
	}else{
		$type = "";
	}
	//价格
	if($livep[0]["live_money_s"]!=""){
		$lms = $livep[0]["live_money_s"];
		$lme = $livep[0]["live_money_e"];
		
		$money = "and live_money_s >= $lms and live_money_e <= $lme";
	}else{
		$money = "";
	}
	//格局
	if($livep[0]["home_geju"]!=""){
		$hg = explode(",",$livep[0]["home_geju"]);
		$home_geju = "and (";
		foreach ($hg as $v){
			$home_geju .= "home_geju like '%$v%' or ";
		}
		$home_geju = rtrim($home_geju,"or ");
		$home_geju .= ")";
	}else{
		$home_geju = "";
	}
	//面积
	if($livep[0]["home_mianji_s"]!=""){
		$hms = $livep[0]["home_mianji_s"];
		$hme = $livep[0]["home_mianji_e"];
		
		$home_mianji = "and home_mianji_s >= $hms and home_mianji_e <= $hme";
	}else{
		$home_mianji = "";
	}
	//距离
	if($livep[0]["home_juli"]!=""){
		$hju = $livep[0]["home_juli"];
		
		$home_juli = "and home_juli <= $hju";
	}else{
		$home_juli = "";
	}
	//房屋年数
	$home_year="";
	if($livep[0]["home_year"]!=""){
		$hyear = $livep[0]["home_year"];
		
		$home_year = "and home_year <= $hyear";
	}else{
		$home_year　= "";
	}
	//维修上门
	if($livep[0]["weixiu_shangmen"]!=""){
		$ws = $livep[0]["weixiu_shangmen"];
		
		$weixiu_shangmen = "and weixiu_shangmen = $ws";
	}else{
		$weixiu_shangmen = "";
	}
	//宠物类别
	if($livep[0]["zhong"]!=""){
		$zho = $livep[0]["zhong"];
	
		$zhong = "and zhong = $zho";
	}else{
		$zhong = "";
	}
	
	$sql_livep = "select * from dtb_lives where live_want = $live_want and service_id = '$service_id' $area_cd $pref_cd $line_num $station_cd $type $money $home_geju $home_mianji $home_juli $home_year $weixiu_shangmen $zhong and del_flg=0 order by rand() limit 0 , 10";
	$live_array = $db->QueryArray($sql_livep);
	
	if(count($live_array)==0){
		$sql_livep = "select * from dtb_lives where live_want = $live_want and service_id = '$service_id' $area_cd $pref_cd $type and del_flg=0 order by rand() limit 0 , 10";
		$live_array = $db->QueryArray($sql_livep);
	}
	$smarty->assign('live_array', $live_array);
	
	
	#######################################################
	#  匹配数据没有，推荐热门数据
	#######################################################
	$live_where = "";
	foreach ($live_array as $v){
		$live_where = "and live_id != '$v[live_id]'" ;
	}
	
	//最新live
	$live_b = getLives("service_id = '$service_id' and live_id != '$live_id' $live_where",0,8);
	$smarty->assign('live_b', $live_b);
	
	
	
	//固定引入参数
	//mypage页面信息查询
	$smarty->assign('now', date("Y-m-d"));
	$smarty->assign('THEME', THEME);
	$smarty->assign('BASE_URL', APP_URL);
	
	$smarty->assign('USERNAME', $_SESSION['kiwa_username']);
	$smarty->assign('USERID', $_SESSION['kiwa_userid']);
	//得到未读message
	$um = unreadMessage($_SESSION['kiwa_userid']);
	$smarty->assign('unread', $um);
	//生活需求个数
	$sql_live="select count(*) from dtb_lives where user_id = '$_SESSION[kiwa_userid]' and del_flg = 0";
	$live_um = $db->QueryItem($sql_live);
	$smarty->assign('live_um', $live_um);
	//招贤纳才个数
	$sql_job="select count(*) from dtb_jobs where company_id = '$_SESSION[kiwa_userid]' and del_flg = 0";
	$job_um = $db->QueryItem($sql_job);
	$smarty->assign('job_um', $job_um);
	//收藏人才企业数
	$sql_fa_u="select count(*) from dtb_favorite_list where user_id = '$_SESSION[kiwa_userid]' and (flag='user' or flag='company') and del_flg = 0";
	$fa_um = $db->QueryItem($sql_fa_u);
	$smarty->assign('fa_um', $fa_um);
	//收藏生活互助数
	$sql_fa_l="select count(*) from dtb_favorite_list where user_id = '$_SESSION[kiwa_userid]' and (flag='live') and del_flg = 0";
	$fa_um_l = $db->QueryItem($sql_fa_l);
	$smarty->assign('fa_um_l', $fa_um_l);
	//收藏人才企业数
	$sql_fa_j="select count(*) from dtb_favorite_list where user_id = '$_SESSION[kiwa_userid]' and (flag='job') and del_flg = 0";
	$fa_um_j = $db->QueryItem($sql_fa_j);
	$smarty->assign('fa_um_j', $fa_um_j);
	//授信箱个数
	$sql_message_fa="select count(*) from dtb_message where message_to = '$_SESSION[kiwa_userid]' and del_flg = 0";
	$message_um_f = $db->QueryItem($sql_message_fa);
	$smarty->assign('message_um_f', $message_um_f);
	//送信箱个数
	$sql_message_s="select count(*) from dtb_message where message_from = '$_SESSION[kiwa_userid]' and from_del_flg = 0";
	$message_um_s = $db->QueryItem($sql_message_s);
	$smarty->assign('message_um_s', $message_um_s);
	if(isMobile()){
		$smarty->display('mobile/Mypage/mypagej.html');
	}else{
		$smarty->display('Mypage/mypagej.html');
	}
	
?>