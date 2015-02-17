<?php
require_once '../_includes/mobile.php';
function smarty_function_strstr($params, &$smarty)
{
	$str = preg_replace('/style/', $style,$params["haystack"]);
	if(isMobile()){
		$str=stripslashes($params["haystack"]);
		//$str=strip_tags($str);
		$str = preg_replace("/style=.+?['|\"]/i",'',$str);
		$str = preg_replace("/class=.+?['|\"]/i",'',$str);//去除样式  
		$str = preg_replace("/width=.+?['|\"]/i",'',$str);//去除样式  
	
		//$str = preg_replace('/<([a-z]+)\s+[^>]*>/is', '<$1>', $str);
		//$str = preg_replace("/style=.+?['|"]/i",'',$str);//去除样式
			$style='<img style="max-width: 280px;"';
		$str = preg_replace('/<img/', $style,$str);
	}else{
			$style='<img style="max-width: 680px;"';
			$str = preg_replace('/<img/', $style,$str);
	}


		$smarty->assign('content',$str);
	//}
		
}
?>