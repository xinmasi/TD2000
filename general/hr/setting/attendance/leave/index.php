<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/td_core.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("免签节假日设置");
include_once("inc/header.inc.php");
$PARA_ARRAY=get_sys_para("LEAVE_BY_SENIORITY,ENTRY_RESET_LEAVE", false);
while(list($PARA_NAME, $PARA_VALUE) = each($PARA_ARRAY))
    $$PARA_NAME = $PARA_VALUE;



?>
<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<style>
.warnspan{
    font-size:13px;
    color:#9a9898;
}
</style>
<script Language="JavaScript">
function CheckForm()
{
    var reg = /^\+?[1-9]\d*$/;

    if(document.form1.seniority.value=="" && document.form1.leave.value!="")
    {
        alert("<?=_("工龄不能为空！")?>");
        return (false);
    }
    if(document.form1.leave.value=="" && document.form1.seniority.value!="")
    {
        alert("<?=_("年假天数不能为空！")?>");
        return (false);
    }
    if(!reg.test(document.form1.seniority.value) && document.form1.seniority.value!="")
    {
        alert('<?=_("工龄应为整数！")?>');
        return (false);
    }
    if(!reg.test(document.form1.leave.value) && document.form1.leave.value!="")
    {
        alert('<?=_("年假天数应为整数！")?>');
        return (false);
    }

    return true;
}

function delete_leave(ID)
{
    msg='<?=_("确认要删除该年假设置吗？")?>';
    if(window.confirm(msg))
    {
        LEAVE="delete.php?ID=" + ID;
        window.location=LEAVE;
    }
}


function delete_all()
{
    msg='<?=_("确认要删除所有年假设置吗？")?>';
    if(window.confirm(msg))
    {
        URL="delete_all.php";
        window.location=URL;
    }
}
function calculation()
{
    msg='<?=_("确定重置人事档案所有员工年假信息吗？")?>';
    if(window.confirm(msg))
    {
        URL="calculation.php";
        window.location=URL;
    }
}
</script>
<?
$sql = "select count(*) from attend_leave_param";
$result= exequery(TD::conn(),$sql);
if($rows=mysql_fetch_array($result))
{
    $count = $rows[0];
}
?>
<body class="attendance">
<h5 class="attendance-title"><?=_("添加年假信息")?></h5><br>
<form action="set.php"  method="post" name="form2">
    <table class="table table-small table-bordered" width="450"  align="center" >

        <tr>
            <td nowrap class=""><?=_("是否开启按工龄自动计算年假并且按入职日期统计年假信息")?></td>
            <td nowrap class="">
                <input type="radio" name="LEAVE_BY_SENIORITY" id="LEAVE_BY_SENIORITY1" value="1" <?if($LEAVE_BY_SENIORITY==1) echo "checked";?>><?=_("是")?>
                <input type="radio" name="LEAVE_BY_SENIORITY" id="LEAVE_BY_SENIORITY2" value="0" <?if($LEAVE_BY_SENIORITY==0) echo "checked";?>><?=_("否")?>
            </td>
        <!--<tr>
            <td nowrap class=""><?=_("是否开启按入职日期计算年假")?></td>
            <td nowrap class="">
                <input type="radio" name="ENTRY_RESET_LEAVE" id="ENTRY_RESET_LEAVE1" value="1" <?if($ENTRY_RESET_LEAVE==1) echo "checked";?>><?=_("是")?>
                <input type="radio" name="ENTRY_RESET_LEAVE" id="ENTRY_RESET_LEAVE2" value="0" <?if($ENTRY_RESET_LEAVE==0) echo "checked";?>><?=_("否")?>
            </td>
        </tr>-->


        <tr>
            <td nowrap  class="TableControl" colspan="2" align="center" style="text-align: center;">
                <input type="submit" value="<?=_("保存")?>" class="btn btn-primary" name="button">
            </td>
        </tr>
</form>
<form action="add.php"  method="post" name="form1" onsubmit="return CheckForm();">
    <tr>
        <td nowrap  class="TableControl" colspan="2" style="text-align: center;"><?=_("工龄")?>
            <input type="text" size="3" name="seniority" value="" class="input-small"><?=_("年以上")?>
            &nbsp;&nbsp;<?=("享有年假天数：")?>
            <input type="text" name="leave" size="3" value="" class="input-small"><?=_("天")?>
        </td>
    </tr>
    <tr>
        <td nowrap  class="TableControl" colspan="2" align="center" style="text-align: center;">
            <input type="submit" value="<?=_("添加")?>" class="btn btn-primary" name="button" <?if($LEAVE_BY_SENIORITY==1){?> disabled <?}?>>
        </td>
    </tr>
