<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("general/attendance/manage/check_func.func.php");

$PAGE_SIZE = 15;
if(!isset($start) || $start=="")
   $start=0;
$end=$PAGE_SIZE;

$query = "SELECT * from SYS_PARA where PARA_NAME='NO_DUTY_USER'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $NO_DUTY_USER=$ROW["PARA_VALUE"];

$WHERE_STR=" where 1=1";
if($DEPARTMENT1!="ALL_DEPT")
{
   $DET_LIST=substr(GetChildDeptId($DEPARTMENT1),0,-1);
   $WHERE_STR.=" and DEPARTMENT.DEPT_ID in ($DET_LIST) ";
}

$query1 = "SELECT USER.USER_NAME,USER.USER_ID,DEPARTMENT.DEPT_NAME from USER,USER_EXT,USER_PRIV,DEPARTMENT ".$WHERE_STR." and (USER.NOT_LOGIN = 0 or USER.NOT_MOBILE_LOGIN = 0) and USER_EXT.USER_ID=USER.USER_ID and not find_in_set(USER.USER_ID,'$NO_DUTY_USER') and USER_EXT.DUTY_TYPE!='99' and DEPARTMENT.DEPT_ID = USER.DEPT_ID and USER.USER_PRIV=USER_PRIV.USER_PRIV order by DEPT_NO,PRIV_NO,USER_NO,USER_NAME limit ".$start.",".$end;

