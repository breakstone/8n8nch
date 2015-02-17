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
	$jp="";
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
	if(isset($_REQUEST["jp"])&&$_REQUEST["jp"]!=""){
			$where .= "read_num > 1000";
			$smarty->assign('jp',"1");
			$jp=1;
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
	$smarty->assign('all',$all_nums);
	
	$subPages = new SubPages($show_num,$all_nums,$pageCurrent,10,APP_URL."bbs/?jp=$jp&fenlei=$fenlei&page=");
	//-----------------分页处理完了----------------------
	//利用limit查询数据库---select * from table limit $start,$end
	$start = $subPages->getStart($show_num,$pageCurrent);//配合分页得到起始数
	$end = $subPages->getEnd($all_nums,$show_num,$pageCurrent);//配合分页得到结束数
	$smarty->assign('subPages',$subPages->showPageStr());
	
	$bbs = getBbs($where,$start, $end);
	
	$smarty->assign('bbs',$bbs);
	
	$today = date("Y-m-d");
	//轮转图片
	$weak = date("Y-m-d",strtotime("-13 day",strtotime($today)));
	$sql_tp="select * from dtb_bbs where bbs_photo_html5 !='' and del_flg = 0 and create_date >= '$weak'  order by read_num desc limit 0 , 20 ";
	$bbs_tpall =$db->QueryArray($sql_tp);
	$temp=array_rand($bbs_tpall, 4);
	
	foreach($temp as $val){
		$bbs_tp[]=$bbs_tpall[$val];
	}
	$smarty->assign('bbs_tp',$bbs_tp);
	//最新热门话题
	$day = date("Y-m-d",strtotime("-3 day",strtotime($today)));
	$sql_zx="select * from dtb_bbs where del_flg = 0 and create_date > '$day' order by read_num desc limit 0 , 6 ";
	$bbs_zx =$db->QueryArray($sql_zx);
	$smarty->assign('bbs_zx',$bbs_zx);
	//today
	$today = date("Y-m-d");
	$yestoday = date("Y-m-d",strtotime("-1 day"));
	$bfyestoday = date("Y-m-d",strtotime("-2 day"));
	$smarty->assign('today',$today);
	$smarty->assign('yestoday',$yestoday);
	$smarty->assign('bfyestoday',$bfyestoday);
	
	$url1=$_SERVER['QUERY_STRING'];
	$url1=empty($url1)? '' : '?'.$_SERVER['QUERY_STRING'];
	$smarty->assign('url1',$url1);
	
	//注册函数
	$smarty->register_function('getname','getName');
	//固定引入参数
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	
	if(isMobile()){
		$smarty->display('mobile/Bbs/bbs.html');
	}else{
		$smarty->display('Bbs/bbs.html');
	}
	
?>