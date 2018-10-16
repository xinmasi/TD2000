<?
include_once("inc/auth.inc.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("免签节假日设置");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
function CheckForm()
{
    function trim(str)
    {
        return str.replace(/(^\s*)|(\s*$)/g,"");
    }

    if(document.form1.BEGIN_DATE.value=="")
    { alert("<?=_("起始日期不能为空！")?>");
        return false;
    }
    if(document.form1.END_DATE.value=="")
    { alert("<?=_("结束日期不能为空！")?>");
        return false;
    }
    if(trim(document.form1.HOLIDAY_NAME.value)=="")
    {
        alert("<?=_("节假日名称不能为空！")?>");
        return false;
    }
    return true;
}
    
function delete_holiday(HOLIDAY_ID)
{
    msg='<?=_("确认要删除该项节假日吗？")?>';
    if(window.confirm(msg))
    {
        HOLIDAY="delete.php?HOLIDAY_ID=" + HOLIDAY_ID;
        window.location=HOLIDAY;
    }
}


function delete_all()
{
    msg='<?=_("确认要删除所有节假日吗？")?>';
    if(window.confirm(msg))
    {
        URL="delete_all.php";
        window.location=URL;
    }
}
</script>


<body class="attendance">
<?
$CUR_DATE=date("Y-m-d",time());
?>
<h5 class="attendance-title"><?=_("添加免签节假日")?></h5>
<form action="add.php"  method="post" name="form1" onsubmit="return CheckForm();">
    <table class="table table-small table-bordered" width="450"  align="center" >

        <tr>
            <td nowrap class=""><?=_("起始日期：")?></td>
            <td nowrap class="">
                <input type="text" name="BEGIN_DATE" class="" size="10" maxlength="10" value="<?=$CUR_DATE?>" onClick="WdatePicker()">
            </td>
        <tr>
            <td nowrap class=""><?=_("结束日期：")?></td>
            <td nowrap class="">
                <input type="text" name="END_DATE" class="" size="10" maxlength="10" value="<?=$CUR_DATE?>" onClick="WdatePicker()">
            </td>
        </tr>
        <tr>
            <td nowrap class=""><?=_("节假日名称：")?></td>
            <td nowrap class="">
                <input type="text" name="HOLIDAY_NAME" class="" size="13" maxlength="10">
            </td>
        </tr>
        <tr>
            <td nowrap  class="" colspan="2" align="center">
                <input type="submit" value="<?=_("添加")?>" class="btn btn-primary" title="<?=_("添加日期")?>" name="button" style="margin-left: 45%;">
            </td>
    </table>
</form>
<br>

<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
    <tr>
        <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
    </tr>
</table>

<h5 class="attendance-title"><?=_("管理免签节假日")?></h5>
<br>
<div align="center">

    <?
    $query = "SELECT * from ATTEND_HOLIDAY order by BEGIN_DATE desc";
    $cursor= exequery(TD::conn(),$query, $connstatus);

    $HOLIDAY_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
    $HOLIDAY_COUNT++;
    $HOLIDAY_ID=$ROW["HOLIDAY_ID"];
    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $END_DATE=$ROW["END_DATE"];
    $HOLIDAY_NAME=$ROW["HOLIDAY_NAME"];

    if($HOLIDAY_COUNT==1)
    {
    ?>

    <table class="table table-bordered">

        <thead class="">
        <tr>
            <th nowrap align="center"><?=_("序号")?></th>
            <th nowrap align="center"><?=_("节假日名称")?></th>
            <th nowrap align="center"><?=_("起始日期")?></th>
            <th nowrap align="center"><?=_("结束日期")?></th>
            <th nowrap align="center"><?=_("操作")?></th>
        </tr>
        </thead>


        <?
        }
        ?>
        <tr class="">
            <td nowrap align="center"><?=$HOLIDAY_COUNT?></td>
            <td nowrap align="center"><?=$HOLIDAY_NAME?></td>
            <td nowrap align="center"><?=$BEGIN_DATE?></td>
            <td nowrap align="center"><?=$END_DATE?></td>
            <td nowrap align="center" width="80">
                <a href="edit.php?HOLIDAY_ID=<?=$HOLIDAY_ID?>"> <?=_("编辑")?></a>
                <a href="javascript:delete_holiday('<?=$HOLIDAY_ID?>');"> <?=_("删除")?></a>
            </td>
        </tr>
        <?
        }

        if($HOLIDAY_COUNT>0)
        {
        ?>

        <tr class="TableControl">
            <td nowrap align="center" colspan="5">
                <input type="button" class="btn" style="margin-left: 45%;" OnClick="javascript:delete_all();" value="<?=_("全部删除")?>">
            </td>
        </tr>
    </table>
<?
}
else
    Message("",_("尚未添加免签节假日"));
?>

</div>
<br>
<div align="center">
    <input type="button"  value="<?=_("返回")?>" class="btn" onClick="location='../#dutyOrno';">
</div>
<br>
</body>
</html>