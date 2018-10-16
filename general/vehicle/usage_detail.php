<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("车辆使用详细信息");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vehicle.gif" width="24" height="24"><span class="big3"><?=_("车辆使用详细信息")?></span><br>
        </td>
    </tr>
</table>

<?
//修改事务提醒状态--yc
update_sms_status('9',$VU_ID);

$query = "SELECT * from VEHICLE_USAGE where VU_ID='$VU_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $VU_ID=$ROW["VU_ID"];
    $V_ID=$ROW["V_ID"];
    $VU_PROPOSER=$ROW["VU_PROPOSER"];
    $VU_REQUEST_DATE=$ROW["VU_REQUEST_DATE"];
    $VU_USER=$ROW["VU_USER"];
    $VU_SUITE=$ROW["VU_SUITE"];
    $VU_REASON=$ROW["VU_REASON"];
    $VU_START =$ROW["VU_START"];
    $VU_END=$ROW["VU_END"];
    $VU_MILEAGE=$ROW["VU_MILEAGE"];
    $VU_MILEAGE_TRUE=$ROW["VU_MILEAGE_TRUE"]!=""?$ROW["VU_MILEAGE_TRUE"]:$VU_MILEAGE;
    $VU_DEPT=$ROW["VU_DEPT"];
    $VU_STATUS=$ROW["VU_STATUS"];
    $VU_REMARK=$ROW["VU_REMARK"];
    $VU_REMARK=str_replace("\n","<br>",$VU_REMARK);
    $VU_DESTINATION=$ROW["VU_DESTINATION"];
    $VU_DRIVER=$ROW["VU_DRIVER"];
    $VU_OPERATOR=$ROW["VU_OPERATOR"];
    $VU_OPERATOR1=$ROW["VU_OPERATOR1"];  //备选调度员
    $DEPT_MANAGER=$ROW["DEPT_MANAGER"];
    $DMER_STATUS=$ROW["DMER_STATUS"];
    $DEPT_REASON=$ROW["DEPT_REASON"];   
    $OPERATOR_REASON=$ROW["OPERATOR_REASON"];  
    
    $query_name = "SELECT USER_NAME from USER where USER_ID = '$VU_USER'";
    $cursor_name= exequery(TD::conn(),$query_name);
    if($ROW_NAME=mysql_fetch_array($cursor_name)){
        $VU_USER = $ROW_NAME["USER_NAME"] != ""?$ROW_NAME["USER_NAME"]:$VU_USER;
    }
    if($VU_START=="0000-00-00 00:00:00")
        $VU_START="";
    if($VU_END=="0000-00-00 00:00:00")
        $VU_END="";

    if($VU_STATUS==0 && $DMER_STATUS!=3)
    {
        if($DEPT_MANAGER!="" && $DMER_STATUS==0)
            $VU_STATUS_DESC=_("部门审批人待批");
        else
            $VU_STATUS_DESC=_("调度员待批");      
    }
    elseif($VU_STATUS==1)
        $VU_STATUS_DESC=_("已准");
    elseif($VU_STATUS==2)
        $VU_STATUS_DESC=_("使用中");
    elseif($VU_STATUS==3 || $DMER_STATUS==3)
    {
        if($VU_STATUS==3)
            $VU_STATUS_DESC=_("调度员未准");
        else if($DMER_STATUS==3)
            $VU_STATUS_DESC=_("部门审批人未准");
    }
    elseif($VU_STATUS==4)
        $VU_STATUS_DESC=_("结束");

    $query = "SELECT USER_NAME from USER where USER_ID='$VU_PROPOSER'";
    $cursor2= exequery(TD::conn(),$query);
    if($ROW2=mysql_fetch_array($cursor2))
        $VU_PROPOSER_NAME=$ROW2["USER_NAME"];

    $query = "SELECT USER_NAME from USER where USER_ID='$DEPT_MANAGER'";
    $cursor2= exequery(TD::conn(),$query);
    if($ROW2=mysql_fetch_array($cursor2))
        $DEPT_MANAGER_NAME=$ROW2["USER_NAME"];

    $query = "SELECT USER_NAME from USER where USER_ID='$VU_OPERATOR'";
    $cursor2= exequery(TD::conn(),$query);
    if($ROW2=mysql_fetch_array($cursor2))
        $VU_OPERATOR_NAME=$ROW2["USER_NAME"];

    $query = "SELECT USER_NAME from USER where USER_ID='$VU_OPERATOR1'"; //获取备选调度员名称
    $cursor2= exequery(TD::conn(),$query);
    if($ROW2=mysql_fetch_array($cursor2))
        $VU_OPERATOR_NAME1=$ROW2["USER_NAME"];

    $query = "SELECT * from DEPARTMENT where DEPT_ID='$VU_DEPT'";
    $cursor2= exequery(TD::conn(),$query);
    if($ROW2=mysql_fetch_array($cursor2))
        $DEPT_NAME=$ROW2["DEPT_NAME"];

    $query = "SELECT * from VEHICLE where V_ID='$V_ID'";
    $cursor2= exequery(TD::conn(),$query);
    if($ROW2=mysql_fetch_array($cursor2))
        $V_NUM=$ROW2["V_NUM"];
