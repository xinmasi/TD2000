<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("�����ƻ���ѯ");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>



<body class="bodycolor" onload="form1.NAME.focus();">

<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("�����ƻ���ѯ")?></span>
    </td>
  </tr>
</table>

<table class="TableBlock" width="500" align="center">
  <form enctype="multipart/form-data" action="search.php"  method="post" name="form1">
    <tr>
      <td colspan="2" nowrap class="Tableheader">
      <img src="<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif"> <?=_("�����ƻ���ѯ")?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("�ƻ����ƣ�")?></td>
      <td class="TableData">
        <input type="text" name="NAME" size="36" maxlength="200" class="BigInput" value="<?=$NAME?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("�ƻ����ݣ�")?></td>
      <td class="TableData">
        <input type="text" name="CONTENT" size="36" maxlength="200" class="BigInput" value="<?=$CONTENT?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("��Ч�ڣ�")?></td>
      <td class="TableData">
        <?=_("��ʼ���ڣ�")?><input type="text" id="start_time" name="BEGIN_DATE" size="10" maxlength="10" class="BigInput" value="<?=$BEGIN_DATE?>" onClick="WdatePicker()">
      <br>
        <?=_("�������ڣ�")?><input type="text" name="END_DATE" size="10" maxlength="10" class="BigInput" value="<?=$END_DATE?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})">
       
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("�ƻ����ͣ�")?></td>
      <td class="TableData">
        <select name="TYPE" class="BigSelect">
          <option value="ALL_TYPE"><?=_("��������")?></option>
<?
 $query = "SELECT * from PLAN_TYPE order by TYPE_NO";
 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
    $TYPE_ID=$ROW["TYPE_ID"];
    $TYPE_NAME=$ROW["TYPE_NAME"];
?>
          <option value="<?=$TYPE_ID?>" <?if($TYPE==$TYPE_ID) echo "selected";?>><?=$TYPE_NAME?></option>
<?
 }
?>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("������Χ�����ţ���")?></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID" value="<?=$TO_ID?>">
        <input type="text" name="TO_NAME" value="<?=$TO_NAME?>" class=BigStatic size=20 maxlength=100 readonly>&nbsp;
        <a href="javascript:;" class="orgAdd" onClick="SelectDeptSingle()"><?=_("ѡ��")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("������Χ����Ա����")?></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID3" value="">
        <input type="text" name="TO_NAME3" size="20" class="BigStatic" readonly>&nbsp;
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('96','','TO_ID3', 'TO_NAME3')"><?=_("ѡ��")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("�����ˣ�")?></td>
      <td class="TableData">
      	<input type="hidden" name="COPY_TO_ID" value="">
        <input type="text" name="COPY_TO_NAME" size="20" class="BigStatic" readonly>&nbsp;
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('96','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("ѡ��")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("�����ˣ�")?></td>
      <td class="TableData">
      	<input type="hidden" name="SECRET_TO_ID" value="">
        <input type="text" name="SECRET_TO_NAME" size="20" class="BigStatic" readonly>&nbsp;
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('96','','SECRET_TO_ID', 'SECRET_TO_NAME')"><?=_("ѡ��")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("��ע��")?></td>
      <td class="TableData">
        <input type="text" name="REMARK" size="40" maxlength="200" class="BigInput" value="<?=$REMARK?>">
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="submit" value="<?=_("��ѯ")?>" class="BigButton">&nbsp;&nbsp;
      </td>
    </tr>
  </table>
</form>

</body>
</html>