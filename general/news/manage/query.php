<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("���Ų�ѯ");
include_once("inc/header.inc.php");
?>


<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
function checkForm()
{
    if(document.getElementById("OPERATION1").checked)
        document.form1.action="search.php";
    else if(document.getElementById("OPERATION2").checked)
    {
         msg='ɾ�����ָܻ���ȷ��ɾ����';
        if(window.confirm(msg))
        {
            document.form1.action="search_del.php";
        }
        else
            return false;
    }
    return true;
}
</script>


<body class="bodycolor">

<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("���Ų�ѯ")?></span>
    </td>
  </tr>
</table>

<table class="TableTop" width="550" align="center">
   <tr>
      <td class="left"></td>
      <td class="center"><?=_("�����ѯ����")?></td>
      <td class="right"></td>
   </tr>
</table>
<form enctype="multipart/form-data" action="search.php"  method="post" name="form1" onsubmit="return checkForm();">
<table class="TableBlock no-top-border" width="550" align="center">
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("��ʽ��")?></td>
      <td class="TableData">
      <select name="FORMAT" class="BigSelect">
        <option value="" selected><?=_("ȫ��")?></option>
        <option value="0"><?=_("��ͨ��ʽ")?></option>
        <option value="1">MHT<?=_("��ʽ")?></option>
        <option value="2"><?=_("�����Ӹ�ʽ")?></option>
      </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("���ͣ�")?></td>
      <td class="TableData"> 
        <select name="TYPE_ID" class="BigSelect">
          <option value="" selected><?=_("ȫ��")?></option>
          <?=code_list("NEWS","");?>
        </select>&nbsp;
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("״̬��")?></td>
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
        <input type="text" name="SEND_TIME_MIN" size="12" maxlength="10" id="start_time" class="BigInput" value="" onClick="WdatePicker()"/> &nbsp;<?=_("��")?>&nbsp;
        <input type="text" name="SEND_TIME_MAX" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("���ݣ�")?></td>
      <td class="TableData">
        <input type="text" name="CONTENT" size="33" maxlength="200" class="BigInput" value="">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("���������")?></td>
      <td class="TableData">
        <input type="text" name="CLICK_COUNT_MIN" size="4" maxlength="10" class="BigInput" value=""> &nbsp;<?=_("��")?>&nbsp;
        <input type="text" name="CLICK_COUNT_MAX" size="4" maxlength="10" class="BigInput" value="">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("������")?></td>
      <td class="TableData">
        <input type="radio" name="OPERATION" id="OPERATION1" value="1" checked><label for="OPERATION1"><?=_("��ѯ")?></label>
        <input type="radio" name="OPERATION" id="OPERATION2" value="2"><label for="OPERATION2"><?=_("ɾ��")?></label>
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