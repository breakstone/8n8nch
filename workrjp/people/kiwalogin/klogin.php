<?php
	if(!file_exists('../../_config/config.php'))
	{
		die('[index.php] _config/config.php not found');
	}
	require_once '../../_config/config.php';
	require_once '../../_includes/functions.php';
	require_once '../../_includes/SubPages.php';
		///////////////////////////////////////////////////
			//可能感兴趣的工作信息contents
			$where = "";
			$keyword = cleanInput('免费');
			$where= "job_title like '%$keyword%'";
			if(@$_REQUEST['kinds']!=""){//业种有选择
				
				if($where != "")$where .=" and ";
				$where .= "kindsID = '$_REQUEST[kinds]'";
			}
		
			if(isset($_REQUEST["jid"])&&$_REQUEST["jid"]!=""){
				$jid = $_REQUEST["jid"];
			}else{
				$jid = 1;
			}
			$start1 =$jid-2;
			if($start1 < 0)
			{
				$start1=0;
			}
			$job1 = getJobs($where,$start1,6);
			$smarty->assign('start1', $start1);
			if($job1=='')
			{
				$job1 = getJobs($where,0,6);
				$smarty->assign('start1', 0);
				
			}
			$smarty->assign('job1',$job1);
		
			$smarty->assign('where', $where);
			
			$sql_bbs= "select * from dtb_bbs where del_flg=0 order by rand() limit 0 , 5";
			$bbs = $db->QueryArray($sql_bbs);
			$smarty->assign('bbs', $bbs);
			
			//////////////////////////////////////////////////
		$company = getCompany($_GET["id"]);
		$smarty->assign("company", $company);
		$smarty->assign("id", $_GET["id"]);
		$companytitle=$company["company_name"]."|工资查询系统";
		$smarty->assign("title", $companytitle);
		
		$sql = "select * from dtb_people_page where del_flg = 0 and user_id='$_GET[id]'";
		$company_page = $db->QueryRow($sql);
		$smarty->assign('company_page', $company_page);
		
		if(@$_GET["error"]==1){
			$smarty->assign('error', "社员番号与密码输入错误，请确认！");
		}
	
		//固定引入参数
		$smarty->assign('BASE_URL', APP_URL);
		$smarty->assign('THEME', THEME);
		//$smarty->display('mobile/People/KiwaLogin/klogin.html');

	if(isMobile()){
		$smarty->display('mobile/People/KiwaLogin/klogin.html');
	}else{
		$smarty->display('People/KiwaLogin/klogin.html');
	}
?>