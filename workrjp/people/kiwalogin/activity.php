<?php
	if(!file_exists('../../_config/config.php'))
	{
		die('[index.php] _config/config.php not found');
	}
	require_once '../../_config/config.php';
	require_once '../../_includes/functions.php';
	require_once '../../_includes/SubPages.php';
	
		$user_id=cleanInput($_REQUEST["user_id"]);
		$pwd=cleanInput($_REQUEST["pwd"]);
		$smarty->assign('user_id', $user_id);
		
		$sqlks = "select * from kiwa_user where staff_id='$user_id' and passwd = '$pwd' and bytDeleteFlag = 0";
		$staff = $db->QueryRow($sqlks);
		
		if($staff["staff_id"]!=""){
		///////////////////////////////////////////////////
			$mon = date("Y-m");
			$start_date=$mon."-16 00:00:00";
			
			$now=strtotime(date("Y-m-d")." 00:00:00");
// 			$now=strtotime(date("2015-01-16")." 00:00:00");
			if( $now >= strtotime($start_date)){
				
				//$today=date("Y-m");
				//$today=date("2014-12");
				$last_mon = date("Y-m",strtotime("-31 day",strtotime($today)));
				$sql_work= "select count(*) as count from kiwa_mingxi where staff_id='$user_id' and moneymonth = '$last_mon' and delflag = 0";
				$cz_work = $db->QueryRow($sql_work);
				
				if($cz_work['count']==0){
					$smarty->assign('nowork','1');
				}else{
					$smarty->assign('canclick','1');
				}
			}
			//提交按钮
			$loto_num_sql= "select loto_num from kiwa_loto where staff_id='$user_id' and  loto_date like '%$mon%'";
			$loto_num= $db->QueryRow($loto_num_sql);
			//var_dump($loto_num);
			if($_POST["number"]&&$_POST["number"]!=$loto_num['loto_num']&&isset($user_id) && $user_id!=""){
				$sql_name= "select name from kiwa_mingxi where staff_id='$user_id'";
				$user_name = $db->QueryRow($sql_name);
				$return = loto_add($user_id, $user_name["name"], $_POST["number"]);
				
				$loto_num_sql= "select loto_num from kiwa_loto where staff_id='$user_id' and  loto_date like '%$mon%'";
				$loto_num= $db->QueryRow($loto_num_sql);
				
				if(!$return){
					if(!$return) echo "抽奖失败" ;exit();
				}
			}
			
			if(isset($user_id) && $user_id!=""){
				if($loto_num['loto_num']){
					$smarty->assign('number', $loto_num['loto_num']);
					$smarty->assign('yclick','1');
				}else {
					while (1){
						$number=rand(1000,9999);
						$sql_cz= "select count(*) as count from kiwa_loto where loto_num='$number' and  loto_date like '%$mon%'";
						$cz= $db->QueryRow($sql_cz);
						if(!$cz["count"]){
							$smarty->assign('number', $number); 
							break;
						}
					}
				}
			}
		
		//固定引入参数
		$smarty->assign('BASE_URL', APP_URL);
		$smarty->assign('THEME', THEME);
		$smarty->display('People/KiwaLogin/chou/activity.html');
	}else{
		echo "系统错误,请重新登录";
	}	
		##########################################
		# loto_add 插入所抽选的数字
		##########################################
		function loto_add($user_id,$user_name,$loto_num){
			global $db;
			$user_id = $db->real_escape_string($user_id);
			$user_name = $db->real_escape_string($user_name);
			$loto_num = $db->real_escape_string($loto_num);
			$loto_date = date("Y-m-d");
			$now = date("Y-m-d H:i:s");
			$sql = "insert into kiwa_loto set staff_id = '$user_id',kiwaname = '$user_name',loto_num='$loto_num',loto_date='$loto_date',create_date='$now'";
	
			if($db->Execute($sql)){
				return true;
			}else{
				return false;
			}
		}
?>