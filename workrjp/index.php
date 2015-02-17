<?php
	// config
	if(!file_exists('_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '_config/config.php';
	require_once '_includes/functions.php';
	require_once '_includes/mobile.php';
	
	if($_SERVER['HTTP_HOST']=="kiwa-g.net"||$_SERVER['HTTP_HOST']=="www.kiwa-g.net"){		
		header("Location:/kiwastaff/admin/login.php");
	}else{
		//跳转
		$sql="select url from dtb_2domain where yuming='$page' and del_flg = 0";
		$url = $db->QueryItem($sql);
		if($url!=""){
			header("Location:".$url);
		}
		if(@$_SESSION['kiwa_companyid']=="" && @$_SESSION['kiwa_userid']==""){
			if(!empty($_COOKIE["8n8nuser"])&&!empty($_COOKIE["8n8npw"])){
				$cookie_user = $_COOKIE["8n8nuser"];
				$cookie_pw = $_COOKIE["8n8npw"];
				$sql_u="select user_id,name01,name02 from dtb_user where email = '$cookie_user' and password = '$cookie_pw'";
				$sql_c="select company_id,company_name from dtb_companyuser where company_email = '$cookie_user' and password = '$cookie_pw'";
				$row_u = $db->QueryRow($sql_u);
				$row_c = $db->QueryRow($sql_c);
				
				$today = date("Y-m-d");
				$ip = getIP();
				if($row_u != ""){
					$_SESSION['kiwa_userid'] = $row_u['user_id'];
					$_SESSION['kiwa_username'] = $row_u['name01']." ".$row_u['name02'];
					
					$sql_u="update dtb_user set login_date='$today',ip='$ip' where user_id='$_SESSION[kiwa_userid]'";
					$db->Execute($sql_u);
				}elseif($row_c != ""){
					$_SESSION['kiwa_companyid'] = $row_c['company_id'];
					$_SESSION['kiwa_companyname'] = $row_c['company_name'];
					
					$sql_c="update dtb_companyuser set login_date='$today',ip='$ip' where company_id='$_SESSION[kiwa_companyid]'";
					$db->Execute($sql_c);
				}
			}
		}		
		$sql_l = "select * from dtb_lives where del_flg = 0 order by rand() limit 0 , 7";
		$lives = $db->QueryArray($sql_l);
		$smarty->assign('lives', $lives);
		$sql_b = "select * from dtb_bbs where del_flg = 0 order by rand() limit 0 , 7";
		$bbs = $db->QueryArray($sql_b);
		$smarty->assign('bbs', $bbs);
		
		$job1 = getJobs("",0, 20);
		$job2 = getJobs("",20, 20);
		
		$smarty->assign('job1', $job1);
		$smarty->assign('job2', $job2);
		
		//用户适用类型
		$clienusetype=$_GET["clienusetype"];
		if($clienusetype!=""){
			if($clienusetype == "computer"){
				$_SESSION['Clienusetype'] = $clienusetype;
			}elseif ($clienusetype == "mobile"){
				$_SESSION['Clienusetype'] = $clienusetype;
			}
		}
		//生活需求个数
		$sql_live="select count(*) from dtb_lives where del_flg = 0";
		$live_um = $db->QueryItem($sql_live);
		$smarty->assign('live_um', $live_um);
		//招贤纳才个数
		$sql_job="select count(*) from dtb_jobs where del_flg = 0";
		$job_um = $db->QueryItem($sql_job);
		$smarty->assign('job_um', $job_um);
		//人才个数
		$sql_user="select count(*) from dtb_user where del_flg = 0";
		$user_um = $db->QueryItem($sql_user);
		$smarty->assign('user_um', $user_um);
		//企业个数
		$sql_company="select count(*) from dtb_companyuser where del_flg = 0";
		$company_um = $db->QueryItem($sql_company);
		$smarty->assign('company_um', $company_um);
		
		//首页幻灯片随机
		$input = array("img_main_1_1"=>"1","img_main_1_2"=>"2","img_main_1_3"=>"3","img_main_1_4"=>"4","img_main_1_5"=>"5");
		$suiji = array_rand($input);
		$smarty->assign('suiji', $suiji);
		//banner随机
		$input = array("banner1.jpg"=>"1","banner2.jpg"=>"2","banner3.gif"=>"3");
		$banner = array_rand($input);
		$smarty->assign('banner', $banner);
		
		$smarty->assign('BASE_URL', APP_URL);
		$smarty->assign('THEME', THEME);
		
		if(isMobile()){
			$smarty->display('mobile/index.html');
		}else{
			$smarty->display('index.html');
		}
	}
?>