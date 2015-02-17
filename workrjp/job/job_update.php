<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	if((isset($_SESSION["kiwa_userid"])&&$_SESSION["kiwa_userid"]!="")||(isset($_SESSION["kiwa_companyid"])&&$_SESSION["kiwa_companyid"]!="")){
	{
		
		$job = getJobs("job_id='$id'",0, 1);
		
		if ((isset($_SESSION["kiwa_companyid"])&&$_SESSION["kiwa_companyid"]=="000001") ||$job[0]["company_id"]==@$_SESSION["kiwa_userid"] || $job[0]["company_id"]==@$_SESSION["kiwa_companyid"])
		{
				//求人标题
				$job_title=$job[0]['job_title'];
				$start=strpos($job_title,'(');
				$end=strpos($job_title,')');
				//是否收费
			
				if($end==7 && $start==0)
				{
					$shoufei=substr($job_title,$start,$end+1);
					//截取工作
					$job_title=substr($job_title,$end+1);
				}
				$smarty->assign('job_title',$job_title);
				//工作内容
				$contents = str_replace("<br>","\r\n",$job[0]["contents"]);
				$smarty->assign('contents', $contents);
				//招收对象
				$requirements = str_replace("<br>","\r\n",$job[0]["requirements"]);
				$smarty->assign('requirements', $requirements);
				$smarty->assign('lianxi',$job[0]["lianxi"]);
				$smarty->assign('shoufei',@$shoufei);
				if(isset($job[0]['numbers'])&&$job[0]['numbers']!=""){
					//招收人数
					$smarty->assign('numbers', $job[0]['numbers']);
				}
			
				$zz=explode(",",$job[0]["level"]);
				
				$smarty->assign('zz', $zz);
				$smarty->assign('w_d', $job[0]["hope_week_day"]);
				
				//根据地域得出城市
				$prefs = getPref($job[0]['area_cd']);
				$smarty->assign('prefSearch', $prefs);
				//根据业种得出职种
				$types = getTypes($job[0]['kindsID']);
			
				$smarty->assign('types', $types);
				
				//希望工作地
				$smarty->assign('areaid',$job[0]['area_cd']);
				$smarty->assign('pref', $job[0]['pref_cd']);
			
				if(isset($job[0]['station_cd'])&&$job[0]['station_cd']!=""){
					$station_cd=explode(",",$job[0]['station_cd']);
					$smarty->assign('eki', $station_cd);
				}
				
				//业种・职业选择
				$smarty->assign('kinds', $job[0]['kindsID']);
				$select_types=explode(",",$job[0]['typesID']);
				$smarty->assign('select_types',$select_types);
				//薪资选择
				$smarty->assign('moneyid',$job[0]['moneyid']);
				//雇用形式选择
				$employed_method=explode(",",$job[0]['employed_method']);
				$smarty->assign('employid', $employed_method);
				//工作时间
				$hope_date=explode(",",$job[0]['hope_date']);
				$smarty->assign('hopeid', $hope_date);
				if(isset($job[0]['condition_id'])&&$job[0]['condition_id']!=""){
					//其他条件
					$condition_id=explode(",",$job[0]['condition_id']);
					$smarty->assign('conditions', $condition_id);
				}
				
			
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
			//get各种条件
			$condition = getCondition();
			
			$smarty->assign('companyfrom', $companyfrom);
			$smarty->assign('areas', $areas);
			$smarty->assign('money', $money);
			$smarty->assign('employ', $employ);
			$smarty->assign('hope', $hope);
			$smarty->assign('condition', $condition);
		
			$smarty->assign('job', $job[0]);
			//注册个方法
			$smarty->register_function('getname','getName');
			$smarty->assign('BASE_URL', APP_URL);
			$smarty->assign('THEME', THEME);
			if(isMobile()){
				$smarty->display('mobile/Job/job_update.html');
			}else{
				$smarty->display('Job/job_update.html');
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