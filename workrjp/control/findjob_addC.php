<?php
	// config
	if(!file_exists('./../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	
	require_once './../_config/config.php';
	require_once './../_includes/functions.php';
	//发表工作
	if(isset($_POST['people_title'])&&isset($_POST['people_pr'])&&isset($_SESSION['kiwa_username'])&&isset($_SESSION['kiwa_userid'])){
		//发表求职
		$people_title = cleanInput($_POST['people_title']);
		$people_pr = cleanInput($_POST['people_pr']);
		
		###职种，业种处理###
		$kinds = cleanInput($_POST['kinds']);
		$kindsname = "";
		$types = "";
		$typesname = "";
		foreach($_POST['types'] as $t){
			$ktrow = getKindTypename($t);
			$kindsname = $ktrow["kindsname"];
			$types .= $t.",";
			$typesname .= $ktrow["typesname"].",";
		}
		$types = rtrim($types,",");
		$typesname = rtrim($typesname,",");
		###职种，业种处理###
		$areaid = cleanInput($_POST['areaid']);
		$pref = cleanInput($_POST['pref']);
		$prefname = getName(array("name"=>"pref","value"=>$pref));
		
		$station_cd="";
		$station_name="";
		$line_num="";
		if(isset($_POST["ensn"])&&$_POST["ensn"]!=""){//線
			foreach($_POST["ensn"] as $l){
				$line_num .= $l.",";
			}
			$line_num = rtrim($line_num,",");
		}
		if(isset($_POST["eki"])&&$_POST["eki"]!=""){//駅
			foreach($_POST["eki"] as $s){
				$station_cd .= $s.",";
				$station_name .= getName(array("name"=>"eki","value"=>$s)).",";
			}
			$station_cd = rtrim($station_cd,",");
			$station_name = rtrim($station_name,",");
		}
		
		$moneyid = cleanInput($_POST['moneyid']);
		$moneyname = getName(array("name"=>"money","value"=>$moneyid));

		$employid = cleanInput($_POST['employid']);
		$employname = getName(array("name"=>"employ","value"=>$employid));
		
		$hopedateid = cleanInput($_POST['hopeid']);
		$hopedatename = getName(array("name"=>"hope","value"=>$hopedateid));
		
		
		
		//个人简历
		$birth = cleanInput($_POST['birth']);
		$sex = cleanInput($_POST['sex']);
		$email = cleanInput($_POST['email']);
		$per_status = cleanInput($_POST['per_status']);
		$pr = cleanInput($_POST['pr']);
		$zige = cleanInput($_POST['zige']);
		$eki = cleanInput($_POST['zj_eki']);
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
		if($per_status = 1){$job_email = $email;}
		if($per_status = 2){$job_email = "非公开";}
		//发表求职处理
		$return1 = findjob_add($_SESSION['kiwa_userid'],$_SESSION['kiwa_username'],$birth,$people_title,$kinds,$kindsname,$types,$typesname,$people_pr,$employid,$employname,$areaid,$pref,$prefname,$line_num,$station_cd,$station_name,$hopedateid,$hopedatename,$moneyid,$moneyname,$last_education,$last_education_name,$job_nowstatus,$job_email);
		
		//个人简历更新
		$return2 = user_update($_SESSION['kiwa_userid'],$zip01,$zip02,$add_pref,$addr01,$addr02,$eki,$email,$per_status,$tel01,$tel02,$tel03,$tel_flag,$sex,$birth,$zhuanye,$last_education,$job_experiencetimes,$job_experience,$job_nowstatus,$zige,$pr);
	
		if(!$return1||!$return2){
			if(!$return1) echo "发表求职处理 失败！" ;exit();
			if(!$return2) echo "个人简历更新 失败！" ;exit();
		}else{
			//没有参数跳到错误页面
			header('Location:'.APP_URL.'mypage/done/addok');
		}
	}else{
		//没有参数跳到错误页面
		header('Location:'.APP_URL.'mypage/done/error');
	}
?>