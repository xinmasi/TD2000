<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("��Ƹ�ƻ���ѯ");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>

<body class="bodycolor">
<table border="0" width="80%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"><?=_("��Ƹ�ƻ���ѯ")?></span></td>
  </tr>
</table>
<form enctype="multipart/form-data" action="search.php"  method="post" name="form1">
<table class="TableBlock" width="600" align="center">
   <tr>
   		<td nowrap class="TableData" width="100"><?=_("���ƣ�")?></td>
    	<td class="TableData"  width="180" ><input type="text" name="PLAN_NAME" size="20" id="PLAN_NAME" class="BigInput"></td>
    	<td nowrap class="TableData"><?=_("�ƻ���ţ�")?></td>
      <td class="TableData" >
        <INPUT type="text"name="PLAN_NO" class="BigInput" size="15" >
      </td>
   </tr>
   <tr>
   		<td nowrap class="TableData"><?=_("�ƻ�״̬��")?></td>
      <td class="TableData"  >
    	<select name="PLAN_STATUS" class="BigSelect">
    			<option></option>
          <option value="0" <? if($STAFF_SEX=="0") echo "selected";?>><?=_("������")?></option>
          <option value="1" <? if($STAFF_SEX=="1") echo "selected";?>><?=_("����׼")?></option>
          <option value="2" <? if($STAFF_SEX=="2") echo "selected";?>><?=_("δ��׼")?></option>
      </select>
      <td nowrap class="TableData"><?=_("�����ˣ�")?></td>
    	<td class="TableData" >
      <input type="text" name="APPROVE_PERSON_NAME" size="15" class="BigStatic" readonly value="">&nbsp;
        <input type="hidden" name="APPROVE_PERSON" value="<?=$APPROVE_PERSON?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','APPROVE_PERSON', 'APPROVE_PERSON_NAME')"><?=_("ѡ��")?></a>
      </td>
   </tr>
   <tr>
      <td nowrap class="TableData"><?=_("��Ƹ˵����")?></td>
      <td class="TableData">
      	<input type="text" name="RECRUIT_DIRECTION" class="BigInput">
      </td>
      <td nowrap class="TableData"><?=_("��Ƹ��ע��")?></td>
      <td class="TableData">
      	<input type="text" name="RECRUIT_REMARK" class="BigInput">
      </td>
   </tr> 
   <tr>
      <td nowrap class="TableData"> <?=_("��Ƹ��ʼ���ڣ�")?></td>
      <td class="TableData" colspan=3>
        <input type="text" id="start_time1" name="START_DATE1" size="10" maxlength="10" class="BigInput" value="<?=$START_DATE1?>" onClick="WdatePicker()"/>
        <?=_("��")?>
        <input type="text" name="END_DATE1" size="10" maxlength="10" class="BigInput" value="<?=$END_DATE1?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time1\')}'})"/>       
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("��Ƹ�������ڣ�")?></td>
      <td class="TableData" colspan=3>
        <input type="text" id="start_time2" name="START_DATE2" size="10" maxlength="10" class="BigInput" value="<?=$START_DATE2?>" onClick="WdatePicker()"/>
        <?=_("��")?>
        <input type="text" name="END_DATE2" size="10" maxlength="10" class="BigInput" value="<?=$END_DATE2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time2\')}'})"/>        
      </td>
    </tr>
		<tr align="center" class="TableControl">
			<td colspan="6" nowrap>
				<input type="submit" value="<?=_("��ѯ")?>" class="BigButton">&nbsp;&nbsp;
			</td>
 		</tr>          
</table>
</form>

</table>
</body>
</html>