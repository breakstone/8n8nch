<?php
// config
if(!file_exists('./../_config/config.php'))
{
	die('[index.php] _config/config.php not found');
}

require_once './../_config/config.php';
require_once './../_includes/functions.php';
//发表求人
if((isset($_SESSION["kiwa_userid"])&&$_SESSION["kiwa_userid"]!="")||(isset($_SESSION["kiwa_companyid"])&&$_SESSION["kiwa_companyid"]!=""))
	{

		$table="dtb_job_ls";
		$id=cleanInput($_POST["id"]);
		$job = getAllInfo("job_ls_id='$id'",0, 1,$table);
		
		if ((isset($_SESSION["kiwa_companyid"])&&$_SESSION["kiwa_companyid"]=="000001") ||$job[0]["user_id"]==@$_SESSION["kiwa_userid"] || $job[0]["user_id"]==@$_SESSION["kiwa_companyid"])
		{

			$job_title = cleanInput($_POST['job_title']);
			$contents = str_replace("<br>","\r\n",$_POST['contents']);
			$contents = cleanInput($contents);
			
			$lianxi = cleanInput($_POST['lianxi']);
			
			$starttime = cleanInput(@$_POST['starttime']);
			$endtime = cleanInput(@$_POST['endtime']);
			###工作地，处理###
			$areaid = cleanInput($_POST['areaid']);
			$pref_cd = cleanInput($_POST['pref']);
			$prefname = getName(array("name"=>"pref","value"=>$pref_cd));
			
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
			//发表求人处理
			$return = update_work_ls($id,$job_title,$contents,$areaid,$pref_cd,$prefname,$line_num,$station_cd,$station_name,$lianxi,$starttime,$endtime);
			//$return=true;
			if(!$return){
				if(!$return) echo "修改信息 失败！" ;exit();
			}else{
				//进行数据匹配，推荐发送站内短信
				// 		if(isset($_POST['types'])&&$_POST['types']!=""){
				// 			$types = $_POST['types'];
				// 		}else{
				// 			$types = "";
				// 		}
				// 		if(isset($_POST['ensn'])&&$_POST['ensn']!=""){
				// 			$lines = $_POST['ensn'];
				// 		}else{
				// 			$lines = "";
				// 		}
				// 		if(isset($_POST['eki'])&&$_POST['eki']!=""){
				// 			$chezhan = $_POST['eki'];
				// 		}else{
				// 			$chezhan = "";
				// 		}
				// 		//业种 职种 区域 市 线路 车站 薪金 雇佣形式 勤务时间
				// 		people_recommend($kinds,$types,$areaid,$pref,$lines,$chezhan,$moneyid,$employid,$hopedateid,$job_title,APP_URL);
				/*if ($send_type==1){
			
				echo '<script>
				alert("发布成功");
				window.location="'.APP_URL.'people/show/'.$usertype.'_'.$userid.'";';
				echo '</script>';
				}else{
				//没有参数跳到错误页面
				header('Location:'.APP_URL.'requirement/saved/');
				}*/
				echo '<script>
				alert("修改成功");
				window.location="'.APP_URL.'job/lsshow/'.$id.'";';
				echo '</script>';
			}
		}
	}

// }else{
// 	//没有参数跳到错误页面
// 	header('Location:'.APP_URL.'requirement/done/error');
// }
	
?>