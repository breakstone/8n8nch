<?php
	//得到求职信息
	$id = cleanInput($_GET["id"]);
	$ws = $_GET["ws"];
	if($id!=""){
		global $db;
		if($_GET["type"]=="live"){
			$sql_see = "select user_id from dtb_lives where Id = $id and del_flg = 0";
			$item = $db->QueryItem($sql_see);
			if($item == $_SESSION['kiwa_companyid']){//判断是不是本公司
				$sql = "update dtb_lives set del_flg = 1 where Id = $id";
				$return = $db->Execute($sql);
				if($return){
					header('Location:'.APP_URL.'companypage/myrequirement/?Which_Show='.$ws);
				}
			}else{
				//没有参数跳到错误页面
				header('Location:'.APP_URL.'companypage/done/error');
			}
		}
		if($_GET["type"]=="job"){
			$sql_see = "select company_id from dtb_jobs where Id = $id and del_flg = 0";
			$item = $db->QueryItem($sql_see);
			if($item == $_SESSION['kiwa_companyid']){//判断是不是本公司
				$sql = "update dtb_jobs set del_flg = 1 where Id = $id";
				$return = $db->Execute($sql);
				if($return){
					header('Location:'.APP_URL.'companypage/myrequirement/?Which_Show='.$ws);
				}
			}else{
				//没有参数跳到错误页面
				header('Location:'.APP_URL.'companypage/done/error');
			}
		}
	}else{
		//没有参数id跳到错误页面
		header('Location:'.APP_URL.'mypage/done/error');
	}
?>