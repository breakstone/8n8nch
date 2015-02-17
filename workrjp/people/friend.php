<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	require_once '../_includes/SubPages.php';
	
	//删除好友
	if(isset($_POST["del"])){
		if(@$_SESSION["kiwa_userid"]!=""){
			$user_id = $_SESSION["kiwa_userid"];
		}
		if(@$_SESSION["kiwa_companyid"]!=""){
			$user_id = $_SESSION["kiwa_companyid"];
		}
		$de_id = $_POST["de_id"];
		$de_type = $_POST["de_type"];
		$sql = "update dtb_favorite_list set del_flg = 1 where user_id='$user_id' and favorite_id = '$de_id' and flag='$de_type'";
		$db->Execute($sql);
	}
	//解除屏蔽
	if(isset($_GET["jie"])&&$_GET["jie"]!=""){
		
		if($_SESSION["kiwa_userid"]!=""){
			$user_id = $_SESSION["kiwa_userid"];
		}
		if($_SESSION["kiwa_companyid"]!=""){
			$user_id = $_SESSION["kiwa_companyid"];
		}
		$jie_id = $_GET["jie"];
		$sql = "update dtb_favorite_list set shield = 0 where user_id='$user_id' and favorite_id = '$jie_id'";
		$db->Execute($sql);
	}
	
	$id = explode("_",$id);
	//-----------------分页处理开始----------------------
	if(isset($_REQUEST["page"])){
		$page = $_REQUEST["page"];
	}else{
		$page = 1;
	}
	//每页显示几条;//得到当前是第几页;
	$show_num=20;$pageCurrent=$page;
	$all_nums = getObjectNum("dtb_people_msg","people_id='$id[1]'");
	if(strlen($id[1]) <= 12){
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
				
				
				$subPages = new SubPages($show_num,$all_nums,$pageCurrent,10,APP_URL."people/friend/company_$id[1]/?page=");
				//-----------------分页处理完了----------------------
				//利用limit查询数据库---select * from table limit $start,$end
				$start = $subPages->getStart($show_num,$pageCurrent);//配合分页得到起始数
				$end = $subPages->getEnd($all_nums,$show_num,$pageCurrent);//配合分页得到结束数
				$smarty->assign('subPages',$subPages->showPageStr());
				
				//添加的个人好友
				$sql_user = "select * from dtb_favorite_list where user_id = '$id[1]' and favorite_id != '$id[1]' and flag='user'  and del_flg = 0 order by create_date desc";
				$friends_user = $db->QueryArray($sql_user);
				$smarty->assign('friends_user', $friends_user);
				//添加的企业好友
				$sql_company= "select * from dtb_favorite_list where user_id = '$id[1]' and favorite_id != '$id[1]' and flag='company' and del_flg = 0 order by create_date desc";
				$friends_company = $db->QueryArray($sql_company);
				$smarty->assign('friends_company', $friends_company);
				
				if(empty($friends_user)&&empty($friends_company))
				{
					$sql_tj = "select user_id,user_type,count(*) as count from dtb_log where user_id != '$id[1]' group by user_id order by  count desc limit 0,12";
					$friends_tj = $db->QueryArray($sql_tj);
					$smarty->assign('friends_tj', $friends_tj);
				}
				
				$sql_tj = "select user_id,user_type,count(*) as count from dtb_log where user_id != '$id[1]' group by user_id order by  count desc limit 0,12";
				$friends_tj = $db->QueryArray($sql_tj);
				$smarty->assign('friends_tj1', $friends_tj1);
				
				$sql_fn="select count(*) from dtb_favorite_list where user_id = '$id[1]' and (flag='user' or flag='company') and del_flg = 0";
				$um = $db->QueryItem($sql_fn);
				$smarty->assign('um', $um);
				
				if($friends_user){
					foreach($friends_user as $friends_user_tj){
						$friend_user_tj= $friends_user_tj["favorite_id"];
						$where_friend .= " (user_id != '$friend_user_tj')";
						$where_friend .= " and ";
					}
				}
				if($friends_company){
					foreach($friends_company as $friends_company_tj){
						$friend_user_tj= $friends_company_tj["favorite_id"];
						$where_friend .= "(user_id != '$friend_user_tj')";
						$where_friend .= " and ";
					}
				}
				if($where_friend){
					$where_friend = rtrim($where_friend,"and ");
					$where_friend=" and  $where_friend ";
				}
				
				$sql_tj = "select user_id,user_type,count(*) as count from dtb_log where user_id != '$id[1]'  $where_friend group by user_id order by  count desc limit 0,8";
				$friends_user_tj = $db->QueryArray($sql_tj);
				$smarty->assign('friends_user_tj', $friends_user_tj);
// 				//新加  加好友
// 				$sqlall = "select * from dtb_favorite_list where user_id = '$id[1]' and (flag='user' or flag='company') and del_flg = 0 order by create_date desc limit 0 , 8";
// 				$friends = $db->QueryArray($sqlall);
// 				$smarty->assign('friend', $friends);
// 				$sql_fn="select count(*) from dtb_favorite_list where user_id = '$id[1]' and (flag='user' or flag='company') and del_flg = 0";
// 				$um = $db->QueryItem($sql_fn);
// 				$smarty->assign('um', $um);
				
				
				$smarty->assign('company', $company);
				//区分company,user
				$smarty->assign('flag', $id[0]);
				//固定引入参数
				$smarty->assign('BASE_URL', APP_URL);
				$smarty->assign('THEME', THEME);
				$smarty->display('People/company_friend.html');
			}else{
				//没有找到
				$smarty->assign('h1', "信息提示");
				$smarty->assign('message', "非常抱歉，您指定的企业信息的登载已经结束。感谢您的关注！");
				$smarty->assign('BASE_URL', APP_URL);
				$smarty->assign('THEME', THEME);
				$smarty->display('People/done.html');
			}
		}
		if($id[0] == "user"){
			$user = getUser($id[1]);
			if($user){
				
				$subPages = new SubPages($show_num,$all_nums,$pageCurrent,10,APP_URL."people/friend/user_$id[1]/?page=");
				//-----------------分页处理完了----------------------
				//利用limit查询数据库---select * from table limit $start,$end
				$start = $subPages->getStart($show_num,$pageCurrent);//配合分页得到起始数
				$end = $subPages->getEnd($all_nums,$show_num,$pageCurrent);//配合分页得到结束数
				$smarty->assign('subPages',$subPages->showPageStr());
				
				//添加的个人好友
				$sql_user = "select * from dtb_favorite_list where user_id = '$id[1]' and favorite_id != '$id[1]' and flag='user'  and del_flg = 0 order by create_date desc";
				$friends_user = $db->QueryArray($sql_user);
				$smarty->assign('friends_user', $friends_user);
				//添加的企业好友
				$sql_company= "select * from dtb_favorite_list where user_id = '$id[1]' and favorite_id != '$id[1]' and flag='company' and del_flg = 0 order by create_date desc";
				$friends_company = $db->QueryArray($sql_company);
				$smarty->assign('friends_company', $friends_company);
				
				if(empty($friends_user)&&empty($friends_company))
				{
					$sql_tj = "select user_id,user_type,count(*) as count from dtb_log where user_id != '$id[1]' group by user_id order by  count desc limit 0,12";
					$friends_tj = $db->QueryArray($sql_tj);
					$smarty->assign('friends_tj', $friends_tj);
				}
				
				$sql_fn="select count(*) from dtb_favorite_list where user_id = '$id[1]' and (flag='user' or flag='company') and del_flg = 0";
				$um = $db->QueryItem($sql_fn);
				$smarty->assign('um', $um);
			
				if($friends_user){
					foreach($friends_user as $friends_user_tj){
						$friend_user_tj= $friends_user_tj["favorite_id"];
						$where_friend .= " (user_id != '$friend_user_tj')";
						$where_friend .= " and ";
					}
				}
				if($friends_company){
					foreach($friends_company as $friends_company_tj){
						$friend_user_tj= $friends_company_tj["favorite_id"];
						$where_friend .= "(user_id != '$friend_user_tj')";
						$where_friend .= " and ";
					}
				}
				if($where_friend){
					$where_friend = rtrim($where_friend,"and ");
					$where_friend=" and  $where_friend ";
				}
				
				$sql_tj = "select user_id,user_type,count(*) as count from dtb_log where user_id != '$id[1]'  $where_friend group by user_id order by  count desc limit 0,8";
				$friends_user_tj = $db->QueryArray($sql_tj);
				$smarty->assign('friends_user_tj', $friends_user_tj);
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
				$smarty->display('People/people_friend.html');
			}else{
				//没有找到
				$smarty->assign('h1', "信息提示");
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