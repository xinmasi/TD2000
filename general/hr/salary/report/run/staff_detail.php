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

$HTML_PAGE_TITLE = _("Ա����������");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif" width="17" height="17"><span class="big3"><?=$USER_NAME?><?=_("��������")?>(<?=$SAL_YEAR._("��").$SAL_MONTH._("��")?>)</span><br>
    </td>
  </tr>
</table>
<?
//�ο�����
if($SAL_YEAR=="")
  	$SAL_YEAR="2005";	
if($SAL_MONTH=="") 	
  	$SAL_MONTH="01";
$MONTH_BEGIN=$SAL_YEAR."-".$SAL_MONTH."-"."01";
$MONTH_BEGIN1=strtotime($MONTH_BEGIN." 00:00:00");
$MONTH_END=$SAL_YEAR."-".$SAL_MONTH."-".date("t",mktime(0,0,0,$SAL_MONTH,5,$SAL_YEAR));
$MONTH_END1=strtotime($MONTH_END." 23:59:59");
//----��־----
$query="select count(DIA_ID) from DIARY where DIA_TYPE!='2' and USER_ID='$USER_ID' and DIA_DATE >= '$MONTH_BEGIN 00:00:00' and DIA_DATE <= '$MONTH_END 23:59:59' ";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $DIARY_NUM=$ROW["0"];
//�ճ�
$query="select count(CAL_ID) from CALENDAR where CAL_TYPE!='2' and USER_ID='$USER_ID' and CAL_TIME >= '$MONTH_BEGIN1' and END_TIME <= '$MONTH_END1' ";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $CAL_NUM=$ROW["0"];
$query="select count(CAL_ID) from CALENDAR where CAL_TYPE!='2' and USER_ID='$USER_ID' and CAL_TIME >= '$MONTH_BEGIN1' and END_TIME <= '$MONTH_END1' and OVER_STATUS='1'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $CAL_NUM1=$ROW["0"];

