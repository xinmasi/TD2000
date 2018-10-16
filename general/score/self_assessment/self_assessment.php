<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("被考核人自评");
include_once("inc/header.inc.php");
?>

<script>
function CheckForm(id)
{
    var id_name;
    for(i=1;i<=id;i++)
    {
        id_name="value"+i;
        var temp=document.getElementById(id_name).value;	
        if(temp=="")
        {
            alert("<?=_("录入的自评分数不能为空！")?>");
            document.getElementById(id_name).focus();
            return false;
        }
    }
    for(i=1;i<=id;i++)
    {
        id_name=i+"MEMO";
        var temp=document.getElementById(id_name).value;	
        if(temp=="")
        {
            alert("<?=_("录入的自评说明不能为空！")?>");
            document.getElementById(id_name).focus();
            return false;
        }
    }
    return true;
}
</script>

<body class="bodycolor">
<?
$query = "SELECT * from USER where USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $USER_NAME=$ROW["USER_NAME"];
}
?>
<table border="0"  cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/salary.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3">
            <?=_("自评信息录入")?>(<?=$USER_NAME?>)</span>
        </td>
    </tr>
</table>

<div align="center">
<form name=form1 method="post" action="submit.php">
<?
$FLOW_ID =intval($FLOW_ID);

//修改事务提醒状态--yc
update_sms_status('15',$FLOW_ID);

//-- 首先查询是否已录入过数据--
$query="select * from SCORE_SELF_DATA where FLOW_ID='$FLOW_ID' and PARTICIPANT='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $SCORE=$ROW["SCORE"];//--- 取出分数---
    $MEMO=$ROW["MEMO"];//--- 取出备注---
    $MY_SCORE=explode(",",$SCORE);
    $MY_MEMO=explode(",",$MEMO);
    $OPERATION=2; //-- 将执行数据更新 --
}
else
    $OPERATION=1; //-- 将执行数据插入 --

//-- 生成考核项目--
$query="select * from SCORE_ITEM where GROUP_ID='$GROUP_ID' ";
$cursor= exequery(TD::conn(),$query);
$ITEM_COUNT=0;

while($ROW=mysql_fetch_array($cursor))
{
    $ITEM_COUNT++;

    $ITEM_ID=$ROW["ITEM_ID"];
    $ITEM_NAME=$ROW["ITEM_NAME"];
    $ITEM_NAME=str_replace("\n","<br/>",$ITEM_NAME);
    $MAX=$ROW["MAX"];
    $MIN=$ROW["MIN"];
    $ITEM_EXPLAIN=$ROW["ITEM_EXPLAIN"];
    $ITEM_EXPLAIN=str_replace("\n","<br/>",$ITEM_EXPLAIN);
    if($ITEM_COUNT==1)
    {
?>
        <table width="90%" class="TableBlock">
<?
    }
?>
    <tr class="TableData">
        <td nowrap align="left" width="30%"><?=$ITEM_NAME?>(<?=$MIN?><?=_("～")?><?=$MAX?>)<br/><?=_('分值说明：').$ITEM_EXPLAIN ?></td>
        <td nowrap align="center" width="20%">
<?
    if($RECALL=='1')
    {
        $value="value".$ITEM_COUNT;
?>
            <input type="text" name="value<?=$ITEM_COUNT?>" id="value<?=$ITEM_COUNT?>" class="BigInputMoney" size="10" maxlength="14" value="<?=$$value?>">
<?
    }
    else
    {
?>
            <input type="text" name="value<?=$ITEM_COUNT?>" id="value<?=$ITEM_COUNT?>" class="BigInputMoney" size="10" maxlength="14" value="<?=$MY_SCORE[$ITEM_COUNT-1]?>">
<?
    }
?>
            <input type="hidden" value="<?=$ITEM_NAME?>" name="<?=$ITEM_COUNT?>NAME">
            <input type="hidden" value="<?=$MIN?>" name="<?=$ITEM_COUNT?>MIN">
            <input type="hidden" value="<?=$MAX?>" name="<?=$ITEM_COUNT?>MAX">
        </td>
        <td nowrap align="center" width="40%">
<?
    if($RECALL=='1')
    {
        $MEMO_DATA=$ITEM_COUNT."MEMO";
?>
            <textarea name="<?=$ITEM_COUNT?>MEMO" id="<?=$ITEM_COUNT?>MEMO" class="BigInputMoney" rows="2" cols="25"><?=$$MEMO_DATA ?></textarea>
<?
    }
    else
    {
?>
            <textarea name="<?=$ITEM_COUNT?>MEMO" id="<?=$ITEM_COUNT?>MEMO" class="BigInputMoney" rows="2" cols="25"><?=$MY_MEMO[$ITEM_COUNT-1]?></textarea>
<?
}
?>
        </td>
    </tr>
<?
}

if($ITEM_COUNT>0)
{
?>  
    <tfoot align="center" class="TableFooter">
        <td nowrap colspan="3">
            <input type="hidden" value="<?=$OPERATION?>" name="OPERATION">
            <input type="hidden" value="<?=$USER_ID?>" name="PARTICIPANT">
            <input type="hidden" value="<?=$FLOW_ID?>" name="FLOW_ID">
            <input type="hidden" value="<?=$GROUP_ID?>" name="GROUP_ID">
            <input type="hidden" value="<?=$ITEM_COUNT?>" name="ITEM_COUNT">
            <input type="submit" value="<?=_("确定")?>" class="BigButton" onClick="return CheckForm(<?=$ITEM_COUNT ?>);">&nbsp;&nbsp;&nbsp;
            <input type="button" value="<?=_("取消")?>" class="BigButton" onClick="location='index1.php'">
        </td>
    </tfoot>

    <thead class="TableHeader">
        <td nowrap align="center"><?=_("自评项目")?></td>
        <td nowrap align="center"><?=_("分数")?></td>
        <td nowrap align="center"><?=_("自评说明")?></td>
    </thead>
</table>
<?
}
else
    Message("",_("暂无需要自评的考核任务！"));
?>

</form>
</div>
</body>
</html>
