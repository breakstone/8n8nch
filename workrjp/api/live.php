<?php
	if(!file_exists('../_config/config.php'))
	{
		die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	$do=@$_REQUEST['do']? $_REQUEST['do'] : '';// 判断所得信息类型
	/*if($do=='insert')
	{
		$user_id=cleanInput(@$_REQUEST['userid']);
		$user_name=cleanInput(@$_REQUEST['username']);
		$user_type=cleanInput(@$_REQUEST['usertype']);
		if($user_id!=""&&$user_name!=""&&$user_type!="")
		{
			$live_id = date("is").rand(1000,9999);//需求id
			$live_title = cleanInput(@$_REQUEST["live_title"]);//需求题目
			$lianxi = cleanInput(@$_REQUEST["lianxi_type"]).":".cleanInput(@$_REQUEST["lianxi_hao"]);
			$live_content = cleanInput(@$_REQUEST["live_content"])."\n\n".$lianxi;//需求内容
			$service_id = cleanInput(@$_REQUEST["service_id"]);//需求分类id
			
			$service = getService($service_id);
			$service_name = cleanInput($service["service_name"]);//需求分类名字
			
			
			
			$live_want = cleanInput(@$_REQUEST["wanted"]);//需求供求
			
			$area_cd = cleanInput(@$_REQUEST["area_cd"]);//区域id
			$pref_cd = cleanInput(@$_REQUEST["pref_cd"]);//都县id
			$sql_pre = "select name from mtb_pref where pref_cd = '$pref_cd'";
			$pref_name = $db->QueryItem($sql_pre);//都县名字
			$line_num="";$station_name="";$station_cd="";
			if(isset($_REQUEST["line_num"])&&@$_REQUEST["line_num"]!=""){//線
				$ensn=json_decode(@$_REQUEST['line_num']);
				foreach($ensn as $l){
					$line_num .= $l.",";
				}
				$line_num = rtrim($line_num,",");//线路id
			}
			if(isset($_REQUEST["station_cd"])&&@$_REQUEST["station_cd"]!=""){//駅
				$eki=json_decode(@$_REQUEST["station_cd"]);
				foreach($eki as $s){
					$station_cd .= $s.",";
					$station_name .= getName(array("name"=>"eki","value"=>$s)).",";
				}
				$station_cd = rtrim($station_cd,",");//车站id
				$station_name = rtrim($station_name,",");//车站name
			}
			if(isset($_REQUEST["type"])&&@$_REQUEST["type"]!=""){//線
				$ensn=json_decode(@$_REQUEST['type']);
				$type1=json_decode(@$_REQUEST['type']);
				foreach ($type1 as $t){
					@$type .= $t.",";
				}
				$type = rtrim($type,",");//内容类别
			}
			if(@$_REQUEST["zhong"]!=""){
				$zhong = cleanInput(@$_REQUEST["zhong"]);//宠物种类
			}else{
				$zhong = "";
			}
			//money_max=1000&money=400
			if($service_id=="s1" or $service_id=="s2"){//内容价格
				if($live_want == 1){
					$live_money_s = @cleanInput(@$_REQUEST["money"]);
					$live_money_e = @cleanInput(@$_REQUEST["money_max"]);
				}else{
					$live_money_s = @cleanInput(@$_REQUEST["money"]);
					$live_money_e = @cleanInput(@$_REQUEST["money"]);
				}
			}else{
				$live_money_s = @cleanInput(@$_REQUEST["money"]);
				$live_money_e = @cleanInput(@$_REQUEST["money"]);
			}
			
			$home_geju = "";
		
			if(is_array(@$_REQUEST['geju'])){
				$geju=json_decode(@$_REQUEST['geju']);
				//@$_REQUEST[$geju_name]
				foreach($geju as $g){
					$home_geju .= $g.",";
				}
				$home_geju = rtrim($home_geju,",");
			}else{
				$home_geju = cleanInput(@$_REQUEST['geju']);//格局
			}
			
			if($service_id=="s1" or $service_id=="s2"){//面积
				if($live_want == 1){
					$home_mianji_s = @cleanInput(@$_REQUEST["mianji"]);
					$home_mianji_e = @cleanInput(@$_REQUEST["mianji_max"]);
				}else{
					$home_mianji_s = @cleanInput(@$_REQUEST["mianji"]);
					$home_mianji_e = @cleanInput(@$_REQUEST["mianji"]);
				}
			}
			
			$home_juli = @cleanInput(@$_REQUEST["juli"]);//车站距离
			$home_year = @cleanInput(@$_REQUEST["home_year"]);//房屋年数
			$weixiu_shangmen = @cleanInput(@$_REQUEST["weixiu_shangmen"]);//上门服务
			
			if(isset($_REQUEST["how"])&&@$_REQUEST["how"]!=""){
				$how = cleanInput(@$_REQUEST["how"]);//宠物种类
			}else{
				$how = "";
			}
			
			$create_date = date("Y-m-d H:i:s");//创建时间
			if(live_save($live_id,$live_title,$live_content,$live_want,$service_id,$service_name,$user_id,$user_name,$user_type,$area_cd,$pref_cd,$pref_name,$line_num,$station_cd,$station_name,$type,$live_money_s,$live_money_e,$create_date,$home_geju,@$home_mianji_s,@$home_mianji_e,$home_juli,$home_year,$weixiu_shangmen,$zhong,$how)){
				$live_photo1=json_decode(@$_REQUEST["url_photo"]);
				$photo_url= "upload/img/live/";
				if($live_photo1)
				{
					foreach($live_photo1 as $photo){
						@$live_photo .= $photo_url.$photo.",";
					}
				}
				@$live_photo = rtrim($live_photo,",");
				$live_photo = cleanInput($live_photo);
				live_img_update($live_id,$live_photo);
				$message=["true","发表成功"];
				exit(json_encode($message));
			}else{
				$message=["false","发表失败"];
				exit(json_encode($message));
				
			}
		}else {
			exit(json_encode(false));
		}
	}
	if($do=='del')
	{
		if(@$_REQUEST['liveid']){
			$id=$_REQUEST['liveid'];
			$userid=cleanInput(@$_REQUEST['userid']);
			$sql="select user_id from dtb_lives where live_id='$id'";
			$uid = $db->QueryItem($sql);
			if($userid == $uid){
				$sql="update dtb_lives set del_flg=1 where live_id='$id'";
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
			$message=["false","liveid不能为空"];
			exit(json_encode($message));
		}
	}
	if($do=='update')
	{
		exit(json_encode("没有更新"));
	}*/
	if($do=='search')
	{
		$page = @$_REQUEST["service_id"];//需求分类id
		if ($page!=''){
			$where = "service_id = '$page'";
			$service = getLifeservice($page);
		}else{
			$where = "";
		}
		if(@$_REQUEST['area_cd']!=""){//地域有选择
			//sql--------------------------------
			if($where != "")$where .=" and ";
			$where .= "area_cd = '$_REQUEST[area_cd]'";
		
			$area_cd = $_REQUEST['area_cd'];
		}
		### keyword
		if(@$_REQUEST['keyword']!=""){//
			$keyword = cleanInput($_REQUEST['keyword']);
			if($where != "")$where .=" and ";
			$where .= "(live_title like '%$keyword%' or live_content like '%$keyword%')";
		}
		### 供求关系
		if(isset($_REQUEST["wanted"])&&$_REQUEST["wanted"]!=""){
			$want = $_REQUEST["wanted"];
				if($where != "")$where .=" and ";
			$where .= "live_want = $want";
		}
		### 各种类别
		if((isset($_REQUEST["type"])&&$_REQUEST["type"]!="")){
			
			$type = json_decode($_REQUEST["type"]);
				
			if(is_array($type)){
				if($where != "")$where .=" and (";
				foreach ($type as $ty){
					$where .= "type like '%$ty%' or ";
				}
				$where = rtrim($where," or ");
				$where .= ")";
			}else{
				if($where != "")$where .=" and ";
				$where .= " type = '$type' ";
			}
		}
		//宠物种类
		if(isset($_REQUEST["zhong"])&&$_REQUEST["zhong"]!=""){
			if($where != "")$where .=" and ";
			$where .= "zhong = '$_REQUEST[zhong]'";
		}
		//格局
		if((isset($_REQUEST["geju"])&&$_REQUEST["geju"]!="")){
			$geju= json_decode($_REQUEST["geju"]);
			if($where != "")$where .=" and (";
			foreach ($geju as $gj){
				$where .= "home_geju like '%$gj%' or ";
			}
			$where = rtrim($where," or ");
			$where .= ")";
		}
		### 车站距离
		if(isset($_REQUEST["juli"])&&$_REQUEST["juli"]!=""){
		if($where != "")$where .=" and ";
			$where .= "home_juli <= $_REQUEST[juli]";
		}
		### 价格范围
		if(isset($_REQUEST["money_s"])&&$_REQUEST["money_s"]!=""&&isset($_REQUEST["money_e"])&&$_REQUEST["money_e"]!=""){
			if($_REQUEST["money_e"] != 0){
			if($where != "")$where .=" and ";
			$where .= "(live_money_e <= $_REQUEST[money_e] or live_money_e = 0)";
			}
			if($_REQUEST["money_s"] != 0){
					if($where != "")$where .=" and ";
					$where .= "(live_money_s >= $_REQUEST[money_s] or live_money_s = 0)";
			}
		}
		### 房屋年数
		if(isset($_REQUEST["home_year"])&&$_REQUEST["home_year"]!=""){
			if($where != "")$where .=" and ";
				$where .= "home_year <= $_REQUEST[home_year]";
		}
		### 房屋面积
		if(isset($_REQUEST["mianji_s"])&&$_REQUEST["mianji_s"]!=""&&isset($_REQUEST["mianji_e"])&&$_REQUEST["mianji_e"]!=""){
				if($_REQUEST["mianji_e"] != 0){
				if($where != "")$where .=" and ";
				$where .= "(home_mianji_e <= $_REQUEST[mianji_e] or home_mianji_e = 0)";
				}
				if($_REQUEST["mianji_s"] != 0){
				if($where != "")$where .=" and ";
				$where .= "(home_mianji_s >= $_REQUEST[mianji_s] or home_mianji_s = 0)";
				}
		}
		### 上门服务
		if(isset($_REQUEST["weixiu_shangmen"])&&$_REQUEST["weixiu_shangmen"]!=""){
			$smfw = $_REQUEST["weixiu_shangmen"];
			if($where != "")$where .=" and ";
			$where .= "weixiu_shangmen = $smfw";
		}
		//echo $where;
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
		$all_nums = getObjectNum("dtb_lives",$where);
		$lives = getLives($where,0,$all_nums);
		exit(json_encode($lives));
	}
	if($do=='show')
	{
		if(@$_REQUEST['liveid'])
		{
			$id=$_REQUEST['liveid'];
			$live = showLive($id);
			if($live)
			{
				exit(json_encode($live));
		
			}else{
				exit(json_encode($live));
			}
		}
		else
		{
			exit(json_encode(false));
		}
	
	}
	if($do=='getfenlei')
	{
		$service_name = @$_REQUEST["funlei"];//需求分类id
		if ($service_name !=''){
			$where = "where funlei = '$service_name'";
		}else{
			$where = "";
		}
		$sql= "select * from mtb_life_service $where order by rank";
		$sheji = $db->QueryArray($sql);
		exit(json_encode($sheji));
	}
	function live_save($live_id,$live_title,$live_content,$live_want,$service_id,$service_name,$user_id,$user_name,$user_type,$area_cd,$pref_cd,$pref_name,$line_num,$station_cd,$station_name,$type,$live_money_s,$live_money_e,$create_date,$home_geju,$home_mianji_s,$home_mianji_e,$home_juli,$home_year,$weixiu_shangmen,$zhong,$how){
		global $db;
		$live_id = $db->real_escape_string($live_id);
		$live_title = $db->real_escape_string($live_title);
		$live_content = $db->real_escape_string($live_content);
		$live_content = $live_content."\n 联系我时，请说是在帮帮网上看到的，谢谢！";
		$live_want = $db->real_escape_string($live_want);
		$service_id = $db->real_escape_string($service_id);
		$service_name = $db->real_escape_string($service_name);
		$area_cd = $db->real_escape_string($area_cd);
		$pref_cd = $db->real_escape_string($pref_cd);
		$line_num = $db->real_escape_string($line_num);
		$station_cd = $db->real_escape_string($station_cd);
		$type = $db->real_escape_string($type);
		$live_money_s = $db->real_escape_string($live_money_s);
		$live_money_e = $db->real_escape_string($live_money_e);
		$home_geju = $db->real_escape_string($home_geju);
		$home_year = $db->real_escape_string($home_year);
		$weixiu_shangmen = $db->real_escape_string($weixiu_shangmen);
		$zhong = $db->real_escape_string($zhong);
		$how = $db->real_escape_string($how);
		if($live_want==""){$live_want='null';}
		if($weixiu_shangmen==""){$weixiu_shangmen='null';}
			$ipd = getIP();
			$sql="insert into dtb_lives set live_id='$live_id',live_title='$live_title',live_content='$live_content',live_want=$live_want,service_id='$service_id',service_name='$service_name',user_id='$user_id',user_name='$user_name',user_type='$user_type',
			area_cd='$area_cd',pref_cd='$pref_cd',pref_name='$pref_name',line_num='$line_num',station_cd='$station_cd',station_name='$station_name',type='$type',live_money_s='$live_money_s',live_money_e='$live_money_e',create_date='$create_date',
			home_geju='$home_geju',home_mianji_s='$home_mianji_s',home_mianji_e='$home_mianji_e',home_juli='$home_juli',home_year='$home_year',weixiu_shangmen=$weixiu_shangmen,zhong='$zhong',how='$ipd'";
	
			if($db->Execute($sql)){
				pointsDo($user_id,$user_type,10);
				return true;
			}else{
				return false;
			}
	
			
	}