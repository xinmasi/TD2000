<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("����֪ͨ��ѯ");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
function checkForm()
{
   if(document.getElementById("OPERATION1").checked)
      document.form1.action="search.php";
   else if(document.getElementById("OPERATION2").checked)
      document.form1.action="search_del.php";
   else if(document.getElementById("OPERATION3").checked)
      document.form1.action="search_export.php";
	  
   return true;
}

</script>


<body class="bodycolor">

<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("����֪ͨ��ѯ")?></span>
    </td>
  </tr>
</table>


<table class="TableBlock" width="550" align="center">
  <form enctype="multipart/form-data" method="post" name="form1" onsubmit="return checkForm();">
<?
if($_SESSION["LOGIN_USER_PRIV"]=="1")
{
?>
    <tr>
      <td nowrap class="TableData"><?=_("�����ˣ�")?></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID" value="">
        <textarea cols=35 name="TO_NAME" rows="3" class="BigStatic" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('196','','TO_ID', 'TO_NAME')"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('TO_ID', 'TO_NAME')"><?=_("���")?></a>
      </td>
    </tr>
<?
}
?>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("��ʽ��")?></td>
      <td class="TableData">
      <select name="FORMAT" class="BigSelect">
        <option value="" selected><?=_("ȫ��")?></option>
        <option value="0"><?=_("��ͨ��ʽ")?></option>
        <option value="1">MHT<?=_("��ʽ")?></option>
      </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("���ͣ�")?></td>
      <td class="TableData">
        <select name="TYPE_ID" class="BigSelect">
          <option value="" selected><?=_("ȫ��")?></option>
          <?=code_list("NOTIFY","");?>
        </select>&nbsp;
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("����״̬��")?></td>
      <td class="TableData">
      <select name="PUBLISH" class="BigSelect">
        <option value="" selected><?=_("ȫ��")?></option>
        <option value="0"><?=_("δ����")?></option>
        <option value="1"><?=_("�ѷ���")?></option>
      </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�Ƿ��ö���")?></td>
      <td class="TableData">
      <select name="TOP" class="BigSelect">
        <option value="" selected><?=_("ȫ��")?></option>
        <option value="0"><?=_("δ�ö�")?></option>
        <option value="1"><?=_("���ö�")?></option>
      </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("���⣺")?></td>
      <td class="TableData">
        <input type="text" name="SUBJECT" size="33" maxlength="100" class="BigInput" value="">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�������ڣ�")?></td>
      <td class="TableData">
        <input type="text" name="SEND_TIME_MIN" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker()"/> &nbsp;<?=_("��")?>&nbsp;
        <input type="text" name="SEND_TIME_MAX" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker()"/>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("���ݣ�")?></td>
      <td class="TableData">
        <input type="text" name="CONTENT" size="33" maxlength="200" class="BigInput" value="">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("��Ч״̬��")?></td>
      <td class="TableData">
      <select name="STAT" class="BigSelect">
        <option value="" selected><?=_("ȫ��")?></option>
        <option value="1"><?=_("����Ч")?></option>
        <option value="2"><?=_("����Ч")?></option>
        <option value="3"><?=_("����ֹ")?></option>
      </select>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="submit" value="<?=_("ȷ��")?>" class="BigButton">&nbsp;&nbsp;
        <input type="reset" value="<?=_("����")?>" class="BigButton">&nbsp;&nbsp;
      </td>
    </tr>
  </table>
</form>

</body>
</html>