<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("�༭�����Ϣ");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.leave.value=="")
   { alert("<?=_("�����������Ϊ�գ�")?>");
     return (false);
   }
   var reg = /^\d+(?=\{0,1}\d+$|$)/
   if(!reg.test(document.form1.leave.value))
   {
     alert('<?=_("�������ӦΪ������")?>');
     return (false);
   }
   return true;
}

</script>


<?
 $query = "SELECT * from attend_leave_param where id='$ID'";
 $cursor= exequery(TD::conn(),$query);

 if($ROW=mysql_fetch_array($cursor))
 {
    $working_years=$ROW["working_years"];
    $leave_day=$ROW["leave_day"];
}
?>

<body class="attendance">
<h5 class="attendance-title"><?=_("�༭�����Ϣ")?></h5><br>
<form action="update.php"  method="post" name="form1" onsubmit="return CheckForm();">
  <table class="table table-middle  table-bordered" align="center" >
  
   <tr>
        <td nowrap  class="TableControl"><?=_("����")?></td>
        <td nowrap  class="TableControl">
            <input type="text" size="3" name="seniority" value="<?=$working_years?>" class=""><?=_("������")?>
        </td>
   <tr>
   <tr>
        <td nowrap  class="TableControl"><?=_("�����������")?></td>
        <td nowrap  class="TableControl">
            <input type="text" name="leave" size="3" value="<?=$leave_day?>" class=""><?=_("��")?>
        </td>
   <tr>
   <tr>
    <td nowrap  class="TableControl" colspan="2" style="text-align: center;">
        <input type="hidden" value="<?=$ID?>" name="LEAVE_ID">
        <input type="submit" value="<?=_("ȷ��")?>" class="btn btn-primary">&nbsp;&nbsp;
        <input type="button" value="<?=_("����")?>" class="btn" onclick="location='index.php'">
    </td>
    </tr>
  </table>
</form>
</body>
</html>