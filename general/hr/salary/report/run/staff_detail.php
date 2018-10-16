<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("general\attendance\manage\check_func.func.php");
$query="select * from SAL_FLOW where FLOW_ID='$FLOW_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	$SAL_YEAR=$ROW["SAL_YEAR"];
	$SAL_MONTH=$ROW["SAL_MONTH"];
}

$query = "SELECT * from USER where USER_ID='$USER_ID'";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $USER_ID=$ROW["USER_ID"];
    $USER_NAME=$ROW["USER_NAME"];
}

$HTML_PAGE_TITLE = _("员工表现详情");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif" width="17" height="17"><span class="big3"><?=$USER_NAME?><?=_("表现详情")?>(<?=$SAL_YEAR._("年").$SAL_MONTH._("月")?>)</span><br>
    </td>
  </tr>
</table>
<?
//参考数据
if($SAL_YEAR=="")
  	$SAL_YEAR="2005";	
if($SAL_MONTH=="") 	
  	$SAL_MONTH="01";
$MONTH_BEGIN=$SAL_YEAR."-".$SAL_MONTH."-"."01";
$MONTH_BEGIN1=strtotime($MONTH_BEGIN." 00:00:00");
$MONTH_END=$SAL_YEAR."-".$SAL_MONTH."-".date("t",mktime(0,0,0,$SAL_MONTH,5,$SAL_YEAR));
$MONTH_END1=strtotime($MONTH_END." 23:59:59");
//----日志----
$query="select count(DIA_ID) from DIARY where DIA_TYPE!='2' and USER_ID='$USER_ID' and DIA_DATE >= '$MONTH_BEGIN 00:00:00' and DIA_DATE <= '$MONTH_END 23:59:59' ";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $DIARY_NUM=$ROW["0"];
//日程
$query="select count(CAL_ID) from CALENDAR where CAL_TYPE!='2' and USER_ID='$USER_ID' and CAL_TIME >= '$MONTH_BEGIN1' and END_TIME <= '$MONTH_END1' ";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $CAL_NUM=$ROW["0"];
$query="select count(CAL_ID) from CALENDAR where CAL_TYPE!='2' and USER_ID='$USER_ID' and CAL_TIME >= '$MONTH_BEGIN1' and END_TIME <= '$MONTH_END1' and OVER_STATUS='1'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $CAL_NUM1=$ROW["0"];

//考勤
$DATE1=$SAL_YEAR."-".$SAL_MONTH."-"."01";
$DATE2=$SAL_YEAR."-".$SAL_MONTH."-".date("t",mktime(0,0,0,$SAL_MONTH,5,$SAL_YEAR));
$NOT_REG_COUNT = 0;
$LAST_COUNT = 0;
$EARLY_COUNT= 0;
$LAST_TIME=array();
$EARLY_TIME=array();
$CUR_DATE=date("Y-m-d",time());

