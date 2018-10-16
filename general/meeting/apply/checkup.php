<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/header.inc.php");
?>
<body class="bodycolor">

<?
/*
$query="select * from MEETING where M_ID='$M_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $M_STATUS1=$ROW["M_STATUS"];
}
*/
$query="update MEETING set M_STATUS='$M_STATUS'where M_ID='$M_ID'";
exequery(TD::conn(),$query);
$query = "SELECT CALENDAR,M_ATTENDEE,M_START,M_END,RECORDER,M_NAME FROM MEETING WHERE M_ID='$M_ID'";
$cursor = exequery(TD::conn(), $query);
if($ROW=mysql_fetch_array($cursor))
{
    $CALENDAR = $ROW["CALENDAR"];
    $M_ATTENDEE = $ROW["M_ATTENDEE"];
    $M_START = $ROW["M_START"];
    $M_END = $ROW["M_END"];
    $RECORDER = $ROW["RECORDER"];
    $M_NAME = $ROW["M_NAME"];
    if(!find_id($M_ATTENDEE, $RECORDER))
    {
        $M_ATTENDEE = $M_ATTENDEE.$RECORDER.",";
    }
}
// 不需要审核的会议关联的日程信息
if ($CALENDAR!="" && $M_STATUS==1)
{
    $CONTENT=_("会议:").$M_NAME;//.$M_ID._("：")
    $MY_ARRAY=td_trim($M_ATTENDEE);
    $MY_ARRAY=explode(",",$MY_ARRAY);
    $ARRAY_COUNT=sizeof($MY_ARRAY);
    $URL = '/general/meeting/query/meeting_detail.php?M_ID='.$M_ID;
    for($I=0;$I<$ARRAY_COUNT;$I++)
    {
        $query="insert into CALENDAR(USER_ID,CAL_TIME,END_TIME,CAL_TYPE,CAL_LEVEL,CONTENT,OVER_STATUS,FROM_MODULE,URL,M_ID) values ('$MY_ARRAY[$I]','".strtotime($M_START)."','".strtotime($M_END)."','1','1','$CONTENT','0','2','$URL','$M_ID')";
        exequery(TD::conn(),$query);
    }
}
//header("location: query.php?M_STATUS=$M_STATUS");
Message(_("提示"),_("会议申请保存成功！"));
Button_Back();
?>

</body>

</html>