</form>
</table>
<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
    <tr>
        <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
    </tr>
</table>

<h5 class="attendance-title"><?=_("管理年假信息")?></h5>
<br>
<div align="center">

    <?
    $query = "SELECT * from attend_leave_param order by working_years asc";
    $cursor= exequery(TD::conn(),$query, $connstatus);

    $LEAVE_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $LEAVE_COUNT++;
        $id=$ROW["id"];
        $working_years=$ROW["working_years"];
        $leave_day=$ROW["leave_day"];

        if($LEAVE_COUNT==1)
        {
    ?>

    <table class="table table-small table-bordered">
        <tr>
            <th nowrap align="center"><?=_("序号")?></th>
            <th nowrap align="center"><?=_("条件")?></th>
            <th nowrap align="center"><?=_("工龄年数(包含)")?></th>
            <th nowrap align="center"><?=_("可享受年假天数")?></th>
            <th nowrap align="center"><?=_("操作")?></th>
        </tr>

        <?
        }
        ?>
        <tr class="">
            <td nowrap align="center"><?=$LEAVE_COUNT?></td>
            <td nowrap align="center"><?=_("大于")?></td>
            <td nowrap align="center"><?=$working_years?><?=_("年")?></td>
            <td nowrap align="center"><?=$leave_day?><?=_("天")?></td>
            <?
            if($LEAVE_BY_SENIORITY == '0'){
                ?>
                <td nowrap align="center" width="80">
                    <a href="edit.php?ID=<?=$id?>"> <?=_("编辑")?></a>
                    <a href="javascript:delete_leave('<?=$id?>');"> <?=_("删除")?></a>
                </td>
            <?}else{
                ?>
                <td nowrap align="center" width="80" title="<?=_("需关闭“按入职日期计算年假”按钮才允许编辑和删除")?>">
                    <?echo _("无权限");?>
                </td>
                <?
            }
            ?>
        </tr>
        <?
        }

        if($LEAVE_COUNT>0)
        {
        ?>

        <tr class="TableControl">
            <td nowrap style="text-align: center;" colspan="5" >
                <input type="button" class="btn btn-primary" OnClick="javascript:delete_all();" value="<?=_("全部删除")?>" <?if($LEAVE_BY_SENIORITY==1){?> disabled <?}?>>
                <?if($LEAVE_BY_SENIORITY == '1'){?>
                    <input type="button" class="btn btn-primary" OnClick="javascript:calculation();" value="<?=_("重置人事档案所有员工年假信息")?>">
                <?}?>
            </td>
        </tr>
    </table>&nbsp;
    <table  border="0" width="800">
        <tr align="left">
            <td nowrap rowspan="5"><?=_("&nbsp&nbsp说明:&nbsp&nbsp&nbsp");?></td>
            <td><span class="warnspan"><?=_("1.所有员工均建立档案并且填写了入职时间方可开启按工龄计算年假并且按入职时间计算年假。")?></span><td>
        </tr>
        <tr>
            <td><span class="warnspan"><?=_("2.开启设置后了“按工龄计算年假”后就不允许再对年假规则进行编辑和删除操作。")?></span></td>
        </tr>
        <tr>
            <td><span class="warnspan"><?=_("3.点击“重置人事档案所有员工年假信息”会按设置重置所有员工年假信息。")?></span></td>
        </tr>
        <tr>
            <td><span class="warnspan"><?=_("4.开启设置后，本年度年休假时间将按照入职时间的月份日期计算，例如当前时间为2016年9月13日，如果入职时间的月份日期为3月5日，则本年度年休假时间为2016年3月5日到2017年3月5日，如果入职时间的月份日期为10月3日，则本年度年休假时间为2015年10月3日到2016年10月3日。")?></span></td>
        </tr>
        <tr>
            <td><span class="warnspan"><?=_("5.开启设置后将通过定时任务根据入职日期重置年假信息。")?></span></td>
        </tr>
    </table>
<?
}
else
    Message("",_("尚未添加年假信息"));
?>

</div>
<br><br>
<div align="center">
    <input type="button"  value="<?=_("返回")?>" class="btn" onClick="location='../#dutyOrno';">
</div>
<br>
</body>
</html>