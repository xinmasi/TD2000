<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
$connstatus = ($connstatus) ? true : false;

//�޸���������״̬--yc
update_sms_status('37',0);

$HTML_PAGE_TITLE = _("���ļ�¼");
include_once("inc/header.inc.php");
?>

<script>
function open_file(FILE_ID)
{
    URL="./read_file.php?FILE_ID="+FILE_ID;
    myleft=(screen.availWidth-500)/2;
    mytop=150
    mywidth=550;
    myheight=300;
    window.open(URL,"read_file","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}
</script>

<body class="bodycolor">

<!------------------------------------- ����׼ ------------------------------->

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/roll_manage.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("����׼����")?></span><br>
        </td>
    </tr>
</table>

<br>

<?
$query = "SELECT RMS_LEND.*,FILE_CODE from RMS_LEND left join RMS_FILE on RMS_FILE.FILE_ID=RMS_LEND.FILE_ID where ALLOW='0' and (RMS_LEND.APPROVE='' or RMS_LEND.APPROVE='".$_SESSION["LOGIN_USER_ID"]."')  and DELETE_FLAG ='0' order by FILE_CODE desc";
$cursor= exequery(TD::conn(),$query, $connstatus);
$LEND_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{

    $LEND_COUNT++;
    $LEND_ID=$ROW["LEND_ID"];
    $FILE_ID=$ROW["FILE_ID"];
    $FILE_CODE=$ROW["FILE_CODE"];
    $ADD_TIME=$ROW["ADD_TIME"];
    $RETURN_TIME=$ROW["RETURN_TIME"];
    $ALLOW_TIME=$ROW["ALLOW_TIME"];
    $USER_ID=$ROW["USER_ID"];

    $query1="select * from USER where USER_ID='$USER_ID'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
        $LEND_USER_NAME=$ROW["USER_NAME"];

    if($LEND_COUNT==1)
    {
    ?>
<table class="TableList" width="95%">
    <?
    }

    if($LEND_COUNT%2==1)
        $TableLine="TableLine1";
    else
        $TableLine="TableLine2";
    ?>
    <tr class="<?=$TableLine?>">
        <td align="center">
            <a href="javascript:open_file('<?=$FILE_ID?>');"><?=$FILE_CODE?></a>
        </td>
        <td nowrap align="center"><?=$LEND_USER_NAME?></td>
        <td nowrap align="center"><?=$ADD_TIME?></td>
        <td nowrap align="center"><?=$ALLOW_TIME?></td>
        <td nowrap align="center"><?=$RETURN_TIME?></td>
        <td nowrap align="center">
            <a href="allow.php?LEND_ID=<?=$LEND_ID?>&ALLOW=1&LEND_USER_ID=<?=$USER_ID?>"> <?=_("��׼")?></a>
            <a href="allow.php?LEND_ID=<?=$LEND_ID?>&ALLOW=2&LEND_USER_ID=<?=$USER_ID?>"> <?=_("��׼")?></a>
        </td>
    </tr>
    <?
    }

    if($LEND_COUNT>0)
    {
    ?>
    <thead class="TableHeader">
    <td nowrap align="center"><u><?=_("�ļ���")?></u><?=$ORDER_IMG;?></td>
    <td nowrap align="center"><?=_("������")?></td>
    <td nowrap align="center"><?=_("����ʱ��")?></td>
    <td nowrap align="center"><?=_("����ʱ��")?></td>
    <td nowrap align="center"><?=_("�黹ʱ��")?></td>
    <td nowrap align="center"><?=_("����")?></td>
    </thead>
</table>
<?
}
else
    message("",_("�޴�������"));
?>
<br>
<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
    <tr>
        <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
    </tr>
</table>

<!------------------------------------- ����׼ ------------------------------->

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/roll_manage.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("����׼����")?></span><br>
        </td>
    </tr>
</table>

<br>

<?
$query = "SELECT RMS_LEND.*,FILE_CODE from RMS_LEND left join RMS_FILE on RMS_FILE.FILE_ID=RMS_LEND.FILE_ID where ALLOW='1'  and (RMS_LEND.APPROVE='' or RMS_LEND.APPROVE='".$_SESSION["LOGIN_USER_ID"]."') and DELETE_FLAG ='0' order by FILE_CODE desc";
$cursor= exequery(TD::conn(),$query, $connstatus);
$LEND_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $LEND_COUNT++;
    $LEND_ID=$ROW["LEND_ID"];
    $FILE_ID=$ROW["FILE_ID"];
    $FILE_CODE=$ROW["FILE_CODE"];
    $ADD_TIME=$ROW["ADD_TIME"];
    $LEND_TIME=$ROW["LEND_TIME"];
    $RETURN_TIME=$ROW["RETURN_TIME"];
    $ALLOW_TIME=$ROW["ALLOW_TIME"];
    $USER_ID=$ROW["USER_ID"];

    $query1="select * from USER where USER_ID='$USER_ID'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
        $LEND_USER_NAME=$ROW["USER_NAME"];

    if($LEND_COUNT==1)
    {
    ?>
<table class="TableList" width="95%">
    <?
    }

    if($LEND_COUNT%2==1)
        $TableLine="TableLine1";
    else
        $TableLine="TableLine2";
    ?>
    <tr class="<?=$TableLine?>">
        <td align="center">
            <a href="javascript:open_file('<?=$FILE_ID?>');"><?=$FILE_CODE?></a>
        </td>
        <td nowrap align="center"><?=$LEND_USER_NAME?></td>
        <td nowrap align="center"><?=$ADD_TIME?></td>
        <td nowrap align="center"><?=$ALLOW_TIME?></td>
        <td nowrap align="center"><?=$RETURN_TIME?></td>
        <td nowrap align="center">
            <a href="allow.php?LEND_ID=<?=$LEND_ID?>&ALLOW=0"> <?=_("����")?></a>
            <a href="delete.php?LEND_ID=<?=$LEND_ID?>"> <?=_("ɾ��")?></a>
        </td>
    </tr>
    <?
    }

    if($LEND_COUNT>0)
    {
    ?>
    <thead class="TableHeader">
    <td nowrap align="center"><u><?=_("�ļ���")?></u><?=$ORDER_IMG;?></td>
    <td nowrap align="center"><?=_("������")?></td>
    <td nowrap align="center"><?=_("����ʱ��")?></td>
    <td nowrap align="center"><?=_("����ʱ��")?></td>
    <td nowrap align="center"><?=_("�黹ʱ��")?></td>
    <td nowrap align="center"><?=_("����")?></td>
    </thead>
</table>
<?
}
else
    message("",_("����׼����"));
?>
<br>
<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
    <tr>
        <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
    </tr>
</table>

<!------------------------------------- δ��׼ ------------------------------->

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/roll_manage.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("δ��׼����")?></span><br>
        </td>
    </tr>
</table>

<br>

<?
$query = "SELECT RMS_LEND.*,FILE_CODE from RMS_LEND left join RMS_FILE on RMS_FILE.FILE_ID=RMS_LEND.FILE_ID where ALLOW='2'  and (RMS_LEND.APPROVE='' or RMS_LEND.APPROVE='".$_SESSION["LOGIN_USER_ID"]."')  and DELETE_FLAG ='0' order by FILE_CODE desc";
$cursor= exequery(TD::conn(),$query, $connstatus);
$LEND_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $LEND_COUNT++;
    $LEND_ID=$ROW["LEND_ID"];
    $FILE_ID=$ROW["FILE_ID"];
    $FILE_CODE=$ROW["FILE_CODE"];
    $ADD_TIME=$ROW["ADD_TIME"];
    $LEND_TIME=$ROW["LEND_TIME"];
    $RETURN_TIME=$ROW["RETURN_TIME"];
    $ALLOW_TIME=$ROW["ALLOW_TIME"];
    $USER_ID=$ROW["USER_ID"];

    $query1="select * from USER where USER_ID='$USER_ID'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
        $LEND_USER_NAME=$ROW["USER_NAME"];

    if($LEND_COUNT==1)
    {
    ?>
<table class="TableList" width="95%">
    <?
    }

    if($LEND_COUNT%2==1)
        $TableLine="TableLine1";
    else
        $TableLine="TableLine2";
    ?>
    <tr class="<?=$TableLine?>">
        <td align="center">
            <a href="javascript:open_file('<?=$FILE_ID?>');"><?=$FILE_CODE?></a>
        </td>
        <td nowrap align="center"><?=$LEND_USER_NAME?></td>
        <td nowrap align="center"><?=$ADD_TIME?></td>
        <td nowrap align="center"><?=$ALLOW_TIME?></td>
        <td nowrap align="center"><?=$RETURN_TIME?></td>
        <td nowrap align="center">
            <a href="allow.php?LEND_ID=<?=$LEND_ID?>&ALLOW=0"> <?=_("����")?></a>
            <a href="delete.php?LEND_ID=<?=$LEND_ID?>"> <?=_("ɾ��")?></a>
        </td>
    </tr>
    <?
    }

    if($LEND_COUNT>0)
    {
    ?>
    <thead class="TableHeader">
    <td nowrap align="center"><u><?=_("�ļ���")?></u><?=$ORDER_IMG;?></td>
    <td nowrap align="center"><?=_("������")?></td>
    <td nowrap align="center"><?=_("����ʱ��")?></td>
    <td nowrap align="center"><?=_("����ʱ��")?></td>
    <td nowrap align="center"><?=_("�黹ʱ��")?></td>
    <td nowrap align="center"><?=_("����")?></td>
    </thead>
</table>
<?
}
else
    message("",_("��δ׼����"));
