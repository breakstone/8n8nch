<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	require_once '../_includes/SubPages.php';
	
// 	//判断session,没session，cookie记住url，login好做处理
// 	if(!isset($_SESSION['kiwa_userid'])){
// 		//★★★将前url写入session用于login成功后的跳转★★★
// 		//$_SESSION['url'] = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
// 		setcookie("jobUrl","http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],time()+86400,"/");
// 		//没有session 跳到登陆
// 		//header('Location:'.APP_URL.'login/');
// 	}
	
	if(strlen($id) <= 12){
		$table="dtb_job_ls";
		$key="job_ls_id";
		$job = showAInfo($id,$table,$key);
		if($job){
			if(!isset($_SESSION["job_ls_num"]) || $_SESSION["job_ls_num"] != $job[$key]){
				//关注度修改
				$read_num = $job['read_num']+1;
				$sql_rn="update $table set read_num=$read_num where $key='$id'";
				$db->Execute($sql_rn);
				
				$_SESSION["job_ls_num"] = $job[$key];
			}
			//banner随机
			$input = array("banner1.jpg"=>"1","banner2.jpg"=>"2","banner3.gif"=>"3");
			$banner = array_rand($input);
			$smarty->assign('banner', $banner);
			
			if($job['user_type'] == "company"){
				$company = getCompany($job['user_id']);
				//公司信息
				$smarty->assign('company', $company);
			}
			if($job['user_type'] == "user"){
				$user = getUser($job['user_id']);
				//个人信息
				$smarty->assign('user', $user);
			}
			//工作信息
			$smarty->assign('job_ls', $job);
			$startime=$job["date_star"];
			$endtime=$job["date_end"];
			
// 			var_dump($job);
			$startday = date("Y-m-d",strtotime($startime));
			$endday = date("Y-m-d",strtotime($endtime));
			if ($startday == $endday){
					$smarty->assign('startday',$startday);
					
					$starthour = date("H:i",strtotime($startime));
					$endhour = date("H:i",strtotime($endtime));
					$smarty->assign('starthour',$starthour);
					$smarty->assign('endhour',$endhour);
			}else{
				$smarty->assign('startday',$startday);
				$smarty->assign('endday',$endday);
			}
			
			
			//分页处理
			$all_nums = getObjectNum("dtb_job_ls_answer","job_ls_id = '$id' and del_flg = 0 order by create_date");
			if(isset($_REQUEST["pid"])&&$_REQUEST["pid"]!=""){
				$pid = $_REQUEST["pid"];
			}else{
				$pid = 1;
			}
			//每页显示几条;//得到当前是第几页;
			$show_num=10;$pageCurrent = $pid;
			$subPages = new SubPages($show_num,$all_nums,$pageCurrent,10,APP_URL."job/lsshow/$id/?pid=");
			//-----------------分页处理完了----------------------
			//利用limit查询数据库---select * from table limit $start,$end
			$start = $subPages->getStart($show_num,$pageCurrent);//配合分页得到起始数
			$end = $subPages->getEnd($all_nums,$show_num,$pageCurrent);//配合分页得到结束数
			$sql = "select * from dtb_job_ls_answer where $key = '$id' and del_flg = 0 order by create_date limit $start , $end";
			$answers = $db->QueryArray($sql);
			$smarty->assign('subPages',$subPages->showPageStr());
			$smarty->assign('answers',$answers);
			
			$url1=$_SERVER['QUERY_STRING'];
			$url1=empty($url1)? '' : '?'.$_SERVER['QUERY_STRING'];
			$smarty->assign('url1',$url1);
			
			//第几楼算法
			$smarty->assign('lou',($pid-1)*$show_num);
			//注册个根据id查找name的方法
			$smarty->register_function("getname","getName");
			///////////////////////////////////////////////////
			//可能感兴趣的工作信息contents
			$where = "";
			$keyword = cleanInput('免费');
			$where= "job_title like '%$keyword%'";
			if(@$_REQUEST['kinds']!=""){//业种有选择
				
				if($where != "")$where .=" and ";
				$where .= "kindsID = '$_REQUEST[kinds]'";
			}
		
			if(isset($_REQUEST["jid"])&&$_REQUEST["jid"]!=""){
				$jid = $_REQUEST["jid"];
			}else{
				$jid = 1;
			}
			$start1 =$jid-2;
			if($start1 < 0)
			{
				$start1=0;
			}
			$job1 = getJobs($where,$start1,6);
			$smarty->assign('start1', $start1);
			if($job1=='')
			{
				$job1 = getJobs($where,0,6);
				$smarty->assign('start1', 0);
				
			}
			$smarty->assign('job1',$job1);
		
			$smarty->assign('where', $where);
			
			$sql_bbs= "select * from dtb_bbs where del_flg=0 order by rand() limit 0 , 5";
			$bbs = $db->QueryArray($sql_bbs);
			$smarty->assign('bbs', $bbs);
			
			//////////////////////////////////////////////////
			//固定引入参数
			$smarty->assign('BASE_URL', APP_URL);
			$smarty->assign('THEME', THEME);
			if(isMobile()){
				$smarty->display('mobile/Job/job_ls_show.html');
			}else{
				$smarty->display('Job/job_ls_show.html');
			}
			//$smarty->display('mobile/Job/job_ls_show.html');
			//$smarty->display('mobile/Job/jobshow.html');
		}else{
			//没有找到工作
			$smarty->assign('h1', "求人詳細");
			$smarty->assign('message', "申し訳ございません。ご指定の求人情報は掲載が終了しております");
			
			$smarty->assign('BASE_URL', APP_URL);
			$smarty->assign('THEME', THEME);
			if(isMobile()){
				$smarty->display('mobile/Job/done.html');
			}else{
				$smarty->display('Job/done.html');
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