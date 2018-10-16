<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
//2013-04-11 主服务器查询
if($IS_MAIN==1)
    $QUERY_MASTER=true;
else
    $QUERY_MASTER="";

$HTML_PAGE_TITLE = _("指定用户");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>

<body class="bodycolor">

<?
$query = "select * from USER_GROUP where GROUP_ID='$GROUP_ID'";
$cursor=exequery(TD::conn(),$query,$QUERY_MASTER);
if($ROW=mysql_fetch_array($cursor))
{
    $TO_ID=$ROW["USER_STR"];
}

$query1 = "SELECT USER_ID,USER_NAME from USER where find_in_set(USER_ID,'$TO_ID')";
$cursor1= exequery(TD::conn(),$query1);
while($ROW=mysql_fetch_array($cursor1))
    $TO_ARRAY[$ROW["USER_ID"]]["USER_NAME"]=$ROW["USER_NAME"];

$TOK=strtok($TO_ID,",");
while($TOK!="")
{
    if($TO_ARRAY[$TOK]["USER_NAME"]=="")
    {
        $TOK=strtok(",");
        continue;
    }
    $TO_NAME.=$TO_ARRAY[$TOK]["USER_NAME"].",";
    $TOK=strtok(",");
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("设置用户")?></span></td>
    </tr>
</table>

<br>

<table class="table table-bordered" style="width:600px;" align="center">
    <form enctype="multipart/form-data" action="user_submit.php"  method="post" name="form1">
        <tr>
            <td class="TableData" nowrap>
                <input type="hidden" name="TO_ID" value="<?=$TO_ID?>">
                <textarea name="TO_NAME" rows="10" style="overflow-y:auto;width:500px" class="SmallStatic" wrap="yes" readonly><?=$TO_NAME?></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectUser('11','2','TO_ID', 'TO_NAME', '', '', '')"><?=_("添加")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser('TO_ID', 'TO_NAME')"><?=_("清空")?></a>
            </td>
        </tr>
        <tr align="center" class="TableData">
            <td colspan="2" nowrap style="text-align:center">
                <input type="hidden" name="GROUP_ID" value="<?=$GROUP_ID?>">
                <input type="submit" value="<?=_("确定")?>" class="btn btn-primary">&nbsp;&nbsp;
                <input type="button" class="btn" value="<?=_("返回")?>" onclick="location='index.php'">
            </td>
        </tr>
    </form>
</table>

</body>
</html>
