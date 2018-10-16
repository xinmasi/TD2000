<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("项目批注");
include_once("inc/header.inc.php");

include_once("../proj_priv.php");
include_once("inc/utility_all.php");

$query = "SELECT PROJ_NAME,PROJ_STATUS from proj_project WHERE PROJ_ID='$PROJ_ID'";
$cursor = exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
{
    $PROJ_NAME   = $ROW["PROJ_NAME"];
    $PROJ_STATUS = $ROW["PROJ_STATUS"];

}


?>


<script>
function delete_comment(COMMENT_ID,PROJ_ID)
{
    msg='<?=_("确认要删除该批注吗？")?>';
    if(window.confirm(msg))
    {
        URL="delete.php?COMMENT_ID=" + COMMENT_ID+"&PROJ_ID=" + PROJ_ID;
        window.location=URL;
    }
}
function checkForm()
{
    if(document.form1.CONTENT.value=="")
    {
        alert("<?=_("请输入批注内容！")?>");
        return (false);
    }
    return (true);
}
</script>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.min.css<?=$GZIP_POSTFIX?>" />

<body>

<?
//修改事务提醒状态--yc
update_sms_status('42',$PROJ_ID);

$query = "SELECT * from PROJ_COMMENT LEFT JOIN USER ON(PROJ_COMMENT.WRITER=USER.USER_ID) where PROJ_ID='$PROJ_ID' ORDER BY WRITE_TIME asc";
$cursor=exequery(TD::conn(),$query);
$DETAIL_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $COMMENT_COUNT++;
    $COMMENT_ID=$ROW["COMMENT_ID"];
    $WRITER=$ROW["WRITER"];
    $WRITE_TIME=$ROW["WRITE_TIME"];
    $CONTENT=$ROW["CONTENT"];
    $USER_NAME = $ROW["USER_NAME"];

    if($COMMENT_COUNT==1)
    {
?>
<table  class="table table-bordered table-striped" style="margin-bottom:70px;" width="98%" >
    <tr  class="info">
        <td colspan="4"><strong><?= _("项目批注")?></strong></td>
    </tr>
    <tr class="info">
        <td ><?=_("批注领导")?></td>
        <td ><?=_("批注内容")?></td>
        <td ><?=_("批注时间")?></td>
        <td ><?=_("操作")?></td>
    </tr>
    <?
    }

    ?>
    <tr>
        <td nowrap align="center"><?=$USER_NAME?></td>
        <td style="word-break:break-all;" align="left"><?=$CONTENT?></td>
        <td nowrap align="center" width="160"><?=$WRITE_TIME?></td>
        <td nowrap align="center">
            <?
            if($_SESSION["LOGIN_USER_ID"]==$WRITER or $_SESSION["LOGIN_USER_PRIV"]==1)
            {
                ?>
                <a href="edit.php?COMMENT_ID=<?=$COMMENT_ID?>&PROJ_ID=<?=$PROJ_ID?>"> <?=_("修改")?></a>
                <a href="javascript:delete_comment('<?=$COMMENT_ID?>','<?=$PROJ_ID?>');"> <?=_("删除")?></a>
                <?
            }
            ?>
        </td>
    </tr>
<?
} //while

    if($COMMENT_COUNT==0)
    {
        Message("",_("暂无领导批注"));
    }
    else
    {
    ?>
</table>
<?
}
?>
<?php
//项目查看者 项目审批者 项目管理者 有权限批注
$query = "SELECT PROJ_VIEWER,PROJ_MANAGER,PROJ_OWNER from PROJ_PROJECT where PROJ_ID='$PROJ_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $PROJ_VIEWER=$ROW["PROJ_VIEWER"];
    $PROJ_MANAGER=$ROW["PROJ_MANAGER"];
    $PROJ_OWNER = $ROW["PROJ_OWNER"];
}
$PRIV_USER=$PROJ_MANAGER.",".$PROJ_VIEWER;
if((find_id($PRIV_USER,$_SESSION["LOGIN_USER_ID"]) || $PROJ_OWNER==$_SESSION["LOGIN_USER_ID"]) && $PROJ_STATUS ==2)
{
    ?>
    <div align="center" style="width:100%; height:50px; background:#fff; border-top:#3f9bca 3px solid; line-height:50px; position:fixed; top:100%; margin-top:-50px;">
        <input type="button" onclick="location.href='new.php?PROJ_ID=<?= $PROJ_ID;?>'" class="btn btn-success" value="新建批注" />
    </div>
    <?
}
?>
</body>
</html>