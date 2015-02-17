<?php
// config
if(!file_exists('../_config/config.php'))
{
	die('[index.php] _config/config.php not found');
}
require_once '../_config/config.php';


$smarty->assign('BASE_URL', APP_URL);
$smarty->assign('THEME', THEME);
$smarty->display('Netshow/mobile.html');
?>