<?php
	if(!file_exists('_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '_config/config.php';
	require_once '_includes/functions.php';
	require_once '_includes/SubPages.php';
	
	$keyword = $_GET["keyword"];
	$smarty->assign('keyword', $keyword);
	$fenlei = $_REQUEST["fenlei"];
	$smarty->assign('fenlei', $fenlei);
	
	if($_GET["p"]!=""){
		$p = $_GET["p"];
	}else{
		$p = 1;
	}
	
	if($fenlei=="job"){
		
		$where = "(job_title like '%$keyword%' or company_name like '%$keyword%' or contents like '%$keyword%')";
		//总条数
		$all_nums = getObjectNum("dtb_jobs",$where);
		//每页显示几条;//得到当前是第几页;
		$show_num=20;
		$pageCurrent = $p;
		$subPages = new SubPages($show_num,$all_nums,$pageCurrent,10,APP_URL."search.php?keyword=$keyword&fenlei=$fenlei&p=");
		//-----------------分页处理完了----------------------
		//利用limit查询数据库---select * from table limit $start,$end
		$start = $subPages->getStart($show_num,$pageCurrent);//配合分页得到起始数
		$end = $subPages->getEnd($all_nums,$show_num,$pageCurrent);//配合分页得到结束数
		
		$jobs = getJobs($where,$start,$end);
		
		//右侧工作信息
		$smarty->assign('subPages',$subPages->showPageStr());
		$smarty->assign('all', $all_nums);
		if($all_nums!=0){$start=$start+1;}
		$smarty->assign('start', $start);
		$smarty->assign('end', $end);
		
		$smarty->assign('jobs',$jobs);
	}
	if($fenlei=="bbs"){
		$where = "(bbs_title like '%$_REQUEST[keyword]%' or bbs_content like '%$_REQUEST[keyword]%')";
		//每页显示几条;//得到当前是第几页;
		$show_num=20;$pageCurrent=$p;
		$all_nums = getObjectNum("dtb_bbs",$where);
		$smarty->assign('all',$all_nums);
		
		$subPages = new SubPages($show_num,$all_nums,$pageCurrent,10,APP_URL."search.php?keyword=$keyword&fenlei=$fenlei&p=");
		//-----------------分页处理完了----------------------
		//利用limit查询数据库---select * from table limit $start,$end
		$start = $subPages->getStart($show_num,$pageCurrent);//配合分页得到起始数
		$end = $subPages->getEnd($all_nums,$show_num,$pageCurrent);//配合分页得到结束数
		$smarty->assign('subPages',$subPages->showPageStr());
		
		$bbs = getBbs($where,$start, $end);
		
		$smarty->assign('bbs',$bbs);
	}
	if($fenlei=="live"){
		$where = "(live_title like '%$keyword%' or live_content like '%$keyword%')";
		
		//总条数
		$all_nums = getObjectNum("dtb_lives",$where);
		
		//每页显示几条;//得到当前是第几页;
		$show_num=12;$pageCurrent = $p;
		$subPages = new SubPages($show_num,$all_nums,$pageCurrent,10,APP_URL."search.php?keyword=$keyword&fenlei=$fenlei&p=");
		//-----------------分页处理完了----------------------
		//利用limit查询数据库---select * from table limit $start,$end
		$start = $subPages->getStart($show_num,$pageCurrent);//配合分页得到起始数
		$end = $subPages->getEnd($all_nums,$show_num,$pageCurrent);//配合分页得到结束数
		$lives = getLives($where,$start,$end);
		
		$smarty->assign('lives',$lives);
	}
	
	//注册个根据id查找name的方法
	$smarty->register_function("getname","getName");
	//固定引入参数
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	
	$smarty->display("search.html");
?>