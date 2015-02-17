<?php
	// config
	if(!file_exists('_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '_config/config.php';
	require_once '_includes/functions.php';
	
	
	if(@$_SESSION['kiwa_companyid']=="" && @$_SESSION['kiwa_userid']==""){
		if(!empty($_COOKIE["8n8nuser"])&&!empty($_COOKIE["8n8npw"])){
			$cookie_user = $_COOKIE["8n8nuser"];
			$cookie_pw = $_COOKIE["8n8npw"];
			$sql_u="select user_id,name01,name02 from dtb_user where email = '$cookie_user' and password = '$cookie_pw'";
			$sql_c="select company_id,company_name from dtb_companyuser where company_email = '$cookie_user' and password = '$cookie_pw'";
			$row_u = $db->QueryRow($sql_u);
			$row_c = $db->QueryRow($sql_c);
	
			$today = date("Y-m-d");
			if($row_u != ""){
				$_SESSION['kiwa_userid'] = $row_u['user_id'];
				$_SESSION['kiwa_username'] = $row_u['name01']." ".$row_u['name02'];
					
				$sql_u="update dtb_user set login_date='$today' where user_id='$_SESSION[kiwa_userid]'";
				$db->Execute($sql_u);
			}elseif($row_c != ""){
				$_SESSION['kiwa_companyid'] = $row_c['company_id'];
				$_SESSION['kiwa_companyname'] = $row_c['company_name'];
					
				$sql_c="update dtb_companyuser set login_date='$today' where company_id='$_SESSION[kiwa_companyid]'";
				$db->Execute($sql_c);
			}
		}
	}
	
	switch($page){
		//商品展示，个人相册
		case 'photo';
			require_once  'c_photo.php';
			exit;
			break;
		case 'photoadd';
			require_once  'c_photoadd.php';
			exit;
			break;
		case 'photoshow';
			require_once  'c_photoshow.php';
			exit;
			break;
		case 'p_adddo';
			require_once  'C/people_pC.php';
			exit;
			break;
		case 'photo_del';
			require_once  'C/photo_delete.php';
			exit;
			break;
		case 'msg';
			require_once  'c_msg.php';
			exit;
			break;
		case 'msgdo';
			require_once  'C/people_msgC.php';
			exit;
			break;
		case 'msg_del';
			require_once  'C/msg_delete.php';
			exit;
			break;
		case 'clive';
			require_once  'c_live.php';
			exit;
			break;
		case 'cjob';
			require_once  'c_job.php';
			exit;
			break;
		case 'company_pr';
			require_once  'c_pr.php';
			exit;
			break;
		case 'page_set';
			require_once  'c_pageset.php';
			exit;
			break;
	}
	
	$page = str_replace(".8n8n.co.jp","",$_SERVER['HTTP_HOST']);
	//跳转
	$sql="select * from dtb_2domain where yuming='$page' and del_flg = 0";
	$url = $db->QueryRow($sql);
	$smarty->assign('page', $_SERVER['HTTP_HOST']);
	if($url){
		$lives = getLives("user_id='$url[user_id]'",0, 3);
		$smarty->assign('lives', $lives);
		$jobs = getJobs("company_id='$url[user_id]'",0, 3);
		$smarty->assign('jobs', $jobs);
		$msgs = getMsg("people_id='$url[user_id]'",0,5);
		$smarty->assign('msgs', $msgs);
		$photo = getPhoto("user_id='$url[user_id]'",0,9);
		$smarty->assign('photo', $photo);
		if(strlen($url["user_id"]) <= 12){
			//注册个根据id查找name的方法
			$smarty->register_function("getname","getName");
			if($url["user_type"] == "company"){
				$company = getCompany($url["user_id"]);
				if(!isset($_SESSION["company_num"]) || $_SESSION["company_num"] != $company["company_id"]){
					//访问空间数
					add_see_page($url["user_id"],"company",$company["see_page"]);
				
					$_SESSION["company_num"]=$company["company_id"];
				}
				//企业信息
				if($company){
					//得到高级设置
					$sql = "select * from dtb_people_page where del_flg = 0 and user_id='$url[user_id]'";
					$company_page = $db->QueryRow($sql);
					$smarty->assign('company_page', $company_page);
					
					//首页轮转图片
					if($company_page["turn_pic1"]==""){
						$skills = explode(",", $company["skill"]);
						$skill = $skills[0];
							
						$sql_document = "select type_english,turn_pic_num from mtb_company_type where type_name='$skill'";
						$document = $db->QueryRow($sql_document);
						$smarty->assign('document', $document);
							
						//range 是将1到42 列成一个数组
						$numbers = range (1,$document["turn_pic_num"]);
						//shuffle 将数组顺序随即打乱
						shuffle ($numbers);
						//array_slice 取该数组中的某一段
						$suiji = array_slice($numbers,0,3);
						$smarty->assign('suiji',$suiji);
					}
					
					$smarty->assign('company', $company);
					
					$sql_yuming = "select yuming from dtb_2domain where user_id = '$url[user_id]' and del_flg = 0";
					$yuming = $db->QueryItem($sql_yuming);
					$smarty->assign('yuming', $yuming);
					
					//区分company,user
					$smarty->assign('flag', $url["user_type"]);
					//固定引入参数
					$smarty->assign('BASE_URL', APP_URL);
					$smarty->assign('THEME', THEME);
					
					//$lujing = 'qiye_1';
					$lujing = 'default';
					$smarty->assign('lujing', $lujing);
					$smarty->display("C/$lujing/index.html");
					
				}else{
					//没有找到
					$smarty->assign('h1', "企业信息");
					$smarty->assign('message', "非常抱歉，您指定的企业信息的登载已经结束。感谢您的关注！");
					$smarty->assign('BASE_URL', APP_URL);
					$smarty->assign('THEME', THEME);
					$smarty->display('People/done.html');
				}
			}
			if($url["user_type"] == "user"){
				$user = getUser($url[user_id]);
				if($user){
					if(!isset($_SESSION["user_num"]) || $_SESSION["user_num"] != $user["user_id"]){
						//访问空间数
						add_see_page($url[user_id],"user",$user["see_page"]);
						
						$_SESSION["user_num"]=$user["user_id"];
					}
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
					$smarty->assign('flag', $url["user_type"]);
					//注册函数
					$smarty->register_function('getname','getName');
					//固定引入参数
					$smarty->assign('BASE_URL', APP_URL);
					$smarty->assign('THEME', THEME);
					$smarty->display('C/default/peopleshow.html');
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
	}else{
		$smarty->assign('BASE_URL', APP_URL);
		$smarty->assign('THEME', THEME);
		$smarty->display('error.html');
	}
?>
