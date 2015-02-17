<?php
		if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	if((isset($_SESSION["kiwa_userid"])&&$_SESSION["kiwa_userid"]!="")||(isset($_SESSION["kiwa_companyid"])&&$_SESSION["kiwa_companyid"]!="")){
		$id = explode("_",$id);
		//get得到业种数据
		$companyfrom = getCompanyfrom();
		//get得到地域数据
		$areas = getPref();
		//get給付
		$money = getMoney();
		//get雇用形態
		$employ = getEmploy_method();
		//get就业希望期间
		$hope = getHope_date();
		//get各种条件
		$condition = getCondition();
		
		$smarty->assign('companyfrom', $companyfrom);
		$smarty->assign('areas', $areas);
		$smarty->assign('money', $money);
		$smarty->assign('employ', $employ);
		$smarty->assign('hope', $hope);
		$smarty->assign('condition', $condition);
		
		if(strlen($id[1]) <= 12&&($id[1]==$_SESSION["kiwa_userid"]||$id[1]==$_SESSION["kiwa_companyid"])){
			//注册个根据id查找name的方法
			$smarty->register_function("getname","getName");
			if($id[0] == "company"){
				$company = getCompany($id[1]);
				//企业信息
				if($company){
					//得到高级设置
					$sql = "select * from dtb_people_page where del_flg = 0 and user_id='$id[1]'";
					$company_page = $db->QueryRow($sql);
					$smarty->assign('company_page', $company_page);
		
					$smarty->assign('company', $company);
					//区分company,user
					$smarty->assign('flag', $id[0]);
					//固定引入参数
					$smarty->assign('BASE_URL', APP_URL);
					$smarty->assign('THEME', THEME);
					$smarty->display('people/company_sendwork.html');
				}else{
					//没有找到
					$smarty->assign('h1', "企业信息");
					$smarty->assign('message', "非常抱歉，您指定的企业信息的登载已经结束。感谢您的关注！");
					$smarty->assign('BASE_URL', APP_URL);
					$smarty->assign('THEME', THEME);
					$smarty->display('People/done.html');
				}
			}
			if($id[0] == "user"){
				$user = getUser($id[1]);
				if($user){
					//好友
					$sqlall = "select * from dtb_favorite_list where user_id = '$user[user_id]' and favorite_id != '$user[user_id]' and (flag='user' or flag='company') and del_flg = 0 order by create_date desc";
					$friends = $db->QueryArray($sqlall);
					$smarty->assign('friend', $friends);
					//人信息
					$smarty->assign('users', $user);
					if($user['sex']==1){
						$usersex="男性";
					}else{
						$usersex="女性";
					}
					$smarty->assign('usersex', $usersex);
					//年齢計算
					$today = date('Ymd');
					$birthday=$user['birth'];
					$birthday = date('Ymd',strtotime($birthday));
					$age=floor(($today-$birthday)/10000);
					$smarty->assign('age', $age);
					//区分company,user
					$smarty->assign('flag', $id[0]);
					//注册函数
					$smarty->register_function('getname','getName');
					//固定引入参数
					$smarty->assign('BASE_URL', APP_URL);
					$smarty->assign('THEME', THEME);
					$smarty->display('People/sendwork.html');
				}else{
					//没有找到
					$smarty->assign('h1', "人才信息");
					$smarty->assign('message', "非常抱歉，您指定的人才信息的登载已经结束。感谢您的关注！");
		
					$smarty->assign('BASE_URL', APP_URL);
					$smarty->assign('THEME', THEME);
					$smarty->display('People/done.html');
				}
			}
		}else{
			//id不合法跳入error
			//固定引入参数
			$smarty->assign('BASE_URL', APP_URL);
			$smarty->assign('THEME', THEME);
			$smarty->display('error.html');
		}
	}else {
		$smarty->assign('BASE_URL', APP_URL);
		$smarty->assign('THEME', THEME);
		$smarty->display('error.html');
	}
?>