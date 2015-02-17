<?php
	if(!file_exists('../_config/config.php'))
	{
		die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	
	$do=@$_REQUEST['do']? $_REQUEST['do'] : '';// 判断所得信息类型
	$user_type=@$_REQUEST['user_type']? $_REQUEST['user_type'] : '';// 判断所得信息类型
	//提交意见
	/*if ($do=='comment')
	{
		$userid = cleanInput(@$_REQUEST['userid']);
		$comment_tent = cleanInput(@$_REQUEST['comment_tent']);
		$comment_tent = $db->real_escape_string($comment_tent);
		$username=cleanInput(@$_REQUEST['username']);
		$now = date("Y-m-d H:i:s");
		$sql="insert into dtb_comment(user_id,user_name,user_comment,create_date)values('$userid','$username','$comment_tent','$now')";
		echo $sql;
		if($db->Execute($sql)){
			$message=["true","提交成功"];
			exit(json_encode($message));
		}else{
			$message=["false","提交失败"];
			exit(json_encode($message));
		}
	}*/
	//好友设置
/*	if ($do=='friend')
	{
		$userid = cleanInput(@$_REQUEST['userid']);
		if (empty($userid))
		{
			$message=["false","userid不能为空"];
			exit(json_encode($message));
		}
		$act=cleanInput(@$_REQUEST['act']);
		if (empty($act))
		{
			$message=["false","act不能为空"];
			exit(json_encode($message));
		}
		
		if($act=="pingbi"){
			$favorite_id= cleanInput($_REQUEST['favorite_id']);
			if (empty($favorite_id))
			{
				$message=["false","favorite_id不能为空"];
				exit(json_encode($message));
			}
			$sql = "update dtb_favorite_list set shield = 1 where user_id = '$userid' and favorite_id = '$favorite_id'";
			if($db->Execute($sql)){
				$message=["true","屏蔽成功"];
				exit(json_encode($message));
			}else{
				$message=["false","屏蔽失败"];
				exit(json_encode($message));
			}
		}elseif ($act=="jiepingbi"){
			$favorite_id= cleanInput($_REQUEST['favorite_id']);
			if (empty($favorite_id))
			{
				$message=["false","favorite_id不能为空"];
				exit(json_encode($message));
			}
			$sql = "update dtb_favorite_list set shield = 0 where user_id='$userid' and favorite_id = '$favorite_id'";
			if($db->Execute($sql)){
				$message=["true","解除成功"];
				exit(json_encode($message));
			}else{
				$message=["false","解除失败"];
				exit(json_encode($message));
			}
		}elseif ($act=="blacklist"){
			$sql = "select * from dtb_favorite_list where  shield = 1 and user_id='$userid'";
			$black_friend= $db->QueryArray($sql);
			if($black_friend){
				var_dump($black_friend);
				exit(json_encode($message));
			}else{
				$message=["false","黑名单没有好友"];
				exit(json_encode($message));
			}
		}else{
				$message=["false","act赋值不正确"];
				exit(json_encode($message));
		}
	}*/
	//朋友圈
	if ($do=='friend_quan')
	{
		$userid = cleanInput(@$_REQUEST['userid']);
		if (empty($userid))
		{
			$message=["false","userid不能为空"];
			exit(json_encode($message));
		}
		$act=cleanInput(@$_REQUEST['act']);
		if (empty($act))
		{
			$message=["false","act不能为空"];
			exit(json_encode($message));
		}
		if($act=="select"){
			
			$sqlquan = "select * from dtb_favorite_list where user_id = '$userid' and (flag='user' or flag='company') and del_flg = 0 and shield = 0";
			$quan_friends = $db->QueryArray($sqlquan);
			$userids = "";
			foreach ($quan_friends as $v){
				if($userids != ""){
					$userids .= " or ";
				}
				$userids .= "user_id = $v[favorite_id]";
			}
			if (!empty($user_type))
			{
				$where="user_type='$user_type'";
			}
			if($userids){
				if($where != "")$where .=" and ";
				$where.="(user_id='$userid' or ($userids))";
			}else {
				if($where != "")$where .=" and ";
				$where.="(user_id='$userid')";
			}
			$sql_quan = "select * from dtb_log where $where and del_flg = 0 order by create_date desc";
			$quan = $db->QueryArray($sql_quan);
			
			if ($quan){
				exit(json_encode($quan));
			}else{
				$message=["false","没有状态信息"];
				exit(json_encode($message));
			}
		}
	}
	//我发布的状态
	/*if ($do=='mysend_status')
	{
		$userid = cleanInput(@$_REQUEST['userid']);
		if (empty($userid))
		{
			$message=["false","userid不能为空"];
			exit(json_encode($message));
		}
		$act=cleanInput(@$_REQUEST['act']);
		if (empty($act))
		{
			$message=["false","act不能为空"];
			exit(json_encode($message));
		}
		if($act=="insert"){
			$username="";$userid="";$usertype="";
			$user_id=cleanInput(@$_REQUEST['userid']);
			$user_type=cleanInput(@$_REQUEST['usertype']);
			if($user_id!=""&&$user_type!=""){
				$status_id=date("is").rand(1000,9999);;
				$status_content = cleanInput($_REQUEST['status_content']);
				if (empty($status_content))
				{
					$message=["false","status_content不能为空"];
					exit(json_encode($message));
				}
				$status_content = $db->real_escape_string($status_content);
				$create_date = date("Y-m-d H:i:s");
				$sql = "insert into dtb_status set status_id = '$status_id',status_content='$status_content',user_id='$user_id',user_type='$user_type',create_date='$create_date'";
				$return = $db->Execute($sql);
				if($return){
					//存日志表
					dolog($user_id, $user_type, "status", $status_id, $status_content, "insert");
					
					$image_names=json_decode(@$_REQUEST["photo_url"]);
					$photo_url= "upload/img/people/";
					if($image_names)
					{
						foreach($image_names as $photo){
							$filetype=pathinfo($photo, PATHINFO_EXTENSION);
							$image_name = $user_id.'_'.date("is").rand(100,999).'.'.$filetype;
							$sql_create = "insert into dtb_people_photo set user_id='$user_id',user_type='$user_type',title='$status_content',content='$status_content',photo_url = 'upload/img/people/$image_name',flag ='status',create_date='$now'";
							$db->Execute($sql_create);
							@$status_photo .= $photo_url.$image_name.",";
						}
					}
					@$status_photo = rtrim($status_photo,",");
					$status_photo = cleanInput($status_photo);
					$status_photo = $db->real_escape_string($status_photo);
					$sql="update dtb_status set status_photo = '$status_photo' where status_id='$status_id' and del_flg =0";
					$db->Execute($sql);
					$message=["true","发布成功"];
					exit(json_encode($message));
				}else{
					$message=["false","没有状态信息"];
					exit(json_encode($message));
				}
			}else{
				$message=["false","userid、usertype没有赋值"];
				var_dump($message);
				exit(json_encode($message));
			}
		}elseif ($act=="del"){
			if(@$_REQUEST['status_id']){
				$status_id=cleanInput(@$_REQUEST['status_id']);
					
				$sql = "select user_id from dtb_status where del_flg = 0 and status_id=".$status_id;
				$uid = $db->QueryItem($sql);
					
				if($userid == $uid){
					$sql = "update dtb_status set del_flg = 1 where status_id='$status_id'";
					$sql1 = "update dtb_log set del_flg = 1 where type_id='$status_id'";
					if($db->Execute($sql)&&$db->Execute($sql1)){
						$message=["true","删除成功"];
						exit(json_encode($message));
					}
					else
					{
						$message=["false","删除失败"];
						exit(json_encode($message));
					}
				}else{
					$message=["false","userid不相等"];
					exit(json_encode($message));
				}
			}else {
				$message=["false","status_id不能为空"];
				exit(json_encode($message));
			}
		}
	}*/
	//我的发布
	if ($do=='mysend')
	{
		$userid = cleanInput(@$_REQUEST['userid']);
		if (empty($userid))
		{
			$message=["false","userid不能为空"];
			exit(json_encode($message));
		}
		$mysend_type = cleanInput(@$_REQUEST['mysend_type']);
		$act=cleanInput(@$_REQUEST['act']);
		if (empty($act))
		{
			$message=["false","act不能为空"];
			exit(json_encode($message));
		}
		if($act=="select"){
			if($mysend_type=="job"){
				$where="company_id='$userid'";
				$sql = "select * from dtb_jobs	where del_flg = 0 and $where order by create_date ";
				$array = $db->QueryArray($sql);
			}elseif ($mysend_type=="bbs"){
				$where="user_id='$userid'";
				$sql = "select * from dtb_bbs	where del_flg = 0 and $where order by create_date ";
				$array = $db->QueryArray($sql);
			}elseif ($mysend_type=="live"){
				$where="user_id='$userid'";
				$sql = "select * from dtb_lives	where del_flg = 0 and $where order by create_date ";
				$array = $db->QueryArray($sql);
			}else{
				$message=["false","mysend_type赋值不正确"];
				exit(json_encode($message));
			}
			if ($array){
				exit(json_encode($array));
				//var_dump($array);
			}else{
				$message=["false","你还没有任何发布信息"];
				exit(json_encode($message));
			}
		}
	}
	
	//忘记密码
	if ($do=='forgetpwd')
	{
		$email = cleanInput(@$_REQUEST['email']);
		if (empty($email))
		{
			$message=["false","email不能为空"];
			exit(json_encode($message));
		}
		if(forgetC($email,APP_URL)){
			$message=["true","发送成功这到邮箱查看"];
			exit(json_encode($message));
		}else{
			$message=["false","发送失败"];
			exit(json_encode($message));
		}
	}
	//判断邮箱是否存在
	if ($do=='isregist')
	{
		$email = cleanInput(@$_REQUEST['email']);
		
		if($email!="")
		{
			//echo $email;
			if(emailRegist($email)){
				$message=["true","用户的邮箱不存在可以注册"];
				exit(json_encode($message));
			}
			else
			{
				$error=["false","用户的邮箱已存在"];
				exit(json_encode($error));
			}
		}
		
	}
	//得到最终学历
	if ($do=='geteduction')
	{
		$eduction = cleanInput(@$_REQUEST['eduction']);
	
		if($eduction =="all")
		{
			
			$eduction = getEduction();
			exit(json_encode($eduction));
		}
	
	}
	//得到企业分类
	if ($do=='getcompanytype')
	{
		$companytype = cleanInput(@$_REQUEST['companytype']);
	
		if($companytype =="all")
		{
			//企业分类
			$sql_comp = "select * from mtb_company_type";
			$qi = $db->QueryArray($sql_comp);
			//var_dump($qi);
			exit(json_encode($qi));
		}
	
	}
	//根据填写的邮政编码获取地址
	if ($do=='getaddr')
	{
		//根据填写的邮政编码获取地址
		$zip = cleanInput(@$_REQUEST['zip']);
		if($zip)
		{
			$zip = $db->real_escape_string($zip);
			$sql="select state,city,town from mtb_zip where `zipcode` = '$zip'";
			
			$row = $db->QueryRow($sql);
			if($row){
				//$state = $row['state'];
				$city = $row['city'];
				$town = $row['town'];
				$row=$city.$town;
				$message=["true",$row];
				exit(json_encode($message));
			}
			else {
				$error=["false","这个邮政编码检索不到，请在下直接填写地址或重新输入"];
				exit(json_encode($error));
			}
		}else{
			$error=["false","邮政编码不能为空"];
			exit(json_encode($error));
		}
	}
	
	//用户登录
	if ($do=='login')
	{
	
		if((isset($_REQUEST["email"])&&$_REQUEST["email"]!="")&&(isset($_REQUEST["password"])&&$_REQUEST["password"]!="")){
			$email = cleanInput(@$_REQUEST['email']);
			$password = cleanInput(@$_REQUEST['password']);
			
			$reward = loginCApp($email,$password);
			//var_dump($reward);		
			if($reward){
				if($reward['usertype'] == "user"){
					unset($reward['login_date']);
					$reward['flag']="true";
					exit(json_encode($reward));
				}
				if($reward['usertype'] == "company"){
					unset($reward['login_date']);
					$reward['flag']="true";
					exit(json_encode($reward));
				}
			}else{
				$error["flag"]=false;
				exit(json_encode($error));
			}
		}else{
			$error=["false","密码或用户名为空"];
			exit(json_encode($error));
		}
	}
	//密码修改
	/*if ($do=='update')
	{
		$update_type=@$_REQUEST["update_type"];
		$userid=cleanInput(@$_REQUEST['userid']);
	
		if (empty($update_type))
		{
			$message=["false","update_type没有赋值"];
			exit(json_encode($message));
		}
		if (empty($userid))
		{
			$message=["false","userid没有赋值"];
			exit(json_encode($message));
		}
		
		if($update_type=="pwd")
		{
			if (empty($user_type))
			{
				$message=["false","user_type没有赋值"];
				exit(json_encode($message));
			}
			if($user_type=='user')
			{
				$table='dtb_user';
				$key='user_id';
			}elseif ($user_type=='company') {
				$table='dtb_companyuser';
				$key='company_id';
			}else{
				$message=["false","user_type只能为user或company"];
				exit(json_encode($message));
			}
			if((isset($_REQUEST["password"])&&$_REQUEST["password"]!="")&&(isset($_REQUEST["newpassword"])&&$_REQUEST["newpassword"]!="")){
				
				$password = cleanInput(@$_REQUEST['password']);
				$password = md5($password);
				$newpassword = cleanInput(@$_REQUEST['newpassword']);
				$newpassword = md5($newpassword);
				$sqlsee = "select password from $table where $key = $userid and del_flg = 0";
				echo $sqlsee;
				$row = $db->QueryRow($sqlsee);
				var_dump($row);
				if($row['password'] == $password){
					$newpassword = $db->real_escape_string($newpassword);
					$sql = "update $table set password ='$newpassword' where user_id=$userid and del_flg = 0";
					echo $sql;
					if($db->Execute($sql)){
						$message=["true","更新成功"];
						exit(json_encode($message));
					}else{
						$message=["false","更新失败"];
						exit(json_encode($message));
					}
				}else{
					$message=["false","旧密码错误"];
					exit(json_encode($message));
				}
			}
		}elseif($update_type=="companypersonal"){
			
			$company_manager = cleanInput(@$_REQUEST['company_manager']);
			//处理設立年月
			$foundation_date = cleanInput(@$_REQUEST['foundation_date']);
				
			$company_money = cleanInput(@$_REQUEST['company_money']);
			$company_url = cleanInput(@$_REQUEST['company_url']);
			
			$right_email = cleanInput(@$_REQUEST['right_email']);//咨询邮箱
			$per_status = cleanInput(@$_REQUEST['per_status']);//是否公开 1:公开 2:不公开
			
			$zip01 = cleanInput(@$_REQUEST['zip01']);    //邮政编码
			$zip02 = cleanInput(@$_REQUEST['zip02']);
				
			$addr01 = cleanInput(@$_REQUEST['addr01']);
			$addr02 = cleanInput(@$_REQUEST['addr02']);
				
			$areaid = cleanInput(@$_REQUEST['area_cd']);
			$pref = cleanInput(@$_REQUEST['pref_cd']);
			$add_pref = getName(array("name"=>"pref","value"=>$pref));
			
			$types_str="";
			$kindsname="";
			$typesname_str="";
			
			$kinds = cleanInput(@$_REQUEST['kinds']);
			$types1=json_decode(@$_REQUEST['kindsid']);
			if($types1)
			{
				foreach($types1 as $t){
					$ktrow = getKindTypename($t);
					$kindsname = $ktrow["kindsname"];
					$types .= $t.",";
					$typesname .= $ktrow["typesname"].",";
				}
				$types_str = rtrim($types,",");
				$typesname_str = rtrim($typesname,",");
			}
			
			
			
			//skills_str=["派遣/中介","工厂/农业"]
			$skills_str="";
			$skills_str1 = json_decode(@$_REQUEST['skills_str']);
			
			if($skills_str1)
			{
				foreach ($skills_str1 as $s){
					@$skills_str .= $s.",";
				}
				$skills_str = rtrim($skills_str,",");
			}
			$ensn_str="";
			if(isset($_REQUEST["line_num"])&&@$_REQUEST["line_num"]!=""){//線
				$ensn=json_decode(@$_REQUEST['line_num']);
				foreach($ensn as $l){
					@$line_num .= $l.",";
				}
				$ensn_str = rtrim($line_num,",");
				$ensn_str = cleanInput($ensn_str);
					
			}
			$eki_str="";
			if(isset($_REQUEST["station_cd"])&&@$_REQUEST["station_cd"]!=""){//駅
				$eki=json_decode(@$_REQUEST["station_cd"]);
				foreach($eki as $s){
					@$station_cd .= $s.",";
				}
				$eki_str = rtrim($station_cd,",");
				$eki_str = cleanInput($eki_str);
			}
			$tel01 = cleanInput(@$_REQUEST['tel01']);
			$tel02 = cleanInput(@$_REQUEST['tel02']);
			$tel03 = cleanInput(@$_REQUEST['tel03']);
			$fax01 = cleanInput(@$_REQUEST['fax01']);
			$fax02 = cleanInput(@$_REQUEST['fax02']);
			$fax03 = cleanInput(@$_REQUEST['fax03']);
			$employees_num = cleanInput(@$_REQUEST['employees_num']);//从业人数
			$company_text = cleanInput(@$_REQUEST['company_text']);  //公司简介
			$return = company_updateApp($userid,$company_manager,$foundation_date,$company_money,$right_email,$per_status,$company_url,$zip01,$zip02,$add_pref,$addr01,$addr02,$tel01,$tel02,$tel03,$fax01,$fax02,$fax03,$employees_num,$company_text,$areaid,$pref,$kinds,$types_str,$skills_str,$ensn_str,$eki_str,$kindsname,$typesname_str);
			if(!$return){
				$message=["false","修改失败"];
				exit(json_encode($message));
			}else{
				$image_name=json_decode(@$_REQUEST["url_photo"]);
				company_img_update($userid,"upload/img/compangy/".$image_name);
				$message=["true","发表成功"];
				exit(json_encode($message));
			}
			//更新公司简历信息
		}elseif($update_type=="userpersonal"){
			
			$email = cleanInput($_REQUEST['email']);
			$sql="select email from dtb_user where user_id = '$userid'";
			
			$row = $db->QueryRow($sql);
			//echo 	$row['email'];
			if ($row['email']!= $email)
			{
				if(!emailRegist($email)){
					$error=["false","用户的email已存在"];
					exit(json_encode($error));
				}
			}
			
			$join = cleanInput(@$_REQUEST['join']);
			//个人简历
			$name01 = cleanInput(@$_REQUEST['name01']);
			$name02 = cleanInput(@$_REQUEST['name02']);
			$birth = @$_REQUEST["birthYear"]."-".@$_REQUEST["birthMonth"]."-".@$_REQUEST["birthDay"];
			$birth = cleanInput($birth);
			$sex = cleanInput(@$_REQUEST['sex']);
			$per_status = cleanInput(@$_REQUEST['per_status']);//邮箱是否公开
			$pr = cleanInput(@$_REQUEST['pr']); //销售自己的一句话
			$zige = cleanInput(@$_REQUEST['zige']);//取得的资格
			
			$areaid = cleanInput(@$_REQUEST['area_cd']);
			$pref = cleanInput(@$_REQUEST['pref_cd']);
			
			$add_pref = getName(array("name"=>"pref","value"=>$pref));
			
			$kinds = @$_REQUEST['kinds'];//业种
			$kindsname="";
			$types1=json_decode(@$_REQUEST['typesid']);//职总ID
			if($types1){
				foreach($types1 as $t){
					$ktrow = getKindTypename($t);
					$kindsname = $ktrow["kindsname"];
					@$types .= $t.",";
					@$typesname .= $ktrow["typesname"].",";
				}
			}
			
			@$types_str = rtrim($types,",");
			@$typesname_str = rtrim($typesname,",");
			
			if(isset($_REQUEST["skills"])&&@$_REQUEST["skills"]!=""){//工作意向
				//skills=["派遣/中介","工厂/农业"]
				$skills_str1 = json_decode(@$_REQUEST['skills']);
				foreach ($skills_str1 as $s){
					@$skills_str .= $s.",";
				}
				$skills_str = rtrim($skills_str,",");
			}
			//line_num=["11313"]
			if(isset($_REQUEST["line_num"])&&@$_REQUEST["line_num"]!=""){//線
				$ensn=json_decode(@$_REQUEST['line_num']);
				foreach($ensn as $l){
					@$line_num .= $l.",";
				}
				$ensn_str = rtrim($line_num,",");
			}
			//station_cd=["1130218"]
			if(isset($_REQUEST["station_cd"])&&@$_REQUEST["station_cd"]!=""){//駅
				$eki1=json_decode(@$_REQUEST["station_cd"]);
				foreach($eki1 as $s){
					@$station_cd .= $s.",";
					@$station_name .= getName(array("name"=>"eki","value"=>$s)).",";
				}
				$eki_str = rtrim($station_cd,",");
			}
			$eki = rtrim($station_name,",");
			
			$zip01 = cleanInput(@$_REQUEST['zip01']);
			$zip02 = cleanInput(@$_REQUEST['zip02']);
			//$add_pref = cleanInput(@$_REQUEST['add_pref']);
			$addr01 = cleanInput(@$_REQUEST['addr01']);
			$addr02 = cleanInput(@$_REQUEST['addr02']);
			$tel01 = cleanInput(@$_REQUEST['tel01']);
			$tel02 = cleanInput(@$_REQUEST['tel02']);
			$tel03 = cleanInput(@$_REQUEST['tel03']);
			$tel_flag = cleanInput(@$_REQUEST['tel_flag']);//电话是否公开
			$last_education = cleanInput(@$_REQUEST['last_education']);//学历
			//$last_education_name = getName(array("name"=>"education","value"=>$last_education));
			
			$zhuanye = cleanInput(@$_REQUEST['zhuanye']);//所学专业
			$job_experiencetimes = cleanInput(@$_REQUEST['job_experiencetimes']);// 就职年限
			$job_nowstatus = cleanInput(@$_REQUEST['job_nowstatus']);//现在是 在职或就职
			echo $job_nowstatus;
			$job_experience = cleanInput(@$_REQUEST['job_experience']);//就职次数
			/*if(isset(@$_REQUEST["url_photo"])&&$_REQUEST["url_photo"]!=""){
				$data = $_REQUEST["url_photo"];
				$filetype = pathinfo($data["name"], PATHINFO_EXTENSION);
				$upfiletype = strtoupper($filetype);
				if($upfiletype!="JPG"&&$upfiletype!="PNG"&&$upfiletype!="GIF"&&$upfiletype!="JPEG"){
					exit;
				}
			}*/
		
			/*//期望工作时间 day=["周日夜班","周五夜班","周六夜班"]
			$day="";
			if(isset($_REQUEST["day"])&&@$_REQUEST["day"]!=""){
				$day1=json_decode(@$_REQUEST["day"]);
				foreach ($day1 as $k=>$v){
					$day .= $v.",";
				}
				$day = rtrim($day,",");
			}
			
			//个人简历更新
			$return = personal_updateApp($userid,$name01,$name02,$zip01,$zip02,$add_pref,$addr01,$addr02,$eki,$email,$per_status,$tel01,$tel02,$tel03,$tel_flag,$sex,$birth,$zhuanye,$last_education,$job_experiencetimes,$job_experience,$job_nowstatus,$zige,$pr,$areaid,$pref,$kinds,$types_str,$skills_str,$ensn_str,$eki_str,$kindsname,$typesname_str,$join,$day);
			if(!$return){
				$message=["false","修改失败"];
				exit(json_encode($message));
			}else{
				$image_name=json_decode(@$_REQUEST["url_photo"]);
				company_img_update($userid,"upload/img/user/".$image_name);
				$message=["true","发表成功"];
				exit(json_encode($message));
			}
			
		}elseif($update_type="photo")//修改头像信息
		{
			$image_name=@$_REQUEST['image_name'];
			if($user_type=='user')
			{
				$message=user_img_update($userid,"upload/img/user/".$image_name);
			}elseif ($user_type=='company') {
				$message=company_img_update($userid,"upload/img/compangy/".$image_name);
			}		
			if($message){
				$message=["true","修改头像成功"];
				exit(json_encode($message));
			}else{
				$message=["false","修改头像失败"];
				exit(json_encode($message));
			}
		}else{
			$message=["false","update_type没有赋值"];
			exit(json_encode($message));
		}
	}
	/*
	//注册用户信息user_type=='user'为个人注册user_type=='company'为公司注册
	if ($do=='insert')
	{
		if($user_type=='user')
		{
			$name01 = cleanInput(@$_REQUEST['name01']);
			$name02 = cleanInput(@$_REQUEST['name02']);
			$kana01 = cleanInput(@$_REQUEST['kana01']);
			$kana02 = cleanInput(@$_REQUEST['kana02']);
			$email = cleanInput(@$_REQUEST['email']);
			$password = cleanInput(@$_REQUEST['password']);
			if($name01!=""&&$name02!=""&&$email!=""&&$password!="")
			{
				if(emailRegist($email)){
					if(registCApp($name01,$name02,$kana01,$kana02,$email,$password,APP_URL)){
						$message=["true","注册成功"];
						exit(json_encode($message));
					}else 
					{
						$error=["false","注册失败"];
						exit(json_encode($error));
					}
				}else {
					$error=["false","用户的邮箱已存在"];
					exit(json_encode($error));
				}
			}else{
				$error=["false","用户姓名或邮箱或密码不能为空"];
				exit(json_encode($error));
			}
		}
		elseif ($user_type=='company')
		{
			$Cemail = cleanInput(@$_REQUEST['email']);
			if(emailRegist($Cemail) && $Cemail!=""){
				//处理参数
				$company_name = cleanInput(@$_REQUEST['company_name']);
				$company_manager = cleanInput(@$_REQUEST['company_manager']);
				//处理設立年月
				$foundation_dateYear = cleanInput(@$_REQUEST['foundation_dateYear']);
				$foundation_dateMonth = cleanInput(@$_REQUEST['foundation_dateMonth']);
				if($foundation_dateYear!=""){
					$foundation_dateYear = $foundation_dateYear." 年 ";
				}
				if($foundation_dateMonth!=""){
					$foundation_dateMonth = $foundation_dateMonth." 月 ";
				}
				$foundation_date = $foundation_dateYear.$foundation_dateMonth;
					
				$company_money = cleanInput(@$_REQUEST['company_money']);
				$company_url = cleanInput(@$_REQUEST['company_url']);
				$zip01 = cleanInput(@$_REQUEST['zip01']);
				$zip02 = cleanInput(@$_REQUEST['zip02']);
			
				$addr01 = cleanInput(@$_REQUEST['addr01']);
				$addr02 = cleanInput(@$_REQUEST['addr02']);
					
				$areaid = cleanInput(@$_REQUEST['area_cd']);
				$pref = cleanInput(@$_REQUEST['pref_cd']);
				$add_pref = getName(array("name"=>"pref","value"=>$pref));
				//$company_form="";
			
				$kinds = "";
				$kindsname = "";
				$types_str = "";
				$typesname_str = "";
				//skills_str=["派遣/中介","工厂/农业"]
				$skills_str1 = json_decode(@$_REQUEST['skills_str']);
				foreach ($skills_str1 as $s){
					@$skills_str .= $s.",";
				}
				$skills_str = rtrim($skills_str,",");
				if(isset($_REQUEST["line_num"])&&@$_REQUEST["line_num"]!=""){//線
					$ensn=json_decode(@$_REQUEST['line_num']);
					foreach($ensn as $l){
						@$line_num .= $l.",";
					}
					$ensn_str = rtrim($line_num,",");
					$ensn_str = cleanInput($ensn_str);
					
				}
				if(isset($_REQUEST["station_cd"])&&@$_REQUEST["station_cd"]!=""){//駅
					$eki=json_decode(@$_REQUEST["station_cd"]);
					foreach($eki as $s){
						@$station_cd .= $s.",";
					}
					$eki_str = rtrim($station_cd,",");
					$eki_str = cleanInput($eki_str);
				}	
				$tel01 = cleanInput(@$_REQUEST['tel01']);
				$tel02 = cleanInput(@$_REQUEST['tel02']);
				$tel03 = cleanInput(@$_REQUEST['tel03']);
				$fax01 = cleanInput(@$_REQUEST['fax01']);
				$fax02 = cleanInput(@$_REQUEST['fax02']);
				$fax03 = cleanInput(@$_REQUEST['fax03']);
				$company_email =$Cemail;
				$password = cleanInput(@$_REQUEST['password']);
				
				//echo $company_name.$company_manager.$foundation_date.$company_money.$company_url.$zip01.$zip02.$add_pref.$addr01.$addr02.$company_form.$tel01.$tel02.$tel03.$fax01.$fax02.$fax03.$company_email.$password.$areaid.$pref.$kinds.$kindsname.$types_str.$typesname_str.$skills_str.$ensn_str.$eki_str;
				if(companyregistCApp($company_name,$company_manager,$foundation_date,$company_money,$company_url,$zip01,$zip02,$add_pref,$addr01,$addr02,$company_form,$tel01,$tel02,$tel03,$fax01,$fax02,$fax03,$company_email,$password,$areaid,$pref,$kinds,$kindsname,$types_str,$typesname_str,$skills_str,$ensn_str,$eki_str)){
					$message=["true","注册成功"];
					var_dump($message);
					//exit(json_encode($message));
				}else{
					$message=["false","注册失败"];
					var_dump($message);
				}
			}else{
				$error=["false","用户的邮箱已存在或为空"];
				exit(json_encode($error));
			}
		
		}
		else
		{
			exit(json_encode(false));
		}
	}
	*/
	##########################################
	# regist.php 个人注册
	##########################################
	function registCApp($name01,$name02,$kana01,$kana02,$email,$password,$URL){
		global $db;
		$name01 = $db->real_escape_string($name01);
		$name02 = $db->real_escape_string($name02);
		$kana01 = $db->real_escape_string($kana01);
		$kana02 = $db->real_escape_string($kana02);
		$email = $db->real_escape_string($email);
		$password = $db->real_escape_string($password);
	
		$user_id = date("dHis").rand(1000,9999);
		$create_date = date("Y-m-d H:i:s");
		$password = md5($password);
		$ipd = getIP();
		
		$sql = "insert into dtb_user
		(user_id,name01,name02,points,kana01,kana02,email,password,status,user_photo_url,create_date,ip)
		values
		('$user_id','$name01','$name02',50,'$kana01','$kana02','$email','$password',0,'upload/img/1.png','$create_date','$ipd')";
		if($db->Execute($sql)){
			//登陆后存入session
			/*$_SESSION['kiwa_userid'] = $user_id;
			$_SESSION['kiwa_username'] = $name01." ".$name02;*/
			//发送站内短信
			$message_title="系统消息";
			$message_content="尊敬的用户，您好！\r\n欢迎使用帮帮网平台！\r\n此为系统自动送信，请勿返信，谢谢合作！";
			message_send_do("000001",$user_id,$message_title,$message_content);
			return true;
		}else{
			return false;
		}
		
	}
	##########################################
	# regist.php 企业注册
	##########################################
	function companyregistCApp($company_name,$company_manager,$foundation_date,$company_money,$company_url,$zip01,$zip02,$add_pref,$addr01,$addr02,$company_form,$tel01,$tel02,$tel03,$fax01,$fax02,$fax03,$company_email,$password,$areaid,$pref,$kinds,$kindsname,$types_str,$typesname_str,$skills_str,$ensn_str,$eki_str){
		global $db;
		$company_name = $db->real_escape_string($company_name);
		$company_manager = $db->real_escape_string($company_manager);
		$foundation_date = $db->real_escape_string($foundation_date);
		$company_money = $db->real_escape_string($company_money);
		$company_url = $db->real_escape_string($company_url);
		$zip01 = $db->real_escape_string($zip01);
		$zip02 = $db->real_escape_string($zip02);
		$pref_cd = $db->real_escape_string($pref);
		$addr01 = $db->real_escape_string($addr01);
		$addr02 = $db->real_escape_string($addr02);
	
		$areaid = $db->real_escape_string($areaid);
		$pref = $db->real_escape_string($add_pref);
		$kinds = $db->real_escape_string($kinds);
		$kindsname = $db->real_escape_string($kindsname);
		$types_str = $db->real_escape_string($types_str);
		$typesname_str = $db->real_escape_string($typesname_str);
		$skills_str = $db->real_escape_string($skills_str);
		$ensn_str = $db->real_escape_string($ensn_str);
		$eki_str = $db->real_escape_string($eki_str);
	
		$tel01 = $db->real_escape_string($tel01);
		$tel02 = $db->real_escape_string($tel02);
		$tel03 = $db->real_escape_string($tel03);
		$fax01 = $db->real_escape_string($fax01);
		$fax02 = $db->real_escape_string($fax02);
		$fax03 = $db->real_escape_string($fax03);
		$company_email = $db->real_escape_string($company_email);
		$password = $db->real_escape_string($password);
	
		$company_id = date("dHis").rand(1000,9999);
		$create_date = date("Y-m-d H:i:s");
		$password = md5($password);
		@$company_form = implode("_",$company_form);
	
		if($addr01!=""){
			$info_flg=1;
		}else{
			$info_flg=0;
		}
		$ipd = getIP();
		$sql = "insert into dtb_companyuser
		(company_id,company_name,company_manager,points,zip01,zip02,pref,addr01,addr02,tel01,tel02,tel03,fax01,fax02,fax03,company_form,foundation_date,company_money,company_email,password,company_url,status,create_date,skill,kindsID,kindsname,typesID,typesname,area_cd,pref_cd,line_num,station_cd,info_flg,ip)
		values
		('$company_id','$company_name','$company_manager',50,'$zip01','$zip02','$pref','$addr01','$addr02','$tel01','$tel02','$tel03','$fax01','$fax02','$fax03','$company_form','$foundation_date','$company_money','$company_email','$password','$company_url',0,'$create_date','$skills_str','$kinds','$kindsname','$types_str','$typesname_str','$areaid','$pref_cd','$ensn_str','$eki_str',$info_flg,'$ipd')";
		if($db->Execute($sql)){
			//登陆后存入session
			//$_SESSION['kiwa_companyid'] = $company_id;
			//$_SESSION['kiwa_companyname'] = $company_name;
			$message_title="系统消息";
			$message_content="尊敬的用户，您好！\r\n欢迎使用帮帮网平台！\r\n此为系统自动送信，请勿返信，谢谢合作！";
			message_send_do("000001",$company_id,$message_title,$message_content);
			//积分处理
			if($info_flg==1){
				pointsDo($company_id, "company", 50);
			}
			return true;
		}else{
			return false;
		}
		
	}
	##########################################
	# login.php データベースを照合
	##########################################
	function loginCApp($email,$password){
		global $db;
		//核对数据库---成功:set_Session,失败:返回
		$email = $db->real_escape_string($email);
		$password = $db->real_escape_string($password);
	
		//密码加密
		$password = md5($password);
		// status : 登陆flag,0:假登陆1:真登陆   del_flg : 删除flag
		$sql_user = "select user_id,name01,name02,status,login_date
		from `dtb_user`
		where `email` = '$email'
		and `password` = '$password'
		and `del_flg` = 0";
	
		$sql_company = "select company_id,company_name,status,login_date
		from `dtb_companyuser`
		where `company_email` = '$email'
		and `password` = '$password'
		and `del_flg` = 0";
		$ipd = getIP();
		if($db->QueryRow($sql_user)){//★判断个人
			$array = $db->QueryRow($sql_user);
			//积分处理
			$today = date("Y-m-d");
			if($array['login_date'] != $today){
				pointsDo($array['user_id'], "user", 10);
				$sql_u="update dtb_user set login_date='$today',ip='$ipd' where user_id='$array[user_id]'";
				$db->Execute($sql_u);
			}
			$array["usertype"]="user";
			return $array;
		}elseif($db->QueryRow($sql_company)){//★判断企业
			$array = $db->QueryRow($sql_company);
			//积分处理
			$today = date("Y-m-d");
			if($array['login_date'] != $today){
					pointsDo($array['company_id'], "company", 10);
					$sql_u="update dtb_companyuser set login_date='$today',ip='$ipd' where company_id='$array[company_id]'";
					$db->Execute($sql_u);
			}
			$array["usertype"]="company";
			return $array;
		}else{
					return false;
		}
	}
	##########################################
	# personal.php 个人简历更新
	##########################################
	function personal_updateApp($user_id,$name01,$name02,$zip01,$zip02,$add_pref,$addr01,$addr02,$eki,$email,$per_status,$tel01,$tel02,$tel03,$tel_flag,$sex,$birth,$zhuanye,$last_education,$job_experiencetimes,$job_experience,$job_nowstatus,$zige,$mypr,$areaid,$pref,$kinds,$types_str,$skills_str,$ensn_str,$eki_str,$kindsname,$typesname_str,$join,$day){
		global $db;
		$user_id = $db->real_escape_string($user_id);
		$name01 = $db->real_escape_string($name01);
		$name02 = $db->real_escape_string($name02);
		$zip01 = $db->real_escape_string($zip01);
		$zip02 = $db->real_escape_string($zip02);
		$add_pref = $db->real_escape_string($add_pref);
		$addr01 = $db->real_escape_string($addr01);
		$addr02 = $db->real_escape_string($addr02);
		$eki = $db->real_escape_string($eki);
		$email = $db->real_escape_string($email);
		$per_status = $db->real_escape_string($per_status);
		$tel01 = $db->real_escape_string($tel01);
		$tel02 = $db->real_escape_string($tel02);
		$tel03 = $db->real_escape_string($tel03);
		$tel_flag = $db->real_escape_string($tel_flag);
		$sex = $db->real_escape_string($sex);
		$birth = $db->real_escape_string($birth);
		$zhuanye = $db->real_escape_string($zhuanye);
		$last_education = $db->real_escape_string($last_education);
		$job_experiencetimes = $db->real_escape_string($job_experiencetimes);
		$job_experience = $db->real_escape_string($job_experience);
		$job_nowstatus = $db->real_escape_string($job_nowstatus);
		$zige = $db->real_escape_string($zige);
		$mypr = $db->real_escape_string($mypr);
		$day = $db->real_escape_string($day);
		$areaid = $db->real_escape_string($areaid);
		$pref = $db->real_escape_string($pref);
		$kinds = $db->real_escape_string($kinds);
		$types_str = $db->real_escape_string($types_str);
		$skills_str = $db->real_escape_string($skills_str);
		$ensn_str = $db->real_escape_string($ensn_str);
		$eki_str = $db->real_escape_string($eki_str);
		$kindsname = $db->real_escape_string($kindsname);
		$typesname_str = $db->real_escape_string($typesname_str);
		//积分处理
		$sql_s = "select info_flg,user_photo_url from dtb_user where user_id='$user_id'";
		$row = $db->QueryRow($sql_s);
		if($row["info_flg"] == 0){
			pointsDo($user_id, "user", 50);
		}
		//默认头像
		if($sex == 2&&$row["user_photo_url"]=="upload/img/1.png"){
			$photo = "upload/img/2.png";
			$sqlp = ",user_photo_url='$photo' ";
		}else{
			$sqlp = "";
		}
		$now = date("Y-m-d H:i:s");
		$sql = "update dtb_user set name01='$name01',name02='$name02',zip01='$zip01',zip02='$zip02',pref='$add_pref',addr01='$addr01',addr02='$addr02',eki='$eki',email='$email',per_status=$per_status,
		tel01='$tel01',tel02='$tel02',tel03='$tel03',tel_flag=$tel_flag,sex=$sex,birth='$birth',zhuanye='$zhuanye',last_education='$last_education',want_day='$day',
		job_experiencetimes='$job_experiencetimes',job_experience='$job_experience',job_nowstatus=$job_nowstatus,zige='$zige',mypr='$mypr',update_date='$now',
		skill='$skills_str',kindsID='$kinds',kindsname='$kindsname',typesID='$types_str',typesname='$typesname_str',area_cd='$areaid',pref_cd='$pref',line_num='$ensn_str',station_cd='$eki_str',info_flg=$join $sqlp
		where user_id='$user_id' and del_flg = 0";
		echo $sql;
		if($db->Execute($sql)){
		$message_title="推荐工作，尽在【我的地盘】";
				$message_content="尊敬的用户，您好！\r\n欢迎使用帮帮网平台！\r\n请您关注【我的地盘】，这里可能有您适合的工作信息！\r\n
				详情请点击 -> http://8n8n.co.jp/mypage/";
	
					message_send_do("000001",$user_id,$message_title,$message_content);
					return true;
		}else{
		return false;
		}
	}
	##########################################
	# company_update.php 修改企业简历
	##########################################
	function company_updateApp($company_id,$company_manager,$foundation_date,$company_money,$right_email,$per_status,$company_url,$zip01,$zip02,$add_pref,$addr01,$addr02,$tel01,$tel02,$tel03,$fax01,$fax02,$fax03,$employees_num,$company_text,$areaid,$pref,$kinds,$types_str,$skills_str,$ensn_str,$eki_str,$kindsname,$typesname_str){
		global $db;
		$company_manager = $db->real_escape_string($company_manager);
		$foundation_date = $db->real_escape_string($foundation_date);
		$company_money = $db->real_escape_string($company_money);
	
		$right_email = $db->real_escape_string($right_email);
		$per_status = $db->real_escape_string($per_status);
		$company_url = $db->real_escape_string($company_url);
		$zip01 = $db->real_escape_string($zip01);
		$zip02 = $db->real_escape_string($zip02);
		$add_pref = $db->real_escape_string($add_pref);
		$addr01 = $db->real_escape_string($addr01);
		$addr02 = $db->real_escape_string($addr02);
	
		$tel01 = $db->real_escape_string($tel01);
		$tel02 = $db->real_escape_string($tel02);
		$tel03 = $db->real_escape_string($tel03);
		$fax01 = $db->real_escape_string($fax01);
		$fax02 = $db->real_escape_string($fax02);
		$fax03 = $db->real_escape_string($fax03);
	
		$areaid = $db->real_escape_string($areaid);
		$pref = $db->real_escape_string($pref);
		$kinds = $db->real_escape_string($kinds);
		$types_str = $db->real_escape_string($types_str);
		$kindsname = $db->real_escape_string($kindsname);
		$typesname_str = $db->real_escape_string($typesname_str);
		$skills_str = $db->real_escape_string($skills_str);
		$ensn_str = $db->real_escape_string($ensn_str);
		$eki_str = $db->real_escape_string($eki_str);
	
		$employees_num = $db->real_escape_string($employees_num);
		$company_text = $db->real_escape_string($company_text);
	
		$now = date("Y-m-d H:i:s");
		//对邮件,密码进行判断
		$sql = "update dtb_companyuser set company_manager='$company_manager',foundation_date='$foundation_date',company_money=$company_money,zip01='$zip01',zip02='$zip02',pref='$add_pref',addr01='$addr01',addr02='$addr02',
		employees_num='$employees_num',company_url='$company_url',company_text='$company_text',
		right_email='$right_email',per_status=$per_status,tel01='$tel01',tel02='$tel02',tel03='$tel03',fax01='$fax01',
		fax02='$fax02',fax03='$fax03',update_date='$now',kindsID='$kinds',kindsname='$kindsname',typesID='$types_str',
		typesname='$typesname_str',area_cd='$areaid',pref_cd='$pref',line_num='$ensn_str',station_cd='$eki_str',skill='$skills_str',info_flg=1
		where company_id='$company_id' and del_flg = 0";
		echo $sql;
		if($db->Execute($sql)){
			/*if($row['info_flg']==0){
				pointsDo($company_id,"company",50);
			}*/
			return true;
		}else{
			return false;
		}
	}