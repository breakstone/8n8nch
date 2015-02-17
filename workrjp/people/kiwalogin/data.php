<?php   
	if(!file_exists('../../_config/config.php'))
	{
		die('[index.php] _config/config.php not found');
	}
	require_once '../../_config/config.php';
	require_once '../../_includes/functions.php';
	//文件锁
	$fp = fopen("lock.txt", "w+");
	if(flock($fp,LOCK_EX))
	{
				$user_id=cleanInput($_REQUEST["user_id"]);
				$user_pwd=cleanInput($_REQUEST["pwd"]);
				$sqlks = "select * from kiwa_user where staff_id='$user_id' and passwd = '$user_pwd' and bytDeleteFlag = 0";
				
				$staff = $db->QueryRow($sqlks);
				
				
				if($staff["staff_id"]!=""){
					$prize_arr = array(
							'0' => array('id'=>1,'min'=>array(2,182),'max'=>array(28,208),'prize'=>'恭喜你,获得一等奖、奖金三万円。','v'=>1),
							'1' => array('id'=>2,'min'=>array(122,302),'max'=>array(148,328),'prize'=>'恭喜你,获得二等奖、奖金一万円。','v'=>2),
							'2' => array('id'=>3,'min'=>array(62,242),'max'=>array(88,268),'prize'=>'恭喜你,获得三等奖、奖金一千円。','v'=>90),
							'3' => array('id'=>4,'min'=>array(32,92,152,212,272,332),
									'max'=>array(58,118,178,238,298,358),'prize'=>'很遗憾您没有中奖、谢谢您的参与。','v'=>407)
					);
					function getRand($proArr) {
						$result = '';
						//概率数组的总概率精度
						$proSum = array_sum($proArr);
						//概率数组循环
						foreach ($proArr as $key => $proCur) {
							$randNum = mt_rand(1, $proSum);
							if ($randNum <= $proCur) {
								$result = $key;
								break;
							} else {
								$proSum -= $proCur;
							}
						}
						unset ($proArr);
					
						return $result;
					}
					foreach ($prize_arr as $key => $val) {
						$arr[$val['id']] = $val['v'];
					}
					
					$date=date("Y-m-d");
// 					$date="2014-12-31";
					$timestamp=strtotime($date);
						
					//计算当前月份的上一个月
					$last_mon=date('Y-m',strtotime(date('Y',$timestamp).'-'.(date('m',$timestamp)-1).'-01'));
					//抽奖月份以抽奖的总人数
					$allcount=peocount("all",$last_mon);
					//var_dump($arr);
					
					$rid_cz=true;
					while($rid_cz){
						$rid = getRand($arr); //根据概率获取奖项id
						
						if($rid==1){
							$rid_count=peocount('1',$last_mon);
							if($rid_count >= 1){
								$rid_cz=true;
							}else{
								$loto_num=1;
								$rid_cz=false;
							}
						}
						if($rid==2){
							$rid_count=peocount('2',$last_mon);
							if($rid_count >= 2){
								$rid_cz=true;
							}else{
								$loto_num=2;
								$rid_cz=false;
							}
						}
						if($rid==3){
							$rid_count=peocount('3',$last_mon);
							if($rid_count >= 50){
								$rid_cz=true;
							}else{
								$loto_num=3;
								$rid_cz=false;
							}
						}
						if($rid==4){
							$loto_num=4;
								$rid_cz=false;
						}
					}
					
					$sql_name= "select kiwaname from kiwa_user where staff_id='$user_id'";
					$user_name = $db->QueryRow($sql_name);
					
					$loto_num_sql= "select loto_num from kiwa_loto where staff_id='$user_id' and  loto_date like '%$last_mon%'  and del_flg = 0";
					$loto_num_cz= $db->QueryRow($loto_num_sql);
						if($loto_num_cz['loto_num']){
								$result['error']="你本月已经抽过奖了";
							}else{
						$return = loto_add($user_id, $user_name["kiwaname"], $loto_num,$last_mon."-01");
						if(!$return){
							$result['error']="抽奖出错，请稍后再试";
						}else{
							//如果没有三等奖  
							if($allcount>5&&$allcount <=20&&$rid==4){
										$rid_count=peocount('3',$last_mon);
										if($rid_count <=5 ){
											if($allcount==8 || $allcount==11 || $allcount==17 || $allcount==18 || $allcount==19 || $allcount==20){
												$rid=3;
												loto_update($user_id, $rid, $last_mon."-01");
											}
										}
							}
							//如果没有一等奖
							if($allcount==88){
								$rid_count=peocount('1',$last_mon);
								if($rid_count==0){
									$rid=1;
									loto_update($user_id, $rid, $last_mon."-01");
								}
							}
							if($allcount==128){
								$rid_count=peocount('2',$last_mon);
								if($rid_count==0){
									$rid=2;
									loto_update($user_id, $rid, $last_mon."-01");
								}
							}
							
							if($allcount==188){
								$rid_count=peocount('2',$last_mon);
								if($rid_count<=1){
									$rid=2;
									loto_update($user_id, $rid, $last_mon."-01");
								}
							}
							$res = $prize_arr[$rid-1]; //中奖项
							
							$min = $res['min'];
							$max = $res['max'];
							if($res['id']==4){ //七等奖
								$i = mt_rand(0,5);
							}else{
								$i = mt_rand(0,1);
							}
							
							$result['angle'] = mt_rand($min[$i],$max[$i]);
							
							$result['prize'] = $res['prize'];
							$result['userid']=$user_id;
							$result['pwd']=$user_pwd;
							$result['error']=false;
						} 
								
						}
						
					echo json_encode($result);
					//var_dump($result);
				} else{
					$result['error']="抽奖出错，请稍后再试";
					echo json_encode($result);
				}
		flock($fp,LOCK_UN);
	}else{
		$result['error']="系统繁忙，请稍后再试";
		echo json_encode($result);
	}
	
	fclose($fp);

##########################################
# loto_add 插入所抽选的数字
##########################################
function loto_add($user_id,$user_name,$loto_num,$loto_date){
	global $db;
	$user_id = $db->real_escape_string($user_id);
	$user_name = $db->real_escape_string($user_name);
	$loto_num = $db->real_escape_string($loto_num);
	$now = date("Y-m-d H:i:s");
	$sql = "insert into kiwa_loto set staff_id = '$user_id',kiwaname = '$user_name',loto_num='$loto_num',loto_date='$loto_date',create_date='$now'";
	//echo $sql;
	if($db->Execute($sql)){
		return true;
	}else{
		return false;
	}
}
##########################################
# loto_update 更新 中奖数字
##########################################
function loto_update($user_id,$loto_num,$loto_date){
	global $db;
	$user_id = $db->real_escape_string($user_id);
	$loto_num = $db->real_escape_string($loto_num);
	$sql = "update kiwa_loto set loto_num='$loto_num' where staff_id = '$user_id' and loto_date='$loto_date' and del_flg = 0";
	
	//echo $sql;
	if($db->Execute($sql)){
		return true;
	}else{
		return false;
	}
}
##########################################
# peocount 已经抽奖的人数/某个奖项已经中奖的人数
##########################################
function peocount($prize,$loto_date){
	global $db;
	if($prize==all){
		$sql_cz= "select count(*) as count from kiwa_loto where loto_date like '%$loto_date%' and del_flg=0";
	}else{
		$sql_cz= "select count(*) as count from kiwa_loto where loto_num='$prize' and  loto_date like '%$loto_date%' and del_flg=0";
	}
	$cz= $db->QueryRow($sql_cz);
	return $cz["count"];
}