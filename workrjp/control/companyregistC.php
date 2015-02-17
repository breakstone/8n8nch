<?php
	// config
	if(!file_exists('./../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	
	require_once './../_config/config.php';
	require_once './../_includes/functions.php';
	
	if(isset($_POST['company_email'])&&$_POST['company_email']!=""){
		$Cemail = cleanInput($_POST['company_email']);
		if(emailRegist($Cemail)){
			//处理参数
			$company_name = cleanInput($_POST['company_name']);
			$company_manager = cleanInput($_POST['company_manager']);
			//处理設立年月
			$foundation_dateYear = cleanInput($_POST['foundation_dateYear']);
			$foundation_dateMonth = cleanInput($_POST['foundation_dateMonth']);
			if($foundation_dateYear!=""){
				$foundation_dateYear = $foundation_dateYear." 年 ";
			}
			if($foundation_dateMonth!=""){
				$foundation_dateMonth = $foundation_dateMonth." 月 ";
			}
			$foundation_date = $foundation_dateYear.$foundation_dateMonth;
			
			$company_money = cleanInput($_POST['company_money']);
			$company_url = cleanInput($_POST['company_url']);
			$zip01 = cleanInput($_POST['zip01']);
			$zip02 = cleanInput($_POST['zip02']);
			$add_pref = cleanInput($_POST['add_pref']);
			$addr01 = cleanInput($_POST['addr01']);
			$addr02 = cleanInput($_POST['addr02']);
			
			$areaid = cleanInput($_POST['areaid']);
			$pref = cleanInput($_POST['pref']);
// 			$kinds = cleanInput($_POST['kinds']);
// 			$kindsname = cleanInput($_POST['kindsname']);
// 			$types_str = cleanInput($_POST['types_str']);
// 			$typesname_str = cleanInput($_POST['typesname_str']);

			$kinds = "";
			$kindsname = "";
			$types_str = "";
			$typesname_str = "";
			$skills_str = cleanInput($_POST['skills_str']);
			
			foreach ($_POST['ensn'] as $en){
				$ensn_str .= $en.",";
			}
			$ensn_str = rtrim($ensn_str,",");
			$ensn_str = cleanInput($ensn_str);
			foreach ($_POST['eki'] as $ei){
				$eki_str .= $ei.",";
			}
			$eki_str = rtrim($eki_str,",");
			$eki_str = cleanInput($eki_str);
			
			$tel01 = cleanInput($_POST['tel01']);
			$tel02 = cleanInput($_POST['tel02']);
			$tel03 = cleanInput($_POST['tel03']);
			$fax01 = cleanInput($_POST['fax01']);
			$fax02 = cleanInput($_POST['fax02']);
			$fax03 = cleanInput($_POST['fax03']);
			$company_email = cleanInput($_POST['company_email']);
			$password = cleanInput($_POST['password']);
			
			if(companyregistC($company_name,$company_manager,$foundation_date,$company_money,$company_url,$zip01,$zip02,$add_pref,$addr01,$addr02,$company_form,$tel01,$tel02,$tel03,$fax01,$fax02,$fax03,$company_email,$password,$areaid,$pref,$kinds,$kindsname,$types_str,$typesname_str,$skills_str,$ensn_str,$eki_str)){
				
				//仮登録完成
				header('Location:'.APP_URL.'regist/done/company_karegist');
			}else{
				//仮登録失敗
				header('Location:'.APP_URL.'regist/done/error');
			}
		}else{
			//邮箱二次校验不成功
			header('Location:'.APP_URL.'regist/company/?emailerror');
		}
		
	}else{
		//没有参数跳到错误页面
		header('Location:'.APP_URL.'regist/done/error');
	}
	

?>