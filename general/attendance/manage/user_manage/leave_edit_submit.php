<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("修改请假记录");
include_once("inc/header.inc.php");
?>
<body class="bodycolor">

<?
$sql="select ALLOW from attend_leave where LEAVE_ID='$LEAVE_ID'";
$result= exequery(TD::conn(),$sql);
if($row=mysql_fetch_array($result))
{
    $allow = $row["ALLOW"];
}
//----------- 合法性校验 ---------
if($LEAVE_DATE1!="")
{
    $TIME_OK=is_date_time($LEAVE_DATE1);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("请假开始时间格式不对，应形如 1999-01-01 12:12:12"));
        Button_Back();
        exit;
    }
}

if($LEAVE_DATE2!="")
{
    $TIME_OK=is_date_time($LEAVE_DATE2);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("请假结束时间格式不对，应形如 1999-01-01 12:12:12"));
        Button_Back();
        exit;
    }
}

if($DESTROY_TIME!="")
{
    $TIME_OK=is_date_time($DESTROY_TIME);
    if(!$TIME_OK && (($allow!="3" && $DESTROY_TIME!="0000-00-00 00:00:00") || $allow=="3"))
    {
        Message(_("错误"),_("销假时间格式不对，应形如 1999-01-01 12:12:12"));
        Button_Back();
        exit;
    }
    if(compare_date_time($LEAVE_DATE1,$DESTROY_TIME)>=0 && ($allow=="3" || $DESTROY_TIME!="0000-00-00 00:00:00"))
    {
        Message(_("错误"),_("销假时间要晚于请假开始时间"));
        Button_Back();
        exit;
    }
}

if(compare_date_time($LEAVE_DATE1,$LEAVE_DATE2)>=0)
{
    Message(_("错误"),_("请假结束时间应晚于请假开始时间"));
    Button_Back();
    exit;
}
if($allow=="3" && $DESTROY_TIME=="")
{
    Message(_("错误"),_("销假时间不能为空"));
    Button_Back();
    exit;
}
$query="update ATTEND_LEAVE set DESTROY_TIME='$DESTROY_TIME',LEAVE_TYPE='$LEAVE_TYPE',LEAVE_DATE1='$LEAVE_DATE1',LEAVE_DATE2='$LEAVE_DATE2',ANNUAL_LEAVE='$ANNUAL_LEAVE',LEAVE_TYPE2='$LEAVE_TYPE2' where LEAVE_ID='$LEAVE_ID'";
exequery(TD::conn(),$query);

Message(_("提示"),_("修改成功"));
?>
<center>
	<input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="javascript:window.close();">
</center>	
</body>
</html>
