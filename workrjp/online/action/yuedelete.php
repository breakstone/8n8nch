<?php
// config
if(!file_exists('../../_config/config.php'))
{
	die('[index.php] _config/config.php not found');
}
require_once '../../_config/config.php';
require_once '../../_includes/functions.php';

if(@$_SESSION['kiwa_companyid']=="141113009425"){
	$id = $_GET["yueid"];
	$sql = "update dtb_kiwayue set del_flg = 1 where Id = $id";
	if($db->Execute($sql)){
		header('Location:'.APP_URL.'online/login/');
	}else{
		echo "删除失败，请找管理员！";
		exit;
	}
}else{
	echo "你没有资格！";
	exit;
}

?>