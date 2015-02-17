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
	
	if(isset($_POST["sendB"])){
		$name = cleanInput($_POST["name"]);
		$name = $db->real_escape_string($name);
		$pinyin = cleanInput($_POST["pinyin"]);
		$pinyin = $db->real_escape_string($pinyin);
		$birth = $_POST["birthYear"]."-".$_POST["birthMonth"]."-".$_POST["birthDay"];
		$sex = cleanInput($_POST["sex"]);
		$sex = $db->real_escape_string($sex);
		$country = cleanInput($_POST["country"]);
		$country = $db->real_escape_string($country);
		$visa = cleanInput($_POST["visa"]);
		$visa = $db->real_escape_string($visa);
		$tall = cleanInput($_POST["tall"]);
		$tall = $db->real_escape_string($tall);
		$tizhong = cleanInput($_POST["tizhong"]);
		$tizhong = $db->real_escape_string($tizhong);
		$shoenum = cleanInput($_POST["shoenum"]);
		$shoenum = $db->real_escape_string($shoenum);
		$xuexing = cleanInput($_POST["xuexing"]);
		$xuexing = $db->real_escape_string($xuexing);
		$tel = cleanInput($_POST["tel"]);
		$tel = $db->real_escape_string($tel);
		$areaid = cleanInput($_POST["areaid"]);
		$areaid = $db->real_escape_string($areaid);
		$pref = cleanInput($_POST["pref"]);
		$pref = $db->real_escape_string($pref);
		$line = cleanInput($_POST["line"]);
		$line = $db->real_escape_string($line);
		$eki = cleanInput($_POST["eki"]);
		$eki = $db->real_escape_string($eki);
		
		$day1="";$day2="";$day3="";$day4="";$day5="";$day6="";$day7="";
		$want_day1="";$want_day2="";$want_day3="";$want_day4="";$want_day5="";$want_day6="";$want_day7="";
		$day = "";
		
		if(isset($_POST["day1"])&&$_POST["day1"]!=""){
			$day1 = "周一(";
			foreach ($_POST["day1"] as $k=>$v){
				$day1 .= $v.",";
				$want_day1 .="周一".$v.",";
			}
			$day1 = rtrim($day1,",");
			$day1 .= ")";
			
			$want_day1 = rtrim($want_day1,",");
			$day .= $day1."#";
		}
		if(isset($_POST["day2"])&&$_POST["day2"]!=""){
			$day2 = "周二(";
			foreach ($_POST["day2"] as $k=>$v){
				$day2 .= $v.",";
				$want_day2 .="周二".$v.",";
			}
			$day2 = rtrim($day2,",");
			$day2 .= ")";
			
			$want_day2 = rtrim($want_day2,",");
			$day .= $day2."#";
		}
		if(isset($_POST["day3"])&&$_POST["day3"]!=""){
			$day3 = "周三(";
			foreach ($_POST["day3"] as $k=>$v){
				$day3 .= $v.",";
				$want_day3 .="周三".$v.",";
			}
			$day3 = rtrim($day3,",");
			$day3 .= ")";
			
			$want_day3 = rtrim($want_day3,",");
			$day .= $day3."#";
		}
		if(isset($_POST["day4"])&&$_POST["day4"]!=""){
			$day4 = "周四(";
			foreach ($_POST["day4"] as $k=>$v){
				$day4 .= $v.",";
				$want_day4 .="周四".$v.",";
			}
			$day4 = rtrim($day4,",");
			$day4 .= ")";
			
			$want_day4 = rtrim($want_day4,",");
			$day .= $day4."#";
		}
		if(isset($_POST["day5"])&&$_POST["day5"]!=""){
			$day5 = "周五(";
			foreach ($_POST["day5"] as $k=>$v){
				$day5 .= $v.",";
				$want_day5 .="周五".$v.",";
			}
			$day5 = rtrim($day5,",");
			$day5 .= ")";
			
			$want_day5 = rtrim($want_day5,",");
			$day .= $day5."#";
		}
		if(isset($_POST["day6"])&&$_POST["day6"]!=""){
			$day6 = "周六(";
			foreach ($_POST["day6"] as $k=>$v){
				$day6 .= $v.",";
				$want_day6 .="周六".$v.",";
			}
			$day6 = rtrim($day6,",");
			$day6 .= ")";
			
			$want_day6 = rtrim($want_day6,",");
			$day .= $day6."#";
		}
		if(isset($_POST["day7"])&&$_POST["day7"]!=""){
			$day7 = "周日(";
			foreach ($_POST["day7"] as $k=>$v){
				$day7 .= $v.",";
				$want_day7 .="周日".$v.",";
			}
			$day7 = rtrim($day7,",");
			$day7 .= ")";
				
			$want_day7 = rtrim($want_day7,",");
			$day .= $day7."#";
		}
		$day = rtrim($day,"#");
		$want_day = $want_day1.",".$want_day2.",".$want_day3.",".$want_day4.",".$want_day5.",".$want_day6.",".$want_day7;
		
		$user_id = date("dHis").rand(1000,9999);
		$create_date = date("Y-m-d H:m:s");
		
		$sql = "insert into dtb_online (user_id,name,pinyin,birth,sex,country,visa,tall,tizhong,shoenum,xuexing,tel,areaid,pref,line,eki,type,day,create_date)values(
				'$user_id','$name','$pinyin','$birth','$sex','$country','$visa','$tall','$tizhong','$shoenum','$xuexing','$tel','$areaid','$pref','$line','$eki','ess','$day','$create_date')";		
		
		$db->Execute($sql);
		//注册帮帮网会员
		if(isset($_POST["8n8n"]) && $_POST["8n8n"]=="8n8n"){
			
			$email = cleanInput($_POST["email"]);
			$email = $db->real_escape_string($email);
			$password = cleanInput($_POST["register_pw"]);
			$password = $db->real_escape_string($password);
			$password = md5($password);
			$skills_str = "";
			
			if($sex == "男"){
				$bang_sex = 1;
				$photo = 'upload/img/1.png';
			}else{
				$bang_sex = 2;
				$photo = 'upload/img/2.png';
			}
			$pref_name = getName(array("name"=>"pref","value"=>$pref));
			
			$eki_name = getName(array("name"=>"eki","value"=>$eki));
			
			foreach ($_POST['skills'] as $s){
				$skills_str .= $s.",";
			}
			$skills_str = rtrim($skills_str,",");
			
			$sql_8n8n = "insert into dtb_user
			(user_id,name01,points,email,password,status,sex,birth,user_photo_url,tel01,area_cd,pref_cd,line_num,station_cd,pref,eki,create_date,info_flg,want_day,skill)
			values
			('$user_id','$name',50,'$email','$password',0,$bang_sex,'$birth','$photo','$tel','$areaid','$pref','$line','$eki','$pref_name','$eki_name','$create_date',1,'$want_day','$skills_str')";
			
			$db->Execute($sql_8n8n);
		}
		header('Location:'.APP_URL.'online/over/');
	}
	//国籍
	$countrys = array('中国','中国台湾','日本','韓国','ベトナム','ネパール','スリランカ','バングラデシュ','フィリピン','インド',"コンゴ","モンゴル",'その他');
	//再留资格
	$zailiuziges = array('留学','特定活動','短期滞在','技術','技能','人文知識国際','家族滞在','定住者','永住者','永住者の配偶者','日本人の配偶者','その他');
	//身高
	$talls = array('140以下','140~141','142~143','144~145','146~147','148~149', '150~151','152~153','154~155','156~157','158~159', '160~161','162~163','164~165','166~167','168~169', '170~171','172~173','174~175','176~177','178~179', '180~181','182~183','184~185','186~187','188~189','190~191','192~193','194~195','196~197','198~199','200以上');
	//体重
	$tizhongs = array('40以下','40~41','42~43','44~45','46~47','48~49', '50~51','52~53','54~55','56~57','58~59','60~61','62~63','64~65','66~67','68~69','70~71','72~73','74~75','76~77','78~79','80~81','82~83','84~85','86~87','88~89','90~91','92~93','94~95','96~97','98~99','100以上');
	//鞋号
	$shoenums = array('21号【中国32号】','21.5【中国33号】','22号【中国34号】','22.5【中国35号】','23号【中国36号】','23.5【中国37号】','24号【中国38号】','24.5【中国39号】','25号【中国40号】','25.5【中国41号】','26号【中国42号】','26.5【中国43号】','27号【中国44号】','27.5【中国45号】','28号【中国46号】','28.5【中国47号】','29号【中国48号】');
	
	//工作标签
	$sql_jb = "select * from mtb_job_biao";
	$biao = $db->QueryArray($sql_jb);
	$smarty->assign('biao', $biao);
	
	//get得到地域数据
	$areas = getPref();
	$smarty->assign('areas', $areas);
	
	$smarty->assign("shoenums",$shoenums);
	$smarty->assign("tizhongs",$tizhongs);
	$smarty->assign("talls",$talls);
	$smarty->assign("countrys",$countrys);
	$smarty->assign("zailiuziges",$zailiuziges);
	//固定引入参数
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	if(isMobile()){
		$smarty->display('Online/essmobile.html');
	}else{
		$smarty->display('Online/ess.html');
	}
?>