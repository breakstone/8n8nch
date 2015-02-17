<?php
// config
if(!file_exists('../_config/config.php'))
{
	die('[index.php] _config/config.php not found');
}
require_once '../_config/config.php';
require_once '../_includes/functions.php';

if(isset($_REQUEST["sendB"])){
	$now = date("Y-m-d H:i:s");
	$name = cleanInput($_POST["name"]);
	$name = $db->real_escape_string($name);
	$sex = cleanInput($_POST["sex"]);
	$sex = $db->real_escape_string($sex);
	$tel = cleanInput($_POST["tel"]);
	$tel = $db->real_escape_string($tel);
	$chezhan = cleanInput($_POST["chezhan"]);
	$chezhan = $db->real_escape_string($chezhan);
	$visa = cleanInput($_POST["visa"]);
	$visa = $db->real_escape_string($visa);
	$japanese = cleanInput($_POST["japanese"]);
	$japanese = $db->real_escape_string($japanese);
	$chinese = cleanInput($_POST["chinese"]);
	$chinese = $db->real_escape_string($chinese);
	
	$day1="";
	$day2="";
	$day3="";
	$day4="";
	$day5="";
	$day6="";
	$day7="";
	
	if(isset($_POST["day7"])&&$_POST["day7"]!=""){
		$day7 = "周日(";
		foreach ($_POST["day7"] as $k=>$v){
			$day7 .= $v.",";
		}
		$day7 = rtrim($day7,",");
		$day7 .= ")";
	}
	if(isset($_POST["day1"])&&$_POST["day1"]!=""){
		$day1 = "周一(";
		foreach ($_POST["day1"] as $k=>$v){
			$day1 .= $v.",";
		}
		$day1 = rtrim($day1,",");
		$day1 .= ")";
	}
	if(isset($_POST["day2"])&&$_POST["day2"]!=""){
		$day2 = "周二(";
		foreach ($_POST["day2"] as $k=>$v){
			$day2 .= $v.",";
		}
		$day2 = rtrim($day2,",");
		$day2 .= ")";
	}
	if(isset($_POST["day3"])&&$_POST["day3"]!=""){
		$day3 = "周三(";
		foreach ($_POST["day3"] as $k=>$v){
			$day3 .= $v.",";
		}
		$day3 = rtrim($day3,",");
		$day3 .= ")";
	}
	if(isset($_POST["day4"])&&$_POST["day4"]!=""){
		$day4 = "周四(";
		foreach ($_POST["day4"] as $k=>$v){
			$day4 .= $v.",";
		}
		$day4 = rtrim($day4,",");
		$day4 .= ")";
	}
	if(isset($_POST["day5"])&&$_POST["day5"]!=""){
		$day5 = "周五(";
		foreach ($_POST["day5"] as $k=>$v){
			$day5 .= $v.",";
		}
		$day5 = rtrim($day5,",");
		$day5 .= ")";
	}
	if(isset($_POST["day6"])&&$_POST["day6"]!=""){
		$day6 = "周六(";
		foreach ($_POST["day6"] as $k=>$v){
			$day6 .= $v.",";
		}
		$day6 = rtrim($day6,",");
		$day6 .= ")";
	}
	
	$sql="insert into dtb_kiwayue set name='$name',sex='$sex',tel='$tel',chezhan='$chezhan',visa='$visa',japanese='$japanese',chinese='$chinese',day1='$day1',day2='$day2',day3='$day3',day4='$day4',day5='$day5',day6='$day6',day7='$day7',create_date='$now'";
	
	if($db->Execute($sql)){
		header('Location:'.APP_URL.'online/done/');
	}else{
		echo "<center><font style='font-size:80px;'>Wrong,Trả lời thất bại！</font></center>";
	}
}

$smarty->assign('BASE_URL', APP_URL);
$smarty->assign('THEME', THEME);
$smarty->display('Online/yue.html');
?>