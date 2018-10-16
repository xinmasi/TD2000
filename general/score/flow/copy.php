<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("保存考核任务");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
$SEND_TIME=date("Y-m-d H:i:s",time());
$CUR_DATE=date("Y-m-d",time());

$FLOW_ID=intval($FLOW_ID);
$query1="select * from SCORE_FLOW where FLOW_ID='$FLOW_ID'";
$cursor1=exequery(TD::conn(),$query1);
if($ROW=mysql_fetch_array($cursor1))
{
    $GROUP_ID=$ROW["GROUP_ID"];
    $FLOW_TITLE=$ROW["FLOW_TITLE"];
    $FLOW_DESC=$ROW["FLOW_DESC"];
    $FLOW_FLAG=$ROW["FLOW_FLAG"];
    $ANONYMITY=$ROW["ANONYMITY"];
}
$BEGIN_DATE=$CUR_DATE;
$END_DATE="";
$RANKMAN="";
$PARTICIPANT="";


$query="insert into SCORE_FLOW (GROUP_ID,FLOW_TITLE,FLOW_DESC,FLOW_FLAG,BEGIN_DATE,END_DATE,SEND_TIME,RANKMAN,PARTICIPANT,ANONYMITY,CREATE_USER_ID) values ('$GROUP_ID','$FLOW_TITLE','$FLOW_DESC','$FLOW_FLAG','$BEGIN_DATE','$END_DATE','$SEND_TIME','$SECRET_TO_ID','$TO_ID','$ANONYMITY','{$_SESSION["LOGIN_USER_ID"]}')";
exequery(TD::conn(),$query);

Message("",_("克隆成功！"));
parse_str($_SERVER["HTTP_REFERER"], $tmp_url);
$paras = strpos($_SERVER["HTTP_REFERER"], "?") ? isset($tmp_url["connstatus"]) ? $_SERVER["HTTP_REFERER"] : $_SERVER["HTTP_REFERER"]."&connstatus=1" : $paras = $_SERVER["HTTP_REFERER"]."?connstatus=1";
?>
<center>
    <input type="button" class="BigButton" value="<?=_("返回")?>" onclick="window.location.href='<?=$paras?>'"/>
</center>

</body>
</html>
