<?php
	if(!file_exists('../../_config/config.php'))
	{
		die('[index.php] _config/config.php not found');
	}
	require_once '../../_config/config.php';
	require_once '../../_includes/functions.php';
	require_once '../../_includes/SubPages.php';
	
		if(@$_SESSION['kiwa_companyid']=="141113009425"){
			if(isset($_POST["del"])){
				$id = $_POST["del"];
				$sql="update kiwa_loto set del_flg=1 where Id='$id'";
				$db->Execute($sql);
				header('Location:'.APP_URL.'people/kiwalogin/lzactivitysee.php');
			}
			$date=date("Y-m-d");
			// 			$date="2014-12-31";
			$timestamp=strtotime($date);
				
			//计算当前月份的上一个月
			$last_mon=date('Y-m',strtotime(date('Y',$timestamp).'-'.(date('m',$timestamp)-1).'-01'));
		//固定引入参数
	//	$sql_loto = "select * from kiwa_loto  where loto_num != 4 and loto_date='$last_mon-01' and del_flg =0 group by loto_date order by loto_num";
		$sql_loto = "select * from kiwa_loto  where loto_num != 4  and del_flg =0  order by loto_date,loto_num";
// 		echo $sql_loto;
		$user = $db->QueryArray($sql_loto);
		$smarty->assign('user', $user);
		$smarty->assign('BASE_URL', APP_URL);
		$smarty->assign('THEME', THEME);
		$smarty->display('People/KiwaLogin/zhuan/lzactivitysee.html');
	}else{
		echo "系统错误,请重新登录";
	}	
?>