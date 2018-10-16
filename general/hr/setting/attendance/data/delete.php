<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
if($TO_ID!="")
{
   $USER_ID_STR=substr($TO_ID,0,-1);
   $WHERE_STR = " and FIND_IN_SET(USER_ID, '$USER_ID_STR')";
   $uid_str = td_trim(GetUidByUserID($TO_ID));
   $WHERE_MOBILE = " and FIND_IN_SET(M_UID, '$uid_str')";
}
if($BEGIN_DATE!="")
{
  $TIME_OK=is_date_time($BEGIN_DATE);

  if(!$TIME_OK)
  { Message(_("错误"),_("起始时间格式不对，应形如 1999-1-2 14:55:20"));
    Button_Back();
    exit;
  }
}

if($END_DATE!="")
{
  $TIME_OK=is_date_time($END_DATE);

  if(!$TIME_OK)
  { Message(_("错误"),_("截止时间格式不对，应形如 1999-1-2 14:55:20"));
    Button_Back();
    exit;
  }
}

if($END_DATE!=""&&$BEGIN_DATE!=""&&$BEGIN_DATE> $END_DATE)
{
   Message(_("错误"),_("截止时间不能晚于起始时间!"));
   Button_Back();
   exit;
}

$TITLE_STR ="";
if($DUTY=="on")
{
	$TITLE_STR .=_("上下班登记");
  $query="delete from ATTEND_DUTY where 1=1".$WHERE_STR;
  if($BEGIN_DATE!="")
     $query.=" and REGISTER_TIME>='$BEGIN_DATE'";
  if($END_DATE!="")
     $query.=" and REGISTER_TIME<='$END_DATE'";
  exequery(TD::conn(),$query);
}

if($OUT=="on")
{
	$TITLE_STR .=_("外出登记");
  $query="delete from ATTEND_OUT where 1=1".$WHERE_STR;
  if($BEGIN_DATE!="")
     $query.=" and SUBMIT_TIME>='$BEGIN_DATE'";
  if($END_DATE!="")
     $query.=" and SUBMIT_TIME<='$END_DATE'";
  exequery(TD::conn(),$query);
}

if($LEAVE=="on")
{
	$TITLE_STR .=_("请假登记");	
  $query="delete from ATTEND_LEAVE where 1=1".$WHERE_STR;
  if($BEGIN_DATE!="")
     $query.=" and LEAVE_DATE1>='$BEGIN_DATE'";
  if($END_DATE!="")
     $query.=" and LEAVE_DATE2<='$END_DATE'";
  exequery(TD::conn(),$query);
}

if($EVECTION=="on")
{
	$TITLE_STR .=_("出差登记");	
  $query="delete from ATTEND_EVECTION where 1=1".$WHERE_STR;
  if($BEGIN_DATE!="")
     $query.=" and EVECTION_DATE1>='$BEGIN_DATE'";
  if($END_DATE!="")
     $query.=" and EVECTION_DATE2<='$END_DATE'";
  exequery(TD::conn(),$query);
}
if($OVERTIME=="on")
{
	$TITLE_STR .=_("加班登记");	
  $query="delete from ATTENDANCE_OVERTIME where 1=1".$WHERE_STR;
  if($BEGIN_DATE!="")
     $query.=" and START_TIME>='$BEGIN_DATE'";
  if($END_DATE!="")
     $query.=" and END_TIME<='$END_DATE'";
  exequery(TD::conn(),$query);
}
if($MOBILE_ATTENDANCE=="on")
{
	$TITLE_STR .=_("外勤登记");	
    $query="delete from attend_mobile where 1=1".$WHERE_MOBILE;
    if($BEGIN_DATE!="")
    {
        $begin_date_time = strtotime($BEGIN_DATE);
        $query.=" and M_TIME>='$begin_date_time'";
    }
    if($END_DATE!="")
    {
        $end_date_time = strtotime($END_DATE);
        $query.=" and M_TIME<='$end_date_time'";
    }
  exequery(TD::conn(),$query);
}

add_log(13,sprintf(_("删除从%s到%s的%s记录"), $BEGIN_DATE, $END_DATE, $TITLE_STR),$_SESSION["LOGIN_USER_ID"]);
Message(_("提示"),_("记录已删除！"));
?>
<br><center><input type="button" class="BigButtonA" value="<?=_("返回")?>" onClick="location.href='index.php'"></center>
</body>
</html>
