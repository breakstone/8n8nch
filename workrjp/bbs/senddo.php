<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	//var_dump($_SESSION); exit();
	if((isset($_SESSION["kiwa_userid"])&&$_SESSION["kiwa_userid"]!="")||(isset($_SESSION["kiwa_companyid"])&&$_SESSION["kiwa_companyid"]!="")){
		if((isset($_SESSION["kiwa_userid"])&&$_SESSION["kiwa_userid"]!="")){
			$userid = $_SESSION["kiwa_userid"];
			$usertype = "user";
		}
		if((isset($_SESSION["kiwa_companyid"])&&$_SESSION["kiwa_companyid"]!="")){
			$userid = $_SESSION["kiwa_companyid"];
			$usertype = "company";
		}
		$now = date("Y-m-d H:i:s");
		$bbs_id = date("is").rand(1000,9999);
		$title = cleanInput($_POST["title"]);
		$title = $db->real_escape_string($title);
		$content = $db->real_escape_string($_POST["content"]);
		$bbs_type = $db->real_escape_string($_POST["fenlei"]);
		
		if($_POST["flag"] == 2){
			$bbs_type = "就职经验-参加活动";
		}
		
		$sql="insert into dtb_bbs set bbs_id='$bbs_id',bbs_title='$title',bbs_content='$content',user_id='$userid',user_type='$usertype',bbs_type='$bbs_type',create_date='$now',answer_date='$now'";
		
		//存日志表
		dolog($userid, $usertype, "bbs", $bbs_id, $title, "insert");
		
		if($db->Execute($sql)){
			if ($_SESSION["image_path"]){
				$bbs_photo_js=$_SESSION["image_path"];
				bbs_img_update_html5($bbs_id,$bbs_photo_js);
				$_SESSION["image_path"]="";
			}
			$dir = APP_PATH."upload/img/bbs/";
			
			$bbs_photo="";
			$index = 0;
			foreach ($_FILES["file_upload"]["error"] as $key => $error) {
				$index = $index + 1;
				if ($error == UPLOAD_ERR_OK) {
					$tmp_name = $_FILES["file_upload"]["tmp_name"][$key];
					$filetype = pathinfo($_FILES["file_upload"]["name"][$key], PATHINFO_EXTENSION);
					//画像ファイルに名前を付ける
					$image_name = $bbs_id.'_'.$index.'.'.$filetype;
					//画像ファイルの保存場所
					$image_path = $dir.$image_name;
					if(move_uploaded_file($tmp_name,$image_path)){
						$bbs_photo .= "upload/img/bbs/".$image_name.",";
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
							//照片旋转270
							//$new_image = imagerotate($new_image,270,0);
							//JPEG形式で保存
							ImageJPEG($new_image, $image_path, 95);
							//メモリ解放
							imagedestroy($image);
							imagedestroy($new_image);
						}
						//水印
						$waterpic= APP_PATH.'upload/shuiyin.png';
						$back = imagecreatefromstring(file_get_contents($image_path));
						$water=imagecreatefrompng($waterpic);
						$width = ImageSX($back);
						$height = ImageSY($back);
						$w_w=imagesx($water);
						$w_h=imagesy($water);
						imagecopy($back, $water, $width - $w_w ,$height -$w_h , 0, 0, $w_w, $w_h);
						imagejpeg($back,$image_path);
						imagedestroy($back);
						imagedestroy($water);
					
					}else{
						//echo "写真アップロードエラー";
						echo "上传图片失败！请联系管理员";
					}
				}
			}
			$bbs_photo = rtrim($bbs_photo,",");
			//照片地址存入数据库
			bbs_img_update($bbs_id,$bbs_photo);
		}
		$send_type=$_GET['send_type'];
		if ($send_type==1){
		
			echo '<script>
			alert("发布成功");
			window.location="'.APP_URL.'people/show/user_'.$userid.'";';
			echo '</script>';
		}else{
			header('Location:'.APP_URL.'bbs/');
		}
	}else{
		//固定引入参数
		$smarty->assign('BASE_URL', APP_URL);
		$smarty->assign('THEME', THEME);
		$smarty->display('error.html');
	}
?>