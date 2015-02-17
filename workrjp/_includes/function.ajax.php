<?php
if($_GET['ajax']==""){
	echo "warning:your ip is deny!";
}else{
	require_once '../_config/config.php';
	require_once 'functions.php';
	header('Content-type: text/xml;charset=utf-8');
	header("cache-control:no-cache,must-revalidate");
	echo "<?xml version='1.0' encoding='utf-8'?>";
	echo "<response>";
	global $db;
	
	if($_GET['ajax']==1){//注册邮箱ajax校验
		//传入参数处理
		$email = cleanInput($_GET['email']);
		$email = $db->real_escape_string($email);
		$sql_user="select Id from dtb_user where `email` = '$email' and del_flg = 0";
		$sql_company="select Id from dtb_companyuser where `company_email` = '$email' and del_flg = 0";
		if($db->QueryItem($sql_user)){
			echo "<emailOk>1</emailOk>";
		}else{
			if($db->QueryItem($sql_company)){
				echo "<emailOk>1</emailOk>";
			}
			echo "<emailOk>0</emailOk>";
		}
	}
	else if($_GET['ajax']==2){//邮编查找地址
		//传入参数处理
		$zip = cleanInput($_GET['zip']);
		$zip = $db->real_escape_string($zip);
		$sql="select state,city,town from mtb_zip where `zipcode` = '$zip'";
		$row = $db->QueryRow($sql);
		if($row){
			$state = $row['state'];
			$city = $row['city'];
			$town = $row['town'];
			echo "<error>yes</error>";
			echo "<state>$state</state>";
			echo "<city>$city</city>";
			echo "<town>$town</town>";
		}else{
			echo "<error>no</error>";
		}
	}
	else if($_GET['ajax']=="gettypes"){//根据业种查询职种
		$kinds = cleanInput($_GET['kinds']);
		$kinds = $db->real_escape_string($kinds);
		$sql = "select typesID,typesname from mtb_kinds_types where kindsID = '$kinds' order by rank";
		$row = $db->QueryArray($sql);
		foreach($row as $k){
			echo "<typeid>$k[typesID]</typeid>";
			echo "<type>$k[typesname]</type>";
		}
	}
	else if($_GET['ajax']=="getpref"){//根据地域查询县城
		$areaid = cleanInput($_GET['areaid']);
		$areaid = $db->real_escape_string($areaid);
		$sql = "select pref_cd,name from mtb_pref where area_cd = '$areaid' order by rank";
		$row = $db->QueryArray($sql);
		foreach($row as $k){
			echo "<prefid>$k[pref_cd]</prefid>";
			echo "<prefname>$k[name]</prefname>";
		}
	}
	else if($_GET['ajax']=="getstation"){//点击線，得到駅
		$prefcd = cleanInput($_GET['prefcd']);
		$sqlPref = "select mtb_line.line_num,mtb_line.line_name from mtb_station,mtb_line where mtb_station.line_num = mtb_line.line_num and mtb_station.pref_cd = $prefcd group by line_name";
		$lines = $db->QueryArray($sqlPref);
		foreach($lines as $l){
			echo "<line_cd>$l[line_num]</line_cd>";
			echo "<line_name>$l[line_name]</line_name>";
			
			$sqlStation = "select station_cd,station_name from mtb_station where line_num = '$l[line_num]' and pref_cd = $prefcd";
			$stations = $db->QueryArray($sqlStation);
			$station_cd = "";
			$station_name = "";
			foreach($stations as $s){
				if($station_cd!=""){$station_cd .= "_";}
				$station_cd .= $s["station_cd"];
				if($station_name!=""){$station_name .= "_";}
				$station_name .= $s["station_name"];
			}
			echo "<station_cd>$station_cd</station_cd>";
			echo "<station_name>$station_name</station_name>";
		}
	}
	else if($_GET['ajax']=="getline"){//点击县，得到線
		$prefcd = cleanInput($_GET['prefid']);
		$sqlPref = "select mtb_line.line_num,mtb_line.line_name from mtb_station,mtb_line where mtb_station.line_num = mtb_line.line_num and mtb_station.pref_cd = $prefcd group by line_name";
		$lines = $db->QueryArray($sqlPref);
		foreach($lines as $l){
			echo "<line_cd>$l[line_num]</line_cd>";
			echo "<line_name>$l[line_name]</line_name>";
		}
	}
	else if($_GET['ajax']=="geteki"){//点击線，得到駅
		$lineid = cleanInput($_GET['lineid']);
		$sqlStation = "select station_cd,station_name from mtb_station where line_num = '$lineid'";
		$eki = $db->QueryArray($sqlStation);
		foreach($eki as $e){
			echo "<eki_cd>$e[station_cd]</eki_cd>";
			echo "<eki_name>$e[station_name]</eki_name>";
		}
	}
	else if($_GET['ajax']=="favorite"){//收藏各种
		if($_SESSION['kiwa_userid'] != ""){
			$user_id = $_SESSION['kiwa_userid'];
		}elseif($_SESSION['kiwa_companyid'] != ""){
			$user_id = $_SESSION['kiwa_companyid'];
		}
		$favorite_id = cleanInput($_GET['favorite_id']);
		$flag = cleanInput($_GET['flag']);
		$now = date("Y-m-d H:i:s");
		$sqlsee = "select Id from dtb_favorite_list where user_id = '$user_id' and favorite_id = '$favorite_id' and del_flg = 0";
		$item = $db->QueryItem($sqlsee);
		if($item == ""){
			$sql = "insert into dtb_favorite_list set user_id = '$user_id',favorite_id = '$favorite_id',flag = '$flag',create_date='$now'";
		}elseif($item != ""){
			$sql = "update dtb_favorite_list set create_Date = '$now' where user_id = '$user_id' and favorite_id = '$favorite_id'";
		}
		if($db->Execute($sql)){
			echo "<favorite>yes</favorite>";
		}else{
			echo "<favorite>no</favorite>";
		}
		echo "<session>yes</session>";
		if($_SESSION['kiwa_userid'] == "" && $_SESSION['kiwa_companyid'] == ""){
			echo "<session>no</session>";
		}
	}
	else if($_GET['ajax']=="login"){//登陆ajax校验
		$email = cleanInput($_GET["email"]);
		$pw = md5($_GET["pw"]);
		$pw = cleanInput($pw);
		$sqluser = "select user_id,name01,name02 from dtb_user where email = '$email' and password = '$pw' and del_flg = 0";
		$user = $db->QueryRow($sqluser);
		
		$sqlcompany = "select company_id,company_name from dtb_companyuser where company_email = '$email' and password = '$pw' and del_flg = 0";
		$companyuser = $db->QueryRow($sqlcompany);
		
		if($user['user_id']!="" || $companyuser['company_id']!=""){
			if($user['user_id']!=""){
				$_SESSION['kiwa_userid'] = $user['user_id'];
				$_SESSION['kiwa_username'] = $user['name01']." ".$user['name02'];
			}
			if($companyuser['company_id']!=""){
				$_SESSION['kiwa_companyid'] = $companyuser['company_id'];
				$_SESSION['kiwa_companyname'] = $companyuser['company_name'];
			}
			echo "<loginok>ok</loginok>";
		}else{
			echo "<loginok>no</loginok>";
		}
	}
	else if($_GET['ajax']=="domain"){//domain ajax校验
		$domain = cleanInput($_GET["domain"]);
		$sqldomain = "select count(*) from dtb_2domain where yuming = '$domain' and del_flg = 0";
		$count = $db->QueryItem($sqldomain);
		if($count > 0 || $domain == ""){
			echo "<domain>no</domain>";
		}else{
			echo "<domain>ok</domain>";
		}
	}
	else if($_GET['ajax']=="pingbi"){//屏蔽各种
		if($_SESSION['kiwa_userid'] != ""){
			$user_id = $_SESSION['kiwa_userid'];
		}elseif($_SESSION['kiwa_companyid'] != ""){
			$user_id = $_SESSION['kiwa_companyid'];
		}
		$pingbiuser_id = cleanInput($_GET['user_id']);
		$now = date("Y-m-d H:i:s");
		
		$sql = "update dtb_favorite_list set shield = 1 where user_id = '$user_id' and favorite_id = '$pingbiuser_id'";
		if($db->Execute($sql)){
			echo "<pingbi>yes</pingbi>";
		}else{
			echo "<pingbi>no</pingbi>";
		}
		echo "<session>yes</session>";
		if($_SESSION['kiwa_userid'] == "" && $_SESSION['kiwa_companyid'] == ""){
			echo "<session>no</session>";
		}
	}
	echo "</response>";
}

?>