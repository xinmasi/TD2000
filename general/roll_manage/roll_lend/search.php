<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("���ļ�¼");

//�޸���������״̬--yc
update_sms_status('37',0);

include_once("inc/header.inc.php");
?>


<script>
function open_file(FILE_ID,ALLOW)
{
    if (ALLOW==0)
        URL="./read_file0.php?FILE_ID="+FILE_ID;
    else
        URL="./read_file.php?FILE_ID="+FILE_ID;
    myleft=(screen.availWidth-600)/2;
    mytop=150
    mywidth=600;
    myheight=400;
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
$query = "SELECT RMS_LEND.*,FILE_CODE from RMS_LEND left join RMS_FILE on RMS_FILE.FILE_ID=RMS_LEND.FILE_ID where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and ALLOW=0  and DELETE_FLAG ='0' order by FILE_CODE desc";
$cursor= exequery(TD::conn(),$query, $connstatus);
$LEND_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $LEND_COUNT++;
    $LEND_ID     = $ROW["LEND_ID"];
    $FILE_ID     = $ROW["FILE_ID"];
    $FILE_CODE   = $ROW["FILE_CODE"];
    $ADD_TIME    = $ROW["ADD_TIME"];
    $LEND_TIME   = $ROW["LEND_TIME"];
    $RETURN_TIME = $ROW["RETURN_TIME"];
    $ALLOW_TIME  = $ROW["ALLOW_TIME"];

    if($LEND_COUNT==1)
    {
    ?>
<table class="TableList"  width="95%">
    <?
    }

    if($LEND_COUNT%2==1)
        $TableLine="TableLine1";
    else
        $TableLine="TableLine2";
    ?>
    <tr class="<?=$TableLine?>">
        <td align="center">
            <a href="javascript:open_file('<?=$FILE_ID?>',0);"><?=$FILE_CODE?></a>
        </td>
        <td nowrap align="center"><?=$ADD_TIME?></td>
        <td nowrap align="center"><?=$ALLOW_TIME?></td>
        <td nowrap align="center"><?=$RETURN_TIME?></td>
        <td nowrap align="center">
            <a href="delete.php?LEND_ID=<?=$LEND_ID?>&type=1"> <?=_("����")?></a>
        </td>
    </tr>
    <?
    }

    if($LEND_COUNT>0)
    {
    ?>
    <thead class="TableHeader">
    <td nowrap align="center"><u><?=_("�ļ���")?></u><?=$ORDER_IMG;?></td>
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
$query = "SELECT RMS_LEND.*,FILE_CODE from RMS_LEND left join RMS_FILE on RMS_FILE.FILE_ID=RMS_LEND.FILE_ID where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and ALLOW=1  and DELETE_FLAG ='0' order by FILE_CODE desc";
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
            <a href="javascript:open_file('<?=$FILE_ID?>',1);"><?=$FILE_CODE?></a>
        </td>
        <td nowrap align="center"><?=$ADD_TIME?></td>
        <td nowrap align="center"><?=$ALLOW_TIME?></td>
        <td nowrap align="center"><?=$RETURN_TIME?></td>
        <td nowrap align="center">
            <a href="allow.php?LEND_ID=<?=$LEND_ID?>&ALLOW=3"> <?=_("�黹")?></a>
        </td>
    </tr>
    <?
    }

    if($LEND_COUNT>0)
    {
    ?>
    <thead class="TableHeader">
    <td nowrap align="center"><u><?=_("�ļ���")?></u><?=$ORDER_IMG;?></td>
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
$query = "SELECT RMS_LEND.*,FILE_CODE from RMS_LEND left join RMS_FILE on RMS_FILE.FILE_ID=RMS_LEND.FILE_ID where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and ALLOW=2  and DELETE_FLAG ='0' order by FILE_CODE desc";
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
            <a href="javascript:open_file('<?=$FILE_ID?>',0);"><?=$FILE_CODE?></a>
        </td>
        <td nowrap align="center"><?=$ADD_TIME?></td>
        <td nowrap align="center"><?=$ALLOW_TIME?></td>
        <td nowrap align="center"><?=$RETURN_TIME?></td>
        <td nowrap align="center">
            <a href="delete.php?LEND_ID=<?=$LEND_ID?>&type=1"> <?=_("����")?></a>
        </td>
    </tr>
    <?
    }

    if($LEND_COUNT>0)
    {
    ?>
    <thead class="TableHeader">
    <td nowrap align="center"><u><?=_("�ļ���")?></u><?=$ORDER_IMG;?></td>
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
$query = "SELECT RMS_LEND.*,FILE_CODE from RMS_LEND left join RMS_FILE on RMS_FILE.FILE_ID=RMS_LEND.FILE_ID where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and ALLOW=3  and DELETE_FLAG ='0' order by FILE_CODE desc";
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
            <a href="javascript:open_file('<?=$FILE_ID?>',0);"><?=$FILE_CODE?></a>
        </td>
        <td nowrap align="center"><?=$ADD_TIME?></td>
        <td nowrap align="center"><?=$ALLOW_TIME?></td>
        <td nowrap align="center"><?=$RETURN_TIME?></td>
        <td nowrap align="center">
            <a href="delete.php?LEND_ID=<?=$LEND_ID?>&type=1"> <?=_("ɾ��")?></a>
        </td>
    </tr>
    <?
    }

    if($LEND_COUNT>0)
    {
    ?>
    <thead class="TableHeader">
    <td nowrap align="center"><u><?=_("�ļ���")?></u><?=$ORDER_IMG;?></td>
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
