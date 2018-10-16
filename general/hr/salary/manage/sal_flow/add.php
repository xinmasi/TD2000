<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("新建工资上报流程");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
$CUR_DATE=date("Y-m-d");
if($BEGIN_DATE!="")
{
    $TIME_OK=is_date($BEGIN_DATE);
    if(!$TIME_OK)
    {
        Message(_("错误"),_("上报起始日期格式不对，应形如 1999-1-2"));
        Button_Back();
        exit;
    }
}

if($END_DATE!="")
{
    $TIME_OK=is_date($END_DATE);
    if(!$TIME_OK)
    {
        Message(_("错误"),_("上报截止日期格式不对，应形如 1999-1-2"));
        Button_Back();
        exit;
    }
}

if(compare_date($BEGIN_DATE,$END_DATE)==1)
{
    Message(_("错误"),_("上报起始日期不能晚于上报截止日期"));
    Button_Back();
    exit;
}

$CONTENT1=$CONTENT;
$SEND_TIME=date("Y-m-d H:i:s");
if($FLOW_ID=="")
    $query="insert into SAL_FLOW(BEGIN_DATE,END_DATE,CONTENT,SEND_TIME,SAL_YEAR,SAL_MONTH,SAL_CREATER) values ('$BEGIN_DATE','$END_DATE','$CONTENT','$SEND_TIME','$SAL_YEAR','$SAL_MONTH','".$_SESSION["LOGIN_USER_ID"]."')";
else
    $query="update SAL_FLOW set SAL_YEAR='$SAL_YEAR', SAL_MONTH='$SAL_MONTH', BEGIN_DATE='$BEGIN_DATE', END_DATE='$END_DATE', CONTENT='$CONTENT', SEND_TIME='$SEND_TIME' where FLOW_ID='$FLOW_ID'";
exequery(TD::conn(),$query);
$FLOW_ID = mysql_insert_id();
//-------内部事务提醒 --------
$USER_STR ="";
if($SMS_REMIND=="on")
{
    $SMS_CONTENT=sprintf(_("请进行工资上报！%s备注："), "\n").csubstr($CONTENT1,0,100);
    $REMIND_URL="1:hr/salary/submit/run/sal_index.php?FLOW_ID=".$FLOW_ID."&PAGE_START=0";
    if(compare_date($BEGIN_DATE,$CUR_DATE)!=0)
        $SEND_TIME=$BEGIN_DATE;

    $query="select USER_ID from USER,USER_PRIV where USER.USER_PRIV=USER_PRIV.USER_PRIV and (FUNC_ID_STR like '%,28,%' or FUNC_ID_STR like '28,%' or FUNC_ID_STR like '%,129,%'or FUNC_ID_STR like '129,%')";
    $cursor=exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $USER_STR.=$ROW["USER_ID"].",";
    }
    $USER_STR = td_trim($USER_STR);
    send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$USER_STR,4,$SMS_CONTENT,$REMIND_URL,$FLOW_ID);

}

if($SMS2_REMIND=="on")
{
    $SMS_CONTENT=sprintf(_("请进行工资上报！%s备注："), "\n").csubstr($CONTENT1,0,100);
    if(compare_date($BEGIN_DATE,$CUR_DATE)!=0)
        $SEND_TIME=$BEGIN_DATE;

    if($USER_STR=="")
    {
        $query="select USER_ID from USER,USER_PRIV where USER.USER_PRIV=USER_PRIV.USER_PRIV and (FUNC_ID_STR like '%,28,%' or FUNC_ID_STR like '28,%' or FUNC_ID_STR like '%,129,%'or FUNC_ID_STR like '129,%')";
        $cursor=exequery(TD::conn(),$query);
        while($ROW=mysql_fetch_array($cursor))
        {
            $USER_STR = $ROW["USER_ID"].",";
        }
        $USER_STR = td_trim($USER_STR);
    }
    send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$USER_STR,$SMS_CONTENT,4);
}

header("location: index.php?FLOW_ID=$FLOW_ID&PAGE_START=$PAGE_START&connstatus=1");
?>
</body>
</html>