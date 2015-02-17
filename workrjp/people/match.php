<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	
	$id = explode("_",$id);
	
	if(strlen($id[1]) <= 12){
		//注册个根据id查找name的方法
		$smarty->register_function("getname","getName");
		if($id[0] == "company"){
			$company = getCompany($id[1]);
			if(!isset($_SESSION["company_num"]) || $_SESSION["company_num"] != $company["company_id"]){
				//访问空间数
				add_see_page($id[1],"company",$company["see_page"]);
				$_SESSION["company_num"]=$company["company_id"];
			}
			//企业信息
			if($company){
				//得到高级设置
				$sql = "select * from dtb_people_page where del_flg = 0 and user_id='$id[1]'";
				$company_page = $db->QueryRow($sql);
				$smarty->assign('company_page', $company_page);
				
				$smarty->assign('company', $company);
				//好友
				
				$sqlall = "select * from dtb_favorite_list where user_id = '$company[company_id]' and favorite_id != '$company[company_id]' and (flag='user' or flag='company') and del_flg = 0 order by create_date desc";
				$friends = $db->QueryArray($sqlall);
				$smarty->assign('friend', $friends);
				
				$jobs = getJobs("company_id = '$_SESSION[kiwa_companyid]'",0,12);
				
				
				if(isset($_REQUEST["job_service"])&&$_REQUEST["job_service"]!=""){
					$job_id = $_REQUEST["job_service"];
				}else{
					$job_id = $jobs[0]["job_id"];
				}
				$smarty->assign('job_id', $job_id);
	
				$smarty->assign('jobs', $jobs);
				#######################################################
				#  匹配数据
				#######################################################
				
				$jobp = getJobs("job_id = '$job_id'",0,1);
				
				# 职种，业种
				if($jobp[0]["kindsID"]!=""){
					$kid = $jobp[0]["kindsID"];
				
					$kindsID = "kindsID = '$kid'";
				}else{
					$kindsID = "";
				}
				if($jobp[0]["typesID"]!=""){
					$tid = explode(",", $jobp[0]["typesID"]);
					$typesID = "and (";
					foreach ($tid as $v){
						$typesID .= "typesID like '%$v%' or ";
					}
					$typesID = rtrim($typesID,"or ");
					$typesID .= ")";
				}else{
					$typesID = "";
				}
				
				$sql_jobp_u = "select * from dtb_user where $kindsID $typesID and del_flg=0 order by rand() limit 0 , 5";
				$job_array_u = $db->QueryArray($sql_jobp_u);
				
				if(count($job_array_u)==0&&count($job_array_c)==0){
					$sql_jobp_u = "select * from dtb_user where $kindsID and del_flg=0 order by rand() limit 0 , 5";
					$job_array_u = $db->QueryArray($sql_jobp_u);
				}
				$smarty->assign('job_array_u', $job_array_u);
				
				
				#######################################################
				#  匹配数据没有，推荐热门数据
				#######################################################
				
				$skills = explode(",", $jobp[0]["level"]);
				
				if(@$skills[0]!=""){
					$sql_user = "select * from dtb_user where skill like '%$skills[0]%' and del_flg=0 order by rand() limit 0 , 10";
					$user_1 = $db->QueryArray($sql_user);
				
					$smarty->assign('skill_1', $skills[0]);
					$smarty->assign('user_1', $user_1);
				}
				if(@$skills[1]!=""){
					$sql_job = "select * from dtb_user where skill like '%$skills[1]%' and del_flg=0 order by rand() limit 0 , 10";
					$user_2 = $db->QueryArray($sql_job);
				
					$smarty->assign('skill_2', $skills[1]);
					$smarty->assign('user_2', $user_2);
				}
				if(@$skills[2]!=""){
					$sql_job = "select * from dtb_user where skill like '%$skills[2]%' and del_flg=0 order by rand() limit 0 , 10";
					$user_3 = $db->QueryArray($sql_job);
				
					$smarty->assign('skill_3', $skills[2]);
					$smarty->assign('user_3', $user_3);
				}
				if(empty($jobs)){
					$sql_job = "select * from dtb_user where user_photo_url !='' and  del_flg=0 and info_flg=1 order by create_date  desc limit 0 , 14";
					$tj_user = $db->QueryArray($sql_job);
					$smarty->assign('tj_user', $tj_user);
					//$job = array_merge($job,$job_4);
				}
				
				$sql_yuming = "select yuming from dtb_2domain where user_id = '$id[1]' and del_flg = 0";
				$yuming = $db->QueryItem($sql_yuming);
				$smarty->assign('yuming', $yuming);
				//区分company,user
				$smarty->assign('flag', $id[0]);
				//固定引入参数
				$smarty->assign('now', date("Y-m-d"));
				$smarty->assign('BASE_URL', APP_URL);
				$smarty->assign('THEME', THEME);
				if(isMobile()){
					$smarty->display('mobile/People/company_match.html');
				}else{
					$smarty->display('People/company_match.html');
				}
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
				$smarty->assign('users', $user);
				//工作，商机区分
				if($id[2]=="job"){
					#######################################################
					#  匹配数据
					#######################################################
					$skills = explode(",", $user["skill"]);
					if(@$skills[0]!=""){
						$sql_job = "select * from dtb_jobs where level like '%$skills[0]%' and del_flg=0 order by rand() limit 0 , 15";
						$job_1 = $db->QueryArray($sql_job);
						
						$smarty->assign('skill_1', $skills[0]);
						$smarty->assign('job_1', $job_1);
					}
					if(@$skills[1]!=""){
						$sql_job = "select * from dtb_jobs where level like '%$skills[1]%' and del_flg=0 order by rand() limit 0 , 15";
						$job_2 = $db->QueryArray($sql_job);
						
						$smarty->assign('skill_2', $skills[1]);
						$smarty->assign('job_2', $job_2);
					}
					if(@$skills[2]!=""){
						$sql_job = "select * from dtb_jobs where level like '%$skills[2]%' and del_flg=0 order by rand() limit 0 , 15";
						$job_3 = $db->QueryArray($sql_job);
						
						$smarty->assign('skill_3', $skills[2]);
						$smarty->assign('job_3', $job_3);
					}
					#######################################################
					#  匹配数据
					#######################################################
				}
				
				if(empty($skills[0])){
						$sql_job = "select * from dtb_jobs where del_flg=0 order by answer_date desc limit 0 , 14";
						$job_tj = $db->QueryArray($sql_job);
						$smarty->assign('job_tj',$job_tj);
				}
				
				if(!isset($_SESSION["user_num"]) || $_SESSION["user_num"] != $user["user_id"]){
					//访问空间数
					add_see_page($id[1],"user",$user["see_page"]);
					$_SESSION["user_num"]=$user["user_id"];
				}
				
				//新加  加好友
				$sqlall = "select * from dtb_favorite_list where user_id = '$user[user_id]' and favorite_id != '$user[user_id]' and (flag='user' or flag='company') and del_flg = 0 order by create_date desc";
				$friends = $db->QueryArray($sqlall);
				$smarty->assign('friend', $friends);
				$sql_fn="select count(*) from dtb_favorite_list where user_id = '$user[user_id]' and (flag='user' or flag='company') and del_flg = 0";
				$um = $db->QueryItem($sql_fn);
				$smarty->assign('um', $um);
				
				//区分company,user
				$smarty->assign('flag', $id[0]);
				//注册函数
				$smarty->register_function('getname','getName');
				//固定引入参数
				$smarty->assign('BASE_URL', APP_URL);
				$smarty->assign('THEME', THEME);
				
				if(isMobile()){
					$smarty->display('mobile/People/match.html');
				}else{
					$smarty->display('People/match.html');
				}
				
				
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
?>