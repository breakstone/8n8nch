<?php
	if($_POST["job_title"]!=""&&$_POST["contents"]!=""&&$_POST["areaid"]!=""&&$_POST["pref"]!=""&&$_POST["kinds"]!=""&&$_POST["types"]!=""&&$_POST["moneyid"]!=""&&$_POST["employid"]!=""&&$_POST["hopeid"]!=""){
		//求职信息
		$smarty->assign('job_title', $_POST["job_title"]);
		$contents = str_replace("\r\n","<br>",$_POST["contents"]);
		$smarty->assign('contents', $contents);
		$requirements = str_replace("\r\n","<br>",$_POST["requirements"]);
		$smarty->assign('requirements', $requirements);
		
		$smarty->assign('lianxi', $_POST["lianxi"]);
		$smarty->assign('shoufei', $_POST["shoufei"]);
		$smarty->assign('numbers', $_POST["numbers"]);
// 		$smarty->assign('over_date',$_POST["over_date"]);
		
		$smarty->assign('areaid', $_POST["areaid"]);
		$smarty->assign('pref', $_POST["pref"]);
		
		if(isset($_POST["ensn"])&&$_POST["ensn"]!=""){
			$smarty->assign('ensn', $_POST["ensn"]);
		}
		if(isset($_POST["eki"])&&$_POST["eki"]!=""){
			$smarty->assign('eki', $_POST["eki"]);
		}
		$smarty->assign('zzs', $_POST["zz"]);
		
		$smarty->assign('w_d', $_POST["w_d"]);
		
		$smarty->assign('kinds', $_POST["kinds"]);
		$smarty->assign('types', $_POST["types"]);
		$smarty->assign('moneyid', $_POST["moneyid"]);
		$smarty->assign('employid', $_POST["employid"]);
		$smarty->assign('hopeid', $_POST["hopeid"]);
		if(isset($_POST["conditions"])&&$_POST["conditions"]!=""){
			$smarty->assign('conditions', $_POST["conditions"]);
		}
		//固定引入参数
		//mypage页面信息查询
		$smarty->assign('THEME', THEME);
		$smarty->assign('BASE_URL', APP_URL);
		$smarty->register_function('getname','getName');
		if(isMobile()){
			$smarty->display('mobile/Requirement/work_review.html');
		}else{
			$smarty->display('Requirement/work_review.html');
		}
		
	}else{
		//没有参数跳回注册页面
		header('Location:'.APP_URL.'regist/');
	}
	
?>