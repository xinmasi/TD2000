<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("���������ѯ");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>

<body class="bodycolor">
<br>
<table class="TableTop" width="500" align="center">
  <form enctype="multipart/form-data" action="search.php"  method="post" name="form1">
    <tr>
	  <td class="left"></td>
      <td class="center"><img src="<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif"> <?=_("��ָ����ѯ������")?></td>
      <td class="right"></td>
    </tr>
	</table>
	<table width="500" align="center" class="TableBlock">
    <tr>
      <td nowrap class="TableData" width="80"> <?=_("״̬��")?></td>
      <td class="TableData">
        <select name="VU_STATUS" class="BigSelect">
          <option value=""></option>
          <option value="0"><?=_("����")?></option>
          <option value="1"><?=_("��׼")?></option>
          <option value="2"><?=_("ʹ����")?></option>
          <option value="4"><?=_("����")?></option>
          <option value="3"><?=_("δ׼")?></option>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="80"> <?=_("���ƺţ�")?></td>
      <td class="TableData">
        <select name="V_ID" class="BigSelect" size>1>
          <option value=""></option>
<?
$query = "SELECT * from VEHICLE order by V_NUM";
$cursor1= exequery(TD::conn(),$query);
while($ROW1=mysql_fetch_array($cursor1))
{
   $V_ID1=$ROW1["V_ID"];
   $V_NUM=$ROW1["V_NUM"];
?>
          <option value="<?=$V_ID1?>"><?=$V_NUM?></option>
<?
}
?>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="80"> <?=_("˾����")?></td>
      <td class="TableData"><input type="text" name="VU_DRIVER" size="11" maxlength="100" class="BigInput"></td>
    </tr>    
    <tr>
      <td nowrap class="TableData" width="80"> <?=_("�������ڣ�")?></td>
      <td class="TableData">
        <input type="text" name="VU_REQUEST_DATE_MIN" size="12" maxlength="10" class="BigInput" value="" 
onClick="WdatePicker()">
      <?=_("��")?>
        <input type="text" name="VU_REQUEST_DATE_MAX" size="12" maxlength="10" class="BigInput" value="" 
onClick="WdatePicker()">
       
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="80"> <?=_("�ó��ˣ�")?></td>
       <td class="TableData">
        <input type="hidden" name="VU_USER" value="<?if ($FLAG=="1") {if($TO_ID!=0)echo $TO_ID;else echo $_SESSION["LOGIN_USER_ID"];} else echo $VU_USER_ID?>"> 	
        <input type="text" name="VU_NAME" size="13"  value="<?if ($FLAG=="1"){ if($TO_ID!=0)echo $TO_NAME;else echo td_trim($OUT_NAME);}else echo $VU_USER?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('90','','VU_USER','VU_NAME','vehicle')"><?=_("ѡ��")?></a>
      </td>
    
     <!--<td class="TableData">
        <input type="text" name="VU_USER" size="20" maxlength="100" class="BigInput" value="">
      </td>
    </tr>-->
    <tr>
      <td nowrap class="TableData" width="80"> <?=_("�ó����ţ�")?></td>
      <td class="TableData">
        <input type="hidden" name="VU_DEPT" value="">
        <input type="text" name="VU_DEPT_FIELD_DESC" value="" class=BigStatic size=20 maxlength=100 readonly>
        <a href="javascript:;" class="orgAdd" onClick="SelectDeptSingle('','VU_DEPT','VU_DEPT_FIELD_DESC')"><?=_("ѡ��")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="80"> <?=_("��ʼ���ڣ�")?></td>
      <td class="TableData">
        <input type="text" name="VU_START_MIN" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker()">
        <?=_("��")?>
        <input type="text" name="VU_START_MAX" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker()">
       
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="80"> <?=_("�������ڣ�")?></td>
      <td class="TableData">
        <input type="text" name="VU_END_MIN" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker()">
      <?=_("��")?>
        <input type="text" name="VU_END_MAX" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker()">
     
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="80"> <?=_("�����ˣ�")?></td>
      <td class="TableData">
        <input type="text" name="TO_NAME" size="10" class="BigStatic" readonly>&nbsp;
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('90','','TO_ID', 'TO_NAME')"><?=_("ѡ��")?></a>
        <input type="hidden" name="TO_ID" value="">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="80"> <?=_("����Ա��")?></td>
      <td class="TableData">
        <select name="VU_OPERATOR" class="BigSelect">
          <option value=""></option>
<?
$query = "SELECT * from VEHICLE_OPERATOR where OPERATOR_ID=1";
$cursor1= exequery(TD::conn(),$query);
if($ROW1=mysql_fetch_array($cursor1))
{
   $OPERATOR_NAME=$ROW1["OPERATOR_NAME"];
   $query = "SELECT * from USER where USER_ID!='' and find_in_set(USER_ID,'$OPERATOR_NAME') order by USER_NO,USER_NAME";
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
      $USER_ID=$ROW["USER_ID"];
      $USER_NAME=$ROW["USER_NAME"];
?>
          <option value="<?=$USER_ID?>"><?=$USER_NAME?></option>
<?
   }
}
?>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="80"> <?=_("���ɣ�")?></td>
      <td class="TableData">
        <input type="text" name="VU_REASON" class="BigInput" size="30">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="80"> <?=_("��ע��")?></td>
      <td class="TableData">
        <input type="text" name="VU_REMARK" class="BigInput" size="30">
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="submit" value="<?=_("��ѯ")?>" class="BigButton" onclick="action='search.php';">&nbsp;&nbsp;
        <input type="submit" value="<?=_("����")?>" class="BigButton" onclick="action='export.php';">&nbsp;&nbsp;
        <input type="reset" value="<?=_("����")?>" class="BigButton">
      </td>
    </tr>
  </table>
</form>

</body>
</html>