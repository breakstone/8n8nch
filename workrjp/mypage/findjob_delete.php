<?php
	//得到求职信息
	$id = cleanInput($id);
	if($id!=""){
		$people = showPeople($id);
		if($people['user_id']==$_SESSION['kiwa_userid']){//判断是不是发表信息的人
			$return = findjob_delete($id,$_SESSION['kiwa_userid']);
			if($return){
				header('Location:'.APP_URL.'mypage/findjob/');
			}
		}else{
			//没有参数跳到错误页面
			header('Location:'.APP_URL.'mypage/done/error');
		}
	}else{
		//没有参数id跳到错误页面
		header('Location:'.APP_URL.'mypage/done/error');
	}
?>