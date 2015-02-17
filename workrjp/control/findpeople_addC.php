<?php
	// config
	if(!file_exists('./../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	
	require_once './../_config/config.php';
	require_once './../_includes/functions.php';
	//发表求人
	if(isset($_POST['job_title'])&&isset($_POST['contents'])&&isset($_SESSION['kiwa_companyname'])&&isset($_SESSION['kiwa_companyid'])){
		//发表求人
		$job_title = cleanInput($_POST['job_title']);
		$contents = str_replace("<br>","\r\n",$_POST['contents']);
		$contents = cleanInput($contents);
		$requirements = str_replace("<br>","\r\n",$_POST["requirements"]);
		$requirements = cleanInput($requirements);
		$numbers = cleanInput($_POST['numbers']);
		$over_date = cleanInput($_POST['over_date']);
		$over_date = date("Y-m-d",time()+$over_date*86400);
		
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

		$employid = cleanInput($_POST['employid']);
		$employname = getName(array("name"=>"employ","value"=>$employid));
		
		$hopedateid = cleanInput($_POST['hopeid']);
		$hopedatename = getName(array("name"=>"hope","value"=>$hopedateid));
		
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
		$now = date("Y-d-m H:i:s");
		
		//发表求人处理
		$return = findpeople_add($_SESSION['kiwa_companyid'],$_SESSION['kiwa_companyname'],"",$now,$job_title,$kinds,$kindsname,$types,$typesname,$contents,$employid,$employname,$areaid,$pref,$prefname,$line_num,$station_cd,$station_name,$hopedateid,$hopedatename,$moneyid,$moneyname,$requirements,$numbers,$over_date,$condition_id,$condition_name);
	
		if(!$return){
			if(!$return) echo "发表求人处理 失败！" ;exit();
		}else{
			//进行数据匹配，推荐发送站内短信
			if(isset($_POST['types'])&&$_POST['types']!=""){
				$types = $_POST['types'];
			}else{
				$types = "";
			}
			if(isset($_POST['ensn'])&&$_POST['ensn']!=""){
				$lines = $_POST['ensn'];
			}else{
				$lines = "";
			}
			if(isset($_POST['eki'])&&$_POST['eki']!=""){
				$chezhan = $_POST['eki'];
			}else{
				$chezhan = "";
			}
			//业种 职种 区域 市 线路 车站 薪金 雇佣形式 勤务时间
			people_recommend($kinds,$types,$areaid,$pref,$lines,$chezhan,$moneyid,$employid,$hopedateid,$job_title,APP_URL);
			
			//没有参数跳到错误页面
			header('Location:'.APP_URL.'companypage/done/addok');
		}
	}else{
		//没有参数跳到错误页面
		header('Location:'.APP_URL.'companypage/done/error');
	}
?>