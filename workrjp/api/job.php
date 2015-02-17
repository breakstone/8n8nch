<?php

	if(!file_exists('../_config/config.php'))
	{
		die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	$do=@$_REQUEST['do']? $_REQUEST['do'] : '';// 判断所得信息类型
	$jobid=@$_REQUEST['jobid']? $_REQUEST['jobid'] : '';//获取热门工作信息
	$rmgz=@$_REQUEST['rmgz']? $_REQUEST['rmgz'] : '';//获取热门工作信息
	$hopedate=@$_REQUEST['hopedate']? $_REQUEST['hopedate'] : '';//获取希望工作时间
	$money=@$_REQUEST['money']? $_REQUEST['money'] : '';//获取薪资标准
	$employ=@$_REQUEST['employ']? $_REQUEST['employ'] : '';//获取雇用形式
	$condition=@$_REQUEST['condition']? $_REQUEST['condition'] : '';//获取その他的数据
	$yz=@$_REQUEST['yz']? $_REQUEST['yz'] : '';//获取业种
	$kindsid=@$_REQUEST['kindsid']? $_REQUEST['kindsid'] : '';//获取职种
	$area_cd="";
	$pref_cd="";
	$where="";
	//joblb==all 获取业种信息
	if ($do=='getyz')
	{
		if($yz=='all')
		{
			//var_dump(getCompanyfrom());
			exit(json_encode(getCompanyfrom()));
		}
		else
		{
			exit(json_encode(false));
		}
	}
	if ($do=='getzz')
	{
		if($kindsid)
		{
			//var_dump(getTypes($kindsid));
			exit(json_encode(getTypes($kindsid)));
		}
		else
		{
			exit(json_encode(false));
		}
	}
	
	//joblb==all 获取热门工作信息
	if ($do=='getrmgz')
	{
		if($rmgz=='all')
		{
			$sql_jb = "select biao_name from mtb_job_biao";
			$biao = $db->QueryArray($sql_jb);
			exit(json_encode($biao));
		}
		else
		{
			exit(json_encode(false));
		}
	}
	//获取希望工作时间
	if ($do=='gethopedate')
	{
		if($hopedate=='all')
		{
			//var_dump(getHope_date());
			exit(json_encode(getHope_date()));
		}
		else
		{
			exit(json_encode(false));
		}
	}
	//获取薪资标准
	if ($do=='getmoney')
	{
		if($money=='all')
		{
			//var_dump(getMoney());
			exit(json_encode(getMoney()));
		}
		else
		{
			exit(json_encode(false));
		}
	}
	//获取雇用形式
	if ($do=='getemploy')
	{
		if($employ=='all')
		{
			//var_dump(getEmploy_method());
			exit(json_encode(getEmploy_method()));
		}
		else
		{
			exit(json_encode(false));
		}
	}
	//获取その他的数据
	if ($do=='getcondition')
	{
		if($condition=='all')
		{
			//var_dump(getCondition());
			exit(json_encode(getCondition()));
		}
		else
		{
			exit(json_encode(false));
		}
	}
	if($do=='search')
	{
		if(isset($_REQUEST['keyword'])){//★点击keyword查询按钮
			if($_REQUEST['keyword']!=""){//
				$keyword = cleanInput($_REQUEST['keyword']);
				$where = "(job_title like '%$keyword%' or company_name like '%$keyword%' or contents like '%$keyword%')";
			}
		}
		//hopeid=["h1","h2"]&employid=["1","2"]&shok3=["1","2"]&biaoname=厨师类
		if(isset($_REQUEST["biaoname"])){
				$biaoname = $_REQUEST["biaoname"];
				$where .= "level like '%$biaoname%'";
		}
		//area_cd=a2&pref_cd=11&eki=["2100111","2100112"]
		if(@$_REQUEST['area_cd']!=""){//地域有选择
			//sql--------------------------------
			if($where != "")$where .=" and ";
			$where .= "area_cd = '$_REQUEST[area_cd]'";
				
			$area_cd = $_REQUEST['area_cd'];
		}
		if(@$_REQUEST['pref_cd']!=""){//都道府県有选择
			//sql--------------------------------
			if($where != "")$where .=" and ";
			$where .= "pref_cd = '$_REQUEST[pref_cd]'";
			//$pref_cd = $_REQUEST['pref_cd'];
		}
		if((isset($_REQUEST["ensn"])&&$_REQUEST["ensn"]!="") ){//線
			$ensn =json_decode($_REQUEST["ensn"]);
			//sql--------------------------------
			if($where != "")$where .=" and (";
			foreach($ensn as $l){
				$where .= "line_num like '%$l%'";
				$where .= " or ";
			}
			$where = rtrim($where,"or ");
			$where .= " or (line_num is null and station_cd is null)) ";
		}
		if((isset($_REQUEST["eki"])&&$_REQUEST["eki"]!="")){//駅
			$eki =json_decode($_REQUEST["eki"]);
			//sql--------------------------------
			if($where != "")$where .=" and (";
			foreach($eki as $e){
				//如果线路里的车站做处理
				$line_eki = substr($e,0,5);
				$where .= "station_cd like '%$e%'";
				$where .= " or ";
				$where .= "line_num like '%$line_eki%'";
				$where .= " or ";
			}
			$where = rtrim($where,"or ");
			$where .= " or (line_num is null and station_cd is null)) ";
		}
		//kindsID=k7&types=["t67","t68"]
		if((isset($_REQUEST["kindsID"]))&&$_REQUEST['kindsID']!="")
		{		
			//sql--------------------------------
			if($where != "")$where .=" and ";
			$where .= "kindsID = '$_REQUEST[kindsID]'";
		}
		if((isset($_REQUEST["types"]))&&$_REQUEST['types']!="")
		{
			$types = json_decode($_REQUEST["types"]);
			if($where != "")$where .=" and (";
			foreach($types as $k){
				$where .= "typesID like '%$k%'";
				$where .= " or ";
			}
			$where = rtrim($where,"or ");
			$where .= ") ";
		}
		//moneyid=m2
		if(@$_REQUEST['moneyid']!=""){//給付有选择
			//sql--------------------------------
			if($where != "")$where .=" and ";
			$where .= "moneyid = '$_REQUEST[moneyid]'";
		}
		//employid=["1","2"]
		if(@$_REQUEST['employid']!=""){//雇用形態有选择
			$employid= json_decode($_REQUEST['employid']);
			//sql--------------------------------
			if($where != ""){$where .=" and (";}else{$where = "(";};
			foreach($employid as $e){
				$where .= "employed_method like '%$e%'";
				$where .= " or ";
			}
			$where = rtrim($where,"or ");
			$where .= ") ";
		}
		//hopeid=["h1","h2"]
		if(@$_REQUEST['hopeid']!=""){//就業希望期間有选择
			$hopeid=json_decode($_REQUEST['hopeid']);
			//sql--------------------------------
			if($where != ""){$where .=" and (";}else{$where = "(";};
			foreach($hopeid as $h){
				$where .= "hope_date like '%$h%'";
				$where .= " or ";
			}
			$where = rtrim($where,"or ");
			$where .= ") ";
		}
		//shok3=["1","2"]
		if(isset($_REQUEST['shok3'])&&$_REQUEST['shok3']!=""){//その他有选择
			$shok3=json_decode($_REQUEST['shok3']);
			//sql--------------------------------
			foreach($shok3 as $v){
				$v = cleanInput($v);
				if($where != "")$where .=" and ";
				$where .= "condition_id like '%$v%'";
			}
		}
		$all_nums = getObjectNum("dtb_jobs",$where);
		$jobs = getJobs($where,0,$all_nums);
		//var_dump($jobs);
		exit(json_encode($jobs));
	}
	if(@$do=='show')
	{
		if($jobid)
		{
			//var_dump(showJob($jobid));
			exit(json_encode(showJob($jobid)));
		}
		else
		{
			exit(json_encode(false));
		}
	
	}
/*	if(@$do=='del')
	{
		if($jobid){
			$userid=cleanInput(@$_REQUEST['userid']);
			$sql="select company_id from dtb_jobs where job_id='$jobid'";
			$uid = $db->QueryItem($sql);
			if($userid == $uid){
				$sql="update dtb_jobs set del_flg=1 where job_id='$jobid'";
				if($db->Execute($sql)){
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
		}else{
			$message=["false","jobid不能为空"];
			exit(json_encode($message));
		}
	}
	if(@$do=='update')
	{
		if($jobid)
		{
			
			$userid=cleanInput(@$_REQUEST['userid']);
			$username=cleanInput(@$_REQUEST['username']);
			$usertype=cleanInput(@$_REQUEST['usertype']);
			if($userid!=""&&$username!=""&&$usertype!="")
			{
				$job_id=cleanInput($jobid);
				//&usertype=user
				//	echo $usertype.'1111111'.$username.'2222'.$userid;
			
				//收费，免费
				$shoufei = cleanInput(@$_REQUEST['shoufei']);
				//echo $usertype.'111'.$shoufei;
				$job_title = $shoufei.cleanInput(@$_REQUEST['job_title']);
				$contents = str_replace("<br>","\r\n",@$_REQUEST['contents']);
				$contents = cleanInput($contents);
				$requirements = str_replace("<br>","\r\n",@$_REQUEST["requirements"]);
				$requirements = cleanInput($requirements);
				$lianxi = cleanInput(@$_REQUEST['lianxi']);
				$numbers = cleanInput(@$_REQUEST['numbers']);
				$over_date = cleanInput(@$_REQUEST['over_date']);
				$over_date = date("Y-m-d",time()+$over_date*86400);
				$level=json_decode($_REQUEST['gzbq']);
				foreach($level as $z){
						
					@$zz_name .= $z.",";
				}
				$zz_name = rtrim($zz_name,",");
				//echo $zz_name;
			
				###职种，业种处理###
				//kindsid=["t1","t5"]&kinds=k1
				$kinds = cleanInput(@$_REQUEST['kinds']);
				$kindsname = "";
				$types = "";
				$typesname = "";
				$types1=json_decode(@$_REQUEST['kindsid']);
				foreach($types1 as $t){
				$ktrow = getKindTypename($t);
					$kindsname = $ktrow["kindsname"];
					$types .= $t.",";
					$typesname .= $ktrow["typesname"].",";
				}
				$types = rtrim($types,",");
				$typesname = rtrim($typesname,",");
				//echo $types.'111'.$typesname.$kinds;
				###工作地，处理###
				//area_cd=a5&pref_cd=27
				$areaid = cleanInput(@$_REQUEST['area_cd']);
				$pref = cleanInput(@$_REQUEST['pref_cd']);
				$prefname = getName(array("name"=>"pref","value"=>$pref));
				//echo $areaid.$pref.$prefname;
				$station_cd="";
				$station_name="";
				$line_num="";
				//station_cd=["1130218","1130219"]&line_num=["11313","11312"]
				if(isset($_REQUEST["line_num"])&&@$_REQUEST["line_num"]!=""){//線
					$ensn=json_decode(@$_REQUEST['line_num']);
					foreach($ensn as $l){
						$line_num .= $l.",";
					}
					$line_num = rtrim($line_num,",");
				}
				if(isset($_REQUEST["station_cd"])&&@$_REQUEST["station_cd"]!=""){//駅
					$eki=json_decode(@$_REQUEST["station_cd"]);
					foreach($eki as $s){
						$station_cd .= $s.",";
						$station_name .= getName(array("name"=>"eki","value"=>$s)).",";
					}
					$station_cd = rtrim($station_cd,",");
					$station_name = rtrim($station_name,",");
				}
				//echo $line_num.$station_cd.$station_name;
				//moneyid=m3
				$moneyid = cleanInput(@$_REQUEST['moneyid']);
				$moneyname = getName(array("name"=>"money","value"=>$moneyid));
				//echo $moneyid.$moneyname;
				//employid=["1","5"] 雇用形式
				$employid1=json_decode(@$_REQUEST["employid"]);
				foreach($employid1 as $e){
				@$employid .= $e.",";
				@$employname .= getName(array("name"=>"employ","value"=>$e)).",";
				}
				$employid = rtrim($employid,",");
				$employname = rtrim($employname,",");
				//echo $employid.$employname;
				//获得希望工作时间里面的id赋值给hopeid 如hopeid=["h1","h5"]方式
				$hopeid=json_decode(@$_REQUEST["hopeid"]);
				foreach($hopeid as $h){
					@$hopedateid .= $h.",";
					@$hopedatename .= getName(array("name"=>"hope","value"=>$h)).",";
	
				}
				$hopedateid = rtrim($hopedateid,",");
				$hopedatename = rtrim($hopedatename,",");
				//勤務日数  为1,2,3,4,5,7
				$w_d = cleanInput(@$_REQUEST['w_d']);
	
				//echo $hopedateid.$hopedatename;
				$condition_id="";
				$condition_name="";
				//把其他条件里面的id赋值给conditionsid 如conditionsid=["1","2"]方式
				if(isset($_REQUEST["conditionsid"])&&@$_REQUEST["conditionsid"]!=""){//駅
					$conditionsid=json_decode(@$_REQUEST["conditionsid"]);
					foreach($conditionsid as $c){
					$condition_id .= $c.",";
					$condition_name .= getName(array("name"=>"condition","value"=>$c)).",";
					}
					$condition_id = rtrim($condition_id,",");
					$condition_name = rtrim($condition_name,",");
				}
				//修改工作信息
				$return = updatejobApp($job_id,$job_title,$kinds,$kindsname,$types,$typesname,$contents,$employid,$employname,$areaid,$pref,$prefname,$line_num,$station_cd,$station_name,$hopedateid,$hopedatename,$moneyid,$moneyname,$requirements,$lianxi,$numbers,$condition_id,$condition_name,$zz_name,$w_d);
				if(!$return){
					$message=["false","更新失败"];
					exit(json_encode($message));
				}else{
					$message=["true","更新成功"];
					exit(json_encode($message));;
				}
		
			}
			else
			{
				$message=["false","userid、username、usertype没有赋值"];
				exit(json_encode($message));
			}
		}
		else
		{
			$message=["false","jobid没有赋值"];
			exit(json_encode($message));
		}
	
	}
	if(@$do=='insert')
	{
			$username="";$userid="";$usertype="";
		
			$userid=cleanInput(@$_REQUEST['userid']);
			$username=cleanInput(@$_REQUEST['username']);
			$usertype=cleanInput(@$_REQUEST['usertype']);
			if($userid!=""&&$username!=""&&$usertype!="")
			{
				//&usertype=user
				//收费，免费
				$shoufei = cleanInput(@$_REQUEST['shoufei']);
				//echo $usertype.'111'.$shoufei;
				$job_title = $shoufei.cleanInput(@$_REQUEST['job_title']);
				$contents = str_replace("<br>","\r\n",@$_REQUEST['contents']);
				$contents = cleanInput($contents);
				$requirements = str_replace("<br>","\r\n",@$_REQUEST["requirements"]);
				$requirements = cleanInput($requirements);
				$lianxi = cleanInput(@$_REQUEST['lianxi']);
				$numbers = cleanInput(@$_REQUEST['numbers']);
				$over_date = cleanInput(@$_REQUEST['over_date']);
				$over_date = date("Y-m-d",time()+$over_date*86400);
				$level=json_decode($_REQUEST['gzbq']);
				foreach($level as $z){
					
					@$zz_name .= $z.",";
				}
				$zz_name = rtrim($zz_name,",");
				//echo $zz_name;
				
				###职种，业种处理###
				//kindsid=["t1","t5"]&kinds=k1
				$kinds = cleanInput(@$_REQUEST['kinds']);
				$kindsname = "";
				$types = "";
				$typesname = "";
				$types1=json_decode(@$_REQUEST['kindsid']);
				foreach($types1 as $t){
					$ktrow = getKindTypename($t);
					$kindsname = $ktrow["kindsname"];
					$types .= $t.",";
					$typesname .= $ktrow["typesname"].",";
				}
				$types = rtrim($types,",");
				$typesname = rtrim($typesname,",");
				//echo $types.'111'.$typesname.$kinds;
				###工作地，处理###
				//area_cd=a5&pref_cd=27
				$areaid = cleanInput(@$_REQUEST['area_cd']);
				$pref = cleanInput(@$_REQUEST['pref_cd']);
				$prefname = getName(array("name"=>"pref","value"=>$pref));
				//echo $areaid.$pref.$prefname;
				$station_cd="";
				$station_name="";
				$line_num="";
				//station_cd=["1130218","1130219"]&line_num=["11313","11312"]
				if(isset($_REQUEST["line_num"])&&@$_REQUEST["line_num"]!=""){//線
					$ensn=json_decode(@$_REQUEST['line_num']);
					foreach($ensn as $l){
						$line_num .= $l.",";
					}
					$line_num = rtrim($line_num,",");
				}
				if(isset($_REQUEST["station_cd"])&&@$_REQUEST["station_cd"]!=""){//駅
					$eki=json_decode(@$_REQUEST["station_cd"]);
					foreach($eki as $s){
						$station_cd .= $s.",";
						$station_name .= getName(array("name"=>"eki","value"=>$s)).",";
					}
					$station_cd = rtrim($station_cd,",");
					$station_name = rtrim($station_name,",");
				}
				//echo $line_num.$station_cd.$station_name;
				//moneyid=m3
				$moneyid = cleanInput(@$_REQUEST['moneyid']);
				$moneyname = getName(array("name"=>"money","value"=>$moneyid));
				//echo $moneyid.$moneyname;
				//employid=["1","5"] 雇用形式
				$employid1=json_decode(@$_REQUEST["employid"]);
				foreach($employid1 as $e){
					@$employid .= $e.",";
					@$employname .= getName(array("name"=>"employ","value"=>$e)).",";
				}
				$employid = rtrim($employid,",");
				$employname = rtrim($employname,",");
				//echo $employid.$employname;
				//获得希望工作时间里面的id赋值给hopeid 如hopeid=["h1","h5"]方式
				$hopeid=json_decode(@$_REQUEST["hopeid"]);
				foreach($hopeid as $h){
					@$hopedateid .= $h.",";
					@$hopedatename .= getName(array("name"=>"hope","value"=>$h)).",";
				
				}
				$hopedateid = rtrim($hopedateid,",");
				$hopedatename = rtrim($hopedatename,",");
				//勤務日数  为1,2,3,4,5,7
				$w_d = cleanInput(@$_REQUEST['w_d']);
				
				//echo $hopedateid.$hopedatename;
				$condition_id="";
				$condition_name="";
				//把其他条件里面的id赋值给conditionsid 如conditionsid=["1","2"]方式
				if(isset($_REQUEST["conditionsid"])&&@$_REQUEST["conditionsid"]!=""){//駅
					$conditionsid=json_decode(@$_REQUEST["conditionsid"]);
					foreach($conditionsid as $c){
						$condition_id .= $c.",";
						$condition_name .= getName(array("name"=>"condition","value"=>$c)).",";
					}
					$condition_id = rtrim($condition_id,",");
					$condition_name = rtrim($condition_name,",");
				}
				//echo $condition_id.$condition_name;
				$now = date("Y-d-m H:i:s");
				//发表求人处理
				$return = findpeople_addApp($userid,$username,$usertype,$now,$job_title,$kinds,$kindsname,$types,$typesname,$contents,$employid,$employname,$areaid,$pref,$prefname,$line_num,$station_cd,$station_name,$hopedateid,$hopedatename,$moneyid,$moneyname,$requirements,$lianxi,$numbers,$over_date,$condition_id,$condition_name,$zz_name,$w_d);
					
				if(!$return){
					$message=["false","发布工作失败"];
					exit(json_encode($message));
				}else{
					//进行数据匹配，推荐发送站内短信
				/*	if(isset($_REQUEST['kindsid'])&&$_REQUEST['kindsid']!=""){
						$types = $_REQUEST['kindsid'];
					}else{
						$types = "";
					}
					if(isset($_REQUEST['line_num'])&&$_REQUEST['line_num']!=""){
						$lines = $_REQUEST['line_num'];
					}else{
						$lines = "";
					}
					if(isset($_REQUEST['station_cd'])&&$_REQUEST['station_cd']!=""){
						$chezhan = $_REQUEST['station_cd'];
					}else{
						$chezhan = "";
					}
					//业种 职种 区域 市 线路 车站 薪金 雇佣形式 勤务时间
					people_recommend($kinds,$types,$areaid,$pref,$lines,$chezhan,$moneyid,$employid,$hopedateid,$job_title,APP_URL);*/
					/*$message=["true","发布工作成功"];
					exit(json_encode($message));
				}
		}
		else
		{
			$message=["false","userid、username、usertype没有赋值"];
			exit(json_encode($message));
		}
	
	}*/
	exit(json_encode(false));
	##########################################
	# findpeople_add.php 提交求人信息
	##########################################
	function findpeople_addApp($kiwa_companyid,$kiwa_companyname,$usertype,$now,$job_title,$kindsID,$kindsname,$typesID,$typesname,$contents,$employ_mothod,$employ_mothod_name,$area_cd,$pref_cd,$pref_name,$line_num,$station_cd,$station_name,$hope_date,$hope_date_name,$moneyid,$money_name,$requirements,$lianxi,$numbers,$over_date,$condition_id,$condition_name,$zz_name,$w_d){
		global $db;
		$job_id = date("is").rand(1000,9999);
		$kiwa_companyid = $db->real_escape_string($kiwa_companyid);
		$kiwa_companyname = $db->real_escape_string($kiwa_companyname);
		$kindsID = $db->real_escape_string($kindsID);
		$kindsname = $db->real_escape_string($kindsname);
		$typesID = $db->real_escape_string($typesID);
		$typesname = $db->real_escape_string($typesname);
		$contents = $db->real_escape_string($contents);
		$employ_mothod = $db->real_escape_string($employ_mothod);
		$employ_mothod_name = $db->real_escape_string($employ_mothod_name);
		$area_cd = $db->real_escape_string($area_cd);
		$pref_cd = $db->real_escape_string($pref_cd);
		$pref_name = $db->real_escape_string($pref_name);
		$line_num = $db->real_escape_string($line_num);
		$station_cd = $db->real_escape_string($station_cd);
		$station_name = $db->real_escape_string($station_name);
	
		$zz_name = $db->real_escape_string($zz_name);
		$w_d = $db->real_escape_string($w_d);
	
		$hope_date = $db->real_escape_string($hope_date);
		$hope_date_name = $db->real_escape_string($hope_date_name);
		$moneyid = $db->real_escape_string($moneyid);
		$money_name = $db->real_escape_string($money_name);
		$requirements = $db->real_escape_string($requirements);
		$lianxi = $db->real_escape_string($lianxi);
		$numbers = $db->real_escape_string($numbers);
		$condition_id = $db->real_escape_string($condition_id);
		$condition_name = $db->real_escape_string($condition_name);
		$now = date("Y-m-d H:i:s");
		$sql = "insert into dtb_jobs set job_id = '$job_id',company_id = '$kiwa_companyid',company_name='$kiwa_companyname',user_type='$usertype',information_date='$now',level='$zz_name',hope_week_day=$w_d,
		job_title='$job_title',kindsID='$kindsID',kindsname='$kindsname',typesID='$typesID',typesname='$typesname',
		contents='$contents',employed_method='$employ_mothod',employed_method_name='$employ_mothod_name',area_cd='$area_cd',pref_cd='$pref_cd',pref_name='$pref_name',line_num='$line_num',station_cd='$station_cd',station_name='$station_name',
		hope_date='$hope_date',hope_date_name='$hope_date_name',moneyid='$moneyid',money_name='$money_name',requirements='$requirements',lianxi='$lianxi',numbers='$numbers',condition_id='$condition_id',condition_name='$condition_name',over_date='$over_date',create_date='$now',answer_date='$now'";
		//echo '<br>'.$sql;
		if($db->Execute($sql)){
			/*$message_title="推荐人才，尽在【我的地盘】";
			if($usertype == "user"){
				$message_content="尊敬的用户，您好！\r\n欢迎使用帮帮网平台发布工作信息！\r\n请您关注【我的地盘】，这里可能有您要招聘的人才！\r\n
					详情请点击 -> http://8n8n.co.jp/mypage/r/";
			}else{
				$message_content="尊敬的用户，您好！\r\n欢迎使用帮帮网平台发布工作信息！\r\n请您关注【我的地盘】，这里可能有您要招聘的人才！\r\n
					详情请点击 -> http://8n8n.co.jp/companypage/";
			}
				
			message_send_do("000001",$kiwa_companyid,$message_title,$message_content);*/
			return true;
		}else{
			return false;
		}
		
	}
	function updatejobApp($job_id,$job_title,$kindsID,$kindsname,$typesID,$typesname,$contents,$employ_mothod,$employ_mothod_name,$area_cd,$pref_cd,$pref_name,$line_num,$station_cd,$station_name,$hope_date,$hope_date_name,$moneyid,$money_name,$requirements,$lianxi,$numbers,$condition_id,$condition_name,$zz_name,$w_d){
		global $db;
		$kindsID = $db->real_escape_string($kindsID);
		$kindsname = $db->real_escape_string($kindsname);
		$typesID = $db->real_escape_string($typesID);
		$typesname = $db->real_escape_string($typesname);
		$contents = $db->real_escape_string($contents);
		$employ_mothod = $db->real_escape_string($employ_mothod);
		$employ_mothod_name = $db->real_escape_string($employ_mothod_name);
		$area_cd = $db->real_escape_string($area_cd);
		$pref_cd = $db->real_escape_string($pref_cd);
		$pref_name = $db->real_escape_string($pref_name);
		$line_num = $db->real_escape_string($line_num);
		$station_cd = $db->real_escape_string($station_cd);
		$station_name = $db->real_escape_string($station_name);
			
		$zz_name = $db->real_escape_string($zz_name);
		$w_d = $db->real_escape_string($w_d);
			
		$hope_date = $db->real_escape_string($hope_date);
		$hope_date_name = $db->real_escape_string($hope_date_name);
		$moneyid = $db->real_escape_string($moneyid);
		$money_name = $db->real_escape_string($money_name);
		$requirements = $db->real_escape_string($requirements);
		$lianxi = $db->real_escape_string($lianxi);
		$numbers = $db->real_escape_string($numbers);
		$condition_id = $db->real_escape_string($condition_id);
		$condition_name = $db->real_escape_string($condition_name);
		$sql = "update dtb_jobs set level='$zz_name',hope_week_day=$w_d,
		job_title='$job_title',kindsID='$kindsID',kindsname='$kindsname',typesID='$typesID',typesname='$typesname',
		contents='$contents',employed_method='$employ_mothod',employed_method_name='$employ_mothod_name',area_cd='$area_cd',pref_cd='$pref_cd',pref_name='$pref_name',line_num='$line_num',station_cd='$station_cd',station_name='$station_name',
		hope_date='$hope_date',hope_date_name='$hope_date_name',moneyid='$moneyid',money_name='$money_name',requirements='$requirements',lianxi='$lianxi',numbers='$numbers',condition_id='$condition_id',condition_name='$condition_name' where job_id = '$job_id'";
		//echo '<br>'.$sql;
		if($db->Execute($sql)){
			return true;
		}else{
			return false;
		}
	
	}
	

