<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
$query1 = "SELECT  PROJ_NAME from PROJ_PROJECT where PROJ_ID='$PROJ_ID'";

$cursor1= exequery(TD::conn(),$query1);
$row1 =mysql_fetch_array($cursor1);
$PROJ_NAME=$row1[0];


$HTML_PAGE_TITLE =$PROJ_NAME."讨论区";
include_once("inc/header.inc.php");
include_once("../proj_priv.php");
if(!$PAGE_SIZE)
    $PAGE_SIZE = get_page_size("PROJECT", 10);
if(!isset($start) || $start=="")
    $start=0;

?>


<script Language="JavaScript">
function check_all()
{
    if(!document.getElementsByName("title_select"))
        return;
    for (i=0;i<document.getElementsByName("title_select").length;i++)
    {
        if(document.getElementsByName("allbox")[0].checked)
            document.getElementsByName("title_select")[i].checked=true;
        else
            document.getElementsByName("title_select")[i].checked=false;
    }

    if(i==0)
    {
        if(document.getElementsByName("allbox")[0].checked)
            document.getElementsByName("title_select")[0].checked=true;
        else
            document.getElementsByName("title_select")[0].checked=false;
    }
}

function check_one(el)
{
    if(!el.checked)
        document.getElementsByName("allbox")[0].checked=false;
}

function get_checked()
{
    checked_str="";
    for(i=0;i<document.getElementsByName("title_select").length;i++)
    {

        el=document.getElementsByName("title_select")[i];
        if(el.checked)
        {  val=el.value;
            checked_str+=val + ",";
        }
    }

    if(i==0)
    {
        el=document.getElementsByName("title_select")[0];
        if(el.checked)
        {  val=el.value;
            checked_str+=val + ",";
        }
    }
    return checked_str;

}

function delete_title()
{
    delete_str=get_checked();
    if(delete_str=="")
    {
        alert("<?=_("要删除主题，请至少选择其中一项。")?>");
        return;
    }

    msg='<?=_("确认要删除所选主题吗？")?>';
    if(window.confirm(msg))
    {
        URL="delete.php?DELETE_STR="+ delete_str +"&PROJ_ID=<?=$PROJ_ID?>&start=<?=$start?>";
        location=URL;
    }
}

</script>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.min.css<?=$GZIP_POSTFIX?>" />

<body>

<?
//修改事务提醒状态--yc
update_sms_status('42',0);

if(!isset($TOTAL_ITEMS))
{
    $TOTAL_ITEMS=0;
    $query = "SELECT count(*) from PROJ_FORUM where PROJ_ID='$PROJ_ID' and PARENT=0";

    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
        $TOTAL_ITEMS=$ROW[0];
}

$sql = "SELECT PROJ_STATUS FROM proj_project WHERE PROJ_ID = '$PROJ_ID'";
$cursor= exequery(TD::conn(),$sql);
if($ROW=mysql_fetch_array($cursor))
    $PROJ_STATUS = $ROW['PROJ_STATUS'];

?>

<style>
    #page_no{
        width:30px;
    }
</style>

