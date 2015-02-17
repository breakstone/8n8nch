<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	require_once '../_includes/SubPages.php';
	
	$where = "";
	$fenlei = "";
	if(isset($_REQUEST["fenlei"])&&$_REQUEST["fenlei"]!=""){
		if($_REQUEST["fenlei"]!="闲聊"){
			$where .= "bbs_type='$_REQUEST[fenlei]'";
			$smarty->assign('fenlei',$_REQUEST["fenlei"]);
		}else{
			$where .= "bbs_type is null";
			$smarty->assign('fenlei',"闲聊");
		}
		$fenlei = $_REQUEST["fenlei"];
	}
	
	if(isset($_REQUEST["keyword"])&&$_REQUEST["keyword"]!=""){
		if($where != ""){
			$where .= " and ";
		}
		$where .= "(bbs_title like '%$_REQUEST[keyword]%' or bbs_content like '%$_REQUEST[keyword]%')";
		$smarty->assign('keyword',$_REQUEST["keyword"]);
	}
	//-----------------分页处理开始----------------------
	if(isset($_REQUEST["page"])){
		$page = $_REQUEST["page"];
	}else{
		$page = 1;
	}
	//每页显示几条;//得到当前是第几页;
	$show_num=30;$pageCurrent=$page;
	$all_nums = getObjectNum("dtb_bbs",$where);
	
	$subPages = new SubPages($show_num,$all_nums,$pageCurrent,10,APP_URL."bbs/mbbs/?fenlei=$fenlei&page=");
	//-----------------分页处理完了----------------------
	//利用limit查询数据库---select * from table limit $start,$end
	$start = $subPages->getStart($show_num,$pageCurrent);//配合分页得到起始数
	$end = $subPages->getEnd($all_nums,$show_num,$pageCurrent);//配合分页得到结束数
	$smarty->assign('subPages',$subPages->showPageStr());
	
	$bbs = getBbs($where,$start, $end);
	
	$smarty->assign('bbs',$bbs);
	
	//today
	$today = date("Y-m-d");
	$yestoday = date("Y-m-d",strtotime("-1 day"));
	$bfyestoday = date("Y-m-d",strtotime("-2 day"));
	$smarty->assign('today',$today);
	$smarty->assign('yestoday',$yestoday);
	$smarty->assign('bfyestoday',$bfyestoday);
	//一共有多少条信息
	$smarty->assign('all', $all_nums);
	//注册函数
	$smarty->register_function('getname','getName');
	//固定引入参数
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	$smarty->display('mobile/Bbs/mbbs.html');
	
?>