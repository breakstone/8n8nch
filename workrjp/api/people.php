<?php
	if(!file_exists('../_config/config.php'))
	{
		die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	$do=@$_REQUEST['do']? $_REQUEST['do'] : '';// 判断所得信息类型
	$user_type=@$_REQUEST["user_type"];
	if (empty($user_type))
	{
		$message=["false","user_type没有赋值"];
		exit(json_encode($message));
	}

	if($do=='show')
	{
		if(@$_REQUEST['id'])
		{
			$id=$_REQUEST['id'];
			if($user_type == "company"){//企业用户
				$company = getCompany($id);
				exit(json_encode($company));
			}elseif($user_type == "user"){//个人用户
				$user = getUser($id);
				exit(json_encode($user));
			}else{
				$message=["false","user_type只能为user或company"];
				exit(json_encode($message));
			}
		}
		else
		{
			exit(json_encode(false));
		}
	}
	if($do=='search')
	{
		$where = "";
		#####################################################
		# 人才热门分类
		#####################################################
		if(isset($_REQUEST["biaoname"])){
			if($_REQUEST["biaoname"]!=""){
				$biaoname = $_REQUEST["biaoname"];
				if($where != "")$where .=" and ";
				$where .= "skill like '%$biaoname%'";
			}
		}
		if(isset($_REQUEST["qiname"])){
			if($_REQUEST["qiname"]!=""){
				$qiname = $_REQUEST["qiname"];
				if($where != "")$where .=" and ";
				$where .= "skill like '%$qiname%'";
			}
		}
		#####################################################
		# 区域/路线
		#####################################################
		//area_cd=a2&pref_cd=11
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
		}
		//line_num=["11332","11333"]&station_cd=["1130218","1130219"]
		if((isset($_REQUEST["line_num"])&&$_REQUEST["line_num"]!="") ){//線
			$ensn =json_decode($_REQUEST["line_num"]);
			//sql--------------------------------
			if($where != "")$where .=" and (";
			foreach($ensn as $l){
				$where .= "line_num like '%$l%'";
				$where .= " or ";
			}
			$where = rtrim($where,"or ");
			$where .= " or (line_num is null and station_cd is null)) ";
		}
		if((isset($_REQUEST["station_cd"])&&$_REQUEST["station_cd"]!="")){//駅
			$eki =json_decode($_REQUEST["station_cd"]);
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
		if($where != "")$where .=" and ";
		$where .= "info_flg = 1 ";
		if($user_type == "company"){//企业用户
			$all_nums = getObjectNum("dtb_companyuser",$where);
			$companyuser = getAllcompanyuser($where,0,$all_nums);
			
			exit(json_encode($companyuser));
			//发需求者的信息
		}elseif($user_type == "user"){//个人用户
			$all_nums = getObjectNum("dtb_user",$where);
			$user = getAlluser($where,0,$all_nums);
			exit(json_encode($user));
			//发需求者的信息
		}else{
			$message=["false","user_type只能为user或company"];
			exit(json_encode($message));
		}
	}