<table  class="table table-bordered " style="margin-bottom:70px;" >
    <tr class="info">
        <td colspan="5">
            <strong><?=$PROJ_NAME?>  &raquo; <a href="index.php?PROJ_ID=<?=$PROJ_ID?>"><?=_("项目讨论区")?> </a> </strong>
            <?
            if($PROJ_STATUS == 2)
            {
            ?>
                <input style="float:right" type="button" class="btn btn-success" value="<?=_("发帖")?>" onclick="location='edit.php?PROJ_ID=<?=$PROJ_ID?>&start=<?=$start?>'" title="<?=_("发表新文章")?>">
            <?
            }
            ?>

        </td>
    </tr>
    <?php
    if($TOTAL_ITEMS > 0){
        ?>
        <tr class="info">
            <?
            $query = "SELECT PROJ_OWNER from PROJ_PROJECT where PROJ_ID='$PROJ_ID'";
            $cursor = exequery(TD::conn(),$query);
            if($ROW=mysql_fetch_array($cursor))
            {
                $PROJ_OWNER = $ROW["PROJ_OWNER"];
            }
            //oa管理员，项目管理者
            if($_SESSION["LOGIN_SYS_ADMIN"]==1 || find_id($PROJ_OWNER,$_SESSION["LOGIN_USER_ID"]))
            {
                ?>
                <td nowrap align="center"><?=_("选择")?></td>
                <?
            }
            ?>
            <td nowrap align="center"><?=_("主题")?></td>
            <td nowrap align="center"><?=_("作者")?></td>
            <td nowrap align="center"><?=_("回复数")?></td>
            <td align="center"><?=_("最后回复")?></td>
        </tr>
        <?
    }
    $query = "SELECT * from PROJ_FORUM where PROJ_ID='$PROJ_ID' and PARENT='0' order by SUBMIT_TIME desc limit $start,$PAGE_SIZE";
    $cursor = exequery(TD::conn(), $query);
    $MSG_COUNT = 0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $MSG_COUNT++;

        $MSG_ID  = $ROW["MSG_ID"];
        $USER_ID = $ROW["USER_ID"];
        $SUBJECT = $ROW["SUBJECT"];
        $SUBMIT_TIME = $ROW["SUBMIT_TIME"];
        $OLD_SUBMIT_TIME = $ROW["OLD_SUBMIT_TIME"];

        if($OLD_SUBMIT_TIME=="0000-00-00 00:00:00")
            $OLD_SUBMIT_TIME=$SUBMIT_TIME;

        $REPLY_CONT = $ROW["REPLY_CONT"];
        $CONTENT=$ROW["CONTENT"];

        $SUBJECT=str_replace("<","&lt",$SUBJECT);
        $SUBJECT=str_replace(">","&gt",$SUBJECT);
        $SUBJECT=stripslashes($SUBJECT);

        $query1 = "SELECT AVATAR,USER_NAME from USER where USER_ID='$USER_ID'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
        {
            $AVATAR=$ROW["AVATAR"];
            $AUTHOR_NAME=$ROW["USER_NAME"];
        }
        else
        {
            $USER_NAME=$USER_ID;
            $AVATAR="";
        }

        $query1 = "SELECT USER.USER_NAME from PROJ_FORUM LEFT JOIN USER ON(PROJ_FORUM.USER_ID=USER.USER_ID) where PARENT='$MSG_ID' order by SUBMIT_TIME desc limit 1";
        $cursor1= exequery(TD::conn(),$query1);
        $NEWER="";
        if($ROW=mysql_fetch_array($cursor1))
            $NEWER=$ROW["USER_NAME"];
        if($REPLY_CONT==0)
            $REPLY_STR=$OLD_SUBMIT_TIME." by ".$AUTHOR_NAME;
        else
            $REPLY_STR=$SUBMIT_TIME." by ".$NEWER;

        if($MSG_COUNT%2==0)
            $TableLine="TableLine1";
        else
            $TableLine="TableLine2";
        ?>
        <tr class="<?=$TableLine?>" onmouseover="this.style.backgroundColor='#F5FBFF'" onmouseout="this.style.backgroundColor='#FFFFFF'">
            <?
            if($_SESSION["LOGIN_USER_PRIV"]==1 || find_id($PROJ_OWNER,$_SESSION["LOGIN_USER_ID"]))
            {
                ?>
                <td width="40px" align="center" nowrap>
                    <input type="checkbox" name="title_select" value="<?=$MSG_ID?>" onClick="check_one(self);">
                </td>
                <?
            }
            ?>
            <td width="50%">
                <a href="comment.php?PROJ_ID=<?=$PROJ_ID?>&MSG_ID=<?=$MSG_ID?>&start=<?=$start?>&PAGE_START=0"><?=$SUBJECT?></a>
            </td>
            <td width="90" align="center" nowrap><?=$AUTHOR_NAME?></td>
            <td align="center"><?=$REPLY_CONT?></td>
            <td nowrap><?=$REPLY_STR?></td>
        </tr>
        <?
    }//while

    if($MSG_COUNT>0)
    {
        ?>
        <tr class="TableControl">
            <td colspan=5>
                <?
                if($_SESSION["LOGIN_USER_PRIV"]==1 || find_id($PROJ_OWNER,$_SESSION["LOGIN_USER_ID"]))
                {
                    ?>
                    <label for="allbox_for"><input type=checkbox  id="allbox_for" onClick="check_all();" name="allbox"><?=_(" 全选")?></label>
                    <input type="button" class="btn" value="<?=_("删除主题")?>" onclick="delete_title()">
                    <?
                }
                ?>
            </td>
        </tr>
        <?
    }
    ?>
</table>

<?php
if($TOTAL_ITEMS==0)
{
    Message("",_("此项目尚无讨论内容！"));
}
?>

<div align="center" style="width:100%; height:50px; background:#fff; border-top:#3f9bca 3px solid; line-height:50px; position:fixed; top:100%; margin-top:-50px;">
    <?=page_bar($start,$TOTAL_ITEMS,$PAGE_SIZE)?>
</div>

</body>
</html>