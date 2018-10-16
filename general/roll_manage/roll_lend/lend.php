<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");

include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
$CUR_TIME=date("Y-m-d H:i:s",time());
$query="select MANAGER from RMS_ROLL where ROLL_ID='$ROLL_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $MANAGER=$ROW["MANAGER"];

$query="insert into RMS_LEND (FILE_ID,USER_ID,ADD_TIME,APPROVE) values ('$FILE_ID','".$_SESSION["LOGIN_USER_ID"]."','$CUR_TIME','$MANAGER')";
exequery(TD::conn(),$query);

$query="select PARA_VALUE from SYS_PARA where PARA_NAME='SMS_REMIND'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $PARA_VALUE=$ROW["PARA_VALUE"];
$SMS_REMIND=substr($PARA_VALUE,0,strpos($PARA_VALUE,"|"));
if(find_id($SMS_REMIND,"37") && $MANAGER!="")
{
    $REMIND_URL="roll_manage/roll_lend/confirm.php";
    $SMS_CONTENT=_("用户向您借阅档案，请审批");
    send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$MANAGER,37,$SMS_CONTENT,$REMIND_URL);
}

Message("",_("案卷借阅成功！"));
if($FROM_SEARCH!=1)
    Button_Back();
else
{
    ?>
    <center><input type="button" value="<?=_("返回")?>" class="BigButton" onclick="location='./search2.php?ROOM_NAME=<?=$ROOM_NAME?>&FILE_CODE=<?=$FILE_CODE?>&FILE_SUBJECT=<?=$FILE_SUBJECT?>&FILE_TITLE=<?=$FILE_TITLE?>&FILE_TITLE0=<?=$FILE_TITLE0?>&SEND_UNIT=<?=$SEND_UNIT?>&REMARK=<?=$REMARK?>&ROLL_NAME=<?=$ROLL_NAME?>'"></center>
    <?
}
?>
</body>
</html>