?>
<table class="TableBlock" width="100%">
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("车牌号：")?></td>
        <td nowrap align="left" class="TableData"><?=$V_NUM?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("司机：")?></td>
        <td nowrap align="left" class="TableData"><?=td_trim($VU_DRIVER)?></td>
    </tr>
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("申请人：")?></td>
        <td nowrap align="left" class="TableData"><?=$VU_PROPOSER_NAME?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("申请时间：")?></td>
        <td nowrap align="left" class="TableData"><?=$VU_REQUEST_DATE?></td>
    </tr>
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("用车人：")?></td>
        <td nowrap align="left" class="TableData"><?=$VU_USER?></td>
    </tr>
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("随行人员：")?></td>
        <td nowrap align="left" class="TableData"><?=td_trim($VU_SUITE)?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("用车部门：")?></td>
        <td nowrap align="left" class="TableData"><?=$DEPT_NAME?></td>
    </tr>
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("事由：")?></td>
        <td align="left" class="TableData"><?=$VU_REASON?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("开始时间：")?></td>
        <td nowrap align="left" class="TableData"><?=$VU_START?></td>
    </tr>
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("结束时间：")?></td>
        <td nowrap align="left" class="TableData"><?=$VU_END?></td>
    </tr>
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("目的地：")?></td>
        <td nowrap align="left" class="TableData"><?=$VU_DESTINATION?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("申请里程：")?></td>
        <td nowrap align="left" class="TableData"><?=$VU_MILEAGE?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("实际里程：")?></td>
        <td nowrap align="left" class="TableData"><?=$VU_MILEAGE_TRUE?></td>
    </tr>  
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("调度员：")?></td>
        <td nowrap align="left" class="TableData"><?=$VU_OPERATOR_NAME?></td>
    </tr>
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("备选调度员：")?></td>
        <td nowrap align="left" class="TableData"><?=$VU_OPERATOR_NAME1?></td>
    </tr>
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("部门审批人：")?></td>
        <td nowrap align="left" class="TableData"><?=$DEPT_MANAGER_NAME?></td>
    </tr>
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("当前状态：")?></td>
        <td nowrap align="left" class="TableData"><?=$VU_STATUS_DESC?></td>
    </tr>
<?
if($DEPT_REASON!="")
{
?> 
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("部门未准理由：")?></td>
        <td nowrap align="left" class="TableData"><?=$DEPT_REASON?></td>
    </tr>
<?
}

if($OPERATOR_REASON!="")
{
?> 
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("调度员")?><br><?=_("未准理由：")?></td>
        <td nowrap align="left" class="TableData"><?=$OPERATOR_REASON?></td>
    </tr>
<?
}
?>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("备注：")?></td>
        <td align="left" class="TableData"><?=$VU_REMARK?></td>
    </tr>
<?
if(!isset($bpm) && !$run_id)
{
?>
    <tr align="center" class="TableControl">
        <td colspan="2">
            <input type="button" value="<?=_("打印")?>" class="BigButton" onclick="document.execCommand('Print');" title="<?=_("直接打印表格页面")?>">&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close();" title="<?=_("关闭窗口")?>">
        </td>
    </tr>
<?
}
?>
</table>
<?
}
else
    Message("",_("未找到相应记录！"));
?>

</body>

</html>
