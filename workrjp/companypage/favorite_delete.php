<?php
	//得到求职信息
	$id = cleanInput($id);
	if($id != ""){
		$where = "Id = $id";
		$favorite = getFevorite($where,0,1,"date");
		if($favorite[0]['user_id']==$_SESSION['kiwa_companyid']){
			//判断是不是收藏本企业
			$return = companyfavorite_delete($id,$_SESSION['kiwa_companyid']);
			if($return){
				header('Location:'.APP_URL.'companypage/favorite/');
			}
		}else{
			//没有参数跳到错误页面
			header('Location:'.APP_URL.'companypage/done/error');
		}
	}else{
		//没有参数id跳到错误页面
		header('Location:'.APP_URL.'companypage/done/error');
	}
?>