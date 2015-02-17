<?php
	// config
	if(!file_exists('_config/config.php')) 
	{
		echo $_SERVER['REQUEST_URI']."<br>".$_SERVER['SCRIPT_NAME']."<br>";
	   die('[index.php] _config/config.php not found');
	}
	
	
	require_once '_config/config.php';
	require_once '_includes/functions.php';
	
	//发表工作
	if(isset($_POST['type']) && isset($_POST['title']) && ((isset($_SESSION['kiwa_username'])&&isset($_SESSION['kiwa_userid'])) || (isset($_SESSION['kiwa_companyname'])&&isset($_SESSION['kiwa_companyid'])))){
		//发表求职
		$user_id = cleanInput($_POST['user_id']);
		$user_type = cleanInput($_POST['type']);
		$title = cleanInput($_POST['title']);
		$content = cleanInput($_POST['content']);
		$now = date("Y-m-d H:i:s");
		
		$dir = APP_PATH."upload/img/people/";
		//アップロードファイル確認
		if(isset($_FILES["photo_url"])&&$_FILES["photo_url"]["type"]!=""){
			//アップロードファイル読み込み
			$data = $_FILES["photo_url"];
			$filetype = pathinfo($data["name"], PATHINFO_EXTENSION);
			//画像ファイルに名前を付ける
			$image_name = $user_id.'_'.date("is").rand(100,999).'.'.$filetype;
			//画像ファイルの保存場所
			$image_path = $dir.$image_name;
			//ファイルアップロードとエラーチェック
			if(move_uploaded_file($data["tmp_name"],$image_path)){
				$photo_url = "upload/img/people/".$image_name;
				//存入数据库
				global $db;
				$sql_create = "insert into dtb_people_photo set user_id='$user_id',user_type='$user_type',title='$title',content='$content',photo_url = '$photo_url',create_date='$now'";
				$db->Execute($sql_create);
			}else{
				//echo "写真アップロードエラー";
				header('Location:'.APP_URL.'people/done/imgerror/');
			}
		}
		//没有参数跳到错误页面
		header('Location:http://'.$_SERVER['HTTP_HOST']);
		
	}else{
		//没有参数跳到错误页面
		header('Location:'.APP_URL.'people/done/error/');
	}
?>