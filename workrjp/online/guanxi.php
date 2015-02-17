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
	//没有Session 跳转到登录页面
	if(@$_SESSION['kiwa_userid']=="" && @$_SESSION['kiwa_companyid']==""){
		header('Location:'.APP_URL.'login/?url=online_gxm/');
	}else{
		if(@$_SESSION['kiwa_userid'] !=""){
			$user_id = $_SESSION['kiwa_userid'];
			$user_type = "user";
		}
		if(@$_SESSION['kiwa_companyid'] !=""){
			$user_id = $_SESSION['kiwa_companyid'];
			$user_type = "company";
		}
		
		if(@$user_id!=""){
			$sql_ol = "select * from dtb_online where user_id = '$user_id' and type='guanxi' and del_flg = 0";
			$ol = $db->QueryRow($sql_ol);
			$smarty->assign('ol', $ol);
			
			if($ol){
				header('Location:'.APP_URL.'online/guanxi/');
			}
		}
		
		
		if(isset($_POST["sendB"])){
			$name = cleanInput($_POST["name"]);
			$name = $db->real_escape_string($name);
			$birth = $_POST["birthYear"]."-".$_POST["birthMonth"]."-".$_POST["birthDay"];
			$sex = cleanInput($_POST["sex"]);
			$sex = $db->real_escape_string($sex);
			$visa = cleanInput($_POST["visa"]);
			$visa = $db->real_escape_string($visa);
			$tel = cleanInput($_POST["tel"]);
			$tel = $db->real_escape_string($tel);
			
			$create_date = date("Y-m-d H:m:s");
			
			$sql = "insert into dtb_online (user_id,user_type,name,birth,sex,visa,tel,type,create_date,xin,checkflag)values(
					'$user_id','$user_type','$name','$birth','$sex','$visa','$tel','guanxi','$create_date',1,1)";
			$db->Execute($sql);
			
			header('Location:'.APP_URL.'online/guanxiover/');
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
			$smarty->display('Online/guanximobile.html');
		}else{
			$smarty->display('Online/guanxi.html');
		}
	}
?>