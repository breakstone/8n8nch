<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	require_once '../_includes/form.class.php';
	if((isset($_SESSION["kiwa_userid"])&&$_SESSION["kiwa_userid"]!="")||(isset($_SESSION["kiwa_companyid"])&&$_SESSION["kiwa_companyid"]!="")){
	{
		
		$table="dtb_job_ls";
		$job = getAllInfo("job_ls_id='$id'",0, 1,$table);
		
		if ((isset($_SESSION["kiwa_companyid"])&&$_SESSION["kiwa_companyid"]=="000001") ||$job[0]["company_id"]==@$_SESSION["kiwa_userid"] || $job[0]["company_id"]==@$_SESSION["kiwa_companyid"])
		{
				//求人标题
				$job_title=$job[0]['job_ls_title'];
				$smarty->assign('job_ls_title',$job_title);
				//工作内容
				$contents = str_replace("<br>","\r\n",$job[0]["contents"]);
				$smarty->assign('contents', $contents);
				//招收对象
				$smarty->assign('lianxi',$job[0]["lianxi"]);
				
				//根据地域得出城市
				$prefs = getPref($job[0]['area_cd']);
				$smarty->assign('prefSearch', $prefs);

				//希望工作地
				$smarty->assign('areaid',$job[0]['area_cd']);
				$smarty->assign('pref', $job[0]['pref_cd']);
			
				if(isset($job[0]['station_cd'])&&$job[0]['station_cd']!=""){
					$station_cd=explode(",",$job[0]['station_cd']);
					$smarty->assign('eki', $station_cd);
				}
				if(isset($job[0]['line_num'])&&$job[0]['line_num']!=""){
					$line_num=explode(",",$job[0]['line_num']);
					$smarty->assign('ensn', $line_num);
				}
				$smarty->assign("date_star", Form::date("starttime",date("Y-m-d ",strtotime($job[0]['date_star']))));
				//$smarty->assign("date_star", Form::date("date_star"));
				$smarty->assign("date_end", Form::date("endtime",date("Y-m-d ",strtotime($job[0]['date_end']))));
			
			//get得到地域数据
			$areas = getPref();
			
			$smarty->assign('areas', $areas);
		
			$smarty->assign('job', $job[0]);
			//注册个方法
			$smarty->register_function('getname','getName');
			$smarty->assign('BASE_URL', APP_URL);
			$smarty->assign('THEME', THEME);
			if(isMobile()){
				$smarty->display('mobile/Job/job_update_ls.html');
			}else{
				$smarty->display('Job/job_update_ls.html');
			}
		}
		else {
			$smarty->assign('BASE_URL', APP_URL);
			$smarty->assign('THEME', THEME);
			$smarty->display('error.html');
		}
		
		
	}
	}else{
			$smarty->assign('BASE_URL', APP_URL);
			$smarty->assign('THEME', THEME);
			$smarty->display('error.html');
	}

?>