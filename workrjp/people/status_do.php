<?php
if(!file_exists('../_config/config.php'))
{
	die('[index.php] _config/config.php not found');
}
require_once '../_config/config.php';
require_once '../_includes/functions.php';

if(isset($_POST['content'])&&((isset($_SESSION["kiwa_userid"])&&$_SESSION["kiwa_userid"]!="")||(isset($_SESSION["kiwa_companyid"])&&$_SESSION["kiwa_companyid"]!=""))){
	
	if(@$_SESSION["kiwa_userid"]!=""){
		$user_id = $_SESSION["kiwa_userid"];
		$user_type = "user";
	}
	if(@$_SESSION["kiwa_companyid"]!=""){
		$user_id = $_SESSION["kiwa_companyid"];
		$user_type = "company";
	}
	$status_id=date("is").rand(1000,9999);;
	$status_content = cleanInput($_POST['content']);
	$status_content = $db->real_escape_string($status_content);
	$create_date = date("Y-m-d H:i:s");
	
	global $db;
	$sql = "insert into dtb_status set status_id = '$status_id',status_content='$status_content',user_id='$user_id',user_type='$user_type',create_date='$create_date'";
//	echo $sql;
	$return = $db->Execute($sql);
	
	//存日志表
	dolog($user_id, $user_type, "status", $status_id, $status_content, "insert");
	if($return){
		$dir = APP_PATH."upload/img/people/";
		$photo_url="";
		$index = 0;
		$now = date("Y-m-d H:i:s");
		foreach ($_FILES["photo_url"]["error"] as $key => $error) {
			$index = $index + 1;
			if ($error == UPLOAD_ERR_OK) {
				$tmp_name = $_FILES["photo_url"]["tmp_name"][$key];
				$filetype = pathinfo($_FILES["photo_url"]["name"][$key], PATHINFO_EXTENSION);
				//画像ファイルに名前を付ける
				$image_name = $user_id.'_'.date("is").rand(100,999).'.'.$filetype;
				//画像ファイルの保存場所
				$image_path = $dir.$image_name;
				if(move_uploaded_file($tmp_name,$image_path)){
					$photo_url .= "upload/img/people/".$image_name.",";
					//画像縮小チェック
					//画像読み込み（JPEG,PNG,GIFなどに対応）
					//file_get_contents($image_path);
					if($filetype != "gif" && $filetype != "GIF"){
						$image = imagecreatefromstring(file_get_contents($image_path));
						//画像サイズ取得
						$width = ImageSX($image);
						$height = ImageSY($image);
						//出力する縮小画像のサイズ
						$new_width = 500;
						$new_height = $new_width / $width * $height;
						//空の画像を作成
						$new_image = ImageCreateTrueColor($new_width, $new_height);
						//リサンプリングして画像を生成
						ImageCopyResampled($new_image,$image,0,0,0,0,$new_width,$new_height,$width,$height);
						
						ImageJPEG($new_image, $image_path, 95);
						//メモリ解放
						imagedestroy($image);
						imagedestroy($new_image);
					}
					$sql_create = "insert into dtb_people_photo set user_id='$user_id',user_type='$user_type',title='$status_content',content='$status_content',photo_url = 'upload/img/people/$image_name',flag ='status',create_date='$now'";
					$db->Execute($sql_create);
				}else{
					//echo "写真アップロードエラー";
					echo "上传图片失败！请联系管理员";
				}
			}
		}
		$photo_url = rtrim($photo_url,",");
		
		//存入数据库
		//更新发表日志路径
		$sql="update dtb_status set status_photo = '$photo_url' where status_id='$status_id' and del_flg =0";
		$db->Execute($sql);
		//插入到用户相册
		/*//アップロードファイル確認
		if(isset($_FILES["photo_url"])&&$_FILES["photo_url"]["type"]!=""){
			//アップロードファイル読み込み
			$data = $_FILES["photo_url"];
			//echo $data;
			$filetype = pathinfo($data["name"], PATHINFO_EXTENSION);
			//画像ファイルに名前を付ける
			$image_name = $user_id.'_'.date("is").rand(100,999).'.'.$filetype;
			//画像ファイルの保存場所
			$image_path = $dir.$image_name;
			//ファイルアップロードとエラーチェック
			if(move_uploaded_file($data["tmp_name"],$image_path)){
				$photo_url = "upload/img/people/".$image_name;
				$now = date("Y-m-d H:i:s");
				//存入数据库
				//更新发表日志路径
				$sql="update dtb_status set status_photo = '$photo_url' where status_id='$status_id' and del_flg =0";
				$db->Execute($sql);
				//插入到用户相册
				$sql_create = "insert into dtb_people_photo set user_id='$user_id',user_type='$user_type',title='$status_content',content='$status_content',photo_url = '$photo_url',flag ='status',create_date='$now'";
				$db->Execute($sql_create);
			}else{
				//echo "写真アップロードエラー";
				header('Location:'.APP_URL.'people/done/imgerror/');
			}
		}*/
		header('Location:'.APP_URL.'people/show/'.$user_type.'_'.$user_id."/");
	}else{
		//系统错误
		$smarty->assign('h1', "信息提示");
		$smarty->assign('message', "非常抱歉，发布状态报错，请联系客服，谢谢你的合作！");
		
		$smarty->assign('BASE_URL', APP_URL);
		$smarty->assign('THEME', THEME);
		$smarty->display('People/done.html');
	}
}
?>