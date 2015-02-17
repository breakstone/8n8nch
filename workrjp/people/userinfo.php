<?php
		if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	if((isset($_SESSION["kiwa_userid"])&&$_SESSION["kiwa_userid"]!="")){
				$user = getUser($_SESSION["kiwa_userid"]);
				if($user){
					if(isset($_POST["review"])&&$_POST["review"]==1){
						### review画面修改
						//创建假user
						$user = array(
						"name01" => $_POST['name01'],
						"name02" => $_POST['name02'],
						"birth" => $_POST['birth'],
						"sex" => $_POST['sex'],
						"email" => $_POST['email'],
						"per_status" => $_POST['per_status'],
						"zige" => $_POST['zige'],
						"mypr" => $_POST['pr'],
						"eki" => $_POST['eki'],
						"zip01" => $_POST['zip01'],"zip02" => $_POST['zip02'],
						"pref" => $_POST['add_pref'],
						"addr01" => $_POST['addr01'],"addr02" => $_POST['addr02'],
						"tel01" => $_POST['tel01'],"tel02" => $_POST['tel02'],"tel03" => $_POST['tel03'],
						"tel_flag"=> $_POST['tel_flag'],
						"last_education" => $_POST['last_education'],
						"zhuanye" => $_POST['zhuanye'],
						"job_experiencetimes" => $_POST['job_experiencetimes'],
						"job_nowstatus" => $_POST['job_nowstatus'],
						"job_experience" => $_POST['job_experience']
						);
					}else{
						### 直接进来
						//得到个人简历
						$user = getUser($_SESSION['kiwa_userid']);
					}
					if($id=="imgerror"){
						$smarty->assign('error', "上传图片格式不正确");
					}
					//得到最终学历
					$eduction = getEduction();
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
					//get得到生活服务内容
					$life_service = getLifeservice();
					$smarty->assign('life_service', $life_service);
					
					//工作标签
					$sql_jb = "select * from mtb_job_biao";
					$biao = $db->QueryArray($sql_jb);
					$smarty->assign('biao', $biao);
					
					//解决缓存，图片不更新的方法
					$catch_img = rand(0,100);
					$smarty->assign("catch_img",$catch_img);
					
					if($user["area_cd"]!=""){
						$pref = getPref($user["area_cd"]);
						$smarty->assign('prefSearch', $pref);
					}
					//根据业种得出职种
					if($user["kindsID"]!=""){
						$types = getTypes($user["kindsID"]);
						$smarty->assign('types', $types);
					}
					if($user["typesID"]!=""){
						$stypes = explode(",", $user["typesID"]);
						$smarty->assign('select_types', $stypes);
					}
					if($user["skill"]!=""){
						$skill = explode(",", $user["skill"]);
						$smarty->assign('skill', $skill);
					}
					if($user["line_num"]!=""){
						$line_num = explode(",", $user["line_num"]);
						$smarty->assign('ensn', $line_num);
					}
					
					if($user["station_cd"]!=""){
						$station_cd = explode(",", $user["station_cd"]);
						$smarty->assign('eki', $station_cd);
					}
					
					
					$smarty->assign('user', $user);
					$smarty->assign('eduction', $eduction);
					$smarty->assign('companyfrom', $companyfrom);
					$smarty->assign('areas', $areas);
					$smarty->assign('money', $money);
					$smarty->assign('employ', $employ);
					$smarty->assign('hope', $hope);
					
					if($user["want_day"]!=""){
						$want_day = explode(",", $user["want_day"]);
						$smarty->assign('want_day', $want_day);
					}
					
					$smarty->assign('USERNAME', $_SESSION['kiwa_username']);
					$smarty->assign('USERID', $_SESSION['kiwa_userid']);
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
					$smarty->assign('flag', $_SESSION["kiwa_userid"]);
					//注册函数
					$smarty->register_function('getname','getName');
					//固定引入参数
					$smarty->assign('BASE_URL', APP_URL);
					$smarty->assign('THEME', THEME);
					$smarty->display('People/personal_info.html');
				}else{
					//没有找到
					$smarty->assign('h1', "人才信息");
					$smarty->assign('message', "非常抱歉，您指定的人才信息的登载已经结束。感谢您的关注！");
		
					$smarty->assign('BASE_URL', APP_URL);
					$smarty->assign('THEME', THEME);
					$smarty->display('People/done.html');
				}
	}elseif((isset($_SESSION["kiwa_companyid"])&&$_SESSION["kiwa_companyid"]!="")){
		$company = getCompany($_SESSION["kiwa_companyid"]);
		//企业信息
		if($company){
			//得到高级设置
			$sql = "select * from dtb_people_page where del_flg = 0 and user_id='$id[1]'";
			$company_page = $db->QueryRow($sql);
			$smarty->assign('company_page', $company_page);
			$smarty->assign('company', $company);
			if($id=="imgerror"){
				$smarty->assign('error', "上传图片格式不正确");
			}
			//得到企业简历
			$company = getCompany($_SESSION['kiwa_companyid']);
			$smarty->assign('company', $company);
			//好友
			
			$sqlall = "select * from dtb_favorite_list where user_id = '$company[company_id]' and favorite_id != '$company[company_id]' and (flag='user' or flag='company') and del_flg = 0 order by create_date desc";
			$friends = $db->QueryArray($sqlall);
			$smarty->assign('friend', $friends);
			//get得到业种数据
			$companyfrom = getCompanyfrom();
			$smarty->assign('companyfrom', $companyfrom);
			//企业形态处理
			$company_form = explode("_",$company["company_form"]);
			
			//企业分类
			$sql_comp = "select * from mtb_company_type";
			$qi = $db->QueryArray($sql_comp);
			$smarty->assign('qi', $qi);
			
			$smarty->assign('company_form', $company_form);
			//判断企业宣传照片
			if($company["company_photo_url"]!=""){
				if(file_exists("../".$company["company_photo_url"])){
					$smarty->assign('img_status', 1);
				}else{
					$smarty->assign('img_status', "");
				}
			}
			//解决缓存，图片不更新的方法
			$catch_img = rand(0,100);
			$smarty->assign("catch_img",$catch_img);
			
			//get得到地域数据
			$areas = getPref();
			$smarty->assign('areas', $areas);
			if($company["area_cd"]!=""){
				$pref = getPref($company["area_cd"]);
				$smarty->assign('prefSearch', $pref);
			}
			//根据业种得出职种
			if($company["kindsID"]!=""){
				$types = getTypes($company["kindsID"]);
				$smarty->assign('types', $types);
			}
			if($company["typesID"]!=""){
				$stypes = explode(",", $company["typesID"]);
				$smarty->assign('select_types', $stypes);
			}
			//get得到生活服务内容
			$life_service = getLifeservice();
			$smarty->assign('life_service', $life_service);
			if($company["skill"]!=""){
				$skill = explode(",", $company["skill"]);
				$smarty->assign('skill', $skill);
			}
			
			if($company["line_num"]!=""){
				$line_num = explode(",", $company["line_num"]);
				$smarty->assign('ensn', $line_num);
			}
			
			if($company["station_cd"]!=""){
				$station_cd = explode(",", $company["station_cd"]);
				$smarty->assign('eki', $station_cd);
			}
			//区分company,user
			$smarty->assign('flag', $id[0]);
			//固定引入参数
			$smarty->register_function("getname","getName");
			$smarty->assign('BASE_URL', APP_URL);
			$smarty->assign('THEME', THEME);
			$smarty->display('People/company_info.html');
		}else{
			//没有找到
			$smarty->assign('h1', "企业信息");
			$smarty->assign('message', "非常抱歉，您指定的企业信息的登载已经结束。感谢您的关注！");
			$smarty->assign('BASE_URL', APP_URL);
			$smarty->assign('THEME', THEME);
			$smarty->display('People/done.html');
		}
	}else{
		//id不合法跳入error
		//固定引入参数
		$smarty->assign('BASE_URL', APP_URL);
		$smarty->assign('THEME', THEME);
		$smarty->display('error.html');
	}

	
	//固定引入参数
// 	$smarty->assign('BASE_URL', APP_URL);
// 	$smarty->assign('THEME', THEME);
// 	$smarty->display('Requirement/live_old.html');
?>