<?php
	// config
	if(!file_exists('./../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	
	require_once './../_config/config.php';
	require_once './../_includes/functions.php';
	if((isset($_SESSION["kiwa_userid"])&&$_SESSION["kiwa_userid"]!="")||(isset($_SESSION["kiwa_companyid"])&&$_SESSION["kiwa_companyid"]!="")){
		$bbs_id=cleanInput($_POST["id"]);
		
		$now = date("Y-m-d H:i:s");
	
		$title = cleanInput($_POST["title"]);
		$title = $db->real_escape_string($title);
		$content = $db->real_escape_string($_POST["content"]);
		$sql="update dtb_bbs set bbs_title='$title',bbs_content='$content' where bbs_id='$bbs_id'" ;
		//echo $sql;
		if($db->Execute($sql)){
			echo '<script>
												alert("修改成功");
												';
			echo '</script>';
			header('Location:'.APP_URL.'bbs/show/'.$bbs_id);
		}else{
			echo '<script>
												alert("修改失败");
												';
			echo '</script>';
		}
		
	
		
	}else{
		//固定引入参数
		$smarty->assign('BASE_URL', APP_URL);
		$smarty->assign('THEME', THEME);
		$smarty->display('error.html');
	}

?>