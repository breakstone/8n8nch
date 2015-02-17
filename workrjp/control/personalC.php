<?php
	// config
	if(!file_exists('./../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	
	require_once './../_config/config.php';
	require_once './../_includes/functions.php';
	//发表工作
	if(isset($_POST['name01'])&&isset($_POST['name02'])&&isset($_SESSION['kiwa_username'])&&isset($_SESSION['kiwa_userid'])){
		
		$join = cleanInput($_POST['join']);
		//个人简历
		$name01 = cleanInput($_POST['name01']);
		$name02 = cleanInput($_POST['name02']);
		$birth = $_POST["birthYear"]."-".$_POST["birthMonth"]."-".$_POST["birthDay"];
		$birth = cleanInput($birth);
		$sex = cleanInput($_POST['sex']);
		$email = cleanInput($_POST['email']);
		$password = cleanInput($_POST['password']);
		$per_status = cleanInput($_POST['per_status']);
		$pr = cleanInput($_POST['pr']);
		$zige = cleanInput($_POST['zige']);
		
		$areaid = cleanInput($_POST['areaid']);
		$pref = cleanInput($_POST['pref']);
		
		$add_pref = getName(array("name"=>"pref","value"=>$pref));
		
		$kinds = $_POST['kinds'];
		foreach ($_POST['types'] as $t){
			@$types_str .= $t.",";
			$row = getKindTypename($t);
			@$typesname_str .= $row["typesname"].",";
			$kindsname = $row["kindsname"];
		}
		$types_str = rtrim($types_str,",");
		$typesname_str = rtrim($typesname_str,",");
		
		
		foreach ($_POST['skills'] as $s){
			@$skills_str .= $s.",";
		}
		$skills_str = rtrim($skills_str,",");
		
		foreach (@$_POST['ensn'] as $en){
			$ensn_str .= $en.",";
		}
		@$ensn_str = rtrim($ensn_str,",");
		
		foreach ($_POST['eki'] as $ei){
			$eki_str .= $ei.",";
		}
		$eki_str = rtrim($eki_str,",");
		
		$eki = cleanInput($_POST['eki']);
		$zip01 = cleanInput($_POST['zip01']);
		$zip02 = cleanInput($_POST['zip02']);
		//$add_pref = cleanInput($_POST['add_pref']);
		$addr01 = cleanInput($_POST['addr01']);
		$addr02 = cleanInput($_POST['addr02']);
		$tel01 = cleanInput($_POST['tel01']);
		$tel02 = cleanInput($_POST['tel02']);
		$tel03 = cleanInput($_POST['tel03']);
		$tel_flag = cleanInput($_POST['tel_flag']);
		$last_education = cleanInput($_POST['last_education']);
		$last_education_name = getName(array("name"=>"education","value"=>$last_education));
		
		$zhuanye = cleanInput($_POST['zhuanye']);
		$job_experiencetimes = cleanInput($_POST['job_experiencetimes']);
		$job_nowstatus = cleanInput($_POST['job_nowstatus']);
		$job_experience = cleanInput($_POST['job_experience']);
		//判断违法否
		if(isset($_FILES["user_photo_url"])&&$_FILES["user_photo_url"]["type"]!=""){
			$data = $_FILES["user_photo_url"];
			$filetype = pathinfo($data["name"], PATHINFO_EXTENSION);
			$upfiletype = strtoupper($filetype);
			if($upfiletype!="JPG"&&$upfiletype!="PNG"&&$upfiletype!="GIF"&&$upfiletype!="JPEG"){
				header('Location:'.APP_URL.'mypage/personal/imgerror/');
				exit;
			}
		}
		
		//期望工作时间
		if(isset($_POST["day"])&&$_POST["day"]!=""){
			foreach ($_POST["day"] as $k=>$v){
				@$day .= $v.",";
			}
			$day = rtrim($day,",");
		}
		
		//邮件公开是否处理
		if($per_status == 1){$job_email = $email;}
		if($per_status == 2){$job_email = "非公开";}
		
		//个人简历更新
		$return = personal_update($_SESSION['kiwa_userid'],$name01,$name02,$zip01,$zip02,$add_pref,$addr01,$addr02,$eki,$email,$password,$per_status,$tel01,$tel02,$tel03,$tel_flag,$sex,$birth,$zhuanye,$last_education,$job_experiencetimes,$job_experience,$job_nowstatus,$zige,$pr,$areaid,$pref,$kinds,$types_str,$skills_str,$ensn_str,$eki_str,$kindsname,$typesname_str,$join,$day);
		
		if(!$return){
			echo "个人简历更新 失败！" ;
			exit();
		}else{
			$dir = APP_PATH."upload/img/user/";
			//アップロードファイル確認
			if(isset($_FILES["user_photo_url"])&&$_FILES["user_photo_url"]["type"]!=""){
				//アップロードファイル読み込み
				$data = $_FILES["user_photo_url"];
				$filetype = pathinfo($data["name"], PATHINFO_EXTENSION);
				//画像ファイルに名前を付ける
				$image_name = $_SESSION['kiwa_userid'].'_'.date("md").'.'.$filetype;
				//画像ファイルの保存場所
				$image_path = $dir.$image_name;
				//ファイルアップロードとエラーチェック
				if(move_uploaded_file($data["tmp_name"],$image_path)){
					//照片地址存入数据库
					user_img_update($_SESSION['kiwa_userid'],"upload/img/user/".$image_name);
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
					header('Location:'.APP_URL.'mypage/done/imgerror/');
				}
			}
			$_SESSION['kiwa_username'] = $name01."　".$name02;
			//没有参数跳到错误页面
			$send_type=$_GET['send_type'];
			if ($send_type==1){
				echo '<script>
				alert("修改成功");
				window.location="'.APP_URL.'people/user_pr/'.$_SESSION['kiwa_userid'].'";
				';
				echo '</script>';
			}else{
				header('Location:'.APP_URL.'mypage/done/personalok');
			}
		}
	}else{
		//没有参数跳到错误页面
		header('Location:'.APP_URL.'mypage/done/error');
	}
?>