//����
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
	//�������
	if(($IS_HOLIDAY=check_holiday($J))!=0)//�Ƿ���Ϣ��
	{
		$SHOW_HOLIDAY.="<font color='#008000'>"._("�ڼ���")."</font>";
		$OUGHT_TO=0;
	}
	else if(($IS_HOLIDAY1=check_holiday1($J,$GENERAL))!=0)//�Ƿ�˫����
	{
		$SHOW_HOLIDAY.="<font color='#008000'>"._("������")."</font>";
		$OUGHT_TO=0;
	}
	else if(($IS_EVECTION =check_evection($USER_ID,$J))!=0)//�Ƿ����
	{
		$SHOW_HOLIDAY.="<font color='#008000'>"._("����")."</font>"; 
		$OUGHT_TO=0;
	}
	if($SHOW_HOLIDAY!="" || $SHOW_HOLIDAY2!="")
		$CLASS="TableContent";
	else
		$CLASS="TableData";
	$DAYS_TEM[$J]["CLASS"]=$CLASS;
	$DAYS_TEM[$J]["DUTY_TYPE"]=$DUTY_TYPE;
	$REGISTERS_TEM=array();
	foreach((array)$DUTY_TYPE_ARR["TYPE"] as $REGISTER_TYPE => $DUTY_TYPE_ONE)//�������Ű���Ҫ�Ǽǵ�
	{
		$START_OR_END=$DUTY_TYPE_ONE["DUTY_TYPE"];			//���°ࣺ1���ϰ࣬2���°ࡣ
		$DUTY_TIME_OUGHT=$DUTY_TYPE_ONE["DUTY_TIME"];//�趨�Ŀ���ʱ�䡣
		$DUTY_ONE_ARR=$DUTY_ARR[$REGISTER_TYPE];//��Ӧ�ĵǼǼ�¼

		$HAS_DUTY=0;
		if(is_array($DUTY_ONE_ARR) && !empty($DUTY_ONE_ARR))
		{
			foreach($DUTY_ONE_ARR as $KEY => $VALUE)
				$$KEY=$VALUE;
			$HAS_DUTY=1;
		}

		//��¼��ȡ����$REGISTER_TIME���Ǽ�ʱ�䣬$REGISTER_IP���Ǽ�IP ��$REMARK����ע
		//var_dump(check_leave($USER_ID,$J,$DUTY_TYPE_ARR[$REGISTER_TYPE]["DUTY_TIME"]));
			//var_dump($DUTY_TYPE_ARR["TYPE"][$REGISTER_TYPE]["DUTY_TIME"]);
		$SHOW_HOLIDAY2="";
		//��ʱ�����ġ�
		if(($IS_LEAVE=check_leave($USER_ID,$J,$DUTY_TYPE_ARR["TYPE"][$REGISTER_TYPE]["DUTY_TIME"]))!="0")//�Ƿ����
		{
			$SHOW_HOLIDAY2.="<font color='#008000'>"._("���")."-$IS_LEAVE</font>";
			$OUGHT_TO=0;
		}
	
		else if(($IS_OUT=check_out($USER_ID,$J,$DUTY_TYPE_ARR["TYPE"][$REGISTER_TYPE]["DUTY_TIME"]))!="0")//�Ƿ����
		{
			$SHOW_HOLIDAY2.="<font color='#008000'>"._("���")."</font>";
			$OUGHT_TO=0;
		}
			
		$SHOW_STR="";
		if($HAS_DUTY==1 &&$OUGHT_TO==1)//�Ѿ��Ǽ�
		{
			$REGISTER_TIME2=$DUTY_ONE_ARR["REGISTER_TIME"];
			$REGISTER_TIME=$DUTY_ONE_ARR["REGISTER_TIME"];
			$REGISTER_TIME=strtok($REGISTER_TIME," ");
			$REGISTER_TIME=strtok(" ");
			
			//�ٵ����˲���ȫ�ڣ�$IS_ALL=0;
				//echo $USER_ID."Ӧ�ã�$DUTY_TIME_OUGHT--ʵ�ʣ�$REGISTER_TIME";
				//echo "���ã�".compare_time($REGISTER_TIME,$DUTY_TIME_OUGHT)."<br>";
			
			if($START_OR_END=="1" && compare_time($REGISTER_TIME,$DUTY_TIME_OUGHT)==1)
			{
	          	 if($REGISTER_TYPE==1)
	          	 {
	          	    $LATE_TIME=number_format((strtotime($J.$REGISTER_TIME)-strtotime($J.$DUTY_TIME_OUGHT))/60,0);
	          	    $LAST_TIME[$LAST_COUNT]=$J._("��һ�εǼǳٵ�").$LATE_TIME._("����")."<br>";
	          	 }
	          	 if($REGISTER_TYPE==3)
	          	 {
	          	    $LATE_TIME=number_format((strtotime($J.$REGISTER_TIME)-strtotime($J.$DUTY_TIME_OUGHT))/60,0);
	          	    $LAST_TIME[$LAST_COUNT]=$J._("�ڶ��εǼǳٵ�").$LATE_TIME._("����")."<br>";
	          	 }
	          	 if($REGISTER_TYPE==5)
	          	 {
	          	    $LATE_TIME=number_format((strtotime($J.$REGISTER_TIME)-strtotime($J.$DUTY_TIME_OUGHT))/60,0);
	          	    $LAST_TIME[$LAST_COUNT]=$J._("�����εǼǳٵ�").$LATE_TIME._("����")."<br>";
	          	 }
	          	 $LAST_COUNT++;
				$SHOW_STR.=$REGISTER_TIME." <font color=red><b>"._("�ٵ�")."</b></font>";//�ٵ�
			}
			
			else if($START_OR_END=="2" && compare_time($REGISTER_TIME,$DUTY_TIME_OUGHT)==-1)
			{
          	 if($REGISTER_TYPE==2)
          	 {
          	    $LEAVE_TIME=number_format((strtotime($J.$DUTY_TIME_OUGHT)-strtotime($J.$REGISTER_TIME))/60,0);
          	    $EARLY_TIME[$EARLY_COUNT]=$J._("��һ�εǼ�����").$LEAVE_TIME._("����")."<br>";
          	 }
          	 if($REGISTER_TYPE==4)
          	 {
          	 	$LEAVE_TIME=number_format((strtotime($J.$DUTY_TIME_OUGHT)-strtotime($J.$REGISTER_TIME))/60,0);
          	    $EARLY_TIME[$EARLY_COUNT]=$J._("�ڶ��εǼ�����").$LEAVE_TIME._("����")."<br>";
          	 }
          	 if($REGISTER_TYPE==6)
          	 {
          	    $LEAVE_TIME=number_format((strtotime($J.$DUTY_TIME_OUGHT)-strtotime($J.$REGISTER_TIME))/60,0);
          	    $EARLY_TIME[$EARLY_COUNT]=$J._("�����εǼ�����").$LEAVE_TIME._("����")."<br>";
          	 }
				$EARLY_COUNT++;
				$SHOW_STR.=$REGISTER_TIME." <font color=red><b>"._("����")."</b></font>";//����
			}
			else
				$SHOW_STR.=$REGISTER_TIME;
           if($REMARK!="")
           {
              $REMARK="<br>"._("˵����").$REMARK;
           	  $SHOW_STR.=$REMARK."&nbsp;<a href=\"javascript:remark('$USER_ID','$REGISTER_TYPE','$REGISTER_TIME2');\" title=\""._("�޸�˵��")."\">"._("�޸�")."</a>";
           }
		}
		else if($HAS_DUTY==0 && $OUGHT_TO==1)//Ӧ�õǼǣ�û�еǼǵ�
		{
			$NOT_REG_COUNT++;
			$SHOW_STR.=_("δ�Ǽ�");
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
				$SHOW_STR.=_("δ�Ǽ�");
			}
		}
	}
}
//var_dump($EARLY_TIME);exit;
if($NOT_REG_COUNT!=0)
$REGISTER = _("δ�Ǽ�").$NOT_REG_COUNT._("��")."<br>";
$query = "SELECT * from ATTEND_OUT where USER_ID='$USER_ID' and to_days(SUBMIT_TIME)>=to_days('$DATE1') and to_days(SUBMIT_TIME)<=to_days('$DATE2') and ALLOW='1' order by SUBMIT_TIME";
$cursor= exequery(TD::conn(),$query);
$OUT_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{   
	 $OUT_COUNT++;
      $SUBMIT_TIME=$ROW["SUBMIT_TIME"];
      $SUBMIT_DATE=substr($SUBMIT_TIME,0,10);
	 $OUT_TYPE=$ROW["OUT_TYPE"];
	 
	 $OUT_DESCRIPTION.=$SUBMIT_DATE._("���ԭ��").$OUT_TYPE."<br>";
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
   $LEAVE_DESCRIPTION.=$LEAVE_DATE1._("��").$LEAVE_DATE2._("���ԭ��").$LEAVE_TYPE."<br>";
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
   
   $EVECTION_DESCRIPTION.=$EVECTION_DATE1._("��").$EVECTION_DATE2._("����ԭ��").$REASON."<br>";
}
//$EVECTION_DESCRIPTION=substr($EVECTION_DESCRIPTION,0,-2);
//-------����---------
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
//------�Ӱ�---------
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
   $OVERTIME_DESCRIPTION.=$START_TIME._("��").$END_TIME._("�Ӱ����ݣ�").$OVERTIME_CONTENT."<br>";
}
?>
<style>
	td{word-wrap:break-word;word-break:break-all;}