$query = "SELECT to_days('$DATE2')-to_days('$DATE1') from ATTEND_CONFIG";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $DAY_TOTAL=$ROW[0]+1;

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
			"REMARK"=>str_replace("\n","<br>",$ROW["REMARK"])
		);
	}
	foreach($DUTY_ARR as $tem)
		$DUTY_TYPE=$tem["DUTY_TYPE"];
	if($DUTY_TYPE=="")	$DUTY_TYPE=get_default_type($USER_ID);
	if($DUTY_TYPE=="" || $DUTY_TYPE==0)	$DUTY_TYPE=1;
	
	if($DUTY_TYPE==99) continue;

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
				$DUTY_TYPE_ARR["TYPE"][$I]=array( "DUTY_TIME" => $ROW["DUTY_TIME".$I] ,"DUTY_TYPE" => $ROW["DUTY_TYPE".$I]);
		}
		$DUTY_TYPE_ARR["NAME"]=$DUTY_NAME;
	}
	
	if(!isset($DUTY_INFO_ARR[$DUTY_TYPE]))
		$DUTY_INFO_ARR[$DUTY_TYPE]=$DUTY_TYPE_ARR;
	$OUGHT_TO=1;
	$SHOW_HOLIDAY="";
	//按日算的
	if(($IS_HOLIDAY=check_holiday($J))!=0)//是否休息日
	{
		$SHOW_HOLIDAY.="<font color='#008000'>"._("节假日")."</font>";
		$OUGHT_TO=0;
	}
	else if(($IS_HOLIDAY1=check_holiday1($J,$GENERAL))!=0)//是否双休日
	{
		$SHOW_HOLIDAY.="<font color='#008000'>"._("公休日")."</font>";
		$OUGHT_TO=0;
	}
	else if(($IS_EVECTION =check_evection($USER_ID,$J))!=0)//是否出差
	{
		$SHOW_HOLIDAY.="<font color='#008000'>"._("出差")."</font>"; 
		$OUGHT_TO=0;
	}
	if($SHOW_HOLIDAY!="" || $SHOW_HOLIDAY2!="")
		$CLASS="TableContent";
	else
		$CLASS="TableData";
	$DAYS_TEM[$J]["CLASS"]=$CLASS;
	$DAYS_TEM[$J]["DUTY_TYPE"]=$DUTY_TYPE;
	$REGISTERS_TEM=array();
	foreach((array)$DUTY_TYPE_ARR["TYPE"] as $REGISTER_TYPE => $DUTY_TYPE_ONE)//遍历按排班需要登记的
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
		//var_dump(check_leave($USER_ID,$J,$DUTY_TYPE_ARR[$REGISTER_TYPE]["DUTY_TIME"]));
			//var_dump($DUTY_TYPE_ARR["TYPE"][$REGISTER_TYPE]["DUTY_TIME"]);
		$SHOW_HOLIDAY2="";
		//按时间点算的。
		if(($IS_LEAVE=check_leave($USER_ID,$J,$DUTY_TYPE_ARR["TYPE"][$REGISTER_TYPE]["DUTY_TIME"]))!="0")//是否请假
		{
			$SHOW_HOLIDAY2.="<font color='#008000'>"._("请假")."-$IS_LEAVE</font>";
			$OUGHT_TO=0;
		}
	
		else if(($IS_OUT=check_out($USER_ID,$J,$DUTY_TYPE_ARR["TYPE"][$REGISTER_TYPE]["DUTY_TIME"]))!="0")//是否外出
		{
			$SHOW_HOLIDAY2.="<font color='#008000'>"._("外出")."</font>";
			$OUGHT_TO=0;
		}
			
		$SHOW_STR="";
		if($HAS_DUTY==1 &&$OUGHT_TO==1)//已经登记
		{
			$REGISTER_TIME2=$DUTY_ONE_ARR["REGISTER_TIME"];
			$REGISTER_TIME=$DUTY_ONE_ARR["REGISTER_TIME"];
			$REGISTER_TIME=strtok($REGISTER_TIME," ");
			$REGISTER_TIME=strtok(" ");
			
			//迟到早退不算全勤，$IS_ALL=0;
				//echo $USER_ID."应该：$DUTY_TIME_OUGHT--实际：$REGISTER_TIME";
				//echo "所得：".compare_time($REGISTER_TIME,$DUTY_TIME_OUGHT)."<br>";
			
			if($START_OR_END=="1" && compare_time($REGISTER_TIME,$DUTY_TIME_OUGHT)==1)
			{
	          	 if($REGISTER_TYPE==1)
	          	 {
	          	    $LATE_TIME=number_format((strtotime($J.$REGISTER_TIME)-strtotime($J.$DUTY_TIME_OUGHT))/60,0);
	          	    $LAST_TIME[$LAST_COUNT]=$J._("第一次登记迟到").$LATE_TIME._("分钟")."<br>";
	          	 }
	          	 if($REGISTER_TYPE==3)
	          	 {
	          	    $LATE_TIME=number_format((strtotime($J.$REGISTER_TIME)-strtotime($J.$DUTY_TIME_OUGHT))/60,0);
	          	    $LAST_TIME[$LAST_COUNT]=$J._("第二次登记迟到").$LATE_TIME._("分钟")."<br>";
	          	 }
	          	 if($REGISTER_TYPE==5)
	          	 {
	          	    $LATE_TIME=number_format((strtotime($J.$REGISTER_TIME)-strtotime($J.$DUTY_TIME_OUGHT))/60,0);
	          	    $LAST_TIME[$LAST_COUNT]=$J._("第三次登记迟到").$LATE_TIME._("分钟")."<br>";
	          	 }
	          	 $LAST_COUNT++;
				$SHOW_STR.=$REGISTER_TIME." <font color=red><b>"._("迟到")."</b></font>";//迟到
			}
			
			else if($START_OR_END=="2" && compare_time($REGISTER_TIME,$DUTY_TIME_OUGHT)==-1)
			{
          	 if($REGISTER_TYPE==2)
          	 {
          	    $LEAVE_TIME=number_format((strtotime($J.$DUTY_TIME_OUGHT)-strtotime($J.$REGISTER_TIME))/60,0);
          	    $EARLY_TIME[$EARLY_COUNT]=$J._("第一次登记早退").$LEAVE_TIME._("分钟")."<br>";
          	 }
          	 if($REGISTER_TYPE==4)
          	 {
          	 	$LEAVE_TIME=number_format((strtotime($J.$DUTY_TIME_OUGHT)-strtotime($J.$REGISTER_TIME))/60,0);
          	    $EARLY_TIME[$EARLY_COUNT]=$J._("第二次登记早退").$LEAVE_TIME._("分钟")."<br>";
          	 }
          	 if($REGISTER_TYPE==6)
          	 {
          	    $LEAVE_TIME=number_format((strtotime($J.$DUTY_TIME_OUGHT)-strtotime($J.$REGISTER_TIME))/60,0);
          	    $EARLY_TIME[$EARLY_COUNT]=$J._("第三次登记早退").$LEAVE_TIME._("分钟")."<br>";
          	 }
				$EARLY_COUNT++;
				$SHOW_STR.=$REGISTER_TIME." <font color=red><b>"._("早退")."</b></font>";//早退
			}
			else
				$SHOW_STR.=$REGISTER_TIME;
           if($REMARK!="")
           {
              $REMARK="<br>"._("说明：").$REMARK;
           	  $SHOW_STR.=$REMARK."&nbsp;<a href=\"javascript:remark('$USER_ID','$REGISTER_TYPE','$REGISTER_TIME2');\" title=\""._("修改说明")."\">"._("修改")."</a>";
           }
		}
		else if($HAS_DUTY==0 && $OUGHT_TO==1)//应该登记，没有登记的
		{
			$NOT_REG_COUNT++;
			$SHOW_STR.=_("未登记");
		}
		else
		{
			if($SHOW_HOLIDAY!="")
				$SHOW_STR.=$SHOW_HOLIDAY;
			else if($SHOW_HOLIDAY2!="")
				$SHOW_STR.=$SHOW_HOLIDAY2;
			else
			{
				$NOT_REG_COUNT++;
				$SHOW_STR.=_("未登记");
			}
		}
	}
}
//var_dump($EARLY_TIME);exit;
if($NOT_REG_COUNT!=0)
$REGISTER = _("未登记").$NOT_REG_COUNT._("次")."<br>";
$query = "SELECT * from ATTEND_OUT where USER_ID='$USER_ID' and to_days(SUBMIT_TIME)>=to_days('$DATE1') and to_days(SUBMIT_TIME)<=to_days('$DATE2') and ALLOW='1' order by SUBMIT_TIME";
$cursor= exequery(TD::conn(),$query);
$OUT_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{   
	 $OUT_COUNT++;
      $SUBMIT_TIME=$ROW["SUBMIT_TIME"];
      $SUBMIT_DATE=substr($SUBMIT_TIME,0,10);
	 $OUT_TYPE=$ROW["OUT_TYPE"];
	 
	 $OUT_DESCRIPTION.=$SUBMIT_DATE._("外出原因：").$OUT_TYPE."<br>";
}
//$OUT_DESCRIPTION=substr($OUT_DESCRIPTION,0,-2);
$query = "SELECT * from ATTEND_LEAVE where USER_ID='$USER_ID' and ((to_days(LEAVE_DATE1)>=to_days('$DATE1') and to_days(LEAVE_DATE1)<=to_days('$DATE2')) or (to_days(LEAVE_DATE2)>=to_days('$DATE1') and to_days(LEAVE_DATE2)<=to_days('$DATE2')) or (to_days(LEAVE_DATE1)<=to_days('$DATE1') and to_days(LEAVE_DATE2)>=to_days('$DATE2'))) and allow in('1','3')";
$cursor= exequery(TD::conn(),$query);
$LEAVE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $LEAVE_COUNT++;
   $LEAVE_TYPE=$ROW["LEAVE_TYPE"];
   $LEAVE_DATE1=$ROW["LEAVE_DATE1"];
   $LEAVE_DATE2=$ROW["LEAVE_DATE2"];
   $LEAVE_DATE1=substr($LEAVE_DATE1,0,10);
   $LEAVE_DATE2=substr($LEAVE_DATE2,0,10);
   $LEAVE_DESCRIPTION.=$LEAVE_DATE1._("至").$LEAVE_DATE2._("请假原因：").$LEAVE_TYPE."<br>";
}
//$LEAVE_DESCRIPTION=substr($LEAVE_DESCRIPTION,0,-2);
$query = "SELECT * from ATTEND_EVECTION where USER_ID='$USER_ID' and ((to_days(EVECTION_DATE1)>=to_days('$DATE1') and to_days(EVECTION_DATE1)<=to_days('$DATE2')) or (to_days(EVECTION_DATE2)>=to_days('$DATE1') and to_days(EVECTION_DATE2)<=to_days('$DATE2')) or (to_days(EVECTION_DATE1)<=to_days('$DATE1') and to_days(EVECTION_DATE2)>=to_days('$DATE2'))) and ALLOW='1'";
$cursor= exequery(TD::conn(),$query);
$EVECTION_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{   
   $EVECTION_COUNT++;
   $EVECTION_DATE1=$ROW["EVECTION_DATE1"];
   $EVECTION_DATE1=strtok($EVECTION_DATE1," ");
   $EVECTION_DATE2=$ROW["EVECTION_DATE2"];
   $EVECTION_DATE2=strtok($EVECTION_DATE2," ");
   $REASON=$ROW["REASON"];
   
   $EVECTION_DESCRIPTION.=$EVECTION_DATE1._("至").$EVECTION_DATE2._("出差原因：").$REASON."<br>";
}
//$EVECTION_DESCRIPTION=substr($EVECTION_DESCRIPTION,0,-2);
//-------考核---------
$query = "SELECT FLOW_ID from SCORE_FLOW  where  find_in_set('$USER_ID',PARTICIPANT) and BEGIN_DATE >= '$DATE1' and END_DATE <='$DATE2' ";
$cursor= exequery(TD::conn(),$query);
$SCOREDATA_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $FLOW_ID=$ROW["FLOW_ID"];	       	 
    $query7 = "SELECT * from SCORE_DATE where FLOW_ID='$FLOW_ID' and PARTICIPANT='$USER_ID'";
    $cursor7= exequery(TD::conn(),$query7);
    if($ROW7=mysql_fetch_array($cursor7))
       $SCOREDATA_COUNT++;
}
//------加班---------
$query = "SELECT * from ATTENDANCE_OVERTIME  where USER_ID='$USER_ID' and START_TIME>='$DATE1' and END_TIME<='$DATE2' and (ALLOW='1' or ALLOW='3')";
$cursor= exequery(TD::conn(),$query);
$OVERTIME_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{   
   $OVERTIME_COUNT++;
   $START_TIME=$ROW["START_TIME"];
   $START_TIME=strtok($START_TIME," ");
   $END_TIME =$ROW["END_TIME"];
   $END_TIME =strtok($END_TIME," ");
   $OVERTIME_CONTENT =$ROW["OVERTIME_CONTENT"];
   $OVERTIME_DESCRIPTION.=$START_TIME._("至").$END_TIME._("加班内容：").$OVERTIME_CONTENT."<br>";
}
?>
<style>
	td{word-wrap:break-word;word-break:break-all;}
