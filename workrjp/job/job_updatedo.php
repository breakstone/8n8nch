<?php
	// config
	if(!file_exists('./../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	
	require_once './../_config/config.php';
	require_once './../_includes/functions.php';
	if((isset($_SESSION["kiwa_userid"])&&$_SESSION["kiwa_userid"]!="")||(isset($_SESSION["kiwa_companyid"])&&$_SESSION["kiwa_companyid"]!="")){
			$job_id=cleanInput($_POST["id"]);
			//收费，免费
			$shoufei = cleanInput($_POST['shoufei']);
			
			$job_title = $shoufei.cleanInput($_POST['job_title']);
			$contents = str_replace("<br>","\r\n",$_POST['contents']);
			$contents = cleanInput($contents);
			$requirements = str_replace("<br>","\r\n",$_POST["requirements"]);
			$requirements = cleanInput($requirements);
			$lianxi = cleanInput($_POST['lianxi']);
			$numbers = cleanInput($_POST['numbers']);
			$over_date = cleanInput(@$_POST['over_date']);
			$over_date = date("Y-m-d",time()+$over_date*86400);
			
			foreach(@$_POST['zz'] as $z){
				@$zz_name .= $z.",";
			}
			$zz_name = rtrim($zz_name,",");
		
			###职种，业种处理###
			$kinds = cleanInput($_POST['kinds']);
			$kindsname = "";
			$types = "";
			$typesname = "";
			foreach($_POST['types'] as $t){
				$ktrow = getKindTypename($t);
				$kindsname = $ktrow["kindsname"];
				$types .= $t.",";
				$typesname .= $ktrow["typesname"].",";
			}
			$types = rtrim($types,",");
			$typesname = rtrim($typesname,",");
		
			###工作地，处理###
			$areaid = cleanInput($_POST['areaid']);
			$pref = cleanInput($_POST['pref']);
			$prefname = getName(array("name"=>"pref","value"=>$pref));
		
			$station_cd="";
			$station_name="";
			$line_num="";
			if(isset($_POST["ensn"])&&$_POST["ensn"]!=""){//線
				foreach($_POST["ensn"] as $l){
					$line_num .= $l.",";
				}
				$line_num = rtrim($line_num,",");
			}
			if(isset($_POST["eki"])&&$_POST["eki"]!=""){//駅
				foreach($_POST["eki"] as $s){
					$station_cd .= $s.",";
					$station_name .= getName(array("name"=>"eki","value"=>$s)).",";
				}
				$station_cd = rtrim($station_cd,",");
				$station_name = rtrim($station_name,",");
			}
		
		
			$moneyid = cleanInput($_POST['moneyid']);
			$moneyname = getName(array("name"=>"money","value"=>$moneyid));
		
			foreach(@$_POST['employid'] as $e){
				@$employid .= $e.",";
				@$employname .= getName(array("name"=>"employ","value"=>$e)).",";
			}
			$employid = rtrim($employid,",");
			$employname = rtrim($employname,",");
		
			foreach(@$_POST['hopeid'] as $h){
				@$hopedateid .= $h.",";
				@$hopedatename .= getName(array("name"=>"hope","value"=>$h)).",";
				
			}
			$hopedateid = rtrim($hopedateid,",");
			$hopedatename = rtrim($hopedatename,",");
			$w_d = cleanInput($_POST['w_d']);
			
		
			$condition_id="";
			$condition_name="";
			if(isset($_POST["conditions"])&&$_POST["conditions"]!=""){//駅
				foreach($_POST["conditions"] as $c){
					$condition_id .= $c.",";
					$condition_name .= getName(array("name"=>"condition","value"=>$c)).",";
				}
				$condition_id = rtrim($condition_id,",");
				$condition_name = rtrim($condition_name,",");
			}
			$kindsID = $db->real_escape_string($kinds);
			$kindsname = $db->real_escape_string($kindsname);
			$typesID = $db->real_escape_string($types);
			$typesname = $db->real_escape_string($typesname);
			$contents = $db->real_escape_string($contents);
			$employ_mothod = $db->real_escape_string($employid);
			$employ_mothod_name = $db->real_escape_string($employname);
			$area_cd = $db->real_escape_string($areaid);
			$pref_cd = $db->real_escape_string($pref);
			$pref_name = $db->real_escape_string($prefname);
			$line_num = $db->real_escape_string($line_num);
			$station_cd = $db->real_escape_string($station_cd);
			$station_name = $db->real_escape_string($station_name);
			
			$zz_name = $db->real_escape_string($zz_name);
			$w_d = $db->real_escape_string($w_d);
			
			$hope_date = $db->real_escape_string($hopedateid);
			$hope_date_name = $db->real_escape_string($hopedatename);
			$moneyid = $db->real_escape_string($moneyid);
			$money_name = $db->real_escape_string($moneyname);
			$requirements = $db->real_escape_string($requirements);
			$lianxi = $db->real_escape_string($lianxi);
			$numbers = $db->real_escape_string($numbers);
			$condition_id = $db->real_escape_string($condition_id);
			$condition_name = $db->real_escape_string($condition_name);
			$sql = "update dtb_jobs set level='$zz_name',hope_week_day=$w_d,
			job_title='$job_title',kindsID='$kindsID',kindsname='$kindsname',typesID='$typesID',typesname='$typesname',
			contents='$contents',employed_method='$employ_mothod',employed_method_name='$employ_mothod_name',area_cd='$area_cd',pref_cd='$pref_cd',pref_name='$pref_name',line_num='$line_num',station_cd='$station_cd',station_name='$station_name',
			hope_date='$hope_date',hope_date_name='$hope_date_name',moneyid='$moneyid',money_name='$money_name',requirements='$requirements',lianxi='$lianxi',numbers='$numbers',condition_id='$condition_id',condition_name='$condition_name' where job_id = '$job_id'";
			if($db->Execute($sql)){
				echo '<script>
						window.location="'.APP_URL.'job/show/'.$job_id.'";
						alert("修改成功");';
				
				echo '</script>';
				
			}else{
				echo '<script>   
						window.location="'.APP_URL.'job/show/'.$job_id.'";
						alert("修改失败");';
				echo '</script>';
			}

	}else{
		//固定引入参数
		$smarty->assign('BASE_URL', APP_URL);
		$smarty->assign('THEME', THEME);
		$smarty->display('error.html');
	}

?>