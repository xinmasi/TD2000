<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("������������");
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
            alert("<?=_("¼���������������Ϊ�գ�")?>");
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
            alert("<?=_("¼�������˵������Ϊ�գ�")?>");
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
            <?=_("������Ϣ¼��")?>(<?=$USER_NAME?>)</span>
        </td>
    </tr>
</table>

<div align="center">
<form name=form1 method="post" action="submit.php">
<?
$FLOW_ID =intval($FLOW_ID);

//�޸���������״̬--yc
update_sms_status('15',$FLOW_ID);

//-- ���Ȳ�ѯ�Ƿ���¼�������--
$query="select * from SCORE_SELF_DATA where FLOW_ID='$FLOW_ID' and PARTICIPANT='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $SCORE=$ROW["SCORE"];//--- ȡ������---
    $MEMO=$ROW["MEMO"];//--- ȡ����ע---
    $MY_SCORE=explode(",",$SCORE);
    $MY_MEMO=explode(",",$MEMO);
    $OPERATION=2; //-- ��ִ�����ݸ��� --
}
else
    $OPERATION=1; //-- ��ִ�����ݲ��� --

//-- ���ɿ�����Ŀ--
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
        <td nowrap align="left" width="30%"><?=$ITEM_NAME?>(<?=$MIN?><?=_("��")?><?=$MAX?>)<br/><?=_('��ֵ˵����').$ITEM_EXPLAIN ?></td>
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
            <input type="submit" value="<?=_("ȷ��")?>" class="BigButton" onClick="return CheckForm(<?=$ITEM_COUNT ?>);">&nbsp;&nbsp;&nbsp;
            <input type="button" value="<?=_("ȡ��")?>" class="BigButton" onClick="location='index1.php'">
        </td>
    </tfoot>

    <thead class="TableHeader">
        <td nowrap align="center"><?=_("������Ŀ")?></td>
        <td nowrap align="center"><?=_("����")?></td>
        <td nowrap align="center"><?=_("����˵��")?></td>
    </thead>
</table>
<?
}
else
    Message("",_("������Ҫ�����Ŀ�������"));
?>

</form>
</div>
</body>
</html>
