<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("ָ������������Ա");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script language="javascript">
function delete_dep(MANAGER_ID)
{
    msg='<?=_("ȷ��Ҫɾ��������")?>';
    if(window.confirm(msg))
    {
        URL="delete.php?MANAGER_ID=" + MANAGER_ID;
        window.location=URL;
    }
}
function CheckForm()
{
    if(document.form1.TO_ID.value=="" || document.form1.COPY_TO_ID.value=="")
    {
        alert("<?=_("����ȷ��д����������Ϣ��")?>");
        return false;
    }
    document.form1.submit();
    document.form1.action="";
    return true;
}
</script>
<body class="attendance">
<h5 class="attendance-title"><?=_("�������������½�")?></h5><br>
<form action="submit.php"  method="post" name="form1" >
    <table class="table table-middle table-bordered" align="center">
        <tr>
            <td nowrap class=""><?=_("��Ͻ���ţ�")?></td>
            <td class="">
                <input type="hidden" name="TO_ID" value="<?=$DEPT_ID_STR?>">
                <textarea cols=50 name=TO_NAME rows=5 class="BigStatic" wrap="yes" readonly><?=$DEPT_NAME_STR?></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectDept('5')"><?=_("���")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("���")?></a>
            </td>
        </tr>
        <tr>
            <td nowrap class=""><?=_("������Ա��")?></td>
            <td nowrap class="">
                <input type="hidden" name="COPY_TO_ID" value="<?=$MANAGERS?>">
                <textarea cols="50" name="COPY_TO_NAME" rows="5" class="BigStatic" wrap="yes" readonly><?=$MANAGERS_USER_NAME?></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("���")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("���")?></a>
            </td>
        </tr>
        <tr>
            <td nowrap  class="" colspan="2" style="text-align: center;">
                <input type="button" value="<?=_("�����������")?>" class="btn btn-primary" onclick="CheckForm();">&nbsp;&nbsp;
            </td>
        </tr>
    </table>
</form>

<br>
<h5 class="attendance-title"><?=_("���������������")?></h5>
<?
$I = 0;
$query = "select MANAGER_ID,DEPT_ID_STR,MANAGERS from ATTEND_MANAGER order by MANAGER_ID";
$cursor= exequery(TD::conn(),$query, $connstatus);
$ATTEND_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $I++;
    $ATTEND_COUNT++;
    $MANAGER_ID=$ROW["MANAGER_ID"];
    $MANAGERS=$ROW["MANAGERS"];
    $DEPT_ID_STR=$ROW["DEPT_ID_STR"];

    if($DEPT_ID_STR!="ALL_DEPT")
        $DEPT_NAME_STR = td_trim(GetDeptNameById($DEPT_ID_STR));
    else
        $DEPT_NAME_STR =_("ȫ�岿��");
    $MANAGERS_NAME_STR = td_trim(GetUserNameById($MANAGERS));
    if($ATTEND_COUNT%2==1)
        $TableLine='TableLine1';
    else
        $TableLine='TableLine2';

    if($ATTEND_COUNT==1)
    {
?>
<table class="table table-bordered" align="center">
    <tr class="">
        <th nowrap align="center"><?=_("���")?></th>
        <th nowrap align="center"><?=_("��Ͻ����")?></th>
        <th nowrap align="center"><?=_("������Ա")?></th>
        <th nowrap align="center"><?=_("����")?></th>
    </tr>
    <?
    }
    ?>
    <tr class="">
        <td nowrap align="center"><?=$I?></td>
        <td align="left"><?=$DEPT_NAME_STR?></td>
        <td align="left"><?=$MANAGERS_NAME_STR?></td>
        <td nowrap align="center">
            <a href="edit.php?MANAGER_ID=<?=$MANAGER_ID?>"> <?=_("�༭")?></a>
            <a href="javascript:delete_dep('<?=$MANAGER_ID?>');"> <?=_("ɾ��")?></a>
        </td>
    </tr>
    <?
    }
    ?>
</table>
<br>
<div align="center">
    <input type="button" class="btn" value="<?=_("����")?>" onClick="location='../index.php#settingRule';">
</div>
<br>
</body>
</html>