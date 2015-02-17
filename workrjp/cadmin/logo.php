<?php
/**mypage**/
###################################
# マイページ画面
###################################
// config
if(!file_exists('../_config/config.php'))
{
	die('[index.php] _config/config.php not found');
}

	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	
	$cid = $_SESSION['kiwa_companyid'];
	if(isset($_REQUEST["gaiphoto"])){
		$dir = APP_PATH."upload/img/compangy/";
		//アップロードファイル確認
		if(isset($_FILES["company_photo_url"])&&$_FILES["company_photo_url"]["type"]!=""){
			//アップロードファイル読み込み
			$data = $_FILES["company_photo_url"];
			$filetype = pathinfo($data["name"], PATHINFO_EXTENSION);
			//画像ファイルに名前を付ける
			$image_name = $_SESSION['kiwa_companyid'].'_'.date("md").'.'.$filetype;
			//画像ファイルの保存場所
			$image_path = $dir.$image_name;
			//ファイルアップロードとエラーチェック
			if(move_uploaded_file($data["tmp_name"],$image_path)){
				//照片地址存入数据库
				
				company_img_update($_SESSION['kiwa_companyid'],"upload/img/compangy/".$image_name);
				//画像縮小チェック
				//画像読み込み（JPEG,PNG,GIFなどに対応）
				//file_get_contents($image_path);
				$image = imagecreatefromstring(file_get_contents($image_path));
				
				//画像サイズ取得
				$width = ImageSX($image);
				$height = ImageSY($image);
				//出力する縮小画像のサイズ
				$new_width = 220;
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
			}else{
				//echo "写真アップロードエラー";
				header('Location:'.APP_URL.'companypage/done/imgerror/');
			}
		}
	}
	
	
	$sql = "select * from dtb_companyuser where company_id = '$cid' and del_flg = 0";
	$company = $db->QueryRow($sql);
	$smarty->assign('company', $company);
	
	if($company["company_photo_url"]!=""){
		if(file_exists("../".$company["company_photo_url"])){
			$smarty->assign('img_status', 1);
		}else{
			$smarty->assign('img_status', "");
		}
	}
	
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	$smarty->display('Cadmin/logo.html');
	
?>