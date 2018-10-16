<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("指定调度人员");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>

<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>


<?
$query = "select OPERATOR_NAME from VEHICLE_OPERATOR where OPERATOR_ID=1";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $MANAGERS=$ROW["OPERATOR_NAME"];

$MANAGERS2 = substr($MANAGERS,0,-1);

$MANAGERS_USER_NAME = "";
$MANAGERS_USER_ID   = "";

$query = "SELECT USER_NAME,USER_ID from USER where find_in_set(USER_ID,'".$MANAGERS2."') and DEPT_ID!=0 and (NOT_LOGIN = 0 or NOT_MOBILE_LOGIN = 0)";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{

    $MANAGERS_USER_NAME .= $ROW["USER_NAME"].",";
    $MANAGERS_USER_ID   .= $ROW["USER_ID"].",";
}

?>
<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("指定车辆调度人员")?></span>
        </td>
    </tr>
</table>
<br>

<form action="submit.php" method="post" name="form1">
    <table class="TableList" width="50%" align="center">
        <tr bgcolor="#CCCCCC">
            <td class="TableData" style="width:90px;"><?=_("车辆调度人员：")?></td>
            <td class="TableData">
                <input type="hidden" name="COPY_TO_ID" value="<?=$MANAGERS_USER_ID?>">
                <textarea cols=40 name="COPY_TO_NAME" rows=8 class="BigStatic" wrap="yes" style="width: 270px;" readonly><?=$MANAGERS_USER_NAME?></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectUser('90','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("添加")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("清空")?></a>
            </td>
        </tr>
        <tr bgcolor="#CCCCCC">
            <td align="center" valign="top" colspan="3">
                <input type="submit" class="BigButton" value="<?=_("保存")?>">&nbsp;&nbsp;
</form>
</td>
</tr>
</table>

</body>
</html>
