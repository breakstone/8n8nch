<?php
	require_once '../_includes/SubPages.php';
	$where = "company_id = '$_SESSION[kiwa_companyid]'";
	
	if($id == ""){$id = 1;}
	//总条数
	$all_nums = getLvliNum("dtb_job_lvli",$where);
	
	//每页显示几条;//得到当前是第几页;
  	$show_num=10;$pageCurrent = $id;
  	$subPages = new SubPages($show_num,$all_nums,$pageCurrent,10,APP_URL."companypage/getnote/");
	//-----------------分页处理完了----------------------
	//利用limit查询数据库---select * from table limit $start,$end
	$start = $subPages->getStart($show_num,$pageCurrent);//配合分页得到起始数
	$end = $subPages->getEnd($all_nums,$show_num,$pageCurrent);//配合分页得到结束数
	$jianli = getCompanyJianli($where,$start,$end);
	
	//前台分页
	$smarty->assign('subPages',$subPages->showPageStr());
	//总数
	$smarty->assign('all', $all_nums);
	//收到个人信件list
	$smarty->assign('jianli',$jianli);
	
	//注册个方法
	$smarty->register_function('getname','getName');
	$smarty->register_function('getjobstatus','getJobStatus');
	//固定引入参数
	//mypage页面信息查询
	$smarty->assign('THEME', THEME);
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('COMPANYNAME', $_SESSION['kiwa_companyname']);
	$smarty->assign('COMPANYID', $_SESSION['kiwa_companyid']);
	//得到未读message
	$um = unreadMessage($_SESSION['kiwa_companyid']);
	$smarty->assign('unread', $um);
	$smarty->display('Companypage/getnote.html');
?>