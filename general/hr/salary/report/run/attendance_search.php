<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("考勤情况查询");
include_once("inc/header.inc.php");
?>


<script>
function out_edit(OUT_ID)
{
 URL="out_edit.php?OUT_ID="+OUT_ID;
 myleft=(screen.availWidth-780)/2;
 mytop=100;
 mywidth=650;
 myheight=400;
 window.open(URL,"out_edit","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}
function leave_edit(LEAVE_ID)
{
 URL="leave_edit.php?LEAVE_ID="+LEAVE_ID;
 myleft=(screen.availWidth-780)/2;
 mytop=100;
 mywidth=650;
 myheight=400;
 window.open(URL,"leave_edit","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}
function evection_edit(EVECTION_ID)
{
 URL="evection_edit.php?EVECTION_ID="+EVECTION_ID;
 myleft=(screen.availWidth-780)/2;
 mytop=100;
 mywidth=650;
 myheight=400;
 window.open(URL,"evection_edit","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}


</script>


<body class="bodycolor">

<?
  //----------- 合法性校验 ---------
  if($DATE1!="")
  {
    $TIME_OK=is_date($DATE1);

    if(!$TIME_OK)
    { Message(_("错误"),_("起始日期格式不对，应形如 1999-1-2"));
      Button_Back();
      exit;
    }
  }

  if($DATE2!="")
  {
    $TIME_OK=is_date($DATE2);

    if(!$TIME_OK)
    { Message(_("错误"),_("截止日期格式不对，应形如 1999-1-2"));
      Button_Back();
      exit;
    }
  }

  if(compare_date($DATE1,$DATE2)==1)
  { Message(_("错误"),_("查询的起始日期不能晚于截止日期"));
    Button_Back();
    exit;
  }

$query = "SELECT USER_EXT.DUTY_TYPE,USER.USER_NAME,USER.DEPT_ID from USER,USER_EXT where USER.UID=USER_EXT.UID and USER.USER_ID='$USER_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $DUTY_TYPE=$ROW["DUTY_TYPE"];
   $USER_NAME=$ROW["USER_NAME"];
   $DEPT_ID=$ROW["DEPT_ID"];
}

 if(!is_dept_priv($DEPT_ID) && $_SESSION["LOGIN_USER_PRIV"]!=1)
 {
  	 Message(_("错误"),_("不属于管理范围内的用户").$DEPT_ID);
     exit;
 }

 $CUR_DATE=date("Y-m-d",time());

 $query = "SELECT to_days('$DATE2')-to_days('$DATE1') from ATTEND_CONFIG";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
    $DAY_TOTAL=$ROW[0]+1;
?>

<!------------------------------------- 上下班 ------------------------------->
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("上下班统计")?>
    <?=sprintf(_("（%s，从 %s 至 %s 共 %s 天）"), $USER_NAME, format_date($DATE1), format_date($DATE2), $DAY_TOTAL)?>
    </span><br>
    </td>
  </tr>
</table>

<?
if($DUTY_TYPE!='99')
{
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
?>
 <table class="TableList"  width="95%" align="center">
  <tr class="TableHeader">
    <td nowrap align="center"><?=_("日期")?></td>
<?
 $COUNT = 0;
 if($DUTY_TIME1!="")
 {
    $COUNT++;
    if($DUTY_TYPE1=="1")
       $DUTY_TYPE_DESC=_("上班");
    else
       $DUTY_TYPE_DESC=_("下班");
?>
    <td nowrap align="center"><?=$DUTY_TYPE_DESC?>(<?=$DUTY_TIME1?>)</td>
<?
 }
 if($DUTY_TIME2!="")
 {
    $COUNT++;
    if($DUTY_TYPE2=="1")
       $DUTY_TYPE_DESC=_("上班");
    else
       $DUTY_TYPE_DESC=_("下班");
?>
    <td nowrap align="center"><?=$DUTY_TYPE_DESC?>(<?=$DUTY_TIME2?>)</td>
<?
 }
 if($DUTY_TIME3!="")
 {
    $COUNT++;
    if($DUTY_TYPE3=="1")
       $DUTY_TYPE_DESC=_("上班");
    else
       $DUTY_TYPE_DESC=_("下班");
?>
    <td nowrap align="center"><?=$DUTY_TYPE_DESC?>(<?=$DUTY_TIME3?>)</td>
<?
 }
 if($DUTY_TIME4!="")
 {
    $COUNT++;
    if($DUTY_TYPE4=="1")
       $DUTY_TYPE_DESC=_("上班");
    else
       $DUTY_TYPE_DESC=_("下班");
?>
    <td nowrap align="center"><?=$DUTY_TYPE_DESC?>(<?=$DUTY_TIME4?>)</td>
<?
 }
 if($DUTY_TIME5!="")
 {
    $COUNT++;
    if($DUTY_TYPE5=="1")
       $DUTY_TYPE_DESC=_("上班");
    else
       $DUTY_TYPE_DESC=_("下班");
?>
    <td nowrap align="center"><?=$DUTY_TYPE_DESC?>(<?=$DUTY_TIME5?>)</td>
<?
 }
 if($DUTY_TIME6!="")
 {
    $COUNT++;
    if($DUTY_TYPE6=="1")
       $DUTY_TYPE_DESC=_("上班");
    else
       $DUTY_TYPE_DESC=_("下班");
?>
    <td nowrap align="center"><?=$DUTY_TYPE_DESC?>(<?=$DUTY_TIME6?>)</td>
<?
 }
?>
  </tr>

<?
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
?>
       <tr class="<?=$TableLine?>">
         <td nowrap align="center"><?=$J?>(<?=get_week($J)?>)</td>

<?
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
             {$REGISTER_TIME.="(".$REGISTER_IP.") <font color=red><b>"._("迟到")."</b></font>";$ADD_IP_FLAG=1;}

          if($HOLIDAY1==""&&$DUTY_TYPE_I=="2" && compare_time($REGISTER_TIME,$DUTY_TIME_I)==-1)
             {$REGISTER_TIME.="(".$REGISTER_IP.") <font color=red><b>"._("早退")."</b></font>";$ADD_IP_FLAG=1;}

          if($REMARK!="")
             $REMARK="<br>"._("说明：").$REMARK;
       }
       else
       {
          if($HOLIDAY1=="")
          {
             $REGISTER_TIME=_("未登记");
             $ADD_IP_FLAG=1;
          }
          else
          {
          	 if($REGISTER_IP!="")
                $REGISTER_TIME=$HOLIDAY1."(".$REGISTER_IP.")";
             else
                $REGISTER_TIME=$HOLIDAY1;
             $ADD_IP_FLAG=1;
          }
       }

       if($ADD_IP_FLAG!=1)
          $REGISTER_TIME.="(".$REGISTER_IP.")";

?>
         <td nowrap align="center"><?=$REGISTER_TIME?><?=$REMARK?>&nbsp;
         </td>
<?
    }
?>
       </tr>
<?
   }
   else //未查到考勤记录
   {
?>
       <tr class="<?=$TableLine?>">
         <td nowrap align="center"><?=$J?>(<?=get_week($J)?>)</td>
<?
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
       $OUT="<font color=red>"._("未登记")."</font>(<font color='#008000'>"._("外出")."</font>)";

    $query="select * from ATTEND_LEAVE where USER_ID='$USER_ID' and (ALLOW='1' or ALLOW='3') and LEAVE_DATE1 <= '$J $DUTY_TIME_I' and LEAVE_DATE2 >= '$J $DUTY_TIME_I'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
       $OUT="<font color='#008000'>"._("请假")."</font>";

	 if($OUT!="" && $HOLIDAY=="")
	    echo "<td nowrap align=center>$OUT</td>";
	 else if($HOLIDAY!="")
      echo "<td nowrap align=center>$HOLIDAY</td>";
   else
      echo "<td nowrap align=center><font color=red>"._("未登记")."</font></td>";
}
?>
       </tr>
<?
   }
}//for
}
?>
</table>
<br>
<div align="center">
	<input type="button"  class="BigButton" value="<?=_("关闭")?>" onclick="window.close();">
</div>

</body>
</html>