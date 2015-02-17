<?php
// config
if(!file_exists('../_config/config.php'))
{
	die('[index.php] _config/config.php not found');
}
require_once '../_config/config.php';
require_once '../_includes/functions.php';
require_once '../_includes/SubPages.php';

//get得到生活服务内容
// $life_service = getLifeservice();
// global $db;
// $sql_1 = "select * from mtb_life_service where funlei = '房产相关' order by rank";
// $fangchan = $db->QueryArray($sql_1);
// $sql_2 = "select * from mtb_life_service where funlei = '跳蚤市场' order by rank";
// $tiaozao = $db->QueryArray($sql_2);
// $sql_3 = "select * from mtb_life_service where funlei = '车辆服务' order by rank";
// $cheliang = $db->QueryArray($sql_3);
// $sql_4 = "select * from mtb_life_service where funlei = '家政服务' order by rank";
// $jiazheng = $db->QueryArray($sql_4);
// $sql_5 = "select * from mtb_life_service where funlei = '生活相关' order by rank";
// $shenghuo = $db->QueryArray($sql_5);
// $sql_6 = "select * from mtb_life_service where funlei = '美食旅游' order by rank";
// $meishi = $db->QueryArray($sql_6);
// $sql_7 = "select * from mtb_life_service where funlei = '设计相关' order by rank";
// $sheji = $db->QueryArray($sql_7);
// $sql_8 = "select * from mtb_life_service where funlei = '法律帮助' order by rank";
// $falv = $db->QueryArray($sql_8);
	
// $smarty->assign('fangchan', $fangchan);
// $smarty->assign('tiaozao', $tiaozao);
// $smarty->assign('cheliang', $cheliang);
// $smarty->assign('jiazheng', $jiazheng);
// $smarty->assign('shenghuo', $shenghuo);
// $smarty->assign('meishi', $meishi);
// $smarty->assign('sheji', $sheji);
// $smarty->assign('falv', $falv);

//热门分类
$sql_jb = "select * from mtb_job_biao";
$biao = $db->QueryArray($sql_jb);
$smarty->assign('biao', $biao);

//企业分类
$sql_comp = "select * from mtb_company_type";
$qi = $db->QueryArray($sql_comp);
$smarty->assign('qi', $qi);

//get得到业种数据
$companyfrom = getCompanyfrom();
//get得到地域数据
$areas = getPref();
//get給付
$money = getMoney();
//---------------检索按钮点击后进入页面---------------------------------//
$where = "";
$keyword="";
$kinds="";
$types_url="";
$service_id="";
$areaid="";
$pref="";
$ensn_url="";
$eki_url = "";
$which_show = "";
$biaoname = "";
$qiname = "";
if(isset($_REQUEST['keyword'])){//★点击keyword查询按钮
	if($_REQUEST['keyword']!=""){//
		$smarty->assign('keyword', $_REQUEST['keyword']);
		$keyword = cleanInput($_REQUEST['keyword']);
		if($_REQUEST["Which_Show"] == 2){
			
		}
		$where = "(people_title like '%$keyword%' or contents like '%$keyword%')";
	}
}

