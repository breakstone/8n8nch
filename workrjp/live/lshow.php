<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	require_once '../_includes/SubPages.php';
	
//	//判断session,没session，cookie记住url，login好做处理
//	if(!isset($_SESSION['kiwa_userid'])){
//		//★★★将前url写入session用于login成功后的跳转★★★
//		setcookie("jobUrl","http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],time()+86400,"/");
//		//没有session 跳到登陆
//		//header('Location:'.APP_URL.'login/');
//	}

	if(strlen($id) <= 12){
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
		$live = showLive($id);
		if($live){
			if(!isset($_SESSION["live_num"]) || $_SESSION["live_num"] != $live["live_id"]){
				//关注度修改
				$read_num = $live['read_num']+1;
				$sql_rn="update dtb_lives set read_num=$read_num where live_id='$id'";
				$db->Execute($sql_rn);
				
				$_SESSION["live_num"]=$live["live_id"];
			}
			
			if($live['user_type'] == "company"){//企业用户
				$company = getCompany($live['user_id']);
				//发需求者的信息
				$smarty->assign('company', $company);
			}
			if($live['user_type'] == "user"){//个人用户
				$user = getUser($live['user_id']);
				//发需求者的信息
				$smarty->assign('user', $user);
			}
			$live_photo = explode(",", $live["live_photo"]);
			$smarty->assign('live_photo', $live_photo);
			//生活服务
			$smarty->assign('live', $live);
			//生活服务回复
			
			//分页处理
			$all_nums = getObjectNum("dtb_lives_answer","live_id = '$id' and del_flg = 0 order by create_date");
			if(isset($_REQUEST["pid"])&&$_REQUEST["pid"]!=""){
				$pid = $_REQUEST["pid"];
			}else{
				$pid = 1;
			}
			//每页显示几条;//得到当前是第几页;
			$show_num=10;$pageCurrent = $pid;
			$subPages = new SubPages($show_num,$all_nums,$pageCurrent,10,APP_URL."live/show/$id/?pid=");
			//-----------------分页处理完了----------------------
			//利用limit查询数据库---select * from table limit $start,$end
			$start = $subPages->getStart($show_num,$pageCurrent);//配合分页得到起始数
			$end = $subPages->getEnd($all_nums,$show_num,$pageCurrent);//配合分页得到结束数
			$answers = getAnswers($id,$start,$end);
			$smarty->assign('subPages',$subPages->showPageStr());
			$smarty->assign('answers',$answers);
			
			//第几楼算法
			$smarty->assign('lou',($pid-1)*$show_num);
			//注册个根据id查找name的方法
			$smarty->register_function("getname","getName");
			//固定引入参数
			$smarty->assign('BASE_URL', APP_URL);
			$smarty->assign('THEME', THEME);
			if(isMobile()){
				$smarty->display('mobile/Live/liveshow.html');
			}else{
				$smarty->display('Live/liveshow.html');
			}
		}else{
			//没有找到工作
			$smarty->assign('h1', "需求詳細");
			$smarty->assign('message', "您访问的需求已经终止！");
			
			$smarty->assign('BASE_URL', APP_URL);
			$smarty->assign('THEME', THEME);
			if(isMobile()){
				$smarty->display('mobile/Live/done.html');
			}else{
				$smarty->display('Live/done.html');
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