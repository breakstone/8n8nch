<?php
	// config
	if(!file_exists('../_config/config.php'))
	{
		die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	
	
	//固定引入参数
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	if((isset($_SESSION["kiwa_userid"])&&$_SESSION["kiwa_userid"]!="")||(isset($_SESSION["kiwa_companyid"])&&$_SESSION["kiwa_companyid"]!="")){
		//登陆发布需求
		$live_id = date("is").rand(1000,9999);//需求id
		$live_title = cleanInput($_POST["live_title"]);//需求题目
		$lianxi = cleanInput($_POST["lianxi_type"]).":".cleanInput($_POST["lianxi_hao"]);
		$live_content = cleanInput($_POST["live_content"])."\n\n".$lianxi;//需求内容
		$service_id = cleanInput($_POST["service_id"]);//需求分类id
		
		$service = getService($service_id);
		$service_name = cleanInput($service["service_name"]);//需求分类名字
		
		if(@$_SESSION["kiwa_userid"]!=""){
			$user_id = $_SESSION["kiwa_userid"];//发布者id
			$user_name = $_SESSION["kiwa_username"];//发布者名字
			$user_type = "user";//发布者类型
		}else{
			$user_id = $_SESSION["kiwa_companyid"];
			$user_name = $_SESSION["kiwa_companyname"];
			$user_type = "company";
		}
		$live_want = cleanInput($_POST["wanted"]);//需求供求
		
		$area_cd = cleanInput($_POST["areaid"]);//区域id
		$pref_cd = cleanInput($_POST["pref"]);//都县id
		$sql_pre = "select name from mtb_pref where pref_cd = '$pref_cd'";
		$pref_name = $db->QueryItem($sql_pre);//都县名字
		$line_num="";$station_name="";$station_cd="";
		if(is_array(@$_POST['ensn'])){
			foreach ($_POST['ensn'] as $en){
				$line_num .= $en.",";
			}
			$line_num = rtrim($line_num,",");//线路id
		}else{
			$line_num = @$_POST['ensn'];
		}
		if(is_array(@$_POST['eki'])){
			foreach ($_POST['eki'] as $ei){
				$sql_sta = "select station_name from mtb_station where station_cd = '$ei'";
				$station_n = $db->QueryItem($sql_sta);
				$station_name .= $station_n.",";
				$station_cd .= $ei.",";
			}
			$station_cd = rtrim($station_cd,",");//车站id
			$station_name = rtrim($station_name,",");//车站name
		}else{
			$station_cd = @$_POST['eki'];//车站id
			$sql_sta = "select station_name from mtb_station where station_cd = '$station_cd'";
			$station_name = $db->QueryItem($sql_sta);//车站name
		}
		
		$type = "";
		$type_name = "type_".$live_want;
		if(@$_POST[$type_name]!=""){
			if(is_array(@$_POST[$type_name])){
				foreach($_POST[$type_name] as $t){
					$type .= $t.",";
				}
				$type = rtrim($type,",");//内容类别
			}else{
				$type = @cleanInput($_POST[$type_name]);
			}
		}else{
			if(is_array(@$_POST["type"])){
				foreach($_POST["type"] as $t){
					$type .= $t.",";
				}
				$type = rtrim($type,",");//内容类别
			}else{
				$type = @cleanInput($_POST["type"]);
			}
		}
		
		if(@$_POST["zhong"]!=""){
			$zhong = cleanInput($_POST["zhong"]);//宠物种类
		}else{
			$zhong = "";
		}
		
		if($service_id=="s1" or $service_id=="s2"){//内容价格
			if($live_want == 1){
				$live_money_s = @cleanInput($_POST["money_1_s"]);
				$live_money_e = @cleanInput($_POST["money_1_e"]);
			}else{
				$live_money_s = @cleanInput($_POST["money_0"]);
				$live_money_e = @cleanInput($_POST["money_0"]);
			}
		}else{
// 			if($live_want == 1){
// 				$live_money_s = @cleanInput($_POST["money_1"]);
// 				$live_money_e = @cleanInput($_POST["money_1"]);
// 			}else{
// 				$live_money_s = @cleanInput($_POST["money_0"]);
// 				$live_money_e = @cleanInput($_POST["money_0"]);
// 			}
			$live_money_s = @cleanInput($_POST["money_0"]);
			$live_money_e = @cleanInput($_POST["money_0"]);
		}
		
		$home_geju = "";
		$geju_name = "geju_".$live_want;
		if(is_array($_POST[$geju_name])){
			foreach(@$_POST[$geju_name] as $g){
				$home_geju .= $g.",";
			}
			$home_geju = rtrim($home_geju,",");
		}else{
			$home_geju = cleanInput($_POST[$geju_name]);//格局
		}
		
		if($service_id=="s1" or $service_id=="s2"){//面积
			if($live_want == 1){
				$home_mianji_s = @cleanInput($_POST["mianji_1_s"]);
				$home_mianji_e = @cleanInput($_POST["mianji_1_e"]);
			}else{
				$home_mianji_s = @cleanInput($_POST["mianji_0"]);
				$home_mianji_e = @cleanInput($_POST["mianji_0"]);
			}
		}
		
		$home_juli = @cleanInput($_POST["juli"]);//车站距离
		$home_year = @cleanInput($_POST["home_year"]);//房屋年数
		$weixiu_shangmen = @cleanInput($_POST["weixiu_shangmen"]);//上门服务
		
		if(isset($_POST["how"])&&@$_POST["how"]!=""){
			$how = cleanInput($_POST["how"]);//宠物种类
		}else{
			$how = "";
		}
		
		$create_date = date("Y-m-d H:i:s");//创建时间
		
		//上传图片
		if(live_save($live_id,$live_title,$live_content,$live_want,$service_id,$service_name,$user_id,$user_name,$user_type,$area_cd,$pref_cd,$pref_name,$line_num,$station_cd,$station_name,$type,$live_money_s,$live_money_e,$create_date,$home_geju,$home_mianji_s,$home_mianji_e,$home_juli,$home_year,$weixiu_shangmen,$zhong,$how)){
			$dir = APP_PATH."upload/img/live/";
			$live_photo="";
			$index = 0;
			foreach ($_FILES["file_upload"]["error"] as $key => $error) {
				$index = $index + 1;
				if ($error == UPLOAD_ERR_OK) {
					$tmp_name = $_FILES["file_upload"]["tmp_name"][$key];
					$filetype = pathinfo($_FILES["file_upload"]["name"][$key], PATHINFO_EXTENSION);
					
					if(strtoupper($filetype) != "JPG" and strtoupper($filetype) != "JPEG" and strtoupper($filetype) != "PNG" and strtoupper($filetype) != "GIF"){
						echo "系统错误，请联系管理员！";
						exit;
					}
					
					//画像ファイルに名前を付ける
					$image_name = $live_id.'_'.$index.'.'.$filetype;
					//画像ファイルの保存場所
					$image_path = $dir.$image_name;
					if(move_uploaded_file($tmp_name,$image_path)){
						$live_photo .= "upload/img/live/".$image_name.",";
						//画像縮小チェック
						//画像読み込み（JPEG,PNG,GIFなどに対応）
						//file_get_contents($image_path);
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
					}else{
						//echo "写真アップロードエラー";
						echo "上传图片失败！请联系管理员";
					}
				}
			}
			$live_photo = rtrim($live_photo,",");
			//照片地址存入数据库
			live_img_update($live_id,$live_photo);
			
			header('Location:'.APP_URL.'requirement/saved/');
		}
	}elseif(isset($_POST["rgister_B"])&&$_POST["usertype"]!=""&&$_POST["register_name"]!=""&&$_POST["register_email"]!=""&&$_POST["register_pw"]!=""){
		//注册发布需求
		//先注册
		if($_POST["usertype"] == "user"){
			$email = cleanInput($_POST["register_email"]);
			$password = cleanInput($_POST["register_pw"]);
			$kana01 = "";$kana02="";
			$name01 = cleanInput($_POST["register_name"]);$name02 = "";
			if(registC($name01,$name02,$kana01,$kana02,$email,$password,APP_URL)){
				$pw = md5($password);
				$sqluser = "select user_id,name01,name02 from dtb_user where email = '$email' and password = '$pw' and del_flg = 0";
				$user = $db->QueryRow($sqluser);
				$_SESSION['kiwa_userid'] = $user['user_id'];
				$_SESSION['kiwa_username'] = $user['name01'];
			}
			
		}elseif($_POST["usertype"] == "company"){
			$company_email = cleanInput($_POST["register_email"]);
			$company_name = cleanInput($_POST["register_name"]);
			$password = cleanInput($_POST["register_pw"]);
			$company_manager="";
			$foundation_date="";$company_money="";$company_url="";$zip01="";$zip02="";$pref="";$addr01="";$addr02="";$company_form=array();$tel01="";$tel02="";
			$tel03="";$fax01="";$fax02="";$fax03="";$areaid="";$pref="";$kinds="";$kindsname="";$types_str="";$typesname_str="";$skills_str="";$ensn_str="";$eki_str="";
			if(companyregistC($company_name,$company_manager,$foundation_date,$company_money,$company_url,$zip01,$zip02,$pref,$addr01,$addr02,$company_form,$tel01,$tel02,$tel03,$fax01,$fax02,$fax03,$company_email,$password,$areaid,$pref,$kinds,$kindsname,$types_str,$typesname_str,$skills_str,$ensn_str,$eki_str)){
				$pw = md5($password);
				$sqlcompany = "select company_id,company_name from dtb_companyuser where company_email = '$company_email' and password = '$pw' and del_flg = 0";
				$companyuser = $db->QueryRow($sqlcompany);
				$_SESSION['kiwa_companyid'] = $companyuser['company_id'];
				$_SESSION['kiwa_companyname'] = $companyuser['company_name'];
			}
		}
		
		//保存需求
		$live_id = date("is").rand(1000,9999);//需求id
		$live_title = cleanInput($_POST["live_title"]);//需求题目
		$lianxi = cleanInput($_POST["lianxi_type"]).":".cleanInput($_POST["lianxi_hao"]);
		$live_content = cleanInput($_POST["live_content"])."\n\n".$lianxi;//需求内容
		$service_id = cleanInput($_POST["service_id"]);//需求分类id
		
		$service = getService($service_id);
		$service_name = cleanInput($service["service_name"]);//需求分类名字
		
		if(@$_SESSION["kiwa_userid"]!=""){
			$user_id = $_SESSION["kiwa_userid"];//发布者id
			$user_name = $_SESSION["kiwa_username"];//发布者名字
			$user_type = "user";//发布者类型
		}else{
			$user_id = $_SESSION["kiwa_companyid"];
			$user_name = $_SESSION["kiwa_companyname"];
			$user_type = "company";
		}
		$live_want = cleanInput($_POST["wanted"]);//需求供求
		
		$area_cd = cleanInput($_POST["areaid"]);//区域id
		$pref_cd = cleanInput($_POST["pref"]);//都县id
		$sql_pre = "select name from mtb_pref where pref_cd = '$pref_cd'";
		$pref_name = $db->QueryItem($sql_pre);//都县名字
		$line_num="";$station_name="";$station_cd="";
		if(is_array(@$_POST['ensn'])){
			foreach ($_POST['ensn'] as $en){
				$line_num .= $en.",";
			}
			$line_num = rtrim($line_num,",");//线路id
		}else{
			$line_num = @$_POST['ensn'];
		}
		if(is_array(@$_POST['eki'])){
			foreach ($_POST['eki'] as $ei){
				$sql_sta = "select station_name from mtb_station where station_cd = '$ei'";
				$station_n = $db->QueryItem($sql_sta);
				$station_name .= $station_n.",";
				$station_cd .= $ei.",";
			}
			$station_cd = rtrim($station_cd,",");//车站id
			$station_name = rtrim($station_name,",");//车站name
		}else{
			$station_cd = @$_POST['eki'];//车站id
			$sql_sta = "select station_name from mtb_station where station_cd = '$station_cd'";
			$station_name = $db->QueryItem($sql_sta);//车站name
		}
		
		$type = "";
		$type_name = "type_".$live_want;
		if(@$_POST[$type_name]!=""){
			if(is_array(@$_POST[$type_name])){
				foreach($_POST[$type_name] as $t){
					$type .= $t.",";
				}
				$type = rtrim($type,",");//内容类别
			}else{
				$type = @cleanInput($_POST[$type_name]);
			}
		}else{
			if(is_array(@$_POST["type"])){
				foreach($_POST["type"] as $t){
					$type .= $t.",";
				}
				$type = rtrim($type,",");//内容类别
			}else{
				$type = @cleanInput($_POST["type"]);
			}
		}
		
		if(@$_POST["zhong"]!=""){
			$zhong = cleanInput($_POST["zhong"]);//宠物种类
		}else{
			$zhong = "";
		}
		
		if($service_id=="s1" or $service_id=="s2"){//内容价格
			if($live_want == 1){
				$live_money_s = @cleanInput($_POST["money_1_s"]);
				$live_money_e = @cleanInput($_POST["money_1_e"]);
			}else{
				$live_money_s = @cleanInput($_POST["money_0"]);
				$live_money_e = @cleanInput($_POST["money_0"]);
			}
		}else{
			if($live_want == 1){
				$live_money_s = @cleanInput($_POST["money_1"]);
				$live_money_e = @cleanInput($_POST["money_1"]);
			}else{
				$live_money_s = @cleanInput($_POST["money_0"]);
				$live_money_e = @cleanInput($_POST["money_0"]);
			}
		}
		
		$home_geju = "";
		$geju_name = "geju_".$live_want;
		if(is_array($_POST[$geju_name])){
			foreach(@$_POST[$geju_name] as $g){
				$home_geju .= $g.",";
			}
			$home_geju = rtrim($home_geju,",");
		}else{
			$home_geju = cleanInput($_POST[$geju_name]);//格局
		}
		
		if($service_id=="s1" or $service_id=="s2"){//面积
			if($live_want == 1){
				$home_mianji_s = @cleanInput($_POST["mianji_1_s"]);
				$home_mianji_e = @cleanInput($_POST["mianji_1_e"]);
			}else{
				$home_mianji_s = @cleanInput($_POST["mianji_0"]);
				$home_mianji_e = @cleanInput($_POST["mianji_0"]);
			}
		}
		
		$home_juli = @cleanInput($_POST["juli"]);//车站距离
		$home_year = @cleanInput($_POST["home_year"]);//房屋年数
		$weixiu_shangmen = @cleanInput($_POST["weixiu_shangmen"]);//上门服务
		
		if(isset($_POST["how"])&&@$_POST["how"]!=""){
			$how = cleanInput($_POST["how"]);//宠物种类
		}else{
			$how = "";
		}
		
		$create_date = date("Y-m-d H:i:s");//创建时间
		if(live_save($live_id,$live_title,$live_content,$live_want,$service_id,$service_name,$user_id,$user_name,$user_type,$area_cd,$pref_cd,$pref_name,$line_num,$station_cd,$station_name,$type,$live_money_s,$live_money_e,$create_date,$home_geju,$home_mianji_s,$home_mianji_e,$home_juli,$home_year,$weixiu_shangmen,$zhong,$how)){
			$dir = APP_PATH."upload/img/live/";
			$live_photo="";
			$index = 0;
			foreach ($_FILES["file_upload"]["error"] as $key => $error) {
				$index = $index + 1;
				if ($error == UPLOAD_ERR_OK) {
					$tmp_name = $_FILES["file_upload"]["tmp_name"][$key];
					$filetype = pathinfo($_FILES["file_upload"]["name"][$key], PATHINFO_EXTENSION);
					//画像ファイルに名前を付ける
					$image_name = $live_id.'_'.$index.'.'.$filetype;
					//画像ファイルの保存場所
					$image_path = $dir.$image_name;
					if(move_uploaded_file($tmp_name,$image_path)){
						$live_photo .= "upload/img/live/".$image_name.",";
						//画像縮小チェック
						//画像読み込み（JPEG,PNG,GIFなどに対応）
						//file_get_contents($image_path);
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
					}else{
						//echo "写真アップロードエラー";
						echo "上传图片失败！请联系管理员";
					}
				}
			}
			$live_photo = rtrim($live_photo,",");
			
			//照片地址存入数据库
			live_img_update($live_id,$live_photo);
			header('Location:'.APP_URL.'requirement/saved/');
		}
	}else{
		echo "发布失败，请联系管理员！";
	}
 	
 	function live_save($live_id,$live_title,$live_content,$live_want,$service_id,$service_name,$user_id,$user_name,$user_type,$area_cd,$pref_cd,$pref_name,$line_num,$station_cd,$station_name,$type,$live_money_s,$live_money_e,$create_date,$home_geju,$home_mianji_s,$home_mianji_e,$home_juli,$home_year,$weixiu_shangmen,$zhong,$how){
 		global $db;
 		$live_id = $db->real_escape_string($live_id);
 		$live_title = $db->real_escape_string($live_title);
 		$live_content = $db->real_escape_string($live_content);
 		$live_content = $live_content."\n 联系我时，请说是在帮帮网上看到的，谢谢！";
 		$live_want = $db->real_escape_string($live_want);
 		$service_id = $db->real_escape_string($service_id);
 		$service_name = $db->real_escape_string($service_name);
 		$area_cd = $db->real_escape_string($area_cd);
 		$pref_cd = $db->real_escape_string($pref_cd);
 		$line_num = $db->real_escape_string($line_num);
 		$station_cd = $db->real_escape_string($station_cd);
 		$type = $db->real_escape_string($type);
 		$live_money_s = $db->real_escape_string($live_money_s);
 		$live_money_e = $db->real_escape_string($live_money_e);
 		$home_geju = $db->real_escape_string($home_geju);
 		$home_year = $db->real_escape_string($home_year);
 		$weixiu_shangmen = $db->real_escape_string($weixiu_shangmen);
 		$zhong = $db->real_escape_string($zhong);
 		$how = $db->real_escape_string($how);
 		if($live_want==""){$live_want='null';}
 		if($weixiu_shangmen==""){$weixiu_shangmen='null';}
 		
 		$sql_see = "select create_date from dtb_lives where user_id='$user_id' order by create_date desc";
 		$cd = $db->QueryItem($sql_see);
 		if($cd==""||strtotime(date("Y-m-d H:i:s"))-strtotime($cd) > 600){
 			$sql="insert into dtb_lives set live_id='$live_id',live_title='$live_title',live_content='$live_content',live_want=$live_want,service_id='$service_id',service_name='$service_name',user_id='$user_id',user_name='$user_name',user_type='$user_type',
 			area_cd='$area_cd',pref_cd='$pref_cd',pref_name='$pref_name',line_num='$line_num',station_cd='$station_cd',station_name='$station_name',type='$type',live_money_s='$live_money_s',live_money_e='$live_money_e',create_date='$create_date',
 			home_geju='$home_geju',home_mianji_s='$home_mianji_s',home_mianji_e='$home_mianji_e',home_juli='$home_juli',home_year='$home_year',weixiu_shangmen=$weixiu_shangmen,zhong='$zhong',how='$how'";
 			
 			if($db->Execute($sql)){
 				
 				//存日志表
 				dolog($user_id, $user_type, "live", $live_id, $live_title, "insert");
 				pointsDo($user_id,$user_type,10);
 				
 				return true;
 			}else{
 				return false;
 			}		
 		}else{
 			echo "每10分钟只能发布一次信息，谢谢合作！";
 		}
 		
 	}
 	
?>