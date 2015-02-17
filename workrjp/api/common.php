<?php
	if(!file_exists('../_config/config.php'))
	{
		die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	
	$do=@$_REQUEST['do']? $_REQUEST['do'] : '';// 判断所得信息类型
	
	$area_cd=@$_REQUEST['area_cd']? $_REQUEST['area_cd'] :'';//获取区域里面的id(area_cd)值
	
	$pref_cd=@$_REQUEST['pref_cd']? $_REQUEST['pref_cd'] :'';//获取都道府県的里面的id(pref_cd)值
	
	$line_num=@$$_REQUEST['line_num']? $_REQUEST['line_num'] :'';   //车站沿线的id(line_num)值
	//获取车站沿线.
	if ($do=='update_readnum')
	{	
		
		$id=$_REQUEST['id'];
		$readnum = $_REQUEST['readnum'];
		$type= $_REQUEST['type'];
		if($id!=""&&$type!=""&&$readnum!="")
		{
			update_readnum($id,$type, $readnum);
		}else{
			$message=["false","id、readnum、type 没有赋值"];
			exit(json_encode($message));
		}
	}
	// 获取区域信息
	if ($do=='search')
	{
		$keyword=$_REQUEST['keyword'];
		$fenlei=$_REQUEST['fenlei'];
		if (empty($keyword))
		{
			$message=["false","keyword没有赋值"];
			exit(json_encode($message));
		}
		if (empty($fenlei))
		{
			$message=["false","fenlei没有赋值"];
			exit(json_encode($message));
		}
		if($fenlei=="job"){
			$where = "(job_title like '%$keyword%' or company_name like '%$keyword%' or contents like '%$keyword%')";
			//总条数
			$all_nums = getObjectNum("dtb_jobs",$where);
			$jobs = getJobs($where,0,$all_nums);
			//var_dump($jobs);
			exit(json_encode($bbs));
		}
		if($fenlei=="bbs"){
			$where = "(bbs_title like '%$_REQUEST[keyword]%' or bbs_content like '%$_REQUEST[keyword]%')";
			$all_nums = getObjectNum("dtb_bbs",$where);
			$bbs = getBbs($where,0, $all_nums);
			exit(json_encode($bbs));
		}
		if($fenlei=="live"){
			$where = "(live_title like '%$keyword%' or live_content like '%$keyword%')";
			//总条数
			$all_nums = getObjectNum("dtb_lives",$where);
			$lives = getLives($where,0,$all_nums);
			exit(json_encode($lives));
		}
	}
	// 获取区域信息
	if ($do=='getarea')
	{
		if($area_cd=='all')
		{
			$areas = getPref();
			exit(json_encode($areas));
		}
		else 
		{
			exit(json_encode(false));
		}
	}
	//获取都道府県信息
	if ($do=='getpref')
	{
		if($area_cd)
		{
			$prefOne = getPref($area_cd);
			exit(json_encode($prefOne));
		}
	}
	
	//获取车站沿线.
	if ($do=='getline')
	{
		if ($pref_cd)
		{
			$ekis=getLineApp($pref_cd);
			exit(json_encode($ekis));
		}
		
	}
	//获取车站
	if ($do=='getstation')
	{
		if ($pref_cd&&$line_num)
		{
			$ekiids=getStationApp($pref_cd,$line_num);
			exit(json_encode($ekiids));
		}
	}
	//根据各种ID  获取其name名称
	if ($do=='getname')
	{
		$type['name']=cleanInput(@$_REQUEST['typename']);
		$type['value']=cleanInput(@$_REQUEST['typeid']);
		//echo getNameApp($type);
		exit(json_encode(getNameApp($type)));
	}
	
	//根据    用户ID和用户类型    得到用户信息
	if ($do=='getuserinfo')
	{
		$userid=cleanInput(@$_REQUEST['userid']);
		$user_type=cleanInput(@$_REQUEST['user_type']);
		if (empty($user_type))
		{
			$message=["false","user_type没有赋值"];
			exit(json_encode($message));
		}
		if (empty($userid))
		{
			$message=["false","userid没有赋值"];
			exit(json_encode($message));
		}
		if($user_type == "company"){//企业用户
			$company = getCompany($userid);
			exit(json_encode($company));
			//发需求者的信息
		}elseif($user_type == "user"){//个人用户
			$user = getUser($userid);
			exit(json_encode($user));
			//发需求者的信息
		}else{
			$message=["false","user_type只能为user或company"];
			exit(json_encode($message));
		}
	}
	if ($do=='message')
	{
		$act=cleanInput(@$_REQUEST['act']);
		/*if($act=="insert"){
			$message_from = cleanInput(@$_REQUEST['message_from']);//发送者的ID
			$message_to = cleanInput(@$_REQUEST['message_to']);//接受者的ID
			$message_title = cleanInput(@$_REQUEST['message_title']);
			$message_content = cleanInput(@$_REQUEST['message_content']);
			if(empty($message_from) ||empty($message_to)){
				$message=["false","message_from和message_to不能为空"];
				exit(json_encode($message));
			}
			//发信息
			$return = message_send_do($message_from,$message_to,$message_title,$message_content);
			if($return){
				$message=["true","发送信件成功"];
				exit(json_encode($message));
			}else{
				$message=["false","发送信件失败"];
				exit(json_encode($message));
			}
		}elseif($act=="del")
		{
			$flag = cleanInput(@$_REQUEST['message_type']); //所添要操作信件的类型   是查询发送的信件（message_type=message_from）或查询所接收的信件（message_type=message_to）
			$id=cleanInput(@$_REQUEST['del_id']);
			if (empty($id))
			{
				$message=["false","del_id没有赋值"];
				exit(json_encode($message));
			}
			if (empty($flag))
			{
				$message=["false","message_type没有赋值"];
				exit(json_encode($message));
			}
			$user_id=cleanInput(@$_REQUEST['userid']);
				
			if (empty($user_id))
			{
				$message=["false","userid没有赋值"];
				exit(json_encode($message));
			}
			if($flag == "outbox"){//发送邮件
				$del_key="from_del_flg";
			}elseif($flag== "inbox"){//接收邮件
				$del_key="del_flg";
			}else{
				$message=["false","message_type赋值错误"];
				exit(json_encode($message));
			}
			$message = getMessage($id);
			//var_dump($message);
			if($message['message_from']==$user_id||$message['message_to']==$user_id){
				$return=pmessage_delete($id,$del_key);
				if($return){
					$message=["true","删除成功"];
					
					exit(json_encode($message));
				}else{
					$message=["false","删除失败"];
					exit(json_encode($message));
				}
			}else{
				$message=["false","删除失败 id不一致"];
				exit(json_encode($message));
			}
		}else*/if($act="select"){
			
			$flag = cleanInput(@$_REQUEST['message_type']); //所添要操作信件的类型   是查询发送的信件（message_type=message_from）或查询所接收的信件（message_type=message_to）
			if (empty($flag))
			{
				$message=["false","message_type没有赋值"];
				exit(json_encode($message));
			}
			$user_id=cleanInput(@$_REQUEST['userid']);//登录用户的id
			$search_userid=cleanInput(@$_REQUEST['search_userid']);//登录用户的id
				
			if (empty($user_id)&&empty($search_userid))
			{
				$message=["false","userid与search_userid没有赋值"];
				exit(json_encode($message));
			}
			
			if($flag == "outmessage"){//该用户发送给到 某个好友所有的信息
				$where = "message_from = '$user_id' and message_to = '$search_userid'";
			}elseif($flag== "inmessage"){//该用户接收给到 某个好友所有的信息
				$where = "message_to = '$user_id' and message_from = '$search_userid'";
			}else{
				$message=["false","message_type赋值错误"];
				exit(json_encode($message));
			}
			$sql = "select * from dtb_message where $where and del_flg = 0 ";
			$array = $db->QueryArray($sql);
			if($array){
				exit(json_encode($array));
			}else{
				$message=["false","没有信息"];
				exit(json_encode($message));
			}
		}
		
	}
	if ($do=='favorite')
	{
		$act=cleanInput(@$_REQUEST['act']);
		if (empty($act))
		{
			$message=["false","act没有赋值"];
			exit(json_encode($message));
		}
		$user_id=cleanInput(@$_REQUEST['userid']);
	
		if (empty($user_id))
		{
			$message=["false","userid没有赋值"];
			exit(json_encode($message));
		}
		/*if($act=="insert"){
			$favorite_id = cleanInput(@$_REQUEST['favorite_id']);//所添加信息的ID   job就是工作信息的ID 所添加用户就是用户的ID
			$flag = cleanInput(@$_REQUEST['favorite_type']); //所添加用户的类型 是收藏工作信息为job  还是添加好友为user/company  live 
			if (empty($favorite_id)||empty($flag))
			{
				$message=["false","favorite_id或favorite_type没有赋值"];
				exit(json_encode($message));
			}
			$now = date("Y-m-d H:i:s");
			$sqlsee = "select Id from dtb_favorite_list where user_id = '$user_id' and favorite_id = '$favorite_id' and del_flg = 0";
			$item = $db->QueryItem($sqlsee);
			if($item == ""){
				$sql = "insert into dtb_favorite_list set user_id = '$user_id',favorite_id = '$favorite_id',flag = '$flag',create_date='$now'";
			}elseif($item != ""){
				$message=["false","已经添加或收藏"];
				//var_dump($message);
				exit(json_encode($message));
			}
			if($db->Execute($sql)){
				$message=["true","添加或收藏成功"];
				exit(json_encode($message));
			}else{
				$message=["false","添加好友失败"];
				exit(json_encode($message));
			}
		}elseif($act=="del")
		{
			$id=cleanInput(@$_REQUEST['del_id']);
			if (empty($id))
			{
				$message=["false","del_id没有赋值"];
				exit(json_encode($message));
			}
			$where = "Id = $id";
			$favorite = getFevorite($where,0,1,"date");
			if($favorite[0]['user_id']==$user_id){
				$sql = "update dtb_favorite_list set del_flg = 1 where Id = $id and user_id = '$user_id'";
				if($db->Execute($sql)){
					$message=["true","删除成功"];
					exit(json_encode($message));
				}else{
					$message=["false","删除失败"];
					exit(json_encode($message));
				}
			}else{
				$message=["false","删除失败"];
				exit(json_encode($message));
			}
		}else*/if($act=="select"){
			$flag = cleanInput(@$_REQUEST['favorite_type']); //所添加用户的类型 是收藏工作信息为job  还是添加好友为user/company  live bbs
			if (empty($flag))
			{
				$message=["false","favorite_type没有赋值"];
				exit(json_encode($message));
			}
			$where = "user_id = '$user_id'";
			if($flag == "live"){
				$where .= "and flag = 'live'";
			}elseif($flag== "job"){
				$where .= "and flag = 'job'";
			}elseif($flag == "friend"){
				$friend_type=@$_REQUEST['friend_type'];
				if($friend_type == "user"){
					$where .= "and (flag = 'user')";
				}elseif ($friend_type == "company"){
					$where .= "and (flag = 'company')";
				}else{
					$where .= "and (flag = 'user' or flag = 'company')";
				}
			}else{
				$message=["false","favorite_type赋值错误"];
				exit(json_encode($message));
			}
			$all_nums = getObjectNum("dtb_favorite_list",$where);
			$favorite = getFevorite($where,0,$all_nums,$user_id);
			exit(json_encode($favorite));
		}
	}
	if ($do=='answer')
	{
		$id=cleanInput(@$_REQUEST['id']);
		$answer_module=cleanInput(@$_REQUEST['answer_module']);
	
		$act=cleanInput(@$_REQUEST['act']);
		
		
		if (empty($answer_module))
		{
			$message=["false","answer_module没有赋值"];
			exit(json_encode($message));
		}
		if (empty($act))
		{
			$message=["false","act没有赋值"];
			exit(json_encode($message));
		}
		
		if (empty($id))
		{
			$message=["false","所要查询的id没有赋值"];
			exit(json_encode($message));
		}
		if($answer_module == "job"){//工作
			$table='dtb_jobs_answer';
			$table_module='dtb_jobs';
			$key='job_id';
			$message_name="工作";
		}elseif($answer_module == "bbs"){//个人用户
			$table='dtb_bbs_answer';
			$table_module='dtb_bbs';
			$key='bbs_id';
			$message_name="闲聊";
		}elseif($answer_module == "live"){
			$table='dtb_lives_answer';
			$table_module='dtb_lives';
			$key='live_id';
			$message_name="生活互助";
		}
		//获取回复信息     根据  发布的ID和发布的类型job bbs 还有 live 得到
		if($act=="search"){
			$sql = "select * from $table where $key = '$id' and del_flg = 0 order by create_date";
			$answers = $db->QueryArray($sql);
			exit(json_encode($answers));
		}/*elseif($act=="insert"){
			$answer_type=cleanInput(@$_REQUEST['answer_type']);//是answerdo或answertado
			if (empty($answer_type))
			{
				$message=["false","answer_type没有赋值"];
				exit(json_encode($message));
			}
			$userid=cleanInput(@$_REQUEST['userid']);
			$re_userid=cleanInput(@$_REQUEST['re_userid']);//被回复者的ID
			
			$user_type=cleanInput(@$_REQUEST['user_type']);
			if (empty($userid)&&empty($re_userid))
			{
				$message=["false","userid没有赋值"];
				exit(json_encode($message));
			}
			if (empty($user_type))
			{
				$message=["false","user_type没有赋值"];
				exit(json_encode($message));
			}
			if($user_type!='user'&&$user_type!='company')
			{
				$message=["false","user_type只能为user或company"];
				exit(json_encode($message));
			}
			$answer_content = $_REQUEST['answer_content'];
			$id = $db->real_escape_string($id);
			$answer_content = $db->real_escape_string($answer_content);
			$create_date = date("Y-m-d H:i:s");
			if ($answer_type=="answerdo"){
				$sql = "insert into $table set $key = '$id',answer_user_id='$userid',answer_user_type='$user_type',answer_content='$answer_content',create_date='$create_date'";
				$return = $db->Execute($sql);
				if(!$return){
					$message=["false","回复失败"];
					exit(json_encode($message));
				}else{
					//回复顶贴
					$now = date("Y-m-d H:i:s");
					$sql_update = "update $table_module set answer_date='$now' where $key = '$id' and del_flg = 0";
					$db->Execute($sql_update);
					if($userid!=$re_userid){
						$message_to=$re_userid;
						$message_from = "000001";
						$message_title = "系统消息,有人回复您的".$message_name."！";
						$message_content = "用户您好，您发表的".$message_name."信息，已经有人回复！\r\n 内容: $answer_content \r\n  详细情况-> ".APP_URL."$answer_module/show/$id/";
						$now = date("Y-m-d H:i:s");
						$message_id = date("dHis").rand(1000,9999);
						$sql_m = "insert into dtb_message set message_id='$message_id', message_from='$message_from',message_to='$message_to',message_title='$message_title',message_content='$message_content',create_date='$now'";
						echo $sql_m;
						$db->Execute($sql_m);
					}
					$message=["true","回复成功"];
					exit(json_encode($message));
					//积分处理
					// pointsDo($answer_user_id, $answer_user_type, 2);
				}
			}elseif ($answer_type=="answertado"){
				if(isset($_REQUEST['fujia'])&&$_REQUEST['fujia']!=""){
					$re_answer = $db->real_escape_string($_REQUEST['fujia']);
				}else{
					$re_answer = "";
				}
				$sql = "insert into $table set $key = '$id',answer_user_id='$userid',answer_user_type='$user_type',answer_content='$answer_content',re_answer='$re_answer',create_date='$create_date'";
				$return = $db->Execute($sql);
				if(!$return){
					$message=["false","回复失败"];
					exit(json_encode($message));
				}else{
					//回复顶贴
					$now = date("Y-m-d H:i:s");
					$sql_update = "update $table_module set answer_date='$now' where $key = '$id' and del_flg = 0";
					$db->Execute($sql_update);
					if($userid!=$re_userid){
						$message_to=$re_userid;
						$message_from = "000001";
						$message_title = "系统消息,有人回复您！";
						$message_content = "用户您好，有人回复您！\r\n 内容: $answer_content \r\n  详细情况-> ".APP_URL."$answer_module/show/$id/";
						$now = date("Y-m-d H:i:s");
						$message_id = date("dHis").rand(1000,9999);
						$sql_m = "insert into dtb_message set message_id='$message_id', message_from='$message_from',message_to='$message_to',message_title='$message_title',message_content='$message_content',create_date='$now'";
						$db->Execute($sql_m);
					}
					$message=["true","回复成功"];
					exit(json_encode($message));}
					
			}else{
				$message=["false","answer_type赋值不正确"];
				exit(json_encode($message));
			}
		}*/
	}
	##########################################
	# 通用 --> 根据县得到线路
	##########################################
	function getLineApp($pref_cd){
		global $db;
		$sql = "select mtb_line.line_num,mtb_line.line_name from mtb_station,mtb_line where mtb_station.line_num = mtb_line.line_num and mtb_station.pref_cd = $pref_cd group by line_name";
		$array = $db->QueryArray($sql);
		if($array){
			return $array;
		}else{
			return false;
		}
	}
	##########################################
	# 通用 --> 根据线路得到车站
	##########################################
	function getStationApp($pref_cd,$lineid){
		global $db;
		$sql = "select station_cd,station_name from mtb_station where line_num = '$lineid' and pref_cd = $pref_cd";
		$array = $db->QueryArray($sql);
		if($array){
			return $array;
		}else{
			return false;
		}
	}
	##########################################
	# 通用 --> 根据ID得到各种名字
	##########################################
	function getNameApp($id){
		extract($id);
		global $db;
		if($name == "condition"){//条件
			$sql = "select name from mtb_condition where Id = $value";
		}
		if($name == "area"){//县城
			$sql = "select area_name from mtb_pref where pref_cd = $value";
		}
		if($name == "pref"){//县城
			$sql = "select name from mtb_pref where pref_cd = $value";
		}
		if($name == "ensn"){//线路
			$sql = "select line_name from mtb_line where line_num = $value";
		}
		if($name == "eki"){//车站
			$sql = "select station_name from mtb_station where station_cd = $value";
		}
		if($name == "kind"){//业种
			$sql = "select kindsname from mtb_kinds_types where typesID = '$value'";
		}
		if($name == "type"){//业种
			$sql = "select typesname from mtb_kinds_types where typesID = '$value'";
		}
		if($name == "money"){//薪资
			$sql = "select concat(name,moneyname) from mtb_money where moneyid = '$value'";
		}
		if($name == "employ"){//雇佣形式
			$sql = "select name from mtb_employed_method where Id = '$value'";
		}
		if($name == "hope"){//希望的工作时间
			$sql = "select name from mtb_hope_date where id = '$value'";
		}
		if($name == "education"){//学历
			$sql = "select eductionname from mtb_eduction where eductionid = '$value'";
		}
		/*if($name == "favorite"){
			$sql = "select pref_name,hope_date_name,money_name,employed_method_name from dtb_jobs where job_id = '$value'";
			$row = $db->QueryRow($sql);
			$name = "<td>$row[employed_method_name]</td><td>$row[money_name]</td>";
			return $name;
		}
		if($name == "company_favorite"){
			$sql = "select birth,kindsname,typesname,employed_method_name,pref_name,money_name from dtb_peoples where people_id = '$value'";
			$row = $db->QueryRow($sql);
			$age = date("Y-m-d")-$row['birth'];
			$name = "<td>$age 歳</td><td class='alLeft' nowrap='nowrap'>$row[typesname]</td><td>$row[pref_name]</td><td>$row[money_name]</td>";
			return $name;
		}*/
		if($name == "username"){
			$sql = "select concat(name01,name02) from dtb_user where user_id = '$value' and del_flg = 0";
		}
		if($name == "company_name"){
			$sql = "select company_name from dtb_companyuser where company_id = '$value' and del_flg = 0";
		}
		if($name == "user_photo"){
			$sql = "select user_photo_url from dtb_user where user_id = '$value' and del_flg = 0";
			$name = $db->QueryItem($sql);
			if($name == ""){
				return "upload/img/nopicture.png";
			}else{
				return $name;
			}
		}
		if($name == "company_photo"){
			$sql = "select company_photo_url from dtb_companyuser where company_id = '$value' and del_flg = 0";
			$name = $db->QueryItem($sql);
			if($name == ""){
				return "upload/img/nopicture.npg";
			}else{
				return $name;
			}
		}
		if($name == "bbs_num"){
			$sql = "select count(*) from dtb_bbs_answer where bbs_id = '$value' and del_flg = 0";
		}
		$name = $db->QueryItem($sql);
		return $name;
	}
	##########################################
	# 更新浏览数
	##########################################
	function update_readnum($id,$type,$see_page){
		global $db;
		$id = cleanInput($id);
		$id = $db->real_escape_string($id);
		$sp = $see_page+1;
		//访问数加1
		if($type == "company"){
			$table="dtb_companyuser";
			$keyid="company_id";
			$readkey="see_page";
		}elseif($type == "user"){
			$table="dtb_user";
			$keyid="user_id";
			$readkey="see_page";
		}elseif($type == "job"){
			$table="dtb_jobs";
			$keyid="job_id";
			$readkey="read_num";
		}elseif($type == "live"){
			$table="dtb_lives";
			$keyid="live_id";
			$readkey="read_num";
		}elseif($type == "bbs"){
			$table="dtb_bbs";
			$keyid="bbs_id";
			$readkey="read_num";
		}else{
			$message=["false","type 赋值不正确"];
			exit(json_encode($message));
			//return "type赋值不正确";
		}
		$sql_isp = "update $table set $readkey=$sp where $keyid = '$id'";
		echo $sql_isp;
		if ($db->Execute($sql_isp)){
			$message=["true","更新成功"];
			exit(json_encode($message));
		}else{
			$message=["false","更新失败"];
			exit(json_encode($message));
		}
	}