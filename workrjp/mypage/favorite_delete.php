<?php
	//得到求职信息
	$id = cleanInput($id);
	if($id != ""){
		$where = "Id = $id";
		$favorite = getFevorite($where,0,1,"date");
		if($favorite[0]['user_id']==$_SESSION['kiwa_userid']){
			//判断是不是收藏本人
			$return = favorite_delete($id,$_SESSION['kiwa_userid']);
			$Which_Show = $_GET["Which_Show"];
			if($return){
				header('Location:'.APP_URL.'mypage/favorite/?Which_Show='.$Which_Show);
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