<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("新建出差登记");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
//----------- 合法性校验 ---------
if($EVECTION_DATE1!="")
{
  $TIME_OK=is_date($EVECTION_DATE1);

  if(!$TIME_OK)
  { Message(_("错误"),_("出差开始日期格式不对，应形如 1999-1-2"));
    Button_Back();
    exit;
  }
}

if($EVECTION_DATE2!="")
{
  $TIME_OK=is_date($EVECTION_DATE2);

  if(!$TIME_OK)
  { Message(_("错误"),_("出差结束日期格式不对，应形如 1999-1-2"));
    Button_Back();
    exit;
  }
}

if(compare_date($EVECTION_DATE1,$EVECTION_DATE2)==1)
{ Message(_("错误"),_("出差开始日期不能晚于出差结束日期"));
  Button_Back();
  exit;
}
$query="select USER_ID from attend_evection  where EVECTION_ID='$EVECTION_ID'";
$result=exequery(TD::conn(),$query);
if($ROWS=mysql_fetch_array($result))
{
    $EVECTION_USER_ID=$ROWS["USER_ID"];
}  
if($EVECTION_DATE1)
{
    // 和外出时间做比较
    $sql = "select *from ATTEND_OUT where  USER_ID = '".$EVECTION_USER_ID."' AND  cast(SUBMIT_TIME as date) <= '".$EVECTION_DATE2."' AND cast(SUBMIT_TIME as date) >= '".$EVECTION_DATE1."'";
    $cursor = exequery(TD::conn(),$sql);

    if($ROW=mysql_fetch_array($cursor)){

        Message(_("错误"),_("您申请出差的时间和外出时间有冲突"));
        Button_Back();
        exit;
    }

    //和请假时间做比较
    $sql = "select *from ATTEND_LEAVE where  USER_ID = '".$EVECTION_USER_ID."' AND (cast(LEAVE_DATE1 as date) >= '".$EVECTION_DATE1."' AND cast(LEAVE_DATE1 as date) <= '".$EVECTION_DATE2."' OR (cast(LEAVE_DATE1 as date) <= '".$EVECTION_DATE1."' AND cast(LEAVE_DATE2 as date) >= '".$EVECTION_DATE1."'))";

    $cursor = exequery(TD::conn(),$sql);

    if($ROW=mysql_fetch_array($cursor)){

        Message(_("错误"),_("您申请出差的时间和请假时间有冲突"));
        Button_Back();
        exit;
    }
    
    //在同一时间段是否重复提交出差申请
    $sql = "select *from ATTEND_EVECTION where EVECTION_ID!='$EVECTION_ID' AND USER_ID = '".$EVECTION_USER_ID."' AND ((EVECTION_DATE1 >= '".$EVECTION_DATE1."' AND EVECTION_DATE1 <= '".$EVECTION_DATE2."') OR (EVECTION_DATE1 <= '".$EVECTION_DATE1."' AND EVECTION_DATE2 >= '".$EVECTION_DATE1."'))";

    $cursor = exequery(TD::conn(),$sql);

    if($ROW=mysql_fetch_array($cursor)){

        Message(_("错误"),_("您此时间段已经申请过出差"));
        Button_Back();
        exit;
    }
}
$query="update ATTEND_EVECTION set ALLOW='0',STATUS='1',EVECTION_DEST='$EVECTION_DEST',REASON='$REASON',EVECTION_DATE1='$EVECTION_DATE1',EVECTION_DATE2='$EVECTION_DATE2',LEADER_ID='$LEADER_ID' where EVECTION_ID='$EVECTION_ID'";
exequery(TD::conn(),$query);

//---------- 事务提醒 ----------
$SMS_CONTENT=$_SESSION["LOGIN_USER_NAME"]._("提交出差申请，请批示！");
$REMIND_URL="attendance/manage/confirm";
if($SMS_REMIND=="on")
   send_sms("",$_SESSION["LOGIN_USER_ID"],$LEADER_ID,6,$SMS_CONTENT,$REMIND_URL);  
if($SMS2_REMIND=="on")
  send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$LEADER_ID,$SMS_CONTENT,6);
  
header("location: ./?connstatus=1");
?>

</body>
</html>
