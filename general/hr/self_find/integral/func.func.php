<?
include_once("inc/conn.php");

function getItemName($ITEM_ID)
{
		$query="select A.TYPE_NAME,B.ITEM_NAME from HR_INTEGRAL_ITEM B,HR_INTEGRAL_ITEM_TYPE A where A.TYPE_ID=B.TYPE_ID and B.ITEM_ID='$ITEM_ID'";
	$cursor=exequery(TD::conn(),$query);
	$ITEM_NAME_SHOW="";
	if($ROW=mysql_fetch_array($cursor))
		$ITEM_NAME_SHOW=$ROW["TYPE_NAME"]."->".$ROW["ITEM_NAME"];
	return $ITEM_NAME_SHOW;
}
function getItemNameByNo($ITEM_NO)
{
		$query="select A.TYPE_NAME,B.ITEM_NAME from HR_INTEGRAL_ITEM B,HR_INTEGRAL_ITEM_TYPE A where A.TYPE_ID=B.TYPE_ID and B.ITEM_NO='$ITEM_NO'";
	$cursor=exequery(TD::conn(),$query);
	$ITEM_NAME_SHOW="";
	if($ROW=mysql_fetch_array($cursor))
		$ITEM_NAME_SHOW=$ROW["TYPE_NAME"]."->".$ROW["ITEM_NAME"];
	return $ITEM_NAME_SHOW;
}
//-----------Qhj-----------//
//获取用户部门
function find_dept_id($USER_ID) 
{
		$DEPT_ID=0;
	$query="select DEPT_ID from USER where 1=1 and USER_ID = '".$USER_ID."'";
	$cursor=exequery(TD::conn(),$query);
	if($ROW=mysql_fetch_array($cursor))
		$DEPT_ID=$ROW["DEPT_ID"];
	return $DEPT_ID;
}
//获取用户积分总数
function getUser_SumPoint($USER_ID){
        $SUM1="";
        $SUM2="";
        $query="SELECT hr_integral_oa.USER_ID,SUM(hr_integral_oa.SUM) as SUM1 from hr_integral_oa,USER where hr_integral_oa.USER_ID=USER.USER_ID and hr_integral_oa.USER_ID='$USER_ID'";
	$cursor=exequery(TD::conn(),$query);
	if($ROW=mysql_fetch_array($cursor))
        {
            $SUM1=$ROW["SUM1"];
        }
        $query="SELECT hr_integral_data.USER_ID,SUM(hr_integral_data.INTEGRAL_DATA) as SUM2 from hr_integral_data,USER where hr_integral_data.USER_ID=USER.USER_ID and hr_integral_data.USER_ID='$USER_ID'";
	$cursor=exequery(TD::conn(),$query);
	if($ROW=mysql_fetch_array($cursor))
        {
            $SUM2=$ROW["SUM2"];
        }
	return $SUM1+$SUM2;
}	



//排行榜 如果返回-1证明此人没有积分
function getUser_Rank($USER_ID){
		$User_Point = getUser_SumPoint($USER_ID);	
	//如果此人没有积分
	if($User_Point=="" || $User_Point==false){
		return "-1";
	}
	if(find_dept_id($USER_ID) != '0'){//在职人员排行
		$query = "select USER_ID from HR_INTEGRAL_DATA where USER_ID IN(SELECT USER_ID  from USER WHERE DEPT_ID  != 0) group by USER_ID order by sum(INTEGRAL_DATA) desc";	
	}
	else{//离职人员排行榜
		$query = "select USER_ID from HR_INTEGRAL_DATA where USER_ID NOT IN(SELECT USER_ID  from USER WHERE DEPT_ID  != 0) group by USER_ID order by sum(INTEGRAL_DATA) desc";	
	}
	$cursor=exequery(TD::conn(),$query);
	$i = 0;
	while($ROW=mysql_fetch_array($cursor)){
		$i++;
		if($USER_ID == $ROW["USER_ID"]){
			break;
		}
	}
	return $i;	
}

