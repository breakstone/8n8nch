<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
// 	$table="dtb_job_ls";
// 	$job = getAllInfo("job_ls_id='$id'",0, 1,$table);
// 	$uid = $job[0]["user_id"];
	//echo $uid;
	if($_SESSION['kiwa_companyid']=="141113009425" ){
		
		$sql="update dtb_job_ls set del_flg=1 where Id='$id'";
		
	
		if($db->Execute($sql)){
			echo '<script>
						window.location="'.APP_URL.'job/ls";
						alert("删除成功");';
			echo '</script>';
		
		}else{
			echo '<script>
						window.location="'.APP_URL.'job/ls";
						alert("删除失败");';
			echo '</script>';
		}
		
	
	}else{
		//固定引入参数
		$smarty->assign('BASE_URL', APP_URL);
		$smarty->assign('THEME', THEME);
		$smarty->display('error.html');
	}
?>