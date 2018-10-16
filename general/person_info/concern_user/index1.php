<?
include_once("inc/auth.inc.php");
//2013-04-11 主服务器查询
if($IS_MAIN==1)
    $QUERY_MASTER=true;
else
    $QUERY_MASTER="";

$HTML_PAGE_TITLE = _("人员关注情况");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/person_info.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>

<body class="bodycolor">
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="title"><img src="<?=MYOA_STATIC_SERVER?>/static/images/person.gif" align="absmiddle"><span class="big3"> <?=_("人员关注信息")?></span>&nbsp;&nbsp;&nbsp;<input type="button" value="<?=_("人员关注组管理")?>" class="BigButton" onClick="location='group_index.php';" title="<?=_("人员关注组管理")?>"><br>
        </td>
    </tr>
</table>

</div>
<?
//============================ 个人资料 =======================================
$query = "SELECT CONCERN_USER from USER_EXT where UID='".$_SESSION["LOGIN_UID"]."'";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
if($ROW=mysql_fetch_array($cursor))
{
    $CONCERN_USER=$ROW["CONCERN_USER"];
}
$CONCERN_USER_NAME = get_concern_user_name($CONCERN_USER);
$USER_CONCERN_ME = td_trim(get_user_name_who_concern_me());
?>
<form action="update.php"  method="post" name="form1">
    <table class="TableBlock" width="450" align="center">
        <tr>
            <td nowrap class="TableHeader" colspan="2"><b>&nbsp;<?=_("基本信息")?></b></td>
        </tr>

        <tr>
            <td nowrap class="TableData"> <font color="darkorange"><b><?=_("我关注的：")?> </b></font></td>
            <td class="TableData">
                <input type="hidden" name="CONCERN_USER" value="<?=$CONCERN_USER?>">
                <textarea style="width:260px;" name="CONCERN_USER_NAME" rows="2" style="overflow-y:auto;" class="SmallStatic" wrap="yes" readonly><?=$CONCERN_USER_NAME?></textarea>
                <a href="#" class="orgAdd" onClick="SelectUser('11','2','CONCERN_USER', 'CONCERN_USER_NAME')" title="<?=_("添加我关注的人员")?>"><?=_("添加")?></a>
                <a href="#" class="orgClear" onClick="ClearUser('CONCERN_USER', 'CONCERN_USER_NAME')" title="<?=_("清空我关注的人员")?>"><?=_("清空")?></a><br>
            </td>
        </tr>

        <tr>
            <td nowrap class="TableData"> <font color="blue"><b><?=_("关注我的：")?></b></font></td>
            <td class="TableData">
                <?=$USER_CONCERN_ME ?>
            </td>
        </tr>

        <tr align="center" class="TableControl">
            <td colspan="2" nowrap>
                <input type="submit" value="<?=_("保存修改")?>" class="BigButton">
            </td>
        </tr>
    </table>
</form>
</body>
</html>
<?
function get_concern_user_name($CONCERN_USER) {
    $CONCERN_USER_NAME = "";
    if ($CONCERN_USER)
    {
        $CONCERN_USER_NEW = "'" . str_replace(",", "','", substr($CONCERN_USER, 0, -1)) . "'";
        $query = "select USER_NAME from USER where USER_ID in ($CONCERN_USER_NEW)";
        $cursor = exequery(TD::conn(), $query);
        while ($ROW = mysql_fetch_array($cursor))
        {
            $USER_NAME = $ROW["USER_NAME"];
            $CONCERN_USER_NAME .= $USER_NAME . ",";
        }
    }
    return $CONCERN_USER_NAME;
}
function get_user_name_who_concern_me() {
    $CONCERN_USER_NAME = "";
    //------------------------修改-------------------------
    $query = "select a.USER_NAME as USER_NAME from USER a, USER_EXT b where (b.CONCERN_USER like '%".$_SESSION["LOGIN_USER_ID"].",%' or b.CONCERN_USER like '%,".$_SESSION["LOGIN_USER_ID"]."%') and a.UID=b.UID";
    $cursor = exequery(TD::conn(), $query);
    while ($ROW = mysql_fetch_array($cursor))
    {
        $USER_NAME = $ROW["USER_NAME"];
        $CONCERN_USER_NAME .= $USER_NAME . ",";
    }
    return $CONCERN_USER_NAME;
}
?>
<br>
<?
Message("",_("所关注人员登录OA时，您会收到事务提醒消息"));
?>

