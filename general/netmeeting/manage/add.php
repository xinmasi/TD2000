<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("网络会议增加参会人员");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script Language="JavaScript">
function CheckForm()
{
    if(document.form1.TO_ID.value=="")
    {
        alert("<?=_("请指定增加参会者！")?>");
        return (false);
    }

    return (true);
}
</script>

<?
$query = "SELECT SUBJECT,TO_ID from NETMEETING where MEET_ID='$MEET_ID'";
$cursor= exequery(TD::conn(),$query);
if ($ROW=mysql_fetch_array($cursor))
{
    $SUBJECT=$ROW["SUBJECT"];
    $TO_ID=$ROW["TO_ID"];

    if($TO_ID!="")
    {
        if(substr($TO_ID,strlen($TO_ID)-1,1)==",")
            $TO_ID=substr($TO_ID,0,-1);

        $query="SELECT USER_ID,USER_NAME from USER where find_in_set(USER_ID,'$TO_ID')";
        $cursor=exequery(TD::conn(),$query);
        while($ROW=mysql_fetch_array($cursor))
            $TO_NAME1.=$ROW["USER_NAME"].",";
        $TO_ID.=",";
        $TO_NAME=$TO_NAME1;
    }
}
?>
<body class="bodycolor">
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("增加参会人员")?>-(<?=_("会议主题：")?><?=$SUBJECT?>)</span>
        </td>
    </tr>
</table>

<br>

<table class="TableBlock" width="500" align="center">
    <form action="submit.php"  method="post" name="form1" onsubmit="return CheckForm();">
        <tr>
            <td nowrap class="TableData"><?=_("增加参会人员：")?></td>
            <td class="TableData">
                <input type="hidden" name="TO_ID" value="<?=$TO_ID?>">
                <textarea cols=40 name=TO_NAME rows=3 class="BigStatic" wrap="yes" readonly><?=$TO_NAME?></textarea>
                <a href="#" class="orgAdd" onClick="SelectUser('25','','TO_ID', 'TO_NAME')"><?=_("添加")?></a>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("参会提醒：")?></td>
            <td class="TableData">
                <?=sms_remind(3);?>
            </td>
        </tr>
        <tr align="center" class="TableControl">
            <td colspan="2" nowrap>
                <input type="submit" value="<?=_("保存")?>" class="BigButton">&nbsp;&nbsp;
                <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="javascript:history.go(-1);">
            </td>
        </tr>
</table>
<input type="hidden" name="MEET_ID" value="<?=$MEET_ID?>">
</form>
</body>
</html>