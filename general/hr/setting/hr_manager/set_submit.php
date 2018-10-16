<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("设置");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
//建档时OA登录权限设置
//设置退休年龄
$PARA_VALUE=$MAN_AGE.",".$WOMEN_AGE;
$ANNUAL_BEGIN_TIME = '-'.$ANNUAL_BEGIN_TIME_MONTH.'-'.$ANNUAL_BEGIN_TIME_DAY.' 00:00:01';
if(1 == $ANNUAL_BEGIN_TIME_MONTH && 1 == $ANNUAL_BEGIN_TIME_DAY)
{
    $ANNUAL_END_TIME_MONTH=12;
    $ANNUAL_END_TIME_DAY=30;
}
else if(3 == $ANNUAL_BEGIN_TIME_MONTH && 1 == $ANNUAL_BEGIN_TIME_DAY)
{
    $ANNUAL_END_TIME_MONTH = 02;
    $ANNUAL_END_TIME_DAY = 28;
    $ANNUAL_END_TIME_MONTH = sprintf("%02d", $ANNUAL_END_TIME_MONTH);
}
else
{
    if(1 == $ANNUAL_BEGIN_TIME_DAY)
    {
        $ANNUAL_END_TIME_MONTH = $ANNUAL_BEGIN_TIME_MONTH-1;
        $ANNUAL_END_TIME_DAY = 30;
        $ANNUAL_END_TIME_MONTH = sprintf("%02d", $ANNUAL_END_TIME_MONTH);
    }
    else
    {
        $ANNUAL_END_TIME_MONTH = $ANNUAL_BEGIN_TIME_MONTH;
        $ANNUAL_END_TIME_DAY = $ANNUAL_BEGIN_TIME_DAY-1;
        $ANNUAL_END_TIME_DAY = sprintf("%02d", $ANNUAL_END_TIME_DAY);
    }
}
$ANNUAL_END_TIME = '-'.$ANNUAL_END_TIME_MONTH.'-'.$ANNUAL_END_TIME_DAY.' 23:59:59';
//echo $ANNUAL_BEGIN_TIME.'<br>';
//echo $ANNUAL_END_TIME;
//echo $PARA_VALUE;
$PARA_ARRAY=array("HR_SET_USER_LOGIN" => "$YES_OTHER","RETIRE_AGE" => "$PARA_VALUE","ANNUAL_BEGIN_TIME" => "$ANNUAL_BEGIN_TIME","ANNUAL_END_TIME" => "$ANNUAL_END_TIME","HR_SET_SPECIALIST" =>"$SPECIALIST","KEEP_WATCH"=>"$KEEP_WATCH");
set_sys_para($PARA_ARRAY);

Message("", _("保存成功"));
Button_Back();
?>
</body>
</html>
