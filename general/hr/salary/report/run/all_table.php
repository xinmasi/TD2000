<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$query = "SELECT ITEM_ID from SAL_ITEM where ISREPORT='1'";
$cursor= exequery(TD::conn(),$query);
$FLOW_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    	$STYLE=$STYLE.$ROW["ITEM_ID"].",";
}

$query="select * from SAL_FLOW where FLOW_ID='$FLOW_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	$SAL_YEAR=$ROW["SAL_YEAR"];
	$SAL_MONTH=$ROW["SAL_MONTH"];
}

$HTML_PAGE_TITLE = _("人员工资录入");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js"></script>
<script Language="JavaScript">
function CheckForm()
{
    /* var fanhui=1;
    $('table input[type="text"]').each(function(){
        
        if(isNaN($(this).val()))
            {
                alert("请输入相应的数字！");
                fanhui=0;
            }
        if($(this).val()=="")
            {
                alert("请输入相应的值！");
                fanhui=0;
            }
    });
    if(fanhui==0)
    {
        return (false);
    }
    else
    {
       return (true);
    }
     */
    
}
</script>
<body class="bodycolor" leftmargin="0">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/salary.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"><?=substr(GetDeptNameById($DEPT_ID),0,-1)?><?=_("绩效上报")?>(<?=$SAL_YEAR._("年").$SAL_MONTH._("月")?>)</span>
    </td>
  </tr>
</table>

<form action="alltable_submit.php"  method="post" name="form1" onSubmit="return CheckForm();">
<table class="TableBlock" align="center" width="98%">
    <tr class="TableHeader" align="center">
      <td nowrap width="15%"><b><?=_("姓名")?></b></td>
