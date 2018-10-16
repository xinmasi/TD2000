<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
//2013-04-11 主从服务器查询判断
if($IS_MAIN==1)
    $QUERY_MASTER=true;
else
    $QUERY_MASTER="";


$HTML_PAGE_TITLE = _("工作日志共享");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/diary.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>

<body class="bodycolor">
<div class="PageHeader">
    <div class="title"><?=_("设置共享范围")?></div>
</div>
<table class="TableTop" width="80%">
    <tr>
        <td class="left"></td>
        <td class="center subject">
            <?=$SUBJECT?>(<?=$USER_NAME?>)
        </td>
        <td class="right"></td>
    </tr>
</table>
<?
$query = "SELECT TO_ID from DIARY where DIA_ID='$DIA_ID'";
$cursor = exequery(TD::conn(),$query,$QUERY_MASTER);
if($ROW=mysql_fetch_array($cursor))
    $TO_ID = $ROW["TO_ID"];

?>
<form enctype="multipart/form-data" action="share_submit.php"  method="post" name="form1">
    <table class="TableBlock no-top-border" width="80%">
        <tr>
            <td class="TableData"><?=_("共享范围：")?></td>
            <td class="TableData">
                <input type="hidden" name="TO_ID" value="<?=$TO_ID?>">
                <textarea cols="50" name="TO_NAME" rows="4" style="overflow-y:auto;" class="SmallStatic" wrap="yes" readonly><?=GetUserNameById($TO_ID)?></textarea>
                <?
                if($FROM_FLAG!=4){
                    ?>
                    <a href="#" class="orgAdd" onClick="SelectUser('9', '','TO_ID', 'TO_NAME')"><?=_("添加")?></a>
                    <a href="#" class="orgClear" onClick="ClearUser('TO_ID', 'TO_NAME')"><?=_("清空")?></a>
                <?}?>
            </td>
        </tr>
        <tr align="center">
            <td colspan="2" nowrap>
                <input type="hidden" name="DIA_ID" value="<?=$DIA_ID?>">
                <input type="hidden" name="USER_ID" value="<?=$USER_ID?>">
                <input type="hidden" name="FROM_FLAG" value="<?=$FROM_FLAG?>">
                <input type="hidden" name="SUBJECT" value="<?=$SUBJECT?>">
                <input type="hidden" name="USER_NAME" value="<?=$USER_NAME?>">
                <?
                if($FROM_FLAG!=4){
                    ?>
                    <input class="BigButton" type="submit" value="<?=_("确定")?>"/>&nbsp;&nbsp;
                    <?
                }
                if($FROM_FLAG==1)
                    $BACK_RUL = "user_diary.php?start=$start&PER_PAGE=$PER_PAGE&USER_ID=$USER_ID";
                else if($FROM_FLAG==2)
                    $BACK_RUL = "user_query.php";
//   $BACK_RUL = "user_search.php?BEGIN_DATE=$BEGIN_DATE&END_DATE=$END_DATE&DIA_TYPE=$DIA_TYPE&SUBJECT=$SUBJECT&TO_ID1=$TO_ID1&TO_ID=$TO_ID&PRIV_ID=$PRIV_ID&COPYS_TO_ID=$COPYS_TO_ID";
                else if($FROM_FLAG==4)
                    $BACK_RUL = "../share_read.php?DIA_ID=$DIA_ID&FROM_FLAG=1";
                else
                    $BACK_RUL = "diary_body.php?start=$start&PER_PAGE=$PER_PAGE";
                ?>

                <input type="button" value="<?=_("返回")?>" class="BigButton" onclick="location='<?=$BACK_RUL?>'">
            </td>
        </tr>
    </table>
</form>
</body>
</html>