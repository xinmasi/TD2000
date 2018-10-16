<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
$SORT_ID=intval($SORT_ID);

$HTML_PAGE_TITLE = _("查看文件");
include_once("inc/header.inc.php");

//修改事务提醒状态--yc
update_sms_status('42',$PROJ_ID);

?>

<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<body class="bodycolor">
<?
$query = "SELECT * from PROJ_FILE_SORT where SORT_ID='$SORT_ID'";
$cursor= exequery(TD::conn(),$query);
if(($ROW=mysql_fetch_array($cursor)))
{

    $SORT_NAME      = $ROW["SORT_NAME"];
    $VIEW_USER      = $ROW["VIEW_USER"];
    $MANAGE_USER    = $ROW["MANAGE_USER"];
    $NEW_USER       = $ROW["NEW_USER"];
    $MODIFY_USER    = $ROW["MODIFY_USER"];

    $VIEW_PRIV      = find_id($VIEW_USER,$_SESSION["LOGIN_USER_ID"])?"1":"0";
    $MANAGE_PRIV    = find_id($MANAGE_USER,$_SESSION["LOGIN_USER_ID"])?"1":"0";
    $NEW_PRIV       = find_id($NEW_USER,$_SESSION["LOGIN_USER_ID"])?"1":"0";
    $MODIFY_PRIV    = find_id($MODIFY_USER,$_SESSION["LOGIN_USER_ID"])?"1":"0";
}
$query = "SELECT PROJ_VIEWER,PROJ_OWNER,PROJ_MANAGER from PROJ_PROJECT where PROJ_ID='$PROJ_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $PROJ_VIEWER    = $ROW["PROJ_VIEWER"];
    $PROJ_OWNER     = $ROW["PROJ_OWNER"];
    $PROJ_MANAGER   = $ROW["PROJ_MANAGER"];
}

if($PROJ_OWNER==$_SESSION["LOGIN_USER_ID"] || $PROJ_MANAGER==$_SESSION["LOGIN_USER_ID"])
    $VIEW_PRIV=$MANAGE_PRIV=$NEW_PRIV=$MODIFY_PRIV=1;
if(find_id($PROJ_VIEWER,$_SESSION["LOGIN_USER_ID"]))
    $VIEW_PRIV=1;

if(!$VIEW_PRIV)
{
    Message(_("错误"),_("您没有权限查看此文件！"));
    exit;
}

//============================ 文件信息 =======================================
$query = "SELECT * from PROJ_FILE where FILE_ID='$FILE_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $UPLOAD_USER        = $ROW["UPLOAD_USER"];
    $SUBJECT            = $ROW["SUBJECT"];
    $FILE_DESC          = $ROW["FILE_DESC"];
    $UPDATE_TIME        = substr($ROW["UPDATE_TIME"],0,-3);
    $ATTACHMENT_ID      = $ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME    = $ROW["ATTACHMENT_NAME"];

    $SUBJECT=td_htmlspecialchars($SUBJECT);

    $query1 = "SELECT USER_NAME FROM USER WHERE USER_ID='$UPLOAD_USER'";
    $cursor1 = exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
        $UPLOAD_USER_NAME = $ROW["USER_NAME"];
}

//-------------------------文件操作记录---------------------------------------
$query = "SELECT USER_NAME,ACTION,ACTION_TIME from PROJ_FILE_LOG,USER where PROJ_FILE_LOG.USER_ID=USER.USER_ID AND FILE_ID='$FILE_ID'";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $USER_NAME=$ROW["USER_NAME"];
    $ACTION=$ROW["ACTION"];
    $ACTION_TIME=$ROW["ACTION_TIME"];

    if($ACTION==1) $ACTION_NAME=_("添加文件");
    elseif($ACTION==2) $ACTION_NAME=_("修改文件");
    elseif($ACTION==3) $ACTION_NAME=_("删除文件");

    $LOG_DESC.=sprintf("%s %s %s", $USER_NAME, $ACTION_TIME, $ACTION_NAME)."<br>";
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/folder_file.gif" width=22 align="absmiddle"><b><span class="Big1"><?=_("查看文件")?></span></b>
        </td>
    </tr>
</table>

<table class="TableBlock" width="100%" align="center">
    <form action="update.php"  method="post" name="form1">
        <tr>
            <td class="TableHeader" align="center" colspan="2"><b><span class="big"><?=$SUBJECT?></span></b></td>
        </tr>
        <tr class=small>
            <td class="TableData" colspan=2 width="400">
                <?=$FILE_DESC?>
            </td>
        </tr>
        <?
        if($ATTACHMENT_NAME!="")
        {
        ?>
        <tr class=small>
            <td class="TableData" width="150"><?=_("文件：")?></td>
            <td class="TableData" width="400">
                <?=attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,$DOWN_PRIV)?>
            </td>
        </tr>
        <?
        }
        ?>
        <tr class=small>
            <td class="TableData" width="150"><?=_("创建人：")?></td>
            <td class="TableData" width="400"><?=$UPLOAD_USER_NAME?></td>
        </tr>
        <?
        if($MANAGE_PRIV=="1")
        {
        ?>
        <tr class=small>
            <td class="TableData" width="150"><?=_("操作记录：")?></td>
            <td class="TableData" width="400"><?=$LOG_DESC?></td>
        </tr>
        <?
        }
        ?>
        <tr align="center" class="TableControl">
            <td colspan="2">
                <input type="button" value="<?=_("打印")?>" class="BigButton" onClick="document.execCommand('Print');" title="<?=_("打印文件内容")?>">&nbsp;&nbsp;
                <?
                if($MANAGE_PRIV || $MODIFY_PRIV_PRIV)
                {
                ?>
                <input type="button" value="<?=_("编辑")?>" class="BigButton" onClick="location='./new/?PROJ_ID=<?=$PROJ_ID?>&SORT_ID=<?=$SORT_ID?>&FILE_ID=<?=$FILE_ID?>'">&nbsp;&nbsp;
                <?
                }
                ?>
                <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='folder.php?PROJ_ID=<?=$PROJ_ID?>&SORT_ID=<?=$SORT_ID?>'">
            </td>
        </tr>
</table>
</form>
</body>
</html>