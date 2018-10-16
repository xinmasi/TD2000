<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("添加维护记录");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>

<body class="bodycolor">

<?
if($VM_ID!="")
{
    $query = "SELECT * from VEHICLE_MAINTENANCE  where VM_ID='$VM_ID'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $V_ID=$ROW["V_ID"];
        $VM_REQUEST_DATE=$ROW["VM_REQUEST_DATE"];
        $VM_TYPE=$ROW["VM_TYPE"];
        $VM_REASON=$ROW["VM_REASON"];
        $VM_FEE=$ROW["VM_FEE"];
        $VM_PERSON=td_trim($ROW["VM_PERSON"]);
        $VM_REMARK =$ROW["VM_REMARK"];

        if($VM_REQUEST_DATE=="0000-00-00")
            $VM_REQUEST_DATE="";
    }
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" HEIGHT="20"><span class="big3"> <?=_("车辆维护记录")?></span>
        </td>
    </tr>
</table>

<table class="TableBlock" align="center" width="550">
<form enctype="multipart/form-data" action="add.php" method="post" name="form1">
    <tr>
        <td nowrap class="TableData" width="80"> <?=_("车牌号：")?></td>
        <td class="TableData" colspan="3" width="470">
            <select name="V_ID" class="BigSelect">
<?
$query = "SELECT * from VEHICLE order by V_NUM";
$cursor1= exequery(TD::conn(),$query);
while($ROW1=mysql_fetch_array($cursor1))
{
    $V_ID1=$ROW1["V_ID"];
    $V_NUM=$ROW1["V_NUM"];
?>
                <option value="<?=$V_ID1?>" <? if($V_ID==$V_ID1) echo "selected";?>><?=$V_NUM?></option>
<?
}
?>
            </select>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="80"> <?=_("维护日期：")?></td>
        <td class="TableData" width="260">
            <input type="text" name="VM_REQUEST_DATE" size="12" maxlength="10" class="BigInput" value="<?=$VM_REQUEST_DATE?>" onClick="WdatePicker()">
        </td>
        <td nowrap class="TableData" width="80"> <?=_("维护类型：")?></td>
        <td class="TableData" width="130">
            <SELECT name="VM_TYPE"  class="BigSelect">
                <?=code_list("VEHICLE_REPAIR_TYPE",$VM_TYPE)?>
            </SELECT>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="80"> <?=_("维护原因：")?></td>
        <td class="TableData" colspan="3">
            <textarea name="VM_REASON" class="BigInput" cols="60" rows="2"><?=$VM_REASON?></textarea>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="80"> <?=_("经办人：")?></td>
        <td class="TableData">
            <input type="hidden" name="VM_PERSON_ID" value="<?=$VM_PERSON_ID?>">
            <input type="text" name="VM_PERSON" size="13" maxlength="100" class="BigInput" value="<?=$VM_PERSON?>" readonly>
            <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('90','','VM_PERSON_ID','VM_PERSON','vehicle')"><?=_("选择")?></a>
        </td>
        <td nowrap class="TableData" width="80"> <?=_("维护费用：")?></td>
        <td class="TableData" width="130">
            <input type="text" name="VM_FEE" size="12" maxlength="25" class="BigInput" value="<?=$VM_FEE?>">
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="80"> <?=_("备注：")?></td>
        <td class="TableData" colspan="3">
            <textarea name="VM_REMARK" class="BigInput" cols="60" rows="2"><?=$VM_REMARK?></textarea>
        </td>
    </tr>
    <tr class="TableControl">
        <td nowrap colspan="4" align="center">
            <input type="hidden" value="<?=$VM_ID?>" name="VM_ID">
            <input type="hidden" value="<?=$V_ID?>" name="FROM_V_ID">
            <input type="hidden" value="<?=$from?>" name="from">
            <input type="submit" value="<?=_("保存")?>" class="BigButton">&nbsp;&nbsp;
            <input type="reset" value="<?=_("重填")?>" class="BigButton">&nbsp;&nbsp;
<?
if($from == 'manage')
{
    echo '<input type="button" value="'._("返回").'" class="BigButton" onclick="location=\'../manage/maintenance.php?V_ID='.$V_ID.'\'">';
}
else if($VM_ID!="")
{
?>
            <input type="button" value="<?=_("返回")?>" class="BigButton" onclick="location='query.php'">
<?
}
?>
        </td>
    </tr>
</table>
</form>
</body>
</html>