<?
 $STYLE_ARRAY=explode(",",$STYLE);
 $ARRAY_COUNT=sizeof($STYLE_ARRAY);
 $COUNT=0;
 if($STYLE_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
 for($I=0;$I<$ARRAY_COUNT;$I++)
 {
   	 $query1 = "select ITEM_ID,ITEM_NAME from SAL_ITEM where ITEM_ID='$STYLE_ARRAY[$I]'";
     $cursor1= exequery(TD::conn(),$query1);
     if($ROW=mysql_fetch_array($cursor1))
     {
        	$ITEM_NAME=$ROW["ITEM_NAME"];
          $ITEM_ID[$COUNT]=$ROW["ITEM_ID"];
     }
     $COUNT++;
?>
      <td nowrap align="center" onClick="clickTitle('<?=$ITEM_ID[$COUNT-1]?>')" style="cursor:hand"><b><?=$ITEM_NAME?></b></td>
<?
 }
?>
 <td><?=_("员工表现")?></td>
 <td><?=_("相关操作")?> </td>
    </tr>
<?
//============================ 显示已定义用户 =======================================
 //$DEPT_ID = intval($DEPT_ID);
 $query8 = "SELECT * from USER,USER_PRIV where USER.USER_PRIV=USER_PRIV.USER_PRIV order by PRIV_NO,USER_NO,USER_NAME";
 $cursor8= exequery(TD::conn(),$query8);
 $USER_COUNT=0;
 while($ROW8=mysql_fetch_array($cursor8))
 {
    $DEPT_ID = $ROW8["DEPT_ID"];
    $USER_COUNT++;
    $USER_ID=$ROW8["USER_ID"];
    $USER_NAME=$ROW8["USER_NAME"];
   // $STYLE_USER=$STYLE_USER.$USER_ID.",";
    if($USER_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableContent";
        
    $data[$DEPT_ID][] = array(
            "USER_ID" => $USER_ID,
            "USER_NAME" => $USER_NAME,
            //"STYLE_USER" => $STYLE_USER,
            "TableLine" => $TableLine
    );
  }
     foreach($data as $k=>$v){
                $a=array(
                    "dept_id"=>$k,
                    "data"=>$data[$k]
                );
               $count = count($a["data"]);
       ?>       
     <tr style="width:500px">
        <td class="Big" style="width:500px"><span class="big3"><?=substr(GetDeptNameById($a["dept_id"]),0,-1)?>：</span>
    </td>
    </tr>
       <?     
            for($i=0;$i<$count;$i++){
                   $USER_NAME = $a["data"][$i]["USER_NAME"];
                   $USER_ID = $a["data"][$i]["USER_ID"];
                   $TableLine = $a["data"][$i]["TableLine"];
                   $STYLE_USER=$STYLE_USER.$USER_ID.",";
            

  
?>
    <tr class="TableLine1" align="center">
      <td nowrap><?=$USER_NAME?></td>
<?

  $query5="select * from SAL_DATA where FLOW_ID='$FLOW_ID' and USER_ID='$USER_ID'";
  $cursor5= exequery(TD::conn(),$query5);
  if($ROW5=mysql_fetch_array($cursor5))  
     $OPERATION=2; //-- 将执行数据更新 --
  else
     $OPERATION=1; //-- 将执行数据插入 --

  $query5="select * from SAL_DATA where FLOW_ID='$FLOW_ID' and USER_ID='$USER_ID' and IS_DEPT_INPUT='1'";
  $cursor5= exequery(TD::conn(),$query5);
  if($ROW5=mysql_fetch_array($cursor5))
  {
     for($I=1;$I<=50;$I++)
     {
       $STR="S".$I;
       $STR2=$STR.$USER_ID;
       $$STR2=format_money($ROW5["$STR"]);
     }
  }
  else
  {
     $query5="select * from HR_SAL_DATA where USER_ID='$USER_ID'";
     $cursor5= exequery(TD::conn(),$query5);
     if($ROW5=mysql_fetch_array($cursor5))
     {
       for($I=1;$I<=50;$I++)
       {
         $STR="S".$I;
         $STR2=$STR.$USER_ID;
         $$STR2=format_money($ROW5["$STR"]);
       }
     }
  }

  $STYLE_ARRAY=explode(",",$STYLE);
  $ARRAY_COUNT=sizeof($STYLE_ARRAY);
  $COUNT=0;
  if($STYLE_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
  for($I=0;$I<$ARRAY_COUNT;$I++)
   {
   	 $query1 = "select ITEM_ID,ITEM_NAME from SAL_ITEM where ITEM_ID='$STYLE_ARRAY[$I]'";
     $cursor1= exequery(TD::conn(),$query1);
     if($ROW=mysql_fetch_array($cursor1))
     {
        $ITEM_NAME=$ROW["ITEM_NAME"];
        $ITEM_ID[$COUNT]=$ROW["ITEM_ID"];
        $S_ID="S".$ITEM_ID[$COUNT].$USER_ID;
     }
     $COUNT++;
?>
      <td nowrap align="center"><input type="text" name="<?=$USER_ID?>_<?=$ITEM_ID[$COUNT-1]?>" class="SmallInput" value="<?=$$S_ID?>" size="10" ></td>
<?
  }
?>
    <input type="hidden" name="<?=$USER_ID?>_OPERATION" class="SmallInput" value="<?=$OPERATION?>" size="10" >
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
if($DIARY_NUM != 0)
   $DIARY_NUM=sprintf(_("日志%s篇"), $DIARY_NUM);
//日程
$query="select count(CAL_ID) from CALENDAR where CAL_TYPE!='2' and USER_ID='$USER_ID' and CAL_TIME >= '$MONTH_BEGIN1' and END_TIME <= '$MONTH_END1' ";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $CAL_NUM=$ROW["0"];
$query="select count(CAL_ID) from CALENDAR where CAL_TYPE!='2' and USER_ID='$USER_ID' and CAL_TIME >= '$MONTH_BEGIN1' and END_TIME <= '$MONTH_END1' and OVER_STATUS='1'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $CAL_NUM1=$ROW["0"];
if($CAL_NUM!=0)
   $CAL_NUM=sprintf(_("日程共%s条，完成%s条"), $CAL_NUM, $CAL_NUM1);
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
	if($ROW=mysql_fetch_array($cursor))
	  	$INCENTIVE_NUM++;
	 if($INCENTIVE_NUM!='0')
	$INCENTIVE_NUM=$CODE_NAME.$INCENTIVE_NUM._("次");
}

//考勤
$DATE1=$SAL_YEAR."-".$SAL_MONTH."-"."01";
$DATE2=$SAL_YEAR."-".$SAL_MONTH."-".date("t",mktime(0,0,0,$SAL_MONTH,5,$SAL_YEAR));
$NOT_REG_COUNT = 0;
$LAST_COUNT = 0;
$EARLY_COUNT= 0;

$query = "SELECT * from USER where USER_ID='$USER_ID'";
$cursor= exequery(TD::conn(),$query);
$LINE_COUNT=0;
if($ROW=mysql_fetch_array($cursor))
{
   $USER_ID=$ROW["USER_ID"];
   $USER_NAME=$ROW["USER_NAME"];
    $query_1="select DUTY_TYPE from USER_EXT where USER_ID='$USER_ID'";
    $cursor_1=exequery(TD::conn(),$query_1);
    if($row1=mysql_fetch_array($cursor_1))
    	$DUTY_TYPE=$ROW["DUTY_TYPE"];
   $DEPT_ID=$ROW["DEPT_ID"];
}

$CUR_DATE=date("Y-m-d",time());

$query = "SELECT to_days('$DATE2')-to_days('$DATE1') from ATTEND_CONFIG";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $DAY_TOTAL=$ROW[0]+1;



//---- 取规定上下班时间 -----
$query1 = "SELECT * from ATTEND_CONFIG where DUTY_TYPE='$DUTY_TYPE'";
$cursor1= exequery(TD::conn(),$query1);
if($ROW=mysql_fetch_array($cursor1))
{
   $DUTY_NAME=$ROW["DUTY_NAME"];
   $GENERAL=$ROW["GENERAL"];

   $DUTY_TIME1=$ROW["DUTY_TIME1"];
   $DUTY_TIME2=$ROW["DUTY_TIME2"];
   $DUTY_TIME3=$ROW["DUTY_TIME3"];
   $DUTY_TIME4=$ROW["DUTY_TIME4"];
   $DUTY_TIME5=$ROW["DUTY_TIME5"];
   $DUTY_TIME6=$ROW["DUTY_TIME6"];

   $DUTY_TYPE1=$ROW["DUTY_TYPE1"];
   $DUTY_TYPE2=$ROW["DUTY_TYPE2"];
   $DUTY_TYPE3=$ROW["DUTY_TYPE3"];
   $DUTY_TYPE4=$ROW["DUTY_TYPE4"];
   $DUTY_TYPE5=$ROW["DUTY_TYPE5"];
   $DUTY_TYPE6=$ROW["DUTY_TYPE6"];
}

$COUNT = 0;
if($DUTY_TIME1!="")
{
   $COUNT++;
   if($DUTY_TYPE1=="1")
      $DUTY_TYPE_DESC=_("上班");
   else
      $DUTY_TYPE_DESC=_("下班");
}
if($DUTY_TIME2!="")
{
   $COUNT++;
   if($DUTY_TYPE2=="1")
      $DUTY_TYPE_DESC=_("上班");
   else
      $DUTY_TYPE_DESC=_("下班");

}
if($DUTY_TIME3!="")
{
   $COUNT++;
   if($DUTY_TYPE3=="1")
      $DUTY_TYPE_DESC=_("上班");
   else
      $DUTY_TYPE_DESC=_("下班");
}
if($DUTY_TIME4!="")
{
   $COUNT++;
   if($DUTY_TYPE4=="1")
      $DUTY_TYPE_DESC=_("上班");
   else
      $DUTY_TYPE_DESC=_("下班");
}
if($DUTY_TIME5!="")
{
   $COUNT++;
   if($DUTY_TYPE5=="1")
      $DUTY_TYPE_DESC=_("上班");
   else
      $DUTY_TYPE_DESC=_("下班");
}
if($DUTY_TIME6!="")
{
   $COUNT++;
   if($DUTY_TYPE6=="1")
      $DUTY_TYPE_DESC=_("上班");
   else
      $DUTY_TYPE_DESC=_("下班");
}

for($J=$DATE1;$J<=$DATE2;$J=date("Y-m-d",strtotime($J)+24*3600))
{
   $WEEK=date("w",strtotime($J));
   $HOLIDAY="";
   $query="select * from ATTEND_HOLIDAY where BEGIN_DATE <='$J' and END_DATE>='$J'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
        $HOLIDAY="<font color='#008000'>"._("节假日")."</font>";
   else
   {
        if(find_id($GENERAL,$WEEK))
           $HOLIDAY="<font color='#008000'>"._("公休日")."</font>";
   }

   if($HOLIDAY=="")
      $TableLine="TableData";
   else
      $TableLine="TableContent";

   //查询考勤记录
   $query1 = "SELECT * from ATTEND_DUTY where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$J') GROUP by to_days(REGISTER_TIME)";
   $cursor1= exequery(TD::conn(),$query1);
   $LINE_COUNT=0;
   if($ROW=mysql_fetch_array($cursor1))
   {
     $LINE_COUNT++;
     $REGISTER_TIME=$ROW["REGISTER_TIME"];

     $query="select * from ATTEND_EVECTION where USER_ID='$USER_ID' and ALLOW='1' and to_days(EVECTION_DATE1)<=to_days('$J') and to_days(EVECTION_DATE2)>=to_days('$J')";
     $cursor= exequery(TD::conn(),$query);
     if($ROW=mysql_fetch_array($cursor))
        $HOLIDAY="<font color='#008000'>"._("出差")."</font>";

    //---- 第1组 -----
   for($I=1;$I<=6;$I++)
    {
       $DUTY_TIME_I="DUTY_TIME".$I;
       $DUTY_TIME_I=$$DUTY_TIME_I;
       $DUTY_TYPE_I="DUTY_TYPE".$I;
       $DUTY_TYPE_I=$$DUTY_TYPE_I;

       if($DUTY_TIME_I=="" || $DUTY_TIME_I=="00:00:00")
          continue;

       $HOLIDAY1="";
       if($HOLIDAY=="")
       {
          $query="select * from ATTEND_LEAVE where USER_ID='$USER_ID' and (ALLOW='1' or ALLOW='3') and LEAVE_DATE1<='$J $DUTY_TIME_I' and LEAVE_DATE2>='$J $DUTY_TIME_I'";
          $cursor= exequery(TD::conn(),$query);
          if($ROW=mysql_fetch_array($cursor))
             $HOLIDAY1="<font color='#008000'>"._("请假")."</font>";
       }
       else
          $HOLIDAY1=$HOLIDAY;

       if($HOLIDAY==""&&$HOLIDAY1=="")
       {
           $DUTY_TIME_I = date("H:s:i",strtotime($DUTY_TIME_I));
          $query="select * from ATTEND_OUT where USER_ID='$USER_ID' and ALLOW='1' and to_days(SUBMIT_TIME)=to_days('$J') and OUT_TIME1<='".substr($DUTY_TIME_I,0,strrpos($DUTY_TIME_I,":"))."' and OUT_TIME2>='".substr($DUTY_TIME_I,0,strrpos($DUTY_TIME_I,":"))."'";
          $cursor= exequery(TD::conn(),$query);
          if($ROW=mysql_fetch_array($cursor))
             $HOLIDAY1="<font color='#008000'>"._("外出")."</font>";
       }

       $REGISTER_TIME="";
       $REMARK="";
       $ADD_IP_FLAG=0;
       $query = "SELECT * from ATTEND_DUTY where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$J') and REGISTER_TYPE='$I'";
       $cursor= exequery(TD::conn(),$query);
       if($ROW=mysql_fetch_array($cursor))
       {
          $REGISTER_TIME2=$ROW["REGISTER_TIME"];
          $REGISTER_TIME=$ROW["REGISTER_TIME"];
          $REGISTER_IP=$ROW["REGISTER_IP"];
          $REMARK=$ROW["REMARK"];
          $REMARK=str_replace("\n","<br>",$REMARK);
          $REGISTER_TIME=strtok($REGISTER_TIME," ");
          $REGISTER_TIME=strtok(" ");

          if($HOLIDAY1==""&&$DUTY_TYPE_I=="1" && compare_time($REGISTER_TIME,$DUTY_TIME_I)==1)
             {$LAST_COUNT++;}
             
          if($HOLIDAY1==""&&$DUTY_TYPE_I=="2" && compare_time($REGISTER_TIME,$DUTY_TIME_I)==-1)
             {$EARLY_COUNT++;}
       }
       else
       {
          if($HOLIDAY1=="")
          {//echo $J; echo "<br>";
             $NOT_REG_COUNT++;
          }
       }
    }
   }
   else //未查到考勤记录
   {
      for($I=1;$I<=$COUNT;$I++)
      {
          $DUTY_TIME_I="DUTY_TIME".$I;
          $DUTY_TIME_I=$$DUTY_TIME_I;
          $DUTY_TYPE_I="DUTY_TYPE".$I;
          $DUTY_TYPE_I=$$DUTY_TYPE_I;
      
        	$OUT = "";
          $query="select USER_ID from ATTEND_EVECTION where USER_ID='$USER_ID' and ALLOW='1' and to_days(EVECTION_DATE1)<=to_days('$J') and to_days(EVECTION_DATE2)>=to_days('$J')";
          $cursor= exequery(TD::conn(),$query);
          if($ROW=mysql_fetch_array($cursor))
             $OUT="<font color='#008000'>"._("出差")."</font>";
      
          $query="select * from ATTEND_OUT where USER_ID='$USER_ID' and ALLOW='1' and to_days(SUBMIT_TIME)=to_days('$J') and OUT_TIME1<='".substr($DUTY_TIME_I,0,strrpos($DUTY_TIME_I,":"))."' and OUT_TIME2>='".substr($DUTY_TIME_I,0,strrpos($DUTY_TIME_I,":"))."'";
          $cursor= exequery(TD::conn(),$query);
          if($ROW=mysql_fetch_array($cursor))
          {
             $OUT="<font color=red>"._("未登记")."</font>(<font color='#008000'>"._("外出")."</font>)";
             //echo $J; echo "<br>";
             $NOT_REG_COUNT++;
          }
      
          $query="select * from ATTEND_LEAVE where USER_ID='$USER_ID' and (ALLOW='1' or ALLOW='3') and LEAVE_DATE1 <= '$J $DUTY_TIME_I' and LEAVE_DATE2 >= '$J $DUTY_TIME_I'";
          $cursor= exequery(TD::conn(),$query);
          if($ROW=mysql_fetch_array($cursor))
             $OUT="<font color='#008000'>"._("请假")."</font>";
      
      	 if($OUT!="" && $HOLIDAY=="")
      	 {}
      	 else if($HOLIDAY!="")
         {}
         else
         {//echo $J; echo "<br>";
            $NOT_REG_COUNT++;
         }
      }
   }
}//for
$REGISTER="";
if($NOT_REG_COUNT!=0)
$REGISTER = sprintf(_("上下班未登记%s次；"), $NOT_REG_COUNT);
if($LAST_COUNT!=0)
$REGISTER .= sprintf(_("迟到%s次；"), $LAST_COUNT);
if($EARLY_COUNT!=0)
$REGISTER .= sprintf(_("早退%s次；"), $EARLY_COUNT);

$query = "SELECT count(USER_ID) from ATTEND_OUT where USER_ID='$USER_ID' and to_days(SUBMIT_TIME)>=to_days('$DATE1') and to_days(SUBMIT_TIME)<=to_days('$DATE2') and ALLOW='1' order by SUBMIT_TIME";
$cursor= exequery(TD::conn(),$query);
$OUT_COUNT=0;
if($ROW=mysql_fetch_array($cursor))
   $OUT_COUNT=$ROW[0];

$query = "SELECT count(USER_ID) from ATTEND_LEAVE where USER_ID='$USER_ID' and ((to_days(LEAVE_DATE1)>=to_days('$DATE1') and to_days(LEAVE_DATE1)<=to_days('$DATE2')) or (to_days(LEAVE_DATE2)>=to_days('$DATE1') and to_days(LEAVE_DATE2)<=to_days('$DATE2')) or (to_days(LEAVE_DATE1)<=to_days('$DATE1') and to_days(LEAVE_DATE2)>=to_days('$DATE2'))) and allow in('1','3')";
$cursor= exequery(TD::conn(),$query);
$LEAVE_COUNT=0;
if($ROW=mysql_fetch_array($cursor))
   $LEAVE_COUNT =$ROW[0];

$query = "SELECT count(USER_ID) from ATTEND_EVECTION where USER_ID='$USER_ID' and ((to_days(EVECTION_DATE1)>=to_days('$DATE1') and to_days(EVECTION_DATE1)<=to_days('$DATE2')) or (to_days(EVECTION_DATE2)>=to_days('$DATE1') and to_days(EVECTION_DATE2)<=to_days('$DATE2')) or (to_days(EVECTION_DATE1)<=to_days('$DATE1') and to_days(EVECTION_DATE2)>=to_days('$DATE2'))) and ALLOW='1'";
$cursor= exequery(TD::conn(),$query);
$EVECTION_COUNT=0;
if($ROW=mysql_fetch_array($cursor))
   $EVECTION_COUNT = $ROW[0];
if($OUT_COUNT!=0)
$REGISTER .= sprintf(_("外出%s次；"), $OUT_COUNT);
if($LEAVE_COUNT!=0)
$REGISTER .= sprintf(_("请假%s次；"), $LEAVE_COUNT);
if($EVECTION_COUNT!=0)
$REGISTER .= sprintf(_("出差%s次；"), $EVECTION_COUNT);

//--------加班--------
$query = "SELECT count(USER_ID) from ATTENDANCE_OVERTIME where USER_ID='$USER_ID' and to_days(START_TIME)>=to_days('$DATE1') and to_days(START_TIME)<=to_days('$DATE2') and ALLOW='1' and STATUS='1' order by START_TIME";
$cursor= exequery(TD::conn(),$query);
$OVERTIME_COUNT=0;
if($ROW=mysql_fetch_array($cursor))
   $OVERTIME_COUNT = $ROW[0];
if($OVERTIME_COUNT!=0)
$REGISTER.=sprintf(_("加班%s次"), $OVERTIME_COUNT);

?>  	
  <td align=left>
    <?if($DIARY_NUM!="0")echo $DIARY_NUM._("；"); ?><?if($CAL_NUM!="0") echo $CAL_NUM._("；"); ?><?if($INCENTIVE_NUM!="00") echo $INCENTIVE_NUM._("；"); ?><?=$REGISTER?>  	    	
  </td>
  <td id="">
  <!--	<a href="javascript:;" onClick="window.open('attendance.php?USER_ID=<?=$USER_ID?>&FLOW_ID=<?=$FLOW_ID?>','','height=150,width=560,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=500,top=270,resizable=yes');"><?=_("考勤数据")?></a>&nbsp;&nbsp;
  	<a href="javascript:;" onClick="window.open('scoredata.php?USER_ID=<?=$USER_ID?>&FLOW_ID=<?=$FLOW_ID?>','','height=150,width=560,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=500,top=270,resizable=yes');"><?=_("考核数据")?></a>&nbsp;&nbsp;
 	  <a href="javascript:;" onClick="window.open('diary.php?USER_ID=<?=$USER_ID?>&FLOW_ID=<?=$FLOW_ID?>','','height=150,width=560,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=500,top=270,resizable=yes');"><?=_("日志")?></a>&nbsp;&nbsp;
 	  <a href="javascript:;" onClick="window.open('incentive.php?USER_ID=<?=$USER_ID?>&FLOW_ID=<?=$FLOW_ID?>','','height=150,width=560,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=500,top=270,resizable=yes');"><?=_("奖惩")?></a>
 	-->  
 	  <a href="javascript:;" onClick="window.open('staff_detail.php?USER_ID=<?=$USER_ID?>&FLOW_ID=<?=$FLOW_ID?>','','height=700,width=900,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=300,top=270,resizable=yes');"><?=_("员工表现详情")?></a>
 	</td>
  </tr>
<?
}
};
 if($USER_COUNT==0)
    $DEPT_COUNT--;
?>    
</table>
<br>
<div align="center">
<input type="hidden" value="<?=$STYLE?>"  name="STYLE">
<input type="hidden" value="<?=$FLOW_ID?>"  name="FLOW_ID">
<input type="hidden" value="<?=$DEPT_ID?>"  name="DEPT_ID">
<input type="hidden" value="<?=$STYLE_USER?>"  name="STYLE_USER">
<input type="submit" value="<?=_("上报")?>" class="BigButton" title="<?=_("上报")?>" name="button">&nbsp;
</div>
</form>
</body>
</html>
<script language="JavaScript">
function clickTitle(ID)
{
  var str1=document.all("STYLE_USER").value;
  var id_value_array=str1.split(",");
  var temp=id_value_array.length-2;
  for(i=0;i<=temp;i++){
  	control=id_value_array[i]+"_"+ID;
  	if(i==0)setvalue=document.all(control).value;
  	document.all(control).value=setvalue;
  }
}
</script>