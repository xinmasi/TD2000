<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("ѧϰ������Ϣ��ѯ");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"] ?>/bbs.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script>
function do_export()
{
  document.form1.action='export.php';
  document.form1.submit();
}
function do_search()
{
  document.form1.action='search.php';
  document.form1.submit();
}
</script>

<body class="bodycolor">

<table border="0" width="80%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("ѧϰ������Ϣ��ѯ")?></span></td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="#"  method="post" name="form1">
 <table class="TableBlock" width="450" align="center">
    <tr>
      <td nowrap class="TableData"><?=_("��λԱ����")?></td>
      <td class="TableData">
        <input type="text" name="STAFF_NAME1" size="15" class="BigStatic" readonly value="">&nbsp;
        <input type="hidden" name="STAFF_NAME" value="<?=$STAFF_NAME ?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','STAFF_NAME', 'STAFF_NAME1','1')"><?=_("ѡ��")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("��ѧרҵ��")?></td>
      <td class="TableData">
        <input type="text" name="MAJOR" size="34" maxlength="200" class="BigInput" value="<?=$MAJOR?>">
      </td>
    </tr>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("����ѧ����")?></td>
      <td class="TableData" >
        <!--<input type="text" name="ACADEMY_DEGREE" size="34" maxlength="200" class="BigInput" value="<?=$ACADEMY_DEGREE?>">-->
    	<select name="ACADEMY_DEGREE" class="BigSelect" title="<?=_("��ְ״̬���ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
        	<option value="" <? if($ACADEMY_DEGREE=="") echo "selected";?>></option>
        	<?=hrms_code_list("STAFF_HIGHEST_SCHOOL",$ACADEMY_DEGREE);?>
      </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("����ԺУ��")?></td>
      <td class="TableData" >
        <input type="text" name="SCHOOL" size="34" maxlength="200" class="BigInput" value="<?=$SCHOOL?> ">
      </td>
    </tr>
     <tr>
      <td nowrap class="TableData"><?=_("����֤�飺")?></td>
      <td class="TableData">
        <input type="text" name="CERTIFICATES" size="34" maxlength="200" class="BigInput" value="<?=$CERTIFICATES?>">
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="button" value="<?=_("��ѯ")?>" class="BigButton" onclick="do_search()">&nbsp;&nbsp;
        <input type="button" value="<?=_("����")?>" class="BigButton" onclick="do_export()">&nbsp;&nbsp;
      </td>
    </tr>
 </table>
</form>


</body>
</html>