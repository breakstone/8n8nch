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
		$userid=cleanInput(@$_REQUEST['userid']);
		$usertype=cleanInput(@$_REQUEST['usertype']);
		if($userid!=""&&$usertype!="")
		{
			$now = date("Y-m-d H:i:s");
			$bbs_id = date("is").rand(1000,9999);
			@$title = cleanInput($_REQUEST["title"]);
			$title = $db->real_escape_string($title);
			@$content = $db->real_escape_string($_REQUEST["content"]);
			@$bbs_type = $db->real_escape_string($_REQUEST["fenlei"]);
			
			
			$bbs_photo1=json_decode(@$_REQUEST["bbs_photo"]);
			$photo_url= "upload/img/bbs/";
			if($bbs_photo1)
			{
				foreach($bbs_photo1 as $photo){
					@$bbs_photo .= $photo_url.$photo.",";
				}
			}
			@$bbs_photo = rtrim($bbs_photo,",");
			$bbs_photo = cleanInput($bbs_photo);
			
			$bbs_photo = $db->real_escape_string($bbs_photo);
			
			$sql="insert into dtb_bbs set bbs_id='$bbs_id',bbs_title='$title',bbs_content='$content',user_id='$userid',user_type='$usertype',bbs_type='$bbs_type',create_date='$now',answer_date='$now',bbs_photo='$bbs_photo'";
			//echo $sql;
			if($db->Execute($sql)){
				$message=["true","发表成功"];
				exit(json_encode($message));
			}else {
				$message=["false","发表失败"];
				exit(json_encode($message));
			}
			
		}
	}*/
	if(@$do=='show')
	{
		if(@$_REQUEST['bbsid'])
		{
			$id=$_REQUEST['bbsid'];
			$bbs = getBbs("bbs_id='$id'",0, 1);
			if($bbs)
			{
				exit(json_encode($bbs));
				
			}else{
				exit(json_encode($bbs));
			}
		}
		else
		{
			exit(json_encode(false));
		}
	
	}
	/*
	if($do=='del')
	{
		if(@$_REQUEST['bbsid']){
			$id=$_REQUEST['bbsid'];
			$userid=cleanInput(@$_REQUEST['userid']);
			$sql="select user_id from dtb_bbs where bbs_id='$id'";
			$uid = $db->QueryItem($sql);
			if($userid == $uid){
				$sql="update dtb_bbs set del_flg=1 where bbs_id='$id'";
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
			$message=["false","bbsid不能为空"];
			exit(json_encode($message));
		}
	}
	if($do=='update')
	{
		if(@$_REQUEST['bbsid']){
			$userid=cleanInput(@$_REQUEST['userid']);
			$bbs_id=cleanInput($_REQUEST['bbsid']);
			@$title = cleanInput($_REQUEST["title"]);
			$title = $db->real_escape_string($title);
			@$content = $db->real_escape_string($_REQUEST["content"]);
			if($userid!=""&&$title!=""&&$content!="")
			{
				$sql="select user_id from dtb_bbs where bbs_id='$bbs_id'";
				$uid = $db->QueryItem($sql);
				if($userid == $uid){
					
					$sql="update dtb_bbs set bbs_title='$title',bbs_content='$content' where bbs_id='$bbs_id'" ;
					//echo $sql;
					if($db->Execute($sql)){
						$message=["true","修改成功"];
						exit(json_encode($message));
					}else {
						$message=["false","修改失败"];
						exit(json_encode($message));
					}
				}else{
					$message=["false","userid不相等"];
					exit(json_encode($message));
				}
			}else{
				$message=["false","请查看赋值变量是否与api相符"];
				exit(json_encode($message));
			}		
		}
	}*/
	if($do=='search')
	{
		$where = "";
		$fenlei = "";
		if(isset($_REQUEST["fenlei"])&&$_REQUEST["fenlei"]!=""){
			if($_REQUEST["fenlei"]!="闲聊"){
				if($_REQUEST["fenlei"] == "精品"){
					$where .= "read_num > 1000";
				}else{
					$where .= "bbs_type='$_REQUEST[fenlei]'";
				}
			}else{
				$where .= "bbs_type is null";
			}
		}
		if(isset($_REQUEST["keyword"])&&$_REQUEST["keyword"]!=""){
			if($where != ""){
				$where .= " and ";
			}
			$where .= "(bbs_title like '%$_REQUEST[keyword]%' or bbs_content like '%$_REQUEST[keyword]%')";
		}
		
		$all_nums = getObjectNum("dtb_bbs",$where);
		$bbs = getBbs($where,0, $all_nums);
		exit(json_encode($bbs));
	}