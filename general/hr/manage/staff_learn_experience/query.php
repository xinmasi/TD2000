<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("学习经历信息查询");
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
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("学习经历信息查询")?></span></td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="#"  method="post" name="form1">
 <table class="TableBlock" width="450" align="center">
    <tr>
      <td nowrap class="TableData"><?=_("单位员工：")?></td>
      <td class="TableData">
        <input type="text" name="STAFF_NAME1" size="15" class="BigStatic" readonly value="">&nbsp;
        <input type="hidden" name="STAFF_NAME" value="<?=$STAFF_NAME ?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','STAFF_NAME', 'STAFF_NAME1','1')"><?=_("选择")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("所学专业：")?></td>
      <td class="TableData">
        <input type="text" name="MAJOR" size="34" maxlength="200" class="BigInput" value="<?=$MAJOR?>">
      </td>
    </tr>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("所获学历：")?></td>
      <td class="TableData" >
        <!--<input type="text" name="ACADEMY_DEGREE" size="34" maxlength="200" class="BigInput" value="<?=$ACADEMY_DEGREE?>">-->
    	<select name="ACADEMY_DEGREE" class="BigSelect" title="<?=_("在职状态可在“人力资源设置”->“HRMS代码设置”模块设置。")?>">
        	<option value="" <? if($ACADEMY_DEGREE=="") echo "selected";?>></option>
        	<?=hrms_code_list("STAFF_HIGHEST_SCHOOL",$ACADEMY_DEGREE);?>
      </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("所在院校：")?></td>
      <td class="TableData" >
        <input type="text" name="SCHOOL" size="34" maxlength="200" class="BigInput" value="<?=$SCHOOL?> ">
      </td>
    </tr>
     <tr>
      <td nowrap class="TableData"><?=_("所获证书：")?></td>
      <td class="TableData">
        <input type="text" name="CERTIFICATES" size="34" maxlength="200" class="BigInput" value="<?=$CERTIFICATES?>">
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="button" value="<?=_("查询")?>" class="BigButton" onclick="do_search()">&nbsp;&nbsp;
        <input type="button" value="<?=_("导出")?>" class="BigButton" onclick="do_export()">&nbsp;&nbsp;
      </td>
    </tr>
 </table>
</form>


</body>
</html>