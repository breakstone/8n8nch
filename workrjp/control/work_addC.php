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
}elseif(isset($_POST["rgister_B"])&&$_POST["usertype"]!=""&&$_POST["register_name"]!=""&&$_POST["register_email"]!=""&&$_POST["register_pw"]!=""){
	//先注册
	if($_POST["usertype"] == "user"){
		$email = cleanInput($_POST["register_email"]);
		$password = cleanInput($_POST["register_pw"]);
		$kana01 = "";$kana02="";
		$name01 = cleanInput($_POST["register_name"]);$name02 = "";
		if(registC($name01,$name02,$kana01,$kana02,$email,$password,APP_URL)){
			$pw = md5($password);
			$sqluser = "select user_id,name01,name02 from dtb_user where email = '$email' and password = '$pw' and del_flg = 0";
			$user = $db->QueryRow($sqluser);
			$_SESSION['kiwa_userid'] = $user['user_id'];
			$_SESSION['kiwa_username'] = $user['name01'];
			
			$userid = $_SESSION["kiwa_userid"];
			$username= $_SESSION['kiwa_username'];
			$usertype = "user";
		}
			
	}elseif($_POST["usertype"] == "company"){
		$company_email = cleanInput($_POST["register_email"]);
		$company_name = cleanInput($_POST["register_name"]);
		$password = cleanInput($_POST["register_pw"]);
		$company_manager="";
		$foundation_date="";$company_money="";$company_url="";$zip01="";$zip02="";$pref="";$addr01="";$addr02="";$company_form=array();$tel01="";$tel02="";
		$tel03="";$fax01="";$fax02="";$fax03="";$areaid="";$pref="";$kinds="";$kindsname="";$types_str="";$typesname_str="";$skills_str="";$ensn_str="";$eki_str="";
		if(companyregistC($company_name,$company_manager,$foundation_date,$company_money,$company_url,$zip01,$zip02,$pref,$addr01,$addr02,$company_form,$tel01,$tel02,$tel03,$fax01,$fax02,$fax03,$company_email,$password,$areaid,$pref,$kinds,$kindsname,$types_str,$typesname_str,$skills_str,$ensn_str,$eki_str)){
			$pw = md5($password);
			$sqlcompany = "select company_id,company_name from dtb_companyuser where company_email = '$company_email' and password = '$pw' and del_flg = 0";
			$companyuser = $db->QueryRow($sqlcompany);
			$_SESSION['kiwa_companyid'] = $companyuser['company_id'];
			$_SESSION['kiwa_companyname'] = $companyuser['company_name'];
			
			$userid = $_SESSION["kiwa_companyid"];
			$username = $_SESSION['kiwa_companyname'];
			$usertype = "company";
		}
	}
}

if(isset($_POST['job_title'])&&isset($_POST['contents'])&&$userid!=""&&$username!=""){
	$send_type=$_GET['send_type'];
	if ($send_type !=1){
		if($_SESSION["bangvcode"] != "$_POST[yanzhengma]"){
			echo "<script language='javascript'>alert('验证码输入错误！');</script>";
		}
		
	}

	//收费，免费
	$shoufei = cleanInput($_POST['shoufei']);
	
	$job_title = $shoufei.cleanInput($_POST['job_title']);
	$contents = str_replace("<br>","\r\n",$_POST['contents']);
	$contents = cleanInput($contents);
	$requirements = str_replace("<br>","\r\n",$_POST["requirements"]);
	$requirements = cleanInput($requirements);
	$lianxi = cleanInput($_POST['lianxi']);
	$numbers = cleanInput($_POST['numbers']);
	$over_date = cleanInput(@$_POST['over_date']);
	$over_date = date("Y-m-d",time()+$over_date*86400);
	
	foreach(@$_POST['zz'] as $z){
		@$zz_name .= $z.",";
	}
	$zz_name = rtrim($zz_name,",");

	###职种，业种处理###
	$kinds = cleanInput($_POST['kinds']);
	$kindsname = "";
	$types = "";
	$typesname = "";
	foreach($_POST['types'] as $t){
		$ktrow = getKindTypename($t);
		$kindsname = $ktrow["kindsname"];
		$types .= $t.",";
		$typesname .= $ktrow["typesname"].",";
	}
	$types = rtrim($types,",");
	$typesname = rtrim($typesname,",");

	###工作地，处理###
	$areaid = cleanInput($_POST['areaid']);
	$pref = cleanInput($_POST['pref']);
	$prefname = getName(array("name"=>"pref","value"=>$pref));

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


	$moneyid = cleanInput($_POST['moneyid']);
	$moneyname = getName(array("name"=>"money","value"=>$moneyid));

	foreach(@$_POST['employid'] as $e){
		@$employid .= $e.",";
		@$employname .= getName(array("name"=>"employ","value"=>$e)).",";
	}
	$employid = rtrim($employid,",");
	$employname = rtrim($employname,",");

	foreach(@$_POST['hopeid'] as $h){
		@$hopedateid .= $h.",";
		@$hopedatename .= getName(array("name"=>"hope","value"=>$h)).",";
		
	}
	$hopedateid = rtrim($hopedateid,",");
	$hopedatename = rtrim($hopedatename,",");
	$w_d = cleanInput($_POST['w_d']);
	

	$condition_id="";
	$condition_name="";
	if(isset($_POST["conditions"])&&$_POST["conditions"]!=""){//駅
		foreach($_POST["conditions"] as $c){
			$condition_id .= $c.",";
			$condition_name .= getName(array("name"=>"condition","value"=>$c)).",";
		}
		$condition_id = rtrim($condition_id,",");
		$condition_name = rtrim($condition_name,",");
	}
	$now = date("Y-d-m H:i:s");

	//发表求人处理
	$return = findpeople_add($userid,$username,$usertype,$now,$job_title,$kinds,$kindsname,$types,$typesname,$contents,$employid,$employname,$areaid,$pref,$prefname,$line_num,$station_cd,$station_name,$hopedateid,$hopedatename,$moneyid,$moneyname,$requirements,$lianxi,$numbers,$over_date,$condition_id,$condition_name,$zz_name,$w_d);
	//$return=true;
	if(!$return){
		if(!$return) echo "发表求人处理 失败！" ;exit();
	}else{
		//进行数据匹配，推荐发送站内短信
		if(isset($_POST['types'])&&$_POST['types']!=""){
			$types = $_POST['types'];
		}else{
			$types = "";
		}
		if(isset($_POST['ensn'])&&$_POST['ensn']!=""){
			$lines = $_POST['ensn'];
		}else{
			$lines = "";
		}
		if(isset($_POST['eki'])&&$_POST['eki']!=""){
			$chezhan = $_POST['eki'];
		}else{
			$chezhan = "";
		}
		//业种 职种 区域 市 线路 车站 薪金 雇佣形式 勤务时间
		people_recommend($kinds,$types,$areaid,$pref,$lines,$chezhan,$moneyid,$employid,$hopedateid,$job_title,APP_URL);
		if ($send_type==1){
	
			echo '<script>
				alert("发布成功");
				window.location="'.APP_URL.'people/show/'.$usertype.'_'.$userid.'";';
			echo '</script>';
		}else{
			//没有参数跳到错误页面
			header('Location:'.APP_URL.'requirement/saved/');
		}
		
	}
}else{
	//没有参数跳到错误页面
	header('Location:'.APP_URL.'requirement/done/error');
}

?>