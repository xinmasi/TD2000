<?
include_once("inc/auth.inc.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("�����Ű�����");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script Language="JavaScript">
function delete_priv(DUTY_TYPE,COUNT)
{
    if(COUNT>0)
    {
        alert("<?=_("���Ű���")?>OA<?=_("�û�ʹ�ã�����ɾ��")?>");
        return false;
    }
    msg="<?=_("ȷ��Ҫɾ�����Ű���")?>";
    if(window.confirm(msg))
    {
        URL="delete.php?DUTY_TYPE="+DUTY_TYPE;
        window.location=URL;
    }
}
</script>

<body class="attendance">
<h5 class="attendance-title"><?=_("�½��Ű�����")?></h5>
<div align="center">
    <input type="button"  value="<?=_("�½��Ű�����")?>" class="btn btn-primary" onClick="location='config_edit.php'"  title="<?=_("�½��Ű�����")?>">
</div>

<br>
<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
    <tr>
        <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
    </tr>
</table>

<h5 class="attendance-title"><?=_("�����Ű�����")?></h5>
<?
//============================ ��ʾ�Ѷ��忼��ʱ�� =======================================
$query = "SELECT * from ATTEND_CONFIG order by DUTY_TYPE";
$cursor= exequery(TD::conn(),$query, $connstatus);

$DUTY_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $DUTY_COUNT++;
    $DUTY_TYPE=$ROW["DUTY_TYPE"];
    $DUTY_NAME=$ROW["DUTY_NAME"];
    $DUTY_TIME1=$ROW["DUTY_TIME1"];
    $DUTY_TIME2=$ROW["DUTY_TIME2"];
    $DUTY_TIME3=$ROW["DUTY_TIME3"];
    $DUTY_TIME4=$ROW["DUTY_TIME4"];
    $DUTY_TIME5=$ROW["DUTY_TIME5"];
    $DUTY_TIME6=$ROW["DUTY_TIME6"];

    $DUTY_BEFORE1 = $ROW['DUTY_BEFORE1'];
    $DUTY_AFTER1  = $ROW['DUTY_AFTER1'];
    $DUTY_BEFORE2 = $ROW['DUTY_BEFORE2'];
    $DUTY_AFTER2  = $ROW['DUTY_AFTER2'];
    $DUTY_BEFORE3 = $ROW['DUTY_BEFORE3'];
    $DUTY_AFTER3  = $ROW['DUTY_AFTER3'];
    $DUTY_BEFORE4 = $ROW['DUTY_BEFORE4'];
    $DUTY_AFTER4  = $ROW['DUTY_AFTER4'];
    $DUTY_BEFORE5 = $ROW['DUTY_BEFORE5'];
    $DUTY_AFTER5  = $ROW['DUTY_AFTER5'];
    $DUTY_BEFORE6 = $ROW['DUTY_BEFORE6'];
    $DUTY_AFTER6  = $ROW['DUTY_AFTER6'];
    $TIME_LATE1   = $ROW['TIME_LATE1'];
    $TIME_EARLY2  = $ROW['TIME_EARLY2'];
    $TIME_LATE3   = $ROW['TIME_LATE3'];
    $TIME_EARLY4  = $ROW['TIME_EARLY4'];
    $TIME_EARLY4  = $ROW['TIME_LATE5'];
    $TIME_EARLY6  = $ROW['TIME_EARLY6'];

    $DUTY_NAME=str_replace("<","&lt",$DUTY_NAME);
    $DUTY_NAME=str_replace(">","&gt",$DUTY_NAME);
    $DUTY_NAME=stripslashes($DUTY_NAME);

    if($DUTY_COUNT==1)
    {
?>

<table class="table  table-bordered" width="100%" align="center">
    <thead class="">
    <tr>
        <th nowrap align="center"><?=_("���")?></th>
        <th nowrap align="center"><?=_("���˵��")?></th>
        <th nowrap align="center"><?=_("��1�εǼ�")?></th>
        <th nowrap align="center"><?=_("��2�εǼ�")?></th>
        <th nowrap align="center"><?=_("��3�εǼ�")?></th>
        <th nowrap align="center"><?=_("��4�εǼ�")?></th>
        <th nowrap align="center"><?=_("��5�εǼ�")?></th>
        <th nowrap align="center"><?=_("��6�εǼ�")?></th>
        <th nowrap align="center"><?=_("����")?></th>
    </tr>
    </thead>
    <?
    }
    ?>
    <tr class="">
        <td nowrap align="center"><?=$DUTY_COUNT?></td>
        <td nowrap align="center"><?=$DUTY_NAME?></td>
        <td nowrap align="center"><?=$DUTY_TIME1?></td>
        <td nowrap align="center"><?=$DUTY_TIME2?></td>
        <td nowrap align="center"><?=$DUTY_TIME3?></td>
        <td nowrap align="center"><?=$DUTY_TIME4?></td>
        <td nowrap align="center"><?=$DUTY_TIME5?></td>
        <td nowrap align="center"><?=$DUTY_TIME6?></td>
        <td nowrap nowrap>
            <a href="general.php?DUTY_TYPE=<?=$DUTY_TYPE?>"> <?=_("������")?></a>
            <a href="config_edit.php?DUTY_TYPE=<?=$DUTY_TYPE?>"> <?=_("�༭")?></a>
            <?
            if($DUTY_COUNT!=1)
            {
                $query1="select count(*) from USER_EXT where DUTY_TYPE='$DUTY_TYPE'";
                $cursor1=exequery(TD::conn(),$query1);
                if($ROW1=mysql_fetch_array($cursor1))
                    $COUNT=$ROW1[0];
                ?>
                <a href="javascript:delete_priv('<?=$DUTY_TYPE?>','<?=$COUNT?>');"> <?=_("ɾ��")?></a>
                <?
            }
            ?>
        </td>
    </tr>
    <?
    }

    if($DUTY_COUNT>0)
    {
    ?>

</table>
<?
}
else
    Message("",_("��δ����"));
?>

<br>
<div align="center">
    <input type="button"  value="<?=_("����")?>" class="btn" onClick="location='../#dutyOrno';">
</div>

</body>
</html>