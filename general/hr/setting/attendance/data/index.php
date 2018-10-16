<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("ɾ����������");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
function CheckForm()
{
   if(window.confirm("<?=_("ɾ�������ݽ����ɻָ���ȷ��Ҫɾ����")?>"))
      return true;

   return false;
}
</script>


<body class="attendance">

<h5 class="attendance-title"><?=_("ɾ����������")?></h5>

<br>
<form action="delete.php" method="post" name="form1" onsubmit="return CheckForm();">
  <table class="table table-middle table-bordered"  width="500" align="center">
  
    <tr>
      <th nowrap class=""><?=_("�û���")?></th>
      <td class="">
          <input type="hidden" name="TO_ID" value="">
        <textarea cols=35 name="TO_NAME" rows="3" class="" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','','TO_ID', 'TO_NAME')"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('TO_ID', 'TO_NAME')"><?=_("���")?></a>
        <br><?=_("��ʾ�������ѡ���û�����ɾ�������û��Ŀ��ڼ�¼��")?>
      </td>
    </tr>    
    <tr>
      <th nowrap class=""><?=_("��ʼʱ�䣺")?></th>
      <td class=""><input type="text" name="BEGIN_DATE" size="20" maxlength="20" class="" value="" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
      </td>
    </tr>
    <tr>
      <th nowrap class=""><?=_("��ֹʱ�䣺")?></th>
      <td class=""><input type="text" name="END_DATE" size="20" maxlength="20" class="" value="<?=date('Y-m-d H:i:s',time())?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
      </td>
    </tr>
    <tr>
      <th nowrap class=""><?=_("ɾ����Ŀ��")?></th>
      <td class="">
          <label for="DUTY"><input type="checkbox" name="DUTY" id="DUTY"><?=_("���°�Ǽ�")?></label>
          <label for="OUT"><input type="checkbox" name="OUT" id="OUT"><?=_("����Ǽ�")?></label>
          <label for="LEAVE"><input type="checkbox" name="LEAVE" id="LEAVE"><?=_("��ٵǼ�")?></label>
          <label for="EVECTION"><input type="checkbox" name="EVECTION" id="EVECTION"><?=_("����Ǽ�")?></label>
          <label for="OVERTIME"><input type="checkbox" name="OVERTIME" id="OVERTIME"><?=_("�Ӱ�Ǽ�")?></label>
          <label for="MOBILE_ATTENDANCE"><input type="checkbox" name="MOBILE_ATTENDANCE" id="MOBILE_ATTENDANCE"><?=_("���ڵǼ�")?></label>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableControl" colspan="2" style="text-align: center;">
          <input type="submit"  value="<?=_("ȷ��")?>" class="btn btn-primary" title="<?=_("ȷ��")?>">&nbsp;&nbsp;
          <input type="button"  value="<?=_("����")?>" class="btn" onClick="location='../#dataManage';">
      </td>
    </tr>
   
  </table>
 </form>
<br>

</body>
</html>