</style>
<table class="TableBlock" width="90%" align="center">
  <tr>
    <td nowrap align="left" class="TableContent"><?=_("项目")?></td>
    <td nowrap align="left" class="TableContent" ><?=_("子项目")?></td>
    <td nowrap align="center" class="TableContent"><?=_("次数")?></td>
    <td nowrap align="center" class="TableContent"  ><?=_("描述")?></td>
  </tr>
  <tr>
    <td nowrap align="left" rowspan=6 class="TableContent"><?=_("考勤")?></td>
    <td nowrap align="left" class="TableContent" ><?=_("迟到")?></td>
    <td nowrap align="center" class="TableData" ><? if($LAST_COUNT != 0) echo "<div nowrap style='color:red' style='display:inline;'>$LAST_COUNT</div>"; else echo "$LAST_COUNT"?></td>
    <td nowrap align="left" class="TableData" ><? for($i=0;$i< $LAST_COUNT;$i++) echo $LAST_TIME[$i]; ?><a href="javascript:;" onClick="window.open('attendance_search.php?USER_ID=<?=$USER_ID?>&FLOW_ID=<?=$FLOW_ID?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>','','height=700,width=900,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=800,top=270,resizable=yes');"><?=_("明细")?></a></td>
  </tr>
  <tr>
  	<td nowrap align="left" class="TableContent" ><?=_("早退")?></td>  
    <td nowrap align="center" class="TableData" ><? if($EARLY_COUNT != 0) echo "<div nowrap style='color:red' style='display:inline;'>$EARLY_COUNT</div>"; else echo "$EARLY_COUNT"?></td>
    <td nowrap align="left" class="TableData" ><? for($i=0;$i< $EARLY_COUNT;$i++) {echo $EARLY_TIME[$i];} ?><a href="javascript:;" onClick="window.open('attendance_search.php?USER_ID=<?=$USER_ID?>&FLOW_ID=<?=$FLOW_ID?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>','','height=700,width=900,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=800,top=270,resizable=yes');"><?=_("明细")?></a></td>
  </tr>
  <tr>
  	<td nowrap align="left" class="TableContent" ><?=_("外出")?></td>
    <td nowrap align="center" class="TableData" ><? if($OUT_COUNT != 0) echo "<div nowrap style='color:red' style='display:inline;'>$OUT_COUNT</div>"; else echo "$OUT_COUNT"?></td>
    <td align="left" class="TableData" ><?=str_replace("\n","<br>",$OUT_DESCRIPTION)?><a href="javascript:;" onClick="window.open('attendance_search1.php?USER_ID=<?=$USER_ID?>&FLOW_ID=<?=$FLOW_ID?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>','','height=700,width=900,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=800,top=270,resizable=yes');"><?=_("明细")?></a></td>
  </tr>
  <tr>
  	<td nowrap align="left" class="TableContent" ><?=_("请假")?></td>
    <td nowrap align="center" class="TableData" ><? if($LEAVE_COUNT != 0) echo "<div nowrap style='color:red' style='display:inline;'>$LEAVE_COUNT</div>"; else echo "$LEAVE_COUNT"?></td>
    <td align="left" class="TableData" ><?=str_replace("\n","<br>",$LEAVE_DESCRIPTION)?><a href="javascript:;" onClick="window.open('attendance_search2.php?USER_ID=<?=$USER_ID?>&FLOW_ID=<?=$FLOW_ID?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>','','height=700,width=900,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=800,top=270,resizable=yes');"><?=_("明细")?></a></td>
  </tr>
  <tr>
  	<td nowrap align="left" class="TableContent" ><?=_("出差")?></td>  
    <td nowrap align="center" class="TableData" ><? if($EVECTION_COUNT != 0) echo "<div nowrap style='color:red' style='display:inline;'>$EVECTION_COUNT</div>"; else echo "$EVECTION_COUNT"?></td>
    <td align="left" class="TableData" ><?=str_replace("\n","<br>",$EVECTION_DESCRIPTION)?><a href="javascript:;" onClick="window.open('attendance_search3.php?USER_ID=<?=$USER_ID?>&FLOW_ID=<?=$FLOW_ID?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>','','height=700,width=900,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=800,top=270,resizable=yes');"><?=_("明细")?></a></td>
  </tr>
  <tr>
  	<td nowrap align="left" class="TableContent" ><?=_("加班")?></td>  
    <td nowrap align="center" class="TableData" ><? if($OVERTIME_COUNT != 0) echo "<div nowrap style='color:red' style='display:inline;'>$OVERTIME_COUNT</div>"; else echo "$OVERTIME_COUNT"?></td>
    <td align="left" class="TableData" ><?=str_replace("\n","<br>",$OVERTIME_DESCRIPTION)?><a href="javascript:;" onClick="window.open('attendance_overtime.php?USER_ID=<?=$USER_ID?>&FLOW_ID=<?=$FLOW_ID?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>','','height=700,width=900,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=800,top=270,resizable=yes');"><?=_("明细")?></a></td>
  </tr>
  <tr>
