<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$connstatus = ($connstatus) ? true : false;

$PAGE_SIZE = 20;
if(!isset($start) || $start=="")
    $start=0;

$HTML_PAGE_TITLE = _("待审批计划");
include_once("inc/header.inc.php");

$query = "SELECT * from HR_RECRUIT_PLAN WHERE APPROVE_PERSON='".$_SESSION["LOGIN_USER_ID"]."' and PLAN_STATUS='0'";
$cursor=exequery(TD::conn(),$query, $connstatus);
$STAFF_COUNT = mysql_num_rows($cursor);

$query = "SELECT * from HR_RECRUIT_PLAN WHERE APPROVE_PERSON='".$_SESSION["LOGIN_USER_ID"]."' and PLAN_STATUS='0' ORDER BY PLAN_ID desc limit $start,$PAGE_SIZE";
$cursor=exequery(TD::conn(),$query, $connstatus);
$COUNT = mysql_num_rows($cursor);

if($PLAN_ID)
{
    //修改事务提醒状态--yc
    update_sms_status('62',$PLAN_ID);
}

?>
<body class="bodycolor">
<?
if($COUNT <= 0)
{
    Message("", _("无符合条件的招聘计划"));
    exit;
}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("待审批计划")?></span><br></td>
        <td align="right" valign="bottom" class="small1"><?=page_bar($start,$STAFF_COUNT,$PAGE_SIZE)?></td>
    </tr>
</table>
<table class="TableList" width="100%">
    <thead class="TableHeader">
    <td nowrap align="center"><?=_("编号")?></td>
    <td nowrap align="center"><?=_("招聘名称")?></td>
    <td nowrap align="center"><?=_("招聘人数")?></td>
    <td nowrap align="center"><?=_("开始日期")?></td>
    <td nowrap align="center"><?=_("计划状态")?></td>
    <td width="150" align="center"><?=_("操作")?></td>
    </thead>
    <?
    while($ROW=mysql_fetch_array($cursor))
    {
        $REQUIREMENTS_COUNT++;

        $PLAN_ID=$ROW["PLAN_ID"];
        $PLAN_NO=$ROW["PLAN_NO"];
        $PLAN_NAME=$ROW["PLAN_NAME"];
        $PLAN_DITCH=$ROW["PLAN_DITCH"];
        $PLAN_BCWS=$ROW["PLAN_BCWS"];
        $PLAN_RECR_NO=$ROW["PLAN_RECR_NO"];
        $REGISTER_TIME=$ROW["REGISTER_TIME"];
        $START_DATE=$ROW["START_DATE"];
        $END_DATE=$ROW["END_DATE"];
        $RECRUIT_DIRECTION=$ROW["RECRUIT_DIRECTION"];
        $RECRUIT_REMARK=$ROW["RECRUIT_REMARK"];
        $APPROVE_PERSON=$ROW["APPROVE_PERSON"];
        $APPROVE_DATE=$ROW["APPROVE_DATE"];
        $APPROVE_COMMENT=$ROW["APPROVE_COMMENT"];
        $APPROVE_RESULT=$ROW["APPROVE_RESULT"];
        $PLAN_STATUS=$ROW["PLAN_STATUS"];
        $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
        $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
        ?>
        <tr class="TableData">
            <td align="center"><?=$PLAN_NO?></td>
            <td align="center"><?=$PLAN_NAME?></td>
            <td align="center"><?=$PLAN_RECR_NO?></td>
            <td align="center"><?=$START_DATE?></td>
            <td align="center"><?if($PLAN_STATUS==0) echo _("待审批");?><?if($PLAN_STATUS==1) echo _("已批准");?><?if($PLAN_STATUS==2) echo _("未批准");?></td>
            <td align="center">
                <a href="javascript:;" onClick="window.open('plan_detail.php?PLAN_ID=<?=$PLAN_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
                <a href="modify1.php?PLAN_ID=<?=$PLAN_ID?>"><?=_("批准")?></a>&nbsp;
                <a href="modify2.php?PLAN_ID=<?=$PLAN_ID?>"><?=_("不批准")?></a>&nbsp;
            </td>
        </tr>
        <?
    }
    ?>

</table>
</body>
</html>