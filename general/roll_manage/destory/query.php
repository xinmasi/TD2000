<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("�ļ���ѯ");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>


<body class="bodycolor">

<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("�ļ���ѯ")?></span>
    </td>
  </tr>
</table>


<table class="TableBlock" width="85%" align="center">
  <form enctype="multipart/form-data" action="search.php"  method="post" name="form1">
  <TR>
      <TD class="TableData"><?=_("�ļ��ţ�")?></TD>
      <TD class="TableData">
       <INPUT name="FILE_CODE" id="FILE_CODE" size=20 maxlength="100" class="BigInput">
      </TD>
      <TD class="TableData"><?=_("�ļ�����ʣ�")?></TD>
      <TD class="TableData">
       <INPUT name="FILE_SUBJECT" id="FILE_SUBJECT" size=30 maxlength="100" class="BigInput">
      </TD>
  </TR>
  <TR>
      <TD class="TableData"><?=_("�ļ����⣺")?></TD>
      <TD class="TableData">
       <INPUT name="FILE_TITLE" id="FILE_TITLE" size=30 maxlength="100" class="BigInput">
      </TD>
      <TD class="TableData"><?=_("�ļ������⣺")?></TD>
      <TD class="TableData">
       <INPUT name="FILE_TITLE0" id="FILE_TITLE0" size=30 maxlength="100" class="BigInput">
      </TD>
  </TR>
  <TR>
      <TD class="TableData"><?=_("���ĵ�λ��")?></TD>
      <TD class="TableData">
       <INPUT name="SEND_UNIT" id="SEND_UNIT" size=30 maxlength="100" class="BigInput">
      </TD>
      <TD class="TableData"><?=_("�������ڣ�")?></TD>
      <TD class="TableData">
        <input type="text" name="SEND_DATE0" size="10" maxlength="10" class="BigInput" value="<?=$SEND_DATE?>" onClick="WdatePicker()">
       
        -
		<input type="text" name="SEND_DATE1" size="10" maxlength="10" class="BigInput" value="<?=$SEND_DATE?>" onClick="WdatePicker()">
        
      </TD>
  </TR>
  <TR>
      <TD nowrap class="TableData"><?=_("�ܼ���")?></TD>
      <TD class="TableData">
	<select name="SECRET" class="BigSelect">
	<option value="" ></option>
    <?=code_list("RMS_SECRET",$SECRET)?>
	</select>
      </TD>
      <TD class="TableData"><?=_("�����ȼ���")?></TD>
      <TD class="TableData">
	<select name="URGENCY" class="BigSelect">
	<option value="" ></option>
    <?=code_list("RMS_URGENCY",$URGENCY)?>
	</select>
     </TD>
  </TR>
  <TR>
      <TD nowrap class="TableData"><?=_("�ļ����ࣺ")?></TD>
      <TD class="TableData">
	<select name="FILE_TYPE" class="BigSelect">
	  <option value="" ></option>
    <?=code_list("RMS_FILE_TYPE",$FILE_TYPE)?>
	</select>
      </TD>
      <TD class="TableData"><?=_("�������")?></TD>
      <TD class="TableData">
	<select name="FILE_KIND" class="BigSelect">
	<option value="" ></option>
    <?=code_list("RMS_FILE_KIND",$FILE_KIND)?>
	</select>
     </TD>
  </TR>
  <TR>
      <TD nowrap class="TableData"><?=_("�ļ�ҳ����")?></TD>
      <TD class="TableData">
        <input type="text" name="FILE_PAGE0" value="" size="10" maxlength="50" class="BigInput">
        -
		<input type="text" name="FILE_PAGE1" value="" size="10" maxlength="50" class="BigInput">
      </TD>
      <TD class="TableData"><?=_("��ӡҳ����")?></TD>
      <TD class="TableData">
        <input type="text" name="PRINT_PAGE0" value="" size="10" maxlength="50" class="BigInput">
        -
		<input type="text" name="PRINT_PAGE1" value="" size="10" maxlength="50" class="BigInput">
      </TD>
  </TR>
  <TR>
      <TD nowrap class="TableData"><?=_("��ע��")?></TD>
      <TD colSpan=3 class="TableData"><input type="text" name="REMARK" value="" size="50" maxlength="100" class="BigInput"></TD>
   </TR>
    <tr align="center" class="TableControl">
      <td colspan="4" nowrap>
        <input type="submit" value="<?=_("��ѯ")?>" class="BigButton">&nbsp;&nbsp;
        <input type="reset" value="<?=_("����")?>" class="BigButton">&nbsp;&nbsp;
      </td>
    </tr>
  </table>
</form>

</body>
</html>