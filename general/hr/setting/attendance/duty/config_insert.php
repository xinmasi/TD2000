<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");

include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
$query = "SELECT * from ATTEND_CONFIG where DUTY_NAME='$DUTY_NAME'";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
if(mysql_fetch_array($cursor))
{
    Message(_("错误"),sprintf(_("已存在该排班类型")));
    Button_Back();
    exit;
}
//----------- 合法性校验 ---------
if($DUTY_TIME1!="")
{
    $TIME_OK=is_time($DUTY_TIME1);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("第1次登记时间格式不对，应形如 08:00:00"));
        Button_Back();
        exit;
    }
}

if($DUTY_TIME2!="")
{
    $TIME_OK=is_time($DUTY_TIME2);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("第2次登记时间格式不对，应形如 08:00:00"));
        Button_Back();
        exit;
    }
}

if($DUTY_TIME3!="")
{
    $TIME_OK=is_time($DUTY_TIME3);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("第3次登记时间格式不对，应形如 08:00:00"));
        Button_Back();
        exit;
    }
}

if($DUTY_TIME4!="")
{
    $TIME_OK=is_time($DUTY_TIME4);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("第4次登记时间格式不对，应形如 08:00:00"));
        Button_Back();
        exit;
    }
}

if($DUTY_TIME5!="")
{
    $TIME_OK=is_time($DUTY_TIME5);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("第5次登记时间格式不对，应形如 08:00:00"));
        Button_Back();
        exit;
    }
}

if($DUTY_TIME6!="")
{
    $TIME_OK=is_time($DUTY_TIME6);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("第6次登记时间格式不对，应形如 08:00:00"));
        Button_Back();
        exit;
    }
}

$query="insert into ATTEND_CONFIG(DUTY_NAME,DUTY_TIME1,DUTY_TIME2,DUTY_TIME3,DUTY_TIME4,DUTY_TIME5,DUTY_TIME6,DUTY_BEFORE1,DUTY_AFTER1,DUTY_BEFORE2,DUTY_AFTER2,DUTY_BEFORE3,DUTY_AFTER3,DUTY_BEFORE4,DUTY_AFTER4,DUTY_BEFORE5,DUTY_AFTER5,DUTY_BEFORE6,DUTY_AFTER6,TIME_LATE1,TIME_EARLY2,TIME_LATE3,TIME_EARLY4,TIME_LATE5,TIME_EARLY6,COLOR) ";
$query.="values ('$DUTY_NAME','$DUTY_TIME1','$DUTY_TIME2','$DUTY_TIME3','$DUTY_TIME4','$DUTY_TIME5','$DUTY_TIME6','$DUTY_BEFORE1','$DUTY_AFTER1','$DUTY_BEFORE2','$DUTY_AFTER2','$DUTY_BEFORE3','$DUTY_AFTER3','$DUTY_BEFORE4','$DUTY_AFTER4','$DUTY_BEFORE5','$DUTY_AFTER5','$DUTY_BEFORE6','$DUTY_AFTER6','$TIME_LATE1','$TIME_EARLY2','$TIME_LATE3','$TIME_EARLY4','$TIME_LATE5','$TIME_EARLY6','$color')";
exequery(TD::conn(),$query);

header("location: index.php?connstatus=1");
?>

</body>
</html>