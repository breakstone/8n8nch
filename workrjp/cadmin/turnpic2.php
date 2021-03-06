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
	
	if(isset($_FILES["file_upload"])){
		$dir = APP_PATH."upload/img/people/";
		$index = 0;
		$turn_pic1="";
		$turn_pic2="";
		$turn_pic3="";
		for($i=0;$i<count($_FILES['file_upload']['tmp_name']);$i++){
			$index = $i+1;
			$tmp_name = $_FILES['file_upload']['tmp_name'][$i];
			
			$filetype = pathinfo($_FILES["file_upload"]["name"][$i], PATHINFO_EXTENSION);
			//画像ファイルに名前を付ける
			$image_name = $cid.'_turn_pic'.$index.'.'.$filetype;
			//画像ファイルの保存場所
			$image_path = $dir.$image_name;
			if($tmp_name!=""){
				if(move_uploaded_file($tmp_name,$image_path)){
					if($i==0){
						$turn_pic1 = "upload/img/people/".$image_name;
					}
					if($i==1){
						$turn_pic2 = "upload/img/people/".$image_name;
					}
					if($i==2){
						$turn_pic3 = "upload/img/people/".$image_name;
					}
					
					$sql_see = "select count(*) from dtb_people_page where user_id = '$cid' and del_flg = 0";
					$count = $db->QueryItem($sql_see);
					if($count>0){
						@$sql_do = "update dtb_people_page set turn_pic1='$turn_pic1',turn_pic2='$turn_pic2',turn_pic3='$turn_pic3' where user_id = '$cid' and del_flg = 0";
					}else{
						@$sql_do = "insert into dtb_people_page set user_id = '$cid',user_type='company',turn_pic1='$turn_pic1',turn_pic2='$turn_pic2',turn_pic3='$turn_pic3'";
					}
					$db->Execute($sql_do);
					
					$smarty->assign('tell', "修改成功！");
				}else{
					//echo "写真アップロードエラー";
					echo "上传图片失败！请联系管理员";
				}
			}
		}
	}
	
	
	$sql = "select * from dtb_companyuser where company_id = '$cid' and del_flg = 0";
	$company = $db->QueryRow($sql);
	$smarty->assign('company', $company);
	
	//企业分类
	$sql_comp = "select * from mtb_company_type";
	$qi = $db->QueryArray($sql_comp);
	$smarty->assign('qi', $qi);
	
	$sql_page = "select * from dtb_people_page where user_id = '$cid' and del_flg = 0";
	$companypage = $db->QueryRow($sql_page);
	$smarty->assign('companypage', $companypage);
	$skill = "";
	if(isset($_REQUEST["company_type"])&&$_REQUEST["company_type"]!=""){
		$skill = $_REQUEST["company_type"];
		$smarty->assign('skill', $skill);
	}else{
		if($companypage["company_type"] !=""){
			$skill = $companypage["company_type"];
			$smarty->assign('skill', $skill);
		}elseif($company["skill"]!=""){
			$skills = explode(",", $company["skill"]);
			$skill = $skills[0];
			$smarty->assign('skill', $skill);
		}
	}
	
	$sql_document = "select type_english,turn_pic_num from mtb_company_type where type_name='$skill'";
	$document = $db->QueryRow($sql_document);
	$smarty->assign('document', $document);
	
	
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	$smarty->display('Cadmin/turnpic2.html');
	
?>