?>
<br>
<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
    <tr>
        <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
    </tr>
</table>

<!------------------------------------- �ѹ黹 ------------------------------->

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/roll_manage.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("�ѹ黹����")?></span><br>
        </td>
    </tr>
</table>

<br>

<?
$query = "SELECT RMS_LEND.*,FILE_CODE from RMS_LEND left join RMS_FILE on RMS_FILE.FILE_ID=RMS_LEND.FILE_ID where ALLOW='3'  and (RMS_LEND.APPROVE='' or RMS_LEND.APPROVE='".$_SESSION["LOGIN_USER_ID"]."') and DELETE_FLAG ='0' order by FILE_CODE desc";
$cursor= exequery(TD::conn(),$query, $connstatus);
$LEND_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $LEND_COUNT++;
    $LEND_ID=$ROW["LEND_ID"];
    $FILE_ID=$ROW["FILE_ID"];
    $FILE_CODE=$ROW["FILE_CODE"];
    $ADD_TIME=$ROW["ADD_TIME"];
    $LEND_TIME=$ROW["LEND_TIME"];
    $RETURN_TIME=$ROW["RETURN_TIME"];
    $ALLOW_TIME=$ROW["ALLOW_TIME"];
    $USER_ID=$ROW["USER_ID"];

    $query1="select * from USER where USER_ID='$USER_ID'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
        $LEND_USER_NAME=$ROW["USER_NAME"];

    if($LEND_COUNT==1)
    {
    ?>
<table class="TableList" width="95%">
    <?
    }

    if($LEND_COUNT%2==1)
        $TableLine="TableLine1";
    else
        $TableLine="TableLine2";
    ?>
    <tr class="<?=$TableLine?>">
        <td align="center">
            <a href="javascript:open_file('<?=$FILE_ID?>');"><?=$FILE_CODE?></a>
        </td>
        <td nowrap align="center"><?=$LEND_USER_NAME?></td>
        <td nowrap align="center"><?=$ADD_TIME?></td>
        <td nowrap align="center"><?=$ALLOW_TIME?></td>
        <td nowrap align="center"><?=$RETURN_TIME?></td>
        <td nowrap align="center">
            <a href="delete.php?LEND_ID=<?=$LEND_ID?>"> <?=_("ɾ��")?></a>
        </td>
    </tr>
    <?
    }

    if($LEND_COUNT>0)
    {
    ?>
    <thead class="TableHeader">
    <td nowrap align="center"><u><?=_("�ļ���")?></u><?=$ORDER_IMG;?></td>
    <td nowrap align="center"><?=_("������")?></td>
    <td nowrap align="center"><?=_("����ʱ��")?></td>
    <td nowrap align="center"><?=_("����ʱ��")?></td>
    <td nowrap align="center"><?=_("�黹ʱ��")?></td>
    <td nowrap align="center"><?=_("����")?></td>
    </thead>
</table>
<?
}
else
    message("",_("�޹黹����"));
?>
</body>

</html>
