<?
include_once ("inc/auth.inc.php");
include_once("inc/header.inc.php");


if($DELETE_STR=="")
{
    $DELETE_STR = 0;
}elseif(substr($DELETE_STR,-1,1)==",")
{
    $DELETE_STR = substr($DELETE_STR,0,-1);
}

$query="delete from office_transhistory where PRO_ID in ($DELETE_STR)";
exequery(TD::conn(),$query);

$query="delete from office_products where PRO_ID in ($DELETE_STR)";
$cursor = exequery(TD::conn(),$query);


if($cursor)
{
    Message (_("提示"), _("删除成功"));
}else
{
    Message (_("错误"), _("请返回重试"));
}
?>
<br><center><input type="button" class="BigButtonA" value="<?=_("返回")?>" onclick="javascript:window.location='list.php'"></center>