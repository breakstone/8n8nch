<?php
	// config
	if(!file_exists('./../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	
	require_once './../_config/config.php';
	require_once './../_includes/functions.php';
	//发表工作
	if(isset($_POST['company_id'])&&isset($_POST['job_id'])&&isset($_SESSION['kiwa_username'])&&isset($_SESSION['kiwa_userid'])){
		//发表求职
		$user_id = cleanInput($_SESSION['kiwa_userid']);
		
		$company_id = cleanInput($_POST['company_id']);
		$company_name = cleanInput($_POST['company_name']);
		$job_id = cleanInput($_POST['job_id']);
		$job_title = cleanInput($_POST['job_title']);
		$toCompany_pr = cleanInput($_POST['toCompany_pr']);
		
		//个人简历
		$name01 = cleanInput($_POST['name01']);
		$name02 = cleanInput($_POST['name02']);
		$birth = $_POST["birthYear"]."-".$_POST["birthMonth"]."-".$_POST["birthDay"];
		$birth = cleanInput($birth);
		$sex = cleanInput($_POST['sex']);
		$email = cleanInput($_POST['email']);
		$per_status = cleanInput($_POST['per_status']);
		$pr = cleanInput($_POST['pr']);
		$zige = cleanInput($_POST['zige']);
		
		$areaid = cleanInput($_POST['areaid']);
		$pref = cleanInput($_POST['pref']);
		
		$add_pref = getName(array("name"=>"pref","value"=>$pref));
		
		$kinds = $_POST['kinds'];
		foreach ($_POST['types'] as $t){
			$types_str .= $t.",";
			$row = getKindTypename($t);
			$typesname_str .= $row["typesname"].",";
			$kindsname = $row["kindsname"];
		}
		$types_str = rtrim($types_str,",");
		$typesname_str = rtrim($typesname_str,",");
		
		
		foreach ($_POST['skills'] as $s){
			$skills_str .= $s.",";
		}
		$skills_str = rtrim($skills_str,",");
		
		foreach ($_POST['ensn'] as $en){
			$ensn_str .= $en.",";
		}
		$ensn_str = rtrim($ensn_str,",");
		
		foreach ($_POST['eki'] as $ei){
			$eki_str .= $ei.",";
		}
		$eki_str = rtrim($eki_str,",");
		
		$eki = cleanInput($_POST['eki']);
		$zip01 = cleanInput($_POST['zip01']);
		$zip02 = cleanInput($_POST['zip02']);
		$add_pref = cleanInput($_POST['add_pref']);
		$addr01 = cleanInput($_POST['addr01']);
		$addr02 = cleanInput($_POST['addr02']);
		$tel01 = cleanInput($_POST['tel01']);
		$tel02 = cleanInput($_POST['tel02']);
		$tel03 = cleanInput($_POST['tel03']);
		$tel_flag = cleanInput($_POST['tel_flag']);
		$last_education = cleanInput($_POST['last_education']);
		$last_education_name = getName(array("name"=>"education","value"=>$last_education));
		
		$zhuanye = cleanInput($_POST['zhuanye']);
		$job_experiencetimes = cleanInput($_POST['job_experiencetimes']);
		$job_nowstatus = cleanInput($_POST['job_nowstatus']);
		$job_experience = cleanInput($_POST['job_experience']);
		
		//邮件公开是否处理
		if($per_status == 1){$job_email = $email;}
		if($per_status == 2){$job_email = "非公开";}
		
				
		//发表求职处理
		$return1 = jobregist($_SESSION['kiwa_userid'],$job_id,$job_title,$company_id,$company_name,$toCompany_pr);
		
		$sqlpass='select password from dtb_user where user_id='.$_SESSION["kiwa_userid"];
		$password=$db->QueryItem($sqlpass);
		
		//个人简历更新
		$return2 = personal_update($_SESSION['kiwa_userid'],$name01,$name02,$zip01,$zip02,$add_pref,$addr01,$addr02,$eki,$email,$password,$per_status,$tel01,$tel02,$tel03,$tel_flag,$sex,$birth,$zhuanye,$last_education,$job_experiencetimes,$job_experience,$job_nowstatus,$zige,$pr,$areaid,$pref,$kinds,$types_str,$skills_str,$ensn_str,$eki_str,$kindsname,$typesname_str,1);
		
		pointsDo($_SESSION['kiwa_userid'], "user",10);
		if(!$return1||!$return2){
			if(!$return1) echo "递交简历处理 失败！" ;exit();
			if(!$return2) echo "个人简历更新 失败！" ;exit();
		}else{
			//没有参数跳到错误页面
			header('Location:'.APP_URL.'mypage/done/jobregistok');
		}
	}else{
		//没有参数跳到错误页面
		header('Location:'.APP_URL.'mypage/done/error');
	}
?>