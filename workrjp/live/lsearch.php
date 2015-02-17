<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	require_once '../_includes/SubPages.php';
	
	//-------------------------------------------各种初始化
	if($page != "" && $page != "index"){
		$where = "service_id = '$page'";
		$service = getLifeservice($page);
		$smarty->assign('service_id', $page);
		$smarty->assign('service_name', $service["service_name"]);
		$sp = "/".$page;
	}else{
		$where = "";
		$sp = "";
	}
	
	//get得到业种数据
	$companyfrom = getCompanyfrom();
	//get得到地域数据
	$areas = getPref();
	//get得到生活服务内容
	$life_service = getLifeservice();
	global $db;
	$sql_1 = "select * from mtb_life_service where funlei = '房产相关' order by rank";
	$fangchan = $db->QueryArray($sql_1);
	$sql_2 = "select * from mtb_life_service where funlei = '跳蚤市场' order by rank";
	$tiaozao = $db->QueryArray($sql_2);
	$sql_3 = "select * from mtb_life_service where funlei = '车辆服务' order by rank";
	$cheliang = $db->QueryArray($sql_3);
	$sql_4 = "select * from mtb_life_service where funlei = '家政服务' order by rank";
	$jiazheng = $db->QueryArray($sql_4);
	$sql_5 = "select * from mtb_life_service where funlei = '生活相关' order by rank";
	$shenghuo = $db->QueryArray($sql_5);
	$sql_6 = "select * from mtb_life_service where funlei = '美食旅游' order by rank";
	$meishi = $db->QueryArray($sql_6);
	$sql_7 = "select * from mtb_life_service where funlei = '设计相关' order by rank";
	$sheji = $db->QueryArray($sql_7);
	$sql_8 = "select * from mtb_life_service where funlei = '法律帮助' order by rank";
	$falv = $db->QueryArray($sql_8);
	
	$smarty->assign('fangchan', $fangchan);
	$smarty->assign('tiaozao', $tiaozao);
	$smarty->assign('cheliang', $cheliang);
	$smarty->assign('jiazheng', $jiazheng);
	$smarty->assign('shenghuo', $shenghuo);
	$smarty->assign('meishi', $meishi);
	$smarty->assign('sheji', $sheji);
	$smarty->assign('falv', $falv);
	//-------------------------------------------各种初始化
	$zhong = "";
	$keyword = "";
	$type_url = "";$want = "";$geju_url = "";
	$juli = "";$money_s="";$money_e="";
	$home_year = "";$mianji_s="";$mianji_e="";$weixiu_shangmen="";
	$areaid="";$pref="";$ensn_url="";$eki_url="";
	if(isset($_REQUEST['searchB'])||isset($_REQUEST['keyword'])){//★点击查询按钮的情况
		### keyword
		if(@$_REQUEST['keyword']!=""){//
			$smarty->assign('keyword', $_REQUEST['keyword']);
			$keyword = cleanInput($_REQUEST['keyword']);
			if($where != "")$where .=" and ";
			$where .= "(live_title like '%$keyword%' or live_content like '%$keyword%')";
		}
		### 供求关系
		if(isset($_REQUEST["wanted"])&&$_REQUEST["wanted"]!=""){
			$want = $_REQUEST["wanted"];
			if($where != "")$where .=" and ";
			$where .= "live_want = $want";
			$smarty->assign('wanted', $_REQUEST['wanted']);
		}
		### 各种类别
		if((isset($_REQUEST["type"])&&$_REQUEST["type"]!="")||(isset($_REQUEST["type_url"])&&$_REQUEST["type_url"]!="")){
			if(isset($_REQUEST["type"])&&$_REQUEST["type"]!=""){
				$type = $_REQUEST["type"];
				$type_url = json_encode($type);
			}
			if(isset($_REQUEST["type_url"])&&$_REQUEST["type_url"]!=""){
				$type = json_decode($_REQUEST["type_url"]);
				$type_url = json_encode($type);
			}
			
			if(is_array($type)){
				if($where != "")$where .=" and (";
				foreach ($type as $ty){
					$where .= "type like '%$ty%' or ";
				}
				$where = rtrim($where," or ");
				$where .= ")";
			}else{
				if($where != "")$where .=" and ";
				$where .= " type = '$type' ";
			}
			$smarty->assign('type', $type);
		}
		### 新旧程度
		if((isset($_REQUEST["how"])&&$_REQUEST["how"]!="")){
			if(isset($_REQUEST["how"])&&$_REQUEST["how"]!=""){
				$how = $_REQUEST["how"];
			}
				
			if(is_array($how)){
				if($where != "")$where .=" and (";
				foreach ($how as $h){
					$where .= "how like '%$h%' or ";
				}
				$where = rtrim($where," or ");
				$where .= ")";
			}else{
				if($where != "")$where .=" and ";
				$where .= " how = '$how' ";
			}
			$smarty->assign('how', $how);
		}
		
		if(isset($_REQUEST["zhong"])&&$_REQUEST["zhong"]!=""){
			if($where != "")$where .=" and ";
			$where .= "zhong = '$_REQUEST[zhong]'";
			
			$smarty->assign('zhong', $_REQUEST["zhong"]);
			$zhong = $_REQUEST["zhong"];
		}
		### 格局
		if((isset($_REQUEST["geju"])&&$_REQUEST["geju"]!="")||(isset($_REQUEST["geju_url"])&&$_REQUEST["geju_url"]!="")){
			if(isset($_REQUEST["geju"])&&$_REQUEST["geju"]!=""){
				$geju = $_REQUEST["geju"];
				$geju_url = json_encode($geju);
			}
			if(isset($_REQUEST["geju_url"])&&$_REQUEST["geju_url"]!=""){
				$geju = json_decode($_REQUEST["geju_url"]);
				$geju_url = json_encode($geju);
			}
			
			if($where != "")$where .=" and (";
			foreach ($geju as $gj){
				$where .= "home_geju like '%$gj%' or ";
			}
			$where = rtrim($where," or ");
			$where .= ")";
			$smarty->assign('geju', $geju);
		}
		### 车站距离
		if(isset($_REQUEST["juli"])&&$_REQUEST["juli"]!=""){
			if($where != "")$where .=" and ";
			$where .= "home_juli <= $_REQUEST[juli]";
			$smarty->assign('juli', $_REQUEST["juli"]);
			$juli = $_REQUEST["juli"];
		}
		### 价格范围
		if(isset($_REQUEST["money_s"])&&$_REQUEST["money_s"]!=""&&isset($_REQUEST["money_e"])&&$_REQUEST["money_e"]!=""){
			if($_REQUEST["money_e"] != 0){
				if($where != "")$where .=" and ";
				$where .= "(live_money_e <= $_REQUEST[money_e] or live_money_e = 0)";
			}
			if($_REQUEST["money_s"] != 0){
				if($where != "")$where .=" and ";
				$where .= "(live_money_s >= $_REQUEST[money_s] or live_money_s = 0)";
			}
			
			$smarty->assign('money_s', $_REQUEST["money_s"]);
			$smarty->assign('money_e', $_REQUEST["money_e"]);
			$money_s = $_REQUEST["money_s"];
			$money_e = $_REQUEST["money_e"];
		}
		### 房屋年数
		if(isset($_REQUEST["home_year"])&&$_REQUEST["home_year"]!=""){
			if($where != "")$where .=" and ";
			$where .= "home_year <= $_REQUEST[home_year]";
			$smarty->assign('home_year', $_REQUEST["home_year"]);
			$home_year = $_REQUEST["home_year"];
		}
		### 房屋面积
		if(isset($_REQUEST["mianji_s"])&&$_REQUEST["mianji_s"]!=""&&isset($_REQUEST["mianji_e"])&&$_REQUEST["mianji_e"]!=""){
			if($_REQUEST["mianji_e"] != 0){
				if($where != "")$where .=" and ";
				$where .= "(home_mianji_e <= $_REQUEST[mianji_e] or home_mianji_e = 0)";
			}
			if($_REQUEST["mianji_s"] != 0){
				if($where != "")$where .=" and ";
				$where .= "(home_mianji_s >= $_REQUEST[mianji_s] or home_mianji_s = 0)";
			}
			
			$smarty->assign('mianji_s', $_REQUEST["mianji_s"]);
			$smarty->assign('mianji_e', $_REQUEST["mianji_e"]);
			$mianji_s = $_REQUEST["mianji_s"];
			$mianji_e = $_REQUEST["mianji_e"];
		}
		### 上门服务
		if(isset($_REQUEST["weixiu_shangmen"])&&$_REQUEST["weixiu_shangmen"]!=""){
			$smfw = $_REQUEST["weixiu_shangmen"];
			if($where != "")$where .=" and ";
			$where .= "weixiu_shangmen = $smfw";
			$smarty->assign('weixiu_shangmen', $_REQUEST['weixiu_shangmen']);
			$weixiu_shangmen = $_REQUEST['weixiu_shangmen'];
		}
		### 地域有选择
		if(isset($_REQUEST['areaid'])&&$_REQUEST['areaid']!=""){//地域有选择
			$prefOne = getPref($_REQUEST['areaid']);//获得指定id的都道府県
			$smarty->assign('areaid', $_REQUEST['areaid']);
			$smarty->assign('prefSearch', $prefOne);
			//sql--------------------------------
			if($where != "")$where .=" and ";
			$where .= "area_cd = '$_REQUEST[areaid]'";
			
			$areaid = $_REQUEST['areaid'];
		}
		if(isset($_REQUEST['pref'])&&$_REQUEST['pref']!=""){//都道府県有选择
			$smarty->assign('pref', $_REQUEST['pref']);
			//sql--------------------------------
			if($where != "")$where .=" and ";
			$where .= "pref_cd = '$_REQUEST[pref]'";
			
			$pref = $_REQUEST['pref'];
		}
		if((isset($_REQUEST["ensn"])&&$_REQUEST["ensn"]!="") || (isset($_REQUEST["ensn_url"])&&$_REQUEST["ensn_url"]!="")){//線
			if(isset($_REQUEST["ensn"])&&$_REQUEST["ensn"]!=""){
				$ensn = $_REQUEST["ensn"];
				$ensn_url = json_encode($ensn);
			}
			if(isset($_REQUEST["ensn_url"])&&$_REQUEST["ensn_url"]!=""){
				$ensn = json_decode($_REQUEST["ensn_url"]);
				$ensn_url = json_encode($ensn);
			}
			$smarty->assign('ensn', $ensn);
			
			//sql--------------------------------
			if($where != "")$where .=" and (";
			foreach($ensn as $l){
				$where .= "line_num like '%$l%'";
				$where .= " or ";
			}
			$where = rtrim($where,"or ");
			$where .= " or (line_num is null and station_cd is null)) ";
		}
		if((isset($_REQUEST["eki"])&&$_REQUEST["eki"]!="") || (isset($_REQUEST["eki_url"])&&$_REQUEST["eki_url"]!="")){//駅
			if(isset($_REQUEST["eki"])&&$_REQUEST["eki"]!=""){
				$eki = $_REQUEST["eki"];
				$eki_url = json_encode($eki);
			}
			if(isset($_REQUEST["eki_url"])&&$_REQUEST["eki_url"]!=""){
				$eki = json_decode($_REQUEST["eki_url"]);
				$eki_url = json_encode($eki);
			}
			$smarty->assign('eki', $eki);
			//sql--------------------------------
			if($where != "")$where .=" and (";
			foreach($eki as $e){
				//如果线路里的车站做处理
				$line_eki = substr($e,0,5);
				$where .= "station_cd like '%$e%'";
				$where .= " or ";
				$where .= "line_num like '%$line_eki%'";
				$where .= " or ";
			}
			$where = rtrim($where,"or ");
			$where .= " or (line_num is null and station_cd is null)) ";
		}
	}
