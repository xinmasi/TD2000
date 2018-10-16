<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/header.inc.php");

$sql = "SELECT * FROM user_duty WHERE duty_type = '$DUTY_TYPE'";
$cursor= exequery(TD::conn(),$sql);
if(mysql_affected_rows()>0)
{
    Message(_("错误"),sprintf(_("该排班类型已被使用")));
    Button_Back();
    exit;
}

$query="delete from ATTEND_CONFIG where DUTY_TYPE='$DUTY_TYPE'";
exequery(TD::conn(),$query);

header("location: index.php?connstatus=1");
?>
