<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	require_once '../_includes/SubPages.php';

	//get得到业种数据
	$companyfrom = getCompanyfrom();
	//get得到地域数据
	$areas = getPref();
	//get得到生活服务内容
	$life_service = getLifeservice();
	global $db;
	$sql_1 = "select * from mtb_life_service where funlei = '生活相关' order by rank";
	$shenghuo = $db->QueryArray($sql_1);
	$sql_2 = "select * from mtb_life_service where funlei = '赚钱相关' order by rank";
	$zhuanqian = $db->QueryArray($sql_2);
	$smarty->assign('shenghuo', $shenghuo);
	$smarty->assign('zhuanqian', $zhuanqian);
//---------------检索按钮点击后进入页面---------------------------------//
	$where = "";
	if(isset($_POST['keyword'])){//★点击keyword查询按钮
		if($_POST['keyword']!=""){//
			$smarty->assign('keyword', $_POST['keyword']);
			$keyword = cleanInput($_POST['keyword']);
			$where = "live_title like '%$keyword%' or live_content like '%$keyword%'";
		}	
	}
	if(isset($_POST['search'])||isset($_POST['searchEki'])){//★点击查询按钮的情况
		/*处理左侧检索框开始*/
		if(isset($_REQUEST['service_id'])&&$_REQUEST['service_id'] != ""){
			if($where != "")$where .=" and ";
			$where .= "service_name = '$_REQUEST[service_id]'";
			//分页用
			$service_id = $_REQUEST['service_id'];
			$smarty->assign('service_id', $service_id);
			$smarty->assign('service_name', $service_id);
		}
		if($_POST['areaid']!=""){//地域有选择
			$prefOne = getPref($_POST['areaid']);//获得指定id的都道府県
			$smarty->assign('areaid', $_POST['areaid']);
			$smarty->assign('prefSearch', $prefOne);
			//sql--------------------------------
			if($where != "")$where .=" and ";
			$where .= "area_cd = '$_POST[areaid]'";
		}
		if($_POST['pref']!=""){//都道府県有选择
			$smarty->assign('pref', $_POST['pref']);
			//sql--------------------------------
			if($where != "")$where .=" and ";
			$where .= "pref_cd = '$_POST[pref]'";
		}
		if(isset($_POST["ensn"])&&$_POST["ensn"]!=""){//線
			//print_r($_POST["ensn"]);
			$smarty->assign('ensn', $_POST["ensn"]);
			//sql--------------------------------
			if($where != "")$where .=" and (";
			foreach($_POST["ensn"] as $l){
				$where .= "line_num like '%$l%'";
				$where .= " or ";
			}
			$where = rtrim($where,"or ");
			$where .= " or (line_num is null and station_cd is null)) ";
		}
		if(isset($_POST["eki"])&&$_POST["eki"]!=""){//駅
			//print_r($_POST["eki"]);
			$smarty->assign('eki', $_POST["eki"]);
			//sql--------------------------------
			if($where != "")$where .=" and (";
			foreach($_POST["eki"] as $e){
				//如果线路里的车站做处理
				$line_eki = substr($e,0,5);
				
				$where .= "station_cd like '%$e%'";
				$where .= " or ";
				$where .= "line_num like '%$line_eki%'";
				$where .= " or ";
			}
			$where = rtrim($where,"or ");
			$where .= " or (line_num is null and station_cd is null)) ";
		}/*处理左侧检索框结束*/
	}
	//-----------------分页处理开始----------------------
	if($id == ""){$id = 1;}
	//总条数
	$all_nums = getObjectNum("dtb_lives",$where);
	
	//每页显示几条;//得到当前是第几页;
  	$show_num=10;$pageCurrent = $id;
  	$subPages = new SubPages($show_num,$all_nums,$pageCurrent,10,APP_URL."live/index/");
	//-----------------分页处理完了----------------------
	//利用limit查询数据库---select * from table limit $start,$end
	$start = $subPages->getStart($show_num,$pageCurrent);//配合分页得到起始数
	$end = $subPages->getEnd($all_nums,$show_num,$pageCurrent);//配合分页得到结束数
	$lives = getLives($where,$start,$end);
	
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
	$smarty->display('Live/live.html');
?>