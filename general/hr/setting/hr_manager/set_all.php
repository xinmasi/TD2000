<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("��������������Դ����Ա");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("��������������Դ����Ա")?></span>
    </td>
  </tr>
</table>

<table class="TableBlock" width="600" align="center" >
  <form action="all_submit.php"  method="post" name="form1">  
   <tr>
    <td nowrap class="TableData"><?=_("������Դ����Ա��")?></td>
    <td nowrap class="TableData">
      <input type="hidden" name="COPY_TO_ID" value="">
      <textarea cols="50" name="COPY_TO_NAME" rows="5" class="BigStatic" wrap="yes" readonly></textarea>
      <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("���")?></a>
      <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("���")?></a> 
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("����רԱ��")?></td>
    <td nowrap class="TableData">
      <input type="hidden" name="TO_ID_HR" value="">
      <textarea cols="50" name="TO_NAME_HR" rows="5" class="BigStatic" wrap="yes" readonly></textarea>
      <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','','TO_ID_HR', 'TO_NAME_HR')"><?=_("���")?></a>
      <a href="javascript:;" class="orgClear" onClick="ClearUser('TO_ID_HR', 'TO_NAME_HR')"><?=_("���")?></a> 
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("��ѡ���ţ�")?></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID" value="">
        <textarea cols="50" name="TO_NAME" rows="5" class="BigStatic" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept('5')"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("���")?></a>
      </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("������")?></td>
      <td class="TableData">
        <input type="radio" name="OPERATION" value="0" id="OPERATION0" checked><label for="OPERATION0"><?=_("�������")?></label>
        <input type="radio" name="OPERATION" value="1" id="OPERATION1"><label for="OPERATION1"><?=_("����ɾ��")?></label>
      </td>
   </tr>
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
      <input type="submit" value="<?=_("ȷ��")?>" class="BigButton">&nbsp;&nbsp;
      <input type="reset" value="<?=_("���")?>" class="BigButton">
      </td>
    </tr>
  </form>
</table>

</body>
</html>