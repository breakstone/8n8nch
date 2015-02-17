<?php
	//得到求职信息
	$id = cleanInput($_GET["id"]);
	$ws = $_GET["ws"];
	$now = date("Y-m-d H:i:s");
	if($id!=""){
		global $db;
		if($_GET["type"]=="live"){
			$sql_see = "select user_id from dtb_lives where Id = $id and del_flg = 0";
			$item = $db->QueryItem($sql_see);
			
			if($item == $_SESSION['kiwa_companyid']){//判断是不是本公司
				$sql = "update dtb_lives set create_date = '$now' where Id = $id";
				$return = $db->Execute($sql);
				if($return){
					pointsDo($item,"company",-10);
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
				$sql = "update dtb_jobs set create_date = '$now' where Id = $id";
				$return = $db->Execute($sql);
				if($return){
					pointsDo($item,"company",-10);
					header('Location:'.APP_URL.'companypage/myrequirement/?Which_Show='.$ws);
				}
			}else{
				//没有参数跳到错误页面
				header('Location:'.APP_URL.'companypage/done/error');
			}
		}
	}else{
		//没有参数id跳到错误页面
		header('Location:'.APP_URL.'companypage/done/error');
	}
?>