</style>
<table class="TableBlock" width="90%" align="center">
  <tr>
    <td nowrap align="left" class="TableContent"><?=_("��Ŀ")?></td>
    <td nowrap align="left" class="TableContent" ><?=_("����Ŀ")?></td>
    <td nowrap align="center" class="TableContent"><?=_("����")?></td>
    <td nowrap align="center" class="TableContent"  ><?=_("����")?></td>
  </tr>
  <tr>
    <td nowrap align="left" rowspan=6 class="TableContent"><?=_("����")?></td>
    <td nowrap align="left" class="TableContent" ><?=_("�ٵ�")?></td>
    <td nowrap align="center" class="TableData" ><? if($LAST_COUNT != 0) echo "<div nowrap style='color:red' style='display:inline;'>$LAST_COUNT</div>"; else echo "$LAST_COUNT"?></td>
    <td nowrap align="left" class="TableData" ><? for($i=0;$i< $LAST_COUNT;$i++) echo $LAST_TIME[$i]; ?><a href="javascript:;" onClick="window.open('attendance_search.php?USER_ID=<?=$USER_ID?>&FLOW_ID=<?=$FLOW_ID?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>','','height=700,width=900,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=800,top=270,resizable=yes');"><?=_("��ϸ")?></a></td>
  </tr>
  <tr>
  	<td nowrap align="left" class="TableContent" ><?=_("����")?></td>  
    <td nowrap align="center" class="TableData" ><? if($EARLY_COUNT != 0) echo "<div nowrap style='color:red' style='display:inline;'>$EARLY_COUNT</div>"; else echo "$EARLY_COUNT"?></td>
    <td nowrap align="left" class="TableData" ><? for($i=0;$i< $EARLY_COUNT;$i++) {echo $EARLY_TIME[$i];} ?><a href="javascript:;" onClick="window.open('attendance_search.php?USER_ID=<?=$USER_ID?>&FLOW_ID=<?=$FLOW_ID?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>','','height=700,width=900,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=800,top=270,resizable=yes');"><?=_("��ϸ")?></a></td>
  </tr>
  <tr>
  	<td nowrap align="left" class="TableContent" ><?=_("���")?></td>
    <td nowrap align="center" class="TableData" ><? if($OUT_COUNT != 0) echo "<div nowrap style='color:red' style='display:inline;'>$OUT_COUNT</div>"; else echo "$OUT_COUNT"?></td>
    <td align="left" class="TableData" ><?=str_replace("\n","<br>",$OUT_DESCRIPTION)?><a href="javascript:;" onClick="window.open('attendance_search1.php?USER_ID=<?=$USER_ID?>&FLOW_ID=<?=$FLOW_ID?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>','','height=700,width=900,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=800,top=270,resizable=yes');"><?=_("��ϸ")?></a></td>
  </tr>
  <tr>
  	<td nowrap align="left" class="TableContent" ><?=_("���")?></td>
    <td nowrap align="center" class="TableData" ><? if($LEAVE_COUNT != 0) echo "<div nowrap style='color:red' style='display:inline;'>$LEAVE_COUNT</div>"; else echo "$LEAVE_COUNT"?></td>
    <td align="left" class="TableData" ><?=str_replace("\n","<br>",$LEAVE_DESCRIPTION)?><a href="javascript:;" onClick="window.open('attendance_search2.php?USER_ID=<?=$USER_ID?>&FLOW_ID=<?=$FLOW_ID?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>','','height=700,width=900,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=800,top=270,resizable=yes');"><?=_("��ϸ")?></a></td>
  </tr>
  <tr>
  	<td nowrap align="left" class="TableContent" ><?=_("����")?></td>  
    <td nowrap align="center" class="TableData" ><? if($EVECTION_COUNT != 0) echo "<div nowrap style='color:red' style='display:inline;'>$EVECTION_COUNT</div>"; else echo "$EVECTION_COUNT"?></td>
    <td align="left" class="TableData" ><?=str_replace("\n","<br>",$EVECTION_DESCRIPTION)?><a href="javascript:;" onClick="window.open('attendance_search3.php?USER_ID=<?=$USER_ID?>&FLOW_ID=<?=$FLOW_ID?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>','','height=700,width=900,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=800,top=270,resizable=yes');"><?=_("��ϸ")?></a></td>
  </tr>
  <tr>
  	<td nowrap align="left" class="TableContent" ><?=_("�Ӱ�")?></td>  
    <td nowrap align="center" class="TableData" ><? if($OVERTIME_COUNT != 0) echo "<div nowrap style='color:red' style='display:inline;'>$OVERTIME_COUNT</div>"; else echo "$OVERTIME_COUNT"?></td>
    <td align="left" class="TableData" ><?=str_replace("\n","<br>",$OVERTIME_DESCRIPTION)?><a href="javascript:;" onClick="window.open('attendance_overtime.php?USER_ID=<?=$USER_ID?>&FLOW_ID=<?=$FLOW_ID?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>','','height=700,width=900,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=800,top=270,resizable=yes');"><?=_("��ϸ")?></a></td>
  </tr>
  <tr>
