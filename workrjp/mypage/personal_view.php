<?php
if($_POST["name01"]!=""&&$_POST["name02"]!=""&&$_POST["birthYear"]!=""&&$_POST["sex"]!=""){
		//个人简历信息
		$smarty->assign('name01', $_POST["name01"]);
		$smarty->assign('name02', $_POST["name02"]);
		$smarty->assign('birthYear', $_POST["birthYear"]);
		$smarty->assign('birthMonth', $_POST["birthMonth"]);
		$smarty->assign('birthDay', $_POST["birthDay"]);
		$smarty->assign('sex', $_POST["sex"]);
		$smarty->assign('email', $_POST["email"]);
		$smarty->assign('per_status', $_POST["per_status"]);
		$smarty->assign('zige', $_POST["zige"]);
		$smarty->assign('pr', $_POST["pr"]);
		
		
		$smarty->assign('eki', $_POST["eki"]);
		$smarty->assign('zip01', $_POST["zip01"]);
		$smarty->assign('zip02', $_POST["zip02"]);
		$smarty->assign('add_pref', $_POST["add_pref"]);
		$smarty->assign('addr01', $_POST["addr01"]);
		$smarty->assign('addr02', $_POST["addr02"]);
		$smarty->assign('tel01', $_POST["tel01"]);
		$smarty->assign('tel02', $_POST["tel02"]);
		$smarty->assign('tel03', $_POST["tel03"]);
		$smarty->assign('tel_flag', $_POST["tel_flag"]);
		$smarty->assign('last_education', $_POST["last_education"]);//最终学历
		$smarty->assign('zhuanye', $_POST["zhuanye"]);//专业
		$smarty->assign('job_experiencetimes', $_POST["job_experiencetimes"]);//就职年限
		if(isset($_POST["job_nowstatus"])){
			$smarty->assign('job_nowstatus', $_POST["job_nowstatus"]);//现在就职状况
		}
		$smarty->assign('job_experience', $_POST["job_experience"]);//转职次数
		
		
		//固定引入参数
		//mypage页面信息查询
		$smarty->assign('THEME', THEME);
		$smarty->assign('BASE_URL', APP_URL);
		
		$smarty->assign('USERNAME', $_SESSION['kiwa_username']);
		$smarty->assign('USERID', $_SESSION['kiwa_userid']);
		$smarty->register_function('getname','getName');
		$smarty->display('Mypage/personal_review.html');
	}else{
		//没有参数跳回注册页面
		header('Location:'.APP_URL.'regist/');
	}
?>