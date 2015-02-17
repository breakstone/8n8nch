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
	//get給付
	$money = getMoney();
	//get雇用形態
	$employ = getEmploy_method();
	//get就业希望期间
	$hope = getHope_date();
	//getその他的数据
	$condition = getCondition();
	//工作标签
	$sql_jb = "select * from mtb_job_biao";
	$biao = $db->QueryArray($sql_jb);
	
	
//---------------检索按钮点击后进入页面---------------------------------//
	$where = "";
	$keyword = "";
	$kinds="";
	$types_url="";
	$service_id="";
	$areaid="";
	$pref="";
	$ensn_url="";
	$eki_url = "";
	$moneyid = "";
	$employid = "";
	$employid_url = "";
	$hopeid = "";
	$hopeid_url = "";
	$shok3_str = "";
	$biaoname = "";
	$hopeidt2="";
	if(isset($_REQUEST['keyword'])){//★点击keyword查询按钮
		if($_REQUEST['keyword']!=""){//
			$smarty->assign('keyword', $_REQUEST['keyword']);
			$keyword = cleanInput($_REQUEST['keyword']);
			$where = "(job_title like '%$keyword%' or company_name like '%$keyword%' or contents like '%$keyword%')";
		}
	}

	if(isset($_REQUEST["biaoname"])){
		if($_REQUEST["biaoname"]!=""){
			
			$biaoname = $_REQUEST["biaoname"];
			if($biaoname=='不限') $biaoname='';
			if($where != "")$where .=" and ";
			$where .= "level like '%$biaoname%'";
			
			$smarty->assign('biaoname', $biaoname);
		}
	}
	if(isset($_REQUEST['search'])||isset($_REQUEST['searchEki'])){//★点击查询按钮的情况
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
		if(@$_REQUEST['moneyid']!=""){//給付有选择
			$smarty->assign('moneyid', $_REQUEST['moneyid']);
			//sql--------------------------------
			if($where != "")$where .=" and ";
			$where .= "moneyid = '$_REQUEST[moneyid]'";
			
			$moneyid = $_REQUEST['moneyid'];
		}
		if(@$_REQUEST['employid']!=""){//雇用形態有选择
			//sql--------------------------------
			if($where != ""){$where .=" and (";}else{$where = "(";};
			foreach($_REQUEST['employid'] as $e){
				$where .= "employed_method like '%$e%'";
				$where .= " or ";
			}
			$where = rtrim($where,"or ");
			$where .= ") ";
			
			if(isset($_REQUEST['employid'])&&$_REQUEST['employid']!=""){
				$employid = $_REQUEST['employid'];
				$employid_url = json_encode($employid);
			}
			if(isset($_REQUEST['employid_url'])&&$_REQUEST['employid_url']!=""){
				$employid = json_decode($_REQUEST['employid_url']);
				$employid_url = $_REQUEST['employid_url'];
			}
			$smarty->assign('employid', $employid);
		}
		if(@$_REQUEST['hopeid']!=""||@$_REQUEST['hopeid_url']!=""){//就業希望期間有选择
			//sql--------------------------------
			if($where != ""){$where .=" and (";}else{$where = "(";};
			foreach($_REQUEST['hopeid'] as $h){
				$where .= "hope_date like '%$h%'";
				$where .= " or ";
			}
			$where = rtrim($where,"or ");
			$where .= ") ";
			
			if(isset($_REQUEST['hopeid'])&&$_REQUEST['hopeid']!=""){
				$hopeid = $_REQUEST['hopeid'];
				$hopeid_url = json_encode($hopeid);
			}
			if(isset($_REQUEST['hopeid_url'])&&$_REQUEST['hopeid_url']!=""){
				$hopeid = json_decode($_REQUEST['hopeid_url']);
				$hopeid_url = $_REQUEST['hopeid_url'];
			}
			$smarty->assign('hopeid', $hopeid);
		}
		if($hopeid!="")
		{
			for($i=0;$i<count($hopeid);$i++)
			{
				if($hopeid[$i] != "") $hopeid[$i].="&";
				$hopeidt2.="hopeid[]=$hopeid[$i]";
				
			}
		}
		if(isset($_REQUEST['shok3'])&&$_REQUEST['shok3']!=""){//その他有选择
			$smarty->assign('conditionSearch', $_REQUEST['shok3']);
			//sql--------------------------------
			foreach($_REQUEST['shok3'] as $v){
				$v = cleanInput($v);
				if($where != "")$where .=" and ";
				$where .= "condition_id like '%$v%'";
			}
			
			$shok3_str = json_encode($_REQUEST['shok3']);
		}
		if(isset($_REQUEST["shok3_str"])&&$_REQUEST["shok3_str"]!=""){
			$shok3 = json_decode($_REQUEST["shok3_str"]);
			$smarty->assign('conditionSearch', $shok3);
			$shok3_str = $_REQUEST["shok3_str"];
		}
		/*处理左侧检索框结束*/
	}
	//-----------------分页处理开始----------------------
	if(isset($_REQUEST["page"])){
		$page = $_REQUEST["page"];
	}else{
		$page = 1;
	}
	//总条数
	$all_nums = getObjectNum("dtb_jobs",$where);
	
	//每页显示几条;//得到当前是第几页;
  	$show_num=20;$pageCurrent = $page;
  	$subPages = new SubPages($show_num,$all_nums,$pageCurrent,10,APP_URL."job/index/?search=true&keyword=$keyword&kinds=$kinds&types_url=$types_url&service_id=$service_id&areaid=$areaid&pref=$pref&ensn_url=$ensn_url&eki_url=$eki_url&moneyid=$moneyid&employid_url=$employid_url&$hopeidt2"."shok3_str=$shok3_str&biaoname=$biaoname&page=");
	//-----------------分页处理完了----------------------
	//利用limit查询数据库---select * from table limit $start,$end
	$start = $subPages->getStart($show_num,$pageCurrent);//配合分页得到起始数
	$end = $subPages->getEnd($all_nums,$show_num,$pageCurrent);//配合分页得到结束数
	$jobs = getJobs($where,$start,$end);
	
	//准备数据---左侧检索数据
	$smarty->assign('companyfrom', $companyfrom);
	$smarty->assign('areas', $areas);
	$smarty->assign('money', $money);
	$smarty->assign('employ', $employ);
	$smarty->assign('hope', $hope);
	$smarty->assign('condition',$condition);
	$smarty->assign('biao',$biao);
	//右侧工作信息
	$smarty->assign('subPages',$subPages->showPageStr());
	$smarty->assign('all', $all_nums);
	if($all_nums!=0){$start=$start+1;}
	$smarty->assign('start', $start);
	$smarty->assign('end', $end);
	
	$smarty->assign('jobs',$jobs);
	
	//today
	$today = date("Y-m-d");
	$yestoday = date("Y-m-d",strtotime("-1 day"));
	$bfyestoday = date("Y-m-d",strtotime("-2 day"));
	$smarty->assign('today',$today);
	$smarty->assign('yestoday',$yestoday);
	$smarty->assign('bfyestoday',$bfyestoday);
	
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
	
	if(isMobile()){
		if($c==""){
			$smarty->display('mobile/Job/job.html');
		}elseif($c=="dd"){
			$smarty->display('Job/job_dd.html');
		}elseif($c=="zz"){
			$smarty->display('Job/job_zz.html');
		}elseif($c=="sj"){
			$smarty->display('Job/job_sj.html');
		}elseif($c=="gy"){
			$smarty->display('Job/job_gy.html');
		}
	}else{
		if($c==""){
			$smarty->display('Job/job.html');
		}elseif($c=="dd"){
			$smarty->display('Job/job_dd.html');
		}elseif($c=="zz"){
			$smarty->display('Job/job_zz.html');
		}elseif($c=="sj"){
			$smarty->display('Job/job_sj.html');
		}elseif($c=="gy"){
			$smarty->display('Job/job_gy.html');
		}
	}
	//$smarty->display('mobile/Job/job.html');
?>