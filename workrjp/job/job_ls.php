<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	require_once '../_includes/SubPages.php';
	require_once '../_includes/form.class.php';
	//get得到地域数据
	$areas = getPref();
	
	
//---------------检索按钮点击后进入页面---------------------------------//
	$where = "";
	$keyword = "";
	$areaid="";
	$pref="";
	$ensn_url="";
	$eki_url = "";
	
	$today = date("Y-m-d");
	$tom = date("Y-m-d",strtotime("+1 day",strtotime($today)));
	$after = date("Y-m-d",strtotime("+2 day",strtotime($today)));

// 	if(isset($_REQUEST['keyword'])){//★点击keyword查询按钮
// 		if($_REQUEST['keyword']!=""){//
// 			$smarty->assign('keyword', $_REQUEST['keyword']);
// 			$keyword = cleanInput($_REQUEST['keyword']);
// 			$where = "(job_title like '%$keyword%' or company_name like '%$keyword%' or contents like '%$keyword%')";
// 		}
// 	}
	if(isset($_REQUEST['jobls_key_ser'])&&$_REQUEST['jobls_key_ser']!=""){//★点击keyword查询按钮
		//echo '11111111111';
			$smarty->assign('jobls_key_ser', $_REQUEST['jobls_key_ser']);
			$jobls_key_ser= cleanInput($_REQUEST['jobls_key_ser']);
			$where = "(concat(user_name,job_ls_title,contents,pref_name) like '%$jobls_key_ser%')";
	}
	if($_REQUEST['starttime']!="")
	{
		// 			$startday = date("Y-m-d",strtotime($startime));
		$startday = $_REQUEST['starttime'];
		//$day2_4 = date("Y-m-d",strtotime("+1 day",strtotime($_REQUEST['starttime'])));
		if($where != "")$where .=" and ";
		$where .= "(date_star like '%$startday%' or (date_star <= '$startday' and date_end >= '$startday'))";
	}
	
	if(isset($_REQUEST["date"])&&$_REQUEST["date"]!="")
	{
		
		$date=$_REQUEST["date"];
		$smarty->assign('date', $date);
		if($where != "")$where .=" and ";
		switch ($date){
			case "today": $where .= "(date_star like '%$today%' or (date_star <= '$today' and date_end >= '$today'))"; break;
			case "tom": $where .= "(date_star like '%$tom%' or (date_star <= '$tom' and date_end >= '$tom'))"; break;
			case "after": $where .= "(date_star like '%$after%' or (date_star <= '$after' and date_end >= '$after'))"; break;
			default:
				$where .= "date_star like '%$date%'"; break;
		}
	}
		/*处理左侧检索框开始*/
		if($_REQUEST['areaid']!=""){//地域有选择
			$prefOne = getPref($_REQUEST['areaid']);//获得指定id的都道府県
			$smarty->assign('areaid', $_REQUEST['areaid']);
			$smarty->assign('prefSearch', $prefOne);
			//sql--------------------------------
			if($where != "")$where .=" and ";
			$where .= "area_cd = '$_REQUEST[areaid]'";
			
			$areaid = $_REQUEST['areaid'];
		}
		/*处理另一个*/
// 		if($_REQUEST['areaid1']!=""){//地域有选择
// 			$prefOne = getPref($_REQUEST['areaid1']);//获得指定id的都道府県
// 			$smarty->assign('areaid1', $_REQUEST['areaid1']);
// 			$smarty->assign('prefSearch1', $prefOne);
// 			//sql--------------------------------
// 		}
// 		/**/
// 		if($_REQUEST['pref1']!=""){//都道府県有选择
// 			$smarty->assign('pref1', $_REQUEST['pref1']);
// 			//sql--------------------------------
// 		}
		if($_REQUEST['pref']!=""){//都道府県有选择
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
		//echo $where.'<br>';
		//return ;
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
			if(@$ensn)
			{
				if($where != "")$where .=" or (";
			}
			else
			{
				if($where != "")$where .=" and (";
			}
			//if($where != "")$where .=" and (";
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

	//-----------------分页处理开始----------------------
	if(isset($_REQUEST["page"])){
		$page = $_REQUEST["page"];
	}else{
		$page = 1;
	}
	//总条数
	$table="dtb_job_ls";
	$all_nums = getObjectNum($table,$where);
	
	//每页显示几条;//得到当前是第几页;
  	$show_num=10;$pageCurrent = $page;
  	$subPages = new SubPages($show_num,$all_nums,$pageCurrent,10,APP_URL."job/ls/?search=true&jobls_key_ser=$jobls_key_ser&date=$date&starttime=$startday&areaid=$areaid&pref=$pref&ensn_url=$ensn_url&eki_url=$eki_url&moneyid=$moneyid&page=");
	//-----------------分页处理完了----------------------
	//利用limit查询数据库---select * from table limit $start,$end
	$start = $subPages->getStart($show_num,$pageCurrent);//配合分页得到起始数
	$end = $subPages->getEnd($all_nums,$show_num,$pageCurrent);//配合分页得到结束数
	$job_ls = getAllInfo($where,$start,$end,$table);
	
	//准备数据---左侧检索数据
	$smarty->assign('areas', $areas);

	//右侧工作信息
	$smarty->assign('subPages',$subPages->showPageStr());
	$smarty->assign('all', $all_nums);
	
	if($all_nums!=0){$start=$start+1;}
	$smarty->assign('start', $start);
	$smarty->assign('end', $end);
	
	$smarty->assign('job_ls',$job_ls);
	
	//today
	
	$yestoday = date("Y-m-d",strtotime("-1 day"));
	$bfyestoday = date("Y-m-d",strtotime("-2 day"));
	$smarty->assign('today',$today);
	$smarty->assign('yestoday',$yestoday);
	$smarty->assign('bfyestoday',$bfyestoday);

//	$today = date("Y-m-d");
	$start=time();
	if($_REQUEST['starttime'])  
	{
		$start=strtotime($_REQUEST['starttime']);

		$smarty->assign('sea_day',date("已选择Y/m/d日",$start));
	}
	//echo $start;

	
	$smarty->assign("date_star", Form::date("starttime",date("Y-m-d",$start)));
	//$smarty->assign("date_star", Form::date("date_star"));
// 	$smarty->assign("date_end", Form::date("endtime",date("Y-m-d H:i:s", ""),1));
	//注册个根据id查找name的方法
	$smarty->register_function("getname","getName");
	//注册个方法
	$smarty->register_function("getstation","getStation");
	//固定引入参数
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	$c = @$_REQUEST["c"];
	$smarty->assign('c', $c);
	
	$url1=$_SERVER['QUERY_STRING'];
	$url1=empty($url1)? '' : '?'.$_SERVER['QUERY_STRING'];
	$smarty->assign('url1',$url1);
	
// 		$smarty->display('mobile/Job/job_ls.html');
	if(isMobile()){
		$smarty->display('mobile/Job/job_ls.html');
	}else{
		$smarty->display('Job/job_ls.html');
	}
?>