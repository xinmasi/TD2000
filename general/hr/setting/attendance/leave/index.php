<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/td_core.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("��ǩ�ڼ�������");
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
        alert("<?=_("���䲻��Ϊ�գ�")?>");
        return (false);
    }
    if(document.form1.leave.value=="" && document.form1.seniority.value!="")
    {
        alert("<?=_("�����������Ϊ�գ�")?>");
        return (false);
    }
    if(!reg.test(document.form1.seniority.value) && document.form1.seniority.value!="")
    {
        alert('<?=_("����ӦΪ������")?>');
        return (false);
    }
    if(!reg.test(document.form1.leave.value) && document.form1.leave.value!="")
    {
        alert('<?=_("�������ӦΪ������")?>');
        return (false);
    }

    return true;
}

function delete_leave(ID)
{
    msg='<?=_("ȷ��Ҫɾ�������������")?>';
    if(window.confirm(msg))
    {
        LEAVE="delete.php?ID=" + ID;
        window.location=LEAVE;
    }
}


function delete_all()
{
    msg='<?=_("ȷ��Ҫɾ���������������")?>';
    if(window.confirm(msg))
    {
        URL="delete_all.php";
        window.location=URL;
    }
}
function calculation()
{
    msg='<?=_("ȷ���������µ�������Ա�������Ϣ��")?>';
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
<h5 class="attendance-title"><?=_("��������Ϣ")?></h5><br>
<form action="set.php"  method="post" name="form2">
    <table class="table table-small table-bordered" width="450"  align="center" >

        <tr>
            <td nowrap class=""><?=_("�Ƿ����������Զ�������ٲ��Ұ���ְ����ͳ�������Ϣ")?></td>
            <td nowrap class="">
                <input type="radio" name="LEAVE_BY_SENIORITY" id="LEAVE_BY_SENIORITY1" value="1" <?if($LEAVE_BY_SENIORITY==1) echo "checked";?>><?=_("��")?>
                <input type="radio" name="LEAVE_BY_SENIORITY" id="LEAVE_BY_SENIORITY2" value="0" <?if($LEAVE_BY_SENIORITY==0) echo "checked";?>><?=_("��")?>
            </td>
        <!--<tr>
            <td nowrap class=""><?=_("�Ƿ�������ְ���ڼ������")?></td>
            <td nowrap class="">
                <input type="radio" name="ENTRY_RESET_LEAVE" id="ENTRY_RESET_LEAVE1" value="1" <?if($ENTRY_RESET_LEAVE==1) echo "checked";?>><?=_("��")?>
                <input type="radio" name="ENTRY_RESET_LEAVE" id="ENTRY_RESET_LEAVE2" value="0" <?if($ENTRY_RESET_LEAVE==0) echo "checked";?>><?=_("��")?>
            </td>
        </tr>-->


        <tr>
            <td nowrap  class="TableControl" colspan="2" align="center" style="text-align: center;">
                <input type="submit" value="<?=_("����")?>" class="btn btn-primary" name="button">
            </td>
        </tr>
</form>
<form action="add.php"  method="post" name="form1" onsubmit="return CheckForm();">
    <tr>
        <td nowrap  class="TableControl" colspan="2" style="text-align: center;"><?=_("����")?>
            <input type="text" size="3" name="seniority" value="" class="input-small"><?=_("������")?>
            &nbsp;&nbsp;<?=("�������������")?>
            <input type="text" name="leave" size="3" value="" class="input-small"><?=_("��")?>
        </td>
    </tr>
    <tr>
        <td nowrap  class="TableControl" colspan="2" align="center" style="text-align: center;">
            <input type="submit" value="<?=_("���")?>" class="btn btn-primary" name="button" <?if($LEAVE_BY_SENIORITY==1){?> disabled <?}?>>
        </td>
    </tr>
</form>
</table>
<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
    <tr>
        <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
    </tr>
</table>

<h5 class="attendance-title"><?=_("���������Ϣ")?></h5>
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
            <th nowrap align="center"><?=_("���")?></th>
            <th nowrap align="center"><?=_("����")?></th>
            <th nowrap align="center"><?=_("��������(����)")?></th>
            <th nowrap align="center"><?=_("�������������")?></th>
            <th nowrap align="center"><?=_("����")?></th>
        </tr>

        <?
        }
        ?>
        <tr class="">
            <td nowrap align="center"><?=$LEAVE_COUNT?></td>
            <td nowrap align="center"><?=_("����")?></td>
            <td nowrap align="center"><?=$working_years?><?=_("��")?></td>
            <td nowrap align="center"><?=$leave_day?><?=_("��")?></td>
            <?
            if($LEAVE_BY_SENIORITY == '0'){
                ?>
                <td nowrap align="center" width="80">
                    <a href="edit.php?ID=<?=$id?>"> <?=_("�༭")?></a>
                    <a href="javascript:delete_leave('<?=$id?>');"> <?=_("ɾ��")?></a>
                </td>
            <?}else{
                ?>
                <td nowrap align="center" width="80" title="<?=_("��رա�����ְ���ڼ�����١���ť������༭��ɾ��")?>">
                    <?echo _("��Ȩ��");?>
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
                <input type="button" class="btn btn-primary" OnClick="javascript:delete_all();" value="<?=_("ȫ��ɾ��")?>" <?if($LEAVE_BY_SENIORITY==1){?> disabled <?}?>>
                <?if($LEAVE_BY_SENIORITY == '1'){?>
                    <input type="button" class="btn btn-primary" OnClick="javascript:calculation();" value="<?=_("�������µ�������Ա�������Ϣ")?>">
                <?}?>
            </td>
        </tr>
    </table>&nbsp;
    <table  border="0" width="800">
        <tr align="left">
            <td nowrap rowspan="5"><?=_("&nbsp&nbsp˵��:&nbsp&nbsp&nbsp");?></td>
            <td><span class="warnspan"><?=_("1.����Ա������������������д����ְʱ�䷽�ɿ��������������ٲ��Ұ���ְʱ�������١�")?></span><td>
        </tr>
        <tr>
            <td><span class="warnspan"><?=_("2.�������ú��ˡ������������١���Ͳ������ٶ���ٹ�����б༭��ɾ��������")?></span></td>
        </tr>
        <tr>
            <td><span class="warnspan"><?=_("3.������������µ�������Ա�������Ϣ���ᰴ������������Ա�������Ϣ��")?></span></td>
        </tr>
        <tr>
            <td><span class="warnspan"><?=_("4.�������ú󣬱�������ݼ�ʱ�佫������ְʱ����·����ڼ��㣬���統ǰʱ��Ϊ2016��9��13�գ������ְʱ����·�����Ϊ3��5�գ���������ݼ�ʱ��Ϊ2016��3��5�յ�2017��3��5�գ������ְʱ����·�����Ϊ10��3�գ���������ݼ�ʱ��Ϊ2015��10��3�յ�2016��10��3�ա�")?></span></td>
        </tr>
        <tr>
            <td><span class="warnspan"><?=_("5.�������ú�ͨ����ʱ���������ְ�������������Ϣ��")?></span></td>
        </tr>
    </table>
<?
}
else
    Message("",_("��δ��������Ϣ"));
?>

</div>
<br><br>
<div align="center">
    <input type="button"  value="<?=_("����")?>" class="btn" onClick="location='../#dutyOrno';">
</div>
<br>
</body>
</html>