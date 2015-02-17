<?php
// config
if(!file_exists('./../_config/config.php'))
{
	die('[index.php] _config/config.php not found');
}

require_once './../_config/config.php';
require_once './../_includes/functions.php';
//发表求人
$username="";$userid="";$usertype="";
if((isset($_SESSION["kiwa_userid"])&&$_SESSION["kiwa_userid"]!="")||(isset($_SESSION["kiwa_companyid"])&&$_SESSION["kiwa_companyid"]!="")){
	if(@$_SESSION["kiwa_userid"]!=""){
		$userid = $_SESSION["kiwa_userid"];
		$username= $_SESSION['kiwa_username'];
		$usertype = "user";
	}
	if(@$_SESSION["kiwa_companyid"]!=""){
		$userid = $_SESSION["kiwa_companyid"];
		$username= $_SESSION['kiwa_companyname'];
		$usertype = "company";
	}

	$job_title = cleanInput($_POST['job_title']);
	$contents = str_replace("<br>","\r\n",$_POST['contents']);
	$contents = cleanInput($contents);
	
	$lianxi = cleanInput($_POST['lianxi']);
	
	$starttime = cleanInput(@$_POST['starttime']);
	$endtime = cleanInput(@$_POST['endtime']);
	
	//$over_date = date("Y-m-d",time()+$over_date*86400);
	###工作地，处理###
	$areaid = cleanInput($_POST['areaid']);
	$pref_cd = cleanInput($_POST['pref']);
	$prefname = getName(array("name"=>"pref","value"=>$pref_cd));
	
	$station_cd="";
	$station_name="";
	$line_num="";
	if(isset($_POST["ensn"])&&$_POST["ensn"]!=""){//線
		foreach($_POST["ensn"] as $l){
			$line_num .= $l.",";
		}
		$line_num = rtrim($line_num,",");
	}
	if(isset($_POST["eki"])&&$_POST["eki"]!=""){//駅
		foreach($_POST["eki"] as $s){
			$station_cd .= $s.",";
			$station_name .= getName(array("name"=>"eki","value"=>$s)).",";
		}
		$station_cd = rtrim($station_cd,",");
		$station_name = rtrim($station_name,",");
	}
// 	$now = date("Y-d-m H:i:s");
	//发表求人处理
	$return = send_work_ls($userid,$username,$usertype,$job_title,$contents,$areaid,$pref_cd,$prefname,$line_num,$station_cd,$station_name,$lianxi,$starttime,$endtime);
	//$return=true;
	if(!$return){
		if(!$return) echo "发表求人处理 失败！" ;exit();
	}else{
		echo '<script>
				alert("发布成功");
				window.location="'.APP_URL.'job/ls";';
		echo '</script>';
	}
}else{
	//没有参数跳到错误页面
	header('Location:'.APP_URL.'requirement/done/error');
}
	
?>