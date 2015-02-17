<?php
	// config
	if(!file_exists('./../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	
	require_once './../_config/config.php';
	require_once './../_includes/functions.php';
	
	//发表工作
	if(isset($_POST['company_id'])&&isset($_POST['job_id'])&&isset($_SESSION['kiwa_companyname'])&&isset($_SESSION['kiwa_companyid'])){
		//发表求职
		$user_id = cleanInput($_SESSION['kiwa_companyid']);
		
		$company_id = cleanInput($_POST['company_id']);
		$company_name = cleanInput($_POST['company_name']);
		$job_id = cleanInput($_POST['job_id']);
		$job_title = cleanInput($_POST['job_title']);
		$toCompany_pr = cleanInput($_POST['toCompany_pr']);
		
		//公司简历
		$company_manager = cleanInput($_POST['company_manager']);
		$foundation_date = cleanInput($_POST['foundation_date']);
		$password = cleanInput($_POST['password']);
		$right_email = cleanInput($_POST['right_email']);
		$per_status = cleanInput($_POST['per_status']);
		$company_url = cleanInput($_POST['company_url']);
		
		$areaid = cleanInput($_POST['areaid']);
		$pref = cleanInput($_POST['pref']);
		
		$add_pref = getName(array("name"=>"pref","value"=>$pref));
		
		$kinds = $_POST['kinds'];
		foreach ($_POST['types'] as $t){
			$types_str .= $t.",";
			$row = getKindTypename($t);
			$typesname_str .= $row["typesname"].",";
			$kindsname = $row["kindsname"];
		}
		$types_str = rtrim($types_str,",");
		$typesname_str = rtrim($typesname_str,",");
		
		foreach ($_POST['skills'] as $s){
			$skills_str .= $s.",";
		}
		$skills_str = rtrim($skills_str,",");
		
		foreach ($_POST['ensn'] as $en){
			$ensn_str .= $en.",";
		}
		$ensn_str = rtrim($ensn_str,",");
		
		foreach ($_POST['eki'] as $ei){
			$eki_str .= $ei.",";
		}
		$eki_str = rtrim($eki_str,",");
		
		$zip01 = cleanInput($_POST['zip01']);
		$zip02 = cleanInput($_POST['zip02']);
		$addr01 = cleanInput($_POST['addr01']);
		$addr02 = cleanInput($_POST['addr02']);
		$tel01 = cleanInput($_POST['tel01']);
		$tel02 = cleanInput($_POST['tel02']);
		$tel03 = cleanInput($_POST['tel03']);
		$fax01 = cleanInput($_POST['fax01']);
		$fax02 = cleanInput($_POST['fax02']);
		$fax03 = cleanInput($_POST['fax03']);
		
		$employees_num = cleanInput($_POST['employees_num']);
		$company_text = cleanInput($_POST['company_text']);
		
		//判断违法否
		if(isset($_FILES["company_photo_url"])){
			$data = $_FILES["company_photo_url"];
			$filetype = pathinfo($data["name"], PATHINFO_EXTENSION);
			$upfiletype = strtoupper($filetype);
			if($upfiletype!="JPG"&&$upfiletype!="PNG"&&$upfiletype!="GIF"&&$upfiletype!="JPEG"){
				header('Location:'.APP_URL.'companypage/company/imgerror/');
			}
		}
		//判断违法否
		
		//发表求职处理
		$return1 = jobregist($_SESSION['kiwa_companyid'],$job_id,$job_title,$company_id,$company_name,$toCompany_pr);
		
		//企业简历更新
		$return2 = company_update($_SESSION['kiwa_companyid'],$company_manager,$foundation_date,$company_email,$password,$right_email,$per_status,$company_url,$zip01,$zip02,$add_pref,$addr01,$addr02,$tel01,$tel02,$tel03,$fax01,$fax02,$fax03,$employees_num,$company_text,$areaid,$pref,$kinds,$types_str,$skills_str,$ensn_str,$eki_str,$kindsname,$typesname_str);
		
		pointsDo($_SESSION['kiwa_companyid'], "company",10);
		if(!$return1||!$return2){
			if(!$return1) echo "递交简历处理 失败！" ;exit();
			if(!$return2) echo "企业简历更新 失败！" ;exit();
		}else{
			$dir = APP_PATH."upload/img/compangy/";
			//アップロードファイル確認
			if(isset($_FILES["company_photo_url"])){
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
			//没有参数跳到错误页面
			header('Location:'.APP_URL.'companypage/done/jobregistok');
		}
	}else{
		//没有参数跳到错误页面
		header('Location:'.APP_URL.'companypage/done/error');
	}
?>