<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	require_once '../_includes/SubPages.php';
	
	$today = date("Y-m-d");
	$hour = date("H");
	$smarty->assign('hour',$hour);
	$yestoday = date("Y-m-d",strtotime("-1 day"));
	$bfyestoday = date("Y-m-d",strtotime("-2 day"));
	$smarty->assign('today',$today);
	$smarty->assign('yestoday',$yestoday);
	$smarty->assign('bfyestoday',$bfyestoday);
	
	$id = explode("_",$id);
	$lives = getLives("user_id='$id[1]'",0, 3);
	$smarty->assign('lives', $lives);
	$jobs = getJobs("company_id='$id[1]'",0, 3);
	$smarty->assign('jobs', $jobs);
	$bbs = getBbs("user_id='$id[1]'",0, 3);
	$smarty->assign('bbs', $bbs);
	
	$msgs = getMsg("people_id='$id[1]'",0,5);
	$smarty->assign('msgs', $msgs);
	$photo = getPhoto("user_id='$id[1]'",0,12);
	$smarty->assign('photo', $photo);
	
	$url1=$_SERVER['QUERY_STRING'];
	$url1=empty($url1)? '' :$_SERVER['QUERY_STRING'];
	$smarty->assign('url1',$url1);
	
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
				//首页轮转图片
				$skills = explode(",", $company["skill"]);
				if($company_page["turn_pic1"]==""){
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
				$jobp = getJobs("company_id = '$company[company_id]'",0,1);
				if($jobp){
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
					
					$sql_jobp_u = "select * from dtb_user where $kindsID $typesID and del_flg=0 order by rand() limit 0 , 14";
					$tj_user = $db->QueryArray($sql_jobp_u);
					
					if(count($tj_user)==0&&count($tj_user)==0){
						$sql_jobp_u = "select * from dtb_user where $kindsID and del_flg=0 order by rand() limit 0 , 14";
						$tj_user = $db->QueryArray($sql_jobp_u);
					}
				}
				if(@$skills[0]!=""){
					$sql_user = "select * from dtb_user where skill like '%$skills[0]%' and del_flg=0 order by rand() limit 0 , 10";
					$user_1 = $db->QueryArray($sql_user);
					$tj_user = array_merge($tj_user,$user_1);
					//$tj_user=$user_1;
				}
				if(@$skills[1]!=""){
					$sql_job = "select * from dtb_user where skill like '%$skills[1]%' and del_flg=0 order by rand() limit 0 , 10";
					$user_2 = $db->QueryArray($sql_job);
					$tj_user = array_merge($tj_user,$user_2);
				}
				if(@$skills[2]!=""){
					$sql_job = "select * from dtb_user where skill like '%$skills[2]%' and del_flg=0 order by rand() limit 0 , 10";
					$user_3 = $db->QueryArray($sql_job);
					$tj_user = array_merge($tj_user,$user_3);
				}
				shuffle($tj_user);
				if(empty($tj_user)){
					$sql_job = "select * from dtb_user where user_photo_url !='' and  del_flg=0 and info_flg=1 order by create_date  desc limit 0 , 14";
					$tj_user = $db->QueryArray($sql_job);
					//$job = array_merge($job,$job_4);
				}
				$smarty->assign('tj_user', $tj_user);
				$smarty->assign('company', $company);
				$yule = getBbs("",0, 14);
				$smarty->assign('yule', $yule);
				//好友
				$sqlall = "select * from dtb_favorite_list where user_id = '$company[company_id]' and favorite_id != '$company[company_id]' and (flag='user' or flag='company') and del_flg = 0 order by create_date desc";
				$friends = $db->QueryArray($sqlall);
				$smarty->assign('friend', $friends);
				
				//好友
				$sqlquan = "select * from dtb_favorite_list where user_id = '$company[company_id]' and (flag='user' or flag='company') and del_flg = 0 and shield = 0";
				$quan_friends = $db->QueryArray($sqlquan);
				
				$userids = "";
				foreach ($quan_friends as $v){
					if($userids != ""){
						$userids .= " or ";
					}
					$userids .= "user_id = $v[favorite_id]";
				}
				$where="";
				$quan_Change="";
				//朋友圈筛选
				if((isset($_REQUEST["quan_Change"])&&$_REQUEST["quan_Change"]!="")){
					$quan_Change=$_REQUEST['quan_Change'];
					if($quan_Change != ""){
						$where="user_type='$quan_Change'";
					}
				}
				$smarty->assign('quan_hange',@$_REQUEST["quan_Change"]);
				//-----------------分页处理开始----------------------
				if(isset($_REQUEST["page"])){
					$page = $_REQUEST["page"];
				}else{
					$page = 1;
				}
				//每页显示几条;//得到当前是第几页;
				if($userids){
					if($where != "")$where .=" and ";
					$where.="(user_id='$id[1]' or ($userids))";
				}else {
					if($where != "")$where .=" and ";
					$where.="(user_id='$id[1]')";
				}
				$show_num=10;$pageCurrent=$page;
				$all_nums = getObjectNum("dtb_log",$where);
				$smarty->assign('all',$all_nums);
				$subPages = new SubPages($show_num,$all_nums,$pageCurrent,10,APP_URL."people/show/company_$company[company_id]/?quan_Change=$quan_Change&page=");
				//-----------------分页处理完了----------------------
				//利用limit查询数据库---select * from table limit $start,$end
				$start = $subPages->getStart($show_num,$pageCurrent);//配合分页得到起始数
				$end = $subPages->getEnd($all_nums,$show_num,$pageCurrent);//配合分页得到结束数
				$smarty->assign('subPages',$subPages->showPageStr());
				//朋友圈
				$sql_quan = "select * from dtb_log where $where and del_flg = 0 order by create_date desc limit $start,$end";
				$quan = $db->QueryArray($sql_quan);
				if(empty($quan)){
					if((!isset($_REQUEST["quan_Change"]))||$_REQUEST["quan_Change"]=="all"){
						$sql_quan = "select * from dtb_log where  del_flg = 0 order by create_date desc limit 0,10";
						$quan = $db->QueryArray($sql_quan);
						$smarty->assign('quannull', '1');
					}
				}
				$smarty->assign('quan', $quan);
							
				$sql_yuming = "select yuming from dtb_2domain where user_id = '$id[1]' and del_flg = 0";
				$yuming = $db->QueryItem($sql_yuming);
				$smarty->assign('yuming', $yuming);
				//区分company,user
				$smarty->assign('flag', $id[0]);
				//固定引入参数
				$smarty->assign('BASE_URL', APP_URL);
				$smarty->assign('THEME', THEME);
				
				if(isMobile()){
// 					$show_num=10;$pageCurrent=$page;
					$job_nums = getObjectNum("dtb_jobs","company_id='$id[1]'");
// 					$subPages2 = new SubPages($show_num,$job_nums,$pageCurrent,10,APP_URL."people/job/company_$id[1]/?page=");
// 					//-----------------分页处理完了----------------------
// 					//利用limit查询数据库---select * from table limit $start,$end
// 					$start = $subPages2->getStart($show_num,$pageCurrent);//配合分页得到起始数
// 					$end = $subPages2->getEnd($job_nums,$show_num,$pageCurrent);//配合分页得到结束数
// 					$smarty->assign('subPages2',$subPages2->showPageStr());
					
					$jobs = getJobs("company_id='$company[company_id]'",0, $job_nums);
					$smarty->assign('jobs', $jobs);
// 					var_dump($jobs);
					$smarty->display('mobile/People/companyshow.html');
				}else{
					$smarty->display('People/companyshow.html');
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
				
				#######################################################
				#  匹配数据
				#######################################################
				$skills = explode(",", $user["skill"]);
				
				if(@$skills[0]!=""){
					$sql_job = "select * from dtb_jobs where level like '%$skills[0]%' and del_flg=0 order by rand() limit 0 , 14";
					$job_1 = $db->QueryArray($sql_job);
					
					$job = $job_1;
				}
				if(@$skills[1]!=""){
					$sql_job = "select * from dtb_jobs where level like '%$skills[1]%' and del_flg=0 order by rand() limit 0 , 14";
					$job_2 = $db->QueryArray($sql_job);
					
					$job = array_merge($job,$job_2);
				}
				if(@$skills[2]!=""){
					$sql_job = "select * from dtb_jobs where level like '%$skills[2]%' and del_flg=0 order by rand() limit 0 , 14";
					$job_3 = $db->QueryArray($sql_job);
					
					$job = array_merge($job,$job_3);
				}
				if(empty($job)){
					$sql_job = "select * from dtb_jobs where  del_flg=0 order by answer_date desc limit 0 , 14";
					$job = $db->QueryArray($sql_job);
					//$job = array_merge($job,$job_4);
				}
				shuffle($job);
				$smarty->assign('job', $job);
				
				
				$yule = getBbs("",0, 14);
				$smarty->assign('yule', $yule);
				#######################################################
				#  匹配数据
				#######################################################
				
				
				if(!isset($_SESSION["user_num"]) || $_SESSION["user_num"] != $user["user_id"]){
					//访问空间数
					add_see_page($id[1],"user",$user["see_page"]);
					$_SESSION["user_num"]=$user["user_id"];
				}
				//人信息
				$smarty->assign('users', $user);
				if($user["want_day"]!=""){
					$want_day = explode(",", $user["want_day"]);
					$smarty->assign('want_day', $want_day);
				}
				
				if($user['sex']==1){
					$usersex="男性";
				}else{
					$usersex="女性";
				}
				$smarty->assign('usersex', $usersex);
				
				//好友
				$sqlall = "select * from dtb_favorite_list where user_id = '$user[user_id]' and favorite_id != '$user[user_id]' and (flag='user' or flag='company') and del_flg = 0 order by create_date desc";
				$friends = $db->QueryArray($sqlall);
				$smarty->assign('friend', $friends);
				
				//好友
				$sqlquan = "select * from dtb_favorite_list where user_id = '$user[user_id]' and (flag='user' or flag='company') and del_flg = 0 and shield = 0";
				$quan_friends = $db->QueryArray($sqlquan);
				
				$userids = "";
				foreach ($quan_friends as $v){
					if($userids != ""){
						$userids .= " or ";
					}
					$userids .= "user_id = $v[favorite_id]";
				}
				$where="";
				$quan_Change="";
				//朋友圈筛选
				if((isset($_REQUEST["quan_Change"])&&$_REQUEST["quan_Change"]!="")){
					$quan_Change=$_REQUEST['quan_Change'];
					if($quan_Change != ""){
						$where="user_type='$quan_Change'";
					}
				}
				$smarty->assign('quan_hange',@$_REQUEST["quan_Change"]);
				//-----------------分页处理开始----------------------
				if(isset($_REQUEST["page"])){
					$page = $_REQUEST["page"];
				}else{
					$page = 1;
				}
				//每页显示几条;//得到当前是第几页;
				if($userids){
					if($where != "")$where .=" and ";
					$where.="(user_id='$id[1]' or ($userids))";
				}else {
					if($where != "")$where .=" and ";
					$where.="(user_id='$id[1]')";
				}
// 				if($where != "")$where .=" and ";
// 				$where.="(user_id='$id[1]' or ($userids))";
				
				$show_num=10;$pageCurrent=$page;
				$all_nums = getObjectNum("dtb_log",$where);
				$smarty->assign('all',$all_nums);
				$subPages = new SubPages($show_num,$all_nums,$pageCurrent,10,APP_URL."people/show/user_$user[user_id]/?quan_Change=$quan_Change&page=");
				//-----------------分页处理完了----------------------
				//利用limit查询数据库---select * from table limit $start,$end
				$start = $subPages->getStart($show_num,$pageCurrent);//配合分页得到起始数
				$end = $subPages->getEnd($all_nums,$show_num,$pageCurrent);//配合分页得到结束数
				$smarty->assign('subPages',$subPages->showPageStr());
				//朋友圈
				$sql_quan = "select * from dtb_log where $where and del_flg = 0 order by create_date desc limit $start,$end";
				$quan = $db->QueryArray($sql_quan);
				if(empty($quan)){
					if((!isset($_REQUEST["quan_Change"]))||$_REQUEST["quan_Change"]=="all"){
						$sql_quan = "select * from dtb_log where  del_flg = 0 order by create_date desc limit 0,10";
						$quan = $db->QueryArray($sql_quan);
						$smarty->assign('quannull', '1');
					}
				}
				$smarty->assign('quan', $quan);
				
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
				if(isMobile()){
					$smarty->display('mobile/People/peopleshow.html');
				}else{
					$smarty->display('People/peopleshow.html');
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