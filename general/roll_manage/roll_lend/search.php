<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("借阅记录");

//修改事务提醒状态--yc
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

<!------------------------------------- 待批准 ------------------------------->

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/roll_manage.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("待批准借阅")?></span><br>
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
            <a href="delete.php?LEND_ID=<?=$LEND_ID?>&type=1"> <?=_("撤销")?></a>
        </td>
    </tr>
    <?
    }

    if($LEND_COUNT>0)
    {
    ?>
    <thead class="TableHeader">
    <td nowrap align="center"><u><?=_("文件号")?></u><?=$ORDER_IMG;?></td>
    <td nowrap align="center"><?=_("申请时间")?></td>
    <td nowrap align="center"><?=_("审批时间")?></td>
    <td nowrap align="center"><?=_("归还时间")?></td>
    <td nowrap align="center"><?=_("操作")?></td>
    </thead>
</table>
<?
}
else
    message("",_("无待批借阅"));
?>
<br>
<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
    <tr>
        <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
    </tr>
</table>

<!------------------------------------- 已批准 ------------------------------->

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/roll_manage.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("已批准借阅")?></span><br>
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
            <a href="allow.php?LEND_ID=<?=$LEND_ID?>&ALLOW=3"> <?=_("归还")?></a>
        </td>
    </tr>
    <?
    }

    if($LEND_COUNT>0)
    {
    ?>
    <thead class="TableHeader">
    <td nowrap align="center"><u><?=_("文件号")?></u><?=$ORDER_IMG;?></td>
    <td nowrap align="center"><?=_("申请时间")?></td>
    <td nowrap align="center"><?=_("审批时间")?></td>
    <td nowrap align="center"><?=_("归还时间")?></td>
    <td nowrap align="center"><?=_("操作")?></td>
    </thead>
</table>
<?
}
else
    message("",_("无已准借阅"));
?>
<br>
<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
    <tr>
        <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
    </tr>
</table>

<!------------------------------------- 未批准 ------------------------------->

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/roll_manage.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("未批准借阅")?></span><br>
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
            <a href="delete.php?LEND_ID=<?=$LEND_ID?>&type=1"> <?=_("撤销")?></a>
        </td>
    </tr>
    <?
    }

    if($LEND_COUNT>0)
    {
    ?>
    <thead class="TableHeader">
    <td nowrap align="center"><u><?=_("文件号")?></u><?=$ORDER_IMG;?></td>
    <td nowrap align="center"><?=_("申请时间")?></td>
    <td nowrap align="center"><?=_("审批时间")?></td>
    <td nowrap align="center"><?=_("归还时间")?></td>
    <td nowrap align="center"><?=_("操作")?></td>
    </thead>
</table>
<?
}
else
    message("",_("无未准借阅"));
?>
<br>
<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
    <tr>
        <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
    </tr>
</table>

<!------------------------------------- 已归还 ------------------------------->

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/roll_manage.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("已归还借阅")?></span><br>
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
            <a href="delete.php?LEND_ID=<?=$LEND_ID?>&type=1"> <?=_("删除")?></a>
        </td>
    </tr>
    <?
    }

    if($LEND_COUNT>0)
    {
    ?>
    <thead class="TableHeader">
    <td nowrap align="center"><u><?=_("文件号")?></u><?=$ORDER_IMG;?></td>
    <td nowrap align="center"><?=_("申请时间")?></td>
    <td nowrap align="center"><?=_("审批时间")?></td>
    <td nowrap align="center"><?=_("归还时间")?></td>
    <td nowrap align="center"><?=_("操作")?></td>
    </thead>
</table>
<?
}
else
    message("",_("无归还借阅"));
?>
</body>

</html>
