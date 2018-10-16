<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("接收短信查询");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
function CheckForm()
{
    if(document.form1.BEGIN_DATE.value!=""&&document.form1.END_DATE.value!=""&&document.form1.BEGIN_DATE.value>=document.form1.END_DATE.value)
    {
        alert("<?=_("起始时间不能大于或等于结束时间！")?>");
        return (false);
    }
    return true;
}
</script>

<body class="bodycolor">
<?
$CUR_DATE=date("Y-m-d H:i:s",time());

$query1= "select MOBIL_NO from USER where UID='".$_SESSION["LOGIN_UID"]."'";
$cursor= exequery(TD::conn(),$query1);
if($ROW=mysql_fetch_array($cursor))
    $MOBIL_NO=$ROW["MOBIL_NO"];

if($MOBIL_NO=="")
{
    Message(_("提示"),_("尚未设置本用户的手机号码"));
    exit;
}

$query1= "select * from USER where MOBIL_NO='$MOBIL_NO'";
$cursor= exequery(TD::conn(),$query1);
while($ROW=mysql_fetch_array($cursor))
{
    $USER_NAME.=$ROW["USER_NAME"].",";
}

?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/mobile_sms.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("接收短信查询")?></span>
            <span class=small1><?=_("查询")?>OA<?=_("用户通过")?>OA<?=_("系统向您发送的手机短信")?></span>
        </td>
    </tr>
</table>
<br>

<table class="TableBlock" width="500" align="center">
    <form action="search.php" name="form1" onsubmit="return CheckForm();">
        <tr>
            <td nowrap class="TableData"><?=_("发送人：")?></td>
            <td class="TableData">
                <input type="hidden" name="TO_ID" value="">
                <textarea cols=23 name="TO_NAME" rows="3" class="BigStatic" wrap="yes" readonly></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectUser('42','','TO_ID', 'TO_NAME')"><?=_("添加")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser('TO_ID', 'TO_NAME')"><?=_("清空")?></a>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("内容：")?></td>
            <td class="TableData"><textarea cols=40 name="CONTENT" rows="3" class="BigInput" wrap="yes"></textarea>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("起始时间：")?></td>
            <td class="TableData"><input type="text" name="BEGIN_DATE" size="20" maxlength="20" class="BigInput" value="" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">

            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("截止时间：")?></td>
            <td class="TableData"><input type="text" name="END_DATE" size="20" maxlength="20" class="BigInput" value="<?=$CUR_DATE?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">

            </td>
        </tr>
        <tr >
            <td nowrap class="TableControl" colspan="2" align="center">
                <input type="hidden" name="PHONE" value="<?=$MOBIL_NO?>">
                <input type="submit" value="<?=_("查询")?>" class="BigButton" title="<?=_("进行查询")?>" name="button">&nbsp;&nbsp;&nbsp;
            </td>
        </tr>
    </form>
</table>
<?

if(substr_count($USER_NAME,",")>1)
{
    $USER_NAME=td_trim($USER_NAME);
    Message(_("提示"),_("以下用户的手机号码同为 $MOBIL_NO:<br> $USER_NAME<br>"));
}
?>
</body>
</html>
