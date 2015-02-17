<?php
	//得到求职信息
	$id = cleanInput($id);
	if($id!=""){
		$job = showJob($id);
		if($job['company_id']==$_SESSION['kiwa_companyid']){//判断是不是发表信息的人
			$return = findpeople_delete($id,$_SESSION['kiwa_companyid']);
			if($return){
				header('Location:'.APP_URL.'companypage/findpeople/');
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