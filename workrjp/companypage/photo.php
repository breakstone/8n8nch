<?php
	if($id=="imgerror"){
		$smarty->assign('error', "上传图片格式不正确");
	}
	//解决缓存，图片不更新的方法
	$catch_img = rand(0,100);
	$smarty->assign("catch_img",$catch_img);
	
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
	//得到企业简历
	$company = getCompany($_SESSION['kiwa_companyid']);
	$smarty->assign('company', $company);
	//判断企业宣传照片
	if($company["company_photo_url"]!=""){
		if(file_exists("../".$company["company_photo_url"])){
			$smarty->assign('img_status', 1);
		}else{
			$smarty->assign('img_status', "");
		}
	}
	//固定引入参数
	//mypage页面信息查询
	$smarty->assign('THEME', THEME);
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('COMPANYNAME', $_SESSION['kiwa_companyname']);
	$smarty->assign('COMPANYID', $_SESSION['kiwa_companyid']);
	//注册个方法
	$smarty->register_function('getname','getName');
	//得到未读message
	$um = unreadMessage($_SESSION['kiwa_companyid']);
	$smarty->assign('unread', $um);
	//生活需求个数
	$sql_live="select count(*) from dtb_lives where user_id = '$_SESSION[kiwa_companyid]' and del_flg = 0";
	$live_um = $db->QueryItem($sql_live);
	$smarty->assign('live_um', $live_um);
	//招贤纳才个数
	$sql_job="select count(*) from dtb_jobs where company_id = '$_SESSION[kiwa_companyid]' and del_flg = 0";
	$job_um = $db->QueryItem($sql_job);
	$smarty->assign('job_um', $job_um);
	//收藏人才企业数
	$sql_fa_u="select count(*) from dtb_favorite_list where user_id = '$_SESSION[kiwa_companyid]' and (flag='user' or flag='company') and del_flg = 0";
	$fa_um = $db->QueryItem($sql_fa_u);
	$smarty->assign('fa_um', $fa_um);
	//收藏生活互助数
	$sql_fa_l="select count(*) from dtb_favorite_list where user_id = '$_SESSION[kiwa_companyid]' and (flag='live') and del_flg = 0";
	$fa_um_l = $db->QueryItem($sql_fa_l);
	$smarty->assign('fa_um_l', $fa_um_l);
	//收藏人才企业数
	$sql_fa_j="select count(*) from dtb_favorite_list where user_id = '$_SESSION[kiwa_companyid]' and (flag='job') and del_flg = 0";
	$fa_um_j = $db->QueryItem($sql_fa_j);
	$smarty->assign('fa_um_j', $fa_um_j);
	//授信箱个数
	$sql_message_fa="select count(*) from dtb_message where message_to = '$_SESSION[kiwa_companyid]' and del_flg = 0";
	$message_um_f = $db->QueryItem($sql_message_fa);
	$smarty->assign('message_um_f', $message_um_f);
	//送信箱个数
	$sql_message_s="select count(*) from dtb_message where message_from = '$_SESSION[kiwa_companyid]' and from_del_flg = 0";
	$message_um_s = $db->QueryItem($sql_message_s);
	$smarty->assign('message_um_s', $message_um_s);
	
	if(isMobile()){
		$smarty->display('mobile/Companypage/photo.html');
	}else{
		$smarty->display('Companypage/photo.html');
	}
	
?>