<?
$query3="select count(*) from HR_CODE where PARENT_NO='INCENTIVE_TYPE'";
$cursor3= exequery(TD::conn(),$query3);
if($ROW3=mysql_fetch_array($cursor3))
	$INCENTIVE_COUNT=$ROW3["0"];
?>
    <td nowrap align="left" rowspan=<?=$INCENTIVE_COUNT?> class="TableContent"><?=_("奖惩")?></td>
<?
//奖惩
$query2="select CODE_NO,CODE_NAME from HR_CODE where PARENT_NO='INCENTIVE_TYPE' order by CODE_ORDER";
$cursor2= exequery(TD::conn(),$query2);
while($ROW2=mysql_fetch_array($cursor2))
{
     $CODE_NO=$ROW2["CODE_NO"];
     $CODE_NAME=$ROW2["CODE_NAME"];
     
	$query="select INCENTIVE_DESCRIPTION from HR_STAFF_INCENTIVE  where INCENTIVE_TYPE='$CODE_NO' and STAFF_NAME='$USER_ID' and INCENTIVE_TIME>= '$MONTH_BEGIN' and INCENTIVE_TIME <= '$MONTH_END'";
	$cursor= exequery(TD::conn(),$query);
	$INCENTIVE_NUM=0;
	while($ROW=mysql_fetch_array($cursor))
	{
	  	$INCENTIVE_NUM++;
	     $INCENTIVE_DESCRIPTION=$ROW["INCENTIVE_DESCRIPTION"]."<br>";
	}

?>
    <td nowrap align="left" class="TableContent" ><?=$CODE_NAME?></td>  
    <td nowrap align="center" class="TableData" ><? if($INCENTIVE_NUM != 0) echo "<div nowrap style='color:red' style='display:inline;'>$INCENTIVE_NUM</div>"; else echo "$INCENTIVE_NUM"?></td>
    <td align="left" class="TableData" ><?=str_replace("\n","<br>",$INCENTIVE_DESCRIPTION) ?><a href="javascript:;" onClick="window.open('incentive_search.php?USER_ID=<?=$USER_ID?>&CODE_NO=<?=$CODE_NO?>&FLOW_ID=<?=$FLOW_ID?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>','','height=700,width=900,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=800,top=270,resizable=yes');"><?=_("明细")?></a></td>
  </tr>
  <tr>
<?
}
?>
  </tr>
  <tr>
    <td nowrap align="left" rowspan=2 class="TableContent"><?=_("日程")?></td>
    <td nowrap align="left" class="TableContent" ><?=_("共计")?></td>  
    <td nowrap align="center" class="TableData" ><? if($CAL_NUM != 0) echo "<div nowrap style='color:red' style='display:inline;'>$CAL_NUM</div>"; else echo "$CAL_NUM"?></td>
    <td nowrap align="center" class="TableData" ></td>
  </tr>
  <tr>
  	<td nowrap align="left" class="TableContent" ><?=_("已完成")?></td>
    <td nowrap align="center" class="TableData" ><? if($CAL_NUM1 != 0) echo "<div nowrap style='color:red' style='display:inline;'>$CAL_NUM1</div>"; else echo "$CAL_NUM1"?></td>
    <td nowrap align="center" class="TableData" ></td>
  </tr>
  <tr>
    <td nowrap align="left"  class="TableContent"><?=_("日志")?></td>
    <td nowrap align="left" class="TableContent" ><?=_("共计")?></td>  
    <td nowrap align="center" class="TableData" ><? if($DIARY_NUM != 0) echo "<a href='diary_user_search.php?USER_ID=$USER_ID&FLOW_ID=$FLOW_ID&DATE1=$DATE1&DATE2=$DATE2'><div nowrap style='color:red' style='display:inline;'>$DIARY_NUM</div></a>"; else echo "$DIARY_NUM"?></td>
    <td nowrap align="left" class="TableData" ><a href="javascript:;" onClick="window.open('diary_user_search.php?USER_ID=<?=$USER_ID?>&FLOW_ID=<?=$FLOW_ID?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>','','height=700,width=900,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=800,top=270,resizable=yes');"><?=_("明细")?></a></td>
  </tr>
  <tr>
    <td nowrap align="left"  class="TableContent"><?=_("考核")?></td>
    <td nowrap align="left" class="TableContent" ><?=_("共计")?></td>  
    <td nowrap align="center" class="TableData" ><? if($SCOREDATA_COUNT != 0) echo "<a href='scoredata_query.php?USER_ID=$USER_ID&FLOW_ID=$FLOW_ID&DATE1=$DATE1&DATE2=$DATE2'><div nowrap style='color:red' style='display:inline;'>$SCOREDATA_COUNT</div></a>"; else echo "$SCOREDATA_COUNT"?></td>
    <td nowrap align="left" class="TableData" ><a href="javascript:;" onClick="window.open('scoredata_query.php?USER_ID=<?=$USER_ID?>&FLOW_ID=<?=$FLOW_ID?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>','','height=700,width=900,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=800,top=270,resizable=yes');"><?=_("明细")?></a></td>
  </tr>
  <tr align="center" class="TableControl">
    <td colspan="4">
      <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close();" title="<?=_("关闭窗口")?>">
    </td>
  </tr>
</table>
</body>

</html>
