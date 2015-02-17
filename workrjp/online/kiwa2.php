<?php
	//人を探す
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	require_once '../_includes/mobile.php';
	//没有Session 跳转到登录页面
	if(@$_SESSION['kiwa_userid']=="" && @$_SESSION['kiwa_companyid']==""){
		header('Location:'.APP_URL.'login/?url=online_kiwa2/');
	}else{
		if(isset($_REQUEST["sendbutton"])){
			if(@$_SESSION['kiwa_userid'] !=""){
				$user_id = $_SESSION['kiwa_userid'];
				$user_type = "user";
			}
			if(@$_SESSION['kiwa_companyid'] !=""){
				$user_id = $_SESSION['kiwa_companyid'];
				$user_type = "company";
			}
			$create_date = date("Y-m-d H:m:s");
			$dir = APP_PATH."upload/online/kiwa/";
			$set = "";
			//护照1
			if(isset($_FILES["hu1"])&&$_FILES["hu1"]["type"]!=""){
				//アップロードファイル読み込み
				$data = $_FILES["hu1"];
				$filetype = pathinfo($data["name"], PATHINFO_EXTENSION);
				//画像ファイルに名前を付ける
				$image_name = $user_id.'_hu1.'.$filetype;
				//画像ファイルの保存場所
				$image_path = $dir.$image_name;
				//ファイルアップロードとエラーチェック
				if(move_uploaded_file($data["tmp_name"],$image_path)){
					//画像縮小チェック
					//file_get_contents($image_path);
					$image = imagecreatefromstring(file_get_contents($image_path));
					//画像サイズ取得
					$width = ImageSX($image);
					$height = ImageSY($image);
					//出力する縮小画像のサイズ
					$new_width = 800;
					$new_height = $new_width / $width * $height;
					//空の画像を作成
					$new_image = ImageCreateTrueColor($new_width, $new_height);
					//リサンプリングして画像を生成
					ImageCopyResampled($new_image,$image,0,0,0,0,$new_width,$new_height,$width,$height);
					//照片旋转270
					//$new_image = imagerotate($new_image,270,0);
					//JPEG形式で保存
					ImageJPEG($new_image, $image_path, 95);
					//メモリ解放
					imagedestroy($image);
					imagedestroy($new_image);
				}
				if($set == ""){
					$set .= " hu1_picture = '$image_name'";
				}else{
					$set .= " , hu1_picture = '$image_name'";
				}
			}
			//护照
			if(isset($_FILES["hu2"])&&$_FILES["hu2"]["type"]!=""){
				//アップロードファイル読み込み
				$data = $_FILES["hu2"];
				$filetype = pathinfo($data["name"], PATHINFO_EXTENSION);
				//画像ファイルに名前を付ける
				$image_name = $user_id.'_hu2.'.$filetype;
				//画像ファイルの保存場所
				$image_path = $dir.$image_name;
				//ファイルアップロードとエラーチェック
				if(move_uploaded_file($data["tmp_name"],$image_path)){
					//画像縮小チェック
					//file_get_contents($image_path);
					$image = imagecreatefromstring(file_get_contents($image_path));
					//画像サイズ取得
					$width = ImageSX($image);
					$height = ImageSY($image);
					//出力する縮小画像のサイズ
					$new_width = 800;
					$new_height = $new_width / $width * $height;
					//空の画像を作成
					$new_image = ImageCreateTrueColor($new_width, $new_height);
					//リサンプリングして画像を生成
					ImageCopyResampled($new_image,$image,0,0,0,0,$new_width,$new_height,$width,$height);
					//照片旋转270
					//$new_image = imagerotate($new_image,270,0);
					//JPEG形式で保存
					ImageJPEG($new_image, $image_path, 95);
					//メモリ解放
					imagedestroy($image);
					imagedestroy($new_image);
				}
				if($set == ""){
					$set .= " hu2_picture = '$image_name'";
				}else{
					$set .= " , hu2_picture = '$image_name'";
				}
			}
			//在留卡1
			if(isset($_FILES["zai1"])&&$_FILES["zai1"]["type"]!=""){
				//アップロードファイル読み込み
				$data = $_FILES["zai1"];
				$filetype = pathinfo($data["name"], PATHINFO_EXTENSION);
				//画像ファイルに名前を付ける
				$image_name = $user_id.'_zai1.'.$filetype;
				//画像ファイルの保存場所
				$image_path = $dir.$image_name;
				//ファイルアップロードとエラーチェック
				if(move_uploaded_file($data["tmp_name"],$image_path)){
					//画像縮小チェック
					//file_get_contents($image_path);
					$image = imagecreatefromstring(file_get_contents($image_path));
					//画像サイズ取得
					$width = ImageSX($image);
					$height = ImageSY($image);
					//出力する縮小画像のサイズ
					$new_width = 800;
					$new_height = $new_width / $width * $height;
					//空の画像を作成
					$new_image = ImageCreateTrueColor($new_width, $new_height);
					//リサンプリングして画像を生成
					ImageCopyResampled($new_image,$image,0,0,0,0,$new_width,$new_height,$width,$height);
					//照片旋转270
					//$new_image = imagerotate($new_image,270,0);
					//JPEG形式で保存
					ImageJPEG($new_image, $image_path, 95);
					//メモリ解放
					imagedestroy($image);
					imagedestroy($new_image);
				}
				
				if($set == ""){
					$set .= " zai1_picture = '$image_name'";
				}else{
					$set .= " , zai1_picture = '$image_name'";
				}
			}
			//在留卡2
			if(isset($_FILES["zai2"])&&$_FILES["zai2"]["type"]!=""){
				//アップロードファイル読み込み
				$data = $_FILES["zai2"];
				$filetype = pathinfo($data["name"], PATHINFO_EXTENSION);
				//画像ファイルに名前を付ける
				$image_name = $user_id.'_zai2.'.$filetype;
				//画像ファイルの保存場所
				$image_path = $dir.$image_name;
				//ファイルアップロードとエラーチェック
				if(move_uploaded_file($data["tmp_name"],$image_path)){
					//画像縮小チェック
					//file_get_contents($image_path);
					$image = imagecreatefromstring(file_get_contents($image_path));
					//画像サイズ取得
					$width = ImageSX($image);
					$height = ImageSY($image);
					//出力する縮小画像のサイズ
					$new_width = 800;
					$new_height = $new_width / $width * $height;
					//空の画像を作成
					$new_image = ImageCreateTrueColor($new_width, $new_height);
					//リサンプリングして画像を生成
					ImageCopyResampled($new_image,$image,0,0,0,0,$new_width,$new_height,$width,$height);
					//照片旋转270
					//$new_image = imagerotate($new_image,270,0);
					//JPEG形式で保存
					ImageJPEG($new_image, $image_path, 95);
					//メモリ解放
					imagedestroy($image);
					imagedestroy($new_image);
				}
				if($set == ""){
					$set .= " zai2_picture = '$image_name'";
				}else{
					$set .= " , zai2_picture = '$image_name'";
				}
			}
			//银行存折
			if(isset($_FILES["cun"])&&$_FILES["cun"]["type"]!=""){
				//アップロードファイル読み込み
				$data = $_FILES["cun"];
				$filetype = pathinfo($data["name"], PATHINFO_EXTENSION);
				//画像ファイルに名前を付ける
				$image_name = $user_id.'_cun.'.$filetype;
				//画像ファイルの保存場所
				$image_path = $dir.$image_name;
				//ファイルアップロードとエラーチェック
				if(move_uploaded_file($data["tmp_name"],$image_path)){
					//画像縮小チェック
					//file_get_contents($image_path);
					$image = imagecreatefromstring(file_get_contents($image_path));
					//画像サイズ取得
					$width = ImageSX($image);
					$height = ImageSY($image);
					//出力する縮小画像のサイズ
					$new_width = 800;
					$new_height = $new_width / $width * $height;
					//空の画像を作成
					$new_image = ImageCreateTrueColor($new_width, $new_height);
					//リサンプリングして画像を生成
					ImageCopyResampled($new_image,$image,0,0,0,0,$new_width,$new_height,$width,$height);
					//照片旋转270
					//$new_image = imagerotate($new_image,270,0);
					//JPEG形式で保存
					ImageJPEG($new_image, $image_path, 95);
					//メモリ解放
					imagedestroy($image);
					imagedestroy($new_image);
				}
				if($set == ""){
					$set .= " cun_picture = '$image_name'";
				}else{
					$set .= " , cun_picture = '$image_name'";
				}
			}
			
			
			//保险证
			if(isset($_FILES["bao"])&&$_FILES["bao"]["type"]!=""){
				//アップロードファイル読み込み
				$data = $_FILES["bao"];
				$filetype = pathinfo($data["name"], PATHINFO_EXTENSION);
				//画像ファイルに名前を付ける
				$image_name = $user_id.'_bao.'.$filetype;
				//画像ファイルの保存場所
				$image_path = $dir.$image_name;
				//ファイルアップロードとエラーチェック
				if(move_uploaded_file($data["tmp_name"],$image_path)){
					//画像縮小チェック
					//file_get_contents($image_path);
					$image = imagecreatefromstring(file_get_contents($image_path));
					//画像サイズ取得
					$width = ImageSX($image);
					$height = ImageSY($image);
					//出力する縮小画像のサイズ
					$new_width = 800;
					$new_height = $new_width / $width * $height;
					//空の画像を作成
					$new_image = ImageCreateTrueColor($new_width, $new_height);
					//リサンプリングして画像を生成
					ImageCopyResampled($new_image,$image,0,0,0,0,$new_width,$new_height,$width,$height);
					//照片旋转270
					//$new_image = imagerotate($new_image,270,0);
					//JPEG形式で保存
					ImageJPEG($new_image, $image_path, 95);
					//メモリ解放
					imagedestroy($image);
					imagedestroy($new_image);
				}
				if($set == ""){
					$set .= " bao_picture = '$image_name'";
				}else{
					$set .= " , bao_picture = '$image_name'";
				}
			}
			
			//学生证
			if(isset($_FILES["xue"])&&$_FILES["xue"]["type"]!=""){
				//アップロードファイル読み込み
				$data = $_FILES["xue"];
				$filetype = pathinfo($data["name"], PATHINFO_EXTENSION);
				//画像ファイルに名前を付ける
				$image_name = $user_id.'_xue.'.$filetype;
				//画像ファイルの保存場所
				$image_path = $dir.$image_name;
				//ファイルアップロードとエラーチェック
				if(move_uploaded_file($data["tmp_name"],$image_path)){
					//画像縮小チェック
					//file_get_contents($image_path);
					$image = imagecreatefromstring(file_get_contents($image_path));
					//画像サイズ取得
					$width = ImageSX($image);
					$height = ImageSY($image);
					//出力する縮小画像のサイズ
					$new_width = 800;
					$new_height = $new_width / $width * $height;
					//空の画像を作成
					$new_image = ImageCreateTrueColor($new_width, $new_height);
					//リサンプリングして画像を生成
					ImageCopyResampled($new_image,$image,0,0,0,0,$new_width,$new_height,$width,$height);
					//照片旋转270
					//$new_image = imagerotate($new_image,270,0);
					//JPEG形式で保存
					ImageJPEG($new_image, $image_path, 95);
					//メモリ解放
					imagedestroy($image);
					imagedestroy($new_image);
				}
				if($set == ""){
					$set .= " xue_picture = '$image_name'";
				}else{
					$set .= " , xue_picture = '$image_name'";
				}
			}
			
			$sql = "update dtb_online set $set where user_id = '$user_id'";
			$db->Execute($sql);
			//header('Location:'.APP_URL.'online/kiwa3/');
			header('Location:'.APP_URL.'online/over/');
		}
		//固定引入参数
		$smarty->assign('BASE_URL', APP_URL);
		$smarty->assign('THEME', THEME);
		if(isMobile()){
			$smarty->display('Online/kiwamobile2.html');
			//$smarty->display('Online/kiwa2.html');
		}else{
			$smarty->display('Online/kiwa2.html');
			//$smarty->display('Online/kiwamobile2.html');
		}
	}
?>