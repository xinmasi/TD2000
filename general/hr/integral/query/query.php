<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("�û�����");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script Language="JavaScript">
function CheckForm(form_action)
{
   document.form1.action=form_action;
   document.form1.submit();
}

function show_item_type(item_type){
   if(item_type==3)
	   document.getElementById("item_type").style.display="";
   else
	   document.getElementById("item_type").style.display="none";
}
</script>
<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("������Ա��ѯ")?></span>
    </td>
  </tr>
</table>
<form action="search.php?ispirit_export=1" method="post" name="form1">
<table class="TableBlock" width="50%" align="center">
  	<tr>
      <td nowrap class="TableData" width="80"><?=_("�����ˣ�")?></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID" id="TO_ID" value="<?=$TO_ID?>">
        <textarea  name="TO_NAME" id="TO_NAME" rows="2" cols="30"  class="SmallStatic" wrap="yes" readonly><?=$TO_NAME?></textarea>
        <a href="#" class="orgAdd" onClick="SelectUser('27','2','TO_ID', 'TO_NAME')" title="<?=_("���Ҫ��ѯ�Ļ�����")?>"><?=_("���")?></a>
        <a href="#" class="orgClear" onClick="ClearUser('TO_ID', 'TO_NAME')" title="<?=_("���Ҫ��ѯ�Ļ�����")?>"><?=_("���")?></a>
    </tr>
   <tr>
    <td nowrap class="TableData"><?=_("�Ա�")?></td>
    <td nowrap class="TableData">
        <select name="SEX" class="BigSelect" >
        <option value=""></option>
        <option value="0"><?=_("��")?></option>
        <option value="1"><?=_("Ů")?></option>
        </select>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("���ţ�")?></td>
    <td nowrap class="TableData">
        <select name="DEPT_ID" class="BigSelect">
       	<option value=""></option>
        <option value=""><?=_("ȫ�岿��")?></option>
<?
      echo my_dept_tree(0,$DEPT_ID,1);
      if($DEPT_ID==0)
      {
?>
          <option value="0"><?=_("��ְ��Ա/�ⲿ��Ա")?></option>

<?
      }
?>
        </select>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("��ɫ��")?></td>
    <td nowrap class="TableData">
        <select name="USER_PRIV" class="BigSelect">
        <option value=""></option>
<?
      $query = "SELECT * from USER_PRIV order by PRIV_NO desc";
      $cursor= exequery(TD::conn(),$query);
      while($ROW=mysql_fetch_array($cursor))
      {
         $USER_PRIV1=$ROW["USER_PRIV"];
         $PRIV_NAME=$ROW["PRIV_NAME"];

?>
          <option value="<?=$USER_PRIV1?>"><?=$PRIV_NAME?></option>
<?
      }
?>
        </select>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("�������ͣ�")?></td>
    <td nowrap class="TableData">
        <select name="INTEGRAL_TYPE" class="BigSelect" onchange="show_item_type(this.value)">
        <option value=""><?=_("��ѡ���������")?></option>
        <option value="0"><?=_("δ�������")?></option>
        <option value="1"><?=_("OAʹ�û���")?></option>
        <option value="3"><?=_("�Զ������")?></option>
        </select>
    </td>
   </tr>
   <tr id="item_type" style="display:none;">
    <td nowrap class="TableData"><?=_("�Զ���������ͣ�")?></td>
    <td nowrap class="TableData">
    		<select name="ITEM_TYPE" >
    			<option value=""><?=_("��ѡ���Զ��������")?></option>
<?
$query="select * from HR_INTEGRAL_ITEM_TYPE where TYPE_FROM=3";
$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
	$TYPE_ID=$ROW["TYPE_ID"];
	$TYPE_NO=$ROW["TYPE_NO"];
	$TYPE_NAME=$ROW["TYPE_NAME"];
?>
		     <option value="<?=$TYPE_ID?>"><?=$TYPE_NAME?></option>
<?
}
?>
    		</select>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("ʱ�䣺")?></td>
    <td nowrap class="TableData">
           <input type="text" name="begin" size="12" maxlength="10" class="BigInput" value="<?=$begin?>" id="start_time" onClick="WdatePicker()" /> <?=_("��")?>&nbsp;
        <input type="text" name="end" size="12" maxlength="10" class="BigInput" value="<?=$end?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})" />
    </td>
   </tr>

   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
        <input type="button" value="<?=_("��ѯ")?>" class="BigButton" onclick="CheckForm('index1.php');" title="<?=_("��ѯ����")?>" name="button">&nbsp;&nbsp;
        <input type="button" value="<?=_("�����ּܷ�¼")?>" class="BigButton" onclick="CheckForm('export.php');" title="<?=_("��������")?>" name="button">
        <input type="button" value="<?=_("������ϸ��¼")?>" class="BigButton" onclick="CheckForm('export_all.php');" title="<?=_("������ϸ����")?>" name="button">
    </td>

</table>
</form>
</body>
</html>