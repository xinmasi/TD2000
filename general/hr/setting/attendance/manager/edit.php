<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

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
    return true;
}
</script>

<body class="attendance">
<?
$query = "select MANAGER_ID,MANAGERS,DEPT_ID_STR from ATTEND_MANAGER where MANAGER_ID='$MANAGER_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $MANAGER_ID=$ROW["MANAGER_ID"];
    $MANAGERS=$ROW["MANAGERS"];
    $DEPT_ID_STR=$ROW["DEPT_ID_STR"];
    if($DEPT_ID_STR!="ALL_DEPT")
        $DEPT_NAME_STR = GetDeptNameById($DEPT_ID_STR);
    else
        $DEPT_NAME_STR =_("ȫ�岿��");
    $MANAGERS_USER_NAME = GetUserNameById($MANAGERS);

}
?>
<h5 class="attendance-title"><?=_("�༭������������")?></h5>
<br>
<form action="update.php"  method="post" name="form1">
    <table class="table table-middle table-bordered" width="70%" align="center" >
        <tr>
            <td nowrap class="TableData"><?=_("��Ͻ���ţ�")?></td>
            <td class="TableData">
                <input type="hidden" name="TO_ID" value="<?=$DEPT_ID_STR?>">
                <textarea cols="50" name="TO_NAME" rows="5" class="BigStatic" wrap="yes" readonly><?=$DEPT_NAME_STR?></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectDept('5')"><?=_("���")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("���")?></a>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("������Ա��")?></td>
            <td nowrap class="TableData">
                <input type="hidden" name="COPY_TO_ID" value="<?=$MANAGERS?>">
                <textarea cols="50" name="COPY_TO_NAME" rows="5" class="BigStatic" wrap="yes" readonly><?=$MANAGERS_USER_NAME?></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("���")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("���")?></a>
            </td>
        </tr>
        <tr>
            <td nowrap  class="TableControl" colspan="2" style="text-align: center;">
                <input type="hidden" value="<?=$MANAGER_ID?>" name="MANAGER_ID">
                <input type="button" value="<?=_("����")?>" class="btn btn-primary" onclick="CheckForm()">&nbsp;&nbsp;
                <input type="button" value="<?=_("����")?>" class="btn" onclick="location='index.php'">
            </td>
        </tr>
    </table>
</form>
</body>
</html>