$cursor1= exequery(TD::conn(),$query1);
$USER_COUNT=0;
$COUNT_ARRAY[$USER_COUNT][$COUNT_TYPE]=0;
while($ROW1=mysql_fetch_array($cursor1))
{
   $USER_COUNT++;
   $TIME_TOTAL = 0;
   $USER_NAME=$ROW1["USER_NAME"];
   $DEPT_NAME=$ROW1["DEPT_NAME"];
   $USER_ID=$ROW1["USER_ID"];

   $COUNT_ARRAY[$USER_COUNT][1]=$DEPT_NAME;
   $COUNT_ARRAY[$USER_COUNT][2]=$USER_NAME;
   $COUNT_ARRAY[$USER_COUNT][11]=$USER_ID;

   for($J=$DATE1;$J<=$DATE2;$J=date("Y-m-d",strtotime($J)+24*3600))
   {
   		$DUTY_ARR  = array();
		$DUTY_TYPE = "";
		$query = "SELECT * from ATTEND_DUTY where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$J') order by REGISTER_TIME DESC";
		$cursor= exequery(TD::conn(),$query);
		while($ROW=mysql_fetch_array($cursor))
		{
			$DUTY_ARR[$ROW["REGISTER_TYPE"]]=array(
				"DUTY_TYPE"=> $ROW["DUTY_TYPE"],
				"REGISTER_TIME"=>$ROW["REGISTER_TIME"],
				"REGISTER_IP"=>$ROW["REGISTER_IP"],
				"REMARK"=>$ROW["REMARK"]
			);
		}
		foreach($DUTY_ARR as $tem)
			$DUTY_TYPE=$tem["DUTY_TYPE"];
		if($DUTY_TYPE=="")	$DUTY_TYPE=get_default_type($USER_ID);
		if($DUTY_TYPE=="" || $DUTY_TYPE==0)	$DUTY_TYPE=1;

		$query = "SELECT * from ATTEND_CONFIG where DUTY_TYPE='$DUTY_TYPE'";
		$cursor= exequery(TD::conn(),$query);
		if($ROW=mysql_fetch_array($cursor))
		{
			$DUTY_NAME=$ROW["DUTY_NAME"];
			$GENERAL=$ROW["GENERAL"];
			$DUTY_TYPE_ARR=array();
			for($I=1;$I<=6;$I++)
			{
                $cn = $I%2==0?"2":"1";
				if($ROW["DUTY_TIME".$I]!="")
					$DUTY_TYPE_ARR[$I]=array( "DUTY_TIME" => $ROW["DUTY_TIME".$I] ,"DUTY_TYPE" => $cn);
			}
		}
		else
			continue;
		$COUNT=count($DUTY_TYPE_ARR);//此排班一天需要登记次数。
		$IS_ALL=1;//全勤
		$OUGHT_TO=1;//应该考勤登记
		//按日算的
		if($IS_HOLIDAY=check_holiday($J)!=0)//是否休息日
			$OUGHT_TO=0;
		else if($IS_HOLIDAY1=check_holiday1($J,$GENERAL)!=0)//是否双休日
			$OUGHT_TO=0;
		else if($IS_EVECTION =check_evection($USER_ID,$J)!=0)//是否出差
			$OUGHT_TO=0;

		foreach($DUTY_TYPE_ARR as $REGISTER_TYPE => $DUTY_TYPE_ONE)//遍历按排班需要登记的
		{
			$START_OR_END=$DUTY_TYPE_ONE["DUTY_TYPE"];			//上下班：1：上班，2：下班。
			$DUTY_TIME_OUGHT=$DUTY_TYPE_ONE["DUTY_TIME"];//设定的考勤时间。
			$DUTY_ONE_ARR=$DUTY_ARR[$REGISTER_TYPE];//相应的登记记录

			$HAS_DUTY=0;
			if(is_array($DUTY_ONE_ARR) && !empty($DUTY_ONE_ARR))
			{
				foreach($DUTY_ONE_ARR as $KEY => $VALUE)
					$$KEY=$VALUE;
				$HAS_DUTY=1;
			}

			//记录已取出：$REGISTER_TIME：登记时间，$REGISTER_IP：登记IP 。$REMARK：备注


			if($IS_HOLIDAY==1 || $IS_HOLIDAY1==1)
			{
				if($START_OR_END==1 && $HAS_DUTY==1)
					$COUNT_ARRAY[$USER_COUNT][9]++;
				else if($START_OR_END==2 && $HAS_DUTY==1)
					$COUNT_ARRAY[$USER_COUNT][10]++;
			}
			//按时间点算的。
			if($IS_LEAVE=check_leave($USER_ID,$J,$DUTY_TYPE_ARR[$REGISTER_TYPE]["DUTY_TIME"])!=0)//是否请假
				$OUGHT_TO=0;
			else if($IS_OUT=check_out($USER_ID,$J,$DUTY_TYPE_ARR[$REGISTER_TYPE]["DUTY_TIME"])!=0)//是否外出
				$OUGHT_TO=0;

			if($HAS_DUTY==1)//已经登记（都计算）
			{
				$REGISTER_TIME2=$DUTY_ONE_ARR["REGISTER_TIME"];
				$REGISTER_TIME=$DUTY_ONE_ARR["REGISTER_TIME"];
				$ALL_MINITES[$USER_ID][$J][$REGISTER_TYPE] = $REGISTER_TIME;
				$REGISTER_TIME=strtok($REGISTER_TIME," ");
				$REGISTER_TIME=strtok(" ");

				//迟到早退不算全勤，$IS_ALL=0;
					//echo $USER_ID."应该：$DUTY_TIME_OUGHT--实际：$REGISTER_TIME";
					//echo "所得：".compare_time($REGISTER_TIME,$DUTY_TIME_OUGHT)."<br>";
				if($START_OR_END=="1" && compare_time($REGISTER_TIME,$DUTY_TIME_OUGHT)==1)
				{
					if($OUGHT_TO!=0)
						$COUNT_ARRAY[$USER_COUNT][5]++;//迟到
					$IS_ALL=0;
				}

				if($START_OR_END=="2" && compare_time($REGISTER_TIME,$DUTY_TIME_OUGHT)==-1)
				{
					$IS_ALL=0;
					if($OUGHT_TO!=0)
						$COUNT_ARRAY[$USER_COUNT][7]++;//早退
				}
			}
			else if($HAS_DUTY==0 && $OUGHT_TO==1)//应该登记，没有登记的
			{
				if($START_OR_END=="1")//上班未登记
					$COUNT_ARRAY[$USER_COUNT][6]++;
				if($START_OR_END=="2")//下班未登记
					$COUNT_ARRAY[$USER_COUNT][8]++;
				$IS_ALL=0;
			}
			else
				$IS_ALL=0;
		}
		if($IS_ALL==1)
			$COUNT_ARRAY[$USER_COUNT][3]++;//全勤的
		for($l = 1 ;$l<= $COUNT/2;$l ++)
		{
			if($ALL_MINITES[$USER_ID][$J][$l*2]!="" && $ALL_MINITES[$USER_ID][$J][$l*2-1]!="")
				$TIME_TOTAL+= strtotime($ALL_MINITES[$USER_ID][$J][$l*2]) - strtotime($ALL_MINITES[$USER_ID][$J][$l*2-1]);
		}
		$ALL_HOURS = floor($TIME_TOTAL / 3600);
		$HOUR1 = $TIME_TOTAL % 3600;
		$MINITE = floor($HOUR1 / 60);
		$COUNT_ARRAY[$USER_COUNT][4]= $ALL_HOURS._("时").$MINITE._("分") ;
	}
}
//$COUNT_ARRAY["COUNT_USER"]=$USER_COUNT;
ob_end_clean();
echo array_to_json($COUNT_ARRAY);
?>