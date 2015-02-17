<?php
	if(!file_exists('../../_config/config.php'))
	{
		die('[index.php] _config/config.php not found');
	}
	require_once '../../_config/config.php';
	require_once '../../_includes/functions.php';
	require_once '../../_includes/SubPages.php';
	
	if($_POST["id"]){
		$company = getCompany($_POST["id"]);
		$smarty->assign("company", $company);
		$smarty->assign("id", $_POST["id"]);
		
		$sql = "select * from dtb_people_page where del_flg = 0 and user_id='$_POST[id]'";
		$company_page = $db->QueryRow($sql);
		$smarty->assign('company_page', $company_page);
		///////////////////////////////////////////////////
		//可能感兴趣的工作信息contents
		
		$sql_jobs= "select * from dtb_jobs where del_flg=0 order by rand() limit 0 , 6";
		$job1 = $db->QueryArray($sql_jobs);
		$smarty->assign('job1', $job1);
		$sql_bbs= "select * from dtb_bbs where del_flg=0 order by rand() limit 0 , 5";
		$bbs = $db->QueryArray($sql_bbs);
		$smarty->assign('bbs', $bbs);
		if($_POST["year"] == ""){
			$day = date("d");
			if($day<15){
				$year = date("Y");
				$month = date("m")-2;
				$monthyear=date("Y-m", strtotime("-2 month"));
			}else{
				$year = date("Y");
				$month = date("m")-1;
				$monthyear=date("Y-m", strtotime("-1 month"));
			}
		}else{
			$year = $_POST["year"];
			$month = $_POST["month"];
			$monthyear=$year."-".$month;
		}
		
		$smarty->assign("year",$year);
		$smarty->assign("month",$month);
		//////////////////////////////////////////////////
		if(isset($_POST["id"])&&$_POST["id"]=="141113009425"){//喜和工业
			if(isset($_POST["kid"])&&isset($_POST["password"])){
				$manager_id = cleanInput($_POST["kid"]);
				//$manager_id = mb_convert_kana($manager_id,"a","UTF-8");
				$manager_password = cleanInput($_POST["password"]);
				$sqlks = "select * from kiwa_user where staff_id='$manager_id' and passwd = '$manager_password' and bytDeleteFlag = 0";
				$staff = $db->QueryRow($sqlks);

				if($staff["staff_id"]!=""){
					$sqlgz = "select * from kiwa_mingxi where staff_id='$manager_id' and moneymonth='$monthyear' and delflag = 0";
					$gongzi = $db->QueryRow($sqlgz);
					if(count($gongzi)>0){
						$shijian=$gongzi["moneytime"];
						$shi= explode(".",$shijian);
						$fen=($shijian-$shi[0])*60;
						$fen=round($fen);
						if($fen==0){
							$fen="00";
						}
						$shidong=$shi[0].":".$fen;
						$zhigei=$gongzi["faflag"];
						if($zhigei==0){
							$zhigei="現金";
						}else{
							$zhigei="銀行振込";
						}
						$smarty->assign('shidong', $shidong);
						$smarty->assign('zhigei', $zhigei);
						$smarty->assign('gongzi', $gongzi);
					}else{
						$smarty->assign('message', "还没有数据，15号开始可以查上个月工资！");
					}	
					$smarty->assign('manager_id', $manager_id);
					$smarty->assign('manager_password', $manager_password);
						
				}else{
					header('Location:'.APP_URL.'people/kiwalogin/klogin.php?id='.$_POST["id"].'&error=1');
				}
			}
		
			$smarty->assign('what', "喜和工业");
		}
		if(isset($_POST["id"])&&$_POST["id"]=="071445274801"){//WAYS
			if(isset($_POST["kid"])&&isset($_POST["password"])){
				$manager_id = cleanInput($_POST["kid"]);
				//$manager_id = mb_convert_kana($manager_id,"a","UTF-8");
				$manager_password = cleanInput($_POST["password"]);
				$sqlks = "select * from ways_user where staff_id='$manager_id' and passwd = '$manager_password' and bytDeleteFlag = 0";
				$staff = $db->QueryRow($sqlks);

				if($staff["staff_id"]!=""){
					$sqlgz = "select * from ways_mingxi where staff_id='$manager_id' and moneymonth='$monthyear' and delflag = 0";
					$gongzi = $db->QueryRow($sqlgz);
					if(count($gongzi)>0){
						$shijian=$gongzi["moneytime"];
						$shi= explode(".",$shijian);
						$fen=($shijian-$shi[0])*60;
						$fen=round($fen);
						if($fen==0){
							$fen="00";
						}
						$shidong=$shi[0].":".$fen;
						$zhigei=$gongzi["faflag"];
						if($zhigei==0){
							$zhigei="現金";
						}else{
							$zhigei="銀行振込";
						}
						$smarty->assign('shidong', $shidong);
						$smarty->assign('zhigei', $zhigei);
						$smarty->assign('gongzi', $gongzi);
					}else{
						$smarty->assign('message', "还没有数据，每个月15号开始可以查上个月工资！");
					}	
					$smarty->assign('manager_id', $manager_id);
					$smarty->assign('manager_password', $manager_password);
				}else{
					header('Location:'.APP_URL.'people/kiwalogin/klogin.php?id='.$_POST["id"].'&error=1');
				}
			}
			$smarty->assign('what', "WAYS");
		}
		if(isset($_POST["id"])&&$_POST["id"]=="161559139290"){//ESS
			if(isset($_POST["kid"])&&isset($_POST["password"])){
				$manager_id = cleanInput($_POST["kid"]);
				//$manager_id = mb_convert_kana($manager_id,"a","UTF-8");
				$manager_password = cleanInput($_POST["password"]);
				$sqlks = "select * from ess_user where staff_id='$manager_id' and passwd = '$manager_password' and bytDeleteFlag = 0";
				$staff = $db->QueryRow($sqlks);

				if($staff["staff_id"]!=""){
					$sqlgz = "select * from ess_mingxi where staff_id='$manager_id' and moneymonth='$monthyear' and delflag = 0";
					$gongzi = $db->QueryRow($sqlgz);
					$shijian=$gongzi["moneytime"];
					if(count($gongzi)>0){
						$shijian=$gongzi["moneytime"];
						$shi= explode(".",$shijian);
						$fen=($shijian-$shi[0])*60;
						$fen=round($fen);
						if($fen==0){
							$fen="00";
						}
						$shidong=$shi[0].":".$fen;
						$zhigei=$gongzi["faflag"];
						if($zhigei==0){
							$zhigei="現金";
						}else{
							$zhigei="銀行振込";
						}
						$smarty->assign('shidong', $shidong);
						$smarty->assign('zhigei', $zhigei);
						$smarty->assign('gongzi', $gongzi);
					}else{
						$smarty->assign('message', "还没有数据，每个月15号开始可以查上个月工资！");
					}	
					$smarty->assign('manager_id', $manager_id);
					$smarty->assign('manager_password', $manager_password);
				}else{
					header('Location:'.APP_URL.'people/kiwalogin/klogin.php?id='.$_POST["id"].'&error=1');
				}
			}
			$smarty->assign('what', "ESS");
		}
		
		
		
		//固定引入参数
		$smarty->assign('BASE_URL', APP_URL);
		$smarty->assign('THEME', THEME);
	//	$smarty->display('mobile/People/KiwaLogin/see.html');
	if(isMobile()){
			$smarty->display('mobile/People/KiwaLogin/see.html');
		}else{
			$smarty->display('People/KiwaLogin/see.html');
		}
	}else{
		echo "系统错误！";
	}
?>