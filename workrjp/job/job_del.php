<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	
	$job = getJobs("job_id='$id'",0, 1);
	$uid = $job[0]["company_id"];
	//echo $uid;
	if((isset($_SESSION["kiwa_companyid"])&&$_SESSION["kiwa_companyid"]=="000001") || (isset($_SESSION["kiwa_companyid"])&&$_SESSION["kiwa_companyid"]==$uid) || (isset($_SESSION["kiwa_userid"])&&$_SESSION["kiwa_userid"]==$uid)){
		
		$sql="update dtb_jobs set del_flg=1 where job_id='$id'";
		
		//存日志表
		dolog($uid, "", "", $id, "", "delete");
		
		if($db->Execute($sql)){
			echo '<script>
						window.location="'.APP_URL.'job/";
						alert("删除成功");';
			echo '</script>';
		
		}else{
			echo '<script>
						window.location="'.APP_URL.'job/";
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