//-------by kw integral get-----------------
function in_collect_attend()
{
		
	$group_str=" group by USER_ID ";
	//上下班
	$COLLECT_TIME="";
	$where_str="";
	$qu="select CREATE_TIME from HR_INTEGRAL_OA where ITEM_NO='attend_duty' order by CREATE_TIME DESC limit 1";
	$cur=exequery(TD::conn(),$qu);
	if($ROW=mysql_fetch_array($cur))
		$CREATE_TIME=$ROW["CREATE_TIME"];
	if($CREATE_TIME!="")
		$where_str.=" and REGISTER_TIME > '$CREATE_TIME' ";
	$query="select count(*) as COUNT,USER_ID from ATTEND_DUTY where 1=1".$where_str.$group_str;
	$cursor=exequery(TD::conn(),$query);
	$CREATE_TIME_NEW=date("Y-m-d H:i:s",time());
	while($ROW=mysql_fetch_array($cursor))
	{
		$COUNT=$ROW["COUNT"];
		$USER_ID=$ROW["USER_ID"];
		$result["attend_duty"][]=array("CREATE_TIME_NEW"=>$CREATE_TIME_NEW,"COUNT"=>$COUNT,"USER_ID"=>$USER_ID);
	}
	//加班
	$CREATE_TIME="";
	$where_str="";
	$qu="select CREATE_TIME from HR_INTEGRAL_OA where ITEM_NO='attend_overtime' order by CREATE_TIME DESC limit 1";
	$cur=exequery(TD::conn(),$qu);
	if($ROW=mysql_fetch_array($cur))
		$CREATE_TIME=$ROW["CREATE_TIME"];
	if($CREATE_TIME!="")
		$where_str.=" and RECORD_TIME > '$CREATE_TIME' ";
	$query="select count(*) as COUNT,USER_ID from ATTENDANCE_OVERTIME where find_in_set(ALLOW,'1,3') ".$where_str.$group_str;
	$cursor=exequery(TD::conn(),$query);
	$CREATE_TIME_NEW=date("Y-m-d H:i:s",time());
	while($ROW=mysql_fetch_array($cursor))
	{
		$COUNT=$ROW["COUNT"];
		$USER_ID=$ROW["USER_ID"];
		$result["attend_overtime"][]=array("CREATE_TIME_NEW"=>$CREATE_TIME_NEW,"COUNT"=>$COUNT,"USER_ID"=>$USER_ID);
	}
	
	return $result;
}

function cal_integral_oa()
{
		$OA_ID_STR="";
	$query_get="select A.OA_ID,A.COUNT,A.USER_ID,A.DEPT_ID,A.CREATE_TIME,B.ITEM_ID,B.ITEM_BRIEF,B.ITEM_VALUE,B.USED from HR_INTEGRAL_OA A left join HR_INTEGRAL_ITEM B on A.ITEM_NO=B.ITEM_NO where A.IS_CALED=0";
	$cursor_get=exequery(TD::conn(),$query_get);
	while($ROW=mysql_fetch_array($cursor_get))
	{
		$OA_ID=$ROW["OA_ID"];
		$OA_ID_STR.=$OA_ID.",";
		$USER_ID=$ROW["USER_ID"];
		$DEPT_ID=$ROW["DEPT_ID"];
		$ITEM_ID=$ROW["ITEM_ID"];
		$ITEM_BRIEF=$ROW["ITEM_BRIEF"];
		$ITEM_VALUE=$ROW["ITEM_VALUE"];
		$INTEGRAL_TIME=$ROW["CREATE_TIME"];
		$COUNT=$ROW["COUNT"];
		$USED=$ROW["USED"];
		$INTEGRAL_DATA=$ITEM_VALUE*$COUNT;
		$CUR_TIME=date("Y-m-d H:i:s",time());
		if($USED==1)
		{
			$query_set="insert into HR_INTEGRAL_DATA (ITEM_ID,INTEGRAL_REASON,INTEGRAL_TYPE,USER_ID,DEPT_ID,INTEGRAL_DATA,INTEGRAL_TIME,CREATE_TIME,CREATE_PERSON) values 
			('$ITEM_ID','$ITEM_BRIEF',1,'$USER_ID','$DEPT_ID','$INTEGRAL_DATA','$INTEGRAL_TIME','$CUR_TIME','admin') ";
			exequery(TD::conn(),$query_set);
		}
	}
	if($OA_ID_STR!="")
	{
		$query_caled="update HR_INTEGRAL_OA set IS_CALED=1 where find_in_set(OA_ID,'$OA_ID_STR')";
		exequery(TD::conn(),$query_caled);
	}
}
$RS_ARR=array(
	"RS001" => _("人事档案->学历"),
	"RS002" => _("人事档案->职称"),
	"RS003" => _("人事档案->证照得分"),
	"RS004" => _("人事档案->岗位"),
);
function cal_integral_attend()
{
		
	//不用考勤的人
	$query = "SELECT * from SYS_PARA where PARA_NAME='NO_DUTY_USER'";
	$cursor= exequery(TD::conn(),$query);
	if($ROW=mysql_fetch_array($cursor))
		$NO_DUTY_USER=$ROW["PARA_VALUE"];
$query1 = "SELECT USER.USER_NAME,USER.USER_ID,DEPARTMENT.DEPT_NAME from USER,USER_EXT,USER_PRIV,DEPARTMENT ".$WHERE_STR." and USER.NOT_LOGIN='0' and USER_EXT.USER_ID=USER.USER_ID and not find_in_set(USER.USER_ID,'$NO_DUTY_USER') and USER_EXT.DUTY_TYPE!='99' and DEPARTMENT.DEPT_ID = USER.DEPT_ID and USER.USER_PRIV=USER_PRIV.USER_PRIV order by DEPT_NO,PRIV_NO,USER_NO,USER_NAME";
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
   		$DUTY_ARR=array();
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
				if($ROW["DUTY_TIME".$I]!="")
					$DUTY_TYPE_ARR[$I]=array( "DUTY_TIME" => $ROW["DUTY_TIME".$I] ,"DUTY_TYPE" => $ROW["DUTY_TYPE".$I]);
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
	
}