<?
$query3="select count(*) from HR_CODE where PARENT_NO='INCENTIVE_TYPE'";
$cursor3= exequery(TD::conn(),$query3);
if($ROW3=mysql_fetch_array($cursor3))
	$INCENTIVE_COUNT=$ROW3["0"];
?>
    <td nowrap align="left" rowspan=<?=$INCENTIVE_COUNT?> class="TableContent"><?=_("����")?></td>
<?
//����
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
    <td align="left" class="TableData" ><?=str_replace("\n","<br>",$INCENTIVE_DESCRIPTION) ?><a href="javascript:;" onClick="window.open('incentive_search.php?USER_ID=<?=$USER_ID?>&CODE_NO=<?=$CODE_NO?>&FLOW_ID=<?=$FLOW_ID?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>','','height=700,width=900,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=800,top=270,resizable=yes');"><?=_("��ϸ")?></a></td>
  </tr>
  <tr>
<?
}
?>
  </tr>
  <tr>
    <td nowrap align="left" rowspan=2 class="TableContent"><?=_("�ճ�")?></td>
    <td nowrap align="left" class="TableContent" ><?=_("����")?></td>  
    <td nowrap align="center" class="TableData" ><? if($CAL_NUM != 0) echo "<div nowrap style='color:red' style='display:inline;'>$CAL_NUM</div>"; else echo "$CAL_NUM"?></td>
    <td nowrap align="center" class="TableData" ></td>
  </tr>
  <tr>
  	<td nowrap align="left" class="TableContent" ><?=_("�����")?></td>
    <td nowrap align="center" class="TableData" ><? if($CAL_NUM1 != 0) echo "<div nowrap style='color:red' style='display:inline;'>$CAL_NUM1</div>"; else echo "$CAL_NUM1"?></td>
    <td nowrap align="center" class="TableData" ></td>
  </tr>
  <tr>
    <td nowrap align="left"  class="TableContent"><?=_("��־")?></td>
    <td nowrap align="left" class="TableContent" ><?=_("����")?></td>  
    <td nowrap align="center" class="TableData" ><? if($DIARY_NUM != 0) echo "<a href='diary_user_search.php?USER_ID=$USER_ID&FLOW_ID=$FLOW_ID&DATE1=$DATE1&DATE2=$DATE2'><div nowrap style='color:red' style='display:inline;'>$DIARY_NUM</div></a>"; else echo "$DIARY_NUM"?></td>
    <td nowrap align="left" class="TableData" ><a href="javascript:;" onClick="window.open('diary_user_search.php?USER_ID=<?=$USER_ID?>&FLOW_ID=<?=$FLOW_ID?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>','','height=700,width=900,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=800,top=270,resizable=yes');"><?=_("��ϸ")?></a></td>
  </tr>
  <tr>
    <td nowrap align="left"  class="TableContent"><?=_("����")?></td>
    <td nowrap align="left" class="TableContent" ><?=_("����")?></td>  
    <td nowrap align="center" class="TableData" ><? if($SCOREDATA_COUNT != 0) echo "<a href='scoredata_query.php?USER_ID=$USER_ID&FLOW_ID=$FLOW_ID&DATE1=$DATE1&DATE2=$DATE2'><div nowrap style='color:red' style='display:inline;'>$SCOREDATA_COUNT</div></a>"; else echo "$SCOREDATA_COUNT"?></td>
    <td nowrap align="left" class="TableData" ><a href="javascript:;" onClick="window.open('scoredata_query.php?USER_ID=<?=$USER_ID?>&FLOW_ID=<?=$FLOW_ID?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>','','height=700,width=900,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=800,top=270,resizable=yes');"><?=_("��ϸ")?></a></td>
  </tr>
  <tr align="center" class="TableControl">
    <td colspan="4">
      <input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="window.close();" title="<?=_("�رմ���")?>">
    </td>
  </tr>
</table>
</body>

</html>