// 	echo $where;
	//-----------------分页处理开始----------------------
	if(isset($_REQUEST["pid"])){
		$pid = $_REQUEST["pid"];
	}else{
		$pid = 1;
	}
	//总条数
	$all_nums = getObjectNum("dtb_lives",$where);
	
	//每页显示几条;//得到当前是第几页;
  	$show_num=12;$pageCurrent = $pid;
  	$subPages = new SubPages($show_num,$all_nums,$pageCurrent,10,APP_URL."live$sp/?searchB=true&keyword=$keyword&wanted=$want&type_url=$type_url&geju_url=$geju_url&juli=$juli&money_s=$money_s&money_e=$money_e&home_year=$home_year&mianji_s=$mianji_s&mianji_e=$mianji_e&weixiu_shangmen=$weixiu_shangmen&areaid=$areaid&pref=$pref&ensn_url=$ensn_url&eki_url=$eki_url&pid=");
	//-----------------分页处理完了----------------------
	//利用limit查询数据库---select * from table limit $start,$end
	$start = $subPages->getStart($show_num,$pageCurrent);//配合分页得到起始数
	$end = $subPages->getEnd($all_nums,$show_num,$pageCurrent);//配合分页得到结束数
	$lives = getLives($where,$start,$end);
	
	//today
	$today = date("Y-m-d");
	$yestoday = date("Y-m-d",strtotime("-1 day"));
	$bfyestoday = date("Y-m-d",strtotime("-2 day"));
	$smarty->assign('today',$today);
	$smarty->assign('yestoday',$yestoday);
	$smarty->assign('bfyestoday',$bfyestoday);
	
	//准备数据---左侧检索数据
	$smarty->assign('companyfrom', $companyfrom);
	$smarty->assign('areas', $areas);
	$smarty->assign('life_service', $life_service);
	//右侧工作信息
	$smarty->assign('subPages',$subPages->showPageStr());
	$smarty->assign('all', $all_nums);
	if($all_nums!=0){$start=$start+1;}
	$smarty->assign('start', $start);
	$smarty->assign('end', $end);
	
	$smarty->assign('lives',$lives);
	
	//注册个根据id查找name的方法
	$smarty->register_function("getname","getName");
	//注册个方法
	$smarty->register_function("getstation","getStation");
	//固定引入参数
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	
	if(isMobile()){
		if($page!="index"){
			$smarty->display("mobile/Live/live$page.html");
		}else{
			$smarty->display("mobile/Live/live.html");
		}
	}else{
		if($page!="index"){
			$smarty->display("Live/live$page.html");
		}else{
			$smarty->display("Live/live.html");
		}
	}
?>