if(isset($_REQUEST['peo_key_ser'])){//★点击keyword查询按钮
	if($_REQUEST['peo_key_ser']!=""){//
		$smarty->assign('peo_key_ser', $_REQUEST['peo_key_ser']);
		$peo_key_ser= cleanInput($_REQUEST['peo_key_ser']);
		if(isset($_REQUEST["Which_Show"])&&$_REQUEST["Which_Show"]!=""){
			if($_REQUEST["Which_Show"] == 2){
					$where = "(concat(name01,name02,skill,kindsname,typesname,mypr) like '%$peo_key_ser%')";
			}
			if($_REQUEST["Which_Show"] == 1){
				$where = "(concat(company_name,company_manager,skill,kindsname,typesname,tel01,tel02,tel03,company_text,addr01,addr02,pref) like '%$peo_key_ser%')";
			}
		}else
		{
				$where = "(concat(company_name,company_manager,skill,kindsname,typesname,tel01,tel02,tel03,company_text,addr01,addr02,pref) like '%$peo_key_ser%')";
		}
		//$where = "(concat(name01,name02,skill) REGEXP '$peo_key_ser')";
	}
}
if(isset($_REQUEST['search'])||isset($_REQUEST['searchEki'])||isset($_REQUEST['service_id'])){//★点击查询按钮的情况
	/*处理左侧检索框开始*/
	#####################################################
	# 转职类 -- 条件
	#####################################################
	if($_REQUEST['kinds']!=""){//业种有选择
		$smarty->assign('kindsid', $_REQUEST['kinds']);
		$typesSearch = getTypes($_REQUEST['kinds']);
		$smarty->assign('typesSearch', $typesSearch);
		//分页用
		$kinds = $_REQUEST['kinds'];
		//没选择职种的情况
		if(!isset($_REQUEST['types'])){
			$gettypes = getTypes($_REQUEST['kinds']);//根据业种得到职种
			$types_str = "";
			foreach($gettypes as $k){
				$values = $k["typesID"];
				$names = $k["typesname"];
				$types_str .= "&nbsp;<input name='types[]' type='checkbox' id='".$values."' value='".$values."'/><label for='".$values."'>".$names."</label><br>";
			}
			$smarty->assign('types_str', $types_str);
		}
		//sql--------------------------------
		if($where != "")$where .=" and ";
		$where .= "kindsID = '$_REQUEST[kinds]'";
	}
	if((isset($_REQUEST['types'])&&$_REQUEST['types']!="") || (isset($_REQUEST["types_url"])&&$_REQUEST["types_url"]!="")){//职种有选择
		if(isset($_REQUEST['types'])&&$_REQUEST['types']!=""){
			$types = $_REQUEST['types'];
			$types_url = json_encode($types);
		}
		if(isset($_REQUEST["types_url"])&&$_REQUEST["types_url"]!=""){
			$types = json_decode($_REQUEST["types_url"]);
			$types_url = json_encode($types);
		}
		$gettypes = getTypes($_REQUEST['kinds']);//根据业种得到职种
		$types_str = "";
		foreach($gettypes as $k){
			$values = $k["typesID"];
			$names = $k["typesname"];
			if(in_array($values,$types)){
				$check = "checked='checked'";
			}else{
				$check = "";
			}
			$types_str .= "&nbsp;<input name='types[]' $check type='checkbox' id='".$values."' value='".$values."'/><label for='".$values."'>".$names."</label><br>";
		}
		$smarty->assign('types_str', $types_str);
		//sql--------------------------------
		if($where != "")$where .=" and (";
		foreach($types as $k){
			$where .= "typesID like '%$k%'";
			$where .= " or ";
		}
		$where = rtrim($where,"or ");
		$where .= ") ";
	}
	#####################################################
	# 人才热门分类
	#####################################################
	if(isset($_REQUEST["biaoname"])){
		if($_REQUEST["biaoname"]!=""){
			$biaoname = $_REQUEST["biaoname"];
			if($where != "")$where .=" and ";
			$where .= "skill like '%$biaoname%'";
			
			$smarty->assign('biaoname', $biaoname);
		}
	}
	if(isset($_REQUEST["qiname"])){
		if($_REQUEST["qiname"]!=""){
			$qiname = $_REQUEST["qiname"];
			if($where != "")$where .=" and ";
			$where .= "skill like '%$qiname%'";
				
			$smarty->assign('qiname', $qiname);
		}
	}
	#####################################################
	# 区域/路线
	#####################################################
	if(isset($_REQUEST['areaid']) && $_REQUEST['areaid']!=""){//地域有选择
		$prefOne = getPref($_REQUEST['areaid']);//获得指定id的都道府県
		$smarty->assign('areaid', $_REQUEST['areaid']);
		$smarty->assign('prefSearch', $prefOne);
		//分页用
		$areaid = $_REQUEST['areaid'];
		//sql--------------------------------
		if($where != "")$where .=" and ";
		$where .= "area_cd = '$_REQUEST[areaid]'";
	}
	if(isset($_REQUEST['pref']) && $_REQUEST['pref']!=""){//都道府県有选择
		$smarty->assign('pref', $_REQUEST['pref']);
		//分页用
		$pref = $_REQUEST['pref'];
		//sql--------------------------------
		if($where != "")$where .=" and ";
		$where .= "pref_cd = '$_REQUEST[pref]'";
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
	//}/*处理左侧检索框结束*/
}
if($where != "")$where .=" and ";
$where .= "info_flg = 1 ";

//-----------------分页处理开始----------------------
if(isset($_REQUEST["page"])){
	$page = $_REQUEST["page"];
}else{
	$page = 1;
}

//总条数
$user="";
$all_nums="";
if(isset($_REQUEST["Which_Show"])&&$_REQUEST["Which_Show"]!=""){
	if($_REQUEST["Which_Show"] == 2){
		$all_nums = getObjectNum("dtb_user",$where);
		$which_show = 2;
	}
	if($_REQUEST["Which_Show"] == 1){
		$all_nums = getObjectNum("dtb_companyuser",$where);
		$which_show = 1;
	}
}else{
	//默认为企业级用户
	$all_nums = getObjectNum("dtb_companyuser",$where);
}

//每页显示几条;//得到当前是第几页;
$show_num=10;$pageCurrent = $page;

$subPages = new SubPages($show_num,$all_nums,$pageCurrent,10,APP_URL."people/index/?search=true&peo_key_ser=$peo_key_ser&keyword=$keyword&kinds=$kinds&types_url=$types_url&service_id=$service_id&areaid=$areaid&pref=$pref&ensn_url=$ensn_url&eki_url=$eki_url&Which_Show=$which_show&biaoname=$biaoname&qiname=$qiname&page=");
//-----------------分页处理完了----------------------
//利用limit查询数据库---select * from table limit $start,$end
$start = $subPages->getStart($show_num,$pageCurrent);//配合分页得到起始数
$end = $subPages->getEnd($all_nums,$show_num,$pageCurrent);//配合分页得到结束数

if(isset($_REQUEST["Which_Show"])&&$_REQUEST["Which_Show"]!=""){
	if($_REQUEST["Which_Show"] == 2){
		$user = getAlluser($where,$start,$end);
		
	}
	if($_REQUEST["Which_Show"] == 1){
		$showCsql = "select * from dtb_companyuser where company_photo_url != 'upload/img/c.png' and del_flg = 0 order by rand() limit 0 , 3";
		$showCompany = $db->QueryArray($showCsql);
		$smarty->assign('showCompany', $showCompany);
		$companyuser = getAllcompanyuser($where,$start,$end);
	}
	
	$smarty->assign('Which_Show', $_REQUEST["Which_Show"]);
}else{
	$showCsql = "select * from dtb_companyuser where company_photo_url != 'upload/img/c.png' and del_flg = 0 order by rand() limit 0 , 3";
	$showCompany = $db->QueryArray($showCsql);
	$smarty->assign('showCompany', $showCompany);
	//默认为企业级用户
	$companyuser = getAllcompanyuser($where,$start,$end);
}
if($_REQUEST["Which_Show"] == 2){
	$show_user = "select name01,name02,user_photo_url,user_id,pref_cd,sex from dtb_user where user_photo_url != 'upload/img/1.png' and user_photo_url != 'upload/img/2.png' and del_flg = 0 and info_flg=1 order by login_date desc limit 0 , 20";
	$lz_user = $db->QueryArray($show_user);
	$smarty->assign('lz_user', $lz_user);
}


//准备数据---左侧检索数据
$smarty->assign('companyfrom', $companyfrom);
$smarty->assign('areas', $areas);
//$smarty->assign('life_service',$life_service);
//右侧工作信息
$smarty->assign('subPages',$subPages->showPageStr());
$smarty->assign('all', $all_nums);
if($all_nums!=0){$start=$start+1;}
$smarty->assign('start', $start);
$smarty->assign('end', $end);

$smarty->assign('peoples',$user);

if(isset($companyuser)&&$companyuser!=""){
	$smarty->assign('companyuser',$companyuser);
}
//注册个根据id查找name的方法
$smarty->register_function("getname","getName");
//注册个方法
$smarty->register_function("getstation","getStation");
//固定引入参数

$url1=$_SERVER['QUERY_STRING'];
$url1=empty($url1)? '' : '?'.$_SERVER['QUERY_STRING'];
$smarty->assign('url1',$url1);

$smarty->assign('now', date("Y-m-d"));
$smarty->assign('BASE_URL', APP_URL);
$smarty->assign('THEME', THEME);
if(isMobile()){
	$smarty->display('mobile/People/people.html');
}else{
	$smarty->display('People/people.html');
}

?>