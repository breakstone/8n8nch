<?php
	if(!file_exists('../../_config/config.php'))
	{
		die('[index.php] _config/config.php not found');
	}
	require_once '../../_config/config.php';
	require_once '../../_includes/functions.php';
	require_once '../../_includes/SubPages.php';
	
		$company = getCompany($_REQUEST["id"]);
		$smarty->assign("company", $company);
		///////////////////////////////////////////////////
		//可能感兴趣的工作信息contents
		
		$sql_jobs= "select * from dtb_jobs where del_flg=0 order by rand() limit 0 , 6";
		$job1 = $db->QueryArray($sql_jobs);
		$smarty->assign('job1', $job1);
		$sql_bbs= "select * from dtb_bbs where del_flg=0 order by rand() limit 0 , 5";
		$bbs = $db->QueryArray($sql_bbs);
		$smarty->assign('bbs', $bbs);
		
		$user_id=cleanInput($_REQUEST["user_id"]);
		$pwd=cleanInput($_REQUEST["pwd"]);
		$smarty->assign('user_id', $user_id);
		
		$sqlks = "select * from kiwa_user where staff_id='$user_id' and passwd = '$pwd' and bytDeleteFlag = 0";
		$staff = $db->QueryRow($sqlks);
	
		if($staff["staff_id"]!=""){
			$smarty->assign('pwd', $_REQUEST["pwd"]);
			$mon = date("Y-m");
			$start_date=$mon."-20 00:00:00";
			
			$date=date("Y-m-d");
// 			$date="2014-12-31";
			$timestamp=strtotime($date);
			
			//计算当前月份的上一个月
			$last_mon=date('Y-m',strtotime(date('Y',$timestamp).'-'.(date('m',$timestamp)-1).'-01'));
			//echo $last_mon;
			
			$now=strtotime(date("Y-m-d")." 00:00:00");
// 			$now=strtotime(date("2015-01-16")." 00:00:00");
			
			if( $now >= strtotime($start_date)){
				$sql_work= "select count(*) as count from kiwa_mingxi where staff_id='$user_id' and moneymonth = '$last_mon' and delflag = 0";
				$cz_work = $db->QueryRow($sql_work);
				if($cz_work['count']==0){
					$smarty->assign('mess','由于你上个月未进行工作，所以本月不能进行抽奖活动.');
				}else{
					$loto_num_sql= "select loto_num from kiwa_loto where staff_id='$user_id' and  loto_date like '%$last_mon%' and del_flg = 0";
					$loto_num= $db->QueryRow($loto_num_sql);
					if($loto_num['loto_num']){
						if($loto_num['loto_num']==4)
						{
							$smarty->assign('mess','你已经抽完奖。很遗憾你什么都没有抽中。');
						}elseif($loto_num['loto_num']==3){
							$smarty->assign('mess','你已抽完奖。恭喜你获得三等奖、奖金一千円。');
						}elseif($loto_num['loto_num']==2){
							$smarty->assign('mess','你已抽完奖。恭喜你获得二等奖、奖金一万円。');
						}
						elseif($loto_num['loto_num']==1){
							$smarty->assign('mess','你已抽完奖。恭喜你获得一等奖、奖金三万円。');
						}
						$smarty->assign('mess1','谢谢你的参与、请等待下期抽奖.');
						//exit("该月你以抽完奖，请等待下期开奖");
					}
				}
				
			}else{
				$smarty->assign('mess2','活动还未开始<br>请在本月的20号至31号之间登录,进行抽奖活动.');
			}
			
		//固定引入参数
		$smarty->assign('BASE_URL', APP_URL);
		$smarty->assign('THEME', THEME);
		if(isMobile()){
			$smarty->display('mobile/People/KiwaLogin/zhuan/lzactivity.html');
		}else{
				$smarty->display('People/KiwaLogin/zhuan/lzactivity.html');
		}
	
	}else{
		echo "系统错误,请重新登录";
	}	
?>