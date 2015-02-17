<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	require_once '../_includes/SubPages.php';
	$bbs = getBbs("bbs_id='$id'",0, 1);
	if($bbs){
		if(!isset($_SESSION["bbs_num"]) || $_SESSION["bbs_num"] != $bbs[0]["bbs_id"]){
			$read_num = $bbs[0]["read_num"]+1;
			$sql="update dtb_bbs set read_num='$read_num' where bbs_id='$id'";
			$db->Execute($sql);
			
			$_SESSION["bbs_num"]=$bbs[0]["bbs_id"];
		}
		
		$smarty->assign('bbs', $bbs[0]);
		
		$bbs_photo = explode(",", $bbs[0]["bbs_photo"]);
		$smarty->assign('bbs_photo', $bbs_photo);
		if($bbs[0]["user_type"] == "company"){
			$company = getCompany($bbs[0]["user_id"]);
			$smarty->assign('company', $company);
		}
		if($bbs[0]["user_type"] == "user"){
			$user = getUser($bbs[0]["user_id"]);
			$smarty->assign('user', $user);
		}
		
		//banner随机
		$input = array("banner1.jpg"=>"1","banner2.jpg"=>"2","banner3.gif"=>"3");
		$banner = array_rand($input);
		$smarty->assign('banner', $banner);
		
		//分页处理
		$all_nums = getObjectNum("dtb_bbs_answer","bbs_id = '$id' and del_flg = 0 order by create_date");
		if(isset($_REQUEST["pid"])&&$_REQUEST["pid"]!=""){
			$pid = $_REQUEST["pid"];
		}else{
			$pid = 1;
		}
		//每页显示几条;//得到当前是第几页;
		$show_num=10;$pageCurrent = $pid;
		$subPages = new SubPages($show_num,$all_nums,$pageCurrent,10,APP_URL."bbs/show/$id/?pid=");
		//-----------------分页处理完了----------------------
		//利用limit查询数据库---select * from table limit $start,$end
		$start = $subPages->getStart($show_num,$pageCurrent);//配合分页得到起始数
		$end = $subPages->getEnd($all_nums,$show_num,$pageCurrent);//配合分页得到结束数
		$answers = getAnswers($id,$start,$end);
		
		$sql_answer = "select * from dtb_bbs_answer where bbs_id='$id' and del_flg = 0 limit $start , $end";
		$answer = $db->QueryArray($sql_answer);
		$smarty->assign('answers', $answer);
		
		$smarty->assign('subPages',$subPages->showPageStr());
		
		//第几楼算法
		$smarty->assign('lou',($pid-1)*$show_num);
		
		$url1=$_SERVER['QUERY_STRING'];
		$url1=empty($url1)? '' : '?'.$_SERVER['QUERY_STRING'];
		$smarty->assign('url1',$url1);
		
		
		///////////////////////////////////////////////////
		//可能感兴趣的话题
		$where = "";
		if(isset($_REQUEST["type"])&&$_REQUEST["type"]!=""){
			if($_REQUEST["type"]!="闲聊"){
				$where .= "bbs_type='$_REQUEST[type]'";
				$smarty->assign('fenlei',$_REQUEST["type"]);
		}else{
			$where .= "bbs_type is null";
			$smarty->assign('fenlei',"闲聊");
		}
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
		$bbs1 = getBbs($where,$start1,6);
		$smarty->assign('start1', $start1);
		if($bbs1=='')
		{
			$bbs1 = getBbs($where,0,6);
			$smarty->assign('start1', 0);
			
		}
		$smarty->assign('bbs1',$bbs1);
		
		$smarty->assign('where', $where.'///'.count($bbs1));
		//可能感兴趣的工作信息contents
		$sql_job= "select * from dtb_jobs where del_flg=0 order by rand() limit 0 , 5";
		$job1 = $db->QueryArray($sql_job);
			$smarty->assign('job1',$job1);
			
		//////////////////////////////////////////////////
		//为了分页
			$smarty->assign('all',$all_nums);
		//注册函数
		$smarty->register_function('getname','getName');
		//固定引入参数
		$smarty->assign('BASE_URL', APP_URL);
		$smarty->assign('THEME', THEME);
		if(isMobile()){
			$smarty->display('mobile/Bbs/show.html');
		}else{
			$smarty->display('Bbs/show.html');
		}
		//$smarty->display('mobile/Bbs/show.html');
	}else{
		//固定引入参数
		$smarty->assign('BASE_URL', APP_URL);
		$smarty->assign('THEME', THEME);
		$smarty->display('error.html');
	}
?>